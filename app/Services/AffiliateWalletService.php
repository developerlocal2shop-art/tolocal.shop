<?php

namespace App\Services;

use App\Models\Affiliate;
use App\Models\AffiliateConversion;
use App\Models\AffiliatePayoutsToWalletAccount;
use App\Models\AffiliatePayoutItem;
use App\Models\AffiliateWallet;
use App\Models\AffiliateWalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;

class AffiliateWalletService
{
    public function payConversion(
        AffiliateConversion $conversion
    ): AffiliatePayoutsToWalletAccount {
        return DB::transaction(function () use ($conversion) {

            $conversion = AffiliateConversion::query()
                ->whereKey($conversion->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($conversion->status !== 'approved') {
                throw new RuntimeException(
                    'Only approved conversions can be paid.'
                );
            }

            if ((float) $conversion->commission_amount <= 0) {
                throw new RuntimeException(
                    'Commission amount must be greater than zero.'
                );
            }

            /*
             * Prevent duplicate payout.
             */
            $existingPayout = AffiliatePayoutItem::query()
                ->where('conversion_id', $conversion->id)
                ->first();

            if ($existingPayout) {
                return $existingPayout->payout;
            }

            $affiliate = Affiliate::query()
                ->whereKey($conversion->affiliate_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($affiliate->status !== 'approved') {
                throw new RuntimeException(
                    'Affiliate is not approved.'
                );
            }

            $userId = $affiliate->user_id;

            /*
             * Find or create wallet.
             */
            $wallet = AffiliateWallet::query()
                ->firstOrCreate(
                    [
                        'user_id' => $userId,
                    ],
                    [
                        'balance' => 0,
                        'currency' => 'INR',
                        'status' => 'active',
                    ]
                );

            /*
             * Lock wallet row.
             */
            $wallet = AffiliateWallet::query()
                ->whereKey($wallet->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($wallet->status !== 'active') {
                throw new RuntimeException(
                    'Affiliate wallet is not active.'
                );
            }

            $amount = number_format(
                (float) $conversion->commission_amount,
                2,
                '.',
                ''
            );

            $reference = 'AFF-COM-' . $conversion->id . '-' . Str::uuid();

            $idempotencyKey = 'conversion-' . $conversion->id;

            /*
             * Create payout record.
             */
            $payout = AffiliatePayoutsToWalletAccount::create([
                'affiliate_id' => $affiliate->id,
                'user_id' => $userId,
                'wallet_id' => $wallet->id,
                'amount' => $amount,
                'currency' => $wallet->currency,
                'reference' => $reference,
                'idempotency_key' => $idempotencyKey,
                'status' => 'processing',
                'requested_at' => now(),
            ]);

            /*
             * Create wallet ledger transaction.
             */
            $walletTransaction = AffiliateWalletTransaction::create([
                'wallet_id' => $wallet->id,
                'user_id' => $userId,
                'type' => 'affiliate_commission',
                'direction' => 'credit',
                'amount' => $amount,
                'currency' => $wallet->currency,
                'reference' => $reference,
                'description' => 'Affiliate commission credited to wallet.',
                'metadata' => [
                    'affiliate_id' => $affiliate->id,
                    'conversion_id' => $conversion->id,
                    'vendor_id' => $conversion->vendor_id,
                    'order_id' => $conversion->order_id,
                    'order_item_id' => $conversion->order_item_id,
                ],
            ]);

            /*
             * Update wallet balance.
             */
            $wallet->balance = bcadd(
                (string) $wallet->balance,
                (string) $amount,
                2
            );

            $wallet->save();

            /*
             * Connect payout to wallet transaction.
             */
            $payout->wallet_transaction_id = $walletTransaction->id;
            $payout->status = 'completed';
            $payout->processed_at = now();
            $payout->paid_at = now();
            $payout->save();

            /*
             * Add payout item.
             */
            AffiliatePayoutItem::create([
                'payout_id' => $payout->id,
                'conversion_id' => $conversion->id,
                'amount' => $amount,
            ]);

            /*
             * Mark conversion as paid.
             */
            $conversion->paid_amount = $amount;
            $conversion->status = 'paid';
            $conversion->paid_at = now();
            $conversion->save();

            return $payout;
        });
    }
}
