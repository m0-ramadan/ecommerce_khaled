<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('external_shipment_id')->nullable()->unique();
            $table->string('tracking_number')->nullable()->unique();
            $table->string('status')->default('pending');
            $table->string('carrier')->nullable();
            $table->string('service_type')->nullable();
            $table->string('delivery_option_id')->nullable();
            $table->string('delivery_option_name')->nullable();
            $table->timestamp('estimated_delivery_date')->nullable();
            $table->timestamp('actual_delivery_date')->nullable();
            $table->timestamp('pickup_date')->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('width', 8, 2)->nullable();
            $table->decimal('length', 8, 2)->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('insurance_amount', 10, 2)->default(0);
            $table->string('shipping_label_url')->nullable();
            $table->string('tracking_url')->nullable();
            $table->json('shipment_details')->nullable();
            $table->text('notes')->nullable();
            $table->string('failure_reason')->nullable();
            $table->json('sender_info')->nullable();
            $table->json('recipient_info')->nullable();
            $table->decimal('customs_value', 10, 2)->nullable();
            $table->string('customs_currency', 3)->default('SAR');
            $table->integer('package_count')->default(1);
            $table->boolean('is_return')->default(false);
            $table->string('return_reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'estimated_delivery_date']);
            $table->index(['tracking_number', 'carrier']);
            $table->index('order_id');
        });

        Schema::create('shipment_tracking_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->onDelete('cascade');
            $table->string('status');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->timestamp('event_date')->nullable();
            $table->timestamp('event_time')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->text('notes')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();

            $table->index(['shipment_id', 'event_date']);
        });

        Schema::create('delivery_options', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('carrier');
            $table->string('service_type');
            $table->integer('estimated_min_days')->nullable();
            $table->integer('estimated_max_days')->nullable();
            $table->decimal('base_cost', 10, 2);
            $table->string('currency', 3)->default('SAR');
            $table->boolean('is_active')->default(true);
            $table->string('city');
            $table->json('requirements')->nullable();
            $table->json('limitations')->nullable();
            $table->timestamps();

            $table->index(['city', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_tracking_history');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('delivery_options');
    }
};