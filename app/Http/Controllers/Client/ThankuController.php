<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class ThankuController extends Controller
{
    public function index(Request $request){

        return view('client.thankunote');
    }

    public function paypalcancel(Request $request){
        return view('client.paypalcancel');
    }

    
}
