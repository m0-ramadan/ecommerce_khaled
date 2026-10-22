<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedInteger('points')->default(0)->after('phone');
            });
        }

        if (! Schema::hasColumn('users', 'phone_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');
            });
        }

        if (! Schema::hasTable('loyalty_settings')) {
            Schema::create('loyalty_settings', function (Blueprint $table) {
                $table->id();
                $table->boolean('is_active')->default(true);
                // عدد النقاط المكتسبة لكل 1 ريال
                $table->decimal('earn_rate', 8, 2)->default(1.00);
                // عدد النقاط المطلوبة لاستبدال 1 ريال
                $table->decimal('redeem_rate', 8, 2)->default(20.00);
                $table->unsignedInteger('min_redeem_points')->default(100);
                $table->decimal('max_redeem_percent', 5, 2)->default(50.00);
                $table->unsignedInteger('points_expiry_days')->nullable()->default(365);
                $table->text('terms')->nullable();
                $table->timestamps();
            });

            DB::table('loyalty_settings')->insert([
                'is_active' => true,
                'earn_rate' => 1.00,
                'redeem_rate' => 20.00,
                'min_redeem_points' => 100,
                'max_redeem_percent' => 50.00,
                'points_expiry_days' => 365,
                'terms' => 'تُكتسب النقاط عند تسليم الطلب، ويمكن استخدامها كخصم عند الشراء. كل 20 نقطة = 1 ريال، وبحد أقصى 50% من قيمة الطلب.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (! Schema::hasTable('point_transactions')) {
            Schema::create('point_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
                $table->string('type', 20); // earn, redeem, adjust, refund, reversal
                $table->integer('points'); // موجب للإضافة وسالب للخصم
                $table->integer('balance_after');
                $table->string('description')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();

                $table->index(['user_id', 'created_at']);
                $table->unique(['order_id', 'type']);
            });
        }

        if (! Schema::hasColumn('orders', 'points_used')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->unsignedInteger('points_used')->default(0)->after('discount_amount');
                $table->decimal('points_discount', 10, 2)->default(0)->after('points_used');
                $table->unsignedInteger('points_awarded')->default(0)->after('points_discount');
                $table->timestamp('points_awarded_at')->nullable()->after('points_awarded');
                $table->timestamp('points_refunded_at')->nullable()->after('points_awarded_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'points_used')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn([
                    'points_used',
                    'points_discount',
                    'points_awarded',
                    'points_awarded_at',
                    'points_refunded_at',
                ]);
            });
        }

        Schema::dropIfExists('point_transactions');
        Schema::dropIfExists('loyalty_settings');

        if (Schema::hasColumn('users', 'points')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('points');
            });
        }

        if (Schema::hasColumn('users', 'phone_verified_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('phone_verified_at');
            });
        }
    }
};
