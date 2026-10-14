<?php ini_set('max_execution_time', 180); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link href="https://tolocal.shop/peter/assets/client/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Poppins, Helvetica, sans-serif;
        }
        table {
            width: 100%;
            background: #fff;
        }
        td {
            padding: 10px;
            vertical-align: top;
        }
        h3, h4, label {
            margin: 0;
            font-family: Poppins, Helvetica, sans-serif;
        }
        .box-title {
            font-weight: bold;
        }
        .panel-heading {
            font-weight: bold;
        }
        textarea {
            width: 100%;
            border: none;
            resize: none;
            overflow: hidden;
        }
        .freeProList {
            list-style-type: none;
            padding-left: 0;
        }
        .freeProList li {
            display: flex;
            align-items: center;
        }
        .freeProList img {
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <?php $adminFlatShippingData = Helper::getAdminFlatShippingData(); ?>
    <?php $flatRateValue = $adminFlatShippingData->flat_rate; ?>

    @php
        $billingcountryName = Helper::getCountryName($billing_address->country);
        $billingstateName = Helper::getStateName($billing_address->state);
        $billingCityName = Helper::getCityName($billing_address->city_id);
        $shippingcountryName = Helper::getCountryName($shiping_address->country);
        $shippingstateName = Helper::getStateName($shiping_address->state);
        $shippingCityName = Helper::getCityName($shiping_address->city_id);
    @endphp

    <table>
        <tr>
            <td colspan="4" style="text-align:right;">
                <img src="https://tolocal.shop/peter/assets/client/images/dark_logo.png" width="165" height="35" alt="Logo">
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <h3 class="box-title">Order #{{ $orderData->id }}</h3>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <h4 class="box-title">Customer Information</h4>
                <textarea rows="5" readonly>Name - {{ $orderData->first_name.' '.$orderData->last_name }}&#13;&#10;Email - {{ $orderData->email }}&#13;&#10;Phone - {{ $orderData->phone }}&#13;&#10;Created - {{ $orderData->created_at->toDateString() }} - {{ $orderData->created_at->format('H:i:s') }}</textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <h4 class="box-title">Billing Address</h4>
                <textarea rows="5" readonly>{{ $billing_address->address1 }}&#13;&#10;{{ $billing_address->address2 }}&#13;&#10;{{ $billingCityName->name }}, {{ $billingstateName->name }}&#13;&#10;{{ $billingcountryName->name }}&#13;&#10;{{ $billing_address->zipcode }}</textarea>
            </td>
            <td colspan="2">
                <h4 class="box-title">Shipping Address</h4>
                <textarea rows="5" readonly>{{ $shiping_address->address1 }}&#13;&#10;{{ $shiping_address->address2 }}&#13;&#10;{{ $shippingCityName->name }}, {{ $shippingstateName->name }}&#13;&#10;{{ $shippingcountryName->name }}&#13;&#10;{{ $shiping_address->zipcode }}</textarea>
            </td>
        </tr>
        <tr style="background: #e5e5e5;">
            <td><label>Item</label></td>
            <td><label>Cost</label></td>
            <td><label>Qty</label></td>
            <td><label>Total</label></td>
        </tr>
        <?php $orderItemData = Helper::getOrderItem($orderData->id); ?>
        @foreach($orderItemData as $rowdata)
        <tr style="background: #ededed;">
            <td>
                <?php $orderImagedata = Helper::getOrderImage($rowdata->product_id); ?>
                <img src="{{ public_path('images/product/').$orderImagedata[0]->name_thumbnail.'.webp' }}" width="70px" height="70px" alt="Product Image">
                {{ $rowdata->product_name }}
                @php
                $free_product_id = $rowdata->free_product_id ?? 0;
                $getFreeProductDirectoryId = Helper::getFreeProductDirectoryId($free_product_id);
                @endphp
                @if(count($getFreeProductDirectoryId[1]) > 0)
                <hr>
                <h6>Free Products</h6>
                <ul class="freeProList">
                    @foreach($getFreeProductDirectoryId[1] as $freeProduct)
                    @foreach($freeProduct as $freePro)
                    <li>
                        <?php $imagename = Helper::getProductImage($freePro->id); ?>
                        <img src="{{ public_path('images/product/').$imagename[0]->name_thumbnail.'.webp' }}" style="width: 40px;height: 40px;" alt="Free Product Image">
                        <b>{{ $freePro->name ?? '' }}</b>
                    </li>
                    @endforeach
                    @endforeach
                </ul>
                @endif
            </td>
            <td class="notranslate">
                {{ $orderData->currency_symbol ?? $orderData->currency }} {{ $rowdata->product_price }}
            </td>
            <td>{{ $rowdata->product_qty }}</td>
            <td class="notranslate">
                {{ $orderData->currency_symbol ?? $orderData->currency }} {{ $rowdata->total }}
            </td>
        </tr>
        @endforeach
        <tr style="background: #e9e9e9;">
            <td colspan="2">
                <h4 class="box-title">Flat rate</h4>
                <span>Items:</span>
                @foreach($orderItemData as $rowdata)
                <span>{{ $rowdata->product_name }}*{{ $rowdata->product_qty }}<br></span>
                @if(!empty($rowdata->attr_name))
                <p>
                    @php
                    $attr_name = explode(',', $rowdata->attr_name);
                    $attr_value = explode(',', $rowdata->attr_value);
                    $c_attr_value = array_combine($attr_name, $attr_value);
                    @endphp
                    @foreach($c_attr_value as $key => $attrval)
                    {{ $key }} : {{ $attrval }}<br>
                    @endforeach
                </p>
                @endif
                @endforeach
            </td>
            <td class="notranslate" colspan="2">
                {{ $orderData->currency_symbol ?? $orderData->currency }} {{ $flatRateValue }}.00
            </td>
        </tr>
        <tr style="background: #f0f0f0;">
            <td colspan="2"></td>
            <td colspan="2">
                <table>
                    <tr>
                        @php $ordertotal = 0 @endphp
                        @foreach($orderItemData as $rowdata)
                        @php $ordertotal += $rowdata->product_price * $rowdata->product_qty @endphp
                        @endforeach
                        <td style="text-align: right;"><span style="font-weight: 700;">Items Subtotal:</span></td>
                        <td class="notranslate" align="right">
                            {{ $orderData->currency_symbol ?? $orderData->currency }} {{ $ordertotal }}
                        </td>
                    </tr>
                    @foreach($orderItemData as $rowdata)
                    @if($rowdata->shoppingcard_type)
                    <tr>
                        <td style="text-align: right;"><span style="font-weight: 700;">Shopping Card:</span></td>
                        <td align="right">
                            @if($rowdata->shoppingcard_type == 'percentage')
                            {{ $rowdata->shoppingcard }}%
                            @else
                            <span class="notranslate">
                                {{ $orderData->currency_symbol ?? $orderData->currency }} {{ $rowdata->shoppingcard }}.00
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @if($rowdata->offer_type)
                    <tr>
                        <td style="text-align: right;"><span style="font-weight: 700;">Offer:</span></td>
                        <td align="right">
                            @if($rowdata->offer_type == 'percentage')
                            {{ $rowdata->offer_value }}%
                            @else
                            <span class="notranslate">
                                {{ $orderData->currency_symbol ?? $orderData->currency }} {{ $rowdata->offer_value }}.00
                            </span>
                            @endif
                        </td>
                    </tr>
                    @endif
                    @endforeach
                    <tr>
                        <td style="text-align: right;"><span style="font-weight: 700;">Flat Rate:</span></td>
                        <td class="notranslate" align="right">
                            {{ $orderData->currency_symbol ?? $orderData->currency }} {{ $flatRateValue }}.00
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><span style="font-weight: 700;">Shipping:</span></td>
                        <td class="notranslate" align="right">
                            {{ $orderData->currency_symbol ?? $orderData->currency }} {{ $orderData->shipping_rate }}.00
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: right;"><span style="font-weight: 700;">Grand Total:</span></td>
                        <td class="notranslate" align="right">
                            @if($orderData->currency_symbol)
                                {{$orderData->currency_symbol }} {{ $ordertotal+$orderData->total_tax+$orderData->shipping_rate+$flatRateValue }}
                            @else
                                {{$orderData->currency }} {{ $ordertotal+$orderData->total_tax+$orderData->shipping_rate+$flatRateValue }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
