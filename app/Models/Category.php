<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //use HasFactory;
    protected $table = 'category';
    protected $fillable = [
        'parent_id', 
        'upload_id', 
        'name', 
        'category_type_id', 
        'feature',
        'description', 
        'icon_class', 
        'status', 
        'deleted'
    ];

    public function childs()
    {
    	return $this->hasMany(Category::class, 'parent_id');
    }

    public function parent()
    {
    	return $this->belongsTo(Category::class, 'parent_id');
    }
    
}
