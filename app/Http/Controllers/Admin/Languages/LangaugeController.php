<?php

namespace App\Http\Controllers\Admin\Languages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Language;
use App\Helpers\Helper;

class LangaugeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'languages';
            $data['columns'] = ['code', 'status', 'name'];
            $languages = Helper::masterSearch($data);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.languages.index', ['languages' => $languages, 'data' => $data, 'query' => $query]);
        } else {
            $languages = Language::where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.languages.index', ['languages' => $languages, 'data' => $data]);
        }
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function submitLanguage(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'code' => 'required'
        ]);
        $lang = new Language;
        $lang->name         = $request->input('name');
        $lang->code         = $request->input('code');
        $lang->created_by   = $request->input('createdby');
        $lang->save();
        return redirect('admin/languages')->with('msg', 'Language Added Successfully!');
    }
    public function editLanguage($id = 0)
    {
        $languageDetails = Language::findOrFail($id);
        return view('admin.languages.edit')->with(compact(['languageDetails']));
    }

    public function updateLanguage(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required',
            'code' => 'required'
        ]);
        Language::where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'code' => $request->input('code')
            ]);
        return redirect('admin/languages')->with('msg', 'Language has been updated');
    }
    public function destroy($id)
    {
        Language::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/languages')->with('msg', 'Language deleted successfully');
    }
}
