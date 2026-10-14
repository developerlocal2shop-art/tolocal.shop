<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productcategory extends Model
{
    use HasFactory;
    protected $table = 'product_category';
    protected $fillable = [
        'parent_id',
        'upload_id',
        'name',
        'feature',
        'description',
        'status',
        'deleted'
    ];

    public function childs()
    {
    	return $this->hasMany(Productcategory::class, 'parent_id');
    }

    public function parent()
    {
    	return $this->belongsTo(Productcategory::class, 'parent_id');
    }
}
