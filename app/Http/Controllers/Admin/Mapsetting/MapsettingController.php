<?php

namespace App\Http\Controllers\Admin\Mapsetting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Mapsetting;

class MapsettingController extends Controller
{
    public function index(){        
        $settingDetails = Mapsetting::get()->toarray();
        if(isset($settingDetails[0])){
             $settingDetails = $settingDetails[0];
        } else{
            $settingDetails['id']="";
        }       
        return view('admin.mapsetting.index',['settingDetails'=>$settingDetails]);
    }

    public function submitMapsetting(Request $request){
        $this->validate($request, [
            'map_title'  => 'required',
            'location' => 'required',
            'zoom_level'=> 'required'
        ]);
        $user_id = 0;
        $setting = new Mapsetting;
        $setting->map_title     = $request->input('map_title');
        $setting->mapaccess_token     = $request->input('mapaccess_token');
        $setting->location      = $request->input('location');
        $setting->zoom_level  = $request->input('zoom_level');
        $setting->listing_page_zoom_level  = $request->input('listing_page_zoom_level');
        $setting->directory_page_zoom_level  = $request->input('directory_page_zoom_level');
        $setting->created_by       = $user_id;
        $setting->save();
        return redirect('admin/mapsetting-edit')->with('msg', 'Map setting updated Successfully!');        
    }
   public function editMapsetting(){
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false){
            abort(404);
        }
        $settingDetails = Mapsetting::get()->toarray();        
        return view('admin.mapsetting.index',['settingDetails'=>$settingDetails[0]]);
    }

    public function updatemapsetting(Request $request){
       $this->validate($request, [
            'map_title'  => 'required',
            'location' => 'required',
            'zoom_level'=> 'required'
        ]);
        Mapsetting::where('id', $request->input('id'))
        ->update([
            'map_title' => $request->input('map_title'),
            'mapaccess_token' => $request->input('mapaccess_token'),
            'location' => $request->input('location'),
            'zoom_level' => $request->input('zoom_level'),
            'listing_page_zoom_level' => $request->input('listing_page_zoom_level'),
            'directory_page_zoom_level' => $request->input('directory_page_zoom_level')
         ]);
        return redirect('admin/mapsetting-edit')->with('msg', 'Map setting updated Successfully!');        
    }  
}
