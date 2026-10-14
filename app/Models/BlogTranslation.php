<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogTranslation extends Model
{
    use HasFactory;
    protected $table = 'blog_translation';
    protected $fillable = [
        'blog_id',
        'category_id', 
        'language_id', 
        'title', 
        'description', 
        'seo_url',
        'created_by'
    ];
}
