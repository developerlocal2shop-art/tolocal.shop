<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;
    protected $table = 'offer';
    protected $fillable = [
        'title',
        'description',
        'discount_selectedvalue',
        'discount_firstvalue',
        'discount_secondvalue',
        'offer_type_selectedvalue',
        'offer_product_id',
        'offer_directory_link',
        'search_parent_category',
        'search_child_category',
        'tags',
        'offer_active_date',
        'offer_expire_date',
        'created_by',
        'status'
    ];

    public function directory(){
        return $this->belongsTo(DiectoryTranslation::class, 'offer_directory_link','directory_id');
    }

    public function offerimage(){
        return $this->hasOne(OfferImage::class, 'offer_id', 'id');
    }

    public function directorySlug(){
        return $this->belongsTo(Diectory::class, 'offer_directory_link','id');
    }
}
