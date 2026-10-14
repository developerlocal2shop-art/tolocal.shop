<?php

namespace App\Models\client;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeCounters extends Model
{
    use HasFactory;
    protected $table = 'counters_like';
    protected $fillable = [
        'id',
        'ip', 
        'module_id',
        'type',
        'country_code',
        'state',
        'city',
        'user_id',
        'created_at'
    ];
}
