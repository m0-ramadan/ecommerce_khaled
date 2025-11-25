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
        Schema::table('images', function (Blueprint $table) {
            // تغيير url إلى path
            if (Schema::hasColumn('images', 'url')) {
                $table->renameColumn('url', 'path');
            }
            
            // إضافة is_primary
            if (!Schema::hasColumn('images', 'is_primary')) {
                $table->boolean('is_primary')->default(false)->after('path');
            }
            
            // حذف الحقول غير المطلوبة
            if (Schema::hasColumn('images', 'alt')) {
                $table->dropColumn(['alt', 'type', 'is_active']);
            }
        });
    }

    public function down()
    {
        Schema::table('images', function (Blueprint $table) {
            $table->renameColumn('path', 'url');
            $table->dropColumn('is_primary');
        });
    }
};
