<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory;

    public function productAttributes(){
            return $this->belongsTo(ProductAttributes::class, 'id','attribute_id');
    }
    public function AttributeStatus(){
            return $this->belongsTo(AttributeStatus::class, 'id','attribute_id');
    }
}
