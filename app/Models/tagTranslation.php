<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tagTranslation extends Model
{
    use HasFactory;
     protected $fillable = [
        'tag_id',
        'category_id',
        'language_id',
        'title',
        'description',
        'seo_url',
        'directory_id',
        'created_by',
    ];
}
