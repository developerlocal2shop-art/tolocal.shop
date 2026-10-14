<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $fillable = [
        'name',
        'seo_url',
        'sku',
        'directory_id',
        'stock',
        'outofstock',
        'status',
        'created_by'
    ];

    public function image(){
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'page_id', 'id');
    }

    public function price(){
        return $this->hasOne(ProductPrice::class, 'product_id', 'id');
    }

    public function directory(){
        return $this->hasOne(DiectoryTranslation::class, 'directory_id', 'directory_id');
    }

    public function directorySlug(){
        return $this->belongsTo(Diectory::class, 'directory_id','id');
    }

    public function vendorSupplier(){
        return $this->hasOne(VendorSupplierDetail::class, 'product_id', 'id');
    }

    public function user(){
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function useraddress(){
        return $this->hasOne(UserAddress::class, 'user_id', 'created_by');
    }

    public function gallery(){
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function attributes(){
        return $this->hasMany(ProductAttributes::class, 'product_id', 'id');
    }

    public function variations(){
        return $this->hasMany(ProductAttributesVariation::class, 'product_id', 'id');
    }

    public function subcat(){
        return $this->hasMany(ProductCategories::class, 'product_id', 'id');
    }

    public function video(){
        return $this->hasMany(ProductVideo::class, 'product_id', 'id');
    }
    

}
