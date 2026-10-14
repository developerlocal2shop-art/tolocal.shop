<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ModuleAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        if( auth()->user()->parent_id == 0){
            return $next($request);
        }else{

        if(request()->path() == 'company/reports/dashboard'){
            if ($user->hasModule('reports') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company-information'){
            if ($user->hasModule('company-info') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/products' || request()->path() == 'company/add-product'){
            if ($user->hasModule('products') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/offline-reports' || request()->path() == 'company/allvisitors'|| request()->path() == 'company/allreceiptclosing' || request()->path() == 'company/allextra/reports'){
            if ($user->hasModule('offline-reports') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/trademarks' || request()->path() == 'company/add-trademarks' || request()->path() == 'company/trademarks-category'){
            if ($user->hasModule('trademarks') == false){
                abort(404);
            }
        }

        if(request()->path() == 'company/shoppingcard' || request()->path() == 'company/add-shoppingcard'){
            if ($user->hasModule('shopping-card') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/blog' || request()->path() == 'company/add-blog'){
            if ($user->hasModule('blogs') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/events' || request()->path() == 'company/add-events'){
            if ($user->hasModule('events') == false ){
                abort(404);
            }
        }
        if(request()->path() == 'company/advertisement' || request()->path() == 'company/add-advertisement'){
            if ($user->hasModule('advertisement') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/product/offer' || request()->path() == 'company/product/add-offer'){
            if ($user->hasModule('offers') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/orders'){
            if ($user->hasModule('orders') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/directory-own-tags' || request()->path() == 'company/add-directory-own-tags'){
            if ($user->hasModule('directory-own-tags') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/discount/voucher' || request()->path() == 'company/discount/voucher/add-voucher'){
            if ($user->hasModule('coupons') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/discount/giftvoucher' || request()->path() == 'company/discount/giftvoucher/add-giftvoucher'){
            if ($user->hasModule('gift-voucher') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/discount/freeproductsdiscount' || request()->path() == 'company/discount/freeproductsdiscount/add-productDiscount'){
            if ($user->hasModule('products-discount') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/booking'){
            if ($user->hasModule('booking') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/role' || request()->path() == 'company/role-create'){
            if ($user->hasModule('role') == false){
                abort(404);
            }
        }
        if(request()->path() == 'company/mail-listing' || request()->path() == 'company/mail' || request()->path() == 'company/add-mail-template'){
            if ($user->hasModule('mail') == false){
                abort(404);
            }
        }

        return $next($request);
       }
    }
}
