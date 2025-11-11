<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // جدول أنواع البانرات
        Schema::create('banner_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // جدول البانرات الرئيسي
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('banner_type_id')->constrained('banner_types');
            $table->integer('section_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->timestamps();

            $table->index(['is_active', 'section_order']);
            $table->index(['start_date', 'end_date']);
        });

        // جدول عناصر البانر (الصور/المنتجات داخل كل بانر)
        Schema::create('banner_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->constrained('banners')->cascadeOnDelete();
            $table->integer('item_order')->default(0);

            $table->string('image_url', 500)->nullable();
            $table->text('image_alt')->nullable();
            $table->string('mobile_image_url', 500)->nullable();

            $table->string('link_url', 500)->nullable();
            $table->string('link_target', 20)->default('_self');
            $table->boolean('is_link_active')->default(true);

            $table->unsignedBigInteger('product_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();

            $table->string('tag_text', 100)->nullable();
            $table->string('tag_color', 20)->nullable();
            $table->string('tag_bg_color', 20)->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['banner_id', 'is_active', 'item_order']);
        });

        // جدول إعدادات السلايدر
        Schema::create('slider_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->unique()->constrained('banners')->cascadeOnDelete();
            $table->boolean('autoplay')->default(true);
            $table->integer('autoplay_delay')->default(5000);
            $table->boolean('loop')->default(true);
            $table->boolean('show_navigation')->default(true);
            $table->boolean('show_pagination')->default(true);
            $table->integer('slides_per_view')->default(1);
            $table->integer('space_between')->default(10);
            $table->json('breakpoints')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // جدول Grid Layout
        Schema::create('banner_grid_layout', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_id')->unique()->constrained('banners')->cascadeOnDelete();
            $table->string('grid_type', 50)->nullable(); // single, double, triple, etc.
            $table->integer('desktop_columns')->default(1);
            $table->integer('tablet_columns')->default(1);
            $table->integer('mobile_columns')->default(1);
            $table->integer('row_gap')->default(10);
            $table->integer('column_gap')->default(10);
            $table->timestamp('created_at')->useCurrent();
        });

        // جدول الأكواد الترويجية
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->text('description')->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        // ربط البانرات بالأكواد الترويجية
        Schema::create('banner_promo_codes', function (Blueprint $table) {
            $table->foreignId('banner_item_id')->constrained('banner_items')->cascadeOnDelete();
            $table->foreignId('promo_code_id')->constrained('promo_codes')->cascadeOnDelete();
            $table->primary(['banner_item_id', 'promo_code_id']);
        });

        // جدول الإحصائيات
        Schema::create('banner_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('banner_item_id')->constrained('banner_items')->cascadeOnDelete();
            $table->integer('views_count')->default(0);
            $table->integer('clicks_count')->default(0);
            $table->date('date');
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['banner_item_id', 'date']);
            $table->index(['date', 'banner_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_analytics');
        Schema::dropIfExists('banner_promo_codes');
        Schema::dropIfExists('promo_codes');
        Schema::dropIfExists('banner_grid_layout');
        Schema::dropIfExists('slider_settings');
        Schema::dropIfExists('banner_items');
        Schema::dropIfExists('banners');
        Schema::dropIfExists('banner_types');
    }
};
