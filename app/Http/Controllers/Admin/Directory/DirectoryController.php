<?php

namespace App\Http\Controllers\Admin\Directory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Mail\DirectoryStatusMail;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Diectory;
use App\Models\DiectoryTranslation;
use App\Models\DiectoryLocation;
use App\Models\State;
use App\Models\City;
use App\Models\DirectoryAmenity;
use App\Models\DirectorySeo;
use App\Models\DirectorySeoTranslation;
use App\Models\DirectoryContact;
use App\Models\DirectoryMedia;
use App\Models\DirectoryMediaCover;
use App\Models\DirectoryMediagallery;
use App\Models\DirectoryType;
use App\Models\DirectoryMediaListing;
use App\Models\DirectoryMediaTrademarks;
use App\Models\DirectorySchedual;
use App\Models\DirectoryCategory;
use App\Models\Assortmentlinks;
use App\Models\Websitelinks;
use App\Models\DirectoryTags;
use App\Models\DirectoryOwnTags;
use App\Models\Tags;
use App\Models\DirectoryCustomHoliday;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Voucher;
use App\Models\Giftvoucher;
use App\Models\DirectoryNewOwnTags;
use App\Models\DirectoryCreateOwnTags;
use App\Models\AmenityCategory;
use App\Models\Amenity;
use App\Models\client\Order;
use App\Models\Trademarks;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\DirectoryExceptionHoliday;
use App\Models\DirectoryClaim;
use Illuminate\Support\Facades\DB;
use App\Models\DirectorySubCategory;
use App\Models\TrademarkCategory;
use App\Models\Category;
use App\Models\ShoppingCard;
use App\Models\Events;
use App\Models\Blog;
use App\Models\client\Wishlistdirectory;
use App\Models\DirectoryTypeGallery;
use App\Models\DirectoryTypePdfs;
use App\Models\ExceptionalHolidayTemplateDetails;
use App\Models\HolidayTemplateDetails;
use App\Models\Plans;
use App\Models\PlanSubscription;
use App\Models\PlanSubscriptionUsage;
use App\Models\tagTranslation;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\File;

class DirectoryController extends Controller
{
    public function index(Request $request)
    {
        $data['parent_category']    = Helper::getAllParentCategory();
        if ($request->input('search')) {
            $query                      = $request->all();
            $searchQuery                = $request->input('searchtext');
            $data['searchQuery']        = $request->input('searchtext');
            $category                = $request->input('category');
            $subcategory                = $request->input('subcategory');
            $tagsid                = $request->input('tagsid');
            $status                = $request->input('status');

            //DB::enableQueryLog();
            $data['directoryDetails']   = DB::table('directory as dt')
            ->select(
                'dt.*',
                'dt_ln.country_id',
                'dt_ln.directory_id',
                'dt_cat.category_id AS dir_cat_id',
                'dt_sub_cat.sub_category_id AS dir_sub_cat_id',
                )
            ->where('deleted', '0');

            $data['directoryDetails'] = $data['directoryDetails']->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id');
            $data['directoryDetails'] = $data['directoryDetails']->leftjoin('directory_category as dt_cat', 'dt.id', '=', 'dt_cat.directory_id');
            $data['directoryDetails'] = $data['directoryDetails']->leftjoin('directory_sub_category as dt_sub_cat', 'dt.id', '=', 'dt_sub_cat.directory_id');

            if (!empty($tagsid)) {
                $data['directoryDetails'] = $data['directoryDetails']->addSelect('directory_tags.tags_id');
                $data['directoryDetails'] = $data['directoryDetails']->leftjoin('directory_tags', 'dt.id', '=', 'directory_tags.directory_id');
            }

            $data['directoryDetails']   =    $data['directoryDetails']->where(function ($query) use ($searchQuery,$category,$subcategory,$tagsid,$status) {

                if (!empty($category) && !empty($searchQuery) ) {
                    $query->where('dt_cat.category_id', $category);
                    $query->where('title', 'like', '%' . $searchQuery . '%');
                }else if (!empty($category) && !empty($status) ) {
                    $query->where('dt_cat.category_id', $category);
                    $query->where('status', 'like', $status);
                }else if ( !empty($status) && !empty($searchQuery)) {
                    $query->where('title', 'like', '%' . $searchQuery . '%');
                    $query->where('status', 'like', $status);
                }else if ( !empty($status) ) {
                    $query->orWhere('status', 'like', $status);
                }else if ( !empty($searchQuery) ) {
                    $query->orWhere('title', 'like', '%' . $searchQuery . '%');
                    $query->orWhere('country_id', 'like', '%' . $searchQuery . '%');
                }else if (!empty($category) && empty($subcategory) && empty($tagsid) && empty($status)) {
                        $query->where('dt_cat.category_id', $category);
                }else if (!empty($category) && !empty($subcategory) && empty($tagsid) && empty($status)) {
                    $query->where('dt_cat.category_id', $category);
                    $query->where('dt_sub_cat.sub_category_id', $subcategory);
                }else if (!empty($category) && !empty($subcategory) && !empty($tagsid) && empty($status)) {
                    $query->where('dt_cat.category_id', $category);
                    $query->where('dt_sub_cat.sub_category_id', $subcategory);
                    $query->where('directory_tags.tags_id', $tagsid);
                }else if (!empty($category) && !empty($subcategory) && !empty($tagsid) && !empty($status)) {
                    $query->where('dt_cat.category_id', $category);
                    $query->where('dt_sub_cat.sub_category_id', $subcategory);
                    $query->where('directory_tags.tags_id', $tagsid);
                    $query->where('status', $status);
                }else if (!empty($category) && !empty($subcategory) && empty($tagsid) && !empty($status)) {

                    $query->where('dt_cat.category_id', $category);
                    $query->where('dt_sub_cat.sub_category_id', $subcategory);
                    $query->where('status', 'like', $status);
                }

            });

            $data['directoryDetails']   =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt.id', 'desc');
            $data['directoryDetails']   =    $data['directoryDetails']->paginate(20);
            return view('admin.directory.index', ['data' => $data, 'query' => $query]);

        } else {
            $data['directoryDetails']   = DB::table('directory as dt')
                ->select('dt.*', 'dt_ln.country_id', 'dt_ln.directory_id')
                ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')->where('deleted', '0');
            $data['directoryDetails']   =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt.id', 'desc');
            $data['directoryDetails']   =    $data['directoryDetails']->paginate(20);
            return view('admin.directory.index', ['data' => $data]);
        }
    }

    public function changeDirectoryStatus(Request $request)
    {
        $product = Diectory::find($request->id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success' => 'Status change successfully.']);
    }


    public function create()
    {
        $data['languagesData']          = Helper::getAllLanguages();            /* get languages */
        $data['parent_category']        = Helper::getAllParentCategory();       /* get Category */
        $data['child_category']         = Helper::getAllChildCategory();      /* get Child Category */
        $data['country']                = Helper::getCountryAllData();          /* get country */
        $data['amenity']                = Helper::getAmenity();                 /* get amenity */
        // $data['trademarkcategory']   = Helper::getTrademarkCategory();       /* get Trademark Category */
        $data['trademarks']             = Helper::getTrademarks();              /* get amenity */
        $data['type']                   = Helper::getType();                    /* get category whose type is listing */
        $data['tags']                   = Helper::getTagsData();                /* get tags */
        $data['owntags']                = Helper::getOwnTagsData();             /* get owntags */
        $data['directoryowntags']       = Helper::getdirectoryOwnTagsData();    /* get directory owntags */
        $data['normaldaysdata']         = DB::table('normaltemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();
        $data['publicdaysdata']         = DB::table('holidaytemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();
        $data['exeptionaldaysdata']     = DB::table('exeptionalholidaytemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();
        $data['amenityCategoryData']    = Helper::getamenityCategoryData();             /* get amenityCategoryData */
        $data['trademarkcategory']      = TrademarkCategory::where('deleted','0')->where('directory_id', 0)->orderBy('id', 'desc')->get();       /* get Trademark Category */
        $data['vendorNameId']           = User::select('id','name', 'last_name', 'email')->where('directory_id',0)->where('role_id',1)->where('status',1)->get()->toArray();

        return view('admin.directory.create', ['data' => $data]);
    }

    public function submit(Request $request)
    {
        //dd($request->all());

        $name_en                = $request->input('name_en');
        $description_en         = $request->input('description_en');
        $seo_en                 = $request->input('seo_en');
        $weoffer_en             = $request->input('weoffer_en');
        $aboutus_en             = $request->input('aboutus_en');
        $email                  = $request->input('email');
        $username               = strstr($email, '@', true);

        $this->validate($request, [
            'name_en'           => 'required',
            'email'             => 'required|email|unique:directory_contact,email',
            'listing_thumb'     => 'array|max:20',
            'listing_thumb.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'listing_cover'     => 'array|max:10',
            'listing_cover.*'   => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'listing_gallery'   => 'array|max:20',
            'listing_gallery.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'phone_number'      => 'numeric|digits:10',
            'address'           => 'required',
            'category'          => 'required',
            'sub_category'      => 'required',
            'trademarkcategory' => 'required',

        ],
        [
            'listing_thumb.*.image' => 'Images must be an extension of JPG, JPEG, PNG, SVG, GIF.',
            'listing_cover.*.image' => 'Images must be an extension of JPG, JPEG, PNG, SVG, GIF.',
            'listing_gallery.*.image' => 'Images must be an extension of JPG, JPEG, PNG, SVG, GIF.',
            'category.required' => 'SEO category field is required',
            'sub_category.required' => 'SEO sub category field is required',
        ]
        );

        $directory                                  = new Diectory;
        $directory->title                           = (isset($name_en)) ? $name_en : "";
        $directory->slug                            = $seo_en??Helper::getSeoUrl($name_en);
        $directory->description                     = isset($description_en) ? $description_en : "";
        $directory->feature_type                    = $request->input('featuretype');
        $directory->google_analytics_id             = $request->input('google_ana_id');
        $directory->schedule_normal_comment         = $request->input('add_normal_comment');
        $directory->created_by                      = $request->input('createdby');
        $directory->is_normaltemplate               = $request->input('normaldays');
        $directory->is_normaltemplate_id            = $request->input('normaldaytemplateselect');
        $directory->is_publictemplate               = $request->input('publicdays');
        $directory->is_publictemplate_id            = $request->input('publicdaystemplateselect');
        $directory->is_exceptionaltemplate          = $request->input('exception_days');
        $directory->is_exceptionaltemplate_id       = $request->input('exception_daystemplateselect');
        $directory->popular                         = $request->input('popular')??0;
        $directory->save();
        $direct_id = $directory->id;

        if ($request->hasFile('company_logo')) {
            $company_logo = $request->file('company_logo');
            $ext = strtolower($company_logo->getClientOriginalExtension());
            $allowed = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'jfif'];
            if (!in_array($ext, $allowed)) {
                return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
            }
            $imageName = Helper::getSeoUrl(time() . pathinfo($company_logo->getClientOriginalName(), PATHINFO_FILENAME));
            $imagePath = "images/directory/{$imageName}.webp";
            $image = Image::make($company_logo->getRealPath())->stream('webp', 50);
            Storage::disk('images')->put("directory/{$imageName}.webp", $image);
            Diectory::where('id', $direct_id)->update(['company_logo' => $imagePath]);
        }

        /*--------------- Checking user --------------------*/
        if (User::where('email', '=', $email)->exists()) {

            $userid                     = User::where('email', '=', $email)->first('id');
            $userData                   = User::findOrFail($userid->id);
            $userData->directory_id     = $direct_id;
            $userData->role_id          = 1;
            $userData->profile_status   = 1;
            $userData->temp             = 0;
            $userData->save();
        }

        $packagedata                            = Plans::where('slug', '=', 'free')->first();
        $planid                                 = $packagedata->id;
        $package_name                           = $packagedata->name;
        $PlanSubscription                       = new PlanSubscription();
        $PlanSubscription->subscriber_type      =   'App\Models\User';
        $PlanSubscription->subscriber_id        =   $direct_id;
        $PlanSubscription->plan_id              =   $planid;
        $PlanSubscription->slug                 =   Helper::getSeoUrl($package_name);
        $PlanSubscription->name                 =   $package_name;
        $PlanSubscription->save();
        $subscriptionid = $PlanSubscription->id;

        $feature = app('rinvex.subscriptions.plan_feature')->where('plan_id', $planid)->get();
        foreach($feature as $val){
            $plansFea = new PlanSubscriptionUsage();
            $plansFea->subscription_id          = $subscriptionid;
            $plansFea->feature_id               = $val->id;
            $plansFea->name                     = $val->slug;
            $plansFea->used                     = $val->value??0;
            $plansFea->save();
        }

        /* Directory Category Starts */

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();

        $categories                         = $request->input('sub_category');
        $categoryInsertData                 = new DirectoryCategory;
        $categoryInsertData->directory_id   = $direct_id;
        $categoryInsertData->category_id    = $request->input('category');
        $categoryInsertData->save();

        $sub_categories = $request->input('sub_category');
            if (!empty($sub_categories)) {
                foreach ($sub_categories as $cat => $value) {
                    $categoryInsertData                     = new DirectorySubCategory;
                    $categoryInsertData->directory_id       = $direct_id;
                    $categoryInsertData->sub_category_id    = $value;
                    $categoryInsertData->save();

                    //$categoryChildData = DB::table('category')->select('id','name')->where('parent_id','<>',0)->where('status','Active')->where('deleted','0')->where('id', $value)->first();
                    //Tags::where('category_id',$request->input('category'))->where('child_cat_id',$value)->where('tags',$categoryChildData->name)->delete();
                    // Tags::where('directory_id',$direct_id)->where('tags',$categoryChildData->name)->delete();
                    //     $categoryInsertTags = new Tags;
                    //     $categoryInsertTags->category_id   = $request->input('category');
                    //     $categoryInsertTags->child_cat_id   = $value;
                    //     $categoryInsertTags->tags   = $categoryChildData->name;
                    //     $categoryInsertTags->directory_id  = $direct_id;
                    //     $categoryInsertTags->save();

                        // tagTranslation::where('directory_id',$direct_id)->where('title',$categoryChildData->name)->delete();
                        // foreach ($languages as $lang) {
                        //     $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($categoryChildData->name);
                        //     $description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($categoryChildData->name);
                        //     $title_name                 = isset($title_name) ? $title_name : $categoryChildData->name;

                        //     $tagtrans                  = new tagTranslation();
                        //     $tagtrans->tag_id          = $categoryInsertTags->id;
                        //     $tagtrans->category_id     = $request->input('category');
                        //     $tagtrans->language_id     = $lang->id;
                        //     $tagtrans->title           = $title_name;
                        //     $tagtrans->description     = $description_code;
                        //     $tagtrans->seo_url         = Helper::getSeoUrl(isset($title_name));
                        //     $tagtrans->directory_id  = $direct_id;
                        //     $tagtrans->save();
                        // }


                        // $simpletagsInsertData                   = new DirectoryTags;
                        // $simpletagsInsertData->directory_id     = $direct_id;
                        // $simpletagsInsertData->tags_id          = $categoryInsertTags->id;
                        // $simpletagsInsertData->save();

                }
            }
        /* Directory Category Ends */

        /* Directory Tags Starts */
        $simpletags = $request->input('simpletags');
        if (!empty($simpletags)) {
            foreach ($simpletags as $tgs => $value) {
                $simpletagsInsertData                   = new DirectoryTags;
                $simpletagsInsertData->directory_id     = $direct_id;
                $simpletagsInsertData->tags_id          = $value;
                $simpletagsInsertData->save();

                $gettagsName = Tags::where('id', $value)->where('status', 'Active')->first();
                    if ($gettagsName) {
                        foreach ($languages as $lang) {
                            $title_name = $tr->setSource('en')->setTarget($lang->code)->translate($gettagsName->tags);
                            $description_code = $tr->setSource('en')->setTarget($lang->code)->translate($gettagsName->tags);
                            $title_name = $title_name ?: $gettagsName->tags;
                            $tagtrans = new tagTranslation();
                            $tagtrans->tag_id = $value;
                            $tagtrans->category_id = $request->input('category');
                            $tagtrans->language_id = $lang->id;
                            $tagtrans->title = $title_name;
                            $tagtrans->description = $description_code;
                            $tagtrans->seo_url = Helper::getSeoUrl($title_name);
                            $tagtrans->save();
                        }
                    }

            }
        }
        /* Directory Tags Ends */


        /* Directory Trademark Starts */
        $trademarks = $request->input('trademark');
        if (!empty($trademarks)) {
            foreach ($trademarks as $cat => $value) {
                $trademarksInsertData                           = new DirectoryMediaTrademarks;
                $trademarksInsertData->directory_id             = $direct_id;
                $trademarksInsertData->trademarkcat_id          = $request->input('trademarkcategory');
                $trademarksInsertData->trademark_id             = $value;
                $trademarksInsertData->save();
            }
        }else{
            $trademarksInsertData                           = new DirectoryMediaTrademarks;
            $trademarksInsertData->directory_id             = $direct_id;
            $trademarksInsertData->trademarkcat_id          = $request->input('trademarkcategory');
            $trademarksInsertData->trademark_id             = $request->input('trademark')??'0';
            $trademarksInsertData->save();
        }
        /* Directory Trademark Ends */

        /* Directory Own Tags Starts */
        $owntags = $request->input('owntags');
        if (!empty($owntags)) {
            foreach ($owntags as $owntgs => $value) {
                $owntagsInsertData                      = new DirectoryOwnTags;
                $owntagsInsertData->directory_id        = $direct_id;
                $owntagsInsertData->owntags_id          = $value;
                $owntagsInsertData->save();
            }
        }
        $newOwnTagsids = array();
        $createnewowntag = $request->input('createnewowntag');
        if ($createnewowntag != "") {
            $owntagsName = explode(',', $createnewowntag);
            //echo "<pre>"; print_r($tagsName);die;
            foreach ($owntagsName as $tag) {
                $tags_insert                            = new DirectoryCreateOwnTags;
                $tags_insert->directory_id              = $direct_id;
                $tags_insert->tag_name                  = $tag;
                $tags_insert->add_to_directory_page     = 'yes';
                $tags_insert->created_by                = $request->input('createdby');
                $tags_insert->save();
                $lastenter_id                           = $tags_insert->id;
                $newOwnTagsids[]                        = $lastenter_id;
            }
        }
        if (!empty($newOwnTagsids)) {
            foreach ($newOwnTagsids as $owntgs => $value) {
                $owntagsInsertData                      = new DirectoryNewOwnTags;
                $owntagsInsertData->directory_id        = $direct_id;
                $owntagsInsertData->owntags_id          = $value;
                $owntagsInsertData->save();
            }
        }
        /* Directory Own Tags Ends */

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        foreach ($languages as $lang) {
        // $title_name                 = $request->input('name_' . $lang->code);
        // $description_code           = $request->input('description_' . $lang->code);
        // $seo_val                    = $request->input('seo_' . $lang->code);
        // $weoffer_val                = $request->input('weoffer_' . $lang->code);
        // $aboutus_val                = $request->input('aboutus_' . $lang->code);

            $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
            $description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($description_en??'');
            $seo_val                    = $tr->setSource('en')->setTarget($lang->code)->translate($seo_en??'');
            $weoffer_val                = $tr->setSource('en')->setTarget($lang->code)->translate($weoffer_en??'');
            $aboutus_val                = $tr->setSource('en')->setTarget($lang->code)->translate($aboutus_en??'');

            $dircttrans = new DiectoryTranslation;
            $dircttrans->directory_id  = $direct_id;
            $dircttrans->language_id  = $lang->id;
            $dircttrans->title        = isset($title_name) ? $title_name : $name_en;
            $dircttrans->description  = isset($description_code) ? $description_code : $description_en;
            $dircttrans->seo_url      = isset($seo_val) ? $seo_val : $seo_en;
            $dircttrans->we_offer     = isset($weoffer_val) ? $weoffer_val : $weoffer_en;
            $dircttrans->about_us     = isset($aboutus_val) ? $aboutus_val : $aboutus_en;
            $dircttrans->save();
        }
        /* directory Location start */
        $countryId = $request->input('country_id');
        $stateId = $request->input('state_id');
        $cityId = $request->input('city_id');
        $lati = $request->input('latitude');
        $longi = $request->input('longitude');
        $dirctlocation                          = new DiectoryLocation;
        $dirctlocation->directory_id            = $direct_id;
        $dirctlocation->country_id              = (isset($countryId)) ? $countryId : 0;
        $dirctlocation->state_id                = (isset($stateId)) ? $stateId : 0;
        $dirctlocation->city_id                 = (isset($cityId)) ? $cityId : 0;
        $dirctlocation->address                 = $request->input('address');
        $dirctlocation->place                   = $request->input('place');
        $dirctlocation->city_name               = $request->input('city_id');
        $dirctlocation->zipcode                 = $request->input('zipcode');
        $dirctlocation->latitude                = (isset($lati)) ? $lati : 0;
        $dirctlocation->longitude               = (isset($longi)) ? $longi : 0;
        $dirctlocation->save();
        /* directory Location end */
        /* directory Amenities start */
        $amenity = $request->input('amenities');
        if (isset($amenity)) {
            $dirtamenity = new DirectoryAmenity;
            $dirtamenity->directory_id  = $direct_id;
            $dirtamenity->amenitycat_id  = implode(",", $request->input('amenitycat_id'));
            $dirtamenity->amenities_id  = implode(",", $amenity);
            $dirtamenity->typeofdata  = 'existing';
            $dirtamenity->save();
        }
        /* directory Amenities END */
        /* directory MEDIA START */
        $payablevalue = $request->input('payimage');
        if ($payablevalue != 'payable') {
            $payval = 0;
        } else {
            $payval = 1;
        }
        $dirtmedia = new DirectoryMedia;
        $dirtmedia->directory_id    =   $direct_id;
        $dirtmedia->payable         =   $payval;
        $dirtmedia->video_provider  =   $request->input('video_provider');
        $dirtmedia->video_url       =   $request->input('videourl');
        $dirtmedia->save();

        if ($request->hasFile('listing_thumb')) {
            $imgfile = $request->file('listing_thumb');
            $limg = 0;
            $hidenarry = $request->input('array_thumb_listing');

            if (!is_array($hidenarry[0])) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
                $hidenarry = [];
            }
            foreach ($imgfile as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                $imagepath = 'images/directory/' . $imageName;

                /*$image_resize = Image::make($imgrow->getRealPath());
                $image_resize->save(public_path('images/directory/' .$imageName));
                */

                    $convertext = "webp";
                    $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                    Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                $dirtmediathumb = new DirectoryMediaListing;
                $dirtmediathumb->directory_id    = $direct_id;
                $dirtmediathumb->position        = !empty($hidenarry[$limg]) ? $hidenarry[$limg] : 0;
                $dirtmediathumb->name_thumbnail  = $imageName;
                $dirtmediathumb->name_large      = $imageName;
                $dirtmediathumb->extension       = $ext;
                $dirtmediathumb->path            = $imagepath;
                $dirtmediathumb->payable        = $payval;
                $dirtmediathumb->save();
                $limg++;
            }
        }

        if ($request->hasFile('listing_cover')) {
            $imgfile = $request->file('listing_cover');
            $i = 0;
            $hidenarry = $request->input('array_cover_listing');
            if (is_array($hidenarry)) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
            }
            foreach ($imgfile as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                $imagepath = 'images/directory/' . $imageName;
                /*$image_resize = Image::make($imgrow->getRealPath());
                $image_resize->save(public_path('files/directory/' .$imageName));*/

                $convertext = "webp";
                $image_resize = Image::make($imgrow->getRealPath());
                $image_resize->resize(960, 450);
                $image_resize->save(public_path('images/directory/'.$imageName.'.'.$convertext,$image_resize));
                // $convertext = "webp";
                // $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                // Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                $dirtmediacover = new DirectoryMediaCover;
                $dirtmediacover->directory_id    = $direct_id;
                $dirtmediacover->position        = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                $dirtmediacover->name_thumbnail  = $imageName;
                $dirtmediacover->name_large      = $imageName;
                $dirtmediacover->extension       = $ext;
                $dirtmediacover->path            = $imagepath;
                $dirtmediacover->payable        = $payval;
                $dirtmediacover->save();
                $i++;
            }
        }
        if ($request->hasFile('listing_gallery')) {

            $imgfilegal = $request->file('listing_gallery');
            $i = 0;
            $hidenarry = $request->input('array_gallery_listing');
            if (is_array($hidenarry)) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
            }
            foreach ($imgfilegal as $imgalrow) {
                $ext                                = $imgalrow->getClientOriginalExtension();
                $name3                              = $imgalrow->getClientOriginalName();
                $imageName2                         = time() . $name3;
                $imageName                          = Helper::getSeoUrl($imageName2);
                $imagepath                          = 'images/directory/' . $imageName;
                /*$image_resize                     = Image::make($imgalrow->getRealPath());
                $image_resize->save(public_path('files/directory/' .$imageName));*/

                    $convertext = "webp";
                    $image_resize = Image::make($imgalrow->getRealPath())->stream($convertext,50);
                    Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                $dirtmediagallery                   = new DirectoryMediagallery;
                $dirtmediagallery->directory_id     = $direct_id;
                $dirtmediagallery->position         = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                $dirtmediagallery->name_thumbnail   = $imageName;
                $dirtmediagallery->name_large       = $imageName;
                $dirtmediagallery->extension        = $ext;
                $dirtmediagallery->path             = $imagepath;
                $dirtmediagallery->payable          = $payval;
                $dirtmediagallery->save();
                $i++;
            }
        }
        /* directory MEDIA END */

        /* Seo Entries start */
        $seo_title_en                   = $request->input('meta_keywords_en');
        $seo_meta_description_en        = $request->input('meta_description_en');
        $dirtseo                        = new DirectorySeo;
        //$dirtseo->directory_id        = $direct_id;
        $dirtseo->module_id             = $direct_id;
        $dirtseo->page_type             = 'directory';
        $dirtseo->meta_keyword          = (isset($seo_title_en)) ? $seo_title_en : "";
        $dirtseo->meta_description      = (isset($seo_meta_description_en)) ? $seo_meta_description_en : "";
        $dirtseo->save();

        // foreach ($languages as $lang) {
        //     $seo_title = $request->input('meta_keywords_' . $lang->code);
        //     $seo_meta_description = $request->input('meta_description_' . $lang->code);

        //     $dircttrans = new DirectorySeoTranslation;
        //     $dircttrans->directory_id  = $direct_id;
        //     $dircttrans->language_id  = $lang->id;
        //     $dircttrans->meta_title        = $seo_title ?? "";
        //     $dircttrans->meta_keyword        = isset($seo_title) ? $seo_title : "";
        //     $dircttrans->meta_description  = isset($seo_meta_description) ? $seo_meta_description : "";
        //     $dircttrans->save();
        // }
        /* Seo Entries End */

        if ($request->input('normaldays') == "no") {
            /* START SCHEDUAL ENTRIES */
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $key => $value) {
                $count = count($request->input($value . "_hours_min"));
                $minHours = $request->input($value . "_hours_min");
                $maxHours = $request->input($value . "_hours_max");
                for ($i = 0; $i < $count; $i++) {
                    $dirctschedual = new DirectorySchedual;
                    $dirctschedual->directory_id    = $direct_id;
                    $dirctschedual->day             = $value;
                    $dirctschedual->is_closed       = $request->input($value . "_is_closed") ? '1' : '0';
                    $dirctschedual->open_time       = $minHours[$i];
                    $dirctschedual->close_time      = $maxHours[$i];
                    $dirctschedual->save();
                }
            }
        }
        if ($request->input('normaldays') == "yes") {
            /* START SCHEDUAL ENTRIES */
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $key => $value) {
                $count = count($request->input($value . "_template_hours_min"));
                $minHours = $request->input($value . "_template_hours_min");
                $maxHours = $request->input($value . "_template_hours_max");
                for ($i = 0; $i < $count; $i++) {
                    $dirctschedual = new DirectorySchedual;
                    $dirctschedual->directory_id    = $direct_id;
                    $dirctschedual->day             = $value;
                    $dirctschedual->is_closed       = $request->input($value . "_template_is_closed") ? '1' : '0';
                    $dirctschedual->open_time       = $minHours[$i];
                    $dirctschedual->close_time      = $maxHours[$i];
                    $dirctschedual->save();
                }
            }
        }
        if ($request->input('publicdays') == "no") {
            /* Holiday time */
            $custom_date = $request->input('custom_date');
            $note = $request->input('note');
            $time_hours_min = $request->input('time_hours_min');
            $time_hours_max = $request->input('time_hours_max');
            $final_custom_time = [];
            if (count($time_hours_min)) {
                for ($i = 0; $i < count($time_hours_min); $i++) {
                    $arr['open_time'] = $time_hours_min[$i];
                    $arr['close_time'] = $time_hours_max[$i];
                    $final_custom_time[] = $arr;
                }
            }
            $final_custom_time = json_encode($final_custom_time);
            $dirctcustom_holiday = new DirectoryCustomHoliday;
            $dirctcustom_holiday->directory_id = $direct_id;
            $dirctcustom_holiday->custom_date = $custom_date;
            $dirctcustom_holiday->note = $note;
            $dirctcustom_holiday->custom_time = $final_custom_time;
            $dirctcustom_holiday->save();
        }
        if ($request->input('publicdays') == "yes") {
            $ids = $request->input('template_id');
            if(count($ids)){
                foreach($ids as $val) {
                    $time_hours_min = $request->input($val.'_temp_time_hours_min');
                    $time_hours_max = $request->input($val.'_temp_time_hours_max');
                    $final_custom_time = [];
                    if (count($time_hours_min)) {
                        for ($j = 0; $j < count($time_hours_min); $j++) {
                            $arr['open_time'] = $time_hours_min[$j];
                            $arr['close_time'] = $time_hours_max[$j];
                            $final_custom_time[] = $arr;
                        }
                    }
                    $final_custom_time = json_encode($final_custom_time);
                    $dirctcustom_holiday = new DirectoryCustomHoliday;
                    $dirctcustom_holiday->directory_id = $direct_id;
                    $dirctcustom_holiday->custom_date = $request->input($val.'_custom_date');
                    $dirctcustom_holiday->note = $request->input($val.'_note');
                    $dirctcustom_holiday->custom_time = $final_custom_time;
                    $dirctcustom_holiday->save();
                }
            }

        }
        if ($request->input('exception_days') == "no") {
            /* Holiday time */
            $custom_date = $request->input('exception_date');
            $note = $request->input('exception_note');
            $time_hours_min = $request->input('exception_time_hours_min');
            $time_hours_max = $request->input('exception_time_hours_max');
            $final_custom_time = [];
            if (count($time_hours_min)) {
                for ($i = 0; $i < count($time_hours_min); $i++) {
                    $arr['open_time'] = $time_hours_min[$i];
                    $arr['close_time'] = $time_hours_max[$i];
                    $final_custom_time[] = $arr;
                }
            }
            $final_custom_time = json_encode($final_custom_time);
            $dirctcustom_holiday = new DirectoryExceptionHoliday;
            $dirctcustom_holiday->directory_id = $direct_id;
            $dirctcustom_holiday->custom_date = $custom_date;
            $dirctcustom_holiday->note = $note;
            $dirctcustom_holiday->custom_time = $final_custom_time;
            $dirctcustom_holiday->save();
        }
        if ($request->input('exception_days') == "yes") {
            $ids = $request->input('exception_template_id');
            if(count($ids)){
                foreach($ids as $val) {
                    $time_hours_min = $request->input($val.'_exc_time_hours_min');
                    $time_hours_max = $request->input($val.'_exc_time_hours_max');
                    $final_custom_time = [];
                    if (count($time_hours_min)) {
                        for ($j = 0; $j < count($time_hours_min); $j++) {
                            $arr['open_time'] = $time_hours_min[$j];
                            $arr['close_time'] = $time_hours_max[$j];
                            $final_custom_time[] = $arr;
                        }
                    }
                    $final_custom_time = json_encode($final_custom_time);
                    $dirctexception_holiday = new DirectoryExceptionHoliday;
                    $dirctexception_holiday->directory_id = $direct_id;
                    $dirctexception_holiday->custom_date = $request->input($val.'_exception_date');
                    $dirctexception_holiday->note = $request->input($val.'_exception_note');
                    $dirctexception_holiday->custom_time = $final_custom_time;
                    $dirctexception_holiday->save();
                }
            }

        }
        /* END SCHEDUAL ENTRIES */
        /* TYPE ENTRIES START */
        $catid = $request->input('hotel_type');
        if ($catid != "") {

            $gallery_image_available = 'gallery_image';
                $gallery_imgfile = $request->file($gallery_image_available);
                if (is_array($gallery_imgfile)) {
                    foreach ($gallery_imgfile as $key => $gallery_imgfile2){
                        foreach ($gallery_imgfile2 as  $imgfile) {
                            if($imgfile){
                                $ext = $imgfile->getClientOriginalExtension();
                                $name2 = $imgfile->getClientOriginalName();
                                $imageName2 = time() . $name2;
                                $imageName = Helper::getSeoUrl($imageName2);
                                $image_resize = Image::make($imgfile->getRealPath());
                                $image_resize->save(public_path('files/directorytypeimage/' .$imageName.'.'.$ext));

                                $dirtTypegallery = new DirectoryTypeGallery;
                                $dirtTypegallery->directory_id = $direct_id;
                                $dirtTypegallery->category_id = $catid;
                                $dirtTypegallery->image_name = $imageName.'.'.$ext;
                                $dirtTypegallery->block_num = $key;
                                $dirtTypegallery->position = $key;
                                $dirtTypegallery->save(); // Save the image details to the database
                            }
                        }
                    }
                }


                $gallery_pdf_available = 'gallery_type_pdf';
                        $gallery_pdf_file = $request->file($gallery_pdf_available);
                        if (is_array($gallery_pdf_file)) {
                            foreach ($gallery_pdf_file as $key => $gallery_pdf_file2){
                                foreach ($gallery_pdf_file2 as  $pdf_file) {
                                    if($pdf_file){
                                        $ext = $pdf_file->getClientOriginalExtension();
                                        if ($ext === 'pdf') {
                                            $name2 = $pdf_file->getClientOriginalName();
                                            $pdfName = time().'_'.$name2;
                                            $pdf_file->move(public_path('files/directorytypeimage/'), $pdfName);

                                            $dirtTypegallery = new DirectoryTypePdfs;
                                            $dirtTypegallery->directory_id = $direct_id;
                                            $dirtTypegallery->category_id = $catid;
                                            $dirtTypegallery->pdf_name = $pdfName;
                                            $dirtTypegallery->block_num = $key;
                                            $dirtTypegallery->position = $key;
                                            $dirtTypegallery->save(); // Save the image details to the database
                                        }
                                    }
                                }
                            }
                        }


            $coulnmname = $this->gettypeCoulmnName($catid);
            //echo "<pre>";print_r($coulnmname);die;
            $block_number = $request->input('block_number') ? $request->input('block_number') : 0;
            for ($i = 0; $i <= $block_number; $i++) {
                foreach ($coulnmname as $rowdata) {
                    $dirctType = new DirectoryType;
                    $dirctType->directory_id    =   $direct_id;
                    $dirctType->category_id     =   $request->input('hotel_type');
                    $dirctType->input_type      =   $rowdata->type;
                    $dirctType->column_name     =   $rowdata->name;
                    $dirctType->column_value    =   $request->input($rowdata->name . "_" . $i);
                    $dirctType->label           =   $rowdata->label;
                    $dirctType->block_num       =   $i;
                    $dirctType->save();
                }
            }
        }
        /* TYPE ENTRIES END */

        /* Contact Entries start */
        $dirctContact = new DirectoryContact;
        $dirctContact->directory_id     = $direct_id;
        $dirctContact->name             = $request->input('name');
        $dirctContact->website          = $request->input('website');
        $dirctContact->email            = $request->input('email');
        $dirctContact->phone            = $request->input('phone_number');
        $dirctContact->facebook         = $request->input('facebook');
        $dirctContact->twitter          = $request->input('twitter');
        $dirctContact->linkedin         = $request->input('linkedin');
        $dirctContact->pinterest        = $request->input('pinterest');
        $dirctContact->whatsapp         = $request->input('whatsapp');
        $dirctContact->instagram        = $request->input('instagram');
        $dirctContact->tiktok           = $request->input('tiktok');
        $dirctContact->youtube          = $request->input('youtube');
        $dirctContact->snapchat         = $request->input('snapchat');
        $dirctContact->save();

        $website_number_count = $request->input('website_count') ? $request->input('website_count') : 1;
        $firstrow = $request->input("website_name_1");
        for ($i = 1; $i <= $website_number_count; $i++) {
            if ($firstrow) {
                $websitelink = new Websitelinks;
                $websitelink->directory_id    =   $direct_id;
                $websitelink->name            =   $request->input("website_name_" . $i);
                $websitelink->link            =   $request->input("website_link_" . $i);
                $websitelink->save();
            }
        }

        $assortment_number_count = $request->input('assort_count') ? $request->input('assort_count') : 1;
        $firstrowass = $request->input("assortment_name_1");
        for ($i = 1; $i <= $assortment_number_count; $i++) {
            if ($firstrowass) {
                $assortmentlink = new Assortmentlinks;
                $assortmentlink->directory_id    =   $direct_id;
                $assortmentlink->name            =   $request->input("assortment_name_" . $i);
                $assortmentlink->link            =   $request->input("assortment_link_" . $i);
                $assortmentlink->save();
            }
        }
        /* Contact Entries end */
        /* Claim Entries start */
        if($request->input("claim_subject")!=''){
            $ClaimInsert = new DirectoryClaim();
            $ClaimInsert->directory_id          =   $direct_id;
            $ClaimInsert->claim_subject         =   $request->input("claim_subject");
            $ClaimInsert->claim_description     =   $request->input("claim_description");
            $ClaimInsert->save();
        }

        /* Claim Entries end */

        $activeTab = strtolower($request->input('active_tab'));
        if ($activeTab != "") {
            return redirect('admin/directory-edit/' . $direct_id . '?target=' . $activeTab)->with('msg', 'Company has been Added');
        } else {
            return redirect('admin/directory-edit/' . $direct_id)->with('msg', 'Company has been Added');
        }
    }

    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false){
            abort(404);
        }
        $data['languagesData']              = Helper::getAllLanguages();                /* get languages */
        $data['parent_category']            = Helper::getAllParentCategory();           /* get Category */
        // $data['child_category']          = Helper::getAllChildCategory();            /* get Child Category */
        $data['country']                    = Helper::getCountryAllData();              /* get country */
        $data['amenity']                    = Helper::getAmenity();                     /* get amenity */
        $data['amenityCategoryData']        = Helper::getamenityCategoryData();         /* get amenityCategoryData */
        $data['type']                       = Helper::getType();                        /* get category whose type is listing */
        $data['trademarks']                 = Helper::getTrademarks();                  /* get trademarks */
        // $data['trademarkcategory']       = Helper::getTrademarkCategory();           /* get Trademark Category */
        $data['trademarkcategory']          = TrademarkCategory::where('deleted','0')->where('directory_id',$id)->orWhere('directory_id', 0)->orderBy('id', 'desc')->get();       /* get Trademark Category */

        // $data['tags']                    = DB::table('directory_category as dc')->select('cts.*')
        //     ->leftjoin('category_tags as cts', 'dc.category_id', '=', 'cts.category_id')
        //     ->where('dc.directory_id',  $id)->get();

        $data['owntags']                    = DB::table('directory_category as dc')->select('cots.*')
            ->leftjoin('category_own_tags as cots', 'dc.category_id', '=', 'cots.category_id')
            ->where('dc.directory_id',  $id)->get();             /* get owntags */
        $data['directoryowntags']           = Helper::getdirectoryOwnTagsData();    /* get directory owntags trademarkcat_id*/

        $data['basicData']                  = Diectory::where('id', $id)->first();
        $categoryData                       = DirectoryCategory::select('category_id')->where('directory_id', $id)->get()->toArray();
        $data['categoryData']               = array_column($categoryData, 'category_id');

        $data['child_category']             = Category::where('parent_id',$data['categoryData'])->where('status','Active')->where('deleted','0')->orderBy('name', 'ASC')->get()->toArray();     /* get Child Category */

        $subcategoryData                    = DirectorySubCategory::select('sub_category_id')->where('directory_id', $id)->get()->toArray();
        $data['subcategoryData']            = array_column($subcategoryData, 'sub_category_id');

        $data['tags']                       = Tags::whereIn('child_cat_id', $data['subcategoryData'])->where('status','Active')->orderBy('tags', 'ASC')->groupBy('tags')->get()->toArray();

        $trademarkData                      = DirectoryMediaTrademarks::select('trademark_id')->where('directory_id', $id)->get()->toArray();
        $data['trademarkData']              = array_column($trademarkData, 'trademark_id');

        $data['trademarkDatacate']          = DirectoryMediaTrademarks::select('*')->where('directory_id', $id)->get()->toArray();
        if (!empty($data['trademarkDatacate'])) {
            $data['trademarkselectedDataAccordingToTrademarkCat']    = DB::table('trademarks')->select('*')->where('category_id', $data['trademarkDatacate'][0]['trademarkcat_id'])->get()->toArray();
        } else {
            $data['trademarkselectedDataAccordingToTrademarkCat'] = array();
        }
        $tagsData                           = DirectoryTags::select('tags_id')->where('directory_id', $id)->get()->toArray();
        $data['tagsData']                   = array_column($tagsData, 'tags_id');

        $ownTagsData                        = DirectoryOwnTags::select('owntags_id')->where('directory_id', $id)->get()->toArray();
        $data['ownTagsData']                = array_column($ownTagsData, 'owntags_id');

        $directoryownTagsData               = DirectoryNewOwnTags::select('owntags_id')->where('directory_id', $id)->get()->toArray();
        $data['directoryownTagsData']       = array_column($directoryownTagsData, 'owntags_id');

        $data['locationData']               = DB::table('directory_location')->select('*')->where('directory_id', $id)->get()->toArray();
        $data['dataamenityCatIdData']       = array();
        $datadataamenityCatIdData           = DB::table('directory_amenities')->select('amenitycat_id')->where('directory_id', $id)->where('typeofdata', 'existing')->first();
        if(!empty($datadataamenityCatIdData)){ $data['dataamenityCatIdData'] = explode(',',$datadataamenityCatIdData->amenitycat_id); }

        $data['getselectetedAmenityData']   = array();
        $getselectetedAmenityData           = DB::table('directory_amenities')->select('amenities_id')->where('directory_id', $id)->where('typeofdata', 'existing')->first();
        if(!empty($getselectetedAmenityData)){ $data['getselectetedAmenityData'] = explode(',',$getselectetedAmenityData->amenities_id); }

        $data['contactData']                = DB::table('directory_contact')->select('*')->where('directory_id', $id)->first();
        $amenitygetallselectedDataAccordingToAmenityCat = array();
        if(!empty($data['dataamenityCatIdData'])){

            $amenityCatName = array();$i=0;
            foreach($data['dataamenityCatIdData'] as $value){
                $amenityCatName[]           = AmenityCategory::where("id", $value)->get(["name"]);
                $amenitygetallselectedDataAccordingToAmenityCat[$amenityCatName[$i][0]->name] = Amenity::where("category_id", $value)->get(["icon_class", "id", "title", "category_id"]);
                $i++;
            }
        }
        $data['basicAndTraslationData']             = DB::table('directory as d')->select('d_tr.*')
            ->leftjoin('directory_basic_translation as d_tr', 'd.id', '=', 'd_tr.directory_id')
            ->where('d_tr.directory_id',  $id)->groupBy('d_tr.language_id')->get();

        $data['seoAndTraslationData']               = DB::table('directory_seo')->where('module_id',  $id)->where('page_type', 'directory')->first();
        //dd($data['seoAndTraslationData']);
        $data['scheduleData']                       = DB::table('directory_schedule')->select('*')->where('directory_id',  $id)->get()->toArray();

        $data['scheduleData']                       = self::group_assoc(json_decode(json_encode($data['scheduleData']), 1), 'day');
        $data['scheduleHolidayData']                = DB::table('directory_custom_holiday')->select('*')->where('directory_id',  $id)->get()->toArray();
        $data['scheduleHolidayDatacount']           = DB::table('directory_custom_holiday')->select('*')->where('directory_id',  $id)->count();

        $data['scheduleExceptionHolidayData']       = DB::table('directory_exceptional')->select('*')->where('directory_id',  $id)->get()->toArray();
        $data['scheduleExceptionHolidayDatacount']  = DB::table('directory_exceptional')->select('*')->where('directory_id',  $id)->count();
        $data['mediaData']                          = DB::table('directory_media')->select('*')->where('directory_id',  $id)->first();

        $data['directoryTypeData']                  = DB::table('directory_type')->select('*')->where('directory_id',  $id)->get();
        $data['directoryTypeDatainreverse']         = DB::table('directory_type')->select('*')->where('directory_id',  $id)->orderBy('id', 'desc')->get();
        $data['websitelink']                        = DB::table('directory_website_links')->select('*')->where('directory_id',  $id)->get();
        $data['assortlink']                         = DB::table('directory_assortment_links')->select('*')->where('directory_id',  $id)->get();
        $data['normaldaysdata']                     = DB::table('normaltemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();
        $data['publicdaysdata']                     = DB::table('holidaytemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();
        $data['exeptionaldaysdata']                 = DB::table('exeptionalholidaytemplate_name')->select('*')->where('status','Active')->where('deleted','0')->get()->toArray();

        $data['directory_claim']                    = DB::table('claim')->select('*')->where('directory_id',  $id)->orderBy('id', 'desc')->get();
        $data['directory_claim_count']              = DB::table('claim')->select('*')->where('directory_id',  $id)->get()->count();

        $directory_gallery_images                   = DB::table('directory_gallery_images')->where(['directory_id' => $id])->get();
        if(count($directory_gallery_images) > 0){
            foreach($directory_gallery_images as $dgi_val){
                $currentDate = NOW()->format('Y-m-d');
                $effectiveDate = date_format(date_create($dgi_val->created_at),"Y-m-d");
                $ts1 = strtotime($currentDate);
                $ts2 = strtotime($effectiveDate);
                $year1 = date('Y', $ts1);
                $year2 = date('Y', $ts2);
                $month1 = date('m', $ts1);
                $month2 = date('m', $ts2);
                $diff = (($year2 - $year1) * 12) + ($month2 - $month1);
                    if($diff > -6){  //echo 1; echo $diff; die;
                    }else{
                        DirectoryMediagallery::where('id', $dgi_val->id)->delete(); //echo 0; echo $diff; die;
                    }
            }
        }
        return view('admin.directory.edit', ['data' => $data,'amenitygetallselectedDataAccordingToAmenityCat' => $amenitygetallselectedDataAccordingToAmenityCat]);
    }

    function group_assoc($array, $key)
    {
        $return = array();
        foreach ($array as $v) {
            $return[$v[$key]][] = $v;
        }
        return $return;
    }
    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'phone_number'          => 'numeric|digits:10',
        ]);
        $is_normaltemplate                  = $request->input('normaldays');
        $is_publictemplate                  = $request->input('publicdays');
        $is_normaltemplate_id               = 0;
        if ($is_normaltemplate == 'yes') {
            DirectorySchedual::where('directory_id', $id)->delete();
            $is_normaltemplate_id           = $request->input('normaldaytemplateselect');
        }
        $name_en                            = $request->input('name_en');
        $activeTab                          = $request->input('active_tab');
        $description_en                     = $request->input('description_en');
        $seo_en                             = $request->input('seo_en');
        $weoffer_en                         = $request->input('weoffer_en');
        $aboutus_en                         = $request->input('aboutus_en');
        Diectory::where('id', $id)
            ->update([
                'title'                         => $name_en ? $name_en : "",
                'slug'                          => $seo_en ? $seo_en : "",
                'description'                   => isset($description_en) ? $description_en : "",
                'feature_type'                  => $request->input('featuretype'),
                'google_analytics_id'           => $request->input('google_ana_id'),
                'is_normaltemplate'             => $request->input('normaldays'),
                'is_normaltemplate_id'          => $is_normaltemplate_id,
                'is_publictemplate'             => $request->input('publicdays'),
                'is_publictemplate_id'          => $request->input('publicdaystemplateselect'),
                'is_exceptionaltemplate'        => $request->input('exception_days'),
                'is_exceptionaltemplate_id'     => $request->input('exception_daystemplateselect'),
                'status'                        => $request->input('status'),
                'popular'                       => $request->input('popular')??0,
                'schedule_normal_comment'       => $request->input('add_normal_comment')
            ]);
        $registeremail = $request->input('email');
        Mail::to($registeremail)->send(new DirectoryStatusMail($request->input('status')));

    if($request->active_tab == 'SEO'){

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();
        DirectoryTags::where('directory_id', $id)->delete();
        DirectorySubCategory::where('directory_id', $id)->delete();
        /* Directory Category Starts */
        $categories = $request->input('sub_category');
        DirectoryCategory::where('directory_id', $id)->delete();
                $categoryInsertData                 = new DirectoryCategory;
                $categoryInsertData->directory_id   = $id;
                $categoryInsertData->category_id    = $request->input('category');
                $categoryInsertData->save();

        $sub_categories = $request->input('sub_category');
            if (!empty($sub_categories)) {
                foreach ($sub_categories as $cat => $value) {
                    $categoryInsertData                     = new DirectorySubCategory;
                    $categoryInsertData->directory_id       = $id;
                    $categoryInsertData->sub_category_id    = $value;
                    $categoryInsertData->save();

                }
            }

        /* Directory Tags Starts */
        $simpletags = $request->input('simpletags');
        if (!empty($simpletags)) {
            foreach ($simpletags as $tgs => $value) {
                $simpletagsInsertData                       = new DirectoryTags;
                $simpletagsInsertData->directory_id         = $id;
                $simpletagsInsertData->tags_id              = $value;
                $simpletagsInsertData->save();

                $gettagsName = Tags::where('id', $value)->where('status', 'Active')->first();
                    if ($gettagsName) {
                        foreach ($languages as $lang) {
                            $title_name = $tr->setSource('en')->setTarget($lang->code)->translate($gettagsName->tags);
                            $description_code = $tr->setSource('en')->setTarget($lang->code)->translate($gettagsName->tags);
                            $title_name = $title_name ?: $gettagsName->tags;
                            $tagtrans = new tagTranslation();
                            $tagtrans->tag_id = $value;
                            $tagtrans->category_id = $request->input('category');
                            $tagtrans->language_id = $lang->id;
                            $tagtrans->title = $title_name;
                            $tagtrans->description = $description_code;
                            $tagtrans->seo_url = Helper::getSeoUrl($title_name);
                            $tagtrans->save();
                        }
                    }
            }
        }
        /* Directory Tags Ends */
    }

        /* Directory Category Ends */

        /* Directory Trademark Starts */
        $trademark = $request->input('trademark');
        DirectoryMediaTrademarks::where('directory_id', $id)->delete();
        if (!empty($trademark)) {
            foreach ($trademark as $cat => $value) {
                $trademarkInsertData                        = new DirectoryMediaTrademarks;
                $trademarkInsertData->directory_id          = $id;
                $trademarkInsertData->trademarkcat_id       = $request->input('trademarkcategory')??'0';
                $trademarkInsertData->trademark_id          = $value;
                $trademarkInsertData->save();
            }
        }else{
            $trademarkInsertData = new DirectoryMediaTrademarks;
            $trademarkInsertData->directory_id              = $id;
            $trademarkInsertData->trademarkcat_id           = $request->input('trademarkcategory')??'0';
            $trademarkInsertData->trademark_id              = $request->input('trademark')??'0';
            $trademarkInsertData->save();
        }
        /* Directory Trademark Ends */

        /* Directory Own Tags Starts */
        $owntags = $request->input('owntags');
        DirectoryOwnTags::where('directory_id', $id)->delete();
        if (!empty($owntags)) {
            foreach ($owntags as $owntgs => $value) {
                $owntagsInsertData                          = new DirectoryOwnTags;
                $owntagsInsertData->directory_id            = $id;
                $owntagsInsertData->owntags_id              = $value;
                $owntagsInsertData->save();
            }
        }

        $newOwnTagsids = array();
        $createnewowntag = $request->input('createnewowntag');
        if ($createnewowntag != "") {
            $owntagsName = explode(',', $createnewowntag);
            foreach ($owntagsName as $tag) {
                $tags_insert                                = new DirectoryCreateOwnTags;
                $tags_insert->directory_id                  = $id;
                $tags_insert->tag_name                      = $tag;
                $tags_insert->add_to_directory_page         = 'yes';
                $tags_insert->created_by                    = $request->input('createdby');
                $tags_insert->save();
                $lastenter_id                               = $tags_insert->id;
                $newOwnTagsids[]                            = $lastenter_id;
            }
        }

        if (!empty($newOwnTagsids)) {
            foreach ($newOwnTagsids as $owntgs => $value) {
                $owntagsInsertData                          = new DirectoryNewOwnTags;
                $owntagsInsertData->directory_id            = $id;
                $owntagsInsertData->owntags_id              = $value;
                $owntagsInsertData->save();
            }
        }
        /* Directory Own Tags Ends */

        $offerdescription = $request->input('description_en');
        $weoffer_en        = $request->input('weoffer_en');
        $aboutus_en  = $request->input('aboutus_en');

        $is_not_change_en_language = DiectoryTranslation::where('language_id',1)
            ->where('title',$name_en)
            ->where('description',$offerdescription)
            ->where('we_offer',$weoffer_en)
            ->where('about_us',$aboutus_en)
            ->where('directory_id',$id)->exists();

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();

            if($is_not_change_en_language){
                foreach ($languages as $lang) {
                    $title_name = $request->input('name_' . $lang->code);
                    $description_code = $request->input('description_' . $lang->code);
                    $languageTranslation_id = $request->input('langtranlation_' . $lang->code);
                    $seo_val = Helper::getSeoUrl($request->input('seo_' . $lang->code));
                    $weoffer_val = $request->input('weoffer_' . $lang->code);
                    $aboutus_val = $request->input('aboutus_' . $lang->code);

                    $offershortinsert =  DiectoryTranslation::find($languageTranslation_id);
                    if (!$offershortinsert) {
                        // Create new if not found
                        $offershortinsert = new DiectoryTranslation();
                        $offershortinsert->directory_id = $id;
                        $offershortinsert->language_id  = $lang->id;
                    }

                    $offershortinsert->title              = $title_name ?: $name_en;;
                    $offershortinsert->description        = $description_code ?: $offerdescription;
                    $offershortinsert->seo_url            = $seo_val;
                    $offershortinsert->we_offer           = $weoffer_val ?: $weoffer_en;
                    $offershortinsert->about_us           = $aboutus_val ?: $aboutus_en;
                    $offershortinsert->save();
                }
            }else{

                DiectoryTranslation::where('directory_id', $id)->delete();
                foreach ($languages as $lang) {

                    if ($lang->code !== 'en') {
                        $name        = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'null');
                        $description = $tr->setSource('en')->setTarget($lang->code)->translate($offerdescription??'null');
                        $seo_en         = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('seo_en')??'null');
                        $weofferen      = $tr->setSource('en')->setTarget($lang->code)->translate($weoffer_en??'null');
                        $aboutusen      = $tr->setSource('en')->setTarget($lang->code)->translate($aboutus_en??'null');
                    }

                    DiectoryTranslation::create([
                        'directory_id'     => $id,
                        'language_id'      => $lang->id,
                        'title'            => $name ?? $name_en,
                        'description'      => $description ?? $offerdescription,
                        'seo_url'          => $seo_en ?? $request->input('seo_en'),
                        'we_offer'         => $weofferen ?? $weoffer_en,
                        'about_us'         => $aboutusen ?? $aboutus_en,
                    ]);
                }

            }

        DiectoryLocation::where('directory_id', $id)
            ->update([
                'country_id'            => $request->input('country_id') ? $request->input('country_id') : 0,
                'state_id'              => $request->input('state_id') ? $request->input('state_id') : 0,
                'city_id'               => $request->input('city_id') ? $request->input('city_id') : 0,
                'address'               => $request->input('address'),
                'place'                 => $request->input('place'),
                'city_name'             => $request->input('city_id'),
                'zipcode'               => $request->input('zipcode'),
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude')
            ]);

            Product::where('directory_id', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);
            ShoppingCard::where('directory_link', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);
            Offer::where('offer_directory_link', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);
            Blog::where('directory_id', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);
            Events::where('directory_id', $id)
            ->update([
                'latitude'              => $request->input('latitude'),
                'longitude'             => $request->input('longitude'),
            ]);

            DirectoryAmenity::where('directory_id', $id)->delete();
        if ($request->input('amenities')) {
                $dirtamenity = new DirectoryAmenity;
                $dirtamenity->directory_id  = $id;
                $dirtamenity->amenitycat_id  = implode(",", $request->input('amenitycat_id'));
                $dirtamenity->amenities_id  = implode(",", $request->input('amenities'));
                $dirtamenity->typeofdata  = 'existing';
                $dirtamenity->save();
        }

        /* MEDIA EDIT START */
        $payablevalue = $request->input('payimage');
        if ($payablevalue != 'payable') {
            $payval = 0;
        } else {
            $payval = 1;
        }
        DirectoryMedia::where('directory_id', $id)
            ->update([
                'video_provider'  => $request->input('video_provider'),
                'video_url'  => $request->input('videourl'),
                'payable'  => $payval,
            ]);
        if ($request->input('payimage') == "payable") {
            DirectoryMediaListing::where(['directory_id' => $id, 'payable' => 0])->delete();
            DirectoryMediaCover::where(['directory_id' => $id, 'payable' => 0])->delete();
            DirectoryMediagallery::where(['directory_id' => $id, 'payable' => 0])->delete();
        } else {
            DirectoryMediaListing::where(['directory_id' => $id, 'payable' => 1])->delete();
            DirectoryMediaCover::where(['directory_id' => $id, 'payable' => 1])->delete();
            DirectoryMediagallery::where(['directory_id' => $id, 'payable' => 1])->delete();
        }

        if ($request->hasFile('company_logo')) {
            $company_logo = $request->file('company_logo');
            $ext = strtolower($company_logo->getClientOriginalExtension());
            $allowed = ['jpeg', 'jpg', 'png', 'gif', 'bmp', 'jfif'];
            if (!in_array($ext, $allowed)) {
                return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
            }
            $imageName = Helper::getSeoUrl(time() . pathinfo($company_logo->getClientOriginalName(), PATHINFO_FILENAME));
            $imagePath = "images/directory/{$imageName}.webp";
            $image = Image::make($company_logo->getRealPath())->stream('webp', 50);
            Storage::disk('images')->put("directory/{$imageName}.webp", $image);
            Diectory::where('id', $id)->update(['company_logo' => $imagePath]);
        }

        if ($request->hasFile('listing_thumb')) {
            if ($request->input('payimage') != "payable") {
                DirectoryMediaListing::where(['directory_id' => $id, 'payable' => 0])->delete();
            }
            $imgfile = $request->file('listing_thumb');
            $i = 0;
            $hidenarry = $request->input('array_thumb_listing_new');
            if (!is_array($hidenarry[0])) {
                $hidenarry = explode(',', $hidenarry[0]);
            }
            foreach ($imgfile as $imgrow) {

                $ext = $imgrow->getClientOriginalExtension();
                $name3 = $imgrow->getClientOriginalName();
                $allowed =  array('jpeg','jpg', "png", "gif", "bmp", "jfif", "JPEG","JPG", "PNG", "GIF", "BMP", "JFIF");
                   if(!in_array($ext,$allowed) ) {
                        return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
                   } else {
                        $imageName2 = time() . $name3;
                        $imageName = Helper::getSeoUrl($imageName2);
                        $imagepath = 'images/directory/' . $imageName;

                        /*
                        $image_resize = Image::make($imgrow->getRealPath());
                        $image_resize->save(public_path('files/directory/' .$imageName));
                        */

                        $convertext = "webp";
                        $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                        Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                        $dirtmediathumb = new DirectoryMediaListing;
                        $dirtmediathumb->directory_id       = $id;
                        $dirtmediathumb->position           = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                        $dirtmediathumb->name_thumbnail     = $imageName;
                        $dirtmediathumb->name_large         = $imageName;
                        $dirtmediathumb->extension          = $ext;
                        $dirtmediathumb->path               = $imagepath;
                        $dirtmediathumb->payable            = $payval;
                        $dirtmediathumb->save();
                        $i++;
                    }
            }
        } else {

            $hidenarrythumb = $request->input('array_thumb_listing_new');
            if (!is_array($hidenarrythumb[0])) {
                $hidenarrythumb = explode(',', $hidenarrythumb[0]);
            }
            $thumb_hidden_img = $request->input('thumb_hidden_img');
            $hiddenarray_thumb_hidden_img = explode(',', $thumb_hidden_img);
            $details = DirectoryMediaListing::where('directory_id', $id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->position = $hidenarrythumb[$key];
                    $detail->name_thumbnail = $hiddenarray_thumb_hidden_img[$key];
                    $detail->name_large = $hiddenarray_thumb_hidden_img[$key];
                    $detail->save();
                }
            }
        }


        if ($request->hasFile('listing_cover')) {

            if ($request->input('payimage') != "payable") {
                DirectoryMediaCover::where(['directory_id' => $id, 'payable' => 0])->delete();
            }
            $imgfile = $request->file('listing_cover');
            $i = 0;

            $hidenarry = $request->input('array_cover_listing');
            if (!is_array($hidenarry[0])) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
            }

            foreach ($imgfile as $imgrow) {

                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $allowed =  array('jpeg','jpg', "png", "gif", "bmp", "jfif", "JPEG","JPG", "PNG", "GIF", "BMP", "JFIF");
                   if(!in_array($ext,$allowed) ) {
                        return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
                   } else {
                    $imageName2 = time() . $name2;
                    $imageName = Helper::getSeoUrl($imageName2);
                    $imagepath = 'images/directory/' . $imageName;
                    /*
                    $imagepath = 'files/directory/' . $imageName;
                    $image_resize = Image::make($imgrow->getRealPath());
                    $image_resize->save(public_path('files/directory/' .$imageName));
                    */
                    // $convertext = "webp";
                    // $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);
                    // Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                    $convertext = "webp";
                    $image_resize = Image::make($imgrow->getRealPath());
                    $image_resize->resize(960, 450);
                    $image_resize->save(public_path('images/directory/'.$imageName.'.'.$convertext,$image_resize));

                    $dirtmediacover = new DirectoryMediaCover;
                    $dirtmediacover->directory_id    = $id;
                    $dirtmediacover->position        = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                    $dirtmediacover->name_thumbnail  = $imageName;
                    $dirtmediacover->name_large      = $imageName;
                    $dirtmediacover->extension       = $ext;
                    $dirtmediacover->path            = $imagepath;
                    $dirtmediacover->payable        = $payval;
                    $dirtmediacover->save();
                    $i++;
                   }
            }
        } else {

            $hidenarr = $request->input('array_cover_listing');
            $hidenarry = explode(',', $hidenarr[0]);
            $hidenarrimg = $request->input('array_cover_images');
            $hidenarryimages = explode(',', $hidenarrimg);
            $details = DirectoryMediaCover::where('directory_id', $id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->position = $hidenarry[$key];
                    $detail->name_thumbnail = $hidenarryimages[$key];
                    $detail->name_large = $hidenarryimages[$key];
                    $detail->save();
                }
            }
        }

        if ($request->hasFile('listing_gallery')) {

            $imgfilegal = $request->file('listing_gallery');
            $i = 0;
            $hidenarry = $request->input('array_gallery_listing');
            if (!is_array($hidenarry[0])) {
                $hidenarry = explode(',', $hidenarry[0]);
            } else {
            }

            foreach ($imgfilegal as $imgalrow) {

                $ext = $imgalrow->getClientOriginalExtension();
                $name3 = $imgalrow->getClientOriginalName();

                $allowed =  array('jpeg','jpg', "png", "gif", "bmp", "jfif", "JPEG","JPG", "PNG", "GIF", "BMP", "JFIF", "webp");
                   if(!in_array($ext,$allowed) ) {
                        return redirect()->back()->with('errorimg', 'Images must be an extension of JPG, JPEG, PNG, GIF.');
                    } else {

                        $is_images_exist = DirectoryMediagallery::where('name_large',$name3)->where('directory_id',$id)->first();
                        if($is_images_exist){}else{

                            $imageName2 = time() . $name3;
                            $imageName = Helper::getSeoUrl($imageName2);
                            $imagepath = 'images/directory/' . $imageName;
                            $convertext = "webp";
                            $image_resize = Image::make($imgalrow->getRealPath())->stream($convertext,50);
                            Storage::disk('images')->put('directory/'.$imageName.'.'.$convertext,$image_resize);

                            $dirtmediagallery = new DirectoryMediagallery;
                            $dirtmediagallery->directory_id    = $id;
                            $dirtmediagallery->position        = isset($hidenarry[$i]) ? $hidenarry[$i] : 0;
                            $dirtmediagallery->name_thumbnail  = $imageName;
                            $dirtmediagallery->name_large      = $name3;
                            $dirtmediagallery->extension       = $ext;
                            $dirtmediagallery->path            = $imagepath;
                            $dirtmediagallery->payable        = $payval;
                            $dirtmediagallery->save();
                            $i++;
                        }
                    }
            }
        } else {

            $hidenarrygallery = $request->input('array_gallery_listing');
            if (!is_array($hidenarrygallery[0])) {
                $hidenarrygall = explode(',', $hidenarrygallery[0]);
            }
            $hidenarrGalleryimg = $request->input('array_gallery_images');
            $hiddenarray_gallery_images = explode(',', $hidenarrGalleryimg);
            //dd($hiddenarray_gallery_images);
            $details = DirectoryMediagallery::where('directory_id', $id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->position = $hidenarrygall[$key] ?? '';
                    $detail->name_thumbnail = $hiddenarray_gallery_images[$key] ?? '';
                    $detail->save();
                }
            }

        }

        /* MEDIA EDIT END */
        /* Seo Entries start */
        $seo_title_en = $request->input('meta_keywords_en');
        $seo_meta_description_en = $request->input('meta_description_en');
        $module_id = $request->input('module_id');
        $page_type = $request->input('page_type');

        if (!empty($module_id) && (!empty($page_type))) {
        DirectorySeo::where('module_id', $id)->where('page_type', 'directory')
            ->update([
                'page_type'    =>'directory',
                'meta_keyword' => (isset($seo_title_en)) ? $seo_title_en : "",
                'meta_description' => isset($seo_meta_description_en) ? $seo_meta_description_en : "",
            ]);
        }else{
            $dirtseo = new DirectorySeo;
            $dirtseo->module_id     = $id;
            $dirtseo->page_type     = 'directory';
            $dirtseo->meta_keyword  = (isset($seo_title_en)) ? $seo_title_en : "";
            $dirtseo->meta_description  = (isset($seo_meta_description_en)) ? $seo_meta_description_en : "";
            $dirtseo->save();
        }
        /* Seo Entries End */

        /* START SCHEDUAL ENTRIES */
        DirectorySchedual::where('directory_id', $id)->delete();
        if ($request->input('normaldays') == "no") {
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $key => $value) {
                $count = count($request->input($value . "_hours_min"));
                $minHours = $request->input($value . "_hours_min");
                $maxHours = $request->input($value . "_hours_max");
                for ($i = 0; $i < $count; $i++) {
                    $dirctschedual = new DirectorySchedual;
                    $dirctschedual->directory_id    = $id;
                    $dirctschedual->day             = $value;
                    $dirctschedual->is_closed       = $request->input($value . "_is_closed") ? '1' : '0';
                    $dirctschedual->open_time       = $minHours[$i];
                    $dirctschedual->close_time      = $maxHours[$i];
                    $dirctschedual->save();
                }
            }
        }
        if ($request->input('normaldays') == "yes") {
            /* START SCHEDUAL ENTRIES */
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            foreach ($days as $key => $value) {

                if(!empty($request->input($value . "_template_hours_min"))){

                    $count = count($request->input($value . "_template_hours_min"));
                    $minHours = $request->input($value . "_template_hours_min");
                    $maxHours = $request->input($value . "_template_hours_max");
                    for ($i = 0; $i < $count; $i++) {
                        $dirctschedual = new DirectorySchedual;
                        $dirctschedual->directory_id    = $id;
                        $dirctschedual->day             = $value;
                        $dirctschedual->is_closed       = $request->input($value . "_template_is_closed") ? '1' : '0';
                        $dirctschedual->open_time       = $minHours[$i];
                        $dirctschedual->close_time      = $maxHours[$i];
                        $dirctschedual->save();
                    }
                }
            }
        }

        /* HOLIDAY TIME ADD */
        if ($request->input('publicdays') == "no") {
            if($request->input('publicdays')!=$request->input('actualdataforyesno') )
            DirectoryCustomHoliday::where('directory_id', $id)->delete();
            if ($request->input('custom_date') != "") {
                $custom_date = $request->input('custom_date');
                $note = $request->input('note');
                $time_hours_min = $request->input('time_hours_min');
                $time_hours_max = $request->input('time_hours_max');
                $final_custom_time = [];
                if (count($time_hours_min)) {
                    for ($i = 0; $i < count($time_hours_min); $i++) {
                        $arr['open_time'] = $time_hours_min[$i];
                        $arr['close_time'] = $time_hours_max[$i];
                        $final_custom_time[] = $arr;
                    }
                }
                $final_custom_time = json_encode($final_custom_time);
                $dirctcustom_holiday = new DirectoryCustomHoliday;
                $dirctcustom_holiday->directory_id = $id;
                $dirctcustom_holiday->custom_date = $custom_date;
                $dirctcustom_holiday->note = $note;
                $dirctcustom_holiday->custom_time = $final_custom_time;
                $dirctcustom_holiday->save();
            }
        }
        if ($request->input('publicdays') == "yes") {

            if($request->input('publicdaystemplateselect')!=$request->input('actualdataforselectedTemplateId')){
                DirectoryCustomHoliday::where('directory_id', $id)->delete();
            }


            $sele_ids = $request->input('template_id');
            $ids = ($sele_ids!="")?$sele_ids:($ids=array());

            if(count($ids)){
                foreach($ids as $val) {
                    $time_hours_min = $request->input($val.'_temp_time_hours_min');
                    $time_hours_max = $request->input($val.'_temp_time_hours_max');
                    $final_custom_time = [];
                    if (count($time_hours_min)) {
                        for ($j = 0; $j < count($time_hours_min); $j++) {
                            $arr['open_time'] = $time_hours_min[$j];
                            $arr['close_time'] = $time_hours_max[$j];
                            $final_custom_time[] = $arr;
                        }
                    }
                    $final_custom_time = json_encode($final_custom_time);
                    $dirctcustom_holiday = new DirectoryCustomHoliday;
                    $dirctcustom_holiday->directory_id = $id;
                    $dirctcustom_holiday->custom_date = $request->input($val.'_custom_date');
                    $dirctcustom_holiday->note = $request->input($val.'_note');
                    $dirctcustom_holiday->custom_time = $final_custom_time;
                    $dirctcustom_holiday->save();
                }
            }

        }

        if ($request->input('exception_days') == "no") {
            if($request->input('exception_days')!=$request->input('exeActualdataforyesno') )
            DirectoryExceptionHoliday::where('directory_id', $id)->delete();
            if ($request->input('exception_date') != "") {
                $custom_date = $request->input('exception_date');
                $note = $request->input('exception_note');
                $time_hours_min = $request->input('exception_time_hours_min');
                $time_hours_max = $request->input('exception_time_hours_max');
                $final_custom_time = [];
                if (count($time_hours_min)) {
                    for ($i = 0; $i < count($time_hours_min); $i++) {
                        $arr['open_time'] = $time_hours_min[$i];
                        $arr['close_time'] = $time_hours_max[$i];
                        $final_custom_time[] = $arr;
                    }
                }
                $final_custom_time = json_encode($final_custom_time);
                $dirctcustom_holiday = new DirectoryExceptionHoliday;
                $dirctcustom_holiday->directory_id = $id;
                $dirctcustom_holiday->custom_date = $custom_date;
                $dirctcustom_holiday->note = $note;
                $dirctcustom_holiday->custom_time = $final_custom_time;
                $dirctcustom_holiday->save();
            }
        }
        if ($request->input('exception_days') == "yes") {
            if($request->input('exception_daystemplateselect')!=$request->input('exeActualdataforselectedTemplateId'))
            {
                DirectoryExceptionHoliday::where('directory_id', $id)->delete();
            }
            $sele_ids = $request->input('exception_template_id');
            $ids = ($sele_ids!="")?$sele_ids:($ids=array());
            if(count($ids)){
                foreach($ids as $val) {
                    $time_hours_min = $request->input($val.'_exc_time_hours_min');
                    $time_hours_max = $request->input($val.'_exc_time_hours_max');
                    $final_custom_time = [];
                    if (count($time_hours_min)) {
                        for ($j = 0; $j < count($time_hours_min); $j++) {
                            $arr['open_time'] = $time_hours_min[$j];
                            $arr['close_time'] = $time_hours_max[$j];
                            $final_custom_time[] = $arr;
                        }
                    }
                    $final_custom_time = json_encode($final_custom_time);
                    $dirctexception_holiday = new DirectoryExceptionHoliday;
                    $dirctexception_holiday->directory_id = $id;
                    $dirctexception_holiday->custom_date = $request->input($val.'_exception_date');
                    $dirctexception_holiday->note = $request->input($val.'_exception_note');
                    $dirctexception_holiday->custom_time = $final_custom_time;
                    $dirctexception_holiday->save();
                }
            }

        }
        /* END SCHEDUAL ENTRIES */

        /* Contact Entries start */
        DirectoryContact::where('directory_id', $id)
            ->update([
                'name'          => $request->input('name'),
                'website'       => $request->input('website'),
                'email'         => $request->input('email'),
                'phone'         => $request->input('phone_number'),
                'facebook'      => $request->input('facebook'),
                'twitter'       => $request->input('twitter'),
                'linkedin'      => $request->input('linkedin'),
                'pinterest'     => $request->input('pinterest'),
                'whatsapp'      => $request->input('whatsapp'),
                'instagram'     => $request->input('instagram'),
                'tiktok'        => $request->input('tiktok'),
                'youtube'       => $request->input('youtube'),
                'snapchat'      => $request->input('snapchat')
            ]);

        Websitelinks::where('directory_id', $id)->delete();
        $website_number_count = $request->input('website_count') ? $request->input('website_count') : 1;
        $firstrow = $request->input("website_name_1");
        $j = 1;
        for ($i = 1; $i <= $website_number_count; $i++) {

            $websitelink = new Websitelinks;
            $websitelink->directory_id    =   $id;
            $websitelink->name            =   $request->input("website_name_" . $j);
            $websitelink->link            =   $request->input("website_link_" . $j);
            $websitelink->save();
            $j++;
        }
        Assortmentlinks::where('directory_id', $id)->delete();
        $assortment_number_count = $request->input('assort_count') ? $request->input('assort_count') : 1;
        $firstrowass = $request->input("assortment_name_1");
        for ($i = 1; $i <= $assortment_number_count; $i++) {
            if ($firstrowass) {
                $assortmentlink = new Assortmentlinks;
                $assortmentlink->directory_id    =   $id;
                $assortmentlink->name            =   $request->input("assortment_name_" . $i);
                $assortmentlink->link            =   $request->input("assortment_link_" . $i);
                $assortmentlink->save();
            }
        }
        /* Contact Entries end */

        /* Claim Entries start */
        if($request->input("claim_subject")!=''){
            $ClaimInsert = new DirectoryClaim();
            $ClaimInsert->directory_id          =   $id;
            $ClaimInsert->claim_subject         =   $request->input("claim_subject");
            $ClaimInsert->claim_description     =   $request->input("claim_description");
            $ClaimInsert->save();
        }
        /* Claim Entries end */
        /* TYPE ENTRIES START */
        $catid = $request->input('hotel_type');
        if ($catid != "") {
            $coulnmname = $this->gettypeCoulmnName($catid);
            if ($request->input('selected_cat_id') != $catid) {
                $insertedBlockCount = "novalue";
            } else {
                $insertedBlockCount = $request->input('block_number_from_database') ? $request->input('block_number_from_database') : "";
            }
            $newBlockCount = $request->input('block_number') ? $request->input('block_number') : 0;
            if ($request->input('selected_cat_id') == $catid) {

                $countDirectoryTypeGallery = DirectoryTypeGallery::where('directory_id', $id)->where('block_num', $newBlockCount)->where('category_id', $catid)->count();
                $gallery_image_available = 'gallery_image';
                    $gallery_imgfile = $request->file($gallery_image_available);
                    if (is_array($gallery_imgfile)) {
                        foreach ($gallery_imgfile as $key => $gallery_imgfile2){
                            $position_key = $countDirectoryTypeGallery+1;
                            foreach ($gallery_imgfile2 as  $imgfile) {
                                if($imgfile){
                                    $ext = $imgfile->getClientOriginalExtension();
                                    $name2 = $imgfile->getClientOriginalName();
                                    $imageName2 = time() . $name2;
                                    $imageName = Helper::getSeoUrl($imageName2);
                                    $image_resize = Image::make($imgfile->getRealPath());
                                    $image_resize->save(public_path('files/directorytypeimage/' .$imageName.'.'.$ext));

                                    $dirtTypegallery = new DirectoryTypeGallery;
                                    $dirtTypegallery->directory_id = $id;
                                    $dirtTypegallery->category_id = $catid;
                                    $dirtTypegallery->image_name = $imageName.'.'.$ext;
                                    $dirtTypegallery->block_num = $key;
                                    $dirtTypegallery->position = $position_key++;
                                    $dirtTypegallery->save(); // Save the image details to the database
                                }
                            }
                        }
                    }

                    $countDirectoryTypePdfs = DirectoryTypePdfs::where('directory_id', $id)->where('block_num', $newBlockCount)->where('category_id', $catid)->count();
                    $gallery_pdf_available = 'gallery_type_pdf';
                            $gallery_pdf_file = $request->file($gallery_pdf_available);
                            if (is_array($gallery_pdf_file)) {
                                foreach ($gallery_pdf_file as $key => $gallery_pdf_file2){
                                    $position_key = $countDirectoryTypePdfs+1;
                                    foreach ($gallery_pdf_file2 as  $pdf_file) {
                                        if($pdf_file){
                                            $ext = $pdf_file->getClientOriginalExtension();
                                            if ($ext === 'pdf') {
                                                $name2 = $pdf_file->getClientOriginalName();
                                                $pdfName = time().'_'.$name2;
                                                $pdf_file->move(public_path('files/directorytypeimage/'), $pdfName);

                                                $dirtTypegallery = new DirectoryTypePdfs;
                                                $dirtTypegallery->directory_id = $id;
                                                $dirtTypegallery->category_id = $catid;
                                                $dirtTypegallery->pdf_name = $pdfName;
                                                $dirtTypegallery->block_num = $key;
                                                $dirtTypegallery->position = $position_key++;
                                                $dirtTypegallery->save(); // Save the image details to the database
                                            }
                                        }
                                    }
                                }
                            }

                for ($i = 0; $i <= $insertedBlockCount; $i++) {
                    foreach ($coulnmname as $rowdata) {

                        $imageavailable = 'image_'.$i;
                        if($request->hasFile($imageavailable)){

                            $imgfile        = $request->file($imageavailable);
                            $ext            = $imgfile->getClientOriginalExtension();
                            $name2          = $imgfile->getClientOriginalName();
                            $imageName2     = time() . $name2;
                            $imageName      = Helper::getSeoUrl($imageName2);
                            $image_resize   = Image::make($imgfile->getRealPath());
                            $image_resize->save(public_path('files/directorytypeimage/' .$imageName));

                            DirectoryType::where('directory_id', $id)->where('category_id', $catid)->where('block_num', $i)
                            ->update([
                                'image_name'        => $imageName
                            ]);
                        }

                        DirectoryType::where('directory_id', $id)->where('column_name', $rowdata->name)->where('block_num', $i)
                            ->update([
                                'category_id'       => $request->input('hotel_type'),
                                'column_name'       => $rowdata->name,
                                'column_value'      => $request->input($rowdata->name . "_" . $i),
                                'label'             => $rowdata->label,
                                'block_num'         => $i
                            ]);
                    }
                }
            }
            if ($insertedBlockCount < $newBlockCount || $insertedBlockCount == "novalue") {
                if ($insertedBlockCount != "novalue") {
                    for ($i = (int)$insertedBlockCount + 1; $i <= $newBlockCount; $i++) {
                        foreach ($coulnmname as $rowdatanew) {

                            $imageavailable = 'image_'.$i;
                            if($request->hasFile($imageavailable)){
                                $imgfile    = $request->file($imageavailable);
                                $ext        = $imgfile->getClientOriginalExtension();
                                $name2      = $imgfile->getClientOriginalName();
                                $imageName2 = time() . $name2;
                                $imageName  = Helper::getSeoUrl($imageName2);
                                $imagepath  = 'files/directorytypeimage/' . $imageName;
                                $image_resize = Image::make($imgfile->getRealPath());
                                $image_resize->save(public_path('files/directorytypeimage/' .$imageName));
                            }

                            $dirctType = new DirectoryType;
                            $dirctType->directory_id    =   $id;
                            $dirctType->category_id     =   $request->input('hotel_type');
                            $dirctType->input_type      =   $rowdatanew->type;
                            $dirctType->column_name     =   $rowdatanew->name;
                            $dirctType->column_value    =   $request->input($rowdatanew->name . "_" . $i);
                            $dirctType->label           =   $rowdatanew->label;
                            $dirctType->block_num       =   $i;
                            $dirctType->image_name      =   $imageName;
                            $dirctType->save();
                        }
                    }
                } else {
                    if ($request->input('selected_cat_id') != $catid) {
                        DirectoryType::where('directory_id', $id)->where('category_id', $request->input('selected_cat_id'))->delete();
                    }
                    for ($i = 0; $i <= $newBlockCount; $i++) {
                        foreach ($coulnmname as $rowdatanew) {

                            $imageavailable = 'image_'.$i;
                            if($request->hasFile($imageavailable)){
                                $imgfile    = $request->file($imageavailable);
                                $ext        = $imgfile->getClientOriginalExtension();
                                $name2      = $imgfile->getClientOriginalName();
                                $imageName2 = time() . $name2;
                                $imageName  = Helper::getSeoUrl($imageName2);
                                $imagepath  = 'files/directorytypeimage/' . $imageName;
                                $image_resize = Image::make($imgfile->getRealPath());
                                $image_resize->save(public_path('files/directorytypeimage/' .$imageName));

                            }else{
                                $imageName  = null;
                            }


                            $dirctType1 = new DirectoryType;
                            $dirctType1->directory_id    =   $id;
                            $dirctType1->category_id     =   $request->input('hotel_type');
                            $dirctType1->input_type      =   $rowdatanew->type;
                            $dirctType1->column_name     =   $rowdatanew->name;
                            $dirctType1->column_value    =   $request->input($rowdatanew->name . "_" . $i);
                            $dirctType1->label           =   $rowdatanew->label;
                            $dirctType1->block_num       =   $i;
                            $dirctType1->image_name      =   $imageName;
                            $dirctType1->save();
                        }
                    }
                }
            }
        }
        /* TYPE ENTRIES END */
        $activeTab = strtolower($activeTab);

        return redirect('admin/directory-edit/' . $id . '?target=' . $activeTab)->with('msg', 'Company has been updated');
    }


    public function destroy($id)
    {
        $data = DirectoryMediagallery::where("directory_id", $id)->get("*");
        foreach($data as $rowdata){
            $image_name = $rowdata->name_thumbnail.".webp";
            $image_path = public_path('images/directory/'.$image_name);
            if(file_exists($image_path)){
              unlink($image_path);
            }
        }
        //Diectory::where(['id' => $id])->update(['deleted' => '1']);
        Diectory::where(['id' => $id])->delete();
        DiectoryTranslation::where('directory_id', $id)->delete();
        DiectoryLocation::where('directory_id', $id)->delete();
        DirectoryAmenity::where('directory_id', $id)->delete();
        DirectorySeo::where('module_id', $id)->delete();
        DirectorySeoTranslation::where('directory_id', $id)->delete();
        DirectoryContact::where('directory_id', $id)->delete();
        DirectoryMedia::where('directory_id', $id)->delete();
        DirectoryMediaCover::where('directory_id', $id)->delete();
        DirectoryMediagallery::where('directory_id', $id)->delete();
        DirectoryType::where('directory_id', $id)->delete();
        DirectoryMediaListing::where('directory_id', $id)->delete();
        DirectoryMediaTrademarks::where('directory_id', $id)->delete();
        DirectorySchedual::where('directory_id', $id)->delete();
        DirectoryCategory::where('directory_id', $id)->delete();
        Assortmentlinks::where('directory_id', $id)->delete();
        Websitelinks::where('directory_id', $id)->delete();
        DirectoryTags::where('directory_id', $id)->delete();
        DirectoryOwnTags::where('directory_id', $id)->delete();
        DirectoryCustomHoliday::where('directory_id', $id)->delete();
        Wishlistdirectory::where('directory_id', $id)->delete();
        ShoppingCard::where('directory_link', $id)->delete();
            $userid                     = User::where('directory_id', '=', $id)->first('id');
            $userData                   = User::findOrFail($userid->id);
            $userData->directory_id     = 0;
            $userData->role_id          = 0;
            $userData->profile_status   = 0;
            $userData->temp             = 0;
            $userData->save();

        return redirect('admin/directory')->with('msg', 'Company has been deleted');
    }

    public function getDirectoryTypePdf(Request $request)
    {
        $data['DirectoryTypePdf'] = DirectoryTypePdfs::where('block_num', $request->block_num)->where('category_id', $request->cat_id)->where('directory_id', $request->directory_id)->orderBy('position', 'asc')->get(["pdf_name", "id", "position"]);

        return response()->json($data);
    }

    public function deleteDirectoryTypePdf(Request $request)
    {
        $get_directoryTypeGallery = DirectoryTypePdfs::where('id',$request->id)->first();
        $filePath = public_path('files/directorytypeimage/' . $get_directoryTypeGallery->pdf_name);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        $is_deleted = DirectoryTypePdfs::find($request->id)->delete();
        $CountDirectoryTypeGallery = DirectoryTypePdfs::where('block_num', $request->block_num)->where('category_id', $request->cat_id)->where('directory_id', $request->directory_id)->count();
        if($is_deleted){
            return response()->json(['type'=>'success','message'=>'Deleted successfully.','count'=>$CountDirectoryTypeGallery]);
        }else{
            return response()->json(['type'=>'error','message'=>'Something went wrong.','count'=>$CountDirectoryTypeGallery]);
        }
    }

    public function deleteCheckedTypePdf(Request $request)
    {
        $ids = $request->ids;
        $iddarr = explode(",", $ids);
        foreach($iddarr as $id){
            $get_directoryTypeGallery = DirectoryTypePdfs::where('id',$id)->first();
            $filePath = public_path('files/directorytypeimage/' . $get_directoryTypeGallery->pdf_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $is_deleted = DirectoryTypePdfs::whereIn('id', explode(",", $ids))->delete();
        if($is_deleted){
            return response()->json(['success'=>'Deleted successfully.']);
        }else{
            return response()->json(['error'=>'Something went wrong.']);
        }
    }

    public function getDirectoryTypeGalleries(Request $request)
    {
        $data['DirectoryTypeGallery'] = DirectoryTypeGallery::where('block_num', $request->block_num)->where('category_id', $request->cat_id)->where('directory_id', $request->directory_id)->orderBy('position', 'asc')->get(["image_name", "id", "position"]);

        return response()->json($data);
    }

    public function deleteDirectoryTypeGallery(Request $request)
    {
        $get_directoryTypeGallery = DirectoryTypeGallery::where('id',$request->id)->first();
        $filePath = public_path('files/directorytypeimage/' . $get_directoryTypeGallery->image_name);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
        $is_deleted = DirectoryTypeGallery::find($request->id)->delete();
        $CountDirectoryTypeGallery = DirectoryTypeGallery::where('block_num', $request->block_num)->where('category_id', $request->cat_id)->where('directory_id', $request->directory_id)->count();
        if($is_deleted){
            return response()->json(['type'=>'success','message'=>'Deleted successfully.','count'=>$CountDirectoryTypeGallery]);
        }else{
            return response()->json(['type'=>'error','message'=>'Something went wrong.','count'=>$CountDirectoryTypeGallery]);
        }
    }

    public function deleteCheckedTypeGallery(Request $request)
    {
        $ids = $request->ids;
        $iddarr = explode(",", $ids);
        foreach($iddarr as $id){
            $get_directoryTypeGallery = DirectoryTypeGallery::where('id',$id)->first();
            $filePath = public_path('files/directorytypeimage/' . $get_directoryTypeGallery->image_name);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
        }

        $is_deleted = DirectoryTypeGallery::whereIn('id', explode(",", $ids))->delete();
        if($is_deleted){
            return response()->json(['success'=>'Deleted successfully.']);
        }else{
            return response()->json(['error'=>'Something went wrong.']);
        }
    }



    // public function updateDirectoryTypeGalleriesBySort(Request $request)
    // {
    //     $gallery_type_id = $request->gallery_type_id;
    //     $gallery_typeimages = $request->gallery_typeimages;
    //     $gallery_position = $request->gallery_position;

    //     foreach ($gallery_typeimages as $key => $val) {
    //         $detail = DirectoryTypeGallery::findOrFail($gallery_type_id[$key]);
    //         $detail->image_name = $val;
    //         $detail->position = $gallery_position[$key];
    //         $detail->save();
    //     }
    //     return response()->json(['type'=>'success','message'=>'Position changed successfully!']);
    // }

    public function updateDirectoryTypeGalleriesBySort(Request $request)
    {
        $gallery_type_id = $request->gallery_type_id;
        $gallery_typeimages = $request->gallery_typeimages;
        $gallery_position = $request->gallery_position;

        $updates = [];
        foreach ($gallery_typeimages as $key => $val) {
            $updates[] = [
                'id' => $gallery_type_id[$key],
                'image_name' => $val,
                'position' => $key
            ];
        }
        DirectoryTypeGallery::upsert($updates, ['id'], ['image_name', 'position']);

        return response()->json(['type' => 'success', 'message' => 'Position changed successfully!']);
    }


    public function getState(Request $request)
    {
        $data['states'] = State::where("country_id", $request->country_id)->get(["name", "id"]);
        return response()->json($data);
    }

    public function getCity(Request $request)
    {
        $data['cities'] = City::where("state_id", $request->state_id)->get(["id", "name", "shipping_amount", "default_delivery_day", "status"]);
        return response()->json($data);
    }

    public function getCountrybyId(Request $request)
    {
        $countryphonecode = DB::table('countries')->select('phonecode')->where('id', $request->country_id)->first();
        return $countryphonecode;
    }

    public static function getCountry($id)
    {
        $countryName = DB::table('countries')->select('name')->where('id', $id)->get()->toArray();
        $country_name = isset($countryName[0]->name) ? $countryName[0]->name : "";
        return $country_name;
    }

    public static function get_thumbImage($directyId)
    {
        $get_thunbimageData = DB::table('directory_media_listing_image')->select('*')->where(['directory_id' => $directyId])->get()->toArray();
        return $get_thunbimageData;
    }
    public static function getgalImage($directyId)
    {
        $getimageData = DB::table('directory_gallery_images')->select('*')->where(['directory_id' => $directyId])->get()->toArray();
        return $getimageData;
    }
    public static function getgalImageChunk($directyId)
    {
        $getimageData = DB::table('directory_gallery_images')->select('*')->where(['directory_id' => $directyId])->get()->chunk(10);
        return $getimageData;
    }
    public static function getallgalImage($directyId)
    {
        $getallimageData = DB::table('directory_gallery_images')->select('*')->where(['directory_id' => $directyId])->get()->toArray();
        return $getallimageData;
    }
    public static function getCoverImage($directyId)
    {
        $getcoverimageData = DirectoryMediaCover::where(['directory_id' => $directyId])->orderBy('id', 'asc')->get();
        return $getcoverimageData;
    }
    public static function getTrademarkImage($directyId)
    {
        $gettrademarkimageData = DB::table('directory_media_trademarks_image')->select('*')->where('directory_id', $directyId)->orderBy('position', 'asc')->get()->toArray();
        return $gettrademarkimageData;
    }
    public function getGalleryImageDelete(Request $request)
    {
        $data = DirectoryMediagallery::find($request->id);
        $image_name = $data->name_thumbnail.".webp";
        $image_path = public_path('images/directory/'.$image_name);
        if(file_exists($image_path)){
          unlink($image_path);
        }
        $data = DirectoryMediagallery::find($request->id)->delete();
        return response()->json($data);
    }
    public function getCoverImageDelete(Request $request)
    {
        $data = DirectoryMediaCover::find($request->id);
        $image_name = $data->name_thumbnail.".webp";
        $image_path = public_path('images/directory/'.$image_name);
        if(file_exists($image_path)){
          unlink($image_path);
        }
        $data = DirectoryMediaCover::find($request->id)->delete();
        return response()->json($data);
    }
    public function getThumbImageDelete(Request $request)
    {
        $data = DirectoryMediaListing::find($request->id);
        $image_name = $data->name_thumbnail.".webp";
        $image_path = public_path('images/directory/'.$image_name);
        if(file_exists($image_path)){
          unlink($image_path);
        }
        $data = DirectoryMediaListing::find($request->id)->delete();
        return response()->json($data);
    }
    public function getTrademarkImageDelete(Request $request)
    {
        $data = DirectoryMediaTrademarks::find($request->id)->delete();
        return response()->json($data);
    }
    function gettypeCoulmnName($id)
    {
        $gettypevalueData = DB::table('category_configurations')->select('fields')->where('category_id', $id)->first();

        $getfeildstypevalueData = DB::table('category_fields')->select('*')->whereIn('id', explode(',', $gettypevalueData->fields??''))->get();
        return $getfeildstypevalueData;
    }


    public static function getTypeBlock(Request $request)
    {
        $block_num = $request->blockcount;
        $block_edit = $request->editval;
        $block_number_total = $request->blockNumber;
        $gettypevalueData = DB::table('category_configurations')->select('fields')->where('category_id', $request->type_value)->first();
        $getfeildstypevalueData = DB::table('category_fields')->select('*')->whereIn('id', explode(',', $gettypevalueData->fields))->get();

        if ($block_edit != "edit") {

            $html = '<hr style="border-top: dotted 3px;">
            <div class="row blocksCount" style="margin-top: 13px;" id="block_' . $block_num . '">
            <div class="col-md-4 mb-5">
                <label class="col-md-12 p-0">Upload Galley Images</label>
                <div class="col-md-12 imageuploadify">
                    <div class="imageuploadify-overlay2" style="display: none;"><i class="fa fa-picture-o"></i></div>
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                    <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>
                    <input type="file" multiple class="form-control" name="gallery_image['.$block_num.'][]" onChange="type_galley_img('.$block_num.')" id="type_galley_img_'.$block_num.'">
                </div>
            </div>
            <div class="col-md-1">
            <b class="count_uploaded_gallery" id="count_uploaded_gallery_'.$block_num.'"> </b>
            </div>
            <div class="col-md-1 mb-5">
                    OR
                </div>
            <div class="col-md-4 mb-5">
                <label class="col-md-12 p-0">Upload pdf file</label>
                <div class="col-md-12 imageuploadify">
                    <div class="imageuploadify-overlay2" style="display: none;"><i class="fa fa-picture-o"></i></div>
                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                    <span class="imageuploadify-message">Upload pdf file</span>
                    <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>
                    <input type="file" multiple class="form-control" accept=".pdf" name="gallery_type_pdf['.$block_num.'][]" onChange="gallery_type_pdf('.$block_num.')" id="gallery_type_pdf_'.$block_num.'">
                </div>
            </div>
            <div class="col-md-1">
            <b class="count_gallery_type_pdf" id="count_gallery_type_pdf_'.$block_num.'"> </b>
            </div>
             <div class="col-md-6">
               <label class="col-md-12 p-0">Upload New Image</label>
                <input type="file" class="form-control" name="image_'.$block_num.'">
            </div>

            ';
            foreach ($getfeildstypevalueData as $fieldData) {
                $html .= '
                <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12" >
                <div class="form-group mb-4">
                <div class="col-md-12 border-bottom p-0">
                <label class="col-md-12 p-0">' . $fieldData->label . '</label>
                ';
                if ($fieldData->type == "textarea") {
                    $html .= '<textarea name="' . $fieldData->name . '_' . $block_num . '" id="' . $fieldData->name . '" class="form-control" placeholder="' . $fieldData->label . '"></textarea>';
                } else if ($fieldData->type == "number") {
                    $html .= '<input type="' . $fieldData->type . '" min="0" oninput="this.value = Math.abs(this.value)" name="' . $fieldData->name . '_' . $block_num . '" id="' . $fieldData->name . '" class="form-control" placeholder="' . $fieldData->label . '">';
                } else {
                    $html .= '<input type="' . $fieldData->type . '" name="' . $fieldData->name . '_' . $block_num . '" id="' . $fieldData->name . '" class="form-control" placeholder="' . $fieldData->label . '">';
                }
                $html .= '
                </div>
                </div>
                </div>
                ';
            }

            if (!$block_num) {
                $html .= ' <div class="col-lg-12"><button type="button" class="btn btn-success" class="add_button" title="Add field" onclick=addSection()>+</button></div>  </div>';
            } else {
                $html .= ' <div class="col-lg-12"><button type="button" class="btn btn-danger" class="remove_button" title="Add field" onclick=removeSection(' . $block_num . ')>-</button></div>  </div>';
            }
            echo $html;
        } else {

            $gettypevalueTableData = DB::table('directory_type')->select('*')->where('category_id', $request->type_value)->where('directory_id', $request->blockdirectoryId)->get()->groupBy('block_num')->toArray();

            foreach ($gettypevalueTableData as $key => $value) {

                $count_DirectoryTypeGallery = DirectoryTypeGallery::where('block_num', $key)->where('category_id', $request->type_value)->where('directory_id', $request->blockdirectoryId)->count();
                $count_DirectoryTypePdf = DirectoryTypePdfs::where('block_num', $key)->where('category_id', $request->type_value)->where('directory_id', $request->blockdirectoryId)->count();


                if($count_DirectoryTypeGallery > 0){
                    $view_gallery_button = '
                    <a id="view_type_gallery_button" style="color:#fff" onClick="view_type_gallery_button('.$request->type_value.','.$request->blockdirectoryId.','.$key.')" class="btn btn-success mt-4" title="View Gallery" >
                    View Gallery ('.$count_DirectoryTypeGallery.')
                    </a>';
                }else{
                    $view_gallery_button = '';
                }

                if($count_DirectoryTypePdf > 0){
                    $view_pdf_button = '
                    <a id="view_type_pdf_button" style="color:#fff" onClick="view_type_pdf_button('.$request->type_value.','.$request->blockdirectoryId.','.$key.')" class="btn btn-success mt-4" title="View PDF" >
                    View PDF ('.$count_DirectoryTypePdf.')
                    </a>';
                }else{
                    $view_pdf_button = '';
                }

                $imagepath = env('IMAGE_PATH').'files/directorytypeimage/'.$value[0]->image_name;
                $html = '
                <hr style="border-top: dotted 3px;">
                <div class="row blocksCount" style="margin-top: 13px;" id="block_' . $key . '">

                <div class="col-md-4 mb-5">
                    <label class="col-md-12 p-0">Upload Galley Images</label>
                    <div class="col-md-12 imageuploadify">
                        <div class="imageuploadify-overlay2" style="display: none;"><i class="fa fa-picture-o"></i></div>
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                        <span class="imageuploadify-message">Drag&amp;Drop Your File(s)Here To Upload</span>
                        <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>
                        <input type="file" multiple class="form-control" accept=".jpg, .jpeg, .png, .gif, .webp" name="gallery_image['.$key.'][]" onChange="type_galley_img('.$key.')" id="type_galley_img_'.$key.'">
                    </div>
                </div>

                <div class="col-md-2">
                    <input type="hidden" id="active-tab" class="active-tab"  name="active_tab" value="type">
                    <button type="submit" onClick="type_galley_submit('.$key.')" id="listsubmit-dirtype" class="btn btn-primary listsubmit">Save </button>
                    <b class="count_uploaded_gallery" id="count_uploaded_gallery_'.$key.'"> </b> '. $view_gallery_button .'
                </div>
                <div class="col-md-1 mb-5">
                    OR
                </div>
                <div class="col-md-3 mb-5">
                    <label class="col-md-12 p-0">Upload pdf file</label>
                    <div class="col-md-12 imageuploadify">
                        <div class="imageuploadify-overlay2" style="display: none;"><i class="fa fa-picture-o"></i></div>
                        <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                        <span class="imageuploadify-message">Upload pdf file</span>
                        <button type="button" class="btn btn-default" style="background: white; color: rgb(58, 160, 255); pointer-events: initial;">or select file to upload</button>
                        <input type="file" multiple class="form-control" accept=".pdf" name="gallery_type_pdf['.$key.'][]" onChange="gallery_type_pdf('.$key.')" id="gallery_type_pdf_'.$key.'">
                    </div>
                </div>
                <div class="col-md-1">
                    <b class="count_gallery_type_pdf" id="count_gallery_type_pdf_'.$key.'"> </b> '. $view_pdf_button .'
                </div>

                <div class="col-md-6">
                    <label class="col-md-12 p-0">Upload New Image</label>
                    <input type="file" class="form-control" name="image_'.$key.'">
                </div>
                <div class="col-md-6">
                    <label class="col-md-12 p-0">Saved Image</label>
                    <img src="'.$imagepath.'" height="50px" width="50px">
                </div>


                ';
                foreach ($value as $fieldDataval) {
                    //$imagepath = env('IMAGE_PATH').'files/directorytypeimage/'.$fieldDataval->image_name;
                    $html .= '

                    <div class="col-md-4 col-lg-4 col-sm-4 col-xs-12" >
                        <div class="form-group mb-4">
                            <label class="col-md-12 p-0">
                            </label>
                            <div class="col-md-12 border-bottom p-0">
                            <label class="col-md-12 p-0">' . $fieldDataval->label . '</label>
                            ';
                    if ($fieldDataval->input_type == "textarea") {
                        $html .= '<textarea name="' . $fieldDataval->column_name . '_' . $key . '" id="' . $fieldDataval->column_name . '" class="form-control" placeholder="' . $fieldDataval->label . '">' . $fieldDataval->column_value . '</textarea>';
                    } else if ($fieldDataval->type ?? '' == "number") {
                        $html .= '<input type="' . $fieldDataval->input_type . '" min="0" oninput="this.value = Math.abs(this.value)" name="' . $fieldDataval->column_name . '_' . $key . '" id="' . $fieldDataval->column_name . '" class="form-control" placeholder="' . $fieldDataval->label . '" value="' . $fieldDataval->column_value . '">';
                    } else {
                        $html .= '<input type="' . $fieldDataval->input_type . '" name="' . $fieldDataval->column_name . '_' . $key . '" id="' . $fieldDataval->column_name . '" class="form-control" placeholder="' . $fieldDataval->label . '" value="' . $fieldDataval->column_value . '">';
                    }
                    $html .= '
                            </div>
                            </div>

                            </div>

                            ';
                }

                if (!$key) {
                    $html .= ' <div class="col-lg-12"><button type="button" class="btn btn-success" class="add_button" title="Add field" onclick=addSection()>+</button></div>  </div>';
                } else {
                    $html .= ' <div class="col-lg-12"><button type="button" class="btn btn-danger" class="remove_button" title="Add field" onclick=removeSection(' . $key . ')>-</button></div>  </div>';
                }
                echo $html .= '</div>';
            }
        }
    }

    public static function getSchedualBlock(Request $request)
    {

        $slotCount = $request->slotCount_value;
        $dayIndex = $request->dayIndex;
        if ($dayIndex == 0) {
            $weekday = "monday";
        } else if ($dayIndex == 1) {
            $weekday = "tuesday";
        } else if ($dayIndex == 2) {
            $weekday = "wednesday";
        } else if ($dayIndex == 3) {
            $weekday = "thursday";
        } else if ($dayIndex == 4) {
            $weekday = "friday";
        } else if ($dayIndex == 5) {
            $weekday = "saturday";
        } else if ($dayIndex == 6) {
            $weekday = "sunday";
        } else {
            $weekday = "";
        }
        $timeValue = array(
            "00:00", "00:30", "01:00", "01:30", "02:00", "02:30", "03:00", "03:30", "04:00", "04:30", "05:00", "05:30", "06:00", "06:30", "07:00", "07:30", "08:00",
            "08:30", "09:00", "09:30", "10:00", "10:30", "11:00", "11:30", "12:00", "12:30", "13:00", "13:30", "14:00", "14:30", "15:00", "15:30", "16:00", "16:30",
            "17:00", "17:30", "18:00", "18:30", "19:00", "19:30", "20:00", "20:30", "21:00", "21:30", "22:00", "22:30", "23:00", "23:30","24:00"
        );

        $dele = "'" . $weekday . "_" . $slotCount . "'";
        $html = '<list-item-element class="weekley-name" id="row_' . $weekday . '_' . $slotCount . '" dayindex="' . $dayIndex . '" slotindex="' . $slotCount . '">';
        $html .= '<div style="min-width: 20px" hidden=""></div>';
        $html .= '<time-input-element class="sub-min-time" name="min" dayindex="' . $dayIndex . '" slotindex="' . $slotCount . '" index="' . $dayIndex . '">';
        $html .= '<select name="' . $weekday . '_hours_min[]">';
        foreach ($timeValue as $rowtime) {
            $html .= '<option value="' . $rowtime . '">' . $rowtime . '</option>';
        }
        $html .= '</select></time-input-element>';
        $html .= '<time-input-element class="sub-max-time" name="max" dayindex="' . $dayIndex . '" slotindex="' . $slotCount . '" index="' . $dayIndex . '">';
        $html .= '<select name="' . $weekday . '_hours_max[]">';
        foreach ($timeValue as $rowtime) {
            $html .= '<option value="' . $rowtime . '">' . $rowtime . '</option>';
        }
        $html .= '</select></time-input-element>';
        $html .= '<button type="button" class="btn btn-danger" onclick="removeSchedual(' . $dele . ')">- Hours</button>';

        $html .= '</li></list-item-element>';
        echo $html;
    }
    public function getTagsAndOwntags(Request $request)
    {
        $data['tags'] = Tags::whereIn("category_id", $request->category_id)
            ->where('status', 'Active')->get(["tags", "id"]);
        return response()->json($data);
    }


    public function getTags(Request $request)
    {
        $data['tags'] = DB::table('category_tags')->select('id','tags')->whereIn('child_cat_id',$request->category_id)->where('status','Active')->orderBy('tags', 'asc')->groupBy('tags')->get()->toArray();
        return response()->json($data);
    }


    public function deleteHolidayRecord(Request $request)
    {
        $data = DirectoryCustomHoliday::find($request->id)->delete();
        return response()->json($data);
    }

    public function deleteHolidayPublicYesRecord(Request $request)
    {
        $data = HolidayTemplateDetails::find($request->id)->delete();
        return response()->json($data);
    }

    public function deleteHolidayExceptionalYesRecord(Request $request)
    {
        $data = ExceptionalHolidayTemplateDetails::find($request->id)->delete();
        return response()->json($data);
    }


    public function deleteExecptionHolidayRecord(Request $request)
    {
        $data = DirectoryExceptionHoliday::find($request->id)->delete();
        return response()->json($data);
    }
    public function directoryAllOffer($id)
    {
        $offerdataCount = Offer::query()->where('deleted', '0')->get()->count();
        $currentdate = strftime('%F');
        $totalOngoingOfferCount = Offer::query()->where('deleted', '0')->where('offer_active_date', '<=', $currentdate)->where('offer_expire_date', '>=', $currentdate)->get()->count();
        $totalExpiredOfferCount = Offer::query()->where('deleted', '0')->where('offer_expire_date', '<=', $currentdate)->get()->count();
        $totalUpcomingOffer = Offer::query()->where('deleted', '0')->where('offer_active_date', '>', $currentdate)->get()->count();
        $getOfferData = Offer::query()->where('deleted', '0')->where('offer_directory_link', $id)->orderBy('id', 'desc')->paginate(20);
        return view('admin.offer.index', ['offerdata' => $getOfferData, 'offerdataCount' => $offerdataCount, 'totalOngoingOfferCount' => $totalOngoingOfferCount, 'totalExpiredOfferCount' => $totalExpiredOfferCount, 'totalUpcomingOffer' => $totalUpcomingOffer]);
    }

    public function directoryOrders($id = null)
    {
        $dirOrdersData = Order::where('directory_id', $id)->orderBy('id', 'desc')->paginate(20);
        return view('admin.directory.orders', compact('dirOrdersData'));
    }

    public function ordersfilter(Request $request)
    {
        $start_date = Carbon::parse($request->startdate)->toDateTimeString();
        $end_date = Carbon::parse($request->enddate)->toDateTimeString();
        $directory_id = $request->directory_id ?? '';
        $status = $request->status ?? '';

        if (!empty($directory_id) && !empty($status) && !empty($request->startdate)) {
            $dirOrdersData = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->where('status', $status)->paginate(20);
        } elseif (empty($directory_id) && empty($status) && empty($request->startdate)) {
            $dirOrdersData = Order::paginate(20);
        } elseif (empty($directory_id) && empty($status) && !empty($request->startdate)) {
            $dirOrdersData = Order::WhereBetween('created_at', [$start_date, $end_date])->paginate(20);
        } elseif (!empty($directory_id) && empty($status) && !empty($request->startdate)) {
            $dirOrdersData = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->paginate(20);
        } elseif (!empty($directory_id) && empty($status) && empty($request->startdate)) {
            $dirOrdersData = Order::where('directory_id', $directory_id)->paginate(20);
        } else {
            $dirOrdersData = Order::WhereBetween('created_at', [$start_date, $end_date])->where('directory_id', $directory_id)->orWhere('status', $status)->paginate(20);
        }
        return view('admin.directory.orders', compact('dirOrdersData'));
    }

    public function directoryAllProduct($id)
    {
        $getProductData = Product::query()->where('deleted', '0')->where('directory_id', $id)->orderBy('id', 'desc')->paginate(20);
        $searchDateAct = '';
        $searchDateExp = '';
        $searchstatus = '';
        return view('admin.product.index', ['productdata' => $getProductData, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp, 'searchstatus' => $searchstatus]);
    }
    public function directoryAllVoucher($id)
    {
        $getProductData = Voucher::query()->where('directory_id', $id)->orderBy('id', 'desc')->paginate(20);
        return view('admin.discount.voucher.index', ['voucher' => $getProductData, 'data' => ""]);
    }
    public function directoryAllGiftVoucher($id)
    {
        $gift_voucher = Giftvoucher::query()->where('directory_id', $id)->orderBy('id', 'desc')->paginate(20);
        return view('admin.discount.giftvoucher.index', ['gift_voucher' => $gift_voucher, 'data' => ""]);
    }
    public function getAmenity(Request $request)
    {
        $dataamenity = array();$amenityCatName = array();$i=0;
        foreach($request->amcat_id as $value){
            $amenityCatName[] = AmenityCategory::where("id", $value)->get(["name"]);
            $dataamenity[$amenityCatName[$i][0]->name] = Amenity::where("category_id", $value)->get(["icon_class", "id", "title", "category_id"]);
            $i++;
        }
        return response()->json($dataamenity);
    }
    public function getTrademarksByCatid(Request $request)
    {
        $data['trademark'] = Trademarks::where("category_id", $request->category_id)
            ->get(["id", "name"]);
        return response()->json($data);
    }
    public static function getNormalTemplateData(Request $request)
    {
        $getTemplateData = DB::table('normaltemplate')->select('*')->where('template_id',$request->teplate_id)->get()->toArray();
        $getNormalTemplateData = self::group_assoc(json_decode(json_encode($getTemplateData), 1), 'day');
        $html = '<table id="nomaldays-template-table" class="table text-nowrap">
                    <thead>
                        <tr>
                            <th class="border-top-0">Day</th>
                            <th class="border-top-0">Closed</th>
                            <th class="border-top-0">Open</th>
                            <th class="border-top-0">Closed</th>
                            <th class="border-top-0">Add Hours</th>
                        </tr>
                    </thead>
                    <tbody>';
            $days = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
            $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
        "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
        "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");

        foreach($getNormalTemplateData as $value=>$dayDetail){
            foreach($dayDetail as $key => $dayData){
            $html.= '<tr class="'.$value.' '.$value.'_template_row_'.$key.'">';
                $html.= '<td>';
                    if($key==0){
                        $html.=$value.':';
                    }else{
                        $html.='';
                    }
                $html.='</td>';
                $html.= '<td>';
                    $selectedisclosedData = $dayData['is_closed']==1?"checked":"";
                    if($key==0){
                        $html.='<input class="form-check-input" type="checkbox" name="'.$value.'_template_is_closed" value="1" '.$selectedisclosedData.'>';
                    }
                $html.= '</td>';
                $html.= '<td><select class="form-control" name="'.$value.'_template_hours_min[]">';
                    foreach($timeValue as $rowtime){
                        $opentimeselectedvalue = $rowtime==$dayData['open_time']?"selected":"";
                        $html.= '<option value="'.$rowtime.'"'.$opentimeselectedvalue.'>'.$rowtime.'</option>';
                    }
                $html.= '</select></td>';
                $html.= '<td><select class="form-control" name="'.$value.'_template_hours_max[]">';
                    foreach($timeValue as $rowtime){
                        $closetimeselectedvalue = $rowtime==$dayData['close_time']?"selected":"";
                        $html.= '<option value="'.$rowtime.'"'.$closetimeselectedvalue.'>'.$rowtime.'</option>';
                    }
                $html.= '</select></td>';
                $html.= '<td>';
                $valday = "'".$value."'";
                    if($key==0){
                        $html.='<button type="button" class="btn btn-info" id="" onclick="addTemplateDayBlock(' .$valday. ')">+ Hours</button>';
                    }else{
                        $html.='<button class="btn btn-danger" type="button" onclick="removeTemplateDayBlock('.$value.','.$key.')">- Hours</button>';
                    }
                $html.= '</td>';
            $html.= '<tr>';
            }
        }
    echo $html;
    }

    public static function getPublicTemplateData(Request $request)
    {
        $getPublicTemplateData = DB::table('holidaytemplate_details')->select('*')->where('template_id',$request->publicsele_id)->get()->toArray();
        if($getPublicTemplateData!=0){
            $html = '<table id="public-template" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="border-top-0">Date</th>
                        <th class="border-top-0">Note</th>
                        <th class="border-top-0">Open</th>
                        <th class="border-top-0">Closed</th>

                    </tr>
                </thead>
                <tbody>';
                $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
        "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
        "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                    foreach($getPublicTemplateData as $holidayrow){
                        $opentimeholiday =   json_decode($holidayrow->custom_time);
                        $html.='<tr id="tblholi_'.$holidayrow->id.'"><input type="hidden" name="template_id[]" value="'.$holidayrow->id.'">';
                        $html.='<td><input type="date" name="'.$holidayrow->id.'_custom_date" value="'.$holidayrow->custom_date.'" class="form-control  border-0" autocomplete=off></td>';
                        $html.='<td><input type="text" name="'.$holidayrow->id.'_note" placeholder="Add note" value="'.$holidayrow->note.'" class="form-control border-0" autocomplete=off></td>';
                        $html.='<td>';
                                foreach($opentimeholiday as $extratime){
                                    $html.='<select class="form-control" name="'.$holidayrow->id.'_temp_time_hours_min[]">';
                                    foreach($timeValue as $rowtime){
                                        $opentimeselectedvalue = $rowtime==$extratime->open_time?"selected":"";
                                        $html.= '<option value="'.$rowtime.'"'.$opentimeselectedvalue.'>'.$rowtime.'</option>';

                                    }
                                    $html.='</select>';
                                }
                        $html.='</td><td>';
                        foreach($opentimeholiday as $extratime){
                            $html.='<select class="form-control" name="'.$holidayrow->id.'_temp_time_hours_max[]">';
                            foreach($timeValue as $rowtime){
                                $closetimeselectedvalue = $rowtime==$extratime->close_time?"selected":"";
                                $html.= '<option value="'.$rowtime.'"'.$closetimeselectedvalue.'>'.$rowtime.'</option>';
                            }
                            $html.='</select>';
                        }


                        $html.='</td>';


                        $html.='</tr>';
                    }
                $html.='</tbody>';
        $html.='</table>';
        }
        echo $html;
    }
    public static function getExceptionTemplateOption(Request $request)
    {
        $getExceptionTemplateData = DB::table('exceptionalholidaytemplate_details')->select('*')->where('template_id',$request->exceptionsele_id)->get()->toArray();
        if($getExceptionTemplateData!=0){
            $html = '<table id="exception-template" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="border-top-0">Date</th>
                        <th class="border-top-0">Note</th>
                        <th class="border-top-0">Open</th>
                        <th class="border-top-0">Closed</th>

                    </tr>
                </thead>
                <tbody>';
                $timeValue = array("00:00","00:30","01:00","01:30","02:00","02:30","03:00","03:30","04:00","04:30","05:00","05:30","06:00","06:30","07:00","07:30","08:00",
        "08:30","09:00","09:30","10:00","10:30","11:00","11:30","12:00","12:30","13:00","13:30","14:00","14:30","15:00","15:30","16:00","16:30",
        "17:00","17:30","18:00","18:30","19:00","19:30","20:00","20:30","21:00","21:30","22:00","22:30","23:00","23:30","24:00");
                    foreach($getExceptionTemplateData as $holidayrow){
                        $opentimeholiday =   json_decode($holidayrow->custom_time);
                        $html.='<tr id="tblexcholi_'.$holidayrow->id.'"><input type="hidden" name="exception_template_id[]" value="'.$holidayrow->id.'">';
                        $html.='<td><input type="date" name="'.$holidayrow->id.'_exception_date" value="'.$holidayrow->custom_date.'" class="form-control  border-0" autocomplete=off></td>';
                        $html.='<td><input type="text" name="'.$holidayrow->id.'_exception_note" placeholder="Add note" value="'.$holidayrow->note.'" class="form-control border-0" autocomplete=off></td>';
                        $html.='<td>';
                                foreach($opentimeholiday as $extratime){
                                    $html.='<select class="form-control" name="'.$holidayrow->id.'_exc_time_hours_min[]">';
                                    foreach($timeValue as $rowtime){
                                        $opentimeselectedvalue = $rowtime==$extratime->open_time?"selected":"";
                                        $html.= '<option value="'.$rowtime.'"'.$opentimeselectedvalue.'>'.$rowtime.'</option>';

                                    }
                                    $html.='</select>';
                                }
                        $html.='</td><td>';
                        foreach($opentimeholiday as $extratime){
                            $html.='<select class="form-control" name="'.$holidayrow->id.'_exc_time_hours_max[]">';
                            foreach($timeValue as $rowtime){
                                $closetimeselectedvalue = $rowtime==$extratime->close_time?"selected":"";
                                $html.= '<option value="'.$rowtime.'"'.$closetimeselectedvalue.'>'.$rowtime.'</option>';
                            }
                            $html.='</select>';
                        }
                        $html.='</td>';
                        $html.='</tr>';
                    }
                $html.='</tbody>';
        $html.='</table>';
        }
        echo $html;
    }

    public function galleryImageDeleteAll( Request $request )
    {
        $data = DirectoryMediagallery::where("directory_id", $request->directory_id)->get("*");
        foreach($data as $rowdata){
            $image_name = $rowdata->name_thumbnail.".webp";
            $image_path = public_path('images/directory/'.$image_name);
            if(file_exists($image_path)){
              unlink($image_path);
            }
        }

        DirectoryMediagallery::where('directory_id', $request->directory_id)->delete();
        return response()->json(['success'=>'Status change successfully.']);
    }
    public function getvendorbyid(Request $request)
        {
            //echo "test vendor data";
            $data = User::where("id", $request->vendor_id)->where('status', 1)->get("*");
            return response()->json($data);
        }
}
