<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Helpers\Helper;
use App\Models\AddToCompare;
use App\Models\client\Wishlist;
use App\Models\Distancesetting;
//use Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductTags;
use App\Models\RecentView;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public $configData = ['minPrice' => '', 'maxPrice' => ''];
    public  $params = ['cat' => [], 'filters' => [], 'amenity' => [], 'price' => [], 'search_string' => []];

    public function getIp()
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        return request()->ip();
    }

    public function shop(Request $request)
    {

        $recentview_products = [];

        Helper::countwebsite();
        $language_id = Helper::getselectedLanguage(); // get selected language Id
        $allParentcategory = DB::table('product_category')->select('id', 'name')->where('parent_id', 0)->where('status', 'Active')->where('deleted', '0')->get()->toArray();
        $categoryUrls = [];
        $subcategoryUrls = [];
        $discountUrls = [];
        $producttagsUrls = [];

        $url_city_name = session()->get('url_city_name');
        if ($url_city_name) {
            $latitude1  = session()->get('url_latitude');
            $longitude1 = session()->get('url_longitude');
        }else if (session()->has('latitude_city')) {
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


        if (isset($_SERVER['QUERY_STRING'])) {
            $query  = explode('&', $_SERVER['QUERY_STRING']);
        } else {
            $query = [];
        }
        $params = $this->params;
        $configData = array();
        if (!empty($query[0])) {
            foreach ($query as $param) {
                list($name, $value) = explode('=', $param);
                $params[urldecode($name)][] = urldecode($value);
            }
        }
        if (!empty($params['cat'])) {
            $configData['categoryUrls'] = $params['cat'];
        }
        if (!empty($params['filters'])) {
            $configData['discountUrls'] = $params['filters'];
        }
        if (!empty($params['subcat'])) {
            $configData['subcategoryUrls'] = $params['subcat'];
        }
        if (!empty($params['tags'])) {
            $configData['producttagsUrls'] = $params['tags'];
        }
        if (!empty($params['price'])) {
            $price = $params['price'][0];
            $price = explode('-', $price);
            $configData['minPrice'] = $price[0];
            $configData['maxPrice'] = $price[1];
        }
        if (!empty($params['search_string'])) {
            $configData['searchstringUrls'] = $params['search_string'];
        }
        $filterData = $configData;
        if (array_key_exists('categoryUrls', $filterData)) {
            if (!empty($filterData['categoryUrls'])) {
                $categoryUrls =  array_unique($filterData['categoryUrls']);
            }
        }
        if (array_key_exists('discountUrls', $filterData)) {
            if (!empty($filterData['discountUrls'])) {
                $discountUrls =  array_unique($filterData['discountUrls']);
            }
        }
        if (array_key_exists('subcategoryUrls', $filterData)) {
            if (!empty($filterData['subcategoryUrls'])) {
                $subcategoryUrls =  array_unique($filterData['subcategoryUrls']);
            }
        }
        if (array_key_exists('producttagsUrls', $filterData)) {
            if (!empty($filterData['producttagsUrls'])) {
                $producttagsUrls =  array_unique($filterData['producttagsUrls']);
            }
        }
        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        DB::enableQueryLog();
        $rating = $request->input('review');

        $productDirctoryData = Product::select(
            'product.*',
            DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(lc_product.latitude)) * cos(radians(lc_product.longitude) - radians(" . $longitude . "))
                            + sin(radians(" . $latitude . ")) * sin(radians(lc_product.latitude))) AS distance"),
            'pro_trans.id AS product_lang_id',
            'pro_trans.name AS product_lang_name',
            'pro_trans.short_content',
            'plan_subsc.slug as plan_slug'
        );

        if (!empty($searchstringUrls)) {

            $productDirctoryData =    $productDirctoryData->where(function ($query) use ($searchstringUrls) {
                $languageid = Helper::getselectedLanguage();
                $product_tags_id = DB::table('tag_product_translations')->select('tag_product_translations.*')->where('tag_product_translations.language_id', $languageid)
                ->where('tag_product_translations.title','like', '%' . $searchstringUrls[0] . '%')
                ->get();

                $query->where('product.name', 'like', '%' . $searchstringUrls[0] . '%');
                $query->orWhere('product.ena', 'like', '%' . $searchstringUrls[0] . '%');
                $query->orWhere('product.supplier_name', 'like', '%' . $searchstringUrls[0] . '%');

                if (!$product_tags_id->isEmpty()) {
                    foreach ($product_tags_id as $dirTagVal) {
                        $query->orwhereRaw('FIND_IN_SET("' . $dirTagVal->tag_id . '",lc_product.tags)');
                    }
                }

                $query->orwhere('product.supplier_product_no','like', '%' . $searchstringUrls[0] . '%');
            });
        }

        $productDirctoryData = $productDirctoryData->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id');
        $productDirctoryData = $productDirctoryData->leftjoin('plan_subscriptions as plan_subsc', 'product.directory_id', '=', 'plan_subsc.subscriber_id');

        if ((!empty($subcategoryUrls)) && $subcategoryUrls[0] != 'all') {
            $productDirctoryData = $productDirctoryData->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'latestproduct') {
            $productDirctoryData = $productDirctoryData->where('product.deleted', '0')->orderBy('product.created_at', 'desc');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'mostpopular') {
            $productDirctoryData = $productDirctoryData->where('product.popular', '1');
        }

        if (!empty($discountUrls) && $discountUrls[0] == 'shoppingcard') {
            $productDirctoryData = $productDirctoryData->leftjoin('shopping_card', 'product.id', '=', 'shopping_card.product_id')->where('shopping_card.expire_date', ">=", date('y-m-d'))->where('shopping_card.deleted', '0')->orderBy('shopping_card.id', 'DESC');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'offer') {
            $productDirctoryData = $productDirctoryData->leftjoin('offer', 'product.id', '=', 'offer.offer_product_id')->where('offer.offer_expire_date', ">=", date('y-m-d'))->where('offer.deleted', '0')->orderBy('offer.id', 'ASC');
        }
        if (!empty($params['price'])) {
            $productDirctoryData = $productDirctoryData->leftjoin('product_price as pro_pr', 'product.id', '=', 'pro_pr.product_id');
        }

        if (!empty($rating)) {
            $productDirctoryData = $productDirctoryData->Where('pro_trans.language_id', $language_id)->whereHas('reviews', function ($query) use ($rating) {
                $query->select(DB::raw('AVG(star) as avg_rating'))
                    ->groupBy('id')
                    ->havingRaw('AVG(star) >= ?', [$rating]);
            });
        }else{

            $productDirctoryData = $productDirctoryData->Where('pro_trans.language_id', $language_id)
            ->where(function ($query) use ($filterData, $categoryUrls, $discountUrls, $subcategoryUrls, $producttagsUrls) {

                if (isset($filterData['minPrice']) ?? '') {
                    if (trim(isset($filterData['minPrice']) ? $filterData['minPrice'] : 0) != "" && trim(isset($filterData['maxPrice']) ? $filterData['maxPrice'] : 0 != "")) {
                       $query->where('pro_pr.regular_price', '>=', $filterData['minPrice']);
                        $query->where('pro_pr.regular_price', '<=', $filterData['maxPrice']);
                        $query->where('pro_pr.currency_base', 'yes');
                    }
                }

                if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                    $query->WhereIn('product.cat_id', $categoryUrls);
                }
                if (!empty($categoryUrls) && (!empty($subcategoryUrls))) {
                    $query->WhereIn('product.cat_id', $categoryUrls);
                    $query->WhereIn('pro_cat.category_id', $subcategoryUrls);
                }
                if (!empty($producttagsUrls)) {
                    foreach ($producttagsUrls as $tagval) {
                        $query->orWhereRaw('FIND_IN_SET("' . $tagval . '",lc_product.tags)');
                    }
                }
                if (!empty($discountUrls) && $discountUrls[0] == 'shoppingcard' && $categoryUrls == '') {
                    $query->WhereIn('shopping_card.product_id', $discountUrls);
                }
                if (!empty($discountUrls) && $discountUrls[0] == 'offer' && $categoryUrls == '') {
                    $query->WhereIn('offer.offer_product_id', $discountUrls);
                }
                if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
                    $query->WhereIn('pro_cat.category_id', $subcategoryUrls);
                }

            });

        }

            // $queries = DB::getQueryLog(); // print_r($queries);die;
        $distance_default = Distancesetting::where('status','Active')->where('default_distance','yes')->orderBy('id', 'asc')->first();

        if(session('distance')){
          $productDirctoryData = $productDirctoryData->having('distance', '<', session('distance'));
        }else{
          $productDirctoryData = $productDirctoryData->having('distance', '<', $distance_default->to_distance);
        }
        $productDirctoryData = $productDirctoryData->where('product.deleted', '0')->where('product.is_free_product', 0)->where('product.status', 'Active')
        //->orderBy('search_count', 'desc')
        ->orderByRaw("
                FIELD(plan_slug, 'premium','base','business','free') DESC,
                created_at DESC
            ")
        ->orderBy('search_count', 'DESC')
        ->paginate(44);
        $productDirctoryData->appends($request->query());

        if (auth()->check()) {
            $recentview_products = RecentView::where('user_id', auth()->id())->latest('updated_at')->with('product')->limit(7)->get()->pluck('product')->filter();
        }

        //dd($recentview_products);

        $distancesetting = Distancesetting::where('status','Active')->orderBy('id', 'asc')->get();

        $pProductSubCatData  = DB::table('product')->select('product.*', 'pro_cat.category_id AS sub_cat_id')
            ->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id')->where('product.deleted', '0')->where('product.status', 'Active')->get();

        $wishlists = array();
        $AddToCompare = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            $AddToCompare = AddToCompare::with('product')->with('user')->where('user_id', $user)->orderBy('id', 'desc')->get();
            return view('client.shop', ['language_id' => $language_id, 'recentview_products' => $recentview_products, 'categoryUrls' => $categoryUrls,'subcategoryUrls' => $subcategoryUrls,'producttagsUrls' => $producttagsUrls,'data' => $productDirctoryData, 'distancesetting' => $distancesetting, 'wishlists' => $wishlists, 'AddToCompare' => $AddToCompare, 'pProductSubCatData' => $pProductSubCatData, 'allParentcategory' => $allParentcategory]);
        } else {
            return view('client.shop', ['language_id' => $language_id, 'recentview_products' => $recentview_products, 'categoryUrls' => $categoryUrls,'subcategoryUrls' => $subcategoryUrls,'producttagsUrls' => $producttagsUrls,'data' => $productDirctoryData, 'distancesetting' => $distancesetting, 'wishlists' => $wishlists, 'AddToCompare' => $AddToCompare, 'pProductSubCatData' => $pProductSubCatData, 'allParentcategory' => $allParentcategory]);
        }
    }


    public function productComparison2(Request $request)
    {

        $recentview_products = [];

        Helper::countwebsite();
        $language_id = Helper::getselectedLanguage();
        $allParentcategory = DB::table('product_category')->select('id', 'name')->where('parent_id', 0)->where('status', 'Active')->where('deleted', '0')->get()->toArray();
        $categoryUrls = [];
        $subcategoryUrls = [];
        $discountUrls = [];
        $producttagsUrls = [];

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


        if (isset($_SERVER['QUERY_STRING'])) {
            $query  = explode('&', $_SERVER['QUERY_STRING']);
        } else {
            $query = [];
        }
        $params = $this->params;
        $configData = array();
        if (!empty($query[0])) {
            foreach ($query as $param) {
                list($name, $value) = explode('=', $param);
                $params[urldecode($name)][] = urldecode($value);
            }
        }

        DB::enableQueryLog();
        $rating = $request->input('review');

        $productDirctoryData = Product::select(
            'product.*',
            DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(lc_product.latitude)) * cos(radians(lc_product.longitude) - radians(" . $longitude . "))
                            + sin(radians(" . $latitude . ")) * sin(radians(lc_product.latitude))) AS distance"),
            'pro_trans.id AS product_lang_id',
            'pro_trans.name AS product_lang_name',
            'pro_trans.short_content',
            'plan_subsc.slug as plan_slug'
        );


        $productDirctoryData = $productDirctoryData->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id');
        $productDirctoryData = $productDirctoryData->leftjoin('plan_subscriptions as plan_subsc', 'product.directory_id', '=', 'plan_subsc.subscriber_id');

        if ((!empty($subcategoryUrls)) && $subcategoryUrls[0] != 'all') {
            $productDirctoryData = $productDirctoryData->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'latestproduct') {
            $productDirctoryData = $productDirctoryData->where('product.deleted', '0')->orderBy('product.created_at', 'desc');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'mostpopular') {
            $productDirctoryData = $productDirctoryData->where('product.popular', '1');
        }

        if (!empty($discountUrls) && $discountUrls[0] == 'shoppingcard') {
            $productDirctoryData = $productDirctoryData->leftjoin('shopping_card', 'product.id', '=', 'shopping_card.product_id')->where('shopping_card.expire_date', ">=", date('y-m-d'))->where('shopping_card.deleted', '0');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'offer') {
            $productDirctoryData = $productDirctoryData->leftjoin('offer', 'product.id', '=', 'offer.offer_product_id')->where('offer.offer_expire_date', ">=", date('y-m-d'))->where('offer.deleted', '0');
        }

        if (!empty($rating)) {
            $productDirctoryData = $productDirctoryData->Where('pro_trans.language_id', $language_id)->whereHas('reviews', function ($query) use ($rating) {
                $query->select(DB::raw('AVG(star) as avg_rating'))
                    ->groupBy('id')
                    ->havingRaw('AVG(star) >= ?', [$rating]);
            });
        }else{

            $productDirctoryData = $productDirctoryData->Where('pro_trans.language_id', $language_id)
            ->where(function ($query) use ($categoryUrls, $discountUrls, $subcategoryUrls, $producttagsUrls) {

                if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                    $query->WhereIn('product.cat_id', $categoryUrls);
                }
                if (!empty($categoryUrls) && (!empty($subcategoryUrls))) {
                    $query->WhereIn('product.cat_id', $categoryUrls);
                    $query->WhereIn('pro_cat.category_id', $subcategoryUrls);
                }
                if (!empty($producttagsUrls)) {
                    foreach ($producttagsUrls as $tagval) {
                        $query->whereRaw('FIND_IN_SET("' . $tagval . '",lc_product.tags)');
                    }
                }
                if (!empty($discountUrls) && $discountUrls[0] == 'shoppingcard' && $categoryUrls == '') {
                    $query->WhereIn('shopping_card.product_id', $discountUrls);
                }
                if (!empty($discountUrls) && $discountUrls[0] == 'offer' && $categoryUrls == '') {
                    $query->WhereIn('offer.offer_product_id', $discountUrls);
                }
                if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
                    $query->WhereIn('pro_cat.category_id', $subcategoryUrls);
                }

            });

        }

            // $queries = DB::getQueryLog(); // print_r($queries);die;
        $distance_default = Distancesetting::where('status','Active')->where('default_distance','yes')->orderBy('id', 'asc')->first();

        if(session('distance')){
          $productDirctoryData = $productDirctoryData->having('distance', '<', session('distance'));
        }else{
          $productDirctoryData = $productDirctoryData->having('distance', '<', $distance_default->to_distance);
        }
        $productDirctoryData = $productDirctoryData->where('product.deleted', '0')->where('product.is_free_product', 0)->where('product.status', 'Active')
        //->orderBy('search_count', 'desc')
        ->orderByRaw("
                FIELD(plan_slug, 'premium','base','business','free') DESC,
                created_at DESC
            ")
        ->orderBy('search_count', 'DESC')
        ->paginate(19);
        $productDirctoryData->appends($request->query());

        if (auth()->check()) {
            $recentview_products = RecentView::where('user_id', auth()->id())->latest('updated_at')->with('product')
                ->limit(7)->get()->pluck('product');
        }

        $distancesetting = Distancesetting::where('status','Active')->orderBy('id', 'asc')->get();

        $pProductSubCatData  = DB::table('product')->select('product.*', 'pro_cat.category_id AS sub_cat_id')
            ->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id')->where('product.deleted', '0')->where('product.status', 'Active')->get();


        $wishlists = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            return view('client.comparison', ['language_id' => $language_id, 'recentview_products' => $recentview_products, 'categoryUrls' => $categoryUrls,'subcategoryUrls' => $subcategoryUrls,'producttagsUrls' => $producttagsUrls,'data' => $productDirctoryData, 'distancesetting' => $distancesetting, 'wishlists' => $wishlists, 'pProductSubCatData' => $pProductSubCatData, 'allParentcategory' => $allParentcategory]);
        } else {
            return view('client.comparison', ['language_id' => $language_id, 'recentview_products' => $recentview_products, 'categoryUrls' => $categoryUrls,'subcategoryUrls' => $subcategoryUrls,'producttagsUrls' => $producttagsUrls,'data' => $productDirctoryData, 'distancesetting' => $distancesetting, 'wishlists' => $wishlists, 'pProductSubCatData' => $pProductSubCatData, 'allParentcategory' => $allParentcategory]);
        }
    }

    public function productComparison(Request $request){

        $recentview_products = [];
        $language_id = Helper::getselectedLanguage();

        if (auth()->check()) {
            $recentview_products = RecentView::where('user_id', auth()->id())->latest('updated_at')->with('product')
                ->limit(7)->get()->pluck('product');
        }

        $wishlists = array();

        if ($user = Auth::user()) {
            //$user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = Wishlist::select("*")->where("user_id", $user->id)->orderby('id', 'desc')->get()->toArray();
            $AddToCompare = AddToCompare::with('product')->with('user')->where('user_id', $user->id)->orderBy('id', 'desc')->get();

        } else {
            $AddToCompare = [];
        }
        return view('client.comparison', compact('AddToCompare','wishlists','language_id','recentview_products'));

    }

    public function companyproductComparison($slug,$dir_id){

        $recentview_products = [];
        $language_id = Helper::getselectedLanguage();
        if (auth()->check()) {
            $recentview_products = RecentView::where('user_id', auth()->id())->latest('updated_at')->with('product')
                ->limit(7)->get()->pluck('product');
        }
        $wishlists = array();
        if ($user = Auth::user()) {
            $wishlists = Wishlist::select("*")->where("user_id", $user->id)->orderby('id', 'desc')->get()->toArray();
            $AddToCompare = AddToCompare::with('product')->with('user')->where('user_id', $user->id)->orderBy('id', 'desc')->get();
        } else {
            $AddToCompare = [];
        }
        return view('client.companycomparison', compact('AddToCompare','wishlists','language_id','recentview_products','slug','dir_id'));
    }

    public static function get_galleryImage($productId)
    {
        $get_mainimageData = DB::table('product_images')->select('*')->where('product_id', $productId)->where('main_image', "galimg")->get()->toArray();
        return $get_mainimageData;
    }

    public function productListUsingTags(Request $request, $tag = '')
    {
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

        $language_id = Helper::getselectedLanguage(); // get selected language Id
        $allParentcategory = Helper::getAllParentCategory();
        $categoryUrls = [];
        $subcategoryUrls = [];
        $producttagsUrls = [];
        $discountUrls = [];

        if (isset($_SERVER['QUERY_STRING'])) {
            $query  = explode('&', $_SERVER['QUERY_STRING']);
        } else {
            $query = [];
        }
        $params = $this->params;
        $configData = array();
        if (!empty($query[0])) {
            foreach ($query as $param) {
                list($name, $value) = explode('=', $param);
                $params[urldecode($name)][] = urldecode($value);
            }
        }
        if (!empty($params['cat'])) {
            $configData['categoryUrls'] = $params['cat'];
        }
        if (!empty($params['subcat'])) {
            $configData['subcategoryUrls'] = $params['subcat'];
        }
        if (!empty($params['tags'])) {
            $configData['producttagsUrls'] = $params['tags'];
        }
        if (!empty($params['filters'])) {
            $configData['discountUrls'] = $params['filters'];
        }
        if (!empty($params['price'])) {
            $price = $params['price'][0];
            $price = explode('-', $price);
            $configData['minPrice'] = $price[0];
            $configData['maxPrice'] = $price[1];
        }
        if (!empty($params['search_string'])) {
            $configData['searchstringUrls'] = $params['search_string'];
        }
        $filterData = $configData;
        if (array_key_exists('categoryUrls', $filterData)) {
            if (!empty($filterData['categoryUrls'])) {
                $categoryUrls =  array_unique($filterData['categoryUrls']);
            }
        }
        if (array_key_exists('subcategoryUrls', $filterData)) {
            if (!empty($filterData['subcategoryUrls'])) {
                $subcategoryUrls =  array_unique($filterData['subcategoryUrls']);
            }
        }
        if (array_key_exists('producttagsUrls', $filterData)) {
            if (!empty($filterData['producttagsUrls'])) {
                $producttagsUrls =  array_unique($filterData['producttagsUrls']);
            }
        }
        if (array_key_exists('discountUrls', $filterData)) {
            if (!empty($filterData['discountUrls'])) {
                $discountUrls =  array_unique($filterData['discountUrls']);
            }
        }
        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        $productDirctoryData = DB::table('product')->select('product.*',
                            DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(lc_product.latitude)) * cos(radians(lc_product.longitude) - radians(" . $longitude . "))
                            + sin(radians(" . $latitude . ")) * sin(radians(lc_product.latitude))) AS distance"),
                            'pro_trans.id AS product_lang_id', 'pro_trans.name AS product_lang_name', 'pro_trans.short_content');
        // if (!empty($searchstringUrls)) {
        //     $productDirctoryData = $productDirctoryData->where('product.name', 'like', '%' . $searchstringUrls[0] . '%');
        // }
        if (!empty($searchstringUrls)) {
            $productDirctoryData =    $productDirctoryData->where(function ($query) use ($searchstringUrls) {
                $product_tags_id = DB::table('product_tags')->where('tags','like', '%' . $searchstringUrls[0] . '%')->get();
                $query->where('product.name', 'like', '%' . $searchstringUrls[0] . '%');
                $query->orWhere('product.ena', 'like', '%' . $searchstringUrls[0] . '%');
                $query->orWhere('product.supplier_name', 'like', '%' . $searchstringUrls[0] . '%');
                if(!empty($product_tags_id)){
                    //$query->orwhereRaw('FIND_IN_SET("' . $product_tags_id->id. '",lc_product.tags)');
                    foreach ($product_tags_id as $protagval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $protagval->id . '",lc_product.tags)');
                    }
                }
                $query->orwhere('product.supplier_product_no',$searchstringUrls[0]);
            });
        }
        $productDirctoryData = $productDirctoryData->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id');
        $productDirctoryData = $productDirctoryData->join('product_tags', 'product.tags', '=', 'product_tags.id');
        if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
            $productDirctoryData = $productDirctoryData->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id');
        }
        if (!empty($params['price'])) {
            $productDirctoryData = $productDirctoryData->leftjoin('product_price as pro_pr', 'product.id', '=', 'pro_pr.product_id');
        }
        // if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
        //     $productDirctoryData = $productDirctoryData->WhereIn('product.cat_id', $categoryUrls);
        // }
        if (!empty($discountUrls) && $discountUrls[0] == 'latestproduct') {
            $productDirctoryData = $productDirctoryData->where('product.deleted', '0')->orderBy('product.created_at', 'desc');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'mostpopular') {
            $productDirctoryData = $productDirctoryData->where('product.popular', '1');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'shoppingcard') {
            $productDirctoryData = $productDirctoryData->leftjoin('shopping_card', 'product.id', '=', 'shopping_card.product_id')->where('shopping_card.expire_date', ">=", date('y-m-d'))->where('shopping_card.deleted', '0');
        }
        if (!empty($discountUrls) && $discountUrls[0] == 'offer') {
            $productDirctoryData = $productDirctoryData->leftjoin('offer', 'product.id', '=', 'offer.offer_product_id')->where('offer.offer_expire_date', ">=", date('y-m-d'))->where('offer.deleted', '0');
        }
        $productDirctoryData = $productDirctoryData->Where('pro_trans.language_id', $language_id)->whereRaw('FIND_IN_SET("' . $tag . '",lc_product.tags)')
            ->where(function ($query) use ($filterData, $categoryUrls, $subcategoryUrls, $discountUrls, $producttagsUrls) {
                if (!empty($categoryUrls) && $categoryUrls[0] != 'all' && $discountUrls == '') {
                    $query->WhereIn('product.cat_id', $categoryUrls);
                }
                if (!empty($subcategoryUrls)) {
                    $query->WhereIn('pro_cat.category_id', $subcategoryUrls);
                }
                if (!empty($categoryUrls) && (!empty($subcategoryUrls))) {
                    $query->orWhereIn('product.cat_id', $categoryUrls);
                    $query->orWhereIn('pro_cat.category_id', $subcategoryUrls);
                }
                if (!empty($producttagsUrls)) {
                    foreach ($producttagsUrls as $tagval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $tagval . '",lc_product.tags)');
                    }
                }
                if (!empty($discountUrls) && $discountUrls[0] == 'shoppingcard' && $categoryUrls == '') {
                    $query->WhereIn('shopping_card.product_id', $discountUrls);
                }
                if (!empty($discountUrls) && $discountUrls[0] == 'offer' && $categoryUrls == '') {
                    $query->WhereIn('offer.offer_product_id', $discountUrls);
                }
                if (isset($filterData['minPrice']) ?? '') {
                    if (trim(isset($filterData['minPrice']) ? $filterData['minPrice'] : 0) != "" && trim(isset($filterData['maxPrice']) ? $filterData['maxPrice'] : 0 != "")) {
                        $query->where('pro_pr.regular_price', '>=', $filterData['minPrice']);
                        $query->where('pro_pr.regular_price', '<=', $filterData['maxPrice']);
                        $query->where('pro_pr.currency_base', 'yes');
                    }
                }
            });
        $productDirctoryData = $productDirctoryData->whereRaw('FIND_IN_SET("' . $tag . '",lc_product.tags)')->where('product.deleted', '0')->where('product.status', 'Active')->groupBy('product.id')->orderBy('distance', 'asc')
        //->having('distance', '<', 2000)
        ->paginate(50);
        // $productData    = DB::table('product')->select('product.*', 'pro_trans.id AS product_lang_id', 'pro_trans.name AS product_lang_name', 'pro_trans.short_content', 'pro_cat.category_id AS pro_cat_id')
        //     ->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id')
        //     ->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id');
        //$data['relatedProductData']         = $productData->Where('pro_trans.language_id', $language_id)->whereRaw('FIND_IN_SET("' . $tag . '",lc_product.tags)')->where('product.deleted', '0')->where('product.status', 'Active')->groupBy('product.id')->orderBy('product.id', 'desc')->get()->toArray();
        //$pDirctoryData = Product::whereRaw('FIND_IN_SET("' . $tag . '",lc_product.tags)')->where('deleted', '0')->where('status', 'Active')->with('subcat')->get();
        $pDirctoryData  = DB::table('product')->select('product.*', 'pro_cat.category_id AS pro_cat_id')
                        ->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id')->whereRaw('FIND_IN_SET("' . $tag . '",lc_product.tags)')->where('product.deleted', '0')->where('product.status', 'Active')->get();

        $wishlists = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            // return view('client.producttag', ['relatedProductData' => $productDirctoryData, 'pDirctoryData' => $productDirctoryData, 'wishlists' => $wishlists, 'allParentcategory' => $allParentcategory]);
            return view('client.producttag', ['relatedProductData' => $productDirctoryData, 'pDirctoryData' => $pDirctoryData, 'wishlists' => $wishlists, 'allParentcategory' => $allParentcategory]);
        } else {
            return view('client.producttag', ['relatedProductData' => $productDirctoryData , 'pDirctoryData' => $pDirctoryData, 'wishlists' => $wishlists, 'allParentcategory' => $allParentcategory]);
        }
    }

    public function shop123()
    {
        $productDirctoryData = DB::table('product')->select('*')->where('deleted', '0')->orderBy('id', 'desc')->paginate(10);
        return view('client.shop', ['data' => $productDirctoryData]);
    }
    public function allCategory()
    {
        Helper::countwebsite();
        $data = DB::table('category')->select('*')->orderBy('id', 'desc')->paginate(20);
        return view('client.allcategory', compact('data'));
    }
    public function categoryProduct(Request $request)
    {
        Helper::countwebsite();
        $language_id = Helper::getselectedLanguage(); // get selected language Id
        $allParentcategory = Helper::getAllParentCategory();                                                        //$amenityUrls = [];
        $categoryid = "";
        $categoryRelatedData = "";
        if ($request->cat) {
            $catid = DB::table('category')->select('slug', 'name')->where('deleted', '0')->where('id', $request->cat)->first();
            $categoryid = $request->cat;
            $catSlug = $catid->slug;
            $categoryRelatedData = $catid->name;
        } else {
            $catid = DB::table('category')->select('id', 'name')->where('deleted', '0')->where('slug', $request->id)->first();
            $categoryid = $catid->id;
            $catSlug = $request->id;
            $categoryRelatedData = $catid->name;
        }
        if (isset($_SERVER['QUERY_STRING'])) {
            $query  = explode('&', $_SERVER['QUERY_STRING']);
        } else {
            $query = [];
        }

        $params = $this->params;
        $configData = array();
        if (!empty($query[0])) {
            foreach ($query as $param) {
                list($name, $value) = explode('=', $param);
                $params[urldecode($name)][] = urldecode($value);
            }
        }

        if (!empty($params['search_string'])) {
            $configData['searchstringUrls'] = $params['search_string'];
        }
        if (!empty($params['price'])) {
            $price = $params['price'][0];
            $price = explode('-', $price);
            $configData['minPrice'] = $price[0];
            $configData['maxPrice'] = $price[1];
        }
        $filterData = $configData;
        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        DB::enableQueryLog();
        $productDirctoryData = DB::table('product')->select('product.*', 'pro_trans.id AS product_lang_id', 'pro_trans.name AS product_lang_name', 'pro_trans.short_content');
        if (!empty($searchstringUrls)) {
            $productDirctoryData = $productDirctoryData->where('product.name', 'like', '%' . $searchstringUrls[0] . '%');
        }
        $productDirctoryData = $productDirctoryData->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id');

        if ($categoryid != "") {
            $productDirctoryData = $productDirctoryData->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id');
        }
        if (!empty($params['price'])) {
            $productDirctoryData = $productDirctoryData->leftjoin('product_price as pro_pr', 'product.id', '=', 'pro_pr.product_id');
        }
        $productDirctoryData = $productDirctoryData->Where('pro_trans.language_id', $language_id)
            ->where(function ($query) use ($filterData, $categoryid) {
                if ($categoryid != "") {
                    $query->Where('pro_cat.category_id', $categoryid);
                }
                if (trim(isset($filterData['minPrice']) ? $filterData['minPrice'] : 0) != "" && trim(isset($filterData['maxPrice']) ? $filterData['maxPrice'] : 0 != "")) {
                    $query->where('pro_pr.regular_price', '>=', $filterData['minPrice']);
                    $query->where('pro_pr.regular_price', '<=', $filterData['maxPrice']);
                    $query->where('pro_pr.currency_base', 'yes');
                }
            });
        $productDirctoryData = $productDirctoryData->where('product.deleted', '0')->groupBy('product.id')->orderBy('product.id', 'desc')->paginate(50);
        $wishlists = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            return view('client.categoryproduct', ['data' => $productDirctoryData, 'wishlists' => $wishlists, 'categoryRelatedData' => $categoryRelatedData, 'allParentcategory' => $allParentcategory, 'catslug' => $catSlug]);
        } else {
            return view('client.categoryproduct', ['data' => $productDirctoryData, 'wishlists' => $wishlists, 'categoryRelatedData' => $categoryRelatedData, 'allParentcategory' => $allParentcategory, 'catslug' => $catSlug]);
        }
    }
}
