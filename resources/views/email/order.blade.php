<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">
    <title></title>
    <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
    <style type="text/css">
        body,
        table,
        td,
        p,
        a,
        li,
        blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            font-family: 'Poppins', sans-serif;
        }

        table,
        td {
            border-collapse: collapse;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        #button a {
            color: #FFFFFF;
        }

        a:link img {
            display: block;
        }

        body {
            margin: 0;
            padding: 0;
            text-align: left !important;
        }

        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        a img {
            border: none !important;
        }

        body {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
            -webkit-font-smoothing: antialiased;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

    </style>
</head>

<body">

    <table style="border: 0px solid #ddd;-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;padding:0 30px;" width="600" cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#fff" class="main">
        <tbody>
            <!--==============banner start here===============-->
            <tr>
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <tbody>
                            <tr>

                                <td align="center" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                                    <a target="_blank" href="#" style="border-bottom: 1px solid #19a7d6;color: #4D008D;text-decoration: none;text-transform: uppercase;font-size: 31px;padding: 20px;display: block;font-weight: 600;letter-spacing: 2px;">
                                        <img style="width:200px;" src="https://tolocal.shop/peter/assets/client/images/dark_logo.png">
                                    </a>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr style="background: #f1f1f1;">
                <td style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                        <tbody>
                            <tr>

                                <td align="center" style="padding: 0;">
                                    <h2 style="font-size: 24px;font-weight: 400;">
                                        Your order was successfully placed</h2>
                                </td>

                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td align="center" style="-webkit-text-size-adjust: 100%;-ms-text-size-adjust: 100%;border-collapse: collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;">
                    <table>

                        <tr style="background-color: #f1f1f1;">
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong>Name :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                {{ auth()->user()->name??'' }} {{ auth()->user()->last_name??'' }}
                            </td>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong>Email :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                {{ auth()->user()->email??'' }}
                            </td>
                        </tr>

                        <tr style="background-color: #f1f1f1;">

                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                <strong>Phone :</strong>
                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px;background: #f1f1f1;">
                                {{ auth()->user()->phone??'' }}
                            </td>
                            <td width="200" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">

                            </td>
                            <td width="400" style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">

                            </td>
                        </tr>

                        <tr>
                            <td colspan="4">
                                <table style="width: 100%;">
                                    <tr style="background-color: #203864;color: #fff;">
                                        <th style="font-weight: 400;">Order ID</th>
                                        <th style="font-weight: 400; text-align: left;
                                        padding-left: 37px;">item</th>
                                        <th style="font-weight: 400;">Qty</th>
                                        <th style="font-weight: 400;">Amount</th>

                                    </tr>

                                    @foreach ($order['orders'] as $val)
                                    <tr style="background-color: #f1f1f1;border-bottom: 1px solid #d9d9d9;">
                                        <td align="center">00{{ $val->id??'' }}</td>
                                        <td>
                                            <table>
                                                <tr>
                                                    <td style="padding:10px;">
                                                        @php
                                                        $getorderimage = Helper::getOrderImage($val->orderitem->product_id);
                                                        @endphp
                                                        <img src="{{ env('IMAGE_PATH').'images/product/'.$getorderimage[0]->name_thumbnail.'.webp' }}" width="70px" height="70px" />
                                                        
                                                        {{-- <img style="width:85px;" src="https://tolocal.shop/peter/public/files/product/1649241980watch1.jpg"> --}}
                                                    </td>
                                                    <td>{{ $val->orderitem->product_name??'' }}</td>
                                                </tr>
                                            </table>
                                        </td>
                                        <td align="center">{{ $val->orderitem->product_qty??'' }}</td>
                                        <td align="center">{{ $val->currency_symbol??'' }} {{ $val->orderitem->product_price??'' }}</td>

                                    </tr>
                                    @endforeach


                                    @php
                                    $currencySymbol = $order['orders'][0]->currency_symbol??$order['orders'][0]->currency;
                                    $shippingRate = $order['orders'][0]->shipping_rate??'';
                                    $taxValue = Helper::taxValueData();
                                    $total = 0; $subtotl = 0;
                                    @endphp
                                    @if(session('cart'))
                                    @foreach(session('cart') as $id => $details)
                                    @php $subtotl += $details['price'] * $details['quantity'] @endphp
                                    @php $total += $details['price'] * $details['quantity'] @endphp

                                    @endforeach
                                    @endif
                                    
                                    @php 
                                        $adminFlatShippingData = Helper::getAdminFlatShippingData(); 
                                        $flatRateValue = $adminFlatShippingData->flat_rate;
                                    @endphp

                                    <tr>
                                        @if(!empty($flatRateValue))
                                            <td colspan="2" align="right" style="font-weight: 500;">Flat rate</td>
                                            <td colspan="2" align="center" style="font-weight: 500;">{{ $currencySymbol." ".$flatRateValue }}</td>
                                        @else
                                        <td colspan="2" align="right" style="font-weight: 500;">Flat rate</td>
                                            <td colspan="2" align="center" style="font-weight: 500;">{{ $currencySymbol}} 0.00</td>
                                        @endif
                                    </tr>

                                    <tr>
                                        <td colspan="2" align="right" style="font-weight: 500;">Subtotal</td>
                                        <td colspan="2" align="center" style="font-weight: 500;">{{ $currencySymbol." ".number_format($subtotl,2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" align="right" style="font-weight: 500;">Shipping</td>
                                        <td colspan="2" align="center" style="font-weight: 500;">{{ $currencySymbol." ".$shippingRate }}.00</td>
                                    </tr>

                                    <tr>
                                        <?php $priceAftertax = ($subtotl)*($taxValue[0]->taxval/100); 
                                        // $gtotal = $total+$priceAftertax;
                                        $gtotal = $total+$priceAftertax+$shippingRate+$flatRateValue;
                                        ?>
                                        <td colspan="2" align="right" style="font-weight: 500;">Tax</td>
                                        <td colspan="2" align="center" style="font-weight: 500;">{{ $currencySymbol." ".number_format($priceAftertax,2) }}</td>
                                    </tr>

                                    <tr>
                                        <td colspan="2" align="right" style="font-weight: 500;">Grand Total</td>
                                        <td colspan="2" align="center" style="font-weight: 500;">{{ $currencySymbol." ".number_format($gtotal,2) }}</td>
                                    </tr>

                                </table>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr>
                <!--==============logo start here===============-->
                <td style="background: #203864;font-size: 12px;color: #fff;text-align: center;border-top: 1px solid #19a7d6;">
                    <p style="display: inline-block;padding-right: 30px;"><b>Contacts</b> : <a style="color: #fff;" href="{{ route('contact') }}">+{{ env('APP_CONTACT') }} </a></p>
                    <p style="display: inline-block;"><b>Email </b>: {{ env('APP_EMAIL') }} </p>
                </td>
                <!--==============logo end here===============-->
            </tr>

            <!--==============footer end here===============-->
    </table>

    </body>

</html>
