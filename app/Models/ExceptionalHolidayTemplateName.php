<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExceptionalHolidayTemplateName extends Model
{
    use HasFactory;
    protected $table = 'exeptionalholidaytemplate_name';
    protected $fillable = [
        'name', 
        'status', 
        'directory_id',
        'deleted', 
        'created_by'
    ];
}
