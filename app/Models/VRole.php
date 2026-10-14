<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VRole extends Model
{
    protected $guarded = [];

    public function permissions() {

        return $this->belongsToMany(Permission::class,'v_roles_permissions');
            
     }

 
     public function modules() {
      return $this->belongsToMany(VModule::class,'v_role_modules','role_id','module_id');
     }
     
     public function users() {
     
        return $this->belongsToMany(User::class,'users_roles');
            
     }
}
