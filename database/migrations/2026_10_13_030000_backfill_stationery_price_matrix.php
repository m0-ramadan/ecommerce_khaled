<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sizes = ['A4', 'A3', 'A5', 'A2', 'A1', 'A0'];
        $sides = ['single', 'double'];
        $orientations = ['portrait', 'landscape'];
        $paperTypes = DB::table('stationery_paper_types')->get();
        $now = now();

        foreach ($paperTypes as $paperType) {
            $defaults = $paperType->service_type === 'photo'
                ? ['bw' => 2.00, 'color' => 3.00, 'file_bw' => 1.50, 'file_color' => 2.50]
                : ['bw' => 0.50, 'color' => 1.50, 'file_bw' => 0.40, 'file_color' => 0.90];

            foreach ($sizes as $sortOrder => $paperSize) {
                foreach ($sides as $sideType) {
                    foreach ($orientations as $orientation) {
                        $configuration = [
                            'service_type' => $paperType->service_type,
                            'paper_type' => $paperType->key,
                            'paper_size' => $paperSize,
                            'side_type' => $sideType,
                            'orientation' => $orientation,
                        ];

                        if (DB::table('stationery_prices')->where($configuration)->exists()) {
                            continue;
                        }

                        DB::table('stationery_prices')->insert([
                            ...$configuration,
                            'bw_price' => $defaults['bw'],
                            'color_price' => $defaults['color'],
                            'file_bw_price' => $defaults['file_bw'],
                            'file_color_price' => $defaults['file_color'],
                            'double_sided_price' => 0,
                            'is_active' => true,
                            'sort_order' => $sortOrder,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        // لا نحذف الأسعار هنا حتى لا نفقد تعديلات الأسعار التي أجراها مدير المتجر.
    }
};
