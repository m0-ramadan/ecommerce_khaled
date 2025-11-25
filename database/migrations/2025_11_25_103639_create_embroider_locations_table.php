<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('embroider_locations', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المكان (مثل: صدر، ظهر، كتف)
            $table->decimal('additional_price', 8, 2)->default(0); // سعر الزيادة
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('embroider_locations');
    }
};
