<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorCompetitionList extends Model
{
    use HasFactory;

    protected $table = 'vendor_competition_list';

    protected $fillable = [
        'competition_id',
        'company_id',
        'title',
        'discount_type',
        'discount_amount',
        'status',
        'longitude',
        'latitude',
    ];

    public function competition()
    {
        return $this->belongsTo(Advertisement::class, 'competition_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Diectory::class, 'company_id', 'id');
    }
}
