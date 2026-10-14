<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Normaltemplate extends Model
{
    use HasFactory;
    protected $table = 'normaltemplate';
    protected $fillable = [
        'template_id', 
        'day', 
        'is_closed', 
        'open_time', 
        'close_time',
        'created_by'
    ];
}
