<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrademarkCategory extends Model
{
    use HasFactory;
    protected $table = 'trademark_category';
    protected $fillable = [
        'name', 
        'description', 
        'status', 
        'deleted'
    ];
}
