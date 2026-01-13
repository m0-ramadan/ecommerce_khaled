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
                'details_samples' => []
            ];

            // Take only first 3 details as samples
            $details = array_slice($option['details'] ?? [], 0, 3);
            foreach ($details as $detail) {
                $optionData['details_samples'][] = [
                    'name' => $detail['name'] ?? '',
                    'additional_price' => $detail['additional_price'] ?? 0
                ];
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
                        'content' => 'You are an expert e-commerce data analyst specializing in product options categorization.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.3,
                'max_tokens' => 2000,
                'response_format' => ['type' => 'json_object']
            ]);

            if (!$response->successful()) {
                throw new \Exception('AI API request failed: ' . $response->status());
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? null;

            if ($content) {
                // Clean JSON response
                $content = str_replace(['```json', '```'], '', $content);
                $aiResponse = json_decode(trim($content), true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return $aiResponse;
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
        $options = json_encode($optionsData['options'], JSON_UNESCAPED_UNICODE);

        $prompt = "I have product options data from an e-commerce store. Please analyze and categorize them.\n\n";
        $prompt .= "Product: {$productName}\n";
        $prompt .= "Category: {$categoryName}\n\n";
        $prompt .= "Options data (JSON format):\n{$options}\n\n";
        $prompt .= "Please categorize each option into one of these categories:\n";
        $prompt .= "1. design_service - For design-related options (contains words like 'تصميم', 'خدمة التصميم')\n";
        $prompt .= "2. printing_method - For printing methods (contains words like 'طباعة', 'طريقة الطباعة')\n";
        $prompt .= "3. print_location - For print locations (contains words like 'مكان الطباعة', 'موقع الطباعة')\n";
        $prompt .= "4. embroider_location - For embroidery locations (contains words like 'تطريز', 'مكان التطريز')\n";
        $prompt .= "5. material - For materials (contains words like 'خامة', 'مادة', 'نوع الخامة')\n";
        $prompt .= "6. size - For sizes (contains words like 'مقاس', 'حجم', 'الحجم')\n";
        $prompt .= "7. quantity - For quantities (contains words like 'كمية', 'عدد')\n";
        $prompt .= "8. general - For everything else\n\n";
        $prompt .= "Return response in JSON format with this structure:\n";
        $prompt .= "{\n";
        $prompt .= "  \"categorized_options\": [\n";
        $prompt .= "    {\n";
        $prompt .= "      \"option_id\": \"original option id\",\n";
        $prompt .= "      \"option_name\": \"original option name\",\n";
        $prompt .= "      \"category\": \"one of the categories above\",\n";
        $prompt .= "      \"confidence\": \"high/medium/low\",\n";
        $prompt .= "      \"recommended_table\": \"table name for storage\",\n";
        $prompt .= "      \"processing_notes\": \"notes for processing\"\n";
        $prompt .= "    }\n";
        $prompt .= "  ]\n";
        $prompt .= "}\n\n";
        $prompt .= "Important: Be accurate in categorization. If uncertain, use 'general' category.";

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
                'quantity' => 0,
                'general' => 0
            ]
        ];

        // Create mapping from option_id to original option
        $originalOptionsMap = [];
        foreach ($originalOptions as $option) {
            $originalOptionsMap[$option['id']] = $option;
        }

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
                        $this->processSize($productId, $originalOption);
                        $results['summary']['size']++;
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
     * Process options without AI (fallback)
     */
    private function processOptionsWithoutAi($productId, $options)
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
                'quantity' => 0,
                'general' => 0
            ]
        ];

        foreach ($options as $option) {
            try {
                $name = $option['name'] ?? '';
                $processed = false;

                // Manual categorization based on keywords
                if (str_contains($name, 'خدمة التصميم') || str_contains($name, 'تصميم')) {
                    $this->processDesignService($productId, $option);
                    $results['summary']['design_service']++;
                    $processed = true;
                } elseif (str_contains($name, 'طريقة الطباعة') || str_contains($name, 'الطباعة')) {
                    $this->processPrintingMethod($productId, $option);
                    $results['summary']['printing_method']++;
                    $processed = true;
                } elseif (str_contains($name, 'مكان الطباعة') || str_contains($name, 'موقع الطباعة')) {
                    $this->processPrintLocation($productId, $option);
                    $results['summary']['print_location']++;
                    $processed = true;
                } elseif (str_contains($name, 'التطريز') || str_contains($name, 'مكان التطريز')) {
                    $this->processEmbroiderLocation($productId, $option);
                    $results['summary']['embroider_location']++;
                    $processed = true;
                } elseif (str_contains($name, 'الخامة') || str_contains($name, 'المادة') || str_contains($name, 'نوع الخامة')) {
                    $this->processMaterial($productId, $option);
                    $results['summary']['material']++;
                    $processed = true;
                } elseif (str_contains($name, 'المقاس') || str_contains($name, 'الحجم')) {
                    $this->processSize($productId, $option);
                    $results['summary']['size']++;
                    $processed = true;
                } elseif (str_contains($name, 'الكمية') || str_contains($name, 'عدد')) {
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
                    'option_name' => $name,
                    'category' => $processed ? 'manual' : 'general',
                    'details_count' => count($option['details'] ?? [])
                ];
                
            } catch (\Exception $e) {
                $results['errors'][] = "Failed to process option {$option['id']}: " . $e->getMessage();
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
        foreach ($option['details'] as $detail) {
            PrintingMethod::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'description' => $detail['name'],
                    'base_price' => $detail['additional_price'] ?? 0
                ]
            );
        }
    }

    /**
     * Process print location
     */
    private function processPrintLocation($productId, $option)
    {
        foreach ($option['details'] as $detail) {
            PrintLocation::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'type' => $option['name'],
                    'additional_price' => $detail['additional_price'] ?? 0
                ]
            );
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
     * Process size
     */
    private function processSize($productId, $option)
    {
        foreach ($option['details'] as $detail) {
            $size = Size::firstOrCreate(
                ['name' => $detail['name']],
                [
                    'product_id' => $productId
                ]
            );
        }
    }

    /**
     * Process quantity
     */
    private function processQuantity($productId, $option)
    {
        // Quantity can be stored in general options
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
}