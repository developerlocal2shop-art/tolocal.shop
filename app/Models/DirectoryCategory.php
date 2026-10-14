<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryCategory extends Model
{
    /* email phone facebook twitter linkedin pinterest whatsapp instagram tiktok youtube snapchat */
    use HasFactory;
    protected $table = 'directory_category';
    protected $fillable = [
        'directory_id',
        'category_id'
    ];
}
