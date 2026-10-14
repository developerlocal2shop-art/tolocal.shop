<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recaptchasetting extends Model
{
    use HasFactory;
    protected $table = 'recaptcha_setting';
    protected $fillable = [
        'site_key', 
        'secret_key', 
        'created_by'
    ];
}
