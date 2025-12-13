<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Product,
    Category,
    Color,
    Material,
    PrintingMethod,
    PrintLocation,
    Offer,
    Size,
    Discount,
    DeliveryTime,
    Warranty,
    Feature,
    Image,
    ProductSizeTier,
    ProductOptions,
    PricingTiers
};
use Illuminate\Support\Facades\DB;

class AddSpecificProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // البحث عن المنتج أولاً أو إنشاؤه
        $product = Product::withTrashed()->find(333);

        if ($product) {
            // إذا كان المنتج محذوفاً بشكل ناعم، أستعيده
            if ($product->trashed()) {
                $product->restore();
            }

            // حذف جميع العلاقات القديمة
            $this->deleteProductRelations($product->id);

            // تحديث بيانات المنتج
            $product->update([
                'category_id' => $this->getOrCreateCategory()->id,
                'name' => 'تيشيرت رياضي مخصص للطباعة',
                'description' => '<p>تيشيرت رياضي عالي الجودة مناسب للطباعة والتصاميم المخصصة</p>
                                <ul>
                                    <li>100% قطن مصري</li>
                                    <li>ملائم للطباعة بجميع التقنيات</li>
                                    <li>متاح بألوان متعددة</li>
                                    <li>مقاسات مختلفة</li>
                                    <li>ضمان 6 أشهر</li>
                                </ul>',
                'price' => 85.99,
                'has_discount' => true,
                'includes_tax' => true,
                'includes_shipping' => false,
                'stock' => 150,
                'status_id' => 1,
                'image' => 'products/tshirt-main.jpg',
            ]);

            $this->command->info('🔄 تم تحديث المنتج الموجود (ID: 333)');
        } else {
            // إنشاء المنتج جديداً
            $category = $this->getOrCreateCategory();

            $product = Product::create([
                'id' => 333,
                'category_id' => $category->id,
                'name' => 'تيشيرت رياضي مخصص للطباعة',
                'description' => '<p>تيشيرت رياضي عالي الجودة مناسب للطباعة والتصاميم المخصصة</p>
                                <ul>
                                    <li>100% قطن مصري</li>
                                    <li>ملائم للطباعة بجميع التقنيات</li>
                                    <li>متاح بألوان متعددة</li>
                                    <li>مقاسات مختلفة</li>
                                    <li>ضمان 6 أشهر</li>
                                </ul>',
                'price' => 85.99,
                'has_discount' => true,
                'includes_tax' => true,
                'includes_shipping' => false,
                'stock' => 150,
                'status_id' => 1,
                'image' => 'products/tshirt-main.jpg',
            ]);

            $this->command->info('✅ تم إنشاء منتج جديد (ID: 333)');
        }

        // الآن نبدأ بإضافة جميع العلاقات
        $this->addProductRelations($product);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->showProductSummary($product);
    }

    /**
     * حذف جميع علاقات المنتج
     */
    private function deleteProductRelations(int $productId): void
    {
        // حذف من جداول pivot
        DB::table('color_product')->where('product_id', $productId)->delete();
        DB::table('material_product')->where('product_id', $productId)->delete();
        DB::table('offer_product')->where('product_id', $productId)->delete();
        DB::table('product_print_methods')->where('product_id', $productId)->delete();
        DB::table('print_location_product')->where('product_id', $productId)->delete();

        // حذف من الجداول المرتبطة مباشرة
        Discount::where('product_id', $productId)->delete();
        DeliveryTime::where('product_id', $productId)->delete();
        Warranty::where('product_id', $productId)->delete();
        Feature::where('product_id', $productId)->delete();
        Size::where('product_id', $productId)->delete();
        ProductSizeTier::where('product_id', $productId)->delete();
        PricingTiers::where('product_id', $productId)->delete();
        ProductOptions::where('product_id', $productId)->delete();
        Image::where('imageable_id', $productId)
            ->where('imageable_type', Product::class)
            ->delete();
    }

    /**
     * الحصول على القسم أو إنشاؤه
     */
    private function getOrCreateCategory(): Category
    {
        return Category::firstOrCreate(
            ['name' => 'ملابس رياضية'],
            [
                'description' => 'ملابس رياضية عالية الجودة',
                'parent_id' => null,
                'status_id' => 1,
                'slug' => 'sports-wear'
            ]
        );
    }

    /**
     * إضافة جميع علاقات المنتج
     */
    private function addProductRelations(Product $product): void
    {
        $this->addDiscount($product);
        $this->addColors($product);
        $this->addMaterials($product);
        $this->addPrintingMethods($product);
        $this->addPrintLocations($product);
        $this->addOffers($product);
        $this->addSizes($product);
        $this->addPricingTiers($product);
        $this->addDeliveryTime($product);
        $this->addWarranty($product);
        $this->addFeatures($product);
        $this->addProductOptions($product);
        $this->addImages($product);
    }

    private function addDiscount(Product $product): void
    {
        Discount::updateOrCreate(
            ['product_id' => $product->id],
            [
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'is_active' => true,
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]
        );
    }

    private function addColors(Product $product): void
    {
        $colors = [
            ['name' => 'أبيض', 'hex_code' => '#FFFFFF', 'price' => 0],
            ['name' => 'أسود', 'hex_code' => '#000000', 'price' => 5],
            ['name' => 'أحمر', 'hex_code' => '#FF0000', 'price' => 8],
            ['name' => 'أزرق', 'hex_code' => '#0000FF', 'price' => 7],
            ['name' => 'رمادي', 'hex_code' => '#808080', 'price' => 3],
        ];

        foreach ($colors as $colorData) {
            $color = Color::firstOrCreate(
                ['name' => $colorData['name']],
                ['hex_code' => $colorData['hex_code']]
            );

            $product->colors()->syncWithoutDetaching([
                $color->id => ['additional_price' => $colorData['price']]
            ]);
        }
    }

    private function addMaterials(Product $product): void
    {
        $materials = [
            ['name' => 'قطن 100%', 'quantity' => 1.5, 'unit' => 'meter', 'price' => 0],
            ['name' => 'خيوط عالية الجودة', 'quantity' => 50, 'unit' => 'gram', 'price' => 3],
            ['name' => 'أحبار طباعة', 'quantity' => 0.1, 'unit' => 'liter', 'price' => 5],
        ];

        foreach ($materials as $materialData) {
            $material = Material::firstOrCreate(
                ['name' => $materialData['name']],
                ['description' => 'مادة عالية الجودة']
            );

            $product->materials()->syncWithoutDetaching([
                $material->id => [
                    'quantity' => $materialData['quantity'],
                    'unit' => $materialData['unit'],
                    'additional_price' => $materialData['price']
                ]
            ]);
        }
    }

    private function addPrintingMethods(Product $product): void
    {
        $printingMethods = [
            ['name' => 'طباعة سلك سكرين', 'base_price' => 15],
            ['name' => 'طباعة ديجيتال', 'base_price' => 20],
            ['name' => 'طباعة نقل حراري', 'base_price' => 12],
            ['name' => 'طباعة ليزر', 'base_price' => 25],
        ];

        foreach ($printingMethods as $methodData) {
            $method = PrintingMethod::firstOrCreate(
                ['name' => $methodData['name']],
                [
                    'description' => 'طريقة طباعة عالية الجودة',
                    'base_price' => $methodData['base_price']
                ]
            );

            $product->printingMethods()->syncWithoutDetaching([
                $method->id => ['additional_price' => rand(0, 5)]
            ]);
        }
    }

    private function addPrintLocations(Product $product): void
    {
        $printLocations = [
            ['name' => 'منتصف الصدر', 'type' => 'print'],
            ['name' => 'الظهر كامل', 'type' => 'print'],
            ['name' => 'على الكم', 'type' => 'embroider'],
            ['name' => 'الجيب الأمامي', 'type' => 'embroider'],
        ];

        foreach ($printLocations as $locationData) {
            $location = PrintLocation::firstOrCreate(
                ['name' => $locationData['name']],
                [
                    'type' => $locationData['type'],
                ]
            );

            $product->printLocations()->syncWithoutDetaching([
                $location->id => ['additional_price' => rand(0, 5)]
            ]);
        }
    }

    private function addOffers(Product $product): void
    {
        $offers = [
            ['name' => 'عرض الصيف', 'description' => 'خصم إضافي على الطلبات الكبيرة'],
            ['name' => 'عرض الشركات', 'description' => 'أسعار خاصة للشركات'],
            ['name' => 'عرض التوصيل المجاني', 'description' => 'توصيل مجاني للطلبات فوق 500 جنيه'],
        ];

        foreach ($offers as $offerData) {
            $offer = Offer::firstOrCreate(
                ['name' => $offerData['name']],
                [
                    'description' => $offerData['description'],
                    'starts_at' => now(),
                    'discount_value' => rand(40, 60),
                    'ends_at' => now()->addMonths(3),
                    'is_active' => true
                ]
            );

            $product->offers()->syncWithoutDetaching([$offer->id]);
        }
    }

    private function addSizes(Product $product): void
    {
        // حذف المقاسات القديمة أولاً
        Size::where('product_id', $product->id)->delete();

        $sizes = ['صغير', 'متوسط', 'كبير', 'اكسترا لارج'];

        foreach ($sizes as $index => $sizeName) {
            $size = Size::create([
                'product_id' => $product->id,
                'name' => $sizeName,
            ]);

            // إضافة مستويات التسعير لكل مقاس
            ProductSizeTier::create([
                'product_id' => $product->id,
                'size_id' => $size->id,
                'quantity' => ($index + 1) * 10,
                'price_per_unit' => 80 - ($index * 5),
            ]);
        }
    }

    private function addPricingTiers(Product $product): void
    {
        $tiers = [
            ['quantity' => 10, 'price_per_unit' => 80.00],
            ['quantity' => 50, 'price_per_unit' => 75.00],
            ['quantity' => 100, 'price_per_unit' => 70.00],
        ];

        foreach ($tiers as $tierData) {
            PricingTiers::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'quantity' => $tierData['quantity']
                ],
                [
                    'price_per_unit' => $tierData['price_per_unit']
                ]
            );
        }
    }

    private function addDeliveryTime(Product $product): void
    {
        DeliveryTime::updateOrCreate(
            ['product_id' => $product->id],
            [
                'from_days' => 3,
                'to_days' => 7,
                'note' => 'التوصيل خلال 3-7 أيام عمل',
            ]
        );
    }

    private function addWarranty(Product $product): void
    {
        Warranty::updateOrCreate(
            ['product_id' => $product->id],
            [
                'duration_months' => 6
            ]
        );
    }

    private function addFeatures(Product $product): void
    {
        $features = [
            ['name' => 'نوع القماش', 'value' => 'قطن 100%'],
            ['name' => 'الوزن', 'value' => '180 جرام'],
            ['name' => 'بلد المنشأ', 'value' => 'مصر'],
            ['name' => 'الغسيل', 'value' => 'يناسب الغسيل الآلي'],
            ['name' => 'الطباعة', 'value' => 'مناسبة لجميع أنواع الطباعة'],
        ];

        foreach ($features as $featureData) {
            Feature::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => $featureData['name']
                ],
                ['value' => $featureData['value']]
            );
        }
    }

    private function addProductOptions(Product $product): void
    {
        $options = [
            ['option_name' => 'نوع الياقة', 'option_value' => 'ياقة مستديرة', 'additional_price' => 0, 'is_required' => true],
            ['option_name' => 'نوع الياقة', 'option_value' => 'ياقة V', 'additional_price' => 3, 'is_required' => false],
            ['option_name' => 'التغليف', 'option_value' => 'تغليف هدايا', 'additional_price' => 10, 'is_required' => false],
        ];

        foreach ($options as $optionData) {
            ProductOptions::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'option_name' => $optionData['option_name'],
                    'option_value' => $optionData['option_value']
                ],
                [
                    'additional_price' => $optionData['additional_price'],
                    'is_required' => $optionData['is_required']
                ]
            );
        }
    }

    private function addImages(Product $product): void
    {
        $images = [
            ['path' => 'products/tshirt-1.jpg', 'alt' => 'تيشيرت من الأمام', 'order' => 1, 'is_primary' => false],
            ['path' => 'products/tshirt-2.jpg', 'alt' => 'تيشيرت من الخلف', 'order' => 2, 'is_primary' => false],
            ['path' => 'products/tshirt-3.jpg', 'alt' => 'تيشيرت على الموديل', 'order' => 3, 'is_primary' => false],
            ['path' => 'products/tshirt-4.jpg', 'alt' => 'تيشيرت مقرّب', 'order' => 4, 'is_primary' => false],
        ];

        foreach ($images as $imageData) {
            Image::updateOrCreate(
                [
                    'imageable_id' => $product->id,
                    'imageable_type' => Product::class,
                    'path' => $imageData['path']
                ],
                [
                    'alt' => $imageData['alt'],
                    'type' => 'product',
                    'order' => $imageData['order'],
                    'is_active' => true,
                    'is_primary' => $imageData['is_primary'],
                ]
            );
        }
    }

    /**
     * عرض ملخص المنتج
     */
    private function showProductSummary(Product $product): void
    {
        $product->load(['colors', 'materials', 'printingMethods', 'sizes']);

        $this->command->info('🎉 تمت معالجة المنتج بنجاح!');
        $this->command->info('===============================');
        $this->command->info('📦 اسم المنتج: ' . $product->name);
        $this->command->info('💰 السعر: ' . $product->price . ' جنيه');
        $this->command->info('💳 السعر النهائي: ' . $product->final_price . ' جنيه (بعد الخصم)');
        $this->command->info('🎨 الألوان: ' . $product->colors->count() . ' لون');
        $this->command->info('📦 المخزون: ' . $product->stock . ' قطعة');
        $this->command->info('📏 المقاسات: ' . $product->sizes->count() . ' مقاس');
        $this->command->info('🧵 المواد: ' . $product->materials->count() . ' مادة');
        $this->command->info('🖨️ طرق الطباعة: ' . $product->printingMethods->count() . ' طريقة');
        $this->command->info('🏷️ العروض: ' . $product->offers->count() . ' عرض');
        $this->command->info('📍 أماكن الطباعة: ' . $product->printLocations->count() . ' مكان');
    }
}
