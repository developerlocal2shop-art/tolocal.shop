<?php

namespace App\Http\Controllers\Admin\Amenity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AmenityCategory;
use App\Helpers\Helper;

class AmenityCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'amenity_category';
            $data['columns'] = ['name', 'status'];
            $amenitiescategory = Helper::masterSearch($data);
        } else {
            $amenitiescategory = AmenityCategory::where('deleted', '0')->orderBy('id', 'asc')->paginate(20);
        }
        $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
        $data = isset($searchQuery) ? $searchQuery : "";
        return view('admin.amenitiescategory.index', ['amenitiescategory' => $amenitiescategory, 'data' => $data]);
    }

    public function create()
    {
        return view('admin.amenitiescategory.create');
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'description' => 'required',
        ]);
        $amecat = new AmenityCategory;
        $amecat->name = $request->input('name');
        $amecat->description = $request->input('description');
        $amecat->save();
        return redirect('admin/amenity-category')->with('msg', 'Amenity Category Added Successfully!');
    }
    public function edit($id = 0)
    {
        $amenityDetails = AmenityCategory::findOrFail($id);
        return view('admin.amenitiescategory.edit')->with(compact(['amenityDetails']));
    }

    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required',
            'description' => 'required'
        ]);
        AmenityCategory::where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);
        return redirect('admin/amenity-category')->with('msg', 'Amenity Category has been updated');
    }
    public function destroy($id)
    {
        AmenityCategory::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/amenity-category')->with('msg', 'Amenity Category deleted successfully');
    }
}
