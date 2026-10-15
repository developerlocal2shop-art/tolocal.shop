<?php

namespace App\View\Components;

use Illuminate\View\Component;

use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;

class ClientfilterShop extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    protected $productData;
    public function __construct($productData)
    {
        $this->productData = $productData;
    }

    public function render()
    {

        $language_id = Helper::getselectedLanguage();
        $defaultCurrency = Helper::defaultCurrencyData();
        if(session()->has('currency_code')){
            $currencycode = session()->get('currency_code')['currcode'];
        }else{
            $currencycode = $defaultCurrency[0]->code;
        }

        $data['categories']   = DB::table('product_category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                ->where('feature','yes')->where('deleted','0')->where('parent_id', 0)->limit(15)->orderby('sort_value','asc')
                                ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();

        $data['subcategories']   = DB::table('product_category as c')->select('c.*','ct.id as cat_trans_id','ct.category_id','ct.language_id','ct.title','ct.description as cat_trans_description','ct.seo_url','ct.created_by')
                                ->where('feature','yes')->where('deleted','0')->where('parent_id','!=',0)->orderby('id','desc')
                                ->leftjoin('category_translations as ct', 'ct.category_id', '=', 'c.id')->where("ct.language_id",$language_id)->get()->toArray();

        $data['maxprice'] = DB::table('product_price')->where('currency_code', $currencycode)->max('regular_price');
        $data['minprice'] = DB::table('product_price')->where('currency_code', $currencycode)->where('regular_price', '>', 0)->min('regular_price');

        $data['amenity'] = Helper::getAmenity();
        $data['producttags'] = DB::table('product_tags')->select('*')->where('status','Active')->get();
        $data['pData'] = $this->productData;

        return view('components.client.filter_side_shop',['data' => $data]);
    }
}
