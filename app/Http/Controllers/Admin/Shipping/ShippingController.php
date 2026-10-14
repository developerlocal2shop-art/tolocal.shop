<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Amenity;
use App\Models\AmenityCategory;
use App\Helpers\Helper;
use App\Models\City;
use App\Models\Country;

class ShippingController extends Controller
{
    public function index(Request $request){
        if($request->input('search')){
            $query = $request->all();
            $searchQuery = $request->input('searchtext')?$request->input('searchtext'):"";
            $data = isset($searchQuery)?$searchQuery:"";
                $countries = Country::with('states')->with('cities')->where('name', 'like', $searchQuery .'%')->orderBy('id', 'desc')->paginate(10);
                return view('admin.shipping.index', ['countries' => $countries,'data'=> $data]);
        }else{
            $countries = Country::with('states')->with('cities')->orderBy('id', 'desc')->paginate(10);
            $searchQuery = $request->input('searchtext')?$request->input('searchtext'):'';
            $data = isset($searchQuery)?$searchQuery:"";
            return view('admin.shipping.index', ['countries' => $countries,'data'=> $data]);
        }
    }

    public function create(){
        $categorydata = AmenityCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.shipping.create', ['catdata' => $categorydata]);
    }

    public function submit(Request $request){
        $this->validate($request, [
            'name'  => 'required',
            'description' => 'required',
            'icon_class' => 'required',
        ]);
        $cat = new Amenity;
        $cat->title = $request->input('name');
        $cat->slug = Helper::getSeoUrl($request->input('name'));
        $cat->description = $request->input('description');
        $cat->icon_class = $request->input('icon_class');
        $cat->category_id = $request->input('amecategory');
        $cat->save();
        return redirect('admin/shipping')->with('msg', 'Amenity Added Successfully!');
    }
    public function edit($id=0){
        $amenityDetails = Amenity::findOrFail($id);
        $categorydata = AmenityCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.shipping.edit')->with(compact(['amenityDetails','categorydata']));
    }

    public function update(Request $request){
        $validator = \Validator::make($request->all(), [
            'shipping_state' => 'required',
        ]);
        if($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->messages()]);
        }else{
            foreach($request->city_id as $key => $city_id){
                $res = City::where('id', $city_id)
                ->update([
                    'shipping_amount' => $request->shipping_charge[$key],
                    'default_delivery_day' => $request->default_delivery_day[$key]
                ]);
            }
            if($res){
                return response()->json(['status' => true, 'message' => 'Updated successfully!']);
            } else {
                return response()->json(['status' => false, 'message' => 'Something went wrong. Try again! ']);
            }
        }
    }

    public function destroy($id)
    {
        Amenity::where('id', $id)->update([ 'deleted' => '1' ]);
        return redirect('admin/shipping')->with('msg','Amenity deleted successfully');
    }

    public function changeStatus(Request $request){
        $data = City::find($request->id);
        $data->status = $request->status;
        $data->save();
        return response()->json(['success'=>'Status change successfully.']);
    }
}