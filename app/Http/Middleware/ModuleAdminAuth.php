<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ModuleAdminAuth
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
        $user = auth()->guard('webadmin')->user();
        //dd(request()->path());

        if(request()->path() == 'admin/dashboard'){
            if ($user->hasModule('dashboard') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/reports/dashboard'){
            if ($user->hasModule('reports') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/amenities' || request()->path() == 'admin/amenity-category' || request()->path() == 'admin/add-amenity' || request()->path() == 'admin/add-amenity-category'){
            
            
            if ($user->hasModule('amenities') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/categories' || request()->path() == 'admin/category-cusfield' || request()->path() == 'admin/add-category' || request()->path() == 'admin/add-category-cusfield'){
            if ($user->hasModule('categories') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/tags' || request()->path() == 'admin/owntags' || request()->path() == 'admin/directory-own-tags' || request()->path() == 'admin/add-tags' || request()->path() == 'admin/add-owntags' || request()->path() == 'admin/add-directory-own-tags'){
            if ($user->hasModule('tags') == false){ 
                abort(404);
            }
        }
        if(request()->path() == 'admin/directory' || request()->path() == 'admin/add-directory' || request()->path() == 'admin/business'){
            if ($user->hasModule('directory') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/trademarks' || request()->path() == 'admin/add-trademarks' || request()->path() == 'admin/trademarks-category'){
            if ($user->hasModule('trademarks') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/product' || request()->path() == 'admin/add-product' || request()->path() == 'admin/inventory'){
            if ($user->hasModule('products') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/offer' || request()->path() == 'admin/add-offer'){
            if ($user->hasModule('product_offer') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/normal-template' || request()->path() == 'admin/holiday-template' || request()->path() == 'admin/exceptionalholiday-template'){
            if ($user->hasModule('templates') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/orders' || request()->path() == 'admin/order-status'){
            if ($user->hasModule('orders') == false){
                abort(404);
            }
        }

        if(request()->path() == 'admin/review' || request()->path() == 'admin/add-review'){
            if ($user->hasModule('reviews') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/discount/voucher' || request()->path() == 'admin/discount/voucher/add-voucher'){
            if ($user->hasModule('discount') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/discount/giftvoucher' || request()->path() == 'admin/discount/giftvoucher/add-giftvoucher'){
            if ($user->hasModule('discount') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/discount/freeproductsdiscount' || request()->path() == 'admin/discount/freeproductsdiscount/add-productDiscount'){
            if ($user->hasModule('discount') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/booking'){
            if ($user->hasModule('appointment_booking') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/blog' || request()->path() == 'admin/add-blog'){
            if ($user->hasModule('blogs') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/events' || request()->path() == 'admin/add-events'){
            if ($user->hasModule('events') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/advertisement' || request()->path() == 'admin/add-advertisement'){
            if ($user->hasModule('advertisement') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/user' || request()->path() == 'admin/user-registration'){
            if ($user->hasModule('users') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/languages' || request()->path() == 'admin/add-languages'){
            if ($user->hasModule('languages') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/systemsetting' || request()->path() == 'admin/frontendsetting'){
            if ($user->hasModule('settings') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/mapsetting' || request()->path() == 'admin/smtpsetting' || request()->path() == 'admin/currency'){
            if ($user->hasModule('settings') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/permission' || request()->path() == 'admin/permission-create' || request()->path() == 'admin/role' || request()->path() == 'admin/role-create'){
            if ($user->hasModule('permission_managment') == false){
                abort(404);
            }
        }
        if(request()->path() == 'admin/mail-listing' || request()->path() == 'admin/mail' || request()->path() == 'admin/add-mail-template'){
            if ($user->hasModule('mail') == false){
                abort(404);
            }
        }
        

        return $next($request);
    }
}
