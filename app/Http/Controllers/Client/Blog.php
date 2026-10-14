<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Review;
use App\Helpers\Helper;
use App\Models\Advertisement;
use App\Models\CustomerClubs;
use App\Models\Frontendsetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;

class Blog extends Controller
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
    public function index(Request $request)
    {

        Helper::countwebsite();
        $language_id = 1;

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

        if (session()->has('lang_code')) {
            $getLanguageId = DB::table('languages')->select('id', 'name', 'code')->where('status', 'Active')
                ->where('deleted', '0')->where('code', session()->get('lang_code'))->first();
            $language_id = $getLanguageId->id;
        }

        $data['blogDataLatest'] = DB::table('blog as b')->select('b.*', DB::raw("6371 * acos(cos(radians(" . $latitude . "))
            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            'bt.title as bt_title', 'bt.blog_id', 'bt.language_id', 'bt.description', 'bt.seo_url', 'c.name as category_name', 'c.slug as category_slug')
            ->where('b.deleted', '0')->limit(9)
            ->orderby('distance', 'asc')
            ->leftjoin('category as c', 'c.id', '=', 'b.category_id')
            ->leftjoin('blog_translation as bt', 'bt.blog_id', '=', 'b.id')
            ->where('b.status', 'Active')->where("bt.language_id", $language_id)->get()->toArray();

        $searchQuery = $request->input('searchtext');
        $searchtext = isset($searchQuery) ? $searchQuery : "";
        if ($request->input('search')) {
            $datablogData = DB::table('blog as b')
                ->select('b.*',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'users.name','users.last_name', 'bt.title as bt_title', 'bt.blog_id', 'bt.language_id', 'bt.description', 'bt.seo_url', 'c.name as category_name')
                ->where('b.deleted', '0')->where('b.status', 'Active')->orderby('distance', 'asc');

            $datablogData = $datablogData->where(function ($query) use ($searchtext) {
                $query->where('bt.title', 'like', '%' . $searchtext . '%');
                $query->orWhere('bt.description', 'like', '%' . $searchtext . '%');
            });
            $datablogData = $datablogData->leftjoin('category as c', 'c.id', '=', 'b.category_id');
            $datablogData = $datablogData->leftjoin('users', 'users.id', '=', 'b.created_by')->where('b.status', 'Active');

            $data['blogData'] = $datablogData->leftjoin('blog_translation as bt', 'bt.blog_id', '=', 'b.id')
            ->where("bt.language_id", $language_id)->paginate(6);
            $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();
            return view('client.blog', ['data' => $data, 'blogsearch' => $searchtext]);
        } else {
            $data['blogData'] = DB::table('blog as b')->select('b.*',
                    DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                    * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                    + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                    'users.name','users.last_name', 'bt.title as bt_title', 'bt.blog_id', 'bt.language_id', 'bt.description', 'bt.seo_url', 'c.name as category_name')
                    ->where('b.deleted', '0')->limit(9)->orderby('distance', 'asc')
                    ->leftjoin('category as c', 'c.id', '=', 'b.category_id')
                    ->leftjoin('users', 'users.id', '=', 'b.created_by')
                    ->leftjoin('blog_translation as bt', 'bt.blog_id', '=', 'b.id')->where('b.status', 'Active')->where("bt.language_id", $language_id)->paginate(6);

            $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();

            return view('client.blog', ['data' => $data, 'blogsearch' => $searchtext]);
        }
    }


    public function details($text = "")
    {
        Helper::countwebsite();
        $language_id = 1;

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

        if (session()->has('lang_code')) {
            $getLanguageId = DB::table('languages')->select('id', 'name', 'code')->where('status', 'Active')
                ->where('deleted', '0')->where('code', session()->get('lang_code'))->first();
            $language_id = $getLanguageId->id;
        }

        $blogid = DB::table('blog')->where('slug', $text)->first();
        $id = $blogid->id;

        $visitors = Helper::visitors('blog',$id);
        $metadata = DB::table('directory_seo')->where('module_id', $id)->where('page_type', 'blog')->first();

        $data['blog']  = DB::table('blog as b')->select('b.*', 'users.name','users.last_name', 'bt.title as bt_title', 'bt.blog_id', 'bt.language_id', 'bt.description', 'bt.seo_url', 'c.name as category_name', 'c.id as category_id', 'c.slug as category_slug')->where('b.id', $id)
            ->leftjoin('category as c', 'c.id', '=', 'b.category_id')
            ->leftjoin('users', 'users.id', '=', 'b.created_by')
            ->leftjoin('blog_translation as bt', 'bt.blog_id', '=', 'b.id')->where("bt.language_id", $language_id)
            ->first();

        $data['blogData'] = DB::table('blog as b')->select('b.*', DB::raw("6371 * acos(cos(radians(" . $latitude . "))
            * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
            + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            'bt.title as bt_title', 'bt.blog_id', 'bt.language_id', 'bt.description', 'bt.seo_url', 'c.name as category_name', 'c.slug as category_slug')
            ->where('b.deleted', '0')->limit(9)
            ->orderby('distance', 'asc')
            ->leftjoin('category as c', 'c.id', '=', 'b.category_id')
            ->leftjoin('blog_translation as bt', 'bt.blog_id', '=', 'b.id')
            ->where('b.status', 'Active')->where("bt.language_id", $language_id)->get()->toArray();

        $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();
        $data['reviewData']  = DB::table('blog as b')
            ->leftjoin('reviews as r', 'r.page_id', '=', 'b.id')
            ->select('r.*', 'r.page_id as id')->where('b.id', $id)->where('r.status', 'Active')
            ->where('r.page', 'blog')->get()->toArray();
        return view('client.blogdetails', ['data' => $data, 'metadata' => $metadata]);
    }
    public function submitComment(Request $request)
    {
        Helper::countwebsite();
        $review = new Review();
        $review->user_id                   = $request->user_login;
        $review->page_id                   = $request->page_id;
        $review->directory_id              = $request->directory_id??0;
        $review->page                      = $request->page;
        $review->url                       = $request->page_url;
        $review->content                   = $request->content;
        $review->save();
        if ($review) {
            $arr = array('msg' => 'Please wait for approval of admin!', 'status' => true);
        }
        return Response()->json($arr);
    }
    public function categorydetails(Request $request, $text)
    {
        Helper::countwebsite();
        $id = DB::table('category')->select('id','slug')->where('slug', $text)->first();
        $id = $id->id;

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

        $searchQuery = $request->input('searchtext');
        $searchtext = isset($searchQuery) ? $searchQuery : "";
        if ($request->input('search')) {
            $datablogData = DB::table('blog as b')
                ->select('b.*',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'users.name','users.last_name', 'c.name as category_name', 'c.slug as category_slug')
                ->where('b.deleted', '0')->orderby('distance', 'asc');

            $datablogData = $datablogData->where(function ($query) use ($searchtext) {
                $query->where('b.title', 'like', '%' . $searchtext . '%');
                $query->orWhere('b.content', 'like', '%' . $searchtext . '%');
            });

            $datablogData = $datablogData->leftjoin('users', 'users.id', '=', 'b.created_by');

            $data['blogData'] = $datablogData->leftjoin('category as c', 'c.id', '=', 'b.category_id')
                            ->where('category_id', $id)->get()->toArray();
            $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();
            return view('client.blogcategoryresult', ['data' => $data, 'blogsearch' => $searchtext]);
        } else {
            $data['blogData'] = DB::table('blog as b')->select('b.*',
                        DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                        * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                        + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                        'users.name','users.last_name', 'c.name as category_name','c.slug as category_slug')
                        ->where('b.deleted', '0')->limit(9)->orderby('distance', 'asc')
                        ->leftjoin('category as c', 'c.id', '=', 'b.category_id')->where('category_id', $id)
                        ->leftjoin('users', 'users.id', '=', 'b.created_by')
                        ->get()->toArray();

            $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();

            //dd($data['blogData']);
            return view('client.blogcategoryresult', ['data' => $data, 'blogsearch' => $searchtext]);
        }
        // $data['blogData'] = DB::table('blog as b')->select('b.*', 'users.name','users.last_name', 'c.name as category_name')->where('b.deleted', '0')->limit(9)->orderby('b.created_at', 'desc')
        //     ->leftjoin('category as c', 'c.id', '=', 'b.category_id')->where('category_id', $id)
        //     ->leftjoin('users', 'users.id', '=', 'b.created_by')
        //     ->get()->toArray();
        // $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();
        // return view('client.blogcategoryresult', ['data' => $data]);
    }
}
