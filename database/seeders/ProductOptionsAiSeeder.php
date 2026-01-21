<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProcessingLog;
use App\Models\ProductOptions;
use App\Models\SeederProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Services\ProductOptionsAiService;

class ProductOptionsAiSeeder extends Seeder
{
    protected $optionsService;
    protected $useAi = true;
    protected $seederName = 'ProductOptionsAiSeeder';
    protected $progress;
    protected $maxPages = 10;
    protected $batchSize = 10;
    protected $productsProcessedInBatch = 0;
    protected $currentStartTime;
    protected $productsPerPage = 32; // نفس الـ limit في الـ API

    public function __construct()
    {
        $this->optionsService = new ProductOptionsAiService();
    }

    private function initSeeder()
    {
        $this->progress = SeederProgress::firstOrCreate(
            ['seeder_name' => $this->seederName],
            [
                'status' => 'pending',
                'total_processed' => 0,
                'success_count' => 0,
                'fail_count' => 0,
                'skipped_count' => 0,
                'last_cursor_url' => null,
                'pages_processed' => 0,
                'started_at' => null
            ]
        );
        
        $this->currentStartTime = now();
        
        $this->command->info("📊 Loaded progress: {$this->progress->total_processed} products processed");
        $this->command->info("📄 Pages processed: {$this->progress->pages_processed}");
        $this->command->info("🔗 Last cursor: " . ($this->progress->last_cursor_url ? 'Available' : 'None'));
        $this->command->info("🏁 Status: {$this->progress->status}");
    }
  /**
     * Run the database seeds - AUTO PAGINATION VERSION
     */
    public function run(): void
    {
        $this->command->info('🚀 Starting AI-powered product options processing...');
        $this->command->info('This seeder will process ALL pages automatically');
        
        $totalSuccessCount = 0;
        $totalFailCount = 0;
        $totalAiProcessed = 0;
        $totalManualProcessed = 0;
        $totalSizeTiers = 0;
        $totalProducts = 0;
        $currentPage = 0;
        
        $nextCursorUrl = null;
        $hasMorePages = true;
        
        $startTime = now();

        try {
            while ($hasMorePages) {
                $currentPage++;
                
                $this->command->info("\n" . str_repeat('═', 70));
                $this->command->info("📄 Processing Page {$currentPage}");
                $this->command->info(str_repeat('═', 70));
                
                // Fetch page from API
                $pageData = $this->fetchProductsPage($nextCursorUrl);
                
                if (!$pageData) {
                    $this->command->error('❌ Failed to fetch page from API');
                    break;
                }
                
                $productsData = $pageData['data'];
                $nextCursorUrl = $pageData['next_cursor_url'];
                $hasMorePages = !empty($nextCursorUrl);
                
                if (count($productsData) === 0) {
                    $this->command->info('📭 No products found on this page');
                    break;
                }
                
                $this->command->info("✅ Fetched " . count($productsData) . " products from API");
                $this->command->info("🔗 Next cursor: " . ($hasMorePages ? 'Available' : 'None'));
                
                // Process products on this page
                $pageResults = $this->processProductsBatch($productsData, $currentPage);
                
                // Update statistics
                $totalSuccessCount += $pageResults['success_count'];
                $totalFailCount += $pageResults['fail_count'];
                $totalAiProcessed += $pageResults['ai_processed_count'];
                $totalManualProcessed += $pageResults['manual_processed_count'];
                $totalSizeTiers += $pageResults['size_tiers_count'];
                $totalProducts += count($productsData);
                
                // Display page summary
                $this->displayPageSummary($pageResults, $currentPage);
                
                // Check for max pages limit
                if ($this->maxPages > 0 && $currentPage >= $this->maxPages) {
                    $this->command->info("\n⏸️ Reached maximum pages limit: {$this->maxPages}");
                    break;
                }
                
                // If no more pages, stop
                if (!$hasMorePages) {
                    $this->command->info("\n🏁 Reached end of pagination");
                    break;
                }
                
                // Delay between pages
                if ($hasMorePages) {
                    $this->command->info("\n⏳ Waiting 2 seconds before next page...");
                    sleep(2);
                }
            }
            
            // Calculate total time
            $totalTime = $startTime->diffInSeconds(now());
            
            // Display final summary
            $this->displayFinalSummary(
                $totalSuccessCount,
                $totalFailCount,
                $totalAiProcessed,
                $totalManualProcessed,
                $totalSizeTiers,
                $totalProducts,
                $totalTime,
                $currentPage
            );
            
        } catch (\Exception $e) {
            $this->command->error("❌ Seeder failed: " . $e->getMessage());
            Log::error('Seeder failed', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Fetch a single page of products
     */
    private function fetchProductsPage($cursorUrl = null)
    {
        try {
            $url = $cursorUrl ?: 'https://api.salla.dev/store/v1/products?limit=' . $this->productsPerPage;
            
            Log::info("Fetching products from: " . $url);
            
            // Use headers from the service
            $headers = [
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
            
            $response = Http::withHeaders($headers)
                ->timeout(60)
                ->get($url);
            
            if (!$response->successful()) {
                $this->command->error("HTTP Error: " . $response->status());
                Log::error('API request failed', [
                    'url' => $url,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return null;
            }
            
            $data = $response->json();
            
            return [
                'data' => $data['data'] ?? [],
                'next_cursor_url' => $data['cursor']['next'] ?? null,
                'current_page' => $data['cursor']['current'] ?? '1'
            ];
            
        } catch (\Exception $e) {
            $this->command->error("Fetch error: " . $e->getMessage());
            Log::error('Fetch page error', ['error' => $e->getMessage()]);
            return null;
        }
    }
    

    
    /**
     * Display processing summary for a product
     */
    private function displayProcessingSummary($result)
    {
        if (isset($result['summary'])) {
            $summary = [];
            foreach ($result['summary'] as $category => $count) {
                if ($count > 0) {
                    $summary[] = "$category: $count";
                }
            }

            if (!empty($summary)) {
                $this->command->info("  ↳ 🏷️ Categories: " . implode(', ', $summary));
            }
        }
    }
    
    /**
     * Clean up memory
     */
    private function cleanupMemory($currentIndex)
    {
        $this->productsProcessedInBatch++;
        
        if ($this->productsProcessedInBatch >= $this->batchSize) {
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
            
            DB::purge();
            
            $this->productsProcessedInBatch = 0;
            
            // Display memory usage
            $memoryUsage = memory_get_usage(true);
            $peakMemory = memory_get_peak_usage(true);
            
            $this->command->info("  ↳ 💾 Memory cleaned | Current: " . $this->formatBytes($memoryUsage) . 
                               " | Peak: " . $this->formatBytes($peakMemory));
        }
    }
    
    /**
     * Format bytes for display
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
    
    /**
     * Add delay between requests
     */
    private function addDelay($currentIndex, $totalProducts)
    {
        // Add longer delay every 5 products
        if (($currentIndex + 1) % 5 === 0 && ($currentIndex + 1) < $totalProducts) {
            $this->command->info("  ↳ ⏳ Waiting 3 seconds...");
            sleep(3);
        } else {
            sleep(1);
        }
    }
    
    /**
     * Display page summary
     */
    private function displayPageSummary($results, $pageNumber)
    {
        $this->command->info("\n📊 Page {$pageNumber} Summary:");
        $this->command->info("✅ Successfully Processed: {$results['success_count']}");
        $this->command->info("❌ Failed: {$results['fail_count']}");
        $this->command->info("🤖 AI Processed: {$results['ai_processed_count']}");
        $this->command->info("👨‍💻 Manual Processed: {$results['manual_processed_count']}");
        $this->command->info("📏 Size Tiers Created: {$results['size_tiers_count']}");
    }
    
    /**
     * Display final summary
     */
    private function displayFinalSummary($success, $fail, $ai, $manual, $tiers, $total, $time, $pages)
    {
        $this->command->info("\n" . str_repeat('=', 70));
        $this->command->info("🎉 PROCESSING COMPLETED!");
        $this->command->info(str_repeat('=', 70));
        $this->command->info("📄 Total Pages Processed: {$pages}");
        $this->command->info("📦 Total Products Fetched: {$total}");
        $this->command->info("✅ Successfully Processed: {$success}");
        $this->command->info("❌ Failed to Process: {$fail}");
        $this->command->info("⏱️ Total Processing Time: " . gmdate("H:i:s", $time));
        $this->command->info("🤖 AI Processed Products: {$ai}");
        $this->command->info("👨‍💻 Manually Processed: {$manual}");
        $this->command->info("📏 Total Size Tiers Created: {$tiers}");
        
        if ($total > 0) {
            $successRate = round(($success / $total) * 100, 2);
            $avgTimePerProduct = round($time / $total, 2);
            
            $this->command->info("📈 Success Rate: {$successRate}%");
            $this->command->info("⚡ Average Time per Product: {$avgTimePerProduct} seconds");
        }
        
        $peakMemory = $this->formatBytes(memory_get_peak_usage(true));
        $this->command->info("💾 Peak Memory Usage: {$peakMemory}");
        
        $this->command->info(str_repeat('=', 70));
        $this->command->info("✨ All pages have been processed successfully!");
    }
    
    /**
     * Test mode - process only first few pages
     */
    public function testMode()
    {
        $this->maxPages = 2;
        $this->productsPerPage = 5;
        $this->command->info("🧪 TEST MODE - Processing 2 pages with 5 products each");
        $this->command->info("This is for testing only");
        $this->run();
    }
    
    /**
     * Set maximum pages to process
     */
    public function setMaxPages($pages)
    {
        $this->maxPages = $pages;
        $this->command->info("📄 Maximum pages to process: {$pages}");
        $this->run();
    }
    
    /**
     * Process with AI disabled
     */
    public function manualMode()
    {
        $this->useAi = false;
        $this->command->info("🛠️ MANUAL MODE - AI processing disabled");
        $this->run();
    }
    
    /**
     * Process with smaller batches
     */
    public function smallBatch()
    {
        $this->productsPerPage = 10;
        $this->command->info("📦 Small batch mode - 10 products per page");
        $this->run();
    }

    
 
    /**
     * معالجة صفحة واحدة من المنتجات
     */
    private function processProductsPage($productsData, $pageNumber)
    {
        $results = [
            'success_count' => 0,
            'fail_count' => 0,
            'ai_processed_count' => 0,
            'manual_processed_count' => 0,
            'size_tiers_count' => 0
        ];

        $totalProducts = count($productsData);
        
        foreach ($productsData as $index => $productData) {
            $productNumber = $index + 1;
            $this->command->info("\n📦 [$productNumber/$totalProducts] Processing: " . ($productData['name'] ?? 'Unknown'));

            // Find product in database
            $product = Product::where('external_id', $productData['id'])->first();

            if (!$product) {
                $this->command->warn("  ↳ ⚠️ Product not found in database: " . $productData['id']);
                $results['fail_count']++;
                continue;
            }

            // Fetch product page
            $url = $productData['url'] ?? null;
            if (!$url) {
                $this->command->warn("  ↳ ⚠️ No URL for product: " . $product->id);
                $results['fail_count']++;
                continue;
            }

            $this->command->info("  ↳ 🌐 Fetching options...");
            $options = $this->optionsService->extractOptionsFromHtml($url);

            if (!$options || !is_array($options)) {
                $this->command->warn("  ↳ ⚠️ No options found");
                $results['fail_count']++;
                continue;
            }

            $this->command->info("  ↳ ✅ Found " . count($options) . " options");

            // Process options
            $processingResult = null;

            if ($this->useAi) {
                $this->command->info("  ↳ 🤖 Processing with AI...");
                $processingResult = $this->optionsService->processOptionsWithAi(
                    $product->id,
                    $options,
                    $product->name,
                    $product->category ? $product->category->name : null
                );

                if ($processingResult && empty($processingResult['errors'])) {
                    $results['ai_processed_count']++;
                    $this->command->info("  ↳ ✅ AI processing successful");
                } else {
                    $this->command->warn("  ↳ ⚠️ Switching to manual...");
                    $processingResult = $this->optionsService->processOptionsWithoutAi($product->id, $options);
                    $results['manual_processed_count']++;
                }
            } else {
                $processingResult = $this->optionsService->processOptionsWithoutAi($product->id, $options);
                $results['manual_processed_count']++;
            }

            if ($processingResult) {
                $this->displayProcessingSummary($processingResult);
                
                if (isset($processingResult['summary']['size_tiers'])) {
                    $results['size_tiers_count'] += $processingResult['summary']['size_tiers'];
                }

                $results['success_count']++;
            } else {
                $this->command->error("  ↳ ❌ Failed to process options");
                $results['fail_count']++;
            }

            // تنظيف الذاكرة
            $this->checkMemoryAndCleanup($index);
            
            // تأخير
            if (($index + 1) % 5 === 0 && ($index + 1) < $totalProducts) {
                sleep(3);
            }
        }
        
        return $results;
    }

    /**
     * Update progress in database
     */
    private function updateProgress($data)
    {
        if ($this->progress) {
            $this->progress->update($data);
            $this->progress->refresh();
        }
    }

    /**
     * التحقق من الذاكرة وتنظيفها
     */
    private function checkMemoryAndCleanup($currentIndex)
    {
        $this->productsProcessedInBatch++;
        
        if ($this->productsProcessedInBatch >= $this->batchSize) {
            if (function_exists('gc_collect_cycles')) {
                gc_collect_cycles();
            }
            
            DB::purge();
            
            $this->productsProcessedInBatch = 0;
        }
    }

    /**
     * Reset progress and run
     */
    public function resetAndRun()
    {
        $this->resetProgress();
        $this->run();
    }





    /**
     * Reset progress
     */
    public function resetProgress()
    {
        $this->initSeeder();
        
        $this->progress->update([
            'last_processed_id' => null,
            'last_cursor_url' => null,
            'total_processed' => 0,
            'success_count' => 0,
            'fail_count' => 0,
            'skipped_count' => 0,
            'pages_processed' => 0,
            'total_pages' => 0,
            'status' => 'pending',
            'started_at' => null,
            'completed_at' => null,
            'current_memory_usage' => 0,
            'average_processing_time' => 0
        ]);
        
        // حذف ملف التقدم المحفوظ أيضاً
        $this->optionsService->resetProgressFile();
        
        $this->command->info("🔄 Progress reset successfully");
    }




    /**
     * تسجيل خطوة معالجة
     */
    private function logStep($step, $status, $details = [])
    {
        try {
            return ProcessingLog::create([
                'seeder_progress_id' => $this->progress->id,
                'step' => $step,
                'status' => $status,
                'details' => $details,
                'memory_usage' => memory_get_usage(true),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log step', ['error' => $e->getMessage()]);
            return null;
        }
    }

 

    /**
     * Set products per page
     */
    public function setProductsPerPage($count)
    {
        $this->productsPerPage = $count;
        $this->command->info("📦 Products per page: {$count}");
        return $this;
    }

    /**
     * Continue processing from where it left off
     */
    public function continueProcessing()
    {
        $this->command->info("🔄 Continuing processing from last cursor...");
        
        // إذا كانت الحالة completed، نغيرها إلى in_progress للاستئناف
        if ($this->progress->status === 'completed') {
            $this->progress->update(['status' => 'in_progress']);
        }
        
        $this->run();
    }

    /**
     * Force run even if completed
     */
    public function forceRun()
    {
      //  $this->forceRun = true;
        $this->command->info("⚡ Force running seeder...");
        $this->run();
    }



    /**
     * Show current progress status
     */
    public function showStatus()
    {
        $this->initSeeder();
        
        $this->command->info("\n📈 Current Seeder Status:");
        $this->command->info("Seeder Name: {$this->progress->seeder_name}");
        $this->command->info("Status: {$this->progress->status}");
        $this->command->info("Last Processed ID: {$this->progress->last_processed_id}");
        $this->command->info("Last Cursor URL: " . ($this->progress->last_cursor_url ? 'Available' : 'None'));
        $this->command->info("Pages Processed: {$this->progress->pages_processed}");
        $this->command->info("Total Processed: {$this->progress->total_processed}");
        $this->command->info("Success Count: {$this->progress->success_count}");
        $this->command->info("Fail Count: {$this->progress->fail_count}");
        $this->command->info("Skipped Count: {$this->progress->skipped_count}");
        $this->command->info("Memory Usage: " . $this->formatBytes($this->progress->current_memory_usage));
        $this->command->info("Avg Processing Time: " . round($this->progress->average_processing_time, 2) . " seconds");
        $this->command->info("Last Updated: {$this->progress->updated_at}");
        
        if ($this->progress->started_at) {
            $elapsed = $this->progress->started_at->diff(now());
            $this->command->info("Started: {$this->progress->started_at} (Elapsed: {$elapsed->format('%Hh %Im %Ss')})");
        }
        
        if ($this->progress->completed_at) {
            $this->command->info("Completed At: {$this->progress->completed_at}");
        }
        
        // Check if there are more pages to process
        if ($this->progress->status === 'paused' || $this->progress->status === 'in_progress') {
            $this->command->info("ℹ️ There are more pages to process. Run --seeder=ProductOptionsAiSeeder@continueProcessing");
        }
        
        // عرض آخر 5 سجلات معالجة
        try {
            $recentLogs = ProcessingLog::where('seeder_progress_id', $this->progress->id)
                ->latest()
                ->limit(5)
                ->get();
            
            if ($recentLogs->count() > 0) {
                $this->command->info("\n🕒 Recent Logs:");
                foreach ($recentLogs as $log) {
                    $time = $log->created_at->format('H:i:s');
                    $memory = $this->formatBytes($log->memory_usage);
                    $this->command->info("[{$time}] {$log->step} - {$log->status} ({$memory})");
                }
            }
        } catch (\Exception $e) {
            // تجاهل الأخطاء في عرض السجلات
        }
    }
    /**
 * معالجة التبعيات للمنتج
 */
private function processDependenciesForProduct($productId, $options)
{
    // التحقق من وجود خيارات الكميات المرتبطة
    $this->linkRelatedQuantityOptions($productId, $options);
}

/**
 * ربط خيارات الكميات بالخيارات التي تعتمد عليها
 */
private function linkRelatedQuantityOptions($productId, $options)
{
    foreach ($options as $option) {
        if (isset($option['visibility_condition'])) {
            $condition = $option['visibility_condition'];
            
            if (is_string($condition) && strpos($condition, 'options[') !== false) {
                // استخراج معلومات التبعية
                $parentOptionId = $this->extractParentOptionId($condition);
                $parentDetailId = $this->extractParentDetailId($condition);
                
                if ($parentOptionId) {
                    // البحث عن السجل الأصلي للخيار المعتمد عليه
                    $parentOption = \App\Models\ProductOptions::where([
                        'product_id' => $productId,
                        'external_option_id' => $parentOptionId
                    ])->first();
                    
                    // تحديث خيارات المنتج لإضافة التبعيات
                    \App\Models\ProductOptions::where([
                        'product_id' => $productId,
                        'external_option_id' => $option['id']
                    ])->update([
                        'depends_on_option_id' => $parentOption->id ?? null,
                        'depends_on_detail_id' => $parentDetailId,
                        'dependency_condition' => 'depends_on'
                    ]);
                    
                    $this->command->info("  ↳ 🔗 Linked option {$option['id']} to parent {$parentOptionId}");
                }
            }
        }
    }
}

/**
 * استخراج معرف الخيار الأصلي من شرط التبعية
 */
private function extractParentOptionId($condition)
{
    // مثال: "options[628499549] = 1293578302"
    if (preg_match('/options\[(\d+)\]/', $condition, $matches)) {
        return $matches[1];
    }
    return null;
}

/**
 * استخراج معرف التفصيل الأصلي من شرط التبعية
 */
private function extractParentDetailId($condition)
{
    // مثال: "options[628499549] = 1293578302"
    if (preg_match('/options\[\d+\]\s*=\s*(\d+)/', $condition, $matches)) {
        return $matches[1];
    }
    return null;
}

/**
 * تحديث دالة processProductsBatch لإضافة استدعاء processDependenciesForProduct
 */
private function processProductsBatch($productsData, $pageNumber)
{
    $results = [
        'success_count' => 0,
        'fail_count' => 0,
        'ai_processed_count' => 0,
        'manual_processed_count' => 0,
        'size_tiers_count' => 0,
        'dependencies_linked' => 0
    ];

    $totalProducts = count($productsData);
    
    foreach ($productsData as $index => $productData) {
        $productNumber = $index + 1;
        $this->command->info("\n📦 [$productNumber/$totalProducts] Page {$pageNumber} - Processing: " . 
                           ($productData['name'] ?? 'Unknown'));

        // Find product in database
        $product = \App\Models\Product::where('external_id', $productData['id'])->first();

        if (!$product) {
            $this->command->warn("  ↳ ⚠️ Product not found in database: " . $productData['id']);
            $results['fail_count']++;
            continue;
        }

        // Check if product has URL
        $url = $productData['url'] ?? null;
        if (!$url) {
            $this->command->warn("  ↳ ⚠️ No URL for product: " . $product->id);
            $results['fail_count']++;
            continue;
        }

        $this->command->info("  ↳ 🌐 Fetching options from product page...");
        
        try {
            $options = $this->optionsService->extractOptionsFromHtml($url);
        } catch (\Exception $e) {
            $this->command->error("  ↳ ❌ Failed to fetch options: " . $e->getMessage());
            $results['fail_count']++;
            continue;
        }

        if (!$options || !is_array($options)) {
            $this->command->warn("  ↳ ⚠️ No options found for this product");
            $results['fail_count']++;
            continue;
        }

        $this->command->info("  ↳ ✅ Found " . count($options) . " options");

        // Process options with AI or manual
        $processingResult = null;

        if ($this->useAi) {
            $this->command->info("  ↳ 🤖 Processing with AI...");
            try {
                $processingResult = $this->optionsService->processOptionsWithAi(
                    $product->id,
                    $options,
                    $product->name,
                    $product->category ? $product->category->name : null
                );

                if ($processingResult && empty($processingResult['errors'])) {
                    $results['ai_processed_count']++;
                    $this->command->info("  ↳ ✅ AI processing successful");
                } else {
                    $this->command->warn("  ↳ ⚠️ AI processing failed, switching to manual...");
                    $processingResult = $this->optionsService->processOptionsWithoutAi($product->id, $options);
                    $results['manual_processed_count']++;
                }
            } catch (\Exception $e) {
                $this->command->error("  ↳ ❌ AI processing error: " . $e->getMessage());
                $processingResult = $this->optionsService->processOptionsWithoutAi($product->id, $options);
                $results['manual_processed_count']++;
            }
        } else {
            $this->command->info("  ↳ 👨‍💻 Processing manually...");
            $processingResult = $this->optionsService->processOptionsWithoutAi($product->id, $options);
            $results['manual_processed_count']++;
        }

        if ($processingResult) {
            // Display processing summary
            $this->displayProcessingSummary($processingResult);
            
            // Track size tiers
            if (isset($processingResult['summary']['size_tiers'])) {
                $results['size_tiers_count'] += $processingResult['summary']['size_tiers'];
            }
            
            // معالجة التبعيات
            $dependenciesLinked = $this->processDependenciesForProduct($product->id, $options);
            if ($dependenciesLinked > 0) {
                $results['dependencies_linked'] += $dependenciesLinked;
                $this->command->info("  ↳ 🔗 Linked {$dependenciesLinked} dependencies");
            }

            $results['success_count']++;
        } else {
            $this->command->error("  ↳ ❌ Failed to process options");
            $results['fail_count']++;
        }

        // Clean up memory
        $this->cleanupMemory($index);
        
        // Add delay between products
        $this->addDelay($index, $totalProducts);
    }
    
    return $results;
}
}