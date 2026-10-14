<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $guarded = [];

    public function roles() {

        return $this->belongsToMany(Role::class,'roles_permissions');

     }

     public function admins() {

        return $this->belongsToMany(Admin::class,'admin_permissions');

     }
}
