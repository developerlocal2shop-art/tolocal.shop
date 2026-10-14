<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventsTranslation extends Model
{
    use HasFactory;
    protected $table = 'events_translation';
    protected $fillable = [
        'event_id',
        'category_id', 
        'language_id', 
        'title', 
        'description', 
        'seo_url',
        'created_by'
    ];
}
