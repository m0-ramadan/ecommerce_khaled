<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'type', 'value',
        'min_order_amount', 'max_uses', 'max_uses_per_user',
        'is_active', 'starts_at', 'expires_at'
    ];

    protected $casts = [
        'value' => 'decimal:4',
        'min_order_amount' => 'decimal:4',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // التحقق من صلاحية الكوبون
    public function isValidForOrder(float $orderTotal, $userId = null, $sessionId = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && $this->starts_at->gt(now())) return false;
        if ($this->expires_at && $this->expires_at->lt(now())) return false;
        if ($this->min_order_amount && $orderTotal < $this->min_order_amount) return false;

        // عدد الاستخدامات الكلي
        if ($this->max_uses && $this->usages()->count() >= $this->max_uses) return false;

        // عدد الاستخدامات للمستخدم/الجلسة
        $usedCount = $this->usages()
            ->where(function ($q) use ($userId, $sessionId) {
                $q->where('user_id', $userId)
                  ->orWhere('session_id', $sessionId);
            })->count();

        return $usedCount < $this->max_uses_per_user;
    }

    public function calculateDiscount(float $orderTotal): float
    {
        if ($this->type === 'percentage') {
            return round(($orderTotal * $this->value) / 100, 2);
        }
        return min($this->value, $orderTotal); // لا يتجاوز إجمالي الطلب
    }
}