<?php

namespace App\Services;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalPayoutService
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->getAccessToken();
    }

    public function payout($email, $amount, $currency, $orderId)
    {
        //dd($email, $amount, $currency, $orderId);
        return $this->provider->createBatchPayout([
            'sender_batch_header' => [
                'sender_batch_id' => uniqid('payout_'),
                'email_subject' => 'You have received a payout',
            ],
            'items' => [[
                'recipient_type' => 'EMAIL',
                'amount' => [
                    'value' => number_format($amount, 2, '.', ''),
                    'currency' => $currency,
                ],
                'receiver' => $email,
                'note' => 'Payout for Order #' . $orderId,
                'sender_item_id' => (string) $orderId,
            ]]
        ]);
    }
}
