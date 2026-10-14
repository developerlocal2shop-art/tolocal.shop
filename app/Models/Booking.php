<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = 'appointment_booking';
    protected $fillable = [
        'directory_id', 
        'user_id', 
        'name', 
        'email', 
        'adult', 
        'child', 
        'message', 
        'status'
    ];
}
