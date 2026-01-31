<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //  $table->json('options_conditions')->nullable();
            // $table->json('valid_combinations')->nullable();
            // $table->integer('combination_count')->default(0);
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'options_conditions',
                'valid_combinations',
                'combination_count'
            ]);
        });
    }
};
