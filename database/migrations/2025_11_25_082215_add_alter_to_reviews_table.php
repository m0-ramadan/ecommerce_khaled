<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
 public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // إضافة is_approved
            if (!Schema::hasColumn('reviews', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('comment');
            }
            
            // إضافة unique constraint (user واحد يقيّم منتج مرة واحدة)
         //   DB::statement('ALTER TABLE reviews ADD UNIQUE KEY unique_product_user (product_id, user_id)');
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('is_approved');
            DB::statement('ALTER TABLE reviews DROP INDEX unique_product_user');
        });
    }
};
