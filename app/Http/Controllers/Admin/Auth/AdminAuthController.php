<?php

namespace App\Http\Controllers\Admin\Auth;

use Validator;
use Session;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\Admin;

class AdminAuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function getLogin()
    {
        $user = 0;
        $user = auth()->guard('webadmin')->check();
              
        if($user==1)
       {
           /*User is logged, then cannot login*/
           return back();
       }
       else
       {
            return view('auth.admin.login');
       }
        //return view('auth.admin.login');
    }

    /**
     * Show the application loginprocess.
     *
     * @return \Illuminate\Http\Response
     */
    public function postLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (auth()->guard('webadmin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')]))
        {
            $user = auth()->guard('webadmin')->user();
            
            if(auth()->guard('webadmin')->user()->role_id==1){
                \Session::put('success','You are Login successfully!!');
                return redirect()->route('admin.dashboard.index');
            }else{
                \Session::flush();
                \Session::put('success','You have no access to login');        
                return redirect(route('adminLogin'));
            }
            
            
        } else {
            return back()->with('error','wrong username and password.')->withInput($request->only('email'));
        }

    }

    /**
     * Show the application logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->guard('webadmin')->logout();
        \Session::flush();
       // \Session::put('success','You are logout successfully');        
        return redirect(route('adminLogin'));
    }
}
