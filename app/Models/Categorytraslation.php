<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorytraslation extends Model
{
    use HasFactory;
    protected $table = 'category_translations';
    protected $fillable = [
        'category_id', 
        'language_id', 
        'title', 
        'description', 
        'seo_url',
        'created_by'
    ];
}
