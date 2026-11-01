<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // الرسوم بقت زيادة على السعر مش خصم — نسمّي الأعمدة باسم يوضح كده.
        if (Schema::hasColumn('orders', 'gateway_discount') && ! Schema::hasColumn('orders', 'gateway_fee')) {
            DB::statement('ALTER TABLE `orders` CHANGE `gateway_discount` `gateway_fee` DECIMAL(10,2) NOT NULL DEFAULT 0');
        }

        if (Schema::hasColumn('payment_methods', 'discount_percent') && ! Schema::hasColumn('payment_methods', 'fee_percent')) {
            DB::statement('ALTER TABLE `payment_methods` CHANGE `discount_percent` `fee_percent` DECIMAL(5,2) NOT NULL DEFAULT 0');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'gateway_fee') && ! Schema::hasColumn('orders', 'gateway_discount')) {
            DB::statement('ALTER TABLE `orders` CHANGE `gateway_fee` `gateway_discount` DECIMAL(10,2) NOT NULL DEFAULT 0');
        }

        if (Schema::hasColumn('payment_methods', 'fee_percent') && ! Schema::hasColumn('payment_methods', 'discount_percent')) {
            DB::statement('ALTER TABLE `payment_methods` CHANGE `fee_percent` `discount_percent` DECIMAL(5,2) NOT NULL DEFAULT 0');
        }
    }
};
