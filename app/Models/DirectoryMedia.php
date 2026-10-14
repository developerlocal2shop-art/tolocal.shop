<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryMedia extends Model
{
    use HasFactory;
    protected $table = 'directory_media';
    protected $fillable = [
        'directory_id', 
        'video_provider',
        'video_url',
        'name_thumbnail',
        'name_large',
        'extension',
        'path'
    ];
}
