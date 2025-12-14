<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Product,
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
    PricingTiers,
    Review,
    User
};
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class AddDetailsToProduct45Seeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create('ar_SA');
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // البحث عن المنتج رقم 45
        $product = Product::withTrashed()->find(45);

        if (!$product) {
            $this->command->error('❌ المنتج رقم 45 غير موجود!');
            return;
        }

        $this->command->info('🎯 بدء إضافة تفاصيل للمنتج: ' . $product->name);

        // إضافة الخصم
        $this->addDiscount($product);

        // إضافة الألوان
        $this->addColors($product);

        // إضافة المواد
        $this->addMaterials($product);

        // إضافة طرق الطباعة
        $this->addPrintingMethods($product);

        // إضافة أماكن الطباعة
        $this->addPrintLocations($product);

        // إضافة العروض
        $this->addOffers($product);

        // إضافة المقاسات
        $this->addSizes($product);

        // إضافة مستويات التسعير
        $this->addPricingTiers($product);

        // إضافة وقت التوصيل
        $this->addDeliveryTime($product);

        // إضافة الضمان
        $this->addWarranty($product);

        // إضافة الميزات والمواصفات
        $this->addFeatures($product);

        // إضافة خيارات المنتج
        $this->addProductOptions($product);

        // إضافة الصور
        $this->addImages($product);

        // إضافة التقييمات والمراجعات
        $this->addReviews($product);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // عرض ملخص شامل
        $this->showCompleteSummary($product);
    }

    private function addDiscount(Product $product): void
    {
        $hasDiscount = rand(0, 1); // 50% فرصة للحصول على خصم

        if ($hasDiscount) {
            $discountTypes = ['percentage', 'fixed'];
            $discountType = $discountTypes[array_rand($discountTypes)];

            $discountValue = $discountType === 'percentage'
                ? rand(10, 30)
                : rand(10, 50);

            Discount::updateOrCreate(
                ['product_id' => $product->id],
                [
                    'discount_type' => $discountType,
                    'discount_value' => $discountValue,
                    'is_active' => true,
                    'starts_at' => now(),
                    'ends_at' => now()->addDays(rand(30, 90)),
                ]
            );

            $this->command->info('✅ تم إضافة خصم ' . $discountValue . ($discountType === 'percentage' ? '%' : ' جنيه'));
        }
    }

    private function addColors(Product $product): void
    {
        $colors = [
            ['name' => 'أبيض', 'hex_code' => '#FFFFFF'],
            ['name' => 'أسود', 'hex_code' => '#000000'],
            ['name' => 'أحمر', 'hex_code' => '#FF0000'],
            ['name' => 'أزرق', 'hex_code' => '#0000FF'],
            ['name' => 'أخضر', 'hex_code' => '#008000'],
            ['name' => 'أصفر', 'hex_code' => '#FFFF00'],
            ['name' => 'وردي', 'hex_code' => '#FFC0CB'],
            ['name' => 'بنفسجي', 'hex_code' => '#800080'],
            ['name' => 'برتقالي', 'hex_code' => '#FFA500'],
            ['name' => 'بني', 'hex_code' => '#A52A2A'],
            ['name' => 'رمادي', 'hex_code' => '#808080'],
            ['name' => 'ذهبي', 'hex_code' => '#FFD700'],
            ['name' => 'فضي', 'hex_code' => '#C0C0C0'],
            ['name' => 'تركواز', 'hex_code' => '#40E0D0'],
            ['name' => 'سماوي', 'hex_code' => '#87CEEB'],
        ];

        $selectedColors = array_rand($colors, rand(3, 8)); // اختيار 3-8 ألوان عشوائية

        if (!is_array($selectedColors)) {
            $selectedColors = [$selectedColors];
        }

        foreach ($selectedColors as $index) {
            $colorData = $colors[$index];
            $color = Color::firstOrCreate(
                ['name' => $colorData['name']],
                ['hex_code' => $colorData['hex_code']]
            );

            $additionalPrice = rand(0, 1) ? rand(0, 15) : 0; // بعض الألوان بسعر إضافي

            $product->colors()->syncWithoutDetaching([
                $color->id => ['additional_price' => $additionalPrice]
            ]);
        }

        $this->command->info('🎨 تم إضافة ' . count($selectedColors) . ' لون');
    }

    private function addMaterials(Product $product): void
    {
        $materials = [
            ['name' => 'قطن 100%', 'description' => 'قطن مصري عالي الجودة'],
            ['name' => 'بوليستر', 'description' => 'بوليستر مقاوم للتجعد'],
            ['name' => 'حرير طبيعي', 'description' => 'حرير طبيعي فاخر'],
            ['name' => 'كتان', 'description' => 'كتان مصري صيفي'],
            ['name' => 'صوف', 'description' => 'صوف طبيعي دافئ'],
            ['name' => 'دينيم', 'description' => 'دينيم متين وكلاسيكي'],
            ['name' => 'قُطْنٌ عُضْوِي', 'description' => 'قطن عضوي صديق للبيئة'],
            ['name' => 'فِسْكُوز', 'description' => 'فسكوز ناعم ومريح'],
        ];

        $selectedMaterials = array_rand($materials, rand(1, 4));

        if (!is_array($selectedMaterials)) {
            $selectedMaterials = [$selectedMaterials];
        }

        $units = ['piece', 'meter', 'kg', 'liter', 'gram', 'yard'];
        $unitsArabic = ['قطعة', 'متر', 'كيلو', 'لتر', 'جرام', 'ياردة'];

        foreach ($selectedMaterials as $index) {
            $materialData = $materials[$index];
            $material = Material::firstOrCreate(
                ['name' => $materialData['name']],
                ['description' => $materialData['description']]
            );

            $unitIndex = array_rand($units);
            $quantity = $units[$unitIndex] === 'piece' ? rand(1, 10) : rand(0.5, 5);
            $additionalPrice = rand(0, 1) ? rand(5, 50) : 0;

            $product->materials()->syncWithoutDetaching([
                $material->id => [
                    'quantity' => $quantity,
                    'unit' => $units[$unitIndex],
                    'additional_price' => $additionalPrice
                ]
            ]);
        }

        $this->command->info('🧵 تم إضافة ' . count($selectedMaterials) . ' مادة');
    }

    private function addPrintingMethods(Product $product): void
    {
        $printingMethods = [
            ['name' => 'طباعة سلك سكرين', 'description' => 'طباعة عالية الجودة ودائمة', 'base_price' => rand(10, 20)],
            ['name' => 'طباعة ديجيتال مباشرة', 'description' => 'طباعة مباشرة بدقة عالية', 'base_price' => rand(15, 25)],
            ['name' => 'طباعة نقل حراري', 'description' => 'نقل حراري ملون', 'base_price' => rand(8, 15)],
            ['name' => 'طباعة ليزر', 'description' => 'طباعة ليزر دقيقة', 'base_price' => rand(20, 30)],
            ['name' => 'طباعة سابلون', 'description' => 'طباعة يدوية تقليدية', 'base_price' => rand(12, 18)],
            ['name' => 'طباعة UV', 'description' => 'طباعة بالأشعة فوق البنفسجية', 'base_price' => rand(25, 40)],
        ];

        $selectedMethods = array_rand($printingMethods, rand(2, 4));

        if (!is_array($selectedMethods)) {
            $selectedMethods = [$selectedMethods];
        }

        foreach ($selectedMethods as $index) {
            $methodData = $printingMethods[$index];
            $method = PrintingMethod::firstOrCreate(
                ['name' => $methodData['name']],
                [
                    'description' => $methodData['description'],
                    'base_price' => $methodData['base_price']
                ]
            );

            $additionalPrice = rand(0, 1) ? rand(0, 10) : 0;

            $product->printingMethods()->syncWithoutDetaching([
                $method->id => ['additional_price' => $additionalPrice]
            ]);
        }

        $this->command->info('🖨️ تم إضافة ' . count($selectedMethods) . ' طريقة طباعة');
    }

    private function addPrintLocations(Product $product): void
    {
        $printLocations = [
            ['name' => 'منتصف الصدر', 'type' => 'print'],
            ['name' => 'الظهر كامل', 'type' => 'print'],
            ['name' => 'على الكم الأيسر', 'type' => 'embroider'],
            ['name' => 'على الكم الأيمن', 'type' => 'embroider', 'description' => 'تطريز على الكم الأيمن'],
            ['name' => 'الجيب الأمامي', 'type' => 'print', 'description' => 'طباعة على الجيب الأمامي'],
            ['name' => 'على الكتف', 'type' => 'embroider', 'description' => 'تطريز على الكتف'],
            ['name' => 'حول الرقبة', 'type' => 'print', 'description' => 'طباعة حول منطقة الرقبة'],
            ['name' => 'على الحاشية', 'type' => 'print', 'description' => 'طباعة على حاشية القميص'],
        ];

        $selectedLocations = array_rand($printLocations, rand(2, 5));

        if (!is_array($selectedLocations)) {
            $selectedLocations = [$selectedLocations];
        }

        foreach ($selectedLocations as $index) {
            $locationData = $printLocations[$index];
            $location = PrintLocation::firstOrCreate(
                ['name' => $locationData['name']],
                [
                    'type' => $locationData['type']
                ]
            );

            $additionalPrice = rand(0, 1) ? rand(2, 20) : 0;

            $product->printLocations()->syncWithoutDetaching([
                $location->id => ['additional_price' => $additionalPrice]
            ]);
        }

        $this->command->info('📍 تم إضافة ' . count($selectedLocations) . ' مكان طباعة');
    }

    private function addOffers(Product $product): void
    {
        $offers = [
            ['name' => 'عرض الصيف', 'description' => 'خصومات صيفية حصرية'],
            ['name' => 'عرض التخفيضات الكبرى', 'description' => 'تخفيضات على جميع المنتجات'],
            ['name' => 'عرض الشراء الجماعي', 'description' => 'خصم عند الشراء بكميات'],
            ['name' => 'عرض التوصيل المجاني', 'description' => 'توصيل مجاني لجميع الطلبات'],
            ['name' => 'عرض العضوية الذهبية', 'description' => 'خصومات حصرية لأعضاء النادي'],
            ['name' => 'عرض نهاية الموسم', 'description' => 'تخفيضات نهاية الموسم'],
        ];

        $selectedOffers = array_rand($offers, rand(1, 3));

        if (!is_array($selectedOffers)) {
            $selectedOffers = [$selectedOffers];
        }

        foreach ($selectedOffers as $index) {
            $offerData = $offers[$index];
            $offer = Offer::firstOrCreate(
                ['name' => $offerData['name']],
                [
                    'description' => $offerData['description'],
                    'discount_value' => rand(10, 50),
                    'starts_at' => now(),
                    'ends_at' => now()->addDays(rand(30, 180)),
                    'is_active' => true,
                    //  'min_purchase' => rand(0, 1) ? rand(100, 500) : null,
                ]
            );

            $product->offers()->syncWithoutDetaching([$offer->id]);
        }

        $this->command->info('🏷️ تم إضافة ' . count($selectedOffers) . ' عرض');
    }

    private function addSizes(Product $product): void
    {
        $sizeGroups = [
            'ملابس' => ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
            'أحذية' => ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45'],
            'أطفال' => ['2 سنوات', '4 سنوات', '6 سنوات', '8 سنوات', '10 سنوات', '12 سنوات'],
            'قياسي' => ['صغير', 'متوسط', 'كبير', 'كبير جداً'],
            'رقمي' => ['28', '30', '32', '34', '36', '38', '40', '42'],
        ];

        $randomGroup = array_rand($sizeGroups);
        $sizes = $sizeGroups[$randomGroup];

        // حذف المقاسات القديمة أولاً
        Size::where('product_id', $product->id)->delete();

        foreach ($sizes as $sizeName) {
            $size = Size::create([
                'product_id' => $product->id,
                'name' => $sizeName,
            ]);

            // إضافة مستويات التسعير لكل مقاس
            ProductSizeTier::create([
                'product_id' => $product->id,
                'size_id' => $size->id,
                'quantity' => rand(5, 50),
                'price_per_unit' => $product->price * (rand(80, 120) / 100), // ±20% من السعر الأساسي
            ]);
        }

        $this->command->info('📏 تم إضافة ' . count($sizes) . ' مقاس (' . $randomGroup . ')');
    }

    private function addPricingTiers(Product $product): void
    {
        $basePrice = $product->price;

        $tiers = [];

        // إضافة 3-6 مستويات تسعير
        $numTiers = rand(3, 6);
        for ($i = 1; $i <= $numTiers; $i++) {
            $quantity = $i * 10; // 10, 20, 30, etc.
            $discountPercentage = $i * 5; // 5%, 10%, 15%, etc.
            $tierPrice = $basePrice * (1 - ($discountPercentage / 100));

            $tiers[] = [
                'quantity' => $quantity,
                'price_per_unit' => round($tierPrice, 2),
            ];
        }

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

        $this->command->info('💰 تم إضافة ' . count($tiers) . ' مستوى تسعير');
    }

    private function addDeliveryTime(Product $product): void
    {
        $fromDays = rand(1, 3);
        $toDays = $fromDays + rand(2, 7);

        DeliveryTime::updateOrCreate(
            ['product_id' => $product->id],
            [
                'from_days' => $fromDays,
                'to_days' => $toDays,
                'note' => $this->faker->sentence(),
                // 'is_express_available' => rand(0, 1),
                //  'express_days' => rand(1, 2),
                //'express_additional_cost' => rand(0, 1) ? rand(10, 50) : 0,
            ]
        );

        $this->command->info('🚚 تم إضافة وقت التوصيل: ' . $fromDays . '-' . $toDays . ' أيام');
    }

    private function addWarranty(Product $product): void
    {
        $warrantyOptions = [3, 6, 12, 24, 36]; // أشهر
        $duration = $warrantyOptions[array_rand($warrantyOptions)];

        Warranty::updateOrCreate(
            ['product_id' => $product->id],
            [
                'duration_months' => $duration,
                // 'terms' => $this->faker->paragraph(),
                // 'covers' => $this->faker->words(rand(3, 6), true),
                // 'exclusions' => $this->faker->words(rand(2, 4), true),
                // 'service_centers' => $this->faker->city . ', ' . $this->faker->city,
            ]
        );

        $this->command->info('🛡️ تم إضافة ضمان: ' . $duration . ' شهر');
    }

    private function addFeatures(Product $product): void
    {
        $features = [
            ['name' => 'المادة', 'value' => 'قطن 100% مصري'],
            ['name' => 'الوزن', 'value' => '180 جرام/متر²'],
            ['name' => 'النوع', 'value' => 'تيشيرت دائري الرقبة'],
            ['name' => 'البلد المصنع', 'value' => 'مصر'],
            ['name' => 'طرق الغسيل', 'value' => 'يناسب الغسالة العادية'],
            ['name' => 'درجة الحرارة', 'value' => '40° مئوية كحد أقصى'],
            ['name' => 'التجفيف', 'value' => 'يناسب التجفيف في الهواء الطلق'],
            ['name' => 'الكي', 'value' => 'يناسب الكي على درجة حرارة متوسطة'],
            ['name' => 'اللون', 'value' => 'ثابت ولا يبهت'],
            ['name' => 'المقاسات', 'value' => 'متوفر بجميع المقاسات'],
            ['name' => 'العناية', 'value' => 'لا يستخدم المبيضات'],
            ['name' => 'التعبئة', 'value' => 'تعبئة فردية في أكياس بلاستيكية'],
            ['name' => 'الصداقة للبيئة', 'value' => 'صديق للبيئة وقابل لإعادة التدوير'],
            ['name' => 'المتانة', 'value' => 'متين ويحافظ على شكله بعد الغسيل'],
            ['name' => 'الراحة', 'value' => 'ناعم على البشرة ومريح للارتداء'],
        ];

        // اختيار 5-10 ميزات عشوائية
        $selectedFeatures = array_rand($features, rand(5, 10));

        if (!is_array($selectedFeatures)) {
            $selectedFeatures = [$selectedFeatures];
        }

        foreach ($selectedFeatures as $index) {
            $featureData = $features[$index];
            Feature::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => $featureData['name']
                ],
                ['value' => $featureData['value']]
            );
        }

        $this->command->info('📋 تم إضافة ' . count($selectedFeatures) . ' ميزة');
    }

    private function addProductOptions(Product $product): void
    {
        $options = [
            ['option_name' => 'نوع الياقة', 'option_value' => 'ياقة مستديرة', 'additional_price' => 0, 'is_required' => true],
            ['option_name' => 'نوع الياقة', 'option_value' => 'ياقة V', 'additional_price' => 5, 'is_required' => false],
            ['option_name' => 'نوع الياقة', 'option_value' => 'ياقة بولونيز', 'additional_price' => 8, 'is_required' => false],
            ['option_name' => 'نوع الأكمام', 'option_value' => 'أكمام قصيرة', 'additional_price' => 0, 'is_required' => true],
            ['option_name' => 'نوع الأكمام', 'option_value' => 'أكمام طويلة', 'additional_price' => 10, 'is_required' => false],
            ['option_name' => 'التغليف', 'option_value' => 'تغليف عادي', 'additional_price' => 0, 'is_required' => false],
            ['option_name' => 'التغليف', 'option_value' => 'تغليف هدايا فاخر', 'additional_price' => 25, 'is_required' => false],
            ['option_name' => 'التغليف', 'option_value' => 'تغليف شفاف', 'additional_price' => 5, 'is_required' => false],
            ['option_name' => 'الشعار', 'option_value' => 'بدون شعار', 'additional_price' => 0, 'is_required' => false],
            ['option_name' => 'الشعار', 'option_value' => 'طباعة شعار بسيط', 'additional_price' => 15, 'is_required' => false],
            ['option_name' => 'الشعار', 'option_value' => 'تطريز شعار فاخر', 'additional_price' => 30, 'is_required' => false],
        ];

        // اختيار 3-6 خيارات عشوائية
        $selectedOptions = array_rand($options, rand(3, 6));

        if (!is_array($selectedOptions)) {
            $selectedOptions = [$selectedOptions];
        }

        foreach ($selectedOptions as $index) {
            $optionData = $options[$index];
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

        $this->command->info('⚙️ تم إضافة ' . count($selectedOptions) . ' خيار');
    }

    private function addImages(Product $product): void
    {
        $images = [
            ['path' => 'https://i.ibb.co/9FB8yLZ/16.png', 'alt' => 'صورة أمامية للمنتج', 'order' => 1, 'is_primary' => true],
            ['path' => 'https://i.ibb.co/nqmT7fgh/12.png', 'alt' => 'صورة خلفية للمنتج', 'order' => 2, 'is_primary' => false],
            ['path' => 'https://i.ibb.co/7x4HY1t1/15.png', 'alt' => 'صورة مقرّبة للتفاصيل', 'order' => 3, 'is_primary' => false]
        ];

        // اختيار 4-6 صور عشوائية
        $selectedImages = array_rand($images, rand(2, 3));

        if (!is_array($selectedImages)) {
            $selectedImages = [$selectedImages];
        }

        $primarySet = false;
        foreach ($selectedImages as $index) {
            $imageData = $images[$index];

            // التأكد من وجود صورة رئيسية واحدة فقط
            if (!$primarySet) {
                $imageData['is_primary'] = true;
                $primarySet = true;
            } else {
                $imageData['is_primary'] = false;
            }

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

        $this->command->info('🖼️ تم إضافة ' . count($selectedImages) . ' صورة');
    }

    private function addReviews(Product $product): void
    {
        // الحصول على بعض المستخدمين أو إنشاءهم
        $users = User::take(20)->get();

        if ($users->isEmpty()) {
            // إنشاء بعض المستخدمين إذا لم يكن هناك مستخدمين
            for ($i = 1; $i <= 10; $i++) {
                $users[] = User::create([
                    'name' => $this->faker->name(),
                    'email' => $this->faker->unique()->safeEmail(),
                    'password' => bcrypt('password123'),
                ]);
            }
        }

        // إنشاء 15-30 تقييم عشوائي
        $numReviews = rand(15, 30);

        for ($i = 0; $i < $numReviews; $i++) {
            $user = $users->random();

            $rating = rand(3, 5); // معظم التقييمات إيجابية
            $comment = rand(0, 1) ? $this->generateReviewComment($rating) : null;

            Review::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'user_id' => $user->id
                ],
                [
                    'rating' => $rating,
                    'comment' => $comment,
                    'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
                ]
            );
        }

        $this->command->info('⭐ تم إضافة ' . $numReviews . ' تقييم');
    }

    private function generateReviewComment($rating): string
    {
        $positiveComments = [
            'منتج رائع جداً، أنصح الجميع به!',
            'الجودة ممتازة والسعر مناسب',
            'التوصيل كان سريعاً والمنتج أفضل مما توقعت',
            'شكراً لكم على هذا المنتج الرائع',
            'جودة عالية وتنفيذ ممتاز',
            'اشتريته لعدة مرات وسأستمر في الشراء',
            'المنتج يستحق أكثر من سعره',
            'تجربة شراء ممتازة بكل المقاييس',
            'الخدمة والعناية بالعميل ممتازة',
            'شكراً لكم على الاهتمام بالجودة',
        ];

        $neutralComments = [
            'منتج جيد بشكل عام',
            'السعر مناسب للجودة',
            'لا بأس به لكن يمكن تحسينه',
            'متوسط الجودة',
            'يناسب الاستخدام اليومي',
        ];

        $negativeComments = [
            'يمكن تحسين الجودة',
            'التوصيل تأخر قليلاً',
            'المقاس أصغر قليلاً مما توقعت',
            'اللون يختلف قليلاً عن الصورة',
            'يحتاج بعض التحسينات',
        ];

        if ($rating >= 4) {
            return $positiveComments[array_rand($positiveComments)];
        } elseif ($rating == 3) {
            return $neutralComments[array_rand($neutralComments)];
        } else {
            return $negativeComments[array_rand($negativeComments)];
        }
    }

    private function showCompleteSummary(Product $product): void
    {
        // تحميل جميع العلاقات
        $product->load([
            'colors',
            'materials',
            'printingMethods',
            'printLocations',
            'offers',
            'sizes',
            'pricingTiers',
            'deliveryTime',
            'warranty',
            'features',
            'options',
            'images',
            'reviews'
        ]);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('🎉 تم إضافة تفاصيل كاملة للمنتج بنجاح!');
        $this->command->info('========================================');
        $this->command->info('');
        $this->command->info('📋 ملخص شامل للمنتج رقم ' . $product->id);
        $this->command->info('========================================');
        $this->command->info('📦 اسم المنتج: ' . $product->name);
        $this->command->info('🏷️ السعر: ' . $product->price . ' جنيه');

        if ($product->discount) {
            $this->command->info('💰 الخصم: ' . $product->discount->discount_value .
                ($product->discount->discount_type === 'percentage' ? '%' : ' جنيه'));
            $this->command->info('💳 السعر النهائي: ' . $product->final_price . ' جنيه');
        }

        $this->command->info('📊 المخزون: ' . $product->stock . ' قطعة');
        $this->command->info('🎨 الألوان: ' . $product->colors->count() . ' لون');
        $this->command->info('🧵 المواد: ' . $product->materials->count() . ' مادة');
        $this->command->info('🖨️ طرق الطباعة: ' . $product->printingMethods->count() . ' طريقة');
        $this->command->info('📍 أماكن الطباعة: ' . $product->printLocations->count() . ' مكان');
        $this->command->info('🏷️ العروض: ' . $product->offers->count() . ' عرض');
        $this->command->info('📏 المقاسات: ' . $product->sizes->count() . ' مقاس');
        $this->command->info('💰 مستويات التسعير: ' . $product->pricingTiers->count() . ' مستوى');
        $this->command->info('🚚 وقت التوصيل: ' . ($product->deliveryTime ? $product->deliveryTime->from_days . '-' . $product->deliveryTime->to_days . ' أيام' : 'غير محدد'));
        $this->command->info('🛡️ الضمان: ' . ($product->warranty ? $product->warranty->duration_months . ' شهر' : 'لا يوجد'));
        $this->command->info('📋 الميزات: ' . $product->features->count() . ' ميزة');
        $this->command->info('⚙️ الخيارات: ' . $product->options->count() . ' خيار');
        $this->command->info('🖼️ الصور: ' . $product->images->count() . ' صورة');
        $this->command->info('⭐ التقييمات: ' . $product->reviews->count() . ' تقييم');

        if ($product->reviews->count() > 0) {
            $averageRating = round($product->reviews->avg('rating'), 1);
            $this->command->info('⭐ متوسط التقييم: ' . $averageRating . '/5');
        }

        $this->command->info('');
        $this->command->info('🔗 يمكنك الآن عرض المنتج على الرابط:');
        $this->command->info('📱 الواجهة الأمامية: /product/' . $product->id);
        $this->command->info('⚙️ لوحة التحكم: /admin/products/' . $product->id . '/edit');
        $this->command->info('');
        $this->command->info('✅ تمت العملية بنجاح! المنتج جاهز للعرض والبيع.');
    }
}
