<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryAmenity extends Model
{
    use HasFactory;
    protected $table = 'directory_amenities';
    protected $fillable = [
        'directory_id', 
        'amenities_id'
    ];
}
