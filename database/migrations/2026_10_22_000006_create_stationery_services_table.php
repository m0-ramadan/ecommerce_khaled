<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('stationery_services')) {
            Schema::create('stationery_services', function (Blueprint $table) {
                $table->id();
                $table->string('key', 50)->unique();
                $table->string('name', 150);
                $table->decimal('price', 8, 2)->default(10.00);
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });

            DB::table('stationery_services')->insert([
                [
                    'key' => 'plastic_wire',
                    'name' => 'سلك بلاستيك',
                    'price' => 10.00,
                    'is_active' => true,
                    'sort_order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'key' => 'iron_wire',
                    'name' => 'سلك حديد',
                    'price' => 10.00,
                    'is_active' => true,
                    'sort_order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'key' => 'tape',
                    'name' => 'تيب',
                    'price' => 10.00,
                    'is_active' => true,
                    'sort_order' => 3,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'key' => 'transparent_packaging',
                    'name' => 'تغليف شفاف',
                    'price' => 10.00,
                    'is_active' => true,
                    'sort_order' => 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stationery_services');
    }
};
