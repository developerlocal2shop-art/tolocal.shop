<?php

namespace App\Http\Controllers\Admin\Amenity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Amenity;
use App\Models\AmenityCategory;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class AmenityController extends Controller
{
    public function index(Request $request)
    {
        $categorydata    = Helper::getamenityCategoryData(); 
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : "";
            $cat_id = $request->input('category') ? $request->input('category') : "";
            $sort = $request->input('sort') ? $request->input('sort') : "";
            if ($sort != "") {
                if ($sort == "name") {
                    $amenities = Amenity::where('deleted', '0')->orderBy('title', 'asc')->paginate(20);
                    $data = isset($searchQuery) ? $searchQuery : "";
                    $searchCatId = isset($cat_id) ? $cat_id : '';
                    return view('admin.amenity.index', ['amenities' => $amenities, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
                } else {
                    $amenities = Amenity::where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                    $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
                    $data = isset($searchQuery) ? $searchQuery : "";
                    $searchCatId = isset($cat_id) ? $cat_id : '';
                    return view('admin.amenity.index', ['amenities' => $amenities, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata]);
                }
            } else {
                if ($searchQuery != "" && $cat_id != "") {
                    $data['amenitycat'] = DB::table('amenity')
                        ->select('*')
                        ->where('title', 'like', '%' . $searchQuery . '%')
                        ->Where('category_id',  $cat_id);
                    $amenities =    $data['amenitycat']->orderBy('id', 'desc')->paginate(20);
                    $data = isset($searchQuery) ? $searchQuery : "";
                    $searchCatId = isset($cat_id) ? $cat_id : '';
                    return view('admin.amenity.index', ['amenities' => $amenities, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
                } else if ($cat_id != "") {
                    $data['amenitycat'] = DB::table('amenity')
                        ->select('*')
                        ->where('category_id',  $cat_id);
                    $amenities =    $data['amenitycat']->orderBy('id', 'desc')->paginate(20);
                    $data = isset($searchQuery) ? $searchQuery : "";
                    $searchCatId = isset($cat_id) ? $cat_id : '';
                    return view('admin.amenity.index', ['amenities' => $amenities, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
                } else {
                    $data['amenitycat'] = DB::table('amenity')
                        ->select('*')
                        ->where('title', 'like', '%' . $searchQuery . '%');
                    $amenities =    $data['amenitycat']->orderBy('id', 'desc')->paginate(20);
                    $data = isset($searchQuery) ? $searchQuery : "";
                    $searchCatId = isset($cat_id) ? $cat_id : '';
                    return view('admin.amenity.index', ['amenities' => $amenities, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
                }
            }
        } else {
            $amenities = Amenity::where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
            $data = isset($searchQuery) ? $searchQuery : "";
            $searchCatId = isset($cat_id) ? $cat_id : '';
            return view('admin.amenity.index', ['amenities' => $amenities, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata]);
        }
    }

    public function create()
    {
        $categorydata = AmenityCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.amenity.create', ['catdata' => $categorydata]);
    }

    public function import()
    {
        $data =   array(
            0 => 'Activity Room',
            1 => 'Badminton Court',
            2 => 'Bakery',
            3 => 'Balcony',
            4 => 'Banks',
            5 => 'Basketball Court',
            6 => 'BBQ Pits',
            7 => 'Beach',
            8 => 'Beacons',
            9 => 'Beauty Salon',
            10 => 'Bike parking',
            11 => 'Bike Paths',
            12 => 'Billiards',
            13 => 'Billiards Table',
            14 => 'Bowling',
            15 => 'Cafes',
            16 => 'Central air conditioning and heating',
            17 => 'Central Areas',
            18 => 'Childcare',
            19 => 'Cinemas',
            20 => 'Clean Air',
            21 => 'Clean Water',
            22 => 'Clinics',
            23 => 'Clubhouse',
            24 => 'Clubhouse',
            25 => 'Community Center',
            26 => 'Complimentary Wi-Fi',
            27 => 'Concert Hall',
            28 => 'Concierge',
            29 => 'Conference Rooms',
            30 => 'connectivity',
            31 => 'Convenience Store',
            32 => 'Covered parking',
            33 => 'Darts',
            34 => 'Dentists',
            35 => 'Dishwasher',
            36 => 'Dry Cleaning',
            37 => 'emergency services',
            38 => 'Fitness centre',
            39 => 'Food & Drink',
            40 => 'Free Phone Charging Stations',
            41 => 'Free Wi-Fi',
            42 => 'Game Room',
            43 => 'Gardens',
            44 => 'Golf Green',
            45 => 'Green surrounding neighbourhood',
            46 => 'Gymnasium',
            47 => 'Hair Salon',
            48 => 'Heritage Sites',
            49 => 'High-end finishes and fixtures',
            50 => 'Hiking',
            51 => 'Hospitals',
            52 => 'Hot Springs',
            53 => 'Ice Skating',
            54 => 'Jogging Track',
            55 => 'Karaoke',
            56 => 'Kitchen Facilities Lessons',
            57 => 'Large bathtubs',
            58 => 'Libraries Lounge',
            59 => 'Local Attractions',
            60 => 'Markets Media Services',
            61 => 'Meeting Rooms',
            62 => 'Mini Golf',
            63 => 'Mobile Payments',
            64 => 'Near-Field Technology (NFC',
            65 => 'Office Facilities',
            66 => 'Online rent options',
            67 => 'Package lockers',
            68 => 'Parking',
            69 => 'Parks',
            70 => 'Party room',
            71 => 'Pet-friendly spaces',
            72 => 'Pharmacy',
            73 => 'Piano / Musical Instruments',
            74 => 'Picnic Area',
            75 => 'Places of Scenic Beauty',
            76 => 'Play Area',
            77 => 'Playgrounds',
            78 => 'Pleasant Views',
            79 => 'Poolside Restaurant / Bar',
            80 => 'Post Office',
            81 => 'postal facilities',
            82 => 'public restrooms',
            83 => 'Recreational Facilities',
            84 => 'Religious Services',
            85 => 'Restaurants',
            86 => 'roads',
            87 => 'Robotics',
            88 => 'Rooftop garden',
            89 => 'Room Service',
            90 => 'safe drinking water,',
            91 => 'Salon',
            92 => 'Schools',
            93 => 'Security',
            94 => 'Security systems and intercom',
            95 => 'Self Check-Out',
            96 => 'Shops',
            97 => 'Shuttle Service',
            98 => 'sidewalks',
            99 => 'signage',
            100 => 'Skiing / Snowboarding',
            101 => 'Smart home',
            102 => 'Social area',
            103 => 'Spa',
            104 => 'Spa',
            105 => 'Space',
            106 => 'Squash Courts',
            107 => 'Surveillance cameras',
            108 => 'Swimming pools',
            109 => 'Table Tennis',
            110 => 'Tennis Courts',
            111 => 'Theatres & Performances',
            112 => 'Touchscreens',
            113 => 'Tours',
            114 => 'Train Stations',
            115 => 'Valet trash',
            116 => 'Vending Machines',
            117 => 'Walking paths',
            118 => 'Washer & Dryer',
            119 => 'Washer and Dryer in-unit',
            120 => 'Wedding Chapel',
            121 => 'Wi-Fi',
        );
        foreach ($data as $key => $value) {
            $cat = new Amenity;
            $cat->title = $value;
            $cat->description = $value;
            $cat->icon_class = 'fas fa-check';
            $cat->save();
        }
        redirect('admin/amenities');
    }


    public function submitAmenity(Request $request)
    {
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
        return redirect('admin/amenities')->with('msg', 'Amenity Added Successfully!');
    }
    public function editAmenity($id = 0)
    {
        $amenityDetails = Amenity::findOrFail($id);
        $categorydata = AmenityCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.amenity.edit')->with(compact(['amenityDetails', 'categorydata']));
    }

    public function updateAmenity(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required',
            'description' => 'required',
            'icon_class' => 'required'
        ]);
        Amenity::where('id', $id)
            ->update([
                'title' => $request->input('name'),
                'slug'  => Helper::getSeoUrl($request->input('name')),
                'description' => $request->input('description'),
                'icon_class' => $request->input('icon_class'),
                'category_id' => $request->input('amecategory')
            ]);
        return redirect('admin/amenities')->with('msg', 'Amenity has been updated');
    }
    public function destroy($id)
    {
        Amenity::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/amenities')->with('msg', 'Amenity deleted successfully');
    }
    public function changeAmenityStatus(Request $request)
    {
        $amenity = Amenity::find($request->id);
        $amenity->status = $request->status;
        $amenity->save();
        return response()->json(['success' => 'Status change successfully.']);
    }
}
