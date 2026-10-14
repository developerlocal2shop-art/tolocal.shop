<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryConfigguration extends Model
{
    use HasFactory;
    protected $table = 'category_configurations';
    protected $fillable = [
        'category_id', 
        'fields', 
        'created_by'
    ];
}
