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
        if (!Schema::hasTable('print_locations')) {
            Schema::create('print_locations', function (Blueprint $table) {
                $table->id();
                $table->string('name'); // صدر، ظهر، كتف يمين
                $table->enum('type', ['print', 'embroider']); // نوع الموقع
                $table->decimal('additional_price', 10, 2)->default(0);
                $table->timestamps();
            });
            
            // إضافة مواقع الطباعة الافتراضية
            DB::table('print_locations')->insert([
                // مواقع الطباعة
                ['name' => 'صدر أمامي A4', 'type' => 'print', 'additional_price' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'ظهر خلفي A4', 'type' => 'print', 'additional_price' => 15, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'شعار صدر صغير', 'type' => 'print', 'additional_price' => 10, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'كتف يمين', 'type' => 'print', 'additional_price' => 15, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'كتف يسار', 'type' => 'print', 'additional_price' => 15, 'created_at' => now(), 'updated_at' => now()],
                
                // مواقع التطريز
                ['name' => 'صدر أمامي (تطريز)', 'type' => 'embroider', 'additional_price' => 30, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'ظهر خلفي (تطريز)', 'type' => 'embroider', 'additional_price' => 35, 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'كتف (تطريز)', 'type' => 'embroider', 'additional_price' => 25, 'created_at' => now(), 'updated_at' => now()],
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('print_locations');
    }
};
