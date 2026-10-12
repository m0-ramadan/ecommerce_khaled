<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stationery_prices', function (Blueprint $table) {
            $table->decimal('file_bw_price', 10, 2)->nullable()->after('color_price');
            $table->decimal('file_color_price', 10, 2)->nullable()->after('file_bw_price');
        });
    }

    public function down(): void
    {
        Schema::table('stationery_prices', function (Blueprint $table) {
            $table->dropColumn(['file_bw_price', 'file_color_price']);
        });
    }
};

