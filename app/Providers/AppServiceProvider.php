<?php

namespace App\Providers;

use App\Helpers\Helper;
use App\Models\Booking;
use App\Models\Frontendsetting;
use App\Models\Systemsetting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Diectory;
use App\Models\PaymentTrasaction;
use App\Models\PlanFeatures;
use App\Models\Plans;
use App\Models\PlanSubscription;
use App\Models\PlanSubscriptionUsage;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind('rinvex.subscriptions.plan', Plans::class);
        $this->app->bind('rinvex.subscriptions.plan_feature', PlanFeatures::class);
        $this->app->bind('rinvex.subscriptions.plan_subscription', PlanSubscription::class);
        $this->app->bind('rinvex.subscriptions.plan_subscription_usage', PlanSubscriptionUsage::class);
    }

    public function boot()
    {
        View::composer(['components.client.footer','components.client.footervendor'], function ($view) {

            $data['footerData']                 = Systemsetting::first();
            $data['socialfooterData']           = Frontendsetting::first();
            //$data['parent_category']            = Helper::getAllParentCategory(); //$data['product_parent_category']    = Helper::getAllParentProductCategory();

            if(Auth::user()){
                $payment_data =  PaymentTrasaction::where('directory_id',Auth::user()->directory_id)->where('type','auto_payment')->first();
                $mysubscription = PlanSubscription::with('plan')->where('slug','!=','free')->where('subscriber_id', Auth::user()->directory_id)->first();
                if($mysubscription && $mysubscription->plan && $mysubscription->plan->signup_fee && $payment_data){
                        $end_date_time = new DateTime($payment_data->ends_at);
                        $efective_date = $end_date_time->format('Y-m-d H:i:s');
                        $current_datetime = new DateTime();
                        $current_date = $current_datetime->format('Y-m-d H:i:s');
                        if($current_date > $efective_date){
                            $expired_plan = 1;
                        }else{
                            $expired_plan = 0;
                        }
                }else{
                    $expired_plan = 0;
                }
            }else{
                $expired_plan = 0;
            }
            $view->with(['expired_plan' => $expired_plan]);
            $view->with(['data' => $data]);
        });

        View::composer(['components.client.filter_side_shop'], function ($view) {
            $data['product_parent_category']    = Helper::getAllParentProductCategory();      /* get All product Category */
            $view->with(['data2' => $data]);
        });
        View::composer(['components.client.filter_side'], function ($view) {
            $data['parent_category']            = Helper::getAllParentCategory();
            $view->with(['data2' => $data]);
        });

        View::composer(['components.client.company_account_menu'], function ($view) {
            $directoryBasicData = Diectory::where('created_by', Auth::user()->id)->first();
            $directoryUrl = DB::table('directory')->where('id', Auth::user()->directory_id)->orderBy('id','DESC')->first();
            $bookingdata = Booking::where('directory_id', Auth::user()->directory_id)
            ->where('notify', '0')
            ->count();
            $queryData = [
                'response_type' => 'code',
                'client_id' => config('stripe.client_id'),
                'scope' => 'read_write',
                'redirect_uri' => config('stripe.redirect_uri')
            ];
            $connectUri = config('stripe.authorization_uri').'?'.http_build_query($queryData);
            $view->with(['bookingdata' => $bookingdata]);
            $view->with(['directoryBasicData' => $directoryBasicData]);
            $view->with(['directoryUrl' => $directoryUrl]);
            $view->with(['connectUri' => $connectUri]);
        });

        View::composer(['components.header'], function ($view) {
            $notificationRequest = auth()->guard('webadmin')->user()->notifications()->limit(3)->get();
            $view->with('notificationRequest', $notificationRequest);
        });

        View::composer(['components.client.menu','components.client.menu_detail','components.client.menu_pro_detail','client.sitemap'], function ($view) {
            $language_id = Helper::getselectedLanguage();

            $data['categories']   = DB::table('category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                    ->where('category_type_id','2')->where('c.status','Active')->where('feature','yes')->where('deleted','0')->where('parent_id', 0)->limit(15)->orderby('sort_value','asc')
                                    ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')
                                    //->innerjoin('directory_category as d_cat', 'd_cat.category_id', '=', 'c.id')
                                    ->where("ct.language_id",$language_id)->get()->toArray();


            $data['subcategories']   = DB::table('category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                    ->where('category_type_id','2')->where('c.status','Active')->where('feature','yes')->where('deleted','0')->where('parent_id','!=',0)->where('sort_value','!=',0)->orderby('sort_value','asc')
                                    ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();


            $data['procategories']   = DB::table('product_category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                    ->where('feature','yes')->where('deleted','0')->where('parent_id', 0)->limit(15)->orderby('sort_value','asc')
                                    ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();

            $data['prosubcategories']   = DB::table('product_category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                    ->where('feature','yes')->where('deleted','0')->where('parent_id','!=',0)->where('sort_value','!=',0)->orderby('sort_value','asc')
                                    ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();

            $data['maxprice']        = DB::table('product_price')->where('regular_price', \DB::raw("(select max(`regular_price`) from lc_product_price)"))->first();
            $data['amenity'] = Helper::getAmenity();
            $view->with(['data' => $data]);

            if (Auth::check()) {
                $notificationRequest = auth()->guard('web')->user()->notifications()->get();
            }else{
                $notificationRequest = '';
            }
            $view->with('notificationRequest', $notificationRequest);
        });
        Schema::defaultStringLength(191);
    }
}
