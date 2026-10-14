<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Websitelinks extends Model
{
    use HasFactory;
    protected $table = 'directory_website_links';
    protected $fillable = [
        'directory_id', 
        'name', 
        'link'
    ];
}
