<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminEmail extends Model
{
    use HasFactory;
    protected $table = 'admin_email';
    protected $fillable = [
        'to_email', 
        'subject', 
        'campaign_id',
        'template_id', 
        'content',
        'status', 
        'send_date', 
    ];
}
