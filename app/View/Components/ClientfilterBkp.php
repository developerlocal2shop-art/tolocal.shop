<?php

namespace App\View\Components;
use Illuminate\View\Component;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
class ClientfilterBKP extends Component
{

    protected $directoryData;
    public function __construct($directoryData)
    {
        $this->directoryData = $directoryData;
    }

    public function render()
    {
        $language_id = Helper::getselectedLanguage();
        $data['categories']      = DB::table('category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                ->where('category_type_id','2')->where('feature','yes')->where('deleted','0')->where('parent_id', 0)->limit(15)->orderby('sort_value','asc')
                                ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();
        $data['subcategories']   = DB::table('category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                ->where('category_type_id','2')->where('feature','yes')->where('deleted','0')->where('parent_id','!=',0)->orderby('id','desc')
                                ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();

        $data['maxprice']        = DB::table('product_price')->where('regular_price', \DB::raw("(select max(`regular_price`) from lc_product_price)"))->first();
        $data['amenity']         =   Helper::getAmenity();
        $data['trademark']       =   Helper::getDirectoryTrademarks();
        $data['listingalltags']  = DB::table('category_tags')->select('*')->where('status','Active')->get();
        //$data['dirData']         = $this->directoryData;

        $user_ip = getenv('REMOTE_ADDR'); 
        if (session('latitude_city')){
            $latitude1       =       session('latitude_city');
            $longitude1      =       session('logitude_city');
        }else{
            if (session('latitude') == null) {             
                if($user_ip=='127.0.0.1' || $user_ip=='::1' || $user_ip=='localhost' ){
                    $latitude1 = '28.6542';
                    $longitude1 = '77.2373';
                }else{
                    $latitude1       =       $geoData['geoplugin_latitude']??'';
                    $longitude1      =       $geoData['geoplugin_longitude']??'';
                }
            } else {
                $latitude1       =       session('latitude');
                $longitude1      =       session('logitude');
            }
        }
        $latitude        =       "'$latitude1'";
        $longitude       =       "'$longitude1'";
        $totalDirectoryData = DB::table('directory as dt')
            ->select(
                'dt.id','dt.status',
                'directory_location.latitude','directory_location.longitude',
                'dt_cat.category_id AS dir_cat_id',
                'dt_sub_cat.sub_category_id AS dir_sub_cat_id',
                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
            );
        $totalDirectoryData = $totalDirectoryData->leftjoin('directory_location', 'dt.id', '=', 'directory_location.directory_id');
        $totalDirectoryData = $totalDirectoryData->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id'); 
        $totalDirectoryData = $totalDirectoryData->leftjoin('directory_sub_category as dt_sub_cat', 'dt.id', '=', 'dt_sub_cat.directory_id');
        $totalDirectoryData = $totalDirectoryData->where('dt.status', 'Active')->groupBy('dt.id')->where('dt.deleted', '0')->get();
        $totalDirectoryData_All = $totalDirectoryData;
        $data['dirData']        = $totalDirectoryData_All;
        //$data['dirData']         = $this->directoryData;
       
        return view('components.client.filter_side',['data' => $data]);
    }
}
