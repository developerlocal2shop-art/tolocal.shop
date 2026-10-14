<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectoryClaim extends Model
{
    use HasFactory;
    protected $table = 'directory_claim';
    protected $fillable = [
        'directory_id',
        'claim_subject',
        'claim_description'
    ];
}
