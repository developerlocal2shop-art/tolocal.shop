<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateWallet extends Model
{
    use HasFactory;
    protected $table = 'affiliate_wallets';

    protected $fillable = [
        'user_id',
        'balance',
        'currency',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo( User::class);
    }

    public function transactions()
    {
        return $this->hasMany(AffiliateWalletTransaction::class, 'user_id');
    }

    // public function payouts(): HasMany
    // {
    //     return $this->hasMany(
    //         AffiliatePayout::class,
    //         'wallet_id'
    //     );
    // }
}
