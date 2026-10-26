<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * المقاسات المتاحة لكل خدمة إضافية.
     * null = متاحة لكل المقاسات.
     */
    public function up(): void
    {
        if (Schema::hasTable('stationery_services') && ! Schema::hasColumn('stationery_services', 'available_sizes')) {
            Schema::table('stationery_services', function (Blueprint $table) {
                $table->json('available_sizes')->nullable()->after('price');
            });

            // الخدمات الإضافية (سلك بلاستك، كعب غراء، ...) مش متاحة للمقاسات الكبيرة A1/A2.
            // يمكن تعديلها لكل خدمة من الداشبورد بعد كده.
            $sizes = json_encode(['A4', 'A3', 'A5'], JSON_UNESCAPED_UNICODE);

            DB::table('stationery_services')
                ->whereNull('available_sizes')
                ->update(['available_sizes' => $sizes]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('stationery_services') && Schema::hasColumn('stationery_services', 'available_sizes')) {
            Schema::table('stationery_services', function (Blueprint $table) {
                $table->dropColumn('available_sizes');
            });
        }
    }
};
