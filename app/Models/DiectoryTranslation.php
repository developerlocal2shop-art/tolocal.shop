<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiectoryTranslation extends Model
{
    use HasFactory;
    protected $table = 'directory_basic_translation';
    protected $fillable = [
        'directory_id', 
        'language_id', 
        'title', 
        'description',
        'we_offer',
        'about_us'
    ];
}
