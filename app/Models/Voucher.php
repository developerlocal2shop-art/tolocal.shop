<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    protected $table = "voucher";

    protected $fillable = [
        'voucher_name', 
        'voucher_code',
        'discount_type ',
        'discount_value ',
        'minimum_amount',
        'directory_id', 
        'status ',
        'expiry_date ',
        'activation_date ',
        'created_by'
    ];
}
