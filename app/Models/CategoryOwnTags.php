<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOwnTags extends Model
{
    use HasFactory;
    protected $table = 'category_own_tags';
    protected $fillable = [
        'category_id', 
        'type',
        'tags', 
        'status', 
        'created_by'
    ];
}
