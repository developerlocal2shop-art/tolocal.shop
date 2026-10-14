<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliatePayoutsToWalletAccount extends Model
{

   use HasFactory;

   protected $table = 'affiliate_payouts_to_wallet_account';

    protected $fillable = [
        'affiliate_id',
        'user_id',
        'wallet_id',
        'amount',
        'currency',
        'reference',
        'idempotency_key',
        'wallet_transaction_id',
        'status',
        'failure_reason',
        'requested_at',
        'processed_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function affiliate()
    {
        return $this->belongsTo(
            Affiliate::class
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function wallet()
    {
        return $this->belongsTo(
            AffiliateWallet::class,
            'wallet_id'
        );
    }

    public function walletTransaction()
    {
        return $this->belongsTo(
            AffiliateWalletTransaction::class,
            'wallet_transaction_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            AffiliatePayoutItem::class,
            'payout_id'
        );
    }
}
