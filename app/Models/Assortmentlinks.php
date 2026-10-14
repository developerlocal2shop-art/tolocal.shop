<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assortmentlinks extends Model
{
    use HasFactory;
    protected $table = 'directory_assortment_links';
    protected $fillable = [
        'directory_id', 
        'name', 
        'link'
    ];
}
