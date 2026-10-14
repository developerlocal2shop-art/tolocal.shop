<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Special Offers</title>
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
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif;">
  <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
      <td align="center" style="padding: 20px 0;">
        @if($data->voucher_code)
          <p>Use the voucher code below during redeem on store only:</p>
          <div class="code">{{ strtoupper($data->voucher_code) }}</div>
        @endif
        
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#ffffff; border-radius:8px; overflow:hidden;">
          <!-- Header -->
          <tr>
            <td align="center" bgcolor="#007bff" style="padding: 30px; color: #ffffff;">
              <h1 style="margin: 0; font-size: 28px;">🎉 Exclusive Offers!</h1>
              <p> {{ $data->title??'' }} </p>
            </td>
          </tr>
          
          
          <!-- Main Content -->
          <tr>
            <td style="padding: 30px; color: #333333; text-align: left;">
              <h2 style="font-size: 22px; margin-top: 0;">Get 
                {{ $data->discount_firstvalue }}
                @if ($data->discount_selectedvalue == 'percentagediscount')
                    % 
                @else
                @endif 
                Off On This Offer's Product
              </h2>

              <p style="font-size: 16px; line-height: 1.6;">
              @if($data->discount_firstvalue)
                  <strong>Discount Amount:</strong>  {{ $currencycode }} {{ $data->discount_firstvalue }} 
              @endif
              </p>
              <p style="font-size: 16px; line-height: 1.6;">
              @if($data->minimum_amount)
                    <strong>Minimum Purchase:</strong> {{ $currencycode }} {{ $data->minimum_amount }}
              @endif
              </p>

              <p style="font-size: 16px; line-height: 1.6;">
                We appreciate you as a valued customer! For a limited time, enjoy <strong>
                  {{ $data->discount_firstvalue }}
                @if ($data->discount_selectedvalue == 'percentagediscount')
                    % 
                @else
                @endif 
                   off</strong> our best-selling items.
              </p>
              @if($data->offer_expire_date)
                <p style="font-size: 16px; line-height: 1.6;">
                  Offer will be expired <strong>{{ $data->offer_expire_date ? \Carbon\Carbon::parse($data->offer_expire_date)->format('d-m-Y') : '' }}</strong>. Hurry, this offer ends soon!
                </p>
              @endif
              <!-- CTA Button -->
              <p style="text-align: center; margin-top: 30px;">
                <a href="{{ url('offer/detail/'.$data->slug) }}" style="background-color: #28a745; color: #ffffff; padding: 14px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">View Offer</a>
              </p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td bgcolor="#f8f8f8" style="padding: 20px; font-size: 14px; color: #999999; text-align: center;">
              You're receiving this email because you subscribed to our customer club.<br>
              <a href="{{ url('account', ['unsubscribe' => 'true', 'sellerid' => $data->offer_directory_link]) }}" style="color:#007bff;">
                Unsubscribe
              </a> 
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
