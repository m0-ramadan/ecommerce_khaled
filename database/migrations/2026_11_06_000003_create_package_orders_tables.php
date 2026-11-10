<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('package_orders')) {
            Schema::create('package_orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
                $table->unsignedBigInteger('order_id')->nullable()->index();
                $table->string('order_number')->nullable()->unique();
                $table->string('status', 30)->default('pending');
                $table->string('status_payment', 30)->default('pending');
                $table->string('payment_method', 50)->nullable();
                $table->decimal('items_total', 12, 2)->default(0);
                $table->decimal('shipping_amount', 12, 2)->default(0);
                $table->decimal('total_amount', 12, 2)->default(0);
                $table->string('shipping_address')->nullable();
                $table->string('customer_name')->nullable();
                $table->string('customer_phone')->nullable();
                $table->string('customer_email')->nullable();
                $table->string('delivery_option_name')->nullable();
                $table->string('delivery_option_id')->nullable();
                $table->string('oto_order_id')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('package_order_items')) {
            Schema::create('package_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('package_order_id')->constrained('package_orders')->cascadeOnDelete();
                $table->foreignId('package_id')->nullable()->constrained('packages')->nullOnDelete();
                $table->foreignId('package_product_id')->nullable()->constrained('package_products')->nullOnDelete();
                $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
                $table->string('package_name')->nullable();
                $table->string('product_name')->nullable();
                $table->string('type', 20)->default('main');
                $table->unsignedInteger('quantity')->default(1);
                $table->decimal('unit_price', 12, 2)->default(0);
                $table->decimal('line_total', 12, 2)->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('package_order_items');
        Schema::dropIfExists('package_orders');
    }
};
