<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    
    protected $table = "reviews";

    protected $fillable = [
        'user_id', 
        'page',
        'page_id',
        'url', 
        'content',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function photos(){
        return $this->hasMany(ReviewImages::class, 'review_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
