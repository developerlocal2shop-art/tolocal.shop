<?php

namespace App\Http\Controllers\Account;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Mail\PlanAboutToExpire;
use App\Mail\PlanExpired;
use App\Models\client\Order;
use App\Models\Currency;
use App\Models\Order_status;
use App\Models\PackageHistory;
use App\Models\Plans;
use App\Models\PlanFeatures;
use App\Models\PlanSubscription;
use App\Models\PlanSubscriptionUsage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Session;
use App\Models\Country;
use App\Models\Diectory;
use App\Services\PayPalService;
use Illuminate\Support\Facades\DB;
use Dotunj\LaraTwilio\Facades\LaraTwilio;

class CompanyDashboardContoller extends Controller
{
    public function index()
    {
        return view('account.company.dashboard.dashboard');
    }

    public function reportsview()
    {
        return view('account.company.dashboard.reportsview');
    }

    public function paypalstoredetail(Request $request)
    {
        //dd($request->paypal_email);
        $validated = $request->validate([
            'paypal_email' => 'required|email',
        ]);

        $user = Auth::user();
        $user->update([
            'paypal_email' => $request->paypal_email??'',
            'paypal_merchant_id' => $request->paypal_merchant_id??'',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'PayPal details updated successfully.',
        ]);
    }


    public function reportsdashboard(Request $request, PayPalService $paypal)
    {

        $userId = Auth::user()->directory_id;
        $planSubscription = PlanSubscription::where('subscriber_id', $userId)
            ->whereNotNull('paypal_subscription_id')
            ->first();

        if ($planSubscription && $planSubscription->paypal_subscription_id) {
            $provider = $paypal->provider();
            $subscription = $provider->showSubscriptionDetails($planSubscription->paypal_subscription_id);
            $status = $subscription['status'] ?? null;
        } else {
            $status = null;
        }

        $planSubscription = PlanSubscription::where('subscriber_id', Auth::user()->directory_id)->first();
        if($planSubscription){
            $mysubscriptionslug = $planSubscription->slug??'';
        }else{
            $mysubscriptionslug = '';
        }

        if (empty($mysubscriptionslug) || in_array($status, ['CANCELLED', 'EXPIRED'], true)) {

                Diectory::where('id', auth()->user()->directory_id)->update(['autopayment' => $status]);
                PlanSubscription::where('paypal_subscription_id', $subscription['id'])->update(['statuss' => 'cancelled']);

                $bookingsOfSubscriber = PlanSubscription::where('subscriber_id', auth()->user()->directory_id ?? '0')->get();
                    if (!empty($bookingsOfSubscriber)) {
                        foreach ($bookingsOfSubscriber as $vall) {
                            PlanSubscriptionUsage::where('subscription_id', $vall['id'])->delete();
                        }
                    }
                    PlanSubscription::where('subscriber_id', auth()->user()->directory_id)->delete();
                    $packagedata = Plans::where('slug', '=', 'free')->first();
                    $planid = $packagedata->id;
                    $package_name = $packagedata->name;

                    $PlanSubscription = new PlanSubscription();
                    $PlanSubscription->subscriber_type    =   'App\Models\User';
                    $PlanSubscription->subscriber_id    =   Auth::user()->directory_id;
                    $PlanSubscription->plan_id    =   $planid;
                    $PlanSubscription->slug     =   Helper::getSeoUrl($package_name);
                    $PlanSubscription->name    =   $package_name;
                    $PlanSubscription->save();
                    $subscriptionid = $PlanSubscription->id;

                    $features = PlanFeatures::query()
                        ->where('plan_id', $planid)
                        ->select(['id', 'slug', 'value'])
                        ->get();

                    if ($features->isNotEmpty()) {
                        $usageRows = $features->map(function ($feature) use ($subscriptionid) {
                            return [
                                'subscription_id' => $subscriptionid,
                                'feature_id' => $feature->id,
                                'name' => $feature->slug,
                                'used' => $feature->value ?? 0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        })->all();

                        PlanSubscriptionUsage::insert($usageRows);
                    }

        }

        if (Auth::user()->profile_status == 0) {
            return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
        } else {
            $directory_id = Auth::user()->directory_id;
            if ($request->type == 'filter') {
                session(['filterdata' => $request->all()]);
                $start_date = Carbon::parse($request->fromdate)->toDateTimeString();
                $end_date = Carbon::parse($request->todate)->toDateTimeString();
                $orderStatusPending = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 1)->get();
                $orderStatusProcessing = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 2)->get();
                $orderStatusCancelled = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 4)->get();
                $orderStatusDelivered = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 5)->get();
                $orderStatusRTO = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 6)->get();
                $orderStatusRefunded = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 7)->get();
                $allorders = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->get();
            } else {
                session()->forget(['filterdata']);
                $orderStatusPending = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 1)->get();
                $orderStatusProcessing = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 2)->get();
                $orderStatusCancelled = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 4)->get();
                $orderStatusDelivered = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 5)->get();
                $orderStatusRTO = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 6)->get();
                $orderStatusRefunded = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 7)->get();
                $allorders = Order::where('directory_id', $directory_id)->with('orderstatus')->get();
            }
            $defaultCurrency = Currency::where('code', 'EUR')->first();
            $orderstatus = Order_status::get();
            return view('account.company.dashboard.reportsdashboard', compact('allorders', 'orderstatus', 'orderStatusPending', 'defaultCurrency', 'orderStatusProcessing', 'orderStatusCancelled', 'orderStatusDelivered', 'orderStatusRTO', 'orderStatusRefunded'));
        }


    }

    // public function reportsdashboard(Request $request, PayPalService $paypal)
    // {

    //     $countryCode = session()->get('countrycode');
    //     $countryCodeData = session()->get('countrynamecode');
    //     $countryName = auth()->user()->country;
    //     $countryPhone = Country::where('name', $countryName)->first();
    //     $userPhone = Auth::user()->phone;

    //     $getAutopayment = Diectory::where('id', Auth::user()->directory_id)->first();
    //     $planSubscription = PlanSubscription::where('subscriber_id', Auth::user()->directory_id)->first();
    //     if ($getAutopayment && $getAutopayment->autopayment != 1) {
    //         if ($planSubscription) {
    //             $currentDate      = NOW()->format('Y-m-d');
    //             $effectiveDate = date_format(date_create($planSubscription->ends_at), "Y-m-d");
    //             $beforeDate = date('Y-m-d', strtotime($effectiveDate . ' - 3 days'));
    //             $Plansdata = Plans::where('id', $planSubscription->plan_id)->first();
    //             $data = [
    //                 'plan' => $planSubscription->name,
    //                 'effectiveDate' => date_format(date_create($planSubscription->ends_at), "Y-m-d"),
    //                 'username' => Auth::user()->name,
    //                 'price' => $Plansdata->price,
    //                 'currency' => $Plansdata->currency,
    //                 'created_at' => date_format(date_create($planSubscription->created_at), "Y-m-d")
    //             ];
    //             //expired and about to expire mail
    //             if ($currentDate == $beforeDate) {
    //                 $isPackageHistory = PackageHistory::where('directory_id', Auth::user()->directory_id)->where('plan_id', $planSubscription->plan_id)->where('status', 'before')->count();
    //                 if ($isPackageHistory == 0) {
    //                     $res =  Mail::to(Auth::user()->email)->send(new PlanAboutToExpire($data));
    //                     $msg = "Your package about to expired! ";
    //                     if (!empty($countryCodeData->phonecode)) {
    //                         $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . '' . Auth::user()->phone, $msg);
    //                     } else {
    //                         $sendSms = LaraTwilio::notify('+' . $countryPhone->phonecode . '' . Auth::user()->phone, $msg);
    //                     }
    //                     $before = new PackageHistory();
    //                     $before->plan_name = $planSubscription->name;
    //                     $before->slug = $planSubscription->slug;
    //                     $before->directory_id = Auth::user()->directory_id;
    //                     $before->plan_id = $planSubscription->plan_id;
    //                     $before->status = 'before';
    //                     $before->save();
    //                 }
    //                 //  echo "Mail before 3 days";
    //             } else if ($currentDate == $effectiveDate) {
    //                 $isPackageHistory = PackageHistory::where('directory_id', Auth::user()->directory_id)->where('plan_id', $planSubscription->plan_id)->where('status', 'expired')->count();
    //                 if ($isPackageHistory > 0) {
    //                     $res = Mail::to(Auth::user()->email)->send(new PlanExpired($data));
    //                     $msg = "Your package has been expired! ";
    //                     if (!empty($countryCodeData->phonecode)) {
    //                         $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . $userPhone, $msg);
    //                     } else {
    //                         $sendSms = LaraTwilio::notify('+' . $countryPhone->phonecode . $userPhone, $msg);
    //                     }
    //                     $before = new PackageHistory();
    //                     $before->plan_name = $planSubscription->name;
    //                     $before->slug = $planSubscription->slug;
    //                     $before->directory_id = Auth::user()->directory_id;
    //                     $before->plan_id = $planSubscription->plan_id;
    //                     $before->status = 'expired';
    //                     $before->save();
    //                 }
    //                 //echo "Package expired mail";
    //             }
    //             //Assigned free package features expired
    //             if ($effectiveDate <= $currentDate) {
    //                 $bookingsOfSubscriber = PlanSubscription::where('subscriber_id', auth()->user()->directory_id ?? '0')->get();
    //                 if (!empty($bookingsOfSubscriber)) {
    //                     foreach ($bookingsOfSubscriber as $vall) {
    //                         PlanSubscriptionUsage::where('subscription_id', $vall['id'])->delete();
    //                     }
    //                 }
    //                 PlanSubscription::where('subscriber_id', auth()->user()->directory_id)->delete();
    //                 $packagedata = Plans::where('slug', '=', 'free')->first();
    //                 $planid = $packagedata->id;
    //                 $package_name = $packagedata->name;
    //                 $PlanSubscription = new PlanSubscription();
    //                 $PlanSubscription->subscriber_type    =   'App\Models\User';
    //                 $PlanSubscription->subscriber_id    =   Auth::user()->directory_id;
    //                 $PlanSubscription->plan_id    =   $planid;
    //                 $PlanSubscription->slug     =   Helper::getSeoUrl($package_name);
    //                 $PlanSubscription->name    =   $package_name;
    //                 $PlanSubscription->save();
    //                 $subscriptionid = $PlanSubscription->id;
    //                 $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
    //                 foreach ($feature as $val) {
    //                     $plansFea = new PlanSubscriptionUsage();
    //                     $plansFea->subscription_id          = $subscriptionid;
    //                     $plansFea->feature_id          = $val->id;
    //                     $plansFea->name          = $val->slug;
    //                     $plansFea->used            = $val->value ?? 0;
    //                     $plansFea->save();
    //                 }
    //             }
    //         }
    //     }

    //     if($planSubscription){
    //         $mysubscriptionslug = $planSubscription->slug??'';
    //     }else{
    //         $mysubscriptionslug = '';
    //     }

    //     if(empty($mysubscriptionslug)){

    //         $packagedata = Plans::where('slug', '=', 'free')->first();
    //         $planid = $packagedata->id;
    //         $package_name = $packagedata->name;

    //         $PlanSubscription = new PlanSubscription();
    //         $PlanSubscription->subscriber_type    =   'App\Models\User';
    //         $PlanSubscription->subscriber_id    =   Auth::user()->directory_id;
    //         $PlanSubscription->plan_id    =   $planid;
    //         $PlanSubscription->slug     =   Helper::getSeoUrl($package_name);
    //         $PlanSubscription->name    =   $package_name;
    //         $PlanSubscription->save();
    //         $subscriptionid = $PlanSubscription->id;

    //         $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
    //             foreach($feature as $val){
    //                 $plansFea = new PlanSubscriptionUsage();
    //                 $plansFea->subscription_id          = $subscriptionid;
    //                 $plansFea->feature_id          = $val->id;
    //                 $plansFea->name          = $val->slug;
    //                 $plansFea->used            = $val->value??0;
    //                 $plansFea->save();
    //             }

    //     }


    //     if (Auth::user()->profile_status == 0) {
    //         return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
    //     } else {
    //         $directory_id = Auth::user()->directory_id;
    //         if ($request->type == 'filter') {
    //             session(['filterdata' => $request->all()]);
    //             $start_date = Carbon::parse($request->fromdate)->toDateTimeString();
    //             $end_date = Carbon::parse($request->todate)->toDateTimeString();
    //             $orderStatusPending = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 1)->get();
    //             $orderStatusProcessing = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 2)->get();
    //             $orderStatusCancelled = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 4)->get();
    //             $orderStatusDelivered = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 5)->get();
    //             $orderStatusRTO = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 6)->get();
    //             $orderStatusRefunded = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->where('status', 7)->get();
    //             $allorders = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->with('orderstatus')->get();
    //         } else {
    //             session()->forget(['filterdata']);
    //             $orderStatusPending = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 1)->get();
    //             $orderStatusProcessing = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 2)->get();
    //             $orderStatusCancelled = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 4)->get();
    //             $orderStatusDelivered = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 5)->get();
    //             $orderStatusRTO = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 6)->get();
    //             $orderStatusRefunded = Order::where('directory_id', $directory_id)->with('orderstatus')->where('status', 7)->get();
    //             $allorders = Order::where('directory_id', $directory_id)->with('orderstatus')->get();
    //         }
    //         $defaultCurrency = Currency::where('code', 'EUR')->first();
    //         $orderstatus = Order_status::get();
    //         return view('account.company.dashboard.reportsdashboard', compact('allorders', 'orderstatus', 'orderStatusPending', 'defaultCurrency', 'orderStatusProcessing', 'orderStatusCancelled', 'orderStatusDelivered', 'orderStatusRTO', 'orderStatusRefunded'));
    //     }
    // }

    public function ordersfilter(Request $request)
    {
        $start_date = $request->startdate;
        $end_date = $request->enddate;
        $directory_id = Auth::user()->directory_id;
        $status = $request->status ?? '';
        if (!empty($start_date) && !empty($status)) {
            $order = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->where('status', $status)->paginate(20);
        } elseif (!empty($start_date) && empty($status)) {
            $order = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->paginate(20);
        } elseif (empty($start_date) && empty($status)) {
            $order = Order::where('directory_id', $directory_id)->paginate(20);
        } else {
            $order = Order::where('directory_id', $directory_id)->where('status', $status)->paginate(20);
        }
        return view('account.company.dashboard.orders', compact('order'));
    }

    public function vendorlikedDetails(Request $request){
        //echo $request->likeCountry;echo $request->likeState;echo $request->likeCity;
        $country = $request->likeCountry;
        $state = $request->likeState;
        $city = $request->likeCity;
        $directory_id = Auth::user()->directory_id;
        $directorylikecount = DB::table('counters_like')
                        ->leftjoin('directory', 'directory.id', '=', 'counters_like.module_id')->where('counters_like.type','directory')->Where('directory.id',  $directory_id);
        $data['directorylikecount'] =  $directorylikecount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['directorylikecount'] = count($data['directorylikecount']->get());

        $productlikecount = DB::table('counters_like')
                        ->leftjoin('product', 'product.id', '=', 'counters_like.module_id')->where('counters_like.type','product')->Where('product.directory_id',  $directory_id);
        $data['productlikecount'] =  $productlikecount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['productlikecount'] = count($data['productlikecount']->get());

        $Shoppingcardlikecount = DB::table('counters_like')
                            ->leftjoin('shopping_card', 'shopping_card.id', '=', 'counters_like.module_id')->where('counters_like.type','Shoppingcard')->Where('shopping_card.directory_link',  $directory_id);
        $data['Shoppingcardlikecount'] =  $Shoppingcardlikecount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['Shoppingcardlikecount'] = count($data['Shoppingcardlikecount']->get());

        $eventlikecount = DB::table('counters_like')
                        ->leftjoin('events', 'events.id', '=', 'counters_like.module_id')->where('counters_like.type','event')->Where('events.directory_id',  $directory_id);
        $data['eventlikecount'] =  $eventlikecount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['eventlikecount'] = count($data['eventlikecount']->get());

        $product_offerlikecount = DB::table('counters_like')
                            ->leftjoin('offer', 'offer.id', '=', 'counters_like.module_id')->where('counters_like.type','product_offer')->Where('offer.offer_directory_link',  $directory_id);
        $data['product_offerlikecount'] =  $product_offerlikecount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['product_offerlikecount'] = count($data['product_offerlikecount']->get());

        $bloglikecount = DB::table('counters_like')
                    ->leftjoin('blog', 'blog.id', '=', 'counters_like.module_id')->where('counters_like.type','blog')->Where('blog.directory_id',  $directory_id);
        $data['bloglikecount'] =  $bloglikecount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['bloglikecount'] = count($data['bloglikecount']->get());

        //echo "<pre>"; print_r($data['Shoppingcardcount']);

        return response()->json($data);
    }

    public function vendorVisitorsDetails(Request $request){
        //echo $request->likeCountry;echo $request->likeState;echo $request->likeCity;
        $country = $request->visitorCountry;
        $state = $request->visitorState;
        $city = $request->visitorCity;
        $directory_id = Auth::user()->directory_id;
        $directoryvendorvisitorcount = DB::table('counters')
                        ->leftjoin('directory', 'directory.id', '=', 'counters.module_id')->where('counters.type','directory')->Where('directory.id',  $directory_id);
        $data['directoryvendorvisitorcount'] =  $directoryvendorvisitorcount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['directoryvendorvisitorcount'] = count($data['directoryvendorvisitorcount']->get());

        $productvendorvisitorcount = DB::table('counters')
                        ->leftjoin('product', 'product.id', '=', 'counters.module_id')->where('counters.type','product')->Where('product.directory_id',  $directory_id);
        $data['productvendorvisitorcount'] =  $productvendorvisitorcount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['productvendorvisitorcount'] = count($data['productvendorvisitorcount']->get());

        $Shoppingcardvendorvisitorcount = DB::table('counters')
                            ->leftjoin('shopping_card', 'shopping_card.id', '=', 'counters.module_id')->where('counters.type','shoppingcard')->Where('shopping_card.directory_link',  $directory_id);
        $data['Shoppingcardvendorvisitorcount'] =  $Shoppingcardvendorvisitorcount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['Shoppingcardvendorvisitorcount'] = count($data['Shoppingcardvendorvisitorcount']->get());

        $eventvendorvisitorcount = DB::table('counters')
                        ->leftjoin('events', 'events.id', '=', 'counters.module_id')->where('counters.type','event')->Where('events.directory_id',  $directory_id);
        $data['eventvendorvisitorcount'] =  $eventvendorvisitorcount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['eventvendorvisitorcount'] = count($data['eventvendorvisitorcount']->get());

        $product_offervendorvisitorcount = DB::table('counters')
                            ->leftjoin('offer', 'offer.id', '=', 'counters.module_id')->where('counters.type','offer')->Where('offer.offer_directory_link',  $directory_id);
        $data['product_offervendorvisitorcount'] =  $product_offervendorvisitorcount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['product_offervendorvisitorcount'] = count($data['product_offervendorvisitorcount']->get());

        $blogvendorvisitorcount = DB::table('counters')
                    ->leftjoin('blog', 'blog.id', '=', 'counters.module_id')->where('counters.type','blog')->Where('blog.directory_id',  $directory_id);
        $data['blogvendorvisitorcount'] =  $blogvendorvisitorcount->where(function ($query) use ($country,$state,$city) {
            if($country!="no_val"){
                $query->where('country_code', 'like', '%' . $country . '%');
            }
            if($state!="no_val"){
                $query->where('state', 'like', '%' . $state . '%');
            }
            if($city!="no_val"){
                $query->where('city', 'like', '%' . $city . '%');
            }
        });
        $data['blogvendorvisitorcount'] = count($data['blogvendorvisitorcount']->get());

        //echo "<pre>"; print_r($data['Shoppingcardcount']);

        return response()->json($data);
    }

}
