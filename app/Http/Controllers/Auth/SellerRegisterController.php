<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Hash;
use Illuminate\Support\Facades\Auth;

class SellerRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SellerRegisterController Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use AuthenticatesUsers;


    public function registerseller()
    {
      	return view('auth.client.seller_register');
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function create(Request $request)
    {
        //dd($request->all()); die;
        unset($request['_token']);
        $validator = \Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'register_email' => 'required|email|unique:users,email',
            'phone' => 'required|digits:10|unique:users,phone',
            'password' => 'required',
            'cpassword' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        }else{

            $user = new User;
            $user->name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->email = $request->input('register_email');
            $user->password = Hash::make($request->input('password'));
            $user->phone = $request->input('phone');
            $user->role_id = 1;
            $user->status = 0;
            $res = $user->save();

            if($res){
        
                return response()->json(['status' => true, 'message' => ' Admin need to verify first then you can login']);

            }else{

                return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);

            }


        }

    }
}
