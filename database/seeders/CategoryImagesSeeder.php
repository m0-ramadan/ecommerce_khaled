<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategoryImagesSeeder extends Seeder
{
    /**
     * خريطة الصور الخاصة بالأقسام:
     * slug القسم => اسم الملف داخل database/seeders/assets/categories/
     */
    public const CATEGORY_IMAGE_MAP = [
        'boksat'          => 'boksat.webp',
        'akyas'           => 'akyas.webp',
        'akoab'           => 'akoab.webp',
        'mlskat'          => 'mlskat.webp',
        'kratyn-shhn'     => 'kratyn-shhn.webp',
        'orod-o-mnasbat'  => 'orod-o-mnasbat.webp',
        'mntgat-jahzh'    => 'mntgat-jahzh.webp',
        'daaayh-o-aelam'  => 'daaayh-o-aelam.webp',
        'hdayh-daaayyh'   => 'hdayh-daaayyh.webp',
        'krtasyh'         => 'krtasyh.webp',
    ];

    public function run(): void
    {
        $assetsDir = database_path('seeders/assets/categories');
        $storageDir = storage_path('app/public/categories');

        if (!File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        $this->command?->info('بدء تحديث صور الأقسام...');

        $updatedCount = 0;

        foreach (self::CATEGORY_IMAGE_MAP as $slug => $fileName) {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                $this->command?->warn("لم يتم العثور على القسم بالاسم اللطيف (slug): {$slug}");
                continue;
            }

            $sourcePath = $assetsDir . '/' . $fileName;
            $destinationPath = $storageDir . '/' . $fileName;

            // إذا لم يكن ملف webp موجوداً، نجرب ملف jpg
            if (!File::exists($sourcePath)) {
                $altSource = $assetsDir . '/' . pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
                if (File::exists($altSource)) {
                    $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '.jpg';
                    $sourcePath = $altSource;
                    $destinationPath = $storageDir . '/' . $fileName;
                }
            }

            // نسخ الملف إلى مجلد التخزين العام إذا كان غير موجود
            if (File::exists($sourcePath) && !File::exists($destinationPath)) {
                File::copy($sourcePath, $destinationPath);
            }

            $relativeDbPath = 'categories/' . $fileName;

            $category->update([
                'image'     => $relativeDbPath,
                'sub_image' => $relativeDbPath,
            ]);

            $this->command?->info("✓ تم تحديث صورة قسم: [{$category->name}] ({$slug}) => {$relativeDbPath}");
            $updatedCount++;
        }

        $this->command?->info("تم الانتهاء! تم تحديث صور {$updatedCount} أقسام بنجاح.");
    }
}

