<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Productcategory;
use App\Models\Productcategorytranslation;
use App\Models\ProductcategoryUploads;
use App\Helpers\Helper;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ProductCategoryController extends Controller
{

    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $cat_id = $request->input('category') ? $request->input('category') : "";
            $data['searchQuery'] = $request->input('searchtext');
            if ($searchQuery != "" && $cat_id != "") {
                $cat = Productcategory::where('deleted', '0')
                    ->where('name', 'like', '%' . $searchQuery . '%')
                    ->Where('parent_id',  $cat_id);
                $product_categories = $cat->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllParentProductCategory();      /* get Category */
                $searchQuery = $request->input('searchtext');
                $data = isset($searchQuery) ? $searchQuery : "";
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.productcategory.index', ['product_categories' => $product_categories, 'data' => $data, 'catid' => $searchCatId, 'parent_category' => $parent_category, 'query' => $query]);
            } else if ($cat_id != "") {
                $cat = Productcategory::where('deleted', '0')
                    ->Where('parent_id',  $cat_id);
                $product_categories = $cat->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllParentProductCategory();      /* get Category */
                $searchQuery = $request->input('searchtext');
                $data = isset($searchQuery) ? $searchQuery : "";
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.productcategory.index', ['product_categories' => $product_categories, 'data' => $data, 'catid' => $searchCatId, 'parent_category' => $parent_category, 'query' => $query]);
            } else {
                $cat = Productcategory::where('deleted', '0')
                    ->where('name', 'like', '%' . $searchQuery . '%');
                $product_categories = $cat->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllParentProductCategory();      /* get Category */
                $searchQuery = $request->input('searchtext');
                $data = isset($searchQuery) ? $searchQuery : "";
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.productcategory.index', ['product_categories' => $product_categories, 'data' => $data, 'catid' => $searchCatId, 'parent_category' => $parent_category, 'query' => $query]);
            }
        } else {

            $product_categories = Productcategory::with('parent')->where('deleted', '0')
            ->orderBy('parent_id')
            ->orderBy('sort_value')
            ->paginate(20);

            $parent_category    = Helper::getAllParentProductCategory();      /* get Category */
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
            $data = isset($searchQuery) ? $searchQuery : "";
            $searchCatId = isset($cat_id) ? $cat_id : '';
            return view('admin.productcategory.index', ['product_categories' => $product_categories, 'data' => $data, 'catid' => $searchCatId, 'parent_category' => $parent_category]);
        }
    }

    public function create()
    {
        $data['parent_category']    = Helper::getAllParentProductCategory();      /* get Category */
        $data['languagesData']      = Helper::getAllLanguages();           /* get languages */
        return view('admin.productcategory.create', ['data' => $data]);
    }

    public function submitProductCategory(Request $request)
    {
        $name_en = $request->input('name_en');
        $description_en = $request->input('description_en');
        $seo_en = $request->input('seo_en');
        $this->validate($request, [
            'name_en'           => 'required',
            'image'             => 'mimes:jpeg,png,jpg,gif,svg|required',
        ]);

        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/productcategory/' . $imageName;
            $imgfile->move(public_path('files/productcategory/'), $imageName);
            $cate_image_insert                   = new ProductcategoryUploads;
            $cate_image_insert->name_thumbnail   = $imageName;
            $cate_image_insert->name_large       = $imageName;
            $cate_image_insert->extension        = $ext;
            $cate_image_insert->path             = $imagepath;
            $cate_image_insert->created_by       = $request->input('createdby');
            $cate_image_insert->save();
            $uploads_id = $cate_image_insert->id;
        }
        $product_cat = new Productcategory;
        $product_cat->parent_id = $request->input('pcategory');
        $product_cat->upload_id = $uploads_id;
        $product_cat->name = (isset($name_en)) ? $name_en : "";
        $product_cat->slug = Helper::getSeoUrl((isset($name_en)) ? $name_en : "");
        $product_cat->feature = $request->input('cat_feature');
        $product_cat->description = isset($description_en) ? $description_en : "";
        $product_cat->icon_class = $request->input('icon_class');
        $product_cat->sort_value = $request->input('sort_value') == null ? 0 : $request->input('sort_value');
        $product_cat->save();
        $product_category_id = $product_cat->id;
        $languages = Helper::getAllLanguages();
        foreach ($languages as $lang) {
            $title_name = $request->input('name_' . $lang->code);
            $seo_val = $request->input('seo_' . $lang->code);
            $description_code = $request->input('description_' . $lang->code);
            $product_cattrans = new Productcategorytranslation;
            $product_cattrans->product_category_id  = $product_category_id;
            $product_cattrans->language_id          = $lang->id;
            $product_cattrans->title                = isset($title_name) ? $title_name : $name_en;
            $product_cattrans->description          = isset($description_code) ? $description_code : $description_en;
            $product_cattrans->seo_url              = isset($seo_val) ? $seo_val : $seo_en;
            $product_cattrans->save();
        }
        return redirect('admin/productcategories')->with('msg', 'Product Category Added Successfully!');
    }

    public function editProductCategory($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        $data['singleProductCategoryData'] = Productcategory::findOrFail($id);
        $data['parent_category']    = Helper::getAllParentProductCategory();      /* get Category */
        $data['languagesData']      = Helper::getAllLanguages();             /* get languages */
        $data['languageAndTraslationData'] = DB::table('product_category as pro_cat')
            ->select('*')
            ->join('product_category_translations as pro_cat_tr', 'pro_cat.id', '=', 'pro_cat_tr.product_category_id')
            ->where('pro_cat_tr.product_category_id',  $id)->get();
        return view('admin.productcategory.edit', ['data' => $data]);
    }

    public function updateProductCategory(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name_en'  => 'required',
            'image'        => 'mimes:jpeg,jpg,png,gif,svg',
        ]);
        $uploads_id = 0;
        $imgfile = $request->file('image');
        if ($imgfile) {
            $uplaodId = $request->input('uplaodId');
            ProductcategoryUploads::where('id', $uplaodId)->delete();
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/productcategory/' . $imageName;
            $imgfile->move(public_path('files/productcategory/'), $imageName);
            $pro_cate_image_edit                   = new ProductcategoryUploads;
            $pro_cate_image_edit->name_thumbnail   = $imageName;
            $pro_cate_image_edit->name_large       = $imageName;
            $pro_cate_image_edit->extension        = $ext;
            $pro_cate_image_edit->path             = $imagepath;
            $pro_cate_image_edit->save();
            $uploads_id = $pro_cate_image_edit->id;
        }
        if ($uploads_id != 0)
            $uploads_id = $uploads_id;
        else
            $uploads_id = $request->input('uplaodId');
        $name_en = $request->input('name_en');
        $description_en = $request->input('description_en');
        Productcategory::where('id', $id)
            ->update([
                'parent_id'             => $request->input('pcategory'),
                'upload_id'             => $uploads_id,
                'name'                  => (isset($name_en)) ? $name_en : "",
                'slug'                  => Helper::getSeoUrl((isset($name_en)) ? $name_en : ""),
                'feature'               => $request->input('cat_feature'),
                'description'           => isset($description_en) ? $description_en : "",
                'icon_class'            => $request->input('icon_class'),
                'sort_value'            => $request->input('sort_value'),
            ]);

        $is_not_change_en_language = Productcategorytranslation::where('title',$name_en)->where('description', $description_en)->where('language_id',1)->where('product_category_id',$id)->exists();

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();

        if($is_not_change_en_language){
            
            foreach ($languages as $lang) {

                $title_name                 = $request->input('name_'.$lang->code);
                $description_code           = $request->input('description_'.$lang->code);
                $categoryTranslation_id     = $request->input('langtranlation_'.$lang->code);
                
                $cattran = Productcategorytranslation::find($categoryTranslation_id);
                if (!$cattran) {
                    $cattran = new Productcategorytranslation();
                    $cattran->product_category_id = $id; // make sure this exists
                    $cattran->language_id = $lang->id;    // adjust based on your column
                }
                $cattran->title = $title_name??$name_en;
                $cattran->description = $description_code??$description_en;
                $cattran->seo_url = Helper::getSeoUrl($name_en);
                $cattran->save();
            }
        }else{
            Productcategorytranslation::where('product_category_id', $id)->delete();
            foreach ($languages as $lang) {
                $title_name                     = $tr->setSource('en')->setTarget($lang->code)->translate($name_en??'Title');
                $description_code               = $tr->setSource('en')->setTarget($lang->code)->translate($description_en??'Description');
                    
                $cattrans                      = new Productcategorytranslation;
                $cattrans->product_category_id = $id;
                $cattrans->language_id         = $lang->id;
                $cattrans->seo_url             = Helper::getSeoUrl($name_en);
                $cattrans->title               = isset($title_name) ? $title_name : $name_en;
                $cattrans->description         = isset($description_code) ? $description_code : $description_en;
                $cattrans->save();
            }
        }

        // $languages = Helper::getAllLanguages();
        // foreach ($languages as $lang) {
        //     $title_name                 = $request->input('name_' . $lang->code);
        //     $description_code           = $request->input('description_' . $lang->code);
        //     $productcategoryTranslation_id     = $request->input('langtranlation_' . $lang->code);
        //     $productcattrans =  Productcategorytranslation::find($productcategoryTranslation_id);
        //     $productcattrans->title        = isset($title_name) ? $title_name : "";
        //     $productcattrans->description  = isset($description_code) ? $description_code : "";
        //     $productcattrans->save();
        // }

        return redirect('admin/productcategory-edit/' . $id)->with('msg', 'Product category has been updated');
    }

    public function destroy($id)
    {
        Productcategory::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/productcategories')->with('msg', 'Product Category deleted successfully');
    }

    public function checkProductCategorySeo(Request $request)
    {
        $data = array();
        $data['seoChangeData'] = Helper::getSeoUrl($request->catname);
        if ($request->directory == "directory") {
            $categoryTypeDetails = DB::table('directory_basic_translation')->select('*')->where('seo_url', $data['seoChangeData'])->orWhere('title', $request->catname)->orderBy('id', 'desc')->get()->toArray();
        }
        if ($request->category == "cate") {
            $categoryTypeDetails = DB::table('product_category_translations')->select('*')->where('seo_url', $data['seoChangeData'])->orWhere('title', $request->catname)->orderBy('id', 'desc')->get()->toArray();
        }
        $data['categoryTypeDetails'] = "";
        if (!empty($categoryTypeDetails)) {
            $datainmatch = $categoryTypeDetails[0]->seo_url;
            $checkdata = explode("-", $datainmatch);
            $sliced = array_slice($checkdata, 0, -1);
            $string = implode("-", $sliced);
            $checkdata = end($checkdata);
            $num = 0;
            if (is_numeric($checkdata)) {
                $checkdata = $string . "-" . ++$checkdata;
            } else {
                $checkdata = $data['seoChangeData'] . "-" . ++$num;
            }
            $data['categoryTypeDetails'] = $checkdata;
        }
        return response()->json($data);
    }
    public function producttree()
    {
        $data['product_parent_category']    = Helper::getAllParentProductCategory();      /* get All product Category */
        return view('admin.productcategory.tree', ['data' => $data]);
    }
}
