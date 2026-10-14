<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryExceptionHoliday extends Model
{
    use HasFactory;
    protected $table = 'directory_exceptional';
    protected $fillable = [
        'directory_id', 
        'note', 
        'day', 
        'closed', 
        'custom_date', 
        'custom_time'     
    ];
}
