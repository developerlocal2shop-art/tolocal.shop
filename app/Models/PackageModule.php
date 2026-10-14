<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageModule extends Model
{
    use HasFactory;

    public function getparent()
    {
        $cat = PackageModule::find($this->parent_id);
        return $cat;
    }

}
