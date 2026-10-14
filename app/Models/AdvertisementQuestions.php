<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementQuestions extends Model
{
    use HasFactory;
    protected $fillable = [
        'advertisement_id',
        'company_id',
        'question_text',
    ];

    public function advertisement() {
        return $this->belongsTo(Advertisement::class);
    }

    public function answers() {
        return $this->hasMany(AdvertisementAnswers::class, 'question_id');
    }

}
