<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Mail\OrderStatusMail;
use App\Models\client\Order as ClientOrder;
use DB;
use App\Models\Order;
use App\Models\PaymentTrasaction;
use App\Models\RefundStatus;
use App\Models\Order_status;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use PDF;
use App\Models\Country;
use App\Models\CustomerClubs;
use App\Models\User;
use App\Services\OrderRefundService;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
class CompanyOrderController extends Controller
{

    
    public function index(Request $request)
    {
        if(Helper::getFeatureUses('orders') == false){
            return abort(404);
        }
        if(Auth::user()->profile_status == 0){
            return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
        }else{
            if ($request->input('search')) {
                $searchDataNew        = explode('-', $request->input('searchtext'));
                   $searchQuery = $request->input('searchtext');
                if($searchDataNew[0]!="ORDID"){
                   $query = $request->all();
                   
                   $data['searchQuery'] = $request->input('searchtext');
                   $data['table_name'] = 'order';
                   $data['columns'] = ['first_name', 'last_name', 'phone', 'email'];
                   $order = Helper::companymasterSearch($data);
                   $data = isset($searchQuery) ? $request->input('searchtext') : "";
                   return view('account.company.order.index', ['order' => $order, 'data' => $data, 'query' => $query]);
               }else{
                   $order = Order::where('directory_id', Auth::user()->directory_id)->where('id', $searchDataNew[1])->orderBy('id', 'desc')->paginate(20);
                   $data = isset($searchQuery) ? $request->input('searchtext') : "";
                   return view('account.company.order.index', ['order' => $order, 'data' => $data]);
               }
               
           } else {
               $order = Order::where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
               $data = isset($searchQuery) ? $request->input('searchtext') : "";
               return view('account.company.order.index', ['order' => $order, 'data' => $data]);
           }
        }
    }

    public function sendBulkMessage(Request $Request){	
		$user_id = explode(',',$Request->user_ids);	
       
        $dupes = array_unique($user_id, SORT_REGULAR);
        $message = str_replace(['<p>', '</p>'], '', $Request->message);
        $sendSms = '';
        foreach($dupes as $key => $val){
            $users = User::where('id', $val)->first();
            if (!empty($users->country_code) && !empty($users->phone) ) {
                $is_customer_club = CustomerClubs::where('customer_id', $val)->where('opt_out', 1)->exists();
                if($is_customer_club){
                    $sendSms = LaraTwilio::notify('+'.$users->country_code.''.$users->phone, $message);
                }
            }
        }
        echo json_encode(array('msg' => 'Message sent successfully.','status' =>'success')); exit;
        return $sendSms;
	}

    public function view($id = 0)
    {
        if(Auth::user()->profile_status == 0){
            return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
        }else{
        $orderData = Order::where('directory_id', Auth::user()->directory_id)->findOrFail($id);
        $shiping_address = DB::table('user_address')->select('*')->where('id', $orderData->shipping_address_id)->get();
        $billing_address = DB::table('user_address')->select('*')->where('id', $orderData->billing_address_id)->get();
        $orderStatus = Order_status::get();
        return view('account.company.order.view', compact('orderData','orderStatus','shiping_address', 'billing_address'));
        }
    }

    public function generatePDF($id = 0)
    {
        $orderData = Order::findOrFail($id);
        $shiping_address = DB::table('user_address')->select('*')->where('id', $orderData->shipping_address_id)->first();
        $billing_address = DB::table('user_address')->select('*')->where('id', $orderData->billing_address_id)->first();
        $pdf = PDF::loadView('account.company.order.pdfgenerate', compact('orderData','shiping_address', 'billing_address'));
        return $pdf->download($id.'.pdf');
    }

    public function refundRequest(Request $request)
    {
        abort_unless(Auth::check(), 401);

        $validator = \Validator::make($request->all(), [
            'order_id' => 'required|integer|min:1',
            'reason'  => 'required|string|max:150|in:performance and quality not adequate,product damaged but shipping box ok,Item arrived too late,Wrong item was sent,Item defective or doesn\'t work,others',
            'comment' => 'required|string|max:150',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        }

        $result = DB::transaction(function () use ($request) {
            $order = Order::where('user_id', Auth::id())
                ->lockForUpdate()
                ->find($request->input('order_id'));

            if (!$order) {
                return ['error' => 'Order not found.', 'code' => 404];
            }

            if ((int) $order->status !== 5) {
                return ['error' => 'A refund can only be requested for a delivered order.', 'code' => 422];
            }

            $payment = PaymentTrasaction::find($order->payment_id);
            $paymentMethod = strtolower((string) ($order->payment_method ?: optional($payment)->payment_method));
            if (!$payment || $paymentMethod !== 'paypal') {
                return ['error' => 'Refund requests are available only for PayPal orders.', 'code' => 422];
            }

            if (RefundStatus::where('order_id', $order->id)->exists()) {
                return ['error' => 'A refund request already exists for this order.', 'code' => 409];
            }

            $refundRequest = new RefundStatus;
            $refundRequest->order_id = $order->id;
            $refundRequest->reason = $request->input('reason');
            $refundRequest->comment = $request->input('comment');
            $refundRequest->status = 1;
            $refundRequest->save();

            return ['success' => true];
        });

        if (isset($result['error'])) {
            return response()->json([
                'status' => false,
                'message' => $result['error'],
            ], $result['code']);
        }

        return response()->json(['status' => true, 'message' => 'Refund request submitted successfully.']);
    }

    public function changeOrderStatus(Request $request, $id = null)
    {
        $countryCode= session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        $orderId = $id ?: $request->input('id');
        $orderData = ClientOrder::with('orderitem')->findOrFail($orderId);
        abort_unless((int) $orderData->directory_id === (int) Auth::user()->directory_id, 403);
        $maildata['ordstatus'] = Order_status::findOrFail($request->input('status'));
        $maildata['order'] = $orderData;

        if ((int) $maildata['ordstatus']->id === 7) {
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
                    Mail::to(Auth::user()->email)->send(new OrderStatusMail($maildata));
                    Mail::to(env('APP_EMAIL'))->send(new OrderStatusMail($maildata));
                    if (!empty($countryCodeData->phonecode)) {
                        LaraTwilio::notify('+' . $countryCodeData->phonecode . $orderData->phone, 'Your order has been refunded.');
                    } else {
                        LaraTwilio::notify('+91' . $orderData->phone, 'Your order has been refunded.');
                    }
                } catch (\Throwable $notificationException) {
                    \Illuminate\Support\Facades\Log::error('Refund completed but vendor order notification failed.', [
                        'order_id' => $orderData->id,
                        'exception' => $notificationException->getMessage(),
                    ]);
                    $message .= ' The refund completed, but a notification could not be sent.';
                }
                return response()->json(['success' => $message]);
            } catch (\Throwable $exception) {
                \Illuminate\Support\Facades\Log::error('Unable to refund vendor order.', [
                    'order_id' => $orderData->id,
                    'exception' => $exception->getMessage(),
                ]);
                return response()->json(['error' => $exception->getMessage()], 422);
            }
        } else {

                Mail::to($request->email)->send(new OrderStatusMail($maildata));
                Mail::to(Auth::user()->email)->send(new OrderStatusMail($maildata));
                Mail::to(env('APP_EMAIL'))->send(new OrderStatusMail($maildata));

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

                if(!empty($countryCodeData->phonecode)){
                    $sendSms = LaraTwilio::notify('+'.$countryCodeData->phonecode.$request->phone, $msg);
                }else{
                    $sendSms = LaraTwilio::notify('+'.'91'.$request->phone, $msg);
                }
                return response()->json(['success' => 'Order status changed successfully.']);

        }
    }

}
