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
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('order_number')->unique();
                $table->string('transaction_id')->unique();
                $table->decimal('subtotal', 12, 4);
                $table->decimal('discount_amount', 12, 4)->default(0);
                $table->decimal('shipping_amount', 12, 4)->default(0);
                $table->decimal('tax_amount', 12, 4)->default(0);
                $table->decimal('total_amount', 12, 4);
                $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
                $table->text('notes')->nullable();
                $table->dateTime('delivered_at')->nullable();
                $table->dateTime('shipped_at')->nullable();
                $table->string('shipping_address')->nullable();
                $table->timestamps();
                $table->softDeletes();
                
                $table->index('user_id');
                $table->index('status');
                $table->index('created_at');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
