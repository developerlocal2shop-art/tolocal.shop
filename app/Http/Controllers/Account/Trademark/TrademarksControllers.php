<?php

namespace App\Http\Controllers\Account\Trademark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Trademarks;
use App\Helpers\Helper;
use App\Models\TrademarkCategory;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrademarksControllers extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $cat_id = $request->input('category')?$request->input('category'):"";
            if($searchQuery!="" && $cat_id!=""){
                $data['trademarkscat'] = DB::table('trademarks')
                ->select('*')
                ->where('directory_id',Auth::user()->directory_id)
                ->where('name', 'like', '%'. $searchQuery .'%')
                ->Where('category_id',  $cat_id );
                $trademark =    $data['trademarkscat']->orderBy('id', 'desc')->paginate(20);
                $categorydata = TrademarkCategory::query()->where('deleted', '0')->where('directory_id',Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
                $data = isset($searchQuery)?$searchQuery:"";
                $searchCatId=isset($cat_id)?$cat_id:'';
                return view('account.company.trademarks.index', ['trademark' => $trademark,'data'=> $data,'catid'=> $searchCatId,'catdata' => $categorydata,'query'=> $query]);

            }else if($cat_id!=""){
                $data['trademarkscat'] = DB::table('trademarks')
                ->select('*')
                ->where('directory_id',Auth::user()->directory_id)
                ->where('category_id',  $cat_id );
                $trademark =    $data['trademarkscat']->orderBy('id', 'desc')->paginate(20);
                $categorydata = TrademarkCategory::query()->where('deleted', '0')->where('directory_id',Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
                $data = isset($searchQuery)?$searchQuery:"";
                $searchCatId=isset($cat_id)?$cat_id:'';
               return view('account.company.trademarks.index', ['trademark' => $trademark,'data'=> $data,'catid'=> $searchCatId,'catdata' => $categorydata,'query'=> $query]);
            }else{
                $data['trademarkscat'] = DB::table('trademarks')
                ->select('*')
                ->where('directory_id',Auth::user()->directory_id)
                ->where('name', 'like', '%'. $searchQuery .'%');
                $trademark =    $data['trademarkscat']->orderBy('id', 'desc')->paginate(20);
                $categorydata = TrademarkCategory::query()->where('deleted', '0')->where('directory_id',Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
                $data = isset($searchQuery)?$searchQuery:"";
                $searchCatId=isset($cat_id)?$cat_id:'';
               return view('account.company.trademarks.index', ['trademark' => $trademark,'data'=> $data,'catid'=> $searchCatId,'catdata' => $categorydata,'query'=> $query]);
            }
        } else {
            $trademark = Trademarks::where('deleted', '0')->where('directory_id',Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
            $categorydata = TrademarkCategory::query()->where('deleted', '0')->where('directory_id',Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
            $searchQuery = $request->input('searchtext')?$request->input('searchtext'):'';
            $data = isset($searchQuery)?$searchQuery:"";
            $searchCatId=isset($cat_id)?$cat_id:'';
            return view('account.company.trademarks.index', ['trademark' => $trademark,'data'=> $data,'catid'=> $searchCatId,'catdata' => $categorydata]);
        }
    }
    public function create()
    {
        if(Helper::getFeatureUses('trademarks') == false){
            return abort(404);
        }
        $categorydata = TrademarkCategory::query()->where('deleted', '0')->where('directory_id',Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
        return view('account.company.trademarks.create', ['catdata' => $categorydata]);
    }

    public function checktrademark(Request $request)
    {
        if($request->id){
            $exists = Trademarks::where('id', '!=', $request->id)->where('name', $request->trademark_name)->where('directory_id', Auth::user()->directory_id)->exists();
        }else{
            $exists = Trademarks::where('name', $request->trademark_name)->where('directory_id', Auth::user()->directory_id)->exists();
        }
        return response()->json(['exists' => $exists]);
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'trademark_name' => 'required',
            'tradecategory' => 'required',
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
            $image_resize->save(public_path('files/trademark/' .$imageName));
            $trademark_insert                   = new Trademarks;
            $trademark_insert->directory_id             = Auth::user()->directory_id;
            $trademark_insert->name             = $request->input('trademark_name');
            $trademark_insert->name_thumbnail   = $imageName;
            $trademark_insert->name_large       = $imageName;
            $trademark_insert->extension        = $ext;
            $trademark_insert->path             = $imagepath;
            $trademark_insert->category_id      = $request->input('tradecategory');
            $trademark_insert->status      = 1;
            $trademark_insert->save();
        }
        return redirect('company/trademarks')->with('msg', 'Trademark Added Successfully!');
    }
    public function edit($id = 0)
    {
        if(Helper::getFeatureUses('trademarks') == false){
            return abort(404);
        }
        $trademarkDetails = Trademarks::findOrFail($id);
        $categorydata = TrademarkCategory::query()->where('deleted', '0')->where('directory_id',Auth::user()->directory_id)->orderBy('id', 'desc')->paginate(20);
        return view('account.company.trademarks.edit')->with(compact(['trademarkDetails','categorydata']));
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
            $image_resize->save(public_path('files/trademark/' .$imageName));
            $updateArray['name']                = $request->input('trademark_name');
            $updateArray['name_thumbnail']      = $imageName;
            $updateArray['name_large']          = $imageName;
            $updateArray['extension']           = $ext;
            $updateArray['path']                = $imagepath;
        }
        Trademarks::where('id', $id)->update($updateArray);
        return redirect('company/trademarks')->with('msg', 'Trademarks has been updated');
    }
    public function destroy($id)
    {
        Trademarks::where('id', $id)->update(['deleted' => '1']);
        return redirect('company/trademarks')->with('msg', 'Trademarks deleted successfully');
    }
}
