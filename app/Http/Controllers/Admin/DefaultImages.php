<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Defaultimagesadmin;
use App\Helpers\Helper;

class DefaultImages extends Controller
{
    public function index(Request $request)
    {
        $defaultimagesDetails = Defaultimagesadmin::where('deleted', '0')->get();
        //echo "<pre>"; print_r($defaultimagesDetails);die;
        return view('admin.defaultimages.index', ['defaultimagesDetails' => $defaultimagesDetails]);
    }

    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $defaultImagesAdminSingleDetail      = Defaultimagesadmin::findOrFail($id);
                
        return view('admin.defaultimages.edit', ['defaultImagesAdminSingleDetail' => $defaultImagesAdminSingleDetail]);
    }

    public function update(Request $request, $id)
    {
        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/default_images/' . $imageName;
            $imgfile->move(public_path('files/default_images/'), $imageName);
            if ($imgfile != "") {
                Defaultimagesadmin::where('id', $id)->update(['image_name' => $imageName,]);
            }
        }
        
        return redirect('admin/default-images')->with('msg', 'Default image has been updated');
    }
    public function destroy($id)
    {
        Defaultimagesadmin::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/default-images')->with('msg', 'Default image details deleted successfully');
    }
}
