<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\client\Wishlist;
use App\Models\Product;
use App\Helpers\Helper;
use App\Models\AddToCompare;
use App\Models\client\Wishlistdirectory;
use App\Models\Saveforlatter;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if(auth()->guard('web')->check()==0){
            return redirect('/login');
        }
        $user_id = Auth::user()->id;
        $wishlists = Wishlist::with('user')->where('user_id', $user_id)->orderby('id', 'desc')->paginate(10);
        return view('account.wishlist', compact('user_id', 'wishlists'));
    }
    public function addToWishlist(Request $request, $id = 0)
    {
        $status = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
        if (isset($status->user_id) and isset($request->id)) {
            if ($status) {
                $arr = array('msg' => 'This item is already in wishlist', 'status' => true);
            }
            $removewislist = Wishlist::where(['product_id' => $request->id, 'user_id' => Auth::user()->id])->delete();
            if ($removewislist) {
                $arr = array('msg' => 'Removed from the wishlist', 'status' => true);
            }
            return Response()->json($arr);
        } else {
            $wishlist = new Wishlist();
            $wishlist->user_id                   = $request->user_login;
            $wishlist->product_id                = $request->id;
            $wishlist->save();
            if ($wishlist) {
                $arr = array('msg' => 'Added to wishlist', 'status' => true);
            }
            return Response()->json($arr);
        }
    }

    
    public function addtocompare(Request $request, $id = 0)
    {
        $status = AddToCompare::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
        if (isset($status->user_id) and isset($request->id)) {
            if ($status) {
                AddToCompare::where('user_id', $request->user_login)->where('product_id', $request->id)->delete();
                $arr = array('msg' => 'This item removed from compare list', 'status' => true);
            }
            return Response()->json($arr);
        } else {
            $Saveforlatter = new AddToCompare();
            $Saveforlatter->user_id      = $request->user_login;
            $Saveforlatter->product_id   = $request->id;
            $Saveforlatter->save();
            if ($Saveforlatter) {
                $arr = array('msg' => 'Product added to compare list', 'status' => true);
            }
            return Response()->json($arr);
        }
    }

    public function addsaveforlatter(Request $request, $id = 0)
    {
        $status = Saveforlatter::where('user_id', Auth::user()->id)->where('product_id', $request->id)->first();
        if (isset($status->user_id) and isset($request->id)) {
            if ($status) {
                $arr = array('msg' => 'This item is already in save for latter', 'status' => true);
            }
            // $removewislist = Saveforlatter::where(['product_id' => $request->id, 'user_id' => Auth::user()->id])->delete();
            // if ($removewislist) {
            //     $arr = array('msg' => 'Removed from the save for latter', 'status' => true);
            // }
            return Response()->json($arr);
        } else {
            $Saveforlatter = new Saveforlatter();
            $Saveforlatter->user_id      = $request->user_login;
            $Saveforlatter->product_id   = $request->id;
            $Saveforlatter->save();
            if ($Saveforlatter) {
                $arr = array('msg' => 'Product saved for latter', 'status' => true);
            }
            return Response()->json($arr);
        }
    }



    public function addDirectoryToWishlist(Request $request, $id = 0)
    {
        $status = Wishlistdirectory::where('user_id', Auth::user()->id)->where('directory_id', $request->id)->first();

        if (isset($status->user_id) and isset($request->id)) {
            if ($status) {
                $arr = array('msg' => 'This item is already in wishlist', 'status' => true);
            }
            $removewislist = Wishlistdirectory::where(['directory_id' => $request->id, 'user_id' => Auth::user()->id])->delete();
            if ($removewislist) {
                $arr = array('msg' => 'Removed from the wishlist', 'status' => true);
            }
            return Response()->json($arr);
        } else {
            $wishlist = new Wishlistdirectory();

            $wishlist->user_id                   = $request->user_login;
            $wishlist->directory_id              = $request->id;
            $wishlist->save();

            if ($wishlist) {
                $arr = array('msg' => 'Added to wishlist', 'status' => true);
            }
            return Response()->json($arr);
        }
    }

    public function moveToCart(Request $request){

        
        $Saveforlatter = Saveforlatter::where('id', $request->s_id)->delete();
        $getProduct = Product::where('id', $request->p_id)->first();
        //dd();
        if ($Saveforlatter) {

            $id = $request->p_id;
            $cart = session()->get('cart', []);
            if(isset($cart[$id])) {
                $cart[$id]['quantity']++;
            } else {
            $cart[$id] = [
                "p_id"                          => $id,
                "directory_id"                  => $getProduct->directory_id??0,
                "free_product_data"             => $getProduct->is_free_product??0,
                "name"                          => $getProduct->name??0,
                "quantity"                      => '1',
                "regularprice"                  => $request->product_price??0,
                "price"                         => $request->product_price??0,
                "image"                         => $request->product_image??0,
                "attr_id"                       => 0,
                "attr_name"                     => null,
                "attr_value"                    => '',
                "offer_dicount_type"            => '',
                "offer"                         => $getProduct->offer_id??0,
                "shopping_dicount_type"         => '',
                "shoppingcard"                  => '',
                "productstock"                  => $getProduct->stock??0,
                "v_stock"                       => '',
                "attribute_value"               => '',
                "product_number"                => $getProduct->supplier_product_no??0,
                "product_seo_slug"              => $getProduct->seo_url??0,
            ];
            }
            session()->put('cart', $cart);

            if ($user = Auth::user()) {
                $countsaveforlatter = Saveforlatter::where('user_id', $user->id)->count();
            }
            $arr = array('msg' => 'Moved item from saved for latter list to cart', 'status' => true, 'countsavelatter' => $countsaveforlatter);
        }else{
            $arr = array('msg' => 'Something went wrong', 'status' => false , 'countsaveforlatter' => '');
        }
        return Response()->json($arr);

    }

    public function cartSavelatterDelete(Request $request)
    {
        $Saveforlatter = Saveforlatter::where('id', $request->id)->delete();
        if ($Saveforlatter) {
            if ($user = Auth::user()) {
                $countsaveforlatter = Saveforlatter::where('user_id', $user->id)->count();
            }
            $arr = array('msg' => 'Removed product from saved for latter list', 'status' => true, 'countsavelatter' => $countsaveforlatter);
        }else{
            $arr = array('msg' => 'Something went wrong', 'status' => false , 'countsaveforlatter' => '');
        }
        return Response()->json($arr);
    }

    public function comparisonDelete(Request $request)
    {
        
        $comparison = AddToCompare::where('id', $request['id'])->delete();
        if ($comparison) {
            if ($user = Auth::user()) {
                $count_comparison = AddToCompare::where('user_id', $user->id)->count();
            }
            $arr = array('msg' => 'Removed product from comparison list', 'status' => true, 'countsavelatter' => $count_comparison);
        }else{
            $arr = array('msg' => 'Something went wrong', 'status' => false , 'countsaveforlatter' => '');
        }
        return Response()->json($arr);
    }

    public function directoryWislishDetails()
    {
        if (Auth::check()) {
            $user_id = Auth::user()->id;
            $wishlists = DB::table('wislist_directory as wld')->select('wld.*', 'd.title as directory_name')->where('wld.user_id', $user_id)->orderby('wld.id', 'desc')
                ->leftjoin('directory as d', 'd.id', '=', 'wld.directory_id')->where('d.deleted','0')->paginate(10);
            return view('account.wishlistdirectory', compact('user_id', 'wishlists'));
        } else {
            return redirect()->route('pages');
        }
    }
    public function productWishlistDelete($id)
    {
        Wishlist::where('id', $id)->delete();
        return redirect('account/wishlist-product')->with('msg', 'Removed from the wishlist');
    }
    public function directoryWishlistDelete($id)
    {
        Wishlistdirectory::where('id', $id)->delete();
        return redirect('account/wishlist-directory')->with('msg', 'Removed from the wishlist');
    }
}
