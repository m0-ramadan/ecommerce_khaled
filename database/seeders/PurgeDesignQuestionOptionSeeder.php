<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * يحذف خيار "لديك نموذج لتصميم خاص ام تريد خدمة تصميم" من كل المنتجات.
 *
 * التشغيل الافتراضي Dry-run:
 *   php artisan db:seed --class=PurgeDesignQuestionOptionSeeder
 * الحذف الفعلي:
 *   touch storage/app/purge_design_option.execute
 *   php artisan db:seed --class=PurgeDesignQuestionOptionSeeder --force
 *   rm storage/app/purge_design_option.execute
 */
class PurgeDesignQuestionOptionSeeder extends Seeder
{
    public function run(): void
    {
        // Dry-run افتراضيًا. للتنفيذ الفعلي ننشئ ملف العلم ده:
        // storage/app/purge_design_option.execute
        $flagPath = storage_path('app/purge_design_option.execute');
        $dryRun = ! file_exists($flagPath);

        $rows = DB::table('product_options')
            ->where('option_value', 'like', '%لديك نموذج%')
            ->get(['id', 'product_id', 'option_name', 'option_value', 'additional_price', 'depends_on_option_id']);

        $this->command?->info(($dryRun ? '[DRY RUN] ' : '') . 'Matched options: ' . $rows->count());

        if ($rows->isEmpty()) {
            return;
        }

        $backupPath = storage_path('app/purge_design_option_backup_' . date('Ymd_His') . '.json');
        @file_put_contents($backupPath, json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        $this->command?->info('Backup: ' . $backupPath);

        foreach ($rows->groupBy('product_id') as $productId => $productRows) {
            $this->command?->line("product {$productId}: " . $productRows->count() . ' option(s)');
        }

        if ($dryRun) {
            return;
        }

        $ids = $rows->pluck('id')->all();

        // نحذف شرائح الكمية التابعة وأي خيارات تعتمد على الخيارات المحذوفة.
        DB::table('product_size_tiers')->whereIn('option_id', $ids)->delete();

        $deleted = DB::table('product_options')->whereIn('id', $ids)->delete();

        $this->command?->info('Deleted options: ' . $deleted);
    }
}
