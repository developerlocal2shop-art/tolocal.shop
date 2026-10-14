<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Reply</title>
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
<body style="margin:0; padding:0; background-color:#f5f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; background:#ffffff; border-radius:12px; overflow:hidden; border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:linear-gradient(135deg, #0f172a 0%, #1d4ed8 100%); padding:28px 32px; color:#ffffff;">
                            <h1 style="margin:0; font-size:28px; line-height:1.3; font-weight:700;">
                                @if($recipientType === 'vendor')
                                    Offer reply received
                                @else
                                    Your offer will be redeemed on store only
                                @endif
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            @if($recipientType === 'vendor')
                                <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                    Hello {{ $data['vendor_name'] ?? 'Vendor' }},
                                </p>
                                <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                    A customer has responded to your offer: <strong>{{ $data['offer_title'] ?? 'Shopping offer' }}</strong>.
                                </p>
                                <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                    Customer details:
                                </p>
                                <table role="presentation" cellpadding="8" cellspacing="0" style="border-collapse:collapse; margin-bottom:20px; width:100%; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
                                    <tr>
                                        <td style="font-weight:700; width:140px; color:#334155;">Name</td>
                                        <td>{{ $data['customer_name'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700; color:#334155;">Email</td>
                                        <td>{{ $data['customer_email'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700; color:#334155;">Voucher</td>
                                        <td>{{ $data['voucher_code'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700; color:#334155;">Discount</td>
                                        @if($data['prize_type'] === 'percentagediscount')
                                            <td>{{ $data['prize_amount'] ?? 'N/A' }}% Off on store only</td>
                                        @else
                                            <td>{{ $currencySymbol }}{{ $data['prize_amount'] ?? 'N/A' }}</td>
                                        @endif
                                    </tr>
                                </table>
                                <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                    Message from customer:<br>
                                    <span style="display:inline-block; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:14px 16px; margin-top:8px; color:#374151;">
                                        {{ $data['description'] ?? 'No message provided.' }}
                                    </span>
                                </p>
                            @else
                                <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                    Hello {{ $data['customer_name'] ?? 'Customer' }},
                                </p>
                                <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                    Thank you for your response to <strong>{{ $data['offer_title'] ?? 'this offer' }}</strong>. Your request has been received and is now pending review.
                                </p>
                                <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                    Your reference details are below:
                                </p>
                                <table role="presentation" cellpadding="8" cellspacing="0" style="border-collapse:collapse; margin-bottom:20px; width:100%; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px;">
                                    <tr>
                                        <td style="font-weight:700; width:150px; color:#334155;">Voucher</td>
                                        <td>{{ $data['voucher_code'] ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td style="font-weight:700; color:#334155;">Prize</td>
                                        @if($data['prize_type'] === 'percentagediscount')
                                            <td>{{ $data['prize_amount'] ?? 'N/A' }}% Off</td>
                                        @else
                                            <td>{{ $currencySymbol }}{{ $data['prize_amount'] ?? 'N/A' }}</td>
                                        @endif
                                    </tr>

                                </table>
                                <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                    We will update you as soon as the vendor processes your reply.
                                </p>
                            @endif

                            <p style="margin:0; font-size:14px; line-height:1.7; color:#4b5563;">
                                Kind regards,<br>
                                <strong>Tolocal.shop</strong>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:18px 32px 28px; font-size:12px; line-height:1.7; color:#6b7280; background:#f8fafc; border-top:1px solid #e5e7eb; text-align:center;">
                            This message was sent automatically. Please do not reply directly to this email.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
