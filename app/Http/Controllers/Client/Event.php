<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\EventCalenderRequest;
use App\Models\EventGallery;
use App\Models\Events;
use App\Helpers\Helper;
use App\Models\Distancesetting;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Models\Review;
use Carbon\Carbon;
use DateTime;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;
use Spatie\CalendarLinks\Link;

class Event extends Controller
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
        $allParentcategory = Helper::getAllParentCategory();
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
        
         $distance_default = Distancesetting::where('status','Active')->where('default_distance','yes')->orderBy('id', 'asc')->first(); 
         $distancesetting = Distancesetting::where('status','Active')->orderBy('id', 'asc')->get();

        if (session()->has('lang_code')) {
            $getLanguageId = DB::table('languages')->select('id', 'name', 'code')->where('status', 'Active')
                ->where('deleted', '0')->where('code', session()->get('lang_code'))->first();
            $language_id = $getLanguageId->id;
        }
                     
            $searchQuery        = $request->input('searchtext');
            $searchFromDate     = $request->fromdate;
            $searchToDate       = $request->todate;
            $searchCategory     = $request->cat;
            $searchcatsubid     = $request->subcat;
            $fromDate           = isset($searchFromDate) ? $searchFromDate : "";
            $toDate             = isset($searchToDate) ? $searchToDate : "";
            $searchtext         = isset($searchQuery) ? $searchQuery : "";
            $searchCatId        = isset($searchCategory) ? $searchCategory : "";
            $searchsubCatId     = isset($searchcatsubid) ? $searchcatsubid : "";

    //  DB::enableQueryLog();  
    $today = date('Y-m-d H:i:s');
           
            $eventData = DB::table('events as e')->select('e.*', 
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'et.title as et_title', 'et.event_id', 'et.language_id', 'et.description', 'et.seo_url', 
                'c.name as category_name', 'c.id as categoryid', 'c.slug as category_slug')

                ->limit(9)->orderby('distance', 'asc')
                ->where("et.language_id", $language_id)
                ->orderBy('expiry_status', 'asc')   // Active first
                ->orderBy('distance', 'asc');    // Then nearest;
                $eventData->leftjoin('category as c', 'c.id', '=', 'e.category_id');
                $eventData->leftjoin('events_translation as et', 'et.event_id', '=', 'e.id');
                
                if(session('distance')){
                    $eventData->having('distance', '<', session('distance'));
                }else{
                    $eventData->having('distance', '<', $distance_default->to_distance);
                }
                $eventData->selectRaw("CONCAT(end_date, ' ', end_time) as end_datetime");
                $eventData->selectRaw("CASE 
                                WHEN CONCAT(end_date, ' ', end_time) < NOW() THEN 'Expired' 
                                ELSE 'Active' 
                            END as expiry_status")->where('e.deleted', '0')->where('e.status','Active');

            if ($request->input('search')) { 
                
                if (!empty($fromDate) && !empty($toDate)) {
                    $eventData->where(function($q) use ($fromDate, $toDate) {
                        $q->whereBetween('e.start_date', [$fromDate, $toDate])   // starts inside range
                        ->orWhereBetween('e.end_date', [$fromDate, $toDate])   // ends inside range
                        ->orWhere(function($q2) use ($fromDate, $toDate) {     // covers entire range
                            $q2->where('e.start_date', '<=', $fromDate)
                                ->where('e.end_date', '>=', $toDate);
                        });
                    });
                }
                elseif (!empty($fromDate)) {
                    $eventData->where('e.end_date', '>=', $fromDate); // event still running after fromDate
                }
                elseif (!empty($toDate)) {
                    $eventData->where('e.start_date', '<=', $toDate); // event started before toDate
                }

                if ($searchCategory != 'all') {
                    $eventData->where('e.category_id', $searchCategory);
                }
                if (!empty($searchcatsubid)) {
                    $eventData->where('e.subcategory_id', $searchcatsubid);
                }
                if (!empty($searchQuery)) {
                    $eventData->where(function ($q) use ($searchQuery) {
                        $q->where('et.title', 'LIKE', "%$searchQuery%")
                        ->orWhere('et.description', 'LIKE', "%$searchQuery%");
                    });
                }
                
            }else{
                
                $eventData->where('e.end_date', '>=', $today);
            }
            //$eventData->where("e.status", 'Active')->orderby('e.id', 'desc');
            

            $data['eventData'] = $eventData->paginate(10);
            $data['eventData']->appends($request->query());


            $eventDataLatest =  DB::table('events as e')->select('e.*', DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                    * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                    + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                    'et.title as et_title', 'et.event_id', 'et.language_id', 'et.description', 'et.seo_url', 
                    'c.name as category_name', 'c.id as category_id', 'c.slug as category_slug')
                    ->where('e.deleted', '0')
                    ->limit(9)
                    ->where("et.language_id", $language_id)
                    ->where('e.status','Active')
                    ->take(5)
                    ->orderBy('expiry_status', 'asc')   // Active first
                    ->orderBy('distance', 'asc');    // Then nearest;

                $eventDataLatest->selectRaw("CONCAT(end_date, ' ', end_time) as end_datetime");
                $eventDataLatest->selectRaw("CASE 
                                WHEN CONCAT(end_date, ' ', end_time) < NOW() THEN 'Expired' 
                                ELSE 'Active' 
                            END as expiry_status");
                    
                    $eventDataLatest->leftjoin('category as c', 'c.id', '=', 'e.category_id');
                    $eventDataLatest->leftjoin('events_translation as et', 'et.event_id', '=', 'e.id');
                    if(session('distance')){
                        $eventDataLatest->having('distance', '<', session('distance'));
                    }else{
                        $eventDataLatest->having('distance', '<', $distance_default->to_distance);
                    }
                    if ($request->input('search')) { 
                    }else{
                         $eventDataLatest->where('end_date', '>=', $today);
                    }
                    
                $data['eventsLatest'] = $eventDataLatest->get();

            $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();
            return view('client.event', ['data' => $data, 'eventsearch' => $searchtext, 'distancesetting' => $distancesetting, 'eventsearchFromDate' => $fromDate, 'eventsearchToDate' => $toDate, 'allParentcategory' => $allParentcategory, 'searchCatId' => $searchCatId, 'searchsubCatId' => $searchsubCatId]);
        //}
        
    }

    public function addToCalender(Request $request)
    {
        if (Auth::check()) {
            $event                          = new GoogleCalendarEvent;
            $event->name                    = $request->title;
            $event->description             = $request->description;
            $event->startDateTime           = Carbon::parse($request->start . ' Asia/Kolkata');
            $event->endDateTime             = Carbon::parse($request->end . ' Asia/Kolkata');
            $event->addAttendee([
                'email'                     => 'sanjay993147@gmail.com',
                'name'                      => 'Test sanjay993147',
                'comment'                   => 'Lorum ipsum sanjay993147',
            ]);
            $event->addAttendee(['email'    => 'anotherEmail@gmail.com']);
            $result                         = $event->save();
            if ($result) {
                $event_r                    = new EventCalenderRequest();
                $event_r->event_id          = $request->event_id;
                $event_r->user_id           = $request->user_id;
                $event_r->title             = $request->title;
                $event_r->description       = $request->description;
                $event_r->start_date        = Carbon::parse($request->start);
                $event_r->end_date          = Carbon::parse($request->end);
                $event_r->save();
                return $event_r->id;
            }
        }
    }

    public function details($slug)
    {
        Helper::countwebsite();
        try{

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

            $language_id = 1;
            if (session()->has('lang_code')) {
                $getLanguageId = DB::table('languages')->select('id', 'name', 'code')->where('status', 'Active')
                    ->where('deleted', '0')->where('code', session()->get('lang_code'))->first();
                $language_id = $getLanguageId->id;
            }
            $evenid = Events::where('slug', $slug)->first();
            $id = $evenid->id;
            $visitors = Helper::visitors('event',$id);
            $data['event']  = DB::table('events as e')->select('e.*', 'et.title as et_title', 'et.event_id', 'et.language_id', 'et.description', 'et.seo_url', 'c.name as category_name', 'c.id as category_id', 'c.slug as category_slug')->where('e.slug', $slug)
                ->leftjoin('category as c', 'c.id', '=', 'e.category_id')
                ->leftjoin('events_translation as et', 'et.event_id', '=', 'e.id')->where("et.language_id", $language_id)
                ->first();

            $userid = Auth::user()->id ?? 0;
            $data['eventgallery'] = Events::with('eventgallery')
                ->with(['googleCalendar' => function ($q) use ($userid) {
                    $q->where('user_id', '=', $userid);
                }])->where('id', $id)->first();
            $data['eventData'] = DB::table('events as e')->select('e.*', DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'et.title as et_title', 'et.event_id', 'et.language_id', 'et.description', 'et.seo_url', 'c.name as category_name')
                ->where('e.deleted', '0')->limit(9)
                // ->orderby('e.updated_at', 'desc')
                ->orderby('distance', 'asc')
                ->leftjoin('category as c', 'c.id', '=', 'e.category_id')
                ->leftjoin('events_translation as et', 'et.event_id', '=', 'e.id')->where("et.language_id", $language_id)->get()->toArray();
           
            $data['eventsLatest'] = DB::table('events as e')->select('e.*', 
                                    DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                    * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                    + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                                    'et.title as et_title', 'et.event_id', 'et.language_id', 'et.description', 'et.seo_url', 'c.name as category_name')
                                    ->where('e.deleted', '0')->limit(9)
                                    // ->orderby('e.updated_at', 'desc')
                                    ->where('e.status', 'Active')
                                    ->where('e.slug', '!=', $slug)
                                    ->orderby('distance', 'asc')
                                    ->leftjoin('category as c', 'c.id', '=', 'e.category_id')
                                    ->leftjoin('events_translation as et', 'et.event_id', '=', 'e.id')->where("et.language_id", $language_id)->take(5)->get();


            $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();

            $data['reviewData']  = DB::table('events as e')
                ->leftjoin('reviews as r', 'r.page_id', '=', 'e.id')
                ->select('r.*', 'r.page_id as id')->where('e.id', $id)->where('r.status', 'Active')
                ->where('r.page', 'event')->get()->toArray();

            $metadata = DB::table('directory_seo')->where('module_id', $id)->where('page_type', 'event')->first();

            $allParentcategory = Helper::getAllParentCategory();
            $searchFromDate     = null; //$request->fromdate;
            $searchToDate       = null; //$request->todate;
            $searchCatId        = null; //$request->cat;
            $searchQuery        = null; //$request->input('searchtext');

            $from = DateTime::createFromFormat('Y-m-d H:i:s', $data['event']->start_date.' '.$data['event']->start_time);
            $to   = DateTime::createFromFormat('Y-m-d H:i:s', $data['event']->end_date.' '.$data['event']->end_time);
            // Ensure $to is always after $from
            if ($to <= $from) {
                // Option A: Set end = start + 1 hour (default duration)
                $to = (clone $from)->modify('+1 hour');
                // Option B: Swap values if event data might be reversed
            }
            $link = Link::create($data['event']->et_title, $from, $to)
                ->description($data['event']->description)
                ->address($data['event']->location ?? 'Drottninggatan 20, 432 41 Varberg, Sweden');
            $ics_link = $link->ics(['URL' => url('/event/details/'.$data['event']->slug)]);

            return view('client.eventdetails', ['data' => $data,'ics_link' => $ics_link,'metadata' => $metadata, 'eventsearch' => $searchQuery, 'eventsearchFromDate' => $searchFromDate, 'searchCatId' => $searchCatId, 'eventsearchToDate' => $searchToDate,'allParentcategory' => $allParentcategory]);
        } catch (Exception $e) {
            return abort(404);
        }
    }

    public function submitEventComment(Request $request)
    {
        Helper::countwebsite();
        $review = new Review();
        $review->user_id                   = $request->user_login;
        $review->page_id                   = $request->page_id;
        $review->page                      = $request->page;
        $review->url                       = $request->page_url;
        $review->star                      = $request->rating;
        $review->directory_id              = $request->directory_id??0;
        $review->content                   = $request->content;
        $review->save();
        if ($review) {
            $arr = array('msg' => 'Please wait for approval of admin!', 'status' => true);
        }
        return Response()->json($arr);
    }

    public function categoryDetailsEvent(Request $request, $text)
    {
       Helper::countwebsite();
       $allParentcategory = Helper::getAllParentCategory();
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

        $latitude           =       "'$latitude1'";
        $longitude          =       "'$longitude1'";

        $data['eventsLatest'] = DB::table('events as e')->select('e.*', DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                    * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                    + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                    'et.title as et_title', 'et.event_id', 'et.language_id', 'et.description', 'et.seo_url', 'c.name as category_name', 'c.id as category_id', 'c.slug as category_slug')
                    ->where('e.deleted', '0')->limit(9)
                    // ->orderby('e.updated_at', 'desc')
                    ->orderby('distance', 'asc')
                    ->leftjoin('category as c', 'c.id', '=', 'e.category_id')
                    ->leftjoin('events_translation as et', 'et.event_id', '=', 'e.id')->where("et.language_id", 1)->take(5)->get();

            $searchQuery        = $request->input('searchtext');
            $searchFromDate     = $request->fromdate;
            $searchToDate       = $request->todate;
            
            $fromDate           = isset($searchFromDate) ? $searchFromDate : "";
            $toDate             = isset($searchToDate) ? $searchToDate : "";
            $searchtext         = isset($searchQuery) ? $searchQuery : "";
            

        // DB::enableQueryLog();

        if ($request->input('search')) {
            $dataEventData = DB::table('events as e')
                ->select('e.*', 
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                'users.name','users.last_name', 'c.name as category_name', 'c.id as category_id', 'c.slug as category_slug')
                ->where('e.deleted', '0')->orderby('distance', 'asc');

                $dataEventData = $dataEventData->where(function ($query) use ($searchtext) {
                    if(!empty($searchtext)){
                        $query->where('e.title', 'like', '%' . $searchtext . '%');
                        $query->orWhere('e.content', 'like', '%' . $searchtext . '%');
                    }
                });
    
                /*$dataEventData = $dataEventData->where(function ($query) use ($fromDate, $toDate) {
                    if(!empty($fromDate)){
                         //echo "test two";
                        $query->where('e.start_date','>=', $fromDate);
                    }
                    if(!empty($toDate)){
                        // echo "test three";
                        $query->where('e.end_date','<=', $toDate);
                    }
                });*/

                if(!empty($fromDate) && !empty($toDate)){
                    $dataEventData = $dataEventData->where(function ($query) use ($fromDate, $toDate) {
    
                        $query->where([['e.start_date','<=',$fromDate],['e.end_date','>=',$toDate]])
                        ->orwhereBetween('e.start_date',array($fromDate,$toDate))
                        ->orWhereBetween('e.end_date',array($fromDate,$toDate));
                    });
                }else{
                    $dataEventData = $dataEventData->where(function ($query) use ($fromDate, $toDate) {
                        if(!empty($fromDate)){
                             //echo "test two";
                            $query->where('e.start_date','>=', $fromDate);
                        }
                        if(!empty($toDate)){
                            // echo "test three";
                            $query->where('e.end_date','>=', $toDate);
                        }
                    });
                }
                
                
            
            $dataEventData = $dataEventData->leftjoin('users', 'users.id', '=', 'e.created_by');

            $data['eventData'] = $dataEventData->leftjoin('category as c', 'c.id', '=', 'e.category_id')->where('category_id', $id)->paginate(6);
            $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();

            // $queries = DB::getQueryLog();
            // print_r($queries);die;

            return view('client.eventscategoryresult', ['data' => $data, 'eventsearch' => $searchtext, 'eventsearchFromDate' => $fromDate, 'eventsearchToDate' => $toDate]);
        } else {
            $data['eventData'] = DB::table('events as e')->select('e.*', 
                        DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                        * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                        + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                        'users.name','users.last_name', 'c.name as category_name','c.slug as category_slug')
                        ->where('e.deleted', '0')->limit(9)->orderby('distance', 'asc')
                        ->leftjoin('category as c', 'c.id', '=', 'e.category_id')->where('category_id', $id)
                        ->leftjoin('users', 'users.id', '=', 'e.created_by')
                        ->paginate(10);

            $data['catData'] = DB::table('category')->select('*')->where('deleted', '0')->limit(9)->orderby('name', 'asc')->get()->toArray();

            //dd($data['blogData']);
            return view('client.eventscategoryresult', ['data' => $data, 'eventsearch' => $searchtext, 'eventsearchFromDate' => $fromDate, 'eventsearchToDate' => $toDate]);
        }
    }
}
