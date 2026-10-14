<?php

namespace App\Http\Controllers\Admin\Attributes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Helpers\Helper;
use App\Models\Attribute;

class AttributesController extends Controller
{
    public function index()
    {
        $attribute = Attribute::where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.attribute.index', ['attribute' => $attribute]);
    }

    public function create()
    {
        return view('admin.attribute.create');
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
        $cat->save();
        return redirect('admin/attributes')->with('msg', 'Successfully Added!');
    }
    public function edit($id = 0)
    {
        $AttributeDetails = Attribute::findOrFail($id);
        return view('admin.attribute.edit')->with(compact(['AttributeDetails']));
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
                'content' => $request->input('content')
            ]);
        return redirect('admin/attributes')->with('msg', 'Data has been updated');
    }
    public function destroy($id)
    {
        Attribute::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/attributes')->with('msg', 'Deleted successfully');
    }
    public function changeStatus(Request $request)
    {
        $amenity = Attribute::find($request->id);
        $amenity->status = $request->status;
        $amenity->save();
        return response()->json(['success' => 'Status change successfully.']);
    }
}
