<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttributes extends Model
{
    protected $fillable = ['id','product_id','attribute_id','attribute_value','created_at','updated_at'];
    use HasFactory;

    public function attribute(){
            return $this->belongsTo(Attribute::class, 'attribute_id','id');
    }

    public function product(){
            return $this->belongsTo(Product::class, 'product_id','id');
    }

}
