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
        Schema::table('colors', function (Blueprint $table) {
            // إضافة additional_price (التكلفة الإضافية للألوان الداكنة)
            if (!Schema::hasColumn('colors', 'additional_price')) {
                $table->decimal('additional_price', 10, 2)->default(0)->after('hex_code');
            }
        });
    }

    public function down()
    {
        Schema::table('colors', function (Blueprint $table) {
            $table->dropColumn('additional_price');
        });
    }
};
