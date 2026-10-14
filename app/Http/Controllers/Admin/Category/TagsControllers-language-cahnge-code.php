<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use DB;
use App\Models\Tags;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\tagTranslation;
use Stichoza\GoogleTranslate\GoogleTranslate;

class TagsControllers_bkpf extends Controller
{
    public function index(Request $request)
    {

        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : "";
            $cat_id = $request->input('category') ? $request->input('category') : "";
            $sort = $request->input('sort') ? $request->input('sort') : "";

            if ($sort != "") {
                if ($sort == "name") {
                    $tags = Tags::query()->orderBy('tags', 'asc')->paginate(20);

                    $parent_category    = Helper::getAllCategoryTypeListing();
                    $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                    $searchCatId = isset($cat_id) ? $cat_id : '';
                    return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
                } else {
                    $tags = Tags::query()->orderBy('id', 'desc')->paginate(20);

                    $parent_category    = Helper::getAllCategoryTypeListing();
                    $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                    $searchCatId = isset($cat_id) ? $cat_id : '';
                    return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category]);
                }
            }

            if ($searchQuery != "" && $cat_id != "") {
                $data['directorysearchDetails'] = DB::table('category_tags')
                    ->select('*')
                    ->where('tags', 'like', '%' . $searchQuery . '%')
                    ->Where('category_id',  $cat_id);

                $tags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllCategoryTypeListing();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
            } else if ($cat_id != "") {
                $data['directorysearchDetails'] = DB::table('category_tags')
                    ->select('*')
                    ->where('category_id',  $cat_id);

                $tags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllCategoryTypeListing();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
            } else {
                $data['directorysearchDetails'] = DB::table('category_tags')
                    ->select('*')
                    ->where('tags', 'like', '%' . $searchQuery . '%');

                $tags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllCategoryTypeListing();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
            }
        } else {
            $tags = Tags::query()->orderBy('id', 'desc')->paginate(20);

            $parent_category    = Helper::getAllCategoryTypeListing();
            $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
            $searchCatId = isset($cat_id) ? $cat_id : '';
            return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category]);
        }
    }
    public function create()
    {
        $data['parent_category']    = Helper::getAllParentCategory();      /* get All Category whose type is listing */
        return view('admin.tags.create', ['data' => $data]);
    }

    public function submit(Request $request)
    {

        $this->validate($request, [
            'category'     => 'required',
            'subcategory'  => 'required',
        ]);

        $languages = Helper::getAllLanguages();
        $tr = new GoogleTranslate();

        $category_id = $request->input('category');
        $tagsName = explode(',', $request->input('tags_name'));
        foreach ($tagsName as $tag) {

            $existTags = Tags::where('category_id', $category_id)->where('tags', $tag)->get();
            if (count($existTags) < 1) {

                $tags_insert = new Tags;
                $tags_insert->category_id           = $category_id;
                $tags_insert->child_cat_id          = $request->input('subcategory');
                $tags_insert->tags                  = trim($tag);
                $tags_insert->created_by            = $request->input('createdby');
                $tags_insert->save();
                $tag_id = $tags_insert->id;

                foreach ($languages as $lang) {

                    $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($tag);
                    $description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($tag);
                    $title_name                 = isset($title_name) ? $title_name : $tag;

                    $tagtrans                  = new tagTranslation;
                    $tagtrans->tag_id         = $tag_id;
                    $tagtrans->category_id     = $request->input('category');
                    $tagtrans->language_id     = $lang->id;
                    $tagtrans->title           = isset($title_name) ? $title_name : $tag;
                    $tagtrans->description     = isset($description_code) ? $description_code : $tag;
                    $tagtrans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $tag);
                    $tagtrans->save();
                }

            }
        }
        return redirect('admin/tags')->with('msg', 'Tags Added Successfully!');
    }


    public function edit($id = 0)
    {
        $data['tagseditdata']        = Tags::findOrFail($id);
        $data['get_parent_category']    = Helper::getAllParentCategory();      /* get Parent Category */
        $data['get_child_category']    = Category::where("parent_id", $data['tagseditdata']->category_id)
            ->where('status', 'Active')->where('deleted', '0')->get(["id", "name"]);/* get Child Category */

        $data['languagesData']      = Helper::getAllLanguages();             /* get languages */

        $data['tagTraslationData'] = DB::table('category_tags as b')
            ->select('*')
            ->join('tag_translations as bt', 'b.id', '=', 'bt.tag_id')
            ->where('bt.tag_id',  $id)->get();

            return view('admin.tags.edit', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category' => 'required',
        ]);

        $name_en = $request->input('tags_name');



        $existTags = Tags::where('category_id', $request->input('category'))->where('tags', $request->input('tags_name'))->get();
        if (count($existTags) < 1) {
            $tagedit =  Tags::find($id);
            $tagedit->category_id               = $request->input('category');
            $tagedit->child_cat_id              = $request->input('subcategory');
            $tagedit->tags                      = trim($request->input('tags_name'));
            $tagedit->save();
        }
 //DB::enableQueryLog();
        $languages = Helper::getAllLanguages();
        //dd($languages);
        $tr = new GoogleTranslate(); // Translates to 'en' from auto-detected language by default
        foreach ($languages as $lang) {

            // $title_name                 = $request->input('tags_name_' . $lang->code);
            // $description_code           = $request->input('content_' . $lang->code);
            $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);

            $Translation_id             = $request->input('tranlation_' . $lang->code);
            $tagsId                     = $request->input('tags_id');
           // dd();

            $tagtrans                   = new tagTranslation;
            $tagtrans->tag_id           = $tagsId;
            $tagtrans->category_id      = $request->input('categoryid');
            $tagtrans->language_id      = $lang->id;
            $tagtrans->title            = isset($title_name) ? $title_name : $name_en;
            $tagtrans->description      = isset($title_name) ? $title_name : $name_en;
            $tagtrans->seo_url          = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
            $tagtrans->save();

            // $trans = tagTranslation::find($Translation_id);
            // $trans->title = $title_name ?? "";
            // $trans->description = "";
            // $trans->save();
        }
            //$queries = DB::getQueryLog();
                //print_r($queries);
        return redirect('admin/tags')->with('msg', 'Tags has been updated');
    }

    public function changeTagStatus(Request $request)
    {
        $TagStatus = Tags::find($request->user_id);
        $TagStatus->status = $request->status;
        $TagStatus->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        Tags::where('id', $id)->delete();
        return redirect('admin/tags')->with('msg', 'Tags deleted successfully');
    }

    public function getSubcategory(Request $request)
    {
        $data['subcat'] = Category::where("parent_id", $request->category_id)
            ->where('status', 'Active')->where('deleted', '0')->orderBy('name', 'ASC')->get(["id", "name"]);
        return response()->json($data);
    }
}
