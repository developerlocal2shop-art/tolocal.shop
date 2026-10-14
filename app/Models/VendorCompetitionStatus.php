<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCompetitionStatus extends Model
{
    use HasFactory;

    protected $table = 'vendor_competition_status';

    protected $fillable = [
        'company_id',
        'competition_id',
        'status',
    ];
}
