<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class UserRequest extends Model
{


    protected $table = 'user_products';
    public $timestamps = true;

    const REQUEST_STATUS = [
        'pending' => 0,
        'accept' => 1,
        'reject' => 2,
    ];

    public static function getStatusValue(string $name)
    {
        if (in_array($name, self::REQUEST_STATUS)) {
            return self::REQUEST_STATUS[$name];
        }

        return self::REQUEST_STATUS['pending'];
    }

    public $fillable = [
        'client_id',
        'product_id',
        'details',
        'status',
        'is_active',
        'original_quantity',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'product_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
