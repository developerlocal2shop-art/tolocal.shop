<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\UserAddress;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $data = array();
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $data['addressData']  = UserAddress::select('*')->where('user_id', $user_id)->groupBy('email', 'phone', 'address1', 'address2')
                ->orderBy('id', 'DESC')->get()->toArray();
            return view('account.address.list', ['data' => $data]);
        } else {
            return redirect()->route('pages');
        }
    }

    public function addAddress()
    {
        $data = array();
        return view('account.address.add', ['data' => $data]);
    }

    public function storeAddress(Request $request)
    {
        $user_id = Auth::user()->id;
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
        return redirect('account/address')->with('success', 'Address Added Successfully!!');
    }

    public function editAddress($id)
    {
        $user_id = Auth::user()->id;
        $data = array();
        $data['addressData']  = UserAddress::select('*')->where('id', $id)->first();
        return view('account.address.edit', ['data' => $data]);
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
        $cityName = Helper::getCityName($request->input('city'));
        $radioaddress = $request->input('default_add');
        if ($radioaddress == "yes") {
            UserAddress::query()->where('id','<>', $request->input('id'))->where('user_id', $request->input('userid'))->update(['default_address' => "no",]);
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
        return redirect('account/address')->with('success', 'Address updated Successfully!!');
    }

    public function deleteAddress($id)
    {
        UserAddress::where('id', $id)->delete();
        return redirect('account/address')->with('success', 'Address deleted Successfully!!');
    }
}
