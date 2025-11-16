<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('image')->default('https://i.ibb.co/rffyqbmk/1.png')->after('order');
            $table->string('sub_image')->default('https://i.ibb.co/20wDxXg9/3.png')->after('image');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            //
        });
    }
};
