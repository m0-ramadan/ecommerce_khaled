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
        if (!Schema::hasTable('pricing_tiers')) {
            Schema::create('pricing_tiers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained()->onDelete('cascade');
                $table->integer('quantity'); // 1, 5, 10, 25, 50...
                $table->decimal('price_per_unit', 10, 4); // 162.45
                $table->boolean('is_sample')->default(false); // عينة
                $table->timestamps();
                
                $table->unique(['product_id', 'quantity']);
                $table->index('product_id');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('pricing_tiers');
    }
};
