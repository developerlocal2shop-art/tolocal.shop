<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeProductsDiscount extends Model
{
    use HasFactory;

    protected $table = "free_products_discount";

    protected $fillable = [
        'title', 
        'description',
        'minimum_amount', 
        'directory_id',
        'product_ids',
        'created_by'
    ];
}
