<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $role
     * @param $permission
     * @return mixed
     */

    public function handle($request, Closure $next, $role, $permission = null)
    {
        
        if(!Auth::guard('web')->check()){
            return redirect()->route('login');
        }

        if(Auth::user()->role_id != $role) {
             abort(404);
        }
        return $next($request);
    }
}
