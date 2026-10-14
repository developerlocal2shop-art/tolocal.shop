<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Giftvoucher extends Model
{
    use HasFactory;

    protected $table = "gift_voucher";

    protected $fillable = [
        'voucher_name', 
        'voucher_code',
        'voucher_title',
        'voucher_description',
        'website_link',
        'directory_id', 
        'minimum_amount', 
        'expiry_date',
        'activation_date',
        'created_by'
    ];
}
