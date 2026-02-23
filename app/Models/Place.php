<?php
// app/Models/Place.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'name',
        'parent_id'
    ];

    /**
     * المدينة الرئيسية (للأحياء)
     */
    public function city()
    {
        return $this->belongsTo(Place::class, 'parent_id');
    }

    /**
     * الأحياء التابعة للمدينة
     */
    public function districts()
    {
        return $this->hasMany(Place::class, 'parent_id');
    }

    /**
     * هل هو مدينة؟
     */
    public function isCity()
    {
        return is_null($this->parent_id);
    }

    /**
     * هل هو حي؟
     */
    public function isDistrict()
    {
        return !is_null($this->parent_id);
    }

    /**
     * الاسم الكامل (للعرض)
     */
    public function getDisplayNameAttribute()
    {
        if ($this->isDistrict() && $this->city) {
            return $this->label . ' - ' . $this->city->label;
        }
        return $this->label;
    }

    /**
     * الاسم المرسل لـ OTO API
     */
    public function getOtoNameAttribute()
    {
        return $this->name;
    }

    /**
     * سكوب: المدن فقط
     */
    public function scopeCities($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * سكوب: الأحياء فقط
     */
    public function scopeDistricts($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * سكوب: بحث في الأسماء العربية
     */
    public function scopeSearchArabic($query, $search)
    {
        return $query->where('label', 'like', "%{$search}%");
    }

    /**
     * سكوب: بحث في الأسماء الإنجليزية
     */
    public function scopeSearchEnglish($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    }
}