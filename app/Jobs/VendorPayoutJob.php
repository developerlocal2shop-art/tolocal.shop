<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\client\Order;
use App\Models\User;
use App\Models\VendorPayout;
use Illuminate\Support\Facades\Log;
use App\Services\PayPalPayoutService;
use Illuminate\Support\Facades\DB;

class VendorPayoutJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderId;
    public $tries = 3;
    public $timeout = 120;

    public function __construct($orderId)
    {
        $this->orderId = $orderId;
    }

    public function handle(PayPalPayoutService $paypal)
    {
        $order = Order::with('user_data')->find($this->orderId);

        // if (!$order || $order->vendor_amount <= 0 || $order->payout_status === 'paid') {
        //     return;
        // }

        $vendor = User::where('directory_id', $order->directory_id)->first();

        // if (!$vendor || !$vendor->paypal_email) {
        //     Log::warning('Vendor PayPal not configured', ['order_id' => $order->id]);
        //     return;
        // }

        DB::transaction(function () use ($order, $vendor, $paypal) {

            if (VendorPayout::where('order_id', $order->id)->lockForUpdate()->exists()) {
                return;
            }

            // increment attempt
            //$order->increment('payout_attempts');

            $response = $paypal->payout(
                $vendor->paypal_email,
                $order->vendor_amount,
                $order->currency,
                $order->id
            );

            if (!isset($response['batch_header']['payout_batch_id'])) {
                throw new \Exception('PayPal payout failed');
            }

            VendorPayout::create([
                'vendor_id'        => $vendor->directory_id,
                'order_id'         => $order->id,
                'amount'           => $order->vendor_amount,
                'currency'         => $order->currency,
                'paypal_payout_id' => $response['batch_header']['payout_batch_id'],
                'status'           => 'success',
                'response'         => json_encode($response),
            ]);

            $order->update([
                'payout_status' => 'paid',
            ]);
        });
    }
}

