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
        Schema::table('products', function (Blueprint $table) {
            $table->string('external_id')->nullable()->unique()->after('id');
            //$table->string('sku')->nullable()->change();
            $table->decimal('weight', 8, 2)->nullable()->after('stock');
            $table->string('url')->nullable()->after('description');
            $table->string('subtitle')->nullable()->after('name');
            $table->string('type')->default('product')->after('subtitle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
