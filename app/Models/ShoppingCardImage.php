<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCardImage extends Model
{
    use HasFactory;
    protected $table = 'shopping_card_image';
    protected $fillable = [
        'shopping_card_id',
        'main_image',
        'name_thumbnail',
        'name_large',
        'extension',
        'path'
    ];
}
