<?php

namespace App\Http\Controllers\Admin\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\CatCustomFields;
use App\Helpers\Helper;

class CatCustomFieldController extends Controller
{
    public function index(Request $request){
        if($request->input('search')){
            $query = $request->all();
            $searchQuery = $request->input('searchtext');           
            $data['searchQuery']=$request->input('searchtext');
            $data['table_name']='category_fields';
            $data['columns']=['label','name','type','status'];
            $categories=Helper::masterSearch($data);    
            $searchQuery = $request->input('searchtext')?$request->input('searchtext'):'';
            $data = isset($searchQuery)?$searchQuery:"";
            return view('admin.customfields.index', ['categories' => $categories,'data'=> $data,'query'=>$query]);      
        }else{
        $categories = CatCustomFields::where('deleted','0')->orderBy('id', 'desc')->paginate(20);
        $searchQuery = $request->input('searchtext')?$request->input('searchtext'):'';
        $data = isset($searchQuery)?$searchQuery:"";
        return view('admin.customfields.index', ['categories' => $categories,'data'=> $data]);
        }
    }
    public function create(){
       return view('admin.customfields.create');
    }
    public function submitcustfield(Request $request){
        $this->validate($request, [
            'label'  => 'required'  ,    
            'name'  => 'required',
            'type'  => 'required'              
        ]);
        $lang = new CatCustomFields;
        $lang->label    = $request->input('label');
        $lang->name         = $request->input('name');
        $lang->type         = $request->input('type');
        $lang->created_by   = $request->input('createdby');
        $lang->save();
        return redirect('admin/category-cusfield')->with('msg', 'Category Custom Fields Added Successfully!');
    }

    public function editcustfield($id=0){
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false){
            abort(404);
        }
        $data['catcustfieldDetails'] = CatCustomFields::findOrFail($id);
        return view('admin.customfields.edit', ['data'=>$data]);
    }

    public function updatecustfield(Request $request,$id){
        $updateData = $this->validate($request, [
            'label'  => 'required'  ,    
            'name'  => 'required',
            'type'  => 'required' 
        ]);
        CatCustomFields::where('id', $id)
        ->update([
            'label' => $request->input('label'),
            'name' => $request->input('name'),
            'type' => $request->input('type')
         ]);
        return redirect('admin/category-cusfield')->with('msg', 'Category Custom Fields has been updated');  
    }
    public function destroy($id)
    {
        CatCustomFields::where('id', $id)->update([ 'deleted' => '1' ]);
        return redirect('admin/category-cusfield')->with('msg','Category Custom Fields deleted successfully');
    }
}
