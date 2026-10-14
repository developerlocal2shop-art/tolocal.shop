<?php

namespace App\Models\client;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = 'wishlist';
    protected $fillable = [
        'id',
        'user_id', 
        'product_id',
        'created_at'
    ];

    public function user(){
       return $this->belongsTo(User::class,'user_id');
    }

    public function product(){
       return $this->belongsTo(Product::class,'product_id');
    }

    

}
