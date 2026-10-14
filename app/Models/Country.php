<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $table = 'countries';
    protected $fillable = [
        'name', 
        'state_id'
    ];

    public function cities(){
        return $this->hasManyThrough(City::class, State::class);
    }

    public function states(){
        return $this->hasMany(State::class);
    }

    public static function findByIdOrName($identifier)
    {
        return self::where(function ($query) use ($identifier) {
            if (is_numeric($identifier)) {
                $query->where('id', $identifier);
            } else {
                $query->where('name', $identifier);
            }
        })->first();
    }


}
