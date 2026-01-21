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
        Schema::table('product_size_tiers', function (Blueprint $table) {
            $table->boolean('is_quantity_tier')->default(false);
            $table->enum('tier_type', ['size','quantity'])->default('size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_size_tiers', function (Blueprint $table) {
            //
        });
    }
};
