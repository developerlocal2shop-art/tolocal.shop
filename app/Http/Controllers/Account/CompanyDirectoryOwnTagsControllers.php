<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use App\Models\DirectoryCreateOwnTags;
use App\Models\DirectoryNewOwnTags;
use Illuminate\Support\Facades\Auth;
use App\Models\DirectoryOwnTagCategory;

class CompanyDirectoryOwnTagsControllers extends Controller
{
    public function index(Request $request)
    {
        if (Auth::check()) {
            if (Helper::getFeatureUses('directory-own-tags') == false) {
                return abort(404);
            }
            if (Auth::user()->profile_status == 0) {
                return redirect('complate-companyinfo')->with('msg', "You need to complete company's information");
            } else {
                if ($request->input('search')) {
                    $query = $request->all();
                    $searchQuery = $request->input('searchtext');
                    $cat_id = $request->input('category') ? $request->input('category') : "";
                    if ($searchQuery != "" && $cat_id != "") {
                        $data['directorycat'] = DB::table('directory_createown_tags')
                            ->select('*')
                            ->where('directory_id', Auth::user()->directory_id)
                            ->where('tag_name', 'like', '%' . $searchQuery . '%')
                            ->Where('category_id',  $cat_id);
                        $tags =    $data['directorycat']->orderBy('id', 'desc')->paginate(20);
                        $categorydata = DirectoryOwnTagCategory::query()->where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
                        $data = isset($searchQuery) ? $searchQuery : "";
                        $searchCatId = isset($cat_id) ? $cat_id : '';
                        return view('account.company.directoryowntag.index', ['tags' => $tags, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
                    } else if ($cat_id != "") {
                        $data['directorycat'] = DB::table('directory_createown_tags')
                            ->select('*')
                            ->where('directory_id', Auth::user()->directory_id)
                            ->where('category_id',  $cat_id);

                        $tags =    $data['directorycat']->orderBy('id', 'desc')->paginate(20);
                        $categorydata = DirectoryOwnTagCategory::query()->where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
                        $data = isset($searchQuery) ? $searchQuery : "";
                        $searchCatId = isset($cat_id) ? $cat_id : '';
                        return view('account.company.directoryowntag.index', ['tags' => $tags, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
                    } else {
                        $data['directorycat'] = DB::table('directory_createown_tags')
                            ->select('*')
                            ->where('directory_id', Auth::user()->directory_id)
                            ->where('tag_name', 'like', '%' . $searchQuery . '%');
                        $tags = $data['directorycat']->orderBy('id', 'desc')->paginate(20);
                        $categorydata = DirectoryOwnTagCategory::query()->where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
                        $data = isset($searchQuery) ? $searchQuery : "";
                        $searchCatId = isset($cat_id) ? $cat_id : '';
                        return view('account.company.directoryowntag.index', ['tags' => $tags, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
                    }
                } else {
                    $tags = DirectoryCreateOwnTags::where('directory_id', Auth::user()->directory_id)->where('created_by', Auth::user()->id)->orderBy('id', 'desc')->paginate(20);
                    $categorydata = DirectoryOwnTagCategory::query()->where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
                    $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
                    $data = isset($searchQuery) ? $searchQuery : "";
                    $searchCatId = isset($cat_id) ? $cat_id : '';
                    return view('account.company.directoryowntag.index', ['tags' => $tags, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata]);
                }
            }
        } else {
            return abort(404);
        }
    }

    public function create()
    {
        if (Helper::getFeatureUses('directory-own-tags') == false) {
            return abort(404);
        }
        $directoryData    = Helper::getAllDirectory(); /* get All directory whose type is listing */
        $categorydata = DirectoryOwnTagCategory::query()->where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
        return view('account.company.directoryowntag.create', compact('directoryData', 'categorydata'));
    }
    public function submit(Request $request)
    {
        unset($request['_token']);
        $validator = \Validator::make($request->all(), [
            'tags_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        } else {
            $directory_id = $request->input('director_id');
            $tagsName = explode(',', $request->input('tags_name'));
            $checkvalue = $request->input('add_tags_to_page');
            $newOwnTagsids = array();
            foreach ($tagsName as $tag) {
                $tags_insert = new DirectoryCreateOwnTags;
                $tags_insert->directory_id              = $directory_id;
                $tags_insert->tag_name                  = $tag;
                $tags_insert->add_to_directory_page     = $request->input('add_tags_to_page');
                $tags_insert->created_by                = $request->input('createdby');
                $tags_insert->category_id               = $request->input('directorycategory');
                $tags_insert->save();
                $lastenter_id = $tags_insert->id;
                $newOwnTagsids[] = $lastenter_id;
            }
            if ($checkvalue == "yes") {
                if (!empty($newOwnTagsids)) {
                    foreach ($newOwnTagsids as $owntgs => $value) {
                        $owntagsInsertData = new DirectoryNewOwnTags;
                        $owntagsInsertData->directory_id  = $directory_id;
                        $owntagsInsertData->owntags_id  = $value;
                        $owntagsInsertData->save();
                    }
                }
            }
            if ($newOwnTagsids) {
                return response()->json(['status' => true, 'message' => ' Tags Added Successfully!']);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
            }
        }
    }

    public function edit($id = 0)
    {
        if (Helper::getFeatureUses('directory-own-tags') == false) {
            return abort(404);
        }
        $user = auth()->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $data['tagseditdata']        = DirectoryCreateOwnTags::findOrFail($id);
        $data['directoryData']      = Helper::getAllDirectory();      /* get All directory whose type is listing */
        $categorydata = DirectoryOwnTagCategory::query()->where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
        return view('account.company.directoryowntag.edit', ['data' => $data, 'categorydata' => $categorydata]);
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'tags_name' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        } else {
            $directory_id = $request->input('director_id');
            $ifdataisAlready = DB::table('directory_newown_tags')->select('*')->where('owntags_id', $id)->where('directory_id', $directory_id)->get()->toArray();
            $returnval = $request->input('add_tags_to_page');
            if (!empty($ifdataisAlready) && $returnval != "yes") {
                DB::table('directory_newown_tags')->select('*')->where('owntags_id', $id)->where('directory_id', $directory_id)->delete();
                $tagedit =  DirectoryCreateOwnTags::find($id);
                $tagedit->directory_id                  = $directory_id;
                $tagedit->tag_name                      = $request->input('tags_name');
                $tagedit->add_to_directory_page         = $request->input('add_tags_to_page');
                $tagedit->created_by                    = $request->input('createdby');
                $tagedit->category_id                   = $request->input('directorycategory');
                $tagedit->save();
            }
            if (empty($ifdataisAlready) && $returnval == "yes") {
                $tags_insert = DirectoryCreateOwnTags::find($id);
                $tags_insert->directory_id              = $directory_id;
                $tags_insert->tag_name                  = $request->input('tags_name');
                $tags_insert->add_to_directory_page     = $request->input('add_tags_to_page');
                $tags_insert->created_by                = $request->input('createdby');
                $tags_insert->category_id               = $request->input('directorycategory');
                $tags_insert->save();
                $owntagsInsertData = new DirectoryNewOwnTags;
                $owntagsInsertData->directory_id  = $directory_id;
                $owntagsInsertData->owntags_id  = $id;
                $owntagsInsertData->save();
            } else {
                $tagedit =  DirectoryCreateOwnTags::find($id);
                $tagedit->directory_id                  = $directory_id;
                $tagedit->tag_name                      = $request->input('tags_name');
                $tagedit->category_id                   = $request->input('directorycategory');
                $tagedit->save();
            }
            return response()->json(['status' => true, 'message' => 'Tags has been updated!']);
        }
    }

    public function changedirectoryownTagStatus(Request $request)
    {
        $TagStatus = DirectoryCreateOwnTags::find($request->user_id);
        $TagStatus->status = $request->status;
        $TagStatus->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function destroy($id)
    {
        DirectoryCreateOwnTags::where('id', $id)->delete();
        return redirect('company/directory-own-tags')->with('msg', 'Tags deleted successfully');
    }
}
