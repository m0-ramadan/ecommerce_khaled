<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('packages') && ! Schema::hasColumn('packages', 'design_price')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->decimal('design_price', 10, 2)->default(0)->after('background_image');
            });
        }

        if (Schema::hasTable('package_orders')) {
            Schema::table('package_orders', function (Blueprint $table) {
                if (! Schema::hasColumn('package_orders', 'design_type')) {
                    $table->string('design_type', 30)->nullable()->after('payment_method');
                }
                if (! Schema::hasColumn('package_orders', 'design_fee')) {
                    $table->decimal('design_fee', 12, 2)->default(0)->after('design_type');
                }
                if (! Schema::hasColumn('package_orders', 'design_file')) {
                    $table->string('design_file')->nullable()->after('design_fee');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('packages', 'design_price')) {
            Schema::table('packages', fn (Blueprint $t) => $t->dropColumn('design_price'));
        }
        foreach (['design_type', 'design_fee', 'design_file'] as $col) {
            if (Schema::hasColumn('package_orders', $col)) {
                Schema::table('package_orders', fn (Blueprint $t) => $t->dropColumn($col));
            }
        }
    }
};
