<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExceptionalHolidayTemplateDetails extends Model
{
    use HasFactory;
    protected $table = 'exceptionalholidaytemplate_details';
    protected $fillable = [
        'template_id', 
        'note', 
        'custom_date', 
        'custom_time',
        'created_by'     
    ];
}
