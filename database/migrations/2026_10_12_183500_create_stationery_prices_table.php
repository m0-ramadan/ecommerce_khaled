<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stationery_prices', function (Blueprint $table) {
            $table->id();
            $table->string('service_type'); // 'document' or 'photo'
            $table->string('paper_size');   // 'A0', 'A1', 'A2', 'A3', 'A4', 'A5'
            $table->decimal('bw_price', 10, 2)->default(0.50); // سعر الصفحة أبيض وأسود
            $table->decimal('color_price', 10, 2)->default(1.50); // سعر الصفحة ألوان
            $table->decimal('double_sided_price', 10, 2)->default(0.00); // تكلفة إضافية للوجهين (أو سعر الصفحة للوجهين)
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['service_type', 'paper_size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stationery_prices');
    }
};

