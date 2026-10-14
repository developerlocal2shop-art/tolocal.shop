<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;
    protected $table = 'advertisement';
    protected $fillable = [
        'heading',
        'image_name',
        'link',
        'deleted'
    ];

    public function competitionVendors()
    {
        return $this->hasOne(CompetitionVendors::class, 'competition_id');
    }

    public function questions() {
        return $this->hasMany(AdvertisementQuestions::class);
    }

    public function directory(){
        return $this->belongsTo(DiectoryTranslation::class, 'directory_id','directory_id');
    }
}
