<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductTags extends Model
{
    use HasFactory;
    protected $table = 'product_tags';
    protected $fillable = [
        'category_id',
        'child_cat_id',
        'tags',
        'status',
        'created_by'
    ];
}
