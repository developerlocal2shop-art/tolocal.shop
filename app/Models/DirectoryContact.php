<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryContact extends Model
{
    /* email phone facebook twitter linkedin pinterest whatsapp instagram tiktok youtube snapchat */
    use HasFactory;
    protected $table = 'directory_contact';
    protected $fillable = [
        'directory_id', 
        'website',
        'email',
        'phone',
        'facebook',
        'twitter',
        'linkedin',
        'pinterest',
        'whatsapp',
        'instagram',
        'tiktok',
        'youtube',
        'snapchat'
    ];
}
