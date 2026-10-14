<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Defaultimagesadmin extends Model
{
    use HasFactory;
    protected $table = 'default_image';
    protected $fillable = [
        'section_title',
        'image1', 
        'image2',
        'image_name'
    ];
}
