<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use App\Models\DirectoryCreateOwnTags;
use App\Models\DirectoryNewOwnTags;

class DirectoryOwnTagsControllers extends Controller
{
    public function index()
    {
        $directoryowntags = DirectoryCreateOwnTags::query()->orderBy('id', 'desc')->paginate(20);
        $allDirectory    = Helper::getAllDirectory();
        return view('admin.directoryowntag.index', ['tags' => $directoryowntags, 'allDirectory' => $allDirectory]);
    }
    public function create()
    {
        $data['directoryData']    = Helper::getAllDirectory();      /* get All directory whose type is listing */
        return view('admin.directoryowntag.create', ['data' => $data]);
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'director_id' => 'required',
        ]);
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
        return redirect('/admin/directory-own-tags')->with('msg', 'Tags Added Successfully!');
    }
    public function edit($id = 0)
    {
        $data['tagseditdata']        = DirectoryCreateOwnTags::findOrFail($id);
        $data['directoryData']      = Helper::getAllDirectory();      /* get All directory whose type is listing */
        return view('admin.directoryowntag.edit', ['data' => $data]);
    }
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'director_id' => 'required',
        ]);
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
            $tagedit->save();
        }
        if (empty($ifdataisAlready) && $returnval == "yes") {
            $tags_insert = DirectoryCreateOwnTags::find($id);
            $tags_insert->directory_id              = $directory_id;
            $tags_insert->tag_name                  = $request->input('tags_name');
            $tags_insert->add_to_directory_page     = $request->input('add_tags_to_page');
            $tags_insert->created_by                = $request->input('createdby');
            $tags_insert->save();
            $owntagsInsertData = new DirectoryNewOwnTags;
            $owntagsInsertData->directory_id  = $directory_id;
            $owntagsInsertData->owntags_id  = $id;
            $owntagsInsertData->save();
        } else {
            $tagedit =  DirectoryCreateOwnTags::find($id);
            $tagedit->directory_id                  = $directory_id;
            $tagedit->tag_name                      = $request->input('tags_name');
            $tagedit->save();
        }
        return redirect('admin/directory-own-tags')->with('msg', 'Tags has been updated');
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
        return redirect('admin/directory-own-tags')->with('msg', 'Tags deleted successfully');
    }
}
