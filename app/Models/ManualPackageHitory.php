<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualPackageHitory extends Model
{
    use HasFactory;

    public function vendor(){
        return $this->hasOne(User::class,'directory_id','vendor_id');
    }

    public function plan(){
        return $this->hasOne(Plans::class,'id','package_id');
    }

    public function directoryName(){
        return $this->hasOne(Diectory::class, 'id','vendor_id');
    }
    
}
