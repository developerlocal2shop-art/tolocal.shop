<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorAffiliateSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'enabled',
        'commission_type',
        'commission_rate',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'commission_rate' => 'decimal:2',
    ];
}
