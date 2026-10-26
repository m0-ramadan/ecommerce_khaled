<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * حذف خيارات "إرسال الطلب" القديمة من كل المنتجات.
 *
 * الخيارات المستهدفة (من شاشة خيارات المنتج):
 *   - الارسال عن طريق الواتس اب
 *   - الارسال عن طريق الايميل / البريد الإلكتروني
 *
 * ملاحظة: خيار "رسوم الشحن والتوصيل" مقصود إنه يفضل لأنه بيحصّل فلوس فعلية.
 *
 * التشغيل الآمن (افتراضيًا Dry run — بيطبع بس ومش بيحذف):
 *   php artisan db:seed --class=PurgeShippingOptionsSeeder --force
 *
 * التشغيل الفعلي (الحذف):
 *   PURGE_OPTIONS_DRY_RUN=0 php artisan db:seed --class=PurgeShippingOptionsSeeder --force
 */
class PurgeShippingOptionsSeeder extends Seeder
{
    /**
     * أنماط البحث في العمود option_name (LIKE %pattern%).
     * عدّل هنا لو فيه خيار تاني عايز تشيله أو تستثنيه.
     */
    protected array $namePatterns = [
        // إرسال الطلب عن طريق واتساب / إيميل / بريد
        '%واتس%',
        '%whatsapp%',
        '%ايميل%',
        '%إيميل%',
        '%email%',

        // ملاحظة: خيار "رسوم الشحن والتوصيل" بيحصّل فلوس فعلية (١٢ منتج)
        // فمش بنحذفه. لو عايز تشيله بعدين ضيف الأنماط دي:
        // '%شحن%', '%توصيل%', '%shipping%', '%delivery%',
    ];

    /**
     * استثناءات: أي خيار اسمه فيه أي كلمة من دول مش هيتحذف
     * (حماية من الحذف بالغلط).
     */
    protected array $excludePatterns = [
        // مثال: '%تصميم%',
    ];

    public function run(): void
    {
        $dryRun = filter_var(
            getenv('PURGE_OPTIONS_DRY_RUN') === false ? '1' : getenv('PURGE_OPTIONS_DRY_RUN'),
            FILTER_VALIDATE_BOOLEAN
        );

        $optionIds = $this->matchingOptionIds();

        if ($optionIds->isEmpty()) {
            $this->command->info('مفيش خيارات مطابقة. مفيش حاجة هتتحذف.');
            return;
        }

        $matched = DB::table('product_options')
            ->whereIn('id', $optionIds)
            ->get(['id', 'product_id', 'option_name', 'option_value', 'additional_price', 'is_required']);

        $this->printReport($matched, $dryRun);

        if ($dryRun) {
            $this->command->warn('DRY RUN — مفيش أي حاجة اتحذفت.');
            $this->command->line('للحذف الفعلي: PURGE_OPTIONS_DRY_RUN=0 php artisan db:seed --class=PurgeShippingOptionsSeeder --force');
            return;
        }

        $deletedOptions = 0;
        $deletedTiers = 0;

        DB::transaction(function () use ($optionIds, &$deletedOptions, &$deletedTiers) {
            // 1) نفصل الخيارات التابعة (اللي بتعتمد على خيار هنحذفه) بدل ما الـ FK cascade يمسحها.
            DB::table('product_options')
                ->whereIn('depends_on_option_id', $optionIds)
                ->update([
                    'depends_on_option_id' => null,
                    'depends_on_detail_id' => null,
                    'dependency_condition' => null,
                ]);

            // 2) نحذف شرائح الكمية المرتبطة بالخيارات.
            if (Schema::hasTable('product_size_tiers')) {
                $tiers = DB::table('product_size_tiers')->whereIn('option_id', $optionIds);
                if (Schema::hasColumn('product_size_tiers', 'related_option_id')) {
                    $tiers->orWhereIn('related_option_id', $optionIds);
                }
                $deletedTiers = $tiers->delete();
            }

            // 3) نحذف الخيارات نفسها.
            $deletedOptions = DB::table('product_options')->whereIn('id', $optionIds)->delete();
        });

        Log::info('PurgeShippingOptionsSeeder: removed shipping/sending product options', [
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

                // "الإرسال عن طريق البريد الإلكتروني" — نجمع بين كلمتين عشان منحذفش
                // خيارات زي "رسالة على الكارت" بالغلط.
                $query->orWhere(function ($sub) {
                    $sub->where('option_name', 'like', '%بريد%')
                        ->where('option_name', 'like', '%رسال%');
                });
            })
            ->when(! empty($this->excludePatterns), function ($query) {
                foreach ($this->excludePatterns as $pattern) {
                    $query->where('option_name', 'not like', $pattern);
                }
            })
            ->pluck('id');
    }

    protected function printReport($matched, bool $dryRun): void
    {
        $this->command->line('');
        $this->command->info($dryRun ? 'تقرير DRY RUN — الخيارات اللي هتتحذف:' : 'الخيارات اللي هتتحذف:');
        $this->command->line('');

        $byName = $matched->groupBy('option_name')->sortKeys();

        foreach ($byName as $name => $rows) {
            $products = $rows->pluck('product_id')->unique()->count();
            $required = $rows->where('is_required', true)->count();
            $this->command->line(sprintf(
                '  - %s | خيارات: %d | منتجات: %d | إلزامي: %d',
                $name,
                $rows->count(),
                $products,
                $required
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
