<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('product_options') && ! Schema::hasColumn('product_options', 'order')) {
            Schema::table('product_options', function (Blueprint $table) {
                $table->unsignedInteger('order')->default(0)->after('is_required');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('product_options', 'order')) {
            Schema::table('product_options', function (Blueprint $table) {
                $table->dropColumn('order');
            });
        }
    }
};
