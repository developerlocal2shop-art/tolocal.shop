<?php

namespace App\Models\client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmail extends Model
{
    use HasFactory;
    protected $table = 'company_email';
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
