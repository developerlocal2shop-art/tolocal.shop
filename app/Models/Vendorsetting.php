<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendorsetting extends Model
{
    use HasFactory;
    protected $table = 'vendor_settings';
    protected $fillable = [
        'flat_rate',
        'shipping_value',
        'directory_id',
        'status',
        'deleted'
    ];
}
