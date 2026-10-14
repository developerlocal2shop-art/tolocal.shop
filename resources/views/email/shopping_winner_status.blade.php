<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Card Voucher Status</title>
</head>
<body style="margin:0; padding:0; background-color:#f5f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f5f7fb; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px; background:#ffffff; border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#1d4ed8; padding:28px 32px; color:#ffffff;">
                            <h1 style="margin:0; font-size:26px; line-height:1.3;">Voucher redeemed successfully</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                Hello {{ $data['customer_name'] ?? 'Customer' }},
                            </p>
                            <p style="margin:0 0 18px; font-size:16px; line-height:1.7;">
                                Your voucher for <strong>{{ $data['offer_title'] ?? 'Shopping card offer' }}</strong> has been redeemed successfully.
                            </p>
                            <table role="presentation" cellpadding="8" cellspacing="0" style="border-collapse:collapse; width:100%; background:#f8fafc; border:1px solid #e2e8f0;">
                                <tr>
                                    <td style="font-weight:700; width:150px; color:#334155;">Voucher</td>
                                    <td>{{ $data['voucher_code'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight:700; color:#334155;">New status</td>
                                    <td><strong>{{ $data['status'] ?? 'Updated' }}</strong></td>
                                </tr>
                            </table>
                            <p style="margin:24px 0 0; font-size:14px; line-height:1.7; color:#4b5563;">
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

