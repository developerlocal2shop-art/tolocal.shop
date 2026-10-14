<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use App\Models\CategoryOwnTags;

class OwnTagsControllers extends Controller
{
    public function index(Request $request)
    {

        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : "";
            $cat_id = $request->input('category') ? $request->input('category') : "";
            if ($searchQuery != "" && $cat_id != "") {
                $data['directorysearchDetails'] = DB::table('category_own_tags')
                    ->select('*')
                    ->where('tags', 'like', '%' . $searchQuery . '%')
                    ->Where('category_id',  $cat_id);

                $owntags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllCategoryTypeListing();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.owntags.index', ['tags' => $owntags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
            } else if ($cat_id != "") {
                $data['directorysearchDetails'] = DB::table('category_own_tags')
                    ->select('*')
                    ->where('category_id',  $cat_id);

                $owntags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllCategoryTypeListing();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.owntags.index', ['tags' => $owntags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
            } else {
                $data['directorysearchDetails'] = DB::table('category_own_tags')
                    ->select('*')
                    ->where('tags', 'like', '%' . $searchQuery . '%');

                $owntags =    $data['directorysearchDetails']->orderBy('id', 'desc')->paginate(20);
                $parent_category    = Helper::getAllCategoryTypeListing();
                $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.owntags.index', ['tags' => $owntags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category, 'query' => $query]);
            }
        } else {
            $owntags = CategoryOwnTags::query()->orderBy('id', 'desc')->paginate(20);
            $parent_category    = Helper::getAllCategoryTypeListing();
            $searchQuery1 = isset($searchQuery) ? $searchQuery : '';
            $searchCatId = isset($cat_id) ? $cat_id : '';
            return view('admin.owntags.index', ['tags' => $owntags, 'searchtxt' => $searchQuery1, 'catid' => $searchCatId, 'allCategory' => $parent_category]);
        }
    }

    public function create()
    {
        $data['parent_category']    = Helper::getAllCategoryTypeListing();
        return view('admin.owntags.create', ['data' => $data]);
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'category' => 'required',
        ]);
        $category_id = $request->input('category');
        $tagsName = explode(',', $request->input('tags_name'));
        foreach ($tagsName as $tag) {
            $tags_insert = new CategoryOwnTags;
            $tags_insert->category_id       = $category_id;
            $tags_insert->tags              = $tag;
            $tags_insert->created_by        = $request->input('createdby');
            $tags_insert->save();
        }
        return redirect('admin/owntags')->with('msg', 'Tags Added Successfully!');
    }
    public function edit($id = 0)
    {
        $data['tagseditdata']        = CategoryOwnTags::findOrFail($id);
        $data['all_category']    = Helper::getAllCategoryTypeListing();      /* get Category */
        return view('admin.owntags.edit', ['data' => $data]);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category' => 'required',
        ]);
        $tagedit =  CategoryOwnTags::find($id);
        $tagedit->category_id       = $request->input('category');
        $tagedit->tags              = $request->input('tags_name');
        $tagedit->save();
        return redirect('admin/owntags')->with('msg', 'Tags has been updated');
    }

    public function changeownTagStatus(Request $request)
    {
        $TagStatus = CategoryOwnTags::find($request->user_id);
        $TagStatus->status = $request->status;
        $TagStatus->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        CategoryOwnTags::where('id', $id)->delete();
        return redirect('admin/owntags')->with('msg', 'Tags deleted successfully');
    }
}
