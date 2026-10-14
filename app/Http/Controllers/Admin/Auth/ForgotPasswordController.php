<?php

namespace App\Http\Controllers\Admin\Auth;

use Validator;
use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordAdminMail;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Hash;
use App\Models\Country;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); 
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); 
    }

    public function showForgot()
    {
        return view('auth.admin.forgot');
    }

    public function forgotlink(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:admin',
        ]);
        $token = Str::random(64);
        $maildata = [
            'email' => $request->email,
            'token' => $token
        ];
        Mail::to($request->email)->send(new ForgetPasswordAdminMail($maildata));
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function showResetPasswordForm($token)
    {
        return view('auth.admin.reset', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admin',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required'
        ]);
        //$userIP = $this->getIp();
        // $new_arr[] = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $userIP));
        // $countryCode       =   $new_arr[0]['geoplugin_countryCode'];
        // $new_arr[] = @unserialize(file_get_contents('http://ip-api.com/php/'.$userIP));
        // $countryCode       =   $new_arr['countryCode'];
        // $countryCodeData = Country::where('code', $countryCode)->first();
        $updatePassword = DB::table('password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();
        if (!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }
        $user = Admin::where('email', $request->email)->update(['password' => Hash::make($request->password)]);
        DB::table('password_resets')->where(['email' => $request->email])->delete();
        //$msg = "Your password has been changed! ";
        //$sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . '7678409612', $msg);
        return redirect('admin/login')->with('success', 'Your password has been changed!');
    }
}
