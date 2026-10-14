<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCardTranslation extends Model
{
    use HasFactory;
    protected $table = 'shoppingcard_translations';
    protected $fillable = [
        'shoppingcard_id',
        'language_id',
        'title',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',

    ];
}
