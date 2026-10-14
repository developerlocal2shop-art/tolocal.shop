<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smtpsetting extends Model
{
    use HasFactory;
    protected $table = 'smtp_setting';
    protected $fillable = [
        'smtp_protocol', 
        'smtp_host', 
        'smtp_port',
        'smtp_user',
        'smtp_password',
        'created_by'
    ];
}
