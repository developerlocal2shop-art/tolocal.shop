<?php

namespace App\Models\client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    protected $table = 'order_item';
    protected $fillable = [
        'order_id', 
        'product_id', 
        'product_name', 
        'product_qty',
        'product_price',
        'total',
        'discount', 
        'tax', 
    ];

    public function order()
    {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

}
