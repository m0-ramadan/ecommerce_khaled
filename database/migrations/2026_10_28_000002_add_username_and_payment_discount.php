<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users') && ! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username', 50)->nullable()->unique()->after('id');
            });
        }

        if (Schema::hasTable('orders') && ! Schema::hasColumn('orders', 'gateway_discount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('gateway_discount', 10, 2)->default(0)->after('points_discount');
            });
        }

        if (Schema::hasTable('payment_methods') && ! Schema::hasColumn('payment_methods', 'discount_percent')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->decimal('discount_percent', 5, 2)->default(0)->after('is_payment');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('username');
            });
        }

        if (Schema::hasColumn('orders', 'gateway_discount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('gateway_discount');
            });
        }

        if (Schema::hasColumn('payment_methods', 'discount_percent')) {
            Schema::table('payment_methods', function (Blueprint $table) {
                $table->dropColumn('discount_percent');
            });
        }
    }
};
