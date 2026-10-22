<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * مدة التنفيذ أصبحت تُحسب من نوع الإنتاج (أوفست/ديجيتال) في الداشبورد،
     * لذلك نحذف خيارات المدة القديمة من المنتجات.
     */
    public function up(): void
    {
        $optionIds = DB::table('product_options')
            ->whereIn('option_name', ['مدة التنفيذ للمنتج', 'مدة تنفيذ المنتج'])
            ->pluck('id');

        if ($optionIds->isEmpty()) {
            return;
        }

        DB::table('product_options')
            ->whereIn('depends_on_option_id', $optionIds)
            ->update([
                'depends_on_option_id' => null,
                'dependency_condition' => null,
            ]);

        DB::table('product_size_tiers')->whereIn('option_id', $optionIds)->delete();
        DB::table('product_options')->whereIn('id', $optionIds)->delete();
    }

    public function down(): void
    {
    }
};
