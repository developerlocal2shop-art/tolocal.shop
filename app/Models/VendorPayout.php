<?php

namespace App\Models;

use Directory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorPayout extends Model
{
    use HasFactory;

    protected $table = 'vendor_payouts';

    protected $fillable = [
        'vendor_id',
        'order_id',
        'amount',
        'paypal_payout_id',
        'status',
        'response',
    ];

    public function vendor()
    {
        return $this->belongsTo(Diectory::class);
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function isSuccessful(): bool
    {
        return $this->status === 'success';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }


}
