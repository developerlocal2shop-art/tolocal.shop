<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorySubCategory extends Model
{
    use HasFactory;
    protected $table = 'directory_sub_category';
    protected $fillable = [
        'directory_id',
        'sub_category_id'
    ];
}
