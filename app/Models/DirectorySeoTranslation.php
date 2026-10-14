<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorySeoTranslation extends Model
{
    use HasFactory;
    protected $table = 'directory_seo_translation';
    protected $fillable = [
        'directory_id', 
        'language_id',
        'meta_keyword',
        'meta_description'
    ];
}
