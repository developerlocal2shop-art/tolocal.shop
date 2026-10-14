<?php

namespace App\Imports;

use App\Helpers\Helper;
use App\Models\Country;
use App\Models\Product;
use App\Models\ProductAttributes;
use App\Models\ProductCategories;
use App\Models\ProductPrice;
use App\Models\ProductTranslation;
use App\Models\VendorSupplierDetail;
use Exception;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\DiectoryLocation;

class ProductsImport_2_25 implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {

        set_time_limit(900);
        
        // Get vendor country name
        $get_vendor_location = DB::table('directory_location')->where('directory_id', Auth::user()->directory_id)->first();
        if($get_vendor_location){
            $country_id = $get_vendor_location->country_id;
        }else{
            $country_id = '';
        }
        $get_country = Country::findByIdOrName($country_id);
        if($get_country){
            $country = $get_country->name;
        }else{
            $userIP = $this->getIp();
            $country = '';
            if (in_array($userIP, ['127.0.0.1', '::1', 'localhost'])) {
                $country = 'Sweden';
            } else {
                $response = @file_get_contents('http://ip-api.com/php/'.$userIP);
                if ($response !== false) {
                    $geoData = @unserialize($response);
                    if ($geoData && isset($geoData['country'])) {
                        $country = $geoData['country'];
                    }
                }
            }
            if (empty($country)) {
                $country = 'Admin';
            }
        }

        $countryToCurrency = [
            'Sweden' => 'SEK',
            //GBP
            'United Kingdom' => 'GBP',
            'Guernsey' => 'GBP',
            'Isle of Man' => 'GBP',
            'Jersey' => 'GBP',
            'Saint Helena' => 'GBP',
            //PLN
            'Poland' => 'PLN',
            //DKK
            'Denmark' => 'DKK',
            'Greenland' => 'DKK',
            'Faroe Islands' => 'DKK',
            //NOK
            'Norway' => 'NOK',
            'Quisling regime' => 'NOK',
            //EUR
            'Andorra' => 'EUR',
            'Austria' => 'EUR',
            'Belgium' => 'EUR',
            'Croatia' => 'EUR',
            'Cyprus' => 'EUR',
            'Estonia' => 'EUR',
            'Finland' => 'EUR',
            'France' => 'EUR',
            'Germany' => 'EUR',
            'Greece' => 'EUR',
            'Ireland' => 'EUR',
            'Italy' => 'EUR',
            'Latvia' => 'EUR',
            'Lithuania' => 'EUR',
            'Luxembourg' => 'EUR',
            'Malta' => 'EUR',
            'Monaco' => 'EUR',
            'Montenegro' => 'EUR',
            'Netherlands' => 'EUR',
            'Portugal' => 'EUR',
            'San Marino' => 'EUR',
            'Slovakia' => 'EUR',
            'Slovenia' => 'EUR',
            'Spain' => 'EUR',
            //BGN
            'Bulgaria' => 'BGN',
            //HRK
            'Croatia' => 'HRK',
            //RON
            'Romania' => 'RON',
            //CZK
            'Czech Republic' => 'CZK',
            //HUF
            'Hungary' => 'HUF',
        ];
        $country_code = '';
        if (isset($countryToCurrency[$country])) {
            $country_code = $countryToCurrency[$country];
        }

        $languages = Helper::getAllLanguages();
        $currencydata = Helper::getAllCurrency();             /* get All Currency */
        $response_json = file_get_contents('https://api.exchangerate-api.com/v4/latest/'.$country_code);

        // get vendor lat and long
        if (isset($_COOKIE['latitude'])) {
            $cookie_latitude = $_COOKIE['latitude'];
            $cookie_longitude = $_COOKIE['longitude'];
        } 
        $dirlocationdetails = DiectoryLocation::where('directory_id', Auth::user()->directory_id)->first();
        if (!empty($dirlocationdetails)) {
            if($dirlocationdetails['latitude']!=0 && $dirlocationdetails['longitude']!=0){
                $latitude = $dirlocationdetails['latitude'];
                $longitude = $dirlocationdetails['longitude'];
            }else{
                $latitude = $cookie_latitude;
                $longitude = $cookie_longitude;
            }
        }else{
            $latitude = $cookie_latitude;
            $longitude = $cookie_longitude;
        }

        $i = 1;
            foreach ($rows as $row) {

                    $opening_dateval = $row['opening_date'] ?? '0';
                    $uniq_date = ($opening_dateval - 25569) * 86400;
                    $opening_date = gmdate('Y-m-d', $uniq_date);

                    $closing_date_val = $row['closing_date'] ?? '0';
                    $closing = ($closing_date_val - 25569) * 86400;
                    $closing_date = gmdate('Y-m-d', $closing);

                    $bin = Product::where('directory_id', Auth::user()->directory_id)->get();
                    $supplier_product_ena = $bin->pluck('ena');

                    if ($supplier_product_ena->contains($row['ena']) == false) {

                        $productshortinsert = new Product();
                        $productshortinsert->name             = $row['product_name'] ?? '';
                        $productshortinsert->seo_url          = Str::of($row['product_name'])->slug('-');
                        $productshortinsert->sku = $row['ena'] ?? '';
                        $productshortinsert->directory_id    = Auth::user()->directory_id;
                        if (Auth::user()->import_optional == 1) {
                            $productshortinsert->tags            = $row['tags_id_optional'] ?? '';
                        }
                        $productshortinsert->cat_id          = $row['category_id'] ?? '';
                        $productshortinsert->stock           = $row['stock'] ?? 0;
                        $productshortinsert->ena             = $row['ena'] ?? '';

                        $productshortinsert->latitude        = $latitude;
                        $productshortinsert->longitude       = $longitude;

                        $productshortinsert->supplier_name = $row['supplier_name'] ?? '';
                        $productshortinsert->is_visible_on_shop_detailpage = $row['display_supplier'] ?? '';
                        $productshortinsert->popular = $row['popular'] ?? '';
                        $productshortinsert->equal_product = $row['equal_product_code'] ?? '';
                        $productshortinsert->supplier_product_no = $row['supplier_product_no'] ?? '';
                        $productshortinsert->status             = 'Inactive';
                        $productshortinsert->closing_date            = $closing_date;
                        $productshortinsert->opening_date            = $opening_date;
                        $productshortinsert->deleted            = '0';
                        $productshortinsert->created_by        = Auth::user()->id;
                        $productshortinsert->save();
                        $product_id =  $productshortinsert->id;

                        //-------------------------------------------------------------------
                        //$languages = Helper::getAllLanguages();
                        foreach ($languages as $lang) {
                            $product_name = $row['product_name'] . $lang->code;
                            $product_short_content = $row['product_description_short'] ?? '';
                            $product_long_content = $row['product_description_long'] ?? '';
                            $product_meta_title = $row['product_name'] ?? '';
                            $product_meta_description = $row['product_description_short'] ?? '';

                            $productshortinsert = new ProductTranslation();
                            $productshortinsert->product_id = $product_id;
                            $productshortinsert->language_id = $lang->id;
                            $productshortinsert->name = $product_name ?? '';
                            $productshortinsert->short_content = $product_short_content;
                            $productshortinsert->long_content = $product_long_content;
                            $productshortinsert->meta_title = $product_meta_title;
                            $productshortinsert->meta_description = $product_meta_description;
                            $productshortinsert->meta_keyword = $product_short_content;
                            $productshortinsert->save();
                        }

                        //$currencydata = Helper::getAllCurrency(); /* get All Currency */
                        foreach ($currencydata as $currencyrow) {
                            // Fetch exchange rates from API
                            //$response_json = file_get_contents('https://api.exchangerate-api.com/v4/latest/SEK');
                            if(false !== $response_json) {
                                $response_object = json_decode($response_json);
                                $rates = [
                                    "BGN" => $response_object->rates->BGN ?? 1, // Set default rate to 1 if rate is not available
                                    "GBP" => $response_object->rates->GBP ?? 1,
                                    "PLN" => $response_object->rates->PLN ?? 1,
                                    "DKK" => $response_object->rates->DKK ?? 1,
                                    "NOK" => $response_object->rates->NOK ?? 1,
                                    "EUR" => $response_object->rates->EUR ?? 1,
                                    "SEK" => $response_object->rates->SEK ?? 1,
                                    "HRK" => $response_object->rates->HRK ?? 1,
                                    "RON" => $response_object->rates->RON ?? 1,
                                    "CZK" => $response_object->rates->CZK ?? 1,
                                    "HUF" => $response_object->rates->HUF ?? 1,
                                ];
                                $base_price = $row['regular_price_sek']??0; 
                                $selling_price_sek = $row['selling_price_sek']??0;
                                $result_price = $base_price; 
                                $result_selling_price = $selling_price_sek; 
                                if (isset($rates[$currencyrow->code])) {
                                    $result_price = round(($base_price * $rates[$currencyrow->code]), 2);
                                    $result_selling_price = round(($selling_price_sek * $rates[$currencyrow->code]), 2);
                                }
                                $productPriceInsertData                     = new ProductPrice;
                                $productPriceInsertData->currency_id        = $currencyrow->id;
                                $productPriceInsertData->currency_code      = $currencyrow->code;
                                $productPriceInsertData->currency_name      = $currencyrow->name;
                                $productPriceInsertData->currency_base      = $currencyrow->base_currency;
                                $productPriceInsertData->product_id         = $product_id;
                                $productPriceInsertData->sales_price        = $result_selling_price;
                                $productPriceInsertData->regular_price      = $result_price??0;
                                $productPriceInsertData->save();
                            }

                        }

                        if (Auth::user()->import_optional == 1 && !empty($row['child_category_id_optional'])) {
                            $categories = explode(',', $row['child_category_id_optional']);
                            foreach ($categories as $categoryId) {
                                ProductCategories::create([
                                    'product_id' => $product_id,
                                    'category_id' => $categoryId,
                                ]);
                            }
                        }
                        
                        /* Product Sub Category Ends */
                        $attributesId = $row['attribute_id'] ?? '';
                        $attributesVal = $row['attribute_value'] ?? '';
                        if (!empty($attributesId) && !empty($attributesVal)) {
                            $arr_attribute_id = explode('&', $row['attribute_id']);
                            $arr_attributesVal = explode('&', $row['attribute_value']);
                            $attributesData = array_combine($arr_attribute_id, $arr_attributesVal);
                            foreach ($attributesData as $key => $val) {
                                ProductAttributes::create([
                                    'product_id' =>  $product_id,
                                    'attribute_id' =>  $key,
                                    'attribute_value' =>  $val
                                ]);
                            }
                        }
                        
                        
                    } else {

                        $proid = Product::where('ena', $row['ena'])->where('directory_id', Auth::user()->directory_id)->first();
                        if ($proid) {
                            $product_id = $proid->id;
                        } else {
                            $product_id = 0;
                        }
                        if (Auth::user()->import_optional == 1) {
                            $tags_id_optional = $row['tags_id_optional'] ?? '';
                        } else {
                            $tags_id_optional = null;
                        }
                        $stock = $row['stock'] ?? '';

                            Product::where("id", $product_id)->update([
                                "name" => $row['product_name'] ?? '',
                                "seo_url" => Str::of($row['product_name'] ?? '')->slug('-'),
                                "sku" => $row['ena'] ?? '',
                                "directory_id" => Auth::user()->directory_id,
                                "tags" => $tags_id_optional,
                                'cat_id' =>  $row['category_id'] ?? '',
                                'stock' => $stock,
                                //'stock' => 0,
                                "supplier_name" => $row['supplier_name'] ?? '',
                                'is_visible_on_shop_detailpage' => $row['display_supplier'] ?? '',
                                'popular'       => $row['popular'] ?? 0,
                                "equal_product" => $row['equal_product_code'] ?? '',
                                "supplier_product_no" => $row['supplier_product_no'] ?? '',
                                "status" => 'Inactive',
                                "closing_date" => $closing_date,
                                "opening_date" => $opening_date,
                                "deleted" => '0',
                                "created_by" => Auth::user()->id
                            ]);
                        //}

                        ProductTranslation::where('product_id', $product_id)->delete();
                        foreach ($languages as $lang) {
                            $product_name = $row['product_name'] . $lang->code;
                            $product_short_content = $row['product_description_short'] ?? '';
                            $product_long_content = $row['product_description_long'] ?? '';
                            $product_meta_title = $row['product_name'] ?? '';
                            $product_meta_description = $row['product_description_short'] ?? '';

                            $productshortinsert = new ProductTranslation();
                            $productshortinsert->product_id = $product_id;
                            $productshortinsert->language_id = $lang->id;
                            $productshortinsert->name = $product_name ?? '';
                            $productshortinsert->short_content = $product_short_content;
                            $productshortinsert->long_content = $product_long_content;
                            $productshortinsert->meta_title = $product_meta_title;
                            $productshortinsert->meta_description = $product_meta_description;
                            $productshortinsert->meta_keyword = $product_short_content;
                            $productshortinsert->save();
                        }

                        ProductPrice::where('product_id', $product_id)->delete();
                        //$currencydata = Helper::getAllCurrency();             /* get All Currency */
                        foreach ($currencydata as $currencyrow) {
                            //$currencyrow_code = $row['selling_price_sek'] ?? '' . $currencyrow->code;
                            //$response_json = file_get_contents('https://api.exchangerate-api.com/v4/latest/'.$country_code);
                            if(false !== $response_json) {
                            $response_object = json_decode($response_json);
                            $rates = [
                                "BGN" => $response_object->rates->BGN ?? 1, // Set default rate to 1 if rate is not available
                                "GBP" => $response_object->rates->GBP ?? 1,
                                "PLN" => $response_object->rates->PLN ?? 1,
                                "DKK" => $response_object->rates->DKK ?? 1,
                                "NOK" => $response_object->rates->NOK ?? 1,
                                "EUR" => $response_object->rates->EUR ?? 1,
                                "SEK" => $response_object->rates->SEK ?? 1,
                                "HRK" => $response_object->rates->HRK ?? 1,
                                "RON" => $response_object->rates->RON ?? 1,
                                "CZK" => $response_object->rates->CZK ?? 1,
                                "HUF" => $response_object->rates->HUF ?? 1,
                            ];
                            
                            $base_price = $row['regular_price_sek']??0; 
                            $selling_price_sek = $row['selling_price_sek']??0;
                            $result_price = $base_price; 
                            $result_selling_price = $selling_price_sek; 
                            if (isset($rates[$currencyrow->code])) {
                                $result_price = round(($base_price * $rates[$currencyrow->code]), 2);
                                $result_selling_price = round(($selling_price_sek * $rates[$currencyrow->code]), 2);
                            }
                            $productPriceInsertData                     = new ProductPrice;
                            $productPriceInsertData->currency_id        = $currencyrow->id;
                            $productPriceInsertData->currency_code      = $currencyrow->code;
                            $productPriceInsertData->currency_name      = $currencyrow->name;
                            $productPriceInsertData->currency_base      = $currencyrow->base_currency;
                            $productPriceInsertData->product_id         = $product_id;
                            $productPriceInsertData->sales_price        = $result_selling_price;
                            $productPriceInsertData->regular_price      = $result_price;
                            $productPriceInsertData->save();
                            }
                        }

                        if (Auth::user()->import_optional == 1) {
                            /* Product Sub Category Starts */
                            if (!empty($row['child_category_id_optional'])) {
                                $categories = explode(',', $row['child_category_id_optional'] ?? '');
                            } else {
                                $categories = '';
                            }
                            ProductCategories::where('product_id', $product_id)->delete();
                            if (!empty($categories)) {
                                foreach ($categories as $pro => $value) {
                                    $productcategoryEditData = new ProductCategories;
                                    $productcategoryEditData->product_id  = $product_id;
                                    $productcategoryEditData->category_id  = $value;
                                    $productcategoryEditData->save();
                                }
                            }
                            /* Product Sub Category Ends */
                        }


                        $attributesId = $row['attribute_id'] ?? '';
                        $attributesVal = $row['attribute_value'] ?? '';
                        if (!empty($attributesId) && !empty($attributesVal)) {

                            ProductAttributes::whereIn('product_id', [$product_id])->delete();
                            $arr_attribute_id = explode('&', $row['attribute_id']);
                            $arr_attributesVal = explode('&', $row['attribute_value']);
                            $attributesData = array_combine($arr_attribute_id, $arr_attributesVal);
                            foreach ($attributesData as $key => $val) {
                                ProductAttributes::create([
                                    'product_id' =>  $product_id,
                                    'attribute_id' =>  $key,
                                    'attribute_value' =>  $val
                                ]);
                            }
                        }
                    }
                $i++;
            }
        }

        public function getIp(){
            foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
                if (array_key_exists($key, $_SERVER) === true){
                    foreach (explode(',', $_SERVER[$key]) as $ip){
                        $ip = trim($ip); // just to be safe
                        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                            return $ip;
                        }
                    }
                }
            }
            return request()->ip(); 
        }

}
