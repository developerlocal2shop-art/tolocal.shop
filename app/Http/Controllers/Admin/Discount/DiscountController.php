<?php

namespace App\Http\Controllers\Admin\Discount;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Voucher;
use App\Models\Giftvoucher;
use App\Models\FreeProductsDiscount;
use App\Models\Product;
use App\Models\Diectory;
use App\Helpers\Helper;
use Auth;

class DiscountController extends Controller
{
    public function index(Request $request)
    {
        $act_date = $request->input('searchdate') ? $request->input('searchdate') : "";
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $act_date = $request->input('searchdate') ? $request->input('searchdate') : "";
            $exp_date = $request->input('searchdateexpiry') ? $request->input('searchdateexpiry') : "";
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'voucher';
            $data['columns'] = ['voucher_name', 'voucher_code', 'discount_type'];
            $voucher = Helper::masterSearch($data);

            if ($act_date != "") {
                $datarow['searchDetails'] = DB::table('voucher')
                    ->select('*')
                    ->where('activation_date',  $act_date);
                $voucher =    $datarow['searchDetails']->orderBy('activation_date', 'asc')->paginate(20);
                $data = isset($searchQuery) ? $request->input('searchtext') : "";
                $searchDateAct = isset($act_date) ? $act_date : '';
                $searchDateExp = isset($exp_date) ? $exp_date : '';
                return view('admin.discount.voucher.index', ['voucher' => $voucher, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp, 'data' => $data, 'query' => $query]);
            } else if ($exp_date != "") {
                $datarow['searchDetails'] = DB::table('voucher')
                    ->select('*')
                    ->where('expiry_date',  $exp_date);

                $voucher =    $datarow['searchDetails']->orderBy('activation_date', 'asc')->paginate(10);
                $data = isset($searchQuery) ? $request->input('searchtext') : "";
                $searchDateExp = isset($exp_date) ? $exp_date : '';
                $searchDateAct = isset($act_date) ? $act_date : '';
                return view('admin.discount.voucher.index', ['voucher' => $voucher, 'searchDateExp' => $searchDateExp, 'searchDateAct' => $searchDateAct, 'data' => $data, 'query' => $query]);
            } else {

                $datarow['searchDetails'] = DB::table('voucher')
                    ->select('*')
                    ->where('voucher_name', 'like', '%' . $searchQuery . '%')->orWhere('voucher_code', 'LIKE', '%' . $searchQuery . '%')->orWhere('discount_type', 'LIKE', '%' . $searchQuery . '%');
                $voucher =    $datarow['searchDetails']->orderBy('activation_date', 'asc')->paginate(10);
                $data = isset($searchQuery) ? $request->input('searchtext') : "";
                $searchDateAct = isset($act_date) ? $act_date : '';
                $searchDateExp = isset($exp_date) ? $exp_date : '';
                return view('admin.discount.voucher.index', ['voucher' => $voucher, 'data' => $data, 'searchDateExp' => $searchDateExp, 'query' => $query, 'searchDateAct' => $searchDateAct]);
            }
            $voucher = $datarow['searchDetails']->orderBy('id', 'desc')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            $searchDateAct = isset($act_date) ? $act_date : '';
            $searchDateExp = isset($exp_date) ? $exp_date : '';
            return view('admin.discount.voucher.index', ['voucher' => $voucher, 'data' => $data, 'query' => $query, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp]);
        } else {
            $voucher = DB::table('voucher')->orderBy('id', 'DESC')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            $searchDateAct = isset($act_date) ? $act_date : '';
            $searchDateExp = isset($exp_date) ? $exp_date : '';
            return view('admin.discount.voucher.index', ['voucher' => $voucher, 'data' => $data, 'searchDateAct' => $searchDateAct, 'searchDateExp' => $searchDateExp]);
        }
    }
    public function create()
    {
        return view('admin.discount.voucher.create');
    }

    public function submitVoucher(Request $request)
    {
        $this->validate($request, [
            'voucher_name' => 'required|max:40',
            'voucher_code' => 'required|unique:voucher,voucher_code',
            'discount_type' => 'required',
            'expiry_date' => 'required',
            'activation_date' => 'required',
        ]);
        $voucher = new Voucher();
        $voucher->voucher_name                  = $request->input('voucher_name');
        $voucher->voucher_code                  = $request->input('voucher_code');
        $voucher->discount_type                 = $request->input('discount_type');
        if ($request->input('price_dis')) {
            $price_dis = $request->input('price_dis');
        } else {
            $price_dis = 0;
        }
        if ($request->input('dis_per')) {
            $dis_per = $request->input('dis_per');
        } else {
            $dis_per = 0;
        }
        if ($request->input('discount_type') == "fixeddiscount") {
            $voucher->discount_value               = $price_dis;
        }
        if ($request->input('discount_type') == "percentagediscount") {
            $voucher->discount_value               = $dis_per;
        }
        $voucher->minimum_amount                = $request->input('minimum_amount');
        $startdate = $request->input('expiry_date');
        $expiredate = $request->input('activation_date');
        $voucher->expiry_date                    = isset($startdate) ? $startdate : date("Y-m-d");
        $voucher->activation_date               = isset($expiredate) ? $expiredate : date("Y-m-d");
        $voucher->directory_id                  = $request->input('directoryId');
        $voucher->status                        = $request->input('status');
        $voucher->created_by                    = $request->input('createdby');
        $voucher->save();
        $voucher_id = $voucher->id;
        return redirect('admin/discount/voucher')->with('msg', 'Voucher Added Successfully!');
    }

    public function editVoucher($id = 0)
    {
        $data['voucherDetails']     = Voucher::findOrFail($id);
        return view('admin.discount.voucher.edit', ['data' => $data]);
    }

    public function updateVoucher(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'voucher_name' => 'required|max:40',
            'voucher_code' => 'required',
            'discount_type' => 'required',
            'expiry_date' => 'required',
            'activation_date' => 'required',
        ]);
        $voucher =  Voucher::findOrFail($id);
        $voucher->voucher_name                      = $request->input('voucher_name');
        $voucher->voucher_code                      = $request->input('voucher_code');
        $voucher->discount_type                     = $request->input('discount_type');
        if ($request->input('price_dis')) {
            $price_dis = $request->input('price_dis');
        } else {
            $price_dis = 0;
        }
        if ($request->input('dis_per')) {
            $dis_per = $request->input('dis_per');
        } else {
            $dis_per = 0;
        }
        if ($request->input('discount_type') == "fixeddiscount") {
            $voucher->discount_value               = $price_dis;
        }
        if ($request->input('discount_type') == "percentagediscount") {
            $voucher->discount_value               = $dis_per;
        }
        $voucher->minimum_amount                     = $request->input('minimum_amount');
        $startdate = $request->input('expiry_date');
        $expiredate = $request->input('activation_date');
        $voucher->expiry_date                         = isset($startdate) ? $startdate : date("Y-m-d");
        $voucher->activation_date                      = isset($expiredate) ? $expiredate : date("Y-m-d");
        $voucher->directory_id                       = $request->input('directoryId');
        $voucher->status                             = $request->input('status');
        $voucher->created_by                         = $request->input('createdby');
        $voucher->save();
        return redirect('admin/discount/voucher')->with('msg', 'Voucher has been updated');
    }

    public function destroyVoucher($id)
    {
        Voucher::find($id)->delete();
        return redirect('admin/discount/voucher')->with('msg', 'Voucher deleted successfully');
    }

    //Gift Voucher Function
    public function indexGiftVoucher(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'gift_voucher';
            $data['columns'] = ['voucher_name', 'voucher_code'];
            $gift_voucher = Helper::masterSearch($data);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.discount.giftvoucher.index', ['gift_voucher' => $gift_voucher, 'data' => $data, 'query' => $query]);
        } else {
            $gift_voucher = DB::table('gift_voucher')->orderBy('id', 'DESC')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";

            return view('admin.discount.giftvoucher.index', ['gift_voucher' => $gift_voucher, 'data' => $data]);
        }
    }

    public function createGiftVoucher()
    {
        return view('admin.discount.giftvoucher.create');
    }

    public function submitGiftVoucher(Request $request)
    {
        $this->validate($request, [
            'voucher_name' => 'required|max:40',
            'voucher_code' => 'required|unique:gift_voucher,voucher_code',
            'voucher_title' => 'required',
            'voucher_description' => 'required',
            'website_link' => 'required',
            'expiry_date' => 'required',
            'activation_date' => 'required',

        ]);
        $giftvoucher = new Giftvoucher();
        $giftvoucher->voucher_name                      = $request->input('voucher_name');
        $giftvoucher->voucher_code                      = $request->input('voucher_code');
        $giftvoucher->voucher_title                     = $request->input('voucher_title');
        $giftvoucher->voucher_description               = $request->input('voucher_description');
        $giftvoucher->minimum_amount                    = $request->input('minimum_amount');
        $giftvoucher->website_link                      = $request->input('website_link');
        $giftvoucher->directory_id                      = $request->input('directoryId');
        $startdate = $request->input('expiry_date');
        $expiredate = $request->input('activation_date');
        $giftvoucher->expiry_date                         = isset($startdate) ? $startdate : date("Y-m-d");
        $giftvoucher->activation_date                      = isset($expiredate) ? $expiredate : date("Y-m-d");
        $giftvoucher->created_by                         = $request->input('createdby');
        $giftvoucher->save();
        $giftvoucher_id = $giftvoucher->id;
        return redirect('admin/discount/giftvoucher')->with('msg', 'Gift Voucher Added Successfully!');
    }

    public function editGiftVoucher($id = 0)
    {
        $user = auth()->guard('webadmin')->user();
        if ($user->hasPermission('edit') == false) {
            abort(404);
        }
        $data['giftvoucherDetails']     = Giftvoucher::findOrFail($id);
        return view('admin.discount.giftvoucher.edit', ['data' => $data]);
    }

    public function updateGiftVoucher(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'voucher_name' => 'required|max:40',
            'voucher_code' => 'required',
            'voucher_title' => 'required',
            'voucher_description' => 'required',
            'website_link' => 'required',
            'expiry_date' => 'required',
            'activation_date' => 'required',
        ]);
        $giftvoucher =  Giftvoucher::findOrFail($id);
        $giftvoucher->voucher_name                      = $request->input('voucher_name');
        $giftvoucher->voucher_code                      = $request->input('voucher_code');
        $giftvoucher->voucher_title                     = $request->input('voucher_title');
        $giftvoucher->voucher_description               = $request->input('voucher_description');
        $giftvoucher->minimum_amount                    = $request->input('minimum_amount');
        $giftvoucher->website_link                      = $request->input('website_link');
        $giftvoucher->directory_id                      = $request->input('directoryId');
        $startdate = $request->input('expiry_date');
        $expiredate = $request->input('activation_date');
        $giftvoucher->expiry_date                         = isset($startdate) ? $startdate : date("Y-m-d");
        $giftvoucher->activation_date                      = isset($expiredate) ? $expiredate : date("Y-m-d");
        $giftvoucher->created_by                         = $request->input('createdby');
        $giftvoucher->save();
        return redirect('admin/discount/giftvoucher')->with('msg', 'Gift Voucher has been updated');
    }

    public function destroyGiftVoucher($id)
    {
        Giftvoucher::find($id)->delete();
        return redirect('admin/discount/giftvoucher')->with('msg', 'Gift Voucher deleted successfully');
    }

    //Free Products Discount
    public function indexProductDiscount(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->all();
            $searchQuery = $request->input('searchtext');
            $data['searchQuery'] = $request->input('searchtext');
            $data['table_name'] = 'free_products_discount';
            $data['columns'] = ['title'];
            $free_products_discount = Helper::masterSearch($data);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.discount.freeproductsdiscount.index', ['free_products_discount' => $free_products_discount, 'data' => $data, 'query' => $query]);
        } else {
            $free_products_discount = DB::table('free_products_discount')->orderBy('id', 'DESC')->paginate(10);
            $data = isset($searchQuery) ? $request->input('searchtext') : "";
            return view('admin.discount.freeproductsdiscount.index', ['free_products_discount' => $free_products_discount, 'data' => $data]);
        }
    }

    public function createProductDiscount()
    {
        $data['allnames']        = Helper::getProductNamesData();
        return view('admin.discount.freeproductsdiscount.create', ['data' => $data]);
    }

    public function submitProductDiscount(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:40',
            'description' => 'required',
            'minimum_amount' => 'required',
            'productId' => 'required|unique:free_products_discount,product_id',
            'free_productId' => 'required',
        ]);
        $productdiscount = new FreeProductsDiscount();
        $productdiscount->product_id              = $request->input('productId');
        $productdiscount->title                     = $request->input('title');
        $productdiscount->description               = $request->input('description');
        $productdiscount->directory_id              = $request->input('directoryId');
        $productlistNames = $request->input('free_productId');
        if ($productlistNames) {
            $productname_list = array();
            foreach ($productlistNames as $productsrow) {
                $productname_list[] = $productsrow;
            }
            $productdiscount->product_ids   =  implode(",", $productname_list);
        }
        $productdiscount->minimum_amount            = $request->input('minimum_amount');
        $productdiscount->created_by                = $request->input('createdby');
        $productdiscount->save();
        $productdiscount_id = $productdiscount->id;
        return redirect('admin/discount/freeproductsdiscount')->with('msg', 'Free Products Discount Added Successfully!');
    }

    public function editProductDiscount($id = 0)
    {
        $data['allnames']        = Helper::getProductNamesData();
        $data['productsDiscountDetails']     = FreeProductsDiscount::findOrFail($id);
        return view('admin.discount.freeproductsdiscount.edit', ['data' => $data]);
    }

    public function updateProductDiscount(Request $request, $id)
    {
        $updateData = $this->validate($request, [
            'title' => 'required|max:40',
            'description' => 'required',
            'minimum_amount' => 'required',
            'productId' => 'required',
            'free_productId' => 'required',
        ]);
        $productdiscount =  FreeProductsDiscount::findOrFail($id);
        $productdiscount->product_id              = $request->input('productId');
        $productdiscount->title                     = $request->input('title');
        $productdiscount->description               = $request->input('description');
        $productdiscount->directory_id              = $request->input('directoryId');
        $productlistNames = $request->input('free_productId');
        if ($productlistNames) {
            $productname_list = array();
            foreach ($productlistNames as $productsrow) {
                $productname_list[] = $productsrow;
            }
            $productdiscount->product_ids   =  implode(",", $productname_list);
        } else {
            $productdiscount->product_ids   =  0;
        }
        $productdiscount->minimum_amount            = $request->input('minimum_amount');
        $productdiscount->created_by                = $request->input('createdby');
        $productdiscount->save();
        return redirect('admin/discount/freeproductsdiscount')->with('msg', 'Free Products Discount has been updated');
    }

    public function destroyProductDiscount($id)
    {
        FreeProductsDiscount::find($id)->delete();
        return redirect('admin/discount/freeproductsdiscount')->with('msg', 'Free Products Discount deleted successfully');
    }

    public function getProduct(Request $request)
    {
        $data['productsdata'] = Product::where("directory_id", $request->directory_id)->get(["name", "id"]);
        return response()->json($data);
    }
}
