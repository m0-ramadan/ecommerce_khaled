<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // جدول الخامات الأساسية
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الخامة مثل (خشب، حديد، بلاستيك)
            $table->text('description')->nullable(); // وصف إضافي إن وجد
            $table->timestamps();
        });

        // جدول الربط بين المنتجات والخامات
        Schema::create('material_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('material_id')->constrained('materials')->cascadeOnDelete();

            // في حالة وجود كمية أو نسبة استخدام الخامة
            $table->string('unit')->nullable(); // مثلاً "كجم" أو "متر"
            $table->decimal('quantity', 10, 2)->nullable(); // كمية الخامة

            $table->timestamps();

            $table->unique(['product_id', 'material_id']); // نفس الخامة لا تتكرر لنفس المنتج
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_product');
        Schema::dropIfExists('materials');
    }
};
