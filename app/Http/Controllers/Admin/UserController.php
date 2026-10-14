<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use DB; 
use App\Helpers\Helper;
use App\Models\Admin;
use App\Models\State;
use App\Models\City;
use App\Models\DirectoryContact;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use App\Models\VModule;
use App\Models\VPermission;
use App\Models\VRole;
use App\Models\UserAddress;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function index(Request $request){
        if($request->input('search')){
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $user = User::where('parent_id',0)->orderBy('id', 'desc')->where('email', 'like', '%' . $searchQuery . '%')->paginate(20);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            
            return view('admin.user.index', ['user' => $user,'data'=> $data,'query'=>$query]);
        }else{
            $user = User::where('parent_id',0)->orderBy('id', 'desc')->paginate(20);
            $data = isset($searchQuery)?$request->input('searchtext'):"";
            return view('admin.user.index', ['user' => $user,'data'=> $data]);
        }  
    }

    public function create(){
        $countries = Helper::getCountryAllData(); /* get country */
    	return view('admin.user.create',compact('countries'));
    }

    public function submitUser(Request $request){
        $this->validate($request, [
            'name'              => 'required',
            'last_name'         => 'required',
            'email'             => 'required|email|unique:users',
            'phone_number'      => 'required',
            'password'          => 'required|string|min:6',
            'country'           => 'required',
            'state'             => 'required',
            'city'              => 'required',
            'role'              => 'required',
        ]);

        $v_role = VRole::where('slug', 'vendor')->first();

        $v_module_reports               = VModule::where('slug','reports')->first();
        $v_module_company_info          = VModule::where('slug','company-info')->first();
        $v_module_products              = VModule::where('slug','products')->first();
        $v_module_offers                = VModule::where('slug','offers')->first();
        $v_module_orders                = VModule::where('slug','orders')->first();
        $v_module_directory_own_tags    = VModule::where('slug','directory-own-tags')->first();
        $v_module_coupons               = VModule::where('slug','coupons')->first();
        $v_module_gift_voucher          = VModule::where('slug','gift-voucher')->first();
        $v_module_products_discount     = VModule::where('slug','products-discount')->first();
        $v_module_booking               = VModule::where('slug','booking')->first();
        $v_module_permission            = VModule::where('slug','permission')->first();
        $v_module_role                  = VModule::where('slug','role')->first();
        $v_module_mail                  = VModule::where('slug','mail')->first();
        $v_module_offline_reports       = VModule::where('slug','offline-reports')->first();
        $v_module_trademarks            = VModule::where('slug','trademarks')->first();
        $v_module_shopping_card         = VModule::where('slug','shopping-card')->first();
        $v_module_blogs                 = VModule::where('slug','blogs')->first();
        $v_module_events                = VModule::where('slug','events')->first();
        $v_module_advertisement         = VModule::where('slug','advertisement')->first();


        $v_perm_add                     = VPermission::where('slug','add')->first();
        $v_perm_edit                    = VPermission::where('slug','edit')->first();
        $v_perm_delete                  = VPermission::where('slug','delete')->first();
        $v_perm_update                  = VPermission::where('slug','update')->first();
        $v_perm_view                    = VPermission::where('slug','view')->first();
        $v_perm_status                  = VPermission::where('slug','status')->first();
        $v_perm_download                = VPermission::where('slug','download')->first();

        if($request->input('import_optional') != 'on'){
            $import_optional = 0;
        }else{
            $import_optional = 1;
        }

        if($request->input('role') == 1){
            $usertype = 'vendor';
        }else{
            $usertype = 'customer';
        }
        $countriesName              = Helper::getCountryName($request->input('country')); 
        $stateName                  = Helper::getStateName($request->input('state'));
        $cityName                   = Helper::getCityName($request->input('city'));
        
        $user                       = new User;
        $user->name                 = $request->input('name');
        $user->last_name            = $request->input('last_name');
        $user->email                = strtolower($request->input('email'));
        $user->phone                = $request->input('phone_number');
        $user->is_verified          = $request->input('is_default_valid');
        $user->country              = $countriesName->name;
        $user->state                = $stateName->name;
        $user->city                 = $cityName->name??null;
        $user->city_id              = $request->input('city-name');
        $user->role_id              = $request->input('role');
        $user->password             = Hash::make($request->input('password'));
        $user->original_password    = $request->input('password');
        $user->address              = $request->input('address');
        $user->commission           = $request->input('commission')??'';
        $user->import_optional      = $import_optional;
        $user->show_import_file     = $request->input('show_import_file')??'0';
        $user->profile_status       = 1;
        $user->user_type            = $usertype;
        $user->temp                 = 0;
        $user->save();

        $role_id = $request->input('role');

        if ($role_id == 1) {

            $user->roles()->attach($v_role);

            $user->modules()->attach($v_module_reports);
            $user->modules()->attach($v_module_company_info);
            $user->modules()->attach($v_module_products);
            $user->modules()->attach($v_module_offers);
            $user->modules()->attach($v_module_orders);
            $user->modules()->attach($v_module_directory_own_tags);
            $user->modules()->attach($v_module_coupons);
            $user->modules()->attach($v_module_gift_voucher);
            $user->modules()->attach($v_module_products_discount);
            $user->modules()->attach($v_module_booking);
            $user->modules()->attach($v_module_permission);
            $user->modules()->attach($v_module_role);
            $user->modules()->attach($v_module_mail);
            $user->modules()->attach($v_module_offline_reports);
            $user->modules()->attach($v_module_trademarks);
            $user->modules()->attach($v_module_shopping_card);
            $user->modules()->attach($v_module_blogs);
            $user->modules()->attach($v_module_events);
            $user->modules()->attach($v_module_advertisement);

            $user->permissions()->attach($v_perm_add);
            $user->permissions()->attach($v_perm_edit);
            $user->permissions()->attach($v_perm_delete);
            $user->permissions()->attach($v_perm_update);
            $user->permissions()->attach($v_perm_view);
            $user->permissions()->attach($v_perm_status);
            $user->permissions()->attach($v_perm_download);
        }
        return redirect('admin/user')->with('msg', 'User Added Successfully!');
    }

    public function editUser($id=0){

        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false){
            abort(404);
        }
        $singleUserData         = User::findOrFail($id);
        $countries              = Helper::getCountryAllData(); /* get country */
        $stateName              = State::select('id', 'name')->where('name',$singleUserData->state)->first();
        $cityName               = City::select('id', 'name')->where('name',$singleUserData->city)->first();
        $addressData            = UserAddress::select('*')->where('user_id', $id)->groupBy('email', 'phone', 'address1', 'address2')->orderBy('id', 'DESC')->get()->toArray();
        

    	return view('admin.user.edit',compact('singleUserData', 'countries','stateName','cityName', 'addressData'));
    }

    public function sendBulkMessage(Request $Request){	
		$user_id = explode(',',$Request->user_ids);		
        //$message = str_replace(['<p>', '</p>'], '', $Request->message);
        $message = strip_tags($Request->message);
        $sendSms = '';
        foreach($user_id as $key => $val){
            $users = User::where('id', $val)->first();
            if (!empty($users->country_code) && !empty($users->phone) ) {
                $sendSms = LaraTwilio::notify('+'.$users->country_code.''.$users->phone, $message);
            }
        }
        echo json_encode(array('msg' => 'Message sent successfully to all user','status' =>'success')); exit;
        return $sendSms; 
	}

    public function vendorstaff($id=0){
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false){
            abort(404);
        }
        $vendorStaffData = User::where('parent_id',$id)->orderBy('id','desc')->paginate(20);
    	return view('admin.user.vendorstaff',compact('vendorStaffData'));
    }

    public function updateUser(Request $request,$id){

        $this->validate($request, [
            'name'          => 'required',
            'last_name'     => 'required',
            'phone_number'  => 'required|numeric|digits:10',
            'email'         => 'required|email',
            'role'          => 'required',
            'country'       => 'required',
            'state'         => 'required',
            //'commission'    => 'required|numeric',
        ]);

        if($request->input('role') == 1){
            $usertype = 'vendor';
        }else{
            $usertype = 'customer';
        }

        if($request->input('import_optional') != 'on'){
            $import_optional = 0;
        }else{
            $import_optional = 1;
        }
        $countriesName              = Helper::getCountryName($request->input('country')); 
        $stateName                  = Helper::getStateName($request->input('state'));
        $cityName                   = Helper::getCityName($request->input('city'));
        $cityId                     = $cityName?null:$request->input('city-name');

        $user                       = User::findOrFail($id);
        $user->name                 = $request->input('name');
        $user->email                = strtolower($request->input('email'));
        $user->last_name            = $request->input('last_name');
        $user->phone                = $request->input('phone_number');
        $user->is_verified          = $request->input('is_default_valid');
        $user->country              = $countriesName->name;
        $user->state                = $stateName->name;
        $user->city                 = $cityName->name??null;
        $user->city_id              = $cityId;
        $user->role_id              = $request->input('role');
        $user->import_optional      = $import_optional;
        $user->show_import_file     = $request->input('show_import_file')??'0';
        $user->commission           = $request->input('commission');
        $user->address              = $request->input('address');
        $user->user_type            = $usertype;

        $is_updated = $user->save();
        if($is_updated){
            $contactData = DirectoryContact::where('directory_id',$request->input('company_id'))->first();
            if($contactData){
                $contactData->name      = $request->input('name').' '.$request->input('last_name');
                $contactData->phone     = $request->input('phone_number');
                $contactData->email     = strtolower($request->input('email'));
                $contactData->save();
            }
        }
        return redirect('admin/user')->with('msg', 'User has been updated');
    }

    public function changeStatus(Request $request){
        $user = User::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success'=>'Status change successfully.']);
    }
    public function changeAdminStatus(Request $request){
        $user = Admin::find($request->user_id);
        $user->status = $request->status;
        $user->save();
        return response()->json(['success'=>'Status change successfully.']);
    }

    public function destroy($id)
    {
        $getUserdata = User::find($id);
        $data = DirectoryContact::where('email',$getUserdata->email)->first();
        if($data){
            return redirect('admin/user')->with('msg','Sorry, this user cannot be deleted because they are already assigned to a company.');
        }else{
            User::find($id)->delete();
            return redirect('admin/user')->with('msg','User deleted successfully');
        }
    }

    public function regionCustomer(Request $request){
        $query = $request->all();
        $country = $request->country ?? '';
        $state = $request->state ?? '';
        $city = $request->city ?? '';
        
        $totalCustomer = User::where('name','<>',' ');
            if (!empty($country)) {
                $totalCustomer = $totalCustomer->where('country',$country);
            }
            if (!empty($state)) {
                $totalCustomer = $totalCustomer->where('state',$state);
            }
            if (!empty($city)) {
                $totalCustomer = $totalCustomer->where('city',$city);
            }
            $totalCustomer = $totalCustomer->paginate(20);
            $data = isset($searchQuery)?$request->input('searchtext'):"";
            return view('admin.user.customerfilter', ['user' => $totalCustomer,'data'=> $data,'query'=>$query]);
    }
    public function regionCompanies(Request $request){
        $query = $request->all();
        $country = $request->country ?? '';
        $state = $request->state ?? '';
        $city = $request->city ?? '';
        
            $query = $request->all();
            $directoryDetails = DB::table('directory as dt')
                ->select('dt.*', 'dt_ln.country_id', 'dt_ln.directory_id')
                ->where('deleted', '0');
            $directoryDetails =    $directoryDetails->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id');
            if (!empty($country)) {
                $directoryDetails = $directoryDetails->where('dt_ln.country_id',$country);
            }
            if (!empty($state)) {
                $directoryDetails = $directoryDetails->where('dt_ln.state_id',$state);
            }
            if (!empty($city)) {
                $directoryDetails = $directoryDetails->where('dt_ln.city_id',$city);
            }
            $directoryDetails =    $directoryDetails->groupBy('dt.id')->orderBy('dt.id', 'desc');
            $data['directoryDetails'] =    $directoryDetails->paginate(20);
            return view('admin.directory.directoryfilter', ['data' => $data, 'query' => $query]);
    }

    public function showBulkImportFile(Request $request)
    {
        //$ids = $request->ids;
        $user_id = explode(',',$request->ids);	
        //User::whereIn('id', explode(",", $ids))->update(['show_import_file' => '1']);
        foreach($user_id as $key => $val){
            $users = User::where('id', $val)->where('role_id','1')->update(['show_import_file' => '1']);
        }
        return response()->json(['success'=>'Vendors product import file updated successfully.']);
    }

    public function addAddress($id)
    {
        $data = array();
        $data['userid'] = $id;
        return view('admin.user.address.add', ['data' => $data]);
    }

    public function storeAddress(Request $request)
    {
        $user_id = $request->input('user_id');
        $this->validate($request, [
            'first_name'   => 'required|regex:/^[\pL\s\-]+$/|max:255',
            'last_name'    => 'required|regex:/^[\pL\s\-]+$/|max:255',
            'phone'        => 'required|numeric|digits:10',
            'email'        => 'required|email',
            'address1'     => 'required',
            'city'         => 'required',
            'state'        => 'required',
            'country'      => 'required',
            'zipcode'      => 'required'
        ]);
        $cityName = Helper::getCityName($request->input('city'));
        $radioaddress = $request->input('default_add');
        if ($radioaddress == "yes") {
            UserAddress::query()->where('id','<>', $request->input('id'))->where('user_id', $user_id)->update(['default_address' => "no",]);
        }
        $address                            = new UserAddress;
        $address->user_id                   = $user_id;
        $address->default_address           = $radioaddress;
        $address->sameBillingAddress        = $request->input('sameBillingAddress');
        $address->type                      = $request->input('type');
        $address->first_name                = $request->input('first_name');
        $address->last_name                 = $request->input('last_name');
        $address->phone                     = $request->input('phone');
        $address->email                     = $request->input('email');
        $address->address1                  = $request->input('address1');
        $address->address2                  = $request->input('address2');
        $address->city                      = $cityName->name;
        $address->city_id                   = $request->input('city');
        $address->state                     = $request->input('state');
        $address->country                   = $request->input('country');
        $address->zipcode                   = $request->input('zipcode');
        $address->save();
        return redirect('/admin/user-edit/'.$user_id)->with('addresssuccess', 'Address Added Successfully!!');
    }

    public function editAddress($id)
    {
        $data = array();
        $data['addressData']  = UserAddress::select('*')->where('id', $id)->first();
        return view('admin.user.address.edit', ['data' => $data]);
    }

    public function updateAddress(Request $request)
    {
        $this->validate($request, [
            'first_name'            => 'required|regex:/^[\pL\s\-]+$/|max:255',
            'last_name'             => 'required|regex:/^[\pL\s\-]+$/|max:255',
            'phone'                 => 'required|numeric|digits:10',
            'email'                 => 'required|email',
            'address1'              => 'required',
            'city'                  => 'required',
            'state'                 => 'required',
            'country'               => 'required',
            'zipcode'               => 'required'
        ]);
        $user_id = $request->input('userid');
        $cityName = Helper::getCityName($request->input('city'));
        $radioaddress = $request->input('default_add');
        if ($radioaddress == "yes") {
            UserAddress::query()->where('id','<>', $request->input('id'))->where('user_id', $user_id)->update(['default_address' => "no",]);
        }
        UserAddress::where('id', $request->input('id'))
            ->update([
                'default_address'       => $radioaddress,
                'sameBillingAddress'    => $request->input('sameBillingAddress'),
                'type'                  => $request->input('type'),
                'first_name'            => $request->input('first_name'),
                'last_name'             => $request->input('last_name'),
                'phone'                 => $request->input('phone'),
                'email'                 => $request->input('email'),
                'address1'              => $request->input('address1'),
                'address2'              => $request->input('address2'),
                'city'                  => $cityName->name,
                'city_id'               => $request->input('city'),
                'state'                 => $request->input('state'),
                'country'               => $request->input('country'),
                'zipcode'               => $request->input('zipcode'),
            ]);
        return redirect('/admin/user-edit/'.$user_id)->with('addresssuccess', 'Address updated Successfully!!');
    }

    public function deleteAddress($id)
    {
        UserAddress::where('id', $id)->delete();
        return redirect()->back()->with('addresssuccess', 'Address deleted Successfully!!');
    }
}
