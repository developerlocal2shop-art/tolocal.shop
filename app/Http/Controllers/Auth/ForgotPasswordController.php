<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\Country;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use Illuminate\Support\Facades\Session;

class ForgotPasswordController extends Controller
{
    //use SendsPasswordResetEmails;


    // public function __construct()
    // {
    //     $this->middleware('guest');
    // }
    
    public function showForgot()
    {
        return view('auth.client.forgot');
    }

    public function forgotlink(Request $request)
    {
        //dd($request->all());
        $this->validate($request, [
            'email' => 'required|email|exists:users',
        ]);

        $useremail = User::where('email', $request->email)->first();
        
        $token = Str::random(64);
        //create a new token to be sent to the user.
        $maildata = [
            'email' => $request->email,
            'token' => $token
        ];

        if($request->email != $useremail->email){

            return back()->with('error','Please enter valid email!');

        }else{

            //create a new token to be sent to the user.
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            Mail::to($request->email)->send(new ForgetPasswordMail($maildata));

            //return view('auth.client.forgetPassword',['token' => $token ]);

            return back()->with('message', 'We have e-mailed your password reset link!');
        }

        //return back()->with('message', 'We have e-mailed your password reset link!');
        // return url(route('email.forgetPassword', [
        //     'token' => $token,

        // ], false));

        // return back()->with('message', 'We have e-mailed your password reset link!');

    }

    public function showResetPasswordForm($token) {
        return view('auth.client.reset', ['token' => $token]);
     }

     public function submitResetPasswordForm(Request $request)
      {

        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required'
        ]);

        $countryCode= session()->get('countrycode');

        $countryCodeData = session()->get('countrynamecode');

        $updatePassword = DB::table('password_resets')
                            ->where([
                            'email' => $request->email,
                            'token' => $request->token
                            ])
                            ->first();
        $userdata = User::where('email', $request->email)->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }
        if(!$userdata){
            return back()->withInput()->with('error', 'Wrong login credential!');
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();
        
        
        $msg = "Your password has been changed! ";
        if(!empty($countryCodeData->phonecode)){
            $sendSms = LaraTwilio::notify('+'.$countryCodeData->phonecode.$userdata->phone, $msg);
        }else{
            $sendSms = LaraTwilio::notify('+'.'91'.$userdata->phone, $msg);
        }
        

        return redirect('/login')->with('success', 'Your password has been changed!');
    }
}
