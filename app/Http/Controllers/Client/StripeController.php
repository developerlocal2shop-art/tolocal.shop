<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Stripe;
use App\Helpers\Helper;

class StripeController extends Controller
{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function stripe()
    {
        $defaultCurrency = Helper::defaultCurrencyData();
        $total = 0;
        $cartItems = session()->get('cart', []);
        foreach($cartItems as $id => $details){
            $total += $details['price'] * $details['quantity'];
        }
        return view('client.stripe', compact('total'));
    }

    // public function stripePost(Request $request)
    // {
    //     $defaultCurrency = Helper::defaultCurrencyData();
    //     $total = 0;
    //     $cartItems = session()->get('cart', []);
    //     foreach($cartItems as $id => $details){
    //         $total += $details['price'] * $details['quantity'];
    //     }
    //     Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
    //     Stripe\Charge::create ([
    //             "amount" => $total,
    //             "currency" => $defaultCurrency[0]->code,
    //             "source" => $request->stripeToken,
    //             "description" => "Test payment"
    //     ]);

    //     Session::flash('success', 'Payment successful!');
    //     session()->forget('cart');
    //     return redirect('thank-you');
    // }

}
