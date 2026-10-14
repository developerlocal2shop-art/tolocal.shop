<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OffersEmailNotificationHistory extends Model
{
    use HasFactory;

    public function offer(){
        return $this->belongsTo(Offer::class, 'offer_id','id');
    }

    public function giftvoucher(){
        return $this->belongsTo(Offer::class, 'offer_id','id');
    }

    public function shoppingcard(){
        return $this->belongsTo(ShoppingCard::class, 'offer_id','id');
    }

}
