<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfferTranslation extends Model
{
    use HasFactory;
    protected $table = 'offer_translations';
    protected $fillable = [
        'offer_id',
        'language_id',
        'title',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',

    ];
}
