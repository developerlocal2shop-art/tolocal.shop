<?php

namespace App\Http\Controllers\Admin\Distance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Distancesetting;
use App\Models\Instruction;

class DistanceControllers extends Controller
{
    public function index()
    {
        $data = Distancesetting::query()->orderBy('id', 'asc')->paginate(10);
        //dd($data);
        //$data = Distancesetting::get()->toarray();
        return view('admin.distance.index', ['distancedata' => $data]);
    }
    public function create()
    {
        return view('admin.distance.create');
    }
    public function submit(Request $request)
    {
        $radioDefaultDistance = $request->input('default_distance');
        if ($radioDefaultDistance == "yes") {
            Distancesetting::query()->update(['default_distance' => "no",]);
        }
        $distance_insert = new Distancesetting;
        $distance_insert->to_distance               = $request->input('to_distance');
        $distance_insert->status                    = $request->input('status');
        $distance_insert->default_distance          = $radioDefaultDistance;
        $distance_insert->save();
        return redirect('admin/distance')->with('msg', 'Distance Added Successfully!');
    }
    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        
        $distanceDetails = Distancesetting::findOrFail($id);
        return view('admin.distance.edit')->with(compact(['distanceDetails']));
    }
    public function update(Request $request, $id)
    {
        $radioDefaultDistance = $request->input('default_distance');
        if ($radioDefaultDistance == "yes") {
            Distancesetting::query()->update(['default_distance' => "no",]);
        }
        Distancesetting::where('id', $id)
            ->update([
                'to_distance'           => $request->input('to_distance'),
                'default_distance'      => $radioDefaultDistance,
                'status'                => $request->input('status')
                ]);            
        return redirect('admin/distance')->with('msg', 'Distance has been updated');
    }
    public function destroy($id)
    {
        Distancesetting::where('id', $id)->delete();
        return redirect('admin/distance')->with('msg', 'Distance deleted successfully');
    }
}
