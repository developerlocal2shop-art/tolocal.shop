<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PlanSubscriptionUsage extends Model
//class PlanSubscriptionUsage extends \Rinvex\Subscriptions\Models\PlanSubscriptionUsage
{
    use HasFactory;
    protected $table = 'plan_subscription_usage';
    protected $fillable = [
        'subscription_id', 
		'feature_id',
        'used', 
        'valid_until', 
        'timezone',
		'created_at'
    ];
	
	public function scopeByFeatureSlug(Builder $builder, $featureSlug)
    {
        $featureId = PlanFeatures::where('slug', $featureSlug)->value('id');

        return $builder->where('feature_id', $featureId);
    }
    
	
}
