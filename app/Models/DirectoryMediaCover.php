<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryMediaCover extends Model
{
    use HasFactory;
    protected $table = 'directory_media_cover';
    protected $fillable = [
        'directory_id',
        'position',
        'name_thumbnail',
        'name_large',
        'extension',
        'path',
        'title',
        'description',
        'link'
    ];
}
