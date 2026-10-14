<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use App\Models\Country;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class LoginController extends Controller
{

    use AuthenticatesUsers;

    protected function guard()
        {
            return Auth::guard('web');
        }

    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
        //$this->middleware('isuserlogin')->except(['logout']);
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
        return request()->ip(); // it will return server ip when no client ip found
    }

    public function getuserLogin(Request $request)
    {

        $previousSegments = explode('/', trim(url()->previous(), '/'));
        $previous_url = url()->previous(); // default

        if (isset($previousSegments[3]) && $previousSegments[3] === 'advertisement') {
            $advertisementquestionnaire_id = $previousSegments[5] ?? null;
            if ($advertisementquestionnaire_id) {
                $previous_url = url('account/advertisementquestionnaire?advertisementid=' . $advertisementquestionnaire_id);
            }
        }
        if (!session()->has('url.intended')) {
            session(['url.intended' => $previous_url]);
        }
        $user = auth()->guard('web')->check();

        $preURLBooking = explode("=", url()->previous());
        Session::put('bookingtrack', end($preURLBooking));

        if ($user == 1) {
            return redirect()->route('pages');
        } else {
            //return view('auth.client.login');
            return view('auth.client.memberlogin');
        }
    }

    public function getCompetitionLogin(Request $request)
    {

        $previousSegments = explode('/', trim(url()->previous(), '/'));
        $previous_url = url()->previous(); // default

        if (isset($previousSegments[3]) && $previousSegments[3] === 'advertisement') {
            $advertisementquestionnaire_id = $previousSegments[5] ?? null;
            if ($advertisementquestionnaire_id) {
                $previous_url = url('account/advertisementquestionnaire?advertisementid=' . $advertisementquestionnaire_id);
            }
        }
        if (!session()->has('url.intended')) {
            session(['url.intended' => $previous_url]);
        }
        $user = auth()->guard('web')->check();

        $preURLBooking = explode("=", url()->previous());
        Session::put('bookingtrack', end($preURLBooking));

        if ($user == 1) {
            return redirect()->route('pages');
        } else {
            return view('auth.client.competition-login');
        }
    }

    public function competitionUniqueCheck(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (!in_array($field, ['register_email', 'phone'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid field.'], 400);
        }

        if (empty($value)) {
            return response()->json(['status' => 'success', 'exists' => false]);
        }

        if ($field === 'register_email') {
            $exists = User::where('email', $value)->exists();
        } else {
            $exists = User::where('phone', $value)->exists();
        }

        return response()->json(['status' => 'success', 'exists' => $exists]);
    }

    public function getVendorLogin(Request $request)
    {

        $previousSegments = explode('/', trim(url()->previous(), '/'));
        $previous_url = url()->previous(); // default

        if (isset($previousSegments[3]) && $previousSegments[3] === 'advertisement') {
            $advertisementquestionnaire_id = $previousSegments[5] ?? null;
            if ($advertisementquestionnaire_id) {
                $previous_url = url('account/advertisementquestionnaire?advertisementid=' . $advertisementquestionnaire_id);
            }
        }
        if (!session()->has('url.intended')) {
            session(['url.intended' => $previous_url]);
        }
        $user = auth()->guard('web')->check();

        $preURLBooking = explode("=", url()->previous());
        Session::put('bookingtrack', end($preURLBooking));

        if ($user == 1) {
            return redirect()->route('pages');
        } else {
            return view('auth.client.vendor_login');
        }
    }

    public function accessByotp()
    {
        $user = 0;
        $user = auth()->guard('web')->check();
        if($user==1){
            return redirect()->route('pages');
        } else {
                return view('auth.client.otplogin');
        }
    }

    protected function postLogin(Request $request)
    {
        $userIP = $this->getIp();
        $bookingtrack = session()->get('bookingtrack');
        $counrtyDetails = @unserialize(file_get_contents('http://ip-api.com/php/'.$userIP));
        if($userIP=='127.0.0.1' || $userIP=='localhost' ){
            $countryName = 'India';
        }else{
            $countryName       =   $counrtyDetails['country']??'';
        }
        $countryname = Country::where('name', $countryName)->first();

        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $email_f = str_replace(' ', '', $request->email);

        $useremail = User::where('email', $email_f)->first();
        if (!$useremail) {
            throw ValidationException::withMessages([
                $this->username() => ['Credentials do not match our database.'],
            ])->redirectTo('login');
        }

        if($useremail->directory_id == 0 || $useremail->directory_id == null || $useremail->directory_id == '' || $useremail->directory_id == 1){
            if($this->guard()->validate($this->credentials($request))) {

                if($email_f != $useremail->email){
                    throw ValidationException::withMessages([
                        $this->username() => ['Credentials do not match our database.'],
                    ])->redirectTo('login');
                }else{
                    if(Auth::attempt(['email' => $email_f, 'password' => $request->password, 'status' => 1])) {

                        $userCountry = auth()->user()->country;
                        $phonecode= auth()->user()->country_code;
                        $country_code = Country::where('name', $userCountry)->first();

                        User::where('id', auth()->user()->id)->update(['last_login_at' => now() ]);

                        if($userCountry==null && $phonecode==null){
                            if(!empty($countryname)){
                                User::where('id', auth()->user()->id)
                                    ->update([
                                    'country' => $countryname->name,
                                    'country_code' => $countryname->phonecode
                                ]);
                            }else{
                                User::where('id', auth()->user()->id)
                                    ->update([
                                    'country' => 'India',
                                    'country_code' => '91'
                                ]);
                            }

                        }else if($phonecode == null){
                            User::where('id', auth()->user()->id)
                                ->update([
                                'country_code' => $country_code->phonecode
                            ]);
                        }

                        if($request->fromCheckoutPage==1){
                            return redirect(route('checkout'));
                        }else{
                            if($bookingtrack=="vendormail"){
                                return redirect(route('company.booking.index'));
                            }else{
                                if (!session()->has('url.intended')) {
                                    $previousUrl = url()->previous();

                                    if ($previousUrl !== route('login')) {
                                        session(['url.intended' => $previousUrl]);
                                    }
                                }else{
                                    return redirect()->intended('/pages');
                                }

                            }
                        }

                    }  else {
                        $this->incrementLoginAttempts($request);

                        throw ValidationException::withMessages([
                            $this->username() => ['This account is not activated.'],
                        ])->redirectTo('login');
                    }
                }
            } else {
                $this->incrementLoginAttempts($request);
                throw ValidationException::withMessages([
                        $this->username() => ['Credentials do not match our database.'],
                    ])->redirectTo('login');
            }
        } else {
            throw ValidationException::withMessages([
                $this->username() => ['Sorry, this is a vendor account. Please use vendor login.'],
            ])->redirectTo('login');
        }
    }

    protected function postCompetitionLogin(Request $request)
    {
        // dd($request->all());

        $userIP = $this->getIp();
        $bookingtrack = session()->get('bookingtrack');
        $counrtyDetails = @unserialize(file_get_contents('http://ip-api.com/php/'.$userIP));
        if($userIP=='127.0.0.1' || $userIP=='localhost' ){
            $countryName = 'India';
        }else{
            $countryName       =   $counrtyDetails['country']??'';
        }
        $countryname = Country::where('name', $countryName)->first();

        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $email_f = str_replace(' ', '', $request->email);

        $useremail = User::where('email', $email_f)->first();
        if (!$useremail) {
            throw ValidationException::withMessages([
                $this->username() => ['Credentials do not match our database.'],
            ])->redirectTo('competition-login');
        }

        if($useremail->directory_id == 0 || $useremail->directory_id == null || $useremail->directory_id == '' || $useremail->directory_id == 1){
            if($this->guard()->validate($this->credentials($request))) {

                if($email_f != $useremail->email){
                    throw ValidationException::withMessages([
                        $this->username() => ['Credentials do not match our database.'],
                    ])->redirectTo('competition-login');
                }else{
                    if(Auth::attempt(['email' => $email_f, 'password' => $request->password, 'status' => 1])) {

                        User::where('id', auth()->user()->id)->update(['last_login_at' => now() ]);

                        $userCountry = auth()->user()->country;
                        $phonecode= auth()->user()->country_code;
                        $country_code = Country::where('name', $userCountry)->first();

                        if($userCountry==null && $phonecode==null){
                            if(!empty($countryname)){
                                User::where('id', auth()->user()->id)
                                    ->update([
                                    'country' => $countryname->name,
                                    'country_code' => $countryname->phonecode
                                ]);
                            }else{
                                User::where('id', auth()->user()->id)
                                    ->update([
                                    'country' => 'India',
                                    'country_code' => '91'
                                ]);
                            }

                        }else if($phonecode == null){
                            User::where('id', auth()->user()->id)
                                ->update([
                                'country_code' => $country_code->phonecode
                            ]);
                        }

                        if($request->fromCheckoutPage==1){

                            return redirect(route('checkout'));
                        }else{

                            if($bookingtrack=="vendormail"){

                                return redirect(route('company.booking.index'));
                            }else{

                                return redirect('account/questionnaire');
                            }

                        }

                    }  else {

                        $this->incrementLoginAttempts($request);

                        throw ValidationException::withMessages([
                            $this->username() => ['This account is not activated.'],
                        ])->redirectTo('competition-login');
                    }
                }
            } else {
                $this->incrementLoginAttempts($request);
                throw ValidationException::withMessages([
                        $this->username() => ['Credentials do not match our database.'],
                    ])->redirectTo('competition-login');
            }
        } else {
            throw ValidationException::withMessages([
                $this->username() => ['Sorry, this is a vendor account. Please use vendor login.'],
            ])->redirectTo('competition-login');
        }
    }

    protected function vendorpostLogin(Request $request)
    {

        $userIP = $this->getIp();
        $bookingtrack = session()->get('bookingtrack');
        $counrtyDetails = @unserialize(file_get_contents('http://ip-api.com/php/'.$userIP));
        if($userIP=='127.0.0.1' || $userIP=='localhost' ){
            $countryName = 'India';
        }else{
            $countryName       =   $counrtyDetails['country']??'';
        }
        $countryname = Country::where('name', $countryName)->first();

        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $email_f = str_replace(' ', '', $request->email);

        $useremail = User::where('email', $email_f)->first();

        if (!$useremail) {
            throw ValidationException::withMessages([
                $this->username() => ["Credentials do not match our vendor's database."],
            ])->redirectTo('vendor-login');
        }

        if($useremail->directory_id != 0 && $useremail->directory_id != null && $useremail->directory_id != '' && $useremail->directory_id != 1){

            if($this->guard()->validate($this->credentials($request))) {

                if($email_f != $useremail->email){
                    throw ValidationException::withMessages([
                        $this->username() => ['Credentials do not match our database.'],
                    ])->redirectTo('vendor-login');
                }else{
                    if(Auth::attempt(['email' => $email_f, 'password' => $request->password, 'status' => 1])) {

                        User::where('id', auth()->user()->id)->update(['last_login_at' => now() ]);

                        $userCountry = auth()->user()->country;
                        $phonecode= auth()->user()->country_code;
                        $country_code = Country::where('name', $userCountry)->first();

                        if($userCountry==null && $phonecode==null){
                            if(!empty($countryname)){
                                User::where('id', auth()->user()->id)
                                    ->update([
                                    'country' => $countryname->name,
                                    'country_code' => $countryname->phonecode
                                ]);
                            }else{
                                User::where('id', auth()->user()->id)
                                    ->update([
                                    'country' => 'India',
                                    'country_code' => '91'
                                ]);
                            }

                        }else if($phonecode == null){
                            User::where('id', auth()->user()->id)
                                ->update([
                                'country_code' => $country_code->phonecode
                            ]);
                        }

                        if($request->fromCheckoutPage==1){
                            return redirect(route('checkout'));
                        }else{
                            if($bookingtrack=="vendormail"){
                                return redirect(route('company.booking.index'));
                            }else{
                                return redirect(route('company.report.dashboard'));
                                //return redirect()->intended('/pages');
                            }
                        }

                    }  else {
                        $this->incrementLoginAttempts($request);

                        throw ValidationException::withMessages([
                            $this->username() => ['This account is not activated.'],
                        ])->redirectTo('vendor-login');
                    }
                }
            } else {
                $this->incrementLoginAttempts($request);
                throw ValidationException::withMessages([
                        $this->username() => ['Credentials do not match our database.'],
                    ])->redirectTo('vendor-login');
            }

        } else {
            throw ValidationException::withMessages([
                $this->username() => ['Sorry, this is a user account. Please use user login.'],
            ])->redirectTo('vendor-login');
        }

    }

    protected function logout()
    {
        Auth::guard('web')->logout();
        session()->forget('cart');
        return redirect(route('login'));
    }
    // Facebook Login
    public function redirectToFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback(Request $request){
        try {
            if($request->input('error')){
                return redirect()->route('login');
            }else{
                $user = Socialite::driver('facebook')->user();
                $saveUser = User::updateOrCreate([
                    'email' => $user->getEmail(),
                ],[
                    'name' => $user->getName(),
                    'password' => Hash::make($user->getName().'@'.$user->getId()),
                    'role_id'       => 0,
                    'status'        => 1,
                ]);
                Auth::login($saveUser);

                return redirect()->route('pages.home');
            }
        }catch (\Throwable $th) {
            throw $th;
            return redirect()->route('pages.home');
        }catch (\Exception $e) {
           return redirect()->route('pages.home');
        }
    }
    // Google Login
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request){

        try {
            if($request->input('error')){
                return redirect()->route('login');
            }else{
                $userdata = Socialite::driver('google')->user();
                //$existuser = User::where('email', $userdata->getEmail())->first();
                $saveUser = User::updateOrCreate(
                    [
                        'email' => $userdata->getEmail(),
                    ],[
                        'name'          => $userdata->user['given_name'],
                        'last_name'     => $userdata->user['family_name'],
                        'password'      => Hash::make($userdata->getName().'@'.$userdata->getId()),
                        'role_id'       => 0,
                        'status'        => 1,
                    ]
                );

                // Check if password or original_password is missing
                if (empty($saveUser->password) || empty($saveUser->original_password) || empty($saveUser->original_password)) {

                    $randomPassword = Str::random(12); // e.g. Ab3xPq9LmN2Z
                    $saveUser->update([
                        'password'          => Hash::make($randomPassword),
                        'original_password' => $randomPassword,
                        'last_name' => 'Last name',
                    ]);
                }

                Auth::login($saveUser);
                return redirect()->route('pages.home');
            }
        } catch (\Throwable $th) {
            throw $th;
            return redirect()->route('pages.home');
        }catch (\Exception $e) {
            return redirect()->route('pages.home');
         }
    }

}
