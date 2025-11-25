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
    // public function up()
    // {
    //     Schema::table('warranties', function (Blueprint $table) {
    //         // تغيير duration_months إلى duration_days
    //         if (Schema::hasColumn('warranties', 'duration_months')) {
    //             // تحويل القيم أولاً (شهر = 30 يوم)
    //             DB::statement('UPDATE warranties SET duration_months = duration_months * 30');
    //             $table->renameColumn('duration_months', 'duration_days');
    //         }
            
    //         // إضافة description
    //         if (!Schema::hasColumn('warranties', 'description')) {
    //             $table->text('description')->nullable()->after('duration_days');
    //         }
    //     });
    // }

    // public function down()
    // {
    //     Schema::table('warranties', function (Blueprint $table) {
    //         DB::statement('UPDATE warranties SET duration_days = CEIL(duration_days / 30)');
    //         $table->renameColumn('duration_days', 'duration_months');
    //         $table->dropColumn('description');
    //     });
    // }
};
