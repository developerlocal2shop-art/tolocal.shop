<?php

namespace App\Http\Controllers\Admin\ShoppingCard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\ShoppingCard;
use App\Models\ShoppingCardImage;
use DB;
use App\Models\SeoTranslation;
use App\Models\ShoppingCardTranslation;
use App\Models\DirectorySeo;
use App\Models\Productcategory;
use App\Models\ProductTags;
use App\Models\DiectoryLocation;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ShoppingCardController extends Controller
{
    public function index(Request $request)
    {
        $allDirectory = Helper::getAllDirectory();
        //dd($allDirectory);
        $shoppingcarddataCount = ShoppingCard::query()->where('deleted', '0')->get()->count();
        $currentdate = strftime('%F');
        $totalOngoingShoppingCardCount = ShoppingCard::query()->where('deleted', '0')->where('active_date', '<=', $currentdate)->where('expire_date', '>=', $currentdate)->get()->count();
        $totalExpiredShoppingCardCount = ShoppingCard::query()->where('deleted', '0')->where('expire_date', '<=', $currentdate)->get()->count();
        $totalUpcomingshoppingcard = ShoppingCard::query()->where('deleted', '0')->where('active_date', '>', $currentdate)->get()->count();
        $query = [];
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $directory_id = $request->input('directory_id');
            if ($directory_id != "") {
                $data['shopping_card'] = DB::table('shopping_card')->select('*')
                    ->where('title', 'like', '%' . $searchQuery . '%')
                    ->where('directory_link', $directory_id);
            } else {
                $data['shopping_card'] = DB::table('shopping_card')->select('*')
                    ->where('title', 'like', '%' . $searchQuery . '%');
            }
            $shoppingcarddata =    $data['shopping_card']->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
        } else {
            $shoppingcarddata = ShoppingCard::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            //return view('admin.shoppingcard.index', ['shoppingcarddata' => $shoppingcarddata, 'textsearch' => $searchtext, 'shoppingcarddataCount' => $shoppingcarddataCount, 'totalOngoingShoppingCardCount' => $totalOngoingShoppingCardCount, 'totalExpiredShoppingCardCount' => $totalExpiredShoppingCardCount, 'totalUpcomingshoppingcard' => $totalUpcomingshoppingcard]);
        }

        return view('admin.shoppingcard.index', ['shoppingcarddata' => $shoppingcarddata, 'textsearch' => $searchtext, 'query' => $query, 'allDirectory' => $allDirectory, 'shoppingcarddataCount' => $shoppingcarddataCount, 'totalOngoingShoppingCardCount' => $totalOngoingShoppingCardCount, 'totalExpiredShoppingCardCount' => $totalExpiredShoppingCardCount, 'totalUpcomingshoppingcard' => $totalUpcomingshoppingcard]);
    }

    public function create()
    {

        // $data['parent_category']        = Helper::getAllParentCategory();  /* get Category */
        // $data['child_category']         = Helper::getAllChildCategory();   /* get Child Category */
        // $data['alltags']                = Helper::getTagsData();       /* get Tags Category */

        $data['parent_category']            = Helper::getAllParentProductCategory();      /* get product Category */
        $data['productsubcat']              = Helper::getProductCatgoeryData();                  /* get Child Category */
        $data['tags']                       = Helper::getProductTagsData();                /* get tags */
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        return view('admin.shoppingcard.create', ['data' => $data]);
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'directoryId'                   => 'required',
            'shoppingcard_title_en'         => 'required|max:40',
            'shoppingcard_description_en'   => 'required|max:500',
            'productId'                     => 'required',
            'shoppingcard_gallery_image'    => 'required|array|max:20',
            'shoppingcard_banner_image'     => 'required',
            'shoppingcard_discount'         => 'required',
            'active_date'                   => 'required',
            'expiry_date'                   => 'required|after_or_equal:active_date'
        ]);
        $name_en = $request->input('shoppingcard_title_en');
        $seo_en = $request->input('seo_en');
        $product_id = $request->input('productId');
        if (!empty($product_id)) {
            $pro_id = implode(",", $product_id);
        } else {
            $pro_id = 0;
        }

        $latitude       = $request->input('lati');
        $longitude      = $request->input('long');
        $directory_id   = $request->input('directoryId');
        $dirlocationdetails = DiectoryLocation::where('directory_id', $directory_id)->first();
        if (!empty($dirlocationdetails)) {
            if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                $latitude = $dirlocationdetails['latitude'];
                $longitude = $dirlocationdetails['longitude'];
            }
        }

        $proID = DB::table('shopping_card')->where('product_id', $product_id)->where('deleted', '0')->exists();
        if (!$proID) {
            $shoppingcardinsert = new ShoppingCard;
            $shoppingcardinsert->title                              = (isset($name_en)) ? $name_en : "";
            $shoppingcardinsert->slug                               = (isset($seo_en)) ? $seo_en : "";
            $shoppingcardinsert->description                        = $request->input('shoppingcard_description_en');
            $shoppingcardinsert->discount_selectedvalue             = $request->input('shoppingcard_discount');
            $shoppingcardinsert->latitude                           =   $latitude;
            $shoppingcardinsert->longitude                          =   $longitude;
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
            if ($request->input('shoppingcard_discount') == "pricereduction") {
                $shoppingcardinsert->discount_firstvalue               = $original_price;
                $shoppingcardinsert->discount_secondvalue              = $price_after_discount;
            }
            if ($request->input('shoppingcard_discount') == "pricediscount") {
                $shoppingcardinsert->discount_firstvalue               = $price_dis;
            }
            if ($request->input('shoppingcard_discount') == "percentagediscount") {
                $shoppingcardinsert->discount_firstvalue               = $dis_per;
            }
            if ($request->input('shoppingcard_discount') == "valuediscount") {
                $shoppingcardinsert->discount_firstvalue               = $spent;
                $shoppingcardinsert->discount_secondvalue              = $customer_spending;
            }
            if ($request->input('shoppingcard_discount') == "quantitativediscount") {
                $shoppingcardinsert->discount_firstvalue               = $many_buy;
                $shoppingcardinsert->discount_secondvalue              = $many_pay;
            }
            $shoppingcardinsert->selectedvalue_type          = 'product';
            $shoppingcardinsert->directory_link              = $request->input('directoryId');
            // if ($request->input('parentcategory')) {
            //     $parentCategory = array();
            //     foreach ($request->input('parentcategory') as $pcatrow) {
            //         $parentCategory[] = $pcatrow;
            //     }
            //     $shoppingcardinsert->search_parent_category            =  implode(",", $parentCategory);    //$request->input('parentcategory');
            // }

            $shoppingcardinsert->search_parent_category         = $request->input('parentcategory');

            if ($request->input('childcategory')) {
                $childCategory = array();
                foreach ($request->input('childcategory') as $childcatrow) {
                    $childCategory[] = $childcatrow;
                }
                $shoppingcardinsert->search_child_category             =  implode(",", $childCategory);    //$request->input('childcategory');
            }
            if ($request->input('shoppingcardtags')) {
                $shoppingCardTags = array();
                foreach ($request->input('shoppingcardtags') as $tagsrow) {
                    $shoppingCardTags[] = $tagsrow;
                }
                $shoppingcardinsert->tags                              =  implode(",", $shoppingCardTags);    //$request->input('offertags');
            }
            $videofile = $request->file('video');
            if ($videofile) {
                $name2 = $videofile->getClientOriginalName();
                $videoName = time() . $name2;
                $videofile->move(public_path('files/shoppingcard/'), $videoName);
                $shoppingcardinsert->video = $videoName;
            }
            $startdate = $request->input('active_date');
            $expiredate = $request->input('expiry_date');
            $shoppingcardinsert->active_date                 = isset($startdate) ? $startdate : date("Y-m-d");
            $shoppingcardinsert->expire_date                 = isset($expiredate) ? $expiredate : date("Y-m-d");
            $shoppingcardinsert->default_duration                  = $request->input('default_status');
            if ($request->input('default_status') == 'yes') {
                $shoppingcardinsert->default_month                 = $request->input('set_months');
            }
            $shoppingcardinsert->status                            = $request->input('shoppingcard_status');
            $shoppingcardinsert->created_by                        = $request->input('createdby');
            $shoppingcardinsert->product_id                        = $pro_id;
            $shoppingcardinsert->save();
            $shopping_card_id = $shoppingcardinsert->id;
            $imgbannerfile = $request->file('shoppingcard_banner_image');
            if ($imgbannerfile) {
                $ext = $imgbannerfile->getClientOriginalExtension();
                $name2 = $imgbannerfile->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                /*
                $imagepath = 'files/shoppingcard/' . $imageName;
                $image_resize = Image::make($imgbannerfile->getRealPath());
                $image_resize->save(public_path('files/shoppingcard/' . $imageName));
                */

                    $imagepath = 'images/shoppingcard/' . $imageName;
                    $convertext = "webp";
                    $image_resize = Image::make($imgbannerfile->getRealPath())->stream($convertext,50);
                    Storage::disk('images')->put('shoppingcard/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                            = new ShoppingCardImage;
                $productmainimage_insert->shopping_card_id          = $shopping_card_id;
                $productmainimage_insert->main_image                = "banner";
                $productmainimage_insert->name_thumbnail            = $imageName;
                $productmainimage_insert->name_large                = $imageName;
                $productmainimage_insert->extension                 = $ext;
                $productmainimage_insert->path                      = $imagepath;
                $productmainimage_insert->save();
            }
            $imgfile = $request->file('shoppingcard_image');
            if ($imgfile) {
                $ext = $imgfile->getClientOriginalExtension();
                $name2 = $imgfile->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);

                /*
                $imagepath = 'files/shoppingcard/' . $imageName;
                $image_resize = Image::make($imgfile->getRealPath());
                //$image_resize->resize(700, 700);
                $image_resize->save(public_path('files/shoppingcard/' . $imageName));
                */

                    $imagepath = 'images/shoppingcard/' . $imageName;
                    $convertext = "webp";
                    $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);
                    Storage::disk('images')->put('shoppingcard/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                            = new ShoppingCardImage;
                $productmainimage_insert->shopping_card_id          = $shopping_card_id;
                $productmainimage_insert->main_image                = "main";
                $productmainimage_insert->name_thumbnail            = $imageName;
                $productmainimage_insert->name_large                = $imageName;
                $productmainimage_insert->extension                 = $ext;
                $productmainimage_insert->path                      = $imagepath;
                $productmainimage_insert->save();
            }
            $languages = Helper::getAllLanguages();
            foreach ($languages as $lang) {
                $shoppingcard_title             = $request->input('shoppingcard_title_' . $lang->code);
                $seo_title                      = $request->input('meta_title_' . $lang->code);
                $seo_meta_description           = $request->input('meta_description_' . $lang->code);
                $dircttrans                     = new SeoTranslation();
                $dircttrans->module_id          = $shopping_card_id;
                $dircttrans->language_id        = $lang->id;
                $dircttrans->meta_title         = isset($seo_title) ? $seo_title : "";
                $dircttrans->meta_keyword       = isset($shoppingcard_title) ? $shoppingcard_title : "";
                $dircttrans->meta_description   = isset($seo_meta_description) ? $seo_meta_description : "";
                $dircttrans->module_name        = 'Shopping Card';
                $dircttrans->save();
            }
            $languages = Helper::getAllLanguages();
            $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
            foreach ($languages as $lang) {
                // $shoppingcard_name                              = $request->input('shoppingcard_title_' . $lang->code);
                // $shoppingcard_des_content                       = $request->input('shoppingcard_description_' . $lang->code);
                // $shoppingcard_meta_title                        = $request->input('meta_title_' . $lang->code);
                // $shoppingcard_meta_description                  = $request->input('meta_description_' . $lang->code);

                $shoppingcard_name                              = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
                $shoppingcard_des_content                       = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('shoppingcard_description_en'));
                $shoppingcard_meta_title                        = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_title_en'));
                $shoppingcard_meta_description                  = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_description_en'));

                $shoppingcardshortinsert                        = new ShoppingCardTranslation;
                $shoppingcardshortinsert->shoppingcard_id       = $shopping_card_id;
                $shoppingcardshortinsert->language_id           = $lang->id;
                $shoppingcardshortinsert->title                 = isset($shoppingcard_name) ? $shoppingcard_name : $name_en;
                $shoppingcardshortinsert->description           = isset($shoppingcard_des_content) ? $shoppingcard_des_content : $request->input('shoppingcard_description_en');
                $shoppingcardshortinsert->meta_title            = isset($shoppingcard_meta_title) ? $shoppingcard_meta_title : $request->input('meta_title_en');
                $shoppingcardshortinsert->meta_description      = isset($shoppingcard_meta_description) ? $shoppingcard_meta_description : $request->input('meta_description_en');
                $shoppingcardshortinsert->meta_keyword          = isset($shoppingcard_name) ? $shoppingcard_name : "";
                $shoppingcardshortinsert->save();
            }

            /* Seo Entries start */
            $shoppingcard_meta_title = $request->input('meta_title_en');
            $shoppingcard_meta_description = $request->input('meta_description_en');
            $dirtseo = new DirectorySeo;
            $dirtseo->module_id     = $shopping_card_id;
            $dirtseo->page_type     = 'shoppingcard';
            $dirtseo->meta_keyword  =  isset($shoppingcard_meta_title) ? $shoppingcard_meta_title : "";
            $dirtseo->meta_description  = isset($shoppingcard_meta_description) ? $shoppingcard_meta_description : "";
            $dirtseo->save();

            $imgfilegal = $request->file('shoppingcard_gallery_image');
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
                    $imagepath = 'files/shoppingcard/' . $imageName;
                    $image_resize = Image::make($imgrow->getRealPath());
                    //$image_resize->resize(700, 700);
                    $image_resize->save(public_path('files/shoppingcard/' . $imageName));
                    */

                    $imagepath = 'images/shoppingcard/' . $imageName;
                    $convertext = "webp";
                    $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                    Storage::disk('images')->put('shoppingcard/'.$imageName.'.'.$convertext,$image_resize);

                    $productmainimage_insert                            = new ShoppingCardImage;
                    $productmainimage_insert->shopping_card_id          = $shopping_card_id;
                    $productmainimage_insert->position                  = !empty($hidenarry[$limg]) ? $hidenarry[$limg] : 0;
                    $productmainimage_insert->main_image                = "galimg";
                    $productmainimage_insert->name_thumbnail            = $imageName;
                    $productmainimage_insert->name_large                = $imageName;
                    $productmainimage_insert->extension                 = $ext;
                    $productmainimage_insert->path                      = $imagepath;
                    $productmainimage_insert->save();
                    $limg++;
                }
            }
            return redirect('admin/shoppingcard')->with('msg', 'Shopping Card Added Successfully!');
        } else {
            return redirect('admin/add-shoppingcard')->with('error', 'Shopping Card Already Added On Given Product Id!');
        }
    }

    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        // $data['parent_category']        = Helper::getAllParentCategory();       /* get Category */
        // $data['child_category']         = Helper::getAllChildCategory();       /* get Child Category */
        // $data['alltags']                = Helper::getTagsData();       /* get Tags Category */
        $data['parent_category']        = Helper::getAllParentProductCategory();      /* get product Category */
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */

        $data['shoppingcard_translationDesData'] = DB::table('shopping_card as s')->select('*')
            ->join('shoppingcard_translations as st', 's.id', '=', 'st.shoppingcard_id')
            ->where('st.shoppingcard_id',  $id)->get();
        $data['shoppingcardEditDetails']     = ShoppingCard::findOrFail($id);

        $data['shoppingcardMetaTag'] = DB::table('directory_seo')->select('*')
        ->where('module_id',  $id)->where('page_type','shoppingcard')->first();

        $data['pro_subcat']       = Productcategory::where('parent_id',$data['shoppingcardEditDetails']->search_parent_category)->where('status','Active')->orderBy('name', 'ASC')->get()->toArray();

        $data['tags']         = ProductTags::whereIn('child_cat_id',  explode(",",$data['shoppingcardEditDetails']->search_child_category))->where('status','Active')->orderBy('tags', 'ASC')->get()->toArray();

        return view('admin.shoppingcard.edit', ['data' => $data]);
    }

    public function update(Request $request, $shopping_card_id)
    {
        $this->validate($request, [
            'shoppingcard_title_en'             => 'required|max:40',
            'shoppingcard_description_en'       => 'required|max:500',
            'productId'                         => 'array|required',
            'shoppingcardgal_image'             => 'array|max:20',
            'active_date'                       => 'required',
            'expiry_date'                       => 'required|after_or_equal:active_date'
        ]);

        $shoppingcartEditDetails                = ShoppingCard::findOrFail($shopping_card_id);
        $record                                 = ShoppingCard::where('id', $shopping_card_id)->first();
        $previousVideo                          = $record['video'];
        ShoppingCard::where('id', $shopping_card_id)->delete();
        $name_en                                = $request->input('shoppingcard_title_en');
        $seo_en                                 = $request->input('seo_en');
        $imgfile                                = $request->file('shoppingcard_image');
        $imgfilegal                             = $request->file('shoppingcard_gallery_image');
        $imgbanner                              = $request->file('shoppingcard_banner_image');
        if ($imgfile != "" && $imgfilegal != "" && $imgbanner != "") {
            ShoppingCardImage::where('shopping_card_id', $shopping_card_id)->delete();
        } else if ($imgfile != "") {
            ShoppingCardImage::where('shopping_card_id', $shopping_card_id)->where('main_image', "main")->delete();
        } else if ($imgfilegal != "") {
            ShoppingCardImage::where('shopping_card_id', $shopping_card_id)->where('main_image', "galimg")->delete();
        } else if ($imgbanner != "") {
            ShoppingCardImage::where('shopping_card_id', $shopping_card_id)->where('main_image', "banner")->delete();
        } else {
        }
        $product_id = $request->input('productId');
        if (!empty($product_id)) {
            $pro_id = implode(",", $product_id);
        } else {
            $pro_id = 0;
        }
        $price_dis = 0;
        $dis_per = 0;
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
        $shoppingcardinsert = new ShoppingCard;
        $shoppingcardinsert->id                                 = $shopping_card_id;
        $shoppingcardinsert->title                              = (isset($name_en)) ? $name_en : "";
        $shoppingcardinsert->slug                               = (isset($seo_en)) ? $seo_en : "";
        $shoppingcardinsert->description                        = $request->input('shoppingcard_description_en');
        $shoppingcardinsert->discount_selectedvalue             = $request->input('shoppingcard_discount');
        $shoppingcardinsert->latitude                           = $latitude;
        $shoppingcardinsert->longitude                          = $longitude;
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
        if ($request->input('shoppingcard_discount') == "pricereduction") {
            $shoppingcardinsert->discount_firstvalue               = $original_price;
            $shoppingcardinsert->discount_secondvalue              = $price_after_discount;
        }
        if ($request->input('shoppingcard_discount') == "pricediscount") {
            if ($request->input('price_dis')) {
                $price_dis = $request->input('price_dis');
            } else {
                $price_dis = 0;
            }
            $shoppingcardinsert->discount_firstvalue               = $price_dis;
        }
        if ($request->input('shoppingcard_discount') == "percentagediscount") {
            if ($request->input('dis_per')) {
                $dis_per = $request->input('dis_per');
            } else {
                $dis_per = 0;
            }
            $shoppingcardinsert->discount_firstvalue                = $dis_per;
        }
        if ($request->input('shoppingcard_discount') == "valuediscount") {
            $shoppingcardinsert->discount_firstvalue                = $spent;
            $shoppingcardinsert->discount_secondvalue               = $customer_spending;
        }
        if ($request->input('shoppingcard_discount') == "quantitativediscount") {
            $shoppingcardinsert->discount_firstvalue                = $many_buy;
            $shoppingcardinsert->discount_secondvalue               = $many_pay;
        }
        $shoppingcardinsert->selectedvalue_type                     = 'product';
        $shoppingcardinsert->directory_link                         = $request->input('directoryId');
        // if ($request->input('parentcategory')) {
        //     $parentCategory = array();
        //     foreach ($request->input('parentcategory') as $pcatrow) {
        //         $parentCategory[] = $pcatrow;
        //     }
        //     $shoppingcardinsert->search_parent_category            =  implode(",", $parentCategory);    //$request->input('parentcategory');
        // }

        $shoppingcardinsert->search_parent_category                   = $request->input('parentcategory');

        if ($request->input('childcategory')) {
            $childCategory = array();
            foreach ($request->input('childcategory') as $childcatrow) {
                $childCategory[] = $childcatrow;
            }
            $shoppingcardinsert->search_child_category                =  implode(",", $childCategory);    //$request->input('childcategory');
        }
        if ($request->input('shoppingcardtags')) {
            $shoppingcardTags = array();
            foreach ($request->input('shoppingcardtags') as $tagsrow) {
                $shoppingcardTags[] = $tagsrow;
            }
            $shoppingcardinsert->tags                                 =  implode(",", $shoppingcardTags);    //$request->input('offertags');
        }
        $videofile = $request->file('video');
        if ($videofile) {
            $name2 = $videofile->getClientOriginalName();
            $videoName = time() . $name2;
            $videofile->move(public_path('files/shoppingcard/'), $videoName);
            $shoppingcardinsert->video = $videoName;
        } else {
            $shoppingcardinsert->video = $previousVideo;
        }
        $shoppingcardinsert->active_date                        = $request->input('active_date');
        $shoppingcardinsert->expire_date                        = $request->input('expiry_date');
        $shoppingcardinsert->default_duration                   = $request->input('default_status');
        if ($request->input('default_status') == 'yes') {
            $shoppingcardinsert->default_month                  = $request->input('set_months');
        }
        $shoppingcardinsert->status                             = $request->input('shoppingcard_status');
        $shoppingcardinsert->created_by                         = $request->input('createdby');
        $shoppingcardinsert->product_id                         = $pro_id;
        $shoppingcardinsert->save();
        $imgbannerfile = $request->file('shoppingcard_banner_image');
        if ($imgbannerfile) {
            $ext = $imgbannerfile->getClientOriginalExtension();
            $name2 = $imgbannerfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            /*
            $imagepath = 'files/shoppingcard/' . $imageName;
            $image_resize = Image::make($imgbannerfile->getRealPath());
            $image_resize->save(public_path('files/shoppingcard/' . $imageName));
            */

                $imagepath = 'images/shoppingcard/' . $imageName;
                $convertext = "webp";
                $image_resize = Image::make($imgbannerfile->getRealPath())->stream($convertext,50);
                Storage::disk('images')->put('shoppingcard/'.$imageName.'.'.$convertext,$image_resize);

            $productmainimage_insert                            = new ShoppingCardImage;
            $productmainimage_insert->shopping_card_id          = $shopping_card_id;
            $productmainimage_insert->main_image                = "banner";
            $productmainimage_insert->name_thumbnail            = $imageName;
            $productmainimage_insert->name_large                = $imageName;
            $productmainimage_insert->extension                 = $ext;
            $productmainimage_insert->path                      = $imagepath;
            $productmainimage_insert->save();
        }

        $module_id = $request->input('module_id');
        $page_type = $request->input('page_type');
        $shoppingcard_meta_title = $request->input('meta_title_en');
        $shoppingcard_meta_description = $request->input('meta_description_en');

        if (!empty($module_id) && (!empty($page_type))) {
            DirectorySeo::where('module_id', $shopping_card_id)->where('page_type', 'shoppingcard')
                ->update([
                    'page_type'  => 'shoppingcard',
                    'meta_keyword' => $shoppingcard_meta_title??"",
                    'meta_description' => $shoppingcard_meta_description??""
            ]);
        }else{
            $dirtseo = new DirectorySeo;
            $dirtseo->module_id             = $shopping_card_id;
            $dirtseo->page_type             = 'shoppingcard';
            $dirtseo->meta_keyword          = $shoppingcard_meta_title??"";
            $dirtseo->meta_description      = $shoppingcard_meta_description??"";
            $dirtseo->save();
        }


        $imgfile = $request->file('shoppingcard_image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            /*
            $imagepath = 'files/shoppingcard/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            //$image_resize->resize(700, 700);
            $image_resize->save(public_path('files/shoppingcard/' . $imageName));
            */

                $imagepath = 'images/shoppingcard/' . $imageName;
                $convertext = "webp";
                $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);
                Storage::disk('images')->put('shoppingcard/'.$imageName.'.'.$convertext,$image_resize);

            $productmainimage_insert                        = new ShoppingCardImage;
            $productmainimage_insert->shopping_card_id         = $shopping_card_id;
            $productmainimage_insert->main_image        = "main";
            $productmainimage_insert->name_thumbnail    = $imageName;
            $productmainimage_insert->name_large        = $imageName;
            $productmainimage_insert->extension         = $ext;
            $productmainimage_insert->path              = $imagepath;
            $productmainimage_insert->save();
        }

        SeoTranslation::where('module_id', $shopping_card_id)->delete();
        $languages = Helper::getAllLanguages();
        foreach ($languages as $lang) {
            $languageTranslation_id         = $request->input('langtranlation_' . $lang->code);
            $shoppingcard_title             = $request->input('shoppingcard_title_' . $lang->code);
            $seo_title                      = $request->input('meta_title_' . $lang->code);
            $seo_meta_description           = $request->input('meta_description_' . $lang->code);
            if ($request->input('langtranlation_id') == 0) {
                $udircttrans =  new SeoTranslation();
            } else {
                $udircttrans =  SeoTranslation::find($languageTranslation_id);
            }
            $udircttrans->module_id             = $shopping_card_id;
            $udircttrans->language_id           = $lang->id;
            $udircttrans->meta_title            =  isset($seo_title) ? $seo_title : "";
            $udircttrans->meta_keyword          = isset($shoppingcard_title) ? $shoppingcard_title : "";
            $udircttrans->meta_description      = isset($seo_meta_description) ? $seo_meta_description : "";
            $udircttrans->module_name           = 'Shopping Card';
            $udircttrans->save();
        }

        $shoppingcard_description_en = $request->input('shoppingcard_description_en');
        $meta_title_en = $request->input('meta_title_en');
        $meta_description_en = $request->input('meta_description_en');
        $is_not_change_en_language = ShoppingCardTranslation::where('language_id',1)->where('shoppingcard_id',$shopping_card_id)
            ->where('title',$name_en)
            ->where('description',$shoppingcard_description_en)
            ->where('meta_title',$meta_title_en)
            ->where('meta_description',$meta_description_en)
            ->exists();

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();
        if($is_not_change_en_language){
            foreach ($languages as $lang) {
                $shoppingcard_name           = $request->input('shoppingcard_title_' . $lang->code);
                $shoppingcard_des_content    = $request->input('shoppingcard_description_' . $lang->code);
                $shoppingcard_meta_title     = $request->input('meta_title_' . $lang->code);
                $shoppingcard_meta_description = $request->input('meta_description_' . $lang->code);
                $shoppingcard_meta_keyword   = $request->input('meta_keyword_' . $lang->code);
                $languageTranslation_id      = $request->input('langtranlation_' . $lang->code);

                    $shoppingcardshortinsert =  ShoppingCardTranslation::find($languageTranslation_id);
                    $shoppingcardshortinsert->shoppingcard_id           = $shopping_card_id;
                    $shoppingcardshortinsert->language_id               = $lang->id;
                    $shoppingcardshortinsert->title                     = isset($shoppingcard_name) ? $shoppingcard_name : $name_en;
                    $shoppingcardshortinsert->description               = isset($shoppingcard_des_content) ? $shoppingcard_des_content : $request->input('shoppingcard_description_en');
                    $shoppingcardshortinsert->meta_title                = isset($shoppingcard_meta_title) ? $shoppingcard_meta_title : $request->input('meta_title_en');
                    $shoppingcardshortinsert->meta_description          = isset($shoppingcard_meta_description) ? $shoppingcard_meta_description : $request->input('meta_description_en');
                    $shoppingcardshortinsert->meta_keyword              = isset($shoppingcard_meta_keyword) ? $shoppingcard_meta_keyword : "";
                    $shoppingcardshortinsert->save();
            }
        }else{

            ShoppingCardTranslation::where('shoppingcard_id', $shopping_card_id)->delete();
            foreach ($languages as $lang) {

                $shoppingcard_name                  = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
                $shoppingcard_des_content           = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('shoppingcard_description_en')??'');
                $shoppingcard_meta_title            = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_title_en')??'');
                $shoppingcard_meta_description      = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_description_en')??'');
                $shoppingcard_meta_keyword          = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_keyword_en')??'');

                    $shoppingcardshortinsert = new ShoppingCardTranslation;
                    $shoppingcardshortinsert->shoppingcard_id           = $shopping_card_id;
                    $shoppingcardshortinsert->language_id               = $lang->id;
                    $shoppingcardshortinsert->title                     = isset($shoppingcard_name) ? $shoppingcard_name : $name_en;
                    $shoppingcardshortinsert->description               = isset($shoppingcard_des_content) ? $shoppingcard_des_content : $request->input('shoppingcard_description_en');
                    $shoppingcardshortinsert->meta_title                = isset($shoppingcard_meta_title) ? $shoppingcard_meta_title : $request->input('meta_title_en');
                    $shoppingcardshortinsert->meta_description          = isset($shoppingcard_meta_description) ? $shoppingcard_meta_description : $request->input('meta_description_en');
                    $shoppingcardshortinsert->meta_keyword              = isset($shoppingcard_name) ? $shoppingcard_name : "";
                    $shoppingcardshortinsert->save();
            }

        }


        $imgfilegal = $request->file('shoppingcardgal_image');
        if ($imgfilegal) {
            foreach ($imgfilegal as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                /*
                $imagepath = 'files/shoppingcard/' . $imageName;
                $image_resize = Image::make($imgrow->getRealPath());
                //$image_resize->resize(700, 700);
                $image_resize->save(public_path('files/shoppingcard/' . $imageName));
                */

                    $imagepath = 'images/shoppingcard/' . $imageName;
                    $convertext = "webp";
                    $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                    Storage::disk('images')->put('shoppingcard/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                            = new ShoppingCardImage;
                $productmainimage_insert->shopping_card_id          = $shopping_card_id;
                $productmainimage_insert->main_image                = "galimg";
                $productmainimage_insert->name_thumbnail            = $imageName;
                $productmainimage_insert->name_large                = $imageName;
                $productmainimage_insert->extension                 = $ext;
                $productmainimage_insert->path                      = $imagepath;
                $productmainimage_insert->save();
            }
        } else {
            $thumb_hidden_img = $request->input('thumb_hidden_img');
            $hiddenarray_thumb_hidden_img = explode(',', $thumb_hidden_img);
            $details = ShoppingCardImage::where('main_image', 'galimg')->where('shopping_card_id', $shopping_card_id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->name_thumbnail             = $hiddenarray_thumb_hidden_img[$key];
                    $detail->name_large                 = $hiddenarray_thumb_hidden_img[$key];
                    $detail->path                       = 'images/shoppingcard/' . $hiddenarray_thumb_hidden_img[$key];
                    $detail->save();
                }
            }
        }
        return redirect('admin/shoppingcard-edit/' . $shopping_card_id)->with('msg', 'Shopping Card has been updated');
    }

    public function destroy($id)
    {
        $data = ShoppingCardImage::where("shopping_card_id", $id)->get("*");
        foreach($data as $rowdata){
            $image_name = $rowdata->name_thumbnail.".webp";
            $image_path = public_path('images/shoppingcard/'.$image_name);
            if(file_exists($image_path)){
              unlink($image_path);
            }
        }
        // ShoppingCard::where('id', $id)->update(['deleted' => '1']);
        ShoppingCard::where('id', $id)->delete();
        return redirect('admin/shoppingcard')->with('msg', 'Shopping Card deleted successfully');
    }
    public static function get_bannerImage($shoppingcardId)
    {
        $get_bannerimageData = DB::table('shopping_card_image')->select('*')->where('shopping_card_id', $shoppingcardId)->where('main_image', "banner")->get()->toArray();
        return $get_bannerimageData;
    }

    public static function get_mainImage($shoppingcardId)
    {
        $get_mainimageData = DB::table('shopping_card_image')->select('*')->where('shopping_card_id', $shoppingcardId)->where('main_image', "main")->get()->toArray();
        return $get_mainimageData;
    }
    public static function get_galleryImage($shoppingcardId)
    {
        $get_mainimageData = DB::table('shopping_card_image')->select('*')->where('shopping_card_id', $shoppingcardId)->where('main_image', "galimg")->get()->toArray();
        return $get_mainimageData;
    }
    public function deleteGalleryImageDelete(Request $request)
    {
        $data = ShoppingCardImage::find($request->id);
        $image_name = $data->name_thumbnail.".webp";
        $image_path = public_path('images/shoppingcard/'.$image_name);
        if(file_exists($image_path)){
          unlink($image_path);
        }
        $data = ShoppingCardImage::find($request->id)->delete();
        return response()->json($data);
    }

    public function checkShoppingcardSlug(Request $request)
    {
        $data = array();
        $data['seoChangeData'] = Helper::getSeoUrl($request->catname);
        if($request->directory=="shoppingcard"){
            $shoppingcardypeDetails = DB::table('shopping_card')->where('slug',$data['seoChangeData'])->orderBy('id', 'desc')->get()->toArray();
        }
        $data['shoppingcardypeDetails'] = "";
        if(!empty($shoppingcardypeDetails)){
            $datainmatch = $shoppingcardypeDetails[0]->slug;
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

            $data['shoppingcardypeDetails'] = $checkdata;
        }
         return response()->json($data);
    }
}
