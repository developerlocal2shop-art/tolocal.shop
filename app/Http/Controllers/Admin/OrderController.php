<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Mail\OrderStatusMail;
use App\Models\client\Order as ClientOrder;
use DB;
use App\Models\Order;
use App\Models\Order_status;
use App\Models\UserAddress;
use App\Models\VendorPayout;
use App\Services\PayPalPayoutService;
use App\Services\OrderRefundService;
use Illuminate\Support\Facades\Mail;
use App\Models\Country;
use App\Models\User;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use PDF;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        $default_commission_value = DB::table('frontend_setting')->select('commission_default_value')->first();
        $defaultCommissionValue = $default_commission_value->commission_default_value;
        //echo "<pre>"; print_r($defaultCommissionValue);die;
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'order';
            $data['columns'] = ['first_name', 'last_name', 'phone', 'email'];
            $order = Helper::masterSearch($data);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.order.index', ['order' => $order, 'data' => $data, 'query' => $query, 'defaultCommissionValue' => $defaultCommissionValue]);
        } else {
            $order = DB::table('order')
                ->select('order.*')
                ->selectSub(
                    DB::table('refund_statuses')
                        ->select('status')
                        ->whereColumn('refund_statuses.order_id', 'order.id')
                        ->orderByDesc('id')
                        ->limit(1),
                    'refund_request_status'
                )
                ->whereNull('order.deleted_at')
                ->orderBy('order.id', 'desc')
                ->paginate(20);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            
            return view('admin.order.index', ['order' => $order, 'data' => $data, 'defaultCommissionValue' => $defaultCommissionValue]);
        }
    }


    public function view($id = 0)
    {
        $orderData = Order::with('refund')->findOrFail($id);
        $shiping_address = DB::table('user_address')->select('*')->where('id', $orderData->shipping_address_id)->get();
        $billing_address = DB::table('user_address')->select('*')->where('id', $orderData->billing_address_id)->get();
        $orderStatus = Order_status::get();
        return view('admin.order.view', compact('orderData', 'orderStatus', 'shiping_address', 'billing_address'));
    }

    public function makepayment($id = 0)
    {
        try {
            return DB::transaction(function () use ($id) {
                $orderData = Order::lockForUpdate()->findOrFail($id);

                if ($orderData->paid_by_admin) {
                    return redirect()->route('admin.order.index')
                        ->with('msg', 'This order has already been paid.');
                }

                $existingPayout = VendorPayout::where('order_id', $orderData->id)
                    ->lockForUpdate()
                    ->first();
                if ($existingPayout && $existingPayout->status === 'success') {
                    $orderData->paid_by_admin = '1';
                    $orderData->payout_status = 'paid';
                    $orderData->save();

                    return redirect()->route('admin.order.index')
                        ->with('msg', 'This order has already been paid.');
                }
                if ($existingPayout && $existingPayout->status === 'pending') {
                    return redirect()->route('admin.order.index')
                        ->with('error', 'A PayPal payout for this order is already pending.');
                }

                $vendor = Helper::getUserByDirectoryId($orderData->directory_id);
                if (!$vendor || !filter_var($vendor->paypal_email, FILTER_VALIDATE_EMAIL)) {
                    return redirect()->route('admin.order.index')
                        ->with('error', 'The vendor does not have a valid PayPal email configured.');
                }

                $defaultCommission = (float) DB::table('frontend_setting')
                    ->value('commission_default_value');
                $commission = $vendor->commission !== null && $vendor->commission !== ''
                    ? (float) $vendor->commission
                    : $defaultCommission;
                $amount = round(
                    (float) $orderData->total_amount * (1 - ($commission / 100)),
                    2
                );

                if ($amount <= 0) {
                    return redirect()->route('admin.order.index')
                        ->with('error', 'The calculated PayPal payout amount must be greater than zero.');
                }

                $response = app(PayPalPayoutService::class)->payout(
                    $vendor->paypal_email,
                    $amount,
                    strtoupper($orderData->currency ?: config('paypal.currency', 'USD')),
                    $orderData->id
                );
                $payoutId = $response['batch_header']['payout_batch_id'] ?? null;

                if (!$payoutId) {
                    return redirect()->route('admin.order.index')
                        ->with('error', 'PayPal did not confirm the payout. The order remains unpaid.');
                }

                $payoutData = [
                    'vendor_id' => $vendor->directory_id,
                    'order_id' => $orderData->id,
                    'amount' => $amount,
                    'paypal_payout_id' => $payoutId,
                    'status' => 'success',
                    'response' => json_encode($response),
                ];
                if ($existingPayout) {
                    $existingPayout->update($payoutData);
                } else {
                    VendorPayout::create($payoutData);
                }

                $orderData->paid_by_admin = '1';
                $orderData->payout_status = 'paid';
                $orderData->save();

                return redirect()->route('admin.order.index')
                    ->with('msg', 'PayPal payout was submitted successfully.');
            });
        } catch (\Throwable $exception) {
            Log::error('Unable to submit PayPal order payout.', [
                'order_id' => $id,
                'exception' => $exception->getMessage(),
            ]);

            return redirect()->route('admin.order.index')
                ->with('error', 'Unable to submit the PayPal payout. Please check the application logs.');
        }

    }

    public function changeOrderStatus(Request $request, $id = null)
    {
        $countryCode = session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        $orderId = $id ?: $request->input('id');
        $orderData = ClientOrder::with('orderitem')->findOrFail($orderId);
        $maildata['ordstatus'] = Order_status::findOrFail($request->input('status'));
        $maildata['order'] = $orderData;

        if ((int) $maildata['ordstatus']->id === 7) {
            abort_unless(auth()->guard('webadmin')->user()->hasPermission('status'), 403);
            try {
                $refundResult = app(OrderRefundService::class)->refund(
                    $orderData->id,
                    $request->input('order_status_description')
                );
                if ($refundResult['already_refunded']) {
                    return response()->json(['error' => 'This order has already been refunded.'], 409);
                }

                $orderData = ClientOrder::with('orderitem')->findOrFail($orderData->id);
                $maildata['order'] = $orderData;
                $message = 'Payment successfully refunded.';
                if ($refundResult['payout_already_paid']) {
                    $message .= ' The vendor payout was already sent and requires manual reconciliation.';
                }
                try {
                    Mail::to($orderData->email)->send(new OrderStatusMail($maildata));
                    Mail::to(auth()->guard('webadmin')->user()->email)->send(new OrderStatusMail($maildata));
                    if (!empty($countryCodeData->phonecode)) {
                        LaraTwilio::notify('+' . $countryCodeData->phonecode . $orderData->phone, 'Your order has been refunded.');
                    } else {
                        LaraTwilio::notify('+91' . $orderData->phone, 'Your order has been refunded.');
                    }
                } catch (\Throwable $notificationException) {
                    Log::error('Refund completed but admin order notification failed.', [
                        'order_id' => $orderData->id,
                        'exception' => $notificationException->getMessage(),
                    ]);
                    $message .= ' The refund completed, but a notification could not be sent.';
                }
                return response()->json(['success' => $message]);
            } catch (\Throwable $exception) {
                Log::error('Unable to refund order.', [
                    'order_id' => $orderData->id,
                    'exception' => $exception->getMessage(),
                ]);
                return response()->json(['error' => $exception->getMessage()]);
            }

        }else{

            if($maildata['ordstatus']->id == 1){
                $msg = "Your Order is pending!!";
            }elseif($maildata['ordstatus']->id == 2){
                $msg = "Your Order is being packed!!";
            }elseif($maildata['ordstatus']->id == 3){
                $msg = "Your Order is being shipped!!";
            }elseif($maildata['ordstatus']->id == 4){
                $msg = "Your Order is Cancelled!!";
            }elseif($maildata['ordstatus']->id == 5){
                $msg = "Your Order is Delivered!!";
            }elseif($maildata['ordstatus']->id == 6){
                $msg = "Your Order is RTO!!";
            }


            $orderData->status = $request->status;
            $orderData->order_status_description = $request->order_status_description;
            $orderData->save();
            //user
            Mail::to($request->email)->send(new OrderStatusMail($maildata));
            //admin
            Mail::to(auth()->guard('webadmin')->user()->email)->send(new OrderStatusMail($maildata));
            if (!empty($countryCodeData->phonecode)) {
                $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . $request->phone, $msg);
            } else {
                $sendSms = LaraTwilio::notify('+' . '91' . $request->phone, $msg);
            }

            return response()->json(['success' => 'Order status changed successfully.']);

        }
        
    }

    public function destroy($id)
    {
        Order::find($id)->delete();
        return redirect('admin/orders')->with('msg', 'Order deleted successfully');
    }

    public function softDeletedOrders()
    {
        $softdeletedorder =  Order::onlyTrashed()->orderBy('id', 'desc')->paginate(20);
        return view('admin.order.softdeletedorders', ['softdeletedorder' => $softdeletedorder]);
        
    }

    public function viewTrashedOrder($id=0)
    {
        $orderData = Order::onlyTrashed()->where('id', $id)->first();
        $shiping_address = DB::table('user_address')->select('*')->where('id', $orderData->shipping_address_id)->get();
        $billing_address = DB::table('user_address')->select('*')->where('id', $orderData->billing_address_id)->get();
        $orderStatus = Order_status::get();
        return view('admin.order.trashedorderview', compact('orderData', 'orderStatus', 'shiping_address', 'billing_address'));
    }

    public function permanentlydeleteOrder(Request $request, $id)
    {
        Order::where('id', $id)->forceDelete();
        return redirect('admin/softdeleteorders')->with('msg', 'Order deleted successfully');
    }

    public function restoredeleteOrder(Request $request)
    {
        $ids = $request->ids;
        Order::whereIn('id', explode(",", $ids))->restore();
        return response()->json(['success'=>'Order restore successfully.']);
    
    }

    public function permanentlyBulkDeletedOrder(Request $request)
    {
        $ids = $request->ids;
        Order::whereIn('id', explode(",", $ids))->forceDelete();
        return response()->json(['success'=>'Order Deleted successfully.']);
    
    }

    public function pendingOrders()
    {
        $pendingorder =  Order::where('deleted_at', NULL)->where('status','1')->orderBy('id', 'desc')->paginate(20);
        return view('admin.order.allpendingorders', ['pendingorder' => $pendingorder]);
        
    }

    public function processingOrders()
    {
        $processingorder =  Order::where('deleted_at', NULL)->where('status','2')->orderBy('id', 'desc')->paginate(20);
        return view('admin.order.processingorders', ['processingorder' => $processingorder]);   
    }

    public function paymentCancelOrders()
    {
        $paymentcancelorder =  Order::where('deleted_at', NULL)->where('status','4')->orderBy('id', 'desc')->paginate(20);
        return view('admin.order.paymentcancelorders', ['paymentcancelorder' => $paymentcancelorder]);    
    }

    public function deliveredOrders()
    {
        $deliveredorder =  Order::where('deleted_at', NULL)->where('status','5')->orderBy('id', 'desc')->paginate(20);
        return view('admin.order.deliveredorders', ['deliveredorder' => $deliveredorder]);    
    }

    public function rtoOrders()
    {
        $rtoorder =  Order::where('deleted_at', NULL)->where('status','6')->orderBy('id', 'desc')->paginate(20);
        return view('admin.order.rtoorders', ['rtoorder' => $rtoorder]);    
    }
    public function refundedOrders()
    {
        $refundedorder =  Order::where('deleted_at', NULL)->where('status','7')->orderBy('id', 'desc')->paginate(20);
        return view('admin.order.refundedorders', ['refundedorder' => $refundedorder]);    
    }

    public function trashedOrderGeneratePDF($id = 0)
    {
        // $orderData = Order::findOrFail($id);
        $orderData = Order::onlyTrashed()->where('id', $id)->first();
        $shiping_address = DB::table('user_address')->select('*')->where('id', $orderData->shipping_address_id)->get();
        $billing_address = DB::table('user_address')->select('*')->where('id', $orderData->billing_address_id)->get();
        $pdf = PDF::loadView('account.company.order.pdfgenerate', compact('orderData','shiping_address', 'billing_address'));
        return $pdf->download($id.'.pdf');
    }

}
