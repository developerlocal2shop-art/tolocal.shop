<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferImage extends Model
{
    use HasFactory;
    protected $table = 'offer_image';
    protected $fillable = [
        'offer_id', 
        'main_image', 
        'name_thumbnail',
        'name_large',
        'extension',
        'path'
    ];
}
