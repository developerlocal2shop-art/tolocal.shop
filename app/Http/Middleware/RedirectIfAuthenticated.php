<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        
        $currentPath = Route::getFacadeRoot()->current()->uri();
        //dd(auth()->guard('webadmin'));
        //echo $currentPath; die;

        // if (!Auth::guard('web')->check()) {
        //     return redirect('/');
        // }

        // if(strtolower($currentPath) == strtolower('login') && Auth::guard('web')->check()){
        //     return redirect(RouteServiceProvider::HOME);
        // }

        //if (Auth::guard('web')->check()) {
        // if (auth()->guard('webadmin')->check()) {
        //     return redirect()->route('admin.dashboard.index');
        // }elseif(auth()->guard('web')->check()){
        //     return redirect()->route('pages');
        // }

        //$user = auth()->guard('webadmin')->check();

        
       
        // if(strtolower($currentPath) == strtolower('login') && Auth::guard('web')->check()){
        //     return redirect(RouteServiceProvider::HOME);
        // }

        /*echo 'Auth Status : '.Auth::guard('web')->check();
        echo ' > currentPath : '.$currentPath;die;*/
        /* For logged in users login page redirects to dashboard */

        //echo $currentPath; die;
        
        if(strtolower($currentPath) == strtolower('login') && Auth::guard('web')->check()){
            return redirect(RouteServiceProvider::HOME);
        }elseif(strtolower($currentPath) == strtolower('admin/login') && Auth::guard('webadmin')->check()){
            return redirect()->route('admin.dashboard.index');
        }

        /* Redirect users to login page if not logged in */
                                    // if (!Auth::guard('web')->check() && strtolower($currentPath) <> strtolower('login')) {
                                    //     return redirect('/');
                                    // }
         return $next($request);
        //  $guards = empty($guards) ? [null] : $guards;

        // foreach ($guards as $guard) {
        //     if (Auth::guard($guard)->check()) {
        //         return redirect(RouteServiceProvider::HOME);
        //     }
        // }

        // return $next($request);
    }
}
