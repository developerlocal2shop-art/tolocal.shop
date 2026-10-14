<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Attribute;
use Intervention\Image\ImageManagerStatic as Image;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\ProductCategories;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\DirectoryCreateOwnTags;
use App\Models\ProductAttributes;
use App\Models\ProductAttributesVariation;
use App\Models\Tags;
use App\Models\Productcategory;
use App\Models\ProductTags;
use App\Models\ProductVideo;
use App\Models\DirectorySeo;
use App\Models\DiectoryLocation;
use App\Models\client\Wishlist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ProductControllers extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {

            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $directory_id = $request->input('directory_id');
            $act_date = $request->input('searchdate') ? $request->input('searchdate') : "";
            $exp_date = $request->input('searchdateexpiry') ? $request->input('searchdateexpiry') : "";
            $statusSearch = $request->input('status') ? $request->input('status') : "";
            if ($directory_id != "") {
                $data['product'] = DB::table('product')->select('*')
                    ->where('name', 'like', '%' . $searchQuery . '%')
                    ->Where('directory_id',  $directory_id);
            } elseif ($act_date != "") {
                $data['product'] = DB::table('product')->select('*')->where('opening_date',  $act_date);
                $productdata =    $data['product']->orderBy('id', 'desc')->paginate(20);
                $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
                $searchDateAct = isset($act_date) ? $act_date : '';
                $searchDateExp = isset($exp_date) ? $exp_date : '';
                $searchstatus = isset($statusSearch) ? $statusSearch : '';
                return view('admin.product.index', ['productdata' => $productdata, 'textsearch' => $searchtext, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp, 'query' => $query, 'searchstatus' => $searchstatus]);
            } else if ($exp_date != "") {
                $data['product'] = DB::table('product')->select('*')->where('closing_date',  $exp_date);
                $productdata =    $data['product']->orderBy('id', 'desc')->paginate(20);
                $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
                $searchDateExp = isset($exp_date) ? $exp_date : '';
                $searchDateAct = isset($act_date) ? $act_date : '';
                $searchstatus = isset($statusSearch) ? $statusSearch : '';
                return view('admin.product.index', ['productdata' => $productdata, 'textsearch' => $searchtext, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp, 'query' => $query, 'searchstatus' => $searchstatus]);
            } else if ($statusSearch == "active") {
                $data['product'] = DB::table('product')->select('*')->where('status',  $statusSearch);
                $productdata =    $data['product']->orderBy('id', 'desc')->paginate(20);
                $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
                $searchDateExp = isset($exp_date) ? $exp_date : '';
                $searchDateAct = isset($act_date) ? $act_date : '';
                $searchstatus = isset($statusSearch) ? $statusSearch : '';
                return view('admin.product.index', ['productdata' => $productdata, 'textsearch' => $searchtext, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp, 'query' => $query, 'searchstatus' => $searchstatus]);
            } else if ($statusSearch == "inactive") {
                $data['product'] = DB::table('product')->select('*')->where('status',  $statusSearch);
                $productdata =    $data['product']->orderBy('id', 'desc')->paginate(20);
                $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
                $searchDateExp = isset($exp_date) ? $exp_date : '';
                $searchDateAct = isset($act_date) ? $act_date : '';
                $searchstatus = isset($statusSearch) ? $statusSearch : '';
                return view('admin.product.index', ['productdata' => $productdata, 'textsearch' => $searchtext, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp, 'query' => $query, 'searchstatus' => $searchstatus]);
            } else {
                $data['product'] = DB::table('product')->select('*')->where('name', 'like', '%' . $searchQuery . '%')->orWhere('ena', 'like', '%' . $searchQuery . '%');
            }

            $product_tags_id = DB::table('product_tags')->where('tags',$searchQuery)->first();
                if(!empty($product_tags_id)){
                    $data['product']->orwhereRaw('FIND_IN_SET("' . $product_tags_id->id. '",lc_product.tags)');
                }
            $productdata =    $data['product']->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            $searchDateExp = isset($exp_date) ? $exp_date : '';
            $searchDateAct = isset($act_date) ? $act_date : '';
            $searchstatus = isset($statusSearch) ? $statusSearch : '';
            return view('admin.product.index', ['productdata' => $productdata, 'textsearch' => $searchtext, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp, 'query' => $query, 'searchstatus' => $searchstatus]);
        } else {
            $main = 'main';
            $productdata = Product::with(['image' => function ($q) use ($main) {
                $q->where('main_image', '=', $main);
            }])->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $searchtext = isset($searchQuery) ? $request->input('searchtext') : "";
            $searchDateExp = isset($exp_date) ? $exp_date : '';
            $searchDateAct = isset($act_date) ? $act_date : '';
            $searchstatus = isset($statusSearch) ? $statusSearch : '';
            return view('admin.product.index', ['productdata' => $productdata, 'textsearch' => $searchtext, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp, 'searchstatus' => $searchstatus]);
        }
    }

    public function create()
    {
        $attributesData = Attribute::where("deleted", '0')->where('directory_id', '==', '0')->get();
        $data['languagesData']          = Helper::getAllLanguages();           /* get languages */
        $data['parent_category']        = Helper::getAllParentProductCategory();      /* get product Category */
        $data['productsubcat']          = Helper::getProductCatgoeryData();
        $data['currency']               = Helper::getAllCurrency();             /* get All Currency */
        $data['alltags']                = Helper::getTagsData();       /* get Tags Category */
        $data['tags']                   = Helper::getProductTagsData();                /* get tags */
        return view('admin.product.create', ['data' => $data, 'attributesData' => $attributesData]);
    }
    
    public function submit(Request $request)
    {
        $this->validate($request, [
            'product_name_en'       => 'required|max:50',
            'short_des_en'          => 'max:100',
            'long_des_en'           => 'max:800',
            // 'sku'                   => 'required|unique:product,sku',
            'main_image'            => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'gal_image'             => 'array|max:15',
            'gal_image.*'           => 'mimes:jpeg,png,jpg,gif,svg',
            'closing_date'          => 'required',
            'opening_date'          => 'required'
        ]);

        $latitude = $request->input('lati');
        $longitude = $request->input('long');
        $dirId = $request->input('directoryId'); 
        $dirlocationdetails = DiectoryLocation::where('directory_id', $dirId)->first();
        if (!empty($dirlocationdetails)) {
            if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                $latitude = $dirlocationdetails['latitude'];
                $longitude = $dirlocationdetails['longitude'];
            }
        }
        
        $name_en = $request->input('product_name_en');
        $product_inst = new Product;
        $product_inst->name                             = (isset($name_en)) ? $name_en : "";
        $product_inst->seo_url                          = Helper::getSeoUrl($name_en);
        $product_inst->product_number_type              = $request->input('product_number_type');
        $product_inst->sku                              = $request->input('sku');
        $product_inst->directory_id                     = $request->input('directoryId');
        $product_inst->cat_id                           =  $request->input('category');
        $product_inst->popular                           =  $request->input('popular') ?? 0;
        $product_inst->equal_product                     = $request->input('equal_product');
        if ($request->input('vendor_own_tags')) {
            $productTags = array();
            foreach ($request->input('vendor_own_tags') as $tagsrow) {
                $productTags[] = $tagsrow;
            }
            $product_inst->tags                              =  implode(",", $productTags);
        }

        $product_inst->stock                            = $request->input('stock');
        $product_inst->ena                              = $request->input('ena');
        $product_inst->supplier_product_no              = $request->input('supplier_product_no');
        $product_inst->status                           = 'Active';
        $product_inst->outofstock                       = $request->input('outstock');
        $product_inst->in_store_only                    = $request->input('in_store_only');
        $product_inst->purchase_price                   = $request->input('purchase_price');
        $product_inst->is_free_product                  = $request->input('is_free_product');
        $product_inst->video_link                       = $request->input('video_link');
        /*$product_inst->latitude                         =   $request->input('lati');
        $product_inst->longitude                        =   $request->input('long');*/
        $product_inst->latitude                         =   $latitude;
        $product_inst->longitude                        =   $longitude;
        $product_inst->supplier_name                    = $request->input('supplier_name');
        $product_inst->is_visible_on_shop_detailpage    = $request->input('is_visible_on_shop_detailpage') ? 'yes' : 'no';
        $product_inst->created_by                       = $request->input('createdby');
        $product_inst->closing_date                     = $request->input('closing_date');
        $product_inst->opening_date                     = $request->input('opening_date');
        $product_inst->default_duration                 = $request->input('default_status');
        if ($request->input('default_status') == 'yes') {
            $product_inst->default_month                = $request->input('set_months');
        }
        $product_inst->save();
        $product_id = $product_inst->id;

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        foreach ($languages as $lang) {
            // $product_name                               = $request->input('product_name_' . $lang->code);
            // $product_short_content                      = $request->input('short_des_' . $lang->code);
            // $product_long_content                       = $request->input('long_des_' . $lang->code);
            // $product_meta_title                         = $request->input('meta_title_' . $lang->code);
            // $product_meta_description                   = $request->input('meta_description_' . $lang->code);

                $product_name                           = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
                $product_short_content                  = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('short_des_en'));
                $product_long_content                   = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('long_des_en'));
                $product_meta_title                     = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_title_en'));
                $product_meta_description               = $tr->setSource('en')->setTarget($lang->code)->translate($request->input('meta_description_en'));

            $productshortinsert = new ProductTranslation;
            $productshortinsert->product_id             = $product_id;
            $productshortinsert->language_id            = $lang->id;
            $productshortinsert->name                   = isset($product_name) ? $product_name : $name_en;
            $productshortinsert->short_content          = isset($product_short_content) ? $product_short_content : $request->input('short_des_en');
            $productshortinsert->long_content           = isset($product_long_content) ? $product_long_content : $request->input('long_des_en');
            $productshortinsert->meta_title             = isset($product_meta_title) ? $product_meta_title : $request->input('meta_title_en');
            $productshortinsert->meta_description       = isset($product_meta_description) ? $product_meta_description : $request->input('meta_description_en');
            $productshortinsert->meta_keyword             = isset($product_short_content) ? $product_short_content : "";
            $productshortinsert->save();
        }

        /* Seo Entries start */
        $product_meta_title = $request->input('meta_title_en');
        $product_meta_description = $request->input('meta_description_en');
        $dirtseo = new DirectorySeo;
        $dirtseo->module_id     = $product_id;
        $dirtseo->page_type     = 'product';
        $dirtseo->meta_keyword  =  isset($product_meta_title) ? $product_meta_title : "";
        $dirtseo->meta_description  = isset($product_meta_description) ? $product_meta_description : "";
        $dirtseo->save();

        $imgfile = $request->file('main_image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name3 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name3;
            $imageName = Helper::getSeoUrl($imageName2);
            /*
            $imagepath = 'files/product/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/product/' . $imageName));
            */

                $imagepath = 'images/product/' . $imageName;
                $convertext = "webp";        
                $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);        
                Storage::disk('images')->put('product/'.$imageName.'.'.$convertext,$image_resize);

            $productmainimage_insert                    = new ProductImage;
            $productmainimage_insert->product_id        = $product_id;
            $productmainimage_insert->main_image        = "main";
            $productmainimage_insert->name_thumbnail    = $imageName;
            $productmainimage_insert->name_large        = $imageName;
            $productmainimage_insert->extension         = $ext;
            $productmainimage_insert->path              = $imagepath;
            $productmainimage_insert->save();
        }

        $imgfilegal = $request->file('gal_image');
        if ($imgfilegal) {
            foreach ($imgfilegal as $imgrow) {
                $ext = $imgrow->getClientOriginalExtension();
                $name2 = $imgrow->getClientOriginalName();
                $imageName2 = time() . $name2;
                $imageName = Helper::getSeoUrl($imageName2);
                /*
                $imagepath = 'files/product/' . $imageName;
                $image_resize = Image::make($imgrow->getRealPath());
                $image_resize->save(public_path('files/product/' . $imageName));
                */

                $imagepath = 'images/product/' . $imageName;
                $convertext = "webp";        
                $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);        
                Storage::disk('images')->put('product/'.$imageName.'.'.$convertext,$image_resize);

                $productmainimage_insert                    = new ProductImage;
                $productmainimage_insert->product_id        = $product_id;
                $productmainimage_insert->main_image        = "galimg";
                $productmainimage_insert->name_thumbnail    = $imageName;
                $productmainimage_insert->name_large        = $imageName;
                $productmainimage_insert->extension         = $ext;
                $productmainimage_insert->path              = $imagepath;
                $productmainimage_insert->save();
            }
        }
        /* Product Category Starts */
        $categories = $request->input('subproductcat');
        if (!empty($categories)) {
            foreach ($categories as $cat => $value) {
                $productcategoryInsertData = new ProductCategories;
                $productcategoryInsertData->product_id  = $product_id;
                $productcategoryInsertData->category_id  = $value;
                $productcategoryInsertData->save();
            }
        }
        /* Product Category Ends */
        $videofile = $request->file('video');
        if ($videofile) {
            $video_ext = $videofile->getClientOriginalExtension();
            $name2 = $videofile->getClientOriginalName();
            $videoName = time() . $name2;
            $videopath = 'files/productvideo/' . $videoName;
            $videofile->move(public_path('files/productvideo/'), $videoName);
            $productvideo_insert                    = new ProductVideo();
            $productvideo_insert->product_id        = $product_id;
            $productvideo_insert->video_link        = $videoName;
            $productvideo_insert->save();
        }

        /* Currency Data Start */
        $currencydata = Helper::getAllCurrency();             /* get All Currency */
        foreach ($currencydata as $currencyrow) {
            
            if ($request->input('sales_' . $currencyrow->code)) {
                $salespr = $request->input('sales_' . $currencyrow->code);
            } else {
                $salespr = 0;
            }
        
            // Fetching JSON
				$req_url = 'https://api.exchangerate-api.com/v4/latest/SEK';
				$response_json = file_get_contents($req_url);
				// Continuing if we got a result
				if(false !== $response_json) {
				    
				    try {
					
                    $regular_EUR = $request->input('regular_SEK');
				    $response_object = json_decode($response_json);

                    $base_price = $regular_EUR;
                    $currencyRates = [
                        "BGN" => $response_object->rates->BGN ?? 1, // Set default rate to 1 if rate is not available
                        "GBP" => $response_object->rates->GBP ?? 1,
                        "PLN" => $response_object->rates->PLN ?? 1,
                        "DKK" => $response_object->rates->DKK ?? 1,
                        "NOK" => $response_object->rates->NOK ?? 1,
                        "EUR" => $response_object->rates->EUR ?? 1,
                        "SEK" => $response_object->rates->SEK ?? 1,
                        "HRK" => $response_object->rates->HRK ?? 1,
                        "RON" => $response_object->rates->RON ?? 1,
                        "CZK" => $response_object->rates->CZK ?? 1,
                        "HUF" => $response_object->rates->HUF ?? 1,
                    ];
                    // Check if currency code exists in the array, if not, set the rate to 1
                    $rate = $currencyRates[$currencyrow->code] ?? '';
                    $result_price = round(($base_price * $rate), 2);
						
						$productPriceInsertData                     = new ProductPrice;
                        $productPriceInsertData->currency_id        = $currencyrow->id;
                        $productPriceInsertData->currency_code      = $currencyrow->code;
                        $productPriceInsertData->currency_name      = $currencyrow->name;
                        $productPriceInsertData->currency_base      = $currencyrow->base_currency;
                        $productPriceInsertData->product_id         = $product_id;
                        $productPriceInsertData->sales_price        = ($salespr) ? $salespr : 0;
                        $productPriceInsertData->regular_price      = $result_price??0;
                        $productPriceInsertData->save();
				    }
				    catch(Exception $e) {
				        // Handle JSON parse error...
				    }
				}


        }
        /* Currency Data Ends */
        $attributesId = $request->attr_id;
        $attributesVal = $request->attr_value;
        if (!empty($attributesId) && !empty($attributesVal)) {
            ProductAttributes::where('product_id', $product_id)->delete();
            $attributesData = array_combine($attributesId, $attributesVal);
            foreach ($attributesData as $key => $val) {
                ProductAttributes::create([
                    'product_id' =>  $product_id,
                    'attribute_id' =>  $key,
                    'attribute_value' =>  $val
                ]);
            }
        }
        $v_attr = $request->v_attr_value;
        if (!empty($v_attr)) {
            $attr_value_count = count(array_filter($request->attr_value));
            $v_attr_value_divid = array_chunk($v_attr, $attr_value_count);
            foreach ($v_attr_value_divid as $key => $v_attr_value) {
                $v_attr_str = implode(',', $v_attr_value);
                ProductAttributesVariation::create([
                    'product_id' =>  $product_id,
                    'attribute_value' =>  $v_attr_str,
                    'v_ena' =>  $request->v_ena[$key],
                    'v_stock' =>  $request->v_stock[$key]
                ]);
            }
        }
        return redirect('admin/product')->with('msg', 'Product Added Successfully!');
    }

    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        $data['parent_category']    = Helper::getAllParentProductCategory();      /* get product Category */
        $data['prosubcat']          = Helper::getProductsubCatgoeryData();
        $data['currency']           = Helper::getAllCurrency();             /* get All Currency */
        $data['productDetails']     = Product::findOrFail($id);
        $data['alltags']            = Helper::getTagsData();       /* get Tags Category */
        $data['directoryTags']      = DirectoryCreateOwnTags::where('status', 'Active')->get()->toArray();
        /* product translation data */
        $data['product_translationDesData'] = DB::table('product as p')->select('*')
            ->join('product_translation as pt', 'p.id', '=', 'pt.product_id')
            ->where('pt.product_id',  $id)->get();
            
        $data['product_seo'] = DB::table('directory_seo')->where('module_id', $id)->where('page_type', 'product')->first();
        /* product category data */
        $productcategoryData = ProductCategories::select('category_id')->where('product_id', $id)->get()->toArray();
        $data['product_categoryData'] = array_column($productcategoryData, 'category_id');
        $data['product_catData'] = DB::table('product as p')->select('*')
            ->join('product_categories as p_cat', 'p.id', '=', 'p_cat.product_id')
            ->where('p_cat.product_id',  $id)->get();
        $data['pro_subcat']       = Productcategory::where('parent_id', $data['productDetails']->cat_id)->where('status', 'Active')->orderBy('name', 'ASC')->get()->toArray();
        $data['productpriceData'] = ProductPrice::select('*')->where('product_id', $id)->get()->toArray();
        $data['productvideoData'] = ProductVideo::select('*')->where('product_id', $id)->get()->toArray();
        $data['tags']         = ProductTags::whereIn('child_cat_id', $data['product_categoryData'])->where('status', 'Active')->orderBy('tags', 'ASC')->get()->toArray();
        $productdirId = Product::select('created_by', 'directory_id')->where('id', $id)->get()->toArray();
        
        $attributesData = Attribute::with(['productAttributes' => function ($q) use ($id) {
            $q->where('product_id', '=', $id);
        }])->where('directory_id', $productdirId[0]['directory_id'])->orWhere('directory_id', 0)->groupBy('slug')->get();

        $attributesVariationData = ProductAttributesVariation::where('product_id', $id)->get();
        return view('admin.product.edit', ['data' => $data, 'attributesData' => $attributesData, 'attributesVariationData' => $attributesVariationData]);
    }



    /* ----------------------------- UPDATE PRODUCT ----------------------------------------*/
    public function update(Request $request, $product_id)
    {

        $this->validate($request, [
            'product_name_en'   => 'required|max:50',
            'short_des_en'      => 'max:100',
            'long_des_en'       => 'max:800',
            'gal_image'         => 'array|max:15',
            'closing_date'      => 'required'
        ]);
        $name_en = $request->input('product_name_en');
        $tagsId = $request->input('vendor_own_tags');
        if ($tagsId) {
            $productTags = array();
            foreach ($tagsId as $tagsrow) {
                $productTags[] = $tagsrow;
            }
        }
        if (!empty($productTags)) {
            $tag_id = implode(",", $productTags);
        } else {
            $tag_id = '0';
        }
        if ($request->input('default_status') == 'yes') {
            $default_month_value     = $request->input('set_months');
        }

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

        Product::where('id', $product_id)
            ->update([
                'name'                                  => (isset($name_en)) ? $name_en : "",
                'seo_url'                               => Helper::getSeoUrl($name_en),
                'product_number_type'                   => $request->input('product_number_type'),
                'sku'                                   => $request->input('sku'),
                'directory_id'                          => $request->input('directoryId'),
                'tags'                                  => $tag_id,
                'cat_id'                                => $request->input('category'),
                'stock'                                 => $request->input('stock'),
                'ena'                                   => $request->input('ena'),
                'supplier_product_no'                   => $request->input('supplier_product_no'),
                'closing_date'                          => $request->input('closing_date'),
                'opening_date'                          => $request->input('opening_date'),
                'default_duration'                      => $request->input('default_status'),
                'default_month'                         => isset($default_month_value) ? $default_month_value : '',
                'outofstock'                            => $request->input('outstock'),
                'in_store_only'                         => $request->input('in_store_only'),
                'purchase_price'                        => $request->input('purchase_price'),
                'is_free_product'                       => $request->input('is_free_product'),
                'video_link'                            => $request->input('video_link'),
                //'latitude'                              => $latitude,
                //'longitude'                             => $longitude,
                'supplier_name'                         => $request->input('supplier_name'),
                'is_visible_on_shop_detailpage'         => $request->input('is_visible_on_shop_detailpage') ? 'yes' : 'no',
                'popular'                               => $request->input('popular') ?? 0,
                'equal_product'                         => $request->input('equal_product'),
            ]);


        //dd($request->all());    

        $short_des_en = $request->input('short_des_en');
        $long_des_en = $request->input('long_des_en');
        $meta_title_en = $request->input('meta_title_en');
        $meta_description_en = $request->input('meta_description_en');

        $is_not_change_en_language = ProductTranslation::where('language_id',1)->where('product_id',$product_id)
        ->where('name',$name_en)
        ->where('short_content', $short_des_en)
        ->where('long_content', $long_des_en)
        ->where('meta_title', $meta_title_en)
        ->where('meta_description', $meta_description_en)
        ->exists();

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();
        if($is_not_change_en_language){
            foreach ($languages as $lang) {
               
                $product_name                   = $request->input('product_name_' . $lang->code);
                $product_short_content          = $request->input('short_des_' . $lang->code);
                $product_long_content           = $request->input('long_des_' . $lang->code);
                $product_meta_title             = $request->input('meta_title_' . $lang->code);
                $product_meta_description       = $request->input('meta_description_' . $lang->code);
                $languageTranslation_id         = $request->input('langtranlation_' . $lang->code);

                $productshortedit = ProductTranslation::find($languageTranslation_id);
                    $productshortedit->product_id             = $product_id;
                    $productshortedit->language_id            = $lang->id;
                    $productshortedit->name                   = isset($product_name) ? $product_name : "";
                    $productshortedit->short_content          = isset($product_short_content) ? $product_short_content : "";
                    $productshortedit->long_content           = isset($product_long_content) ? $product_long_content : "";
                    $productshortedit->meta_title             = isset($product_meta_title) ? $product_meta_title : "";
                    $productshortedit->meta_description       = isset($product_meta_description) ? $product_meta_description : "";
                    $productshortedit->meta_keyword           = isset($product_short_content) ? $product_short_content : "";
                    $productshortedit->save();
            }

        }else{

            ProductTranslation::where('product_id', $product_id)->delete();
            foreach ($languages as $lang) {

                $product_name               = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'');
                $product_short_content      = $tr->setSource('en')->setTarget($lang->code)->translate($short_des_en??'');
                $product_long_content       = $tr->setSource('en')->setTarget($lang->code)->translate($long_des_en??'');
                $product_meta_title         = $tr->setSource('en')->setTarget($lang->code)->translate($meta_title_en??'');
                $product_meta_description   = $tr->setSource('en')->setTarget($lang->code)->translate($meta_description_en??'');

                $productshortinsert = new ProductTranslation();
                $productshortinsert->product_id             = $product_id;
                $productshortinsert->language_id            = $lang->id;
                $productshortinsert->name                   = isset($product_name) ? $product_name : $name_en ;
                $productshortinsert->short_content          = isset($product_short_content) ? $product_short_content : $short_des_en;
                $productshortinsert->long_content           = isset($product_long_content) ? $product_long_content : $long_des_en;
                $productshortinsert->meta_title             = isset($product_meta_title) ? $product_meta_title : $meta_title_en;
                $productshortinsert->meta_description       = isset($product_meta_description) ? $product_meta_description : $meta_description_en;
                $productshortinsert->meta_keyword           = isset($product_short_content) ? $product_short_content : $short_des_en;
                $productshortinsert->save();
            }

        }

        // $languages = Helper::getAllLanguages();
        // foreach ($languages as $lang) {
        //     ProductTranslation::updateOrCreate(
        //         [
        //             'product_id'  => $product_id,
        //             'language_id' => $lang->id,
        //         ],
        //         [
        //             'name'             => $request->input('product_name_' . $lang->code, ''),
        //             'short_content'    => $request->input('short_des_' . $lang->code, ''),
        //             'long_content'     => $request->input('long_des_' . $lang->code, ''),
        //             'meta_title'       => $request->input('meta_title_' . $lang->code, ''),
        //             'meta_description' => $request->input('meta_description_' . $lang->code, ''),
        //             'meta_keyword'     => $request->input('short_des_' . $lang->code, ''),
        //         ]
        //     );
        // }

        $module_id = $request->input('module_id');
        $page_type = $request->input('page_type');
        $product_meta_title = $request->input('meta_title_en')??'';
        $product_meta_description = $request->input('meta_description_en')??'';
        if (!empty($module_id) && (!empty($page_type))) {
            DirectorySeo::where('module_id', $product_id)->where('page_type', 'product')
                ->update([
                    'page_type'  => 'product',
                    'meta_keyword' => $product_meta_title,
                    'meta_description' => $product_meta_description
            ]);
        }else{
            $dirtseo                        = new DirectorySeo;
            $dirtseo->module_id             = $product_id;
            $dirtseo->page_type             = 'product';
            $dirtseo->meta_keyword          = $product_meta_title;
            $dirtseo->meta_description      = $product_meta_description;
            $dirtseo->save();
        }
        DB::enableQueryLog();
        /* Currency Data Start */
        ProductPrice::where('product_id', $product_id)->delete();
        $currencydata = Helper::getAllCurrency();   /* get All Currency */

        foreach($currencydata as $currencyrow) {
            $salesprice                     = $request->input('sales_' . $currencyrow->code);
            // Fetching 
				$req_url = 'https://api.exchangerate-api.com/v4/latest/SEK';
				$response_json = file_get_contents($req_url);
				if(false !== $response_json) {
				    try {
					$regular_EUR = $request->input('regular_SEK');
				    $response_object = json_decode($response_json);
				    $base_price = $regular_EUR; 
                    // Define currency rates
                    $currencyRates = [
                        "BGN" => $response_object->rates->BGN ?? 1, // Set default rate to 1 if rate is not available
                        "GBP" => $response_object->rates->GBP ?? 1,
                        "PLN" => $response_object->rates->PLN ?? 1,
                        "DKK" => $response_object->rates->DKK ?? 1,
                        "NOK" => $response_object->rates->NOK ?? 1,
                        "EUR" => $response_object->rates->EUR ?? 1,
                        "SEK" => $response_object->rates->SEK ?? 1,
                        "HRK" => $response_object->rates->HRK ?? 1,
                        "RON" => $response_object->rates->RON ?? 1,
                        "CZK" => $response_object->rates->CZK ?? 1,
                        "HUF" => $response_object->rates->HUF ?? 1,
                    ];
                    // Check if currency code exists in the array, if not, set the rate to 1
                    $rate = $currencyRates[$currencyrow->code] ?? '';
                    $result_price = round(($base_price * $rate), 2);

						$productPriceInsertData                     = new ProductPrice;
                        $productPriceInsertData->currency_id        = $currencyrow->id;
                        $productPriceInsertData->currency_code      = $currencyrow->code;
                        $productPriceInsertData->currency_name      = $currencyrow->name;
                        $productPriceInsertData->currency_base      = $currencyrow->base_currency;
                        $productPriceInsertData->product_id         = $product_id;
                        $productPriceInsertData->sales_price        = ($salesprice) ? $salesprice : 0;
                        $productPriceInsertData->regular_price      = $result_price??0;
                        $productPriceInsertData->save();
				    }
				    catch(Exception $e) {
				    }
				}
            
        }
        /* Currency Data Ends */
        /* Product Category Starts */
        $categories = $request->input('subproductcat');
        ProductCategories::where('product_id', $product_id)->delete();
        if (!empty($categories)) {
            foreach ($categories as $pro => $value) {
                $productcategoryEditData                    = new ProductCategories;
                $productcategoryEditData->product_id        = $product_id;
                $productcategoryEditData->category_id       = $value;
                $productcategoryEditData->save();
            }
        }
        /* Product Category Ends */
        $videofile = $request->file('video');
        if ($videofile) {
            ProductVideo::where('product_id', $product_id)->delete();
            $video_ext                                  = $videofile->getClientOriginalExtension();
            $name2                                      = $videofile->getClientOriginalName();
            $videoName                                  = time() . $name2;
            $videopath                                  = 'files/productvideo/' . $videoName;
            $videofile->move(public_path('files/productvideo/'), $videoName);
            $productvideo_insert                        = new ProductVideo();
            $productvideo_insert->product_id            = $product_id;
            $productvideo_insert->video_link            = $videoName;
            $productvideo_insert->save();
        }
        $imgfile = $request->file('main_image');
        if ($imgfile) {
            ProductImage::where('product_id', $product_id)->where('main_image', "main")->delete();
            $ext                                        = $imgfile->getClientOriginalExtension();
            $name2                                      = $imgfile->getClientOriginalName();
            $imageName2                                 = time() . $name2;
            $imageName                                  = Helper::getSeoUrl($imageName2);

            /*$imagepath                                  = 'files/product/' . $imageName;
            $image_resize                               = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/product/' . $imageName));
            */

            $imagepath = 'images/product/' . $imageName;
                $convertext = "webp";        
                $image_resize = Image::make($imgfile->getRealPath())->stream($convertext,50);        
                Storage::disk('images')->put('product/'.$imageName.'.'.$convertext,$image_resize);

            $productmainimage_insert                    = new ProductImage;
            $productmainimage_insert->product_id        = $product_id;
            $productmainimage_insert->main_image        = "main";
            $productmainimage_insert->name_thumbnail    = $imageName;
            $productmainimage_insert->name_large        = $imageName;
            $productmainimage_insert->extension         = $ext;
            $productmainimage_insert->path              = $imagepath;
            $productmainimage_insert->save();
        }
        $imgfilegal = $request->file('gal_image');
        if ($imgfilegal) {
            foreach ($imgfilegal as $imgrow) {
                $ext                                            = $imgrow->getClientOriginalExtension();
                $name2                                          = $imgrow->getClientOriginalName();
                $imageName2                                     = time() . $name2;
                $imageName                                      = Helper::getSeoUrl($imageName2);
                /*
                $imagepath                                      = 'files/product/' . $imageName;
                $image_resize                                   = Image::make($imgrow->getRealPath());
                $image_resize->save(public_path('files/product/' . $imageName));
                */
                
                $imagepath = 'images/product/' . $imageName;
                $convertext = "webp";        
                $image_resize = Image::make($imgrow->getRealPath())->stream($convertext,50);        
                Storage::disk('images')->put('product/'.$imageName.'.'.$convertext,$image_resize);

                $productmultiimage_insert                       = new ProductImage;
                $productmultiimage_insert->product_id           = $product_id;
                $productmultiimage_insert->main_image           = "galimg";
                $productmultiimage_insert->name_thumbnail       = $imageName;
                $productmultiimage_insert->name_large           = $imageName;
                $productmultiimage_insert->extension            = $ext;
                $productmultiimage_insert->path                 = $imagepath;
                $productmultiimage_insert->save();
            }
        } else {
            $thumb_hidden_img = $request->input('thumb_hidden_img');
            $hiddenarray_thumb_hidden_img = explode(',', $thumb_hidden_img);
            $details = ProductImage::where('main_image', 'galimg')->where('product_id', $product_id)->get();
            if ($details->count() > 0) {
                foreach ($details as $key => $detail) {
                    $detail->name_thumbnail                     = $hiddenarray_thumb_hidden_img[$key];
                    $detail->name_large                         = $hiddenarray_thumb_hidden_img[$key];
                    $detail->path                               = 'images/product/' . $hiddenarray_thumb_hidden_img[$key];
                    $detail->save();
                }
            }
        }
        $attributesId = $request->attr_id;
        $attributesVal = $request->attr_value;
        if (!empty($attributesId) && !empty($attributesVal)) {
            ProductAttributes::whereIn('product_id', [$product_id])->delete();
            $attributesData = array_combine($attributesId, $attributesVal);

            foreach ($attributesData as $key => $val) {
                ProductAttributes::create([
                    'product_id'                =>  $product_id,
                    'attribute_id'              =>  $key,
                    'attribute_value'           =>  $val
                ]);
            }
        }
        $v_attr = $request->v_attr_value;
        if (!empty($v_attr)) {
            ProductAttributesVariation::whereIn('product_id', [$product_id])->delete();
            $attr_value_count = count(array_filter($request->attr_value));
            $v_attr_value_divid = array_chunk($v_attr, $attr_value_count);
            foreach ($v_attr_value_divid as $key => $v_attr_value) {
                $v_attr_str = implode(',', $v_attr_value);
                ProductAttributesVariation::create([
                    'product_id' =>  $product_id,
                    'attribute_value' =>  $v_attr_str,
                    'v_ena' =>  $request->v_ena[$key],
                    'v_stock' =>  $request->v_stock[$key]
                ]);
            }
        }
        return redirect('admin/product-edit/' . $product_id)->with('msg', 'Product has been updated');
    }

    public function destroy($id)
    {
        //Product::where('id', $id)->update(['deleted' => '1']);
        $data = ProductImage::where("product_id", $id)->get("*");
        foreach($data as $rowdata){
            $image_name = $rowdata->name_thumbnail.".webp";
            $image_path = public_path('images/product/'.$image_name);
            if(file_exists($image_path)){
              unlink($image_path);
            }
        }
        Product::where('id', $id)->delete();
        ProductImage::where('product_id', $id)->delete();
        ProductPrice::where('product_id', $id)->delete();
        ProductTranslation::where('product_id', $id)->delete();
        ProductCategories::where('product_id', $id)->delete();
        ProductAttributes::where('product_id', $id)->delete();
        ProductVideo::where('product_id', $id)->delete();
        Wishlist::where('product_id', $id)->delete();
       
        return redirect('admin/product')->with('msg', 'Product deleted successfully');
    }

    public static function get_mainImage($productId)
    {
        $get_mainimageData = DB::table('product_images')->select('*')->where('product_id', $productId)->where('main_image', "main")->get()->toArray();
        return $get_mainimageData;
    }
    public static function get_galleryImage($productId)
    {
        $get_mainimageData = DB::table('product_images')->select('*')->where('product_id', $productId)->where('main_image', "galimg")->get()->toArray();
        return $get_mainimageData;
    }
    public function deleteGalleryImageDelete(Request $request)
    {
        $data = ProductImage::find($request->id);
        $image_name = $data->name_thumbnail.".webp";
        $image_path = public_path('images/product/'.$image_name);
        if(file_exists($image_path)){
          unlink($image_path);
        }
        $data = ProductImage::find($request->id)->delete();
        return response()->json($data);
    }

    public function getTagsByDirectoryId(Request $request)
    {
        $data['directoryTags'] = DirectoryCreateOwnTags::where("directory_id", $request->directory_id)->get(["id", "tag_name"]);
        $data['tags'] = Tags::get(["id", "tags"]);
        return response()->json($data);
    }

    public function changeProductStatus(Request $request)
    {
        $product = Product::find($request->id);
        $product->status = $request->status;
        $product->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function getProductParentSubCategory(Request $request)
    {
        if (!empty($request->cat_id)) {
            $cat = Productcategory::where('parent_id', $request->cat_id)->where('status', 'Active')->orderBy('name', 'ASC')->get();
            $html = "<option></option>";
            foreach ($cat as $s) {
                $html = $html . "<option value='" . $s->id . "'>" . $s->name . "</option>";
            }
            return $html;
        }
    }

    public function getProductTags(Request $request)
    {
        $data['tags'] = DB::table('product_tags')->select('id', 'tags')->whereIn('child_cat_id', $request->category_id)->where('status', 'Active')->orderBy('tags', 'ASC')->get()->toArray();
        return response()->json($data);
    }
    public function getProductAttributeByDirectoryid(Request $request)
    {
        $attributesData = Attribute::where('directory_id', $request->directory_id)->get();
        return response()->json($attributesData);
    }

    public function deleteMtltipleProducts(Request $request)
    {
        $ids = $request->ids;
        // Product::whereIn('id', explode(",", $ids))->update(['deleted' => '1']);
        Product::whereIn('id', explode(",", $ids))->delete();
        ProductImage::whereIn('product_id', explode(",", $ids))->delete();
        ProductPrice::whereIn('product_id', explode(",", $ids))->delete();
        ProductTranslation::whereIn('product_id', explode(",", $ids))->delete();
        ProductCategories::whereIn('product_id', explode(",", $ids))->delete();
        ProductAttributes::whereIn('product_id', explode(",", $ids))->delete();
        ProductVideo::whereIn('product_id', explode(",", $ids))->delete();
        
        return response()->json(['success'=>'Products deleted successfully.']);
    }
}
