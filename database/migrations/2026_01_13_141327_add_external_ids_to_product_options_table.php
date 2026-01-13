<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->bigInteger('external_option_id')->nullable()->after('product_id');
            $table->bigInteger('external_detail_id')->nullable()->after('external_option_id');
            // $table->index('external_option_id');
            // $table->index('external_detail_id');
        });
    }

    public function down()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn('external_option_id');
            $table->dropColumn('external_detail_id');
        });
    }
};