<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferWinner extends Model
{
    use HasFactory;

    protected $table = 'offers_winner';

    protected $fillable = [
        'offer_id',
        'customer_id',
        'vendor_id',
        'description',
        'prize_amount',
        'voucher_code',
        'type',
        'status',
        'action_status',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'directory_id');
    }
}
