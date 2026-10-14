<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = [
        'user_id',
        'affiliate_code',
        'status',
        'store_type',
        'commission_type',
        'commission_rate',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function links()
    {
        return $this->hasMany(
            AffiliateLink::class
        );
    }

    public function clicks()
    {
        return $this->hasMany(
            AffiliateClick::class
        );
    }

    public function conversions()
    {
        return $this->hasMany(
            AffiliateConversion::class
        );
    }

    public function payouts()
    {
        return $this->hasMany(
            AffiliatePayoutsToWalletAccount::class
        );
    }
}
