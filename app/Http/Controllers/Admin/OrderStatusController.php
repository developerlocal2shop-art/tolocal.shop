<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Order_status;
use Illuminate\Support\Str;

class OrderStatusController extends Controller
{
    public function index(Request $request)
    {

        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'order_statuses';
            $data['columns'] = ['name'];
            $order = Helper::masterSearch($data);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.orderstatus.index', ['order' => $order, 'data' => $data, 'query' => $query]);
        } else {
            $order = Order_status::orderBy('id', 'asc')->paginate(20);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";

            return view('admin.orderstatus.index', ['order' => $order, 'data' => $data]);
        }
    }

    public function create()
    {
        return view('admin.orderstatus.create');
    }

    public function submit(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required|unique:order_statuses',
        ]);
        $data = new Order_status();
        $data->name = $request->name;
        $data->slug = Str::slug($request->name);
        $data->active = '1';
        $data->save();
        return redirect()->route('admin.orderstatus.index')->with('msg', 'Order status has been created');
    }

    public function update(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'name' => 'required|unique:order_statuses,name,' . $id,
        ]);
        $data = ['name' => $request->name, 'slug' => Str::slug($request->name)];
        Order_status::whereId($id)->update($data);
        return redirect()->route('admin.orderstatus.index')->with('msg', 'Order status has been updated');
    }

    public function changeStatus(Request $request)
    {
        $review = Order_status::find($request->id);
        $review->active = $request->status;
        $review->save();
        return response()->json(['success' => 'Status change successfully.']);
    }

    public function edit($id = 0)
    {
        $data = Order_status::findOrFail($id);
        return view('admin.orderstatus.edit', compact('data'));
    }
}
