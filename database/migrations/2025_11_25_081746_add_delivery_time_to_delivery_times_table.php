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
        // نفصل إعادة التسمية عن إضافة الملاحظة حتى لا يشير موضع العمود
        // إلى اسم تم تغييره داخل نفس ALTER TABLE.
        Schema::table('delivery_times', function (Blueprint $table) {
            if (Schema::hasColumn('delivery_times', 'from_days')) {
                $table->renameColumn('from_days', 'min_days');
            }
            if (Schema::hasColumn('delivery_times', 'to_days')) {
                $table->renameColumn('to_days', 'max_days');
            }
        });

        Schema::table('delivery_times', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_times', 'note')) {
                $table->text('note')->nullable()->after('max_days');
            }
        });
    }

    public function down()
    {
        Schema::table('delivery_times', function (Blueprint $table) {
            $table->renameColumn('min_days', 'from_days');
            $table->renameColumn('max_days', 'to_days');
            $table->dropColumn('note');
        });
    }
};
