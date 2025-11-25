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
        // نقل البيانات من favorites إلى favourites
        DB::statement('INSERT IGNORE INTO favourites (user_id, product_id, created_at, updated_at) 
                       SELECT user_id, product_id, created_at, updated_at FROM favorites');
        
        // حذف جدول favorites
        Schema::dropIfExists('favorites');
        
        // تعديل favourites
        Schema::table('favourites', function (Blueprint $table) {
            // حذف type إذا كان موجود
            if (Schema::hasColumn('favourites', 'type')) {
                $table->dropColumn('type');
            }
            
            // إضافة unique constraint
            if (!Schema::hasColumn('favourites', 'user_id')) {
                return; // الجدول موجود بالفعل
            }
            
            // إضافة الـ indexes
            DB::statement('ALTER TABLE favourites ADD UNIQUE KEY unique_user_product (user_id, product_id)');
        });
    }

    public function down()
    {
        // إعادة إنشاء favorites
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }
};
