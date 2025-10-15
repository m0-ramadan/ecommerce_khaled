<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasTranslations;
    use SoftDeletes;
    public $translatable = [
        'name','name_url',
        'details',
        'delivery_time',
        'shippingcharges',
        'detailsweb'
    ];

    protected $table = 'products';
    public $timestamps = true;

    public $fillable = [
        'name','name_url',
        'current_price',
        'old_price',
        'pagesFooter',
        'details',
        'quantity',
        'sub_category_id',
        'category_id',
        'is_active',
        'store_id',
        'name_search',
        'image',
        'alt_text',
        'title_img',
        'slug',
        'serial_no',
        'tax_amount',
        'delivery_time',
        'aliexpress',
        'keywords',
        'shippingcharges_value',
        'mainprice',
        'original_quantity',
        'mainoldprice','ref_name','url','ref_name1','detailsweb','metadescription'
    ];


    // public function scopeActive($query)
    // {
    //     return $query->where('is_active',true);
    // }

    public function subcategory()
    {
        return $this->belongsTo(
            'App\Models\Subcategory',
            'sub_category_id'
        );
    }

    public function category()
    {
        return $this->belongsTo(
            'App\Models\Category',
            'category_id'
        );
    }

    public function reviews()
    {
        return $this->hasMany(
            'App\Models\Review',
            'product_id'
        );
    }

    public function client()
    {
        return $this->belongsTo(
            'App\Models\Client',
            'client_id'
        );
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function Favorites()
    {
        return $this->hasMany('App\Models\Favorite','product_id');
    }

    public function Rosters()
    {
        return $this->hasMany(
            'App\Models\RosterItem',
            'product_id'
        );
    }

    public function productFeatures()
    {
        return $this->hasMany('App\Models\ProductFeature');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeActive($query, $isActive = true)
    {
        return $query->where('is_active', $isActive);
    }
}
