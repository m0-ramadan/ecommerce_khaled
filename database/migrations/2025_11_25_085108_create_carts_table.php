<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // جدول السلة الرئيسية
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // للزوار = null
            $table->string('session_id')->nullable()->index(); // للزوار غير المسجلين
            $table->decimal('subtotal', 12, 4)->default(0);
            $table->decimal('total', 12, 4)->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'session_id']); // واحدة لكل مستخدم أو session
        });

        // عناصر السلة
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->foreignId('size_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('color_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('printing_method_id')->nullable()->constrained('printing_methods')->nullOnDelete();

            $table->json('print_locations')->nullable();
            $table->json('embroider_locations')->nullable();
            $table->json('selected_options')->nullable();

            $table->foreignId('design_service_id')->nullable()->constrained('design_services')->nullOnDelete();

            $table->text('note')->nullable();
            $table->text('quantity_id')->nullable();
            $table->text('image_design')->nullable();

            $table->integer('quantity')->default(1);
            $table->decimal('price_per_unit');
            $table->decimal('line_total', 12, 4);

            $table->boolean('is_sample')->default(false);

            // المفتاح اللي يمنع تكرار نفس العنصر بالتخصيص
            $table->string('hash_key')->index();

            $table->timestamps();

            $table->unique(['cart_id', 'hash_key'], 'unique_cart_item');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('carts');
    }
};
