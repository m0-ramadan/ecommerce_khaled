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
        if (!Schema::hasTable('statuses')) {
            Schema::create('statuses', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // متاح، نفذ، قريباً
                $table->string('slug')->unique();
                $table->timestamps();
            });
            
            // إضافة الحالات الافتراضية
            DB::table('statuses')->insert([
                ['name' => 'متاح', 'slug' => 'available', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'نفذ', 'slug' => 'out_of_stock', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'قريباً', 'slug' => 'coming_soon', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('statuses');
    }
};
