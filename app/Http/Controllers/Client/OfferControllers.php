<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Helpers\Helper;
use App\Models\Offer;
use App\Models\ShoppingCard;
use App\Models\client\Wishlist;
use App\Models\CountCustomerClub;
use Illuminate\Support\Facades\Session;
use DB;

class OfferControllers extends Controller
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
    
    public function details($text = ""){
        
        Helper::countwebsite();
        if (session('latitude_city')){
            $latitude1       =       session('latitude_city');
            $longitude1      =       session('logitude_city');
        }else{
            if (session('latitude') == null) {
                $userIP = $this->getIp();
                $new_arr 			= 	@unserialize(file_get_contents('http://ip-api.com/php/'.$userIP));

                if($userIP=='127.0.0.1' || $userIP=='localhost' ){
                    $latitude1 = '28.6542';
                    $longitude1 = '77.2373';
                }else{
                    $latitude1       =       $new_arr['lat'];
                    $longitude1      =       $new_arr['lon'];
                }
            } else {
                $latitude1       =       session('latitude');
                $longitude1      =       session('logitude');
            }
        }
        $latitude        =       "'$latitude1'";
        $longitude       =       "'$longitude1'";

        $data=array();
        $offer = DB::table('offer')->select('id', 'slug')->where('slug', $text)->first();
        if (!$offer && ctype_digit((string) $text)) {
            $offer = DB::table('offer')->select('id', 'slug')->where('id', (int) $text)->first();
        }
        abort_unless($offer, 404);
        $id = $offer->id;

        $data['offerData']      = Offer::with('directorySlug')->where('id',$id)->first();
        $visitors = Helper::visitors('offer',$id);
        $data['bannerImage']    = DB::table('offer_image')->select('*')->where('main_image','banner')->where('offer_id',$id)->get()->toArray();
        $data['galleryImage']   = DB::table('offer_image')->select('*')->where('main_image','galimg')->where('offer_id',$id)->get()->toArray();
        $productDirctoryData    = DB::table('product')->select('product.*',
                            DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                            'pro_trans.id AS product_lang_id','pro_trans.name AS product_lang_name','pro_trans.short_content')
                                ->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id')->Where('pro_trans.language_id',  '1' );
        $data['relatedProductData'] = $productDirctoryData->Where('product.offer_id',$id)
                                    ->where('product.is_free_product',0)
                                    ->where('product.deleted','0')->groupBy('product.id')
                                    ->orderby('distance', 'asc')
                                    //->having('distance', '<', 2000)
                                    ->get()->toArray();
        $data['offerrelatedproductData']   = DB::table('offer')->select('*')->where('id', $id)->where('deleted', '0')->get()->toArray();
        
        $wishlists = array();
        $metadata = DB::table('directory_seo')->where('module_id', $id)->where('page_type', 'offer')->first();

        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            return view('client.offerdetails', ['data' => $data, 'metadata' => $metadata, 'wishlists' => $wishlists]);
        } else {
            return view('client.offerdetails', ['data' => $data, 'metadata' => $metadata, 'wishlists' => $wishlists]);
        }
    }

    public function shoppingcarddetails($slug = ""){

            if (session()->has('latitude_city')) {
                $latitude1  = session('latitude_city');
                $longitude1 = session('logitude_city');
            } else {
                if (!session()->has('latitude')) {
                    $user_ip = request()->ip(); 
                    $latitude1  = '57.107118';
                    $longitude1 = '12.252091';
                    if ($user_ip && $user_ip != '127.0.0.1' && $user_ip != '::1') {
                        $geoData = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);
                        if (!empty($geoData) 
                            && isset($geoData['lat']) 
                            && isset($geoData['lon'])
                            && $geoData['lat'] != ''
                            && $geoData['lon'] != ''
                        ) {
                            $latitude1  = $geoData['lat'];
                            $longitude1 = $geoData['lon'];
                        }
                    }
                } else {
                    $latitude1  = session('latitude');
                    $longitude1 = session('logitude');
                }
            }
            $latitude        =       "'$latitude1'";
            $longitude       =       "'$longitude1'";

        $language_id = Helper::getselectedLanguage(); // get selected language Id
        Helper::countwebsite();
        $data=array();
        $shopping_card_data = DB::table('shopping_card')->select('id','slug','product_id','directory_link')->where('slug', $slug)->first();
       
        $id = $shopping_card_data->id;
        $visitors = Helper::visitors('shoppingcard',$id);
        $metadata = DB::table('directory_seo')->where('module_id', $id)->where('page_type', 'shoppingcard')->first();
        $data['offerData']      = ShoppingCard::with('directorySlug')->where('id',$id)->first();

        $productCategoryData      = DB::table('product')->inRandomOrder()->select('product.*', 
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'pro_trans.id AS product_lang_id', 'pro_trans.name AS product_lang_name', 'pro_trans.short_content', 'pro_pr.sales_price', 'pro_pr.regular_price')
                ->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id')
                ->leftjoin('product_price as pro_pr', 'product.id', '=', 'pro_pr.product_id');
           
            $data['relatedProductData'] = $productCategoryData
                                        //->where('pro_trans.language_id', $language_id)
                                        ->where('product.id',$shopping_card_data->product_id)
                                        ->where('pro_pr.regular_price','!=', '0')
                                        ->where('product.deleted', '0')
                                        ->where('product.status', 'Active')
                                        ->groupBy('product.id')
                                        ->orderby('distance', 'asc')
                                        //->having('distance', '<', 2000)
                                        ->limit(30)->get()->toArray();

            if (auth()->guard('web')->check() != 0) {
                $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
                $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            }else{
                $wishlists = [];
            }

        $data['count_cc_visitor'] = CountCustomerClub::where('module_id', $shopping_card_data->directory_link)->count();
        //dd($data['relatedProductData']);

        $data['bannerImage']    = DB::table('shopping_card_image')->select('*')->where('main_image','banner')->where('shopping_card_id',$id)->get()->toArray();
        $data['galleryImage']   = DB::table('shopping_card_image')->select('*')->where('main_image','galimg')->where('shopping_card_id',$id)->get()->toArray();
    	
        
        return view('client.shoppingcard', ['data' => $data, 'metadata' => $metadata , 'wishlists' => $wishlists]);
    }


}
