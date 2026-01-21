<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use App\Models\Product;
use App\Models\Size;
use App\Models\ProductSizeTier;
use App\Models\PricingTiers;
use App\Models\Material;
use App\Models\DesignService;
use App\Models\PrintingMethod;
use App\Models\PrintLocation;
use App\Models\EmbroiderLocation;
use App\Models\ProductOptions;

class SallaDataProcessorService
{
    public $baseHeaders = [
        'cache-control' => 'no-cache',
        'currency' => 'SAR',
        'origin' => 'https://printnes.co',
        'priority' => 'u=1, i',
        'referer' => 'https://printnes.co/',
        's-anonymous-id' => 'adc56dc2-e714-4bc9-bf6a-56d1241de77c',
        's-app-os' => 'browser',
        's-app-version' => '2.0.0',
        's-country' => 'EG',
        's-ray' => '50',
        's-source' => 'twilight',
        's-store-api-version' => 'swoole',
        's-user-id' => 'rvNP14rwutHVUhMitKNqRvFIE8FX5uewVC4rbeaO',
        's-version-id' => '1731030587',
        'sec-ch-ua' => '"Google Chrome";v="141", "Not?A_Brand";v="8", "Chromium";v="141"',
        'sec-ch-ua-mobile' => '?1',
        'sec-ch-ua-platform' => '"Android"',
        'sec-fetch-dest' => 'empty',
        'sec-fetch-mode' => 'cors',
        'sec-fetch-site' => 'cross-site',
        'store-identifier' => '650799341'
    ];

    // متغير لتخزين الأبعاد المرتبطة بالشرط
    public $sizeConditionMap = [];

    /**
     * استخراج البيانات من صفحة المنتج
     */
    public function extractDataFromHtml($url)
    {
        try {
            $response = Http::withHeaders($this->baseHeaders)->get($url);

            if (!$response->successful()) {
                Log::warning('Failed to fetch product page', ['url' => $url]);
                return null;
            }

            $html = $response->body();

            // استخراج JSON من salla-product-options
            preg_match('/<salla-product-options\s+options="([^"]+)"/', $html, $matches);

            if (!isset($matches[1])) {
                preg_match('/options="([^"]+)"/', $html, $matches);
            }

            if (!isset($matches[1])) {
                Log::warning('Options not found in page', ['url' => $url]);
                return null;
            }

            // فك تشفير وتحويل JSON
            $jsonString = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
            $options = json_decode($jsonString, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('JSON conversion error', [
                    'url' => $url,
                    'error' => json_last_error_msg()
                ]);
                return null;
            }

            return $options;
        } catch (\Exception $e) {
            Log::error('Error extracting data', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * بناء جميع التركيبات الصالحة بناءً على شروط الظهور
     * هذه الدالة هي الحل للمشكلة المذكورة
     */
    public function buildAllValidCombinations(array $options)
    {
        // ترتيب الخيارات بشكل هرمي حسب الأهمية والعلاقات
        $hierarchicalOptions = $this->buildHierarchicalOptions($options);

        $results = [];

        // الدالة الداخلية للتنقل عبر الخيارات باستخدام DFS
        $walk = function ($selected, $remainingOptions) use (&$walk, &$results, $hierarchicalOptions) {
            // إذا لم يتبقى أي خيارات، أضف التركيبة الحالية إلى النتائج
            if (empty($remainingOptions)) {
                $results[] = $selected;
                return;
            }

            // أخذ أول خيار من القائمة المتبقية
            $currentOption = array_shift($remainingOptions);
            $optionId = $currentOption['id'];

            // فحص شرط الظهور لهذا الخيار
            if (!empty($currentOption['visibility_condition'])) {
                $cond = $currentOption['visibility_condition'];
                $conditionOptionId = $cond['option'];
                $conditionValue = $cond['value'];

                // التحقق مما إذا تم اختيار الخيار الشرطي
                if (
                    !isset($selected[$conditionOptionId]) ||
                    $selected[$conditionOptionId]['detail_id'] != $conditionValue
                ) {
                    // إذا لم يتحقق الشرط، تخطي هذا الخيار وانتقل للباقي
                    $walk($selected, $remainingOptions);
                    return;
                }
            }

            // تجربة جميع تفاصيل الخيار الحالي
            foreach ($currentOption['details'] as $detail) {
                $newSelected = $selected;
                $newSelected[$optionId] = [
                    'option_id' => $optionId,
                    'option_name' => $currentOption['name'],
                    'value' => $detail['name'],
                    'detail_id' => $detail['id'],
                    'additional_price' => $detail['additional_price'] ?? 0,
                ];

                // استمرار التنقل في الخيارات المتبقية
                $walk($newSelected, $remainingOptions);
            }
        };

        // البدء مع خيارات فارغة
        $walk([], $hierarchicalOptions);

        // تصفية التركيبات الفارغة
        $validCombinations = collect($results)
            ->filter(fn($combination) => !empty($combination))
            ->values()
            ->map(function ($combination) {
                // تحويل التركيبة إلى شكل يمكن قراءته
                $readableCombination = [];
                $totalAdditionalPrice = 0;

                foreach ($combination as $option) {
                    $readableCombination[$option['option_name']] = $option['value'];
                    $totalAdditionalPrice += $option['additional_price'];
                }

                return [
                    'combination' => $readableCombination,
                    'details' => $combination,
                    'total_additional_price' => $totalAdditionalPrice,
                    'option_count' => count($combination)
                ];
            });

        return $validCombinations;
    }

    /**
     * بناء هيكل هرمي للخيارات بناءً على العلاقات
     */
    public function buildHierarchicalOptions(array $options): array
    {
        $optionsById = collect($options)->keyBy('id');
        $dependencyGraph = [];
        $roots = [];

        // بناء مخطط التبعيات
        foreach ($options as $option) {
            $optionId = $option['id'];
            $dependencyGraph[$optionId] = [
                'option' => $option,
                'dependencies' => [],
                'dependents' => []
            ];

            if (!empty($option['visibility_condition'])) {
                $dependencyId = $option['visibility_condition']['option'];
                $dependencyGraph[$optionId]['dependencies'][] = $dependencyId;

                if (isset($dependencyGraph[$dependencyId])) {
                    $dependencyGraph[$dependencyId]['dependents'][] = $optionId;
                }
            }
        }

        // إيجاد الجذور (الخيارات بدون تبعيات)
        foreach ($dependencyGraph as $optionId => $data) {
            if (empty($data['dependencies'])) {
                $roots[] = $optionId;
            }
        }

        // إذا لم توجد جذور واضحة، نرتب حسب أولوية الخيارات
        if (empty($roots)) {
            return $this->sortOptionsByPriority($options);
        }

        // استخراج الخيارات بترتيب صحيح (من الجذور إلى الأوراق)
        $sortedOptions = [];
        $visited = [];

        $visit = function ($optionId) use (&$visit, &$sortedOptions, &$visited, $dependencyGraph, $optionsById) {
            if (in_array($optionId, $visited)) return;
            $visited[] = $optionId;

            // زيارات متعددة أولاً
            foreach ($dependencyGraph[$optionId]['dependents'] as $dependentId) {
                $visit($dependentId);
            }

            // إضافة الخيار الحالي بعد زيارات التبعيات
            $sortedOptions[] = $optionsById[$optionId];
        };

        foreach ($roots as $rootId) {
            $visit($rootId);
        }

        // إضافة أي خيارات لم تتم زيارتها
        foreach ($options as $option) {
            if (!in_array($option['id'], $visited)) {
                $sortedOptions[] = $option;
            }
        }

        return $sortedOptions;
    }

    /**
     * ترتيب الخيارات حسب الأولوية
     */
    public function sortOptionsByPriority(array $options): array
    {
        $priorityOrder = [
            'المقاس' => 1,
            'الحجم' => 1,
            'Size' => 1,
            'الكمية' => 2,
            'عدد' => 2,
            'Quantity' => 2,
            'الخامة' => 3,
            'المادة' => 3,
            'Material' => 3,
            'خدمة التصميم' => 4,
            'تصميم' => 4,
            'Design' => 4,
            'طريقة الطباعة' => 5,
            'الطباعة' => 5,
            'Printing' => 5,
            'مكان الطباعة' => 6,
            'موقع الطباعة' => 6,
            'Print Location' => 6,
            'التطريز' => 7,
            'تطريز' => 7,
            'Embroider' => 7
        ];

        return collect($options)->sortBy(function ($option) use ($priorityOrder) {
            $name = $option['name'] ?? '';
            foreach ($priorityOrder as $keyword => $priority) {
                if (str_contains($name, $keyword)) {
                    return $priority;
                }
            }
            return 999; // أولوية منخفضة للخيارات الأخرى
        })->values()->toArray();
    }

    /**
     * معالجة بيانات المنتج وتخزينها في الجداول المناسبة
     */
    public function processProductOptions($productId, $options)
    {
        $product = Product::find($productId);
        if (!$product) {
            Log::error('Product not found', ['product_id' => $productId]);
            return false;
        }

        // أولاً: تجميع وتحليل العلاقات بين الخيارات
        $this->analyzeOptionRelationships($options);

        // بناء التركيبات الصالحة
        $validCombinations = $this->buildAllValidCombinations($options);

        // تخزين التركيبات في قاعدة البيانات
        $this->storeCombinations($productId, $validCombinations);

        $results = [
            'sizes' => [],
            'materials' => [],
            'design_services' => [],
            'printing_methods' => [],
            'print_locations' => [],
            'general_options' => [],
            'conditions' => [],
            'quantity_tiers' => [],
            'valid_combinations' => $validCombinations->count()
        ];

        // أولاً: معالجة جميع الأحجام
        foreach ($options as $option) {
            $name = $option['name'] ?? '';
            if (str_contains($name, 'المقاس') || str_contains($name, 'الحجم') || str_contains($name, 'Size')) {
                $this->processSizeOption($product, $option, $results);
            }
        }

        // ثانياً: معالجة باقي الخيارات
        foreach ($options as $option) {
            $name = $option['name'] ?? '';

            if (str_contains($name, 'المقاس') || str_contains($name, 'الحجم') || str_contains($name, 'Size')) {
                continue; // تمت معالجتها مسبقاً
            }

            if (str_contains($name, 'الكمية') || str_contains($name, 'عدد') || str_contains($name, 'Quantity')) {
                $this->processQuantityOption($product, $option, $results);
            } elseif (str_contains($name, 'الخامة') || str_contains($name, 'المادة') || str_contains($name, 'Material')) {
                $this->processMaterialOption($product, $option, $results);
            } elseif (str_contains($name, 'خدمة التصميم') || str_contains($name, 'تصميم') || str_contains($name, 'Design')) {
                $this->processDesignServiceOption($product, $option, $results);
            } elseif (str_contains($name, 'طريقة الطباعة') || str_contains($name, 'الطباعة') || str_contains($name, 'Printing')) {
                $this->processPrintingMethodOption($product, $option, $results);
            } elseif (str_contains($name, 'مكان الطباعة') || str_contains($name, 'موقع الطباعة') || str_contains($name, 'Print Location')) {
                $this->processPrintLocationOption($product, $option, $results);
            } elseif (str_contains($name, 'التطريز') || str_contains($name, 'تطريز') || str_contains($name, 'Embroider')) {
                $this->processEmbroiderLocationOption($product, $option, $results);
            } else {
                $this->processGeneralOption($product, $option, $results);
            }

            // جمع شروط الظهور
            if (isset($option['visibility_condition']) && $option['visibility_condition']) {
                $results['conditions'][] = [
                    'option_id' => $option['id'],
                    'option_name' => $name,
                    'condition' => $option['visibility_condition']
                ];
            }
        }

        // تخزين شروط الظهور
        $this->storeVisibilityConditions($productId, $options);

        return $results;
    }

    /**
     * تخزين التركيبات في قاعدة البيانات
     */
    public function storeCombinations($productId, $combinations)
    {
        $product = Product::find($productId);
        if (!$product) return;

        // تخزين في حقل JSON في قاعدة البيانات
        $product->valid_combinations = $combinations->toArray();
        $product->combination_count = $combinations->count();
        $product->save();

        Log::info('Combinations stored', [
            'product_id' => $productId,
            'combination_count' => $combinations->count()
        ]);
    }

    /**
     * تحليل العلاقات بين الخيارات
     */
    public function analyzeOptionRelationships($options)
    {
        $this->sizeConditionMap = [];

        foreach ($options as $option) {
            if (isset($option['visibility_condition']) && $option['visibility_condition']) {
                $condition = $option['visibility_condition'];
                $targetOptionId = $condition['option'];
                $targetValue = $condition['value'];

                // إذا كان الخيار مرتبطاً بخيار مقاس
                foreach ($options as $sizeOption) {
                    if ($sizeOption['id'] == $targetOptionId) {
                        // البحث عن تفاصيل المقاس التي تطابق القيمة
                        foreach ($sizeOption['details'] as $detail) {
                            if ($detail['id'] == $targetValue) {
                                $this->sizeConditionMap[$option['id']] = [
                                    'size_option_id' => $targetOptionId,
                                    'size_detail_id' => $targetValue,
                                    'size_detail_name' => $detail['name']
                                ];
                                break;
                            }
                        }
                        break;
                    }
                }
            }
        }
    }

    /**
     * تحليل العلاقات بين الخيارات
     */



    /**
     * معالجة خيارات المقاس
     */
    public function processSizeOption($product, $option, &$results)
    {
        foreach ($option['details'] as $detail) {
            // استخراج أبعاد المقاس من الاسم
            $sizeName = $detail['name'] ?? '';
            $dimension = $this->extractDimension($sizeName);

            // إنشاء أو تحديث المقاس
            $size = Size::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'name' => $sizeName
                ],
                [
                    'dimension' => $dimension['dimension'],
                    'unit' => $dimension['unit']
                ]
            );

            $results['sizes'][] = [
                'id' => $size->id,
                'name' => $sizeName,
                'dimension' => $dimension['dimension'],
                'unit' => $dimension['unit'],
                'external_detail_id' => $detail['id']
            ];
        }
    }

    /**
     * معالجة خيارات الكمية
     */
    public function processQuantityOption($product, $option, &$results)
    {
        $parentSizeId = null;
        $parentSizeName = null;

        // التحقق إذا كان هناك شرط ظهور مرتبط بمقاس معين
        if (isset($this->sizeConditionMap[$option['id']])) {
            $conditionInfo = $this->sizeConditionMap[$option['id']];
            $parentSizeName = $conditionInfo['size_detail_name'];

            // البحث عن المقاس في قاعدة البيانات
            $parentSize = Size::where('product_id', $product->id)
                ->where('name', $parentSizeName)
                ->first();

            if ($parentSize) {
                $parentSizeId = $parentSize->id;
            }
        }

        foreach ($option['details'] as $detail) {
            // استخراج الكمية والسعر من الاسم
            $quantityData = $this->extractQuantityAndPrice($detail['name']);

            if ($quantityData['quantity'] > 0) {
                // إذا كان هناك size_id، تخزين في ProductSizeTier
                if ($parentSizeId) {
                    $sizeTier = ProductSizeTier::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'size_id' => $parentSizeId,
                            'quantity' => $quantityData['quantity']
                        ],
                        [
                            'price_per_unit' => $quantityData['unit_price'],
                            'additional_price' => $detail['additional_price'] ?? 0,
                            'unit_price' => $quantityData['unit_price']
                        ]
                    );
                } else {
                    // إذا لم يكن هناك size_id، خزن فقط في PricingTiers
                    $sizeTier = ProductSizeTier::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'quantity' => $quantityData['quantity']
                        ],
                        [
                            'price_per_unit' => $quantityData['unit_price'],
                            // 'additional_price' => $detail['additional_price'] ?? 0,
                            // 'unit_price' => $quantityData['unit_price']
                        ]
                    );
                }

                // تخزين في PricingTiers أيضاً
                $pricingTier = PricingTiers::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'quantity' => $quantityData['quantity']
                    ],
                    [
                        'price_per_unit' => $quantityData['unit_price'],
                        'unit_price' => $quantityData['unit_price'],
                        'is_sample' => $quantityData['is_sample'] ?? false,
                        'discount_percentage' => $quantityData['discount_percentage'] ?? 0
                    ]
                );

                $results['quantity_tiers'][] = [
                    'quantity' => $quantityData['quantity'],
                    'unit_price' => $quantityData['unit_price'],
                    'size_name' => $parentSizeName ?? 'عام',
                    'additional_price' => $detail['additional_price'] ?? 0
                ];
            }
        }
    }

    /**
     * معالجة خيارات المواد
     */
    public function processMaterialOption($product, $option, &$results)
    {
        foreach ($option['details'] as $detail) {
            $material = Material::firstOrCreate(
                ['name' => $detail['name']],
                ['description' => $detail['name']]
            );

            // ربط المادة بالمنتج مع السعر الإضافي
            $product->materials()->syncWithoutDetaching([
                $material->id => [
                    'additional_price' => $detail['additional_price'] ?? 0,
                    'quantity' => 1,
                    'unit' => 'piece'
                ]
            ]);

            $results['materials'][] = [
                'id' => $material->id,
                'name' => $detail['name'],
                'additional_price' => $detail['additional_price'] ?? 0
            ];
        }
    }

    /**
     * معالجة خدمات التصميم
     */
    public function processDesignServiceOption($product, $option, &$results)
    {
        foreach ($option['details'] as $detail) {
            $designService = DesignService::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'description' => $detail['name'],
                    'price' => $detail['additional_price'] ?? 0
                ]
            );

            // ربط خدمة التصميم بالمنتج
            $product->designServices()->syncWithoutDetaching([
                $designService->id => ['additional_price' => $detail['additional_price'] ?? 0]
            ]);

            $results['design_services'][] = [
                'id' => $designService->id,
                'name' => $detail['name'],
                'price' => $detail['additional_price'] ?? 0
            ];
        }
    }

    /**
     * معالجة طرق الطباعة
     */
    public function processPrintingMethodOption($product, $option, &$results)
    {
        foreach ($option['details'] as $detail) {
            $printingMethod = PrintingMethod::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'description' => $detail['name'],
                    'base_price' => $detail['additional_price'] ?? 0
                ]
            );

            // ربط طريقة الطباعة بالمنتج
            $product->printingMethods()->syncWithoutDetaching([
                $printingMethod->id => ['additional_price' => $detail['additional_price'] ?? 0]
            ]);

            $results['printing_methods'][] = [
                'id' => $printingMethod->id,
                'name' => $detail['name'],
                'base_price' => $detail['additional_price'] ?? 0
            ];
        }
    }

    /**
     * معالجة مواقع الطباعة
     */
    public function processPrintLocationOption($product, $option, &$results)
    {
        foreach ($option['details'] as $detail) {
            $printLocation = PrintLocation::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'type' => $option['name'],
                    'additional_price' => $detail['additional_price'] ?? 0
                ]
            );

            // ربط موقع الطباعة بالمنتج
            $product->printLocations()->syncWithoutDetaching([
                $printLocation->id => ['additional_price' => $detail['additional_price'] ?? 0]
            ]);

            $results['print_locations'][] = [
                'id' => $printLocation->id,
                'name' => $detail['name'],
                'type' => $option['name'],
                'additional_price' => $detail['additional_price'] ?? 0
            ];
        }
    }

    /**
     * معالجة مواقع التطريز
     */
    public function processEmbroiderLocationOption($product, $option, &$results)
    {
        foreach ($option['details'] as $detail) {
            $embroiderLocation = EmbroiderLocation::firstOrCreate(
                ['name' => $detail['name']],
                ['additional_price' => $detail['additional_price'] ?? 0]
            );

            // ربط موقع التطريز بالمنتج
            $product->embroiderLocations()->syncWithoutDetaching([
                $embroiderLocation->id => ['additional_price' => $detail['additional_price'] ?? 0]
            ]);
        }
    }

    /**
     * معالجة الخيارات العامة
     */
    public function processGeneralOption($product, $option, &$results)
    {
        foreach ($option['details'] as $detail) {
            ProductOptions::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'external_option_id' => $option['id'],
                    'external_detail_id' => $detail['id']
                ],
                [
                    'option_name' => $option['name'],
                    'option_value' => $detail['name'],
                    'additional_price' => $detail['additional_price'] ?? 0,
                    'is_required' => $option['required'] ?? false
                ]
            );

            $results['general_options'][] = [
                'option_name' => $option['name'],
                'option_value' => $detail['name'],
                'additional_price' => $detail['additional_price'] ?? 0,
                'is_required' => $option['required'] ?? false
            ];
        }
    }

    /**
     * استخراج الأبعاد من اسم المقاس
     */
    public function extractDimension($sizeName)
    {
        // أنماط شائعة للأحجام
        $patterns = [
            '/(\d+)\s*x\s*(\d+)\s*(سم|cm|م|m)/i',
            '/(\d+)\s*×\s*(\d+)\s*(سم|cm|م|m)/i',
            '/(\d+)\s*ب\s*(\d+)\s*(سم|cm|م|m)/i',
            '/(\d+)\s*في\s*(\d+)\s*(سم|cm|م|m)/i',
            '/(\d+)\s*by\s*(\d+)\s*(سم|cm|م|m)/i'
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $sizeName, $matches)) {
                return [
                    'dimension' => $matches[1] . 'x' . $matches[2],
                    'unit' => $matches[3]
                ];
            }
        }

        // إذا لم يتم العثور على نمط، استخدم الاسم كاملاً
        return [
            'dimension' => $sizeName,
            'unit' => 'piece'
        ];
    }

    /**
     * استخراج الكمية والسعر من اسم الخيار
     */
    public function extractQuantityAndPrice($optionName)
    {
        $quantity = 1;
        $unitPrice = 0;
        $isSample = false;
        $discountPercentage = 0;

        // استخراج الكمية
        if (preg_match('/(\d+)\s*حبة/i', $optionName, $matches)) {
            $quantity = (int)$matches[1];
        } elseif (preg_match('/(\d+)\s*قطعة/i', $optionName, $matches)) {
            $quantity = (int)$matches[1];
        } elseif (preg_match('/(\d+)\s*piece/i', $optionName, $matches)) {
            $quantity = (int)$matches[1];
        }

        // استخراج السعر للوحدة
        if (preg_match('/\(?\s*([\d\.]+)\s*ريال\s*\/?\s*للحبة\s*\)?/i', $optionName, $matches)) {
            $unitPrice = (float)$matches[1];
        } elseif (preg_match('/\(?\s*([\d\.]+)\s*ر\.س\s*\/?\s*للحبة\s*\)?/i', $optionName, $matches)) {
            $unitPrice = (float)$matches[1];
        } elseif (preg_match('/\(?\s*([\d\.]+)\s*SAR\s*\/?\s*piece\s*\)?/i', $optionName, $matches)) {
            $unitPrice = (float)$matches[1];
        }

        // استخراج نسبة الخصم إذا وجدت
        if (preg_match('/خصم\s*(\d+)%/i', $optionName, $matches)) {
            $discountPercentage = (float)$matches[1];
        }

        // التحقق إذا كان عينة
        if (str_contains($optionName, 'عينة') || str_contains($optionName, 'sample')) {
            $isSample = true;
        }

        return [
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'is_sample' => $isSample,
            'discount_percentage' => $discountPercentage
        ];
    }

    /**
     * تخزين شروط الظهور
     */
    public function storeVisibilityConditions($productId, $options)
    {
        $conditions = [];

        foreach ($options as $option) {
            if (isset($option['visibility_condition']) && $option['visibility_condition']) {
                $conditions[] = [
                    'option_id' => $option['id'],
                    'option_name' => $option['name'] ?? '',
                    'condition' => $option['visibility_condition']
                ];
            }
        }

        if (!empty($conditions)) {
            $product = Product::find($productId);
            if ($product) {
                $product->options_conditions = $conditions;
                $product->save();
            }
        }
    }

    /**
     * بناء جميع التركيبات الممكنة (الطريقة القديمة)
     */
    public function buildAllCombinations($productId)
    {
        $product = Product::with([
            'sizes',
            'sizeTiers',
            'materials',
            'designServices',
            'printingMethods',
            'printLocations',
            'options'
        ])->find($productId);

        if (!$product) {
            return [];
        }

        $combinations = [];

        // إذا كان هناك أحجام
        if ($product->sizes->count() > 0) {
            foreach ($product->sizes as $size) {
                // الحصول على الكميات المتاحة لهذا المقاس
                $quantities = $product->sizeTiers->where('size_id', $size->id);

                if ($quantities->count() > 0) {
                    foreach ($quantities as $quantityTier) {
                        $combination = [
                            'size' => $size->name,
                            'quantity' => $quantityTier->quantity,
                            'unit_price' => $quantityTier->price_per_unit,
                            'total_price' => $quantityTier->quantity * $quantityTier->price_per_unit
                        ];

                        $combinations[] = $combination;
                    }
                } else {
                    // إذا لم تكن هناك كميات محددة لهذا المقاس
                    $combinations[] = [
                        'size' => $size->name,
                        'quantity' => 1,
                        'unit_price' => $product->base_price,
                        'total_price' => $product->base_price
                    ];
                }
            }
        } else {
            // إذا لم تكن هناك أحجام، استخدم فقط الكميات العامة
            $quantities = $product->sizeTiers->whereNull('size_id');

            foreach ($quantities as $quantityTier) {
                $combination = [
                    'size' => 'عام',
                    'quantity' => $quantityTier->quantity,
                    'unit_price' => $quantityTier->price_per_unit,
                    'total_price' => $quantityTier->quantity * $quantityTier->price_per_unit
                ];

                $combinations[] = $combination;
            }
        }

        return $combinations;
    }

    /**
     * الحصول على التركيبات المخزنة
     */
    public function getStoredCombinations($productId)
    {
        $product = Product::find($productId);
        if (!$product || empty($product->valid_combinations)) {
            return collect([]);
        }

        return collect($product->valid_combinations);
    }

    /**
     * بناء جميع التركيبات الصالحة بناءً على شروط الظهور (النسخة المصححة)
     */
    // public function buildAllValidCombinations(array $options)
    // {
    //     // ترتيب الخيارات بشكل صحيح (من الأب إلى الابن)
    //     $hierarchicalOptions = $this->buildHierarchicalOptions($options);

    //     $results = [];
    //     $requiredOptions = collect($hierarchicalOptions)
    //         ->filter(fn($option) => $option['required'] ?? false)
    //         ->pluck('id')
    //         ->toArray();

    //     // الدالة الداخلية للتنقل عبر الخيارات باستخدام DFS
    //     $walk = function ($selected, $remainingOptions, $depth = 0) use (&$walk, &$results, $hierarchicalOptions, $requiredOptions) {
    //         // إذا لم يتبقى أي خيارات، تحقق من الشروط ثم أضف التركيبة
    //         if (empty($remainingOptions)) {
    //             // تأكد من وجود جميع الخيارات المطلوبة
    //             $missingRequired = array_diff($requiredOptions, array_keys($selected));
    //             if (!empty($missingRequired) && count($selected) > 0) {
    //                 // إذا كانت هناك خيارات مطلوبة مفقودة، تجاهل هذه التركيبة
    //                 return;
    //             }

    //             // تأكد من وجود خيارات على الأقل
    //             if (count($selected) > 0) {
    //                 $results[] = $selected;
    //             }
    //             return;
    //         }

    //         // أخذ أول خيار من القائمة المتبقية
    //         $currentOption = array_shift($remainingOptions);
    //         $optionId = $currentOption['id'];

    //         // فحص شرط الظهور لهذا الخيار
    //         if (!empty($currentOption['visibility_condition'])) {
    //             $cond = $currentOption['visibility_condition'];
    //             $conditionOptionId = $cond['option'];
    //             $conditionValue = $cond['value'];

    //             // التحقق مما إذا تم اختيار الخيار الشرطي
    //             if (
    //                 !isset($selected[$conditionOptionId]) ||
    //                 $selected[$conditionOptionId]['detail_id'] != $conditionValue
    //             ) {
    //                 // إذا لم يتحقق الشرط، تجاهل هذا الخيار فقط وتابع مع الباقي
    //                 $walk($selected, $remainingOptions, $depth + 1);
    //                 return;
    //             }
    //         }

    //         // تجربة جميع تفاصيل الخيار الحالي
    //         foreach ($currentOption['details'] as $detail) {
    //             $newSelected = $selected;
    //             $newSelected[$optionId] = [
    //                 'option_id' => $optionId,
    //                 'option_name' => $currentOption['name'],
    //                 'value' => $detail['name'],
    //                 'detail_id' => $detail['id'],
    //                 'additional_price' => $detail['additional_price'] ?? 0,
    //                 'is_required' => $currentOption['required'] ?? false,
    //             ];

    //             // استمرار التنقل في الخيارات المتبقية
    //             $walk($newSelected, $remainingOptions, $depth + 1);
    //         }

    //         // أيضاً، فكر في حالة عدم اختيار هذا الخيار (إذا لم يكن مطلوباً)
    //         if (!($currentOption['required'] ?? false)) {
    //             $walk($selected, $remainingOptions, $depth + 1);
    //         }
    //     };

    //     // البدء مع خيارات فارغة
    //     $walk([], $hierarchicalOptions);

    //     // تصفية التركيبات الفارغة وتحسين الأداء
    //     $validCombinations = collect($results)
    //         ->filter(function ($combination) use ($requiredOptions) {
    //             // تأكد من وجود جميع الخيارات المطلوبة
    //             if (!empty($requiredOptions)) {
    //                 $selectedIds = array_keys($combination);
    //                 foreach ($requiredOptions as $requiredId) {
    //                     if (!in_array($requiredId, $selectedIds)) {
    //                         return false;
    //                     }
    //                 }
    //             }
    //             return count($combination) > 0;
    //         })
    //         ->unique(function ($combination) {
    //             // إزالة التكرارات بناءً على اختيارات متطابقة
    //             return md5(json_encode($combination));
    //         })
    //         ->values()
    //         ->map(function ($combination) {
    //             // تحويل التركيبة إلى شكل يمكن قراءته
    //             $readableCombination = [];
    //             $totalAdditionalPrice = 0;
    //             $optionCount = 0;

    //             foreach ($combination as $option) {
    //                 $readableCombination[$option['option_name']] = $option['value'];
    //                 $totalAdditionalPrice += $option['additional_price'];
    //                 $optionCount++;
    //             }

    //             return [
    //                 'combination' => $readableCombination,
    //                 'details' => $combination,
    //                 'total_additional_price' => $totalAdditionalPrice,
    //                 'option_count' => $optionCount
    //             ];
    //         });

    //     return $validCombinations;
    // }

    /**
     * بناء هيكل هرمي للخيارات (النسخة المصححة)
     */
    // public function buildHierarchicalOptions(array $options): array
    // {
    //     $optionsById = collect($options)->keyBy('id');
    //     $dependencyGraph = [];
    //     $roots = [];

    //     // بناء مخطط التبعيات
    //     foreach ($options as $option) {
    //         $optionId = $option['id'];
    //         $dependencyGraph[$optionId] = [
    //             'option' => $option,
    //             'dependencies' => [],
    //             'dependents' => []
    //         ];

    //         if (!empty($option['visibility_condition'])) {
    //             $dependencyId = $option['visibility_condition']['option'];
    //             $dependencyGraph[$optionId]['dependencies'][] = $dependencyId;

    //             if (isset($dependencyGraph[$dependencyId])) {
    //                 $dependencyGraph[$dependencyId]['dependents'][] = $optionId;
    //             }
    //         }
    //     }

    //     // إيجاد الجذور (الخيارات بدون تبعيات)
    //     foreach ($dependencyGraph as $optionId => $data) {
    //         if (empty($data['dependencies'])) {
    //             $roots[] = $optionId;
    //         }
    //     }

    //     // إعادة ترتيب الخيارات بالطريقة الصحيحة (الأب ثم الأبناء)
    //     $sortedOptions = [];
    //     $visited = [];

    //     $visit = function ($optionId) use (&$visit, &$sortedOptions, &$visited, $dependencyGraph, $optionsById) {
    //         if (in_array($optionId, $visited)) {
    //             return;
    //         }

    //         $visited[] = $optionId;

    //         // إضافة الخيار الحالي أولاً (الأب)
    //         $sortedOptions[] = $optionsById[$optionId];

    //         // ثم زيارة الأبناء
    //         foreach ($dependencyGraph[$optionId]['dependents'] as $dependentId) {
    //             $visit($dependentId);
    //         }
    //     };

    //     // البدء من الجذور
    //     foreach ($roots as $rootId) {
    //         $visit($rootId);
    //     }

    //     // إضافة أي خيارات لم تتم زيارتها (مستقلة)
    //     foreach ($options as $option) {
    //         if (!in_array($option['id'], $visited)) {
    //             $sortedOptions[] = $option;
    //         }
    //     }

    //     return $sortedOptions;
    // }

    /**
     * معالجة خيارات الكمية (مصححة للتعامل مع size_id)
     */
    // public function processQuantityOption($product, $option, &$results)
    // {
    //     $parentSizeId = null;
    //     $parentSizeName = null;

    //     // التحقق إذا كان هناك شرط ظهور مرتبط بمقاس معين
    //     if (isset($this->sizeConditionMap[$option['id']])) {
    //         $conditionInfo = $this->sizeConditionMap[$option['id']];
    //         $parentSizeName = $conditionInfo['size_detail_name'];

    //         // البحث عن المقاس في قاعدة البيانات
    //         $parentSize = Size::where('product_id', $product->id)
    //             ->where('name', $parentSizeName)
    //             ->first();

    //         if ($parentSize) {
    //             $parentSizeId = $parentSize->id;
    //         }
    //     }

    //     foreach ($option['details'] as $detail) {
    //         // استخراج الكمية والسعر من الاسم
    //         $quantityData = $this->extractQuantityAndPrice($detail['name']);

    //         if ($quantityData['quantity'] > 0) {
    //             // إذا كان هناك size_id، تخزين في ProductSizeTier
    //             if ($parentSizeId) {
    //                 $sizeTier = ProductSizeTier::updateOrCreate(
    //                     [
    //                         'product_id' => $product->id,
    //                         'size_id' => $parentSizeId,
    //                         'quantity' => $quantityData['quantity']
    //                     ],
    //                     [
    //                         'price_per_unit' => $quantityData['unit_price'],
    //                         'additional_price' => $detail['additional_price'] ?? 0,
    //                     ]
    //                 );
    //             } else {
    //                 // إذا لم يكن هناك size_id، خزن في ProductSizeTier مع size_id = null
    //                 $sizeTier = ProductSizeTier::updateOrCreate(
    //                     [
    //                         'product_id' => $product->id,
    //                         'quantity' => $quantityData['quantity']
    //                     ],
    //                     [
    //                         'price_per_unit' => $quantityData['unit_price'],
    //                         'additional_price' => $detail['additional_price'] ?? 0,
    //                         'size_id' => null // تعيين صريح لـ null
    //                     ]
    //                 );
    //             }

    //             // تخزين في PricingTiers أيضاً
    //             $pricingTier = PricingTiers::updateOrCreate(
    //                 [
    //                     'product_id' => $product->id,
    //                     'quantity' => $quantityData['quantity']
    //                 ],
    //                 [
    //                     'price_per_unit' => $quantityData['unit_price'],
    //                     'unit_price' => $quantityData['unit_price'],
    //                     'is_sample' => $quantityData['is_sample'] ?? false,
    //                     'discount_percentage' => $quantityData['discount_percentage'] ?? 0
    //                 ]
    //             );

    //             $results['quantity_tiers'][] = [
    //                 'quantity' => $quantityData['quantity'],
    //                 'unit_price' => $quantityData['unit_price'],
    //                 'size_name' => $parentSizeName ?? 'عام',
    //                 'additional_price' => $detail['additional_price'] ?? 0
    //             ];
    //         }
    //     }
    // }

    /**
     * طريقة محسنة لبناء التركيبات مع الحد من الانفجار التوافقي
     */
    public function buildOptimizedCombinations(array $options)
    {
        // ترتيب الخيارات بشكل هرمي
        $hierarchicalOptions = $this->buildHierarchicalOptions($options);

        // تصنيف الخيارات
        $requiredOptions = [];
        $optionalOptions = [];

        foreach ($hierarchicalOptions as $option) {
            if ($option['required'] ?? false) {
                $requiredOptions[] = $option;
            } else {
                $optionalOptions[] = $option;
            }
        }

        $results = [];

        // أولاً: بناء التركيبات الأساسية من الخيارات المطلوبة
        $baseCombinations = $this->buildCombinationsForOptions($requiredOptions);

        // إذا لم تكن هناك خيارات مطلوبة، ابدأ بمجموعة فارغة
        if (empty($baseCombinations)) {
            $baseCombinations = [[]];
        }

        // ثم إضافة الخيارات الاختيارية
        foreach ($baseCombinations as $baseCombination) {
            // إضافة الخيارات الاختيارية
            $this->addOptionalOptions($baseCombination, $optionalOptions, 0, $results);
        }

        // تحويل النتائج إلى الشكل المطلوب
        return $this->formatCombinations($results);
    }

    /**
     * بناء التركيبات لمجموعة من الخيارات
     */
    public function buildCombinationsForOptions(array $options)
    {
        if (empty($options)) {
            return [];
        }

        $results = [];

        $combine = function ($selected, $remainingOptions) use (&$combine, &$results) {
            if (empty($remainingOptions)) {
                $results[] = $selected;
                return;
            }

            $currentOption = array_shift($remainingOptions);
            $optionId = $currentOption['id'];

            // فحص شرط الظهور
            if (!empty($currentOption['visibility_condition'])) {
                $cond = $currentOption['visibility_condition'];
                $conditionOptionId = $cond['option'];
                $conditionValue = $cond['value'];

                if (
                    !isset($selected[$conditionOptionId]) ||
                    $selected[$conditionOptionId]['detail_id'] != $conditionValue
                ) {
                    // إذا لم يتحقق الشرط، تخطي هذا الخيار
                    $combine($selected, $remainingOptions);
                    return;
                }
            }

            // إضافة جميع تفاصيل الخيار
            foreach ($currentOption['details'] as $detail) {
                $newSelected = $selected;
                $newSelected[$optionId] = [
                    'option_id' => $optionId,
                    'option_name' => $currentOption['name'],
                    'value' => $detail['name'],
                    'detail_id' => $detail['id'],
                    'additional_price' => $detail['additional_price'] ?? 0,
                    'is_required' => $currentOption['required'] ?? false,
                ];

                $combine($newSelected, $remainingOptions);
            }
        };

        $combine([], $options);
        return $results;
    }

    /**
     * إضافة الخيارات الاختيارية للتركيبات
     */
    public function addOptionalOptions($combination, $optionalOptions, $index, &$results)
    {
        if ($index >= count($optionalOptions)) {
            $results[] = $combination;
            return;
        }

        $currentOption = $optionalOptions[$index];

        // الخيار 1: عدم إضافة هذا الخيار الاختياري
        $this->addOptionalOptions($combination, $optionalOptions, $index + 1, $results);

        // الخيار 2: إضافة هذا الخيار الاختياري (إذا كان الشرط متحققاً)
        if (!empty($currentOption['visibility_condition'])) {
            $cond = $currentOption['visibility_condition'];
            if (
                !isset($combination[$cond['option']]) ||
                $combination[$cond['option']]['detail_id'] != $cond['value']
            ) {
                // الشرط غير متحقق، لا تضيف هذا الخيار
                return;
            }
        }

        // إضافة جميع تفاصيل هذا الخيار
        foreach ($currentOption['details'] as $detail) {
            $newCombination = $combination;
            $newCombination[$currentOption['id']] = [
                'option_id' => $currentOption['id'],
                'option_name' => $currentOption['name'],
                'value' => $detail['name'],
                'detail_id' => $detail['id'],
                'additional_price' => $detail['additional_price'] ?? 0,
                'is_required' => false,
            ];

            $this->addOptionalOptions($newCombination, $optionalOptions, $index + 1, $results);
        }
    }

    /**
     * تنسيق التركيبات
     */
    public function formatCombinations(array $combinations)
    {
        return collect($combinations)
            ->filter(fn($c) => !empty($c))
            ->unique(fn($c) => md5(json_encode($c)))
            ->values()
            ->map(function ($combination) {
                $readable = [];
                $totalPrice = 0;

                foreach ($combination as $option) {
                    $readable[$option['option_name']] = $option['value'];
                    $totalPrice += $option['additional_price'];
                }

                return [
                    'combination' => $readable,
                    'details' => $combination,
                    'total_additional_price' => $totalPrice,
                    'option_count' => count($combination)
                ];
            });
    }

    /**
     * طريقة ذكية لبناء التركيبات مع الحد من العدد
     */
    public function buildSmartCombinations(array $options, $maxCombinations = 100)
    {
        // 1. ترتيب الخيارات حسب الأهمية
        $sortedOptions = $this->sortOptionsByPriority($options);

        // 2. تحديد الخيارات الأساسية (المقاس والكمية)
        $coreOptions = array_filter($sortedOptions, function ($option) {
            $name = $option['name'] ?? '';
            return str_contains($name, 'المقاس') ||
                str_contains($name, 'الحجم') ||
                str_contains($name, 'Size') ||
                str_contains($name, 'الكمية') ||
                str_contains($name, 'عدد') ||
                str_contains($name, 'Quantity');
        });

        // 3. تحديد الخيارات التكميلية
        $supplementalOptions = array_filter($sortedOptions, function ($option) use ($coreOptions) {
            return !in_array($option, $coreOptions, true);
        });

        $results = [];

        // 4. بناء التركيبات الأساسية أولاً
        $coreCombinations = $this->buildCombinationsForOptions(array_values($coreOptions));

        if (empty($coreCombinations)) {
            return collect([]);
        }

        // 5. لكل تركيب أساسي، إضافة خيارات تكميلية (بحد أقصى)
        foreach ($coreCombinations as $coreCombo) {
            if (count($results) >= $maxCombinations) break;

            // أضف التركيب الأساسي كونه تركيب صالح
            $results[] = $coreCombo;

            // أضف مع خيارات تكميلية
            $this->addSupplementalOptions($coreCombo, array_values($supplementalOptions), 0, $results, $maxCombinations);
        }

        return $this->formatCombinations(array_slice($results, 0, $maxCombinations));
    }

    /**
     * إضافة الخيارات التكميلية
     */
    public function addSupplementalOptions($combination, $supplementalOptions, $index, &$results, $maxCombinations)
    {
        if ($index >= count($supplementalOptions) || count($results) >= $maxCombinations) {
            return;
        }

        $currentOption = $supplementalOptions[$index];

        // تجاهل هذا الخيار
        $this->addSupplementalOptions($combination, $supplementalOptions, $index + 1, $results, $maxCombinations);

        // فحص شرط الظهور
        $canAdd = true;
        if (!empty($currentOption['visibility_condition'])) {
            $cond = $currentOption['visibility_condition'];
            if (
                !isset($combination[$cond['option']]) ||
                $combination[$cond['option']]['detail_id'] != $cond['value']
            ) {
                $canAdd = false;
            }
        }

        // إضافة هذا الخيار (إذا أمكن)
        if ($canAdd) {
            foreach ($currentOption['details'] as $detail) {
                if (count($results) >= $maxCombinations) break;

                $newCombo = $combination;
                $newCombo[$currentOption['id']] = [
                    'option_id' => $currentOption['id'],
                    'option_name' => $currentOption['name'],
                    'value' => $detail['name'],
                    'detail_id' => $detail['id'],
                    'additional_price' => $detail['additional_price'] ?? 0,
                    'is_required' => false,
                ];

                $results[] = $newCombo;

                // استمرار مع الخيارات التالية
                $this->addSupplementalOptions($newCombo, $supplementalOptions, $index + 1, $results, $maxCombinations);
            }
        }
    }
}
