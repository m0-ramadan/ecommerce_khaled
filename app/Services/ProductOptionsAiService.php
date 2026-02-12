<?php

namespace App\Services;

use App\Models\ProductOptions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class ProductOptionsAiService
{
    private array $baseHeaders = [
        'cache-control'        => 'no-cache',
        'currency'             => 'SAR',
        'origin'               => 'https://printnes.co',
        'referer'              => 'https://printnes.co/',
        's-anonymous-id'       => 'adc56dc2-e714-4bc9-bf6a-56d1241de77c',
        's-app-os'             => 'browser',
        's-app-version'        => '2.0.0',
        's-country'            => 'EG',
        's-source'             => 'twilight',
        's-store-api-version'  => 'swoole',
        's-user-id'            => 'rvNP14rwutHVUhMitKNqRvFIE8FX5uewVC4rbeaO',
        's-version-id'         => '1731030587',
        'store-identifier'     => '650799341',
    ];

    private string $deepseekApiKey;
    private string $deepseekModel   = 'deepseek-chat';
    private string $deepseekBaseUrl = 'https://api.deepseek.com/v1/chat/completions';

    private const CATEGORIES = [
        'design_service',
        'printing_method',
        'print_location',
        'embroider_location',
        'material',
        'size',
        'color',
        'delivery_time',
        'quantity',
        'general',
    ];

    public function __construct()
    {
        $this->deepseekApiKey = env('DEEPSEEK_API_KEY', '');
    }

    /**
     * ===================================================================
     * PUBLIC: Fetch products from Salla API
     * ===================================================================
     */
    public function fetchProductsPage(?string $cursorUrl = null, int $limit = 32): ?array
    {
        $url = $cursorUrl ?? "https://api.salla.dev/store/v1/products?limit={$limit}";

        try {
            $response = Http::withHeaders($this->baseHeaders)->timeout(60)->get($url);

            if (!$response->successful()) {
                Log::error('fetchProductsPage failed', ['status' => $response->status()]);
                return null;
            }

            $data = $response->json();

            return [
                'data'            => $data['data']           ?? [],
                'next_cursor_url' => $data['cursor']['next'] ?? null,
            ];
        } catch (\Throwable $e) {
            Log::error('fetchProductsPage exception', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * ===================================================================
     * PUBLIC: Extract options from product page HTML
     * 
     * ⭐ الميزة الجديدة: نقرأ visibility_condition من JSON مباشرة
     *    مش معتمدين بس على HTML attributes
     * ===================================================================
     */
    public function extractOptionsFromHtml(string $url): ?array
    {
        try {
            $response = Http::withHeaders($this->baseHeaders)->timeout(30)->get($url);

            if (!$response->successful()) {
                Log::warning('extractOptionsFromHtml – HTTP failed', ['url' => $url]);
                return null;
            }

            $html = $response->body();

            // 1. Extract the options JSON from salla-product-options
            preg_match('/<salla-product-options\s[^>]*options="([^"]+)"/', $html, $m);
            if (empty($m[1])) {
                preg_match('/\boptions="([^"]+)"/', $html, $m);
            }
            if (empty($m[1])) {
                Log::warning('extractOptionsFromHtml – options attribute not found', ['url' => $url]);
                return null;
            }

            $json    = html_entity_decode($m[1], ENT_QUOTES, 'UTF-8');
            $options = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::warning('extractOptionsFromHtml – JSON decode failed', ['url' => $url]);
                return null;
            }

            // 2. ⭐ الأهم: نقرأ visibility_condition من كل option في JSON
            foreach ($options as &$opt) {
                if (isset($opt['visibility_condition']) && $opt['visibility_condition'] !== null) {
                    $opt['dependency_info'] = [
                        'type'                  => $opt['visibility_condition']['operator'] === '=' ? 'equals' : 'not_equals',
                        'operator'              => $opt['visibility_condition']['operator'], // '=' or '!='
                        'depends_on_option_id'  => (string)$opt['visibility_condition']['option'],
                        'depends_on_detail_id'  => (string)$opt['visibility_condition']['value'],
                    ];
                }
            }

            // 3. Also extract from HTML attributes (fallback)
            $htmlDeps = $this->extractDependenciesFromHtml($html);
            foreach ($htmlDeps as $optId => $depInfo) {
                foreach ($options as &$opt) {
                    if ((string)($opt['id'] ?? '') === $optId && !isset($opt['dependency_info'])) {
                        $opt['dependency_info'] = $depInfo;
                        break;
                    }
                }
            }

            Log::info('extractOptionsFromHtml success', [
                'url'          => $url,
                'option_count' => count($options),
                'dep_count'    => count(array_filter($options, fn($o) => isset($o['dependency_info']))),
            ]);

            return $options;
        } catch (\Throwable $e) {
            Log::error('extractOptionsFromHtml exception', ['url' => $url, 'error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * ===================================================================
     * PUBLIC: AI first → keyword fallback
     * ===================================================================
     */
    public function processOptionsWithAi(
        int $productId,
        array $options,
        ?string $productName = null,
        ?string $categoryName = null
    ): ?array {
        if ($this->deepseekApiKey) {
            try {
                $result = $this->runAiProcessing($productId, $options, $productName, $categoryName);
                if ($result && empty($result['errors'])) {
                    return $result;
                }
            } catch (\Throwable $e) {
                Log::warning('AI processing failed, falling back', ['product_id' => $productId]);
            }
        }

        return $this->processOptionsWithoutAi($productId, $options);
    }

    public function processOptionsWithoutAi(int $productId, array $options): ?array
    {
        foreach ($options as &$opt) {
            $opt['_category'] = $this->detectCategory($opt['name'] ?? '');
        }

        return $this->storeAllOptions($productId, $options);
    }

    /**
     * ===================================================================
     * PRIVATE: Core storage — كل شيء في product_options
     * 
     * ⭐ الآن نخزن:
     *   - depends_on_option_id  = parent external_option_id
     *   - depends_on_detail_id  = parent external_detail_id
     *   - dependency_operator   = '=' or '!='
     *   - dependency_condition  = 'equals' or 'not_equals' (for backward compatibility)
     * ===================================================================
     */
    private function storeAllOptions(int $productId, array $options): array
    {
        $summary = array_fill_keys(self::CATEGORIES, 0);
        $summary['total_rows']   = 0;
        $summary['dependencies'] = 0;
        $summary['errors']       = [];

        DB::beginTransaction();

        try {
            // Delete old rows
            ProductOptions::where('product_id', $productId)->delete();

            // Store all options with their details
            foreach ($options as $opt) {
                $extOptId   = (string)($opt['id'] ?? '');
                $category   = $opt['_category'] ?? 'general';
                $details    = $opt['details'] ?? [];
                $depInfo    = $opt['dependency_info'] ?? null;

                if ($extOptId === '' || empty($details)) {
                    continue;
                }

                // Prepare dependency data
                $dependsOnOptionId = null;
                $dependsOnDetailId = null;
                $dependencyOperator = null;
                $dependencyCondition = null;

                if ($depInfo) {
                    $dependsOnOptionId = $depInfo['depends_on_option_id'] ?? null;
                    $dependsOnDetailId = $depInfo['depends_on_detail_id'] ?? null;
                    $dependencyOperator = $depInfo['operator'] ?? ($depInfo['type'] === 'equals' ? '=' : '!=');
                    $dependencyCondition = $depInfo['type'] ?? 'equals';

                    if ($dependsOnOptionId) {
                        $summary['dependencies']++;
                    }
                }

                // Insert each detail as a separate row
                foreach ($details as $detail) {
                    ProductOptions::create([
                        'product_id'            => $productId,
                        'external_option_id'    => (int)$extOptId,
                        'external_detail_id'    => isset($detail['id']) ? (int)$detail['id'] : null,
                        'option_name'           => trim($opt['name'] ?? ''),
                        'option_value'          => trim($detail['name'] ?? ''),
                        'additional_price'      => $this->resolvePrice($detail),
                        'is_required'           => (bool)($opt['required'] ?? false),
                        'category'              => $category,
                        // Dependency fields
                        'depends_on_option_id'  => $dependsOnOptionId ? (int)$dependsOnOptionId : null,
                        'depends_on_detail_id'  => $dependsOnDetailId ? (int)$dependsOnDetailId : null,
                        'dependency_operator'   => $dependencyOperator,
                        'dependency_condition'  => $dependencyCondition,
                    ]);

                    $summary['total_rows']++;
                }

                $summary[$category] = ($summary[$category] ?? 0) + 1;
            }

            DB::commit();

            Log::info('storeAllOptions completed', [
                'product_id'    => $productId,
                'rows'          => $summary['total_rows'],
                'dependencies'  => $summary['dependencies'],
            ]);

            return $summary;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('storeAllOptions failed', ['product_id' => $productId, 'error' => $e->getMessage()]);
            $summary['errors'][] = $e->getMessage();
            return $summary;
        }
    }

    /**
     * ===================================================================
     * PRIVATE: Dependency extraction from HTML (fallback)
     * ===================================================================
     */
    private function extractDependenciesFromHtml(string $html): array
    {
        $deps = [];

        // Pattern 1: data-option-id first, then data-show-when
        preg_match_all(
            '/data-option-id="(\d+)"[^>]*data-show-when="([^"]+)"/s',
            $html,
            $m1,
            PREG_SET_ORDER
        );

        // Pattern 2: data-show-when first, then data-option-id
        preg_match_all(
            '/data-show-when="([^"]+)"[^>]*data-option-id="(\d+)"/s',
            $html,
            $m2,
            PREG_SET_ORDER
        );

        foreach ($m1 as $m) {
            $parsed = $this->parseVisibilityCondition($m[2]);
            if ($parsed) {
                $deps[$m[1]] = $parsed;
            }
        }

        foreach ($m2 as $m) {
            if (!isset($deps[$m[2]])) {
                $parsed = $this->parseVisibilityCondition($m[1]);
                if ($parsed) {
                    $deps[$m[2]] = $parsed;
                }
            }
        }

        return $deps;
    }

    /**
     * Parse data-show-when condition string from HTML
     */
    private function parseVisibilityCondition(string $condition): ?array
    {
        if (preg_match('/options\[(\d+)\]\s*=\s*(\d+)/', $condition, $m)) {
            return [
                'type'                  => 'equals',
                'operator'              => '=',
                'depends_on_option_id'  => $m[1],
                'depends_on_detail_id'  => $m[2],
            ];
        }

        if (preg_match('/options\[(\d+)\]\s*!=\s*(\d+)/', $condition, $m)) {
            return [
                'type'                  => 'not_equals',
                'operator'              => '!=',
                'depends_on_option_id'  => $m[1],
                'depends_on_detail_id'  => $m[2],
            ];
        }

        return null;
    }

    /**
     * ===================================================================
     * PRIVATE: Keyword-based category detection
     * ===================================================================
     */
    private function detectCategory(string $name): string
    {
        $n = mb_strtolower($name);

        if (str_contains($n, 'تصميم'))                    return 'design_service';
        if (str_contains($n, 'طريقة الطباعة') || str_contains($n, 'نوع الطباعة')) return 'printing_method';
        if (str_contains($n, 'مكان الطباعة')  || str_contains($n, 'موقع الطباعة')) return 'print_location';
        if (str_contains($n, 'تطريز'))                    return 'embroider_location';
        if (str_contains($n, 'خامة')  || str_contains($n, 'مادة')) return 'material';
        if (str_contains($n, 'مقاس')  || str_contains($n, 'حجم')  || str_contains($n, 'size')) return 'size';
        if (str_contains($n, 'لون')   || str_contains($n, 'color')) return 'color';
        if (str_contains($n, 'توصيل') || str_contains($n, 'تسليم') || str_contains($n, 'delivery')) return 'delivery_time';
        if (str_contains($n, 'كمية')  || str_contains($n, 'عدد')   || str_contains($n, 'qty')) return 'quantity';

        return 'general';
    }

    /**
     * ===================================================================
     * PRIVATE: AI processing
     * ===================================================================
     */
    private function runAiProcessing(
        int $productId,
        array $options,
        ?string $productName,
        ?string $categoryName
    ): ?array {
        $payload = [
            'product_name'  => $productName ?? 'Unknown',
            'category_name' => $categoryName ?? 'General',
            'options'       => array_map(fn($o) => [
                'id'      => $o['id']   ?? null,
                'name'    => $o['name'] ?? '',
                'details' => array_map(fn($d) => [
                    'id'   => $d['id']   ?? null,
                    'name' => $d['name'] ?? '',
                ], $o['details'] ?? []),
            ], $options),
        ];

        $aiResponse = $this->callDeepSeek($this->buildPrompt($payload));

        if (!$aiResponse || empty($aiResponse['categorized_options'])) {
            return null;
        }

        $catMap = [];
        foreach ($aiResponse['categorized_options'] as $row) {
            $catMap[(string)($row['option_id'] ?? '')] = $row['category'] ?? 'general';
        }

        foreach ($options as &$opt) {
            $oid = (string)($opt['id'] ?? '');
            $cat = $catMap[$oid] ?? 'general';
            $opt['_category'] = in_array($cat, self::CATEGORIES) ? $cat : 'general';
        }

        return $this->storeAllOptions($productId, $options);
    }

    private function buildPrompt(array $payload): string
    {
        $json = json_encode($payload['options'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return <<<PROMPT
📦 تصنيف خيارات منتج

المنتج: {$payload['product_name']}
الفئة: {$payload['category_name']}

الخيارات:
{$json}

صنّف كل option إلى إحدى الفئات:
design_service, printing_method, print_location, embroider_location,
material, size, color, delivery_time, quantity, general

أعد JSON فقط بهذا الشكل:
{
  "categorized_options": [
    { "option_id": "123", "category": "color" }
  ]
}
PROMPT;
    }

    private function callDeepSeek(string $prompt): ?array
    {
        try {
            $response = Http::timeout(60)->withHeaders([
                'Authorization' => 'Bearer ' . $this->deepseekApiKey,
                'Content-Type'  => 'application/json',
            ])->post($this->deepseekBaseUrl, [
                'model'           => $this->deepseekModel,
                'messages'        => [
                    ['role' => 'system', 'content' => 'You are an e-commerce options categorizer. Reply ONLY with valid JSON.'],
                    ['role' => 'user',   'content' => $prompt],
                ],
                'temperature'     => 0.2,
                'max_tokens'      => 2000,
                'response_format' => ['type' => 'json_object'],
            ]);

            if (!$response->successful()) {
                throw new \RuntimeException('DeepSeek HTTP ' . $response->status());
            }

            $content = trim(preg_replace('/^```json|```$/m', '', $response->json('choices.0.message.content', '')));
            return json_decode($content, true);
        } catch (\Throwable $e) {
            Log::error('callDeepSeek failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * ===================================================================
     * PRIVATE: Price helpers
     * ===================================================================
     */
    private function resolvePrice(array $detail): float
    {
        $p = (float)($detail['additional_price'] ?? 0);
        return $p > 0 ? $p : $this->extractPriceFromText($detail['name'] ?? '');
    }

    private function extractPriceFromText(string $text): float
    {
        if (preg_match('/([\d,٫.]+)\s*ريال?\s*\/\s*للحبة/u', $text, $m)) {
            return (float)str_replace([',', '٫'], '.', $m[1]);
        }
        if (preg_match('/([\d,٫.٠-٩]+)\s*ر\.س/u', $text, $m)) {
            return (float)str_replace([',', '٫'], '.', $this->arabicDigitsToWestern($m[1]));
        }
        if (preg_match('/(\d+(?:[.,]\d+)?)/', $text, $m)) {
            return (float)str_replace(',', '.', $m[1]);
        }
        return 0.0;
    }

    private function arabicDigitsToWestern(string $s): string
    {
        return str_replace(
            ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            $s
        );
    }

    /**
     * ===================================================================
     * PUBLIC: Read helpers for controllers / frontend
     * 
     * ⭐ هنا الأهم: الـ runtime logic الصحيح للـ dependencies
     *    مع دعم = و !=
     *    ومع دعم nested dependencies
     * ===================================================================
     */

    /**
     * Get all options for a product, grouped by external_option_id
     */
    public function getProductOptions(int $productId): array
    {
        $rows = ProductOptions::where('product_id', $productId)
            ->orderBy('option_name')
            ->get();

        $grouped = [];

        foreach ($rows as $row) {
            $key = (string)$row->external_option_id;

            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'id'         => $row->external_option_id,
                    'name'       => $row->option_name,
                    'required'   => (bool)$row->is_required,
                    'category'   => $row->category ?? 'general',
                    'type'       => $this->detectOptionType($row->option_value), // file, single-option, etc
                    'depends_on' => [
                        'option_ext_id' => $row->depends_on_option_id,
                        'detail_ext_id' => $row->depends_on_detail_id,
                        'operator'      => $row->dependency_operator ?? '=',
                        'condition'     => $row->dependency_condition ?? 'equals',
                    ],
                    'details'    => [],
                ];
            }

            $grouped[$key]['details'][] = [
                'db_id'       => $row->id,
                'external_id' => $row->external_detail_id,
                'value'       => $row->option_value,
                'price'       => (float)$row->additional_price,
            ];
        }

        return array_values($grouped);
    }

    /**
     * ⭐⭐ CORE RUNTIME LOGIC ⭐⭐
     * 
     * Check if an option should be visible based on current selections
     * 
     * @param array $option Option group data
     * @param array $selectedDetails [external_option_id => external_detail_id]
     * @param array $allOptionsMap All options mapped by external_option_id (for nested checks)
     * @return bool
     */
    private function isOptionVisible(array $option, array $selectedDetails, array $allOptionsMap): bool
    {
        $dep = $option['depends_on'];

        // No dependency → always visible
        if ($dep['option_ext_id'] === null) {
            return true;
        }

        $parentExtId = (int)$dep['option_ext_id'];
        $requiredDtl = (int)($dep['detail_ext_id'] ?? 0);
        $operator = $dep['operator'] ?? '=';

        // ⭐ Nested dependency check: parent must be visible first
        if (isset($allOptionsMap[$parentExtId])) {
            if (!$this->isOptionVisible($allOptionsMap[$parentExtId], $selectedDetails, $allOptionsMap)) {
                return false;
            }
        }

        // User hasn't selected parent option yet
        if (!isset($selectedDetails[$parentExtId])) {
            return false;
        }

        $chosenDtl = (int)$selectedDetails[$parentExtId];

        // Apply operator
        if ($operator === '!=') {
            return $chosenDtl !== $requiredDtl;
        }

        // Default: equals (=)
        return $chosenDtl === $requiredDtl;
    }

    /**
     * Get only visible options based on user selections
     * 
     * @param int $productId
     * @param array $selectedDetails [external_option_id => external_detail_id]
     * @return array
     */
    public function getAvailableOptions(int $productId, array $selectedDetails = []): array
    {
        $allOptions = $this->getProductOptions($productId);

        // Build map for nested dependency checks
        $optionsMap = [];
        foreach ($allOptions as $opt) {
            $optionsMap[$opt['id']] = $opt;
        }

        // Filter visible options
        $visibleOptions = array_filter(
            $allOptions,
            fn($opt) => $this->isOptionVisible($opt, $selectedDetails, $optionsMap)
        );

        return array_values($visibleOptions);
    }

    /**
     * Detect option type based on value/content
     */
    private function detectOptionType(string $value): string
    {
        if (str_contains($value, '.png') || str_contains($value, '.jpg') || str_contains($value, '.pdf')) {
            return 'file';
        }
        return 'single-option';
    }

    /**
     * ===================================================================
     * PUBLIC: Progress file helpers
     * ===================================================================
     */
    public function saveProgress(string $cursorUrl, int $totalFetched, int $pagesProcessed): void
    {
        file_put_contents(
            storage_path('app/fetch_progress.json'),
            json_encode([
                'cursor_url'      => $cursorUrl,
                'total_fetched'   => $totalFetched,
                'pages_processed' => $pagesProcessed,
                'saved_at'        => now()->toDateTimeString(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }

    public function loadProgress(): ?array
    {
        $path = storage_path('app/fetch_progress.json');
        if (!file_exists($path)) {
            return null;
        }
        return json_decode(file_get_contents($path), true) ?: null;
    }

    public function resetProgressFile(): void
    {
        $path = storage_path('app/fetch_progress.json');
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function getHeaders(): array
    {
        return $this->baseHeaders;
    }
}
