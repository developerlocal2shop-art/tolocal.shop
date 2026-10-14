<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    use HasFactory;
    protected $table = 'events';
    protected $fillable = [
        'title',
        'category_id', 
        'content', 
        'deleted', 
        'created_by'
    ];
	
	public function eventgallery(){
        return $this->hasMany(EventGallery::class,'event_id','id');
    }

    public function googleCalendar(){
        return $this->hasOne(EventCalenderRequest::class,'event_id','id');
    }
	
	
}
