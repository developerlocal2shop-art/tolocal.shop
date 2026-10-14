<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingCard extends Model
{
    use HasFactory;
    protected $table = 'shopping_card';
    protected $fillable = [
        'title',
        'description',
        'discount_selectedvalue',
        'discount_firstvalue',
        'discount_secondvalue',
        'selectedvalue_type',
        'directory_link',
        'search_parent_category',
        'search_child_category',
        'tags',
        'active_date',
        'voucher_code',
        'expire_date',
        'created_by',
        'status'
    ];

    public function image(){
        return $this->hasOne(ShoppingCardImage::class, 'shopping_card_id', 'id');
    }

    public function gallery(){
        return $this->hasMany(ShoppingCardImage::class, 'shopping_card_id', 'id');
    }

    public function directory(){
        return $this->belongsTo(DiectoryTranslation::class, 'directory_link','directory_id');
    }

    public function directorySlug(){
        return $this->belongsTo(Diectory::class, 'directory_link','id');
    }

}
