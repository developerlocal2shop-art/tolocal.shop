<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plans extends Model
{
    use HasFactory;
    protected $table = 'plans';
    protected $fillable = [
        'slug', 
        'name', 
        'description', 
        'is_active',
        'price',
		'invoice_period',
		'invoice_interval',
		'sort_order'
    ];
	
	public function planfeatures(){
        return $this->hasMany(PlanFeatures::class,'plan_id','id');
    }
	
}
