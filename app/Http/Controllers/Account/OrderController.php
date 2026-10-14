<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use DB;
use App\Models\Order;
use App\Models\PaymentTrasaction;
use Hash;
use App\Helpers\Helper;
use Auth;
use App\Models\UserAddress;

class OrderController extends Controller
{
    public function index(){
        $data = array();
        if (Auth::check()){
            $user_id = Auth::user()->id;
            $orderData  = Order::select('*')->where('user_id', $user_id)->orderBy('created_at','DESC')->paginate(10); 
            return view('account.order', ['orderData' => $orderData]);
        }else{
            return redirect()->route('pages');
        }    
    }   

    public function orderDeatil($id){
        $orderData = Order::with('refund')
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        $shiping_address = DB::table('user_address')->select('*')->where('id', $orderData->shipping_address_id)->first();
        $billing_address = DB::table('user_address')->select('*')->where('id', $orderData->billing_address_id)->first();
        $payment = PaymentTrasaction::find($orderData->payment_id);
        $isPaypalOrder = strtolower((string) ($orderData->payment_method ?: optional($payment)->payment_method)) === 'paypal';
        //dd($shiping_address);
        return view('account.orderview', compact('orderData','shiping_address', 'billing_address', 'isPaypalOrder'));
    }

}
