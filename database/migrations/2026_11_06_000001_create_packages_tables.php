<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('packages')) {
            Schema::create('packages', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->nullable()->unique();
                $table->text('description')->nullable();
                $table->string('image')->nullable();
                $table->string('background_image')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('package_products')) {
            Schema::create('package_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('package_id')->constrained('packages')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
                // main = منتج أساسي (له حد أدنى وسعر أساسي) / optional = مرشح
                $table->string('type', 20)->default('main');
                $table->unsignedInteger('base_quantity')->default(0);
                $table->decimal('base_price', 10, 2)->default(0);
                $table->unsignedInteger('order')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('package_product_tiers')) {
            Schema::create('package_product_tiers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('package_product_id')->constrained('package_products')->cascadeOnDelete();
                // للأساسي: كمية إضافية تُضاف على الحد الأدنى
                // للمرشح: الكمية المطلوبة
                $table->unsignedInteger('quantity')->default(0);
                $table->decimal('price', 10, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('package_product_tiers');
        Schema::dropIfExists('package_products');
        Schema::dropIfExists('packages');
    }
};
