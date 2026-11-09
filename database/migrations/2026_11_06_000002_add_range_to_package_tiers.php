<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('package_product_tiers') && ! Schema::hasColumn('package_product_tiers', 'max_quantity')) {
            Schema::table('package_product_tiers', function (Blueprint $table) {
                // quantity = بداية الرينج، max_quantity = نهاية الرينج (null = مفتوح)
                $table->unsignedInteger('max_quantity')->nullable()->after('quantity');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('package_product_tiers', 'max_quantity')) {
            Schema::table('package_product_tiers', function (Blueprint $table) {
                $table->dropColumn('max_quantity');
            });
        }
    }
};
