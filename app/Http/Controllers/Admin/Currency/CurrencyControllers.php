<?php

namespace App\Http\Controllers\Admin\Currency;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Currency;
use App\Models\ProductPrice;
use App\Helpers\Helper;

class CurrencyControllers extends Controller
{
    public function index(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'currency';
            $data['columns'] = ['name', 'code'];
            $currency = Helper::masterSearch($data);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.currency.index', ['addedcurrency' => $currency, 'data' => $data, 'query' => $query]);
        } else {
            $currency = Currency::orderBy('id', 'desc')->paginate(20);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.currency.index', ['addedcurrency' => $currency, 'data' => $data]);
        }
    }
    public function create()
    {
        return view('admin.currency.create');
    }
    public function submit(Request $request)
    {
        $this->validate($request, [
            'currency_code'     => 'unique:currency,code',
            'currency_name'     => 'unique:currency,name',
            'currency_symbol'   => 'unique:currency,symbol'
        ]);
        $radiocurrency = $request->input('base_currency');
        if ($radiocurrency == "yes") {
            Currency::query()->update(['base_currency' => "no",]);
        }
        $currency_insert = new Currency;
        $currency_insert->name              = $request->input('currency_name');
        $currency_insert->code              = $request->input('currency_code');
        $currency_insert->symbol            = $request->input('currency_symbol');
        $currency_insert->base_currency     = $radiocurrency;
        $currency_insert->status            = $request->input('status');
        $currency_insert->save();
        return redirect('admin/currency')->with('msg', 'Currency Added Successfully!');
    }
    public function edit($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $currencyDetails = Currency::findOrFail($id);
        return view('admin.currency.edit')->with(compact(['currencyDetails']));
    }
    public function update(Request $request, $id)
    {
        $radiocurrency = $request->input('base_currency');
        if ($radiocurrency == "yes") {
            Currency::query()->update(['base_currency' => "no",]);
        }
        Currency::where('id', $id)
            ->update([
                'name'          => $request->input('currency_name'),
                'code'          => $request->input('currency_code'),
                'symbol'        => $request->input('currency_symbol'),
                'status'        => $request->input('status'),
                'base_currency' => $radiocurrency,
            ]);            
        ProductPrice::where('currency_name',$request->input('currency_name'))->update(['currency_code' => $request->input('currency_code')]);
        Helper::productCurrencyChange();
        return redirect('admin/currency')->with('msg', 'Currency has been updated');
    }
    public function destroy($id)
    {
        Currency::where('id', $id)->delete();
        return redirect('admin/currency')->with('msg', 'Currency deleted successfully');
    }
}
