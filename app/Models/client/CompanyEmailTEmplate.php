<?php

namespace App\Models\client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyEmailTEmplate extends Model
{
    use HasFactory;
    protected $table = 'vendor_email_template';
    protected $fillable = [
        'template_name', 
        'template_design'
    ];
}
