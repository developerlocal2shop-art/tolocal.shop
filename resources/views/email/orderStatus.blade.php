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

<body>

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
                                    <h2 style="font-size: 20px;font-weight: 400;">
                                    @if($data['ordstatus']->id == 1)
                                        Your Order is being processed!!
                                    @elseif($data['ordstatus']->id == 2)
                                        Your Order is packed!!
                                    @elseif($data['ordstatus']->id == 3)
                                        Your Order is Shipped!!
                                    @elseif($data['ordstatus']->id == 4)
                                        Your Order is Cancelled!!
                                    @elseif($data['ordstatus']->id == 5)
                                        Your Order Delivered!!
                                    @elseif($data['ordstatus']->id == 6)
                                        Your Order returned!!
                                    @elseif($data['ordstatus']->id == 7)
                                        Your Order is being Refunded!!
                                    @endif
                                        
                                    </h2>
                                </td>

                            </tr>

                            <tr>
                                <td style="vertical-align:top;padding: 8px 13px;font-size: 13px; background: #f1f1f1;">
                                    <strong>You can check your order status:</strong> <a href="{{url('login')}}" style="color: #fff; padding: 5px 15px; background: #000; border-radius: 5px; display: inline-block; text-decoration: none;">Click</a>
                                </td>
                                
                            </tr>

                            <tr>
                                <td colspan="4">
                                    <table style="width: 100%;">
                                        <tr style="background-color: #203864;color: #fff;">
                                            <th style="font-weight:400;text-align: center;">Order ID</th>
                                            <th style="font-weight:400; text-align:left; padding-left:37px;">item</th>
                                            <th style="font-weight: 400;">Qty</th>
                                            <th style="font-weight:400;text-align: center;">Amount</th>

                                        </tr>
                                        @php
                                            $orderdata = $data['order'];
                                        @endphp
                                        <tr style="background-color: #f1f1f1;border-bottom: 1px solid #d9d9d9;">
                                            <td align="center">00{{ $orderdata->id??'' }}</td>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td style="padding:10px;">
                                                            @php
                                                            $getorderimage = Helper::getOrderImage($orderdata->orderitem->product_id);
                                                            @endphp
                                                            <img src="{{ env('IMAGE_PATH').'images/product/'.$getorderimage[0]->name_thumbnail.'.webp' }}" width="70px" height="70px" />
                                                        </td>
                                                        <td>{{ $orderdata->orderitem->product_name??'' }}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td align="center">{{ $orderdata->orderitem->product_qty??'' }}</td>
                                            <td align="center">{{ $orderdata->currency_symbol??'' }} {{ $orderdata->orderitem->product_price??'' }}</td>

                                        </tr>
                                       

                                        @php
                                        $currencySymbol = $orderdata->currency_symbol??$orderdata->currency;
                                        $taxValue = Helper::taxValueData();
                                        $shippingRate = $orderdata->shipping_rate??'';
                                        @endphp
                                       
                                        @php $subtotl = $orderdata->orderitem->product_price * $orderdata->orderitem->product_qty @endphp
                                        @php $total = $orderdata->orderitem->product_price * $orderdata->orderitem->product_qty @endphp

                                        @php 
                                        $adminFlatShippingData = Helper::getAdminFlatShippingData(); 
                                        $flatRateValue = $adminFlatShippingData->flat_rate;
                                        @endphp

                                        <tr>
                                            @if(!empty($flatRateValue))
                                                <td colspan="2" align="right" style="font-weight: 500;">Flat rate</td>
                                                <td colspan="2" align="right" style="font-weight: 500;padding: 5px 10px;">{{ $currencySymbol." ".$flatRateValue }}</td>
                                            @else
                                                <td colspan="2" align="right" style="font-weight: 500;">Flat rate</td>
                                                <td colspan="2" align="right" style="font-weight: 500;padding: 5px 10px;">{{ $currencySymbol}} 0.00</td>
                                            @endif
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="right" style="font-weight: 500;">Subtotal</td>
                                            <td colspan="2" align="right" style="font-weight:500;padding: 5px 10px;">{{ $currencySymbol." ".number_format($subtotl,2) }}</td>
                                        </tr>

                                        <tr>
                                            <?php 
                                                $priceAftertax = ($subtotl)*($taxValue[0]->taxval/100); 
                                                $gtotal = $total+$priceAftertax+$shippingRate+$flatRateValue;
                                                ?>
                                            <td colspan="2" align="right" style="font-weight: 500;">Tax</td>
                                            <td colspan="2" align="right" style="font-weight:500;padding: 5px 10px;">{{ $currencySymbol." ".number_format($priceAftertax,2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="right" style="font-weight: 500;">Shipping</td>
                                            <td colspan="2" align="right" style="font-weight:500;padding: 5px 10px;">{{ $currencySymbol." ".number_format($shippingRate,2) }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" align="right" style="font-weight: 500;">Grand Total</td>
                                            <td colspan="2" align="right" style="font-weight:500;padding: 5px 10px;">{{ $currencySymbol." ".number_format($gtotal,2) }}</td>
                                        </tr>

                                    </table>
                                </td>
                            </tr>
                        </tbody>
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
