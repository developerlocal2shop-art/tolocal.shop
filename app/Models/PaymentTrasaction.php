<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentTrasaction extends Model
{
    use HasFactory;
    protected $table = 'payment';
    protected $fillable = [
        'payment_method', 
        'transaction_id', 
        'api_response',
        'total_amount',
        'currency',
        'payment_date',
        'type',
        'directory_id',
        'planid',
        'package_name'
    ];
}
