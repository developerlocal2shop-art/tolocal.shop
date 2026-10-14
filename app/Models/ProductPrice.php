<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    use HasFactory;
    protected $table = 'product_price';
    protected $fillable = [
        'currency_id', 
        'currency_code',
        'currency_name',
        'currency_base',
        'product_id', 
        'sales_price',
        'regular_price'
    ];
}
