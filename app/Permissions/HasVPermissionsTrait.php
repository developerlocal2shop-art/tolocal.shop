<?php

namespace App\Permissions;

use App\Models\PlanFeatures;
use App\Models\PlanSubscription;
use App\Models\VModule;
use App\Models\VPermission;
use App\Models\VRole;

trait HasVPermissionsTrait {

   public function givePermissionsTo(... $permissions) {
        $permissions = $this->getAllPermissions($permissions);
        //dd($permissions);
        if($permissions === null) {
        return $this;
        }
        $this->permissions()->saveMany($permissions);
        return $this;
    }

  public function withdrawPermissionsTo( ... $permissions ) {
    $permissions = $this->getAllPermissions($permissions);
    $this->permissions()->detach($permissions);
    return $this;
  }

  public function refreshPermissions( ... $permissions ) {
    $this->permissions()->detach();
    return $this->givePermissionsTo($permissions);
  }

  public function hasPermissionTo($permission) {
    return $this->hasPermissionThroughRole($permission) || $this->hasPermission($permission);
  }

  public function hasPermissionThroughRole($permission) {
    foreach ($permission->roles as $role){
      if($this->roles->contains($role)) {
        return true;
      }
    }
    return false;
  }

  //check has role
  public function hasRole( ... $roles ) {
    foreach ($roles as $role) {
      if ($this->roles->contains('slug', $role)) {
        return true;
      }
    }
    return false;
  }


  public function roles() {
    return $this->belongsToMany(VRole::class,'users_roles');
  }

  public function permissions() {
    return $this->belongsToMany(VPermission::class,'users_permissions');
  }

  //check has permission
  public function hasPermission($permission) {
    return (bool) $this->permissions->where('slug', $permission)->count();
  }

  public function modules() {
    return $this->belongsToMany(VModule::class,'users_modules');
  }
  //check has module
  public function hasModule($module) {
    return (bool) $this->modules->where('slug', $module)->count();
  }
  

  protected function getAllPermissions(array $permissions) {
    return VPermission::whereIn('slug',$permissions)->get();
  }

}
