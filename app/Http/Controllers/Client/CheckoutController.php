<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use App\Helpers\Helper;
use App\Jobs\VendorPayoutJob;
use App\Mail\Order as MailOrder;
use App\Models\Cart;
use App\Models\client\UserAddress;
use App\Models\client\Order;
use App\Models\client\OrderItem;
use App\Models\PaymentTrasaction;
use App\Models\PlanSubscription;
use App\Models\PlanSubscriptionUsage;
use App\Models\User;
use App\Models\Country;
use App\Models\ProductAttributesVariation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Stripe\Stripe;
use \Stripe\Token;
use Stripe\Customer;
use Stripe\Charge;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use App\Models\State;
use App\Models\City;
use App\Models\CompetitionWinners;
use App\Models\CustomerClubs;
use App\Models\Diectory;
use App\Models\Frontendsetting;
use App\Models\IsUsedCouponCode;
use App\Models\Plans;
use Carbon\Carbon;
use Directory;
use App\Services\PayPalService;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class CheckoutController extends Controller
{

    public function index()
    {

        if(auth()->guard('web')->check()==0){
            return redirect('/login');
        }
        $userIP = request()->ip();
        Helper::countwebsite();
        $cartItems              = session()->get('cart', []);
        $couponData             = session()->get('coupon', []);
        $countries              = Helper::getCountryAllData(); /* get country */
        $user_id                = Auth::user()->id??"";
        if($user_id !=""){
            $addressData            = UserAddress::select('*')->where('user_id', $user_id)->groupBy('email', 'phone', 'address1', 'address2')->orderBy('id', 'DESC')->get()->toArray();
            $defaultaddressData     = UserAddress::select('*')->where('user_id', $user_id)->where('default_address', 'yes')->groupBy('email', 'phone', 'address1', 'address2')->orderBy('id', 'DESC')->get()->first();
            $countryPhone           = Country::where('id', $defaultaddressData['country']??'')->first();
            $stateSelected          = State::select('*')->where('country_id', $defaultaddressData['country']??'')->get()->toArray();
            $citySelected           = City::select('*')->where('state_id', $defaultaddressData['state']??'')->get()->toArray();
            return view('client.checkout', compact('cartItems', 'userIP','countries', 'couponData', 'addressData', 'defaultaddressData', 'countryPhone', 'stateSelected', 'citySelected'));
        }

        return view('client.checkout', compact('cartItems', 'userIP', 'countries', 'couponData'));
    }


    public function thankyou(Request $request)
    {

        $checkoutData = session()->get('checkout_data');
        $charge = session()->get('paypalOrder');
        $default_commission_value = Frontendsetting::select('commission_default_value')->first();

        if (!$checkoutData) {
            return redirect()->route('checkout')
                ->with('error', 'Session expired. Please try again.');
        }

        $countryCode = session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        $countryPhone = Country::where('id', $checkoutData['shipping_country'])->first();
        $cartItems = session()->get('cart', []);
        $couponData = session()->get('coupon', []);
        $sameBillingAddress = ($checkoutData['samebillingaddress_pay'] == 'yes' ? "yes" : "no");
        $taxValue = Helper::taxValueData();

        $defaultCurrency = Helper::defaultCurrencyData();
        if (session()->has('currency_code')) {
            $currencySymbol = session()->get('currency_code')['currsymbol'];
            $currencycode = session()->get('currency_code')['currcode'];
        } else {
            $currencySymbol = $defaultCurrency[0]->symbol;
            $currencycode = $defaultCurrency[0]->code;
        }

        $userAddress_inst = new UserAddress;
        $userAddress_inst->user_id              = auth()->guard('web')->user()->id;
        $userAddress_inst->sameBillingAddress   = $sameBillingAddress;
        $userAddress_inst->first_name           = $checkoutData['shipping_first_name'];
        $userAddress_inst->last_name            = $checkoutData['shipping_last_name'];
        $userAddress_inst->phone                = $checkoutData['shipping_phone_contact'];
        $userAddress_inst->email                = $checkoutData['shipping_email'];
        $userAddress_inst->address1             = $checkoutData['shipping_address1'];
        $userAddress_inst->address2             = $checkoutData['shipping_address2'];
        $userAddress_inst->city_id              = $checkoutData['shipping_city'];
        $userAddress_inst->city                 = $checkoutData['shipping_cityname'];
        $userAddress_inst->state                = $checkoutData['shipping_state'];
        $userAddress_inst->country              = $checkoutData['shipping_country'];
        $userAddress_inst->zipcode              = $checkoutData['shipping_zip_code'];
        $userAddress_inst->type                 = 'shipping';
        $userAddress_inst->save();
        $useraddress = $userAddress_inst->id;
        if ($sameBillingAddress == "no") {
            $userBillingAddress_inst = new UserAddress;
            $userBillingAddress_inst->user_id              = auth()->guard('web')->user()->id;
            $userBillingAddress_inst->sameBillingAddress   = $sameBillingAddress;
            $userBillingAddress_inst->first_name           = $checkoutData['billing_first_name'];
            $userBillingAddress_inst->last_name            = $checkoutData['billing_last_name'];
            $userBillingAddress_inst->phone                = $checkoutData['billing_phone_contact'];
            $userBillingAddress_inst->email                = $checkoutData['billing_email'];
            $userBillingAddress_inst->address1             = $checkoutData['billing_address1'];
            $userBillingAddress_inst->address2             = $checkoutData['billing_address2'];
            $userBillingAddress_inst->city_id              = $checkoutData['billing_city'];
            $userBillingAddress_inst->state                = $checkoutData['billing_state'];
            $userBillingAddress_inst->country              = $checkoutData['billing_country'];
            $userBillingAddress_inst->zipcode              = $checkoutData['billing_zip_code'];
            $userBillingAddress_inst->type                 = 'billing';
            $userBillingAddress_inst->save();
            $userbillingaddress = $userBillingAddress_inst->id;
        }


        //try {

            $commissionPercent = $default_commission_value->commission_default_value;
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $capturePaymentOrder = $provider->capturePaymentOrder($request->token);

            if (!isset($capturePaymentOrder['status']) || $capturePaymentOrder['status'] !== 'COMPLETED') {
                return redirect()->route('checkout')->with('error', 'Payment not completed.');
            }

            if (isset($capturePaymentOrder['status']) && $capturePaymentOrder['status'] == 'COMPLETED') {

                $payment_inst = new PaymentTrasaction;
                $payment_inst->payment_method           = 'paypal';
                $payment_inst->transaction_id           = $capturePaymentOrder['id'];
                $payment_inst->api_response             = json_encode($capturePaymentOrder);
                $payment_inst->total_amount             = $checkoutData['total_amount'];
                $payment_inst->currency                 = $currencycode;
                $payment_inst->payment_date             = date('y-m-d');
                $payment_inst->save();
                    $payment_id = $payment_inst->id;

                $couponcode = isset($couponData['couponcode']) ? $couponData['couponcode'] : "";
                $coupontype = isset($couponData['coupontype']) ? $couponData['coupontype'] : "";
                $code_type = isset($couponData['code_type']) ? $couponData['code_type'] : "";
                $itemTotal = isset($couponData['total']) ? $couponData['total'] : $checkoutData['total_amount'];
                $discounttotal = isset($couponData['totaldiscount']) ? $couponData['totaldiscount'] : "";

                foreach ($cartItems as $productId => $value) {

                    $product_dir = Product::find($productId);
                    $subtotl = $value['price'] * $value['quantity'];
                    $free_product_id = json_decode($value['free_product_data'])->id ?? 0;
                    $getFreeProductDirectoryId = Helper::getFreeProductDirectoryId($free_product_id);
                    $subtotallprice = (float)$value['price'] * $value['quantity'];
                    $minimum_amount = $getFreeProductDirectoryId[0]->minimum_amount ?? 0;
                    if ($subtotallprice > $minimum_amount) {
                        $free_pro_id = json_decode($value['free_product_data'])->id ?? 0;
                    } else {
                        $free_pro_id = 0;
                    }
                    $priceAftertax = ($subtotl) * ($taxValue[0]->taxval / 100);

                    $order_inst = new Order;
                    $order_inst->user_id                    = auth()->user()->id;
                    $order_inst->shipping_address_id        = $useraddress;
                    $order_inst->billing_address_id         = $sameBillingAddress == "no" ? $userbillingaddress : $useraddress;
                    $order_inst->first_name                 = $checkoutData['shipping_first_name'];
                    $order_inst->last_name                  = $checkoutData['shipping_last_name'];
                    $order_inst->phone                      = $checkoutData['shipping_phone_contact'];
                    $order_inst->email                      = $checkoutData['shipping_email'];
                    $order_inst->shipping_rate              = $checkoutData['shipping_rate_value'];
                    $order_inst->expected_delivery_date     = $checkoutData['expected_delivery_date'];
                    $order_inst->vendor_amount              = 0;
                    $order_inst->platform_commission        = $commissionPercent;
                    $order_inst->paypal_capture_id          = $capturePaymentOrder['id'];
                    $order_inst->payment_id                 = isset($payment_id) ? $payment_id : 0;

                    if ($couponcode != "") {
                        if (in_array($productId, $couponData['productId'])) {
                            if ($coupontype == "percentagediscount") {
                                $order_inst->total_amount   = isset($couponData['couponvalue']) ? ($value['price'] * $value['quantity']) - (($value['price'] * $value['quantity']) * ($couponData['couponvalue'] / 100)) : 0;
                            }
                            if ($coupontype == "fixeddiscount") {
                                $order_inst->total_amount   = isset($couponData['couponvalue']) ? ($value['price'] * $value['quantity']) - ($couponData['couponvalue']) : 0;
                            }
                        } else {
                            $order_inst->total_amount   = $value['price'] * $value['quantity'];
                        }
                    } else {
                        $order_inst->total_amount    = $value['price'] * $value['quantity'];
                    }
                    $order_inst->status                     = 1;
                    $order_inst->currency                   = $currencycode;
                    $order_inst->currency_symbol            = $currencySymbol;
                    $order_inst->total_tax                  = $priceAftertax;
                    $order_inst->directory_id               = $product_dir->directory_id;

                    $order_inst->payment_method             = $checkoutData['paypal_payment'];
                    $order_inst->platform_commission        = $commissionPercent;
                    $order_inst->flat_rate                  = $checkoutData['flat_rate_value'];

                    $order_inst->save();
                    $order_id = $order_inst->id;

                    $productdir = $product_dir->directory_id ?? '0';
                    $usere = User::where('directory_id', $productdir)->first();
                    $vendor_stripe_account_id = $usere->stripe_connect;

                    if (!empty($value['attr_name'])) {
                        $attr_id = implode(',', $value['attr_id']);
                        $attr_name = implode(',', $value['attr_name']);
                        $attr_value = implode(',', $value['attr_value']);
                    } else {
                        $attr_id = '';
                        $attr_name = '';
                        $attr_value = '';
                    }
                    if ($value['offer_dicount_type']) {
                        if ($value['offer_dicount_type'] == 'fixed') {
                            $offerval = $value['offer'];
                            $offertype = 'fixed';
                        } else {
                            $offerval =  $value['offer'];
                            $offertype = 'percentage';
                        }
                    } else {
                        $offerval =  '';
                        $offertype = '';
                    }
                    if ($value['shopping_dicount_type']) {
                        if ($value['shopping_dicount_type'] == 'percentage') {
                            $shopping_offerval = $value['shoppingcard'];
                            $shopping_offertype = 'percentage';
                        } else {
                            $shopping_offerval =  $value['shoppingcard'];
                            $shopping_offertype = 'fixed';
                        }
                    } else {
                        $shopping_offerval =  '';
                        $shopping_offertype = '';
                    }

                    $orderItem_inst = new OrderItem;
                    $orderItem_inst->order_id           = $order_id;
                    $orderItem_inst->product_id         = $productId;
                    $orderItem_inst->product_name       = $value['name'];
                    $orderItem_inst->product_qty        = $value['quantity'];
                    $orderItem_inst->product_price      = $value['price'];
                    if ($couponcode != "") {
                        if (in_array($productId, $couponData['productId'])) {
                            if ($coupontype == "percentagediscount") {
                                $orderItem_inst->total   = isset($couponData['couponvalue']) ? ($value['price'] * $value['quantity']) - (($value['price'] * $value['quantity']) * ($couponData['couponvalue'] / 100)) : 0;
                            }
                            if ($coupontype == "fixeddiscount") {
                                $orderItem_inst->total   = isset($couponData['couponvalue']) ? ($value['price'] * $value['quantity']) - ($couponData['couponvalue']) : 0;
                            }
                        } else {
                            $orderItem_inst->total   = $value['price'] * $value['quantity'];
                        }
                    } else {
                        $orderItem_inst->total   = $value['price'] * $value['quantity'];
                    }
                    $orderItem_inst->offer              = $offerval;
                    $orderItem_inst->offer_type         = $offertype;
                    $orderItem_inst->discount           = $discounttotal;
                    $orderItem_inst->free_product_id    = $free_pro_id;
                    $orderItem_inst->shoppingcard       = $shopping_offerval;
                    $orderItem_inst->shoppingcard_type  = $shopping_offertype;
                    $orderItem_inst->tax                = $discounttotal;
                    $orderItem_inst->attr_value         = $attr_value;
                    $orderItem_inst->attr_name          = $attr_name;
                    $orderItem_inst->attr_id            = $attr_id;
                    $orderItem_inst->save();

                    if (!empty($value['v_stock'])) {
                        ProductAttributesVariation::where('product_id', $productId)->where('attribute_value', $value['attribute_value'])->update(['v_stock' => DB::raw('v_stock-' . $value['quantity'])]);
                    }
                    $product = Product::find($productId);
                    $product->stock -= $value['quantity'];
                    $product->save();
                    $ordersv = Order::with('orderitem')->where('payment_id', $payment_id)->get();
                    $maildata['orders'] = $ordersv;
                    $directory_id = $value['directory_id'] ?? '0';
                    $usere = User::where('directory_id', $directory_id)->first();
                    $vendorPhone = $usere->phone;

                    if($checkoutData['shipping_country']){
                        $c_country = $checkoutData['shipping_country'];
                    }else{
                        $c_country = $checkoutData['billing_country'];
                    }
                    if($checkoutData['shipping_state']){
                        $c_state = $checkoutData['shipping_state'];
                    }else{
                        $c_state = $checkoutData['billing_state'];
                    }
                    if($checkoutData['shipping_city']){
                        $c_city = $checkoutData['shipping_city'];
                    }else{
                        $c_city = $checkoutData['billing_city'];
                    }
                    if($checkoutData['shipping_zip_code']){
                        $c_zip_code = $checkoutData['shipping_zip_code'];
                    }else{
                        $c_zip_code = $checkoutData['billing_zip_code'];
                    }

                    $customer_club                    = new CustomerClubs();
                    $customer_club->fname           = auth()->guard('web')->user()->name;
                    $customer_club->lname           = auth()->guard('web')->user()->last_name;
                    $customer_club->phone           = auth()->guard('web')->user()->email;
                    $customer_club->email           = auth()->guard('web')->user()->phone;
                    $customer_club->zipcode         = $c_zip_code;
                    $customer_club->country         = $c_country;
                    $customer_club->state           = $c_state;
                    $customer_club->city            = $c_city;
                    $customer_club->message         = "Joined customer club by product order.";
                    $customer_club->seller_id       = $directory_id;
                    $customer_club->customer_id     = auth()->guard('web')->user()->id;
                    $customer_club->joined_at       = Carbon::now();
                    $res = $customer_club->save();

                    if($code_type == 'couponcode'){
                        $IsUsedCouponCode                    = new IsUsedCouponCode();
                        $IsUsedCouponCode->coupon_code       = $couponcode;
                        $IsUsedCouponCode->user_id           = auth()->guard('web')->user()->id;
                        $IsUsedCouponCode->save();
                    }elseif($code_type == 'prizecode'){
                        CompetitionWinners::where('voucher_code', $couponcode)->where('customer_id', auth()->guard('web')->user()->id)->update(['status' => '0']);
                    }

                    //product vendor
                    Mail::to($usere->email)->send(new MailOrder($maildata));
                    if ($vendorPhone != null) {
                        $msg = "Order Placed Successfully. Order id is: " . $order_id;
                        if (!empty($countryCodeData->phonecode)) {
                            //$sendSms = LaraTwilio::notify('+' . $usere->country_code??$countryCodeData->phonecode . $vendorPhone, $msg);
                        } else {
                            //$sendSms = LaraTwilio::notify('+' . $usere->country_code??$countryPhone->phonecode . $vendorPhone, $msg);
                        }
                    }
                }



                $orders = Order::with('orderitem')->where('payment_id', $payment_id)->get();
                ///dd($orders->count(), $payment_id);
                foreach ($orders as $order) {
                    $commissionAmount = round(
                        ($order->total_amount * $order->platform_commission) / 100, 2
                    );
                    $vendorAmount = round(
                        $order->total_amount - $commissionAmount, 2
                    );
                    $order->update([
                        'payment_status'      => 'paid',
                        'payout_status'       => 'pending',
                        'platform_commission' => $commissionAmount,
                        'vendor_amount'       => $vendorAmount,
                    ]);
                    if($usere->email !== env('PLATFORM_PAYPAL_EMAIL')){
                    VendorPayoutJob::dispatch($order->id);
                    }
                }


                $maildata['orders'] = $orders;
                //Admin
                Mail::to(env('APP_EMAIL'))->send(new MailOrder($maildata));
                //User
                Mail::to(auth()->user()->email)->send(new MailOrder($maildata));
                $msg = "Your order was successfully placed.Your Order id is: " . $order_id;
                if (!empty($countryCodeData->phonecode)) {
                    //$sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . auth()->user()->phone, $msg);
                } else {
                    //$sendSms = LaraTwilio::notify('+' . $countryPhone->phonecode . auth()->user()->phone, $msg);
                }

                session()->forget('checkout_data');
                session()->forget('cart');
                session()->forget('coupon');
                session()->forget('paypalOrder');

                return view('client.thankunote');

            }else{
                return redirect()->route('checkout')->with('error', 'Payment not completed. Please try again.');
            }

        // } catch (\Exception $ex) {
        //     session()->flash('stripe_error', $ex->getMessage());
        //     return redirect('checkout');
        // }
    }


    public function placeOrder(Request $request)
    {

        session()->put('checkout_data', $request->all());
        try {
            $user       = auth()->user();
            $cartItems  = session()->get('cart', []);
            $defaultCurrency = Helper::defaultCurrencyData();
            if (session()->has('currency_code')) {
                $currencySymbol = session()->get('currency_code')['currsymbol'];
                $currencycode = session()->get('currency_code')['currcode'];
            } else {
                $currencySymbol = $defaultCurrency[0]->symbol;
                $currencycode = $defaultCurrency[0]->code;
            }
            if (empty($cartItems)) {
                return redirect()->route('checkout')->with('error', 'Cart is empty');
            }

            //CREATE PAYPAL ORDER
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $paypalOrder = $provider->createOrder([
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => $currencycode,
                        'value' => number_format($request->total_amount, 2, '.', '')
                    ]
                ]],
                'application_context' => [
                    'return_url' => route('thankyou'),
                    'cancel_url' => route('paypal.cancel')
                ]
            ]);
            if (!isset($paypalOrder['id'])) {
                throw new \Exception('PayPal order creation failed');
            }

            session()->put('paypalOrder', $paypalOrder);

            //REDIRECT TO PAYPAL
            foreach ($paypalOrder['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }
            throw new \Exception('PayPal approval link not found');
        } catch (\Exception $e) {
            //DB::rollBack();
            return redirect()->route('checkout')->with('error', $e->getMessage());
        }
    }


    public function placePackage(Request $request, PayPalService $paypal)
    {

        //try {

            $defaultCurrency = Helper::defaultCurrencyData();
            if (session()->has('currency_code')) {
                $currency = session()->get('currency_code')['currcode'];
            } else {
                $currency = $defaultCurrency[0]->code;
            }
            $provider = $paypal->provider();
            // First payment
            $firstAmount = round($request->total_amount2, 2);
            // Recurring payment
            $recurringAmount = round($request->second_total_amount, 2);

            if ($request->has('autopayment')) {

                $product = $provider->createProduct([
                    'name'        => $request->package_name,
                    'description' => 'Subscription Package',
                    'type'        => 'SERVICE',
                    'category'    => 'SOFTWARE',
                ]);

                $firstSubtotal = round($firstAmount / 1.25, 2);
                $firstTax = round($firstSubtotal * 0.25, 2);

                $planDescription =
                    "Subtotal:  {$currency} " . number_format($firstSubtotal, 2) . "\n" .
                    "Tax (25%): {$currency} " . number_format($firstTax, 2) . "\n" .
                    "Total:     {$currency} " . number_format($firstAmount, 2);

                /** Create Plan **/
                $plan = $provider->createPlan([
                    'product_id' => $product['id'],
                    'name' => $request->package_name,
                    'description' => $planDescription,
                    'billing_cycles' => [

                    /**FIRST PAYMENT */
                        [
                            'frequency' => [
                                'interval_unit'  => strtoupper($request->invoice_interval), // MONTH
                                'interval_count' => 3
                            ],
                            'tenure_type' => 'TRIAL',
                            'sequence' => 1,
                            'total_cycles' => 1,
                            'pricing_scheme' => [
                                'fixed_price' => [
                                    'value' => number_format($firstAmount, 2, '.', ''),
                                    'currency_code' => strtoupper($currency),
                                ]
                            ]
                        ],

                    /*** RECURRING PAYMENT*/
                        [
                            'frequency' => [
                                'interval_unit'  => strtoupper($request->invoice_interval), // MONTH
                                'interval_count' => 3
                            ],
                            'tenure_type' => 'REGULAR',
                            'sequence' => 2,
                            // Infinite subscription
                            'total_cycles' => 0,
                            'pricing_scheme' => [
                                'fixed_price' => [
                                    'value' => number_format($recurringAmount, 2, '.', ''),
                                    'currency_code' => strtoupper($currency),
                                ]
                            ]
                        ],
                    ],
                    'payment_preferences' => [
                        'auto_bill_outstanding' => true,
                        'payment_failure_threshold' => 3,
                    ]
                ]);

                /*** Create Subscription*/
                $subscription = $provider->createSubscription([
                    'plan_id' => $plan['id'],
                    'application_context' => [
                        'brand_name' => config('app.name'),
                        'return_url' => route('paypal.subscription.success'),
                        'cancel_url' => route('paypal.subscription.cancel'),
                    ],
                    'custom_id' => auth()->user()->directory_id,
                ]);

                session()->put('subscription_data', $request->all());

                foreach ($subscription['links'] as $link) {
                    if ($link['rel'] == 'approve') {
                        return redirect($link['href']);
                    }
                }
                return back()->with('stripe_error', 'Approval URL not found.');
            }

        // } catch (\Exception $e) {
        //     return back()->with('stripe_error', $e->getMessage());
        // }

    }


    //Stripe recurring payment
    // public function placePackage_stripe(Request $request)
    // {

    //     $countryCode = session()->get('countrycode');
    //     $countryCodeData = session()->get('countrynamecode');
    //     $defaultCurrency = Helper::defaultCurrencyData();

    //     if (session()->has('currency_code')) {
    //         $currencySymbol = session()->get('currency_code')['currsymbol'];
    //         $currencycode = session()->get('currency_code')['currcode'];
    //     } else {
    //         $currencySymbol = $defaultCurrency[0]->symbol;
    //         $currencycode = $defaultCurrency[0]->code;
    //     }
    //     try{

    //     $amount = round($request->total_amount);
    //     $amount2 = round($request->total_amount2);

    //     $vendorAddressFromDirectory = DB::table('directory_location')->select('*')->where('directory_id',auth()->user()->directory_id)->first();

    //         Stripe::setApiKey(env('STRIPE_SECRET'));
	//         $stripe = new \Stripe\StripeClient([
	//               "api_key" => env('STRIPE_SECRET'),
	//               "stripe_version" => "2020-08-27"
	//             ]);

	//         $getCustomer = $stripe->customers->search([
	//           'query' => 'email:\''.auth()->user()->email.'\' AND metadata[\'id\']:\''.auth()->user()->id.'\'',
	//         ]);

	//         $customerExist = count($getCustomer);

	//     if($customerExist!=0){
    //         $customer_id = $getCustomer['data']['0']['id'];
	//     }else{
    //         $customer = \Stripe\Customer::create([
    //             'name'              => auth()->user()->name." ".auth()->user()->last_name,
    //             'address'           => [
    //                 'line1'         => $vendorAddressFromDirectory->address,
    //                 'postal_code'   => $vendorAddressFromDirectory->zipcode,
    //                 'city'          => $vendorAddressFromDirectory->city_id,
    //                 'state'         => $vendorAddressFromDirectory->state_id,
    //                 'country'       => $vendorAddressFromDirectory->country_id,
    //             ],
    //             'email'             => auth()->user()->email,
    //             'source'            => $request->stripeToken,
    //             'metadata' => [
    //                     'id' => auth()->user()->id
    //                 ]
    //         ]);
    //         $customer_id = $customer->id;
	//     }

    //     if(isset($request->autopayment)){

	//             $months         = $request->invoice_period;
	//             $today          = time();
	//             $cancelAt = strtotime("+$months months", $today);
	//             $getProduct = $stripe->products->search([
	//                 'query' => 'active:\'true\' AND metadata[\'name\']:\''.$request->package_name.'\'',
	//             ]);
	//             $productExist = count($getProduct);

	//             if($productExist!=0){
	//                 $product_id = $getProduct['data']['0']['id'];
	//             }else{
	//                 $product = $stripe->products->create([
	//                     'name' => $request->package_name,
	//                     'id'   => $request->package_id,
	//                     'metadata' => [
	//                         'name' => $request->package_name,
	//                         'last-date' => '30-7-2023'
	//                     ]
	//                 ]);
	//                 $product_id = $product->id;
	//             }
	//             $getPrice = $stripe->prices->search([
	//                 'query' => 'active:\'true\' AND product:\''.$request->package_id.'\'',
	//             ]);
	//             $priceExist = count($getPrice);
	//             if($priceExist!=0){
	//                 $price_id = $getPrice['data']['0']['id'];

	//             }else{
	//                 $price = $stripe->prices->create([
	//                     'unit_amount'   => $amount2,
	//                     'currency'      => 'SEK',
	//                     'recurring'     => ['interval' => 'month','interval_count' => $months],
	//                     'product'       => $product_id,
	//                 ]);
	//                 $price_id = $price->id;
	//             }

	//             $getSubscriptionByCustomerID = $stripe->subscriptions->search([
	// 			  'query' => 'status:\'active\' AND metadata[\'customerid\']:\''.$customer_id.'\'',
	// 			]);
	// 			$subscriptionByCustomeridExist = count($getSubscriptionByCustomerID);

	// 			if($subscriptionByCustomeridExist!=0){
	// 				$stripe->subscriptions->cancel(
	// 				  $getSubscriptionByCustomerID['data']['0']['id'],
	// 				  []
	// 				);
	//                 $subscription = $stripe->subscriptions->create([
	// 	                'customer' => $customer_id,
	// 	                'items' => [
	// 	                    ['price' => $price_id],
	// 	                ],
	// 	    	            'metadata' => [
	// 	                    'start_date' => $today,
	// 	                    'total_months' => $months,
	// 	                    'end_date' => $cancelAt,
	// 	                    'customerid' => $customer_id
	// 	                ],
	// 	                "cancel_at" => $cancelAt
	// 	            ]);
	//             }else{
	//                  //create subscription
	//                 $subscription = $stripe->subscriptions->create([
	// 	                'customer' => $customer_id,
	// 	                'items' => [
	// 	                    ['price' => $price_id],
	// 	                ],
	// 	                'metadata' => [
	// 	                    'start_date' => $today,
	// 	                    'total_months' => $months,
	// 	                    'end_date' => $cancelAt,
	// 	                    'customerid' => $customer_id
	// 	                ],
	// 	                "cancel_at" => $cancelAt
	// 	            ]);
	//             }


	//              if ($subscription['status'] == 'active') {

    //                 $updateAutopayment = Diectory::where('id', Auth::user()->directory_id)->update(['autopayment' => 1]);

    //                 $currentDate      = strtotime('today');
	// 	            $effectiveDate = date('Y-m-d h:i:s', strtotime("+" . $request->invoice_period . " " . $request->invoice_interval . " ", $currentDate));


	// 	            $payment_inst = new PaymentTrasaction;
	// 	            $payment_inst->payment_method           = 'card';
	// 	            $payment_inst->transaction_id           = $subscription['id'];
	// 	            $payment_inst->api_response             = json_encode($subscription);
	// 	            $payment_inst->total_amount             = $amount2;
	// 	            $payment_inst->currency                 = $currencySymbol;
	// 	            $payment_inst->payment_date             = date('y-m-d');
    //                 $payment_inst->package_name             = $request->package_name;
    //                 $payment_inst->planid             = $request->package_id;
    //                 $payment_inst->directory_id             = auth()->user()->directory_id;
    //                 $payment_inst->type             = 'auto_payment';
    //                 $payment_inst->ends_at              =   $effectiveDate;
    //                 $payment_inst->invoice_period       =   $request->invoice_period;
    //                 $payment_inst->invoice_interval     =   $request->invoice_interval;

	// 	            $payment_inst->save();
	// 	            $payment_id = $payment_inst->id;

	// 	            $bookingsOfSubscriber = PlanSubscription::where('subscriber_id', auth()->user()->directory_id ?? '0')->get();
	// 	            if (!empty($bookingsOfSubscriber)) {
	// 	                foreach ($bookingsOfSubscriber as $vall) {
	// 	                    PlanSubscriptionUsage::where('subscription_id', $vall['id'])->delete();
	// 	                }
	// 	            }
	// 	            PlanSubscription::where('subscriber_id', auth()->user()->directory_id)->delete();
	// 	            $planid                                 = $request->package_id;
	// 	            $package_name                           = $request->package_name;
	// 	            $userdirid                              = auth()->user()->directory_id;
	// 	            $PlanSubscription                       = new PlanSubscription();
	// 	            $PlanSubscription->subscriber_type      =   'App\Models\User';
	// 	            $PlanSubscription->subscriber_id        =   $userdirid;
	// 	            $PlanSubscription->plan_id              =   $planid;
	// 	            $PlanSubscription->slug                 =   Helper::getSeoUrl($package_name);
	// 	            $PlanSubscription->name                 =   json_encode($package_name);
	// 	            $PlanSubscription->ends_at              =   $effectiveDate;
    //                 $PlanSubscription->type              =   'signup';
	// 	            $PlanSubscription->save();
	// 	            $subscriptionid = $PlanSubscription->id;
	// 	            $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
	// 	            foreach ($feature as $val) {
	// 	                $plansFea = new PlanSubscriptionUsage();
	// 	                $plansFea->subscription_id          = $subscriptionid;
	// 	                $plansFea->feature_id               = $val->id;
	// 	                $plansFea->name                     = $val->slug;
	// 	                $plansFea->used                     = $val->value ?? 0;
	// 	                $plansFea->save();
	// 	            }
	// 	        }
    //     }else{

    //     		$getSubscriptionByCustomerID = $stripe->subscriptions->search([
	// 			  'query' => 'status:\'active\' AND metadata[\'customerid\']:\''.$customer_id.'\'',
	// 			]);
	// 			$subscriptionByCustomeridExist = count($getSubscriptionByCustomerID);
	// 			if($subscriptionByCustomeridExist!=0){
	//                 $stripe->subscriptions->cancel(
	// 				  $getSubscriptionByCustomerID['data']['0']['id'],
	// 				  []
	// 				);
	// 			}

	// 			$charge = Charge::create([
	// 	            'currency'      => 'SEK',
	// 	            'amount'        => $amount2,
	// 	            'customer'      => $customer_id,
	// 	            'description'   => $request->package_name
	// 	        ]);

	// 	        if ($charge['status'] == 'succeeded') {

    //                 $currentDate      = strtotime('today');
	// 	            $effectiveDate = date('Y-m-d h:i:s', strtotime("+" . $request->invoice_period . " " . $request->invoice_interval . " ", $currentDate));

	// 				$payment_inst = new PaymentTrasaction;
	// 	            $payment_inst->payment_method           = 'card';
	// 	            $payment_inst->transaction_id           = $charge['id'];
	// 	            $payment_inst->api_response             = json_encode($charge);
	// 	            $payment_inst->total_amount             = $amount2;
	// 	            $payment_inst->currency                 = $currencySymbol;
	// 	            $payment_inst->payment_date             = date('y-m-d');
    //                 $payment_inst->package_name             = $request->package_name;
    //                 $payment_inst->planid                   = $request->package_id;
    //                 $payment_inst->directory_id             = auth()->user()->directory_id;
    //                 $payment_inst->type             = 'default_payment';
    //                 $payment_inst->ends_at              =   $effectiveDate;
    //                 $payment_inst->invoice_period       =   $request->invoice_period;
    //                 $payment_inst->invoice_interval     =   $request->invoice_interval;
	// 	            $payment_inst->save();
	// 	            $payment_id = $payment_inst->id;

	// 	            $bookingsOfSubscriber = PlanSubscription::where('subscriber_id', auth()->user()->directory_id ?? '0')->get();
	// 	            if (!empty($bookingsOfSubscriber)) {
	// 	                foreach ($bookingsOfSubscriber as $vall) {
	// 	                    PlanSubscriptionUsage::where('subscription_id', $vall['id'])->delete();
	// 	                }
	// 	            }
	// 	            PlanSubscription::where('subscriber_id', auth()->user()->directory_id)->delete();
	// 	            $planid                                 = $request->package_id;
	// 	            $package_name                           = $request->package_name;
	// 	            $userdirid                              = auth()->user()->directory_id;
	// 	            $PlanSubscription                       = new PlanSubscription();
	// 	            $PlanSubscription->subscriber_type      =   'App\Models\User';
	// 	            $PlanSubscription->subscriber_id        =   $userdirid;
	// 	            $PlanSubscription->plan_id              =   $planid;
	// 	            $PlanSubscription->slug                 =   Helper::getSeoUrl($package_name);
	// 	            $PlanSubscription->name                 =   json_encode($package_name);
	// 	            $PlanSubscription->ends_at              =   $effectiveDate;
    //                 $PlanSubscription->type              =   'reguler';
	// 	            $PlanSubscription->save();
	// 	            $subscriptionid = $PlanSubscription->id;
	// 	            $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
	// 	            foreach ($feature as $val) {
	// 	                $plansFea = new PlanSubscriptionUsage();
	// 	                $plansFea->subscription_id          = $subscriptionid;
	// 	                $plansFea->feature_id               = $val->id;
	// 	                $plansFea->name                     = $val->slug;
	// 	                $plansFea->used                     = $val->value ?? 0;
	// 	                $plansFea->save();
	// 	            }
	// 	        }
    //     }

    //     $phoneUser = auth()->user()->phone;
    //     $countryName = auth()->user()->country;
    //     $countryPhone = Country::where('name', $countryName)->first();

    //     $msg = "Your profile upgrade successfully .";
    //     if (!empty($countryCodeData->phonecode)) {
    //         $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . $phoneUser, $msg);
    //     } else{
    //         $sendSms = LaraTwilio::notify('+' . $countryPhone->phonecode . $phoneUser, $msg);
    //     }
    //     return redirect('company/packages');
    //     }catch (\Exception $ex) {
    //         session()->flash('stripe_error', $ex->getMessage());
    //         return redirect()->route('company.package.details', $request->package_id);
    //     }
    // }


    public function setAddressChange(Request $request)
    {
        $addressId = $request->input('address_id');
            $data['defaultaddressData']         = UserAddress::select('*')->where('id', $addressId)->get()->first();


            return response()->json($data);
    }

}
