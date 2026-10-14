<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trademarks extends Model
{
    use HasFactory;
    protected $table = 'trademarks';
    protected $fillable = [
        'name', 
        'name_thumbnail', 
        'name_large', 
        'extension',
        'path'
    ];
}
