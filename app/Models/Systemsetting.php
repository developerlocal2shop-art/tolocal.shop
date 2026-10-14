<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Systemsetting extends Model
{
    use HasFactory;
    protected $table = 'system_setting';
    protected $fillable = [
        'website_title', 
        'system_title', 
        'meta_keyword',
        'meta_description',
        'system_email',
        'address',
        'phone',
        'country',
        'timezone',
        'system_language',
        'footer_text', 
        'footer_link',
        'created_by'
    ];
}
