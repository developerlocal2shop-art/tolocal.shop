<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Coupon - {{ $coupon['voucher_name'] }}</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f9f9f9;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .header {
            background: #4CAF50;
            color: white;
            padding: 5px 20px;
            text-align: center;
        }
        .content {
            padding: 25px 30px;
        }
        .code {
            background: #f0f0f0;
            font-size: 22px;
            text-align: center;
            padding: 12px;
            margin: 20px 0;
            border-radius: 8px;
            letter-spacing: 2px;
            font-weight: bold;
        }
        .details {
            margin-top: 20px;
        }
        .details td {
            padding: 8px 0;
        }
        .footer {
            background: #f1f1f1;
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
@php
  $defaultCurrency = Helper::defaultCurrencyData();
	if(session()->has('currency_code')){
		$currencySymbol = session()->get('currency_code')['currsymbol'];
		$currencycode = session()->get('currency_code')['currcode'];
	}else{
		$currencySymbol = $defaultCurrency[0]->symbol;
		$currencycode = $defaultCurrency[0]->code;
	}
@endphp
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $coupon['voucher_name'] }}</h1>
            <p>Enjoy your exclusive discount!</p>
        </div>
        <div class="content">
            <p>Use the coupon code below during cart:</p>
            <div class="code">{{ strtoupper($coupon['voucher_code']) }}</div>

            <table class="details" width="100%">
                <tr>
                    <td><strong>Discount Value:</strong></td>
                    <td>
                        @if($coupon['discount_type'] === 'fixeddiscount')
                            {{ $currencycode }}{{ $coupon['discount_value'] }}
                        @else
                            {{ $coupon['discount_value'] }}%
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Minimum Purchase:</strong></td>
                    <td>{{ $currencycode }}{{ $coupon['minimum_amount'] }}</td>
                </tr>
                <tr>
                    <td><strong>Valid Until:</strong></td>
                    <td>{{ \Carbon\Carbon::parse($coupon['expiry_date'])->format('d M, Y') }}</td>
                </tr>
               
            </table>

            @php
             $getDirectory = Helper::getDirectorySeoSlug($coupon['directory_id']);
            @endphp
            <p style="margin-top: 30px;">Don't miss out! Apply the code before it expires. shop 
              <a href="{{ url('listings-product').'/'.$getDirectory->slug??'' }}"> 
                {{ $getDirectory->title??'' }}
              </a>
            </p>
        </div>
        <div class="footer">
            &copy; {{ now()->year }} tolocal.shop. All rights reserved.
        </div>
    </div>
</body>
</html>
