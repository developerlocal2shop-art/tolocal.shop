<?php

namespace App\Services;

use App\Models\Order;
use App\Models\PaymentTrasaction;
use App\Models\RefundStatus;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class OrderRefundService
{
    public function refund($orderId, $description = null)
    {
        return DB::transaction(function () use ($orderId, $description) {
            $order = Order::whereKey($orderId)->lockForUpdate()->firstOrFail();

            if ((int) $order->status === 7) {
                return [
                    'already_refunded' => true,
                    'payout_already_paid' => (bool) $order->paid_by_admin || $order->payout_status === 'paid',
                    'order' => $order,
                ];
            }

            $payment = PaymentTrasaction::find($order->payment_id);
            if (!$payment) {
                throw new RuntimeException('Payment record not found for this order.');
            }

            $paymentMethod = strtolower((string) ($order->payment_method ?: $payment->payment_method));
            if ($paymentMethod !== 'paypal') {
                throw new RuntimeException('Only PayPal payments can be refunded automatically.');
            }

            $captureId = $order->paypal_capture_id ?: $payment->transaction_id;
            if (empty($captureId)) {
                throw new RuntimeException('PayPal capture ID not found for this order.');
            }

            $amount = round((float) $order->total_amount, 2);
            if ($amount <= 0) {
                throw new RuntimeException('The order refund amount must be greater than zero.');
            }

            $paypal = app(PayPalService::class)->provider()->withExceptions();
            $paypal->setCurrency(strtoupper($order->currency ?: config('paypal.currency', 'USD')));
            $refund = $paypal->refundCapturedPayment(
                $captureId,
                '',
                $amount,
                $description ?: 'Order refund'
            );

            if (!is_array($refund) || ($refund['status'] ?? null) !== 'COMPLETED') {
                throw new RuntimeException('PayPal did not confirm the refund.');
            }

            $order->status = 7;
            $order->payment_status = 'refunded';
            $order->order_status_description = $description ?: 'Order refund';
            $order->save();

            $refundRequest = RefundStatus::where('order_id', $order->id)
                ->where('status', 1)
                ->lockForUpdate()
                ->orderByDesc('id')
                ->first();
            if ($refundRequest) {
                $refundRequest->status = 0;
                $refundRequest->save();
            }

            return [
                'already_refunded' => false,
                'payout_already_paid' => (bool) $order->paid_by_admin || $order->payout_status === 'paid',
                'order' => $order,
                'paypal_refund' => $refund,
            ];
        });
    }
}
