<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use DB;
use App\Models\Product;
use App\Models\Inventory;

class InventoryControllers extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $sort = $request->input('sort') ? $request->input('sort') : "";
            $data['searchQuery'] = $searchQuery;
            $data['table_name'] = 'product';
            $data['columns'] = ['name', 'sku'];
            $data['productdata'] = Helper::masterSearch($data);
            if ($sort != "") {
                if ($sort == "outofstock") {
                    $data['productdata'] = Product::query()->where('deleted', '0')->where('outofstock', $sort)->orderBy('outofstock', 'asc')->paginate(20);
                    $searchSort = isset($sort) ? $sort : '';
                    $searchdata = isset($searchQuery) ? $request->input('searchtext') : "";
                    return view('admin.inventory.index', ['productData' => $data['productdata'], 'sortproduct' => $searchSort, 'searchdata' => $searchdata, 'query' => $query]);
                } else if ($sort == "instock") {
                    $data['productdata'] = Product::query()->where('deleted', '0')->where('outofstock', NULL)->orderBy('stock', 'asc')->paginate(20);
                    $searchSort = isset($sort) ? $sort : '';
                    $searchdata = isset($searchQuery) ? $request->input('searchtext') : "";
                    return view('admin.inventory.index', ['productData' => $data['productdata'], 'sortproduct' => $searchSort, 'searchdata' => $searchdata, 'query' => $query]);
                } else {
                    $data['productdata'] = Product::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
                    $searchSort = isset($sort) ? $sort : '';
                    $searchdata = isset($searchQuery) ? $request->input('searchtext') : "";
                    return view('admin.inventory.index', ['productData' => $data['productdata'], 'sortproduct' => $searchSort, 'searchdata' => $searchdata, 'query' => $query]);
                }
            }
            $searchdata = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.inventory.index', ['productData' => $data['productdata'], 'searchdata' => $searchdata, 'query' => $query]);
        } else {
            $data['productdata'] = Product::query()->where('deleted', '0')->orderBy('id', 'desc')->paginate(20);
            $searchdata = isset($searchQuery) ? $request->input('searchtext') : "";
            $searchSort = isset($sort) ? $sort : '';
            return view('admin.inventory.index', ['productData' => $data['productdata'], 'sortproduct' => $searchSort, 'searchdata' => $searchdata]);
        }
    }
    public function update(Request $request, $product_id)
    {
        Product::where('id', $product_id)
            ->update([
                'stock'             => $request->input('stock'),
                'outofstock'        => $request->input('outstock')
            ]);
        return redirect('admin/inventory/')->with('msg', 'Product has been updated');
    }
}
