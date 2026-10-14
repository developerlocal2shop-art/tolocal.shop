<?php

namespace App\Http\Controllers\Auth;

// use App\User;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Mail\UserRegMail;
use App\Mail\VendorRegMail;
use App\Mail\VendorRegProfileComMail;
use App\Models\VModule;
use App\Models\VPermission;
use App\Models\VRole;
use App\Rules\isUserTemp;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use Illuminate\Support\Facades\Session;
use App\Helpers\Helper;
use App\Mail\EmailOtpMail;
use App\Models\Country;
use App\Models\DirectoryContact;
use App\Models\Plans;
use App\Rules\Capitalized;
use App\Rules\DifferentFirstLastName;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    //Gitesh
    /* public function __construct()
    {
        $this->middleware('guest');
    }*/

    //gitesh

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    // protected function validator(array $data)
    // {
    //     return Validator::make($data, [
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|string|email|max:255|unique:users',
    //         'password' => 'required|string|min:6|confirmed',
    //     ]);
    // }

    public function registerUser()
    {
        return view('auth.client.register');
    }

    public function sendcompanyEmailotp(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validator->fails()) {
            $error = $validator->messages();
            $data = ['status' => 'error', 'isSuccess' => 'false', 'error' => $error];
            return response($data);
        }else{

            $user = auth()->user();
                $token = rand(100000, 999999);
                $tokenData = ['email' => $request->email, 'otp' => $token];
                // Check if the email address is provided, otherwise use a default
                //$email = $request->email ?: 'sanjay993147@gmail.com';
                // Send email asynchronously
                Mail::to($user->email)->queue(new EmailOtpMail($tokenData));
                session(['otp' => $token]);
                // Prepare response message
                $response = [
                    'status' => 'success',
                    'msg' => 'Please check your email Id. OTP sent successfully!'
                ];
                return response($response);
        }
    }

    public function sendemailotp(Request $request)
    {

        $validator =  Validator::make($request->all(), [
            'email' => 'required|unique:users',
        ]);
        if ($validator->fails()) {
            $error = $validator->messages();
            $data = ['status' => 'error', 'isSuccess' => 'false', 'error' => $error];
            return response($data);
        }else{
                //SMS gaytway
                // $otp = rand(100000,999999);
                // session(['otp' => $otp]);
                // $otpData = ['email' => $request->email, 'otp' => $otp];
                // if (!empty($request->email)) {
                //     $emailSendSms = Mail::to('sanjay993147@gmail.com')->send(new EmailOtpMail($otpData));
                // } else {
                //     $emailSendSms = Mail::to($request->email)->send(new EmailOtpMail($otpData));
                // }
                // if($emailSendSms){
                //     $response = array('status' => 'success','msg' => 'Please check your email Id. Otp sent successfully!!');
                // }
                // return response($response);

                $otp = rand(100000, 999999);
                session(['otp' => $otp]);
                $otpData = ['email' => $request->email, 'otp' => $otp];
                // Check if the email address is provided, otherwise use a default
                $email = $request->email ?: 'sanjay993147@gmail.com';
                // Send email asynchronously
                Mail::to($email)->queue(new EmailOtpMail($otpData));
                // Prepare response message
                $response = [
                    'status' => 'success',
                    'msg' => 'Please check your email Id. OTP sent successfully!'
                ];
                return response($response);

        }
    }


    public function sendotp(Request $request)
    {

        $countryCodeData = session()->get('countrynamecode');
        $validator =  Validator::make($request->all(), [
            'phone' => 'required|unique:users',
        ]);
        if ($validator->fails()) {
            $error = $validator->messages();
            $data = ['status' => 'error', 'isSuccess' => 'false', 'error' => $error];
            return response($data);
        }else{
                //SMS gaytway
                $otp = rand(100000,999999);
                //$otp = 1234;
                session(['otp' => $otp]);
                //session(['userid' => $request->userid]);
                $msg = "OTP from Tolocal.shop and your otp is $otp";

                if (!empty($countryCodeData->phonecode)) {
                    $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode.''.$request->phone, $msg);
                } else {
                    $sendSms = LaraTwilio::notify('+' . '91' . '99330886391', $msg);
                }
                //$sendSms = LaraTwilio::notify('91'.$request->phone, $msg);

                if($sendSms){
                    $response = array('status' => 'success','msg' => 'Otp sent successfully!!');
                }
                // }else{
                //     $response = array('status' => 'error','msg' => 'Woof, Try again!!');
                // }
                //echo json_encode($response);
                //$data = ['status' => 201, 'isSuccess' => 'false', 'error' => $error];
                return response($response);
                //chk
                // $request->validate([
                //     'otp' => ['required',
                //     function ($attribute, $value, $fail) {
                //         if ($value != session('otp')) {
                //             $fail($attribute.' is not matched.');
                //         }
                //     },
                // ],
            }
    }

    public function verify(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()){
            session(['otpverify' => '0']);
            $error = $validator->messages();
            $data = ['status' => 201, 'isSuccess' => 'false', 'error' => $error];
            return response($data);
        }else{

            $value = session('otp');
            if ($value == $request->otp) {
                session(['otpverify' => '1']);
                $data = ['status' => 200, 'isSuccess' => 'true'];
                return response($data, 200);
            } else {
                session(['otpverify' => '0']);
                $data = ['status' => 200, 'isSuccess' => 'false'];
                return response($data, 200);
            }

        }
    }

    public function verifyCotp(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'otp' => 'required',
        ]);

        if ($validator->fails()){
            session(['otpverify' => '0']);
            $error = $validator->messages();
            $data = ['status' => 201, 'isSuccess' => 'false', 'error' => $error];
            return response($data);
        }else{

            $value = session('otp');
            if ($value == $request->otp) {
                session(['otpverify' => '1']);

                DirectoryContact::where('directory_id', Auth::user()->directory_id??0)
                ->update([
                    'email'         => $request->input('email'),
                ]);
                User::where('id', Auth::user()->id??0)
                ->update([
                    'email'         => $request->input('email'),
                ]);
                $data = ['status' => 200, 'isSuccess' => 'true'];
                return response($data, 200);
            } else {
                session(['otpverify' => '0']);
                $data = ['status' => 200, 'isSuccess' => 'false'];
                return response($data, 200);
            }

        }
    }

    public function requestOTP(Request $request)
    {
        $countryCodeData = session()->get('countrynamecode');

        $messages = [
            'phone.required' => 'The phone number is required.',
            'phone.numeric' => 'The phone number must contain only digits.',
            'phone.digits_between' => 'The phone number must be either 9 or 10 digits.'
        ];
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits_between:9,10'
        ], $messages);

        if ($validator->fails()) {
            $error = $validator->messages();
            $data = ['status' => 'error', 'isSuccess' => 'false', 'error' => $error];
            return response($data);
        }else{
            //SMS gaytway
            $otp = rand(100000,999999);
            Cache::put("otp_$request->phone", $otp, now()->addMinutes(1));
            //session(['accessOTP' => $otp]);
            $msg = "OTP from Tolocal.shop and your otp is $otp";
            if (!empty($countryCodeData->phonecode)) {
                $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode.''.$request->phone, $msg);
             } else {
                $sendSms = LaraTwilio::notify('+' . '91' . '99330886391', $msg);
             }
            if($sendSms){
                $response = array('status' => 'success','msg' => 'Otp sent successfully!!');
            }
            return response($response);
        }
    }


    public function verifyLoginOtp(Request $request)
    {

        $messages = [
            'phone.required' => 'The phone number is required.',
            'phone.numeric' => 'The phone number must contain only digits.',
            'phone.digits_between' => 'The phone number must be either 9 or 10 digits.',
            'otp.required' => 'The OTP is required.',
            'otp.digits' => 'The OTP must be a 6-digit number.',
        ];
        $validator = Validator::make($request->all(), [
            'phone' => 'required|numeric|digits_between:9,10',
            'otp' => 'required|digits:6',
        ], $messages);

        if ($validator->fails()){
            session(['otpverify' => '0']);
            $error = $validator->messages();
            $data = ['status' => 201, 'isSuccess' => 'false', 'error' => $error];
            return response($data);
        }else{

            $phone = $request->phone;
            $otp = $request->otp;

            $cachedOtp = Cache::get("otp_$phone");
            if (!$cachedOtp || $cachedOtp != $otp) {
                //return response()->json(['message' => 'Invalid OTP or OTP expired.'], 400);
                $data = ['status' => 200, 'isSuccess' => 'false'];
                return response($data, 200);
            }else{
                //$user = User::firstOrCreate(['mobile' => $mobile]);
                $user = User::where('phone', $phone)->first();
                if($user){
                    // Log in the user
                    auth()->login($user);
                    Cache::forget("otp_$phone");
                    $data = ['status' => 200, 'isSuccess' => 'true'];
                    return response($data, 200);
                    //return response()->json(['message' => 'Login successful.', 'user' => $user]);
                }else{
                    $data = ['status' => 200, 'isSuccess' => 'false'];
                    return response($data, 200);
                }

            }



            // $value = session('accessOTP');
            // if ($value == $request->otp) {
            //     session(['otpverify' => '1']);

            //     User::where('id', Auth::user()->id??0)
            //     ->update([
            //         'email'         => $request->input('email'),
            //     ]);
            //     $data = ['status' => 200, 'isSuccess' => 'true'];
            //     return response($data, 200);
            // } else {
            //     session(['otpverify' => '0']);
            //     $data = ['status' => 200, 'isSuccess' => 'false'];
            //     return response($data, 200);
            // }

        }
    }


    public function create(Request $request)
    {

        $countryCode= session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');

        $country_code = Country::where('id', $request->input('register_country'))->first();

        $email = $request->input('register_email');
        $temp = 0;
        $this->validate($request, [
            'register_name'             => ['required', 'max:255', new Capitalized, new DifferentFirstLastName],
            'register_last_name'        => ['required', 'max:255', new Capitalized],
            'register_email'            => ['required', 'email', 'unique:users,email', new isUserTemp],
            'register_password'         => 'required|string|min:6',
            'register_country'          => 'required|integer',
            'register_state'            => 'required|integer',
            'register_city'             => 'required',
            'otp'                       => 'required',
        ]);

        $value = session('otp');
        if ($value == $request->otp) {

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

            if (User::where('temp', 1)->where('role_id', 1)->where('email', '=', $email)->first()) {

                $userid                     = User::where('email', '=', $email)->first('id');
                $user                       = User::findOrFail($userid->id);

                $user->name                 = $request->input('register_name');
                $user->last_name            = $request->input('register_last_name');
                $user->password             = Hash::make($request->input('register_password'));
                $user->phone                = $request->input('register_phone');

                $user->original_password    = $request->input('register_password');
                $user->address              = $request->input('address');
                $user->country              = $request->input('register_country');
                $user->country_code         = $country_code->phonecode;
                $user->state                = $request->input('register_state');

                $user->city                 = $request->input('register_city');
                $user->profile_status       = 1;
                $user->temp                 = 0;
                $user->status               = 1;
                $user->user_type            = 'vendor';
                $res                        = $user->save();

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


                if ($res) {
                    Mail::to(env('APP_EMAIL'))->send(new VendorRegProfileComMail($request->all()));
                    Mail::to($email)->send(new VendorRegProfileComMail($request->all()));

                }else{
                    return redirect('login')->with('error', 'Somthing went wrong!');
                }

            } else {

                $user                       = new User;
                $user->name                 = $request->input('register_name');
                $user->last_name            = $request->input('register_last_name');
                $user->email                = $request->input('register_email');
                $user->password             = Hash::make($request->input('register_password'));
                $user->phone                = $request->input('register_phone');
                $user->country              = $request->input('hiddencountry');
                $user->country_code         = $country_code->phonecode;
                $user->state                = $request->input('hiddenstate');
                $user->city                 = $request->input('register_city');
                $user->original_password    = $request->input('register_password');
                $user->address              = $request->input('address');

                if ($request->iscompany == null) {
                    $user->role_id = 0;
                    $user->user_type            = 'customer';
                } else {
                    $user->role_id = 1;
                    $user->user_type            = 'vendor';
                }
                $user->status = 1;
                $res = $user->save();
                if ($request->iscompany != null) {

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

                if ($res) {
                    if ($request->iscompany == null) {
                        Mail::to(env('APP_EMAIL'))->send(new UserRegMail($request->all()));
                        Mail::to($email)->send(new UserRegMail($request->all()));

                        $msg = "User Register Successfully";
                        if(!empty($countryCodeData->phonecode)){
                            $sendSms = LaraTwilio::notify('+'.$countryCodeData->phonecode.''.$request->input('register_phone'), $msg);
                        }else{
                            $sendSms = LaraTwilio::notify('+'.$country_code->phonecode.''.$request->input('register_phone'), $msg);
                        }

                    } else {
                        Mail::to(env('APP_EMAIL'))->send(new VendorRegMail($request->all()));
                        Mail::to($email)->send(new VendorRegMail($request->all()));
                        $msg = "Company Register Successfully!";
                        if(!empty($countryCodeData->phonecode)){
                            $sendSms = LaraTwilio::notify('+'.$countryCodeData->phonecode.''.$request->input('register_phone'), $msg);
                        }else{
                            $sendSms = LaraTwilio::notify('+'.$country_code->phonecode.''.$request->input('register_phone'), $msg);
                        }
                        //return $sendSms;
                    }
                }else{
                    return redirect('login')->with('error', 'Somthing went wrong!');
                }

            }

            if ($request->fromCheckoutPage == 1) {
                return redirect(route('checkout'))->with('msg', 'Register Successfully! Please login!');
            } else {

                Auth::login($user);
                return redirect('/login')->with('msg', 'Register Successfully! Please login!');
            }

        }else{
            return redirect('login')->with('error', 'Somthing went wrong!');
        }

    }

    public function membercreate(Request $request)
    {

        $countryCode= session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        $country_code = Country::where('name', $request->input('location_country'))->first();

        $email = $request->input('register_email');
        $temp = 0;
        $this->validate($request, [
            'register_name'             => ['required', 'max:255', new Capitalized, new DifferentFirstLastName],
            'register_last_name'        => ['required', 'max:255', new Capitalized],
            'register_email'            => ['required', 'email', 'unique:users,email', new isUserTemp],
            'register_password'         => 'required|string|min:6',
            'otp'                       => 'required',
        ]);

        $value = session('otp');
        if ($value == $request->otp) {

                $user                       = new User;
                $user->name                 = $request->input('register_name');
                $user->last_name            = $request->input('register_last_name');
                $user->email                = $request->input('register_email');
                $user->password             = Hash::make($request->input('register_password'));
                $user->phone                = $request->input('register_phone');

                $user->country              = $request->input('location_country');
                $user->country_code         = $country_code->phonecode ?? $countryCodeData->phonecode ?? $countryCode;
                $user->state                = $request->input('location_state');
                $user->city                 = $request->input('location_city');
                $user->address              = $request->input('location_address');

                $user->original_password    = $request->input('register_password');
                $user->role_id = 0;
                $user->user_type            = 'customer';

                $user->status = 1;
                $res = $user->save();

                if ($res) {
                    if ($request->iscompany == null) {
                        Mail::to(env('APP_EMAIL'))->send(new UserRegMail($request->all()));
                        Mail::to($email)->send(new UserRegMail($request->all()));

                        $msg = "User Register Successfully";
                        if(!empty($countryCodeData->phonecode)){
                            $sendSms = LaraTwilio::notify('+'.$countryCodeData->phonecode.''.$request->input('register_phone'), $msg);
                        }else{
                            $sendSms = LaraTwilio::notify('+'.$country_code->phonecode.''.$request->input('register_phone'), $msg);
                        }

                    } else {
                        Mail::to(env('APP_EMAIL'))->send(new VendorRegMail($request->all()));
                        Mail::to($email)->send(new VendorRegMail($request->all()));
                        $msg = "Company Register Successfully!";
                        if(!empty($countryCodeData->phonecode)){
                            $sendSms = LaraTwilio::notify('+'.$countryCodeData->phonecode.''.$request->input('register_phone'), $msg);
                        }else{
                            $sendSms = LaraTwilio::notify('+'.$country_code->phonecode.''.$request->input('register_phone'), $msg);
                        }
                        //return $sendSms;
                    }
                }else{
                    return redirect('login')->with('error', 'Somthing went wrong!');
                }



            if ($request->fromCheckoutPage == 1) {
                return redirect(route('checkout'))->with('msg', 'Register Successfully! Please login!');
            } else {

                Auth::login($user);
                return redirect('/login')->with('msg', 'Register Successfully! Please login!');
            }

        }else{
            return redirect('login')->with('error', 'Somthing went wrong!');
        }

    }

    public function competitionRegister(Request $request)
    {

        $countryCode= session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        $country_code = Country::where('id', $request->input('register_country'))->first();
        $email = $request->input('register_email');

        $this->validate($request, [
            'register_name'             => ['required', 'max:255', new Capitalized, new DifferentFirstLastName],
            'register_last_name'        => ['required', 'max:255', new Capitalized],
            'register_email'            => ['required', 'email', 'unique:users,email'],
            'register_phone'            => ['required', 'integer', 'phone', 'unique:users,phone'],
            'otp'                       => 'required',
        ]);

        $value = session('otp');
        if ($value == $request->otp) {

                $user                       = new User;
                $user->name                 = $request->input('register_name');
                $user->last_name            = $request->input('register_last_name');
                $user->email                = $request->input('register_email');
                $user->password             = Hash::make($request->input('register_phone'));
                $user->phone                = $request->input('register_phone');
                $user->original_password    = $request->input('register_phone');
                $user->role_id              = 0;
                $user->user_type            = 'competitor';
                $user->role_id              = 0;
                $user->status               = 1;
                $res = $user->save();

                if ($res) {

                        Mail::to(env('APP_EMAIL'))->send(new UserRegMail($request->all()));
                        Mail::to($email)->send(new UserRegMail($request->all()));

                        $msg = "Registration completed successfully.";
                        if(!empty($countryCodeData->phonecode)){
                            $sendSms = LaraTwilio::notify('+'.$countryCodeData->phonecode.''.$request->input('register_phone'), $msg);
                        }else{
                            $sendSms = LaraTwilio::notify('+'.$country_code->phonecode.''.$request->input('register_phone'), $msg);
                        }

                }else{
                    return redirect('competition-login')->with('error', 'Somthing went wrong!');
                }

            if ($request->fromCheckoutPage == 1) {
                return redirect(route('checkout'))->with('msg', 'Register Successfully! Please login!');
            } else {

                Auth::login($user);
                return redirect('/competition-login')->with('msg', 'Register Successfully! Please login!');
            }

        }else{
            return redirect('competition-login')->with('error', 'Somthing went wrong!');
        }

    }

}
