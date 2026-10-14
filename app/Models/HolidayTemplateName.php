<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayTemplateName extends Model
{
    use HasFactory;
    protected $table = 'holidaytemplate_name';
    protected $fillable = [
        'name', 
        'status', 
        'deleted', 
        'directory_id',
        'created_by'
    ];
}
