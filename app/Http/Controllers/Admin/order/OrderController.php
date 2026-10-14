<?php

namespace App\Http\Controllers\Admin\Order;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'order';
            $data['columns'] = ['first_name', 'last_name', 'phone'];
            $order = Helper::masterSearch($data);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.order.index', ['order' => $order, 'data' => $data, 'query' => $query]);
        } else {
            $order = DB::table('order')->orderBy('id', 'desc')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.order.index', ['order' => $order, 'data' => $data]);
        }
    }

}
