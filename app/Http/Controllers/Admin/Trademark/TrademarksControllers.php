<?php

namespace App\Http\Controllers\Admin\Trademark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Trademarks;
use App\Helpers\Helper;
use App\Models\TrademarkCategory;
use Intervention\Image\ImageManagerStatic as Image;

class TrademarksControllers extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $cat_id = $request->input('category') ? $request->input('category') : "";
            if ($searchQuery != "" && $cat_id != "") {
                $data['trademarkscat'] = DB::table('trademarks')
                    ->select('*')
                    ->where('name', 'like', '%' . $searchQuery . '%')
                    ->Where('category_id',  $cat_id);
                $trademark =    $data['trademarkscat']->orderBy('id', 'desc')->paginate(20);
                $categorydata = TrademarkCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                $data = isset($searchQuery) ? $searchQuery : "";
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.trademarks.index', ['trademark' => $trademark, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
            } else if ($cat_id != "") {
                $data['trademarkscat'] = DB::table('trademarks')
                    ->select('*')
                    ->where('category_id',  $cat_id);
                $trademark =    $data['trademarkscat']->orderBy('id', 'desc')->paginate(20);
                $categorydata = TrademarkCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                $data = isset($searchQuery) ? $searchQuery : "";
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.trademarks.index', ['trademark' => $trademark, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
            } else {
                $data['trademarkscat'] = DB::table('trademarks')
                    ->select('*')
                    ->where('name', 'like', '%' . $searchQuery . '%');
                $trademark =    $data['trademarkscat']->orderBy('id', 'desc')->paginate(20);
                $categorydata = TrademarkCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                $data = isset($searchQuery) ? $searchQuery : "";
                $searchCatId = isset($cat_id) ? $cat_id : '';
                return view('admin.trademarks.index', ['trademark' => $trademark, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata, 'query' => $query]);
            }
        } else {
            $trademark = Trademarks::where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $categorydata = TrademarkCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
            $data = isset($searchQuery) ? $searchQuery : "";
            $searchCatId = isset($cat_id) ? $cat_id : '';
            return view('admin.trademarks.index', ['trademark' => $trademark, 'data' => $data, 'catid' => $searchCatId, 'catdata' => $categorydata]);
        }
    }


    public function pendingtrademarks()
    {
        $pendingdata = Trademarks::where('status', 0)->where('deleted', '0')->paginate(20);
        return view('admin.trademarks.pendding', compact('pendingdata'));
    }

    public function updateStatus(Request $request)
    {
        unset($request['_token']);
        $status = $request['status'];
        $id = $request['id'];
        $Status = Trademarks::find($id);
        $Status->status = $status;
        $result = $Status->save();
        if ($result) {
            $response = array('status' => 'success', 'msg' => 'Status changed successfully!!.');
        } else {
            $response = array('status' => 'error', 'msg' => 'Not changed. Try again!!');
        }
        echo json_encode($response);
        exit;
    }

    public function updateBulkStatus(Request $request)
    {
        unset($request['_token']);
        $tradId = explode(',', $request['id']);
        $status = $request['status'];
        foreach ($tradId as $val) {
            $Status = Trademarks::find($val);
            $Status->status = $status;
            $result = $Status->save();
        }
        if ($result) {
            $response = array('status' => 'success', 'msg' => 'Status changed successfully!!.');
        } else {
            $response = array('status' => 'error', 'msg' => 'Not changed. Try again!!');
        }
        echo json_encode($response);
        exit;
    }

    public function create()
    {
        $categorydata = TrademarkCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.trademarks.create', ['catdata' => $categorydata]);
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'trademark_name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/trademark/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/trademark/' . $imageName));

            $trademark_insert                   = new Trademarks;
            $trademark_insert->directory_id     = 0;
            $trademark_insert->name             = $request->input('trademark_name');
            $trademark_insert->name_thumbnail   = $imageName;
            $trademark_insert->name_large       = $imageName;
            $trademark_insert->extension        = $ext;
            $trademark_insert->path             = $imagepath;
            $trademark_insert->category_id      = $request->input('tradecategory');
            $trademark_insert->icon_class      = $request->input('icon_class');
            $trademark_insert->save();
        }
        return redirect('admin/trademarks')->with('msg', 'Trademark Added Successfully!');
    }
    public function edit($id = 0)
    {
        $trademarkDetails = Trademarks::findOrFail($id);
        $categorydata = TrademarkCategory::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        return view('admin.trademarks.edit')->with(compact(['trademarkDetails', 'categorydata']));
    }

    public function update(Request $request, $id)
    {
        $updateArray = [
            'name'              => $request->input('trademark_name'),
            'category_id'       => $request->input('tradecategory')
        ];
        $imgfile = $request->file('image');
        if ($imgfile) {
            $ext = $imgfile->getClientOriginalExtension();
            $name2 = $imgfile->getClientOriginalName();
            $imageName2 = time() . $name2;
            $imageName = Helper::getSeoUrl($imageName2);
            $imagepath = 'files/trademark/' . $imageName;
            $image_resize = Image::make($imgfile->getRealPath());
            $image_resize->save(public_path('files/trademark/' . $imageName));
            $updateArray['name']                = $request->input('trademark_name');
            $updateArray['name_thumbnail']      = $imageName;
            $updateArray['name_large']          = $imageName;
            $updateArray['extension']           = $ext;
            $updateArray['path']                = $imagepath;
        }
        
        $updateArray['icon_class']          = $request->input('icon_class');
        Trademarks::where('id', $id)->update($updateArray);
        return redirect('admin/trademarks')->with('msg', 'Trademarks has been updated');
    }
    public function destroy($id)
    {
        Trademarks::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/trademarks')->with('msg', 'Trademarks deleted successfully');
    }
}
