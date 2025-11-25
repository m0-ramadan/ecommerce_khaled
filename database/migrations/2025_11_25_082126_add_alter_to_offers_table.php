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
        Schema::table('offers', function (Blueprint $table) {
            // إضافة description
            if (!Schema::hasColumn('offers', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            
            // إضافة discount_type
            if (!Schema::hasColumn('offers', 'discount_type')) {
                $table->enum('discount_type', ['percentage', 'fixed'])
                      ->default('percentage')
                      ->after('description');
            }
            
            // إضافة discount_value
            if (!Schema::hasColumn('offers', 'discount_value')) {
                $table->decimal('discount_value', 10, 2)->after('discount_type');
            }
            
            // إضافة is_active
            if (!Schema::hasColumn('offers', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('discount_value');
            }
            
            // إضافة starts_at
            if (!Schema::hasColumn('offers', 'starts_at')) {
                $table->timestamp('starts_at')->nullable()->after('is_active');
            }
            
            // إضافة ends_at
            if (!Schema::hasColumn('offers', 'ends_at')) {
                $table->timestamp('ends_at')->nullable()->after('starts_at');
            }
        });
    }

    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn([
                'description', 'discount_type', 'discount_value',
                'is_active', 'starts_at', 'ends_at'
            ]);
        });
    }
};
