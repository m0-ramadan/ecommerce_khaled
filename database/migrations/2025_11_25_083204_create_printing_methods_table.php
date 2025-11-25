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
   public function up()
    {
        if (!Schema::hasTable('printing_methods')) {
            Schema::create('printing_methods', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // طباعة، تطريز، طباعة وتطريز
                $table->text('description')->nullable();
                $table->decimal('base_price', 10, 2)->default(0);
                $table->timestamps();
            });
            
            // إضافة طرق الطباعة الافتراضية
            DB::table('printing_methods')->insert([
                ['name' => 'طباعة', 'description' => 'طباعة رقمية على المنتج', 'base_price' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'تطريز', 'description' => 'تطريز على القماش', 'base_price' => 30, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'طباعة وتطريز', 'description' => 'دمج الطباعة والتطريز', 'base_price' => 40, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('printing_methods');
    }
};
