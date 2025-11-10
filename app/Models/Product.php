<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'description', 'price', 'has_discount', 
        'includes_tax', 'includes_shipping', 'stock','status_id'
    ];

    protected $casts = [
        'has_discount' => 'boolean',
        'includes_tax' => 'boolean',
        'includes_shipping' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function deliveryTime()
    {
        return $this->hasOne(DeliveryTime::class);
    }

    public function warranty()
    {
        return $this->hasOne(Warranty::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    public function getFinalPriceAttribute()
    {
        if ($this->has_discount && $this->discount) {
            if ($this->discount->discount_type === 'percentage') {
                return $this->price - ($this->price * $this->discount->discount_value / 100);
            }
            return $this->price - $this->discount->discount_value;
        }
        return $this->price;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
    public function favouritedBy()
{
    return $this->belongsToMany(\App\Models\User::class, 'favourites');
}

}