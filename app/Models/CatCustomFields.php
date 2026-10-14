<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CatCustomFields extends Model
{
    use HasFactory;
    protected $table = 'category_fields';
    protected $fillable = [
        'label', 
        'name', 
        'type',
        'created_by'
    ];
}
