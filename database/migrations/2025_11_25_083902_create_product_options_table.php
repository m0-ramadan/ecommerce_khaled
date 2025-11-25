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
        if (!Schema::hasTable('product_options')) {
            Schema::create('product_options', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->string('option_name'); // عدد الوجوه، شكل الاستيكر
                $table->string('option_value'); // وجهين، دائري
                $table->decimal('additional_price', 10, 2)->default(0);
                $table->boolean('is_required')->default(false);
                $table->timestamps();
                
                $table->index('product_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('product_options');
    }
};
