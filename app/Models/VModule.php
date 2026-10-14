<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VModule extends Model
{
    use HasFactory;
	protected $guarded = [];

    public function roles() {
        return $this->belongsToMany(Role::class,'role_modules');
    }

     public function users() {
        return $this->belongsToMany(User::class,'users_modules');
     }
}
