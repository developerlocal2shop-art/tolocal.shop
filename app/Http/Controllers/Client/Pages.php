<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Response;
use Illuminate\Http\Request;
use App\Models\Diectory;
use App\Helpers\Helper;
use App;
use App\Models\Advertisement;
use App\Models\City;
use App\Models\Currency;
use App\Models\Country;
use App\Models\DirectoryMediaTrademarks;
use App\Models\Distancesetting;
use App\Models\HotAds;
use App\Models\ProductTranslation;
use App\Models\ProductPrice;
use App\Models\ShoppingCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use Stripe\Stripe;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Trademarks;
use Illuminate\Support\Facades\Mail;
use Exception;


class Pages extends Controller
{

    public  $params = ['cat' => [], 'subcat' => [], 'amenity' => [], 'trademark' => [], 'tags' => [], 'filters' => [], 'search_string' => []];
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

    public function index(Request $request)
    {

        $countSiteOpens = 0;
        if (Helper::cookieConsentGranted('analytics')) {
            Cookie::queue('countSiteOpens', $countSiteOpens, 60);
        }
        $userIP = getenv('REMOTE_ADDR');
        $counrtyDetails = Helper::userCurrentLocationDetailsOnIpAddress($userIP);
        $today = date('Y-m-d H:i:s');
        Helper::visitors('website',0);

        // Default coordinates (fallback values)
        $defaultLatitude  = '57.10621635';
        $defaultLongitude = '12.25154933';

        if (!empty($counrtyDetails)) {
            if ($userIP == '127.0.0.1' || $userIP == '::1' || $userIP == 'localhost') {
                $countryCode = 'IN';
                $timezone = 'Asia/Kolkata';
            } else {
                try {
                    $countryCode = $counrtyDetails['countryCode'] ?? 'IN';
                    $timezone    = $counrtyDetails['timezone'] ?? 'Asia/Kolkata';
                } catch (Exception $e) {
                    $timezone = 'Europe/Stockholm';
                    $countryCode = 'SE'; // FIXED (was wrong: 46)
                }
            }
            Session::put('countrycode', $countryCode);
            $countryNameCode = Country::where('code', $countryCode)->first();
            Session::put('countrynamecode', $countryNameCode);
        }

        $latitude  = session('latitude');
        $longitude = session('logitude');
        $city_name = $request->get('city');

        if ($city_name && ($city_latlong = $this->getLatLongByCity($city_name))) {
            $latitude  = $city_latlong['latitude'];
            $longitude = $city_latlong['longitude'];
            session([
                'url_city_name' => $city_name,
                'filter_city'   => $city_name,
                'url_latitude'  => $latitude,
                'url_longitude' => $longitude,
            ]);
        } elseif ($request->ajax()) {
            $latitude  = $request->lati;
            $longitude = $request->long;
            session([
                'latitude' => $latitude,
                'logitude' => $longitude,
            ]);
        } elseif (session()->has('latitude_city')) {
            $latitude  = session('latitude_city');
            $longitude = session('logitude_city');
        }else{
            $latitude  = $defaultLatitude;
            $longitude = $defaultLongitude;
        }

        $latitude = trim((string) $latitude, "'\" ");
        $longitude = trim((string) $longitude, "'\" ");

        $latitude = is_numeric($latitude) ? (float) $latitude : (float) $defaultLatitude;
        $longitude = is_numeric($longitude) ? (float) $longitude : (float) $defaultLongitude;

        //dd($latitude,$longitude);

        $substringToRemove = "'";
            $result_latitude = $this->removeSubstring($latitude, $substringToRemove);
            $result_longitude= $this->removeSubstring($longitude, $substringToRemove);
            $locality = $this->get_city_lat_long($result_latitude, $result_longitude);

        if (url()->previous() != $request->url()) {
            if ($locality) { //$request->session()->put('filter_city', $locality);
            }
        } else {
            $user_ip = getenv('REMOTE_ADDR');
            if ($user_ip != '127.0.0.1' || $user_ip != '::1' || $user_ip != 'localhost') {
                $currentlocality    = $this->get_city_lat_long($counrtyDetails['geoplugin_latitude'] ?? '57.12134020', $counrtyDetails['geoplugin_longitude'] ?? '12.25968470');
            }
        }

        $currentdate = strftime('%F');
        $afterThreeDaysdate = date('Y-m-d', strtotime(strftime('%F') . ' + 3 days'));
        $language_id = Helper::getselectedLanguage();

        $data['categoryData']   = DB::table('category as c')->select('c.*', 'ct.id as cat_trans_id', 'ct.category_id', 'ct.language_id', 'ct.title', 'ct.description as cat_trans_description', 'ct.seo_url', 'ct.created_by')
            ->where('category_type_id', '2')->where('feature', 'yes')->where('c.status', 'Active')->where('deleted', '0')->where('parent_id', 0)->limit(8)->orderby('sort_value', 'asc')
            ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id", $language_id)->get()->toArray();

        $data['categoryTotalData']   = DB::table('category as c')->select('c.*', 'ct.id as cat_trans_id', 'ct.category_id', 'ct.language_id', 'ct.title', 'ct.description as cat_trans_description', 'ct.seo_url', 'ct.created_by')
            ->where('category_type_id', '2')->where('feature', 'yes')->where('c.status', 'Active')->where('deleted', '0')->where('parent_id', 0)
            ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id", $language_id)->get();
        $direCount = 0;
        foreach($data['categoryTotalData'] as $key => $row)  {
            $directoryCount = Helper::getCountRelatedDirectory($row->id, $latitude, $longitude);
            if($directoryCount > 0){
                $direCount ++;
            }
        }
        $distance_default = Distancesetting::where('status', 'Active')->where('default_distance', 'yes')->orderBy('id', 'asc')->first();

        $directoryData = DB::table('directory as d')
            ->select(
                'd.*',
                'dt_trans.id AS directory_lang_id',
                'dt_trans.title as dtr_title',
                'dt_trans.description as dtr_description',
                'directory_location.latitude',
                'directory_location.longitude',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                        * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                        + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            )
            ->where('deleted', '0')
            ->leftjoin('directory_location', 'd.id', '=', 'directory_location.directory_id')
            ->where('d.status', 'Active')
            ->groupBy('d.id')
            ->orderBy('distance', 'asc');
            $directoryData = $directoryData->Where('dt_trans.language_id',  $language_id);
        if (session('distance')) {
            $directoryData = $directoryData->having('distance', '<', session('distance'));
        } else {
            $directoryData = $directoryData->having('distance', '<', $distance_default->to_distance ?? '');
        }
            $directoryData = $directoryData->leftjoin('directory_basic_translation as dt_trans', 'd.id', '=', 'dt_trans.directory_id');
        $data['directoryData'] = $directoryData->get()->take(20);

        $data['countDirectoryData'] = $directoryData->count();

        $data['splofferData']  = DB::table('offer')->select('*')->where('deleted', '0')->where('offer_expire_date', '>=', $currentdate)->where('offer_expire_date', '<=', $afterThreeDaysdate)->orderby('id', 'desc')->count();

        $shoppingcardData   = DB::table('offer')->select('*', DB::raw("6371 * acos(cos(radians(" . $latitude . "))
            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"))
            ->where('deleted', '0')
            ->where('offer_expire_date', '>', $afterThreeDaysdate)
            ->limit(9)->orderby('distance', 'asc');
        if (session('distance')) {
            $shoppingcardData       =    $shoppingcardData->having('distance', '<', session('distance'));
        } else {
            $shoppingcardData       =    $shoppingcardData->having('distance', '<', $distance_default->to_distance ?? '');
        }
        $data['shoppingcardData']   =    $shoppingcardData->count();

        $data['blogData'] = DB::table('blog as b')
            ->select(
                'b.*',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'plan_subsc.slug as plan_slug'
            )
            ->leftJoin('plan_subscriptions as plan_subsc', 'b.directory_id', '=', 'plan_subsc.subscriber_id')
            ->where('b.deleted', '0')
            ->where('b.status', 'Active')
            ->limit(9)
            ->orderByRaw("
                FIELD(plan_slug, 'premium', 'base', 'business', 'free') DESC,
                plan_slug ASC
            ")
            ->get()->toArray();

        $eventsData = DB::table('events as e')
            ->select(
                'e.*',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'plan_subsc.slug as plan_slug'
            )
            ->leftJoin('plan_subscriptions as plan_subsc', 'e.directory_id', '=', 'plan_subsc.subscriber_id')
            ->where('e.deleted', '0')
            ->where('status', 'Active')  // Status Active only for selected plans
            ->limit(9)
            ->orderByRaw("FIELD(plan_slug, 'premium', 'base', 'business', 'free') asc")
            ->where('e.end_date', '>=', $today);
        $eventsData2 = $eventsData->count();
        $data['eventsData'] = $eventsData2;

        $hotAdsQuery = DB::table('hot_ads as b')
            ->join('hot_ad_translations as bt', 'b.id', '=', 'bt.hotad_id')
            ->where('b.deleted', '0')
            ->where('b.status', 'Active')
            ->whereDate('b.end_date', '>=', now()->toDateString())
            ->where('bt.language_id', $language_id);

        $data['hotAds'] = (clone $hotAdsQuery)
            ->select(
                'b.id',
                'b.display_home',
                'b.image_name',
                'b.deleted',
                'b.status',
                'b.start_date',
                'b.end_date',
                'b.link',
                'bt.language_id',
                'bt.title',
                'b.short_video',
                'b.mp3_file',
                'b.video_display_on_home',
                'bt.description'
            )
            ->where('b.display_home', '1')
            ->orderByDesc('b.id')
            ->limit(6)
            ->get();

        $data['hotAdsCount'] = (clone $hotAdsQuery)->count();

        $data['today_competitions'] = Advertisement::where('end_date', '>=', $today)->whereHas('questions')
                                      ->with(['questions', 'competitionVendors'])->count();

        $shoppingcard = ShoppingCard::select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"));
        if (session('distance')) {
            $shoppingcard       =     $shoppingcard->having('distance', '<', session('distance'));
        } else {
            $shoppingcard       =     $shoppingcard->having('distance', '<', $distance_default->to_distance ?? '');
        }
        $shoppingcard           =     $shoppingcard->orderBy('distance', 'asc');
        $data['shoppingcard']   =     $shoppingcard->with('image')->where('expire_date', '>=', $currentdate)->where('deleted', '0')->limit(8)->get();

        $shoppingcard_count = ShoppingCard::select("*", DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"));
        if (session('distance')) {
            $shoppingcard_count       =     $shoppingcard_count->having('distance', '<', session('distance'));
        } else {
            $shoppingcard_count       =     $shoppingcard_count->having('distance', '<', $distance_default->to_distance ?? '');
        }
        $shoppingcard_count           =     $shoppingcard_count->orderBy('distance', 'asc');
        $data['shoppingcard_count']   =     $shoppingcard_count->with('image')->where('expire_date', '>=', $currentdate)->where('deleted', '0')->count();

        $metatitle = 'Find your local places, you love most to roam around';

        return view('client.home', compact('data','metatitle','latitude','longitude','direCount') );

    }

    public function hotaddslist(Request $request)
    {
        $language_id = Helper::getselectedLanguage();

        $hotAdsQuery = DB::table('hot_ads as b')
            ->join('hot_ad_translations as bt', 'b.id', '=', 'bt.hotad_id')
            ->where('b.deleted', '0')
            ->where('b.status', 'Active')
            ->whereDate('b.end_date', '>=', now()->toDateString())
            ->where('bt.language_id', $language_id);

        $data['HotAdsData'] = (clone $hotAdsQuery)
            ->select(
                'b.id',
                'b.display_home',
                'b.company_id',
                'b.image_name',
                'b.deleted',
                'b.status',
                'b.start_date',
                'b.end_date',
                'b.link',
                'bt.language_id',
                'bt.title',
                'bt.description'
            )
            ->orderByDesc('b.id')
            ->paginate(21);

        return view('client.hotads_list', compact('data'));
    }

    // public function hotaddslist(Request $request)
    // {
    //     $language_id = Helper::getselectedLanguage();
    //     $data['HotAdsData'] = DB::table('hot_ads as b')
    //         ->select('b.id','b.display_home','b.company_id','b.image_name','b.deleted','b.status','b.start_date','b.end_date','b.link', 'bt.language_id', 'bt.title', 'bt.description')
    //         ->join('hot_ad_translations as bt', 'b.id', '=', 'bt.hotad_id')
    //         ->where('deleted', '0')
    //         ->whereDate('end_date', '>=', now()->toDateString())
    //         ->where('status', 'Active')
    //         ->where('bt.language_id', $language_id)
    //         ->paginate(21);

    //     return view('client.hotads_list', ['data' => $data]);
    // }

    public function hotaddetails($id = "")
    {
        $language_id = Helper::getselectedLanguage();
        $hotadData = DB::table('hot_ads as b')
            ->select('b.id','b.display_home','b.short_video','b.mp3_file','b.company_id','b.image_name','b.deleted','b.status','b.start_date','b.end_date','b.link', 'bt.language_id', 'bt.title', 'bt.description')
            ->join('hot_ad_translations as bt', 'b.id', '=', 'bt.hotad_id')
            ->where('deleted', '0')
            ->where('status', 'Active')
            ->where('bt.language_id', $language_id)
            ->where('b.id', $id)
            ->first();

        return view('client.hotad_details', ['data' => $hotadData]);
    }

    public function competitiondetails($id = "")
    {
        $competitionData  = Advertisement::where('deleted', '0')->find($id);
        return view('client.competition_details', ['data' => $competitionData]);
    }

    public function competitionlist(Request $request)
    {
        $data['competitionData'] = DB::table('advertisement')
            ->whereDate('end_date', '>=', now()->toDateString())
            ->where('deleted', '0')
            ->orderBy('id', 'desc')
            ->paginate(21);
        return view('client.competition_list', ['data' => $data]);
    }


    function removeSubstring($originalString, $substringToRemove) {
        $resultString = str_replace($substringToRemove, '', $originalString);
        return $resultString;
    }

    public function getCities(Request $request)
    {
        if (!$request['search'] == '') {

            if (session('latitude') == null) {
                $userIP = getenv('REMOTE_ADDR');
                $counrtyDetails = Helper::userCurrentLocationDetailsOnIpAddress($userIP);
                if (!empty($counrtyDetails)) {
                    if ($userIP == '127.0.0.1' || $userIP == 'localhost') {
                        $latitude1 = '28.6542';
                        $longitude1 = '77.2373';
                    } else {
                        $latitude1       =       $counrtyDetails['lat'];
                        $longitude1      =       $counrtyDetails['lon'];
                    }
                }
            } else {
                $latitude1       =       session('latitude');
                $longitude1      =       session('logitude');
            }
            $latitude        =       $latitude1;
            $longitude       =       $longitude1;

            $locality = $this->get_city_lat_long($latitude, $longitude);

            $data = City::select('id', 'name')->where('name', 'LIKE', $request['search'] . '%')->groupBy('name')->orderBy('id', 'desc')->get();
            $response = array();
            foreach ($data as $key => $val) {
                if ($key == 0) {
                    $response[] = array(
                        'id' =>  $locality,
                        'text' => 'Current location'
                    );
                }
                if ($key == 0) {
                    $response[] = array(
                        'id' =>  'globally',
                        'text' => 'All globally'
                    );
                }
                $response[] = array(
                    'id' =>  $val->name,
                    'text' => $val->name
                );
            }
            return response()->json($response);
        }
    }

    public function getcitybyid(Request $request)
    {
        $data = City::where('id', $request['city_id'])->first();
        return response()->json($data);
    }

    public function getcitybyname(Request $request)
    {
        $data = City::where('name', $request['city_name'])->first();
        return response()->json($data);
    }

    public function setCitySession(Request $request)
    {
        $city_name = $request->input('city_name');
        $getCountryByCityName = Helper::getCountryByCityName($city_name);

        $getLatLog = $this->get_lat_long($city_name);
        if ($getLatLog) {
            $latitude       =       $getLatLog[0];
            $longitude      =       $getLatLog[1];
        }
        $request->session()->forget('latitude_city');
        $request->session()->forget('logitude_city');
        $request->session()->forget('url_latitude');
        $request->session()->forget('url_longitude');
        $request->session()->forget('filter_city');
        $request->session()->forget('getcountryname');
        $request->session()->forget('url_city_name');

        $request->session()->put('url_city_name', $city_name);
        $request->session()->put('latitude_city', $latitude);
        $request->session()->put('logitude_city', $longitude);
        $request->session()->put('url_latitude', $latitude);
        $request->session()->put('url_longitude', $longitude);
        $request->session()->put('filter_city', $city_name);
        $request->session()->put('getcountryname', $getCountryByCityName);

        return response()->json($city_name);
    }


    function get_lat_long($address)
    {
        try {
            $address = str_replace(" ", "+", $address);
            $json = file_get_contents("https://maps.google.com/maps/api/geocode/json?key=AIzaSyBxzKYI3QrSnkPY08S8-KpvlI5QqjOLNhU&address=$address&sensor=false&region=$address");
            $json = json_decode($json);
            $lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
            $long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
            if ($lat) {
                return array($lat, $long);
            } else {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
    }


    function get_city_lat_long($latitude, $longitude)
    {
        try {
            $apiKey = env('GOOGLE_MAP_API_KEY');
            $geocode = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?key={$apiKey}&latlng=$latitude,$longitude&sensor=false");

            $geocode = json_decode($geocode);
            $add_array  = $geocode->results;
            $add_array = $add_array[0];
            $add_array = $add_array->address_components;

            $country = "Not found";
            $state   = "Not found";
            $city    = "Not found";

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

    function getLatLongByCity($city)
    {
        try {
            $city = urlencode($city);
            $apiKey = env('GOOGLE_MAP_API_KEY');
            $url = "https://maps.googleapis.com/maps/api/geocode/json?address={$city}&key={$apiKey}";
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            if ($data['status'] == 'OK') {
                $location = $data['results'][0]['geometry']['location'];
                return [
                    'latitude'  => $location['lat'],
                    'longitude' => $location['lng']
                ];
            }
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function category($categoryName = "")
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
        if (!empty($params['cat'])) {
            $configData['categoryUrls'] = $params['cat'];
        }
        if (!empty($params['subcat'])) {
            $configData['subcategoryUrls'] = $params['subcat'];
        }
        if (!empty($params['tags'])) {
            $configData['tagsUrls'] = $params['tags'];
        }
        if (!empty($params['amenity'])) {
            $configData['amenityUrls'] = $params['amenity'];
        }
        if (!empty($params['trademark'])) {
            $configData['trademarkUrls'] = $params['trademark'];
        }
        if (!empty($params['filters'])) {
            $configData['filtersUrls'] = $params['filters'];
        }
        if (!empty($params['search_string'])) {
            $configData['searchstringUrls'] = $params['search_string'];
        }

        $filterData = $configData;
        $categoryUrls = [];
        $subcategoryUrls = [];
        $tagsUrls = [];
        $amenityUrls = [];
        $filtersUrls = [];
        $trademarkUrls = [];

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
        if (array_key_exists('tagsUrls', $filterData)) {
            if (!empty($filterData['tagsUrls'])) {
                $tagsUrls =  array_unique($filterData['tagsUrls']);
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

        if (session('filter_city')) {
            $address             = session('filter_city');
        } else {
            $user_ip = getenv('REMOTE_ADDR');
            $counrtyDetails = Helper::userCurrentLocationDetailsOnIpAddress($user_ip);
            if (!empty($counrtyDetails)) {
                $address             = $counrtyDetails['city'];
            }
        }
        $getLatLog = $this->get_lat_long($address);

        $language_id = Helper::getselectedLanguage();
        $catid = DB::table('category')->select('*')->where('deleted', '0')->where('slug', $categoryName)->first();
        //dd($catid);
        $current_day = date('l', strtotime(date("y-m-d")));
        $directoryDetailsData = DB::table('directory as dt')
            ->select(
                'dt.*',
                'dt_cat.category_id',
                'd_tr.id as di_transId',
                'd_tr.title as dtr_title',
                'd_tr.description as dtr_description',
                'd_tr.seo_url',
                'ds.day',
                'ds.is_closed',
                'ds.open_time',
                'ds.close_time',
                'directory_location.latitude',
                'directory_location.longitude',
                'directory_tags.tags_id',
                'directory_media_trademarks_image.trademark_id',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance")
            )
            ->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id')->Where('dt_cat.category_id',  $catid->id ?? '')
            ->leftjoin('directory_basic_translation as d_tr', 'dt.id', '=', 'd_tr.directory_id')->where("d_tr.language_id", $language_id)
            ->leftjoin('directory_location', 'dt.id', '=', 'directory_location.directory_id')
            ->leftjoin('directory_tags', 'dt.id', '=', 'directory_tags.directory_id')
            ->leftjoin('directory_media_trademarks_image', 'dt.id', '=', 'directory_media_trademarks_image.directory_id')
            ->join('directory_schedule as ds', 'dt.id', '=', 'ds.directory_id');

        if (!empty($categoryUrls) && $categoryUrls[0] != 'all') {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id');
        }
        if ((!empty($subcategoryUrls)) && $subcategoryUrls[0] != 'all') {
            $directoryDetailsData = $directoryDetailsData->leftjoin('directory_sub_category as dt_sub_cat', 'dt.id', '=', 'dt_sub_cat.directory_id');
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

        $directoryDetailsData = $directoryDetailsData
            ->where(function ($query) use ($filterData, $categoryUrls, $subcategoryUrls, $amenityUrls, $current_day, $filtersUrls, $trademarkUrls, $tagsUrls) {
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
                if (!empty($tagsUrls)) {
                    foreach ($tagsUrls as $tagsval) {
                        $query->orwhereRaw('FIND_IN_SET("' . $tagsval . '",lc_dt_tag.tags_id )');
                    }
                }
            });

        $data['directoryDetails'] =    $directoryDetailsData->Where('ds.day', strtolower($current_day))->groupBy('dt.id')->orderBy('distance', 'asc');
        $directoryDetails =    $data['directoryDetails']->paginate(20);

        $pDirectorySubCatData  = DB::table('directory')->select('directory.*', 'diry_cat.category_id AS dir_cat_id', 'diry_tags.*')
            ->leftjoin('directory_category as diry_cat', 'directory.id', '=', 'diry_cat.directory_id')
            ->leftjoin('directory_tags as diry_tags', 'directory.id', '=', 'diry_tags.directory_id')
            ->where('directory.deleted', '0')->where('directory.status', 'Active')->get();

        $wishlists = array();
        if (auth()->guard('web')->check() != 0) {
            $user = isset(auth()->guard('web')->user()->id) ? auth()->guard('web')->user()->id : 0;
            $wishlists = DB::table('wislist_directory')->select("*")->where("user_id", $user)->orderby('id', 'desc')->get()->toArray();
            return view('client.category', ['directoryDetails' => $directoryDetails, 'wishlists' => $wishlists, 'getLatLog' => $getLatLog, 'categoryName' => $catid->name, 'pDirectorySubCatData' => $pDirectorySubCatData, 'categoryId' => $catid->id,]);
        } else {
            return view('client.category', ['directoryDetails' => $directoryDetails, 'wishlists' => $wishlists, 'getLatLog' => $getLatLog, 'categoryName' => $catid->name, 'pDirectorySubCatData' => $pDirectorySubCatData, 'categoryId' => $catid->id,]);
        }
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        $filterResult = Diectory::select("title")->where('title', 'LIKE', '%' . $query . '%')->get();
        $data = array();
        foreach ($filterResult as $hsl) {
            $data[] = $hsl->title;
        }
        return response()->json($data);
    }

    public function changeLang($langcode)
    {
        App::setLocale($langcode);
        session()->put("lang_code", $langcode);
        session()->put("is_lang", true);
        $language_id = Helper::getselectedLanguage(); // get selected language Id
        $cart = session()->get('cart', []);
        if (!empty($cart)) {
            foreach ($cart as $key => $value) {
                $product = ProductTranslation::where('product_id', $key)->where('language_id', $language_id)->first();
                $cart[$key]["name"] = $product->name;
                session()->put('cart', $cart);
            }
        }
        return redirect()->back();
    }

    public function changeCurrency($currencycode)
    {
        $getCurrency = Currency::select('*')->where('code', $currencycode)->first();
        session()->put("currency_code", ['currcode' => $currencycode, 'currsymbol' => $getCurrency->symbol]);
        $cart = session()->get('cart', []);
        if (!empty($cart)) {
            foreach ($cart as $key => $value) {
                $prodctPrice = ProductPrice::where('product_id', $key)->where('currency_code', $currencycode)->first();
                $cart[$key]["regularprice"] = $prodctPrice->regular_price;
                $cart[$key]["price"] = $prodctPrice->regular_price;
                session()->put('cart', $cart);
            }
        }
        return redirect()->back();
    }

    public function autocompleteSearch(Request $request)
    {
        $query            = $request->get('query');
        $typedata         = $request->get('type');
        $data = array();
        $languageid = Helper::getselectedLanguage();

        if ($typedata == "listings") {

            // First, fetch from Directory table with groupBy
            $directoryResults = DB::table('directory')
                ->select('title')
                ->where('title', 'LIKE', '%' . $query . '%')
                ->where('deleted', '0')
                ->where('status', 'Active')
                ->groupBy('title');

            $categoryTagResults = DB::table('category_tags')
                ->join('tag_translations', 'category_tags.id', '=', 'tag_translations.tag_id')
                ->select('tag_translations.title as title')
                ->where('tag_translations.language_id', $languageid)
                ->where('tag_translations.status', 1)
                ->where('tag_translations.title', 'LIKE', $query . '%')
                ->groupBy('tag_translations.title');

            $trademarksResults = DB::table('trademarks')->where('trademarks.name', 'LIKE', '%' . $query . '%')
                ->join('directory_media_trademarks_image', 'trademarks.id', '=', 'directory_media_trademarks_image.trademark_id')
                ->select('trademarks.name as title')
                ->groupBy('trademarks.name');

            $competitionResults = DB::table('advertisement')->where('advertisement.heading', 'LIKE', '%' . $query . '%')
                ->join('advertisement_questions', 'advertisement.id', '=', 'advertisement_questions.advertisement_id')
                ->select('advertisement.heading as title')
                ->groupBy('advertisement.heading');

            $directoryLocationResults = DB::table('directory_location')
                ->selectRaw("
                    SUBSTRING_INDEX(address, ' ', 1) AS title
                ")
                ->whereRaw("
                    SUBSTRING_INDEX(address, ' ', 1) LIKE ?
                ", [trim($query) . '%'])
                ->groupBy(DB::raw("SUBSTRING_INDEX(address, ' ', 1)"));

            // Combine three queries using unionAll and distinct
            $filterResult = $directoryResults
            ->union($directoryLocationResults)
            ->union($categoryTagResults)
            ->union($trademarksResults)
            ->union($competitionResults)
            ->distinct()
            ->take(7)
            ->get();

            // Extract titles into an array
            $data = [];
            foreach ($filterResult as $hsl) {
            $data[] = $hsl->title;
            }
        }
        if ($typedata == "product") {

            $filterResult = Product::select('name')
                ->where('name', 'LIKE', $query . '%')
                ->unionAll(
                    DB::table('product_tags')
                        ->join('tag_product_translations', 'product_tags.id', '=', 'tag_product_translations.tag_id')
                        ->select('tag_product_translations.title') // Adjust to the actual column names you need
                        ->where('tag_product_translations.language_id', $languageid)
                        ->where('tag_product_translations.title', 'LIKE', $query . '%')
                )
                ->take(6)
                ->get();
            foreach ($filterResult as $hsl) {
                $data[] = $hsl->name;
            }
        }
        return response()->json($data);
    }

}
