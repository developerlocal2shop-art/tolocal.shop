<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\client\Order;
use App\Models\Counter;
use App\Models\Currency;
use App\Models\Diectory;
use App\Models\Order_status;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use Directory;
use App\User;
use App\Models\DiectoryLocation;
use Illuminate\Support\Facades\Session;
use App\Models\client\LikeCounters;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Helpers\Helper;

class DashboardContoller extends Controller
{
    
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

    public function index(Request $request)
    {
        $userIP = $this->getIp();
        $new_arr = @unserialize(file_get_contents('http://ip-api.com/php/'.$userIP));

        if ($userIP == '127.0.0.1' || $userIP == 'localhost' || $userIP == '::1') {
            $countryCode = 'IN';
            $timezone = 'Asia/Kolkata';
        } else {
            $countryCode = $new_arr['countryCode'] ?? 'IN';
            $timezone = $new_arr['timezone'] ?? 'Asia/Kolkata';
        }

        Session::put('countrycode', $countryCode);

        $countryNameCode = Country::where('code', $countryCode)->first();
        Session::put('countrynamecode', $countryNameCode);

        if ($request->type == 'filter') {

            session(['filterregiondata' => $request->all()]);

            $countrywise = $request->countrywise;
            $statewise   = $request->statewise;
            $citywise    = $request->citywise;

            $totalCompanies = DiectoryLocation::query();

            if (!empty($countrywise)) {
                $totalCompanies->where('country_id', $countrywise);
            }
            if (!empty($statewise)) {
                $totalCompanies->where('state_id', $statewise);
            }
            if (!empty($citywise)) {
                $totalCompanies->where('city_id', $citywise);
            }
            $totalCustomer = User::where('name', '<>', ' ');
            if (!empty($countrywise)) {
                $totalCustomer->where('country', $countrywise);
            }
            if (!empty($statewise)) {
                $totalCustomer->where('state', $statewise);
            }
            if (!empty($citywise)) {
                $totalCustomer->where('city', $citywise);
            }
            $totalCompanies = $totalCompanies->count();
            $totalCustomer  = $totalCustomer->count();

        } else {

            session()->forget(['filterregiondata']);

            $totalCustomer  = User::count();
            $totalCompanies = Diectory::count();
        }

        $visitors = Counter::where(function ($query) {
                        $query->where('type', 'website')
                            ->orWhere('type', '')
                            ->orWhereNull('type');
                    })->count();
        $deliveredOrders = Order::where('status', 4)->count();

        $adminDetail = DB::table('admin')
            ->where('id', 1)
            ->first();

        return view('admin.dashboard', compact(
            'totalCustomer',
            'deliveredOrders',
            'totalCompanies',
            'visitors',
            'adminDetail'
        ));
    }


    public function reportsview(){
      	return view('admin.reportsview');
    }

    public function reportsdashboard(Request $request){

        if($request->type == 'filter'){

            session(['filterdata' => $request->all()]);
            $start_date1 = $request->fromdate;
            $end_date1 = $request->todate;
            $directory_id = $request->directory_id;
            if (!empty($start_date1)) {
                $start_date = Carbon::parse($request->fromdate)->toDateTimeString();
                $end_date = Carbon::parse($request->todate)->toDateTimeString();
            }else{
                $start_date = '';
                $end_date = '';
            }
            
            $orderStatusPend = Order::with('orderstatus')
                    ->where('status', 1);
                    if (!empty($start_date)) {
                        $orderStatusPend = $orderStatusPend->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $orderStatusPend = $orderStatusPend->where('directory_id',$directory_id);
                    }
            $orderStatusPending = $orderStatusPend->get();
            $orderStatusProcess = Order::with('orderstatus')
                    ->where('status', 1);
                    if (!empty($start_date)) {
                        $orderStatusProcess = $orderStatusProcess->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $orderStatusProcess = $orderStatusProcess->where('directory_id',$directory_id);
                    }
            $orderStatusProcessing = $orderStatusProcess->get();

            $orderStatusPackedProcess = Order::with('orderstatus')
                    ->where('status', 2);
                    if (!empty($start_date)) {
                        $orderStatusPackedProcess = $orderStatusPackedProcess->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $orderStatusPackedProcess = $orderStatusPackedProcess->where('directory_id',$directory_id);
                    }
            $orderStatusPacked = $orderStatusPackedProcess->get();

            $orderStatusShippedProcess = Order::with('orderstatus')
                    ->where('status', 3);
                    if (!empty($start_date)) {
                        $orderStatusShippedProcess = $orderStatusShippedProcess->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $orderStatusShippedProcess = $orderStatusShippedProcess->where('directory_id',$directory_id);
                    }
            $orderStatusShipped = $orderStatusShippedProcess->get();

            $orderStatusCancel = Order::with('orderstatus')
                    ->where('status', 4);
                    if (!empty($start_date)) {
                        $orderStatusCancel = $orderStatusCancel->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $orderStatusCancel = $orderStatusCancel->where('directory_id',$directory_id);
                    }
            $orderStatusCancelled = $orderStatusCancel->get();
            $orderStatusDeliver = Order::with('orderstatus')
                    ->where('status', 5);
                    if (!empty($start_date)) {
                        $orderStatusDeliver = $orderStatusDeliver->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $orderStatusDeliver = $orderStatusDeliver->where('directory_id',$directory_id);
                    }
            $orderStatusDelivered = $orderStatusDeliver->get();
            $orderSRTO = Order::with('orderstatus')
                    ->where('status', 6);
                    if (!empty($start_date)) {
                        $orderSRTO = $orderSRTO->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $orderSRTO = $orderSRTO->where('directory_id',$directory_id);
                    }
            $orderStatusRTO = $orderSRTO->get();
            $orderStatusRefund = Order::with('orderstatus')
                    ->where('status', 7);
                    if (!empty($start_date)) {
                        $orderStatusRefund = $orderStatusRefund->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $orderStatusRefund = $orderStatusRefund->where('directory_id',$directory_id);
                    }
            $orderStatusRefunded = $orderStatusRefund->get();
            $allorder = Order::with('orderstatus');
                    if (!empty($start_date)) {
                        $allorder = $allorder->WhereBetween('created_at',[$start_date, $end_date]);
                    }
                    if (!empty($directory_id)) {
                        $allorder = $allorder->where('directory_id',$directory_id);
                    }
            $allorders = $allorder->get();

            $data['directoryvisitorData'] = Counter::where('type','directory')->where('module_id',$directory_id)->count();

        }else{
            $data = 0;
            session()->forget(['filterdata']);
            $orderStatusProcessing = Order::with('orderstatus')->where('status', 1)->get();
            $orderStatusPacked = Order::with('orderstatus')->where('status', 2)->get();
            $orderStatusShipped = Order::with('orderstatus')->where('status', 3)->get();
            $orderStatusCancelled = Order::with('orderstatus')->where('status', 4)->get();
            $orderStatusDelivered = Order::with('orderstatus')->where('status', 5)->get();
            $orderStatusRTO = Order::with('orderstatus')->where('status', 6)->get();
            $orderStatusRefunded = Order::with('orderstatus')->where('status', 7)->get();
            $allorders = Order::with('orderstatus')->get();
        }

        

        $defaultCurrency = Currency::where('code','EUR')->first();
        $directory = Diectory::where('status','Active')->where('deleted','0')->get();
        $orderstatus = Order_status::get();
    	return view('admin.reportsdashboard',compact('data','orderstatus','allorders','directory','orderStatusPacked','orderStatusShipped','defaultCurrency','orderStatusProcessing', 'orderStatusCancelled', 'orderStatusDelivered', 'orderStatusRTO', 'orderStatusRefunded'));
    }


    public function likedDetails(Request $request){
        //echo $request->likeCountry;echo $request->likeState;echo $request->likeCity;
        $country = $request->likeCountry;
        $state = $request->likeState;
        $city = $request->likeCity;
        $directorycount = LikeCounters::select()->where('type','directory');
        $data['directorycount'] =  $directorycount->where(function ($query) use ($country,$state,$city) {
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
        $data['directorycount'] = count($data['directorycount']->get());

        $productcount = LikeCounters::select()->where('type','product');
        $data['productcount'] =  $productcount->where(function ($query) use ($country,$state,$city) {
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
        $data['productcount'] = count($data['productcount']->get());

        $Shoppingcardcount = LikeCounters::select()->where('type','Shoppingcard');
        $data['Shoppingcardcount'] =  $Shoppingcardcount->where(function ($query) use ($country,$state,$city) {
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
        $data['Shoppingcardcount'] = count($data['Shoppingcardcount']->get());

        $eventcount = LikeCounters::select()->where('type','event');
        $data['eventcount'] =  $eventcount->where(function ($query) use ($country,$state,$city) {
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
        $data['eventcount'] = count($data['eventcount']->get());

        $product_offercount = LikeCounters::select()->where('type','product_offer');
        $data['product_offercount'] =  $product_offercount->where(function ($query) use ($country,$state,$city) {
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
        $data['product_offercount'] = count($data['product_offercount']->get());

        $blogcount = LikeCounters::select()->where('type','blog');
        $data['blogcount'] =  $blogcount->where(function ($query) use ($country,$state,$city) {
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
        $data['blogcount'] = count($data['blogcount']->get());

        //echo "<pre>"; print_r($data['Shoppingcardcount']);

        return response()->json($data);
    }

    public function visitorsDetails(Request $request){
        //echo $request->likeCountry;echo $request->likeState;echo $request->likeCity;
        $country = $request->visitorCountry;
        $state = $request->visitorState;
        $city = $request->visitorCity;
        $directoryvisitorcount = Counter::select()->where('type','directory');
        $data['directoryvisitorcount'] =  $directoryvisitorcount->where(function ($query) use ($country,$state,$city) {
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
        $data['directoryvisitorcount'] = count($data['directoryvisitorcount']->get());

        $productvisitorcount = Counter::select()->where('type','product');
        $data['productvisitorcount'] =  $productvisitorcount->where(function ($query) use ($country,$state,$city) {
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
        $data['productvisitorcount'] = count($data['productvisitorcount']->get());

        $Shoppingcardvisitorcount = Counter::select()->where('type','shoppingcard');
        $data['Shoppingcardvisitorcount'] =  $Shoppingcardvisitorcount->where(function ($query) use ($country,$state,$city) {
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
        $data['Shoppingcardvisitorcount'] = count($data['Shoppingcardvisitorcount']->get());

        $eventvisitorcount = Counter::select()->where('type','event');
        $data['eventvisitorcount'] =  $eventvisitorcount->where(function ($query) use ($country,$state,$city) {
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
        $data['eventvisitorcount'] = count($data['eventvisitorcount']->get());

        $product_offervisitorcount = Counter::select()->where('type','offer');
        $data['product_offervisitorcount'] =  $product_offervisitorcount->where(function ($query) use ($country,$state,$city) {
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
        $data['product_offervisitorcount'] = count($data['product_offervisitorcount']->get());

        $blogvisitorcount = Counter::select()->where('type','blog');
        $data['blogvisitorcount'] =  $blogvisitorcount->where(function ($query) use ($country,$state,$city) {
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
        $data['blogvisitorcount'] = count($data['blogvisitorcount']->get());

        //echo "<pre>"; print_r($data['Shoppingcardcount']);

        return response()->json($data);
    }
    
    public function getCountry(){
        return view('admin.newcountrystatecity.country');
    }
    public function addCountry(Request $request){
        $this->validate($request, [
            'country_name'  => 'required|unique:countries,name',
            'country_code'  => 'required|unique:countries,code',
            'phonecode'     => 'required|unique:countries,phonecode',
        ]);
        $country = new Country();
        $country->name                      = $request->country_name;
        $country->code                      = $request->country_code;
        $country->phonecode                 = $request->phonecode;
        $country->timestamps                = false;
        $country->save();
        return redirect('admin/add-new-country')->with('msg', 'Country Added Successfully!');
    }
    public function getState(){
        return view('admin.newcountrystatecity.state');
    }
    public function addState(Request $request){
        $this->validate($request, [
            'countryname'   => 'required',
            'statename'     => 'required',
        ]);
        $multipleStateName                      = explode(',', $request->statename);
        foreach ($multipleStateName as $stateName) {
            $state = new State();
            $state->name                        = trim($stateName);
            $state->country_id                  = $request->countryname;
            $state->timestamps                  = false;
            $state->save();
        }
        return redirect('admin/add-new-state')->with('msg', 'State Added Successfully!');
    }
    public function getCity(){
        return view('admin.newcountrystatecity.city');
    }
    public function addCity(Request $request){
        $this->validate($request, [
            'cityname'          => 'required',
            'state'             => 'required',
            'countryname'       => 'required',
        ]);

        $multipleCityName                      = explode(',', $request->cityname);
        foreach ($multipleCityName as $cityName) {
            $city = new City();
            $city->name                      = trim($cityName);
            $city->state_id                  = $request->state;
            $city->save();
        }
        return redirect('admin/add-new-city')->with('msg', 'City Added Successfully!');
    }
    public function editAdminDetail(){
        $adminDetail         = DB::table('admin')->select("*")->where('id',1)->first();
        //dd($adminDetail);
        return view('admin.editadmindetail',compact('adminDetail'));
        
    }
    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'admin_name' => 'required',
            'admin_email' => 'required'
        ]);
        
        $adminDetail         = DB::table('admin')->select("*")->where('id',1)->first();
        $enteredPassword    = $request->input('admin_password');
        if(!empty($enteredPassword)){
            $pass = Hash::make($request->input('admin_password'));
        }else{
            $pass = $adminDetail->password;
        }
        
        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/admin/' . $imageName;
            $imgfile->move(public_path('files/admin/'), $imageName);
            if ($imgfile != "") {
                DB::table('admin')->where('id', $id)->update(['image_name' => $imageName,]);
            }
        }
        DB::table('admin')->where('id', $id)
            ->update([
                'name'          => $request->input('admin_name'),
                'email'         => $request->input('admin_email'),
                'password'      => $pass
            ]);
        
            return redirect('/admin/edit-admin-detail')->with('msg', 'Admin details has been updated');
    }
}
