<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTextAd;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTextAdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // قائمة كبيرة من العروض الترويجية (النصوص + الأيقونات المناسبة)
        $promoTexts = [
            ['name' => 'شحن مجاني على كل الطلبات!',                  'icon' => 'fa-truck-fast'],
            ['name' => 'خصم ١٠٪ عند الدفع الآن',                     'icon' => 'fa-percent'],
            ['name' => 'اشترِ ٢ واحصل على الثالث مجانًا',           'icon' => 'fa-gift'],
            ['name' => 'شحن مجاني + خصم ١٥٪ للطلبات فوق ٥٠٠ ج.م',   'icon' => 'fa-truck-fast'],
            ['name' => 'عرض اليوم فقط: خصم حتى ٢٠٪!',                'icon' => 'fa-fire'],
            ['name' => 'الكمية تزيد.. الخصم يزيد!',                  'icon' => 'fa-tags'],
            ['name' => 'شحن سريع مجاني لفترة محدودة',               'icon' => 'fa-rocket'],
            ['name' => 'خصم ١٥٪ على أول طلب للعميل الجديد',         'icon' => 'fa-user-plus'],
            ['name' => 'اشترِ ٣ واحصل على خصم ٢٥٪',                 'icon' => 'fa-percent'],
            ['name' => 'توصيل مجاني لباب البيت',                     'icon' => 'fa-home'],
            ['name' => 'خصم ١٠٪ + هدية مجانية مع كل طلب',           'icon' => 'fa-gift'],
            ['name' => 'اشترِ أكثر.. وفر أكثر!',                     'icon' => 'fa-wallet'],
            ['name' => 'شحن مجاني عند الطلب فوق ٣٠٠ ج.م فقط',       'icon' => 'fa-truck'],
            ['name' => 'خصم ٢٠٪ على الكميات الكبيرة',               'icon' => 'fa-boxes-stacked'],
            ['name' => 'اطلب الآن واستمتع بشحن مجاني فوري',         'icon' => 'fa-bolt'],
            ['name' => 'عروض خاصة: خصم ١٥٪ + شحن هدية',             'icon' => 'fa-star'],
            ['name' => 'اشترِ ٤ منتجات واحصل على خصم ٣٠٪',          'icon' => 'fa-percent'],
            ['name' => 'شحن مجاني للطلبات فوق ٤٠٠ ج.م',             'icon' => 'fa-truck-fast'],
            ['name' => 'خصم خاص للعملاء الدائمين ١٢٪',              'icon' => 'fa-heart'],
            ['name' => 'كلما زادت الكمية.. زاد التوفير!',           'icon' => 'fa-arrow-trend-up'],
        ];

        // جلب كل المنتجات الموجودة
        $products = Product::select('id')->get();

        if ($products->isEmpty()) {
            $this->command->warn('لا توجد منتجات في جدول products لإضافة العروض الترويجية!');
            return;
        }

        $this->command->info('بدء إضافة عروض ترويجية لـ ' . $products->count() . ' منتج...');

        DB::transaction(function () use ($products, $promoTexts) {
            foreach ($products as $product) {
                // اختيار عرضين عشوائيين مختلفين لكل منتج
                $randomPromos = collect($promoTexts)->random(2);

                foreach ($randomPromos as $promo) {
                    ProductTextAd::create([
                        'product_id' => $product->id,
                        'name'       => $promo['name'],
                        'icon'       => $promo['icon'],
                    ]);
                }
            }
        });

        $this->command->info('تم إضافة عرضين ترويجيين لكل منتج بنجاح! ✓');
    }
}