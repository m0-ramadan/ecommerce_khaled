<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_places_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('label'); // الاسم بالعربي للعرض
            $table->string('name'); // الاسم بالإنجليزي المتوافق مع OTO
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('places')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('parent_id');
            $table->index('name');
            $table->index('label');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('places');
    }
};