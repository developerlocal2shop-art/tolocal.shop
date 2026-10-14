<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    use HasFactory;
    protected $table = 'category_tags';
    protected $fillable = [
        'category_id', 
        'child_cat_id',
        'tags', 
        'status', 
        'created_by'
    ];
}
