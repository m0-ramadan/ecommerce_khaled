<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stationery_paper_types', function (Blueprint $table) {
            $table->id();
            $table->string('service_type');
            $table->string('key');
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['service_type', 'key']);
        });

        $now = now();
        DB::table('stationery_paper_types')->insert([
            ['service_type' => 'document', 'key' => 'plain', 'name' => 'ورق عادي', 'is_active' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_type' => 'document', 'key' => 'coated', 'name' => 'ورق كوشيه', 'is_active' => true, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['service_type' => 'document', 'key' => 'cardstock', 'name' => 'ورق مقوّى', 'is_active' => true, 'sort_order' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['service_type' => 'photo', 'key' => 'glossy', 'name' => 'ورق صور لامع', 'is_active' => true, 'sort_order' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['service_type' => 'photo', 'key' => 'matte', 'name' => 'ورق صور مطفي', 'is_active' => true, 'sort_order' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('stationery_paper_types');
    }
};
