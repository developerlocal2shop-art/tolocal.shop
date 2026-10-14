<?php

namespace App\Http\Controllers\Account\Attributes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Helpers\Helper;
use App\Models\Attribute;
use App\Models\AttributeStatus;
use Illuminate\Support\Facades\Auth;

class AttributesController extends Controller
{

    public function index()
    {
        //$attribute = Attribute::where('deleted', '0')->where('directory_id', Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
        $attribute = Attribute::with('AttributeStatus')->where('deleted', '0')->where('status', 1)->orderBy('id', 'desc')->paginate(20);
        return view('account.company.attribute.index', ['attribute' => $attribute]);
    }
    public function create()
    {
        return view('account.company.attribute.create');
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'content' => 'required'
        ]);
        $cat = new Attribute;
        $cat->name = $request->input('name');
        $cat->slug = Helper::getSeoUrl($request->input('name'));
        $cat->content = $request->input('content');
        $cat->directory_id = Auth::user()->directory_id;
        $cat->save();
        return redirect('company/attributes')->with('msg', 'Successfully Added!');
    }
    public function edit($id = 0)
    {
        $AttributeDetails = Attribute::findOrFail($id);
        return view('account.company.attribute.edit')->with(compact(['AttributeDetails']));
    }
    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required',
            'content' => 'required'
        ]);
        Attribute::where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'slug'  => Helper::getSeoUrl($request->input('name')),
                'content' => $request->input('content'),
                'directory_id' => Auth::user()->directory_id
            ]);
        return redirect('company/attributes')->with('msg', 'Data has been updated');
    }
    public function destroy($id)
    {
        Attribute::where('id', $id)->update(['deleted' => '1']);
        return redirect('company/attributes')->with('msg', 'Deleted successfully');
    }
    public function changeStatus(Request $request)
    {
        //dd($request->all());
        if($request->id == '0'){
            $amenity = new AttributeStatus;
        }else{
            $amenity = AttributeStatus::find($request->id);
        }
        
        $amenity->attribute_id = $request->attrid;
        $amenity->directory_id = Auth::user()->directory_id;
        $amenity->status = $request->status;
        $amenity->save();
        return response()->json(['success' => 'Status change successfully.']);
    }
}
