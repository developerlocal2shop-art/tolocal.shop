<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionWinners extends Model
{
    use HasFactory;
    protected $fillable = [
        'advertisement_id',
        'customer_id',
        'vendor_id',
        'question_id',
        'type',
        'prize_description',
        'voucher_code',
        'prize_amount',
        'type'
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'customer_id','id');
    }

    public function question()
    {
        return $this->belongsTo(AdvertisementQuestions::class);
    }

    public function answer()
    {
        return $this->belongsTo(AdvertisementAnswers::class,'question_id','question_id');
    }

}
