<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    use HasFactory;

    protected $table = "claim";

    protected $fillable = [
        'directory_id',
        'subject',
        'description',
        'status'
    ];
}
