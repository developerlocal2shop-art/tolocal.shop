<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductcategoryUploads extends Model
{
    use HasFactory;
    protected $table = 'product_category_uploads';
    protected $fillable = [
        'name_thumbnail',
        'name_large',
        'extension',
        'path',
        'deleted',
        'created_by'
    ];
}
