<?php
namespace Database\Seeders;

use App\Models\Size;
use App\Models\Color;
use App\Models\Feature;
use App\Models\Product;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Warranty;
use App\Models\DeliveryTime;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    public function run()
    {
        $categories = Category::whereNotNull('parent_id')->get();
        $colors = Color::all();

        $productsData = [
            // غرف نوم
            [
                'category' => 'غرف نوم',
                'products' => [
                    [
                        'name' => 'سرير خشب زان مودرن',
                        'description' => 'سرير فاخر من خشب الزان الطبيعي بتصميم عصري، متوفر بمقاسات متعددة مع قاعدة قوية وتشطيب ممتاز',
                        'price' => 8500.00,
                        'has_discount' => true,
                        'discount_value' => 15,
                        'discount_type' => 'percentage',
                        'stock' => 15,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'خشب زان طبيعي'],
                            ['name' => 'الأبعاد', 'value' => '200 × 180 سم'],
                            ['name' => 'الوزن', 'value' => '85 كجم'],
                            ['name' => 'التصميم', 'value' => 'مودرن'],
                        ],
                        'sizes' => ['مفرد', 'مزدوج', 'كينج'],
                        'colors' => ['بني', 'بيج', 'أبيض'],
                        'warranty' => 24,
                        'delivery' => ['from' => 3, 'to' => 7]
                    ],
                    [
                        'name' => 'دولاب ملابس 6 أبواب',
                        'description' => 'دولاب واسع بـ 6 أبواب مع مرآة كبيرة وأدراج داخلية، تصميم كلاسيكي فاخر',
                        'price' => 12000.00,
                        'has_discount' => true,
                        'discount_value' => 1500,
                        'discount_type' => 'fixed',
                        'stock' => 8,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'خشب MDF مع قشرة طبيعية'],
                            ['name' => 'عدد الأبواب', 'value' => '6 أبواب'],
                            ['name' => 'الأبعاد', 'value' => '280 × 220 × 60 سم'],
                            ['name' => 'المرآة', 'value' => 'مرآة كاملة على بابين'],
                        ],
                        'colors' => ['بني', 'أبيض', 'بيج'],
                        'warranty' => 18,
                        'delivery' => ['from' => 5, 'to' => 10]
                    ],
                ]
            ],
            // غرف معيشة
            [
                'category' => 'غرف معيشة',
                'products' => [
                    [
                        'name' => 'كنبة زاوية مودرن',
                        'description' => 'كنبة زاوية فاخرة بتصميم عصري، قماش عالي الجودة مع إسفنج مضغوط للراحة القصوى',
                        'price' => 15000.00,
                        'has_discount' => true,
                        'discount_value' => 20,
                        'discount_type' => 'percentage',
                        'stock' => 12,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'قماش كتان فاخر'],
                            ['name' => 'عدد المقاعد', 'value' => '6 مقاعد'],
                            ['name' => 'الإطار', 'value' => 'خشب صلب'],
                            ['name' => 'الإسفنج', 'value' => 'عالي الكثافة'],
                        ],
                        'colors' => ['رمادي', 'بيج', 'أزرق', 'بني'],
                        'warranty' => 36,
                        'delivery' => ['from' => 7, 'to' => 14]
                    ],
                    [
                        'name' => 'طاولة قهوة رخام',
                        'description' => 'طاولة قهوة أنيقة بسطح رخام طبيعي مع قاعدة معدنية ذهبية',
                        'price' => 3500.00,
                        'stock' => 20,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'رخام طبيعي'],
                            ['name' => 'القاعدة', 'value' => 'استانلس ستيل مطلي ذهبي'],
                            ['name' => 'الأبعاد', 'value' => '120 × 60 × 45 سم'],
                        ],
                        'colors' => ['أبيض', 'رمادي', 'أسود'],
                        'warranty' => 12,
                        'delivery' => ['from' => 2, 'to' => 5]
                    ],
                ]
            ],
            // غرف سفرة
            [
                'category' => 'غرف سفرة',
                'products' => [
                    [
                        'name' => 'طاولة سفرة 8 كراسي',
                        'description' => 'طاولة سفرة فاخرة من الخشب الطبيعي مع 8 كراسي منجدة بقماش فاخر',
                        'price' => 18000.00,
                        'has_discount' => true,
                        'discount_value' => 2000,
                        'discount_type' => 'fixed',
                        'stock' => 6,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'خشب زان طبيعي'],
                            ['name' => 'عدد الكراسي', 'value' => '8 كراسي'],
                            ['name' => 'الأبعاد', 'value' => '220 × 100 × 75 سم'],
                            ['name' => 'التنجيد', 'value' => 'قماش مخمل فاخر'],
                        ],
                        'colors' => ['بني', 'أبيض', 'رمادي'],
                        'warranty' => 24,
                        'delivery' => ['from' => 7, 'to' => 14]
                    ],
                    [
                        'name' => 'بوفيه وفاترينة',
                        'description' => 'بوفيه أنيق مع فاترينة زجاجية لعرض الأطباق والتحف',
                        'price' => 9500.00,
                        'stock' => 10,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'خشب MDF مع قشرة'],
                            ['name' => 'الأبعاد', 'value' => '200 × 180 × 45 سم'],
                            ['name' => 'الإضاءة', 'value' => 'إضاءة LED داخلية'],
                        ],
                        'colors' => ['بني', 'بيج', 'أبيض'],
                        'warranty' => 18,
                        'delivery' => ['from' => 5, 'to' => 10]
                    ],
                ]
            ],
            // وحدات تخزين
            [
                'category' => 'خزائن',
                'products' => [
                    [
                        'name' => 'خزانة متعددة الأغراض',
                        'description' => 'خزانة عصرية بأدراج وأرفف متعددة للتخزين العملي',
                        'price' => 4200.00,
                        'has_discount' => true,
                        'discount_value' => 10,
                        'discount_type' => 'percentage',
                        'stock' => 25,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'خشب MDF عالي الجودة'],
                            ['name' => 'الأبعاد', 'value' => '120 × 180 × 40 سم'],
                            ['name' => 'عدد الأدراج', 'value' => '6 أدراج'],
                        ],
                        'colors' => ['أبيض', 'رمادي', 'بني'],
                        'warranty' => 12,
                        'delivery' => ['from' => 3, 'to' => 7]
                    ],
                ]
            ],
            // ديكورات منزلية
            [
                'category' => 'لوحات فنية',
                'products' => [
                    [
                        'name' => 'لوحة فنية مودرن 3 قطع',
                        'description' => 'مجموعة لوحات فنية عصرية بتصميم تجريدي، طباعة عالية الجودة على كانفاس',
                        'price' => 850.00,
                        'has_discount' => true,
                        'discount_value' => 15,
                        'discount_type' => 'percentage',
                        'stock' => 50,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'كانفاس مطبوع'],
                            ['name' => 'الإطار', 'value' => 'خشب مع تغليف'],
                            ['name' => 'عدد القطع', 'value' => '3 قطع'],
                            ['name' => 'الأبعاد', 'value' => '40 × 60 سم لكل قطعة'],
                        ],
                        'colors' => ['متعدد الألوان'],
                        'warranty' => 6,
                        'delivery' => ['from' => 1, 'to' => 3]
                    ],
                    [
                        'name' => 'مرآة ديكور دائرية',
                        'description' => 'مرآة حائط دائرية بإطار معدني ذهبي أنيق',
                        'price' => 1200.00,
                        'stock' => 30,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'زجاج عالي الجودة'],
                            ['name' => 'الإطار', 'value' => 'معدن مطلي ذهبي'],
                            ['name' => 'القطر', 'value' => '80 سم'],
                        ],
                        'colors' => ['ذهبي', 'فضي', 'أسود'],
                        'warranty' => 12,
                        'delivery' => ['from' => 2, 'to' => 5]
                    ],
                ]
            ],
            // أثاث مكتبي
            [
                'category' => 'مكاتب',
                'products' => [
                    [
                        'name' => 'مكتب تنفيذي فاخر',
                        'description' => 'مكتب تنفيذي كبير بتصميم كلاسيكي مع أدراج جانبية وسطح واسع',
                        'price' => 7500.00,
                        'has_discount' => true,
                        'discount_value' => 1000,
                        'discount_type' => 'fixed',
                        'stock' => 12,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'خشب طبيعي مع قشرة'],
                            ['name' => 'الأبعاد', 'value' => '180 × 90 × 75 سم'],
                            ['name' => 'عدد الأدراج', 'value' => '5 أدراج'],
                            ['name' => 'التشطيب', 'value' => 'دهان لامع'],
                        ],
                        'colors' => ['بني', 'أسود', 'بيج'],
                        'warranty' => 24,
                        'delivery' => ['from' => 5, 'to' => 10]
                    ],
                    [
                        'name' => 'كرسي مكتب طبي',
                        'description' => 'كرسي مكتب بتصميم طبي يدعم الظهر والرقبة مع إمكانية التحكم بالارتفاع',
                        'price' => 2800.00,
                        'stock' => 40,
                        'features' => [
                            ['name' => 'الدعم', 'value' => 'دعم قطني وعنق'],
                            ['name' => 'الارتفاع', 'value' => 'قابل للتعديل'],
                            ['name' => 'المادة', 'value' => 'شبك تنفس + إسفنج'],
                            ['name' => 'العجلات', 'value' => '5 عجلات سايلنت'],
                        ],
                        'colors' => ['أسود', 'رمادي', 'أزرق'],
                        'warranty' => 18,
                        'delivery' => ['from' => 2, 'to' => 5]
                    ],
                ]
            ],
            // الإضاءة
            [
                'category' => 'ثريات',
                'products' => [
                    [
                        'name' => 'ثريا كريستال فاخرة',
                        'description' => 'ثريا فاخرة من الكريستال الأصلي بتصميم كلاسيكي مع إضاءة LED',
                        'price' => 5500.00,
                        'has_discount' => true,
                        'discount_value' => 20,
                        'discount_type' => 'percentage',
                        'stock' => 8,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'كريستال أصلي'],
                            ['name' => 'الإضاءة', 'value' => 'LED 60 واط'],
                            ['name' => 'القطر', 'value' => '80 سم'],
                            ['name' => 'الارتفاع', 'value' => '100 سم'],
                        ],
                        'colors' => ['ذهبي', 'فضي'],
                        'warranty' => 24,
                        'delivery' => ['from' => 5, 'to' => 10]
                    ],
                    [
                        'name' => 'أباجورة أرضية مودرن',
                        'description' => 'أباجورة أرضية عصرية بتصميم بسيط وأنيق مع إضاءة قابلة للتعديل',
                        'price' => 980.00,
                        'stock' => 35,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'معدن + قماش'],
                            ['name' => 'الارتفاع', 'value' => '160 سم'],
                            ['name' => 'نوع اللمبة', 'value' => 'E27 LED'],
                            ['name' => 'التحكم', 'value' => 'مفتاح قدم'],
                        ],
                        'colors' => ['أسود', 'أبيض', 'رمادي'],
                        'warranty' => 12,
                        'delivery' => ['from' => 2, 'to' => 4]
                    ],
                ]
            ],
            // أقمشة ومفروشات
            [
                'category' => 'سجاد',
                'products' => [
                    [
                        'name' => 'سجادة تركي مودرن',
                        'description' => 'سجادة فاخرة بتصميم تركي عصري، نسيج عالي الكثافة ومقاوم للبقع',
                        'price' => 3200.00,
                        'has_discount' => true,
                        'discount_value' => 500,
                        'discount_type' => 'fixed',
                        'stock' => 20,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'بوليستر عالي الكثافة'],
                            ['name' => 'الأبعاد', 'value' => '200 × 300 سم'],
                            ['name' => 'السمك', 'value' => '12 ملم'],
                            ['name' => 'المنشأ', 'value' => 'تركيا'],
                        ],
                        'sizes' => ['160×230', '200×300', '250×350'],
                        'colors' => ['رمادي', 'بيج', 'أزرق'],
                        'warranty' => 12,
                        'delivery' => ['from' => 3, 'to' => 7]
                    ],
                    [
                        'name' => 'طقم مفارش سرير قطن',
                        'description' => 'طقم مفارش سرير من القطن المصري الفاخر، يتضمن ملاءة ولحاف و4 أكياس مخدات',
                        'price' => 1850.00,
                        'stock' => 45,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'قطن مصري 100%'],
                            ['name' => 'عدد الخيوط', 'value' => '600 خيط'],
                            ['name' => 'المحتويات', 'value' => 'ملاءة + لحاف + 4 مخدات'],
                        ],
                        'sizes' => ['مفرد', 'مزدوج', 'كينج'],
                        'colors' => ['أبيض', 'بيج', 'رمادي', 'أزرق'],
                        'warranty' => 6,
                        'delivery' => ['from' => 2, 'to' => 5]
                    ],
                ]
            ],
            // المطبخ والحمام
            [
                'category' => 'خزائن مطبخ',
                'products' => [
                    [
                        'name' => 'مطبخ خشب كامل 3 متر',
                        'description' => 'مطبخ كامل بتصميم عصري، خزائن علوية وسفلية مع رخامة وحوض',
                        'price' => 25000.00,
                        'has_discount' => true,
                        'discount_value' => 15,
                        'discount_type' => 'percentage',
                        'stock' => 5,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'خشب MDF مقاوم للرطوبة'],
                            ['name' => 'الطول', 'value' => '3 متر'],
                            ['name' => 'الرخامة', 'value' => 'جرانيت مصري'],
                            ['name' => 'المفصلات', 'value' => 'إيطالية هيدروليك'],
                        ],
                        'colors' => ['أبيض', 'رمادي', 'بني'],
                        'warranty' => 36,
                        'delivery' => ['from' => 14, 'to' => 21]
                    ],
                    [
                        'name' => 'وحدة حمام معلقة',
                        'description' => 'وحدة حمام معلقة مع مرآة وإضاءة LED وحوض سيراميك',
                        'price' => 4800.00,
                        'stock' => 15,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'خشب مقاوم للرطوبة'],
                            ['name' => 'الأبعاد', 'value' => '80 × 60 × 45 سم'],
                            ['name' => 'الحوض', 'value' => 'سيراميك أبيض'],
                            ['name' => 'المرآة', 'value' => 'مع إضاءة LED'],
                        ],
                        'colors' => ['أبيض', 'رمادي', 'بيج'],
                        'warranty' => 24,
                        'delivery' => ['from' => 5, 'to' => 10]
                    ],
                ]
            ],
            // الأجهزة المنزلية
            [
                'category' => 'أجهزة مطبخ صغيرة',
                'products' => [
                    [
                        'name' => 'خلاط كهربائي 3 في 1',
                        'description' => 'خلاط كهربائي متعدد الاستخدامات بـ 3 سرعات ومحرك قوي',
                        'price' => 850.00,
                        'has_discount' => true,
                        'discount_value' => 10,
                        'discount_type' => 'percentage',
                        'stock' => 60,
                        'features' => [
                            ['name' => 'القوة', 'value' => '1000 واط'],
                            ['name' => 'السرعات', 'value' => '3 سرعات + نبضة'],
                            ['name' => 'السعة', 'value' => '1.5 لتر'],
                            ['name' => 'الملحقات', 'value' => 'عصارة + مطحنة + خلاط'],
                        ],
                        'colors' => ['أبيض', 'أسود', 'فضي'],
                        'warranty' => 24,
                        'delivery' => ['from' => 2, 'to' => 4]
                    ],
                    [
                        'name' => 'مكنسة كهربائية روبوت',
                        'description' => 'مكنسة روبوت ذكية بتقنية الذكاء الاصطناعي للتنظيف الأوتوماتيكي',
                        'price' => 5200.00,
                        'stock' => 25,
                        'features' => [
                            ['name' => 'التقنية', 'value' => 'ذكاء اصطناعي'],
                            ['name' => 'البطارية', 'value' => 'ليثيوم 3 ساعات'],
                            ['name' => 'التحكم', 'value' => 'تطبيق موبايل + ريموت'],
                            ['name' => 'المساحة', 'value' => 'حتى 150 متر'],
                        ],
                        'colors' => ['أسود', 'أبيض'],
                        'warranty' => 12,
                        'delivery' => ['from' => 3, 'to' => 7]
                    ],
                ]
            ],
            // أدوات رياضية
            [
                'category' => 'أجهزة رياضية',
                'products' => [
                    [
                        'name' => 'جهاز مشي كهربائي',
                        'description' => 'جهاز مشي احترافي بشاشة رقمية ومحرك قوي مع برامج تدريب متعددة',
                        'price' => 12500.00,
                        'has_discount' => true,
                        'discount_value' => 2000,
                        'discount_type' => 'fixed',
                        'stock' => 10,
                        'features' => [
                            ['name' => 'المحرك', 'value' => '2.5 حصان'],
                            ['name' => 'السرعة', 'value' => 'حتى 16 كم/ساعة'],
                            ['name' => 'الشاشة', 'value' => 'LCD 7 بوصة'],
                            ['name' => 'الوزن المحمول', 'value' => 'حتى 150 كجم'],
                        ],
                        'colors' => ['أسود', 'رمادي'],
                        'warranty' => 24,
                        'delivery' => ['from' => 7, 'to' => 14]
                    ],
                    [
                        'name' => 'سجادة يوغا احترافية',
                        'description' => 'سجادة يوغا مضادة للانزلاق بسمك مثالي وحقيبة حمل',
                        'price' => 350.00,
                        'stock' => 80,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'TPE صديق للبيئة'],
                            ['name' => 'السمك', 'value' => '6 ملم'],
                            ['name' => 'الأبعاد', 'value' => '183 × 61 سم'],
                            ['name' => 'الملحقات', 'value' => 'حقيبة + حزام حمل'],
                        ],
                        'colors' => ['أزرق', 'بنفسجي', 'أخضر', 'رمادي'],
                        'warranty' => 6,
                        'delivery' => ['from' => 1, 'to' => 3]
                    ],
                ]
            ],
            // إلكترونيات
            [
                'category' => 'سماعات',
                'products' => [
                    [
                        'name' => 'سماعة بلوتوث لاسلكية',
                        'description' => 'سماعة بلوتوث 5.0 بصوت نقي وبطارية تدوم 20 ساعة',
                        'price' => 1200.00,
                        'has_discount' => true,
                        'discount_value' => 15,
                        'discount_type' => 'percentage',
                        'stock' => 50,
                        'features' => [
                            ['name' => 'البلوتوث', 'value' => 'نسخة 5.0'],
                            ['name' => 'البطارية', 'value' => '20 ساعة تشغيل'],
                            ['name' => 'المدى', 'value' => 'حتى 10 متر'],
                            ['name' => 'الميزات', 'value' => 'عزل ضوضاء + مايك'],
                        ],
                        'colors' => ['أسود', 'أبيض', 'أزرق', 'أحمر'],
                        'warranty' => 12,
                        'delivery' => ['from' => 2, 'to' => 4]
                    ],
                ]
            ],
            // أثاث خارجي
            [
                'category' => 'طاولات خارجية',
                'products' => [
                    [
                        'name' => 'طقم حديقة 6 كراسي',
                        'description' => 'طقم كامل للحديقة مع طاولة و6 كراسي من الراتان الصناعي المقاوم للعوامل الجوية',
                        'price' => 8900.00,
                        'has_discount' => true,
                        'discount_value' => 1200,
                        'discount_type' => 'fixed',
                        'stock' => 12,
                        'features' => [
                            ['name' => 'المادة', 'value' => 'راتان صناعي + ألمنيوم'],
                            ['name' => 'الطاولة', 'value' => '150 × 90 سم'],
                            ['name' => 'المقاومة', 'value' => 'مقاوم للماء والشمس'],
                            ['name' => 'الوسائد', 'value' => 'وسائد قابلة للإزالة'],
                        ],
                        'colors' => ['بني', 'رمادي', 'أسود'],
                        'warranty' => 18,
                        'delivery' => ['from' => 7, 'to' => 14]
                    ],
                    [
                        'name' => 'أرجوحة حديقة معلقة',
                        'description' => 'أرجوحة مريحة مع مظلة قابلة للتعديل ومقاعد واسعة',
                        'price' => 6500.00,
                        'stock' => 8,
                        'features' => [
                            ['name' => 'السعة', 'value' => '3 أشخاص'],
                            ['name' => 'المظلة', 'value' => 'قابلة للتعديل UV'],
                            ['name' => 'الإطار', 'value' => 'حديد مطلي مقاوم للصدأ'],
                            ['name' => 'الوسائد', 'value' => 'مقاومة للماء'],
                        ],
                        'colors' => ['بيج', 'أخضر', 'رمادي'],
                        'warranty' => 12,
                        'delivery' => ['from' => 5, 'to' => 10]
                    ],
                ]
            ],
        ];

        foreach ($productsData as $categoryData) {
            $category = $categories->firstWhere('name', $categoryData['category']);
            
            if (!$category) continue;

            foreach ($categoryData['products'] as $productData) {
                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $productData['name'],
                    'description' => $productData['description'],
                    'price' => $productData['price'],
                    'has_discount' => $productData['has_discount'] ?? false,
                    'includes_tax' => true,
                    'includes_shipping' => false,
                    'stock' => $productData['stock'],
                ]);

                // إضافة الخصم
                if (isset($productData['discount_value'])) {
                    Discount::create([
                        'product_id' => $product->id,
                        'discount_value' => $productData['discount_value'],
                        'discount_type' => $productData['discount_type'],
                    ]);
                }

                // إضافة المواصفات
                if (isset($productData['features'])) {
                    foreach ($productData['features'] as $feature) {
                        Feature::create([
                            'product_id' => $product->id,
                            'name' => $feature['name'],
                            'value' => $feature['value'],
                        ]);
                    }
                }

                // إضافة الألوان
                if (isset($productData['colors'])) {
                    foreach ($productData['colors'] as $colorName) {
                        $color = $colors->firstWhere('name', $colorName);
                        if ($color) {
                            $product->colors()->attach($color->id);
                        }
                    }
                }

                // إضافة المقاسات
                if (isset($productData['sizes'])) {
                    foreach ($productData['sizes'] as $size) {
                        Size::create([
                            'product_id' => $product->id,
                            'value' => $size,
                        ]);
                    }
                }

                // إضافة الضمان
                if (isset($productData['warranty'])) {
                    Warranty::create([
                        'product_id' => $product->id,
                        'duration_months' => $productData['warranty'],
                    ]);
                }

                // إضافة مدة التوصيل
                if (isset($productData['delivery'])) {
                    DeliveryTime::create([
                        'product_id' => $product->id,
                        'from_days' => $productData['delivery']['from'],
                        'to_days' => $productData['delivery']['to'],
                    ]);
                }
            }
        }
    }
}