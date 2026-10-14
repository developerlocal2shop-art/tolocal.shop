<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Amenity extends Model
{
    use HasFactory;
    protected $table = 'amenity';
    protected $fillable = [
        'title', 
        'description', 
        'icon_class', 
        'category_id',
        'status', 
        'deleted', 
        'created_by'
    ];

}
