<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PlanSubscription extends Model
//class PlanSubscription extends \Rinvex\Subscriptions\Models\PlanSubscription
{
    use HasFactory;
    protected $table = 'plan_subscriptions';

    public function subscriptionsUses(){
        return $this->hasMany(PlanSubscriptionUsage::class,'subscription_id','id');
    }

    public function plan(){
        return $this->hasOne(Plans::class,'id','plan_id');
    }

    public function directoryName(){
        return $this->hasOne(Diectory::class, 'id','subscriber_id');
    }

    public function userData(){
        return $this->hasMany(User::class, 'directory_id','subscriber_id');
    }
	
	public function canUseFeature(string $featureSlug): bool
    {
        //dd($featureSlug);
        $featureValue = $this->getFeatureValue($featureSlug);
        $usage = $this->usage()->byFeatureSlug($featureSlug)->first();

        if ($featureValue === 'true') {
            return true;
        }

        if(!$usage){
            $usage = new PlanSubscriptionUsage();
        }

        // If the feature value is zero, let's return false since
        // there's no uses available. (useful to disable countable features)
        if ($usage->expired() || is_null($featureValue) || $featureValue === '0' || $featureValue === 'false') {
            return false;
        }

        // Check for available uses
        return $this->getFeatureRemainings($featureSlug) > 0;
    }

    public function getFeatureUsage(string $featureSlug): int
    {
        //$usage = $this->usage()->byFeatureSlug($featureSlug)->first();
        $usage = $this->usage()->byFeatureSlug($featureSlug)->firstOrFail();
        if(!$usage){
           return 0;
        }
        return ! $usage->expired() ? $usage->used : 0;
    }


	
}
