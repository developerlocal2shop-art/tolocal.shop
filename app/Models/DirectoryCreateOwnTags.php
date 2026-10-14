<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryCreateOwnTags extends Model
{
    use HasFactory;
    protected $table = 'directory_createown_tags';
    protected $fillable = [
        'directory_id', 
        'tag_name', 
        'status',
        'add_to_directory_page', 
        'created_by'     
    ];
}
