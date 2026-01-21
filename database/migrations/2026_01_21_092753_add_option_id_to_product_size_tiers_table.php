<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_size_tiers', function (Blueprint $table) {

            // علاقات جديدة
            $table->unsignedBigInteger('detail_id')->nullable()->after('option_id');
            $table->unsignedBigInteger('parent_option_id')->nullable()->after('detail_id');
            $table->unsignedBigInteger('parent_detail_id')->nullable()->after('parent_option_id');

            // Dependency relations
            $table->unsignedBigInteger('depends_on_option_id')->nullable()->after('tier_type');
            $table->unsignedBigInteger('depends_on_detail_id')->nullable()->after('depends_on_option_id');

            // تحسين الأعمدة الحالية
            $table->boolean('is_quantity_tier')->default(false)->change();
            $table->string('tier_type')->nullable()->change();

            // Indexes (مهمة للأداء)
            $table->index('option_id');
            $table->index('detail_id');
            $table->index('parent_option_id');
            $table->index('parent_detail_id');
            $table->index('depends_on_option_id');
            $table->index('depends_on_detail_id');
        });
    }

    public function down(): void
    {
        Schema::table('product_size_tiers', function (Blueprint $table) {

            $table->dropIndex([
                'option_id',
                'detail_id',
                'parent_option_id',
                'parent_detail_id',
                'depends_on_option_id',
                'depends_on_detail_id',
            ]);

            $table->dropColumn([
                'detail_id',
                'parent_option_id',
                'parent_detail_id',
                'depends_on_option_id',
                'depends_on_detail_id',
            ]);
        });
    }
};
