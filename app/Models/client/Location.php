<?php

namespace App\Models\client;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Location extends Model
{
    use HasFactory;
    protected $table = 'location_details';
    protected $fillable = [
        'id',
        'user_id', 
        'user_ip',
        'day',
        'date',
        'time',
        'timezone'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
     }
}
