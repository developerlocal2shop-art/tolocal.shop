<?php

namespace App\Http\Controllers\Admin\Directory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use App\Models\Diectory;

class DirectoryBussinessController extends Controller
{
    public function index(Request $request)
    {
        $categorytype  = Helper::getType();  
        $cat_id = $request->input('category') ? $request->input('category') : "";
        $searchCatId = isset($cat_id) ? $cat_id : '';
        if ($request->input('sortbtn') == "valsort") {
            $query = $request->all();
            $sortvalue = $request->input('sortvalue');
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
            $datasearch = isset($searchQuery) ? $searchQuery : "";
            if ($request->input('sortby') == "name") {
                $data['directoryDetails'] = DB::table('directory as dt')
                    ->select('dt.*', 'dt_ln.city_name', 'dt_ln.place', 'dt_ln.address', 'dt_ln.zipcode')
                    ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')
                    ->where('deleted', '0');
                $data['directoryDetails'] =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt.title', $sortvalue);
                $directoryDetails =    $data['directoryDetails']->paginate(10);
                if ($sortvalue == "asc") {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'desc', 'query' => $query]);
                } else {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'asc', 'query' => $query]);
                }
            }
            if ($request->input('sortby') == "city") {
                $data['directoryDetails'] = DB::table('directory as dt')
                    ->select('dt.*', 'dt_ln.city_name', 'dt_ln.place', 'dt_ln.address', 'dt_ln.zipcode')
                    ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')
                    ->where('deleted', '0');
                $data['directoryDetails'] =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt_ln.city_name', $sortvalue);
                $directoryDetails =    $data['directoryDetails']->paginate(10);
                if ($sortvalue == "asc") {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'desc', 'query' => $query]);
                } else {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'asc', 'query' => $query]);
                }
            }
            if ($request->input('sortby') == "address") {
                $data['directoryDetails'] = DB::table('directory as dt')
                    ->select('dt.*', 'dt_ln.city_name', 'dt_ln.place', 'dt_ln.address', 'dt_ln.zipcode')
                    ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')
                    ->where('deleted', '0');
                $data['directoryDetails'] =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt_ln.address', $sortvalue);
                $directoryDetails =    $data['directoryDetails']->paginate(10);
                if ($sortvalue == "asc") {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'desc', 'query' => $query]);
                } else {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'asc', 'query' => $query]);
                }
            }
            if ($request->input('sortby') == "zipcode") {
                $data['directoryDetails'] = DB::table('directory as dt')
                    ->select('dt.*', 'dt_ln.city_name', 'dt_ln.place', 'dt_ln.address', 'dt_ln.zipcode')
                    ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')
                    ->where('deleted', '0');
                $data['directoryDetails'] =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt_ln.zipcode', $sortvalue);
                $directoryDetails =    $data['directoryDetails']->paginate(10);
                if ($sortvalue == "asc") {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'desc', 'query' => $query]);
                } else {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'asc', 'query' => $query]);
                }
            }
            if ($request->input('sortby') == "place") {
                $data['directoryDetails'] = DB::table('directory as dt')
                    ->select('dt.*', 'dt_ln.city_name', 'dt_ln.place', 'dt_ln.address', 'dt_ln.zipcode')
                    ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')
                    ->where('deleted', '0');
                $data['directoryDetails'] =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt_ln.place', $sortvalue);
                $directoryDetails =    $data['directoryDetails']->paginate(10);
                if ($sortvalue == "asc") {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'desc', 'query' => $query]);
                } else {
                    return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'asc', 'query' => $query]);
                }
            }
        } else if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
            $cat_id = $request->input('category') ? $request->input('category') : "";
            $datasearch = isset($searchQuery) ? $searchQuery : "";

            if ($cat_id != "") {
                $categorywisedata = DB::table('directory as dt')
                    ->join('directory_location', 'dt.id', '=', 'directory_location.directory_id')
                    ->join('directory_type', 'dt.id', '=', 'directory_type.directory_id')
                    ->select(
                        'dt.*',
                        'directory_location.city_name',
                        'directory_location.place',
                        'directory_location.address',
                        'directory_location.zipcode'
                    )
                    ->where('directory_type.category_id', $cat_id);
                $data['directoryDetails'] =    $categorywisedata->groupBy('dt.id')->orderBy('dt.id', 'desc');
                $directoryDetails =    $data['directoryDetails']->paginate(10);
            } else {
                $data['directoryDetails'] = DB::table('directory as dt')
                    ->select('dt.*', 'dt_ln.city_name', 'dt_ln.place', 'dt_ln.address', 'dt_ln.zipcode')
                    ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')
                    ->where('deleted', '0')
                    ->where('dt.title', 'like', '%' . $searchQuery . '%');
                $data['directoryDetails'] =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt.id', 'desc');
                $directoryDetails =    $data['directoryDetails']->paginate(10);
            }
            return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'asc', 'query' => $query]);
        } else {
            $data['directoryDetails'] = DB::table('directory as dt')
                ->select('dt.*', 'dt_ln.city_name', 'dt_ln.place', 'dt_ln.address', 'dt_ln.zipcode')
                ->leftjoin('directory_location as dt_ln', 'dt.id', '=', 'dt_ln.directory_id')
                ->where('deleted', '0');
            $data['directoryDetails'] =    $data['directoryDetails']->groupBy('dt.id')->orderBy('dt.id', 'desc');
            $directoryDetails =    $data['directoryDetails']->paginate(10);
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
            $datasearch = isset($searchQuery) ? $searchQuery : "";
            return view('admin.business.index', ['directoryDetails' => $directoryDetails, 'data' => $datasearch, 'categorytype' => $categorytype, 'catid' => $searchCatId, 'sortvaluedata' => 'asc']);
        }
    }
}
