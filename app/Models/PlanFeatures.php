<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFeatures extends Model
{
    use HasFactory;
    protected $table = 'plan_features';
    protected $fillable = [
        'plan_id', 
		'slug',
        'name', 
        'description', 
        'value',
    ];
}
