<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * حقول التحكم في المنتجات المعروضة داخل أقسام الصفحة الرئيسية:
     * - show_in_home: إظهار المنتج أو إخفاؤه من أقسام الرئيسية.
     * - home_order: ترتيب المنتج داخل قسمه في الرئيسية (الأصغر أولاً).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'show_in_home')) {
                $table->boolean('show_in_home')->default(true)->after('status_id');
            }

            if (! Schema::hasColumn('products', 'home_order')) {
                $table->unsignedInteger('home_order')->default(0)->after('show_in_home');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'home_order')) {
                $table->dropColumn('home_order');
            }

            if (Schema::hasColumn('products', 'show_in_home')) {
                $table->dropColumn('show_in_home');
            }
        });
    }
};
