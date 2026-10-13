<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stationery_prices', function (Blueprint $table) {
            $table->string('paper_type')->default('plain')->after('service_type');
            $table->string('side_type')->default('single')->after('paper_size');
            $table->string('orientation')->default('portrait')->after('side_type');
            $table->dropUnique('stationery_prices_service_type_paper_size_unique');
            $table->unique(
                ['service_type', 'paper_type', 'paper_size', 'side_type', 'orientation'],
                'stationery_prices_configuration_unique'
            );
        });

        $rows = DB::table('stationery_prices')->get();
        $paperTypes = [
            'document' => ['plain', 'coated', 'cardstock'],
            'photo' => ['glossy', 'matte'],
        ];

        foreach ($rows as $row) {
            $types = $paperTypes[$row->service_type] ?? ['plain'];
            $basePaperType = $types[0];
            DB::table('stationery_prices')->where('id', $row->id)->update([
                'paper_type' => $basePaperType,
            ]);

            foreach ($types as $paperType) {
                foreach (['portrait', 'landscape'] as $orientation) {
                    foreach (['single', 'double'] as $sideType) {
                        if ($paperType === $basePaperType && $orientation === 'portrait' && $sideType === 'single') {
                            continue;
                        }

                        $doubleSidedExtra = $sideType === 'double' ? (float) $row->double_sided_price : 0;
                        DB::table('stationery_prices')->insert([
                            'service_type' => $row->service_type,
                            'paper_type' => $paperType,
                            'paper_size' => $row->paper_size,
                            'side_type' => $sideType,
                            'orientation' => $orientation,
                            'bw_price' => (float) $row->bw_price + $doubleSidedExtra,
                            'color_price' => (float) $row->color_price + $doubleSidedExtra,
                            'file_bw_price' => (float) ($row->file_bw_price ?? $row->bw_price) + $doubleSidedExtra,
                            'file_color_price' => (float) ($row->file_color_price ?? $row->color_price) + $doubleSidedExtra,
                            'double_sided_price' => 0,
                            'is_active' => $row->is_active,
                            'sort_order' => $row->sort_order,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down(): void
    {
        DB::table('stationery_prices')
            ->where('side_type', '!=', 'single')
            ->orWhere('orientation', '!=', 'portrait')
            ->orWhere(function ($query) {
                $query->where('service_type', 'document')->where('paper_type', '!=', 'plain');
            })
            ->orWhere(function ($query) {
                $query->where('service_type', 'photo')->where('paper_type', '!=', 'glossy');
            })
            ->delete();

        DB::table('stationery_prices')->update(['paper_type' => 'plain']);

        Schema::table('stationery_prices', function (Blueprint $table) {
            $table->dropUnique('stationery_prices_configuration_unique');
            $table->dropColumn(['paper_type', 'side_type', 'orientation']);
            $table->unique(['service_type', 'paper_size']);
        });
    }
};
