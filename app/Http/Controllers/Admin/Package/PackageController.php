<?php

namespace App\Http\Controllers\Admin\Package;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Events;
use App\Helpers\Helper;
use App\Models\EventGallery;
use App\Models\ManualPackageHitory;
use App\Models\PackageModule;
use App\Models\PlanFeatures;
use App\Models\Plans;
use App\Models\PlanSubscription;
use App\Models\PlanSubscriptionUsage;
use App\Models\User;
use App\Models\VModule;
use App\Models\VPermission;
use App\Models\VRole;
use Illuminate\Database\Eloquent\Builder;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'plans';
            $data['columns'] = ['title', 'status'];
            $events = Helper::masterSearch($data);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.package.index', ['plans' => $plans, 'eventssearch' => $searchtext, 'query' => $query]);
        } else {
            $plans = Plans::orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.package.index', ['plans' => $plans, 'eventssearch' => $searchtext]);
        }
    }
    public function create()
    {
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        $data['packagemodule']      = PackageModule::get();
        return view('admin.package.create', ['data' => $data]);
    }
    public function submit(Request $request)
    {
        $name_en = $request->input('plan_title_en');
        $description_en = $request->input('plan_content_en');
        $pland = $request->input('plan');
        $limit = $request->input('limit');
        $this->validate($request, [
            'plan_title_en'  => 'required'
        ]);
        $plans = new Plans();
        $plans->slug            = Helper::getSeoUrl($name_en);
        $plans->name            = json_encode($name_en);
        $plans->description     = json_encode($description_en);
        $plans->is_active       = $request->input('status');
        $plans->price           = $request->input('price');
        $plans->signup_fee           = $request->input('signup_fee');
        $plans->currency        = 'kr';
        $plans->invoice_period  = $request->input('invoice_period');
        $plans->invoice_interval = $request->input('invoice_interval');
        $plans->save();
        $plan_id = $plans->id;
        foreach ($pland as $key => $val) {
            $plansFea = new PlanFeatures();
            $plansFea->plan_id         = $plan_id;
            $plansFea->slug            = Helper::getSeoUrl($val);
            $plansFea->name            = json_encode($val);
            $plansFea->value            = $limit[$key] ?? '0';
            $plansFea->save();
        }
        return redirect('admin/package-edit/' . $plan_id)->with('msg', 'Package Added Successfully!');
    }

    public function deleteGalleryImage(Request $request)
    {
        $data = EventGallery::find($request->id)->delete();
        return response()->json($data);
    }

    public function edit($id = 0)
    {
        $data['plansDetails'] = Plans::with('planfeatures')->findOrFail($id);
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        $data['packagemodule']      = PackageModule::get();
        return view('admin.package.edit', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        //dd($request->all());

        $updateData = $this->validate($request, [
            'plan_title_en'  => 'required',
        ]);
        $name_en = $request->input('plan_title_en');
        $description_en = $request->input('plan_content_en');
        $pland = $request->input('plan');
        $limit = $request->input('limit');

        Plans::where('id', $id)
            ->update([
                'slug'             => Helper::getSeoUrl($name_en),
                'name'             => json_encode($name_en),
                'description'      => json_encode($description_en),
                'is_active'        => $request->input('status'),
                'price'            => $request->input('price'),
                'signup_fee'            => $request->input('signup_fee'),
                'invoice_period'        => $request->input('invoice_period'),
                'invoice_interval'          => $request->input('invoice_interval'),
                'currency'          => 'kr'
            ]);
        PlanFeatures::where('plan_id', $id)->delete();
        foreach ($pland as $key => $val) {
            $plansFea = new PlanFeatures();
            $plansFea->plan_id         = $id;
            $plansFea->slug            = Helper::getSeoUrl($val);
            $plansFea->name            = json_encode($val);
            $plansFea->value           = $limit[$key] ?? '0';
            $plansFea->save();
        }
        return redirect('admin/package-edit/' . $id)->with('msg', 'Package has been updated');
    }

    public function destroy($id)
    {
        Plans::where('id', $id)->update(['is_active' => '0']);
        return redirect('admin/package')->with('msg', 'Package deleted successfully');
    }

    public function manualindex(Request $request)
    {
        $searchQuery = $request->input('searchtext');
        $whatToSearch = $request->input('whattosearch');
        if ($request->input('search')) {
            if($searchQuery!="" && $whatToSearch!=""){
                $query = $request->all();

                if($whatToSearch=="emailvendor"){
                    $plans = ManualPackageHitory::with(['vendor' => function($q) use($searchQuery) {
                        if($searchQuery){
                            $q->where('email', 'like', '%'.$searchQuery.'%');
                        }
                    }])
                    ->whereHas('vendor', function($q) use($searchQuery){
                        if($searchQuery){
                            $q->where('email', 'like', '%'.$searchQuery.'%');
                        }
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(20);
                }

                if($whatToSearch=="directoryname"){
                    $plans = ManualPackageHitory::with(['directoryName' => function($q) use($searchQuery) {
                        if($searchQuery){
                            $q->where('title', 'like', '%'.$searchQuery.'%');
                        }
                    }])
                    ->whereHas('directoryName', function($q) use($searchQuery){
                        if($searchQuery){
                            $q->where('title', 'like', '%'.$searchQuery.'%');
                        }
                    })
                    ->orderBy('id', 'desc')
                    ->paginate(20);
                }

                    $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
                    $searchstatus = isset($whatToSearch) ? $request->input('whattosearch') : "";
                    return view('admin.package.indexmanual', ['plans' => $plans, 'manualpackagesearch' => $searchtext, 'searchstatus' => $searchstatus, 'query' => $query]);
            }else{
                $plans = ManualPackageHitory::where('deleted', '0')->with('vendor')->with('plan')->with('directoryName')->orderBy('id', 'desc')->paginate(20);
                //dd($plans);
                // echo "<pre>"; print_r($plans);die;
                $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
                $searchstatus = isset($whatToSearch) ? $request->input('whattosearch') : "";
                return view('admin.package.indexmanual', ['plans' => $plans, 'manualpackagesearch' => $searchtext, 'searchstatus' => $searchstatus, 'emptysearch' => "Choose option for search and fill search box!"]);
            }

        } else {
            $plans = ManualPackageHitory::where('deleted', '0')->with('vendor')->with('plan')->with('directoryName')->orderBy('id', 'desc')->paginate(20);

            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            $searchstatus = isset($whatToSearch) ? $request->input('whattosearch') : "";
            return view('admin.package.indexmanual', ['plans' => $plans, 'manualpackagesearch' => $searchtext, 'searchstatus' => $searchstatus]);
        }
    }


    public function manualcreate()
    {
        $data['vendor']      = User::where('parent_id', 0)->where('role_id', 1)->get();
            /* get languages */
        $data['package']      = Plans::get();
        return view('admin.package.manualcreate', ['data' => $data]);
    }

    public function manualsubmit(Request $request)
    {

        $this->validate($request, [
            'vendor'  => 'required',
            'package'  => 'required'
        ]);
        $package = $request->input('package');
        $vendor = $request->input('vendor');
        $plan = Plans::find($package);
        $currentDate      = strtotime('today');
        $effectiveDate = date('Y-m-d h:i:s', strtotime("+" . $plan->invoice_period . " " . $plan->invoice_interval . " ", $currentDate));
        $bookingsOfSubscriber = PlanSubscription::where('subscriber_id', $vendor ?? '0')->get();

        if (!empty($bookingsOfSubscriber)) {
            foreach ($bookingsOfSubscriber as $vall) {
                PlanSubscriptionUsage::where('subscription_id', $vall['id'])->delete();
            }
        }
        PlanSubscription::where('subscriber_id', $vendor)->delete();
        $planid = $package;
        $package_name = $plan->name;
        $userdirid = $vendor;
        $PlanSubscription = new PlanSubscription();
        $PlanSubscription->subscriber_type  =   'App\Models\User';
        $PlanSubscription->subscriber_id    =   $userdirid;
        $PlanSubscription->plan_id    =   $planid;
        $PlanSubscription->slug       =   Helper::getSeoUrl($package_name);
        $PlanSubscription->name       =   json_encode($package_name);
        $PlanSubscription->ends_at    =   $effectiveDate;
        $PlanSubscription->save();
        $subscriptionid = $PlanSubscription->id;

        $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
        foreach ($feature as $val) {
            $plansFea = new PlanSubscriptionUsage();
            $plansFea->subscription_id          = $subscriptionid;
            $plansFea->feature_id          = $val->id;
            $plansFea->name          = $val->slug;
            $plansFea->used            = $val->value ?? 0;
            $plansFea->save();
        }

        $ManualPackageHitory = new ManualPackageHitory();
        $ManualPackageHitory->vendor_id    =   $vendor;
        $ManualPackageHitory->package_id    =   $package;
        $ManualPackageHitory->notes    =   $request->input('notes');
        $ManualPackageHitory->transaction_id     =   $request->input('transaction_id');
        $ManualPackageHitory->transaction_mode    =   $request->input('transaction_mode');
        $ManualPackageHitory->status    =   $request->input('status');
        $ManualPackageHitory->save();
        return redirect('admin/manual-package-permission')->with('msg', 'Manual Package Added Successfully!');
    }
    public function manualedit($id = 0)
    {
        $data['plansDetails'] = ManualPackageHitory::with('vendor')->with('plan')->findOrFail($id);
        $data['vendor']      = User::where('parent_id', 0)->where('role_id', 1)->get();
        $data['package']      = Plans::get();
        return view('admin.package.manualedit', ['data' => $data]);
    }

    public function manualupdate(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'vendor'  => 'required',
            'package'  => 'required'
        ]);
        $package = $request->input('package');
        $vendor = $request->input('vendor');
        $plan = Plans::find($package);

        $userData = User::where('directory_id', $vendor)->first();
        $v_role = VRole::where('slug', 'vendor')->first();

        $currentDate      = strtotime('today');
        $effectiveDate = date('Y-m-d h:i:s', strtotime("+" . $plan->invoice_period . " " . $plan->invoice_interval . " ", $currentDate));
        $bookingsOfSubscriber = PlanSubscription::where('subscriber_id', $vendor ?? '0')->first();
        $hold_paypal_subscription_id = $bookingsOfSubscriber->paypal_subscription_id??'';

        PlanSubscription::where('subscriber_id', $vendor)->delete();
        $planid                                 = $package;
        $package_name                           = $plan->name;
        $userdirid                              = $vendor;

        $PlanSubscription                       = new PlanSubscription();
        $PlanSubscription->subscriber_type      =   'App\Models\User';
        $PlanSubscription->subscriber_id        =   $userdirid;
        $PlanSubscription->plan_id              =   $planid;
        $PlanSubscription->slug                 =   Helper::getSeoUrl($package_name);
        $PlanSubscription->name                 =   json_encode($package_name);
        $PlanSubscription->ends_at              =   $effectiveDate;
        $PlanSubscription->paypal_subscription_id =   $hold_paypal_subscription_id;
        $PlanSubscription->save();

        $subscriptionid = $PlanSubscription->id;
            if (!empty($bookingsOfSubscriber)) {
                PlanSubscriptionUsage::where('subscription_id', $bookingsOfSubscriber->id)->delete();
            }

        $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
        foreach ($feature as $val) {
            $plansFea = new PlanSubscriptionUsage();
            $plansFea->subscription_id          = $subscriptionid;
            $plansFea->feature_id          = $val->id;
            $plansFea->name          = $val->slug;
            $plansFea->used            = $val->value ?? 0;
            $plansFea->save();
        }

        ManualPackageHitory::where('id', $id)
            ->update([
                'vendor_id'             => $vendor,
                'package_id'            => $package,
                'notes'                 => $request->input('notes'),
                'transaction_id'        => $request->input('transaction_id'),
                'transaction_mode'      => $request->input('transaction_mode'),
                'status'                => $request->input('status')
            ]);


        return redirect('admin/manual-package-edit/' . $id)->with('msg', 'Manual Package has been updated');
    }

    public function manualdestroy($id)
    {
        ManualPackageHitory::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/manual-package-permission')->with('msg', 'Deleted successfully');
    }

    public function packageHistory()
    {
        $allPackageOrderData = PlanSubscription::orderby('id','desc')->with('directoryName')->with('userData')->where('subscriber_id','<>',0)->where('ends_at','<>','NULL')->paginate(20);
        //$query = $request->all();
        //dd($allPackageOrderData);

        return view('admin.packageorder.index', ['allPackageOrder' => $allPackageOrderData]);
    }
}
