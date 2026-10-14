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

class TagsControllers extends Controller
{

    // public function index(Request $request)
    // {

    //     if ($request->input('search')) {
    //         $query = $request->all();
    //         $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : "";
    //         $cat_id = $request->input('category') ? $request->input('category') : "";
    //         $sort = $request->input('sort') ? $request->input('sort') : "";

    //         if ($sort != "") {
    //             if ($sort == "name") {
    //                 $tags = Tags::query()->orderBy('tags', 'asc')->paginate(20);

    //                 $parent_category    = Helper::getAllCategoryTypeListing();
    //                 $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
    //                 $searchCatId = isset($cat_id) ? $cat_id : '';
    //                 return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
    //             } else {
    //                 $tags = Tags::query()->orderBy('id', 'desc')->paginate(20);

    //                 $parent_category    = Helper::getAllCategoryTypeListing();
    //                 $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
    //                 $searchCatId = isset($cat_id) ? $cat_id : '';
    //                 return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category]);
    //             }
    //         }

    //         if ($searchQuery != "" && $cat_id != "") {
    //             $data['directorysearchDetails'] = DB::table('category_tags')
    //                 ->select('*')
    //                 ->where('tags', 'like', '%' . $searchQuery . '%')
    //                 ->Where('category_id',  $cat_id);

    //             $tags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
    //             $parent_category    = Helper::getAllCategoryTypeListing();
    //             $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
    //             $searchCatId = isset($cat_id) ? $cat_id : '';
    //             return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
    //         } else if ($cat_id != "") {
    //             $data['directorysearchDetails'] = DB::table('category_tags')
    //                 ->select('*')
    //                 ->where('category_id',  $cat_id);

    //             $tags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
    //             $parent_category    = Helper::getAllCategoryTypeListing();
    //             $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
    //             $searchCatId = isset($cat_id) ? $cat_id : '';
    //             return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
    //         } else {
    //             $data['directorysearchDetails'] = DB::table('category_tags')
    //                 ->select('*')
    //                 ->where('tags', 'like', '%' . $searchQuery . '%');

    //             $tags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
    //             $parent_category    = Helper::getAllCategoryTypeListing();
    //             $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
    //             $searchCatId = isset($cat_id) ? $cat_id : '';
    //             return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
    //         }
    //     } else {
    //         $tags = Tags::query()->orderBy('id', 'desc')->paginate(20);

    //         $parent_category    = Helper::getAllCategoryTypeListing();
    //         $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
    //         $searchCatId = isset($cat_id) ? $cat_id : '';
    //         return view('admin.tags.index', ['tags' => $tags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category]);
    //     }
    // }

    public function index(Request $request)
    {
        $searchQuery = trim($request->searchtext ?? '');
        $categoryId  = $request->category;
        $sort        = $request->sort;

        $prefix = DB::getTablePrefix();

        $query = Tags::query()
            ->leftJoin('category as c', 'c.id', '=', 'category_tags.category_id')
            ->leftJoin('category as p', 'p.id', '=', 'c.parent_id')
            ->select(
                'category_tags.*',
                DB::raw('COALESCE(' . $prefix . 'p.id, ' . $prefix . 'c.id) as group_category_id'),
                DB::raw('COALESCE(' . $prefix . 'p.name, ' . $prefix . 'c.name) as group_category_name')
            )
            ->when($searchQuery, function ($q) use ($searchQuery) {
                $q->where('category_tags.tags', 'like', "%{$searchQuery}%");
            })
            ->when($categoryId, function ($q) use ($categoryId) {
                $q->where(function ($q2) use ($categoryId) {
                    $q2->where('category_tags.category_id', $categoryId)
                       ->orWhere('c.parent_id', $categoryId);
                });
            });

        $query->orderBy(DB::raw('COALESCE(' . $prefix . 'p.id, ' . $prefix . 'c.id)'));

        if ($sort == 'name') {
            $query->orderBy('category_tags.tags');
        } else {
            $query->orderBy('category_tags.id','desc')
                ->orderBy('category_tags.tags');
        }

        $tags = $query->paginate(40)->appends($request->query());

        return view('admin.tags.index', [
            'tags'        => $tags,
            'searchtxt'   => $searchQuery,
            'catid'       => $categoryId,
            'allCategory' => Helper::getAllCategoryTypeListing(),
            'query'       => $request->all(),
        ]);
    }

    public function create()
    {
        $data['parent_category']    = Helper::getAllParentCategory();      /* get All Category whose type is listing */
        return view('admin.tags.create', ['data' => $data]);
    }

    public function submit(Request $request)
    {
        $request->validate([
            'category'    => 'required',
            'subcategory' => 'required',
        ]);

        $languages = Helper::getAllLanguages();
        $translator = new GoogleTranslate();
        $translator->setSource('en');

        $categoryId = $request->category;
        $subcategoryId = $request->subcategory;
        $createdBy = $request->createdby;

        // Remove empty values and duplicate tags from input
        $tags = array_unique(array_filter(array_map('trim', explode(',', $request->tags_name))));

        $existingTags = [];
        $addedTags = [];

        foreach ($tags as $tag) {

            $exists = Tags::where('category_id', $categoryId)->where('tags', $tag)->exists();

            if ($exists) {
                $existingTags[] = $tag;
                continue;
            }
            // Save Tag
            $newTag = Tags::create([
                'category_id'  => $categoryId,
                'child_cat_id' => $subcategoryId,
                'tags'         => $tag,
                'created_by'   => $createdBy,
            ]);

            // Save Translations
            foreach ($languages as $lang) {

                try {
                    $title = $translator->setTarget($lang->code)->translate($tag);
                } catch (\Exception $e) {
                    $title = $tag;
                }

                tagTranslation::create([
                    'tag_id'       => $newTag->id,
                    'category_id'  => $categoryId,
                    'language_id'  => $lang->id,
                    'title'        => $title,
                    'description'  => $title,
                    'seo_url'      => Helper::getSeoUrl($title),
                ]);
            }

            $addedTags[] = $tag;
        }

        // Flash message
        if (!empty($addedTags) && !empty($existingTags)) {
            return redirect('admin/tags')->with(
                'msg',
                'Tags added successfully. Existing tags skipped: ' . implode(', ', $existingTags)
            );
        }

        if (!empty($existingTags)) {
            return redirect('admin/tags')->with(
                'warning',
                'These tags already exist: ' . implode(', ', $existingTags)
            );
        }

        return redirect('admin/tags')->with(
            'msg',
            'Tags added successfully.'
        );
    }

    // public function submit(Request $request)
    // {
    //     //dd($request->all());
    //     $this->validate($request, [
    //         'category'     => 'required',
    //         'subcategory'  => 'required',
    //     ]);

    //     $languages = Helper::getAllLanguages();
    //     $tr = new GoogleTranslate();

    //     $category_id = $request->input('category');
    //     $tagsName = explode(',', $request->input('tags_name'));
    //     foreach ($tagsName as $tag) {

    //         $existTags = Tags::where('category_id', $category_id)->where('tags', $tag)->get();
    //         if (count($existTags) < 1) {
    //             $tags_insert = new Tags;
    //             $tags_insert->category_id           = $category_id;
    //             $tags_insert->child_cat_id          = $request->input('subcategory');
    //             $tags_insert->tags                  = trim($tag);
    //             $tags_insert->created_by            = $request->input('createdby');
    //             $tags_insert->save();
    //             $tag_id = $tags_insert->id;

    //             foreach ($languages as $lang) {

    //                 $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($tag);
    //                 $description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($tag);
    //                 $title_name                 = isset($title_name) ? $title_name : $tag;

    //                 $tagtrans                  = new tagTranslation;
    //                 $tagtrans->tag_id         = $tag_id;
    //                 $tagtrans->category_id     = $request->input('category');
    //                 $tagtrans->language_id     = $lang->id;
    //                 $tagtrans->title           = isset($title_name) ? $title_name : $tag;
    //                 $tagtrans->description     = isset($description_code) ? $description_code : $tag;
    //                 $tagtrans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $tag);
    //                 $tagtrans->save();
    //             }
    //         }
    //     }
    //     return redirect('admin/tags')->with('msg', 'Tags Added Successfully!');
    // }


    public function edit($id = 0)
    {
        $data['tagseditdata']        = Tags::findOrFail($id);
        $data['get_parent_category']    = Helper::getAllParentCategory();      /* get Parent Category */
        $data['get_child_category']    = Category::where("parent_id", $data['tagseditdata']->category_id)
            ->where('status', 'Active')->where('deleted', '0')->get(["id", "name"]);/* get Child Category */

        $data['languagesData']      = Helper::getAllLanguages();             /* get languages */

        $data['tagTraslationData'] = DB::table('category_tags as b')
            //->groupBy('tags')
            ->join('tag_translations as bt', 'b.id', '=', 'bt.tag_id')
            ->where('bt.tag_id',  $id)->get();

            return view('admin.tags.edit', ['data' => $data]);
    }

    // public function update(Request $request, $id)
    // {

    //     $this->validate($request, [
    //         'category' => 'required',
    //     ]);

    //     $name_en = $request->input('tags_name_en');
    //     $description_en = $request->input('content_en');

    //     $existTags = Tags::where('category_id', $request->input('category'))->where('tags', $request->input('tags_name'))->get();
    //     if (count($existTags) < 1) {
    //         $tagedit =  Tags::find($id);
    //         $tagedit->category_id               = $request->input('category');
    //         $tagedit->child_cat_id              = $request->input('subcategory');
    //         $tagedit->tags                      = trim($request->input('tags_name_en'));
    //         $tagedit->save();
    //     }

    //     $languages = Helper::getAllLanguages();
    //     $tr = new GoogleTranslate();
    //         $tranlation_en   = $request->input('tranlation_en');

    //         if($tranlation_en == 0){

    //             tagTranslation::where('tag_id', $id)->delete();

    //             foreach ($languages as $lang) {
    //                 $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
    //                 $description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($description_en);

    //                 $tagtrans                  = new tagTranslation;
    //                 $tagtrans->tag_id          = $id;
    //                 $tagtrans->category_id     = $request->input('category');
    //                 $tagtrans->language_id     = $lang->id;
    //                 $tagtrans->title           = isset($title_name) ? $title_name : $name_en;
    //                 $tagtrans->description     = isset($description_code) ? $description_code : $description_en;
    //                 $tagtrans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
    //                 $tagtrans->save();

    //             }
    //         }else{

    //         //dd($request->all());

    //          $is_not_change_en_language = tagTranslation::where('title',$name_en)->where('language_id',1)->where('tag_id',$id)->exists();
    //             if($is_not_change_en_language){
    //                 foreach ($languages as $lang) {
    //                     $title_name                 = $request->input('tags_name_' . $lang->code);
    //                     $description_code           = $request->input('tags_name_' . $lang->code);
    //                     $Translation_id             = $request->input('tranlation_' . $lang->code);

    //                     $trans = tagTranslation::find($Translation_id);
    //                     $trans->title = $title_name ?? "";
    //                     $trans->description = $description_code ?? "";
    //                     $trans->save();
    //                 }
    //             }else{

    //                 tagTranslation::where('tag_id', $id)->delete();

    //                 foreach ($languages as $lang) {
    //                     $title_name                 = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);
    //                     $description_code           = $tr->setSource('en')->setTarget($lang->code)->translate($name_en);

    //                     $tagtrans                  = new tagTranslation;
    //                     $tagtrans->tag_id          = $id;
    //                     $tagtrans->category_id     = $request->input('category');
    //                     $tagtrans->language_id     = $lang->id;
    //                     $tagtrans->title           = isset($title_name) ? $title_name : $name_en;
    //                     $tagtrans->description     = isset($description_code) ? $description_code : $name_en;
    //                     $tagtrans->seo_url         = Helper::getSeoUrl(isset($title_name) ? $title_name : $name_en);
    //                     $tagtrans->save();
    //                 }
    //             }
    //         }

    //     return redirect('admin/tags')->with('msg', 'Tags has been updated');
    // }

    public function update(Request $request, $id)
    {
        $request->validate([
            'category' => 'required',
        ]);

        $tag = Tags::findOrFail($id);

        $categoryId    = $request->category;
        $subcategoryId = $request->subcategory;
        $nameEn        = trim($request->tags_name_en);
        $descriptionEn = $request->content_en ?? $nameEn;

        // Check duplicate except current tag
        $exists = Tags::where('category_id', $categoryId)
            ->where('tags', $nameEn)
            ->where('id', '!=', $id)
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withInput()
                ->with('warning', 'Tag "' . $nameEn . '" already exists.');
        }

        // Update Tag
        $tag->update([
            'category_id' => $categoryId,
            'child_cat_id' => $subcategoryId,
            'tags' => $nameEn,
        ]);

        $languages  = Helper::getAllLanguages();
        $translator = new GoogleTranslate();
        $translator->setSource('en');

        if ($request->tranlation_en == 0) {
            tagTranslation::where('tag_id', $id)->delete();

            foreach ($languages as $lang) {

                try {
                    $title = $translator->setTarget($lang->code)->translate($nameEn);
                    $description = $translator->setTarget($lang->code)->translate($descriptionEn);
                } catch (\Exception $e) {
                    $title = $nameEn;
                    $description = $descriptionEn;
                }

                tagTranslation::create([
                    'tag_id'       => $id,
                    'category_id'  => $categoryId,
                    'language_id'  => $lang->id,
                    'title'        => $title,
                    'description'  => $description,
                    'seo_url'      => Helper::getSeoUrl($title),
                ]);
            }

        }else {

            $englishChanged = !tagTranslation::where('tag_id', $id)->where('language_id', 1)->where('title', $nameEn)->exists();

            if ($englishChanged) {
                tagTranslation::where('tag_id', $id)->delete();
                foreach ($languages as $lang) {
                    try {
                        $title = $translator->setTarget($lang->code)->translate($nameEn);
                        $description = $translator->setTarget($lang->code)->translate($descriptionEn);
                    } catch (\Exception $e) {
                        $title = $nameEn;
                        $description = $descriptionEn;
                    }
                    tagTranslation::create([
                        'tag_id'       => $id,
                        'category_id'  => $categoryId,
                        'language_id'  => $lang->id,
                        'title'        => $title,
                        'description'  => $description,
                        'seo_url'      => Helper::getSeoUrl($title),
                    ]);
                }
            }else {
                foreach ($languages as $lang) {
                    $translation = tagTranslation::find($request->{'tranlation_'.$lang->code});
                    if (!$translation) {
                        continue;
                    }
                    $title = $request->{'tags_name_'.$lang->code} ?? '';
                    $translation->update([
                        'title' => $title,
                        'description' => $title,
                        'seo_url' => Helper::getSeoUrl($title),
                    ]);
                }
            }
        }
        return redirect('admin/tags')->with('msg', 'Tag updated successfully.');
    }

    public function changeTagStatus(Request $request)
    {

        $TagStatus = Tags::find($request->user_id);
        $TagStatus->status = $request->status;
        $is_saved = $TagStatus->save();
        if($is_saved){
            $getTagStatus = tagTranslation::where('tag_id', $request->user_id)->get();
            if(count($getTagStatus) > 0){
                $t_status = ($request->status == 'Active') ? 1 : 0;
                foreach($getTagStatus as $tagval){

                    $tagTranslationStatus = tagTranslation::find($tagval->id);
                    if ($tagTranslationStatus) {
                        $tagTranslationStatus->status = $t_status;
                        $tagTranslationStatus->save();
                    }
                }
            }
            return response()->json(['success' => 'Status change successfully.']);
        }
    }

    public function destroy($id)
    {
        Tags::where('id', $id)->delete();
        tagTranslation::where('tag_id', $id)->delete();
        return redirect('admin/tags')->with('msg', 'Tags deleted successfully');
    }

    public function getSubcategory(Request $request)
    {
        $data['subcat'] = Category::where("parent_id", $request->category_id)
            ->where('status', 'Active')->where('deleted', '0')->orderBy('name', 'ASC')->get(["id", "name"]);
        return response()->json($data);
    }

    public function getTagsSubcategoryId(Request $request)
    {
        $data['tags'] = DB::table('category_tags')->select('id','tags','child_cat_id')->where('child_cat_id',$request->subcategory_id)->where('status','Active')->orderBy('tags', 'ASC')->groupBy('tags')->get()->toArray();
        return response()->json($data);
    }

}
