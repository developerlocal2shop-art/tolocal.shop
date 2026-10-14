<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Product;
use App\Models\Offer;
use App\Helpers\Helper;
use App\Models\Cart;
use App\Models\CompetitionWinners;
use App\Models\IsUsedCouponCode;
use App\Models\ProductAttributesVariation;
use App\Models\Saveforlatter;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cartList()
    {
        $cartItems = session()->get('cart');

        if ($user = Auth::user()) {
            $saveforlatter = Saveforlatter::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        } else {
            $saveforlatter = [];
        }
        return view('client.cartlist', compact('cartItems','saveforlatter'));
    }
    public function addToCart(Request $request, $id)
    {
        $product = Product::with('user')->findOrFail($id);
        $cart = session()->get('cart', []);
        if(isset($cart[$id])) {

            //$cart[$id]['quantity']++;

            if (!isset($cart[$id]['quantity'])) {
                $cart[$id]['quantity'] = 0;
            }
            $cart[$id]['quantity'] += $request->input('quantity');
            
            $cart[$id] = array_merge($cart[$id], [
                "attr_id"          => $request->input('attr_id'),
                "p_id"                          => $id,
                "directory_id"                  => $request->input('directory_id'),
                "free_product_data"             => $request->input('free_product_data'),
                "name"                          => $request->input('name'),
                "regularprice"                  => $request->input('regularprice'),
                "price"                         => $request->input('price'),
                "image"                         => $request->input('image'),
                "attr_id"                       => $request->input('attr_id'),
                "attr_name"                     => $request->input('attr_name'),
                "attr_value"                    => $request->input('attr_value'),
                "offer_dicount_type"            => $request->input('offer_dicount_type'),
                "offer"                         => $request->input('offer'),
                "shopping_dicount_type"         => $request->input('shopping_dicount_type'),
                "shoppingcard"                  => $request->input('shoppingcard'),
                "productstock"                  => $request->input('productstock'),
                "v_stock"                       => $request->input('v_stock'),
                "attribute_value"               => $request->input('attribute_value'),
                "product_number"                => $request->input('supplier_product_no'),
                "product_seo_slug"              => $request->input('product_slug'),
            ]);
            // $cart[$id] = [
            //     "p_id"                          => $id,
            //     "quantity"                      => $request->input('quantity'),
            //     "directory_id"                  => $request->input('directory_id'),
            //     "attr_id"                       => $request->input('attr_id'),
            //     "attr_name"                     => $request->input('attr_name'),
            //     "attr_value"                    => $request->input('attr_value'),
            //     "productstock"                  => $request->input('productstock'),
            //     "v_stock"                       => $request->input('v_stock'),
            //     "attribute_value"               => $request->input('attribute_value'),
            // ];
        } else {
           $cart[$id] = [
                "p_id"                          => $id,
                "directory_id"                  => $request->input('directory_id'),
                "free_product_data"             => $request->input('free_product_data'),
                "name"                          => $request->input('name'),
                "quantity"                      => $request->input('quantity'),
                "regularprice"                  => $request->input('regularprice'),
                "price"                         => $request->input('price'),
                "image"                         => $request->input('image'),
                "attr_id"                       => $request->input('attr_id'),
                "attr_name"                     => $request->input('attr_name'),
                "attr_value"                    => $request->input('attr_value'),
                "offer_dicount_type"            => $request->input('offer_dicount_type'),
                "offer"                         => $request->input('offer'),
                "shopping_dicount_type"         => $request->input('shopping_dicount_type'),
                "shoppingcard"                  => $request->input('shoppingcard'),
                "productstock"                  => $request->input('productstock'),
                "v_stock"                       => $request->input('v_stock'),
                "attribute_value"               => $request->input('attribute_value'),
                "product_number"                => $request->input('supplier_product_no'),
                "product_seo_slug"              => $request->input('product_slug'),
            ];
        }
        session()->put('cart', $cart);
        return redirect()->route('cart.list')->with('success', 'Product added to cart successfully!');
    }
    public function update(Request $request)
    {   
        //if($request->id && $request->quantity){
        if($request->id && $request->quantity && $request->productstock){
            if($request->quantity <= $request->productstock){
            
                $cart = session()->get('cart');
                $cart[$request->id]["quantity"]     = $request->quantity;
                $cart[$request->id]["productstock"] = $request->productstock;
                session()->forget('coupon');
                session()->put('cart', $cart);
                session()->flash('success', 'Cart updated successfully');
            }
            return response()->json(['quantity'=>$request->quantity,'productstock'=>$request->productstock]);
        }
    }
    public function clearAllCart()
    {
        session()->forget('cart');
        session()->forget('coupon');
        return redirect()->route('cart.list')->with('success', 'No Item in cart!');
    }
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
                session()->forget('coupon');
            }
            session()->flash('success', 'Product removed successfully');
        }
    }
    
    public function applyCouponCode(Request $request)
    {

        $couponcode = $request->couponcode;

        if ($user = Auth::user()) {
            $user_id = $user->id;
        } else {
            $user_id = null;
        }
        $isUsedCouponCode = IsUsedCouponCode::where('coupon_code', $couponcode)->where('user_id', $user_id)->first();

       $isUsedCompetitionWinners = CompetitionWinners::where('voucher_code', $couponcode)
                                    ->where('customer_id', $user_id)
                                    ->value('status');

        if($isUsedCompetitionWinners === '0'){
            return response()->json(['error'=>'Prize coupon code has already been used.']);
        }elseif($isUsedCompetitionWinners === '1'){

            $get_voucherData = CompetitionWinners::with(['advertisement' => function($query) {
                                 $query->where('end_date', '>=', date('Y-m-d'));
                                }])->where('customer_id', $user_id)->where('status','1')->where('voucher_code', $couponcode)->first();

            if(!$get_voucherData->advertisement){
                return response()->json(['error'=>'Prize coupon code is invalid!']);
            }else{
                $cart = session()->get('cart');
                $productTotal = 0;$subtotal = 0;$subtotalwthoutcoupn = 0;
                $productTotalno = 0;$flagyes = 1; $flagno = 1;$is_coupon_product_id=array();$productCoupon_id=array();

                $couponData = [
                    "code"                  => $get_voucherData->voucher_code,
                    "discount_type"         => 'percentagediscount',
                    "discount_value"        => $get_voucherData->advertisement->price,
                ];
                $product_id = array();
                $get_productData = DB::table('product')->select('*')->where('directory_id', $get_voucherData->vendor_id)->get()->toArray();
                
                foreach($get_productData as $value){
                    $product_id[] = $value->id;
                }
                foreach($cart as $key => $cartval){
                    if(in_array($key,$product_id)){
                        $productCoupon_id[]=$key;
                        $flagyes = 0;
                        $productTotal = $cartval['price']*$cartval['quantity'];
                            $percentagediscount = ($productTotal)-(($productTotal)*($get_voucherData->advertisement->price/100));
                            $percentagediscount_sub_tot = $productTotal;
                        $is_coupon_product_id[]=[
                            'id'=> $key,
                            'productcartprice'=> $productTotal,
                            'poroductdisprice'=> $percentagediscount,
                        ];
                        $subtotal += $percentagediscount_sub_tot;
                    }else{
                        $flagno = 0;
                        $subtotalwthoutcoupn += $cartval['price']*$cartval['quantity'];
                        $productTotalno += $cartval['price']*$cartval['quantity'];
                    }
                }
                $discountvalue = (($productTotal)*($get_voucherData->advertisement->price/100));
                $subtotal = $cartval['price']*$cartval['quantity'];
                $productTotal = $productTotal-$discountvalue;
                if($flagyes==0 && $flagno==0){
                    $productTotal += $productTotalno;
                    $coupon = [
                        "subtotal"          => $subtotal,
                        "totaldiscount"     => $discountvalue,
                        "couponcode"        => $get_voucherData->voucher_code,
                        "coupontype"        => 'percentagediscount',
                        "couponvalue"       => $get_voucherData->advertisement->price,
                        "productId"         => $productCoupon_id,
                        "code_type"      => 'prizecode',
                    ];
                    session()->put('coupon', $coupon);
                    return response()->json(['subtotal'=>$subtotal,'productTotal'=>$productTotal,'discountval'=>$discountvalue,'couponproductId'=>$is_coupon_product_id,'couponData'=>$couponData]);
                }
                if($flagyes==0 && $flagno==1){
                    $coupon = [
                        "subtotal"          => $subtotal,
                        "totaldiscount"     => $discountvalue,
                        "couponcode"        => $get_voucherData->voucher_code,
                        "coupontype"        => 'percentagediscount',
                        "couponvalue"       => $get_voucherData->advertisement->price,
                        "productId"         => $productCoupon_id,
                        "code_type"         => 'prizecode',
                    ];
                    session()->put('coupon', $coupon);
                    return response()->json(['subtotal'=>$subtotal,'productTotal'=>$productTotal,'discountval'=>$discountvalue,'couponproductId'=>$is_coupon_product_id,'couponData'=>$couponData]);
                }
                if($flagyes==1 && $flagno==0){
                    return response()->json(['error'=>'Prize Coupon code is invalid!']);
                }
            }


        }elseif($isUsedCouponCode){
                return response()->json(['error'=>'This coupon code has already been used.']);
        }else{

            $get_voucherData = DB::table('voucher')->select('*')->where('voucher_code', $couponcode)->where('expiry_date',">=", date('y-m-d'))->get()->toArray();
            if(!$get_voucherData){
                return response()->json(['error'=>'Coupon code is invalid!']);
            }else{
                $cart = session()->get('cart');
                $productTotal = 0;$subtotal = 0;$subtotalwthoutcoupn = 0;
                $productTotalno = 0;$flagyes = 1; $flagno = 1;$is_coupon_product_id=array();$productCoupon_id=array();
                $couponData = [
                    "code"                  => $get_voucherData[0]->voucher_code,
                    "discount_type"         => $get_voucherData[0]->discount_type,
                    "discount_value"        => $get_voucherData[0]->discount_value,
                ];
                $product_id = array();
                $get_productData = DB::table('product')->select('*')->where('directory_id', $get_voucherData[0]->directory_id)->get()->toArray();
                foreach($get_productData as $value){
                    $product_id[] = $value->id;
                }
                foreach($cart as $key => $cartval){
                    if(in_array($key,$product_id)){
                        $productCoupon_id[]=$key;
                        $flagyes = 0;
                        $productTotal = $cartval['price']*$cartval['quantity'];
                        if($get_voucherData[0]->discount_type=="percentagediscount"){
                            $percentagediscount = ($productTotal)-(($productTotal)*($get_voucherData[0]->discount_value/100));
                            $percentagediscount_sub_tot = $productTotal;
                        }
                        if($get_voucherData[0]->discount_type=="fixeddiscount"){
                            $percentagediscount = ($productTotal)-($get_voucherData[0]->discount_value);
                            $percentagediscount_sub_tot = $productTotal;
                        }
                        $is_coupon_product_id[]=[
                            'id'=> $key,
                            'productcartprice'=> $productTotal,
                            'poroductdisprice'=> $percentagediscount,
                        ];
                        $subtotal += $percentagediscount_sub_tot;
                    }else{
                        $flagno = 0;
                        $subtotalwthoutcoupn += $cartval['price']*$cartval['quantity'];
                        $productTotalno += $cartval['price']*$cartval['quantity'];
                    }
                }
            if($get_voucherData[0]->discount_type=="percentagediscount"){
                $discountvalue = (($productTotal)*($get_voucherData[0]->discount_value/100));
            }
            if($get_voucherData[0]->discount_type=="fixeddiscount"){
                $discountvalue = (int)$get_voucherData[0]->discount_value;
            }
                $subtotal = $cartval['price']*$cartval['quantity'];
                $productTotal = $productTotal-$discountvalue;
                if($flagyes==0 && $flagno==0){
                    $productTotal += $productTotalno;
                    $coupon = [
                        "subtotal"          => $subtotal,
                        "totaldiscount"     => $discountvalue,
                        "couponcode"        => $get_voucherData[0]->voucher_code,
                        "coupontype"        => $get_voucherData[0]->discount_type,
                        "couponvalue"       => $get_voucherData[0]->discount_value,
                        "productId"         => $productCoupon_id,
                        "code_type"         => 'couponcode',
                    ];
                    session()->put('coupon', $coupon);
                    return response()->json(['subtotal'=>$subtotal,'productTotal'=>$productTotal,'discountval'=>$discountvalue,'couponproductId'=>$is_coupon_product_id,'couponData'=>$couponData]);
                }
                if($flagyes==0 && $flagno==1){
                    $coupon = [
                        "subtotal"          => $subtotal,
                        "totaldiscount"     => $discountvalue,
                        "couponcode"        => $get_voucherData[0]->voucher_code,
                        "coupontype"        => $get_voucherData[0]->discount_type,
                        "couponvalue"       => $get_voucherData[0]->discount_value,
                        "productId"         => $productCoupon_id,
                        "code_type"         => 'couponcode',
                    ];
                    session()->put('coupon', $coupon);
                    return response()->json(['subtotal'=>$subtotal,'productTotal'=>$productTotal,'discountval'=>$discountvalue,'couponproductId'=>$is_coupon_product_id,'couponData'=>$couponData]);
                }
                if($flagyes==1 && $flagno==0){
                    return response()->json(['error'=>'Coupon code is invalid!']);
                }
            }
        }    


    }


}