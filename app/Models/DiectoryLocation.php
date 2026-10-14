<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DiectoryLocation extends Model
{
    use HasFactory;
    protected $table = 'directory_location';
    protected $fillable = [
        'directory_id', 
        'country_id', 
        'state_id', 
        'city_id',
        'address',
        'latitude',
        'longitude',
        'place',
        'city_name'
    ];

    public function directoryloc(){
        return $this->hasOne(DiectoryLocation::class, 'id', 'country_id');
    }
    public function user(){
        return $this->hasOne(User::class, 'directory_id', 'directory_id');
    }
    public function directoryowner(){
        return $this->hasOne(DirectoryContact::class, 'directory_id', 'directory_id');
    }
}
