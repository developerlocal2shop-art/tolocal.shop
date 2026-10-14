<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $table = 'order';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'user_id',
        'shipping_address_id',
        'billing_address_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'payment_id',
        'total_amount',
        'status',
        'currency',
        'total_tax',
        'created_at'
    ];


    public function orderitem()
    {
        return $this->hasOne(OrderItem::class, 'order_id', 'id');
    }

    public function refund()
    {
        return $this->hasOne(RefundStatus::class, 'order_id', 'id');
    }

}
