<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HolidayTemplateDetails extends Model
{
    use HasFactory;
    protected $table = 'holidaytemplate_details';
    protected $fillable = [
        'template_id', 
        'note', 
        'day',
        'custom_date', 
        'custom_time',
        'closed'     
    ];
}
