<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributesVariation extends Model
{
    protected $fillable = ['id','product_id','directory_id','attribute_value','v_ena','v_stock','created_at','updated_at'];
    use HasFactory;
}
