<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fixed editorial policy for design services:
     * - "لدي تصميم يحتاج تعديل" (redesign) => 100 SAR
     * - "طلب خدمة تصميم" (new design)        => 350 SAR
     */
    public function up(): void
    {
        $designOptionNames = ['خدمة التصميم', 'خدمة تصميم'];

        // إعادة التصميم / تعديل تصميم
        DB::table('product_options')
            ->whereIn('option_name', $designOptionNames)
            ->where('option_value', 'like', '%تعديل%')
            ->update([
                'option_value' => 'لدي تصميم يحتاج تعديل',
                'additional_price' => 100,
            ]);

        // طلب خدمة تصميم جديدة
        DB::table('product_options')
            ->whereIn('option_name', $designOptionNames)
            ->where(function ($query) {
                $query->where('option_value', 'like', '%طلب خدمة تصميم%')
                    ->orWhere('option_value', 'like', '%احتاج خدمة تصميم%');
            })
            ->update([
                'option_value' => 'طلب خدمة تصميم',
                'additional_price' => 350,
            ]);

        // الخيارات الجاهزة (رفع تصميم) تبقى مجانية
        DB::table('product_options')
            ->whereIn('option_name', $designOptionNames)
            ->where(function ($query) {
                $query->where('option_value', 'like', '%رفع تصميم%')
                    ->orWhere('option_value', 'like', '%لدي تصميم خاص%')
                    ->orWhere('option_value', 'like', '%لدى تصميم خاص%');
            })
            ->update(['additional_price' => 0]);

        // نفس القيم في جدول خدمات التصميم المستخدم في API (بدون تغيير الأسماء
        // حتى لا نفقد ربط عناصر الطلبات القديمة design_service_id)
        DB::table('design_services')
            ->where('name', 'like', '%تعديل%')
            ->update(['price' => 100]);

        DB::table('design_services')
            ->where(function ($query) {
                $query->where('name', 'like', '%طلب خدمة تصميم%')
                    ->orWhere('name', 'like', '%احتاج خدمة تصميم%');
            })
            ->update(['price' => 350]);

        DB::table('design_services')->where('name', 'طلب خدمة تصميم')->update(['price' => 350]);
        DB::table('design_services')->where('name', 'لدي تصميم يحتاج تعديل')->update(['price' => 100]);
    }

    public function down(): void
    {
        // لا نرجع الأسعار القديمة المتغيرة.
    }
};
