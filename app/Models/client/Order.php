<?php

namespace App\Models\client;

use App\Models\Order_status;
use App\Models\User;
use App\Models\VendorPayout;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = 'order';
    protected $fillable = [
        'user_id', 
        'shipping_address_id', 
        'billing_address_id', 
        'first_name',
        'last_name', 
        'phone', 
        'email',
        'payment_id',
        'total_amount',
        'currency',
        'payment_status',
        'payout_status',
        'vendor_amount',
        'total_tax'
    ];

    public function orderstatus(){
        return $this->hasOne(Order_status::class, 'id', 'status');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    } 

    public function user_data()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    } 

    public function orderitem()
    {
        return $this->hasOne(OrderItem::class, 'order_id', 'id');
    }

    public function vendorPayout()
    {
        return $this->hasOne(VendorPayout::class);
    }

}
