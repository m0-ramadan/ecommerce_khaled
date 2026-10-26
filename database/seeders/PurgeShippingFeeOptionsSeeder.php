<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * حذف خيار "رسوم الشحن والتوصيل" (وأي خيار شحن/توصيل) من كل المنتجات.
 * الشحن بقى بيتحسب من خدمة الشحن نفسها مش كخيار على المنتج.
 *
 * التشغيل الآمن (افتراضيًا Dry run — بيطبع بس ومش بيحذف):
 *   php artisan db:seed --class=PurgeShippingFeeOptionsSeeder --force
 *
 * التشغيل الفعلي (الحذف):
 *   PURGE_OPTIONS_DRY_RUN=0 php artisan db:seed --class=PurgeShippingFeeOptionsSeeder --force
 */
class PurgeShippingFeeOptionsSeeder extends Seeder
{
    /**
     * أنماط البحث في العمود option_name (LIKE %pattern%).
     */
    protected array $namePatterns = [
        '%شحن%',
        '%توصيل%',
        '%shipping%',
        '%delivery%',
    ];

    public function run(): void
    {
        $dryRun = filter_var(
            getenv('PURGE_OPTIONS_DRY_RUN') === false ? '1' : getenv('PURGE_OPTIONS_DRY_RUN'),
            FILTER_VALIDATE_BOOLEAN
        );

        $optionIds = $this->matchingOptionIds();

        if ($optionIds->isEmpty()) {
            $this->command->info('مفيش خيارات شحن مطابقة. مفيش حاجة هتتحذف.');
            return;
        }

        $matched = DB::table('product_options')
            ->whereIn('id', $optionIds)
            ->get(['id', 'product_id', 'option_name', 'option_value', 'additional_price', 'is_required']);

        $this->printReport($matched, $dryRun);

        if ($dryRun) {
            $this->command->warn('DRY RUN — مفيش أي حاجة اتحذفت.');
            $this->command->line('للحذف الفعلي: PURGE_OPTIONS_DRY_RUN=0 php artisan db:seed --class=PurgeShippingFeeOptionsSeeder --force');
            return;
        }

        $deletedOptions = 0;
        $deletedTiers = 0;

        DB::transaction(function () use ($optionIds, &$deletedOptions, &$deletedTiers) {
            // نفصل أي خيار تابع قبل الحذف بدل ما الـ FK cascade يمسحه.
            DB::table('product_options')
                ->whereIn('depends_on_option_id', $optionIds)
                ->update([
                    'depends_on_option_id' => null,
                    'depends_on_detail_id' => null,
                    'dependency_condition' => null,
                ]);

            if (Schema::hasTable('product_size_tiers')) {
                $tiers = DB::table('product_size_tiers')->whereIn('option_id', $optionIds);
                if (Schema::hasColumn('product_size_tiers', 'related_option_id')) {
                    $tiers->orWhereIn('related_option_id', $optionIds);
                }
                $deletedTiers = $tiers->delete();
            }

            $deletedOptions = DB::table('product_options')->whereIn('id', $optionIds)->delete();
        });

        Log::info('PurgeShippingFeeOptionsSeeder: removed shipping fee options', [
            'options' => $deletedOptions,
            'size_tiers' => $deletedTiers,
            'option_ids' => $optionIds->all(),
        ]);

        $this->command->info("تم حذف {$deletedOptions} خيار و {$deletedTiers} شريحة كمية.");
    }

    /**
     * @return \Illuminate\Support\Collection<int, int>
     */
    protected function matchingOptionIds()
    {
        return DB::table('product_options')
            ->where(function ($query) {
                foreach ($this->namePatterns as $pattern) {
                    $query->orWhere('option_name', 'like', $pattern);
                }
            })
            ->pluck('id');
    }

    protected function printReport($matched, bool $dryRun): void
    {
        $this->command->line('');
        $this->command->info($dryRun ? 'تقرير DRY RUN — الخيارات اللي هتتحذف:' : 'الخيارات اللي هتتحذف:');
        $this->command->line('');

        foreach ($matched->groupBy('option_name')->sortKeys() as $name => $rows) {
            $this->command->line(sprintf(
                '  - %s | خيارات: %d | منتجات: %d | إلزامي: %d | إجمالي السعر: %.2f',
                $name,
                $rows->count(),
                $rows->pluck('product_id')->unique()->count(),
                $rows->where('is_required', true)->count(),
                (float) $rows->sum('additional_price')
            ));
        }

        $this->command->line('');
        $this->command->line(sprintf(
            'الإجمالي: %d خيار على %d منتج.',
            $matched->count(),
            $matched->pluck('product_id')->unique()->count()
        ));
        $this->command->line('');
    }
}
