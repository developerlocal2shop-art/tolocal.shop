<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;
    protected $table = 'user_address';
    protected $fillable = [
        'id',
        'user_id', 
        'sameBillingAddress', 
        'first_name', 
        'last_name',
        'phone',
        'email',
        'address1',
        'address2',
        'city',
        'state',
        'country',
        'zipcode',
        'type',
        'created_at'
    ];
}
