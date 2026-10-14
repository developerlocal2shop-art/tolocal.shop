<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapsetting extends Model
{
    use HasFactory;
    protected $table = 'map_setting';
    protected $fillable = [
        'map_title', 
        'location', 
        'zoom_level',
        'listing_page_zoom_level',
        'directory_page_zoom_level',
        'created_by'
    ];
}
