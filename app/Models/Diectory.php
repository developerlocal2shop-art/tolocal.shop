<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diectory extends Model
{
    use HasFactory;
    protected $table = 'directory';
    protected $fillable = [
        'title',
        'description', 
        'feature_type', 
        'google_analytics_id',
        'claim',
        'created_by'
    ];

    public function payouts()
    {
        return $this->hasMany(VendorPayout::class);
    }
    public function user(){
        return $this->hasOne(User::class,'directory_id','id');
    }

    public function directoryTags()
    {
        return $this->hasMany(DirectoryTags::class, 'directory_id', 'id');
    }

    public function directoryLanguage(){
        return $this->hasMany(DiectoryTranslation::class,'directory_id','id');
    }

}
