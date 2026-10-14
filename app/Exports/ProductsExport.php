<?php

namespace App\Exports;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductsExport implements FromCollection , WithHeadings
{
    protected $data;
    function __construct($data) {
            $this->fromdate = $data['fromdate'];
            $this->todate = $data['todate'];
    }

    public function collection()
    {
        $start_date = $this->fromdate;
        $to_date = $this->todate;
            if (!empty($start_date)) {
                $sdate = Carbon::parse($start_date)->toDateString();
                $todate = Carbon::parse($to_date)->toDateString();
            } else {
                $sdate = '';
                $todate = '';
            }

        $products = Product::with(['attributes', 'variations','price'])
            ->where('directory_id', Auth::user()->directory_id)
            ->where('deleted', '0')
            ->groupBy('name') 
            ->WhereBetween('product.created_at', [$sdate, $todate])
            ->get();

        $exportData = $products->map(function ($product) {
            // Combine attributes with "&" operator
            $attributeIds = $product->attributes->pluck('attribute_id')->filter()->implode('&');

            $attributeValues = $product->attributes->pluck('attribute_value')->implode('&');
            // Combine variations with "&" operator
            $variationValues = $product->variations->pluck('attribute_value')->implode('&');
            $variationStocks = $product->variations->pluck('v_stock')->implode('&');
            $variationEna    = $product->variations->pluck('v_ena')->implode('&');

            $sales_price = $product->price->sales_price;
            $regular_price = $product->price->regular_price;

            return [
                'Supplier Product No' => $product->supplier_product_no,
                'Supplier Name' => $product->supplier_name,
                'Visible on Shop Page' => $product->is_visible_on_shop_detailpage,
                'Equal Product Code' => $product->equal_product,
                'Product Name' => $product->name,
                'Short Description' => $product->short_content,
                'Long Description' => $product->long_content,
                'Unique Default ENA' => $product->ena,
                'Default Stock' => $product->stock,
                'Attribute ID' => $attributeIds,
                'Attribute Value' => $attributeValues,
                'Attribute Variations Value' => $variationValues,
                'Variations Stock' => $variationStocks,
                'Variations ENA' => $variationEna,
                'Regular price' => $regular_price,
                'Sales price' => $sales_price,
                'Popular' => $product->popular,
                'Opening date' => $product->opening_date,
                'Closing date' => $product->closing_date,
                'Category Id' => $product->cat_id,
                'Tags' => $product->tags,
                'Status' => $product->status,
            ];
        });
        return $exportData;
    //     $prodata = Product::leftjoin('product_price', 'product_price.product_id', '=', 'product.id')
    //             ->leftjoin('product_translation', 'product_translation.product_id', '=', 'product.id')
    //             ->leftjoin('product_attributes', 'product_attributes.product_id', '=', 'product.id')
    //             ->leftjoin('product_attributes_variations', 'product_attributes_variations.product_id', '=', 'product.id')
    //             ->select(
    //                 'product.supplier_product_no',
    //                 'product.supplier_name',
    //                 'product.is_visible_on_shop_detailpage',
    //                 'product.equal_product',
    //                 'product.name',
    //                 'product_translation.short_content as short_description',
    //                 'product_translation.long_content as long_description',
    //                 'product.ena',
    //                 'product.stock',
    //                 'product_attributes.attribute_id',
    //                 'product_attributes.attribute_value',
    //                 'product_attributes_variations.attribute_value as variation_value',
    //                 'product_attributes_variations.v_stock as variation_stock',
    //                 'product_attributes_variations.v_ena as variation_ena',
    //                 'product_price.regular_price',
    //                 'product_price.sales_price',
    //                 'product.popular', 
    //                 'product.opening_date', 
    //                 'product.closing_date',
    //                 'product.cat_id',
    //                 'product.tags',
    //                 'product.status', 
    //             )
    //             ->WhereBetween('product.created_at', [$sdate, $todate])
    //             ->where('product.directory_id', Auth::user()->directory_id)
    //             ->where('product.deleted', '0')
    //             ->groupBy('product.name') 
    //             ->get();     
    //    return $prodata;


    }
    public function headings() : array
    { 
        return [
            'Supplier Product No',
            'Supplier name',
            'Display Supplier',
            'Equal Product Code',
            'Product name',
            'Product Description Short',
            'Product Description Long',
            'Unique Default ENA',
            'Default Stock',
            'Attribute Id',
            'Attribute Value',
            'Attribute Variations value',
            'Variations Stock',
            'Variations ENA',
            'Regular Price',
            'Selling Price',
            'Popular',
            'Opening Date',
            'Closing Date',
            'Category Id',
            'Tags Id (Optional)',
            'Status',
        ]; 
    } 

}
