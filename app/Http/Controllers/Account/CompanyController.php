<?php

namespace App\Http\Controllers\Account;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Imports\HolidayExceptionalTempImportInner;
use App\Imports\HolidayTempImportInner;
use App\Imports\ProductsImport;
use App\Imports\VendorExceptionalHolidayImport;
use App\Models\Amenity;
use App\Models\AmenityCategory;
use App\Models\Assortmentlinks;
use App\Models\Attribute;
use App\Models\Diectory;
use App\Models\DiectoryLocation;
use App\Models\DiectoryTranslation;
use App\Models\DirectoryAmenity;
use App\Models\DirectoryCategory;
use App\Models\DirectoryContact;
use App\Models\DirectoryCreateOwnTags;
use App\Models\DirectoryCustomHoliday;
use App\Models\DirectoryMedia;
use App\Models\DirectoryMediaCover;
use App\Models\DirectoryMediagallery;
use App\Models\DirectoryMediaListing;
use App\Models\DirectoryMediaTrademarks;
use App\Models\DirectoryNewOwnTags;
use App\Models\DirectoryOwnTags;
use App\Models\DirectorySchedual;
use App\Models\DirectorySeo;
use App\Models\DirectorySeoTranslation;
use App\Models\DirectoryTags;
use App\Models\DirectoryType;
use App\Models\Product;
use App\Models\ProductCategories;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductTranslation;
use App\Models\User;
use App\Models\Websitelinks;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\DirectoryExceptionHoliday;
use App\Models\DirectoryClaim;
use App\Models\Claim;
use App\Models\PackageModule;
use App\Models\TrademarkCategory;
use App\Models\Trademarks;
use App\Models\VendorSupplierDetail;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Plans;
use App\Models\PlanSubscription;
use App\Models\PlanSubscriptionUsage;
use App\Models\Productcategory;
use App\Models\DirectoryServices;
use App\Models\ProductAttributes;
use App\Models\DirectorySubCategory;
use App\Models\ProductAttributesVariation;
use App\Models\ProductTags;
use App\Models\ProductVideo;
use App\Models\Category;
use App\Models\Country;
use App\Models\Tags;
use App\Models\Instruction;
use App\Models\client\Wishlist;
use AshAllenDesign\LaravelExchangeRates\ExchangeRate;
use Exception;
use Stripe\OAuth;
use Stripe\Stripe;
use Stripe\StripeClient;
use App\Models\ShoppingCard;
use App\Models\Events;
use App\Models\Blog;
use App\Models\DirectoryTypeGallery;
use App\Models\DirectoryTypePdfs;
use App\Models\Offer;
use App\Models\PaymentTrasaction;
use App\Models\tagTranslation;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\File;
use App\Imports\VendorPublicHolidayImport;
use App\Mail\ReplyMail;
use App\Models\CompanyImpProductTags;
use App\Models\CustomerClubs;
use App\Models\ExceptionalHolidayTemplateName;
use App\Models\Giftvoucher;
use App\Models\HolidayTemplateName;
use App\Models\OffersEmailNotificationHistory;
use App\Models\RedeemGiftVoucher;
use App\Models\ReplyRequest;
use App\Services\PayPalService;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CompanyController extends Controller
{

    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(config('stripe.api_keys.secret_key'));
        Stripe::setApiKey(config('stripe.api_keys.secret_key'));
    }

    /*
    * Company profile
    * Created By: Sanjay Sah
    * Created At: 17-03-22
    */
    public function companyprofile()
    {
        if (Auth::check()) {
            if (Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {
                $directoryBasicData = Diectory::where('created_by', Auth::user()->id)->first();
                return view('account.companyprofile', compact('directoryBasicData'));
            }
        } else {
            return abort(404);
        }
    }

    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }


    public function subscriptionSuccess(Request $request, PayPalService $paypal)
    {
        $subscriptionData = session()->get('subscription_data');
        $planid           = $subscriptionData['package_id'];
        $userId = auth()->user()->directory_id;

        if (!$subscriptionData) { return redirect('company/packages')->with('error', 'Subscription session expired.');}

        $subscriptionId = $request->get('subscription_id');
        if (!$subscriptionId) {  return redirect('company/packages')->with('error', 'Subscription ID missing.'); }

        $provider = $paypal->provider();
        $subscription = $provider->showSubscriptionDetails($subscriptionId);
        if (!isset($subscription['status']) || $subscription['status'] !== 'ACTIVE') {
            return redirect('company/packages')->with('error', 'Subscription not active.');
        }

        /* Cancel Existing PayPal Subscription */
        $oldSubscription = PlanSubscription::where('subscriber_id', $userId)->whereNotNull('paypal_subscription_id')->first();
        if ($oldSubscription) {
            try {
                $provider->cancelSubscription(
                    $oldSubscription->paypal_subscription_id,
                    'User changed subscription plan.'
                );
            } catch (\Exception $e) {
                Log::error('Cancel error: ' . $e->getMessage());
            }
            PlanSubscriptionUsage::where('subscription_id', $oldSubscription->id)->delete();
            // Delete old subscription
            $oldSubscription->delete();
        }

        $payment = new PaymentTrasaction();
        $payment->payment_method = 'paypal';
        $payment->transaction_id = $subscription['id']; // PayPal subscription ID
        $payment->api_response = json_encode($subscription);
        $payment->total_amount = $subscription['billing_info']['last_payment']['amount']['value'] ?? 0;
        $payment->currency = $subscription['billing_info']['last_payment']['amount']['currency_code'] ?? 'EUR';
        $payment->payment_date = now();
        $payment->package_name = $subscriptionData['package_name'];
        $payment->planid = $subscriptionData['package_id'];
        $payment->directory_id = $userId;
        $payment->type = 'recurring';
        $payment->save();

        /* Create New Plan Subscription */
        $planid                      = $subscriptionData['package_id'];
        $package_name                = $subscriptionData['package_name'];
        PlanSubscription::where('subscriber_id', auth()->user()->directory_id)->delete();

        $startTime = $subscription['start_time'] ?? now();
        $nextBillingTime = $subscription['billing_info']['next_billing_time'] ?? null;

        $planSubscription = new PlanSubscription();
        $planSubscription->subscriber_type = 'App\Models\User';
        $planSubscription->subscriber_id = $userId;
        $planSubscription->plan_id = $planid;
        $planSubscription->slug = Helper::getSeoUrl($package_name);
        $planSubscription->name = json_encode($package_name);
        $planSubscription->starts_at = $startTime;
        $planSubscription->ends_at = $nextBillingTime; // real PayPal billing date
        $planSubscription->paypal_subscription_id = $subscription['id'];
        $planSubscription->statuss = 'active';
        $planSubscription->type = 'recurring';
        $planSubscription->save();
        $subscription_id = $planSubscription->id;

        $bookingsOfSubscriber = PlanSubscription::where('subscriber_id', auth()->user()->directory_id ?? '0')->get();
            if (!empty($bookingsOfSubscriber)) {
                foreach ($bookingsOfSubscriber as $vall) {
                    PlanSubscriptionUsage::where('subscription_id', $vall['id'])->delete();
                }
            }

        $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
            foreach ($feature as $val) {
                $plansFea = new PlanSubscriptionUsage();
                $plansFea->subscription_id          = $subscription_id;
                $plansFea->feature_id               = $val->id;
                $plansFea->name                     = $val->slug;
                $plansFea->used                     = $val->value ?? 0;
                $plansFea->save();
            }

        session()->forget('subscription_data');

        return redirect('company/packages')
            ->with('success', 'Subscription activated successfully.');
    }

    /**
     * PayPal subscription cancelled by user
     */
    public function subscriptionCancel()
    {
        return redirect('company/packages')
            ->with('warning', 'Subscription process was cancelled.');
    }




    public function customerClubList(Request $request)
    {
        //dd($request->searchtext);
        if (Auth::check()) {
            if (Helper::getFeatureUses('customer-club') == false) {
                return abort(404);
            }
            $getCustomerValue = Helper::getFeatureUsesValue('customer-club');
            $data = $request->searchtext??'';
            if ($request->input('search')) {
                $searchText = $request->searchtext;

                $customerclubdata = CustomerClubs::where('seller_id', Auth::user()->directory_id)
                ->where(function ($query) use ($searchText) {
                    $query->where('email', 'like', "%$searchText%")
                        ->orWhere('fname', 'like', "%$searchText%")
                        ->orWhere('lname', 'like', "%$searchText%")
                        ->orWhere('message', 'like', "%$searchText%")
                        ->orWhere('phone', 'like', "%$searchText%")
                        ->orWhere('zipcode', 'like', "%$searchText%");
                })
                ->groupBy('email')
                ->orderByDesc('id')
                ->paginate($getCustomerValue);

            }else{
                $customerclubdata = CustomerClubs::where('seller_id', Auth::user()->directory_id)->orderBy('id', 'desc')->groupBy('email')->paginate($getCustomerValue);
            }
           return view('account.company.customer_club', compact('data','customerclubdata','getCustomerValue'));
        } else {
            return abort(404);
        }
    }

    public function exportCustomerClub(Request $request)
    {
        if (!Auth::check()) {
            return abort(404);
        }

        $getCustomerValue = Helper::getFeatureUsesValue('customer-club');

        if ($request->input('search')) {
            $searchText = $request->searchtext;

            $customerclubdata = CustomerClubs::where('seller_id', Auth::user()->directory_id)
                ->where(function ($query) use ($searchText) {
                    $query->where('email', 'like', "%$searchText%")
                        ->orWhere('fname', 'like', "%$searchText%")
                        ->orWhere('lname', 'like', "%$searchText%")
                        ->orWhere('message', 'like', "%$searchText%")
                        ->orWhere('phone', 'like', "%$searchText%")
                        ->orWhere('zipcode', 'like', "%$searchText%");
                })
                ->groupBy('email')
                ->orderByDesc('id')
                ->get();

        } else {
            $customerclubdata = CustomerClubs::where('seller_id', Auth::user()->directory_id)->orderBy('id', 'desc')->groupBy('email')->get();
        }

        return Excel::download(new \App\Exports\CustomerClubExport(collect($customerclubdata)), 'customer_club.xlsx');
    }

     public function appointmentBookingReply(Request $request)
    {
        //dd($request->all());
        unset($request['_token']);
        $validator = \Validator::make($request->all(), [
            'reply_email'  => 'required',
            'subject'      => 'required',
            'query'        => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        } else {

            //dd($request->all());

            //try {
                $booking                    = new ReplyRequest();
                $booking->company_id        = Auth::user()->directory_id;
                $booking->request_id        = $request->input('reply_id');
                $booking->type              = 'booking';
                $booking->email             = $request->input('reply_email');
                $booking->subject           = $request->input('subject');
                $booking->query             = $request->input('query');
                $res = $booking->save();
                if ($res) {
                    Mail::to($request->input('reply_email'))->send(new ReplyMail($request->all()));
                    return response()->json(['status' => true, 'message' => 'Reply message sent successfully!']);
                } else {
                    return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
                }
            // } catch (Exception $e) {
            //     return response()->json(['status' => false, 'message' => 'Something went wrong. Try again!']);
            // }
        }
    }

    public function getOffersByType(Request $request){

        if($request->offertype == 'offers'){
           $get_offer = Offer::find($request->offerid);

           $minimum_amount = $get_offer->minimum_amount;
        }else if($request->offertype == 'gift_voucher'){
            $get_offer = Giftvoucher::find($request->offerid);
            $minimum_amount = $get_offer->minimum_amount;
        }else{
            $get_offer = ShoppingCard::find($request->offerid);
            $minimum_amount = $get_offer->minimum_amount;
        }
        return response()->json([
            'status' => 'success',
            'offers_data' => $minimum_amount
        ]);
        //dd($minimum_amount);
    }

    public function deleteImpProTagsbyId(Request $request){

        CompanyImpProductTags::where('ptag_id', $request->imp_tag_id)->where('company_id', $request->company_id)->delete();

        $tags = CompanyImpProductTags::where('company_id',$request->company_id)->get();

        return response()->json([
            'status'  => true,
            'message' => 'Successfully deleted.',
            'data'    => $tags,
        ]);

    }

    public function getVendorImpProTagsbyId(Request $request)
    {
        //dd($request->all());
        $is_exist = CompanyImpProductTags::where('ptag_id', $request->tag_id)->first();

        if ($is_exist) {
            return response()->json([
                'status'  => false,
                'message' => 'This tag has already been added.',
            ]);
        }

        $com_imp_ptags = new CompanyImpProductTags();
        $com_imp_ptags->ptag_id     = $request->tag_id;
        $com_imp_ptags->ptag_name   = $request->tag_title;
        $com_imp_ptags->company_id  = $request->company_id;
        $com_imp_ptags->save();

        $tags = CompanyImpProductTags::where('company_id',$request->company_id)->get();

        return response()->json([
            'status'  => true,
            'message' => 'Tag added successfully.',
            'data'    => $tags,
        ]);



    }

    // public function verifyStripe(Request $request)
    // {
    //     $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));

    //     $company = Auth::user();

    //     $company_email = $company->email;
    //     $existing_account_id = $company->stripe_connect;

    //     // Detect if current API key is Live or Test
    //     $isLiveMode = !str_starts_with(env('STRIPE_SECRET'), 'sk_test_');

    //     // Helper: Stripe account IDs start with "acct_"
    //     $isAccountValid = function ($accountId) use ($stripe, $isLiveMode) {
    //         if (!$accountId) return false;
    //         try {
    //             $account = $stripe->accounts->retrieve($accountId, []);
    //             // Compare live/test mode consistency
    //             return $account->livemode === $isLiveMode;
    //         } catch (\Exception $e) {
    //             return false;
    //         }
    //     };

    //     // Create or reuse account
    //     if ($isAccountValid($existing_account_id)) {
    //         // Use existing connected account
    //         $created_ac_id = $existing_account_id;
    //         session()->put('stripe_connected_id', $created_ac_id);
    //         session()->put('p_status', 'update');
    //     } else {
    //         // Create new connected account (test or live mode)
    //         $connected_account = $stripe->accounts->create([
    //             'type' => 'express',
    //             'country' => 'SE',
    //             'email' => $company_email,
    //             'capabilities' => [
    //                 'card_payments' => ['requested' => true],
    //                 'transfers' => ['requested' => true],
    //             ],
    //         ]);

    //         $created_ac_id = $connected_account->id;

    //         // Save to user
    //         $company->update(['stripe_connect' => $created_ac_id]);

    //         session()->put('stripe_connected_id', $created_ac_id);
    //         session()->put('p_status', 'create');
    //     }

    //     // Create onboarding link
    //     $account_links = $stripe->accountLinks->create([
    //         'account' => $created_ac_id,
    //         'refresh_url' => url('/'),
    //         'return_url' => url('/redirect'),
    //         'type' => 'account_onboarding',
    //     ]);

    //     return redirect($account_links->url);
    // }


    public function redirect(Request $request)
    {

        $vendor     = User::where('directory_id',Auth::user()->directory_id??0)->where('parent_id',0)->first();
        $user       = User::findOrFail($vendor->id);
        $user->stripe_connect = session()->get('stripe_connected_id');
        $user->save();

        if(session()->get('p_status') == 'create'){
            return redirect()->route('company.products')->with('msg', 'Successfully connected with stripe.');
        }else{
            return redirect()->route('company.products')->with('msg', 'Successfully updated connected stripe info.');
        }

    }

    private function getToken($code)
    {
        $token = null;
        try {
            $token = OAuth::token([
                'grant_type' => 'authorization_code',
                'code' => $code
            ]);
        } catch (Exception $e) {
            $token['error'] = $e->getMessage();
        }
        return $token;
    }

    private function getAccount($connectedAccountId)
    {
        $account = null;
        try {
            $account = $this->stripe->accounts->retrieve(
                $connectedAccountId,
                []
            );
        } catch (Exception $e) {
            $account['error'] = $e->getMessage();
        }
        return $account;
    }





    /*
    * Company dashboard
    * Created By: Sanjay Sah
    * Created At: 17-03-22
    */
    public function companydashboard()
    {
        if (Auth::check()) {


            //https://free.currconv.com/api/v7/convert?q=USD_PHP&compact=ultra&apiKey=eccdb944e27a1f80280c

                //$amount = ($request->amount)?($request->amount):(1);
                // $amount = 1;

                // $apikey = 'eccdb944e27a1f80280c';

                // $from_Currency = urlencode('USD');
                // $to_Currency = urlencode('INR');
                // //$from_Currency = urlencode($request->from_currency);
                // //$to_Currency = urlencode($request->to_currency);
                // $query =  "{$from_Currency}_{$to_Currency}";

                // // change to the free URL if you're using the free version
                // $json = file_get_contents("https://free.currconv.com/api/v7/convert?q={$query}&compact=ultra&apiKey={$apikey}");

                // $obj = json_decode($json, true);

                // $val = $obj["$query"];

                // $total = $val['val'] * 1;

                // $formatValue = number_format($total, 2, '.', '');

                // $data = "$amount $from_Currency = $to_Currency $formatValue";

                // echo $data; die;




            if (Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {
                return view('account.companydashboard');
            }
        } else {
            return abort(404);
        }
    }
    /*
    * Complete Company info
    * Created By: Sanjay Sah
    * Created At: 24-03-22
    */
    public function complatecompanyinfo()
    {
        if (Auth::check()) {


            $hasDiectory = Diectory::where('id', Auth::user()->directory_id)->first();
            if ($hasDiectory && Auth::user()->profile_status == 1) {
                return redirect('company/reports/dashboard');
            } else {

                $data['languagesData']          = Helper::getAllLanguages();            /* get languages */
                $data['parent_category']        = Helper::getAllParentCategory();       /* get Category */
                $data['child_category']     = Helper::getAllChildCategory();      /* get Child Category */
                $data['country']                = Helper::getCountryAllData();          /* get country */
                $data['amenity']                = Helper::getAmenity();                 /* get amenity */
                $data['trademarks']             = DB::table('trademarks')->where('status',1)->select('*')->where('deleted','0')->get();             /* get amenity */
                $data['trademarkcategory']      = TrademarkCategory::where('deleted','0')->where('directory_id',Auth::user()->directory_id)->orWhere('directory_id', 0)->orderBy('id', 'desc')->get(); //Helper::getTrademarkCategory();       /* get Trademark Category */
                $data['type']                   = Helper::getType();                    /* get category whose type is listing */
                $data['tags']                   = Helper::getTagsData();                /* get tags */
                $data['owntags']                = Helper::getOwnTagsData();             /* get owntags */
                $data['directoryowntags']       = Helper::getdirectoryOwnTagsData();    /* get directory owntags */
                $data['normaldaysdata']         = DB::table('normaltemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();
                $data['publicdaysdata']         = DB::table('holidaytemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();
                $data['exeptionaldaysdata']     = DB::table('exeptionalholidaytemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();
                $data['amenityCategoryData']    = Helper::getamenityCategoryData();             /* get amenityCategoryData */
                $data['instruction']            = Instruction::select('message')->where('section_name', 'companyinfo')->where('status','Active')->first(); /* get instructionData */
                return view('account.company_add_info', ['data' => $data]);
            }
        } else {
            return abort(404);
        }
    }

    public function importExportView()
    {
        try{
            return view('import');
        } catch (Exception $e) {
            return redirect('company/products')->with('error', 'Something went wrong. Try again!');
        }
    }

    public function redeemvoucher(Request $request){

            $is_code_valid = OffersEmailNotificationHistory::where('user_email', $request->input('redeem_email'))->where('offer_code', $request->input('voucher_code'))->first();

            if($is_code_valid){

                if($request->offer_type == 'gift_voucher'){
                    $getVoucher = Giftvoucher::where('id', $is_code_valid->offer_id)->first();
                }else if($request->offer_type == 'shopping_card'){
                    $getVoucher = ShoppingCard::where('id', $is_code_valid->offer_id)->first();
                }else if($request->offer_type == 'offers'){
                    $getVoucher = Offer::where('id', $is_code_valid->offer_id)->first();
                }
                //dd($getVoucher);

                if($getVoucher->minimum_amount > $request->input('total_purchaged_amount')){
                    echo json_encode(array('msg' => 'To use the voucher code '.$request->input('voucher_code').', your total purchase amount must exceed '.$getVoucher->minimum_amount.'.','status' =>'error')); exit;
                }else{
                    $is_customer_club = RedeemGiftVoucher::where('redeem_email', $request->input('redeem_email'))->where('voucher_type', $request->offer_type)->where('voucher_code', $request->input('voucher_code'))->exists();

                    if($is_customer_club){
                        echo json_encode(array('msg' => 'Voucher code redeemed already.','status' =>'error')); exit;
                    }else{

                        $RedeemGift = new RedeemGiftVoucher();
                        $RedeemGift->cc_id             = $request->input('offeremail_id');
                        $RedeemGift->redeem_email      = $request->input('redeem_email');
                        $RedeemGift->voucher_code      = $request->input('voucher_code');
                        $RedeemGift->voucher_type      = $request->offer_type;
                        $RedeemGift->total_purchaged_amount = $request->input('total_purchaged_amount');
                        $RedeemGift->voucher_message   = $request->input('voucher_message');
                        $save = $RedeemGift->save();
                        if($save){
                            OffersEmailNotificationHistory::where('user_email', $request->input('redeem_email'))
                                ->where('offer_code', $request->input('voucher_code'))
                                ->update(['redeem_status' => 'Redeemed at '.NOW()->format('Y-m-d')]);

                            echo json_encode(array('msg' => 'Voucher code redeemed successfully.','status' =>'success')); exit;
                        }else{
                            echo json_encode(array('msg' => 'Something went wrong.','status' =>'error')); exit;
                        }

                    }

                }

            }else{
                echo json_encode(array('msg' => 'Invalid voucher code. Please try again.','status' =>'error')); exit;
            }

	}

    public function export(Request $request)
    {
        return Excel::download(new ProductsExport($request->all()), 'products.xlsx');
    }

    public function import(Request $request)
    {

        unset($request['_token']);
        $validator = \Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls', // Adjust max file size as per your requirement
        ], [
            'file.required' => 'Please upload an Excel file.',
            'file.file' => 'The uploaded file must be a valid file.',
            'file.mimes' => 'The uploaded file must be a .xlsx or .xls file.'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        } else {

            $file = $request->file('file');
            $rows = Excel::toArray([], $file);
            $filteredRows = array_filter($rows[0], function ($row) {
                return array_filter($row); // Exclude empty rows
            });
            $rowCount = count($filteredRows);
            $package_products_limit = Helper::getFeatureUsesValue('products');
            $getFeatureUsesValueimport = Helper::getFeatureUsesValue('import');
            $count_existing_product = Product::where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->count(); // 24

            if ($rowCount > $getFeatureUsesValueimport  ) {
                return response()->json(['status' => false, 'message' => 'Please upgrade your package. your product import limit '.$getFeatureUsesValueimport.' rows at a time ']);
            }else if ($count_existing_product >= $package_products_limit) {
                return response()->json(['status' => false, 'message' => 'Please upgrade your package. you can not update or upload products. package maximum upload limit is '.$package_products_limit.'. if you want to update the products then existing product should be less than '.$package_products_limit]);
            }else{

                try {
                   $productsImport = Excel::import(new ProductsImport, $request->file('file'));
                   return response()->json(['status' => true, 'message' => 'Successfully imported. If some columns were not imported, please follow the sample Excel sheet format.']);
                } catch (\Exception $e) {
                    return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
                }

            }

        }
    }

    /*
    * Company products
    * Created By: Sanjay Sah
    * Created At: 22-03-22
    */
    public function companyproducts(Request $request)
    {

        $language_id = Helper::getselectedLanguage();

        if (Auth::check()) {

            if(Helper::getFeatureUses('products') == false){
                return abort(404);
            }

            if (Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {

                $attributes = Attribute::where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->count();

                $instruction = Instruction::select('message')->where('section_name',  'product')->where('status','Active')->first();

                $main = 'main';
                $galimg = 'galimg';
                $act_date = $request->input('searchdate')?$request->input('searchdate'):"";
                if($request->input('search')){

                    $query = $request->all();
                    $searchQuery = $request->input('searchtext');
                    $act_date = $request->input('searchdate')?$request->input('searchdate'):"";
                    $exp_date = $request->input('searchdateexpiry')?$request->input('searchdateexpiry'):"";
                    $statusSearch = $request->input('status')?$request->input('status'):"";
                    $data['searchQuery']=$request->input('searchtext');
                    $data['table_name']='product';
                    $data['columns']=['name','ena'];
                    $productdata=Helper::companymasterSearch($data);

                    if($act_date!=""){



                        $datarow['searchDetails'] = Product::where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')
                        ->with(['image' => function ($q) use ($main) {
                            $q->where('main_image', '=', $main);
                        }])
                        ->with(['gallery' => function ($q) use ($galimg) {
                            $q->where('main_image', '=', $galimg);
                        }])->where('opening_date',  $act_date );

                        $productdata =    $datarow['searchDetails']->where('directory_id', Auth::user()->directory_id)->orderBy('opening_date', 'asc')->paginate(20);

                        $data = isset($searchQuery)?$request->input('searchtext'):"";
                        $searchDateAct=isset($act_date)?$act_date:'';
                        $searchDateExp=isset($exp_date)?$exp_date:'';
                        $searchstatus = isset($statusSearch)?$statusSearch:'';
                        return view('account.company.products', ['productdata' => $productdata, 'attributes' => $attributes, 'instruction'=> $instruction, 'searchDateAct'=>$searchDateAct,'searchDateExp'=>$searchDateExp ,'data'=> $data,'query'=>$query, 'searchstatus'=>$searchstatus]);
                    }else if($exp_date!=""){

                        $datarow['searchDetails'] = Product::where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')
                        ->with(['image' => function ($q) use ($main) {
                            $q->where('main_image', '=', $main);
                        }])
                        ->with(['gallery' => function ($q) use ($galimg) {
                            $q->where('main_image', '=', $galimg);
                        }])->where('closing_date',  $exp_date );

                        $productdata =    $datarow['searchDetails']->orderBy('opening_date', 'asc')->paginate(20);

                        $data = isset($searchQuery)?$request->input('searchtext'):"";
                        $searchDateExp=isset($exp_date)?$exp_date:'';
                        $searchDateAct=isset($act_date)?$act_date:'';
                        $searchstatus = isset($statusSearch)?$statusSearch:'';
                        return view('account.company.products', ['productdata' => $productdata,'attributes' => $attributes, 'instruction'=> $instruction, 'searchDateExp'=>$searchDateExp,'searchDateAct'=>$searchDateAct ,'data'=> $data,'query'=>$query, 'searchstatus'=>$searchstatus]);
                    }else if($statusSearch=="active"){
                        $datarow['searchDetails'] = Product::where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')
                        ->with(['image' => function ($q) use ($main) {
                            $q->where('main_image', '=', $main);
                        }])
                        ->with(['gallery' => function ($q) use ($galimg) {
                            $q->where('main_image', '=', $galimg);
                        }])->where('status',  $statusSearch );

                        $productdata =    $datarow['searchDetails']->orderBy('id', 'asc')->paginate(20);

                        $data = isset($searchQuery)?$request->input('searchtext'):"";
                        $searchDateExp=isset($exp_date)?$exp_date:'';
                        $searchDateAct=isset($act_date)?$act_date:'';
                        $searchstatus = isset($statusSearch)?$statusSearch:'';
                        return view('account.company.products', ['productdata' => $productdata,'attributes' => $attributes, 'instruction'=> $instruction, 'searchDateExp'=>$searchDateExp,'searchDateAct'=>$searchDateAct ,'data'=> $data,'query'=>$query, 'searchstatus'=>$searchstatus]);
                    }else if($statusSearch=="inactive"){
                        $datarow['searchDetails'] = Product::where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')
                        ->with(['image' => function ($q) use ($main) {
                            $q->where('main_image', '=', $main);
                        }])
                        ->with(['gallery' => function ($q) use ($galimg) {
                            $q->where('main_image', '=', $galimg);
                        }])->where('status',  $statusSearch );

                        $productdata =    $datarow['searchDetails']->orderBy('id', 'asc')->paginate(20);

                        $data = isset($searchQuery)?$request->input('searchtext'):"";
                        $searchDateExp=isset($exp_date)?$exp_date:'';
                        $searchDateAct=isset($act_date)?$act_date:'';
                        $searchstatus = isset($statusSearch)?$statusSearch:'';
                        return view('account.company.products', ['productdata' => $productdata,'attributes' => $attributes,'instruction'=> $instruction, 'searchDateExp'=>$searchDateExp,'searchDateAct'=>$searchDateAct ,'data'=> $data,'query'=>$query, 'searchstatus'=>$searchstatus]);
                    }else{

                        $datarow['searchDetails'] = Product::where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')
                        ->with(['image' => function ($q) use ($main) {
                            $q->where('main_image', '=', $main);
                        }])
                        ->with(['gallery' => function ($q) use ($galimg) {
                            $q->where('main_image', '=', $galimg);
                        }])->where('name', 'like', '%'. $searchQuery .'%')->orWhere('ena', 'LIKE', '%'.$searchQuery.'%');

                        $product_tags_id = DB::table('product_tags')->where('tags',$searchQuery)->first();
                        if(!empty($product_tags_id)){
                            $datarow['searchDetails']->orwhereRaw('FIND_IN_SET("' . $product_tags_id->id. '",lc_product.tags)');
                        }
                        $datarow['searchDetails']->orwhere('supplier_product_no',$searchQuery)->where('directory_id', Auth::user()->directory_id);

                        $productdata =    $datarow['searchDetails']->orderBy('opening_date', 'asc')->paginate(20);
                        $data = isset($searchQuery)?$request->input('searchtext'):"";
                        $searchDateAct=isset($act_date)?$act_date:'';
                        $searchDateExp=isset($exp_date)?$exp_date:'';
                        $searchstatus = isset($statusSearch)?$statusSearch:'';
                        return view('account.company.products', ['productdata' => $productdata,'attributes' => $attributes,'instruction'=> $instruction,'data'=> $data,'searchDateExp'=>$searchDateExp,'query'=>$query,'searchDateAct'=>$searchDateAct, 'searchstatus'=>$searchstatus]);
                    }


                    $productdata = $datarow['searchDetails']->orderBy('id', 'desc')
                    ->with(['image' => function ($q) use ($main) {
                        $q->where('main_image', '=', $main);
                    }])
                    ->with(['gallery' => function ($q) use ($galimg) {
                        $q->where('main_image', '=', $galimg);
                    }])->orderBy('id', 'desc')->paginate(20);

                    $data = isset($searchQuery)?$request->input('searchtext'):"";
                    $searchDateAct=isset($act_date)?$act_date:'';
                    $searchDateExp=isset($exp_date)?$exp_date:'';
                    $searchstatus = isset($statusSearch)?$statusSearch:'';
                    return view('account.company.products', ['productdata' => $productdata,'attributes' => $attributes,'instruction'=> $instruction, 'data'=> $data,'query'=>$query,'searchDateAct'=>$searchDateAct,'searchDateExp'=>$searchDateExp, 'searchstatus'=>$searchstatus]);

                }else{

                    $productDirctoryData = Product::select(
                                'product.*',
                                'pro_trans.id AS product_lang_id',
                                'pro_trans.name AS product_lang_name',
                                'pro_trans.short_content'
                            );
                    $productDirctoryData = $productDirctoryData->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id');
                    $productDirctoryData = $productDirctoryData->Where('pro_trans.language_id', $language_id);
                    $productDirctoryData = $productDirctoryData->where('directory_id', Auth::user()->directory_id)->where('deleted', '0')->orderBy('id', 'desc')
                        ->with(['image' => function ($q) use ($main) {
                            $q->where('main_image', '=', $main);
                        }])
                        ->with(['gallery' => function ($q) use ($galimg) {
                            $q->where('main_image', '=', $galimg);
                        }])->orderBy('id', 'desc')->paginate(20);
                    $productDirctoryData->appends($request->query());

                    //dd($productDirctoryData);

                    $data = isset($searchQuery)?$request->input('searchtext'):"";
                    $searchDateAct=isset($act_date)?$act_date:'';
                    $searchDateExp=isset($exp_date)?$exp_date:'';
                    $searchstatus = isset($statusSearch)?$statusSearch:'';
                    return view('account.company.products', ['productdata' => $productDirctoryData,'attributes' => $attributes,'instruction'=> $instruction,'data'=> $data,'searchDateAct'=>$searchDateAct,'searchDateExp'=>$searchDateExp, 'searchstatus'=>$searchstatus]);

                }
            }

        } else {
            return abort(404);
        }
    }

    /*
    * Company add new products
    * Created By: Sanjay Sah
    * Created At: 22-03-22
    */
    public function companyaddproduct()
    {
        if (Auth::check()) {
            if (Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {

                $attributesData = Attribute::leftjoin('attribute_statuses', 'attribute_statuses.attribute_id', '=', 'attributes.id')
                ->select('attributes.*','attribute_statuses.status as attributeStatus')
                ->where('deleted','0')->where('attributes.status', 1)->get();

                $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
                $data['parent_category']    = Helper::getAllParentProductCategory();      /* get product Category */
                $data['productsubcat']      = Helper::getProductCatgoeryData();
                $data['currency']           = Helper::getAllCurrency();             /* get All Currency */
                $data['tags']                   = Helper::getProductTagsData();                /* get tags */
                $data['tagsownDirectory']   = DirectoryCreateOwnTags::where("directory_id", auth()->user()->directory_id)->get(["id", "tag_name"]);             /* get All tags */

                $userIP = $this->getIp();
                    $country = '';
                if (in_array($userIP, ['127.0.0.1', '::1', 'localhost'])) {
                    $country = 'Sweden';
                } else {
                    $response = @file_get_contents('http://ip-api.com/php/'.$userIP);
                    if ($response !== false) {
                        $geoData = @unserialize($response);
                        if ($geoData && isset($geoData['country'])) {
                            $country = $geoData['country'];
                        }
                    }
                }
                if (empty($country)) {
                    $country = 'Admin';
                }

                return view('account.company.addproduct', compact('data','attributesData','country'));
            }
        } else {
            return abort(404);
        }
    }

    /*
    * Company add new products
    * Created By: Sanjay Sah
    * Created At: 22-03-22
    */
    public function companyproductsubmit(Request $request)
    {

        $this->validate($request, [
            'product_name_en'   => 'required|max:50',
            'regular_SEK'       => 'required',
            'short_des_en'      => 'max:40',
            'long_des_en'       => 'max:500',
            //'sku'               => 'required|unique:product,sku',
            'main_image'        => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            //'gal_image'         => 'array|max:15',
            //'gal_image.*'       => 'mimes:jpeg,png,jpg,gif,svg',
            //'video'             => 'required|mimes:mp4,3gp,mov,flv,avi,wmv,ogg,ogv,webm,mpg,mpeg',
            'opening_date'      => 'required',
            'closing_date'      => 'required',
            'ena'               => 'required',
            ],
            [
                'opening_date.required'     => 'The Active date field is required.',
                'closing_date.required'     => 'The Expiry date field is required.',
                'regular_SEK.required'      => 'The Swedish Kronor field is required.',
            ]
        );


        $dirId = $request->input('directoryId');
        $dirlocationdetails = DiectoryLocation::where('directory_id', $dirId)->first();
        if (!empty($dirlocationdetails)) {
            if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                $latitude = $dirlocationdetails['latitude'];
                $longitude = $dirlocationdetails['longitude'];
            }else{
                $latitude = $request->input('lati');
                $longitude = $request->input('long');
            }
        }else{
            $latitude = $request->input('lati');
            $longitude = $request->input('long');
        }

        $name_en = $request->input('product_name_en');
        $product_inst = new Product;
        $product_inst->name                     = (isset($name_en)) ? $name_en : "";
        $product_inst->product_number_type      = $request->input('product_number_type');
        $product_inst->seo_url                  = Helper::getSeoUrl($name_en);
        $product_inst->sku                      = $request->input('sku');
        $product_inst->directory_id             = $request->input('directoryId');
        $product_inst->tags                     = $request->input('vendor_own_tags');
        $product_inst->cat_id                   = $request->input('category');

        if ($request->input('vendor_own_tags')) {
            $productTags = array();
            foreach ($request->input('vendor_own_tags') as $tagsrow) {
                $productTags[] = $tagsrow;
            }
            $product_inst->tags    =  implode(",", $productTags);
        }

        $product_inst->stock                    = $request->input('stock');
        $product_inst->ena                      = $request->input('ena');
        $product_inst->supplier_name            = $request->input('supplier_name');
        $product_inst->is_visible_on_shop_detailpage  = $request->input('is_visible_on_shop_detailpage') ? 'yes' : 'no';
        $product_inst->supplier_product_no      = $request->input('supplier_product_no');
        $product_inst->status                   = 'Active';
        $product_inst->outofstock               = $request->input('outstock');
        $product_inst->in_store_only            = $request->input('in_store_only');
        $product_inst->is_free_product          = $request->input('is_free_product');
        $product_inst->video_link               = $request->input('video_link')??'';
        $product_inst->latitude                 = $latitude;
        $product_inst->longitude                = $longitude;
        $product_inst->popular                  = $request->input('popular')??0;
        $product_inst->equal_product            = $request->input('equal_product');
        $product_inst->purchase_price           = $request->input('purchase_price');
        $product_inst->created_by               = $request->input('createdby');
        $product_inst->closing_date             = $request->input('closing_date');
        $product_inst->opening_date             = $request->input('opening_date');
        $product_inst->default_duration         = $request->input('default_status');

        if($request->input('default_status')=='yes'){
            $product_inst->default_month        = $request->input('set_months');
        }
        $product_inst->save();
        $product_id = $product_inst->id;

        $short_des_en               = $request->input('short_des_en');
        $long_des_en                = $request->input('long_des_en');
        $meta_title_en              = $request->input('meta_title_en');
        $meta_description_en        = $request->input('meta_description_en');

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        foreach ($languages as $lang) {
            $product_name               = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
            $product_short_content      = $tr->setSource('en')->setTarget($lang->code)->translate($short_des_en??'');
            $product_long_content       = $tr->setSource('en')->setTarget($lang->code)->translate($long_des_en??'');
            $product_meta_title         = $tr->setSource('en')->setTarget($lang->code)->translate($meta_title_en??'');
            $product_meta_description   = $tr->setSource('en')->setTarget($lang->code)->translate($meta_description_en??'');

            $productshortinsert = new ProductTranslation();
            $productshortinsert->product_id             = $product_id;
            $productshortinsert->language_id            = $lang->id;
            $productshortinsert->name                   = isset($product_name) ? $product_name : $name_en ;
            $productshortinsert->short_content          = isset($product_short_content) ? $product_short_content : $short_des_en;
            $productshortinsert->long_content           = isset($product_long_content) ? $product_long_content : $long_des_en;
            $productshortinsert->meta_title             = isset($product_meta_title) ? $product_meta_title : $meta_title_en;
            $productshortinsert->meta_description       = isset($product_meta_description) ? $product_meta_description : $meta_description_en;
            $productshortinsert->meta_keyword           = isset($product_short_content) ? $product_short_content : $short_des_en;
            $productshortinsert->save();
        }

        /* Seo Entries start */
        $product_meta_title = $request->input('meta_title_en');
        $product_meta_description = $request->input('meta_description_en');
        $dirtseo = new DirectorySeo;
        $dirtseo->module_id     = $product_id;
        $dirtseo->page_type     = 'product';
        $dirtseo->meta_keyword  =  isset($product_meta_title) ? $product_meta_title : "";
        $dirtseo->meta_description  = isset($product_meta_description) ? $product_meta_description : "";
        $dirtseo->save();

        $imgfile = $request->file('main_image');
        if ($imgfile) {

            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);

            $imagepath = 'images/product/' . $imageName;
                $convertext = "webp";
                $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);
                Storage::disk('images')->put('product/'.$imageName.'.'.$convertext,$image_resize);
            $productmainimage_insert                    = new ProductImage();
            $productmainimage_insert->product_id        = $product_id;
            $productmainimage_insert->main_image        = "main";
            $productmainimage_insert->name_thumbnail    = $imageName;
            $productmainimage_insert->name_large        = $imageName;
            $productmainimage_insert->extension         = $ext;
            $productmainimage_insert->path              = $imagepath;
            $productmainimage_insert->save();
        }

        $imgfilegal = $request->file('gal_image');
        if ($imgfilegal) {
            foreach ($imgfilegal as $imgrow) {

                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);

                $imagepath = 'images/product/' . $imageName;
                $convertext = "webp";
                $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                Storage::disk('images')->put('product/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                    = new ProductImage();
                $productmainimage_insert->product_id        = $product_id;
                $productmainimage_insert->main_image        = "galimg";
                $productmainimage_insert->name_thumbnail    = $imageName;
                $productmainimage_insert->name_large        = $imageName;
                $productmainimage_insert->extension         = $ext;
                $productmainimage_insert->path              = $imagepath;
                $productmainimage_insert->save();
            }
        }

        $videofile = $request->file('video');
        if ($videofile) {
            $video_ext = $videofile->getClientOriginalExtension();
            $name2 = $videofile->getClientOriginalName();
            $videoName = time() . $name2;
            $videopath = 'files/productvideo/' . $videoName;
            $videofile->move(public_path('files/productvideo/'), $videoName);
                $productvideo_insert                    = new ProductVideo();
                $productvideo_insert->product_id        = $product_id;
                $productvideo_insert->video_link        = $videoName;
            $productvideo_insert->save();
        }
        /* Directory Category Starts */
        $categories = $request->input('subproductcat');
        if (!empty($categories)) {
            foreach ($categories as $cat => $value) {
                $productcategoryInsertData = new ProductCategories();
                $productcategoryInsertData->product_id  = $product_id;
                // $productcategoryInsertData->sub_category_id  = $value;
                $productcategoryInsertData->category_id  = $value;
                $productcategoryInsertData->save();
            }
        }

        $userIP = $this->getIp();
        $country = '';
        if (in_array($userIP, ['127.0.0.1', '::1', 'localhost'])) {
            $country = 'Sweden';
        } else {
            $response = @file_get_contents('http://ip-api.com/php/'.$userIP);
            if ($response !== false) {
                $geoData = @unserialize($response);
                if ($geoData && isset($geoData['country'])) {
                    $country = $geoData['country'];
                }
            }
        }
        if (empty($country)) {
            $country = 'Admin';
        }

        $countryToCurrency = [
            'Sweden' => 'SEK',
            //GBP
            'United Kingdom' => 'GBP',
            'Guernsey' => 'GBP',
            'Isle of Man' => 'GBP',
            'Jersey' => 'GBP',
            'Saint Helena' => 'GBP',
            //PLN
            'Poland' => 'PLN',
            //DKK
            'Denmark' => 'DKK',
            'Greenland' => 'DKK',
            'Faroe Islands' => 'DKK',
            //NOK
            'Norway' => 'NOK',
            'Quisling regime' => 'NOK',
            //EUR
            'Andorra' => 'EUR',
            'Austria' => 'EUR',
            'Belgium' => 'EUR',
            'Croatia' => 'EUR',
            'Cyprus' => 'EUR',
            'Estonia' => 'EUR',
            'Finland' => 'EUR',
            'France' => 'EUR',
            'Germany' => 'EUR',
            'Greece' => 'EUR',
            'Ireland' => 'EUR',
            'Italy' => 'EUR',
            'Latvia' => 'EUR',
            'Lithuania' => 'EUR',
            'Luxembourg' => 'EUR',
            'Malta' => 'EUR',
            'Monaco' => 'EUR',
            'Montenegro' => 'EUR',
            'Netherlands' => 'EUR',
            'Portugal' => 'EUR',
            'San Marino' => 'EUR',
            'Slovakia' => 'EUR',
            'Slovenia' => 'EUR',
            'Spain' => 'EUR',
            //BGN
            'Bulgaria' => 'BGN',
            //HRK
            'Croatia' => 'HRK',
            //RON
            'Romania' => 'RON',
            //CZK
            'Czech Republic' => 'CZK',
            //HUF
            'Hungary' => 'HUF',
        ];
        $country_code = '';
        if (isset($countryToCurrency[$country])) {
            $country_code = $countryToCurrency[$country];
        }


        /* Directory Category Ends */
        /* Currency Data Start */
        $currencydata           = Helper::getAllCurrency();             /* get All Currency */
        foreach ($currencydata as $currencyrow) {
            if ($request->input('sales_' . $currencyrow->code)) {
                $salespr = $request->input('sales_' . $currencyrow->code);
            } else {
                $salespr = 0;
            }

            // Fetching JSON
				$req_url = 'https://api.exchangerate-api.com/v4/latest/'.$country_code;
				$response_json = file_get_contents($req_url);
				if(false !== $response_json) {
				    try {

					$regular_EUR = $request->input('regular_'.$country_code);
				    $response_object = json_decode($response_json);

				    $base_price = $regular_EUR; // Your price in base currency based on project
                    $currencyRates = [
                        "BGN" => $response_object->rates->BGN ?? 1, // Set default rate to 1 if rate is not available
                        "GBP" => $response_object->rates->GBP ?? 1,
                        "PLN" => $response_object->rates->PLN ?? 1,
                        "DKK" => $response_object->rates->DKK ?? 1,
                        "NOK" => $response_object->rates->NOK ?? 1,
                        "EUR" => $response_object->rates->EUR ?? 1,
                        "SEK" => $response_object->rates->SEK ?? 1,
                        "HRK" => $response_object->rates->HRK ?? 1,
                        "RON" => $response_object->rates->RON ?? 1,
                        "CZK" => $response_object->rates->CZK ?? 1,
                        "HUF" => $response_object->rates->HUF ?? 1,
                    ];
                    // Check if currency code exists in the array, if not, set the rate to 1
                    $rate = $currencyRates[$currencyrow->code] ?? '';
                    $result_price = round(($base_price * $rate), 2);

						$productPriceInsertData                     = new ProductPrice;
                        $productPriceInsertData->currency_id        = $currencyrow->id;
                        $productPriceInsertData->currency_code      = $currencyrow->code;
                        $productPriceInsertData->currency_name      = $currencyrow->name;
                        $productPriceInsertData->currency_base      = $currencyrow->base_currency;
                        $productPriceInsertData->product_id         = $product_id;
                        $productPriceInsertData->sales_price        = ($salespr) ? $salespr : 0;
                        $productPriceInsertData->regular_price      = $result_price??0;
                        $productPriceInsertData->save();
				    }
				    catch(Exception $e) {
				        // Handle JSON parse error...
				    }
				}

        }

        $attributesId = $request->attr_id;
        $attributesVal = $request->attr_value;
            if(!empty($attributesId) && !empty($attributesVal)){
                ProductAttributes::where('product_id', $product_id)->delete();
                $attributesData = array_combine($attributesId,$attributesVal);
                foreach($attributesData as $key => $val){
                    ProductAttributes::create( [
                        'product_id'=>  $product_id,
                        'attribute_id'=>  $key,
                        'attribute_value'=>  $val
                    ]);
                }
            }
            $v_attr = $request->v_attr_value;
            if(!empty($v_attr)){
                $attr_value_count = count(array_filter($request->attr_value));
                    $v_attr_value_divid = array_chunk($v_attr,$attr_value_count);
                    foreach($v_attr_value_divid as $key => $v_attr_value){
                            $v_attr_str = implode(',',$v_attr_value);
                                    ProductAttributesVariation::create( [
                                        'product_id'=>  $product_id,
                                        'directory_id'=>  auth()->user()->directory_id??0,
                                        'attribute_value'=>  $v_attr_str,
                                        'v_ena'=>  $request->v_ena[$key],
                                        'v_stock'=>  $request->v_stock[$key]
                                    ]);
                        }
                }
        /* Currency Data Ends */
        return redirect('company/products')->with('msg', 'Product Added Successfully!');
    }

    /*
    * Company edit product
    * Created By: Sanjay Sah
    * Created At: 22-03-22
    */
    public function companyedit($id)
    {
        if (Auth::check()) {
            $user = auth()->user();
            if ($user->hasPermission('edit') == false){
                abort(404);
            }
            if (Auth::user()->directory_id == 0 || Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {
                $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
                $data['parent_category']    = Helper::getAllParentProductCategory();      /* get product Category */
                $data['prosubcat']          = Helper::getProductsubCatgoeryData();
                $data['currency']           = Helper::getAllCurrency();             /* get All Currency */
                $data['tagsownDirectory']   = DirectoryCreateOwnTags::where("directory_id", auth()->user()->directory_id)->get(["id", "tag_name"]); /* get All tags */
                $data['VendorSupplierDetail']   = VendorSupplierDetail::where("product_id", $id)->first();
                $main = 'main';
                $galimg = 'galimg';

                $data['productDetails'] = Product::where('deleted', '0')->with('subcat')->with('video')
                    ->with(['image' => function ($q) use ($main) {
                        $q->where('main_image', '=', $main);
                    }])
                    ->with(['gallery' => function ($q) use ($galimg) {
                        $q->where('main_image', '=', $galimg);
                    }])->orderBy('id', 'desc')->findOrFail($id);

                /* product translation data */
                $data['product_translationDesData'] = DB::table('product as p')->select('*')
                    ->join('product_translation as pt', 'p.id', '=', 'pt.product_id')
                    ->where('p.id',  $id)->get();

                $data['product_seo'] = DB::table('product as p')->select('*')
                    ->leftjoin('directory_seo as dso', 'p.id', '=', 'dso.module_id')
                    ->where('p.id',  $id)->get();

                /* product category data */
                $productcategoryData = ProductCategories::select('category_id')->where('product_id', $id)->get()->toArray();
                $data['product_categoryData'] = array_column($productcategoryData, 'category_id');
                $data['productpriceData'] = ProductPrice::select('*')->where('product_id', $id)->get()->toArray();
                $data['pro_subcat']       = Productcategory::where('parent_id',$data['productDetails']->cat_id)->where('status','Active')->orderBy('name', 'ASC')->get()->toArray();

                $language_id = Helper::getselectedLanguage();
                $data['tags'] = DB::table('product_tags')->select('product_tags.id','product_tags.tags','tag_product_translations.title')
                ->join('tag_product_translations', 'product_tags.id', '=', 'tag_product_translations.tag_id')
                ->where('tag_product_translations.language_id', $language_id)
                ->whereIn('product_tags.child_cat_id',$data['product_categoryData'])->where('product_tags.status','Active')->orderBy('product_tags.tags', 'ASC')->get()->toArray();

                $attributesData = Attribute::leftjoin('attribute_statuses', 'attribute_statuses.attribute_id', '=', 'attributes.id')
                ->select('attributes.*','attribute_statuses.status as attributeStatus')
                ->where('attributes.deleted','0')->where('attributes.status', 1)->with(['productAttributes' => function ($q) use ($id) {
                    $q->where('product_id', '=', $id);
                }])->get();

                $attributesVariationData = ProductAttributesVariation::where('product_id', $id)->get();

                $userIP = $this->getIp();
                    $country = '';
                if (in_array($userIP, ['127.0.0.1', '::1', 'localhost'])) {
                    $country = 'Sweden';
                } else {
                    $response = @file_get_contents('http://ip-api.com/php/'.$userIP);
                    if ($response !== false) {
                        $geoData = @unserialize($response);
                        if ($geoData && isset($geoData['country'])) {
                            $country = $geoData['country'];
                        }
                    }
                }
                if (empty($country)) {
                    $country = 'Admin';
                }

                return view('account.company.editproduct', compact('data','attributesData','attributesVariationData','country'));
            }
        } else {
            return abort(404);
        }
    }
    /*
    * Update Company product
    * Created By: Sanjay Sah
    * Created At: 22-03-22
    */
    public function companyupdate(Request $request, $product_id)
    {

        $this->validate($request, [
            'product_name_en'   => 'required|max:50',
            'short_des_en'      => 'max:40',
            'long_des_en'       => 'max:500',
            'gal_image'         => 'array|max:15',
            'opening_date'      => 'required',
            'closing_date'      => 'required'
        ],
        [
            'opening_date.required'    => 'The active date field is required.',
            'closing_date.required'    => 'The expiry date field is required.',
        ]
        );


        $dirId = $request->input('directoryId');

        $dirlocationdetails = DiectoryLocation::where('directory_id', $dirId)->first();
        if (!empty($dirlocationdetails)) {
            if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                $latitude = $dirlocationdetails['latitude'];
                $longitude = $dirlocationdetails['longitude'];
            }else{
                $latitude = $request->input('lati');
                $longitude = $request->input('long');
            }
        }else{
            $latitude = $request->input('lati');
            $longitude = $request->input('long');
        }

        $name_en = $request->input('product_name_en');


        $tagsId = $request->input('vendor_own_tags');
        if ($tagsId) {
            $productTags = array();
            foreach ($tagsId as $tagsrow) {
                $productTags[] = $tagsrow;
            }
        }
        if(!empty($productTags)){
            $tag_id = implode(",", $productTags);
        }else{
            $tag_id = '0';
        }
        if($request->input('default_status')=='yes'){
            $default_month_value     = $request->input('set_months');
        }
        Product::where('id', $product_id)
            ->update([
                'name'                  => (isset($name_en)) ? $name_en : "",
                'product_number_type'   => $request->input('product_number_type'),
                'seo_url'               => Helper::getSeoUrl($name_en),
                'sku'                   => $request->input('sku'),
                'directory_id'          => $request->input('directoryId'),
                'tags'                  => $tag_id,
                'cat_id'                => $request->input('category'),
                'stock'                 => $request->input('stock'),
                'ena'                   => $request->input('ena'),
                'supplier_product_no'   => $request->input('supplier_product_no'),
                'size'                  => $request->input('size'),
                'color'                 => $request->input('color'),
                'closing_date'          => $request->input('closing_date'),
                'opening_date'          => $request->input('opening_date'),
                'default_duration'      => $request->input('default_status'),
                'default_month'         => isset($default_month_value) ? $default_month_value:'',
                'outofstock'            => $request->input('outstock'),
                'in_store_only'            => $request->input('in_store_only'),
                'is_free_product'       => $request->input('is_free_product'),
                'purchase_price'        => $request->input('purchase_price'),
                'video_link'            => $request->input('video_link'),
                //'latitude'              => $latitude,
                //'longitude'             => $longitude,
                'is_visible_on_shop_detailpage' => $request->input('is_visible_on_shop_detailpage') ? 'yes' : 'no',
                'supplier_name'         => $request->input('supplier_name'),
                'popular'               => $request->input('popular')??0,
                'equal_product'         => $request->input('equal_product'),
            ]);

        $short_des_en = $request->input('short_des_en');
        $long_des_en = $request->input('long_des_en');
        $meta_title_en = $request->input('meta_title_en');
        $meta_description_en = $request->input('meta_description_en');

        $is_not_change_en_language = ProductTranslation::where('language_id',1)->where('product_id',$product_id)
        ->where('name',$name_en)
        ->where('short_content', $short_des_en)
        ->where('long_content', $long_des_en)
        ->where('meta_title', $meta_title_en)
        ->where('meta_description', $meta_description_en)
        ->exists();

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();
        if($is_not_change_en_language){

            foreach ($languages as $lang) {
                //dd($lang);
                $product_name                   = $request->input('product_name_' . $lang->code);
                $product_short_content          = $request->input('short_des_' . $lang->code);
                $product_long_content           = $request->input('long_des_' . $lang->code);
                $product_meta_title             = $request->input('meta_title_' . $lang->code);
                $product_meta_description       = $request->input('meta_description_' . $lang->code);
                $languageTranslation_id         = $request->input('langtranlation_' . $lang->code);

                $productshortedit = ProductTranslation::find($languageTranslation_id);
                    $productshortedit->product_id             = $product_id;
                    $productshortedit->language_id            = $lang->id;
                    $productshortedit->name                   = isset($product_name) ? $product_name : "";
                    $productshortedit->short_content          = isset($product_short_content) ? $product_short_content : "";
                    $productshortedit->long_content           = isset($product_long_content) ? $product_long_content : "";
                    $productshortedit->meta_title             = isset($product_meta_title) ? $product_meta_title : "";
                    $productshortedit->meta_description       = isset($product_meta_description) ? $product_meta_description : "";
                    $productshortedit->meta_keyword           = isset($product_short_content) ? $product_short_content : "";
                    $productshortedit->save();
            }

        }else{

            ProductTranslation::where('product_id', $product_id)->delete();
            foreach ($languages as $lang) {

                $product_name               = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
                $product_short_content      = $tr->setSource('en')->setTarget($lang->code)->translate($short_des_en??'');
                $product_long_content       = $tr->setSource('en')->setTarget($lang->code)->translate($long_des_en??'');
                $product_meta_title         = $tr->setSource('en')->setTarget($lang->code)->translate($meta_title_en??'');
                $product_meta_description   = $tr->setSource('en')->setTarget($lang->code)->translate($meta_description_en??'');

                $productshortinsert = new ProductTranslation();
                $productshortinsert->product_id             = $product_id;
                $productshortinsert->language_id            = $lang->id;
                $productshortinsert->name                   = isset($product_name) ? $product_name : $name_en ;
                $productshortinsert->short_content          = isset($product_short_content) ? $product_short_content : $short_des_en;
                $productshortinsert->long_content           = isset($product_long_content) ? $product_long_content : $long_des_en;
                $productshortinsert->meta_title             = isset($product_meta_title) ? $product_meta_title : $meta_title_en;
                $productshortinsert->meta_description       = isset($product_meta_description) ? $product_meta_description : $meta_description_en;
                $productshortinsert->meta_keyword           = isset($product_short_content) ? $product_short_content : $short_des_en;
                $productshortinsert->save();
            }

        }

        $module_id = $request->input('module_id');
        $page_type = $request->input('page_type');
        $product_meta_title = $request->input('meta_title_en');
        $product_meta_description = $request->input('meta_description_en');

        if (!empty($module_id) && (!empty($page_type))) {
            DirectorySeo::where('module_id', $product_id)
                ->update([
                    'page_type'  => 'product',
                    'meta_keyword' => (isset($product_meta_title)) ? $product_meta_title : "",
                    'meta_description' => (isset($product_meta_description)) ? $product_meta_description : ""
            ]);
        }else{
            $dirtseo = new DirectorySeo;
            $dirtseo->module_id     = $product_id;
            $dirtseo->page_type     = 'product';
            $dirtseo->meta_keyword  = (isset($product_meta_title)) ? $product_meta_title : "";
            $dirtseo->meta_description  = (isset($product_meta_description)) ? $product_meta_description : "";
            $dirtseo->save();
        }


        $userIP = $this->getIp();
        $country = '';
        if (in_array($userIP, ['127.0.0.1', '::1', 'localhost'])) {
            $country = 'Sweden';
        } else {
            $response = @file_get_contents('http://ip-api.com/php/'.$userIP);
            if ($response !== false) {
                $geoData = @unserialize($response);
                if ($geoData && isset($geoData['country'])) {
                    $country = $geoData['country'];
                }
            }
        }
        if (empty($country)) {
            $country = 'Admin';
        }

        $countryToCurrency = [
            'Sweden' => 'SEK',
            //GBP
            'United Kingdom' => 'GBP',
            'Guernsey' => 'GBP',
            'Isle of Man' => 'GBP',
            'Jersey' => 'GBP',
            'Saint Helena' => 'GBP',
            //PLN
            'Poland' => 'PLN',
            //DKK
            'Denmark' => 'DKK',
            'Greenland' => 'DKK',
            'Faroe Islands' => 'DKK',
            //NOK
            'Norway' => 'NOK',
            'Quisling regime' => 'NOK',
            //EUR
            'Andorra' => 'EUR',
            'Austria' => 'EUR',
            'Belgium' => 'EUR',
            'Croatia' => 'EUR',
            'Cyprus' => 'EUR',
            'Estonia' => 'EUR',
            'Finland' => 'EUR',
            'France' => 'EUR',
            'Germany' => 'EUR',
            'Greece' => 'EUR',
            'Ireland' => 'EUR',
            'Italy' => 'EUR',
            'Latvia' => 'EUR',
            'Lithuania' => 'EUR',
            'Luxembourg' => 'EUR',
            'Malta' => 'EUR',
            'Monaco' => 'EUR',
            'Montenegro' => 'EUR',
            'Netherlands' => 'EUR',
            'Portugal' => 'EUR',
            'San Marino' => 'EUR',
            'Slovakia' => 'EUR',
            'Slovenia' => 'EUR',
            'Spain' => 'EUR',
            //BGN
            'Bulgaria' => 'BGN',
            //HRK
            'Croatia' => 'HRK',
            //RON
            'Romania' => 'RON',
            //CZK
            'Czech Republic' => 'CZK',
            //HUF
            'Hungary' => 'HUF',
        ];
        $country_code = '';
        if (isset($countryToCurrency[$country])) {
            $country_code = $countryToCurrency[$country];
        }

        /* Currency Data Start */
        ProductPrice::where('product_id', $product_id)->delete();
        $currencydata           = Helper::getAllCurrency();             /* get All Currency */
        foreach ($currencydata as $currencyrow) {

            if ($request->input('sales_' . $currencyrow->code)) {
                $salesprice = $request->input('sales_' . $currencyrow->code);
            } else {
                $salesprice = 0;
            }

            // Fetching JSON
            try {
				$req_url = 'https://api.exchangerate-api.com/v4/latest/'.$country_code;
				$response_json = file_get_contents($req_url);
				if(false !== $response_json) {

					$regular_EUR = $request->input('regular_'.$country_code);
				    $response_object = json_decode($response_json);
				    $base_price = $regular_EUR;
                    $currencyRates = [
                        "BGN" => $response_object->rates->BGN ?? 1,
                        "GBP" => $response_object->rates->GBP ?? 1,
                        "PLN" => $response_object->rates->PLN ?? 1,
                        "DKK" => $response_object->rates->DKK ?? 1,
                        "NOK" => $response_object->rates->NOK ?? 1,
                        "EUR" => $response_object->rates->EUR ?? 1,
                        "SEK" => $response_object->rates->SEK ?? 1,
                        "HRK" => $response_object->rates->HRK ?? 1,
                        "RON" => $response_object->rates->RON ?? 1,
                        "CZK" => $response_object->rates->CZK ?? 1,
                        "HUF" => $response_object->rates->HUF ?? 1,
                    ];
                    // Check if currency code exists in the array, if not, set the rate to 1
                    $rate = $currencyRates[$currencyrow->code] ?? '';
                    $result_price = round(($base_price * $rate), 2);

						$productPriceInsertData                     = new ProductPrice;
                        $productPriceInsertData->currency_id        = $currencyrow->id;
                        $productPriceInsertData->currency_code      = $currencyrow->code;
                        $productPriceInsertData->currency_name      = $currencyrow->name;
                        $productPriceInsertData->currency_base      = $currencyrow->base_currency;
                        $productPriceInsertData->product_id         = $product_id;
                        $productPriceInsertData->sales_price        = ($salesprice) ? $salesprice : 0;
                        $productPriceInsertData->regular_price      = $result_price??0;
                        $productPriceInsertData->save();

				}
            }catch(Exception $e) {
				        // Handle JSON parse error...
			}

        }
        /* Currency Data Ends */

        /* Product Category Starts */
        ProductCategories::where('product_id', $product_id)->delete();
        $categories = $request->input('subproductcat');
        if (!empty($categories)) {
            foreach ($categories as $pro => $value) {
                $productcategoryEditData = new ProductCategories;
                $productcategoryEditData->product_id  = $product_id;
                $productcategoryEditData->category_id  = $value;
                $productcategoryEditData->save();
            }
        }
        /* Product Category Ends */
        $imgfile = $request->file('main_image');
        if ($imgfile) {
            ProductImage::where('product_id', $product_id)->where('main_image', "main")->delete();

            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'images/product/' . $imageName;
                $convertext = "webp";
                $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);
                Storage::disk('images')->put('product/'.$imageName.'.'.$convertext,$image_resize);

            $productmainimage_insert                    = new ProductImage;
            $productmainimage_insert->product_id        = $product_id;
            $productmainimage_insert->main_image        = "main";
            $productmainimage_insert->name_thumbnail    = $imageName;
            $productmainimage_insert->name_large        = $imageName;
            $productmainimage_insert->extension         = $ext;
            $productmainimage_insert->path              = $imagepath;
            $productmainimage_insert->save();
        }

        $imgfilegal = $request->file('gal_image');
        if ($imgfilegal) {
            foreach ($imgfilegal as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                /*
                $imagepath = 'files/product/' . $imageName;
                $image_resize = Image::make($imgrow->getRealPath());
                //$image_resize->resize(700, 700);
                $image_resize->save(public_path('files/product/' .$imageName));
                */
                $imagepath = 'images/product/' . $imageName;
                $convertext = "webp";
                $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,65);
                Storage::disk('images')->put('product/'.$imageName.'.'.$convertext,$image_resize);

                $productmultiimage_insert                    = new ProductImage;
                $productmultiimage_insert->product_id        = $product_id;
                $productmultiimage_insert->main_image        = "galimg";
                $productmultiimage_insert->name_thumbnail    = $imageName;
                $productmultiimage_insert->name_large        = $imageName;
                $productmultiimage_insert->extension         = $ext;
                $productmultiimage_insert->path              = $imagepath;
                $productmultiimage_insert->save();
            }
        }else{
            $thumb_hidden_img = $request->input('thumb_hidden_img');
            $hiddenarray_thumb_hidden_img = explode(',', $thumb_hidden_img);
            $details = ProductImage::where('main_image','galimg')->where('product_id', $product_id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->name_thumbnail = $hiddenarray_thumb_hidden_img[$key];
                    $detail->name_large = $hiddenarray_thumb_hidden_img[$key];
                    $detail->path = 'images/product/'.$hiddenarray_thumb_hidden_img[$key];
                    $detail->save();
                }
            }
        }
        $videofile = $request->file('video');
        if ($videofile) {
            ProductVideo::where('product_id', $product_id)->delete();
            $video_ext = $videofile->getClientOriginalExtension();
            $name2 = $videofile->getClientOriginalName();
            $videoName = time() . $name2;
            $videopath = 'files/productvideo/' . $videoName;
            $videofile->move(public_path('files/productvideo/'), $videoName);
                $productvideo_insert                    = new ProductVideo();
                $productvideo_insert->product_id        = $product_id;
                $productvideo_insert->video_link        = $videoName;
                $productvideo_insert->save();
        }

        $attributesId = $request->attr_id;
        $attributesVal = $request->attr_value;
            if(!empty($attributesId) && !empty($attributesVal)){
                ProductAttributes::whereIn('product_id', [$product_id])->delete();
                $attributesData = array_combine($attributesId,$attributesVal);
                foreach($attributesData as $key => $val){
                    ProductAttributes::create( [
                        'product_id'=>  $product_id,
                        'attribute_id'=>  $key,
                        'attribute_value'=>  $val
                    ]);
                }
            }
        $v_attr = $request->v_attr_value;
        if(!empty($v_attr)){
            $attr_value_count = count(array_filter($request->attr_value));
            ProductAttributesVariation::whereIn('product_id', [$product_id])->delete();
                $v_attr_value_divid = array_chunk($v_attr,$attr_value_count);
                foreach($v_attr_value_divid as $key => $v_attr_value){
                        $v_attr_str = implode(',',$v_attr_value);
                                ProductAttributesVariation::create( [
                                    'product_id'=>  $product_id,
                                    'directory_id'=>  auth()->user()->directory_id ?? 0,
                                    'attribute_value'=>  $v_attr_str,
                                    'v_ena'=>  $request->v_ena[$key] ?? null,
                                    'v_stock'=>  $request->v_stock[$key] ?? null
                                ]);
                    }
            }
        return redirect('company/product-edit/' . $product_id)->with('msg', 'Product has been updated');
    }
    /*
    * Company save directory
    * Created By: Sanjay Sah
    * Created At: 21-03-22
    */
    public function savedirectory(Request $request)
    {
        $name_en = $request->input('name_en');
        $description_en = $request->input('description_en');
        $seo_en = $request->input('seo_en');
        $weoffer_en = $request->input('weoffer_en');
        $aboutus_en = $request->input('aboutus_en');
        $this->validate($request, [
            'name_en'  => 'required',
            'description_en'        => 'required',
            'email'  => 'email|required',
            'name'  => 'required',
            'trademarkcategory'     => 'required',
            'phone_number'  => 'required|numeric|digits:10',
            'listing_thumb'     => 'array|max:20',
            'listing_thumb.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'listing_cover'     => 'array|max:10',
            'listing_cover.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'listing_gallery'   => 'array|max:20',
            'listing_gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            'trademarkcategory.required'    => 'The trademark category field is required.',
            'listing_thumb.*.image' => 'Images must be an extension of JPG, JPEG, PNG, SVG, GIF.',
            'listing_cover.*.image' => 'Images must be an extension of JPG, JPEG, PNG, SVG, GIF.',
            'listing_gallery.*.image' => 'Images must be an extension of JPG, JPEG, PNG, SVG, GIF.',
        ]
        );
        $user_id = Auth::user()->id;
        $directory                          = new Diectory();
        $directory->title                   = (isset($name_en)) ? $name_en : "";
        $directory->slug                   = (isset($seo_en)) ? $seo_en : "";
        $directory->description             = isset($description_en) ? $description_en : "";
        $directory->feature_type            = $request->input('featuretype');
        $directory->google_analytics_id     = $request->input('google_ana_id');
        $directory->claim                   = $request->input('claim');
        $directory->schedule_normal_comment = $request->input('add_normal_comment');
        $directory->created_by              = $request->input('createdby');
        $directory->is_normaltemplate       = $request->input('normaldays');
        $directory->is_normaltemplate_id    = $request->input('normaldaytemplateselect')??0;
        $directory->is_publictemplate       = $request->input('publicdays');
        $directory->is_publictemplate_id    = $request->input('publicdaystemplateselect')??0;
        $directory->is_exceptionaltemplate       = $request->input('exception_days');
        $directory->is_exceptionaltemplate_id    = $request->input('exception_daystemplateselect')??0;
        $directory->popular                       = $request->input('popular')??0;
        $directory->save();
        $direct_id = $directory->id;
        if (!empty($user_id)) {
            $udata = User::find($user_id);
            $udata->directory_id = $direct_id;
            $udata->profile_status = 1;
            $udata->save();
        }
        $packagedata = Plans::where('slug', '=', 'free')->first();
        $planid = $packagedata->id;
        $package_name = $packagedata->name;
        $PlanSubscription = new PlanSubscription();
        $PlanSubscription->subscriber_type    =   'App\Models\User';
        $PlanSubscription->subscriber_id    =   $direct_id;
        $PlanSubscription->plan_id    =   $planid;
        $PlanSubscription->slug     =   Helper::getSeoUrl($package_name);
        $PlanSubscription->name    =   $package_name;
        $PlanSubscription->save();
        $subscriptionid = $PlanSubscription->id;

        $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
            foreach($feature as $val){
                $plansFea = new PlanSubscriptionUsage();
                $plansFea->subscription_id          = $subscriptionid;
                $plansFea->feature_id          = $val->id;
                $plansFea->name          = $val->slug;
                $plansFea->used            = $val->value??0;
                $plansFea->save();
            }
        /*--------------- Directory Category Starts --------------------*/
        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();


        /* Directory Category Starts */
        $categories = $request->input('category');
        $categoryInsertData = new DirectoryCategory;
        $categoryInsertData->directory_id  = $direct_id;
        $categoryInsertData->category_id  = $request->input('category');
        $categoryInsertData->save();

        $sub_categories = $request->input('sub_category');
            if (!empty($sub_categories)) {
                foreach ($sub_categories as $cat => $value) {
                    $categoryInsertData = new DirectorySubCategory;
                    $categoryInsertData->directory_id  = $direct_id;
                    $categoryInsertData->sub_category_id  = $value;
                    $categoryInsertData->save();
                }
            }

        /* Directory Tags Starts */
        $simpletags = $request->input('simpletags');
        if (!empty($simpletags)) {
            foreach ($simpletags as $tgs => $value) {
                $simpletagsInsertData = new DirectoryTags();
                $simpletagsInsertData->directory_id  = $direct_id;
                $simpletagsInsertData->tags_id  = $value;
                $simpletagsInsertData->save();

                $gettagsName = Tags::where('id', $value)->where('status', 'Active')->first();
                if ($gettagsName) {
                    foreach ($languages as $lang) {
                        $title_name = $tr->setSource('en')->setTarget($lang->code)->translate($gettagsName->tags);
                        $description_code = $tr->setSource('en')->setTarget($lang->code)->translate($gettagsName->tags);
                        $title_name = $title_name ?: $gettagsName->tags;
                        $tagtrans = new tagTranslation();
                        $tagtrans->company_id = $direct_id;
                        $tagtrans->tag_id = $value;
                        $tagtrans->category_id = $request->input('category');
                        $tagtrans->language_id = $lang->id;
                        $tagtrans->title = $title_name;
                        $tagtrans->description = $description_code;
                        $tagtrans->seo_url = Helper::getSeoUrl($title_name);
                        $tagtrans->save();
                    }
                }

            }
        }


        /*--------------- Directory own amenity --------------------*/
        $directory_own_amenity = $request->input('directory_own_amenity');
        if ($directory_own_amenity != "") {
            $dir_own_amenity = explode(',', $directory_own_amenity);
            foreach ($dir_own_amenity as $value) {
                $own_amenity = new Amenity();
                $own_amenity->title             = $value;
                $own_amenity->slug              = Str::of($value)->slug('-');
                $own_amenity->created_by        = $request->input('createdby');
                $own_amenity->directory_id      = $direct_id;
                $own_amenity->save();
                $own_amenity_id = $own_amenity->id;

                $dirtamenity = new DirectoryAmenity;
                $dirtamenity->directory_id  = $direct_id;
                $dirtamenity->amenities_id  = $own_amenity_id;
                $dirtamenity->save();
            }
        }

        $directory_services = $request->input('directory_services');
        if ($directory_services != "") {
            $dir_own_services = explode(',', $directory_services);
            foreach ($dir_own_services as $value) {
                $own_service = new DirectoryServices();
                $own_service->directory_id      = $direct_id;
                $own_service->name             = $value;
                $own_service->slug              = Str::of($value)->slug('-');
                $own_service->save();
                $own_amenity_id = $own_service->id;
            }
        }

        /* Directory Trademark Starts */
        $trademarks = $request->input('trademark');
        if (!empty($trademarks)) {
            foreach ($trademarks as $cat => $value) {
                $trademarksInsertData = new DirectoryMediaTrademarks();
                $trademarksInsertData->directory_id  = $direct_id;
                $trademarksInsertData->trademarkcat_id  = $request->input('trademarkcategory');
                $trademarksInsertData->trademark_id  = $value;
                $trademarksInsertData->save();
            }
        }else{
            $trademarksInsertData = new DirectoryMediaTrademarks;
            $trademarksInsertData->directory_id  = $direct_id;
            $trademarksInsertData->trademarkcat_id  = $request->input('trademarkcategory')??'0';
            $trademarksInsertData->trademark_id  = $request->input('trademark')??'0';
            $trademarksInsertData->save();
        }
        /* Directory Trademark Ends */


        /* Directory Tags Ends */
        /* Directory Own Tags Starts */
        $owntags = $request->input('owntags');
        if (!empty($owntags)) {
            foreach ($owntags as $owntgs => $value) {
                $owntagsInsertData = new DirectoryOwnTags();
                $owntagsInsertData->directory_id  = $direct_id;
                $owntagsInsertData->owntags_id  = $value;
                $owntagsInsertData->save();
            }
        }
        $newOwnTagsids = array();
        $createnewowntag = $request->input('createnewowntag');
        if ($createnewowntag != "") {
            $owntagsName = explode(',', $createnewowntag);
            //echo "<pre>"; print_r($tagsName);die;
            foreach ($owntagsName as $tag) {
                $tags_insert = new DirectoryCreateOwnTags();
                $tags_insert->directory_id              = $direct_id;
                $tags_insert->tag_name                  = $tag;
                $tags_insert->add_to_directory_page     = 'yes';
                $tags_insert->created_by        = $request->input('createdby');
                $tags_insert->save();
                $lastenter_id = $tags_insert->id;
                $newOwnTagsids[] = $lastenter_id;
            }
        }

        if (!empty($newOwnTagsids)) {
            foreach ($newOwnTagsids as $owntgs => $value) {
                $owntagsInsertData = new DirectoryNewOwnTags();
                $owntagsInsertData->directory_id  = $direct_id;
                $owntagsInsertData->owntags_id  = $value;
                $owntagsInsertData->save();
            }
        }

        /* Directory Own Tags Ends */
        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        foreach ($languages as $lang) {
            // $title_name         = $request->input('name_' . $lang->code);
            // $description_code   = $request->input('description_' . $lang->code);
            // $seo_val            = $request->input('seo_' . $lang->code);
            // $weoffer_val        = $request->input('weoffer_' . $lang->code);
            // $aboutus_val        = $request->input('aboutus_' . $lang->code);

            $title_name              = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
            $description_code        = $tr->setSource('en')->setTarget($lang->code)->translate($description_en);
            $weoffer_val             = $tr->setSource('en')->setTarget($lang->code)->translate($weoffer_en);
            $aboutus_val             = $tr->setSource('en')->setTarget($lang->code)->translate($aboutus_en);

            $dircttrans = new DiectoryTranslation();
            $dircttrans->directory_id  = $direct_id;
            $dircttrans->language_id  = $lang->id;
            $dircttrans->title        = isset($title_name) ? $title_name : $name_en;
            $dircttrans->description  = isset($description_code) ? $description_code : $description_en;
            $dircttrans->seo_url      = $seo_en;//isset($seo_val) ? $seo_val : $seo_en;
            $dircttrans->we_offer     = isset($weoffer_val) ? $weoffer_val : $weoffer_en;
            $dircttrans->about_us     = isset($aboutus_val) ? $aboutus_val : $aboutus_en;
            $dircttrans->save();
        }
        /* directory Location start */
        $countryId = $request->input('country_id');
        $stateId = $request->input('state_id');
        $cityId = $request->input('city_id');
        $lati = $request->input('latitude');
        $longi = $request->input('longitude');
        $dirctlocation                          = new DiectoryLocation();
        $dirctlocation->directory_id            = $direct_id;
        $dirctlocation->country_id              = (isset($countryId)) ? $countryId : 0;
        $dirctlocation->state_id                = (isset($stateId)) ? $stateId : 0;
        $dirctlocation->city_id                 = (isset($cityId)) ? $cityId : 0;
        $dirctlocation->address                 = $request->input('address');
        $dirctlocation->place                   = $request->input('place');
        $dirctlocation->city_name               = $request->input('city_id');
        $dirctlocation->zipcode                 = $request->input('zipcode');
        $dirctlocation->latitude                = (isset($lati)) ? $lati : 0;
        $dirctlocation->longitude               = (isset($longi)) ? $longi : 0;
        $dirctlocation->save();
        /* directory Location end */




        /* directory Amenities start */

        $amenity = $request->input('amenities');
        if (isset($amenity)) {
            $dirtamenity = new DirectoryAmenity;
            $dirtamenity->directory_id  = $direct_id;
            $dirtamenity->amenitycat_id  = implode(",", $request->input('amenitycat_id'));
            $dirtamenity->amenities_id  = implode(",", $amenity);
            $dirtamenity->save();
        }
        /* directory Amenities END */
        /* directory MEDIA START */
        $payablevalue = $request->input('payimage');
        if ($payablevalue != 'payable') {
            $payval = 0;
        } else {
            $payval = 1;
        }
        $dirtmedia = new DirectoryMedia();
        $dirtmedia->directory_id    =   $direct_id;
        $dirtmedia->payable         =   $payval;
        $dirtmedia->video_provider  =   $request->input('video_provider');
        $dirtmedia->video_url       =   $request->input('videourl');
        $dirtmedia->save();
        if ($request->hasFile('listing_thumb')) {
            $imgfile = $request->file('listing_thumb');
            $limg = 0;
            $hidenarry = $request->input('array_thumb_listing');

            if (!is_array($hidenarry[0])) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
                $hidenarry = [];
            }
            //echo "<pre>";print_r($hidenarry);die;
            foreach ($imgfile as $imgrow) {

                // $ext = $imgrow->getClientOriginalExtension();
                // $name2 = $imgrow->getClientOriginalName();
                // $imageName = time() . $name2;
                // $imagepath = 'files/directory/' . $imageName;
                // $imgrow->move(public_path('files/directory/'), $imageName);

                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                $imagepath = 'images/directory/' . $imageName;
                /*
                $image_resize = Image::make($imgrow->getRealPath());
                //$image_resize->resize(700, 700);
                $image_resize->save(public_path('files/directory/' .$imageName));
                */

                    $convertext = "webp";
                    $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                    Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                $dirtmediathumb = new DirectoryMediaListing();
                $dirtmediathumb->directory_id       = $direct_id;
                $dirtmediathumb->position           = !empty($hidenarry[$limg]) ? $hidenarry[$limg] : 0;
                $dirtmediathumb->name_thumbnail     = $imageName;
                $dirtmediathumb->name_large         = $imageName;
                $dirtmediathumb->extension          = $ext;
                $dirtmediathumb->path               = $imagepath;
                $dirtmediathumb->payable            = $payval;
                $dirtmediathumb->save();
                $limg++;
            }
        }

        if ($request->hasFile('listing_cover')) {
            $imgfile = $request->file('listing_cover');
            $i = 0;
            $hidenarry = $request->input('array_cover_listing');
            if (is_array($hidenarry)) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
            }
            foreach ($imgfile as $imgrow) {

                // $ext = $imgrow->getClientOriginalExtension();
                // $name2 = $imgrow->getClientOriginalName();
                // $imageName = time() . $name2;
                // $imagepath = 'files/directory/' . $imageName;
                // $imgrow->move(public_path('files/directory/'), $imageName);

                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                $imagepath = 'images/directory/' . $imageName;

                $convertext = "webp";
                //$image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                $image_resize = Image::make($imgrow->getRealPath());
                $image_resize->resize(960, 450);
                $image_resize->save(public_path('images/directory/'.$imageName.'.'.$convertext,$image_resize));

                //Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                $dirtmediacover = new DirectoryMediaCover();
                $dirtmediacover->directory_id    = $direct_id;
                $dirtmediacover->position        = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                $dirtmediacover->name_thumbnail  = $imageName;
                $dirtmediacover->name_large      = $imageName;
                $dirtmediacover->extension       = $ext;
                $dirtmediacover->path            = $imagepath;
                $dirtmediacover->payable        = $payval;
                $dirtmediacover->save();
                $i++;
            }
        }
        if ($request->hasFile('listing_gallery')) {

            $imgfilegal = $request->file('listing_gallery');
            $i = 0;
            $hidenarry = $request->input('array_gallery_listing');
            if (is_array($hidenarry)) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
            }
            foreach ($imgfilegal as $imgalrow) {

                // $ext = $imgalrow->getClientOriginalExtension();
                // $name3 = $imgalrow->getClientOriginalName();
                // $imageName = time() . $name3;
                // $imagepath = 'files/directory/' . $imageName;
                // $imgalrow->move(public_path('files/directory/'), $imageName);

                $ext = $imgalrow->getClientOriginalExtension();
                $name3 = $imgalrow->getClientOriginalName();
                $imageName2 = time() . $name3;
                $imageName = Helper::getSeoUrl($imageName2);
                $imagepath = 'images/directory/' . $imageName;
                /*
                $image_resize = Image::make($imgalrow->getRealPath());
                //$image_resize->resize(700, 700);
                $image_resize->save(public_path('files/directory/' .$imageName));
                */

                    $convertext = "webp";
                    $image_resize = Image::make($imgalrow->getRealPath())->stream($convertext,50);
                    Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);


                $dirtmediagallery = new DirectoryMediagallery();
                $dirtmediagallery->directory_id    = $direct_id;
                $dirtmediagallery->position        = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                $dirtmediagallery->name_thumbnail  = $imageName;
                $dirtmediagallery->name_large      = $imageName;
                $dirtmediagallery->extension       = $ext;
                $dirtmediagallery->path            = $imagepath;
                $dirtmediagallery->payable        = $payval;
                $dirtmediagallery->save();
                $i++;
            }
        }
        /* directory MEDIA END */

        /* Seo Entries start */
        $seo_title_en = $request->input('meta_keywords_en');
        $seo_meta_description_en = $request->input('meta_description_en');
        $dirtseo = new DirectorySeo();
        // $dirtseo->directory_id  = $direct_id;
        $dirtseo->module_id  = $direct_id;
        $dirtseo->page_type  = 'directory';
        $dirtseo->meta_keyword  = (isset($seo_title_en)) ? $seo_title_en : "";
        $dirtseo->meta_description  = (isset($seo_meta_description_en)) ? $seo_meta_description_en : "";
        $dirtseo->save();

        foreach ($languages as $lang) {
            $seo_title = $request->input('meta_keywords_' . $lang->code);
            $seo_meta_description = $request->input('meta_description_' . $lang->code);
            $dircttrans = new DirectorySeoTranslation();
            $dircttrans->directory_id  = $direct_id;
            $dircttrans->language_id  = $lang->id;
            $dircttrans->meta_title        = $seo_title ?? "";
            $dircttrans->meta_keyword        = isset($seo_title) ? $seo_title : "";
            $dircttrans->meta_description  = isset($seo_meta_description) ? $seo_meta_description : "";
            $dircttrans->save();
        }
        /* Seo Entries End */

        if ($request->input('normaldays') == "no") {
            /* START SCHEDUAL ENTRIES */
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $key => $value) {
                $count = count($request->input($value . "_hours_min"));
                $minHours = $request->input($value . "_hours_min");
                $maxHours = $request->input($value . "_hours_max");
                for ($i = 0; $i < $count; $i++) {
                    $dirctschedual = new DirectorySchedual();
                    $dirctschedual->directory_id    = $direct_id;
                    $dirctschedual->day             = $value;
                    $dirctschedual->is_closed       = $request->input($value . "_is_closed") ? '1' : '0';
                    $dirctschedual->open_time       = $minHours[$i];
                    $dirctschedual->close_time      = $maxHours[$i];
                    $dirctschedual->save();
                }
            }
        }

        if ($request->input('normaldays') == "yes") {
            /* START SCHEDUAL ENTRIES */
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $key => $value) {
                //dd($request->input($value . "_template_hours_min"));

                if(!empty($request->input($value . "_template_hours_min"))){
                    $count = count($request->input($value . "_template_hours_min"));
                    $minHours = $request->input($value . "_template_hours_min");
                    $maxHours = $request->input($value . "_template_hours_max");
                    for ($i = 0; $i < $count; $i++) {
                        $dirctschedual = new DirectorySchedual;
                        $dirctschedual->directory_id    = $direct_id;
                        $dirctschedual->day             = $value;
                        $dirctschedual->is_closed       = $request->input($value . "_template_is_closed") ? '1' : '0';
                        $dirctschedual->open_time       = $minHours[$i];
                        $dirctschedual->close_time      = $maxHours[$i];
                        $dirctschedual->save();
                    }
                }
            }
        }

        if ($request->input('publicdays') == "no") {
            /* Holiday time */
            $custom_date = $request->input('custom_date');
            $note = $request->input('note');
            $time_hours_min = $request->input('time_hours_min');
            $time_hours_max = $request->input('time_hours_max');
            $final_custom_time = [];
            if (count($time_hours_min)) {
                for ($i = 0; $i < count($time_hours_min); $i++) {
                    $arr['open_time'] = $time_hours_min[$i];
                    $arr['close_time'] = $time_hours_max[$i];
                    $final_custom_time[] = $arr;
                }
            }
            $final_custom_time = json_encode($final_custom_time);
            $dirctcustom_holiday = new DirectoryCustomHoliday();
            $dirctcustom_holiday->directory_id = $direct_id;
            $dirctcustom_holiday->custom_date = $custom_date;
            $dirctcustom_holiday->note = $note;
            $dirctcustom_holiday->custom_time = $final_custom_time;
            $dirctcustom_holiday->save();
        }

        if ($request->input('publicdays') == "yes") {
            $ids = $request->input('template_id');
            if(count($ids)){
                foreach($ids as $val) {
                    $time_hours_min = $request->input($val.'_temp_time_hours_min');
                    $time_hours_max = $request->input($val.'_temp_time_hours_max');
                    $final_custom_time = [];
                    if (count($time_hours_min)) {
                        for ($j = 0; $j < count($time_hours_min); $j++) {
                            $arr['open_time'] = $time_hours_min[$j];
                            $arr['close_time'] = $time_hours_max[$j];
                            $final_custom_time[] = $arr;
                        }
                    }
                    $final_custom_time = json_encode($final_custom_time);
                    $dirctcustom_holiday = new DirectoryCustomHoliday;
                    $dirctcustom_holiday->directory_id = $direct_id;
                    $dirctcustom_holiday->custom_date = $request->input($val.'_custom_date');
                    $dirctcustom_holiday->note = $request->input($val.'_note');
                    $dirctcustom_holiday->custom_time = $final_custom_time;
                    $dirctcustom_holiday->save();
                }
            }

        }
        if ($request->input('exception_days') == "no") {
            /* Holiday time */
            $custom_date = $request->input('exception_date');
            $note = $request->input('exception_note');
            $edit_exceptional_day = $request->input('edit_exceptional_day');

            $time_hours_min = $request->input('exception_time_hours_min');
            $time_hours_max = $request->input('exception_time_hours_max');
            $final_custom_time = [];
            if (count($time_hours_min)) {
                for ($i = 0; $i < count($time_hours_min); $i++) {
                    $arr['open_time'] = $time_hours_min[$i];
                    $arr['close_time'] = $time_hours_max[$i];
                    $final_custom_time[] = $arr;
                }
            }
            $template_exceptional_is_closed = $request->input('template_exceptional_is_closed')??'No';
            $final_custom_time = json_encode($final_custom_time);
            $dirctcustom_holiday = new DirectoryExceptionHoliday;
            $dirctcustom_holiday->directory_id = $direct_id;
            $dirctcustom_holiday->custom_date = $custom_date;
            $dirctcustom_holiday->note = $note;
            $dirctcustom_holiday->day = $edit_exceptional_day;
            $dirctcustom_holiday->closed = $template_exceptional_is_closed;
            $dirctcustom_holiday->custom_time = $final_custom_time;
            $dirctcustom_holiday->save();
        }
        if ($request->input('exception_days') == "yes") {
            $ids = $request->input('exception_template_id');
            if(count($ids)){
                foreach($ids as $val) {
                    $time_hours_min = $request->input($val.'_exc_time_hours_min');
                    $time_hours_max = $request->input($val.'_exc_time_hours_max');
                    $final_custom_time = [];
                    if (count($time_hours_min)) {
                        for ($j = 0; $j < count($time_hours_min); $j++) {
                            $arr['open_time'] = $time_hours_min[$j];
                            $arr['close_time'] = $time_hours_max[$j];
                            $final_custom_time[] = $arr;
                        }
                    }
                    $final_custom_time = json_encode($final_custom_time);
                    $dirctexception_holiday = new DirectoryExceptionHoliday;
                    $dirctexception_holiday->directory_id = $direct_id;
                    $dirctexception_holiday->custom_date = $request->input($val.'_exception_date');
                    $dirctexception_holiday->note = $request->input($val.'_exception_note');
                    $dirctexception_holiday->day = $request->input($val.'_exception_day');
                    $dirctexception_holiday->closed = $request->input($val.'_exception_closed');
                    $dirctexception_holiday->custom_time = $final_custom_time;
                    $dirctexception_holiday->save();
                }
            }

        }
        /* END SCHEDUAL ENTRIES */

        /* TYPE ENTRIES START */
        $catid = $request->input('hotel_type');
        if ($catid != "") {
            $coulnmname = $this->gettypeCoulmnName($catid);
            //echo "<pre>";print_r($coulnmname);die;
            $block_number = $request->input('block_number') ? $request->input('block_number') : 0;
            for ($i = 0; $i <= $block_number; $i++) {
                foreach ($coulnmname as $rowdata) {
                    $dirctType = new DirectoryType();
                    $dirctType->directory_id    =   $direct_id;
                    $dirctType->category_id     =   $request->input('hotel_type');
                    $dirctType->input_type      =   $rowdata->type;
                    $dirctType->column_name     =   $rowdata->name;
                    $dirctType->column_value    =   $request->input($rowdata->name . "_" . $i);
                    $dirctType->label           =   $rowdata->label;
                    $dirctType->block_num       =   $i;
                    $dirctType->save();
                }
            }
        }
        /* TYPE ENTRIES END */

        /* Contact Entries start */
        $dirctContact = new DirectoryContact();
        $dirctContact->directory_id     = $direct_id;
        $dirctContact->name             = $request->input('name');
        $dirctContact->website          = $request->input('website');
        $dirctContact->email            = $request->input('email');
        $dirctContact->phone            = $request->input('phone_number');
        $dirctContact->facebook         = $request->input('facebook');
        $dirctContact->twitter          = $request->input('twitter');
        $dirctContact->linkedin         = $request->input('linkedin');
        $dirctContact->pinterest        = $request->input('pinterest');
        $dirctContact->whatsapp         = $request->input('whatsapp');
        $dirctContact->instagram        = $request->input('instagram');
        $dirctContact->tiktok           = $request->input('tiktok');
        $dirctContact->youtube          = $request->input('youtube');
        $dirctContact->snapchat         = $request->input('snapchat');
        $dirctContact->save();

        $website_number_count = $request->input('website_count') ? $request->input('website_count') : 1;
        $firstrow = $request->input("website_name_1");
        for ($i = 1; $i <= $website_number_count; $i++) {
            if ($firstrow) {
                $websitelink = new Websitelinks();
                $websitelink->directory_id    =   $direct_id;
                $websitelink->name            =   $request->input("website_name_" . $i);
                $websitelink->link            =   $request->input("website_link_" . $i);
                $websitelink->save();
            }
        }

        $assortment_number_count = $request->input('assort_count') ? $request->input('assort_count') : 1;
        $firstrowass = $request->input("assortment_name_1");
        for ($i = 1; $i <= $assortment_number_count; $i++) {
            if ($firstrowass) {
                $assortmentlink = new Assortmentlinks();
                $assortmentlink->directory_id    =   $direct_id;
                $assortmentlink->name            =   $request->input("assortment_name_" . $i);
                $assortmentlink->link            =   $request->input("assortment_link_" . $i);
                $assortmentlink->save();
            }
        }
        /* Contact Entries end */
        /* ------- Claim Entries  START -----*/
        // $claimSubject = $request->input('claim_subject');
        // $claimdescription = $request->input('claim_description');
        // $claiminsert = array_combine($claimSubject,$claimdescription);
        // foreach($claiminsert as $subject => $description){
            if($request->input("claim_subject")!=''){
                $ClaimInsert = new Claim();
                $ClaimInsert->directory_id          =   $direct_id;
                $ClaimInsert->subject               =   $request->input("claim_subject");
                $ClaimInsert->description           =   $request->input("claim_description");
                $ClaimInsert->save();
            }
        //}
        //echo "<pre>"; print_r($claiminsert);die;
        /* ------- Claim Entries  END -----*/
        $activeTab = strtolower($request->input('active_tab'));
        //echo $activeTab;die;
        if($activeTab!=""){
            return redirect('company/reports/dashboard')->with('msg', 'Company has been Added');
        }else{
            return redirect('company/reports/dashboard')->with('msg', 'Company has been Added');
        }
    }

    function gettypeCoulmnName($id)
    {
        $gettypevalueData = DB::table('category_configurations')->select('fields')->where('category_id', $id)->first();

        $getfeildstypevalueData = DB::table('category_fields')->select('*')->whereIn('id', explode(',', $gettypevalueData->fields??''))->get();
        return $getfeildstypevalueData;
    }


    public function importHolidayTempName(Request $request){

        $tempname = new HolidayTemplateName();
        $tempname->name     = $request->input('public_template_file_name');
        $tempname->directory_id     = $request->input('directoryId');
        $tempname->created_by   = $request->input('createdby');
        $res = $tempname->save();
        $templateid = $tempname->id;
        if ($res) {
            return response()->json(['status' => true, 'message' => 'Created successfull.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
        }
    }

    public function addExceptionalHolidayTempName(Request $request){

        $tempname = new ExceptionalHolidayTemplateName();
        $tempname->name     = $request->input('exceptional_template_file_name');
        $tempname->directory_id     = $request->input('directoryId');
        $tempname->created_by   = $request->input('createdby');
        $res = $tempname->save();
        if ($res) {
            return response()->json(['status' => true, 'message' => 'Created successfull.']);
        } else {
            return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
        }
    }

    public function importHolidaypublic(Request $request)
    {
        $file = $request->file('public_file'); // Get the uploaded file
        $directoryId = $request->directoryId; // Retrieve the template ID
        Excel::import(new VendorPublicHolidayImport($directoryId), $file);
        return back();
    }

    public function importHolidayExceptional(Request $request)
    {
        $file = $request->file('exceptional_file'); // Get the uploaded file
        $directoryId = $request->directoryId; // Retrieve the template ID
        Excel::import(new VendorExceptionalHolidayImport($directoryId), $file);
        return back();
    }

    /*
    * Company update directory info
    * Created By: Sanjay Sah
    * Created At: 21-10-24
    */
    public function uploadCompanyCoverImage(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('update')) {
            return response()->json(['message' => 'You do not have permission to upload cover images.'], 403);
        }

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,bmp,webp|max:10240',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);

        $directoryId = $user->directory_id;
        if (!Diectory::where('id', $directoryId)->exists()) {
            return response()->json(['message' => 'Company not found.'], 404);
        }

        $imageName = (string) Str::uuid();
        $storagePath = 'directory/' . $imageName . '.webp';
        $fileStored = false;

        try {
            $imageStream = Image::make($request->file('image')->getRealPath())
                ->resize(960, 450)
                ->stream('webp', 50);
            if (!Storage::disk('images')->put($storagePath, $imageStream)) {
                throw new \RuntimeException('The image could not be saved.');
            }
            $fileStored = true;

            $lastPosition = DirectoryMediaCover::where('directory_id', $directoryId)->max('position');
            $coverImage = new DirectoryMediaCover();
            $coverImage->directory_id = $directoryId;
            $coverImage->position = $lastPosition === null ? 0 : $lastPosition + 1;
            $coverImage->name_thumbnail = $imageName;
            $coverImage->name_large = $imageName;
            $coverImage->extension = strtolower($request->file('image')->getClientOriginalExtension());
            $coverImage->path = 'images/directory/' . $imageName;
            $coverImage->payable = DirectoryMediaCover::where('directory_id', $directoryId)->value('payable') ?? 0;
            $coverImage->title = $validated['title'];
            $coverImage->description = $validated['description'];
            $coverImage->save();

            return response()->json([
                'message' => 'Image uploaded successfully.',
                'image' => [
                    'title' => $coverImage->title,
                    'description' => $coverImage->description,
                ],
            ], 201);
        } catch (\Throwable $exception) {
            if ($fileStored) {
                try {
                    if (!Storage::disk('images')->delete($storagePath)) {
                        Log::warning('Could not remove an uploaded cover image after a failed database save.', [
                            'directory_id' => $directoryId,
                            'image' => $imageName,
                        ]);
                    }
                } catch (\Throwable $cleanupException) {
                    Log::warning('Could not remove an uploaded cover image after a failed database save.', [
                        'directory_id' => $directoryId,
                        'image' => $imageName,
                        'exception' => $cleanupException,
                    ]);
                }
            }

            Log::error('Company cover image upload failed.', [
                'directory_id' => $directoryId,
                'exception' => $exception,
            ]);

            return response()->json(['message' => 'The image could not be uploaded. Please try again.'], 500);
        }
    }

    public function uploadCompanyGalleryImageWithText(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('update')) {
            return response()->json(['message' => 'You do not have permission to upload gallery images.'], 403);
        }

        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,bmp,webp|max:10240',
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
        ]);

        $directoryId = $user->directory_id;
        if (!Diectory::where('id', $directoryId)->exists()) {
            return response()->json(['message' => 'Company not found.'], 404);
        }

        $imageName = (string) Str::uuid();
        $storagePath = 'directory/' . $imageName . '.webp';
        $fileStored = false;

        try {
            $imageStream = Image::make($request->file('image')->getRealPath())->stream('webp', 50);
            if (!Storage::disk('images')->put($storagePath, $imageStream)) {
                throw new \RuntimeException('The image could not be saved.');
            }
            $fileStored = true;

            $lastPosition = DirectoryMediagallery::where('directory_id', $directoryId)->max('position');
            $galleryImage = new DirectoryMediagallery();
            $galleryImage->directory_id = $directoryId;
            $galleryImage->position = $lastPosition === null ? 0 : $lastPosition + 1;
            $galleryImage->name_thumbnail = $imageName;
            $galleryImage->name_large = $request->file('image')->getClientOriginalName();
            $galleryImage->extension = strtolower($request->file('image')->getClientOriginalExtension());
            $galleryImage->path = 'images/directory/' . $imageName;
            $galleryImage->payable = DirectoryMedia::where('directory_id', $directoryId)->value('payable') ?? 0;
            $galleryImage->title = $validated['title'];
            $galleryImage->description = $validated['description'];
            $galleryImage->save();

            return response()->json([
                'message' => 'Gallery image uploaded successfully.',
                'image' => [
                    'title' => $galleryImage->title,
                    'description' => $galleryImage->description,
                ],
            ], 201);
        } catch (\Throwable $exception) {
            if ($fileStored) {
                try {
                    if (!Storage::disk('images')->delete($storagePath)) {
                        Log::warning('Could not remove an uploaded gallery image after a failed database save.', [
                            'directory_id' => $directoryId,
                            'image' => $imageName,
                        ]);
                    }
                } catch (\Throwable $cleanupException) {
                    Log::warning('Could not remove an uploaded gallery image after a failed database save.', [
                        'directory_id' => $directoryId,
                        'image' => $imageName,
                        'exception' => $cleanupException,
                    ]);
                }
            }

            Log::error('Company gallery image upload failed.', [
                'directory_id' => $directoryId,
                'exception' => $exception,
            ]);

            return response()->json(['message' => 'The gallery image could not be uploaded. Please try again.'], 500);
        }
    }

    public function updatecompanyinformation(Request $request)
    {

        $id = Auth::user()->directory_id;
        $user_id = Auth::user()->id;

        $this->validate($request, [
            'name_en'               => 'required|string',
            'description_en'        => 'required|string',
            'seo_en'                => 'required|string',
            'weoffer_en'            => 'required|string',
            'aboutus_en'            => 'required|string',
            'email'                 => 'email|required',
            'trademarkcategory'     => 'required',
            'phone_number'          => 'required|numeric|digits_between:8,10',
            'listing_thumb'         => 'array|max:20',
            'listing_cover'         => 'array|max:20',
            'listing_gallery'       => 'array|max:20',
            'paypal_email'          => 'required',
        ],
        [
            'trademarkcategory.required'    => 'The trademark category field is required.',
        ]
        );
        $is_normaltemplate = $request->input('normaldays');
        $is_publictemplate = $request->input('publicdays');
        if ($is_normaltemplate == 'yes') {

            DirectorySchedual::where('directory_id', $id)->delete();
        }

        $name_en = $request->input('name_en');
        $seo_en = $request->input('seo_en');
        $activeTab = $request->input('active_tab');

        //dd($activeTab);

        $description_en = $request->input('description_en');
        $weoffer_en = $request->input('weoffer_en');
        $aboutus_en = $request->input('aboutus_en');


        $publicdaystemplateselect = $request->input('publicdaystemplateselect');
        if($publicdaystemplateselect == 'none'){
            $publicdaystemplateselect = $request->input('publicdaystemplateselect_admin');
        }else{
            $publicdaystemplateselect = $request->input('publicdaystemplateselect');
        }

        $exceptiondaystemplateselect = $request->input('exception_daystemplateselect');
        if($exceptiondaystemplateselect == 'none'){
            $exception_daystemplateselect = $request->input('exception_daystemplateselect_admin');
        }else{
            $exception_daystemplateselect = $request->input('exception_daystemplateselect');
        }

        Diectory::where('id', $id)
            ->update([
                'title'                         => $name_en ? $name_en : "",
                'slug'                          => $seo_en ? $seo_en : "",
                'description'                   => isset($description_en) ? $description_en : "",
                'feature_type'                  => $request->input('featuretype'),
                'company_color'                 => $request->input('company_color'),
                'google_analytics_id'           => $request->input('google_ana_id'),
                'is_normaltemplate'             => $request->input('normaldays'),
                'is_normaltemplate_id'          => $request->input('normaldaytemplateselect'),
                'is_publictemplate'             => $request->input('publicdays'),
                'is_publictemplate_id'          => $publicdaystemplateselect,
                'is_exceptionaltemplate'        => $request->input('exception_days'),
                'is_exceptionaltemplate_id'     => $exception_daystemplateselect,
                'claim'                         => $request->input('claim'),
                'schedule_normal_comment'       => $request->input('add_normal_comment'),
                'popular'                       => $request->input('popular')??0,
                'holiday_status'                => $request->input('holiday_status')??'normal_holiday',
            ]);

        /* Directory Category Starts */
        $categories = $request->input('sub_category');
        DirectoryCategory::where('directory_id', $id)->delete();
                $categoryInsertData = new DirectoryCategory;
                $categoryInsertData->directory_id  = $id;
                $categoryInsertData->category_id  = $request->input('category');
                $categoryInsertData->save();


        /*--------------- Directory own amenity --------------------*/
        $directory_own_amenity = $request->input('directory_own_amenity');
        Amenity::where('directory_id', $id)->delete();
        DirectoryAmenity::where('directory_id', $id)->delete();

        if ($directory_own_amenity != "") {
            $dir_own_amenity = explode(',', $directory_own_amenity);

            foreach ($dir_own_amenity as $value) {
                $own_amenity = new Amenity();
                $own_amenity->title             = $value;
                $own_amenity->slug              = Str::of($value)->slug('-');
                //$own_amenity->category_id       = $request->input('amenitycat_id');
                $own_amenity->created_by        = $request->input('createdby');
                $own_amenity->directory_id      = $id;
                $own_amenity->save();
                $own_amenity_id = $own_amenity->id;

                   $dirtamenity = new DirectoryAmenity;
                   $dirtamenity->directory_id  = $id;
                   //$dirtamenity->amenitycat_id  = $request->input('amenitycat_id');
                   $dirtamenity->amenities_id  = $own_amenity_id;
                   $dirtamenity->typeofdata  = 'new';
                   $dirtamenity->save();

            }
        }

        $directory_services = $request->directory_services;
        DirectoryServices::where('directory_id', $id)->delete();
        if ($directory_services != "") {
            $dir_own_services = explode(',', $directory_services);

            foreach ($dir_own_services as $value) {
                $own_service = new DirectoryServices();
                $own_service->directory_id      = $id;
                $own_service->name              = $value;
                $own_service->slug              = Str::of($value)->slug('-');
                $own_service->save();
                $own_amenity_id = $own_service->id;
            }
        }

        /* Directory Category Ends */
        /* Directory Trademark Starts */
        $trademark = $request->input('trademark');
        DirectoryMediaTrademarks::where('directory_id', $id)->delete();
        if (!empty($trademark)) {
            foreach ($trademark as $cat => $value) {
                $trademarkInsertData                    = new DirectoryMediaTrademarks;
                $trademarkInsertData->directory_id      = $id;
                $trademarkInsertData->trademarkcat_id   = $request->input('trademarkcategory')??'0';
                $trademarkInsertData->trademark_id      = $value;
                $trademarkInsertData->save();
            }
        }else{
                $trademarkInsertData = new DirectoryMediaTrademarks;
                $trademarkInsertData->directory_id      = $id;
                $trademarkInsertData->trademarkcat_id   = $request->input('trademarkcategory')??'0';
                $trademarkInsertData->trademark_id      = $request->input('trademark')??'0';
                $trademarkInsertData->save();
        }
        /* Directory Trademark Ends */


        if($request->active_tab == 'SEO'){

            $sub_categories = $request->input('sub_category');
            DirectorySubCategory::where('directory_id', $id)->delete();
            DirectoryTags::where('directory_id', $id)->delete();
            tagTranslation::where('company_id', $id)->delete();

            $languages = Helper::getAllLanguages();
            $tr = new GoogleTranslate();
            if (!empty($sub_categories)) {
                foreach ($sub_categories as $cat => $value) {
                    $categoryInsertData = new DirectorySubCategory;
                    $categoryInsertData->directory_id  = $id;
                    $categoryInsertData->sub_category_id  = $value;
                    $categoryInsertData->save();

                }
            }

            /* Directory Tags Starts */
            $simpletags = $request->input('simpletags');
            //dd($id);
            if (!empty($simpletags)) {
                foreach ($simpletags as $value) {
                    $simpletagsInsertData = new DirectoryTags();
                    $simpletagsInsertData->directory_id = $id;
                    $simpletagsInsertData->tags_id = $value;
                    $simpletagsInsertData->save();
                    $gettagsName = Tags::where('id', $value)->where('status', 'Active')->first();
                    if ($gettagsName) {
                        foreach ($languages as $lang) {
                            $title_name = $tr->setSource('en')->setTarget($lang->code)->translate($gettagsName->tags);
                            $description_code = $tr->setSource('en')->setTarget($lang->code)->translate($gettagsName->tags);
                            $title_name = $title_name ?: $gettagsName->tags;
                            $tagtrans = new tagTranslation();
                            $tagtrans->company_id = $id;
                            $tagtrans->tag_id = $value;
                            $tagtrans->category_id = $request->input('category');
                            $tagtrans->language_id = $lang->id;
                            $tagtrans->title = $title_name;
                            $tagtrans->description = $description_code;
                            $tagtrans->seo_url = Helper::getSeoUrl($title_name);
                            $tagtrans->save();
                        }
                    }

                }
            }

        }

        /* Directory Tags Ends */
        /* Directory Own Tags Starts */
        $owntags = $request->input('owntags');
        DirectoryOwnTags::where('directory_id', $id)->delete();
        if (!empty($owntags)) {
            foreach ($owntags as $owntgs => $value) {
                $owntagsInsertData = new DirectoryOwnTags;
                $owntagsInsertData->directory_id  = $id;
                $owntagsInsertData->owntags_id  = $value;
                $owntagsInsertData->save();
            }
        }


        $newOwnTagsids = array();
        $createnewowntag = $request->input('createnewowntag');
        if ($createnewowntag != "") {
            $owntagsName = explode(',', $createnewowntag);
            foreach ($owntagsName as $tag) {
                $tags_insert = new DirectoryCreateOwnTags;
                $tags_insert->directory_id              = $id;
                $tags_insert->tag_name                  = $tag;
                $tags_insert->add_to_directory_page     = 'yes';
                $tags_insert->created_by        = $request->input('createdby');
                $tags_insert->save();
                $lastenter_id = $tags_insert->id;
                $newOwnTagsids[] = $lastenter_id;
            }
        }

        if (!empty($newOwnTagsids)) {
            foreach ($newOwnTagsids as $owntgs => $value) {
                $owntagsInsertData = new DirectoryNewOwnTags;
                $owntagsInsertData->directory_id  = $id;
                $owntagsInsertData->owntags_id  = $value;
                $owntagsInsertData->save();
            }
        }
         /* Directory Own Tags Ends */

        $offerdescription = $request->input('description_en');
        $weoffer_en        = $request->input('weoffer_en');
        $aboutus_en  = $request->input('aboutus_en');

        $is_not_change_en_language = DiectoryTranslation::where('language_id',1)
            ->where('title',$name_en)
            ->where('description',$offerdescription)
            ->where('we_offer',$weoffer_en)
            ->where('about_us',$aboutus_en)
            ->where('directory_id',$id)->exists();

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();

            if($is_not_change_en_language){
                foreach ($languages as $lang) {


                    $title_name = $request->input('name_' . $lang->code);
                    $description_code = $request->input('description_' . $lang->code);
                    $languageTranslation_id = $request->input('langtranlation_' . $lang->code);
                    $seo_val = Helper::getSeoUrl($request->input('seo_' . $lang->code));
                    $weoffer_val = $request->input('weoffer_' . $lang->code);
                    $aboutus_val = $request->input('aboutus_' . $lang->code);

                    $offershortinsert =  DiectoryTranslation::find($languageTranslation_id);
                    $offershortinsert->title              = isset($title_name) ? $title_name : $name_en;
                    $offershortinsert->description        = isset($description_code) ? $description_code : $offerdescription;
                    $offershortinsert->seo_url            = $seo_val;
                    $offershortinsert->we_offer           = isset($weoffer_val) ? $weoffer_val : $weoffer_en;
                    $offershortinsert->about_us           = isset($aboutus_val) ? $aboutus_val : $aboutus_en;
                    $offershortinsert->save();
                }
            }else{

                DiectoryTranslation::where('directory_id', $id)->delete();
                foreach ($languages as $lang) {

                    if ($lang->code !== 'en') {
                        $name        = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
                        $description = $tr->setSource('en')->setTarget($lang->code)->translate($offerdescription);
                        $seo_en         = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('seo_en'));
                        $weofferen      = $tr->setSource('en')->setTarget($lang->code)->translate($weoffer_en);
                        $aboutusen      = $tr->setSource('en')->setTarget($lang->code)->translate($aboutus_en);
                    }

                    DiectoryTranslation::create([
                        'directory_id'     => $id,
                        'language_id'      => $lang->id,
                        'title'            => $name ?? $name_en,
                        'description'      => $description ?? $offerdescription,
                        'seo_url'          => $seo_en ?? $request->input('seo_en'),
                        'we_offer'         => $weofferen ?? $weoffer_en,
                        'about_us'         => $aboutusen ?? $aboutus_en,
                    ]);
                }

            }

        DiectoryLocation::where('directory_id', $id)
            ->update([
                'country_id'    => $request->input('country_id') ? $request->input('country_id') : 0,
                'state_id'      => $request->input('state_id') ? $request->input('state_id') : 0,
                'city_id'       => $request->input('city_id') ? $request->input('city_id') : 0,
                'address'       => $request->input('address'),
                'place'         => $request->input('place'),
                'city_name'     => $request->input('city_id'),
                'zipcode'       => $request->input('zipcode'),
                'latitude'      => $request->input('latitude'),
                'longitude'     => $request->input('longitude')
            ]);

            Product::where('directory_id', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);
            ShoppingCard::where('directory_link', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);
            Offer::where('offer_directory_link', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);
            Blog::where('directory_id', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);
            Events::where('directory_id', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);

            User::where('id', $user_id)
            ->update([
                'paypal_email'            => $request->input('paypal_email'),
                'paypal_merchant_id'      => $request->input('paypal_merchant_id'),
            ]);

            if ($request->input('amenities')) {
                    $dirtamenity = new DirectoryAmenity;
                    $dirtamenity->directory_id  = $id;
                    $dirtamenity->amenitycat_id  = implode(",", $request->input('amenitycat_id'));
                    $dirtamenity->amenities_id  = implode(",", $request->input('amenities'));
                    $dirtamenity->typeofdata  = 'existing';
                    $dirtamenity->save();
            }

        /* MEDIA EDIT START */
        $payablevalue = $request->input('payimage');
        if ($payablevalue != 'payable') {
            $payval = 0;
        } else {
            $payval = 1;
        }
        DirectoryMedia::where('directory_id', $id)
            ->update([
                'video_provider'  => $request->input('video_provider'),
                'video_url'  => $request->input('videourl'),
                'payable'  => $payval,
            ]);
        if ($request->input('payimage') == "payable") {
            DirectoryMediaListing::where(['directory_id' => $id, 'payable' => 0])->delete();
            DirectoryMediaCover::where(['directory_id' => $id, 'payable' => 0])->delete();
            DirectoryMediagallery::where(['directory_id' => $id, 'payable' => 0])->delete();
        } else {
            DirectoryMediaListing::where(['directory_id' => $id, 'payable' => 1])->delete();
            DirectoryMediaCover::where(['directory_id' => $id, 'payable' => 1])->delete();
            DirectoryMediagallery::where(['directory_id' => $id, 'payable' => 1])->delete();
        }

        if ($request->hasFile('company_logo')) {
            $company_logo = $request->file('company_logo');
            $ext = strtolower($company_logo->getClientOriginalExtension());

            $allowed = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'jfif'];

            if (!in_array($ext, $allowed)) {
                return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
            }

            $imageName = Helper::getSeoUrl(time() . pathinfo($company_logo->getClientOriginalName(), PATHINFO_FILENAME));
            $imagePath = "images/directory/{$imageName}.webp";

            $image = Image::make($company_logo->getRealPath())->stream('webp', 50);
            Storage::disk('images')->put("directory/{$imageName}.webp", $image);

            Diectory::where('id', $id)->update(['company_logo' => $imagePath]);
        }


        if ($request->hasFile('listing_thumb')) {
            if ($request->input('payimage') != "payable") {
                DirectoryMediaListing::where(['directory_id' => $id, 'payable' => 0])->delete();
            }
            $imgfile = $request->file('listing_thumb');
            $i = 0;
            $hidenarry = $request->input('array_thumb_listing_new');
            if (!is_array($hidenarry[0])) {
                $hidenarry = explode(',', $hidenarry[0]);
            }

            foreach ($imgfile as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $allowed =  array('jpeg','jpg', "png", "gif", "bmp", "jfif", "JPEG","JPG", "PNG", "GIF", "BMP", "JFIF");
                    if(!in_array($ext,$allowed) ) {
                        return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
                    } else {
                        $imageName2 = time() . $name2;
                        $imageName = Helper::getSeoUrl($imageName2);
                        $imagepath = 'images/directory/' . $imageName;
                        /*
                        //$imgrow->move(public_path('files/directory/'), $imageName);
                        $image_resize = Image::make($imgrow->getRealPath());
                        //$image_resize->resize(700, 700);
                        $image_resize->save(public_path('files/directory/' .$imageName));
                        */

                        $convertext = "webp";
                        $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                        Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                        $dirtmediathumb = new DirectoryMediaListing;
                        $dirtmediathumb->directory_id    = $id;
                        $dirtmediathumb->position        = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                        $dirtmediathumb->name_thumbnail  = $imageName;
                        $dirtmediathumb->name_large      = $imageName;
                        $dirtmediathumb->extension       = $ext;
                        $dirtmediathumb->path            = $imagepath;
                        $dirtmediathumb->payable        = $payval;
                        $dirtmediathumb->save();
                        $i++;
                    }

            }
        } else {

            $hidenarrythumb = $request->input('array_thumb_listing_new');
            if (!is_array($hidenarrythumb[0])) {
                $hidenarrythumb = explode(',', $hidenarrythumb[0]);
            }
            $thumb_hidden_img = $request->input('thumb_hidden_img');
            $hiddenarray_thumb_hidden_img = explode(',', $thumb_hidden_img);
            $details = DirectoryMediaListing::where('directory_id', $id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->position = $hidenarrythumb[$key];
                    $detail->name_thumbnail = $hiddenarray_thumb_hidden_img[$key];
                    $detail->name_large = $hiddenarray_thumb_hidden_img[$key];
                    $detail->save();
                }
            }
        }

        if ($request->hasFile('listing_cover')) {
            if ($request->input('payimage') != "payable") {
                DirectoryMediaCover::where(['directory_id' => $id, 'payable' => 0])->delete();
            }
            $imgfile = $request->file('listing_cover');
            $i = 0;
            $hidenarry = $request->input('array_cover_listing');
            if (!is_array($hidenarry[0])) {
                $hidenarry = explode(',', $hidenarry[0]);
            }
            foreach ($imgfile as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $allowed =  array('jpeg','jpg', "png", "gif", "bmp", "jfif", "JPEG","JPG", "PNG", "GIF", "BMP", "JFIF");
                   if(!in_array($ext,$allowed) ) {
                        return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
                    } else {
                        $imageName2 = time() . $name2;
                        $imageName = Helper::getSeoUrl($imageName2);
                        $imagepath = 'images/directory/' . $imageName;
                        $convertext = "webp";
                        $image_resize = Image::make($imgrow->getRealPath());
                        $image_resize->resize(960, 450);
                        $image_resize->save(public_path('images/directory/'.$imageName.'.'.$convertext,$image_resize));

                        $dirtmediacover = new DirectoryMediaCover;
                        $dirtmediacover->directory_id    = $id;
                        $dirtmediacover->position        = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                        $dirtmediacover->name_thumbnail  = $imageName;
                        $dirtmediacover->name_large      = $imageName;
                        $dirtmediacover->extension       = $ext;
                        $dirtmediacover->path            = $imagepath;
                        $dirtmediacover->payable         = $payval;
                        $dirtmediacover->save();
                        $i++;
                    }
            }
        } else {
            $hidenarr = $request->input('array_cover_listing');
            $hidenarry = explode(',', $hidenarr[0]);
            $hidenarrimg = $request->input('array_cover_images');
            $hidenarryimages = explode(',', $hidenarrimg);
            $details = DirectoryMediaCover::where('directory_id', $id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->position = $hidenarry[$key];
                    $detail->name_thumbnail = $hidenarryimages[$key];
                    $detail->name_large = $hidenarryimages[$key];
                    $detail->save();
                }
            }
        }

        if ($request->hasFile('listing_gallery')) {

            $imgfilegal = $request->file('listing_gallery');
            $i = 0;
            $hidenarry = $request->input('array_gallery_listing');
            if (!is_array($hidenarry[0])) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
            }

            foreach ($imgfilegal as $imgalrow) {

                $ext = $imgalrow->getClientOriginalExtension();
                $name3 = $imgalrow->getClientOriginalName();

                $allowed =  array('jpeg','jpg', "png", "gif", "bmp", "jfif", "JPEG","JPG", "PNG", "GIF", "BMP", "JFIF", "webp");
                   if(!in_array($ext,$allowed) ) {
                        return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
                    } else {

                        $is_images_exist = DirectoryMediagallery::where('name_large',$name3)->where('directory_id',$id)->first();
                        if($is_images_exist){}else{

                            $imageName2 = time() . $name3;
                            $imageName = Helper::getSeoUrl($imageName2);
                            $imagepath = 'images/directory/' . $imageName;
                            $convertext = "webp";
                            $image_resize = Image::make($imgalrow->getRealPath())->stream($convertext,50);
                            Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                            $dirtmediagallery = new DirectoryMediagallery;
                            $dirtmediagallery->directory_id    = $id;
                            $dirtmediagallery->position        = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                            $dirtmediagallery->name_thumbnail  = $imageName;
                            $dirtmediagallery->name_large      = $name3;
                            $dirtmediagallery->extension       = $ext;
                            $dirtmediagallery->path            = $imagepath;
                            $dirtmediagallery->payable        = $payval;
                            $dirtmediagallery->save();
                            $i++;
                        }
                    }
            }
        } else {

            $hidenarrygallery = $request->input('array_gallery_listing');
            if (!is_array($hidenarrygallery[0])) {
                $hidenarrygall = explode(',', $hidenarrygallery[0]);
            }
            $hidenarrGalleryimg = $request->input('array_gallery_images');
            $hiddenarray_gallery_images = explode(',', $hidenarrGalleryimg);
            ///dd($hiddenarray_gallery_images);
            $details = DirectoryMediagallery::where('directory_id', $id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->position = $hidenarrygall[$key] ?? '';
                    $detail->name_thumbnail = $hiddenarray_gallery_images[$key] ?? '';
                    $detail->save();
                }
            }

        }
        /* MEDIA EDIT END */
        /* Seo Entries start */
        $seo_title_en = $request->input('meta_keywords_en');
        $seo_meta_description_en = $request->input('meta_description_en');
        DirectorySeo::where('module_id', $id)
            ->update([
                'page_type'    =>'directory',
                'meta_keyword' => (isset($seo_title_en)) ? $seo_title_en : "",
                'meta_description' => isset($seo_meta_description_en) ? $seo_meta_description_en : "",
            ]);

        // foreach ($languages as $lang) {
        //     $seo_title = $request->input('meta_keywords_' . $lang->code);
        //     $seo_meta_description = $request->input('meta_description_' . $lang->code);
        //     $seoTranslation_id = $request->input('seolangtranlation_' . $lang->code);
        //     $seodircttrans =  DirectorySeoTranslation::find($seoTranslation_id);
        //     $seodircttrans->meta_title        = $seo_title ?? "";
        //     $seodircttrans->meta_keyword        = $seo_title ?? "";
        //     $seodircttrans->meta_description  = $seo_meta_description ?? "";
        //     $seodircttrans->save();
        // }

        foreach ($languages as $lang) {

            $seo_title = $request->input('meta_keywords_' . $lang->code);
            $seo_meta_description = $request->input('meta_description_' . $lang->code);
            $seoTranslation_id = $request->input('seolangtranlation_' . $lang->code);

            $seodircttrans = DirectorySeoTranslation::find($seoTranslation_id);

            if ($seodircttrans) {
                // Update existing record
                $seodircttrans->meta_title = $seo_title ?? "";
                $seodircttrans->meta_keyword = $seo_title ?? "";
                $seodircttrans->meta_description = $seo_meta_description ?? "";
            } else {
                // Create new record (important fix)
                $seodircttrans = new DirectorySeoTranslation();
                $seodircttrans->directory_id = $id; // make sure this exists in table
                $seodircttrans->language_id = $lang->id; // adjust column name if needed
                $seodircttrans->meta_title = $seo_title ?? "";
                $seodircttrans->meta_keyword = $seo_title ?? "";
                $seodircttrans->meta_description = $seo_meta_description ?? "";
            }

            $seodircttrans->save();
        }

        /* Seo Entries End */
        /* START SCHEDUAL ENTRIES */
        DirectorySchedual::where('directory_id', $id)->delete();
        if ($request->input('normaldays') == "no") {
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $key => $value) {
                $count = count($request->input($value . "_hours_min"));
                $minHours = $request->input($value . "_hours_min");
                $maxHours = $request->input($value . "_hours_max");
                for ($i = 0; $i < $count; $i++) {
                    $dirctschedual = new DirectorySchedual;
                    $dirctschedual->directory_id    = $id;
                    $dirctschedual->day             = $value;
                    $dirctschedual->is_closed       = $request->input($value . "_is_closed") ? '1' : '0';
                    $dirctschedual->open_time       = $minHours[$i];
                    $dirctschedual->close_time      = $maxHours[$i];
                    $dirctschedual->save();
                }
            }
        }
        if ($request->input('normaldays') == "yes") {
            /* START SCHEDUAL ENTRIES */
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $key => $value) {
                if(!empty($request->input($value . "_template_hours_min"))){
                    $count = count($request->input($value . "_template_hours_min"));
                    $minHours = $request->input($value . "_template_hours_min");
                    $maxHours = $request->input($value . "_template_hours_max");
                    for ($i = 0; $i < $count; $i++) {
                        $dirctschedual = new DirectorySchedual;
                        $dirctschedual->directory_id    = $id;
                        $dirctschedual->day             = $value;
                        $dirctschedual->is_closed       = $request->input($value . "_template_is_closed") ? '1' : '0';
                        $dirctschedual->open_time       = $minHours[$i];
                        $dirctschedual->close_time      = $maxHours[$i];
                        $dirctschedual->save();
                    }
                }
            }
        }
        /* HOLIDAY TIME ADD */

        //DirectoryCustomHoliday::where('directory_id', $id)->delete();
        if ($request->input('publicdays') == "no") {
            if($request->input('publicdays')!=$request->input('actualdataforyesno') )
            DirectoryCustomHoliday::where('directory_id', $id)->delete();
            if ($request->input('editDataPublic') == "one") {
                $edit_tempalte_real_id           = $request->input('public_real_id');
                $edit_template_custom_date       = $request->input('edit_public_custom_date');
                $edit_template_note              = $request->input('edit_public_note');
                $edit_template_day               = $request->input('edit_day');
                $edit_template_closed            = $request->input('template_holiday_is_closed');

                    for ($i = 0; $i < count($edit_template_custom_date); $i++) {

                        $edit_template_final_custom_time = [];
                        $edit_time_hours_min    = $request->input($edit_tempalte_real_id[$i].'_edit_public_notemplate_time_hours_min');
                        $edit_time_hours_max    = $request->input($edit_tempalte_real_id[$i].'_edit_public_notemplate_time_hours_max');
                        for ($j = 0; $j < count($edit_time_hours_min); $j++) {
                            $arr['open_time']           = $edit_time_hours_min[$j];
                            $arr['close_time']          = $edit_time_hours_max[$j];
                            $edit_template_final_custom_time[]   = $arr;
                        }
                        DirectoryCustomHoliday::where('id', $edit_tempalte_real_id[$i])
                        ->update([
                            'custom_date'       => $edit_template_custom_date[$i],
                            'closed'            => $edit_template_closed[$i]??'No',
                            'note'              => $edit_template_note[$i],
                            'day'               => $edit_template_day[$i],
                            'custom_time'       => json_encode($edit_template_final_custom_time),
                        ]);
                    }
            }
            if ($request->input('custom_date') != "") {
                $custom_date                    = $request->input('custom_date');
                $note                           = $request->input('note');
                $add_template_day               = $request->input('day');
                $add_template_closed            = $request->input('closed');
                $time_hours_min                 = $request->input('time_hours_min');
                $time_hours_max                 = $request->input('time_hours_max');
                $final_custom_time = [];
                if (count($time_hours_min)) {
                    for ($i = 0; $i < count($time_hours_min); $i++) {
                        $arr['open_time'] = $time_hours_min[$i];
                        $arr['close_time'] = $time_hours_max[$i];
                        $final_custom_time[] = $arr;
                    }
                }
                $final_custom_time = json_encode($final_custom_time);
                $dirctcustom_holiday = new DirectoryCustomHoliday;
                $dirctcustom_holiday->directory_id = $id;
                $dirctcustom_holiday->custom_date = $custom_date;
                $dirctcustom_holiday->day = $add_template_day;
                $dirctcustom_holiday->note = $note;
                $dirctcustom_holiday->closed = $add_template_closed;
                $dirctcustom_holiday->custom_time = $final_custom_time;
                $dirctcustom_holiday->save();
            }
        }

        if ($request->input('publicdays') == "yes") {
            if ($request->input('editDataTemplatepublic') != "one") {
                if($request->input('publicdaystemplateselect')!=$request->input('actualdataforselectedTemplateId')){
                    DirectoryCustomHoliday::where('directory_id', $id)->delete();
                }
            }
            $sele_ids = $request->input('template_id');
            $ids = ($sele_ids!="")?$sele_ids:($ids=array());
            if(count($ids)){
                foreach($ids as $val) {
                    $time_hours_min = $request->input($val.'_temp_time_hours_min');
                    $time_hours_max = $request->input($val.'_temp_time_hours_max');
                    $final_custom_time = [];
                    if (is_array($time_hours_min) && count($time_hours_min) > 0) {
                    //if (count($time_hours_min)) {
                        for ($j = 0; $j < count($time_hours_min); $j++) {
                            $arr['open_time'] = $time_hours_min[$j];
                            $arr['close_time'] = $time_hours_max[$j];
                            $final_custom_time[] = $arr;
                        }
                    }
                    $final_custom_time = json_encode($final_custom_time);
                    $dirctcustom_holiday = new DirectoryCustomHoliday;
                    $dirctcustom_holiday->directory_id = $id;
                    $dirctcustom_holiday->custom_date = $request->input($val.'_custom_date');
                    $dirctcustom_holiday->closed = $request->input($val.'_temp_holiday_closed');
                    $dirctcustom_holiday->note = $request->input($val.'_note');
                    $dirctcustom_holiday->day = $request->input($val.'_day');
                    $dirctcustom_holiday->custom_time = $final_custom_time;
                    $dirctcustom_holiday->save();
                }
            }

            if ($request->input('editDataTemplatepublic') == "one") {
                $edit_tempalte_real_id           = $request->input('public_template_real_id');
                $edit_template_custom_date       = $request->input('edit_public_template_custom_date');
                $edit_template_note              = $request->input('edit_public_template_note');
                $edit_template_day               = $request->input('edit_day');
                $edit_template_holiday_is_closed = $request->input('template_holiday_is_closed');

                    for ($i = 0; $i < count($edit_template_custom_date); $i++) {

                        $edit_template_final_custom_time = [];
                        $edit_time_hours_min    = $request->input($edit_tempalte_real_id[$i].'_edit_public_time_hours_min');
                        $edit_time_hours_max    = $request->input($edit_tempalte_real_id[$i].'_edit_public_time_hours_max');
                        for ($j = 0; $j < count($edit_time_hours_min); $j++) {
                            $arr['open_time']           = $edit_time_hours_min[$j];
                            $arr['close_time']          = $edit_time_hours_max[$j];
                            $edit_template_final_custom_time[]   = $arr;
                        }
                        //dd($edit_template_day[$i]);
                        DirectoryCustomHoliday::where('id', $edit_tempalte_real_id[$i])
                        ->update([
                            'custom_date'       => $edit_template_custom_date[$i],
                            'note'              => $edit_template_note[$i],
                            'day'               => $edit_template_day[$i],
                            'custom_time'       => json_encode($edit_template_final_custom_time),
                            'closed'            => $edit_template_holiday_is_closed[$i]??'No',
                        ]);
                    }
            }
        }

        if ($request->input('exception_days') == "no") {
            if($request->input('exception_days')!=$request->input('exeActualdataforyesno') )
            DirectoryExceptionHoliday::where('directory_id', $id)->delete();
            if ($request->input('editDataexception') == "one") {
                $edit_real_id           = $request->input('real_id');
                $edit_custom_date       = $request->input('edit_custom_date');
                $edit_note              = $request->input('edit_note');
                $edit_exceptional_day       = $request->input('edit_exceptional_day');
                $edit_exceptional_is_closed = $request->input('template_exceptional_is_closed');

                    for ($i = 0; $i < count($edit_custom_date); $i++) {
                        $edit_final_custom_time = [];
                        $edit_time_hours_min    = $request->input($edit_real_id[$i].'_edit_time_hours_min');
                        $edit_time_hours_max    = $request->input($edit_real_id[$i].'_edit_time_hours_max');
                        for ($j = 0; $j < count($edit_time_hours_min); $j++) {
                            $arr['open_time']           = $edit_time_hours_min[$j];
                            $arr['close_time']          = $edit_time_hours_max[$j];
                            $edit_final_custom_time[]   = $arr;
                        }
                        DirectoryExceptionHoliday::where('id', $edit_real_id[$i])
                        ->update([
                            'custom_date'       => $edit_custom_date[$i],
                            'note'              => $edit_note[$i],
                            'day'               => $edit_exceptional_day[$i],
                            'closed'            => $edit_exceptional_is_closed[$i],
                            'custom_time'       => json_encode($edit_final_custom_time),
                        ]);
                    }
            }
            if ($request->input('exception_date') != "") {
                $custom_date = $request->input('exception_date');
                $note = $request->input('exception_note');
                $exception_day = $request->input('exception_day');
                $exception_closed = $request->input('exception_closed');
                $time_hours_min = $request->input('exception_time_hours_min');
                $time_hours_max = $request->input('exception_time_hours_max');
                $final_custom_time = [];
                if (count($time_hours_min)) {
                    for ($i = 0; $i < count($time_hours_min); $i++) {
                        $arr['open_time'] = $time_hours_min[$i];
                        $arr['close_time'] = $time_hours_max[$i];
                        $final_custom_time[] = $arr;
                    }
                }
                $final_custom_time = json_encode($final_custom_time);
                $dirctcustom_holiday = new DirectoryExceptionHoliday;
                $dirctcustom_holiday->directory_id = $id;
                $dirctcustom_holiday->custom_date = $custom_date;
                $dirctcustom_holiday->note = $note;
                $dirctcustom_holiday->day = $exception_day;
                $dirctcustom_holiday->closed = $exception_closed;
                $dirctcustom_holiday->custom_time = $final_custom_time;
                $dirctcustom_holiday->save();
            }
        }

        if ($request->input('exception_days') == "yes") {
            if($request->input('exception_daystemplateselect')!=$request->input('exeActualdataforselectedTemplateId'))
            {
                DirectoryExceptionHoliday::where('directory_id', $id)->delete();
            }
            $sele_ids = $request->input('exception_template_id');
            $ids = ($sele_ids!="")?$sele_ids:($ids=array());
            if(count($ids)){
                foreach($ids as $val) {
                    $time_hours_min = $request->input($val.'_exc_time_hours_min');
                    $time_hours_max = $request->input($val.'_exc_time_hours_max');
                    $final_custom_time = [];
                    if (count($time_hours_min)) {
                        for ($j = 0; $j < count($time_hours_min); $j++) {
                            $arr['open_time'] = $time_hours_min[$j];
                            $arr['close_time'] = $time_hours_max[$j];
                            $final_custom_time[] = $arr;
                        }
                    }
                    $final_custom_time = json_encode($final_custom_time);
                    $dirctexception_holiday = new DirectoryExceptionHoliday;
                    $dirctexception_holiday->directory_id = $id;
                    $dirctexception_holiday->custom_date = $request->input($val.'_exception_date');
                    $dirctexception_holiday->closed = $request->input($val.'_exception_closed')??'No';
                    $dirctexception_holiday->day = $request->input($val.'_exception_day');
                    $dirctexception_holiday->note = $request->input($val.'_exception_note');
                    $dirctexception_holiday->custom_time = $final_custom_time;
                    $dirctexception_holiday->save();
                }
            }
            if ($request->input('editDataTemplateexception') == "one") {
                $edit_tempalte_real_id           = $request->input('template_real_id');
                $edit_template_custom_date       = $request->input('edit_template_custom_date');
                $edit_template_note              = $request->input('edit_template_note');

                    for ($i = 0; $i < count($edit_template_custom_date); $i++) {
                        $edit_template_final_custom_time = [];
                        $edit_time_hours_min    = $request->input($edit_tempalte_real_id[$i].'_edit_time_hours_min');
                        $edit_time_hours_max    = $request->input($edit_tempalte_real_id[$i].'_edit_time_hours_max');
                        for ($j = 0; $j < count($edit_time_hours_min); $j++) {
                            $arr['open_time']           = $edit_time_hours_min[$j];
                            $arr['close_time']          = $edit_time_hours_max[$j];
                            $edit_template_final_custom_time[]   = $arr;
                        }
                        DirectoryExceptionHoliday::where('id', $edit_tempalte_real_id[$i])
                        ->update([
                            'custom_date'       => $edit_template_custom_date[$i],
                            'note'              => $edit_template_note[$i],
                            'custom_time'       => json_encode($edit_template_final_custom_time),
                        ]);
                    }
            }
        }
        /* END SCHEDUAL ENTRIES */



        /* Contact Entries start */
        DirectoryContact::where('directory_id', $id)
            ->update([
                'name'          => $request->input('name'),
                'website'       => $request->input('website'),
                'email'         => $request->input('email'),
                'phone'         => $request->input('phone_number'),
                'facebook'      => $request->input('facebook'),
                'twitter'       => $request->input('twitter'),
                'linkedin'      => $request->input('linkedin'),
                'pinterest'     => $request->input('pinterest'),
                'whatsapp'      => $request->input('whatsapp'),
                'instagram'     => $request->input('instagram'),
                'tiktok'        => $request->input('tiktok'),
                'youtube'       => $request->input('youtube'),
                'snapchat'      => $request->input('snapchat')
            ]);
        Websitelinks::where('directory_id', $id)->delete();
        $website_number_count = $request->input('website_count') ? $request->input('website_count') : 1;
        $firstrow = $request->input("website_name_1");
        $j = 1;
        for ($i = 1; $i <= $website_number_count; $i++) {
            $websitelink = new Websitelinks;
            $websitelink->directory_id    =   $id;
            $websitelink->name            =   $request->input("website_name_" . $j);
            $websitelink->link            =   $request->input("website_link_" . $j);
            $websitelink->save();
            $j++;
        }
        Assortmentlinks::where('directory_id', $id)->delete();
        $assortment_number_count = $request->input('assort_count') ? $request->input('assort_count') : 1;
        $firstrowass = $request->input("assortment_name_1");
        for ($i = 1; $i <= $assortment_number_count; $i++) {
            if ($firstrowass) {
                $assortmentlink = new Assortmentlinks;
                $assortmentlink->directory_id    =   $id;
                $assortmentlink->name            =   $request->input("assortment_name_" . $i);
                $assortmentlink->link            =   $request->input("assortment_link_" . $i);
                $assortmentlink->save();
            }
        }
        /* Contact Entries end */
        /* ------- Claim Entries  START -----*/
            if($request->input("claim_subject")!=''){
                $ClaimInsert = new Claim();
                $ClaimInsert->directory_id          =   $id;
                $ClaimInsert->subject         =   $request->input("claim_subject");
                $ClaimInsert->description     =   $request->input("claim_description");
                $ClaimInsert->save();
            }
        /* ------- Claim Entries  END -----*/

        /* TYPE ENTRIES START */
        $catid = $request->input('hotel_type');
        if ($catid != "") {

            $coulnmname = $this->gettypeCoulmnName($catid);
            if ($request->input('selected_cat_id') != $catid) {
                $insertedBlockCount = "novalue";
            } else {
                $insertedBlockCount = $request->input('block_number_from_database') ? $request->input('block_number_from_database') : "";
            }
            $newBlockCount = $request->input('block_number') ? $request->input('block_number') : 0;

            if ($request->input('selected_cat_id') == $catid) {

                $countDirectoryTypeGallery = DirectoryTypeGallery::where('directory_id', $id)->where('block_num', $newBlockCount)->where('category_id', $catid)->count();
                $gallery_image_available = 'gallery_image';
                        $gallery_imgfile = $request->file($gallery_image_available);
                        if (is_array($gallery_imgfile)) {

                            foreach ($gallery_imgfile as $key => $gallery_imgfile2){
                                $position_key = $countDirectoryTypeGallery+1;
                                foreach ($gallery_imgfile2 as  $imgfile) {
                                    if($imgfile){
                                        $ext = $imgfile->getClientOriginalExtension();
                                        $name2 = $imgfile->getClientOriginalName();
                                        $imageName2 = time() . $name2;
                                        $imageName = Helper::getSeoUrl($imageName2);
                                        $image_resize = Image::make($imgfile->getRealPath());
                                        $image_resize->save(public_path('files/directorytypeimage/' .$imageName.'.'.$ext));

                                        $dirtTypegallery = new DirectoryTypeGallery;
                                        $dirtTypegallery->directory_id = $id;
                                        $dirtTypegallery->category_id = $catid;
                                        $dirtTypegallery->image_name = $imageName.'.'.$ext;
                                        $dirtTypegallery->block_num = $key;
                                        $dirtTypegallery->position = $position_key++;
                                        $dirtTypegallery->save(); // Save the image details to the database
                                    }

                                }
                            }
                        }

                $countDirectoryTypePdfs = DirectoryTypePdfs::where('directory_id', $id)->where('block_num', $newBlockCount)->where('category_id', $catid)->count();
                $gallery_pdf_available = 'gallery_type_pdf';
                        $gallery_pdf_file = $request->file($gallery_pdf_available);
                        if (is_array($gallery_pdf_file)) {
                            foreach ($gallery_pdf_file as $key => $gallery_pdf_file2){
                                $position_key = $countDirectoryTypePdfs+1;
                                foreach ($gallery_pdf_file2 as  $pdf_file) {
                                    if($pdf_file){
                                        $ext = $pdf_file->getClientOriginalExtension();
                                        if ($ext === 'pdf') {
                                            $name2 = $pdf_file->getClientOriginalName();
                                            $pdfName = time().'_'.$name2;
                                            $pdf_file->move(public_path('files/directorytypeimage/'), $pdfName);

                                            $dirtTypegallery = new DirectoryTypePdfs;
                                            $dirtTypegallery->directory_id = $id;
                                            $dirtTypegallery->category_id = $catid;
                                            $dirtTypegallery->pdf_name = $pdfName;
                                            $dirtTypegallery->block_num = $key;
                                            $dirtTypegallery->position = $position_key++;
                                            $dirtTypegallery->save(); // Save the image details to the database
                                        }
                                    }
                                }
                            }
                        }

                for ($i = 0; $i <= $insertedBlockCount; $i++) {
                    foreach ($coulnmname as $rowdata) {

                        $imageavailable = 'image_'.$i;
                        if($request->hasFile($imageavailable)){
                            $imgfile        = $request->file($imageavailable);
                            $ext            = $imgfile->getClientOriginalExtension();
                            $name2          = $imgfile->getClientOriginalName();
                            $imageName2     = time() . $name2;
                            $imageName      = Helper::getSeoUrl($imageName2);
                            $image_resize   = Image::make($imgfile->getRealPath());
                            $image_resize->save(public_path('files/directorytypeimage/' .$imageName));
                            DirectoryType::where('directory_id', $id)->where('category_id', $catid)->where('block_num', $i)
                            ->update(['image_name' => $imageName]);
                        }


                        // try {
                        //     $galleryTypePdfInputName = 'gallery_type_pdf_' . $i;
                        //     if ($request->hasFile($galleryTypePdfInputName)) {
                        //         $file = $request->file($galleryTypePdfInputName);
                        //         $ext = $file->getClientOriginalExtension();

                        //         if ($ext === 'pdf') {
                        //             $name2 = $file->getClientOriginalName();
                        //             $fileName = time().'_'.$name2;
                        //             //$fileName = Helper::getSeoUrl($fileName);
                        //             $file->move(public_path('files/directorytypeimage/'), $fileName);

                        //             DirectoryType::where('directory_id', $id)
                        //                 ->where('category_id', $catid)
                        //                 ->where('block_num', $i)
                        //                 ->update(['pdf_doc' => $fileName]);
                        //         } else {
                        //             $name2 = $file->getClientOriginalName();
                        //             $imageName2 = time() . $name2;
                        //             $imageName = Helper::getSeoUrl($imageName2);
                        //             $image_resize = Image::make($file->getRealPath());
                        //             $image_resize->save(public_path('files/directorytypeimage/' . $imageName));

                        //             DirectoryType::where('directory_id', $id)
                        //                 ->where('category_id', $catid)
                        //                 ->where('block_num', $i)
                        //                 ->update(['pdf_doc' => $imageName]);
                        //         }
                        //     }
                        // } catch (Exception $e) {

                        // }


                        DirectoryType::where('directory_id', $id)->where('column_name', $rowdata->name)->where('block_num', $i)
                            ->update([
                                'category_id'       => $request->input('hotel_type'),
                                'column_name'       => $rowdata->name,
                                'column_value'      => $request->input($rowdata->name . "_" . $i),
                                'label'             => $rowdata->label,
                                'block_num'         => $i
                            ]);
                    }
                }
            }

            if ($insertedBlockCount < $newBlockCount || $insertedBlockCount == "novalue") {

                if ($insertedBlockCount != "novalue") {
                    for ($i = (int)$insertedBlockCount + 1; $i <= $newBlockCount; $i++) {
                        foreach ($coulnmname as $rowdatanew) {

                            $imgfile    = $request->file('image_'.$i);
                            if ($imgfile) {
                                $ext        = $imgfile->getClientOriginalExtension();
                                $name2      = $imgfile->getClientOriginalName();
                                $imageName2 = time() . $name2;
                                $imageName  = Helper::getSeoUrl($imageName2);
                                $imagepath  = 'files/directorytypeimage/' . $imageName;
                                $image_resize = Image::make($imgfile->getRealPath());
                                $image_resize->save(public_path('files/directorytypeimage/' .$imageName));

                                $dirctType = new DirectoryType;
                                $dirctType->directory_id    =   $id;
                                $dirctType->category_id     =   $request->input('hotel_type');
                                $dirctType->input_type      =   $rowdatanew->type;
                                $dirctType->column_name     =   $rowdatanew->name;
                                $dirctType->column_value    =   $request->input($rowdatanew->name . "_" . $i);
                                $dirctType->label           =   $rowdatanew->label;
                                $dirctType->block_num       =   $i;
                                $dirctType->image_name      =   $imageName;
                                $dirctType->save();
                            }


                        }
                    }
                } else {
                    if ($request->input('selected_cat_id') != $catid) {
                        DirectoryType::where('directory_id', $id)->where('category_id', $request->input('selected_cat_id'))->delete();
                    }
                    for ($i = 0; $i <= $newBlockCount; $i++) {
                        foreach ($coulnmname as $rowdatanew) {

                            $imgfile    = $request->file('image_'.$i);
                            $ext        = $imgfile->getClientOriginalExtension();
                            $name2      = $imgfile->getClientOriginalName();
                            $imageName2 = time() . $name2;
                            $imageName  = Helper::getSeoUrl($imageName2);
                            $imagepath  = 'files/directorytypeimage/' . $imageName;
                            $image_resize = Image::make($imgfile->getRealPath());
                            $image_resize->save(public_path('files/directorytypeimage/' .$imageName));

                            $dirctType1 = new DirectoryType;
                            $dirctType1->directory_id       =   $id;
                            $dirctType1->category_id        =   $request->input('hotel_type');
                            $dirctType1->input_type         =   $rowdatanew->type;
                            $dirctType1->column_name        =   $rowdatanew->name;
                            $dirctType1->column_value       =   $request->input($rowdatanew->name . "_" . $i);
                            $dirctType1->label              =   $rowdatanew->label;
                            $dirctType1->block_num          =   $i;
                            $dirctType1->image_name         =   $imageName;
                            $dirctType1->save();
                        }
                    }
                }
            }

        }
        /* TYPE ENTRIES END */


        $activeTab = strtolower($activeTab);
        return redirect('company-information?target=' . $activeTab)->with('msg', "Company's Information Updated");
    }


    public function getAmenity(Request $request)
    {
        $dataamenity = array();
        $amenityCatName = array();
        $i=0;
        foreach($request->amcat_id as $value){
            $amenityCatName[] = AmenityCategory::where("id", $value)->get(["name"]);
            $dataamenity[$amenityCatName[$i][0]->name] = Amenity::where("category_id", $value)->get(["icon_class", "id", "title", "category_id"]);
            $i++;
        }
        return response()->json($dataamenity);
    }


    public function importHolidayTempInner(Request $request)
    {
        $file = $request->file('public_file'); // Get the uploaded file
        $templateId = $request->template_id; // Retrieve the template ID
        Excel::import(new HolidayTempImportInner($templateId), $file);
        return back();
    }

    public function importExceptionalHolidayTempInner(Request $request)
    {
        $file = $request->file('public_file'); // Get the uploaded file
        $templateId = $request->template_id; // Retrieve the template ID
        Excel::import(new HolidayExceptionalTempImportInner($templateId), $file);
        return back();
    }


    /*
    * Company edit company info
    * Created By: Sanjay Sah
    * Created At: 21-03-24
    */
    public function editcompanyinformation()
    {

        $hasDiectory = Diectory::where('id', Auth::user()->directory_id)->first();
        if (empty($hasDiectory)) {
            return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
        } else {

            $id                             = Auth::user()->directory_id;
            $data['languagesData']          = Helper::getAllLanguages();           /* get languages */
            $data['parent_category']        = Helper::getAllParentCategory();      /* get Category */
            // $data['child_category']      = Helper::getAllChildCategory();       /* get Child Category */
            $data['country']                = Helper::getCountryAllData();         /* get country */
            $data['amenity']                = Helper::getAmenity();                /* get amenity */
            $data['amenityCategoryData']    = Helper::getamenityCategoryData();    /* get amenityCategoryData */
            $data['type']                   = Helper::getType();                   /* get category whose type is listing */
            $data['trademarks']             = Helper::getTrademarks();             /* get trademarks */
            $data['trademarkcategory']      = TrademarkCategory::where('deleted','0')->where('directory_id',Auth::user()->directory_id)->orWhere('directory_id', 0)->orderBy('id', 'desc')->get();       /* get Trademark Category */

            $data['owntags']                = DB::table('directory_category as dc')->select('cots.*')->leftjoin('category_own_tags as cots', 'dc.category_id', '=', 'cots.category_id')->where('dc.directory_id',  $id)->get();             /* get owntags */
            $data['directoryowntags']       = Helper::getdirectoryOwnTagsData();    /* get directory owntags trademarkcat_id*/

            $data['basicData']              = Diectory::where('id', $id)->first();

            $categoryData                   = DirectoryCategory::select('category_id')->where('directory_id', $id)->get()->toArray();
            $data['categoryData']           = array_column($categoryData, 'category_id');

            $data['child_category']         = Category::where('parent_id',$data['categoryData'])->where('status','Active')->where('deleted','0')->orderBy('name', 'ASC')->get()->toArray();     /* get Child Category */

            $subcategoryData                = DirectorySubCategory::select('sub_category_id')->where('directory_id', $id)->get()->toArray();
            $data['subcategoryData']        = array_column($subcategoryData, 'sub_category_id');

            $data['tags']                   = Tags::whereIn('child_cat_id', $data['subcategoryData'])->where('status','Active')->orderBy('tags', 'ASC')->get()->toArray();

            $trademarkData                  = DirectoryMediaTrademarks::select('trademark_id')->where('directory_id', $id)->get()->toArray();
            $data['trademarkData']          = array_column($trademarkData, 'trademark_id');

            $data['trademarkDatacate']      = DirectoryMediaTrademarks::select('*')->where('directory_id', $id)->get()->toArray();
            if (!empty($data['trademarkDatacate'])) {
                $data['trademarkselectedDataAccordingToTrademarkCat']    = DB::table('trademarks')->where('status',1)->whereIn('directory_id',[Auth::user()->directory_id, 0])->select('*')->where('category_id', $data['trademarkDatacate'][0]['trademarkcat_id'])->get()->toArray();
            } else {
                $data['trademarkselectedDataAccordingToTrademarkCat'] = array();
            }

            $tagsData                       = DirectoryTags::select('tags_id')->where('directory_id', $id)->get()->toArray();
            $data['tagsData']               = array_column($tagsData, 'tags_id');
            $ownTagsData                    = DirectoryOwnTags::select('owntags_id')->where('directory_id', $id)->get()->toArray();
            $data['ownTagsData']            = array_column($ownTagsData, 'owntags_id');
            $directoryownTagsData           = DirectoryNewOwnTags::select('owntags_id')->where('directory_id', $id)->get()->toArray();
            $data['directoryownTagsData']   = array_column($directoryownTagsData, 'owntags_id');
            $data['locationData']           = DB::table('directory_location')->select('*')->where('directory_id', $id)->get()->toArray();
            $data['dataamenityCatIdData']   = array();
            $datadataamenityCatIdData       = DB::table('directory_amenities')->select('amenitycat_id')->where('directory_id', $id)->where('typeofdata', 'existing')->first();
            if(!empty($datadataamenityCatIdData)){ $data['dataamenityCatIdData'] = explode(',',$datadataamenityCatIdData->amenitycat_id); }
            $data['getselectetedAmenityData'] = array();
            $getselectetedAmenityData = DB::table('directory_amenities')->select('amenities_id')->where('directory_id', $id)->where('typeofdata', 'existing')->first();
            if(!empty($getselectetedAmenityData)){ $data['getselectetedAmenityData'] = explode(',',$getselectetedAmenityData->amenities_id); }

            $data['contactData'] = DB::table('directory_contact')->select('*')->where('directory_id', $id)->get()->toArray();
            $amenitygetallselectedDataAccordingToAmenityCat = array();
            if(!empty($data['dataamenityCatIdData'])){
                $amenityCatName = array();$i=0;
                foreach($data['dataamenityCatIdData'] as $value){
                    $amenityCatName[] = AmenityCategory::where("id", $value)->get(["name"]);
                    $amenitygetallselectedDataAccordingToAmenityCat[$amenityCatName[$i][0]->name] = Amenity::where("category_id", $value)->get(["icon_class", "id", "title", "category_id"]);
                    $i++;
                }
            }
            $data['basicAndTraslationData'] = DB::table('directory as d')->select('d_tr.*')
                ->leftjoin('directory_basic_translation as d_tr', 'd.id', '=', 'd_tr.directory_id')
                ->where('d_tr.directory_id',  $id)->groupBy('d_tr.language_id')->get();
            $data['seoAndTraslationData'] = DB::table('directory_seo as dse')->select('*')
                ->leftjoin('directory_seo_translation as dse_tr', 'dse.module_id', '=', 'dse_tr.directory_id')
                ->where('dse_tr.directory_id',  $id)->groupBy('dse_tr.language_id')->get();

            $data['scheduleData'] = DB::table('directory_schedule')->select('*')->where('directory_id',  $id)->get()->toArray();
            $data['scheduleData'] = self::group_assoc(json_decode(json_encode($data['scheduleData']), 1), 'day');
            $data['scheduleHolidayData'] = DB::table('directory_custom_holiday')->select('*')->where('directory_id',  $id)->get()->toArray();
            $data['scheduleHolidayDatacount'] = DB::table('directory_custom_holiday')->select('*')->where('directory_id',  $id)->count();
            $data['scheduleExceptionHolidayData'] = DB::table('directory_exceptional')->select('*')->where('directory_id',  $id)->get()->toArray();
            $data['scheduleExceptionHolidayDatacount'] = DB::table('directory_exceptional')->select('*')->where('directory_id',  $id)->count();
            $data['mediaData'] = DB::table('directory_media')->select('*')->where('directory_id',  $id)->get();
            $data['directoryTypeData'] = DB::table('directory_type')->select('*')->where('directory_id',  $id)->get();
            $data['directoryTypeDatainreverse'] = DB::table('directory_type')->select('*')->where('directory_id',  $id)->orderBy('id', 'desc')->get();
            $data['websitelink'] = DB::table('directory_website_links')->select('*')->where('directory_id',  $id)->get();
            $data['assortlink'] = DB::table('directory_assortment_links')->select('*')->where('directory_id',  $id)->get();
            $data['normaldaysdata']         = DB::table('normaltemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();

            $data['publicdaysdata_admin'] = DB::table('holidaytemplate_name')
                ->where('status','Active')->where('deleted','0')
                ->where('name', '!=', ' ')
                ->where(function($query) use ($id) {
                    $query->WhereNull('directory_id');
                })
                ->get()->toArray();

            $data['publicdaysdata'] = DB::table('holidaytemplate_name')
                ->where('status','Active')->where('deleted','0')
                ->where('name', '!=', ' ')
                ->where(function($query) use ($id) {
                    $query->where('directory_id', $id);
                })
                ->get()->toArray();

            $data['exeptionaldaysdata_admin']     = DB::table('exeptionalholidaytemplate_name')->where('status','Active')->where('deleted','0')
            ->where('name', '!=', ' ')
                ->where(function($query) use ($id) {
                    $query->WhereNull('directory_id');
                })
            ->get()->toArray();
            $data['exeptionaldaysdata']           = DB::table('exeptionalholidaytemplate_name')->where('status','Active')->where('deleted','0')
            ->where('name', '!=', ' ')
                ->where(function($query) use ($id) {
                    $query->where('directory_id', $id);
                })
            ->get()->toArray();

            $data['directory_amenity'] = DB::table('amenity')->select('*')->where('directory_id',  $id)->get();
            $data['directory_services'] = DirectoryServices::where('directory_id',  $id)->get();
            $data['directory_claim'] = DB::table('claim')->select('*')->where('directory_id',  $id)->orderBy('id', 'desc')->get();
            $data['directory_claim_count'] = DB::table('claim')->select('*')->where('directory_id',  $id)->get()->count();
            $directory_gallery_images = DB::table('directory_gallery_images')->where(['directory_id' => $id])->get();

            $data['instruction']    = Instruction::select('message')->where('section_name',  'companyinfo')->where('status','Active')->first();

            return view('account.companyeditinfo', ['data' => $data,'amenitygetallselectedDataAccordingToAmenityCat' => $amenitygetallselectedDataAccordingToAmenityCat]);

        }

    }

    public function getTrademarksByCatid(Request $request)
    {
        $data['trademark'] = Trademarks::where("category_id", $request->category_id)->whereIn('directory_id',[Auth::user()->directory_id, 0])->get();
        return response()->json($data);
    }
    public function getGalleryImageDelete(Request $request)
    {
        $data = DirectoryMediagallery::find($request->id);
        $image_name = $data->name_thumbnail.".webp";
        $image_path = public_path('images/directory/'.$image_name);
        if(file_exists($image_path)){
          unlink($image_path);
        }
        $data = DirectoryMediagallery::find($request->id)->delete();
        return response()->json($data);
    }
    public function getCoverImageDelete(Request $request)
    {
        $data = DirectoryMediaCover::find($request->id);
        $image_name = $data->name_thumbnail.".webp";
        $image_path = public_path('images/directory/'.$image_name);
        if(file_exists($image_path)){
          unlink($image_path);
        }
        $data = DirectoryMediaCover::find($request->id)->delete();
        return response()->json($data);
    }
    public function getThumbImageDelete(Request $request)
    {
        $data = DirectoryMediaListing::find($request->id);
        $image_name = $data->name_thumbnail.".webp";
        $image_path = public_path('images/directory/'.$image_name);
        if(file_exists($image_path)){
          unlink($image_path);
        }
        $data = DirectoryMediaListing::find($request->id)->delete();
        return response()->json($data);
    }
    public function getTrademarkImageDelete(Request $request)
    {
        $data = DirectoryMediaTrademarks::find($request->id)->delete();
        return response()->json($data);
    }

    public function packages(){
        $plans = Plans::where('is_active',1)->orderBy('id', 'asc')->paginate(20);
        $mysubscription = PlanSubscription::where('subscriber_id', auth()->user()->directory_id)->first();

        $paymentTrasaction = PaymentTrasaction::where('directory_id', auth()->user()->directory_id)->orderby('id','desc')->first(); //->where('type','auto_payment')

        return view('account.company.package.index', compact('plans','mysubscription','paymentTrasaction'));
    }

    public function packagesdetails($id){
        $plan = app('rinvex.subscriptions.plan')->find($id);
        $dirid = auth()->user()->directory_id;
        $user = User::where('directory_id',$dirid)->first();
        $phone = auth()->user()->phone;
        $data['plansDetails'] = Plans::with('planfeatures')->findOrFail($id);
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        $data['packagemodule']      = PackageModule::get();
        $data['planSubscription']      = PlanSubscription::with('plan')->where('subscriber_id', auth()->user()->directory_id)->first();
        //dd($data['plansDetails']);
        return view('account.company.package.edit', ['data'=>$data]);
    }

    function group_assoc($array, $key)
    {
        $return = array();
        foreach ($array as $v) {
            $return[$v[$key]][] = $v;
        }
        return $return;
    }
    /*
    * Destroy Company's product
    * Created By: Sanjay Sah
    * Created At: 22-03-22
    */

    public function destroy($id)
    {
        //Product::where('id', $id)->update(['deleted' => '1']);
        $data = ProductImage::where("product_id", $id)->get("*");
        foreach($data as $rowdata){
            $image_name = $rowdata->name_thumbnail.".webp";
            $image_path = public_path('images/product/'.$image_name);
            if(file_exists($image_path)){
              unlink($image_path);
            }
        }
        Product::where('id', $id)->delete();
        ProductImage::where('product_id', $id)->delete();
        ProductPrice::where('product_id', $id)->delete();
        ProductTranslation::where('product_id', $id)->delete();
        ProductCategories::where('product_id', $id)->delete();
        ProductAttributes::where('product_id', $id)->delete();
        ProductVideo::where('product_id', $id)->delete();
        Wishlist::where('product_id', $id)->delete();

        return redirect('company/products')->with('msg', 'Product deleted successfully');
    }
    public function changeVendorProductStatus(Request $request){
        $product = Product::find($request->id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success'=>'Status change successfully.']);
    }

    public function changeCustomerClubStatus(Request $request){
        $c_club = CustomerClubs::find($request->id);
        $c_club->opt_out = $request->opt_out;
        $c_club->save();
        return response()->json(['success'=>'Status change successfully.']);
    }

    /*
    * Change schedule based on template
    * Created By: Madhulika
    * Created At: 08-06-22
    */
    public static function getNormalTemplateData(Request $request)
    {
        $getTemplateData = DB::table('normaltemplate')->select('*')->where('template_id',$request->teplate_id)->get()->toArray();
        $getNormalTemplateData = self::group_assoc(json_decode(json_encode($getTemplateData), 1), 'day');
        $html = '<table id="nomaldays-template-table" class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="border-top-0">Day</th>
                            <th class="border-top-0">Closed</th>
                            <th class="border-top-0">Open</th>
                            <th class="border-top-0">Closed</th>
                            <th class="border-top-0">Add Hours</th>
                        </tr>
                    </thead>
                    <tbody>';
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
        "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
        "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");

        foreach($getNormalTemplateData as $value=>$dayDetail){
            foreach($dayDetail as $key => $dayData){
            $html.= '<tr class="'.$value.' '.$value.'_template_row_'.$key.'">';
                $html.= '<td>';
                    if($key==0){
                        $html.=$value.':';
                    }else{
                        $html.='';
                    }
                $html.='</td>';
                $html.= '<td>';
                    $selectedisclosedData = $dayData['is_closed']==1?"checked":"";
                    if($key==0){
                        $html.='<input class="form-check-input" type="checkbox" name="'.$value.'_template_is_closed" value="1" '.$selectedisclosedData.'>';
                    }
                $html.= '</td>';
                $html.= '<td><select class="form-control" name="'.$value.'_template_hours_min[]">';
                    foreach($timeValue as $rowtime){
                        $opentimeselectedvalue = $rowtime==$dayData['open_time']?"selected":"";
                        $html.= '<option value="'.$rowtime.'"'.$opentimeselectedvalue.'>'.$rowtime.'</option>';
                    }
                $html.= '</select></td>';
                $html.= '<td><select class="form-control" name="'.$value.'_template_hours_max[]">';
                    foreach($timeValue as $rowtime){
                        $closetimeselectedvalue = $rowtime==$dayData['close_time']?"selected":"";
                        $html.= '<option value="'.$rowtime.'"'.$closetimeselectedvalue.'>'.$rowtime.'</option>';
                    }
                $html.= '</select></td>';
                $html.= '<td>';
                $valday = "'".$value."'";
                    if($key==0){
                        $html.='<button type="button" class="btn btn-info" id="" onclick="addTemplateDayBlock(' .$valday. ')">+ Hours</button>';
                    }else{
                        $html.='<button class="btn btn-danger" type="button" onclick="removeTemplateDayBlock('.$value.','.$key.')">- Hours</button>';
                    }
                $html.= '</td>';
            $html.= '<tr>';
            }
        }
    echo $html;
    }
    public static function getPublicTemplateData(Request $request)
    {
        $getPublicTemplateData = DB::table('holidaytemplate_details')->select('*')->where('template_id',$request->publicsele_id)->get()->toArray();
        if($getPublicTemplateData!=0){

            $is_display = $request->type=='admin'?"none":"block";

            $html = '<table id="public-template" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="border-top-0">Date</th>
                        <th class="border-top-0">Holiday</th>
                        <th class="border-top-0">Day</th>
                        <th class="border-top-0">Opening</th>
                        <th class="border-top-0">Closing</th>
                        <th class="border-top-0">Closed</th>
                        <th class="border-top-0 delete_yes_exceptional_th" style="display:'.$is_display.';">Action</th>
                    </tr>
                </thead>
                <tbody>';
                $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
                $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
        "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
        "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                    foreach($getPublicTemplateData as $holidayrow){
                        $opentimeholiday =   json_decode($holidayrow->custom_time);
                        $html.='<tr id="tblholi__'.$holidayrow->id.'" class="tblholi_yes_public_'.$holidayrow->id.'"><input type="hidden" name="template_id[]" value="'.$holidayrow->id.'">';
                        $html.='<td><input type="date" name="'.$holidayrow->id.'_custom_date" value="'.$holidayrow->custom_date.'" class="form-control  border-0" autocomplete=off></td>';
                        $html.='<td><input type="text" name="'.$holidayrow->id.'_note" placeholder="Holiday title" value="'.$holidayrow->note.'" class="form-control border-0" autocomplete=off></td>';
                        $html.='<td>';
                            $html.='<select class="form-control" name="'.$holidayrow->id.'_day">';
                            foreach($days as $days_val){
                                $opentimeselectedvalue = strtolower($holidayrow->day)==strtolower($days_val)?"selected":"";
                                $html.= '<option value="'.$days_val.'"'.$opentimeselectedvalue.'>'.$days_val.'</option>';

                            }
                        $html.='</select>';
                        $html.='</td><td>';
                                foreach($opentimeholiday as $extratime){
                                    $html.='<select class="form-control" name="'.$holidayrow->id.'_temp_time_hours_min[]">';
                                    foreach($timeValue as $rowtime){
                                        $opentimeselectedvalue = $rowtime==$extratime->open_time?"selected":"";
                                        $html.= '<option value="'.$rowtime.'"'.$opentimeselectedvalue.'>'.$rowtime.'</option>';

                                    }
                                    $html.='</select>';
                                }
                        $html.='
                        </td>
                        <td>';
                        foreach($opentimeholiday as $extratime){
                            $html.='<select class="form-control" name="'.$holidayrow->id.'_temp_time_hours_max[]">';
                            foreach($timeValue as $rowtime){
                                $closetimeselectedvalue = $rowtime==$extratime->close_time?"selected":"";
                                $html.= '<option value="'.$rowtime.'"'.$closetimeselectedvalue.'>'.$rowtime.'</option>';
                            }
                            $html.='</select>';
                        }
                        $html.='</td>';

                        $html.='<td>';
                            $is_closed = $holidayrow->closed=='Yes'?"checked":"";
                            $html.= '<input class="form-check-input" type="checkbox" name="'.$holidayrow->id.'_temp_holiday_closed" value="1" '.$is_closed.'>';
                        $html.='</td>';


                        $html.='<td class="delete_yes_exceptional_td" style="display:'.$is_display.';">';
                            $html.= '<button type="button" class="btn btn-sm btn-danger delete_yes_public" data-id="'.$holidayrow->id.'">Delete</button>';
                        $html.='</td>';

                        $html.='</tr>';
                    }
                $html.='</tbody>';
        $html.='</table>';
        }
        echo $html;
    }
    public static function getExceptionTemplateOption(Request $request)
    {

        $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
        $getExceptionTemplateData = DB::table('exceptionalholidaytemplate_details')->select('*')->where('template_id',$request->exceptionsele_id)->get()->toArray();
        if($getExceptionTemplateData!=0){
            $is_display = $request->type=='admin'?"none":"block";
            $html = '<table id="exception-template" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="border-top-0">Date</th>
                        <th class="border-top-0">Holiday</th>
                        <th class="border-top-0">Day</th>
                        <th class="border-top-0">Opening</th>
                        <th class="border-top-0">Closing</th>
                        <th class="border-top-0">Closed</th>
                        <th class="border-top-0 delete_yes_exceptional_th" style="display:'.$is_display.';">Action</th>
                    </tr>
                </thead>
                <tbody>';
                $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
        "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
        "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                    foreach($getExceptionTemplateData as $holidayrow){
                        $opentimeholiday =   json_decode($holidayrow->custom_time);
                        $html.='<tr  id="tblexcholi_'.$holidayrow->id.'" class="delete_yes_exceptional_tr_'.$holidayrow->id.'"><input type="hidden" name="exception_template_id[]" value="'.$holidayrow->id.'">';
                        $html.='<td><input type="date" name="'.$holidayrow->id.'_exception_date" value="'.$holidayrow->custom_date.'" class="form-control  border-0" autocomplete=off></td>';
                        $html.='<td><input type="text" name="'.$holidayrow->id.'_exception_note" placeholder="Add note" value="'.$holidayrow->note.'" class="form-control border-0" autocomplete=off></td>';
                        $html.='<td>';
                            $html.='<select class="form-control" name="'.$holidayrow->id.'_exception_day">';
                            foreach($days as $days_val){
                                $opentimeselectedvalue = $holidayrow->day==$days_val?"selected":"";
                                $html.= '<option value="'.$days_val.'"'.$opentimeselectedvalue.'>'.$days_val.'</option>';

                            }
                        $html.='</select>';
                        $html.='</td><td>';
                                foreach($opentimeholiday as $extratime){
                                    $html.='<select class="form-control" name="'.$holidayrow->id.'_exc_time_hours_min[]">';
                                    foreach($timeValue as $rowtime){
                                        $opentimeselectedvalue = $rowtime==$extratime->open_time?"selected":"";
                                        $html.= '<option value="'.$rowtime.'"'.$opentimeselectedvalue.'>'.$rowtime.'</option>';

                                    }
                                    $html.='</select>';
                                }
                        $html.='</td><td>';
                        foreach($opentimeholiday as $extratime){
                            $html.='<select class="form-control" name="'.$holidayrow->id.'_exc_time_hours_max[]">';
                            foreach($timeValue as $rowtime){
                                $closetimeselectedvalue = $rowtime==$extratime->close_time?"selected":"";
                                $html.= '<option value="'.$rowtime.'"'.$closetimeselectedvalue.'>'.$rowtime.'</option>';
                            }
                            $html.='</select>';
                        }
                        $html.='</td>';
                        $html.='<td>';
                            $is_closed = $holidayrow->closed=='Yes'?"checked":"";
                            $html.= '<input class="form-check-input" type="checkbox" name="'.$holidayrow->id.'_exception_closed" value="1" '.$is_closed.'>';
                        $html.='</td>';

                        $html.='<td class="delete_yes_exceptional_td" style="display:'.$is_display.';">';
                            $html.= '<button type="button" class="btn btn-sm btn-danger delete_yes_exceptional" data-id="'.$holidayrow->id.'">Delete</button>';
                        $html.='</td>';

                        $html.='</tr>';
                    }
                $html.='</tbody>';
        $html.='</table>';
        }
        echo $html;
    }
    public function getProductsubCategory(Request $request)
    {
        if(!empty($request->cat_id)){
            $cat = Productcategory::where('parent_id', $request->cat_id)->where('status', 'Active')->orderBy('name', 'ASC')->get();
            $html = "<option></option>";
            foreach($cat as $s){
                $html = $html."<option value='".$s->id."'>".$s->name."</option>";
            }
            return $html;
        }
    }

    public function getsubcatProductTags(Request $request)
    {
        $language_id = Helper::getselectedLanguage();
        $data['tags'] = DB::table('product_tags')->select('product_tags.id','product_tags.tags','tag_product_translations.title')
        ->join('tag_product_translations', 'product_tags.id', '=', 'tag_product_translations.tag_id')
        ->where('tag_product_translations.language_id', $language_id)
        ->whereIn('product_tags.child_cat_id',$request->category_id)->where('product_tags.status','Active')->orderBy('product_tags.tags', 'ASC')->get()->toArray();

        //dd($data);

        return response()->json($data);
    }


    public function deleteCheckedListingGallery(Request $request)
    {
        $ids = $request->ids;
        $iddarr = explode(",", $ids);
        foreach($iddarr as $id){
            $get_directoryTypeGallery = DirectoryMediagallery::where('id',$id)->first();
            $image_name = $get_directoryTypeGallery->name_thumbnail.".webp";
            $filePath = public_path('images/directory/' . $image_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }
        $is_deleted = DirectoryMediagallery::whereIn('id', explode(",", $ids))->delete();
        if($is_deleted){
            return response()->json(['success'=>'Deleted successfully.']);
        }else{
            return response()->json(['error'=>'Something went wrong.']);
        }

    }


    public function galleryImageDeleteAll( Request $request )
    {
        $data = DirectoryMediagallery::where("directory_id", $request->directory_id)->get("*");
        foreach($data as $rowdata){
            $image_name = $rowdata->name_thumbnail.".webp";
            $image_path = public_path('images/directory/'.$image_name);
            if(file_exists($image_path)){
              unlink($image_path);
            }
        }
        DirectoryMediagallery::where('directory_id', $request->directory_id)->delete();
        return response()->json(['success'=>'Status change successfully.']);
    }

    public function deleteCheckedProducts(Request $request)
    {
        $ids = $request->ids;
        // Product::whereIn('id', explode(",", $ids))->update(['deleted' => '1']);
        Product::whereIn('id', explode(",", $ids))->delete();
        ProductImage::whereIn('product_id', explode(",", $ids))->delete();
        ProductPrice::whereIn('product_id', explode(",", $ids))->delete();
        ProductTranslation::whereIn('product_id', explode(",", $ids))->delete();
        ProductCategories::whereIn('product_id', explode(",", $ids))->delete();
        ProductAttributes::whereIn('product_id', explode(",", $ids))->delete();
        ProductVideo::whereIn('product_id', explode(",", $ids))->delete();

        return response()->json(['success'=>'Products deleted successfully.']);
    }

}
