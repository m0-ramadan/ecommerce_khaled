<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favourites'; // اسم الجدول

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * 🔹 العلاقة مع المستخدم
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🔹 العلاقة مع المنتج
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
