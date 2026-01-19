<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // جدول مدن السعودية
        Schema::create('saudi_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name_ar')->comment('اسم المدينة بالعربية');
            $table->string('name_en')->nullable()->comment('اسم المدينة بالإنجليزية');
            $table->string('region_ar')->nullable()->comment('المنطقة بالعربية');
            $table->string('region_en')->nullable()->comment('المنطقة بالإنجليزية');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('oto_city_code')->nullable()->comment('كود المدينة في نظام OTO');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // جدول الأحياء/المناطق
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('saudi_cities')->onDelete('cascade');
            $table->string('name_ar')->comment('اسم الحي/المنطقة بالعربية');
            $table->string('name_en')->nullable()->comment('اسم الحي/المنطقة بالإنجليزية');
            $table->string('oto_district_code')->nullable()->comment('كود المنطقة في نظام OTO');
            $table->string('postal_code')->nullable();
            $table->string('additional_code')->nullable()->comment('كود إضافي للحي');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // جدول خدمات الشحن
        Schema::create('shipping_services', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->comment('كود الخدمة');
            $table->string('name_ar')->comment('اسم الخدمة بالعربية');
            $table->string('name_en')->nullable()->comment('اسم الخدمة بالإنجليزية');
            $table->text('description_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->decimal('base_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable()->comment('ميزات الخدمة');
            $table->integer('delivery_days')->nullable()->comment('أيام التوصيل المتوقعة');
            $table->timestamps();
        });

        // جدول أوامر الشحن
        Schema::create('shipping_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('oto_order_id')->unique()->nullable()->comment('رقم الطلب في نظام OTO');
            $table->string('oto_tracking_number')->nullable()->comment('رقم التتبع في OTO');
            $table->foreignId('shipping_service_id')->constrained('shipping_services');
            $table->string('status')->default('pending')->comment('حالة الشحن: pending, created, picked_up, in_transit, delivered, cancelled');
            
            // بيانات المرسل
            $table->string('sender_name');
            $table->string('sender_phone');
            $table->string('sender_email')->nullable();
            $table->string('sender_city');
            $table->string('sender_district');
            $table->text('sender_address');
            $table->string('sender_postal_code')->nullable();
            
            // بيانات المستلم
            $table->string('receiver_name');
            $table->string('receiver_phone');
            $table->string('receiver_email')->nullable();
            $table->string('receiver_city');
            $table->string('receiver_district');
            $table->text('receiver_address');
            $table->string('receiver_postal_code')->nullable();
            
            // تفاصيل الشحنة
            $table->integer('pieces_count')->default(1);
            $table->decimal('weight', 10, 3)->comment('الوزن بالكيلو جرام');
            $table->decimal('length', 10, 2)->nullable()->comment('الطول بالسم');
            $table->decimal('width', 10, 2)->nullable()->comment('العرض بالسم');
            $table->decimal('height', 10, 2)->nullable()->comment('الارتفاع بالسم');
            $table->decimal('declared_value', 10, 2)->comment('القيمة المعلنة');
            $table->string('content_type')->default('other')->comment('نوع المحتويات');
            $table->text('content_description')->nullable()->comment('وصف المحتويات');
            
            // معلومات الدفع
            $table->string('payment_type')->default('cash_on_delivery')->comment('نوع الدفع: cash_on_delivery, prepaid, credit');
            $table->decimal('shipping_cost', 10, 2);
            $table->decimal('cash_on_delivery_amount', 10, 2)->default(0);
            $table->decimal('insurance_amount', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // معلومات إضافية
            $table->text('notes')->nullable();
            $table->json('oto_response')->nullable()->comment('استجابة OTO الكاملة');
            $table->json('oto_labels')->nullable()->comment('ملصقات الشحن');
            $table->timestamp('estimated_delivery_date')->nullable();
            $table->timestamp('actual_delivery_date')->nullable();
            $table->timestamps();
        });

        // جدول تحديثات حالة الشحن
        Schema::create('shipment_tracking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipping_order_id')->constrained('shipping_orders')->onDelete('cascade');
            $table->string('status');
            $table->string('status_ar')->nullable()->comment('الحالة بالعربية');
            $table->text('description')->nullable();
            $table->text('description_ar')->nullable()->comment('الوصف بالعربية');
            $table->string('location')->nullable()->comment('الموقع');
            $table->timestamp('event_date');
            $table->json('oto_data')->nullable()->comment('بيانات إضافية من OTO');
            $table->timestamps();
        });

        // جدول أسعار الشحن
 Schema::create('shipping_prices', function (Blueprint $table) {
    $table->id();
    $table->foreignId('from_city_id')->constrained('saudi_cities');
    $table->foreignId('to_city_id')->constrained('saudi_cities');
    $table->foreignId('shipping_service_id')->constrained('shipping_services');
    $table->decimal('base_price', 10, 2);
    $table->decimal('per_kg_price', 10, 2)->default(0);
    $table->decimal('cod_percentage', 5, 2)->default(0);
    $table->decimal('insurance_percentage', 5, 2)->default(0);
    $table->integer('estimated_days');
    $table->boolean('is_active')->default(true);
    $table->timestamps();

    $table->unique(
        ['from_city_id', 'to_city_id', 'shipping_service_id'],
        'shipping_prices_route_service_unique'
    );
});

    }

    public function down()
    {
        Schema::dropIfExists('shipment_tracking');
        Schema::dropIfExists('shipping_orders');
        Schema::dropIfExists('shipping_prices');
        Schema::dropIfExists('shipping_services');
        Schema::dropIfExists('districts');
        Schema::dropIfExists('saudi_cities');
    }
};