<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketRequest extends Model
{
    use HasFactory;

    public function vendor(){
        return $this->hasOne(User::class,'directory_id','directory_id')->where('parent_id',0);
    }

}
