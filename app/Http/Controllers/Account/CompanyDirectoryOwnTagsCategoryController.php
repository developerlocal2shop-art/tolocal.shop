<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DirectoryOwnTagCategory;
use App\Helpers\Helper;
use Illuminate\Support\Facades\Auth;

class CompanyDirectoryOwnTagsCategoryController extends Controller
{
    public function index(Request $request){
        if($request->input('search')){
            $searchQuery = $request->input('searchtext');
            $data['searchQuery']=$request->input('searchtext');
            $data['table_name']='directory_own_tags_category';
            $data['columns']=['name','status'];
            $directoryowntagscategory=Helper::masterSearch($data);
        }else{
            $directoryowntagscategory = DirectoryOwnTagCategory::where('deleted','0')->where('directory_id',Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
        }
       $searchQuery = $request->input('searchtext')?$request->input('searchtext'):'';
       $data = isset($searchQuery)?$searchQuery:"";
       return view('account.company.directoryowntagscategory.index', ['directoryowntagscategory' => $directoryowntagscategory,'data'=> $data]);
    }

    public function create(){
        return view('account.company.directoryowntagscategory.create');
    }

    public function submit(Request $request){
        $this->validate($request, [
            'name'  => 'required',
            'description' => 'required',
        ]);
        $tradecat = new DirectoryOwnTagCategory;
        $tradecat->directory_id = Auth::user()->directory_id;
        $tradecat->name = $request->input('name');
        $tradecat->description = $request->input('description');
        $tradecat->save();
        return redirect('company/directory-own-tags-category')->with('msg', 'Directory Own Tags Category Added Successfully!');
    }

    public function edit($id=0){
        $directoryCategoryDetails = DirectoryOwnTagCategory::findOrFail($id);
        return view('account.company.directoryowntagscategory.edit')->with(compact(['directoryCategoryDetails']));
    }

    public function update(Request $request,$id){
        $updateData = $this->validate($request, [
            'name'  => 'required',
            'description' => 'required'
        ]);
        DirectoryOwnTagCategory::where('id', $id)
        ->update([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);
        return redirect('company/directory-own-tags-category')->with('msg', 'Directory Own Tags Category has been updated');
    }
    
    public function destroy($id)
    {
        DirectoryOwnTagCategory::where('id', $id)->update([ 'deleted' => '1' ]);
        return redirect('company/directory-own-tags-category')->with('msg','Directory Own Tags Category deleted successfully');
    }
}
