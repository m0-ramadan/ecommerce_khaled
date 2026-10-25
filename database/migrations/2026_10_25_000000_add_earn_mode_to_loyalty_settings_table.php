<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loyalty_settings', function (Blueprint $table) {
            if (! Schema::hasColumn('loyalty_settings', 'earn_mode')) {
                // amount = نقاط حسب قيمة الطلب، per_order = نقاط ثابتة لكل طلب، both = الاثنان
                $table->string('earn_mode', 20)->default('amount')->after('is_active');
            }

            if (! Schema::hasColumn('loyalty_settings', 'points_per_order')) {
                $table->unsignedInteger('points_per_order')->default(0)->after('earn_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('loyalty_settings', function (Blueprint $table) {
            if (Schema::hasColumn('loyalty_settings', 'points_per_order')) {
                $table->dropColumn('points_per_order');
            }

            if (Schema::hasColumn('loyalty_settings', 'earn_mode')) {
                $table->dropColumn('earn_mode');
            }
        });
    }
};
