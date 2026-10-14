<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorySeo extends Model
{
    use HasFactory;
    protected $table = 'directory_seo';
    protected $fillable = [
        'directory_id', 
        'meta_keyword',
        'meta_description'
    ];
}
