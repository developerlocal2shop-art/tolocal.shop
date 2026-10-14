<?php

namespace App\Http\Controllers\Admin\Trademark;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TrademarkCategory;
use App\Helpers\Helper;

class TrademarkCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            
            $searchQuery = $request->input('searchtext');
            if($searchQuery == 'admin' || $searchQuery == 'Admin'){
                $trademarkcategory = TrademarkCategory::where('deleted', '0')->where('directory_id', 0)->orderBy('id', 'desc')->paginate(20);
            }elseif($searchQuery == 'vendor' || $searchQuery == 'Vendor'){
                $trademarkcategory = TrademarkCategory::where('deleted', '0')->where('directory_id', '!=', 0)->orderBy('id', 'desc')->paginate(20);
            }else{
                $data['searchQuery'] = $request->input('searchtext');
                $data['table_name'] = 'trademark_category';
                $data['columns'] = ['name', 'status'];
                $trademarkcategory = Helper::masterSearch($data);
            }
        } else {
            $trademarkcategory = TrademarkCategory::where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
        }
        $searchQuery = $request->input('searchtext') ? $request->input('searchtext') : '';
        $data = isset($searchQuery) ? $searchQuery : "";
        return view('admin.trademarkcategory.index', ['trademarkcategory' => $trademarkcategory, 'data' => $data]);
    }

    public function create()
    {
        return view('admin.trademarkcategory.create');
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'name'  => 'required',
            'description' => 'required',
        ]);
        $tradecat = new TrademarkCategory;
        $tradecat->directory_id = 0;
        $tradecat->name = $request->input('name');
        $tradecat->description = $request->input('description');
        $tradecat->save();
        return redirect('admin/trademarks-category')->with('msg', 'Trademark Category Added Successfully!');
    }
    public function edit($id = 0)
    {
        $tradeDetails = TrademarkCategory::findOrFail($id);
        return view('admin.trademarkcategory.edit')->with(compact(['tradeDetails']));
    }

    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name'  => 'required',
            'description' => 'required'
        ]);
        TrademarkCategory::where('id', $id)
            ->update([
                'name' => $request->input('name'),
                'description' => $request->input('description')
            ]);
        return redirect('admin/trademarks-category')->with('msg', 'Trademark Category has been updated');
    }
    public function destroy($id)
    {
        TrademarkCategory::where('id', $id)->update(['deleted' => '1']);
        return redirect('admin/trademarks-category')->with('msg', 'Trademark Category deleted successfully');
    }
}
