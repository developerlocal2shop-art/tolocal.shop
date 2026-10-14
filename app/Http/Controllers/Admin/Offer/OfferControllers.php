<?php

namespace App\Http\Controllers\Admin\Offer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Offer;
use App\Models\OfferImage;
use DB;
use App\Models\Product;
use App\Models\SeoTranslation;
use App\Models\OfferTranslation;
use App\Models\DirectorySeo;
use App\Models\Productcategory;
use App\Models\ProductTags;
use App\Models\DiectoryLocation;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class OfferControllers extends Controller
{
    public function index(Request $request)
    {
        $offerdataCount = Offer::query()->where('deleted', '0')->get()->count();
        $currentdate = strftime('%F');
        $totalOngoingOfferCount = Offer::query()->where('deleted', '0')->where('offer_active_date', '<=', $currentdate)->where('offer_expire_date', '>=', $currentdate)->get()->count();
        $totalExpiredOfferCount = Offer::query()->where('deleted', '0')->where('offer_expire_date', '<=', $currentdate)->get()->count();
        $totalUpcomingOffer = Offer::query()->where('deleted', '0')->where('offer_active_date', '>', $currentdate)->get()->count();
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $directory_id = $request->input('directory_id');
            if ($directory_id != "") {
                $data['offer'] = DB::table('offer')->select('*')
                    ->where('title', 'like', '%' . $searchQuery . '%')
                    ->Where('directory_id',  $directory_id);
            } else {
                $data['offer'] = DB::table('offer')->select('*')
                    ->where('title', 'like', '%' . $searchQuery . '%');
            }
            $offerdata =    $data['offer']->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.offer.index', ['offerdata' => $offerdata, 'textsearch' => $searchtext, 'query' => $query, 'offerdataCount' => $offerdataCount, 'totalOngoingOfferCount' => $totalOngoingOfferCount, 'totalExpiredOfferCount' => $totalExpiredOfferCount, 'totalUpcomingOffer' => $totalUpcomingOffer]);
        } else {
            $offerdata = Offer::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";

            return view('admin.offer.index', ['offerdata' => $offerdata, 'textsearch' => $searchtext, 'offerdataCount' => $offerdataCount, 'totalOngoingOfferCount' => $totalOngoingOfferCount, 'totalExpiredOfferCount' => $totalExpiredOfferCount, 'totalUpcomingOffer' => $totalUpcomingOffer]);
        }
    }
    public function create()
    {
        // $data['parent_category']        = Helper::getAllParentCategory();       /* get Category */
        // $data['child_category']         = Helper::getAllChildCategory();       /* get Child Category */
        // $data['alltags']                = Helper::getTagsData();       /* get Tags Category */
        $data['parent_category']            = Helper::getAllParentProductCategory();      /* get product Category */
        $data['productsubcat']              = Helper::getProductCatgoeryData();                  /* get Child Category */
        $data['tags']                       = Helper::getProductTagsData();                /* get tags */
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        return view('admin.offer.create', ['data' => $data]);
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'directoryId'                   => 'required',
            'productId'                     => 'required',
            'offer_title_en'                => 'required|max:40',
            'offer_description_en'          => 'required|max:500',
            'offer_gallery_image'           => 'required|array|max:20',
            'offer_banner_image'            => 'required',
            'offer_discount'                => 'required',
            'active_date'                   => 'required',
            'expiry_date'                   => 'required|after_or_equal:active_date',
            'video' => 'nullable|file|max:5120', // 5120 KB = 5 MB
        ], [
            'video.max' => 'The video may not be greater than 5 MB.',
        ]);

        $latitude       = $request->input('lati');
        $longitude      = $request->input('long');
        $dirId          = $request->input('directoryId'); 
        $dirlocationdetails = DiectoryLocation::where('directory_id', $dirId)->first();
        if (!empty($dirlocationdetails)) {
            if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                $latitude = $dirlocationdetails['latitude'];
                $longitude = $dirlocationdetails['longitude'];
            }
        }
        $name_en = $request->input('offer_title_en');
        $seo_en = $request->input('seo_en');
        $productvalidid = array();
        foreach ($request->input('productId') as $pcatrow) {
            $productvalidid[] = $pcatrow;
        }
        $proID = implode(",", $productvalidid);
        $resultid = array();
        $pro_id = explode(",", $proID);
        foreach ($pro_id as $id) {
            $resultid[$id] = count(Helper::getValidityOfOffer($id));
        }
        $existOffer = "";
        foreach ($resultid as $row) {
            if ($row > 0) {
                $existOffer = "yes";
                break;
            } else {
                $existOffer = "no";
            }
        }
        if ($existOffer != 'yes') {
            $offerinsert = new Offer;
            //die;
            $offerinsert->title                              = (isset($name_en)) ? $name_en : "";
            $offerinsert->slug                               = $seo_en;
            $offerinsert->description                        = $request->input('offer_description_en');
            $offerinsert->discounttype                       = $request->input('discount_type');
            $offerinsert->discount_selectedvalue             = $request->input('offer_discount');
            $offerinsert->latitude                           = $latitude;
            $offerinsert->longitude                          = $longitude;

            if ($request->input('original_price')) {
                $original_price = $request->input('original_price');
            } else {
                $original_price = 0;
            }
            if ($request->input('price_after_discount')) {
                $price_after_discount = $request->input('price_after_discount');
            } else {
                $price_after_discount = 0;
            }
            if ($request->input('price_dis')) {
                $price_dis = $request->input('price_dis');
            } else {
                $price_dis = 0;
            }
            if ($request->input('dis_per')) {
                $dis_per = $request->input('dis_per');
            } else {
                $dis_per = 0;
            }
            if ($request->input('spent')) {
                $spent = $request->input('spent');
            } else {
                $spent = 0;
            }
            if ($request->input('customer_spending')) {
                $customer_spending = $request->input('customer_spending');
            } else {
                $customer_spending = 0;
            }
            if ($request->input('many_buy')) {
                $many_buy = $request->input('many_buy');
            } else {
                $many_buy = 0;
            }
            if ($request->input('many_pay')) {
                $many_pay = $request->input('many_pay');
            } else {
                $many_pay = 0;
            }

            if ($request->input('offer_discount') == "pricereduction") {
                $offerinsert->discount_firstvalue               = $original_price;
                $offerinsert->discount_secondvalue              = $price_after_discount;
            }
            if ($request->input('offer_discount') == "pricediscount") {
                $offerinsert->discount_firstvalue               = $price_dis;
            }
            if ($request->input('offer_discount') == "percentagediscount") {
                $offerinsert->discount_firstvalue               = $dis_per;
            }
            if ($request->input('offer_discount') == "valuediscount") {
                $offerinsert->discount_firstvalue               = $spent;
                $offerinsert->discount_secondvalue              = $customer_spending;
            }
            if ($request->input('offer_discount') == "quantitativediscount") {
                $offerinsert->discount_firstvalue               = $many_buy;
                $offerinsert->discount_secondvalue              = $many_pay;
            }

            $offerinsert->offer_type_selectedvalue          = 'product';
            if ($request->input('productId')) {
                $productid = array();
                foreach ($request->input('productId') as $pcatrow) {
                    $productid[] = $pcatrow;
                }
                $offerinsert->offer_product_id            =  implode(",", $productid);
            }
            $offerinsert->offer_directory_link              = $request->input('directoryId');

            // if ($request->input('parentcategory')) {
            //     $parentCategory = array();
            //     foreach ($request->input('parentcategory') as $pcatrow) {
            //         $parentCategory[] = $pcatrow;
            //     }
            //     $offerinsert->search_parent_category            =  implode(",", $parentCategory);    //$request->input('parentcategory');
            // }

            $offerinsert->search_parent_category                   = $request->input('parentcategory');

            if ($request->input('childcategory')) {
                $childCategory = array();
                foreach ($request->input('childcategory') as $childcatrow) {
                    $childCategory[] = $childcatrow;
                }
                $offerinsert->search_child_category             =  implode(",", $childCategory);    //$request->input('childcategory');
            }
            if ($request->input('offertags')) {
                $offerTags = array();
                foreach ($request->input('offertags') as $tagsrow) {
                    $offerTags[] = $tagsrow;
                }
                $offerinsert->tags                              =  implode(",", $offerTags);    //$request->input('offertags');
            }

            $videofile = $request->file('video');
            if ($videofile) {
                $name2 = $videofile->getClientOriginalName();
                $videoName = time() . $name2;
                $videofile->move(public_path('files/offer/'), $videoName);
                $offerinsert->video = $videoName;
            }

            $startdate = $request->input('active_date');
            $expiredate = $request->input('expiry_date');
            $offerinsert->offer_active_date                 = isset($startdate) ? $startdate : date("Y-m-d");
            $offerinsert->offer_expire_date                 = isset($expiredate) ? $expiredate : date("Y-m-d");
            $offerinsert->default_duration                  = $request->input('default_status');
            if ($request->input('default_status') == 'yes') {
                $offerinsert->default_month                 = $request->input('set_months');
            }
            $offerinsert->status                            = $request->input('offer_status');
            $offerinsert->created_by                        = $request->input('createdby');
            $offerinsert->save();
            $offer_id = $offerinsert->id;

            $product_id = $request->input('productId');
            if (!empty($product_id)) {
                foreach ($pro_id as $id) {
                    DB::table('product')->where('id', $id)->update(array('offer_id' => $offer_id));
                    $this->ProductDiscountChange($id, $price_dis, $dis_per, $request->input('offer_discount'));
                }
            }
            $imgbanner = $request->file('offer_banner_image');
            if ($imgbanner) {
                $ext = $imgbanner->getClientOriginalExtension();
                $name2 = $imgbanner->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                /*$imagepath = 'files/offer/' . $imageName;
                $image_resize = Image::make($imgbanner->getRealPath());
                $image_resize->save(public_path('files/offer/' . $imageName));
                */

                    $imagepath = 'images/offer/' . $imageName;
                    $convertext = "webp";        
                    $image_resize = Image::make($imgbanner->getRealPath())->stream($convertext,50);        
                    Storage::disk('images')->put('offer/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                    = new OfferImage;
                $productmainimage_insert->offer_id          = $offer_id;
                $productmainimage_insert->main_image        = "banner";
                $productmainimage_insert->name_thumbnail    = $imageName;
                $productmainimage_insert->name_large        = $imageName;
                $productmainimage_insert->extension         = $ext;
                $productmainimage_insert->path              = $imagepath;
                $productmainimage_insert->save();
            }

            $imgfile = $request->file('offer_image');
            if ($imgfile) {
                $ext = $imgfile->getClientOriginalExtension();
                $name2 = $imgfile->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                /*
                $imagepath = 'files/offer/' . $imageName;
                $image_resize = Image::make($imgfile->getRealPath());
                $image_resize->save(public_path('files/offer/' . $imageName));
                */

                    $imagepath = 'images/offer/' . $imageName;
                    $convertext = "webp";        
                    $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);        
                    Storage::disk('images')->put('offer/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                    = new OfferImage;
                $productmainimage_insert->offer_id          = $offer_id;
                $productmainimage_insert->main_image        = "main";
                $productmainimage_insert->name_thumbnail    = $imageName;
                $productmainimage_insert->name_large        = $imageName;
                $productmainimage_insert->extension         = $ext;
                $productmainimage_insert->path              = $imagepath;
                $productmainimage_insert->save();
            }

            $languages = Helper::getAllLanguages();
            foreach ($languages as $lang) {
                $offer_title = $request->input('offer_title_' . $lang->code);
                $seo_title = $request->input('meta_title_' . $lang->code);
                $seo_meta_description = $request->input('meta_description_' . $lang->code);
                $dircttrans = new SeoTranslation();
                $dircttrans->module_id  = $offer_id;
                $dircttrans->language_id  = $lang->id;
                $dircttrans->meta_title        = isset($seo_title) ? $seo_title : "";
                $dircttrans->meta_keyword        = isset($offer_title) ? $offer_title : "";
                $dircttrans->meta_description  = isset($seo_meta_description) ? $seo_meta_description : "";
                $dircttrans->module_name       = 'Offer';
                $dircttrans->save();
            }

            $languages = Helper::getAllLanguages();
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            foreach ($languages as $lang) {
            // $offer_name                 = $request->input('offer_title_' . $lang->code);
            // $offer_des_content          = $request->input('offer_description_' . $lang->code);
            // $offer_meta_title           = $request->input('meta_title_' . $lang->code);
            // $offer_meta_description     = $request->input('meta_description_' . $lang->code);

                $offer_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
                $offer_des_content          = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('offer_description_en'));
                $offer_meta_title           = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_title_en'));
                $offer_meta_description     = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_description_en'));

                $offershortinsert = new OfferTranslation;
                $offershortinsert->offer_id             = $offer_id;
                $offershortinsert->language_id          = $lang->id;
                $offershortinsert->title                = isset($offer_name) ? $offer_name : $name_en;
                $offershortinsert->description          = isset($offer_des_content) ? $offer_des_content : $request->input('offer_description_en');
                $offershortinsert->meta_title           = isset($offer_meta_title) ? $offer_meta_title : $request->input('meta_title_en');
                $offershortinsert->meta_description     = isset($offer_meta_description) ? $offer_meta_description : $request->input('meta_description_en');
                $offershortinsert->meta_keyword         = isset($offer_meta_title) ? $offer_meta_title : "";
                $offershortinsert->save();
            }

            /* Seo Entries start */
            $offer_meta_title = $request->input('meta_title_en');
            $offer_meta_description = $request->input('meta_description_en');
            
            $dirtseo = new DirectorySeo;
            $dirtseo->module_id     = $offer_id;
            $dirtseo->page_type     = 'offer';
            $dirtseo->meta_keyword  =  isset($offer_meta_title) ? $offer_meta_title : "";
            $dirtseo->meta_description  = isset($offer_meta_description) ? $offer_meta_description : "";
            $dirtseo->save();

            $imgfilegal = $request->file('offer_gallery_image');
            if ($imgfilegal) {
                $limg = 0;
                $hidenarry = $request->input('array_gallery_listing');

                if (!is_array($hidenarry[0])) {
                    $hidenarry = explode(',', $hidenarry[0]);
                } else {
                    $hidenarry = [];
                }
                foreach ($imgfilegal as $imgrow) {
                    $ext = $imgrow->getClientOriginalExtension();
                    $name2 = $imgrow->getClientOriginalName();
                    $imageName2 = time() . $name2;
                    $imageName = Helper::getSeoUrl($imageName2);
                    /*
                    $imagepath = 'files/offer/' . $imageName;
                    $imgrow->move(public_path('files/offer/'), $imageName);
                    */

                        $imagepath = 'images/offer/' . $imageName;
                        $convertext = "webp";        
                        $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);        
                        Storage::disk('images')->put('offer/'.$imageName.'.'.$convertext,$image_resize);

                    $productmainimage_insert                    = new OfferImage;
                    $productmainimage_insert->offer_id          = $offer_id;
                    $productmainimage_insert->position          = !empty($hidenarry[$limg]) ? $hidenarry[$limg] : 0;
                    $productmainimage_insert->main_image        = "galimg";
                    $productmainimage_insert->name_thumbnail    = $imageName;
                    $productmainimage_insert->name_large        = $imageName;
                    $productmainimage_insert->extension         = $ext;
                    $productmainimage_insert->path              = $imagepath;
                    $productmainimage_insert->save();
                    $limg++;
                }
            }
            return redirect('admin/offer')->with('msg', 'Offer Added Successfully!');
        } else {
            return redirect('/admin/add-offer')->with('error', 'Offer Already Applied on the Given Product Id!');
        }
    }

    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        // $data['parent_category']        = Helper::getAllParentCategory();       /* get Category */
        $data['parent_category']        = Helper::getAllParentProductCategory();      /* get product Category */
        // $data['child_category']         = Helper::getAllChildCategory();       /* get Child Category */
        // $data['alltags']                = Helper::getTagsData();       /* get Tags Category */
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        $data['offer_translationDesData'] = DB::table('offer as o')->select('*')
            ->join('offer_translations as ot', 'o.id', '=', 'ot.offer_id')
            ->where('ot.offer_id',  $id)->get();

        $data['offerMetaTag'] = DB::table('directory_seo as dse')->select('*')
            ->where('dse.module_id',  $id)->where('page_type','offer')->first();

        $data['offerEditDetails']     = Offer::findOrFail($id);

        $data['pro_subcat']       = Productcategory::where('parent_id',$data['offerEditDetails']->search_parent_category)->where('status','Active')->orderBy('name', 'ASC')->get()->toArray();
        
        $data['tags']         = ProductTags::whereIn('child_cat_id',  explode(",",$data['offerEditDetails']->search_child_category))->where('status','Active')->orderBy('tags', 'ASC')->get()->toArray();
        //dd($data['tags']);

        return view('admin.offer.edit', ['data' => $data]);
    }

    public function update(Request $request, $offer_id)
    {
        $this->validate($request, [
            'offer_title_en'                => 'required|max:40',
            'offer_description_en'          => 'required|max:500',
            'offergal_image'                => 'array|max:5',
            'productId'                     => 'required',
            'active_date'                   => 'required',
            'expiry_date'                   => 'required|after_or_equal:active_date',
            'video' => 'nullable|file|max:5120', // 5120 KB = 5 MB
        ], [
            'video.max' => 'The video may not be greater than 5 MB.',
        ]);

        $name_en                            = $request->input('offer_title_en');
        $seo_en                             = $request->input('seo_en');
        $offerEditDetails                   = Offer::findOrFail($offer_id);
        $offerEditDetails                   = explode(",", $offerEditDetails->offer_product_id);
        $newProductids                      = $request->input('productId');
        $result = array();
        foreach ($newProductids as $key => $value) {
            if (!in_array($value, $offerEditDetails))
                $result[] = $value;
        }
        /* -----------------------------------------------------------------------------  */
        $existOffer = "no";
        if (!empty($result)) {
            $proID = implode(",", $result);
            $resultid = array();
            $pro_id = explode(",", $proID);
            foreach ($pro_id as $id) {
                $resultid[$id] = count(Helper::getValidityOfOffer($id));
            }
            foreach ($resultid as $row) {
                if ($row > 0) {
                    $existOffer = "yes";
                    break;
                } else {
                    $existOffer = "no";
                }
            }
        }
        if ($existOffer != 'yes') {
            $latitude       = $request->input('lati');
            $longitude      = $request->input('long');
            $dirId          = $request->input('directoryId'); 
            $dirlocationdetails = DiectoryLocation::where('directory_id', $dirId)->first();
            if (!empty($dirlocationdetails)) {
                if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                    $latitude = $dirlocationdetails['latitude'];
                    $longitude = $dirlocationdetails['longitude'];
                }
            }
            Offer::where('id', $offer_id)->delete();
            $imgfile = $request->file('offer_image');
            $imgfilegal = $request->file('offer_gallery_image');
            $offerimgbanner = $request->file('offer_banner_image');
            if ($imgfile != "" && $imgfilegal != "") {
                OfferImage::where('offer_id', $offer_id)->delete();
            } else if ($imgfile != "") {
                OfferImage::where('offer_id', $offer_id)->where('main_image', "main")->delete();
            } else if ($imgfilegal != "") {
                OfferImage::where('offer_id', $offer_id)->where('main_image', "galimg")->delete();
            } else if ($offerimgbanner != "") {
                OfferImage::where('offer_id', $offer_id)->where('main_image', "banner")->delete();
            } else {
            }
            $price_dis = 0;
            $dis_per = 0;
            $offerinsert = new Offer;
            $offerinsert->id                                    = $offer_id;
            $offerinsert->title                                 = (isset($name_en)) ? $name_en : "";
            $offerinsert->slug                                  = $seo_en;
            $offerinsert->description                           = $request->input('offer_description_en');
            $offerinsert->discounttype                          = $request->input('discount_type');
            $offerinsert->discount_selectedvalue                = $request->input('offer_discount');
            $offerinsert->latitude                              = $latitude;
            $offerinsert->longitude                             = $longitude;
            if ($request->input('original_price')) {
                $original_price = $request->input('original_price');
            } else {
                $original_price = 0;
            }
            if ($request->input('price_after_discount')) {
                $price_after_discount = $request->input('price_after_discount');
            } else {
                $price_after_discount = 0;
            }
            if ($request->input('spent')) {
                $spent = $request->input('spent');
            } else {
                $spent = 0;
            }
            if ($request->input('customer_spending')) {
                $customer_spending = $request->input('customer_spending');
            } else {
                $customer_spending = 0;
            }
            if ($request->input('many_buy')) {
                $many_buy = $request->input('many_buy');
            } else {
                $many_buy = 0;
            }
            if ($request->input('many_pay')) {
                $many_pay = $request->input('many_pay');
            } else {
                $many_pay = 0;
            }
            if ($request->input('offer_discount') == "pricereduction") {
                $offerinsert->discount_firstvalue               = $original_price;
                $offerinsert->discount_secondvalue              = $price_after_discount;
            }
            if ($request->input('offer_discount') == "pricediscount") {
                if ($request->input('price_dis')) {
                    $price_dis = $request->input('price_dis');
                } else {
                    $price_dis = 0;
                }
                $offerinsert->discount_firstvalue               = $price_dis;
            }
            if ($request->input('offer_discount') == "percentagediscount") {
                if ($request->input('dis_per')) {
                    $dis_per = $request->input('dis_per');
                } else {
                    $dis_per = 0;
                }
                $offerinsert->discount_firstvalue               = $dis_per;
            }
            if ($request->input('offer_discount') == "valuediscount") {
                $offerinsert->discount_firstvalue               = $spent;
                $offerinsert->discount_secondvalue              = $customer_spending;
            }
            if ($request->input('offer_discount') == "quantitativediscount") {
                $offerinsert->discount_firstvalue               = $many_buy;
                $offerinsert->discount_secondvalue              = $many_pay;
            }
            $offerinsert->offer_type_selectedvalue          = 'product';

            if ($request->input('productId')) {
                $productid = array();
                foreach ($request->input('productId') as $pcatrow) {
                    $productid[] = $pcatrow;
                    DB::table('product')->where('id', $pcatrow)->update(array('offer_id' => $offer_id));
                }
                $offerinsert->offer_product_id            =  implode(",", $productid);
            }
            $offerinsert->offer_directory_link              = $request->input('directoryId');

            // if ($request->input('parentcategory')) {
            //     $parentCategory = array();
            //     foreach ($request->input('parentcategory') as $pcatrow) {
            //         $parentCategory[] = $pcatrow;
            //     }
            //     $offerinsert->search_parent_category            =  implode(",", $parentCategory);    //$request->input('parentcategory');
            // }

            $offerinsert->search_parent_category                = $request->input('parentcategory');

            if ($request->input('childcategory')) {
                $childCategory = array();
                foreach ($request->input('childcategory') as $childcatrow) {
                    $childCategory[] = $childcatrow;
                }
                $offerinsert->search_child_category             =  implode(",", $childCategory);    //$request->input('childcategory');
            }
            if ($request->input('offertags')) {
                $offerTags = array();
                foreach ($request->input('offertags') as $tagsrow) {
                    $offerTags[] = $tagsrow;
                }
                $offerinsert->tags                              =  implode(",", $offerTags);    //$request->input('offertags');
            }

            $videofile = $request->file('video');
            if ($videofile) {
                $name2 = $videofile->getClientOriginalName();
                $videoName = time() . $name2;
                $videofile->move(public_path('files/offer/'), $videoName);
                $offerinsert->video = $videoName;
            }

            $offerinsert->offer_active_date                 = $request->input('active_date');
            $offerinsert->offer_expire_date                 = $request->input('expiry_date');
            $offerinsert->default_duration                  = $request->input('default_status');
            if ($request->input('default_status') == 'yes') {
                $offerinsert->default_month                 = $request->input('set_months');
            }
            $offerinsert->status                            = $request->input('offer_status');
            $offerinsert->created_by                        = $request->input('createdby');
            $offerinsert->save();
            $product_id = $request->input('productId');
            if (!empty($product_id)) {
                foreach ($product_id as $id) {
                    DB::table('product')->where('id', $id)->update(array('offer_id' => $offer_id));
                    $this->ProductDiscountChange($id, $price_dis, $dis_per, $request->input('offer_discount'));
                }
            }
            $imgbanner = $request->file('offer_banner_image');
            if ($imgbanner) {
                $ext = $imgbanner->getClientOriginalExtension();
                $name2 = $imgbanner->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                /*
                $imagepath = 'files/offer/' . $imageName;
                $image_resize = Image::make($imgbanner->getRealPath());
                $image_resize->save(public_path('files/offer/' . $imageName));
                */

                    $imagepath = 'images/offer/' . $imageName;
                    $convertext = "webp";        
                    $image_resize = Image::make($imgbanner->getRealPath())->stream($convertext,50);        
                    Storage::disk('images')->put('offer/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                    = new OfferImage;
                $productmainimage_insert->offer_id          = $offer_id;
                $productmainimage_insert->main_image        = "banner";
                $productmainimage_insert->name_thumbnail    = $imageName;
                $productmainimage_insert->name_large        = $imageName;
                $productmainimage_insert->extension         = $ext;
                $productmainimage_insert->path              = $imagepath;
                $productmainimage_insert->save();
            }
            $imgfile = $request->file('offer_image');
            if ($imgfile) {

                $ext = $imgfile->getClientOriginalExtension();
                $name2 = $imgfile->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);

                /*
                $imagepath = 'files/offer/' . $imageName;
                $image_resize = Image::make($imgfile->getRealPath());
                $image_resize->save(public_path('files/offer/' . $imageName));
                */

                    $imagepath = 'images/offer/' . $imageName;
                    $convertext = "webp";        
                    $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);        
                    Storage::disk('images')->put('offer/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                    = new OfferImage;
                $productmainimage_insert->offer_id          = $offer_id;
                $productmainimage_insert->main_image        = "main";
                $productmainimage_insert->name_thumbnail    = $imageName;
                $productmainimage_insert->name_large        = $imageName;
                $productmainimage_insert->extension         = $ext;
                $productmainimage_insert->path              = $imagepath;
                $productmainimage_insert->save();
            }

            $module_id = $request->input('module_id');
            $page_type = $request->input('page_type');
            $offer_meta_title = $request->input('meta_title_en');
            $seo_meta_description_en = $request->input('meta_description_en');
    
            if (!empty($module_id) && (!empty($page_type))) {
                DirectorySeo::where('module_id', $offer_id)->where('page_type', 'offer')
                    ->update([
                        'page_type'  => 'offer',
                        'meta_keyword' => $offer_meta_title??'',
                        'meta_description' => $seo_meta_description_en??''
                ]);
            }else{
                $dirtseo = new DirectorySeo;
                $dirtseo->module_id     = $offer_id;
                $dirtseo->page_type     = 'offer';
                $dirtseo->meta_keyword  = $offer_meta_title??'';
                $dirtseo->meta_description  = $seo_meta_description_en??'';
                $dirtseo->save();
            }

            $languages = Helper::getAllLanguages();
            foreach ($languages as $lang) {

                $languageTranslation_id = $request->input('langtranlation_' . $lang->code);
                $offer_title = $request->input('offer_title_' . $lang->code);
                $seo_title = $request->input('meta_title_' . $lang->code);
                $seo_meta_description = $request->input('meta_description_' . $lang->code);
                if ($request->input('langtranlation_id') == 0) {
                    $udircttrans =  new SeoTranslation();
                } else {
                    $udircttrans =  SeoTranslation::find($languageTranslation_id);
                }
                $udircttrans->module_id  = $offer_id;
                $udircttrans->language_id  = $lang->id;
                $udircttrans->meta_title        =  isset($seo_title) ? $seo_title : "";
                $udircttrans->meta_keyword     = isset($offer_title) ? $offer_title : "";
                $udircttrans->meta_description  = isset($seo_meta_description) ? $seo_meta_description : "";
                $udircttrans->module_name       = 'Offer';
                $udircttrans->save();
            }

            $languages = Helper::getAllLanguages();
            foreach ($languages as $lang) {
                $offer_name = $request->input('offer_title_' . $lang->code);
                $offer_des_content = $request->input('offer_description_' . $lang->code);
                $offer_meta_title = $request->input('meta_title_' . $lang->code);
                $offer_meta_description = $request->input('meta_description_' . $lang->code);
                $languageTranslation_id         = $request->input('langtranlation_' . $lang->code);
                $offershortinsert = OfferTranslation::find($languageTranslation_id);

                if ($offershortinsert == null) {
                    $offershortinsert = new OfferTranslation;
                    $offershortinsert->offer_id                 = $offer_id;
                    $offershortinsert->language_id            = $lang->id;
                    $offershortinsert->title                  = isset($offer_name) ? $offer_name : $name_en;
                    $offershortinsert->description           = isset($offer_des_content) ? $offer_des_content : $request->input('offer_description_en');
                    $offershortinsert->meta_title             = isset($offer_meta_title) ? $offer_meta_title : $request->input('meta_title_en');
                    $offershortinsert->meta_description       = isset($offer_meta_description) ? $offer_meta_description : $request->input('meta_description_en');
                    $offershortinsert->meta_keyword            = isset($offer_meta_title) ? $offer_meta_title : "";
                    $offershortinsert->save();
                } else {
                    $offershortinsert->offer_id             = $offer_id;
                    $offershortinsert->language_id            = $lang->id;
                    $offershortinsert->title                  = isset($offer_name) ? $offer_name : $name_en;
                    $offershortinsert->description           = isset($offer_des_content) ? $offer_des_content : $request->input('offer_description_en');
                    $offershortinsert->meta_title             = isset($offer_meta_title) ? $offer_meta_title : $request->input('meta_title_en');
                    $offershortinsert->meta_description       = isset($offer_meta_description) ? $offer_meta_description : $request->input('meta_description_en');
                    $offershortinsert->meta_keyword            = isset($offer_meta_title) ? $offer_meta_title : "";
                    $offershortinsert->save();
                }
            }

            $imgfilegal = $request->file('offergal_image');
            if ($imgfilegal) {
                foreach ($imgfilegal as $imgrow) {
                    $ext = $imgrow->getClientOriginalExtension();
                    $name2 = $imgrow->getClientOriginalName();
                    $imageName2 = time() . $name2;
                    $imageName = Helper::getSeoUrl($imageName2);
                    /*
                    $imagepath = 'files/offer/' . $imageName;
                    $image_resize = Image::make($imgrow->getRealPath());
                    $image_resize->save(public_path('files/offer/' . $imageName));
                    */

                        $imagepath = 'images/offer/' . $imageName;
                        $convertext = "webp";        
                        $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);        
                        Storage::disk('images')->put('offer/'.$imageName.'.'.$convertext,$image_resize);

                    $productmainimage_insert                    = new OfferImage;
                    $productmainimage_insert->offer_id          = $offer_id;
                    $productmainimage_insert->main_image        = "galimg";
                    $productmainimage_insert->name_thumbnail    = $imageName;
                    $productmainimage_insert->name_large        = $imageName;
                    $productmainimage_insert->extension         = $ext;
                    $productmainimage_insert->path              = $imagepath;
                    $productmainimage_insert->save();
                }
            } else {
                $thumb_hidden_img = $request->input('thumb_hidden_img');
                $hiddenarray_thumb_hidden_img = explode(',', $thumb_hidden_img);
                $details = OfferImage::where('main_image', 'galimg')->where('offer_id', $offer_id)->get();
                if ($details->count() > 0) {
                    foreach ($details as $key => $detail) {
                        $detail->name_thumbnail = $hiddenarray_thumb_hidden_img[$key];
                        $detail->name_large = $hiddenarray_thumb_hidden_img[$key];
                        $detail->path = 'images/offer/' . $hiddenarray_thumb_hidden_img[$key];
                    }
                }
            }
            return redirect('/admin/offer-edit/' . $offer_id)->with('msg', 'Offer has been updated');
        } else {
            return redirect('/admin/offer-edit/' . $offer_id)->with('product_add_error', 'Offer Already Applied on the Given Product Id!');
        }
    }
    public function destroy($id)
    {
        Offer::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/offer')->with('msg', 'Offer deleted successfully');
    }
    public static function get_bannerImage($offerId)
    {
        $get_mainimageData = DB::table('offer_image')->select('*')->where('offer_id', $offerId)->where('main_image', "banner")->get()->toArray();
        return $get_mainimageData;
    }

    public static function get_mainImage($offerId)
    {
        $get_mainimageData = DB::table('offer_image')->select('*')->where('offer_id', $offerId)->where('main_image', "main")->get()->toArray();
        return $get_mainimageData;
    }
    public static function get_galleryImage($offerId)
    {
        $get_mainimageData = DB::table('offer_image')->select('*')->where('offer_id', $offerId)->where('main_image', "galimg")->get()->toArray();
        return $get_mainimageData;
    }
    public function deleteGalleryImageDelete(Request $request)
    {
        $data = OfferImage::find($request->id)->delete();
        return response()->json($data);
    }
    public function ProductDiscountChange($productId, $price_dis, $percentage_dis, $discount_value)
    {
        $get_mainimageData = DB::table('product_price')->select('*')->where('product_id', $productId)->get()->toArray();
        foreach ($get_mainimageData as $row) {
            if (is_numeric($price_dis) <> 0 && is_numeric($row->regular_price) > 0) {
                $discountPrice = $row->regular_price - $price_dis;
                DB::table('product_price')->where('id', $row->id)->update(array('sales_price' => $discountPrice, 'discount_type' => $discount_value, 'discount_value' => $price_dis));
            }
            if (is_numeric($percentage_dis) <> 0 && is_numeric($row->regular_price) > 0) {
                $afterdiscount = ($row->regular_price) * ($percentage_dis / 100);
                $discountPerPrice = $row->regular_price - $afterdiscount;
                DB::table('product_price')->where('id', $row->id)->update(array('sales_price' => $discountPerPrice, 'discount_type' => $discount_value, 'discount_value' => $percentage_dis));
            }
        }
    }

    public function getProduct(Request $request)
    {
        $data['productsdata'] = Product::where("directory_id", $request->directory_id)->where('deleted', '0')->get(["name", "id"]);
        return response()->json($data);
    }

    public function getFreeProduct(Request $request)
    {
        $data['productsdata'] = Product::where("directory_id", $request->directory_id)->where('is_free_product', 0)->where('deleted', '0')->get(["name", "id"]);
        $data['freeproductsdata'] = Product::where("directory_id", $request->directory_id)->where('is_free_product', 1)->where('deleted', '0')->get(["name", "id"]);
        return response()->json($data);
    }

    public function checkOfferSlug(Request $request)
    {
        $data = array();
        $data['seoChangeData'] = Helper::getSeoUrl($request->catname);
        if($request->directory=="offer"){
            $offerTypeDetails = DB::table('offer')->where('slug',$data['seoChangeData'])->orderBy('id', 'desc')->get()->toArray();
        }
        $data['offerTypeDetails'] = "";
        if(!empty($offerTypeDetails)){
            $datainmatch = $offerTypeDetails[0]->slug;
            $checkdata = explode("-",$datainmatch);
            $sliced = array_slice($checkdata, 0, -1);
            $string = implode("-", $sliced);
            $checkdata = end($checkdata);

            $num = 0;
            if(is_numeric($checkdata)){
                $checkdata = $string."-".++$checkdata;
            }else{
                $checkdata = $data['seoChangeData']."-".++$num;
            }

            $data['offerTypeDetails'] = $checkdata;
        }
         return response()->json($data);
    }

    public function getAdminOfferProductsubCategory(Request $request)
    {
        if(!empty($request->cat_id)){
            // $cat = Productcategory::where('parent_id', $request->cat_id)->where('status', 'Active')->orderBy('name', 'ASC')->get();
            // $html = "<option></option>";
            // foreach($cat as $s){
            //     $html = $html."<option value='".$s->id."'>".$s->name."</option>";
            // }
            // return $html;
            $data['subcat'] = Productcategory::where("parent_id", $request->cat_id)
                ->where('status', 'Active')->where('deleted','0')->orderBy('name', 'ASC')->get(["id", "name"]);
            return response()->json($data);
        }
    }
}
