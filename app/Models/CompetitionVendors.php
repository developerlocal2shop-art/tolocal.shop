<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitionVendors extends Model
{
    use HasFactory;
    protected $table = 'competition_vendors';
    protected $fillable = [
        'competition_id',
        'directory_id',
        'vendor_name',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'competition_id');
    }

}
