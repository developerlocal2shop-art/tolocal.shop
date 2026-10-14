<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryTags extends Model
{
    use HasFactory;
    protected $table = 'directory_tags';
    protected $fillable = [
        'directory_id',
        'tags_id'
    ];
}
