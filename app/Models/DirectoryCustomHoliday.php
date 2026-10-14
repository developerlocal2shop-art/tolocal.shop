<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryCustomHoliday extends Model
{
    use HasFactory;
    protected $table = 'directory_custom_holiday';
    protected $fillable = [
        'directory_id', 
        'note', 
        'custom_date', 
        'custom_time'     
    ];
}
