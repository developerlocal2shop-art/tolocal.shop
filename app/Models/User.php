<?php

namespace App\Models;

use App\Permissions\HasVPermissionsTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasVPermissionsTrait;

    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'phone',
        'role_id',
        'status',
        'country',
        'state',
        'city',
        'original_password',
        'address',
        'paypal_email',
        'paypal_merchant_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

	public function roles() {
        return $this->belongsToMany(VRole::class,'users_roles','user_id','role_id');
    }

    public function permissions() {
        return $this->belongsToMany(VPermission::class,'users_permissions','user_id','permission_id');
    }

	public function modules() {
        return $this->belongsToMany(VModule::class,'users_modules','user_id','module_id');
    }

    public function UseFeatures() {
        return $this->belongsToMany(PlanFeatures::class,'plan_subscriptions','subscriber_id','plan_id');
    }

    public function affiliateWallet()
    {
        return $this->hasOne(AffiliateWallet::class);
    }

}
