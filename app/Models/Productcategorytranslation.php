<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productcategorytranslation extends Model
{
    use HasFactory;
    protected $table = 'product_category_translations';
    protected $fillable = [
        'product_category_id',
        'language_id',
        'title',
        'description',
        'seo_url',
        'created_by'
    ];
}
