<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // جدول الكوبونات
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();                    // مثل: WELCOME50
            $table->string('name');                              // عرض 50% للعملاء الجدد
            $table->text('description')->nullable();

            $table->enum('type', ['percentage', 'fixed']);      // نسبة مئوية أو مبلغ ثابت
            $table->decimal('value', 12, 4);                     // 50.0000 أو 150.0000

            $table->decimal('min_order_amount', 12, 4)->nullable(); // الحد الأدنى للطلب
            $table->integer('max_uses')->nullable();                // عدد الاستخدامات الكلي (null = لا نهائي)
            $table->integer('max_uses_per_user')->default(1);       // كل مستخدم يستخدمه مرة واحدة فقط

            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();
        });

        // جدول تتبع الاستخدام (اختياري لكن مهم جدًا)
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupon_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // للزوار = null
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index(); // للزوار
            $table->ipAddress('ip_address')->nullable();
            $table->timestamp('used_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupon_usages');
        Schema::dropIfExists('coupons');
    }
};