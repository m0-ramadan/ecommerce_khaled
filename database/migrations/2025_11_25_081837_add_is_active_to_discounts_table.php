<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('discounts', function (Blueprint $table) {
            // إضافة is_active
            if (!Schema::hasColumn('discounts', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('discount_type');
            }
            
            // إضافة starts_at
            if (!Schema::hasColumn('discounts', 'starts_at')) {
                $table->timestamp('starts_at')->nullable()->after('is_active');
            }
            
            // إضافة ends_at
            if (!Schema::hasColumn('discounts', 'ends_at')) {
                $table->timestamp('ends_at')->nullable()->after('starts_at');
            }
        });
    }

    public function down()
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'starts_at', 'ends_at']);
        });
    }
};
