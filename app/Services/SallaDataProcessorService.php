<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
    private $baseHeaders = [
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
    private $sizeConditionMap = [];

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

        $results = [
            'sizes' => [],
            'materials' => [],
            'design_services' => [],
            'printing_methods' => [],
            'print_locations' => [],
            'general_options' => [],
            'conditions' => [],
            'quantity_tiers' => []
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
     * تحليل العلاقات بين الخيارات
     */
    private function analyzeOptionRelationships($options)
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
     * معالجة خيارات المقاس
     */
    private function processSizeOption($product, $option, &$results)
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
    private function processQuantityOption($product, $option, &$results)
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
    private function processMaterialOption($product, $option, &$results)
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
    private function processDesignServiceOption($product, $option, &$results)
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
    private function processPrintingMethodOption($product, $option, &$results)
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
    private function processPrintLocationOption($product, $option, &$results)
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
    private function processEmbroiderLocationOption($product, $option, &$results)
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
    private function processGeneralOption($product, $option, &$results)
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
    private function extractDimension($sizeName)
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
    private function extractQuantityAndPrice($optionName)
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
    private function storeVisibilityConditions($productId, $options)
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
     * بناء جميع التركيبات الممكنة
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
}