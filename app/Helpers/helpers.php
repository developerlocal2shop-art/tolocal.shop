<?php
namespace App\Helpers;

use App\Models\AdvertisementQuestions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\AmenityCategory;
use App\Models\AttributeStatus;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Counter;
use App\Models\FreeProductsDiscount;
use App\Models\DirectorySchedual;
use App\Models\PlanSubscription;
use App\Models\Productcategory;
use App\Models\ShoppingCard;
use App\Models\TrademarkCategory;
use App\Models\Trademarks;
use App\Models\User;
use App\Models\Frontendsetting;
use App\Models\Vendorsetting;
use App\Models\Order;
use App\Models\Currency;
use App\Models\client\LikeCounters;
use App\Models\CountCustomerClub;
use App\Models\Diectory;
use App\Models\Distancesetting;
use App\Models\Language;
use App\Models\OfferWinner;
use App\Models\PlanFeatures;
use App\Models\ProductAttributes;
use App\Models\ProductCategories;
use App\Models\Review;
use App\Models\SearchCounter;
use App\Models\VendorCompetitionStatus;
use Exception;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Stevebauman\Location\Facades\Location;

class Helper {

    function pr($array, $flag = true) {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
        if($flag == false) {
            die();
        }
    }

    public static function getselectedLanguage(){
        $languageCode = session('lang_code', config('app.locale', 'sv'));
        $language = Language::select('id','name','code')->where('status','Active')
            ->where('deleted','0')->where('code',$languageCode)->first();

        if (!$language && $languageCode !== 'sv') {
            $language = Language::select('id','name','code')->where('status','Active')
                ->where('deleted','0')->where('code','sv')->first();
        }

        return $language ? $language->id : 1;
    }

    public static function cookieConsentGranted($category)
    {
        $consent = request()->cookie('cookie_consent');
        if (is_string($consent)) {
            $consent = json_decode(rawurldecode($consent), true);
        }

        return is_array($consent) && !empty($consent[$category]);
    }

    public static function getAllLanguages(){
        $languagesData = Language::select('id','name','code')
                        ->where('status','Active')->where('deleted','0')
                        ->get();
        return $languagesData;
    }


    public static function getAllCategoryType(){
        $categoryTypeData = DB::table('category_type')
                        ->select('id','title')
                        ->where('status','Active')->where('deleted','0')
                        ->get()->toArray();
        return $categoryTypeData;
    }

    public static function getProductCategories(){
        $data = DB::table('product_category')->select('id','name')->where('parent_id',0)->where('status','Active')->where('deleted','0')->get()->toArray();
        return $data;
    }


    public static function getAllParentCategory(){
        $categoryParentData = DB::table('category')
                        ->select('id','name')
                        ->where('parent_id',0)->where('status','Active')->where('deleted','0')->orderby('sort_value','asc')
                        ->get()->toArray();
        return $categoryParentData;
    }

    public static function getAllParentProductCategory(){
        $productcategoryParentData = DB::table('product_category')
                        ->select('id','name')
                        ->where('parent_id',0)->where('status','Active')->where('deleted','0')
                        ->get()->toArray();
        return $productcategoryParentData;
    }
    public static function getAllChildCategory(){
        $categoryChildData = DB::table('category')
                        ->select('id','name')
                        ->where('parent_id','<>',0)->where('status','Active')->where('deleted','0')
                        ->orderBy('name', 'ASC')->get()->toArray();
        return $categoryChildData;
    }

    public static function getLanguagesDataById($id){
        $languagesDataById = Language::select('id','name','code')
                        ->where('status','Active')->where('deleted','0')
                        ->where('id',$id)
                        ->first();
        return $languagesDataById;
    }

    public static function getReplyRequestId($id){
        $ReplyRequestId = DB::table('reply_requests')
                        ->where('company_id',Auth::user()->directory_id)
                        ->where('request_id',$id)
                        ->count();
        return $ReplyRequestId;
    }

    public static function getDirectorySlug($id){
        $data = DB::table('directory_basic_translation')
                        ->select('seo_url')
                        ->where('directory_id',$id)
                        ->first();
        return $data;
    }

    public static function getDirectoryDescriptionIdLocationid($id,$language_id){
        $data = DB::table('directory_basic_translation')
                        ->select('description')
                        ->where('directory_id',$id)
                        ->where('language_id',$language_id)
                        ->first();
        return $data;
    }

    public static function getAnswerByQueId($id){
        $data = DB::table('advertisement_answers')
                        ->select('id','answer','is_correct','prize_code','advertisement_id')
                        ->where('question_id',$id)
                        ->where('customer_id',Auth::id())
                        ->first();
        return $data;
    }


    public static function getPrizeDataId($question_id, $advertisement_id, $customer_id){
        $data = DB::table('competition_winners')
                        ->select('prize_amount', 'voucher_code')
                        ->where('question_id', $question_id)
                        ->where('advertisement_id', $advertisement_id)
                        ->where('customer_id', $customer_id)
                        ->first();
        return $data;
    }

    public static function getOfferPrizeDataId($offer_id, $vendor_id, $customer_id){

        $data = OfferWinner::select('prize_amount', 'voucher_code','description','discount_type')
                        ->where('offer_id', $offer_id)
                        ->where('vendor_id', $vendor_id)
                        ->where('customer_id', $customer_id)
                        ->first();
        return $data;
    }

    public static function getVenAnswerByQueId($id){
        $data = DB::table('advertisement_answers')
                        ->select('answer','vendor_id')
                        ->where('question_id',$id)
                        ->first();
        return $data;
    }

    public static function getPrizeType($question_id,$advertisement_id){
        $data = DB::table('competition_winners')
                        ->where('question_id',$question_id)
                        ->where('advertisement_id',$advertisement_id)
                        ->first();
        return $data;
    }

    public static function getDirectoryIdSlug($id){
        $data = DB::table('directory')
                        ->select('slug','title','company_logo','company_color','competition_discount','competition_discount_text','discount_type')
                        ->where('id',$id)
                        ->first();
        return $data;
    }

    public static function getVendorCompetitionById($company_id,$competition_id){
        $data = DB::table('vendor_competition_list')
                        ->select('discount_type','discount_amount','title')
                        ->where('competition_id',$competition_id)
                        ->where('company_id',$company_id)
                        ->first();
        return $data;
    }

    public static function OfferWinnerByCompanyId($offer_id){
        return OfferWinner::where('offer_id', $offer_id)->where('vendor_id', Auth::user()->directory_id)->orderBy('id', 'desc')->count();
    }

    public static function QuestionsAnsById($queid){
        $data = AdvertisementQuestions::with('answers')
                ->where('id', $queid)
                ->first();
        return $data;
    }

    public static function IsCompetitionHasQuestions($competition_id){
        $data = AdvertisementQuestions::with('answers')
                ->where('advertisement_id', $competition_id)
                ->where('company_id', Auth::user()->directory_id)
                ->count();
        return $data;
    }

    public static function VendorCompetitionStatus($competition_id){
        $data = VendorCompetitionStatus::where('competition_id', $competition_id)
                ->where('company_id', Auth::user()->directory_id)
                ->first();
        return $data;
    }

    public static function IsVendorCompetitionStatusActive($competition_id, $companyId){
        $data = VendorCompetitionStatus::where('competition_id', $competition_id)->where('company_id', $companyId)->first();
        return $data;
    }

    public static function QuestionsAnsByVendorId($row){
        $data = AdvertisementQuestions::with('answers')
                ->where('id', $row->id)
                ->whereHas('answers', function ($query) use ($row) {
                    $query->where('vendor_id', $row->company_id);
                })
                ->first();
        return $data;
    }

    public static function QuestionsByVendorId($company_id){
        $data = AdvertisementQuestions::with('answers')
                ->whereHas('answers', function ($query) use ($company_id) {
                    $query->where('vendor_id', $company_id);
                })
                ->first();
        return $data;
    }

    public static function getReviewCount($id,$page_id){
        $data = DB::table('reviews')
                        ->where('user_id',$id)
                        ->where('page_id',$page_id)
                        ->where('page','blog')
                        ->count();
        return $data;
    }
    public static function getReviews($id){
        $reviews = Review::with('user')->with('photos')->where('page_id', $id)->orderBy('id','desc')->where('status', 'Active')->get();
        return $reviews;
    }

    public static function getSeoUrl($text, string $divider = '-'){
        // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, $divider);
        // remove duplicate divider
        $text = preg_replace('~-+~', $divider, $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }

    public static function generatePrizeCode($prefix = 'PRIZE-', $length = 8)
    {
        $random = strtoupper(substr(bin2hex(random_bytes((int) ceil($length / 2))), 0, $length));
        return $prefix . $random;
    }

    public static function getcatcutfields(){
        $fieldsData = DB::table('category_fields')
                        ->select('*')
                        ->where('status','Active')->where('deleted','0')
                        ->get()->toArray();
        return $fieldsData;
    }

    public static function getCustomerClubByCusid($customer_id){
        $cData = DB::table('customer_clubs')->where('customer_id',$customer_id)->first();
        return $cData;
    }

    public static function getParentCategory($id){
        $fieldsData = DB::table('category')
                        ->select('*')
                        ->where('status','Active')->where('deleted','0')
                        ->where('id',$id)
                        ->get()->toArray();
        return $fieldsData;
    }

    public static function getParentCompanyCategory($id){
        $fieldsData = DB::table('category')
                        ->where('status','Active')->where('deleted','0')
                        ->where('id',$id)
                        ->first();
        return $fieldsData;
    }

    public static function getproductParentCategory($id){
        $fieldsParentData = DB::table('product_category')
                        ->select('*')
                        ->where('status','Active')->where('deleted','0')
                        ->where('id',$id)
                        ->get()->toArray();
        return $fieldsParentData;
    }

    public static function getFeatureUsesCompanyDetail($slug){
        $subscriptionsUses = PlanSubscription::where('subscriber_id',auth()->user()->directory_id ?? 0)->with('subscriptionsUses')->first();
        if($subscriptionsUses){
             $PlanFeatures = PlanFeatures::where('plan_id',$subscriptionsUses->plan_id)->where('slug',$slug)->first();
            if($PlanFeatures){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public static function getFeatureUses($slug){
        $subscriptionsUses = PlanSubscription::where('subscriber_id',auth()->user()->directory_id ?? 0)->with('subscriptionsUses')->first();
        //dd($subscriptionsUses);
        if($subscriptionsUses){
            foreach($subscriptionsUses->subscriptionsUses as $val){
                $name = explode(' ',$val->name);
                if (in_array($slug, $name))
                {
                    return true;
                }
            }
        }
    }

    public static function getFeatureUsesValue($slug){
        $subscriptionsUses = PlanSubscription::where('subscriber_id',auth()->user()->directory_id??0)->with('subscriptionsUses')->first();
        if($subscriptionsUses){
            foreach($subscriptionsUses->subscriptionsUses as $val){
                $name = explode(' ',$val->name);
                if (in_array($slug, $name))
                {
                    return $val->used;
                }
            }
        }
    }

    public static function getUserById($uerId) {
        return User::select('id','email','name','last_name')->where('id', $uerId)->first();
    }

    public static function getCountryAllData(){
        $countriesData = DB::table('countries')
                        ->select('*')
                        ->get()->toArray();
        return $countriesData;
    }
    public static function getAmenity(){

        $amenityData = DB::table('amenity')
            ->where('deleted', '0')
            ->where(function ($query) {
                $query->whereExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                        ->from('plan_subscriptions')
                        ->whereColumn('plan_subscriptions.subscriber_id', 'amenity.directory_id')
                        ->where('plan_subscriptions.slug', 'premium');
                })
                ->orWhere('status', 'Active');  // Apply status condition only if plan_subscriptions does not exist
            })
            ->get()->toArray();
        return $amenityData;
    }
    public static function getTrademarks(){
        $trademarksData = DB::table('trademarks')
                        ->select('*')
                        ->where('deleted','0')
                        ->get();
        return $trademarksData;
    }
    public static function getType(){
        $typeData = DB::table('category')
                        ->select('id','name')
                        ->where('parent_id',0)->where('status','Active')->where('deleted','0')->where('category_type_id','2')
                        ->get()->toArray();
        return $typeData;
    }
    function getSearchData(Request $request)
    {
        //echo $request."<br>";die;
        if ($request->ajax()) {
            $output = '';
            $query = $request->get('query');
            $tblname = $request->get('tbl_name');
            $editurl = $request->get('editurl');
            $deleteurl = $request->get('deleteurl');
            $infofield = $request->get('info');

                 $data = DB::table($tblname)
                 ->where($infofield[0], 'like', '%'. $query .'%');
                 foreach($infofield as $key => $value){
                    if($key > 0){
                        $data->orWhere($value, 'like', '%'. $query .'%');
                    }
                 }

                 $languages = $data->orderBy('id', 'desc')->get();

            $total_row = $data->count();
            if ($total_row > 0) {
                foreach ($languages as $row) {
                    $output .= '
                        <tr>
                            <td>' . $row->name . '</td>
                            <td>' . $row->code . '</td>
                            <td>' . $row->status . '</td>
                            <td>
                            <a class="btn btn-primary btn-xs" href="'.$editurl.'/'.$row->id.'">EDIT</a>
                            <a class="btn btn-danger btn-xs show_confirm" href="'.$deleteurl.'/'.$row->id.'">DELETE</a>
                        </tr>
                        ';
                }
            } else {
                $output = '
                    <tr>
                        <td align="center" colspan="5">No Data Found</td>
                    </tr>
                    ';
            }
            $data = array(
                'table_data'  => $output,
            );

            echo json_encode($data);
        }
    }

    public static function masterSearch($data){

          $result= DB::table($data['table_name'])->select('*')->where('id','>',0);
          if ($data['table_name'] === 'order') {
            $result->addSelect([
              'refund_request_status' => DB::table('refund_statuses')
                ->select('status')
                ->whereColumn('refund_statuses.order_id', 'order.id')
                ->orderByDesc('id')
                ->limit(1)
            ]);
          }
          $result=$result->where(function ($query) use ($data) {
          foreach($data['columns'] as $key=>$value){
            if($value!="category_id" && $data['searchQuery']!=""){
                $query->orWhere($value, 'like', '%'. $data['searchQuery'] .'%');
            }
             if($value=="category_id"){
                $query->Where('category_id', $data['serchcategory_id'] );
             }
          }
        });
          $result=$result->orderBy('id', 'desc')->paginate(20);
          return $result;
    }

    public static function companymasterSearch($data){

        $result= DB::table($data['table_name'])->select('*')->where('directory_id',Auth::user()->directory_id)->where('id','>',0);
        if ($data['table_name'] === 'order') {
          $result->addSelect([
            'refund_request_status' => DB::table('refund_statuses')
              ->select('status')
              ->whereColumn('refund_statuses.order_id', 'order.id')
              ->orderByDesc('id')
              ->limit(1)
          ]);
        }
        $result=$result->where(function ($query) use ($data) {
          foreach($data['columns'] as $key=>$value){
            if($value!="category_id" && $data['searchQuery']!=""){
                $query->orWhere($value, 'like', '%'. $data['searchQuery'] .'%');
            }
             if($value=="category_id"){
                $query->Where('category_id', $data['serchcategory_id'] );
             }
          }
        });
          $result=$result->orderBy('id', 'desc')->paginate(20);
          return $result;
    }

    public static function getAllCategoryTypeListing(){
        $typeData = DB::table('category')
                        ->select('id','name')
                        ->where('status','Active')->where('deleted','0')->where('category_type_id','2')
                        ->get()->toArray();
        return $typeData;
    }
    public static function getAllCurrency(){
        $currencydata = DB::table('currency')
                        ->select('*')
                        ->get()->toArray();
        return $currencydata;
    }
    public static function getAllDirectory(){
        $directoryData = DB::table('directory')
                        ->select('id','title')
                        ->where('title','!=','')->where('status','Active')->where('deleted','0')
                        ->get()->toArray();
        return $directoryData;
    }

    public static function getallProductRelatedToDirectory($directory_id)
    {
        $data['productsdata'] = Product::where("directory_id", $directory_id)->where('deleted','0')->get(["name", "id"])->toArray();
        return $data['productsdata'];
    }

    public static function totalProducts()
    {
        $totalProducts = Product::where('directory_id', Auth::user()->directory_id)->where('deleted','0')->count();
        return $totalProducts;
    }

    public static function totalAdminProducts()
    {
        $totalProducts = Product::where('deleted','0')->count();
        return $totalProducts;
    }

    public static function getallWithFreeProductRelatedToDirectory($directory_id)
    {
        $data['productsdata'] = Product::where("directory_id", $directory_id)->where('is_free_product',1)->where('deleted','0')->get(["name", "id"])->toArray();
        return $data['productsdata'];
    }

    public static function getallWithoutFreeProductRelatedToDirectory($directory_id)
    {
        $data['productsdata'] = Product::where("directory_id", $directory_id)->where('is_free_product',0)->where('deleted','0')->get(["name", "id"])->toArray();
        return $data['productsdata'];
    }

    public static function getTagsData(){
        $tagsData = DB::table('category_tags')
                        ->select('*')->where('status','Active')
                        ->orderBy('tags', 'ASC')->get()->toArray();
        return $tagsData;
    }
    public static function getProductTagsData(){
        $tagsData = DB::table('product_tags')
                        ->select('*')->where('status','Active')
                        ->orderBy('tags', 'ASC')->get()->toArray();
        return $tagsData;
    }

    public static function getProductCatgoeryData(){
        $productcatData = DB::table('product_category')
                        ->select('*')->where('status','Active')->where('deleted','0')
                        ->orderBy('name', 'ASC')->get()->toArray();
        return $productcatData;
    }

    public static function getProductsubCatgoeryData(){
        $productcatData = DB::table('product_category')
                            ->select('id','name')->where('parent_id','<>',0)
                            ->where('status','Active')->where('deleted','0')
                            ->orderBy('name', 'ASC')->get()->toArray();
        return $productcatData;
    }
    public static function getOwnTagsData(){
        $ownTagsData = DB::table('category_own_tags')
                        ->select('*')->where('status','Active')
                        ->get()->toArray();
        return $ownTagsData;
    }
    public static function getdirectoryOwnTagsData(){
        $ownTagsData = DB::table('directory_createown_tags')->select('*')->where('status','Active')->get()->toArray();
        return $ownTagsData;
    }
    public static function getDirectory($id){
        $fieldsData = DB::table('directory')->select('*')->where('status','Active')->where('deleted','0')
                        ->where('id',$id)->get()->toArray();
        return $fieldsData;
    }
    public static function getDirectoryContact($dir_id){
        $fieldsData = DB::table('directory_contact')->where('directory_id',$dir_id)->first();
        return $fieldsData;
    }
    public static function getAmenityCategory($id){
        $fieldsData = DB::table('amenity_category')
                        ->select('*')
                        ->where('status','Active')->where('deleted','0')
                        ->where('id',$id)
                        ->get()->toArray();
        return $fieldsData;
    }
    public static function getTradeCategory($id){
        $fieldsData = DB::table('trademark_category')
                        ->select('*')
                        ->where('status','Active')->where('deleted','0')
                        ->where('id',$id)
                        ->get()->toArray();
        return $fieldsData;
    }

    public static function getDirectoryOwnTagsCategory($id){
        $fieldsData = DB::table('directory_own_tags_category')
                        ->select('*')
                        ->where('status','Active')->where('deleted','0')
                        ->where('id',$id)
                        ->get()->toArray();
        return $fieldsData;
    }
    public static function getTagsCount($id){
        $tagsCount = DB::table('directory_tags')->where('directory_id', $id)->count();
        return $tagsCount;
    }
    public static function getOwnTagsCount($id){
        $tagsCount = DB::table('directory_own_tags')->where('directory_id', $id)->count();
        return $tagsCount;
    }
    public static function getImageThumbnailCount($id){
        $thumbimage = DB::table('directory_media_listing_image')->where('directory_id', $id)->count();
        return $thumbimage;
    }
    public static function getImageCoverCount($id){
        $coverimage = DB::table('directory_media_cover')->where('directory_id', $id)->count();
        return $coverimage;
    }
    public static function getImageGalleryCount($id){
        $galleryimage = DB::table('directory_gallery_images')->where('directory_id', $id)->count();
        return $galleryimage;
    }
    public static function getTrademarkCount($id){
        $trademarkimage = DB::table('directory_media_trademarks_image')->where('directory_id', $id)->count();
        return $trademarkimage;
    }
    public static function getDirectoryProductCount($id){
        $directoryproduct = Product::where('directory_id', $id)->count();
        return $directoryproduct;
    }
    public static function getDirectoryImageThumbnail($id){
        $directorythumbimage = DB::table('directory_media_listing_image')->where('directory_id', $id)->get();
        return $directorythumbimage;
    }
    public static function getDirectoryPackageName($id){
        $directorypackagename = DB::table('plan_subscriptions')->where('subscriber_id', $id)->get();
        return $directorypackagename;
    }
    public static function getadminName($id){
        $userData = DB::table('admin')
                        ->select('name')
                        ->where('id',$id)
                        ->get()->toArray();
        return $userData;
    }
    public static function getCategoryimage($id){
        $catimage = DB::table('category_uploads')->select('*')->where('deleted','0')->where('id',$id)
                    ->get()->toArray();
        return $catimage;
    }
    public static function getProductCategoryimage($id){
        $productcatimage = DB::table('product_category_uploads')->select('*')->where('deleted','0')->where('id',$id)
                    ->get()->toArray();
        return $productcatimage;
    }
    public static function getDirectoryimage($id){
        $thumbimage = DB::table('directory_media_listing_image')->select('*')->where('directory_id', $id)->first();
        return $thumbimage;
    }
    public static function getOfferimage($id){
        $thumbimage = DB::table('offer_image')->select('*')->where('main_image','main')->where('offer_id', $id)
                     ->get()->toArray();
        return $thumbimage;
    }
    public static function getOfferGallery($id){
        $thumbimage = DB::table('offer_image')->select('*')->where('main_image','galimg')->where('offer_id', $id)
                     ->get()->toArray();
        return $thumbimage;
    }
    public static function getShoppingcardimage($id){
        $thumbimage = DB::table('shopping_card_image')->select('*')->where('main_image','main')->where('shopping_card_id', $id)
                     ->get()->toArray();

        return $thumbimage;
    }
    public static function getProductImage($id){
        $thumbimage = DB::table('product_images')->select('*')->where('main_image','main')->where('product_id', $id)
                     ->get()->toArray();

        return $thumbimage;
    }
    public static function getAllCategory(){
        $categoryParentData = DB::table('category')
                        ->select('id','name')
                        ->where('status','Active')->where('deleted','0')
                        ->get()->toArray();
        return $categoryParentData;
    }
    public static function getAllCity(){
        $data['directoryDetails'] = DB::table('directory as dt')
                ->select('dt_ln.city_name')
                ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')->where('deleted', '0');
        $cityData =    $data['directoryDetails']->get()->toArray();

        return $cityData;
    }
    public static function getAllState($countryId){
        $stateData =    State::query()->where('country_id',$countryId)->get()->toArray();
        //$stateData =    State::get();
       return $stateData;
    }
    public static function getAllCityByState($stateId){
        $cityDataByState =    City::select('id','name')->where('state_id',$stateId)->get()->toArray();
        //$stateData =    State::get();
       return $cityDataByState;
    }
    public static function getCities(){
        $cityData =    City::get();
       return $cityData;
    }
    public static function getCatName($id){
        $pcatData =    Productcategory::select('id', 'name')->where('id',$id)->first();
       return $pcatData;
    }
    public static function getDirectoryLocation($id){
        $address = DB::table('directory_location')->select('*')->where('directory_id',$id)
                     ->get()->toArray();
        return $address;
    }

    public static function getCatnameIdLangId($id,$language){
        $catData = DB::table('category_translations')->where('category_id',$id)->where('language_id',$language)->first();
        return $catData ? $catData->title : null;
    }
    public static function getProductCatnameIdLangId($id,$language){
        $catData = DB::table('product_category_translations')->where('product_category_id',$id)->where('language_id',$language)->first();
        return $catData ? $catData->title : null;
    }
    public static function getTagnameIdLangId($id,$language){
        $tagData = DB::table('tag_translations')->where('tag_id',$id)->where('language_id',$language)->first();
        return $tagData ? $tagData->title : null;
    }
    public static function getProductTagnameIdLangId($id,$language){
        $tagData = DB::table('tag_product_translations')->where('tag_id',$id)->where('language_id',$language)->first();
        return $tagData ? $tagData->title : null;
    }

    public static function getProductLangId($id,$language){
        $productContentShort = DB::table('product_translation')
                ->leftjoin('product', 'product.id', '=', 'product_translation.product_id')
                ->select('product_translation.short_content', 'product_translation.long_content', 'product_translation.name', 'product_translation.meta_title', 'product_translation.meta_description')
                ->where('product_translation.product_id', $id)
                ->where('product_translation.language_id', $language)->get();
        return $productContentShort;
    }

    public static function getDirectoryLocationID($id){
        $address = DB::table('directory_location')->select('*')->where('directory_id',$id)->first();
        return $address;
    }

    public static function getProductAttributes($id){
    $attributesData = ProductAttributes::leftjoin('attribute_statuses', 'attribute_statuses.attribute_id', '=', 'product_attributes.attribute_id')
                    ->leftjoin('attributes', 'attributes.id', '=', 'product_attributes.attribute_id')
                    ->select('product_attributes.*','attribute_statuses.status as attributeStatus')
                    ->where('product_attributes.attribute_value', '!=', null)->where('attributes.status', 1)->with('attribute')->where('product_id', '=', $id)->get();
        return $attributesData;
    }

    public static function getTypeCategory($id){
        $typeData = DB::table('category')->select('id','name')->where('id',$id)->get()->toArray();
        return $typeData;
    }

    public static function getDirectoryTypeGalleries($category_id,$block,$directoryid){
        $typeData = DB::table('directory_type_galleries')->select('id','image_name')->where('block_num',$block)->where('directory_id',$directoryid)->where('category_id',$category_id)->get()->toArray();
        return $typeData;
    }

    public static function getDirectoryTypePdf($category_id,$block,$directoryid){
        $typeData = DB::table('directory_type_pdfs')->select('id','pdf_name')->where('block_num',$block)->where('directory_id',$directoryid)->where('category_id',$category_id)->get()->toArray();
        return $typeData;
    }

    public static function getProductId($id){
        $typeData = Product::select('id','name')->where('id',$id)->first();
        return $typeData;
    }

    public static function getEventId($id){
        $typeData = DB::table('events')->select('id','title')->where('id',$id)->first();
        return $typeData;
    }

    public static function getBlogId($id){
        $typeData = DB::table('blog')->select('id','title')->where('id',$id)->first();
        return $typeData;
    }

    public static function getProductprice($id,$currencyCode){
        $price = DB::table('product_price')->select('*')->where('product_id', $id)->where('currency_code', $currencyCode)->get()->toArray();
        return $price;
    }
    public static function getProductpriceId($id,$currencyCode){
        $price = DB::table('product_price')->select('*')->where('product_id', $id)->where('currency_code', $currencyCode)->first();
        return $price;
    }
    public static function productCurrencyChange(){
        $baseCurrency = DB::table('currency')->select('*')->where('base_currency', 'yes')->get()->toArray();
        DB::table('product_price')->update(array('currency_base' => 'no'));
        DB::table('product_price')->where('currency_code', $baseCurrency[0]->code)->update(array('currency_base' => 'yes'));
        return $baseCurrency;
    }
    public static function defaultCurrencyData(){
        $baseCurrency = DB::table('currency')->select('*')->where('base_currency', 'yes')->get()->toArray();
        return $baseCurrency;
    }
    public static function checkCompanyPermissionById($company_id){
        $is_plan_subscriptions = DB::table('plan_subscriptions')->where('subscriber_id', $company_id)->first();
        if($is_plan_subscriptions){
            $plan_subscription_usage = DB::table('plan_subscription_usage')->select('name')->where('subscription_id', $is_plan_subscriptions->id)->get();
        }else{
            $plan_subscription_usage = [];
        }

        return $plan_subscription_usage;
    }

    public static function getCategoryType($id=0){
        $categoryTypeData = DB::table('category_type')
                        ->select('id','title')
                        ->where('id',$id)
                        ->get()->toArray();
        return $categoryTypeData;
    }

    public static function getSEO_URL($id=0){
        $categoryTypeData = DB::table('directory_basic_translation')->select('id','seo_url','directory_id')->where('directory_id',$id)->where('language_id','1')->first();
        return $categoryTypeData;
    }
    public static function getUserName($id){
        $fieldsData = User::where('id',$id)->get()->toArray();
        return $fieldsData;
    }

    public static function getUserNameDirId($id){
        $fieldsData = User::where('directory_id',$id)->first();
        return $fieldsData;
    }

    public static function getProductNamesData(){
        $namesData = Product::get()->toArray();
        return $namesData;
    }

    public static function getValidityOfOffer($id){
        $validityData = DB::table('offer')->select('*')->whereRaw('FIND_IN_SET("'.$id.'",offer_product_id)')->where('offer_expire_date',">=", date('y-m-d'))->where('deleted', '0')->get()->toArray();
        return $validityData;
    }

    public static function getNewOffer($id){
        $validityData = DB::table('offer')->select('*')->whereRaw('FIND_IN_SET("'.$id.'",offer_product_id)')->where('discount_selectedvalue', 'new')->where('offer_expire_date',">=", date('y-m-d'))->where('deleted', '0')->count();
        return $validityData;
    }

    public static function getProductOffers($id){
        $validityData = DB::table('offer')->select('*')->whereRaw('FIND_IN_SET("'.$id.'",offer_product_id)')->where('discount_selectedvalue', '!=', 'new')->where('offer_expire_date',">=", date('y-m-d'))->where('deleted', '0')->count();
        return $validityData;
    }

    public static function getNewShopping($id){
        $Data = ShoppingCard::whereRaw('FIND_IN_SET("'.$id.'",product_id)')->where('expire_date',">=", date('y-m-d'))->where('discount_selectedvalue', 'new')->where('deleted', '0')->count();
        return $Data;
    }

    public static function getShoppingCard($id){
        $Data = ShoppingCard::find($id);
        return $Data;
    }

    public static function getNewShoppingOffer($id){
        $Data = ShoppingCard::whereRaw('FIND_IN_SET("'.$id.'",product_id)')->where('expire_date',">=", date('y-m-d'))->where('discount_selectedvalue', '!=', 'new')->where('deleted', '0')->count();
        return $Data;
    }

    public static function getValidityOfShoppingCardOffer($id){
        $validityData = DB::table('shopping_card')->select('*')->whereRaw('FIND_IN_SET("'.$id.'",product_id)')->where('expire_date',">=", date('y-m-d'))->where('deleted', '0')->get()->toArray();
        return $validityData;
    }

    public static function getDirectoryScheduleIDday($id, $day){
        $DirectorySchedual = DirectorySchedual::where('directory_id',$id)->where('day',$day)->first();
        return  $DirectorySchedual;
    }

    public static function getFreeProductDirectoryId($id){

        $FreeProductsDiscount = FreeProductsDiscount::where('id',$id)->first();
        if(!empty($FreeProductsDiscount->product_ids)){
            $product_ids = explode(',',$FreeProductsDiscount->product_ids);
        }else{
            $product_ids = [];
        }

        $validityData = [];
        foreach($product_ids as $productid){
            $validityData[] = Product::where('id',$productid)->where('deleted', '0')->get();
        }
        return [$FreeProductsDiscount, $validityData];

    }

    public static function getCountRelatedDirectory($id, $latitude, $longitude){
        //$countData = DB::table('directory_category')->select('*')->where('category_id',$id)->get();
        $distance_default = Distancesetting::where('status', 'Active')->where('default_distance', 'yes')->orderBy('id', 'asc')->first();
        $current_day = date('l', strtotime(date("y-m-d")));
        $data['countData'] = DB::table('directory as dt')
                            ->select(
                                'dt.*',
                                'dt_cat.category_id',
                                'directory_location.latitude',
                                'directory_location.longitude',
                                DB::raw("6371 * acos(cos(radians(" . $latitude . "))
                                        * cos(radians(latitude)) * cos(radians(longitude) - radians(" . $longitude . "))
                                        + sin(radians(" . $latitude . ")) * sin(radians(latitude))) AS distance"),
                            )
                            ->leftjoin('directory_location', 'dt.id', '=', 'directory_location.directory_id')
                            ->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id')->Where('dt_cat.category_id',  $id)
                            ->leftjoin('directory_basic_translation as d_tr', 'dt.id', '=', 'd_tr.directory_id')
                            ->join('directory_schedule as ds', 'dt.id', '=', 'ds.directory_id');
        if (session('distance')) {
            $data['countData']      =     $data['countData']->having('distance', '<', session('distance'));
        } else {
            $data['countData']      =     $data['countData']->having('distance', '<', $distance_default->to_distance ?? '');
        }
        $data['countData'] =    $data['countData']->Where('ds.day', strtolower($current_day))->groupBy('dt.id')->orderBy('dt.id', 'desc');
        $countData =    $data['countData']->count();

        return $countData;
    }

    public static function getOrderItem($id){
        $fieldsData = DB::table('order_item')->select('*')->where('order_id',$id)->get();
        return $fieldsData;
    }

    public static function formatCurrency($order, $amount)
    {
        $symbol = $order->currency_symbol ?: $order->currency;
        // Clean value
        $amount = str_replace(',', '', $amount);
        $amount = is_numeric($amount) ? (float) $amount : 0;
        return $symbol . ' ' . number_format($amount, 2, '.', '');
    }

    public static function numberCurrencyformat($amount): string
    {
        if (!is_numeric($amount)) {
            return '0.00';
        }
        return number_format((float) str_replace(',', '', $amount), 2, '.', ',');
    }

    public static function numberformat($amount): string
    {
        $amount = str_replace(',', '', $amount);
        if (!is_numeric($amount)) {
            return '0';
        }
        $amount = (float) $amount;
        return ($amount == floor($amount))
            ? number_format($amount, 0, '.', ',')
            : number_format($amount, 2, '.', ',');
    }

    public static function OrderItemId($id){
        $fieldsData = DB::table('order_item')->select('*')->where('order_id',$id)->first();
        return $fieldsData;
    }

    public static function getCat_by_directoryId($id){
        $fieldsDatacategory = DB::table('directory_category')->select('*')->where('directory_id',$id)->first();
        $categoryParentData = DB::table('category')->where('id',$fieldsDatacategory->category_id)->select('id','name')->where('parent_id',0)->where('status','Active')->where('deleted','0')->orderby('sort_value','asc')->first();
        return $categoryParentData->name??'N/A';
    }

    public static function getUserByDirectoryId($directory_id){

        $fieldsData = User::where('parent_id',0)->where('directory_id',$directory_id)->first();
        return $fieldsData;
    }

    public static function getExistingCatByProduct($category_id){
        $fieldsData = Product::where('cat_id',$category_id)->count();
        return $fieldsData;
    }

    public static function getExistingSubCatByProduct($sub_category_id){
        $fieldsData = DB::table('product_categories')->where('category_id',$sub_category_id)->count();
        return $fieldsData;
    }

    public static function getExistingCatByDirectory($category_id){
        $fieldsData = DB::table('directory_category')->where('category_id',$category_id)->count();
        return $fieldsData;
    }
    public static function getExistingSubCatByDirectory($sub_category_id){
        $fieldsData = DB::table('directory_sub_category')->where('sub_category_id',$sub_category_id)->count();
        return $fieldsData;
    }

    public static function getOrderImage($id){
        $fieldsData = DB::table('product_images')->select('*')->where('main_image','main')->where('product_id',$id)->get();
        return $fieldsData;
    }
    public static function getTrademarkImage($id){
        $thumbimage = DB::table('trademarks')->select('*')->where('id', $id)->get()->toArray();
        //echo "<pre>"; print_r($thumbimage);die;
        return $thumbimage;
    }
    public static function taxValueData(){
        $taxValueData = DB::table('frontend_setting')->select('*')->get()->toArray();
        return $taxValueData;
    }

    public static function getDirectoryTags($tag_id){
        $tagsData = DB::table('category_tags')
        ->select('*')
        ->where('id',$tag_id)
        ->orderBy('tags', 'ASC')->get()->toArray();
        return $tagsData;
    }

    public static function getDirectoryTagsDirId($dir_id,$dir_cat_id,$language_id){
        $tagsData = DB::table('directory_tags')->where('directory_id',$dir_id)->first();
            if ($tagsData) {
            //$tagTranslationsData = DB::table('tag_translations')->where('tag_id',$tagsData->tags_id)->where('category_id',$dir_cat_id)->where('language_id',$language_id)->get();
            $tagTranslationsData = DB::table('tag_translations')->where('category_id',$dir_cat_id)->where('language_id',$language_id)->get();
                return $tagTranslationsData;
            }else{
                return null;
            }
    }

    public static function getProductTags($id){
        $tagsData = DB::table('product_tags')->select('*')->where('id',$id)->orderBy('tags', 'ASC')->get()->toArray();
        return $tagsData;
    }
    public static function getProductSingleTags($id){
        $tagsData = DB::table('product_tags')->where('id',$id)->first();
        return $tagsData;
    }
    public static function getProductSingleTagsByName($tag){
        $language_id = Helper::getselectedLanguage();
        //$tagsData = DB::table('product_tags')->where('tags',$tag)->first();
        $tagsdirectorydata          = DB::table('product_tags')->select('product_tags.*', 'tag_trans.title')
                                        //->leftjoin('category_tags as cat_tags', 'product_tags.tags_id', '=', 'cat_tags.id')
                                        ->leftjoin('tag_product_translations as tag_trans', 'tag_trans.tag_id', '=', 'product_tags.id');
                $tagsData           = $tagsdirectorydata->where('tag_trans.language_id', $language_id)->where('tag_trans.title',$tag)->first();
        return $tagsData;
    }
    public static function getDirectoryOwnTags($owntag_id){
        $owntagsData = DB::table('category_own_tags')->select('*')->where('id',$owntag_id)->get()->toArray();
        return $owntagsData;
    }
    public static function getTotalOrders(){
        $owntagsData = DB::table('order')->select('*')->count();
        return $owntagsData;
    }
    public static function getTotalBookings(){
        $owntagsData = DB::table('appointment_booking')->count();
        return $owntagsData;
    }
    public static function getTotalProducts(){
        $owntagsData = Product::count();
        return $owntagsData;
    }
    public static function getamenityCategoryData(){
        $amenityCategoryData = AmenityCategory::query()->where('status','Active')->where('deleted', '0')->orderBy('id', 'asc')->get()->toArray();

        return $amenityCategoryData;
    }
    public static function getTrademarkCategory(){
        $trademarkCategory = TrademarkCategory::query()->where('status','Active')->where('deleted', '0')->orderBy('id', 'desc')->get()->toArray();

        return $trademarkCategory;
    }

    public static function search_visitors($type, $module_id){
        if (!self::cookieConsentGranted('analytics')) {
            return false;
        }


        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        if($ip=='127.0.0.1' || $ip=='::1' || $ip=='localhost' ){
            $ip_add = '127.0.0.12';
        }else{
            $ip_add = $ip;
        }
        $uniquevisitor = SearchCounter::where([['visiters', $ip_add], ['type', $type], ['module_id', $module_id] ])->first();

        if (!$uniquevisitor && $type === 'product') {
            $uniquevisitor_product = Product::find($module_id);
            if ($uniquevisitor_product) {
                $uniquevisitor_product->u_ip = $ip_add;
                $uniquevisitor_product->increment('search_count');
            }
            $counter = new SearchCounter;
            $counter->visiters = $ip_add;
            $counter->type = $type;
            $counter->module_id = $module_id;
            $counter->save();
        }else if (!$uniquevisitor && $type === 'company') {
            $uniquevisitor_dir = Diectory::find($module_id);
            if ($uniquevisitor_dir) {
                $uniquevisitor_dir->u_ip = $ip_add;
                $uniquevisitor_dir->increment('search_count');
            }
            $counter = new SearchCounter;
            $counter->visiters = $ip_add;
            $counter->type = $type;
            $counter->module_id = $module_id;
            $counter->save();
        }
    }

    public static function visitors($type, $module_id) {
        if (!self::cookieConsentGranted('analytics')) {
            return false;
        }

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip == '127.0.0.1' || $ip == 'localhost') {
            $countryCode = 'IN';
            $state = 'National Capital Territory of Delhi';
            $state_array = explode(" ", $state);
            $city = 'Delhi';
        } else {
            $geoData = @file_get_contents("http://www.geoplugin.net/php.gp?ip=$ip");
            if ($geoData !== false) {
                $geo = @unserialize($geoData);
                $countryCode = $geo["geoplugin_countryCode"] ?? '';
                $state = $geo["geoplugin_regionName"] ?? '';
                $city = $geo["geoplugin_city"] ?? '';
            } else {
                // Fallback if geoplugin request fails
                $countryCode = 'Unknown';
                $state = 'Unknown';
                $city = 'Unknown';
            }

            $state_array = explode(" ", $state);
        }

        if (in_array("Capital", $state_array)) {
            $state = $city;
        }

        $uniquevisitor = Counter::where('visiters', $ip)
            ->where('type', $type)
            ->where('module_id', $module_id)
            ->first();

        if (!$uniquevisitor) {
            $counter = new Counter;
            $counter->visiters = $ip;
            $counter->type = $type;
            $counter->module_id = $module_id;
            $counter->country_code = $countryCode;
            $counter->state = $state;
            $counter->city = $city;
            return $counter->save();
        }
    }


    public static function countCustomerClubsVisitors($module_id) {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $uniquevisitor = CountCustomerClub::where('visiters', $ip)->where('module_id', $module_id)->first();
        if (!$uniquevisitor) {
            $counter = new CountCustomerClub;
            $counter->visiters = $ip;
            $counter->module_id = $module_id;
            return $counter->save();
        }
    }

    public static function userCityCountryName(){

        $ip = request()->header('CF-Connecting-IP') ?? request()->ip();
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            $ip = '49.37.11.88';
        }
        $location = Location::get($ip);
        if(session()->get('filter_city')){
            $city = session()->get('filter_city');
            $country = session()->get('getcountryname')??'';

            $url_city_name = session()->get('url_city_name');
                if ($url_city_name) {
                    $country = City::with('state.country')->where('name', $url_city_name)->first()->state->country->name ?? '';
                }else{
                    $country = $location->countryName ?? 'Sweden';
                }

        } else {
            if(!$location || !$location->cityName || !$location->countryName) {
                $city = 'Varberg';
                $country = 'Sweden';
            } else {
                $city = $location->cityName ?? 'Varberg';
                $url_city_name = session()->get('url_city_name');
                if ($url_city_name) {
                    $country = City::with('state.country')->where('name', $url_city_name)->first()->state->country->name ?? '';
                }else{
                    $country = $location->countryName ?? 'Sweden';
                }

            }

        }

        return ['country' => $country, 'city' => $city];
    }

    public static function CountVisitorsId($type, $module_id){
        $uniquevisitor = Counter::where('type',$type)->where('module_id',$module_id)->get();
        return $uniquevisitor;
    }
    public static function CountLikeData($type, $module_id){
        $likeUnique = LikeCounters::where('type',$type)->where('module_id',$module_id)->get();
        return $likeUnique;
    }

    public static function notification(){
        $claimcount = DB::table('claim')->where('status','1')->count();
        return $claimcount;
    }

    public static function affiliate_notification(){
        $affiliatecount = DB::table('affiliates')->where('status','pending')->count();
        return $affiliatecount;
    }

    public static function trademarkNotification(){
        $data = Trademarks::where('status',0)->where('deleted','0')->count();
        return $data;
    }
    public static function getchildCategory($cat_id){
        $categoryChildData = DB::table('category')
                        ->select('id','name')
                        ->where('parent_id',$cat_id)->where('status','Active')->where('deleted','0')
                        ->orderBy('name','ASC')
                        ->get()->toArray();
        return $categoryChildData;
    }
    public static function getListingTags($cat_child_id){
        $listingTagsData = DB::table('category_tags')->select('id','tags','child_cat_id')->where('child_cat_id',$cat_child_id)->where('status','Active')->groupBy('tags')->orderBy('tags', 'ASC')->get()->toArray();
        return $listingTagsData;
    }

    public static function getListingTagsArray($subcatids){
        $listingTagsData = DB::table('category_tags')->select('id','tags','child_cat_id')->whereIn('child_cat_id',$subcatids)->where('status','Active')->orderBy('tags', 'ASC')->get()->toArray();
        return $listingTagsData;
    }

    public static function getTagsSubcatid($subcatids){
        $dirTagsData = DB::table('category_tags')->select('id','tags','child_cat_id')->where('child_cat_id',$subcatids)->where('status','Active')->orderBy('tags', 'ASC')->get()->toArray();
        return $dirTagsData;
    }

    public static function getUserdetailsLocation($id){
        $fieldsData = User::where('id',$id)->first();
        return $fieldsData;
    }
    public static function getProductchildCategory($cat_id){
        $categoryChildData = DB::table('product_category')
                        ->select('id','name','parent_id')
                        ->where('parent_id',$cat_id)->where('status','Active')->where('deleted','0')
                        ->orderBy('name', 'ASC')->get()->toArray();
        return $categoryChildData;
    }
    public static function getAllProductTagsListing($cat_child_id){
        $listingTagsData = DB::table('product_tags')->select('id','tags','child_cat_id')->where('child_cat_id',$cat_child_id)->where('status','Active')->orderBy('tags', 'ASC')->get();
        return $listingTagsData;
    }

    public static function getAllProductTagsListingArray($cat_child_id){
        $listingTagsData = DB::table('product_tags')->select('id','tags','child_cat_id')->whereIn('child_cat_id',$cat_child_id)->where('status','Active')->orderBy('tags', 'ASC')->get();

        return $listingTagsData;
    }

    // for fetching shipping and Flat value
    public static function getAdminFlatShippingData(){
        $adminFlatShippingData = Frontendsetting::select('flat_rate', 'shipping_value')->first();
        return $adminFlatShippingData;
    }

    //for fetching country name
    public static function getCountryName($id){
        $countryName = Country::select('id', 'name')->where('id',$id)->first();
        return $countryName;
    }
    //for fetching state name
    public static function getStateName($id){
        $stateName = State::select('id', 'name')->where('id',$id)->first();
        return $stateName;
    }
    //for fetching city name
    public static function getCityName($id){
        $cityName = City::select('id', 'name')->where('id',$id)->first();
        return $cityName;
    }

    public static function getCountryByCityName($name){
        return City::with('state.country')->where('name', $name)->first()->state->country->name ?? '';
    }

    public static function getDirectorySeoSlug($id){
        $data = DB::table('directory')
                        ->select('slug','title')
                        ->where('id',$id)
                        ->first();
        return $data;
    }

    public static function showImportFile(){
        $userImportdata = User::where('directory_id', auth()->user()->directory_id ?? 0)->where('show_import_file','1')->first();
        return $userImportdata;
    }

    public static function totalAdminOrders()
    {
        $totalOrders = Order::where('deleted_at', NULL)->count();
        return $totalOrders;
    }

    public static function totalTrashOrders()
    {
        $totalTrashOrders = Order::onlyTrashed()->count();
        return $totalTrashOrders;
    }

    public static function totalPendingOrders()
    {
        $totalPendingOrders = Order::where('deleted_at', NULL)->where('status', '1')->count();
        return $totalPendingOrders;
    }

    public static function totalProcessingOrders()
    {
        $totalProcessingOrders = Order::where('deleted_at', NULL)->where('status', '2')->count();
        return $totalProcessingOrders;
    }

    public static function totalPaymentCancelOrders()
    {
        $totalPaymentCancelOrders = Order::where('deleted_at', NULL)->where('status', '4')->count();
        return $totalPaymentCancelOrders;
    }

    public static function totalDeliveredOrders()
    {
        $totalDeliveredOrders = Order::where('deleted_at', NULL)->where('status', '5')->count();
        return $totalDeliveredOrders;
    }

    public static function totalRtoOrders()
    {
        $totalRtoOrders = Order::where('deleted_at', NULL)->where('status', '6')->count();
        return $totalRtoOrders;
    }

    public static function totalRefundedOrders()
    {
        $totalRefundedOrders = Order::where('deleted_at', NULL)->where('status', '7')->count();
        return $totalRefundedOrders;
    }

    public static function getDirectoryTrademarks(){

        $trademarksData = DB::table('trademarks')
            ->where('deleted', '0')
            ->where(function ($query) {
                $query->whereExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                        ->from('plan_subscriptions')
                        ->whereColumn('plan_subscriptions.subscriber_id', 'trademarks.directory_id')
                        ->where('plan_subscriptions.slug', 'premium');
                })
                ->orWhere('status', '1');  // Apply status condition only if plan_subscriptions does not exist
            })
            ->get();

        return $trademarksData;
    }

    public static function getTotalVistitors(){
        $blogvisitorData = Counter::where('type','website')->count();
        return $blogvisitorData;
    }
    public static function getTotalVistitorsByType($type){
        $visitorData = Counter::where('type', $type)->count();
        return $visitorData;
    }

    public static function getVendorVistitors($id){
        $vendorvisitorData = DB::table('counters')
                            ->leftjoin('directory', 'directory.id', '=', 'counters.module_id')->where('counters.type','directory')->Where('directory.id',  $id)->count();
        return $vendorvisitorData;
    }
    public static function getTotalVendorEventsVistitors($id){
        $vendorenentsvisitorData = DB::table('counters')
                                ->leftjoin('events', 'events.id', '=', 'counters.module_id')->where('counters.type','event')->Where('events.directory_id',  $id)->count();
        return $vendorenentsvisitorData;
    }
    public static function getTotalVendorShoppingcardVistitors($id){
        $vendorshoppingcardvisitorData = DB::table('counters')
                                    ->leftjoin('shopping_card', 'shopping_card.id', '=', 'counters.module_id')->where('counters.type','shoppingcard')->Where('shopping_card.directory_link',  $id)->count();
        return $vendorshoppingcardvisitorData;
    }
    public static function getTotalVendorOfferVistitors($id){
        $vendoroffervisitorData = DB::table('counters')
                                    ->leftjoin('offer', 'offer.id', '=', 'counters.module_id')->where('counters.type','offer')->Where('offer.offer_directory_link',  $id)->count();
        return $vendoroffervisitorData;
    }
    public static function getTotalVendorProductVistitors($id){
        $vendorproductvisitorData = DB::table('counters')
                                    ->leftjoin('product', 'product.id', '=', 'counters.module_id')->where('counters.type','product')->Where('product.directory_id',  $id)->count();
        return $vendorproductvisitorData;
    }
    public static function getTotalVendorBlogVistitors($id){
        $vendorblogvisitorData = DB::table('counters')
                                ->leftjoin('blog', 'blog.id', '=', 'counters.module_id')->where('counters.type','blog')->Where('blog.directory_id',  $id)->count();
        return $vendorblogvisitorData;
    }


    public static function getTotalLikesByType($type){
        $likesData = LikeCounters::where('type', $type)->count();
        return $likesData;
    }

    public static function getVendorLikes($id){
        $vendorlikesData = DB::table('counters_like')
                            ->leftjoin('directory', 'directory.id', '=', 'counters_like.module_id')->where('counters_like.type','directory')->Where('directory.id',  $id)->count();
        return $vendorlikesData;
    }
    public static function getTotalVendorEventsLikes($id){
        $vendorenentslikesData = DB::table('counters_like')
                                ->leftjoin('events', 'events.id', '=', 'counters_like.module_id')->where('counters_like.type','event')->Where('events.directory_id',  $id)->count();
        return $vendorenentslikesData;
    }
    public static function getTotalVendorShoppingcardLikes($id){
        $vendorshoppingcardlikesData = DB::table('counters_like')
                                    ->leftjoin('shopping_card', 'shopping_card.id', '=', 'counters_like.module_id')->where('counters_like.type','Shoppingcard')->Where('shopping_card.directory_link',  $id)->count();
        return $vendorshoppingcardlikesData;
    }
    public static function getTotalVendorOfferLikes($id){
        $vendorofferlikesData = DB::table('counters_like')
                                    ->leftjoin('offer', 'offer.id', '=', 'counters_like.module_id')->where('counters_like.type','product_offer')->Where('offer.offer_directory_link',  $id)->count();
        return $vendorofferlikesData;
    }
    public static function getTotalVendorProductLikes($id){
        $vendorproductlikesData = DB::table('counters_like')
                                    ->leftjoin('product', 'product.id', '=', 'counters_like.module_id')->where('counters_like.type','product')->Where('product.directory_id',  $id)->count();
        return $vendorproductlikesData;
    }
    public static function getTotalVendorBlogLikes($id){
        $vendorbloglikesData = DB::table('counters_like')
                                ->leftjoin('blog', 'blog.id', '=', 'counters_like.module_id')->where('counters_like.type','blog')->Where('blog.directory_id',  $id)->count();
        return $vendorbloglikesData;
    }
    public static function getProductSeoSlug($id){
        $data = DB::table('product')
                        ->select('seo_url','name')
                        ->where('id',$id)
                        ->first();
        return $data;
    }
    public static function countwebsite(){
        if (!self::cookieConsentGranted('analytics')) {
            return;
        }

        $value = Cookie::get('countSiteOpens');
        $countSiteOpens = $value+1;
        Cookie::queue('countSiteOpens', $countSiteOpens, 60);
        //sreturn $data;
    }

    public static function userCurrentLocationDetailsOnIpAddress($userip){
        // if (!self::cookieConsentGranted('functional')) {
        //     return [
        //         'country' => 'Sweden',
        //         'countryCode' => 'SE',
        //         'timezone' => 'Europe/Stockholm',
        //         'lat' => '57.107118',
        //         'lon' => '12.252091',
        //         'geoplugin_latitude' => '57.107118',
        //         'geoplugin_longitude' => '12.252091',
        //     ];
        // }

        try {
            $geoData = @file_get_contents("http://ip-api.com/php/$userip");
            if ($geoData === false) {
                throw new Exception("Unable to fetch data from GeoPlugin API.");
            }

            $geo = @unserialize($geoData);
            if (!is_array($geo) || empty($geo['lat']) || empty($geo['lon'])) {
                throw new Exception("Invalid response from GeoPlugin API.");
            }
            return $geo;
        } catch (Exception $e) {
            error_log("Error fetching user location details: " . $e->getMessage());
            return [
                'lat'  => '57.107118', // fallback
                'lon' => '12.252091',
            ];
        }
    }

    public static function userCurrentLocationDetailsOnIpAddress_bkp($userip){
        try {
            $geoData = @file_get_contents("http://www.geoplugin.net/php.gp?ip=$userip");
            if ($geoData === false) {
                throw new Exception("Unable to fetch data from GeoPlugin API.");
            }

            $geo = @unserialize($geoData);
            if (!is_array($geo) || empty($geo['geoplugin_latitude']) || empty($geo['geoplugin_longitude'])) {
                throw new Exception("Invalid response from GeoPlugin API.");
            }

            return $geo;
        } catch (Exception $e) {
            error_log("Error fetching user location details: " . $e->getMessage());
            return [
                'geoplugin_latitude'  => '28.6542', // fallback
                'geoplugin_longitude' => '77.2373',
            ];
        }
    }

    public static function categorySelectedId($categoryName){
        $catid = Category::where('deleted', '0')->where('slug', $categoryName)->first();
        return $catid;
    }

    public static function procategorySelectedId($categoryName){
        $catid = DB::table('product_category')->where('deleted', '0')->where('slug', $categoryName)->first();
        return $catid;
    }

}
?>
