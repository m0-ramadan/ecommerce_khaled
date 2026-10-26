<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * إعدادات تسعير المقاسات الكبيرة (A1 / A2) — كل قيمة تُدار من الداشبورد
     * بدل ما تكون ثابتة في الكود.
     */
    public function up(): void
    {
        if (! Schema::hasTable('stationery_large_format_rules')) {
            Schema::create('stationery_large_format_rules', function (Blueprint $table) {
                $table->id();
                $table->string('paper_size', 10)->unique();
                // هل نطبّق سعر البداية كحد أدنى للفاتورة؟
                $table->boolean('is_minimum_enabled')->default(true);
                // عدد الورق المشمول في سعر البداية.
                $table->unsignedInteger('included_sheets')->default(100);
                // سعر البداية لكل حالة (وجه × لون).
                $table->decimal('single_bw_price', 10, 2)->default(0);
                $table->decimal('single_color_price', 10, 2)->default(0);
                $table->decimal('double_bw_price', 10, 2)->default(0);
                $table->decimal('double_color_price', 10, 2)->default(0);
                $table->timestamps();
            });
        }

        $rules = [
            [
                'paper_size' => 'A1',
                'single_bw_price' => 500,
                'single_color_price' => 600,
                'double_bw_price' => 900,
                'double_color_price' => 1000,
            ],
            [
                'paper_size' => 'A2',
                'single_bw_price' => 400,
                'single_color_price' => 500,
                'double_bw_price' => 750,
                'double_color_price' => 850,
            ],
        ];

        foreach ($rules as $rule) {
            if (! DB::table('stationery_large_format_rules')->where('paper_size', $rule['paper_size'])->exists()) {
                DB::table('stationery_large_format_rules')->insert($rule + [
                    'is_minimum_enabled' => true,
                    'included_sheets' => 100,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('stationery_large_format_rules');
    }
};
