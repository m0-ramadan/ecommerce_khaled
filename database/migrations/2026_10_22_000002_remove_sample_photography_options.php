<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Removes the "هل تريد تصوير عينة قبل طباعة الكمية؟" option from every product.
     * The option is also filtered from the API for future imports.
     */
    public function up(): void
    {
        $optionIds = DB::table('product_options')
            ->where(function ($query) {
                $query->where('option_name', 'like', '%تصوير عينة%')
                    ->orWhere('option_name', 'like', '%عينة قبل طباعة%');
            })
            ->pluck('id');

        if ($optionIds->isEmpty()) {
            return;
        }

        // لا نترك خيارات تابعة تشير إلى خيار محذوف.
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
        // لا يمكن استرجاع البيانات المحذوفة.
    }
};
