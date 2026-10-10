<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\BannerItem;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CategoryBannersSeeder extends Seeder
{
    /**
     * قائمة السلاجات ومسمياتها للأقسام العشرة
     */
    public const CATEGORY_BANNERS = [
        'boksat'         => 'بوكسات',
        'akyas'          => 'أكياس',
        'akoab'          => 'أكواب',
        'mlskat'         => 'ملصقات',
        'kratyn-shhn'    => 'كراتين شحن',
        'orod-o-mnasbat' => 'عروض ومناسبات',
        'mntgat-jahzh'   => 'منتجات جاهزة',
        'daaayh-o-aelam' => 'دعاية وإعلام',
        'hdayh-daaayyh'  => 'هدايا دعائية',
        'krtasyh'        => 'قرطاسية',
    ];

    public function run(): void
    {
        $assetsDir = database_path('seeders/assets/banners');
        $storageDir = storage_path('app/public/banners/categories');

        if (!File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        // نسخ الصور من assets إلى storage إذا لم تكن موجودة
        if (File::exists($assetsDir)) {
            $files = File::files($assetsDir);
            foreach ($files as $file) {
                $targetFile = $storageDir . '/' . $file->getFilename();
                if (!File::exists($targetFile)) {
                    File::copy($file->getRealPath(), $targetFile);
                }
            }
        }

        $this->command?->info('بدء تهيئة بنرات الأقسام...');

        // البنر الأب الخاص بالأقسام (banner_type_id = 3 هو category_slider)
        $parentBanner = Banner::firstOrCreate(
            ['title' => 'بنرات الأقسام', 'banner_type_id' => 3],
            [
                'section_order' => 1,
                'is_active' => true,
            ]
        );

        $seededCount = 0;

        foreach (self::CATEGORY_BANNERS as $slug => $label) {
            $category = Category::where('slug', $slug)->first();

            if (!$category) {
                $this->command?->warn("لم يتم العثور على القسم بالاسم اللطيف (slug): {$slug}");
                continue;
            }

            $desktopRel = "banners/categories/{$slug}-desktop.webp";
            $mobileRel  = "banners/categories/{$slug}-mobile.webp";

            // التحقق من وجود الملفات في التخزين، أو استخدام jpg كبديل إن وجد
            if (!File::exists(storage_path("app/public/{$desktopRel}"))) {
                if (File::exists(storage_path("app/public/banners/categories/{$slug}-desktop.jpg"))) {
                    $desktopRel = "banners/categories/{$slug}-desktop.jpg";
                }
            }

            if (!File::exists(storage_path("app/public/{$mobileRel}"))) {
                if (File::exists(storage_path("app/public/banners/categories/{$slug}-mobile.jpg"))) {
                    $mobileRel = "banners/categories/{$slug}-mobile.jpg";
                }
            }

            BannerItem::updateOrCreate(
                [
                    'category_id' => $category->id,
                ],
                [
                    'banner_id'         => $parentBanner->id,
                    'item_order'        => 1,
                    'image_url'         => $desktopRel,
                    'mobile_image_url'  => $mobileRel,
                    'image_alt'         => "بنر قسم {$category->name}",
                    'link_url'          => "/category/{$category->slug}",
                    'link_target'       => '_self',
                    'is_link_active'    => true,
                    'is_active'         => true,
                ]
            );

            $this->command?->info("✓ تم إعداد بنر قسم: [{$category->name}] ({$slug})");
            $this->command?->info("   سطح المكتب: {$desktopRel} (1024x341 - 3:1)");
            $this->command?->info("   الجوال:     {$mobileRel} (682x341 - 2:1)");
            $seededCount++;
        }

        $this->command?->info("تم الانتهاء! تم تجهيز بنرات {$seededCount} أقسام بنجاح.");
    }
}

