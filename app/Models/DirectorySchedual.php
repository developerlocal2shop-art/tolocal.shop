<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectorySchedual extends Model
{
    use HasFactory;
    protected $table = 'directory_schedule';
    protected $fillable = [
        'directory_id', 
        'day',
        'is_closed',
        'open_time',
        'close_time'
        
    ];
}
