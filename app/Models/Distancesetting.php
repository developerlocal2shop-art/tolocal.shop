<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distancesetting extends Model
{
    use HasFactory;
    protected $table = 'distance_setting';
    protected $fillable = [
        'from_distance',
        'to_distance', 
        'status'
    ];
}
