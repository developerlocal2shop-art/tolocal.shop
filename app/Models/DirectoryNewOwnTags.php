<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryNewOwnTags extends Model
{
    use HasFactory;
    protected $table = 'directory_newown_tags';
    protected $fillable = [
        'directory_id',
        'owntags_id'
    ];
}
