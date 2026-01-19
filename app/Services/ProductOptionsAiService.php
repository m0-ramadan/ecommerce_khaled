<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductOptions;
use App\Models\DesignService;
use App\Models\PrintingMethod;
use App\Models\PrintLocation;
use App\Models\EmbroiderLocation;
use App\Models\Material;
use App\Models\Size;
use App\Models\Color;
use App\Models\DeliveryTime;
use App\Models\ProductSizeTier;

class ProductOptionsAiService
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

    private $deepseekApiKey;
    private $deepseekModel = 'deepseek-chat';
    private $deepseekBaseUrl = 'https://api.deepseek.com/v1/chat/completions';

    public function __construct()
    {
        $this->deepseekApiKey = env('DEEPSEEK_API_KEY', 'sk-97536bc2a134431aa194412221882ca2');
    }

    /**
     * Fetch products from Salla API
     */
    public function fetchProductsFromApi()
    {
        try {
            $response = Http::withHeaders($this->baseHeaders)
                ->get('https://api.salla.dev/store/v1/products');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Failed to fetch products from API', ['response' => $response->body()]);
            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching products from API', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Extract JSON from product page
     */
    public function extractOptionsFromHtml($url)
    {
        try {
            $response = Http::withHeaders($this->baseHeaders)->get($url);

            if (!$response->successful()) {
                Log::warning('Failed to fetch product page', ['url' => $url]);
                return null;
            }

            $html = $response->body();

            // Find salla-product-options and extract JSON
            preg_match('/<salla-product-options\s+options="([^"]+)"/', $html, $matches);

            if (!isset($matches[1])) {
                // Try another pattern
                preg_match('/options="([^"]+)"/', $html, $matches);
            }

            if (!isset($matches[1])) {
                Log::warning('Options not found in page', ['url' => $url]);
                return null;
            }

            // Decode HTML entities and convert to JSON
            $jsonString = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
            $options = json_decode($jsonString, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('JSON conversion error', [
                    'url' => $url,
                    'error' => json_last_error_msg(),
                    'json_string' => substr($jsonString, 0, 200)
                ]);
                return null;
            }

            return $options;
        } catch (\Exception $e) {
            Log::error('Error extracting options', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Process options with AI categorization - UPDATED
     */
    public function processOptionsWithAi($productId, $options, $productName = null, $categoryName = null)
    {
        if (!$this->deepseekApiKey) {
            Log::error('DeepSeek API key not found');
            return $this->processOptionsWithoutAi($productId, $options);
        }

        try {
            // Prepare data for AI processing
            $optionsData = $this->prepareOptionsDataForAi($options, $productName, $categoryName);

            // Send to DeepSeek AI for categorization
            $aiResponse = $this->sendToDeepSeekAi($optionsData);

            if ($aiResponse && isset($aiResponse['categorized_options'])) {
                // Process AI categorized options
                return $this->processAiCategorizedOptions($productId, $aiResponse['categorized_options'], $options);
            } else {
                // Fallback to manual processing
                return $this->processOptionsWithoutAi($productId, $options);
            }

        } catch (\Exception $e) {
            Log::error('AI processing failed', [
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
            return $this->processOptionsWithoutAi($productId, $options);
        }
    }

    /**
     * Prepare options data for AI processing
     */
    private function prepareOptionsDataForAi($options, $productName = null, $categoryName = null)
    {
        $optionsData = [
            'product_name' => $productName ?? 'Unknown Product',
            'category_name' => $categoryName ?? 'General',
            'options_count' => count($options),
            'options' => []
        ];

        foreach ($options as $option) {
            $optionData = [
                'id' => $option['id'] ?? null,
                'name' => $option['name'] ?? '',
                'type' => $option['type'] ?? 'single-option',
                'required' => $option['required'] ?? false,
                'visibility_condition' => $option['visibility_condition'] ?? null,
                'details_count' => count($option['details'] ?? []),
                'details' => []
            ];

            // Include all details for better analysis
            foreach ($option['details'] as $detail) {
                $detailData = [
                    'id' => $detail['id'] ?? null,
                    'name' => $detail['name'] ?? '',
                    'additional_price' => $detail['additional_price'] ?? 0,
                    'image' => $detail['image'] ?? null,
                    'hex_code' => $detail['hex_code'] ?? null
                ];
                $optionData['details'][] = $detailData;
            }

            $optionsData['options'][] = $optionData;
        }

        return $optionsData;
    }

    /**
     * Send data to DeepSeek AI for categorization - UPDATED PROMPT
     */
    private function sendToDeepSeekAi($optionsData)
    {
        try {
            $prompt = $this->createAiPrompt($optionsData);

            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                'Content-Type' => 'application/json',
            ])->post($this->deepseekBaseUrl, [
                'model' => $this->deepseekModel,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert e-commerce data analyst specializing in product options categorization for printing and custom products.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 3000,
                'response_format' => ['type' => 'json_object']
            ]);

            if (!$response->successful()) {
                throw new \Exception('AI API request failed: ' . $response->status());
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if ($content) {
                // Clean JSON response
                $content = str_replace(['\n', '\r', '\t'], '', $content);
                $content = preg_replace('/^json\s*/', '', $content);
                $content = trim($content, '`');
                
                $aiResponse = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    Log::info('AI Response received', ['response' => $aiResponse]);
                    return $aiResponse;
                } else {
                    Log::error('JSON decode error in AI response', [
                        'error' => json_last_error_msg(),
                        'content' => substr($content, 0, 500)
                    ]);
                }
            }

            return null;

        } catch (\Exception $e) {
            Log::error('DeepSeek AI request failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Create AI prompt for categorization - UPDATED
     */
    private function createAiPrompt($optionsData)
    {
        $productName = $optionsData['product_name'];
        $categoryName = $optionsData['category_name'];
        $options = json_encode($optionsData['options'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        $prompt = "📦 تحليل خيارات منتج للتخزين في قاعدة بيانات\n\n";
        $prompt .= "🔍 المنتج: {$productName}\n";
        $prompt .= "📂 الفئة: {$categoryName}\n";
        $prompt .= "📊 عدد الخيارات: {$optionsData['options_count']}\n\n";
        
        $prompt .= "🔧 **تفاصيل الخيارات:**\n";
        $prompt .= "```json\n{$options}\n```\n\n";
        
        $prompt .= "🎯 **مطلوب التصنيف إلى الفئات التالية:**\n";
        $prompt .= "1. **design_service** - خدمات التصميم (يحتوي على: تصميم، خدمة تصميم، جرافيك، تصميم جرافيك، تصميم شعار)\n";
        $prompt .= "2. **printing_method** - طرق الطباعة (يحتوي على: طريقة الطباعة، طباعة، ديجيتال، طباعة أوفست، سلك سكرين)\n";
        $prompt .= "3. **print_location** - مواقع الطباعة (يحتوي على: مكان الطباعة، موقع الطباعة، وجه الطباعة، عدد الأوجه)\n";
        $prompt .= "4. **embroider_location** - مواقع التطريز (يحتوي على: تطريز، مكان التطريز، موقع التطريز)\n";
        $prompt .= "5. **material** - المواد/الخامات (يحتوي على: خامة، مادة، نوع الخامة، نوع القماش، الورق)\n";
        $prompt .= "6. **size** - الأحجام/المقاسات (يحتوي على: مقاس، حجم، الحجم، الأبعاد، الطول، العرض، القياس)\n";
        $prompt .= "7. **color** - الألوان (يحتوي على: لون، اللون، اختيار اللون، الألوان المتاحة، كود اللون)\n";
        $prompt .= "8. **delivery_time** - وقت التوصيل (يحتوي على: وقت التوصيل، مدة التصنيع، وقت الإنتاج)\n";
        $prompt .= "9. **quantity** - الكمية/العدد (يحتوي على: كمية، عدد، القطعة، العدد المطلوب)\n";
        $prompt .= "10. **general** - خيارات عامة (كل ما لا ينتمي للفئات السابقة)\n\n";
        
        $prompt .= "📝 **ملاحظات مهمة للتعرف:**\n";
        $prompt .= "- **الأحجام/المقاسات**: قد تأتي بأسماء مثل 'المقاس'، 'الحجم'، 'الأبعاد'، 'القياس'\n";
        $prompt .= "- **الألوان**: قد تحتوي تفاصيلها على hex_code أو image\n";
        $prompt .= "- **الكميات**: قد تكون مرتبطة بالأحجام (مثل: كميات لكل مقاس)\n";
        $prompt .= "- **وقت التوصيل**: قد يكون بالأيام أو الأسابيع\n\n";
        
        $prompt .= "🎨 **الهيكل المطلوب للرد (JSON فقط):**\n";
        $prompt .= "{\n";
        $prompt .= "  \"categorized_options\": [\n";
        $prompt .= "    {\n";
        $prompt .= "      \"option_id\": \"original_option_id\",\n";
        $prompt .= "      \"option_name\": \"original_option_name\",\n";
        $prompt .= "      \"category\": \"one_of_the_categories_above\",\n";
        $prompt .= "      \"confidence\": \"high/medium/low\",\n";
        $prompt .= "      \"details_analysis\": [\n";
        $prompt .= "        {\n";
        $prompt .= "          \"detail_id\": \"detail_id\",\n";
        $prompt .= "          \"detail_name\": \"detail_name\",\n";
        $prompt .= "          \"is_size_quantity_combination\": true/false,\n";
        $prompt .= "          \"size_name\": \"if_applicable\",\n";
        $prompt .= "          \"quantity\": \"if_applicable\",\n";
        $prompt .= "          \"price_per_unit\": \"if_applicable\"\n";
        $prompt .= "        }\n";
        $prompt .= "      ],\n";
        $prompt .= "      \"processing_notes\": \"notes_for_processing\"\n";
        $prompt .= "    }\n";
        $prompt .= "  ]\n";
        $prompt .= "}\n\n";
        
        $prompt .= "⚠️ **تأكد من:**\n";
        $prompt .= "- تحليل كل خيار بدقة\n";
        $prompt .= "- التعرف على العلاقات بين الأحجام والكميات\n";
        $prompt .= "- التعرف على الألوان من خلال hex_code أو الصور\n";
        $prompt .= "- إذا كان هناك شك، استخدم 'general'\n";
        
        return $prompt;
    }

    /**
     * Process AI categorized options - UPDATED
     */
    private function processAiCategorizedOptions($productId, $categorizedOptions, $originalOptions)
    {
        $results = [
            'processed_options' => [],
            'errors' => [],
            'summary' => [
                'design_service' => 0,
                'printing_method' => 0,
                'print_location' => 0,
                'embroider_location' => 0,
                'material' => 0,
                'size' => 0,
                'color' => 0,
                'delivery_time' => 0,
                'quantity' => 0,
                'general' => 0,
                'size_tiers' => 0
            ]
        ];

        // Create mapping from option_id to original option
        $originalOptionsMap = [];
        foreach ($originalOptions as $option) {
            $originalOptionsMap[$option['id']] = $option;
        }

        // First pass: Process non-size options
        foreach ($categorizedOptions as $categorized) {
            $optionId = $categorized['option_id'];
            $category = $categorized['category'];

            if (!isset($originalOptionsMap[$optionId])) {
                $results['errors'][] = "Option ID {$optionId} not found in original options";
                continue;
            }

            $originalOption = $originalOptionsMap[$optionId];

            try {
                switch ($category) {
                    case 'design_service':
                        $this->processDesignService($productId, $originalOption);
                        $results['summary']['design_service']++;
                        break;

                    case 'printing_method':
                        $this->processPrintingMethod($productId, $originalOption);
                        $results['summary']['printing_method']++;
                        break;

                    case 'print_location':
                        $this->processPrintLocation($productId, $originalOption);
                        $results['summary']['print_location']++;
                        break;

                    case 'embroider_location':
                        $this->processEmbroiderLocation($productId, $originalOption);
                        $results['summary']['embroider_location']++;
                        break;

                    case 'material':
                        $this->processMaterial($productId, $originalOption);
                        $results['summary']['material']++;
                        break;

                    case 'color':
                        $this->processColor($productId, $originalOption);
                        $results['summary']['color']++;
                        break;

                    case 'delivery_time':
                        $this->processDeliveryTime($productId, $originalOption);
                        $results['summary']['delivery_time']++;
                        break;

                    case 'quantity':
                        $this->processQuantity($productId, $originalOption);
                        $results['summary']['quantity']++;
                        break;

                    case 'size':
                        // Process size in second pass
                        break;

                    case 'general':
                    default:
                        $this->processGeneralOption($productId, $originalOption);
                        $results['summary']['general']++;
                        break;
                }

                // Only add to processed if not size (sizes will be processed separately)
                if ($category !== 'size') {
                    $results['processed_options'][] = [
                        'option_id' => $optionId,
                        'option_name' => $originalOption['name'],
                        'category' => $category,
                        'details_count' => count($originalOption['details'] ?? [])
                    ];
                }

            } catch (\Exception $e) {
                $results['errors'][] = "Failed to process option {$optionId}: " . $e->getMessage();
            }
        }

        // Second pass: Process sizes and create tiers
        foreach ($categorizedOptions as $categorized) {
            if ($categorized['category'] === 'size') {
                $optionId = $categorized['option_id'];
                
                if (isset($originalOptionsMap[$optionId])) {
                    $originalOption = $originalOptionsMap[$optionId];
                    
                    // Process size and create tiers if analysis available
                    $sizeTiersCreated = $this->processSizeWithTiers(
                        $productId, 
                        $originalOption, 
                        $categorized['details_analysis'] ?? []
                    );
                    
                    $results['summary']['size']++;
                    $results['summary']['size_tiers'] += $sizeTiersCreated;
                    
                    $results['processed_options'][] = [
                        'option_id' => $optionId,
                        'option_name' => $originalOption['name'],
                        'category' => 'size',
                        'details_count' => count($originalOption['details'] ?? []),
                        'tiers_created' => $sizeTiersCreated
                    ];
                }
            }
        }

        // Store visibility conditions
        $this->storeVisibilityConditions($productId, $originalOptions);

        return $results;
    }

    /**
     * Process size with tiers - NEW METHOD
     */
    private function processSizeWithTiers($productId, $option, $detailsAnalysis = [])
    {
        $tiersCreated = 0;
        $sizeRecords = [];

        // First, create size records for each detail
        foreach ($option['details'] as $detail) {
            $sizeName = $detail['name'];
            $additionalPrice = $detail['additional_price'] ?? 0;
            
            // Try to extract size name from analysis if available
            foreach ($detailsAnalysis as $analysis) {
                if ($analysis['detail_id'] == $detail['id'] && !empty($analysis['size_name'])) {
                    $sizeName = $analysis['size_name'];
                    break;
                }
            }

            // Create or get size record
            $size = Size::updateOrCreate(
                [
                    'product_id' => $productId,
                    'name' => $sizeName
                ],
                [
                    'product_id' => $productId,
                    'name' => $sizeName
                ]
            );

            $sizeRecords[$detail['id']] = $size;

            // Check if this detail contains quantity information
            foreach ($detailsAnalysis as $analysis) {
                if ($analysis['detail_id'] == $detail['id'] && 
                    $analysis['is_size_quantity_combination'] && 
                    !empty($analysis['quantity'])) {
                    
                    // Create size tier
                    ProductSizeTier::updateOrCreate(
                        [
                            'product_id' => $productId,
                            'size_id' => $size->id,
                            'quantity' => $analysis['quantity']
                        ],
                        [
                            'price_per_unit' => $analysis['price_per_unit'] ?? $additionalPrice
                        ]
                    );
                    
                    $tiersCreated++;
                }
            }
        }

        // If no tiers were created from analysis, check if there's a separate quantity option
        if ($tiersCreated === 0) {
            // Look for quantity options in the product to associate with sizes
            $this->linkSizesWithQuantities($productId, $sizeRecords);
        }

        return $tiersCreated;
    }

    /**
     * Link sizes with quantities - NEW METHOD
     */
    private function linkSizesWithQuantities($productId, $sizeRecords)
    {
        // This method would look for quantity options and link them with sizes
        // Implementation depends on how your data is structured
        
        // Example: If you have a separate quantity option, you could:
        // 1. Find quantity options for this product
        // 2. Create tiers for each size with each quantity
        
        // For now, we'll create default tiers
        $defaultQuantities = [10, 50, 100, 500];
        
        foreach ($sizeRecords as $size) {
            foreach ($defaultQuantities as $quantity) {
                ProductSizeTier::firstOrCreate(
                    [
                        'product_id' => $productId,
                        'size_id' => $size->id,
                        'quantity' => $quantity
                    ],
                    [
                        'price_per_unit' => 0 // Default price
                    ]
                );
            }
        }
        
        return count($sizeRecords) * count($defaultQuantities);
    }

    /**
     * Process color - NEW METHOD
     */
    private function processColor($productId, $option)
    {
        $product = Product::find($productId);
        
        foreach ($option['details'] as $detail) {
            $colorData = [
                'name' => $detail['name']
            ];

            // Add hex code if available
            if (!empty($detail['hex_code'])) {
                $colorData['hex_code'] = $detail['hex_code'];
            }

            // Add image if available
            if (!empty($detail['image'])) {
                $colorData['image'] = $detail['image'];
            }

            // Add additional price
            if (!empty($detail['additional_price'])) {
                $colorData['additional_price'] = $detail['additional_price'];
            }

            // Create or update color
            $color = Color::updateOrCreate(
                ['name' => $detail['name']],
                $colorData
            );

            // Attach to product
            if ($product && !$product->colors()->where('color_id', $color->id)->exists()) {
                $product->colors()->attach($color->id);
            }
        }
    }

    /**
     * Process delivery time - NEW METHOD
     */
    private function processDeliveryTime($productId, $option)
    {
        // Delete existing delivery time for this product
        DeliveryTime::where('product_id', $productId)->delete();

        // Process each detail as a delivery time option
        foreach ($option['details'] as $detail) {
            $timeString = $detail['name'];
            
            // Try to extract days from string (e.g., "3-5 أيام", "أسبوع", "24 ساعة")
            preg_match('/(\d+)\s*-\s*(\d+)/', $timeString, $rangeMatches);
            preg_match('/(\d+)/', $timeString, $singleMatches);

            $fromDays = 1;
            $toDays = 3;

            if (!empty($rangeMatches)) {
                $fromDays = (int)$rangeMatches[1];
                $toDays = (int)$rangeMatches[2];
            } elseif (!empty($singleMatches)) {
                $fromDays = (int)$singleMatches[1];
                $toDays = $fromDays + 2;
            }

            // Check for weeks
            if (str_contains($timeString, 'أسبوع') || str_contains($timeString, 'اسبوع')) {
                $fromDays = $fromDays * 7;
                $toDays = $toDays * 7;
            }

            DeliveryTime::create([
                'product_id' => $productId,
                'from_days' => $fromDays,
                'to_days' => $toDays
            ]);
        }
    }

    /**
     * Process options without AI (fallback) - UPDATED
     */
    public function processOptionsWithoutAi($productId, $options)
    {
        $results = [
            'processed_options' => [],
            'errors' => [],
            'summary' => [
                'design_service' => 0,
                'printing_method' => 0,
                'print_location' => 0,
                'embroider_location' => 0,
                'material' => 0,
                'size' => 0,
                'color' => 0,
                'delivery_time' => 0,
                'quantity' => 0,
                'general' => 0,
                'size_tiers' => 0
            ]
        ];

        // Separate sizes for special processing
        $sizeOptions = [];
        $otherOptions = [];

        foreach ($options as $option) {
            $name = strtolower($option['name'] ?? '');
            
            if (str_contains($name, 'مقاس') || str_contains($name, 'حجم') || 
                str_contains($name, 'قياس') || str_contains($name, 'بعد')) {
                $sizeOptions[] = $option;
            } else {
                $otherOptions[] = $option;
            }
        }

        // Process non-size options
        foreach ($otherOptions as $option) {
            try {
                $name = strtolower($option['name'] ?? '');
                $processed = false;

                // Manual categorization based on keywords - UPDATED
                if (str_contains($name, 'خدمة التصميم') || str_contains($name, 'تصميم')) {
                    $this->processDesignService($productId, $option);
                    $results['summary']['design_service']++;
                    $processed = true;
                } elseif (str_contains($name, 'طريقة الطباعة') || str_contains($name, 'طباعة')) {
                    $this->processPrintingMethod($productId, $option);
                    $results['summary']['printing_method']++;
                    $processed = true;
                } elseif (str_contains($name, 'مكان الطباعة') || str_contains($name, 'موقع الطباعة') || 
                         str_contains($name, 'وجه') || str_contains($name, 'أوجه')) {
                    $this->processPrintLocation($productId, $option);
                    $results['summary']['print_location']++;
                    $processed = true;
                } elseif (str_contains($name, 'تطريز') || str_contains($name, 'مكان التطريز')) {
                    $this->processEmbroiderLocation($productId, $option);
                    $results['summary']['embroider_location']++;
                    $processed = true;
                } elseif (str_contains($name, 'خامة') || str_contains($name, 'مادة') || 
                         str_contains($name, 'قماش') || str_contains($name, 'ورق')) {
                    $this->processMaterial($productId, $option);
                    $results['summary']['material']++;
                    $processed = true;
                } elseif (str_contains($name, 'لون') || str_contains($name, 'اللون') || 
                         str_contains($name, 'ألوان')) {
                    $this->processColor($productId, $option);
                    $results['summary']['color']++;
                    $processed = true;
                } elseif (str_contains($name, 'وقت التوصيل') || str_contains($name, 'مدة التصنيع') || 
                         str_contains($name, 'وقت الإنتاج')) {
                    $this->processDeliveryTime($productId, $option);
                    $results['summary']['delivery_time']++;
                    $processed = true;
                } elseif (str_contains($name, 'كمية') || str_contains($name, 'عدد') || 
                         str_contains($name, 'قطعة')) {
                    $this->processQuantity($productId, $option);
                    $results['summary']['quantity']++;
                    $processed = true;
                }

                if (!$processed) {
                    $this->processGeneralOption($productId, $option);
                    $results['summary']['general']++;
                }

                $results['processed_options'][] = [
                    'option_id' => $option['id'],
                    'option_name' => $option['name'],
                    'category' => $processed ? 'manual' : 'general',
                    'details_count' => count($option['details'] ?? [])
                ];

            } catch (\Exception $e) {
                $results['errors'][] = "Failed to process option {$option['id']}: " . $e->getMessage();
            }
        }

        // Process size options
        foreach ($sizeOptions as $option) {
            try {
                $tiersCreated = $this->processSizeWithTiers($productId, $option);
                $results['summary']['size']++;
                $results['summary']['size_tiers'] += $tiersCreated;
                
                $results['processed_options'][] = [
                    'option_id' => $option['id'],
                    'option_name' => $option['name'],
                    'category' => 'size',
                    'details_count' => count($option['details'] ?? []),
                    'tiers_created' => $tiersCreated
                ];
            } catch (\Exception $e) {
                $results['errors'][] = "Failed to process size option {$option['id']}: " . $e->getMessage();
            }
        }

        // Store visibility conditions
        $this->storeVisibilityConditions($productId, $options);

        return $results;
    }

    /**
     * Process design service
     */
    private function processDesignService($productId, $option)
    {
        foreach ($option['details'] as $detail) {
            DesignService::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'description' => $detail['name'],
                    'price' => $detail['additional_price'] ?? 0
                ]
            );
        }
    }

    /**
     * Process printing method
     */
    private function processPrintingMethod($productId, $option)
    {
        $product = Product::find($productId);
        
        foreach ($option['details'] as $detail) {
            $printingMethod = PrintingMethod::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'description' => $detail['name'],
                    'base_price' => $detail['additional_price'] ?? 0
                ]
            );

            // Attach to product
            if ($product && !$product->printingMethods()->where('printing_method_id', $printingMethod->id)->exists()) {
                $product->printingMethods()->attach($printingMethod->id);
            }
        }
    }

    /**
     * Process print location
     */
    private function processPrintLocation($productId, $option)
    {
        $product = Product::find($productId);
        
        foreach ($option['details'] as $detail) {
            $printLocation = PrintLocation::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'type' => $option['name'],
                    'additional_price' => $detail['additional_price'] ?? 0
                ]
            );

            // Attach to product
            if ($product) {
                $product->printLocations()->syncWithoutDetaching([
                    $printLocation->id => ['additional_price' => $detail['additional_price'] ?? 0]
                ]);
            }
        }
    }

    /**
     * Process embroider location
     */
    private function processEmbroiderLocation($productId, $option)
    {
        foreach ($option['details'] as $detail) {
            EmbroiderLocation::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'additional_price' => $detail['additional_price'] ?? 0
                ]
            );
        }
    }

    /**
     * Process material
     */
    private function processMaterial($productId, $option)
    {
        foreach ($option['details'] as $detail) {
            $material = Material::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'description' => $detail['name']
                ]
            );

            // Link material to product if needed
            $product = Product::find($productId);
            if ($product) {
                $product->materials()->syncWithoutDetaching([$material->id]);
            }
        }
    }

    /**
     * Process quantity
     */
    private function processQuantity($productId, $option)
    {
        // Quantity can be stored in general options or processed specially
        // For now, store in general options
        $this->processGeneralOption($productId, $option);
    }

    /**
     * Process general option
     */
    private function processGeneralOption($productId, $option)
    {
        foreach ($option['details'] as $detail) {
            ProductOptions::updateOrCreate(
                [
                    'product_id' => $productId,
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
        }
    }

    /**
     * Store visibility conditions
     */
    private function storeVisibilityConditions($productId, $options)
    {
        $conditions = [];

        foreach ($options as $option) {
            if (isset($option['visibility_condition']) && $option['visibility_condition']) {
                $conditions[] = [
                    'option_id' => $option['id'],
                    'option_name' => $option['name'],
                    'condition' => $option['visibility_condition']
                ];
            }
        }

        if (!empty($conditions)) {
            $product = Product::find($productId);
            if ($product) {
                $product->options_conditions = json_encode($conditions, JSON_UNESCAPED_UNICODE);
                $product->save();
            }
        }
    }

    /**
     * Build all possible combinations
     */
    public function buildAllCombinations($options)
    {
        $indexedOptions = collect($options)->keyBy('id');
        $combinations = $this->buildCombinationsRecursive($options, []);

        $structuredResults = [];
        foreach ($combinations as $combo) {
            $row = [];
            foreach ($combo as $optionId => $detailId) {
                if (isset($indexedOptions[$optionId])) {
                    $option = $indexedOptions[$optionId];
                    $detail = collect($option['details'])->firstWhere('id', $detailId);

                    if ($detail) {
                        $row[$option['name']] = [
                            'value' => $detail['name'],
                            'additional_price' => $detail['additional_price'] ?? 0
                        ];
                    }
                }
            }
            if (!empty($row)) {
                $structuredResults[] = $row;
            }
        }

        return $structuredResults;
    }

    private function buildCombinationsRecursive($options, $selected = [], $level = 0)
    {
        $results = [];

        foreach ($options as $option) {
            // Check visibility condition
            if (isset($option['visibility_condition']) && $option['visibility_condition']) {
                $cond = $option['visibility_condition'];
                if (!isset($selected[$cond['option']]) || $selected[$cond['option']] != $cond['value']) {
                    continue;
                }
            }

            foreach ($option['details'] as $detail) {
                $newSelected = $selected;
                $newSelected[$option['id']] = $detail['id'];

                $results[] = $newSelected;

                // Recursive call for remaining options
                $remainingOptions = array_filter($options, function($opt) use ($option) {
                    return $opt['id'] != $option['id'];
                });

                if (!empty($remainingOptions)) {
                    $results = array_merge(
                        $results,
                        $this->buildCombinationsRecursive($remainingOptions, $newSelected, $level + 1)
                    );
                }
            }
        }

        return array_unique($results, SORT_REGULAR);
    }
    // إضافة دالة لربط أوزان المنتجات مع الشحن
private function processShippingIntegration($productId, $options)
{
    $product = Product::find($productId);
    
    if (!$product) return;
    
    // البحث عن خيار الوزن
    foreach ($options as $option) {
        $name = strtolower($option['name'] ?? '');
        
        if (str_contains($name, 'وزن') || str_contains($name, 'weight')) {
            foreach ($option['details'] as $detail) {
                $weight = $this->extractWeightFromDetail($detail['name']);
                
                if ($weight > 0) {
                    $product->weight = $weight;
                    $product->save();
                    
                    Log::info('Updated product weight from options', [
                        'product_id' => $productId,
                        'weight' => $weight
                    ]);
                }
            }
        }
    }
}

private function extractWeightFromDetail($detailName)
{
    $patterns = [
        '/(\d+(\.\d+)?)\s*(كجم|kg|كيلو)/i',
        '/وزن\s*(\d+(\.\d+)?)/i',
        '/(\d+(\.\d+)?)\s*(جرام|g)/i'
    ];
    
    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $detailName, $matches)) {
            $value = floatval($matches[1]);
            
            // تحويل الجرام إلى كيلوجرام
            if (strpos(strtolower($detailName), 'جرام') !== false || 
                strpos(strtolower($detailName), 'g') !== false) {
                $value = $value / 1000;
            }
            
            return $value;
        }
    }
    
    return 0;
}
}