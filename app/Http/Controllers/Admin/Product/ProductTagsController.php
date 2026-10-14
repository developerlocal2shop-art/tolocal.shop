<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use App\Models\ProductTags;
use App\Models\Productcategory;
use App\Models\tagProductTranslations;
use Stichoza\GoogleTranslate\GoogleTranslate;

class ProductTagsController extends Controller
{

    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : "";
            $cat_id = $request->input('category') ? $request->input('category') : "";

            if ($searchQuery != "" && $cat_id != "") {
                $data['productsearchDetails'] = DB::table('product_tags')
                    ->select('*')
                    ->where('tags', 'like', '%' . $searchQuery . '%')
                    ->Where('category_id',  $cat_id);

                $tags =    $data['productsearchDetails']->orderBy('id', 'desc')->paginate(20);
                $product_category    = Helper::getProductCatgoeryData();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.producttags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $product_category, 'query' => $query]);
            } else if ($cat_id != "") {
                $data['productsearchDetails'] = DB::table('product_tags')
                    ->select('*')
                    ->where('category_id',  $cat_id);

                $tags =    $data['productsearchDetails']->orderBy('id', 'desc')->paginate(20);
                $product_category    = Helper::getProductCatgoeryData();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.producttags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $product_category, 'query' => $query]);
            } else {
                $data['productsearchDetails'] = DB::table('product_tags')
                    ->select('*')
                    ->where('tags', 'like', '%' . $searchQuery . '%');

                $tags =    $data['productsearchDetails']->orderBy('id', 'desc')->paginate(20);
                $product_category    = Helper::getProductCatgoeryData();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.producttags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $product_category, 'query' => $query]);
            }
        } else {
            $tags = ProductTags::query()->orderBy('id', 'desc')->paginate(20);
            $product_category    = Helper::getProductCatgoeryData();
            $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
            $searchCatId = isset($cat_id) ? $cat_id : '';
            return view('admin.producttags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $product_category]);
        }
    }

    public function create()
    {
        $data['parent_category']    = Helper::getAllParentProductCategory();      /* get All Product Parent Category */
        return view('admin.producttags.create', ['data' => $data]);
    }

    
    public function submit(Request $request)
    {
        $this->validate($request, [
            'category'     => 'required',
            'subcategory'  => 'required',
        ]);
        $category_id                = $request->input('category');
        $tagsName                   = explode(',', $request->input('tags_name'));

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();
        
        foreach ($tagsName as $tag) {
            $existTags = ProductTags::where('category_id', $category_id)->where('tags', $tag)->get();
            if (count($existTags) < 1) {

                $tags_insert = new ProductTags;
                $tags_insert->category_id           = $category_id;
                $tags_insert->child_cat_id          = $request->input('subcategory');
                $tags_insert->tags                  = trim($tag);
                $tags_insert->created_by            = $request->input('createdby');
                $tags_insert->save();
                $tag_id = $tags_insert->id;

                foreach ($languages as $lang) {

                    $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($tag);
                    $title_name                 = isset($title_name) ? $title_name : $tag;
                    
                    $tagtrans                  = new tagProductTranslations;
                    $tagtrans->tag_id         = $tag_id;
                    $tagtrans->category_id     = $category_id;
                    $tagtrans->language_id     = $lang->id;
                    $tagtrans->title           = isset($title_name) ? $title_name : $tag;
                    $tagtrans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $tag);
                    $tagtrans->save();
                }

            }
        }

        return redirect('admin/product-tags')->with('msg', 'Product Tag Added Successfully!');
    }



    public function edit($id = 0)
    {
        $data['tagseditdata']        = ProductTags::findOrFail($id);
        $data['get_parent_category']    = Helper::getAllParentProductCategory();      /* get Parent Category */
        $data['get_child_category']    = Productcategory::where("parent_id", $data['tagseditdata']->category_id)
            ->where('status', 'Active')->where('deleted', '0')->get(["id", "name"]);/* get Child Category */

            $data['languagesData']      = Helper::getAllLanguages();             /* get languages */

            $data['tagTraslationData'] = DB::table('product_tags as b')
            ->select('*')
            ->join('tag_product_translations  as bt', 'b.id', '=', 'bt.tag_id')
            ->where('bt.tag_id',  $id)->get();
        return view('admin.producttags.edit', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category' => 'required',
        ]);
        $existTags = ProductTags::where('category_id', $request->input('category'))->where('tags', $request->input('tags_name'))->get();
        if (count($existTags) < 1) {
            $tagedit =  ProductTags::find($id);
            $tagedit->category_id               = $request->input('category');
            $tagedit->child_cat_id              = $request->input('subcategory');
            $tagedit->tags                      = trim($request->input('tags_name_en'));
            $tagedit->save();
        }

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        foreach ($languages as $lang) {
            
            $title_name                 = $request->input('tags_name_' . $lang->code);
            $description_code           = $request->input('content_' . $lang->code);
            //$title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
            //$description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($description_en);
            $Translation_id             = $request->input('tranlation_' . $lang->code);
            $trans = tagProductTranslations::find($Translation_id);
            $trans->title = $title_name ?? "";
            $trans->save();
        }
        return redirect('admin/product-tags')->with('msg', 'Tags has been updated');
    }

    public function changeProductTagStatus(Request $request)
    {
        $TagStatus = ProductTags::find($request->user_id);
        $TagStatus->status = $request->status;
        $TagStatus->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        ProductTags::where('id', $id)->delete();
        tagProductTranslations::where('tag_id', $id)->delete();
        return redirect('admin/product-tags')->with('msg', 'Tags deleted successfully');
    }

    public function getParentProductSubCategory(Request $request)
    {
        $data['subcat'] = Productcategory::where("parent_id", $request->category_id)
            ->where('status', 'Active')->where('deleted', '0')->orderBy('name', 'ASC')->get(["id", "name"]);;
        return response()->json($data);
    }
}
