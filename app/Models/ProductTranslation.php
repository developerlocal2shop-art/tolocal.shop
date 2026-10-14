<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    use HasFactory;
    protected $table = 'product_translation';
    protected $fillable = [
        'product_id', 
        'language_id', 
        'name',
        'short_content',
        'long_content',
        'meta_title',
        'meta_description',

    ];
}
