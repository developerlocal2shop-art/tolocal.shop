<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Special Shopping Card Offer</title>
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
        <p>Use the voucher code below during redeem on store only:</p>
        <div class="code">{{ $data->voucher_code }}</div>
        <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#ffffff; border-radius:8px; overflow:hidden;">
          <!-- Header -->
          <tr>
            <td align="center" bgcolor="#007bff" style="padding: 30px; color: #ffffff;">
              <h1 style="margin: 0; font-size: 28px;">🎉 Exclusive Shopping Card Offer!</h1>
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
              @if($data->minimum_amount)
                    <td><strong>Minimum Amount:</strong></td>
                    <td>
                        {{ $currencycode }} {{ $data->minimum_amount }}  
                    </td>
              @endif
              @if($data->discount_firstvalue)
                    <td><strong>Discount Amount:</strong></td>
                    <td>
                        {{ $currencycode }} {{ $data->discount_firstvalue }}  
                    </td>
              @endif
              <p style="font-size: 16px; line-height: 1.6;">
                We appreciate you as a valued customer! For a limited time, enjoy <strong>
                  {{ $data->discount_firstvalue }}
                @if ($data->discount_selectedvalue == 'percentagediscount')
                    % 
                @else
                @endif 
                   off</strong> our best-selling items.
              </p>
              @if($data->expire_date)
                <p style="font-size: 16px; line-height: 1.6;">
                  Also Online Offer will be expired <strong>{{ $data->expire_date ? \Carbon\Carbon::parse($data->expire_date)->format('d-m-Y') : '' }}</strong>. Hurry, this offer ends soon!
                </p>
              @endif
              <!-- CTA Button -->
              <p style="text-align: center; margin-top: 30px;">
                <a href="{{ url('shopping-card/detail/'.$data->slug) }}" style="background-color: #28a745; color: #ffffff; padding: 14px 24px; text-decoration: none; border-radius: 6px; font-weight: bold;">View Offer</a>
              </p>
            </td>
          </tr>
          <!-- Footer -->
          <tr>
            <td bgcolor="#f8f8f8" style="padding: 20px; font-size: 14px; color: #999999; text-align: center;">
              You're receiving this email because you subscribed to our customer club.<br>
              <a href="{{ url('account', ['unsubscribe' => 'true', 'sellerid' => $data->directory_link]) }}" style="color:#007bff;">
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
