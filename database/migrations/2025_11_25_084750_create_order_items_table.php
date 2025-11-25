<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->foreignId('size_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('color_id')->nullable()->constrained()->nullOnDelete();
                $table->foreignId('printing_method_id')->nullable()
                      ->constrained('printing_methods')->nullOnDelete();
                
                // عدد مواقع الطباعة والتطريز
                $table->integer('print_locations_count')->default(1);
                $table->integer('embroider_locations_count')->default(0);
                
                // الكمية والسعر
                $table->integer('quantity');
                $table->decimal('price_per_unit', 10, 4);
                $table->decimal('total_price', 12, 4);
                
                // هل هي عينة؟
                $table->boolean('is_sample')->default(false);
                
                // خيارات إضافية (JSON)
                $table->json('options')->nullable();
                
                $table->timestamps();
                
                $table->index('order_id');
                $table->index('product_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
