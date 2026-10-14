<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\ProductTranslation;
use App\Models\Booking;
use App\Helpers\Helper;
use App\Mail\AdminCClubMail;
use App\Mail\BookingMail;
use App\Models\Attribute;
use App\Models\client\Wishlist;
use App\Models\Diectory;
use App\Models\DirectoryContact;
use App\Models\DirectoryServices;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\Review;
use App\Models\ProductTags;
use App\Models\ShoppingCard;
use App\Models\client\Location;
use App\Models\FreeProductsDiscount;
use App\Models\ProductVideo;
use App\Models\ViewedListingsCounter;
use App\Models\Advertisement;
use Directory;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Dotunj\LaraTwilio\Facades\LaraTwilio;
use App\Models\Country;
use App\Models\ProductAttributesVariation;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;
use App\Models\User;
use App\Mail\VisitorAndUserBookingMail;
use App\Mail\AdminCopyBookingMail;
use App\Mail\CompanyCClubMail;
use App\Mail\UserCClubMail;
use App\Models\AddToCompare;
use App\Models\client\Order;
use App\Models\client\OrderItem;
use App\Models\CustomerClubs;
use App\Models\UserAddress;
use App\Models\Distancesetting;
use App\Models\Events;
use App\Models\Frontendsetting;
use App\Models\OffersEmailNotificationHistory;
use App\Models\RecentView;
use App\Models\ReviewImages;
use App\Models\VendorCompetitionList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManagerStatic as Image;

class Listing extends Controller
{

    public  $params = ['cat' => [], 'subcat' => [], 'timeSelected' => [], 'filters' => [], 'amenity' => [], 'trademark' => [], 'tags' => [], 'search_string' => []];
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

    function get_lat_long($address){
        try{
            $address = str_replace(" ", "+", $address);
            $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyBxzKYI3QrSnkPY08S8-KpvlI5QqjOLNhU&address=$address&sensor=false&region=$address");
            $json = json_decode($json);
            //dd($json);
            $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
            if($lat){
                return array($lat,$long);
            }else{
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }



    public function index(Request $request)
    {

        set_time_limit(300);
        Helper::countwebsite();
        $language_id = Helper::getselectedLanguage(); // get selected language Id
        $allParentcategory = Helper::getAllParentCategory();

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

        $configData = array_filter([
            'categoryUrls' => $params['cat'] ?? null,
            'amenityUrls' => $params['amenity'] ?? null,
            'trademarkUrls' => $params['trademark'] ?? null,
            'tagsUrls' => $params['tags'] ?? null,
            'searchstringUrls' => $params['search_string'] ?? null,
            'filtersUrls' => $params['filters'] ?? null,
            'timeSelectedUrls' => $params['timeSelected'] ?? null,
            'subcategoryUrls' => $params['subcat'] ?? null,
        ]);

        $filterData = $configData;

        $categoryUrls = [];
        $subcategoryUrls = [];
        $amenityUrls = [];
        $filtersUrls = [];
        $trademarkUrls = [];
        $tagsUrls = [];
        $timeSelectedUrls = [];
        if (array_key_exists('categoryUrls', $filterData)) {
            if (!empty($filterData['categoryUrls'])) {
                $categoryUrls =  array_unique($filterData['categoryUrls']);
            }
        }
        if (array_key_exists('amenityUrls', $filterData)) {
            if (!empty($filterData['amenityUrls'])) {
                $amenityUrls =  array_unique($filterData['amenityUrls']);
            }
        }
        if (array_key_exists('trademarkUrls', $filterData)) {
            if (!empty($filterData['trademarkUrls'])) {
                $trademarkUrls =  array_unique($filterData['trademarkUrls']);
            }
        }
        if (array_key_exists('tagsUrls', $filterData)) {
            if (!empty($filterData['tagsUrls'])) {
                $tagsUrls =  array_unique($filterData['tagsUrls']);
            }
        }
        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        if (array_key_exists('filtersUrls', $filterData)) {
            if (!empty($filterData['filtersUrls'])) {
                $filtersUrls =  array_unique($filterData['filtersUrls']);
            }
        }
        if (array_key_exists('subcategoryUrls', $filterData)) {
            if (!empty($filterData['subcategoryUrls'])) {
                $subcategoryUrls =  array_unique($filterData['subcategoryUrls']);
            }
        }
        if (array_key_exists('timeSelectedUrls', $filterData)) {
            if (!empty($filterData['timeSelectedUrls'])) {
                $timeSelectedUrls =  array_unique($filterData['timeSelectedUrls']);
            }
        }

        $city_name = $request->input('city_name');

        $url_city_name = session()->get('url_city_name');
        if ($url_city_name) {
            $latitude1  = session()->get('url_latitude');
            $longitude1 = session()->get('url_longitude');
        }else if (!empty($city_name) && ($city_name != 'globally')) {

            $getLatLog_city_url = $this->get_lat_long($city_name);
            if($getLatLog_city_url){
                $latitude1       =       $getLatLog_city_url[0];
                $longitude1      =       $getLatLog_city_url[1];
            }else{
                $latitude1       =       session('latitude_city');
                $longitude1      =       session('logitude_city');
            }
            $request->session()->put('filter_city', $city_name);

        }else{

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
        }


        $latitude        =       "'$latitude1'";
        $longitude       =       "'$longitude1'";

        $substringToRemove = "'";
        $latitude_session = $this->removeSubstring($latitude, $substringToRemove);
        $longitude_session= $this->removeSubstring($longitude, $substringToRemove);
        $locality = $this->get_city_lat_long($latitude_session, $longitude_session);

        if (session('filter_city')){
            if(session('filter_city') == 'globally'){
                try{
                    $address = $geoData['geoplugin_city'];
                } catch (Exception $e) {
                    $address = 'sweden';
                }
            }else{
                $address 			= session('filter_city');
            }
		}else{
            try{
                $address = $geoData['geoplugin_city'];
            } catch (Exception $e) {
                $address = 'sweden';
            }
		}
        $getLatLog = $this->get_lat_long($address);
        DB::enableQueryLog();

        $distance_default = Distancesetting::where('status','Active')->where('default_distance','yes')->orderBy('id', 'asc')->first();
        $directoryDetailsData = DB::table('directory as dt')
            ->select(
                'dt.*',
                'directory_location.latitude',
                'directory_location.longitude',
                'directory_location.city_name',
                'directory_location.address',
                'dt_cat.category_id AS dir_cat_id',
                'dt_sub_cat.sub_category_id AS dir_sub_cat_id',
                'directory_media_trademarks_image.trademark_id',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            );

            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_media_trademarks_image', 'dt.id', '=', 'directory_media_trademarks_image.directory_id');
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_location', 'dt.id', '=', 'directory_location.directory_id');
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id');
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_sub_category as dt_sub_cat', 'dt.id', '=', 'dt_sub_cat.directory_id');

        if (!empty($timeSelectedUrls)) {
            $directoryDetailsData = $directoryDetailsData->addSelect('ds.day','ds.is_closed','ds.open_time','ds.close_time');
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_schedule as ds', 'dt.id', '=', 'ds.directory_id');
            $current_day = date('l', strtotime(date("y-m-d")));
            $directoryDetailsData = $directoryDetailsData->Where('ds.day', strtolower($current_day));
        }

        if (!empty($filtersUrls) && $filtersUrls[0] == 'relevant-listings') {
            $directoryDetailsData = $directoryDetailsData->rightjoin('plan_subscriptions as plan_s', 'dt.id', '=', 'plan_s.subscriber_id');
            $directoryDetailsData = $directoryDetailsData->rightjoin('plans', 'plan_s.plan_id', '=', 'plans.id');
            $directoryDetailsData = $directoryDetailsData->rightjoin('viewed_listings_counters', 'plan_s.subscriber_id', '=', 'viewed_listings_counters.directory_id');
        }
        if (!empty($amenityUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_amenities as dt_ame', 'dt.id', '=', 'dt_ame.directory_id');
        }
        if (!empty($trademarkUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_media_trademarks_image as dt_trad', 'dt.id', '=', 'dt_trad.directory_id');
        }
        if (!empty($tagsUrls) || !empty($filterData['searchstringUrls']) && array_filter($filterData['searchstringUrls'], 'strlen') !== []) {

            $directoryDetailsData = $directoryDetailsData->addSelect('directory_tags.tags_id');
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_tags', 'dt.id', '=', 'directory_tags.directory_id');
            $directoryDetailsData = $directoryDetailsData->leftJoin('tag_translations', 'directory_tags.tags_id', '=', 'tag_translations.tag_id');

        }


        $directoryDetailsData = $directoryDetailsData->where(function ($query) use ($filterData, $categoryUrls,$subcategoryUrls,$tagsUrls,$language_id, $amenityUrls, $filtersUrls, $timeSelectedUrls, $trademarkUrls) {

                if (!empty($filterData['searchstringUrls']) && array_filter($filterData['searchstringUrls'], 'strlen') !== []) {
                    $searchTerm = $filterData['searchstringUrls'][0];

                        $query->where('dt.title', 'like', '%' . $searchTerm . '%');
                        //$query->orWhere('directory_location.address', 'like', '%' . $searchTerm . '%');

                        $query->orWhereRaw("
                            TRIM(
                                REGEXP_REPLACE(
                                    REGEXP_REPLACE(lc_directory_location.address, '[0-9]+', ''),
                                    '\\\\s+,', ','
                                )
                            ) LIKE ?
                        ", ['%' . trim($searchTerm) . '%']);

                        $query->orWhereExists(function ($q) use ($searchTerm) {
                            $q->select(DB::raw(1))
                            ->from('advertisement_questions as aq')
                            ->join('advertisement as ad', 'aq.advertisement_id', '=', 'ad.id')
                            ->whereColumn('aq.company_id', 'dt.id')
                            ->where('ad.heading', 'LIKE', "%{$searchTerm}%");
                        });

                        // Fetch tag translations
                        $tagTranslations = DB::table('tag_translations')->select('tag_id')->where('language_id', $language_id)->where('title', 'like', '%' . $searchTerm . '%')->get();
                        if (!$tagTranslations->isEmpty()) {
                            foreach ($tagTranslations as $tagTranslation) {
                                $query->orWhereRaw('FIND_IN_SET(?, lc_directory_tags.tags_id)', [$tagTranslation->tag_id]);
                            }
                        }

                        // Fetch trademark
                        $trademark = DB::table('trademarks')->select('id')->where('name', 'like', '%' . $searchTerm . '%')->get();
                        if ($trademark) {
                            foreach ($trademark as $trademarkval) {
                            $query->orWhereRaw('FIND_IN_SET(?, lc_directory_media_trademarks_image.trademark_id)', [$trademarkval->id]);
                            }
                        }
                }

                if (!empty($categoryUrls) && empty($subcategoryUrls) && empty($tagsUrls) ) {

                    if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                        $query->WhereIn('dt_cat.category_id', $categoryUrls);
                    }
                }else if (!empty($categoryUrls) && !empty($subcategoryUrls) && empty($tagsUrls) ) {
                    if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                        $query->WhereIn('dt_cat.category_id', $categoryUrls);
                    }
                    if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
                        $query->WhereIn('dt_sub_cat.sub_category_id', $subcategoryUrls);
                    }
                }else if (!empty($categoryUrls) && !empty($subcategoryUrls) && !empty($tagsUrls) ) {
                    if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                        $query->WhereIn('dt_cat.category_id', $categoryUrls);
                    }
                    if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
                        $query->WhereIn('dt_sub_cat.sub_category_id', $subcategoryUrls);
                    }
                    if (!empty($tagsUrls)) {
                        $query->WhereIn('directory_tags.tags_id', $tagsUrls);
                    }
                }else if (empty($categoryUrls) && !empty($subcategoryUrls) && !empty($tagsUrls) ) {

                    if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
                        $query->WhereIn('dt_sub_cat.sub_category_id', $subcategoryUrls);
                    }
                    $query->WhereIn('directory_tags.tags_id', $tagsUrls);

                }else if (!empty($categoryUrls) && empty($subcategoryUrls) && !empty($tagsUrls) ) {

                    if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                        $query->WhereIn('dt_cat.category_id', $categoryUrls);
                    }
                    $query->WhereIn('directory_tags.tags_id', $tagsUrls);

                }else if (empty($categoryUrls) && !empty($subcategoryUrls) && empty($tagsUrls) ) {
                    if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
                        $query->WhereIn('dt_sub_cat.sub_category_id', $subcategoryUrls);
                    }
                }else if (empty($categoryUrls) && empty($subcategoryUrls) && !empty($tagsUrls) ) {
                    $query->WhereIn('directory_tags.tags_id', $tagsUrls);
                }

                if (!empty($amenityUrls)) {
                    foreach ($amenityUrls as $ameval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $ameval . '",lc_dt_ame.amenities_id)');
                    }
                }
                if (!empty($trademarkUrls)) {
                    foreach ($trademarkUrls as $tradval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $tradval . '",lc_dt_trad.trademark_id )');
                    }
                }
                if (!empty($timeSelectedUrls)) {

                    $user_ip = request()->ip();//getenv('REMOTE_ADDR');
                    $geoData = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);
                    if($user_ip=='127.0.0.1' || $user_ip=='::1' || $user_ip=='localhost' ){
                        $timezone = 'Asia/Kolkata';
                    }else{
                        //$timezone = $geoData["geoplugin_timezone"];
                        $timezone = isset($geoData['timezone']) && !empty($geoData['timezone'])
                        ? $geoData['timezone']
                        : 'Europe/Stockholm'; // fallback timezone
                    }
                    date_default_timezone_set($timezone);

                    $info = getdate();
                    $date = $info['mday'];
                    $month = $info['mon'];
                    $year = $info['year'];
                    $hour = $info['hours'];
                    $min = $info['minutes'];
                    $sec = $info['seconds'];
                    $time_curr = $hour.':'.$min;
                    if($timeSelectedUrls[0]=="open_companies"){
                        $query->Where('ds.open_time','<=', $time_curr)->where('ds.close_time', '>=', $time_curr);
                    }
                    if($timeSelectedUrls[0]=="closed_companies"){
                        $query->where('ds.close_time', '<=', $time_curr);
                    }
                }

        });


        if (!empty($city_name) && ($city_name != 'globally')) {
            $directoryDetailsData = $directoryDetailsData->where('directory_location.city_name',  'like', '%' . $city_name . '%');
        }
        $distance = session('distance') ?? $distance_default->to_distance ?? '';
        if ($distance) {
            $directoryDetailsData->having('distance', '<', $distance);
        }
        if (!empty($filtersUrls)) {
            if ($filtersUrls[0] == 'mostpopular') {
                $directoryDetailsData = $directoryDetailsData->where('dt.popular', '1');
            }
            $sortField = $filtersUrls[0] == 'latestcompany' ? 'dt.created_at' : ($filtersUrls[0] == 'relevant-listings' ? 'plans.price' : 'distance');
            $sortOrder = $filtersUrls[0] == 'relevant-listings' ? 'desc' : 'asc';
        } else {
            $sortField = 'search_count';
            $sortOrder = 'desc';
        }
        $directoryDetailsData = $directoryDetailsData->where('dt.status', 'Active')
                                                     ->groupBy('dt.id')
                                                     ->orderBy($sortField, $sortOrder)
                                                     //dd($directoryDetailsData->toSql());
                                                    ->paginate(50);

        $directoryDetails = $directoryDetailsData;
        $directoryDetails->appends($request->query());

        $distancesetting = Distancesetting::where('status','Active')->orderBy('id', 'asc')->get();
        $wislistdirectoryId = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = DB::table('wislist_directory')->select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            $wislistdirectoryId=array();
            foreach($wishlists as $wishid){
                $wislistdirectoryId[]=$wishid->directory_id;
            }
            return view('client.listings', compact('categoryUrls','subcategoryUrls','tagsUrls','language_id','directoryDetails','distancesetting','wislistdirectoryId','allParentcategory','latitude_session','longitude_session'));
        } else {
            return view('client.listings', compact('categoryUrls','subcategoryUrls','tagsUrls','language_id','directoryDetails','distancesetting','wislistdirectoryId','allParentcategory','latitude_session','longitude_session'));
        }
    }


    public function getMessages(Request $request)
    {
        $email = $request->input('email');
        if (!$email) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Email is required.'
            ]);
        }
        $messages = CustomerClubs::where('email', $email)->get();
        return response()->json([
            'status' => 'success',
            'messages' => $messages
        ]);
    }


    public function deleteCustomerClub(Request $request)
    {
        $deleted = CustomerClubs::where('email', $request->email)->delete();
        return response()->json([
            'status' => 'success',
            'messages' => 'Successfully deleted'
        ]);
    }


    public function redeemOffersCustomerClubbyEmail(Request $request)
    {
        $email = $request->input('email');
        if (!$email) {
            return response()->json([
                'status' => 'error',
                'msg' => 'Email is required.'
            ]);
        }
        $messages = OffersEmailNotificationHistory::where('seller_id', Auth::user()->directory_id)->where('user_email', $email)->get();
        //dd($messages);
        return response()->json([
            'status' => 'success',
            'messages' => $messages
        ]);
    }


    public function loadTreeStructureListings(Request $request) {
        $data['parent_category']            = Helper::getAllParentCategory();
        return view('client.loadTreeStructureListings', compact('data'))->render();
    }

    public function loadTreeStructureShop(Request $request) {
        $data['product_parent_category']    = Helper::getAllParentProductCategory();
        return view('client.loadTreeStructureShop', compact('data'))->render();
    }

    public function loadMostViewedListings(Request $request) {

        $wislistdirectoryId=array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = DB::table('wislist_directory')->select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            $wislistdirectoryId=array();
            foreach($wishlists as $wishid){
                $wislistdirectoryId[]=$wishid->directory_id;
            }
        }

        $user_ip = getenv('REMOTE_ADDR');
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

        $distance_default = Distancesetting::where('status','Active')->where('default_distance','yes')->orderBy('id', 'asc')->first();
        // Your existing code for fetching most viewed listings
        $most_viewed_listings = ViewedListingsCounter::select(
            'directory.*',
            'directory_location.latitude',
            'directory_location.longitude',
            DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            'viewed_listings_counters.visiters',
            'viewed_listings_counters.directory_id',
        )
            ->selectRaw("COUNT(lc_viewed_listings_counters.directory_id) as occurence, lc_viewed_listings_counters.directory_id")
            ->join('directory', 'directory.id', '=', 'viewed_listings_counters.directory_id')
            ->join('directory_location', 'directory.id', '=', 'directory_location.directory_id')
            ->groupBy("viewed_listings_counters.directory_id")
            ->orderby('distance', 'asc');

            if(session('distance')){
                $most_viewed_listings = $most_viewed_listings->having('distance', '<', session('distance'));
            }else{
                $most_viewed_listings = $most_viewed_listings->having('distance', '<', $distance_default->to_distance??'');
            }
            $most_viewed_listings = $most_viewed_listings->where('directory.status', 'Active')->get()->take(10);

        // Return the view as HTML
        return view('client.most_viewed_listings', compact('most_viewed_listings','wislistdirectoryId'))->render();
    }


    function get_city_lat_long($latitude, $longitude)
    {
        try {
            $apiKey = env('GOOGLE_MAP_API_KEY');
            $geocode = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key={$apiKey}&latlng=$latitude,$longitude&sensor=false");

            $geocode = json_decode($geocode);
            //dd($geocode);
            $add_array  = $geocode->results;
            $add_array = $add_array[0];
            $add_array = $add_array->address_components;

            $country = "Not found";
            $state = "Not found";
            $city = "Not found";

            foreach ($add_array as $key) {
                if ($key->types[0] == 'locality' || $key->types[0] == 'postal_town') {
                    $city = $key->long_name;
                }
                if ($key->types[0] == 'administrative_area_level_2') {
                    $state = $key->long_name;
                }
                if ($key->types[0] == 'administrative_area_level_1') {
                    $country = $key->long_name;
                }
            }

            return $city;
        } catch (\Exception $e) {
            return null;
        }
    }



    function removeSubstring($originalString, $substringToRemove) {
        $resultString = str_replace($substringToRemove, '', $originalString);
        return $resultString;
    }


    public function updateDistanceSessionData(Request $request)
    {
        $distance = $request->input('distance');
        $request->session()->forget('distance');
        $request->session()->put('distance', $distance);
        return response()->json($distance);
    }


    public function directories_map(Request $request)
    {
        Helper::countwebsite();
        $language_id = Helper::getselectedLanguage(); // get selected language Id
        $allParentcategory = Helper::getAllParentCategory();

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
        if (!empty($params['amenity'])) {
            $configData['amenityUrls'] = $params['amenity'];
        }
        if (!empty($params['trademark'])) {
            $configData['trademarkUrls'] = $params['trademark'];
        }
        if (!empty($params['tags'])) {
            $configData['tagsUrls'] = $params['tags'];
        }
        if (!empty($params['search_string'])) {
            $configData['searchstringUrls'] = $params['search_string'];
        }
        if (!empty($params['filters'])) {
            $configData['filtersUrls'] = $params['filters'];
        }

        if (!empty($params['subcat'])) {
            $configData['subcategoryUrls'] = $params['subcat'];
        }
        $filterData = $configData;
        $categoryUrls = [];
        $subcategoryUrls = [];
        $amenityUrls = [];
        $filtersUrls = [];
        $trademarkUrls = [];
        $tagsUrls = [];
        if (array_key_exists('categoryUrls', $filterData)) {
            if (!empty($filterData['categoryUrls'])) {
                $categoryUrls =  array_unique($filterData['categoryUrls']);
            }
        }
        if (array_key_exists('amenityUrls', $filterData)) {
            if (!empty($filterData['amenityUrls'])) {
                $amenityUrls =  array_unique($filterData['amenityUrls']);
            }
        }
        if (array_key_exists('trademarkUrls', $filterData)) {
            if (!empty($filterData['trademarkUrls'])) {
                $trademarkUrls =  array_unique($filterData['trademarkUrls']);
            }
        }
        if (array_key_exists('tagsUrls', $filterData)) {
            if (!empty($filterData['tagsUrls'])) {
                $tagsUrls =  array_unique($filterData['tagsUrls']);
            }
        }
        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        if (array_key_exists('filtersUrls', $filterData)) {
            if (!empty($filterData['filtersUrls'])) {
                $filtersUrls =  array_unique($filterData['filtersUrls']);
            }
        }
        if (array_key_exists('subcategoryUrls', $filterData)) {
            if (!empty($filterData['subcategoryUrls'])) {
                $subcategoryUrls =  array_unique($filterData['subcategoryUrls']);
            }
        }

        $user_ip = request()->ip();
        $geoData = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);

        if (session()->has('latitude_city')) {
            $latitude1  = session('latitude_city');
            $longitude1 = session('logitude_city');
        } else {
            if (!session()->has('latitude')) {

                $latitude1  = '57.107118';
                $longitude1 = '12.252091';
                if ($user_ip && $user_ip != '127.0.0.1' && $user_ip != '::1') {

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

        if (session('filter_city')){
            if(session('filter_city') == 'globally'){
                $address 	= $geoData['city'];
            }else{
                $address 	= session('filter_city');
            }
		}else{
			$address 			= $geoData['city'];
		}
        $getLatLog = $this->get_lat_long($address);
        //DB::enableQueryLog();

        $directoryDetailsData = DB::table('directory as dt')
            ->select(
                'dt.*',
                'directory_location.latitude',
                'directory_location.longitude',
                'directory_tags.tags_id',
                'directory_media_trademarks_image.trademark_id',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            );



        if (!empty($searchstringUrls)) {

            $directoryDetailsData =    $directoryDetailsData->where(function ($query) use ($searchstringUrls) {
                $query->where('dt.title', 'like', '%' . $searchstringUrls[0] . '%');
                $languageid = Helper::getselectedLanguage();
                $dircetory_tags_id = DB::table('tag_translations')->select('tag_translations.*')->where('tag_translations.language_id', $languageid)
                ->where('tag_translations.title','like', '%' . $searchstringUrls[0] . '%')
                ->get();
                 $dircetory_trademark_id = DB::table('trademarks')->where('name','like', '%' . $searchstringUrls[0] . '%')->first();

                 if (!$dircetory_tags_id->isEmpty()) {
                    foreach ($dircetory_tags_id as $dirTagVal) {
                        $query->orwhereRaw('FIND_IN_SET("' . $dirTagVal->tag_id . '",lc_directory_tags.tags_id)');
                    }
                }

                $dircetory_trademark_id = DB::table('trademarks')->where('name','like', '%' . $searchstringUrls[0] . '%')->first();
                if(!empty($dircetory_trademark_id)){
                    $query->orwhereRaw('FIND_IN_SET("' . $dircetory_trademark_id->id. '",lc_directory_media_trademarks_image.trademark_id )');
                }
            });
        }
        $directoryDetailsData = $directoryDetailsData->leftjoin('directory_tags', 'dt.id', '=', 'directory_tags.directory_id');
        $directoryDetailsData = $directoryDetailsData->leftJoin('tag_translations', 'directory_tags.tags_id', '=', 'tag_translations.tag_id');
        //echo $directoryDetailsData->toSql(); die; //$queries = DB::getQueryLog();
        //echo "<pre>"; print_r($queries); die;

        $directoryDetailsData = $directoryDetailsData->leftjoin('directory_media_trademarks_image', 'dt.id', '=', 'directory_media_trademarks_image.directory_id');
        $directoryDetailsData = $directoryDetailsData->leftjoin('directory_location', 'dt.id', '=', 'directory_location.directory_id');
        if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id');
        }
        if ((!empty($subcategoryUrls)) && $subcategoryUrls[0] != 'all') {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_sub_category as dt_sub_cat', 'dt.id', '=', 'dt_sub_cat.directory_id');
        }
        if (!empty($filtersUrls) && $filtersUrls[0] == 'latestcompany') {
            $directoryDetailsData = $directoryDetailsData->orderBy('dt.created_at', 'desc');
        }
        if (!empty($filtersUrls) && $filtersUrls[0] == 'mostpopular') {
            $directoryDetailsData = $directoryDetailsData->where('dt.popular', '1');
        }

        if (!empty($amenityUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_amenities as dt_ame', 'dt.id', '=', 'dt_ame.directory_id');
        }
        if (!empty($trademarkUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_media_trademarks_image as dt_trad', 'dt.id', '=', 'dt_trad.directory_id');
        }
        if (!empty($tagsUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_tags as dt_tag', 'dt.id', '=', 'dt_tag.directory_id');
        }

        $directoryDetailsData = $directoryDetailsData->where(function ($query) use ($filterData, $categoryUrls,$subcategoryUrls, $amenityUrls, $filtersUrls, $trademarkUrls,$tagsUrls) {
                if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                    $query->WhereIn('dt_cat.category_id', $categoryUrls);
                }
                if (!empty($categoryUrls) && (!empty($subcategoryUrls))) {
                    $query->orWhereIn('dt_sub_cat.sub_category_id', $subcategoryUrls);
                }
                if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
                    $query->WhereIn('dt_sub_cat.sub_category_id', $subcategoryUrls);
                }
                if (!empty($amenityUrls)) {
                    foreach ($amenityUrls as $ameval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $ameval . '",lc_dt_ame.amenities_id)');
                    }
                }
                if (!empty($trademarkUrls)) {
                    foreach ($trademarkUrls as $tradval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $tradval . '",lc_dt_trad.trademark_id )');
                    }
                }
                if (!empty($tagsUrls)) {
                    foreach ($tagsUrls as $tagsval) {
                        $query->orWhere('directory_tags.tags_id', $tagsval);
                    }
                }
            });

        $directoryDetailsData = $directoryDetailsData->Where('dt.status', 'Active')->groupBy('dt.id')->orderBy('distance', 'asc')->get();

        //dd($directoryDetailsData);
        $substringToRemove = "'";
            $result_latitude = $this->removeSubstring($latitude, $substringToRemove);
            $result_longitude= $this->removeSubstring($longitude, $substringToRemove);

        $wishlists = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = DB::table('wislist_directory')->select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            return view('client.listingsmap', ['directoryDetails' => $directoryDetailsData, 'wishlists' => $wishlists, 'latitude_session'=>$result_latitude, 'longitude_session'=>$result_longitude, 'allParentcategory' => $allParentcategory]);
        } else {
            return view('client.listingsmap', ['directoryDetails' => $directoryDetailsData, 'wishlists' => $wishlists, 'latitude_session'=>$result_latitude, 'longitude_session'=>$result_longitude, 'allParentcategory' => $allParentcategory]);
        }
    }

    public function listingbytag(Request $request, $tag = '')
    {
        Helper::countwebsite();
        $language_id = Helper::getselectedLanguage(); // get selected language Id
        $allParentcategory = Helper::getAllParentCategory();
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
        if (!empty($params['amenity'])) {
            $configData['amenityUrls'] = $params['amenity'];
        }
        if (!empty($params['trademark'])) {
            $configData['trademarkUrls'] = $params['trademark'];
        }
        if (!empty($params['search_string'])) {
            $configData['searchstringUrls'] = $params['search_string'];
        }
        if (!empty($params['filters'])) {
            $configData['filtersUrls'] = $params['filters'];
        }
        $filterData = $configData;
        $categoryUrls = [];
        $amenityUrls = [];
        $trademarkUrls = [];
        $filtersUrls = [];
        if (array_key_exists('categoryUrls', $filterData)) {
            if (!empty($filterData['categoryUrls'])) {
                $categoryUrls =  array_unique($filterData['categoryUrls']);
            }
        }
        if (array_key_exists('amenityUrls', $filterData)) {
            if (!empty($filterData['amenityUrls'])) {
                $amenityUrls =  array_unique($filterData['amenityUrls']);
            }
        }
        if (array_key_exists('trademarkUrls', $filterData)) {
            if (!empty($filterData['trademarkUrls'])) {
                $trademarkUrls =  array_unique($filterData['trademarkUrls']);
            }
        }
        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        if (array_key_exists('filtersUrls', $filterData)) {
            if (!empty($filterData['filtersUrls'])) {
                $filtersUrls =  array_unique($filterData['filtersUrls']);
            }
        }
        DB::enableQueryLog();
        $directoryDetailsData = DB::table('directory as dt')
            ->select(
                'dt.*',
                'dt_trans.id AS directory_lang_id',
                'dt_trans.title as dtr_title',
                'dt_trans.description as dtr_description',
                'dt_trans.seo_url',
                'ds.day',
                'ds.is_closed',
                'ds.open_time',
                'ds.close_time',
                'directory_tags.tags_id',
            );
        if (!empty($searchstringUrls)) {
            //$directoryDetailsData = $directoryDetailsData->where('dt.title', 'like', '%' . $searchstringUrls[0] . '%');
            $directoryDetailsData =    $directoryDetailsData->where(function ($query) use ($searchstringUrls) {
                $dircetory_tags_id = DB::table('category_tags')->where('tags','like', '%' . $searchstringUrls[0] . '%')->get();
                $dircetory_trademark_id = DB::table('trademarks')->where('name','like', '%' . $searchstringUrls[0] . '%')->first();
                $query->where('dt.title', 'like', '%' . $searchstringUrls[0] . '%');
                if(!empty($dircetory_tags_id)){
                    // $query->orwhereRaw('FIND_IN_SET("' . $dircetory_tags_id->id. '",lc_directory_tags.tags_id)');
                    foreach ($dircetory_tags_id as $dirtagval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $dirtagval->id . '",lc_directory_tags.tags_id)');
                    }
                }
                if(!empty($dircetory_trademark_id)){
                    $query->orwhereRaw('FIND_IN_SET("' . $dircetory_trademark_id->id. '",lc_directory_media_trademarks_image.trademark_id )');
                }
            });
        }




        $directoryDetailsData = $directoryDetailsData->leftjoin('directory_basic_translation as dt_trans', 'dt.id', '=', 'dt_trans.directory_id');
        $directoryDetailsData = $directoryDetailsData->leftjoin('directory_schedule as ds', 'dt.id', '=', 'ds.directory_id');
        $directoryDetailsData = $directoryDetailsData->leftjoin('directory_tags', 'dt.id', '=', 'directory_tags.directory_id');
        $directoryDetailsData = $directoryDetailsData->leftjoin('directory_media_trademarks_image', 'dt.id', '=', 'directory_media_trademarks_image.directory_id');
        if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id');
        }
        if (!empty($filtersUrls) && $filtersUrls[0] == 'latestcompany') {
            $directoryDetailsData = $directoryDetailsData->orderBy('dt.created_at', 'desc');
        }
        if (!empty($filtersUrls) && $filtersUrls[0] == 'mostpopular') {
            $directoryDetailsData = $directoryDetailsData->where('dt.popular', '1');
        }
        if (!empty($filtersUrls) && $filtersUrls[0] == 'relevant-listings') {
            $directoryDetailsData = $directoryDetailsData->rightjoin('plan_subscriptions as plan_s', 'dt.id', '=', 'plan_s.subscriber_id');
            $directoryDetailsData = $directoryDetailsData->rightjoin('plans', 'plan_s.plan_id', '=', 'plans.id');
            $directoryDetailsData = $directoryDetailsData->rightjoin('viewed_listings_counters', 'plan_s.subscriber_id', '=', 'viewed_listings_counters.directory_id');
        }
        if (!empty($amenityUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_amenities as dt_ame', 'dt.id', '=', 'dt_ame.directory_id');
        }
        if (!empty($trademarkUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_media_trademarks_image as dt_trad', 'dt.id', '=', 'dt_trad.directory_id');
        }
        $current_day = date('l', strtotime(date("y-m-d")));
        $directoryDetailsData = $directoryDetailsData->Where('dt_trans.language_id',  $language_id)
            ->where(function ($query) use ($filterData, $categoryUrls, $amenityUrls, $current_day, $filtersUrls, $trademarkUrls) {
                if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                    $query->WhereIn('dt_cat.category_id', $categoryUrls);
                }
                if (!empty($amenityUrls)) {
                    foreach ($amenityUrls as $ameval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $ameval . '",lc_dt_ame.amenities_id)');
                    }
                }
                if (!empty($trademarkUrls)) {
                    foreach ($trademarkUrls as $tradval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $tradval . '",lc_dt_trad.trademark_id )');
                    }
                }
                $query->Where('ds.day', strtolower($current_day));
            });
        $directoryDetailsData = $directoryDetailsData->where('dt.status', 'Active')->where('directory_tags.tags_id', $tag)->groupBy('dt.id')->orderBy('dt.id', 'desc')->paginate(10);
        $most_viewed_listings = ViewedListingsCounter::select(
            'directory.*',
            'viewed_listings_counters.visiters',
            'viewed_listings_counters.directory_id',
        )
            ->selectRaw("COUNT(directory_id) as occurence, directory_id")
            ->join('directory', 'directory.id', '=', 'viewed_listings_counters.directory_id')
            ->groupBy("viewed_listings_counters.directory_id")
            ->orderBy("occurence", "desc")
            ->get()->take(10);

		$pDirectorySubCatData  = DB::table('directory')->select('directory.*', 'diry_cat.category_id AS dir_cat_id', 'diry_tags.*')
            ->leftjoin('directory_category as diry_cat', 'directory.id', '=', 'diry_cat.directory_id')
            ->leftjoin('directory_tags as diry_tags', 'directory.id', '=', 'diry_tags.directory_id')
            ->where('directory.deleted', '0')->where('directory.status', 'Active')->get();

        $wishlists = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = DB::table('wislist_directory')->select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            return view('client.listings', ['directoryDetails' => $directoryDetailsData, 'most_viewed_listings' => $most_viewed_listings, 'wishlists' => $wishlists, 'allParentcategory' => $allParentcategory, 'pDirectorySubCatData' => $pDirectorySubCatData]);
        } else {
            return view('client.listings', ['directoryDetails' => $directoryDetailsData, 'most_viewed_listings' => $most_viewed_listings, 'wishlists' => $wishlists, 'allParentcategory' => $allParentcategory, 'pDirectorySubCatData' => $pDirectorySubCatData]);
        }
    }



    public function details($text = "")
    {
        Helper::countwebsite();
        $id = DB::table('directory')->select('id','slug','deleted')->where('slug', $text)->first();
        if (!$id) {
            abort(404); // or return redirect()->back();
        }

        if($id->deleted!=1){
            //try {
                $userId = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
                $user_ip = request()->ip(); //$_SERVER['REMOTE_ADDR'];
                $geoData = Helper::userCurrentLocationDetailsOnIpAddress($user_ip); //$geo = @unserialize(file_get_contents('http://ip-api.com/php/'.$user_ip));
                if ($user_ip == '127.0.0.1' || $user_ip=='::1' || $user_ip == 'localhost') {
                    $timezone = 'Asia/Kolkata';
                } else {
                    try {
                        $timezone = $geoData["timezone"];
                    } catch (Exception $e) {
                        $timezone = 'Europe/Stockholm';
                    }
                }
                date_default_timezone_set($timezone);

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

                $info = getdate();
                $date = $info['mday'];
                $month = $info['mon'];
                $year = $info['year'];
                $hour = $info['hours'];
                $min = $info['minutes'];
                $sec = $info['seconds'];
                $serverDay = strtolower(date("D"));

                $language_id = Helper::getselectedLanguage(); // get selected language Id
                $id = DB::table('directory')->select('id','slug')->where('slug', $text)->first();
                $id = $id->id;

                $visitors = Helper::visitors('directory',$id);
                if(isset($_GET['ref']) == 'cc'){
                    Helper::countCustomerClubsVisitors($id);
                }

                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                $uniquevisitor = ViewedListingsCounter::where('directory_id', $id)->where('visiters', $ip)->first();
                if (!$uniquevisitor) {
                    $counter = new ViewedListingsCounter;
                    $counter->directory_id = $id;
                    $counter->visiters = $ip;
                    $counter->save();
                }

                $metadata = DB::table('directory_seo')->where('module_id', $id)->where('page_type', 'directory')->first();

                $data['mainDirectoryDetails'] = Diectory::with('user')
                    ->with(['directoryLanguage' => function ($q) use ($language_id) {
                        $q->where('language_id', '=', $language_id);
                    }])->select('*')->where('id', $id)->first();

                $data['address']                    = DB::table('directory_location')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['bannerImage']                = DB::table('directory_media_listing_image')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['galleryImage']               = DB::table('directory_gallery_images')->select('*')->where('directory_id', $id)->orderby('id', 'desc')->limit(20)->get()->toArray();
                $data['allgalleryImage']            = DB::table('directory_gallery_images')->select('*')->where('directory_id', $id)->orderby('id', 'desc')->get()->toArray();

                $data['galleryImageChunk']          = DB::table('directory_gallery_images')->select('*')->where('directory_id', $id)->orderby('id', 'desc')->limit(20)->get()->chunk(10);
                $data['allgalleryImageChunk']       = DB::table('directory_gallery_images')->select('*')->where('directory_id', $id)->orderby('id', 'desc')->get()->chunk(10);

                $data['directory_media_cover']      = DB::table('directory_media_cover')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['amenity']                    = Helper::getAmenity();                 /* get amenity */
                $data['amenityData']                = DB::table('directory_amenities')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['directoryTypeimage']         = DB::table('directory_type')->select('*')->where('directory_id',  $id)->groupBy('block_num')->get()->toArray();
                $data['directoryTypeData']          = DB::table('directory_type')->select('*')->where('directory_id',  $id)->get()->toArray();
                $data['directoryTypeDataPosition']  = DB::table('directory_type')->select('*')->where('directory_id',  $id)->where('column_name', "position")->orderby(DB::raw('CAST(column_value AS SIGNED)'), 'ASC')->get()->toArray(); // Added by Madhulika for manage the position
                $data['mediaData']                  = DB::table('directory_media')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['contactData']                = DB::table('directory_contact')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['trademarkData']              = DB::table('directory_media_trademarks_image')->select('*')->where('directory_id', $id)->get()->toArray();

                $productDirctoryData                = DB::table('product')->select('product.*',
                                        DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                        * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                        + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                                        'pro_trans.id AS product_lang_id', 'pro_trans.name AS product_lang_name', 'pro_trans.short_content')
                                        ->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id');


                $data['relatedProductData']   = $productDirctoryData
                                    ->Where('pro_trans.language_id', $language_id)
                                    ->where('product.directory_id', $id)
                                    ->where('product.deleted', '0')->where('product.is_free_product', 0)
                                    ->where('product.status', 'Active')->groupBy('product.id')
                                    // ->orderBy('product.id', 'desc')
                                    ->orderby('distance', 'desc')
                                    //->having('distance', '<', 2000)
                                    ->get()->take(7);

                $data['directoryProductData']         = $productDirctoryData
                                    ->Where('pro_trans.language_id', $language_id)
                                    ->where('product.directory_id', $id)
                                    ->where('product.deleted', '0')->where('product.is_free_product', 0)
                                    ->where('product.status', 'Active')->groupBy('product.id')
                                    // ->orderBy('product.id', 'desc')
                                    ->orderby('distance', 'asc')
                                    //->having('distance', '<', 2000)
                                    ->get()->toArray();
                $data['offerData']                  = DB::table('offer')->select('*',  DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                                    * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                                    + sin(radians(" .$latitude. ")) * sin(radians(latitude))) AS distance"))
                                                    ->where('offer_directory_link', $id)->where('deleted', '0')
                                                    ->where('offer_expire_date', ">=", date('y-m-d'))
                                                    ->orderby('distance', 'asc')
                                                    //->having('distance', '<', 2000)
                                                    ->get()->toArray();

                $data['eventdata'] = Events::where('directory_id', $id)
                                                    ->where('end_date', '>=', date('Y-m-d')) // Correct format for year is 'Y'
                                                    ->where(function ($query) {
                                                        $query->where('display_status', 'display_on_vendor_page_only')
                                                              ->orWhere('display_status', 'display_both');
                                                    })
                                                    ->orderBy('id', 'desc')
                                                    ->get();

                $data['shoppingcardData']            = DB::table('shopping_card')->select('*', DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                                    * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                                    + sin(radians(" .$latitude. ")) * sin(radians(latitude))) AS distance"))
                                                    ->where('directory_link', $id)
                                                    ->where('deleted', '0')
                                                    ->where('expire_date', ">=", date('y-m-d'))
                                                    ->orderby('distance', 'asc')
                                                    //->having('distance', '<', 2000)
                                                    ->get()->toArray();

                $today = date('Y-m-d H:i:s');

                // $data['adevertiseData'] = Advertisement::from('advertisement')
                //     ->select([
                //         'advertisement.*',
                //         'vc_status.status',
                //     ])
                //     ->leftJoin('vendor_competition_status as vc_status',
                //         'advertisement.id', '=','vc_status.competition_id' )
                //     ->whereDate('advertisement.end_date', '>=', $today)
                //     ->whereRaw('FIND_IN_SET(?, lc_advertisement.adminassignvendor)', [$id])
                //     ->where('advertisement.deleted', 0)
                //     ->where('vc_status.status', 'Active')
                //     ->whereHas('questions')
                //     ->orderByDesc('advertisement.id')
                //     ->limit(15)
                //     ->get();

    $data['adevertiseData'] = Advertisement::query()
        ->select([
            'advertisement.*',
            'vendor_competition_status.status',
        ])
        ->leftJoin('vendor_competition_status', function ($join) use ($id) {
            $join->on('advertisement.id','=','vendor_competition_status.competition_id')
            ->where('vendor_competition_status.company_id', $id);
        })
        ->whereDate('advertisement.end_date', '>=', now())
        ->whereRaw(
            'FIND_IN_SET(?, lc_advertisement.adminassignvendor)',
            [$id]
        )
        ->where('advertisement.deleted', '0')
        ->whereHas('questions')
        ->where(function ($query) {
            $query->where('vendor_competition_status.status', 'Active')
                ->orWhereNull('vendor_competition_status.id');
        })
        ->orderByDesc('advertisement.id')
        ->limit(15)
        ->get();

                $data['blogData'] = DB::table('blog as b')
                        ->select(
                            'b.*',
                            DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                            //'plan_subsc.slug as plan_slug'
                        )
                        ->where('b.deleted', '0')
                        ->where('b.directory_id', $id)  // Make sure the blog is not deleted
                        ->where('b.status', 'Active')
                        ->orderBy('b.id', 'desc')
                        ->limit(5)
                        ->get()->toArray();

                $data['categoriesData']             = DB::table('category')->whereIn('id', function ($query) use ($id) {
                    $query->select('category_id')->from('directory_category')->Where('directory_id', $id);
                })->get()->toArray();


                $tagsdirectorydata                  = DB::table('directory_tags')->select('directory_tags.*', 'cat_tags.tags', 'tag_trans.title')
                                                    ->leftjoin('category_tags as cat_tags', 'directory_tags.tags_id', '=', 'cat_tags.id')
                                                    ->leftjoin('tag_translations as tag_trans', 'tag_trans.tag_id', '=', 'cat_tags.id');
                $data['tagsData']                   = $tagsdirectorydata->groupBy('tag_trans.title')->where('tag_trans.language_id', $language_id)->where('tag_trans.company_id', $id)->where('directory_tags.directory_id', $id)->where('cat_tags.status','Active')->orderBy('cat_tags.tags', 'ASC')->get()->toArray();

                $data['owntagsData']                = DB::table('directory_own_tags')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['assortment_links']           = DB::table('directory_assortment_links')->select('*')->where('directory_id', $id)->where('name', '<>', '')->get()->toArray();
                $data['website_links']              = DB::table('directory_website_links')->select('*')->where('directory_id', $id)->where('name', '<>', '')->get()->toArray();

                /*  FOR GETING NORMAL HOLIDAY SCHEDULE START */

                $data['templateandScheduleDaysData']    = DB::table('directory_schedule')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['templateandScheduleDaysData']    = self::group_assoc(json_decode(json_encode($data['templateandScheduleDaysData']), 1), 'day');

                /*  FOR GETING NORMAL HOLIDAY SCHEDULE END */

                /*  FOR GETING PUBLIC HOLIDAY SCHEDULE START */
                $data['publicScheduleData']    = DB::table('directory_custom_holiday')->select('*')->where('custom_date', date("Y-m-d"))->where('directory_id', $id)->get()->toArray();
                $data['pubholidayData']        = DB::table('directory_custom_holiday')->select('*')->where('directory_id', $id)->get()->toArray();
                /*  FOR GETING EXCEPTION HOLIDAY SCHEDULE START */
                //dd($data['pubholidayData']);
                $data['exceptionScheduleData']    = DB::table('directory_exceptional')->select('*')->where('custom_date', date("Y-m-d"))->where('directory_id', $id)->get()->toArray();
                $data['execholidayData']        = DB::table('directory_exceptional')->select('*')->where('directory_id', $id)->get()->toArray();
                $data['directory_services'] = DirectoryServices::where('directory_id',  $id)->get();

                $currentdate = strftime('%F');
                $afterThreeDaysdate = date('Y-m-d', strtotime(strftime('%F') . ' + 25 days'));

                $data['next_threedata_exception']       = DB::table('directory_exceptional')->select('*')->where('custom_date', '>', $currentdate)->where('custom_date', '<=', $afterThreeDaysdate)->where('directory_id', $id)->limit(9)->orderby('custom_date', 'asc')->get()->toArray();
                $data['next_threedata_public']          = DB::table('directory_custom_holiday')->select('*')->where('custom_date', '>', $currentdate)->where('custom_date', '<=', $afterThreeDaysdate)->where('directory_id', $id)->limit(9)->orderby('custom_date', 'asc')->get()->toArray();
                $next_threedata_exception_and_public    = array_merge($data['next_threedata_exception'],$data['next_threedata_public']);
                $data['next_threedata_exception_and_public'] = collect($next_threedata_exception_and_public)->sortBy('custom_date')->toArray();
                $data['next_threedata_exception_and_public'] = array_slice($data['next_threedata_exception_and_public'], 0, 5, true);

                $most_viewed_listings = ViewedListingsCounter::select(
                    'directory.*',
                    'directory_location.latitude',
                    'directory_location.longitude',
                    DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                        * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                        + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                    'viewed_listings_counters.visiters',
                    'viewed_listings_counters.directory_id',
                )
                    ->selectRaw("COUNT(lc_viewed_listings_counters.directory_id) as occurence, lc_viewed_listings_counters.directory_id")
                    ->join('directory', 'directory.id', '=', 'viewed_listings_counters.directory_id')
                    ->join('directory_location', 'directory.id', '=', 'directory_location.directory_id')
                    ->groupBy("viewed_listings_counters.directory_id")
                    // ->orderBy("occurence", "desc")
                    ->orderby('distance', 'asc')
                    //->having('distance', '<', 2000)
                    ->get()->take(7);

                    $userdetails = DB::table('users')->select("*")->where("directory_id",$id)->first();
                    $data['default_image_listing_banner'] = DB::table('default_image')->select("*")->where("section_title", 'Listing Banner Image')->first();

                $tagIds = DB::table('product')->where('directory_id', $id)->pluck('tags')->toArray();
                // Step 2: Flatten and convert comma-separated strings into an array
                $tagIds = collect($tagIds)->flatMap(fn($t) => explode(',', $t))->unique()->filter()->values()->toArray();
                //$tagNames = DB::table('product_tags')->whereIn('id', $tagIds)->pluck('tags','id')->toArray();

                $data['related_tags'] = DB::table('product_tags')
                    ->join('tag_product_translations', 'product_tags.id', '=', 'tag_product_translations.tag_id')
                    ->where('tag_product_translations.language_id', $language_id)
                    ->whereIn('product_tags.id', $tagIds)
                    ->where('product_tags.status', 'Active')
                    ->orderBy('product_tags.tags', 'ASC')
                    ->pluck('tag_product_translations.title', 'product_tags.id')
                    ->toArray();

                $data['imp_ptags'] = DB::table('company_imp_product_tags')
                    ->join('tag_product_translations', 'company_imp_product_tags.ptag_id', '=', 'tag_product_translations.tag_id')
                    ->where('tag_product_translations.language_id', $language_id)
                    ->where('company_imp_product_tags.company_id', $id)
                    //->orderBy('company_imp_product_tags.tags', 'ASC')
                    ->pluck('tag_product_translations.title', 'company_imp_product_tags.ptag_id')
                    ->toArray();

                $data['bookingdata'] = Booking::query()->where('directory_id', $id)->orderBy('id', 'desc')->take(5)->get();
                $data['frontend_setting'] = Frontendsetting::first();


                $wishlists = array();
                $wishlists_dir = array();
                if (auth()->guard('web')->check() != 0) {
                    $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
                    $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
                    $wishlists_dir = DB::table('wislist_directory')->select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
                    return view('client.listingdetails', ['data' => $data, 'most_viewed_listings' => $most_viewed_listings, 'metadata' => $metadata, 'wishlists' => $wishlists, 'wishlists_dir' => $wishlists_dir, 'userData' => $userdetails]);
                } else {
                    return view('client.listingdetails', ['data' => $data, 'most_viewed_listings' => $most_viewed_listings, 'metadata' => $metadata, 'wishlists' => $wishlists, 'wishlists_dir' => $wishlists_dir, 'userData' => $userdetails]);
                }

            // } catch (Exception $e) {
            //     return redirect('/');
            // }
        }else{
            return redirect('/');
        }

    }
    function group_assoc($array, $key)
    {
        $return = array();
        foreach ($array as $v) {
            $return[$v[$key]][] = $v;
        }
        return $return;
    }

    public function appointmentBooking(Request $request)
    {

        $userIP = $this->getIp();
        $new_arr = @unserialize(file_get_contents('http://ip-api.com/php/'.$userIP));
        if($userIP=='127.0.0.1' || $userIP=='localhost' || $userIP=='::1' ){
            $countryCode = 'IN';
        }else{
            $countryCode       =   $new_arr['countryCode'];
        }
        $countryNameCode = Country::where('code', $countryCode)->first();

        $countryCode = session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');
        if($countryCodeData){
           $countryCode = $countryCodeData->phonecode;
        }else{
           $countryCode = $countryNameCode->phonecode;
        }


        unset($request['_token']);
        // $validator = \Validator::make($request->all(), [
        //     'name'          => 'required',
        //     'email'         => 'required',
        //     'booking_phone' => 'required'
        // ]);
        $validator = \Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'email' => 'required|email:rfc,dns|max:255',
            'booking_phone' => [
                'required',
                'regex:/^(\+91[\-\s]?)?[6-9]\d{9}$|^(\+46[\-\s]?)?7\d{8}$/'
            ]
        ], [
            'email.email' => 'Please enter a valid email address.',
            'booking_phone.regex' =>'Please enter a valid mobile number.'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        } else {

            //try {

                $id                         = $request->input('directory_id');
                $adult                      = $request->input('adult');
                $child                      = $request->input('child');

                $booking                    = new Booking;
                $booking->directory_id      = $id;
                $booking->user_id           = $request->input('user_id');
                $booking->name              = $request->input('name');
                $booking->email             = $request->input('email');
                $booking->adult             = isset($adult) ? $adult : 0;
                $booking->child             = isset($child) ? $child : 0;
                $booking->service           = $request->input('service');
                $booking->message           = $request->input('message');
                $booking->booking_date      = $request->input('booking_date');
                $booking->country_code      = $countryCode;
                $booking->phone             = $request->input('booking_phone');
                $booking->created_date      = Carbon::now();
                $res = $booking->save();
                $directoryData              = DirectoryContact::where('directory_id', $id)->first();
                $vendorData                 = User::where('directory_id', $id)->first();
                $vendorCountryCode          = $vendorData->country_code ?? '';
                $vendorDataPhone            = $vendorData->phone ?? '';
                $directoryEmail             = $directoryData->email ?? '';
                /*$userPhone                  = auth()->user()->phone; */
                if ($res) {

                    Mail::to(env('APP_EMAIL'))->send(new AdminCopyBookingMail($request->all()));
                    Mail::to($directoryEmail)->send(new BookingMail($request->all()));
                    Mail::to($request->input('email'))->send(new VisitorAndUserBookingMail($request->all()));

                    $usermsg = "Thankyou for contacting us, await confirmation. Our official number is ". $directoryData->phone .". If you have any query please contact us! ";
                    $vendormsg = "You have received an enquery from user! Please check the Booking Detail in 'My Account' after login.";
                    $sendSms = LaraTwilio::notify('+' . $countryCode . $request->input('booking_phone'), $usermsg);
                    $sendSms = LaraTwilio::notify('+' . $vendorCountryCode . $vendorDataPhone, $vendormsg);
                    return response()->json(['status' => true, 'message' => 'Thankyou for contacting us, await confirmation!']);
                } else {
                    return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
                }
            // } catch (Exception $e) {
            //     return response()->json(['status' => false, 'message' => 'Something went wrong. Try again!']);
            // }
        }
    }

    public function CustomerClubReg(Request $request){

        $countryCode = session()->get('countrycode');
        $countryCodeData = session()->get('countrynamecode');

        $cc_user_id                         = $request->input('cc_user_id');
        $cc_directory_id                      = $request->input('cc_directory_id');

        $directoryData              = DirectoryContact::where('directory_id', $cc_directory_id)->first();
        $userData                   = User::where('id', $cc_user_id)->first();
        $vendorData                 = User::where('directory_id', $cc_directory_id)->first();
        $vendorCountryCode          = $vendorData->country_code ?? '';
        $directoryEmail             = $directoryData->email ?? '';

        unset($request['_token']);
        $validator = \Validator::make($request->all(), [
            'customer_club_name'          => 'required',
            'customer_club_lname'         => 'required',
            'customer_club_phone'         => 'required',
            'customer_club_email'         => 'required',
            'customer_club_postal_code'   => 'required',
            'country'                     => 'required',
            'state'                       => 'required',
            'city'                        => 'required',
        ],[
            'customer_club_name.required'        => 'First name is required.',
            'customer_club_lname.required'       => 'Last name is required.',
            'customer_club_phone.required'       => 'Phone number is required.',
            'customer_club_email.required'       => 'Email address is required.',
            'customer_club_email.email'          => 'Enter a valid email address.',
            'customer_club_postal_code.required' => 'Postal code is required.',
            'country.required'                   => 'Country is required.',
            'state.required'                     => 'State is required.',
            'city.required'                      => 'City is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        } else {

            $exists = CustomerClubs::where('customer_id', $cc_user_id)->where('seller_id', $cc_directory_id)->exists();

            if ($exists) {
                return response()->json([
                    'status' => 'exists',
                    'message' => 'You have already joined this Customer Club.'
                ]);
            }
            //try {
                $customer_club                    = new CustomerClubs();
                $customer_club->fname           = $request->input('customer_club_name');
                $customer_club->lname           = $request->input('customer_club_lname');
                $customer_club->phone           = $request->input('customer_club_phone');
                $customer_club->email           = $request->input('customer_club_email');
                $customer_club->zipcode         = $request->input('customer_club_postal_code');
                $customer_club->country         = $request->input('country');
                $customer_club->state           = $request->input('state');
                $customer_club->city            = $request->input('city');
                $customer_club->message         = $request->input('customer_club_message');
                $customer_club->seller_id       = $cc_directory_id;
                $customer_club->customer_id     = $cc_user_id;
                $customer_club->joined_at       = Carbon::now();
                $res = $customer_club->save();

                if ($res) {

                    Mail::to($request->input('customer_club_email'))->send(new UserCClubMail($request->all()));
                    Mail::to(env('APP_EMAIL'))->send(new AdminCClubMail($request->all()));
                    Mail::to($directoryEmail)->send(new CompanyCClubMail($request->all()));

                    $usermsg = "Thank you for joining our Customer Club. Our official number is ". $directoryData->phone .". If you have any query please contact us! ";
                    $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . $request->input('customer_club_phone'), $usermsg);

                    $vendormsg = "You have received an enquery from user! Please check the Our Customer Club in 'My Account' after login.";
                    //$sendSms = LaraTwilio::notify('+' . $vendorCountryCode . $directoryData->phone, $vendormsg);
                    return response()->json(['status' => true, 'message' => 'Thank you for joining our Customer Club!']);
                } else {
                    return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
                }
            // } catch (Exception $e) {
            //     return response()->json(['status' => false, 'message' => 'Something went wrong. Try again!']);
            // }
        }
    }

    public function userContactVendor(Request $request)
    {
        // 1. Let Laravel handle validation automatically
        $validated = $request->validate([
            'directory_id' => 'required',
            'user_id'      => 'nullable',
            'type'         => 'nullable|string',
            'name'         => 'required|string|max:255',
            'email'        => 'required|email',
            'phone'        => 'required|string',
            'message'      => 'required|string',
        ]);

        // 2. Safely retrieve session data (supports object, array, or null)
        $countryCodeData = session('countrynamecode');
        $phoneCode = is_object($countryCodeData)
            ? ($countryCodeData->phonecode ?? '')
            : ($countryCodeData['phonecode'] ?? '');

        //try {

            // 3. Create booking using mass assignment or explicit fields
            $booking = Booking::create([
                'directory_id' => $validated['directory_id'],
                'user_id'      => $validated['user_id'] ?? null,
                'type'         => $validated['type'] ?? null,
                'name'         => $validated['name'],
                'email'        => $validated['email'],
                'message'      => $validated['message'],
                'country_code' => $phoneCode,
                'phone'        => $validated['phone'],
                'created_date' => now(), // Laravel Carbon helper
            ]);

            // 4. Fetch related model data cleanly
            $directoryEmail = DirectoryContact::where('directory_id', $validated['directory_id'])
                ->value('email'); // Fetches only the email column directly

            $vendorData = User::where('directory_id', $validated['directory_id'])
                ->first(['country_code', 'phone']);

            // 5. Send Emails
            if ($directoryEmail) {
                Mail::to($directoryEmail)->send(new BookingMail($request->all()));
            }
            Mail::to($validated['email'])->send(new VisitorAndUserBookingMail($request->all()));

            // 6. Send SMS Notifications safely
            $userMsg   = "Thank you for contacting us, we will get back to you soon!";
            $vendorMsg = "You have received a product delivery inquiry from a user! Please check the Booking Details in 'My Account' after login.";

            if ($phoneCode && !empty($validated['phone'])) {
                LaraTwilio::notify('+' . ltrim($phoneCode . $validated['phone'], '+'), $userMsg);
            }

            if ($vendorData && $vendorData->country_code && $vendorData->phone) {
                LaraTwilio::notify('+' . ltrim($vendorData->country_code . $vendorData->phone, '+'), $vendorMsg);
            }

            return response()->json([
                'status'  => true,
                'message' => 'Thank you for contacting us, we will get back to you soon!'
            ]);

        // } catch (\Exception $e) {
        //     \Log::error('User contact vendor error: ' . $e->getMessage());

        //     return response()->json([
        //         'status'  => false,
        //         'message' => 'Something went wrong. Try again!'
        //     ], 500);
        // }

    }

    // public function userContactVendor(Request $request)
    // {


    //     $countryCode = session()->get('countrycode');
    //     $countryCodeData = session()->get('countrynamecode');

    //     unset($request['_token']);
    //     $validator = \Validator::make($request->all(), [
    //         'name'          => 'required',
    //         'email'         => 'required',
    //         'phone' => 'required',
    //         'message' => 'required'
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(['status' => false, 'errors' => $validator->messages()]);
    //     } else {

    //         //try {

    //             $id                         = $request->input('directory_id');

    //             $booking                    = new Booking;
    //             $booking->directory_id      = $id;
    //             $booking->user_id           = $request->input('user_id');
    //             $booking->type              = $request->input('type');
    //             $booking->name              = $request->input('name');
    //             $booking->email             = $request->input('email');
    //             $booking->message           = $request->input('message');
    //             $booking->country_code      = $countryCodeData->phonecode;
    //             $booking->phone             = $request->input('phone');
    //             $booking->created_date      = Carbon::now();
    //             $res = $booking->save();

    //             $directoryData              = DirectoryContact::where('directory_id', $id)->first();
    //             $vendorData                 = User::where('directory_id', $id)->first();
    //             $vendorCountryCode          = $vendorData->country_code ?? '';
    //             $vendorDataPhone            = $vendorData->phone ?? '';
    //             $directoryEmail             = $directoryData->email ?? '';
    //             if ($res) {
    //                 //Mail::to(env('APP_EMAIL'))->send(new AdminCopyBookingMail($request->all()));
    //                 Mail::to($directoryEmail)->send(new BookingMail($request->all()));
    //                 Mail::to($request->input('email'))->send(new VisitorAndUserBookingMail($request->all()));

    //                 $usermsg = "Thankyou for contacting us, we will get back to you soon!";
    //                 $vendormsg = "You have received an product delivery enquery from user! Please check the Booking Detail in 'My Account' after login.";
    //                 $sendSms = LaraTwilio::notify('+' . $countryCodeData->phonecode . $request->input('phone'), $usermsg);
    //                 $sendSms = LaraTwilio::notify('+' . $vendorCountryCode . $vendorDataPhone, $vendormsg);

    //                 return response()->json(['status' => true, 'message' => 'Thankyou for contacting us, we will get back to you soon!']);
    //             } else {
    //                 return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
    //             }
    //         // } catch (Exception $e) {
    //         //     return response()->json(['status' => false, 'message' => 'Something went wrong. Try again!']);
    //         // }
    //     }
    // }


    public function addreview(Request $request)
    {
        unset($request['_token']);

        //dd($request->all());

        $validator = \Validator::make($request->all(), [
            'rating' => 'required',
            'comment' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        } else {

            $review = new Review();
            $review->user_id = auth()->id();
            $review->page = 'product';
            $review->page_id = $request->product_id;
            $review->directory_id = $request->review_directory_id;
            $review->star = $request->rating;
            $review->content = $request->comment;
            $res = $review->save();
            $review_id = $review->id;

            $r_images = $request->file('r_images');
            if (is_array($r_images)) {
                foreach ($r_images as $imgrow) {
                    $ext = $imgrow->getClientOriginalExtension();
                    $name2 = $imgrow->getClientOriginalName();
                    $imageName2 = time() . $name2;
                    $imageName = Helper::getSeoUrl($imageName2);
                    $image_resize = Image::make($imgrow->getRealPath());
                    $image_resize->save(public_path('files/reviews_photo/' .$imageName.'.'.$ext));

                    $review_images = new ReviewImages();
                    $review_images->review_id = $review_id;
                    $review_images->product_id = $request->product_id;
                    $review_images->user_id = auth()->id();
                    $review_images->photos = $imageName.'.'.$ext;
                    $review_images->save();
                }
            }

            if ($res) {
                return response()->json(['status' => true, 'message' => 'Thanks for your review and it will show after admin approval']);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
            }
        }
    }

    public function product_variations(Request $request, $id)
    {

        $arributes = $request->arributes; // value = 45,ggg,55,Red
        $requestValues = explode(',', $arributes);



        $attributesVariationData = ProductAttributesVariation::where('product_id', $id)
            ->where(function ($query) use ($requestValues) {
                foreach ($requestValues as $value) {
                    $query->where(DB::raw("FIND_IN_SET('" . $value . "', attribute_value)"), '>', 0);
                }
            })
            ->first();

        //dd($attributesVariationData);

        //$attributesVariationData = ProductAttributesVariation::where('attribute_value', $arributes)->where('product_id', $id)->first();

        return $attributesVariationData;
    }

    public function shopdetails($pid,$slug)
    {

        if (auth()->check()) {
            RecentView::updateOrCreate(
                [
                'user_id' => auth()->id(),
                'product_id' => $pid],
                ['updated_at' => now()] // Update timestamp
            );
        }

        Helper::countwebsite();
        //try{

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

            if (auth()->guard('web')->check() != 0) {
                $user_id = auth()->user()->id;
            }else{
                $user_id = 0;
            }

            $is_purchase_product = Order::whereHas('orderitem', function ($query) use ($pid) {
                $query->where('product_id', $pid);
            })->where('user_id', $user_id)->where('status', 5)->count();

            //dd($pid);

            $product_id = Product::where('seo_url', $slug)->where('id', $pid)->first();
            if($product_id){
                $id = $product_id->id;
            }else{
                $id = 0;
            }


            $language_id = Helper::getselectedLanguage(); // get selected language Id
            $data['productDetails'] = Product::with('user')->with('directorySlug')->with('image')->with('gallery')->with('useraddress')->where('status', 'Active')->where('id', $id)->first();

            if($data['productDetails'] == null){
                return abort(404);
            }

            $visitors = Helper::visitors('product',$id);
            Helper::search_visitors('product',$id);


            if (!empty($data['productDetails']->equal_product)) {
                $main = 'main';
                $data['similar_product'] = Product::with('user')->with(['image' => function ($q) use ($main) {
                    $q->where('main_image', '=', $main);
                }])->with('directorySlug')->with('useraddress')->where('deleted', '0')->where('status', 'Active')->where('equal_product', $data['productDetails']->equal_product)->get();
            } else {
                $data['similar_product'] = [];
            }

            $data['tagsproduct'] = explode(',', $data['productDetails']['tags']);
            $data['tags']         = ProductTags::select('*')->whereIn('id', $data['tagsproduct'])->where('status', 'Active')->orderBy('tags', 'ASC')->get();

            //dd($data['tagsproduct']);

            $reviews = Review::with('user')->with('photos')->where('page_id', $id)->orderBy('id','desc')->where('status', 'Active')->get();

            $productvideo = ProductVideo::where('product_id', $id)->first();
            $directory_id = $data['productDetails']->directory_id ?? 0;
            $FreeProductsID = FreeProductsDiscount::where('product_id', $id)->where('directory_id', $directory_id)->first();

            // $productContentShort = DB::table('product_translation')
            //     ->leftjoin('product', 'product.id', '=', 'product_translation.product_id')
            //     ->select('product_translation.short_content', 'product_translation.long_content', 'product_translation.name', 'product_translation.meta_title', 'product_translation.meta_description')
            //     ->where('product_translation.product_id', $id)
            //     ->where('product_translation.language_id', $language_id)->first();

            $productContentShort = DB::table('product_translation')
            ->leftJoin('product', 'product.id', '=', 'product_translation.product_id')
            ->select(
                'product_translation.short_content',
                'product_translation.long_content',
                'product_translation.name',
                'product_translation.meta_title',
                'product_translation.meta_description'
            )
            ->where('product_translation.product_id', $id)
            ->where('product_translation.language_id', $language_id)
            ->first();
            //dd($productContentShort);

            $metadata = DB::table('directory_seo')->where('module_id', $id)->where('page_type', 'product')->first();

            $wishlists = array();

            $attributesData = ProductAttributes::leftjoin('attribute_statuses', 'attribute_statuses.attribute_id', '=', 'product_attributes.attribute_id')
                    ->leftjoin('attributes', 'attributes.id', '=', 'product_attributes.attribute_id')
                    ->select('product_attributes.*','attribute_statuses.status as attributeStatus')
                    //->where('product_attributes.attribute_value', '!=', null)->where('attributes.status', 1)->where('attribute_statuses.status', 1)->with('attribute')->where('product_id', '=', $id)->get();
                    ->where('product_attributes.attribute_value', '!=', null)->where('attributes.status', 1)->with('attribute')->where('product_id', '=', $id)->get();
            //dd($attributesData);

            $productCategoryData      = DB::table('product')->inRandomOrder()->select('product.*',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'pro_trans.id AS product_lang_id', 'pro_trans.name AS product_lang_name', 'pro_trans.short_content', 'pro_pr.sales_price', 'pro_pr.regular_price')
                ->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id')
                ->leftjoin('product_price as pro_pr', 'product.id', '=', 'pro_pr.product_id');

            $data['relatedProductData'] = $productCategoryData
                                        ->Where('pro_trans.language_id', $language_id)
                                        ->where('product.cat_id',$data['productDetails']->cat_id)
                                        //->where('product.name', 'like', '%' . $data['productDetails']->name. '%')
                                        ->where('product.id', '!=', $id)->where('pro_pr.regular_price','!=', '0')
                                        ->where('product.deleted', '0')
                                        ->where('product.status', 'Active')
                                        ->groupBy('product.id')
                                        // ->orderBy('product.id', 'desc')
                                        ->orderby('distance', 'asc')
                                        //->having('distance', '<', 2000)
                                        ->limit(20)->get()->toArray();

            $data['maylikeProductData'] = $productCategoryData
                                        ->Where('pro_trans.language_id', $language_id)
                                        ->where('product.cat_id',$data['productDetails']->cat_id)
                                        ->where('product.id', '!=', $id)->where('pro_pr.regular_price','!=', '0')
                                        ->where('product.deleted', '0')
                                        ->where('product.status', 'Active')->groupBy('product.id')
                                        // ->orderBy('product.id', 'desc')
                                        ->orderby('distance', 'desc')
                                        //->having('distance', '<', 2000)
                                        ->limit(30)->get()->toArray();

            $substringToRemove = "'";
            $result_latitude = $this->removeSubstring($latitude, $substringToRemove);
            $result_longitude= $this->removeSubstring($longitude, $substringToRemove);


            $userAddressData  = UserAddress::where('user_id', $user_id)->orderBy('id', 'DESC')->first();
            $isApprovedAffiliate = auth()->check()
                && \App\Models\Affiliate::where('user_id', auth()->id())
                    ->where('status', 'approved')
                    ->exists();

            if (auth()->guard('web')->check() != 0) {
                $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
                $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
                return view('client.shopdetails', ['data' => $data, 'userAddressData' => $userAddressData, 'latitude_session'=>$result_latitude, 'longitude_session'=> $result_longitude, 'FreeProductsID' => $FreeProductsID, 'attributesData' => $attributesData, 'productContent' => $productContentShort, 'metadata' => $metadata, 'wishlists' => $wishlists, 'reviews' => $reviews, 'is_purchase_product' => $is_purchase_product,  'productvideo' => $productvideo, 'isApprovedAffiliate' => $isApprovedAffiliate]);
            } else {
                return view('client.shopdetails', ['data' => $data, 'userAddressData' => $userAddressData, 'latitude_session'=>$result_latitude, 'longitude_session'=> $result_longitude, 'FreeProductsID' => $FreeProductsID, 'attributesData' => $attributesData, 'productContent' => $productContentShort, 'metadata' => $metadata, 'wishlists' => $wishlists, 'reviews' => $reviews, 'is_purchase_product' => $is_purchase_product, 'productvideo' => $productvideo, 'isApprovedAffiliate' => $isApprovedAffiliate]);
            }

        // } catch (Exception $e) {
        //     return abort(404);
        // }


    }


    public function allCategory()
    {
        Helper::countwebsite();
        $language_id = Helper::getselectedLanguage(); // get selected language Id

        $data = DB::table('category as c')->select('c.*', 'ct.id as cat_trans_id', 'ct.category_id', 'ct.language_id', 'ct.title', 'ct.description as cat_trans_description', 'ct.seo_url', 'ct.created_by')
            ->where('category_type_id', '2')->where('c.status', 'Active')->where('deleted', '0')->where('parent_id', 0)
            ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id", $language_id)->paginate(20);

        return view('client.allcategory', compact('data'));
    }


    public function productListing(Request $request, $slug="")
    {

        Helper::countwebsite();
        //try{

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

            $latitude  = "'$latitude1'";
            $longitude = "'$longitude1'";


            $language_id = Helper::getselectedLanguage();
            $allParentcategory = Helper::getAllParentProductCategory();

            $categoryRelatedData = "";
            $directory_id = Diectory::where('slug', $slug)->first();
            $id = $directory_id->id;
            $directoryData = DB::table('directory')->select('id','title','slug')->where('deleted', '0')->where('id', $id)->first();
            $directoryRelatedData = $directoryData;

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
            $categoryUrls = [];
            $subcategoryUrls = [];
            $producttagsUrls = [];
            $discountUrls = [];
            $searchstringUrls = [];


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

            //dd($producttagsUrls);


            DB::enableQueryLog();
            $productDirctoryData = DB::table('product')->select('product.*', 'pro_sub_cat.category_id',
                                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(lc_product.latitude)) * cos(radians(lc_product.longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(lc_product.latitude))) AS distance"),
                                'pro_trans.id AS product_lang_id', 'pro_trans.name AS product_lang_name', 'pro_trans.short_content');

            $productDirctoryData = $productDirctoryData->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id');
            $productDirctoryData = $productDirctoryData->leftjoin('product_translation as pro_trans', 'product.id', '=', 'pro_trans.product_id');
            $productDirctoryData = $productDirctoryData->leftjoin('product_categories as pro_sub_cat', 'product.id', '=', 'pro_sub_cat.product_id');
            if (!empty($params['price'])) {
                $productDirctoryData = $productDirctoryData->leftjoin('product_price as pro_pr', 'product.id', '=', 'pro_pr.product_id');
            }

            if (!empty($searchstringUrls)) {
                $productDirctoryData =  $productDirctoryData->where(function ($query) use ($searchstringUrls) {
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

	$productDirctoryData = $productDirctoryData->Where('pro_trans.language_id', $language_id)
            ->where(function ($query) use ($filterData, $categoryUrls, $discountUrls, $subcategoryUrls, $producttagsUrls) {
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
                if (isset($filterData['minPrice']) ?? '') {
                    if (trim(isset($filterData['minPrice']) ? $filterData['minPrice'] : 0) != "" && trim(isset($filterData['maxPrice']) ? $filterData['maxPrice'] : 0 != "")) {
                        $query->where('pro_pr.regular_price', '>=', $filterData['minPrice']);
                        $query->where('pro_pr.regular_price', '<=', $filterData['maxPrice']);
                        $query->where('pro_pr.currency_base', 'yes');
                    }
                }
            });

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


            $productDirctoryData = $productDirctoryData->where('product.directory_id', $id)
                                    ->where('product.deleted', '0')->where('product.is_free_product', 0)
                                    ->where('product.status', 'Active')->groupBy('product.id')
                                    ->orderBy('product.id', 'desc')
                                    //->orderBy('product.search_count', 'desc')
                                    //->having('distance', '<', 2000)
                                    ->paginate(52);
            $productDirctoryData->appends($request->query());

            $pDirctoryData  = DB::table('product')->select('product.*', 'pro_cat.category_id AS sub_cat_id')
                ->leftjoin('product_categories as pro_cat', 'product.id', '=', 'pro_cat.product_id')->where('product.directory_id', $id)->where('product.deleted', '0')->where('product.status', 'Active')->get();

            $recentview_products = [];
            if (auth()->check()) {
                $recentview_products = RecentView::where('user_id', auth()->id())->latest('updated_at')->with('product')->limit(7)->get()->pluck('product')->filter();
            }

            $imp_ptags = DB::table('company_imp_product_tags')
                    ->join('tag_product_translations', 'company_imp_product_tags.ptag_id', '=', 'tag_product_translations.tag_id')
                    ->where('tag_product_translations.language_id', $language_id)
                    ->where('company_imp_product_tags.company_id', $id)
                    //->orderBy('company_imp_product_tags.tags', 'ASC')
                    ->pluck('tag_product_translations.title', 'company_imp_product_tags.ptag_id')
                    ->toArray();
                //dd($data['imp_ptags']);

            $mainDirectoryDetails = Diectory::with('user')
                    ->with(['directoryLanguage' => function ($q) use ($language_id) {
                        $q->where('language_id', '=', $language_id);
                    }])->select('*')->where('id', $id)->first();

            $AddToCompare = array();
            $wishlists = array();
            if (auth()->guard('web')->check() != 0) {
                $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
                $wishlists = Wishlist::select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
                $AddToCompare = AddToCompare::with('product')->with('user')->where('user_id', $user)->orderBy('id', 'desc')->get();
                return view('client.productlisting', ['language_id' => $language_id, 'imp_ptags' => $imp_ptags, 'mainDirectoryDetails' => $mainDirectoryDetails, 'data' => $productDirctoryData, 'AddToCompare' => $AddToCompare, 'recentview_products' => $recentview_products, 'categoryUrls' => $categoryUrls,'subcategoryUrls' => $subcategoryUrls,'producttagsUrls' => $producttagsUrls, 'pDirctoryData' => $pDirctoryData, 'wishlists' => $wishlists, 'directoryRelatedData' => $directoryRelatedData, 'allParentcategory' => $allParentcategory]);
            } else {
                return view('client.productlisting', ['language_id' => $language_id, 'imp_ptags' => $imp_ptags, 'mainDirectoryDetails' => $mainDirectoryDetails,  'data' => $productDirctoryData, 'AddToCompare' => $AddToCompare, 'recentview_products' => $recentview_products, 'categoryUrls' => $categoryUrls,'subcategoryUrls' => $subcategoryUrls,'producttagsUrls' => $producttagsUrls, 'pDirctoryData' => $pDirctoryData, 'wishlists' => $wishlists, 'directoryRelatedData' => $directoryRelatedData, 'allParentcategory' => $allParentcategory]);
            }

        // } catch (Exception $e) {
        //     return abort(404);
        // }

    }

    public function shareonFacebook($id = 0)
    {
        $language_id = Helper::getselectedLanguage(); // get selected language Id
        $data['productDetails'] = Product::with('user')->with('directory')->with('useraddress')->where('id', $id)->get();

        $reviews = Review::with('user')->where('page_id', $id)->where('status', 'Active')->get();

        $productContentShort = DB::table('product_translation')
            ->leftjoin('product', 'product.id', '=', 'product_translation.product_id')
            ->select('product_translation.short_content', 'product_translation.long_content', 'product_translation.name')
            ->where('product_translation.product_id', $id)
            ->where('product_translation.language_id', $language_id)->get();

        return view('client.sharefacebook', ['data' => $data, 'productContent' => $productContentShort]);
    }
    public function shareshoppingcardonFacebook($id = 0)
    {
        $shoppingCardDetails = ShoppingCard::select("*")->where('id', $id)->get();

        return view('client.shareshoppingcardonfacebook', ['shoppingCardDetails' => $shoppingCardDetails]);
    }
    public function shareofferonFacebook($id = 0)
    {
        $offerDetails = DB::table('offer')->where('id', $id)->get();

        return view('client.shareofferonfacebook', ['offerDetails' => $offerDetails]);
    }


    public function allShoppingCard(Request $request)
    {

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

        $configData = array_filter([
            'categoryUrls' => $params['cat'] ?? null,
            'tagsUrls' => $params['tags'] ?? null,
            'searchstringUrls' => $params['search_string'] ?? null,
            'subcategoryUrls' => $params['subcat'] ?? null,
        ]);

        $filterData = $configData;

        $categoryUrls = [];
        $subcategoryUrls = [];
        $amenityUrls = [];
        $tagsUrls = [];

        if (array_key_exists('categoryUrls', $filterData)) {
            if (!empty($filterData['categoryUrls'])) {
                $categoryUrls =  array_unique($filterData['categoryUrls']);
            }
        }
        if (array_key_exists('tagsUrls', $filterData)) {
            if (!empty($filterData['tagsUrls'])) {
                $tagsUrls =  array_unique($filterData['tagsUrls']);
            }
        }
        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        if (array_key_exists('subcategoryUrls', $filterData)) {
            if (!empty($filterData['subcategoryUrls'])) {
                $subcategoryUrls =  array_unique($filterData['subcategoryUrls']);
            }
        }
        //dd($searchstringUrls);
        $distance_default = Distancesetting::where('status','Active')->where('default_distance','yes')->orderBy('id', 'asc')->first();
        Helper::countwebsite();

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

        $languageid = Helper::getselectedLanguage();

        $substringToRemove = "'";
        $latitude_session = $this->removeSubstring($latitude, $substringToRemove);
        $longitude_session= $this->removeSubstring($longitude, $substringToRemove);
        $locality = $this->get_city_lat_long($latitude_session, $longitude_session);

        $currentdate = strftime('%F');

        $shoppingcard = ShoppingCard::select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"));
        if (session('distance')) {
            $shoppingcard       =     $shoppingcard->having('distance', '<', session('distance'));
        } else {
            $shoppingcard       =     $shoppingcard->having('distance', '<', $distance_default->to_distance ?? '');
        }
        $shoppingcard           =     $shoppingcard->orderBy('distance', 'asc');



            $searchText = $request->input('search_string');
            $searchDirectoryId = $request->input('directoryId');
            $shoppingcard =  $shoppingcard->where(function ($query) use ($filterData,$categoryUrls,$subcategoryUrls,$tagsUrls,$searchText,$searchDirectoryId,$languageid) {


                if (!empty($categoryUrls) || !empty($subcategoryUrls) ) {
                    if (!empty($categoryUrls) && !empty($subcategoryUrls) ) {
                        if (!empty($categoryUrls)) {
                            $query->WhereIn('search_parent_category', $categoryUrls);
                        }
                        if (!empty($subcategoryUrls)) {
                            $query->WhereIn('search_child_category', $subcategoryUrls);
                        }
                    }
                    if (!empty($categoryUrls) && empty($subcategoryUrls) ) {
                        if (!empty($categoryUrls)) {
                            $query->WhereIn('search_parent_category', $categoryUrls);
                        }
                    }
                    if (empty($categoryUrls) && !empty($subcategoryUrls) ) {

                        if (!empty($categoryUrls)) {
                            $query->WhereIn('search_child_category', $subcategoryUrls);
                        }
                    }
                }else{

                    if($searchDirectoryId != 0){
                        $query->where('directory_link', $searchDirectoryId);
                    }
                    if(!empty($searchText)){
                        //dd($searchText);
                        $query->where('title', 'LIKE', "%{$searchText}%");
                        $query->orwhere('description', 'LIKE', "%{$searchText}%");

                        $product_tags_id = DB::table('tag_product_translations')->select('tag_product_translations.*')->where('tag_product_translations.language_id', $languageid)
                        ->where('tag_product_translations.title','like', '%' . $searchText . '%')->get();
                        if (!$product_tags_id->isEmpty()) {
                            foreach ($product_tags_id as $dirTagVal) {
                                $query->orwhereRaw('FIND_IN_SET("' . $dirTagVal->tag_id . '",lc_shopping_card.tags)');
                            }
                        }

                    }
                }

            });
        //}

        $data['shoppingcard']   =     $shoppingcard->with('image')->where('expire_date', '>=', $currentdate)->where('deleted', '0')->paginate(20);

        //$data['parent_category']  = Helper::getAllParentProductCategory();
        $data['parent_category']   = DB::table('product_category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                    ->where('feature','yes')->where('deleted','0')->where('parent_id', 0)->limit(15)->orderby('sort_value','asc')
                                    ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$languageid)->get()->toArray();

        $data['subcategories']     = DB::table('product_category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                    ->where('feature','yes')->where('deleted','0')->where('parent_id','!=',0)->orderby('id','desc')
                                    ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$languageid)->get()->toArray();

        $data['listingalltags']  = DB::table('category_tags')->select('*')->where('status','Active')->get();

        $data['distancesetting']  = Distancesetting::where('status','Active')->orderBy('id', 'asc')->get();

        return view('client.allshoppingcard',compact('data','latitude_session','longitude_session'));

    }


    public function competitions(Request $request)
    {

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

        $configData = array_filter([
            'searchstringUrls' => $params['search_string'] ?? null,
        ]);

        $filterData = $configData;

        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        $distance_default = Distancesetting::where('status','Active')->where('default_distance','yes')->orderBy('id', 'asc')->first();
        Helper::countwebsite();
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

        $substringToRemove = "'";
        $latitude_session = $this->removeSubstring($latitude, $substringToRemove);
        $longitude_session= $this->removeSubstring($longitude, $substringToRemove);
        //$locality = $this->get_city_lat_long($latitude_session, $longitude_session);
        //$currentdate = strftime('%F');
         //$languageid = Helper::getselectedLanguage();

        $vendor_competition_list = VendorCompetitionList::select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"));
        if (session('distance')) {
            $vendor_competition_list       =     $vendor_competition_list->having('distance', '<', session('distance'));
        } else {
            $vendor_competition_list       =     $vendor_competition_list->having('distance', '<', $distance_default->to_distance ?? '');
        }
        $vendor_competition_list           =     $vendor_competition_list->orderBy('distance', 'asc');

            $searchText = $request->input('search_string');
            $searchDirectoryId = $request->input('directoryId');

            $vendor_competition_list =  $vendor_competition_list->where(function ($query) use ($searchText,$searchDirectoryId) {
                    if($searchDirectoryId != 0){
                        $query->where('company_id', $searchDirectoryId);
                    }
                    if(!empty($searchText)){
                        $query->where('title', 'LIKE', "%{$searchText}%");
                    }

                    // $competitionsD = DB::table('advertisement')->select('heading','description')
                    // ->where('heading','like', '%' . $searchText . '%')
                    // ->get();
                    //     if (!$competitionsD->isEmpty()) {
                    //         foreach ($competitionsD as $compet) {
                    //             $query->orwhere('title','like', '%' . $compet->heading . '%');
                    //         }
                    //     }

            });
        //}

        $data['shoppingcard']   =     $vendor_competition_list->with('company')->with('competition')->where('status', 'Active')->paginate(20);

        //dd($data['shoppingcard']);

        return view('client.competitions',compact('data','latitude_session','longitude_session'));

    }

    public function allHotOffers(Request $request)
    {
        Helper::countwebsite();

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

        //$currentdate = strftime('%F');
        $afterThreeDaysdate = date('Y-m-d', strtotime(strftime('%F') . ' + 3 days'));

        $hotofferData = DB::table('offer')
            ->select('*', DB::raw("6371 * acos(cos(radians($latitude))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians($longitude))
                + sin(radians($latitude))
                * sin(radians(latitude))) AS distance"))
            ->where('deleted', '0')
            ->where('offer_expire_date', '>=', $afterThreeDaysdate)
            ->orderBy('distance', 'asc');

        // if ($request->input('search')) {
        //     $searchText = $request->input('searchtext');
        //     $hotofferData->where('title', 'LIKE', "%{$searchText}%");
        // }
        if ($request->input('search')) {
            $searchText = $request->input('searchtext');
            $hotofferData =  $hotofferData->where(function ($query) use ($searchText) {

                $query->where('title', 'LIKE', "%{$searchText}%");
                $query->orWhere('description', 'LIKE', "%{$searchText}%");
                $languageid = Helper::getselectedLanguage();
                $product_tags_id = DB::table('tag_product_translations')->select('tag_product_translations.*')->where('tag_product_translations.language_id', $languageid)
                    ->where('tag_product_translations.title','like', '%' . $searchText . '%')
                    ->get();
                //dd($product_tags_id);
                if (!$product_tags_id->isEmpty()) {
                    foreach ($product_tags_id as $dirTagVal) {
                        $query->orwhereRaw('FIND_IN_SET("' . $dirTagVal->tag_id . '",lc_offer.tags)');
                    }
                }
            });
        }

        $data['hotoffer'] = $hotofferData->paginate(20);

        return view('client.allhotoffer', ['data' => $data]);
    }

    public function allSpecialOffers(Request $request)
    {
        Helper::countwebsite();

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

        $currentdate = strftime('%F');
        $afterThreeDaysdate = date('Y-m-d', strtotime(strftime('%F') . ' + 3 days'));

        $hotofferData = DB::table('offer')
            ->select('*', DB::raw("6371 * acos(cos(radians($latitude))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians($longitude))
                + sin(radians($latitude))
                * sin(radians(latitude))) AS distance"))
            ->where('deleted', '0')
            ->where('offer_expire_date', '>=', $currentdate)
            ->where('offer_expire_date', '<=', $afterThreeDaysdate)
            ->orderBy('distance', 'asc');

        // if ($request->input('search')) {
        //     $searchText = $request->input('searchtext');
        //     $hotofferData->where('title', 'LIKE', "%{$searchText}%");
        // }
        if ($request->input('search')) {
            $searchText = $request->input('searchtext');
            $hotofferData =  $hotofferData->where(function ($query) use ($searchText) {

                $query->where('title', 'LIKE', "%{$searchText}%");
                $query->orWhere('description', 'LIKE', "%{$searchText}%");
                $languageid = Helper::getselectedLanguage();
                $product_tags_id = DB::table('tag_product_translations')->select('tag_product_translations.*')->where('tag_product_translations.language_id', $languageid)
                    ->where('tag_product_translations.title','like', '%' . $searchText . '%')
                    ->get();
                //dd($product_tags_id);
                if (!$product_tags_id->isEmpty()) {
                    foreach ($product_tags_id as $dirTagVal) {
                        $query->orwhereRaw('FIND_IN_SET("' . $dirTagVal->tag_id . '",lc_offer.tags)');
                    }
                }
            });
        }

        $data['hotoffer'] = $hotofferData->paginate(20);

        return view('client.all_special_offer', ['data' => $data]);
    }

    public function DirectoryHotOffers($id)
    {
        Helper::countwebsite();

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

        $currentdate = strftime('%F');
        $data['hotoffer']  = DB::table('offer')->select('*',  DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                            + sin(radians(" .$latitude. ")) * sin(radians(latitude))) AS distance"))
                            ->where('deleted', '0')
                            ->where('offer_directory_link', $id)
                            ->where('offer_expire_date', '>=', $currentdate)
                            ->orderby('distance', 'asc')
                            //->having('distance', '<', 2000)
                            ->paginate(20);
        return view('client.allhotoffer', ['data' => $data]);
    }

    public function DirectoryShoppingCard($id)
    {
        Helper::countwebsite();

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

        $currentdate            = strftime('%F');
        $shoppingcard           = ShoppingCard::select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                    * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                    + sin(radians(" .$latitude. ")) * sin(radians(latitude))) AS distance"));
        //$shoppingcard         =     $shoppingcard->having('distance', '<', 2000);
        $shoppingcard           =     $shoppingcard->orderBy('distance', 'asc');
        $data['shoppingcard']   = $shoppingcard->where('deleted', '0')->with('image')->where('directory_link', $id)->where('expire_date', '>=', $currentdate)->paginate(20);
        return view('client.allshoppingcard', ['data' => $data]);
    }

    public function hotadsdetails($link = '')
    {

        $id = DB::table('advertisement')->select('id')->where('link', $link)->first();
        $data['hotads']             = DB::table('advertisement')->select('*')->where('id', $id->id)->first();
        $data['hotadsData']         = Advertisement::with('directory')->where('id', $id->id)->get();
        $data['adevertiseData']     = DB::table('advertisement')->select('*')->where('deleted', '0')->orderby('id', 'desc')->get()->toArray();

        return view('client.hotads', ['data' => $data]);
    }

    public function getGeoLocation()
    {
        $locationdata = Location::with('user')->get();
        //echo "<pre>";
        //print_r($locationdata);
    }

    public function listingbytrademark(Request $request, $trademark = '')
    {
        Helper::countwebsite();
        $allParentcategory = Helper::getAllParentCategory();
        $language_id = Helper::getselectedLanguage(); // get selected language Id

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

        if (session('filter_city')){
            $address            = session('filter_city');
        }else{
            $address            = $geoData['geoplugin_city'];
        }
        $getLatLog = $this->get_lat_long($address);

        $latitude        =       "'$latitude1'";
        $longitude       =       "'$longitude1'";

        $substringToRemove = "'";
            $result_latitude = $this->removeSubstring($latitude, $substringToRemove);
            $result_longitude= $this->removeSubstring($longitude, $substringToRemove);

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
        if (!empty($params['amenity'])) {
            $configData['amenityUrls'] = $params['amenity'];
        }
        if (!empty($params['trademark'])) {
            $configData['trademarkUrls'] = $params['trademark'];
        }
        if (!empty($params['search_string'])) {
            $configData['searchstringUrls'] = $params['search_string'];
        }
        if (!empty($params['filters'])) {
            $configData['filtersUrls'] = $params['filters'];
        }
        if (!empty($params['subcat'])) {
            $configData['subcategoryUrls'] = $params['subcat'];
        }
        $filterData = $configData;
        $categoryUrls = [];
        $subcategoryUrls = [];
        $amenityUrls = [];
        $trademarkUrls = [];
        $filtersUrls = [];
        if (array_key_exists('categoryUrls', $filterData)) {
            if (!empty($filterData['categoryUrls'])) {
                $categoryUrls =  array_unique($filterData['categoryUrls']);
            }
        }
        if (array_key_exists('amenityUrls', $filterData)) {
            if (!empty($filterData['amenityUrls'])) {
                $amenityUrls =  array_unique($filterData['amenityUrls']);
            }
        }
        if (array_key_exists('trademarkUrls', $filterData)) {
            if (!empty($filterData['trademarkUrls'])) {
                $trademarkUrls =  array_unique($filterData['trademarkUrls']);
            }
        }
        if (array_key_exists('searchstringUrls', $filterData)) {
            if (!empty($filterData['searchstringUrls'])) {
                $searchstringUrls =  array_unique($filterData['searchstringUrls']);
            }
        }
        if (array_key_exists('filtersUrls', $filterData)) {
            if (!empty($filterData['filtersUrls'])) {
                $filtersUrls =  array_unique($filterData['filtersUrls']);
            }
        }
        if (array_key_exists('subcategoryUrls', $filterData)) {
            if (!empty($filterData['subcategoryUrls'])) {
                $subcategoryUrls =  array_unique($filterData['subcategoryUrls']);
            }
        }
        DB::enableQueryLog();
        $directoryDetailsData = DB::table('directory as dt')
            ->select(
                'dt.*',
                'dt_trans.id AS directory_lang_id',
                'dt_trans.title as dtr_title',
                'dt_trans.description as dtr_description',
                'dt_trans.seo_url',
                'ds.day',
                'ds.is_closed',
                'ds.open_time',
                'ds.close_time',
                'directory_location.latitude',
                'directory_location.longitude',
                'directory_media_trademarks_image.trademark_id',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            );
        if (!empty($searchstringUrls)) {
            //$directoryDetailsData = $directoryDetailsData->where('dt.title', 'like', '%' . $searchstringUrls[0] . '%');
            $directoryDetailsData =    $directoryDetailsData->where(function ($query) use ($searchstringUrls) {
                $dircetory_tags_id = DB::table('category_tags')->where('tags','like', '%' . $searchstringUrls[0] . '%')->get();
                $dircetory_trademark_id = DB::table('trademarks')->where('name','like', '%' . $searchstringUrls[0] . '%')->first();
                $query->where('dt.title', 'like', '%' . $searchstringUrls[0] . '%');
                if(!empty($dircetory_tags_id)){
                    foreach ($dircetory_tags_id as $dirtagval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $dirtagval->id . '",lc_directory_tags.tags_id)');
                    }
                }
                if(!empty($dircetory_trademark_id)){
                    $query->orwhereRaw('FIND_IN_SET("' . $dircetory_trademark_id->id. '",lc_directory_media_trademarks_image.trademark_id )');
                }
            });
        }
        $directoryDetailsData           = $directoryDetailsData->leftjoin('directory_basic_translation as dt_trans', 'dt.id', '=', 'dt_trans.directory_id');
        $directoryDetailsData           = $directoryDetailsData->leftjoin('directory_schedule as ds', 'dt.id', '=', 'ds.directory_id');
        $directoryDetailsData           = $directoryDetailsData->leftjoin('directory_tags', 'dt.id', '=', 'directory_tags.directory_id');
        $directoryDetailsData           = $directoryDetailsData->leftjoin('directory_media_trademarks_image', 'dt.id', '=', 'directory_media_trademarks_image.directory_id');
        $directoryDetailsData           = $directoryDetailsData->leftjoin('directory_location', 'dt.id', '=', 'directory_location.directory_id');
        if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id');
        }
        if ((!empty($subcategoryUrls)) && $subcategoryUrls[0] != 'all') {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_sub_category as dt_sub_cat', 'dt.id', '=', 'dt_sub_cat.directory_id');
        }
        if (!empty($filtersUrls) && $filtersUrls[0] == 'latestcompany') {
            $directoryDetailsData = $directoryDetailsData->orderBy('dt.created_at', 'desc');
        }
        if (!empty($filtersUrls) && $filtersUrls[0] == 'mostpopular') {
            $directoryDetailsData = $directoryDetailsData->where('dt.popular', '1');
        }
        if (!empty($filtersUrls) && $filtersUrls[0] == 'relevant-listings') {
            $directoryDetailsData = $directoryDetailsData->rightjoin('plan_subscriptions as plan_s', 'dt.id', '=', 'plan_s.subscriber_id');
            $directoryDetailsData = $directoryDetailsData->rightjoin('plans', 'plan_s.plan_id', '=', 'plans.id');
            $directoryDetailsData = $directoryDetailsData->rightjoin('viewed_listings_counters', 'plan_s.subscriber_id', '=', 'viewed_listings_counters.directory_id');
        }
        if (!empty($amenityUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_amenities as dt_ame', 'dt.id', '=', 'dt_ame.directory_id');
        }
        if (!empty($trademarkUrls)) {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_media_trademarks_image as dt_trad', 'dt.id', '=', 'dt_trad.directory_id');
        }
        $current_day = date('l', strtotime(date("y-m-d")));
        $directoryDetailsData = $directoryDetailsData->where(function ($query) use ($filterData, $categoryUrls,$subcategoryUrls, $filtersUrls, $amenityUrls, $current_day, $trademarkUrls) {
                if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
                    $query->WhereIn('dt_cat.category_id', $categoryUrls);
                }
                if (!empty($categoryUrls) && (!empty($subcategoryUrls))) {
                    //$query->orWhereIn('dt_cat.category_id', $categoryUrls);
                    $query->orWhereIn('dt_sub_cat.sub_category_id', $subcategoryUrls);
                }
                if (!empty($subcategoryUrls) && $subcategoryUrls[0] != 'all') {
                    $query->WhereIn('dt_sub_cat.sub_category_id', $subcategoryUrls);
                }
                if (!empty($amenityUrls)) {
                    foreach ($amenityUrls as $ameval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $ameval . '",lc_dt_ame.amenities_id)');
                    }
                }
                if (!empty($trademarkUrls)) {
                    foreach ($trademarkUrls as $tradval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $tradval . '",lc_dt_trad.trademark_id )');
                    }
                }
                $query->Where('ds.day', strtolower($current_day));
            });
        $directoryDetailsData = $directoryDetailsData->where('dt.status', 'Active')
                            ->where('directory_media_trademarks_image.trademark_id', $trademark)
                            ->groupBy('dt.id')
                            ->orderBy('distance', 'asc')
                            //->having('distance', '<', 2000)
                            ->paginate(10);

        $most_viewed_listings = ViewedListingsCounter::select(
            'directory.*',
            'directory_location.latitude',
            'directory_location.longitude',
            DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            'viewed_listings_counters.visiters',
            'viewed_listings_counters.directory_id',
        )
            ->selectRaw("COUNT(lc_viewed_listings_counters.directory_id) as occurence, lc_viewed_listings_counters.directory_id")
            ->join('directory', 'directory.id', '=', 'viewed_listings_counters.directory_id')
            ->join('directory_location', 'directory.id', '=', 'directory_location.directory_id')
            ->groupBy("viewed_listings_counters.directory_id")
            // ->orderBy("occurence", "desc")
            ->orderby('distance', 'asc')
            //->having('distance', '<', 2000)
            ->get()->take(10);

		$pDirectorySubCatData  = DB::table('directory')->select('directory.*', 'diry_cat.category_id AS dir_cat_id', 'diry_tags.*')
            ->leftjoin('directory_category as diry_cat', 'directory.id', '=', 'diry_cat.directory_id')
            ->leftjoin('directory_tags as diry_tags', 'directory.id', '=', 'diry_tags.directory_id')
            ->where('directory.deleted', '0')->where('directory.status', 'Active')->get();

        $distancesetting = Distancesetting::where('status','Active')->orderBy('id', 'asc')->get();

        $wishlists = array();
        $wislistdirectoryId = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = DB::table('wislist_directory')->select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            $wislistdirectoryId=array();
            foreach($wishlists as $wishid){
                $wislistdirectoryId[]=$wishid->directory_id;
            }
            return view('client.listings', ['directoryDetails' => $directoryDetailsData, 'wislistdirectoryId' => $wislistdirectoryId, 'language_id' => $language_id, 'distancesetting' => $distancesetting, 'latitude_session'=>$result_latitude, 'longitude_session'=> $result_longitude, 'most_viewed_listings' => $most_viewed_listings, 'wishlists' => $wishlists, 'allParentcategory' => $allParentcategory, 'getLatLog'=>$getLatLog, 'pDirectorySubCatData' => $pDirectorySubCatData]);
        } else {
            return view('client.listings', ['directoryDetails' => $directoryDetailsData, 'wislistdirectoryId' => $wislistdirectoryId, 'language_id' => $language_id, 'distancesetting' => $distancesetting, 'latitude_session'=>$result_latitude, 'longitude_session'=> $result_longitude, 'most_viewed_listings' => $most_viewed_listings, 'wishlists' => $wishlists, 'allParentcategory' => $allParentcategory, 'getLatLog'=>$getLatLog, 'pDirectorySubCatData' => $pDirectorySubCatData]);
        }
    }


}
