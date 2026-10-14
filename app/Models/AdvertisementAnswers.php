<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvertisementAnswers extends Model
{
    use HasFactory;
    protected $table = 'advertisement_answers';
    protected $fillable = [
        'is_correct',
        'advertisement_id',
        'question_id',
        'customer_id',
        'vendor_id',
        'exp_date',
        'prize_description',
        'prize_code',
        'status',
        'answer',
        'auto_reply_status',
        'auto_reply_response',
        'reply_description'
    ];

    public function question() {
        return $this->belongsTo(AdvertisementQuestions::class);
    }

    public function advertisement() {
        return $this->belongsTo(Advertisement::class);
    }

    public function customer() {
        return $this->belongsTo(User::class, 'customer_id');
    }

}
