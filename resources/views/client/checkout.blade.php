@extends('layouts.client.contact')
@section('title','Cart | ToLocal.shop ')
@section('content')
</a>
<?php
$defaultCurrency = Helper::defaultCurrencyData();
if(session()->has('currency_code')){
	$currencySymbol = session()->get('currency_code')['currsymbol'];
	$currencycode = session()->get('currency_code')['currcode'];
}else{
	$currencySymbol = $defaultCurrency[0]->symbol;
	$currencycode = $defaultCurrency[0]->code;
}
$taxValue = Helper::taxValueData();
 $adminFlatShippingData = Helper::getAdminFlatShippingData();
 $shippingValue = $adminFlatShippingData->shipping_value??0;
 $flatRateValue = $adminFlatShippingData->flat_rate??0;

 $geoData = Helper::userCurrentLocationDetailsOnIpAddress($userIP);
 $country = $geoData['country'] ?? 'Sweden';
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
            'India' => 'IN',
        ];

        $country_code = '';
        if (isset($countryToCurrency[$country])) {
            $country_code = $countryToCurrency[$country];
        }
        if($country_code){
        $is_country_exist = 1;
        }else{
        $is_country_exist = 1;
        }
?>

<div class="sub_header_in sticky_header filter_sub_blue_header" id="results">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-5 col-3">
                <h4>{{__("message.checkout_title")}}</h4>
            </div>
            @if(auth()->guard('web')->check()!=0)
            <div class="col-lg-6 col-md-7 col-9">
                <h4 class="text-right">{{__("message.checking_out_subtitle")}} {{ auth()->guard('web')->user()->email }}
                </h4>
            </div>
            @endif
        </div>
    </div>
</div>
@if(session()->get('stripe_error'))
<div class="alert alert-danger">
    {{ session()->get('stripe_error') }}
</div><br />
@endif
<div class="container margin_60_35">
    <div class="row justify-content-center">
        {{-- @if(auth()->guard('web')->check()==0)

        <x-client.login />
        <x-client.register />
        @endif --}}
        @if(auth()->guard('web')->check()!=0)
        <div class="col-xl-6 col-lg-6 pr-xl-5">
            <?php if($addressData){ ?>
            <div class="form-group">
                <label>Select a delivery address</label>
                <select class="form-control" id="getchangeAddressData" onChange="getchangeAddressData(this)">
                    <?php foreach($addressData as $addrow){ ?>
                    <option value="{{ $addrow['id'] }}" {{ $addrow['default_address']=='yes' ?"selected":""; }}>{{
                        $addrow['address1'].', '.$addrow['address2'] }}</option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>

            <div class="main_title_3">
                <span></span>
                <h2>{{__("message.shipping_address")}}</h2>
                <p style="font-size: 15px;">{{__("message.enter_shipping_address")}}</p>
            </div>
            <form method="post" action="" role="form" class="address-validation" id="checkoutaddress-form"
                autocomplete="off">
                @csrf
                <input class="form-control" type="hidden" id="type" name="type" value="shipping">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("message.first_name_text")}}<span style="color:red;">*</span></label>
                            <input class="form-control" type="text" id="first_name" name="first_name"
                                value="{{ old('first_name',auth()->guard('web')->user()->name??'') }}"
                                autocomplete="off">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("message.last_name_text")}}<span style="color:red;">*</span></label>
                            <input class="form-control" type="text" id="last_name"
                                value="{{ old('last_name',auth()->guard('web')->user()->last_name??'') }}"
                                name="last_name" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>{{__("message.email_text")}}<span style="color:red;">*</span></label>
                    <input class="form-control" type="email" id="email"
                        value="{{ old('email',auth()->guard('web')->user()->email??'') }}" name="email"
                        autocomplete="off">
                </div>
                <div class="form-group">
                    <label>{{__("message.address1_text")}}<span style="color:red;">*</span></label>
                    <input class="form-control " type="text" id="address1"
                        value="{{ old('address1', $defaultaddressData['address1']??'') }}" name="address1"
                        autocomplete="off">
                </div>
                <div class="form-group">
                    <label>{{__("message.address2_text")}}<span style="color:red;">*</span></label>
                    <input class="form-control" type="text" id="address2"
                        value="{{ old('address2', $defaultaddressData['address2']??'') }}" name="address2"
                        autocomplete="off">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        @php
                        if($defaultaddressData == null){
                        $dcountry = 0;
                        }else{
                        $dcountry = $defaultaddressData['country'];
                        }
                        @endphp
                        <div class="form-group">
                            <label>{{__("message.country_text")}}<span style="color:red;">*</span></label>
                            <select class="form-control" id="country" name="country" id="country">
                                <option value=""> Select Country </option>
                                @foreach($countries as $countryrow)
                                <option @if($countryrow->id==old('country_id')) selected @endif
                                    @if($countryrow->id==$dcountry) selected @endif value="{{ $countryrow->id }}"> {{
                                    $countryrow->name }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>{{__("message.state_text")}}<span style="color:red;">*</span></label>
                            <select class="form-control" id="state" name="state" id="state">
                                <option value="">Select State</option>
                                @foreach($stateSelected as $stateSelectedrow)
                                <option @if($stateSelectedrow['id']==$defaultaddressData['state']) selected @endif
                                    value="{{ $stateSelectedrow['id'] }}"> {{ $stateSelectedrow['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group" id="city_name" style="display: none">
                    <label>{{__("message.city_text")}}<span style="color:red;">*</span></label>
                    <input class="form-control" type="text" id="cityname" name="city-name" value="">
                </div>
                <div class="form-group" id="city_dropdown">
                    <label style="display: inline-flex;">{{__("message.city_text")}}<span class="notranslate"
                            style="color:red;">*</span>
                        <div id="tooltip">
                            <span id="tooltipText">"If your city is not in list please select nearest city".</span>
                            <span><i class="fas fa-info-circle" style="color:#3c3c3e"></i></span>
                        </div>
                    </label>
                    <select class="form-control" name="city" id="city">
                        <option value="">Select City</option>
                        @foreach($citySelected as $citySelectedrow)
                        <option @if($citySelectedrow['id']==$defaultaddressData['city_id']) selected @endif
                            value="{{ $citySelectedrow['id'] }}"> {{ $citySelectedrow['name'] }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-8 isdmobile-hide">
                        <div class="form-group">
                            <label>{{__("message.mobile_text")}}<span style="color:red;">*</span></label>
                            <div class="row">
                                <div class="col-md-4">

                                    <input class="form-control" type="text" id="countrycode"
                                        value="{{ $countryPhone->phonecode??'' }}" name="countrycode">
                                </div>
                                <div class="col-md-8">
                                    <input class="form-control" type="text" id="phone_contact"
                                        value="{{ old('phone_contact',auth()->guard('web')->user()->phone??'') }}"
                                        name="phone">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mobile-isd-view">
                        <div class="form-group">
                            <label>{{__("message.mobile_text")}}<span style="color:red;">*</span></label>
                            <div class="row">
                                <div class="col-md-2" style="display: flex; width: 32%; padding-right: 0px;">

                                    <input class="form-control" readonly type="text" id="countrycode_m" value=""
                                        name="countrycode">
                                </div>
                                <div class="col-md-10" style="width: 68%; padding-left: 0px;">
                                    <input class="form-control" type="text" id="phone_contact"
                                        value="{{ old('phone_contact',auth()->guard('web')->user()->phone??'') }}"
                                        name="phone"
                                        style="border-top-left-radius: unset;border-bottom-left-radius: unset;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>{{__("message.zip_code_text")}}<span style="color:red;">*</span></label>
                            <input class="form-control" type="text" id="zip_code"
                                value="{{ old('zip_code', $defaultaddressData['zipcode']??'') }}" name="zip_code">
                        </div>
                    </div>
                </div>
                <div class="custom-control custom-control2">
                    <input type="checkbox" id="samebillingaddress" class="form-check-input" checked value="yes"
                        name="sameBillingAddress">
                    <label>{{__("message.check_box_text")}}</label>
                    <br>
                </div>
                <div id="billing_add">
                    <div class="main_title_3">
                        <span></span>
                        <h2>{{__("message.billing_address")}}</h2>
                        <p style="font-size: 15px;">{{__("message.enter_billing_address_text")}}</p>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__("message.first_name_text")}}</label>
                                <input class="form-control" type="text" id="billing_first_name"
                                    name="billing_first_name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__("message.last_name_text")}}</label>
                                <input class="form-control" type="text" id="billing_last_name" name="billing_last_name">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__("message.email_text")}}</label>
                        <input class="form-control" type="email" id="billing_email" name="billing_email">
                    </div>
                    <div class="form-group">
                        <label>{{__("message.address1_text")}}</label>
                        <input class="form-control" type="text" id="billing_address1" name="billing_address1">
                    </div>
                    <div class="form-group">
                        <label>{{__("message.address2_text")}}</label>
                        <input class="form-control" type="text" id="billing_address2" name="billing_address2">
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__("message.country_text")}}</label>
                                <select class="form-control" name="billing_country" id="billing_country">
                                    <option value=""> Select Country </option>
                                    @foreach($countries as $countryrow)
                                    <option @if($countryrow->id==old('country_id')) selected @endif value="{{
                                        $countryrow->id }}"> {{ $countryrow->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>{{__("message.state_text")}}</label>
                                <!-- <input class="form-control" type="text" id="billing_state" name="billing_city"> -->
                                <select class="form-control" name="billing_state" id="billing_state">
                                    <option value="">Select State</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{{__("message.city_text")}}</label>
                        <!-- <input class="form-control" type="text" id="billing_city" name="billing_city"> -->

                        <select class="form-control" name="billing_city" id="billing_city">
                            <option value="">Select City</option>
                        </select>

                    </div>
                    <!-- <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__("message.mobile_text")}}</label>
                                    <input class="form-control" type="text" id="countrycode2">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>- </label>
                                    <input class="form-control" type="text" id="billing_phone_contact" name="billing_phone_contact">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>{{__("message.zip_code_text")}}</label>
                                    <input class="form-control" type="text" id="billing_zip_code" name="billing_zip_code">
                                </div>
                            </div>
                        </div> -->
                    <div class="row">
                        <div class="col-md-8 isdmobile-hide">
                            <div class="form-group">
                                <label>{{__("message.mobile_text")}}</label>
                                <div class="row">
                                    <div class="col-md-4">

                                        <input class="form-control" type="text" id="countrycode2">
                                    </div>
                                    <div class="col-md-8">
                                        <input class="form-control" type="text" id="billing_phone_contact" value=""
                                            name="billing_phone_contact">
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-8 mobile-isd-view">
                            <div class="form-group">
                                <label>{{__("message.mobile_text")}}</label>
                                <div class="row">
                                    <div class="col-md-2" style="display: flex; width: 32%; padding-right: 0px;">

                                        <input class="form-control" type="text" readonly id="billingcountrycode_m">
                                    </div>
                                    <div class="col-md-10" style="width: 68%; padding-left: 0px;">
                                        <input class="form-control" type="text" id="billing_phone_contact" value=""
                                            name="billing_phone_contact"
                                            style="border-top-left-radius: unset;border-bottom-left-radius: unset;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>{{__("message.zip_code_text")}}</label>
                                <input class="form-control" type="text" id="billing_zip_code" value=""
                                    name="billing_zip_code">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 form-group">
                    @if($is_country_exist == 1)
                        <button class="btn btn-warning btn-lg btn-block" id="address_place" type="submit">
                            {{__("message.place_order_text")}}
                        </button>
                    @else
                        <a class="btn btn-warning btn-lg btn-block"
                        onclick="confirmAlert()">
                        {{ __('message.place_order_text') }}
                        </a>
                    @endif

                </div>
            </form>
        </div>

        <div class="col-xl-6 col-lg-6 pl-xl-5">
            <form method="post" action="{{ route('order.place') }}" role="form" class="require-validation"
                data-cc-on-file="false" id="payment-form"
                autocomplete="off">

            {{-- <form method="post" action="{{ route('order.place') }}" role="form" class="require-validation"
                data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form"
                autocomplete="off"> --}}

                @csrf
                <div class="main_title_3">
                    <span></span>
                    <h2>{{__("message.order_summary_text")}}</h2>
                </div>
                <table id="cart" class="table table-hover table-condensed imgdetails-mobile-alignment">
                    <thead>
                        <tr>
                            <th style="width:40%">{{__("message.items_text")}}</th>
                            <th style="width:60%"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0 @endphp
                        @if(session('cart'))
                        @foreach(session('cart') as $id => $details)
                        @php $total += (float)$details['price'] * $details['quantity'] @endphp
                        <tr data-id="{{ $id }}">
                            <td data-th="Product">
                                <div class="row">
                                    <div class="col-md-3 hidden-xs">
                                        @if($details['image'])
                                        <img src="{{ env('IMAGE_PATH').'images/product/'.$details['image'].'.webp' }}"
                                            width="100" height="100" class="img-responsive" />
                                        @else
                                        <img src="{{ env('IMAGE_PATH').'assets/plugins/images/placeholder.png' }}"
                                            width="100" height="100" class="img-responsive" />
                                        @endif
                                        @php
                                        $free_product_id = json_decode($details['free_product_data'])->id??0;
                                        $getFreeProductDirectoryId =
                                        Helper::getFreeProductDirectoryId($free_product_id);
                                        $subtotallprice = (float)$details['price'] * $details['quantity'];
                                        $minimum_amount = $getFreeProductDirectoryId[0]->minimum_amount??0;
                                        @endphp
                                        @if($subtotallprice > $minimum_amount)
                                        @if(count($getFreeProductDirectoryId[1]) > 0)
                                        <h6>Free Products</h6>
                                        <ul class="freeProList">
                                            @foreach($getFreeProductDirectoryId[1] as $freeProduct)
                                            @foreach($freeProduct as $freePro)
                                            <li>
                                                <?php $imagename = Helper::getProductImage($freePro->id); ?>
                                                <?php if(!empty($imagename[0]->name_thumbnail)){ ?>
                                                <img src="{{ env('IMAGE_PATH').'images/product/'.$imagename[0]->name_thumbnail.'.webp' }}"
                                                    style="width: 40px;height: 40px;" />
                                                <?php }else{ ?>
                                                <img src="{{ env('IMAGE_PATH').'assets/plugins/images/placeholder.png' }}"
                                                    style="width: 40px;height: 40px;" />
                                                <?php } ?>
                                                <b>{{ $freePro->name??'' }}</b>
                                            </li>
                                            @endforeach
                                            @endforeach
                                        </ul>
                                        @endif
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td data-th="Price" class="text-right imgdetails-alignment">
                                {{ $details['name'] }} x {{$details['quantity']}}</br>
                                @if(!empty($details['product_number']))
                                <span style="font-size:14px; color:#555;">Product No: {{ $details['product_number']
                                    }}</span>
                                @endif
                                <p>
                                    @if(!empty($details['attr_name']))
                                    @php
                                    $attr_name=$details['attr_name'];
                                    $attr_value=$details['attr_value'];
                                    $c_attr_value=array_combine($attr_name,$attr_value);
                                    @endphp
                                    @foreach ($c_attr_value as $key => $attrval)
                                    {{ $key }} : {{ $attrval }}<br>
                                    @endforeach
                                    <hr>
                                    @endif
                                </p>
                                <p>
                                    @if($details['offer_dicount_type'])
                                    @if($details['offer_dicount_type'] == 'fixed')
                                    Offer - {{ $details['offer'] }}
                                    @else
                                    Offer - {{ $details['offer'] }}
                                    @endif
                                    @endif
                                    @if($details['shopping_dicount_type'])
                                    @if($details['shopping_dicount_type'] == 'percentage')
                                    Shopping Card - {{ $details['shoppingcard'] }}%
                                    @else
                                    Shopping Card - {{ $details['shoppingcard'] }}
                                    @endif
                                    @endif
                                </p>
                                <p>{{__("message.subtotal_text")}} : <span class="notranslate"> {{ $currencySymbol."
                                        ".(float)$details['price'] * $details['quantity'] }}</span></p>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <?php
                                if(isset($couponData['couponcode'])){
                                    $subtotal = round($couponData['subtotal']);
                                    $taxval = ($subtotal)*($taxValue[0]->taxval/100);
                                    $discountvalue = round($couponData['totaldiscount']);
                                    $total = round(($subtotal+($taxval)+($shippingValue)+($flatRateValue))-($discountvalue));
                                }else{
                                    $subtotal = $total;
                                    $taxval = ($total)*($taxValue[0]->taxval/100);
                                    $total = round($total+($taxval)+($shippingValue)+($flatRateValue));
                                }
                                ?>
                        <?php if(isset($discountvalue)){ ?>
                        <tr>
                            <td colspan="2" class="text-right">
                                <strong><span class="checkout-text">{{__("message.subtotal_text")}} including 25% tax:</span>
                                    <span class="notranslate">{{ $currencySymbol." ".$subtotal }}</span></strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">
                                <strong><span class="checkout-text">
                                        {{__("Flat Rate")}} :</span> <span class="notranslate">{{ $currencySymbol."
                                        ".$flatRateValue }}.00</span>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">
                                <strong><span class="checkout-text">{{__("Shipping Rate")}} :</span>
                                    <span class="notranslate">{{ $currencySymbol }}</span> <span id="shippingValue">{{
                                        $shippingValue }}</span>.00
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">
                                <strong><span class="checkout-text">{{__("message.discount_text")}}</span> <span
                                        class="notranslate">{{ $currencySymbol." ".$discountvalue }}</span></strong>
                            </td>
                        </tr>
                        <tr>
                            <input class="form-control total_tax" type="hidden" name="total_tax" value="{{$taxval}}">
                            <input class="form-control total_amount" type="hidden" name="total_amount"
                                value="{{$total}}">
                            <input class="shipping_rate_value" type="hidden" name="shipping_rate_value"
                                value="{{$shippingValue}}">
                            <input class="flat_rate_value" type="hidden" name="flat_rate_value"
                                value="{{$flatRateValue}}">
                            <td colspan="2" class="text-right"><strong><span
                                        class="checkout-text">{{__("message.grand_total_text")}}</span>
                                    <span class="notranslate">{{ $currencySymbol }}</span> <span id="total_shipping">
                                        {{ $total }}</span>.00
                                </strong></td>
                        </tr>
                        <?php }else{ ?>
                        <tr>
                            <td colspan="2" class="text-right">
                                <strong><span class="checkout-text">
                                        {{__("message.subtotal_text")}} including 25% tax:</span> <span class="notranslate"> {{
                                        $currencySymbol." ".$subtotal }}</span>
                                </strong>
                            </td>
                        </tr>
                        {{-- @if($taxValue[0]->taxval > 0)
                            <tr>
                                <td colspan="2" class="text-right">
                                    <strong><span class="checkout-text">
                                            {{__("message.tax_text")}}({{$taxValue[0]->taxval}}%):</span> <span
                                            class="notranslate">{{ $currencySymbol." ".round($taxval,2) }}</span>
                                    </strong>
                                </td>
                            </tr>
                        @endif --}}

                        <tr>
                            <td colspan="2" class="text-right">
                                <strong><span class="checkout-text">
                                        {{__("Flat Rate")}} :</span> <span class="notranslate"> {{ $currencySymbol."
                                        ".$flatRateValue }}.00</span>
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-right">
                                <strong><span class="checkout-text">
                                    <i class="fa fa-truck" aria-hidden="true"></i>
                                    Expected delivery by <i style="font-weight: 400; color: green;" id="delivery_date"> </i>
                                     &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
                                    {{__("Shipping")}} :</span>
                                    <span class="notranslate">{{ $currencySymbol }}</span>
                                    <span id="shippingValue">{{ $shippingValue }}.00</span>
                                </strong>
                            </td>
                        </tr>
                        <tr>

                            <input type="hidden" name="expected_delivery_date" class="expected_delivery_date" >

                            <input class="form-control total_tax" type="hidden" name="total_tax" value="{{$taxval}}">
                            <input class="form-control total_amount" type="hidden" name="total_amount"
                                value="{{$total}}">
                            <input class="shipping_rate_value" type="hidden" name="shipping_rate_value"
                                value="{{$shippingValue}}">
                            <input class="flat_rate_value" type="hidden" name="flat_rate_value"
                                value="{{$flatRateValue}}">
                            <td colspan="2" class="text-right">
                                <strong><span class="checkout-text">
                                        {{__("message.grand_total_text")}}</span>
                                    <span class="notranslate"> {{ $currencySymbol }}</span> <span id="total_shipping">
                                        {{ $total }}.00</span>
                                </strong>
                            </td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="3" class="text-right">
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="payment_section">
                    <div class="main_title_3">
                        <span></span>
                        <h2>{{__("message.payment_details_title")}}</h2>
                    </div><br />
                    <div class="stripe-paymt">
                        {{-- <input type="radio" name="stripe_payment" value="stripe">{{__("message.stripe_payment_text")}}</input> --}}
                        <input type="radio" checked name="paypal_payment" value="paypal">{{__("Pay With")}}
                        <img src="{{ url('/').'/assets/images/paypal1.png' }}" style="width: 181px;" />
                        </input>
                    </div>

                    <div id="address_hidden_data"></div>
                    <div id="payfield">
                        <div class='col-xs-12 form-group required'>
                            <label class='control-label'>{{__("message.name_on_card_text")}}</label>
                            <input class='form-control' placeholder="{{__('message.placeholder_name_text')}}"
                                type='text' name="name_on_card">
                        </div>
                        <div class='col-xs-12 form-group card required' style="background: none !important;">
                            <label class='control-label'>Card Number</label>
                            <input autocomplete='off' placeholder="Enter Card Number" class='form-control card-number'
                                size='20' type='text' maxlength="16" id="number_on_card" name="number_on_card">
                        </div>
                        <div class='form-row row'>
                            <div class='col-xs-12 col-md-4 form-group cvc required'>
                                <label class='control-label'>CVC</label>
                                <input autocomplete='off' class='form-control card-cvc' id="card_cvc" name="card_cvc"
                                    placeholder='ex. 311' size='4' type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Month</label>
                                <input class='form-control card-expiry-month' name="card_expiry_month"
                                    id="card_expiry_month" placeholder='MM' size='2' type='text'>
                            </div>
                            <div class='col-xs-12 col-md-4 form-group expiration required'>
                                <label class='control-label'>Expiration Year</label>
                                <input class='form-control card-expiry-year' name="card_expiry_year"
                                    id="card_expiry_year" placeholder='YYYY' size='4' type='text'>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 form-group" id="btn-place-payment">
                        <button class="btn btn-warning btn-lg btn-block"
                            type="submit">{{__("message.place_order_now_btn")}} </button>
                    </div>

                </div>
            </form>

            {{-- <form action="{{ route('order.place') }}" method="POST">
                @csrf
                <button type="submit">Checkout</button>
            </form> --}}
        </div>
        @endif
    </div>
</div>

<script type="text/javascript">

    function confirmAlert() {
       //alert('You are unable to place an order due to country restrictions')
            //return false;

    }

    function getchangeAddressData(addressId) {
        if(addressId){
            var selobj = addressId.value;
        }else{
            var selobj = 0;
        }

            //console.log('fffff ',selobj);
            $.ajax({
                url: baseUrl + '/set-address-change',
                type: "POST"
                , data: {
                    address_id: selobj
                    , _token: '{{csrf_token()}}'
                }
                , dataType: 'json'
                , success: function(result) {
                    console.log('State Id ',result);
                    $("#address1").val(result.defaultaddressData.address1);
                    $("#address2").val(result.defaultaddressData.address2);
                    $("#zip_code").val(result.defaultaddressData.zipcode);
                    $("#country").val(result.defaultaddressData.country);

                    getAllCityOnChangeAddressbyDropdown(result.defaultaddressData.state,result.defaultaddressData.city_id)
                   var city_id = result.defaultaddressData.city_id;
                   getAllCityOnChangeId(city_id);

                   setTimeout(function() {
                            $("#state").val(result.defaultaddressData.state);
                    }, 2000);

                    $.ajax({
                        url: baseUrl + '/get-states-by-country'
                        , type: "POST"
                        , data: {
                            country_id: result.defaultaddressData.country
                            , _token: '{{csrf_token()}}'
                        }
                        , dataType: 'json'
                        , success: function(result) {
                            $('#state').html('<option value="">Select State</option>');
                            $.each(result.states, function(key, value) {
                                $("#state").append('<option value="' + value.id + '">' + value.name + '</option>');
                            });
                        }
                    });

                    $.ajax({
                        url: baseUrl + '/get-countrybyid'
                        , type: "POST"
                        , data: {
                            country_id: result.defaultaddressData.country
                            , _token: '{{csrf_token()}}'
                        }
                        , dataType: 'json'
                        , success: function(result2) {
                            $('#countrycode').prop('readonly', true);
                            $('#countrycode').val(result2.phonecode);
                            $('#countrycode_m').prop('readonly', true);
                            $('#countrycode_m').val(result2.phonecode);
                        }
                    });



                }
            });
        }
        function getAllCityOnChangeAddressbyDropdown(state_id, cityId){
            $("#city").html('');
            $.ajax({
                url: baseUrl + '/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: state_id
                    , _token: '{{ csrf_token() }}'
                }
                , dataType: 'json'
                , success: function(result) {
                    if(result.cities.length== 0){
                        document.getElementById('city_name').style.display = "block";
                        document.getElementById('city_dropdown').style.display = "none";
                    }else{
                        document.getElementById('city_name').style.display = "none";
                        document.getElementById('city_dropdown').style.display = "block";

                        $('#city').html('<option value="">Select City</option>');
                        $.each(result.cities, function(key, value) {
                            var cityselectedindata = (value.id==cityId)?"Selected":"";
                            $("#city").append('<option value="' + value.id + '" '+cityselectedindata+'>' + value.name + '</option>');
                        });
                    }
                }
            });
        }
    $('#country').on('change', function() {
        var country_id = this.value;
        $("#state").html('');
        $.ajax({
            url: baseUrl + '/get-countrybyid'
            , type: "POST"
            , data: {
                country_id: country_id
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result2) {
                $('#countrycode').prop('readonly', true);
                $('#countrycode').val(result2.phonecode);
                $('#countrycode_m').prop('readonly', true);
                $('#countrycode_m').val(result2.phonecode);
            }
        });

        $.ajax({
            url: baseUrl + '/get-states-by-country'
            , type: "POST"
            , data: {
                country_id: country_id
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                $('#state').html('<option value="">Select State</option>');
                $.each(result.states, function(key, value) {
                    $("#state").append('<option value="' + value.id + '">' + value.name + '</option>');
                });
            }
        });
    });

    $('#billing_country').on('change', function() {
        var country_id = this.value;
        $("#billing_state").html('');
        $.ajax({
            url: baseUrl + '/get-countrybyid'
            , type: "POST"
            , data: {
                country_id: country_id
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result2) {
                $('#countrycode2').prop('readonly', true);
                $('#countrycode2').val(result2.phonecode);
                $('#billingcountrycode_m').prop('readonly', true);
                $('#billingcountrycode_m').val(result2.phonecode);
                console.log(result2.phonecode);
            }
        });

        $.ajax({
            url: baseUrl + '/get-states-by-country',
            type: "POST"
            , data: {
                country_id: country_id
                , _token: '{{csrf_token()}}'
            }
            , dataType: 'json'
            , success: function(result) {
                $('#billing_state').html('<option value="">Select State</option>');
                $.each(result.states, function(key, value) {
                    $("#billing_state").append('<option value="' + value.id + '">' + value.name + '</option>');
                });

            }
        });
    });

    $('#billing_state').on('change', function() {
            var state_id = this.value;
            $("#billing_city").html('');
            $.ajax({
                url: baseUrl + '/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: state_id
                    , _token: '{{ csrf_token() }}'
                }
                , dataType: 'json'
                , success: function(result) {

                    $('#billing_city').html('<option value="">Select City</option>');
                    $.each(result.cities, function(key, value) {
                        $("#billing_city").append('<option value="' + value.id + '">' + value.name + '</option>');
                    });

                }
            });
    });

    $('#state').on('change', function() {
            var state_id = this.value;
            $("#city").html('');
            $.ajax({
                url: baseUrl + '/get-cities-by-state'
                , type: "POST"
                , data: {
                    state_id: state_id
                    , _token: '{{ csrf_token() }}'
                }
                , dataType: 'json'
                , success: function(result) {
                    if(result.cities.length== 0){
                        document.getElementById('city_name').style.display = "block";
                        document.getElementById('city_dropdown').style.display = "none";
                    }else{
                        document.getElementById('city_name').style.display = "none";
                        document.getElementById('city_dropdown').style.display = "block";

                        $('#city').html('<option value="">Select City</option>');
                        $.each(result.cities, function(key, value) {
                            $("#city").append('<option value="' + value.id + '">' + value.name + '</option>');
                        });
                    }
                }
            });
    });


    function getAllCityOnChangeId(city_id){

        if(city_id != null) {
               $.ajax({
                    url: baseUrl + '/get-city-by-id'
                    , type: "POST"
                    , data: {
                        city_id: city_id
                        , _token: '{{ csrf_token() }}'
                    }
                    , dataType: 'json'
                    , success: function(result) {

                        //current date
                        var currentDate = new Date();
                            currentDate.setDate(currentDate.getDate() + result.default_delivery_day);
                            var daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wedneday', 'Thursday', 'Friday', 'Saterday'];
                            var monthsOfYear = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                            var dayOfWeek = daysOfWeek[currentDate.getDay()];
                            var dayOfMonth = currentDate.getDate();
                            var monthOfYear = monthsOfYear[currentDate.getMonth()];
                            //var year = currentDate.getFullYear();
                            var formatted_default_delivery_day = dayOfMonth+ ' ' + monthOfYear + ', ' +dayOfWeek ;

                        if( result.shipping_amount != undefined) {
                            <?php
                             $total = 0;
                            if(session('cart')){
                            foreach(session('cart') as $id => $details){
                                $total += (float)$details['price'] * $details['quantity'];
                                if(isset($couponData['couponcode'])){
                                    $subtotal = round($couponData['subtotal']);
                                    $taxval = ($subtotal)*($taxValue[0]->taxval/100);
                                    $discountvalue = round($couponData['totaldiscount']);
                                    $total = round(($subtotal+($taxval)+($flatRateValue))-($discountvalue));
                                }else{
                                    $subtotal = $total;
                                    $taxval = ($total)*($taxValue[0]->taxval/100);
                                    $total = round($total+($taxval)+($flatRateValue));
                                }
                            }}
                            ?>
                            var total = parseInt({{ $total }});
                            var shipping_amount = parseInt(result.shipping_amount);
                            $('#shippingValue').html(shipping_amount);

                            $('#delivery_date').html(formatted_default_delivery_day);
                            $('.expected_delivery_date').val(formatted_default_delivery_day);



                            $('#total_shipping').html((total+shipping_amount).toFixed(2));
                            $('.total_amount').val(total+shipping_amount);
                            $('.shipping_rate_value').val(shipping_amount);

                        }else{

                            <?php
                             $total = 0;
                                if(session('cart')){
                                foreach(session('cart') as $id => $details){
                                    $total += (float)$details['price'] * $details['quantity'];
                                    if(isset($couponData['couponcode'])){
                                        $subtotal = round($couponData['subtotal']);
                                        $taxval = ($subtotal)*($taxValue[0]->taxval/100);
                                        $discountvalue = round($couponData['totaldiscount']);
                                        $total = round(($subtotal+($taxval)+($flatRateValue))-($discountvalue));
                                    }else{
                                        $subtotal = $total;
                                        $taxval = ($total)*($taxValue[0]->taxval/100);
                                        $total = round($total+($taxval)+($flatRateValue));
                                    }
                                }}
                                ?>
                                var total = parseInt({{ $total }});
                                var shipping_amount = parseInt({{ $shippingValue }});
                                $('#shippingValue').html(shipping_amount);
                                $('#delivery_date').html(formatted_default_delivery_day);
                                $('.expected_delivery_date').val(formatted_default_delivery_day);
                                $('#total_shipping').html((total+shipping_amount).toFixed(2));
                                $('.total_amount').val(total+shipping_amount);
                                $('.shipping_rate_value').val(shipping_amount);
                        }
                    }
                });
            }
    }


    $('#city').on('change', function() {
            var city_id = this.value;
            getAllCityOnChangeId(city_id);
    });


    // $('input[type=radio][name=paypal_payment]').click(function() {
    //     $("#payfield").show();
    // });

    $(document).ready(function() {


        // $('form').on('submit', function (e) {
        //     let valid = true;
        //     $('.error-text').remove();

        //     function showError(el, msg) {
        //         valid = false;
        //         el.after('<span class="error-text text-danger">'+msg+'</span>');
        //     }

        //     let name  = $('input[name="name_on_card"]');
        //     let card  = $('#number_on_card');
        //     let cvc   = $('#card_cvc');
        //     let month = $('#card_expiry_month');
        //     let year  = $('#card_expiry_year');

        //     // Name validation
        //     if ($.trim(name.val()) === '') {
        //         showError(name, 'Name on card is required');
        //     }

        //     // Card number validation
        //     if (!/^\d{16}$/.test(card.val())) {
        //         showError(card, 'Enter valid 16-digit card number');
        //     }

        //     // CVC validation
        //     if (!/^\d{3,4}$/.test(cvc.val())) {
        //         showError(cvc, 'Invalid CVC');
        //     }

        //     // Expiry month
        //     let m = parseInt(month.val(), 10);
        //     if (isNaN(m) || m < 1 || m > 12) {
        //         showError(month, 'Invalid expiry month');
        //     }

        //     // Expiry year
        //     let y = parseInt(year.val(), 10);
        //     let currentYear = new Date().getFullYear();
        //     let currentMonth = new Date().getMonth() + 1;

        //     if (isNaN(y) || y < currentYear) {
        //         showError(year, 'Card expired');
        //     }

        //     if (y === currentYear && m < currentMonth) {
        //         showError(month, 'Card expired');
        //     }

        //     if (!valid) {
        //         e.preventDefault();
        //     }
        // });


        var addressId = $('#getchangeAddressData')[0];
        getchangeAddressData(addressId)

        $('#number_on_card').bind('keyup paste', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
        $('#card_expiry_month').mask("99");
        $('#card_expiry_year').mask("9999");
        $('#card_cvc').mask("999");
        $(".payment_section").hide();
        $("#payfield").hide();
        $("#billing_add").hide();

        $("#samebillingaddress").on("click", function() {

            $("input[name=sameBillingAddress]").prop("checked", $(this).prop("checked"));
            if ($('input.form-check-input').is(':checked')) {
                 $("#billing_add").hide();

                $("#samebillingaddress").val("yes");
                $("#billing_first_name").val($("#first_name").val());
                $("#billing_last_name").val($("#last_name").val());
                $("#billing_email").val($("#email").val());
                $("#billing_address1").val($("#address1").val());
                $("#billing_address2").val($("#address2").val());
                $("#countrycode2").val($("#countrycode").val());
                $("#billing_phone_contact").val($("#phone_contact").val());
                $("#billing_zip_code").val($("#zip_code").val());
                $("#billing_country").val($("#country").val());
                //$("#billing_state").val($("#state").val());
                $("#billing_state").append('<option value='+$('#state').val()+' selected>'+$("select#state option:selected").text()+'</option>');
                // $("#billing_city").val($("#city").val());
                $("#billing_city").append('<option value='+$('#city').val()+' selected>'+$("select#city option:selected").text()+'</option>');

            } else {
                $("#billing_add").show();
                $("#samebillingaddress").val("no");
                $("#billing_first_name").val('');
                $("#billing_last_name").val('');
                $("#billing_email").val('');
                $("#billing_address1").val('');
                $("#billing_address2").val('');
                $("#billing_phone_contact").val('');
                $("#billing_zip_code").val('');
                $("#billing_country").val('');
                $("#billing_state").val('');
                $("#billing_city").val('');
            }
        });
    });

</script>

<script>
    jQuery.validator.addMethod("email", function(value, element) {
        return this.optional(element) || /^[A-Z0-9._%+-]+@([A-Z]+\.)+[A-Z]{2,4}$/i.test(value);
    }, "Please enter valid email");

    jQuery.validator.addMethod("lettersonly", function(value, element) {
        return this.optional(element) || /^[A-Za-zÅÄÖåäö\s]+$/i.test(value);
    }, "Please enter only letters");

    jQuery(function($) {
        var sameBillingAddressChekbox = $("#samebillingaddress").val();
        if (sameBillingAddressChekbox == "no") {
            $("#checkoutaddress-form").validate({
                rules: {
                    first_name: {
                        required: true
                        //, lettersonly: true
                    }
                    , last_name: {
                        required: true
                        //, lettersonly: true
                    }
                    , email: {
                        required: true
                        , email: true
                    }
                    , address1: {
                        required: true
                    }
                    , address2: {
                        required: true
                    }
                    , phone: {
                        required: true
                        , number: true
                        , minlength: 10
                        , maxlength: 10
                    , }
                    , zip_code: {
                        required: true
                        , number: true
                    }
                    , country: {
                        required: true
                    }
                    , state: {
                        required: true
                    }
                    , city: {
                        required: true
                    },

                    billing_first_name: {
                        required: true
                        , lettersonly: true
                    }
                    , billing_last_name: {
                        required: true
                        , lettersonly: true
                    }
                    , billing_email: {
                        required: true
                        , email: true
                    }
                    , billing_address1: {
                        required: true
                    }
                    , billing_address2: {
                        required: true
                    }
                    , billing_phone_contact: {
                        required: true
                        //, number: true
                        //, minlength: 10
                        , maxlength: 10
                    , }
                    , billing_zip_code: {
                        required: true
                        , number: true
                    }
                    , billing_country: {
                        required: true
                    }
                    , billing_state: {
                        required: true
                    }
                    , billing_city: {
                        required: true
                        // , lettersonly: true
                    },

                }
                , messages: {
                    first_name: {
                        required: "Please enter your first name",

                    }
                    , last_name: {
                        required: "Please enter your last name",

                    }
                    , email: {
                        required: "Please Enter e-mail address"
                        , email: "The email should be in the format: abc@domain.tld"
                    }
                    , address1: {
                        required: "Please enter address"
                    }
                    , address2: {
                        required: "Please enter address"
                    }
                    , phone: {
                        required: "Mobile number is required"
                        , number: "Please enter as a numerical value"
                        , minlength: "Please enter a vaild mobile number"
                        , maxlength: "Please enter a vaild mobile number"
                    }
                    , zip_code: {
                        required: "Enter zip code"
                        , number: "Enter as a numerical value"
                    , }
                    , country: {
                        required: "Please enter country"
                    , }
                    , state: {
                        required: "Please enter state"
                    , }
                    , city: {
                        required: "Please enter city"
                    , },

                    billing_first_name: {
                        required: "Please enter your first name",

                    }
                    , billing_last_name: {
                        required: "Please enter your last name",

                    }
                    , billing_email: {
                        required: "Please Enter e-mail address"
                        , email: "The email should be in the format: abc@domain.tld"
                    }
                    , billing_address1: {
                        required: "Please enter address"
                    }
                    , billing_address2: {
                        required: "Please enter address"
                    }
                    , billing_phone_contact: {
                        required: "Mobile number is required"
                        , number: "Please enter as a numerical value"
                        , minlength: "Please enter a vaild mobile number"
                        , maxlength: "Please enter a vaild mobile number"
                    }
                    , billing_zip_code: {
                        required: "Enter zip code"
                        , number: "Enter as a numerical value"
                    , }
                    , billing_country: {
                        required: "Please enter country"
                    , }
                    , billing_state: {
                        required: "Please enter state"
                    , }
                    , billing_city: {
                        required: "Please enter city"
                    , },

                }
                , submitHandler: function() {
                    if ($("#checkoutaddress-form").valid() === true) {
                        $("#address_place").remove();
                        $(".payment_section").show();
                        var first_name = $("#first_name").val();
                        var last_name = $("#last_name").val();
                        var email = $("#email").val();
                        var address1 = $("#address1").val();
                        var address2 = $("#address2").val();
                        var phone_contact = $("#phone_contact").val();
                        var zip_code = $("#zip_code").val();
                        var country = $("#country").val();
                        var state = $("#state").val();
                        var city = $("#city").val();
                        var cityname = $("#cityname").val();
                        var samebillingaddress_pay = $("#samebillingaddress").val();
                        var billing_fname = $("#billing_first_name").val();
                        var billing_lname = $("#billing_last_name").val();
                        var billing_phone = $("#billing_phone_contact").val();
                        var billing_mail = $("#billing_email").val();
                        var billing_addr1 = $("#billing_address1").val();
                        var billing_addr2 = $("#billing_address2").val();
                        var billingcity = $("#billing_city").val();
                        var billingstate = $("#billing_state").val();
                        var billingcountry = $("#billing_country").val();
                        var billing_zipcode = $("#billing_zip_code").val();

                        $("div#address_hidden_data").html(`
				<input type="hidden" value="` + first_name + `" name="shipping_first_name" >
				<input type="hidden" value="` + last_name + `" name="shipping_last_name" >
				<input type="hidden" value="` + email + `" name="shipping_email" >
				<input type="hidden" value="` + address1 + `" name="shipping_address1" >
				<input type="hidden" value="` + address2 + `" name="shipping_address2" >
				<input type="hidden" value="` + phone_contact + `" name="shipping_phone_contact" >
				<input type="hidden" value="` + zip_code + `" name="shipping_zip_code" >
				<input type="hidden" value="` + country + `" name="shipping_country" >
				<input type="hidden" value="` + state + `" name="shipping_state" >
				<input type="hidden" value="` + city + `" name="shipping_city" >
                <input type="hidden" value="` + cityname + `" name="shipping_cityname" >
				<input type="hidden" value="` + samebillingaddress_pay + `" name="samebillingaddress_pay" >
				<input type="hidden" value="` + billing_fname + `" name="billing_first_name" >
				<input type="hidden" value="` + billing_lname + `" name="billing_last_name" >
				<input type="hidden" value="` + billing_phone + `" name="billing_phone_contact" >
				<input type="hidden" value="` + billing_mail + `" name="billing_email" >
				<input type="hidden" value="` + billing_addr1 + `" name="billing_address1" >
				<input type="hidden" value="` + billing_addr2 + `" name="billing_address2" >
				<input type="hidden" value="` + billingcity + `" name="billing_city" >
				<input type="hidden" value="` + billingstate + `" name="billing_state" >
				<input type="hidden" value="` + billingcountry + `" name="billing_country" >
				<input type="hidden" value="` + billing_zipcode + `" name="billing_zip_code" >
				`);
                    }
                }
            });
        } else {
            $("#checkoutaddress-form").validate({
                rules: {
                    first_name: {
                        required: true
                        , lettersonly: true
                    }
                    , last_name: {
                        required: true
                        , lettersonly: true
                    }
                    , email: {
                        required: true
                        , email: true
                    }
                    , address1: {
                        required: true
                    }
                    , address2: {
                        required: true
                    }
                    , phone: {
                        required: true
                        , number: true
                        , minlength: 10
                        , maxlength: 10
                    , }
                    , zip_code: {
                        required: true
                        , number: true
                    }
                    , country: {
                        required: true
                    }
                    , state: {
                        required: true
                    }
                    , city: {
                        required: true
                        // , lettersonly: true
                    },

                }
                , messages: {
                    first_name: {
                        required: "Please enter your first name",

                    }
                    , last_name: {
                        required: "Please enter your last name",

                    }
                    , email: {
                        required: "Please Enter e-mail address"
                        , email: "The email should be in the format: abc@domain.tld"
                    }
                    , address1: {
                        required: "Please enter address"
                    }
                    , address2: {
                        required: "Please enter address"
                    }
                    , phone: {
                        required: "Mobile number is required"
                        , number: "Please enter as a numerical value"
                        , minlength: "Please enter a vaild mobile number"
                        , maxlength: "Please enter a vaild mobile number"
                    }
                    , zip_code: {
                        required: "Enter zip code"
                        , number: "Enter as a numerical value"
                    , }
                    , country: {
                        required: "Please enter country"
                    , }
                    , state: {
                        required: "Please enter state"
                    , }
                    , city: {
                        required: "Please enter city"
                    , },

                }
                , submitHandler: function() {

                    console.log('dd ', $("#checkoutaddress-form").valid());

                    if ($("#checkoutaddress-form").valid() === true) {
                        $("#address_place").remove();
                        $(".payment_section").show();
                        var first_name = $("#first_name").val();
                        var last_name = $("#last_name").val();
                        var email = $("#email").val();
                        var address1 = $("#address1").val();
                        var address2 = $("#address2").val();
                        var phone_contact = $("#phone_contact").val();
                        var zip_code = $("#zip_code").val();
                        var country = $("#country").val();
                        var state = $("#state").val();
                        var city = $("#city").val();
                        var cityname = $("#cityname").val();
                        var samebillingaddress_pay = $("#samebillingaddress").val();
                        var billing_fname = $("#billing_first_name").val();
                        var billing_lname = $("#billing_last_name").val();
                        var billing_phone = $("#billing_phone_contact").val();
                        var billing_mail = $("#billing_email").val();
                        var billing_addr1 = $("#billing_address1").val();
                        var billing_addr2 = $("#billing_address2").val();
                        var billingcity = $("#billing_city").val();
                        var billingstate = $("#billing_state").val();
                        var billingcountry = $("#billing_country").val();
                        var billing_zipcode = $("#billing_zip_code").val();

                        $("div#address_hidden_data").html(`
                            <input type="hidden" value="` + first_name + `" name="shipping_first_name" >
                            <input type="hidden" value="` + last_name + `" name="shipping_last_name" >
                            <input type="hidden" value="` + email + `" name="shipping_email" >
                            <input type="hidden" value="` + address1 + `" name="shipping_address1" >
                            <input type="hidden" value="` + address2 + `" name="shipping_address2" >
                            <input type="hidden" value="` + phone_contact + `" name="shipping_phone_contact" >
                            <input type="hidden" value="` + zip_code + `" name="shipping_zip_code" >
                            <input type="hidden" value="` + country + `" name="shipping_country" >
                            <input type="hidden" value="` + state + `" name="shipping_state" >
                            <input type="hidden" value="` + city + `" name="shipping_city" >
                            <input type="hidden" value="` + cityname + `" name="shipping_cityname" >
                            <input type="hidden" value="` + samebillingaddress_pay + `" name="samebillingaddress_pay" >
                            <input type="hidden" value="` + billing_fname + `" name="billing_first_name" >
                            <input type="hidden" value="` + billing_lname + `" name="billing_last_name" >
                            <input type="hidden" value="` + billing_phone + `" name="billing_phone_contact" >
                            <input type="hidden" value="` + billing_mail + `" name="billing_email" >
                            <input type="hidden" value="` + billing_addr1 + `" name="billing_address1" >
                            <input type="hidden" value="` + billing_addr2 + `" name="billing_address2" >
                            <input type="hidden" value="` + billingcity + `" name="billing_city" >
                            <input type="hidden" value="` + billingstate + `" name="billing_state" >
                            <input type="hidden" value="` + billingcountry + `" name="billing_country" >
                            <input type="hidden" value="` + billing_zipcode + `" name="billing_zip_code" >
                        `);
                    }
                }
            });
        }
    });


</script>

<style>
    [type="radio"]:checked, [type="radio"]:not(:checked) {
        top: 34px;
    }
    #tooltip {
        position: relative;
        cursor: pointer;
        padding-left: 7px;
        font-size: 17px;
        font-family: sans-serif;
    }

    #tooltipText {
        position: absolute;
        top: 0;
        transform: translate(-12%, 7%);
        background: #e9f3f4;
        color: #555a6b;
        border: 1px solid #203864;
        white-space: normal;
        padding: 12px 17px;
        border-radius: 7px;
        width: 180px;
        height: auto;
        visibility: hidden;
        opacity: 0;
        font-size: 12px;
        font-weight: 400;
        transition: opacity 0.5s ease;
        box-shadow: 0px 10px 20px rgb(32 56 100 / 51%);
    }

    span#tooltipText:after {
        top: -10px;
        content: "";
        position: absolute;
        bottom: 100%;
        left: 16%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: transparent transparent #203864 transparent;
    }

    #tooltipText::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 100%;
        transform: translateX(-50%);
        border-color: black;
    }

    #tooltip:hover #tooltipText {
        visibility: visible;
        opacity: 1;
        top: 76%;
        z-index: 99;
    }

    ul.freeProList li {
        display: inline-grid;
    }

    [type="radio"]:checked,
    [type="radio"]:not(:checked) {
        position: absolute;
        left: 0px;
    }

    label.error {
        color: #f33155 !important;
    }

    @media only screen and (max-width: 990px) {
        .isdmobile-hide {
            display: none
        }
    }

    @media only screen and (min-width: 991px) {
        .mobile-isd-view {
            display: none;
        }
    }

    @media only screen and (max-width: 991px) {
        #tooltip:hover #tooltipText {
            top: 80%;
        }

        #tooltip:hover #tooltipText {
            right: auto !important;
            left: 0 !important;
        }

        #tooltip {
            position: relative !important;

        }

        span#tooltipText:after {
            left: 7%;

        }

        #tooltipText {
            transform: translate(1%, 5%) !important;
            width: 180px !important;
        }
    }
</style>
@endsection
