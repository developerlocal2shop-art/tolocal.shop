<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateWalletTransaction extends Model
{
    use HasFactory;
    protected $table = 'affiliate_wallet_transactions';

    protected $fillable = [
        'wallet_id',
        'user_id',
        'type',
        'direction',
        'amount',
        'currency',
        'reference',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function wallet()
    {
        return $this->belongsTo( AffiliateWallet::class,'wallet_id' );
    }

    public function user()
    {
        return $this->belongsTo( User::class );
    }

    public function payouts()
    {
        return $this->hasMany(
            AffiliatePayoutsToWalletAccount::class
        );
    }
}
