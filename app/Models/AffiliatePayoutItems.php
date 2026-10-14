<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliatePayoutItem extends Model
{
    use HasFactory;

    protected $table = 'affiliate_payout_items';

    protected $fillable = [
        'payout_id',
        'conversion_id',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function payout()
    {
        return $this->belongsTo(
            AffiliatePayoutsToWalletAccount::class,
            'payout_id'
        );
    }

    public function conversion()
    {
        return $this->belongsTo(
            AffiliateConversion::class,
            'conversion_id'
        );
    }
}
