<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shipping_orders', function (Blueprint $table) {
            // $table->string('external_order_ref', 50)
            //     ->after('order_id')
            //     ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('shipping_orders', function (Blueprint $table) {
            $table->dropColumn('external_order_ref');
        });
    }
};
