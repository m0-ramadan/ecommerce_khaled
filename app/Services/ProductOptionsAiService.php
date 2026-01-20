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
     * Fetch ALL products from Salla API with CURSOR pagination
     * Updated to handle cursor-based pagination
     */
    public function fetchProductsFromApi($allPages = true, $limit = 50)
    {
        try {
            $allProducts = [];
            $nextCursorUrl = "https://api.salla.dev/store/v1/products?limit={$limit}";
            $pageCount = 0;

            while ($nextCursorUrl && ($allPages || $pageCount < 1)) {
                $pageCount++;
                Log::info("Fetching products page {$pageCount} from: " . $nextCursorUrl);

                $response = Http::withHeaders($this->baseHeaders)
                    ->get($nextCursorUrl);

                if ($response->successful()) {
                    $data = $response->json();

                    // Check if data exists
                    if (isset($data['data']) && is_array($data['data'])) {
                        $allProducts = array_merge($allProducts, $data['data']);

                        Log::info("Fetched " . count($data['data']) . " products from page {$pageCount}");

                        // Check for next cursor URL
                        if ($allPages && isset($data['cursor']['next']) && !empty($data['cursor']['next'])) {
                            $nextCursorUrl = $data['cursor']['next'];

                            // Add delay between requests
                            sleep(1);
                        } else {
                            $nextCursorUrl = null;
                        }
                    } else {
                        $nextCursorUrl = null;
                        Log::warning("No data found in API response for page {$pageCount}");
                    }
                } else {
                    Log::error('Failed to fetch products from API', [
                        'page' => $pageCount,
                        'url' => $nextCursorUrl,
                        'status' => $response->status(),
                        'response' => $response->body()
                    ]);
                    $nextCursorUrl = null;
                }
            }

            Log::info("Total products fetched: " . count($allProducts) . " from {$pageCount} pages");

            return [
                'data' => $allProducts,
                'total' => count($allProducts),
                'pages_processed' => $pageCount,
                'has_more' => !empty($nextCursorUrl)
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching products from API with cursor pagination', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Fetch products from specific cursor or start from beginning
     */
    public function fetchProductsFromApiWithProgress($startCursorUrl = null, $maxPages = 0, $limit = 50)
    {
        try {
            $allProducts = [];
            $nextCursorUrl = $startCursorUrl ?? "https://api.salla.dev/store/v1/products?limit={$limit}";
            $pageCount = 0;
            $hasMorePages = true;

            while ($nextCursorUrl && $hasMorePages) {
                $pageCount++;
                Log::info("Fetching products page {$pageCount} from cursor URL");

                $response = Http::withHeaders($this->baseHeaders)
                    ->get($nextCursorUrl);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['data']) && is_array($data['data'])) {
                        $allProducts = array_merge($allProducts, $data['data']);

                        Log::info("Fetched " . count($data['data']) . " products from page {$pageCount}");

                        // Check for next cursor URL
                        if (isset($data['cursor']['next']) && !empty($data['cursor']['next'])) {
                            $nextCursorUrl = $data['cursor']['next'];

                            // Check if we reached max pages
                            if ($maxPages > 0 && $pageCount >= $maxPages) {
                                $hasMorePages = false;
                                Log::info("Reached maximum pages limit: {$maxPages}");
                            } else {
                                // Add delay between requests
                                sleep(1);
                            }
                        } else {
                            $nextCursorUrl = null;
                            $hasMorePages = false;
                            Log::info("No more pages available. Reached end of pagination.");
                        }
                    } else {
                        $nextCursorUrl = null;
                        $hasMorePages = false;
                        Log::warning("No data found in API response for page {$pageCount}");
                    }
                } else {
                    Log::error('Failed to fetch products page', [
                        'page' => $pageCount,
                        'url' => $nextCursorUrl,
                        'status' => $response->status()
                    ]);

                    // Retry logic for failed requests
                    if ($pageCount <= 3) {
                        Log::info("Retrying page {$pageCount} after 5 seconds...");
                        sleep(5);
                        continue;
                    } else {
                        $nextCursorUrl = null;
                        $hasMorePages = false;
                    }
                }
            }

            Log::info("Total products fetched: " . count($allProducts) . " from {$pageCount} pages");

            return [
                'data' => $allProducts,
                'total' => count($allProducts),
                'pages_processed' => $pageCount,
                'last_cursor_url' => $nextCursorUrl,
                'has_more' => !empty($nextCursorUrl)
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching products with cursor progress', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Extract cursor from URL for storage/resumption
     */
    public function extractCursorFromUrl($url)
    {
        if (empty($url)) {
            return null;
        }

        $parsedUrl = parse_url($url);
        $queryParams = [];

        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
        }

        return [
            'url' => $url,
            'cursor_param' => $queryParams['cursor'] ?? null,
            'page_param' => $queryParams['page'] ?? null,
            'limit_param' => $queryParams['limit'] ?? null
        ];
    }

    /**
     * Save progress to resume later
     */
    public function saveFetchProgress($cursorUrl, $totalFetched, $pagesProcessed)
    {
        $progressData = [
            'cursor_url' => $cursorUrl,
            'total_fetched' => $totalFetched,
            'pages_processed' => $pagesProcessed,
            'last_updated' => now()->toDateTimeString(),
            'extracted_cursor' => $this->extractCursorFromUrl($cursorUrl)
        ];

        // Save to file or database
        $filePath = storage_path('app/fetch_progress.json');
        file_put_contents($filePath, json_encode($progressData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        Log::info("Fetch progress saved", $progressData);

        return $progressData;
    }

    /**
     * Load saved progress
     */
    public function loadFetchProgress()
    {
        $filePath = storage_path('app/fetch_progress.json');

        if (file_exists($filePath)) {
            $data = json_decode(file_get_contents($filePath), true);

            if ($data) {
                Log::info("Fetch progress loaded", $data);
                return $data;
            }
        }

        Log::info("No saved progress found, starting from beginning");
        return null;
    }

    /**
     * Batch process products with cursor pagination
     */
    public function batchProcessProducts($batchSize = 10, $resumeFromSaved = true)
    {
        try {
            $results = [
                'total_processed' => 0,
                'successful' => 0,
                'failed' => 0,
                'cursor_history' => [],
                'products' => []
            ];

            // Load saved progress if requested
            $startCursorUrl = null;
            if ($resumeFromSaved) {
                $progress = $this->loadFetchProgress();
                if ($progress && isset($progress['cursor_url'])) {
                    $startCursorUrl = $progress['cursor_url'];
                    Log::info("Resuming from saved cursor: " . $startCursorUrl);
                }
            }

            // Fetch products with cursor pagination
            $fetchResult = $this->fetchProductsFromApiWithProgress(
                $startCursorUrl,
                $batchSize,
                50 // limit per page
            );

            if (!$fetchResult || empty($fetchResult['data'])) {
                Log::warning("No products fetched from API");
                return $results;
            }

            $products = $fetchResult['data'];
            $results['total_processed'] = count($products);
            $results['last_cursor'] = $fetchResult['last_cursor_url'] ?? null;
            $results['has_more'] = $fetchResult['has_more'] ?? false;

            // Process each product
            foreach ($products as $productData) {
                try {
                    $productId = $productData['id'] ?? null;
                    $productUrl = $productData['url'] ?? null;

                    if (!$productId || !$productUrl) {
                        continue;
                    }

                    Log::info("Processing product {$productId}: " . ($productData['name'] ?? 'Unknown'));

                    // Extract options from product page
                    $options = $this->extractOptionsFromHtml($productUrl);

                    if ($options) {
                        // Process options with AI
                        $processResult = $this->processOptionsWithAi(
                            $productId,
                            $options,
                            $productData['name'] ?? null,
                            $productData['category']['name'] ?? null
                        );

                        if ($processResult) {
                            $results['successful']++;

                            $results['products'][] = [
                                'id' => $productId,
                                'name' => $productData['name'] ?? 'Unknown',
                                'options_processed' => count($options),
                                'categories_summary' => $processResult['summary'] ?? []
                            ];
                        } else {
                            $results['failed']++;
                        }
                    } else {
                        Log::warning("No options found for product {$productId}");
                        $results['failed']++;
                    }
                } catch (\Exception $e) {
                    Log::error("Error processing product {$productId}", [
                        'error' => $e->getMessage(),
                        'product_id' => $productId
                    ]);
                    $results['failed']++;
                }
            }

            // Save progress for next batch
            if ($results['last_cursor'] && $results['has_more']) {
                $this->saveFetchProgress(
                    $results['last_cursor'],
                    $results['total_processed'],
                    $batchSize
                );

                Log::info("Batch completed. Next cursor saved: " . $results['last_cursor']);
            } else {
                Log::info("Batch completed. No more pages or cursor not available.");
            }

            return $results;
        } catch (\Exception $e) {
            Log::error('Error in batch processing', ['error' => $e->getMessage()]);
            return null;
        }
    }
    /**
     * Reset progress file
     */
    public function resetProgressFile()
    {
        $filePath = storage_path('app/fetch_progress.json');

        if (file_exists($filePath)) {
            unlink($filePath);
            Log::info("Progress file deleted");
        }

        return true;
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
     * Alternative: Fetch products with next URL pagination
     */
    public function fetchProductsWithNextUrl($allPages = true)
    {
        try {
            $allProducts = [];
            $nextUrl = 'https://api.salla.dev/store/v1/products?per_page=100';

            while ($nextUrl) {
                Log::info("Fetching products from: " . $nextUrl);

                $response = Http::withHeaders($this->baseHeaders)
                    ->get($nextUrl);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['data']) && is_array($data['data'])) {
                        $allProducts = array_merge($allProducts, $data['data']);

                        Log::info("Fetched " . count($data['data']) . " products");

                        // التحقق من وجود رابط للصفحة التالية
                        if ($allPages && isset($data['pagination']['next'])) {
                            $nextUrl = $data['pagination']['next'];

                            // تأجيل بين الطلبات
                            sleep(1);
                        } else {
                            $nextUrl = null;
                        }
                    } else {
                        $nextUrl = null;
                    }
                } else {
                    Log::error('Failed to fetch products page', [
                        'url' => $nextUrl,
                        'status' => $response->status()
                    ]);
                    $nextUrl = null;
                }
            }

            Log::info("Total products fetched with next URL: " . count($allProducts));

            return [
                'data' => $allProducts,
                'total' => count($allProducts)
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching products with next URL', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Process options with AI categorization
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
     * Send data to DeepSeek AI for categorization
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
     * Create AI prompt for categorization
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
        $prompt .= "1. **design_service** - خدمات التصميم\n";
        $prompt .= "2. **printing_method** - طرق الطباعة\n";
        $prompt .= "3. **print_location** - مواقع الطباعة\n";
        $prompt .= "4. **embroider_location** - مواقع التطريز\n";
        $prompt .= "5. **material** - المواد/الخامات\n";
        $prompt .= "6. **size** - الأحجام/المقاسات\n";
        $prompt .= "7. **color** - الألوان\n";
        $prompt .= "8. **delivery_time** - وقت التوصيل\n";
        $prompt .= "9. **quantity** - الكمية/العدد\n";
        $prompt .= "10. **general** - خيارات عامة\n\n";

        $prompt .= "🎨 **الهيكل المطلوب للرد (JSON فقط):**\n";
        $prompt .= "{\n";
        $prompt .= "  \"categorized_options\": [\n";
        $prompt .= "    {\n";
        $prompt .= "      \"option_id\": \"original_option_id\",\n";
        $prompt .= "      \"option_name\": \"original_option_name\",\n";
        $prompt .= "      \"category\": \"one_of_the_categories_above\"\n";
        $prompt .= "    }\n";
        $prompt .= "  ]\n";
        $prompt .= "}\n";

        return $prompt;
    }

    /**
     * Process AI categorized options
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

        // Process all options
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

                    case 'size':
                        $tiersCreated = $this->processSizeWithTiers($productId, $originalOption);
                        $results['summary']['size']++;
                        $results['summary']['size_tiers'] += $tiersCreated;
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

                    case 'general':
                    default:
                        $this->processGeneralOption($productId, $originalOption);
                        $results['summary']['general']++;
                        break;
                }

                $results['processed_options'][] = [
                    'option_id' => $optionId,
                    'option_name' => $originalOption['name'],
                    'category' => $category,
                    'details_count' => count($originalOption['details'] ?? [])
                ];
            } catch (\Exception $e) {
                $results['errors'][] = "Failed to process option {$optionId}: " . $e->getMessage();
            }
        }

        // Store visibility conditions
        $this->storeVisibilityConditions($productId, $originalOptions);

        return $results;
    }

    /**
     * Process size with tiers
     */
    private function processSizeWithTiers($productId, $option, $detailsAnalysis = [])
    {
        $tiersCreated = 0;

        foreach ($option['details'] as $detail) {
            $sizeName = $detail['name'];
            $additionalPrice = $detail['additional_price'] ?? 0;

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

            // Create default tiers
            $defaultQuantities = [10, 50, 100, 500];

            foreach ($defaultQuantities as $quantity) {
                ProductSizeTier::firstOrCreate(
                    [
                        'product_id' => $productId,
                        'size_id' => $size->id,
                        'quantity' => $quantity
                    ],
                    [
                        'price_per_unit' => $additionalPrice / max($quantity, 1)
                    ]
                );

                $tiersCreated++;
            }
        }

        return $tiersCreated;
    }

    /**
     * Process color
     */
    private function processColor($productId, $option)
    {
        $product = Product::find($productId);

        foreach ($option['details'] as $detail) {
            $colorData = [
                'name' => $detail['name']
            ];

            if (!empty($detail['hex_code'])) {
                $colorData['hex_code'] = $detail['hex_code'];
            }

            if (!empty($detail['image'])) {
                $colorData['image'] = $detail['image'];
            }

            if (!empty($detail['additional_price'])) {
                $colorData['additional_price'] = $detail['additional_price'];
            }

            $color = Color::updateOrCreate(
                ['name' => $detail['name']],
                $colorData
            );

            if ($product && !$product->colors()->where('color_id', $color->id)->exists()) {
                $product->colors()->attach($color->id);
            }
        }
    }

    /**
     * Process delivery time
     */
    private function processDeliveryTime($productId, $option)
    {
        DeliveryTime::where('product_id', $productId)->delete();

        foreach ($option['details'] as $detail) {
            $timeString = $detail['name'];

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
     * Process options without AI (fallback)
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

        foreach ($options as $option) {
            try {
                $name = strtolower($option['name'] ?? '');
                $processed = false;

                if (str_contains($name, 'خدمة التصميم') || str_contains($name, 'تصميم')) {
                    $this->processDesignService($productId, $option);
                    $results['summary']['design_service']++;
                    $processed = true;
                } elseif (str_contains($name, 'طريقة الطباعة') || str_contains($name, 'طباعة')) {
                    $this->processPrintingMethod($productId, $option);
                    $results['summary']['printing_method']++;
                    $processed = true;
                } elseif (
                    str_contains($name, 'مكان الطباعة') || str_contains($name, 'موقع الطباعة') ||
                    str_contains($name, 'وجه') || str_contains($name, 'أوجه')
                ) {
                    $this->processPrintLocation($productId, $option);
                    $results['summary']['print_location']++;
                    $processed = true;
                } elseif (str_contains($name, 'تطريز') || str_contains($name, 'مكان التطريز')) {
                    $this->processEmbroiderLocation($productId, $option);
                    $results['summary']['embroider_location']++;
                    $processed = true;
                } elseif (
                    str_contains($name, 'خامة') || str_contains($name, 'مادة') ||
                    str_contains($name, 'قماش') || str_contains($name, 'ورق')
                ) {
                    $this->processMaterial($productId, $option);
                    $results['summary']['material']++;
                    $processed = true;
                } elseif (
                    str_contains($name, 'مقاس') || str_contains($name, 'حجم') ||
                    str_contains($name, 'قياس') || str_contains($name, 'بعد')
                ) {
                    $tiersCreated = $this->processSizeWithTiers($productId, $option);
                    $results['summary']['size']++;
                    $results['summary']['size_tiers'] += $tiersCreated;
                    $processed = true;
                } elseif (
                    str_contains($name, 'لون') || str_contains($name, 'اللون') ||
                    str_contains($name, 'ألوان')
                ) {
                    $this->processColor($productId, $option);
                    $results['summary']['color']++;
                    $processed = true;
                } elseif (
                    str_contains($name, 'وقت التوصيل') || str_contains($name, 'مدة التصنيع') ||
                    str_contains($name, 'وقت الإنتاج')
                ) {
                    $this->processDeliveryTime($productId, $option);
                    $results['summary']['delivery_time']++;
                    $processed = true;
                } elseif (
                    str_contains($name, 'كمية') || str_contains($name, 'عدد') ||
                    str_contains($name, 'قطعة')
                ) {
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

                $remainingOptions = array_filter($options, function ($opt) use ($option) {
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

    private function processShippingIntegration($productId, $options)
    {
        $product = Product::find($productId);

        if (!$product) return;

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

    /**
     * Get headers for API requests
     */
    public function getHeaders()
    {
        return $this->baseHeaders;
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

                if (
                    strpos(strtolower($detailName), 'جرام') !== false ||
                    strpos(strtolower($detailName), 'g') !== false
                ) {
                    $value = $value / 1000;
                }

                return $value;
            }
        }

        return 0;
    }
}
