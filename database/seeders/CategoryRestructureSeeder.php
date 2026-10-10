<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * إعادة هيكلة أقسام المتجر.
 *
 * - يُنشئ الأقسام العشرة المعتمدة فقط، ويحذف جميع الأقسام الأخرى.
 * - يعيد توزيع كل منتج على 8 أقسام فقط:
 *   بوكسات / اكياس / اكواب / ملصقات / كراتين شحن /
 *   عروض ومناسبات / دعايه وإعلام / هدايه دعائيه
 * - يبقى قسمَا "منتجات جاهزه" و"قرطاسيه" موجودين لكن فارغين بدون منتجات.
 *
 * تشغيل تجريبي بدون أي تعديل على قاعدة البيانات:
 *   SEEDER_DRY_RUN=1 php artisan db:seed --class=CategoryRestructureSeeder
 *
 * التشغيل الفعلي:
 *   php artisan db:seed --class=CategoryRestructureSeeder
 */
class CategoryRestructureSeeder extends Seeder
{
    /* ------------------------------------------------------------------
     | الأقسام العشرة النهائية
     * ---------------------------------------------------------------- */
    public const TARGETS = [
        ['name' => 'بوكسات',          'slug' => 'boksat',           'receives' => true,  'image' => 'categories/boksat.webp'],
        ['name' => 'اكياس',           'slug' => 'akyas',            'receives' => true,  'image' => 'categories/akyas.webp'],
        ['name' => 'اكواب',           'slug' => 'akoab',            'receives' => true,  'image' => 'categories/akoab.webp'],
        ['name' => 'ملصقات',          'slug' => 'mlskat',           'receives' => true,  'image' => 'categories/mlskat.webp'],
        ['name' => 'كراتين شحن',      'slug' => 'kratyn-shhn',      'receives' => true,  'image' => 'categories/kratyn-shhn.webp'],
        ['name' => 'عروض ومناسبات',   'slug' => 'orod-o-mnasbat',   'receives' => true,  'image' => 'categories/orod-o-mnasbat.webp'],
        ['name' => 'منتجات جاهزه',    'slug' => 'mntgat-jahzh',     'receives' => false, 'image' => 'categories/mntgat-jahzh.webp'],
        ['name' => 'دعايه وإعلام',    'slug' => 'daaayh-o-aelam',   'receives' => true,  'image' => 'categories/daaayh-o-aelam.webp'],
        ['name' => 'هدايه دعائيه',    'slug' => 'hdayh-daaayyh',    'receives' => true,  'image' => 'categories/hdayh-daaayyh.webp'],
        ['name' => 'قرطاسيه',         'slug' => 'krtasyh',          'receives' => false, 'image' => 'categories/krtasyh.webp'],
    ];

    /* ------------------------------------------------------------------
     | الخريطة الافتراضية: رقم القسم القديم => slug القسم الجديد
     | تُطبَّق على كل منتجات القسم إلا من ورد له استثناء في $overrides
     * ---------------------------------------------------------------- */
    public const CATEGORY_MAP = [
        16 => 'daaayh-o-aelam',   // رول اب
        17 => 'hdayh-daaayyh',    // الهدايا الدعائية
        18 => 'boksat',           // كل المنتجات            (مختلط - استثناءات كثيرة)
        19 => 'mlskat',           // طباعة الملصقات والاستيكرات
        20 => 'mlskat',           // استيكرات Spot UV
        21 => 'mlskat',           // استيكرات دائرية
        22 => 'boksat',           // الطباعة على البوكسات
        23 => 'akoab',            // أكواب
        24 => 'orod-o-mnasbat',   // كل منتجات المناسبات    (مختلط)
        25 => 'boksat',           // بوكسات توزيعات
        26 => 'boksat',           // التغليف والتعبئة        (مختلط - كل منتجاته مستثناة)
        27 => 'mlskat',           // استيكرات مربعة
        28 => 'akoab',            // mug
        29 => 'orod-o-mnasbat',   // المناسبات والافراح      (مختلط)
        30 => 'hdayh-daaayyh',    // الملابس المهنيه والخدميه
        31 => 'daaayh-o-aelam',   // مطبوعات ورقية اخرى      (مختلط)
        32 => 'daaayh-o-aelam',   // كروت شخصية
        33 => 'akyas',            // اكياس البن
        34 => 'daaayh-o-aelam',   // كروت 3D
        35 => 'kratyn-shhn',      // كراتين الشحن            (مختلط)
        36 => 'akyas',            // اكياس قماش
        37 => 'daaayh-o-aelam',   // المطاعم والمقاهي        (مختلط)
        38 => 'daaayh-o-aelam',   // أظرف مراسلات
        39 => 'orod-o-mnasbat',   // شرائط الهدايا
        40 => 'hdayh-daaayyh',    // اكسسوارات قماشيه مطبوعه (مختلط)
        41 => 'daaayh-o-aelam',   // ختم
        42 => 'hdayh-daaayyh',    // ازياء الانديه الرياضيه
        43 => 'mlskat',           // استيكرات مخصص - قص على الحدود
        44 => 'daaayh-o-aelam',   // طباعة الكتيبات والدفاتر
        45 => 'orod-o-mnasbat',   // بطاقات الشكر والاهداء
        46 => 'daaayh-o-aelam',   // ملف
        47 => 'boksat',           // بوكس كامل بإغلاق
        48 => 'akyas',            // طباعة انواع اكياس اخرى
        49 => 'akyas',            // اكياس الشحن
        50 => 'kratyn-shhn',      // كراتين شحن بني
        51 => 'boksat',           // حامل البوكسات
        52 => 'daaayh-o-aelam',   // قائمة الطعام MENU
        53 => 'akyas',            // الطباعة على الأكياس
        54 => 'boksat',           // بوكس مع حامل
        55 => 'boksat',           // قواعد بوكسات داخلية
        56 => 'orod-o-mnasbat',   // بطاقات مربعة
        57 => 'boksat',           // بوكسات سحاب
        58 => 'mlskat',           // تغليف العلب (ليبل)
        59 => 'boksat',           // بوكسات سحاب مقسمة
        60 => 'orod-o-mnasbat',   // كرت قص خاص
        61 => 'orod-o-mnasbat',   // بطاقة - حامل لمنتج
        62 => 'mlskat',           // استيكرات مستطيلة
        63 => 'orod-o-mnasbat',   // بطاقات مطوية
        64 => 'mlskat',           // استيكرات - ملصقات
        65 => 'daaayh-o-aelam',   // مطبوعات ورقية
        66 => 'orod-o-mnasbat',   // بطاقات دائرية
        67 => 'hdayh-daaayyh',    // بروش (شارات)
        68 => 'daaayh-o-aelam',   // كروت فاخرة
        69 => 'daaayh-o-aelam',   // بروشور
        70 => 'daaayh-o-aelam',   // التقويم
        71 => 'orod-o-mnasbat',   // تاقات
        72 => 'mlskat',           // استيكرات الشحن والاغلاق
        73 => 'daaayh-o-aelam',   // صور photograph
        74 => 'daaayh-o-aelam',   // مخططات هندسية
        75 => 'daaayh-o-aelam',   // ورق المراسلات
        76 => 'daaayh-o-aelam',   // فلاير
        77 => 'hdayh-daaayyh',    // ملابس رياضية
        79 => 'orod-o-mnasbat',   // عروض البكجات
    ];

    /* ------------------------------------------------------------------
     | استثناءات بالمنتج: رقم المنتج => slug القسم الجديد
     | لها الأولوية على الخريطة الافتراضية أعلاه
     * ---------------------------------------------------------------- */
    public const OVERRIDES = [
        // ---- من قسم "كل المنتجات" (18) الافتراضي بوكسات ----
        30  => 'daaayh-o-aelam',   // استاند رول اب
        32  => 'akyas',            // كيس 15x12x12 سم
        33  => 'kratyn-shhn',      // كرتون شحن 29x29x9
        44  => 'orod-o-mnasbat',   // طباعة بالونات
        50  => 'orod-o-mnasbat',   // تغريسات كيك
        71  => 'daaayh-o-aelam',   // اعلام الريشة
        86  => 'daaayh-o-aelam',   // بوب آب منحني
        112 => 'daaayh-o-aelam',   // ختم كريستال
        131 => 'akyas',            // كيس ورد طولي
        172 => 'akyas',            // كيس كرتوني فاخر
        173 => 'akyas',            // كيس كرتوني فاخر
        181 => 'akyas',            // كيس كرتوني فاخر
        191 => 'mlskat',           // ليبل ملابس
        194 => 'akyas',            // كيس كرتوني فاخر
        195 => 'akyas',            // كيس كرتوني فاخر
        196 => 'akyas',            // كيس كرتوني فاخر
        208 => 'akyas',            // كيس كرتوني فاخر
        209 => 'akyas',            // كيس كرتوني فاخر
        221 => 'akyas',            // كيس كرتوني فاخر
        222 => 'akyas',            // كيس كرتوني فاخر
        227 => 'akyas',            // كيس توزيعات مقفل
        268 => 'orod-o-mnasbat',   // كرت بخلفية مربع
        273 => 'orod-o-mnasbat',   // كرت اهداء مطوى على شكل قلب
        274 => 'orod-o-mnasbat',   // تاق على شكل قلب
        276 => 'orod-o-mnasbat',   // تاق 6x3 سم
        277 => 'orod-o-mnasbat',   // تاق 3x10 سم
        292 => 'akyas',            // كيس توزيعات
        315 => 'daaayh-o-aelam',   // ختم اوتوماتك
        363 => 'daaayh-o-aelam',   // طباعة فلاير

        // ---- من قسم "التغليف والتعبئة" (26) الافتراضي بوكسات ----
        52  => 'akoab',            // حامل اكواب او زجاجات (2)
        53  => 'akoab',            // حامل اكواب او زجاجات (3)
        102 => 'orod-o-mnasbat',   // ورق تغليف طعام
        105 => 'orod-o-mnasbat',   // شرائط قماش - طباعه كاملة
        108 => 'orod-o-mnasbat',   // شرائط قماش - الوان متعدده
        213 => 'orod-o-mnasbat',   // ورق تغليف (جريدة)
        230 => 'akoab',            // سليف اكواب شكل 2
        240 => 'mlskat',           // ليبل علبه بلاستك
        241 => 'mlskat',           // ليبل علبه طوليه
        242 => 'mlskat',           // ليبل علبه عسل
        244 => 'mlskat',           // ليبل علبه دائرية
        246 => 'mlskat',           // ليبل علبه طولي
        259 => 'orod-o-mnasbat',   // طباعة تاق لعلبه الماء
        271 => 'orod-o-mnasbat',   // تاق على شكل وردة
        278 => 'orod-o-mnasbat',   // شرائط ورقية لاختبار العطور
        313 => 'akoab',            // أكواب قهوة 8 أونص
        314 => 'akoab',            // سليف اكواب
        326 => 'akoab',            // حامل اكواب قهوة
        342 => 'orod-o-mnasbat',   // ورق تغليف

        // ---- من قسم "مطبوعات ورقية اخرى" (31) ----
        225 => 'boksat',           // حامل دونات

        // ---- من قسم "المطاعم والمقاهي" (37) ----
        92  => 'orod-o-mnasbat',   // تغريسات طعام
        101 => 'boksat',           // بوكس بطاطس
        140 => 'akoab',            // جيك حراري مع طباعة الشعار

        // ---- من قسم "المناسبات والافراح" (29) ----
        200 => 'akoab',            // كوب قهوة 12 أونصة
        304 => 'daaayh-o-aelam',   // استاند طاولة ورقي
        334 => 'daaayh-o-aelam',   // طباعة شهادة شكر

        // ---- من قسم "كل منتجات المناسبات" (24) ----
        43  => 'hdayh-daaayyh',    // بروش قص مخصص
        98  => 'daaayh-o-aelam',   // أعلام مكتبية صغيرة
        104 => 'daaayh-o-aelam',   // طباعه رول DTF
        281 => 'akyas',            // بكج تغليف ( تاق - كيس شفاف )

        // ---- من قسم "اكسسوارات قماشيه مطبوعه" (40) ----
        106 => 'orod-o-mnasbat',   // شرائط ستان - طباعه كاملة
        107 => 'orod-o-mnasbat',   // شرائط ستان - الوان متعدده

        // ---- من قسم "طباعة الملصقات والاستيكرات" (19) ----
        364 => 'daaayh-o-aelam',   // بوستر باحجام مختلفة

        // ---- من قسم "كراتين الشحن" (35) ----
        83  => 'boksat',           // بوكسات بيتزا - مقاسات متعددة

        // ---- من قسم "مطبوعات ورقية" (65) ----
        301 => 'orod-o-mnasbat',   // تاق دائري مقاس 5 سم
        303 => 'orod-o-mnasbat',   // تاق مربع
    ];

    public function run(): void
    {
        $dryRun = (bool) getenv('SEEDER_DRY_RUN');

        $slugToId = [];
        $idToSlug = [];
        $targets  = collect(self::TARGETS);
        $receivingSlugs = $targets->where('receives', true)->pluck('slug')->all();

        DB::beginTransaction();

        try {
            // 1) إنشاء/تحديث الأقسام العشرة
            $order = 1;
            foreach (self::TARGETS as $target) {
                $category = Category::firstOrNew(['slug' => $target['slug']]);
                $category->name        = $target['name'];
                $category->slug        = $target['slug'];
                $category->description = 'قسم ' . $target['name'];
                $category->parent_id   = null;
                $category->order       = $order++;
                $category->status_id   = 1;

                $defaultImage = $target['image'] ?? 'https://i.ibb.co/rffyqbmk/1.png';
                $defaultSubImage = $target['image'] ?? 'https://i.ibb.co/20wDxXg9/3.png';

                if (! $category->exists || empty($category->image) || str_contains($category->image, 'ibb.co')) {
                    $category->image     = $defaultImage;
                    $category->sub_image = $defaultSubImage;
                }

                $category->save();
                $slugToId[$target['slug']] = $category->id;
                $idToSlug[$category->id]   = $target['slug'];
            }

            // 2) حساب القسم الجديد لكل منتج
            $products = Product::withTrashed()
                ->select('id', 'name', 'category_id')
                ->get();

            $buckets  = [];          // slug => [product ids]
            $unmapped = [];

            foreach ($products as $product) {
                $oldCategoryId = $product->category_id;

                if (isset($idToSlug[$oldCategoryId])) {
                    // المنتج موجود بالفعل داخل أحد الأقسام العشرة => أبقِه مكانه
                    // (هذا ما يجعل السيدر آمناً لإعادة التشغيل أكثر من مرة)
                    $newSlug = $idToSlug[$oldCategoryId];
                } else {
                    $newSlug = self::OVERRIDES[$product->id]
                        ?? self::CATEGORY_MAP[$oldCategoryId]
                        ?? null;
                }

                if ($newSlug === null || ! in_array($newSlug, $receivingSlugs, true)) {
                    $unmapped[] = $product->id . ' | ' . $product->name;
                    continue;
                }

                $buckets[$newSlug][] = $product->id;
            }

            if ($unmapped !== []) {
                DB::rollBack();
                $this->command?->error('منتجات لم يتم تحديد قسم لها (' . count($unmapped) . '):');
                foreach ($unmapped as $line) {
                    $this->command?->error('  - ' . $line);
                }
                return;
            }

            // 3) تحديث category_id لكل منتج
            foreach ($buckets as $slug => $ids) {
                foreach (array_chunk($ids, 500) as $chunk) {
                    Product::withTrashed()
                        ->whereIn('id', $chunk)
                        ->update(['category_id' => $slugToId[$slug]]);
                }
            }

            // 4) حذف كل قسم غير الأقسام العشرة (بعد إعادة توزيع المنتجات)
            $keepIds = array_values($slugToId);
            $toDelete = Category::whereNotIn('id', $keepIds)->pluck('name', 'id');
            Category::whereNotIn('id', $keepIds)->delete();

            // 5) تنظيف الإشارات المعلّقة على أقسام محذوفة
            $orphanBanners = DB::table('banner_items')
                ->whereNotNull('category_id')
                ->whereNotIn('category_id', $keepIds)
                ->update(['category_id' => null]);

            // 6) ملخص
            $summary = [];
            foreach (self::TARGETS as $target) {
                $summary[$target['name']] = [
                    'slug'  => $target['slug'],
                    'count' => count($buckets[$target['slug']] ?? []),
                ];
            }

            $this->command?->info('القسم الجديد            | Slug                 | عدد المنتجات');
            $this->command?->info(str_repeat('-', 62));
            foreach ($summary as $name => $row) {
                $this->command?->info(sprintf(
                    '%-23s | %-20s | %d',
                    $name,
                    $row['slug'],
                    $row['count']
                ));
            }
            $this->command?->info(str_repeat('-', 62));
            $this->command?->info('إجمالي المنتجات: ' . array_sum(array_column($summary, 'count')));
            $this->command?->info('أقسام محذوفة: ' . $toDelete->count());
            $this->command?->info('بنرات تم فك ارتباطها بقسم محذوف: ' . $orphanBanners);

            if ($dryRun) {
                DB::rollBack();
                $this->command?->warn('DRY RUN: تم التراجع عن كل التعديلات، لم تُحفظ أي تغييرات.');
                return;
            }

            DB::commit();
            $this->command?->info('تم تنفيذ إعادة هيكلة الأقسام بنجاح.');
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
