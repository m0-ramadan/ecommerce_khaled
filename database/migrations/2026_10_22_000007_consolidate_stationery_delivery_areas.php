<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Stationery delivery is now inside Riyadh only at a fixed 20 SAR price.
     */
    public function up(): void
    {
        DB::table('stationery_delivery_areas')->delete();

        DB::table('stationery_delivery_areas')->insert([
            'name' => 'الرياض',
            'price' => 20.00,
            'is_active' => true,
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('stationery_delivery_areas')->delete();
    }
};
