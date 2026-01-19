<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\ProductOptionsAiService;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductOptionsAiSeeder extends Seeder
{
    protected $optionsService;
    protected $useAi = true; // Set to false to use manual processing only

    public function __construct()
    {
        $this->optionsService = new ProductOptionsAiService();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting AI-powered product options processing...');

        // 1. Fetch products from API
        $this->command->info('Fetching products list from Salla API...');
        $productsData = $this->optionsService->fetchProductsFromApi();

        if (!$productsData || !isset($productsData['data'])) {
            $this->command->error('Failed to fetch products data from API');
            return;
        }

        $products = $productsData['data'];
        $this->command->info('Fetched ' . count($products) . ' products from API');

        $successCount = 0;
        $failCount = 0;
        $aiProcessedCount = 0;
        $manualProcessedCount = 0;
        $totalSizeTiers = 0;

        foreach ($products as $index => $productData) {
            $productNumber = $index + 1;
            $totalProducts = count($products);

            $this->command->info("[$productNumber/$totalProducts] Processing: " . ($productData['name'] ?? 'Unknown'));

            // Find product in database
            $product = Product::where('external_id', $productData['id'])->first();

            if (!$product) {
                $this->command->warn("  ↳ Product not found in database: " . $productData['id']);
                $failCount++;
                continue;
            }

            // 2. Fetch product page
            $url = $productData['url'] ?? null;
            if (!$url) {
                $this->command->warn("  ↳ No URL for product: " . $product->id);
                $failCount++;
                continue;
            }

            $this->command->info("  ↳ Fetching options from: " . $url);
            $options = $this->optionsService->extractOptionsFromHtml($url);

            if (!$options || !is_array($options)) {
                $this->command->warn("  ↳ No options found for product: " . $product->id);
                $failCount++;
                continue;
            }

            $this->command->info("  ↳ Found " . count($options) . " options");

            // Display option names for debugging
            foreach ($options as $opt) {
                $this->command->line("    - " . ($opt['name'] ?? 'Unnamed') . " (ID: " . ($opt['id'] ?? 'N/A') . ")");
            }

            // 3. Process options with AI or manual
            $processingResult = null;

            if ($this->useAi) {
                $this->command->info("  ↳ Processing with AI...");
                $processingResult = $this->optionsService->processOptionsWithAi(
                    $product->id,
                    $options,
                    $product->name,
                    $product->category ? $product->category->name : null
                );

                if ($processingResult && empty($processingResult['errors'])) {
                    $aiProcessedCount++;
                    $this->command->info("  ↳ ✓ AI processing successful");
                } else {
                    $this->command->warn("  ↳ AI processing failed, switching to manual...");
                    $processingResult = $this->optionsService->processOptionsWithoutAi($product->id, $options);
                    $manualProcessedCount++;
                }
            } else {
                $this->command->info("  ↳ Processing manually...");
                $processingResult = $this->optionsService->processOptionsWithoutAi($product->id, $options);
                $manualProcessedCount++;
            }

            if ($processingResult) {
                // Show processing summary
                $this->displayProcessingSummary($processingResult);
                
                // Track size tiers
                if (isset($processingResult['summary']['size_tiers'])) {
                    $totalSizeTiers += $processingResult['summary']['size_tiers'];
                }

                // Build combinations
                $combinations = $this->optionsService->buildAllCombinations($options);
                $this->command->info("  ↳ Built " . count($combinations) . " possible combinations");

                $successCount++;

                // Log any errors
                if (!empty($processingResult['errors'])) {
                    foreach ($processingResult['errors'] as $error) {
                        $this->command->warn("    - Error: " . $error);
                    }
                }
            } else {
                $this->command->error("  ↳ Failed to process options");
                $failCount++;
            }

            // Delay between requests
            $this->addDelay($index, $totalProducts);
        }

        // Display final summary
        $this->displayFinalSummary($successCount, $failCount, $aiProcessedCount, $manualProcessedCount, $totalSizeTiers, count($products));
    }

    /**
     * Display processing summary
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
                $this->command->info("  ↳ Categorized: " . implode(', ', $summary));
            }
        }

        if (isset($result['processed_options'])) {
            $this->command->info("  ↳ Total processed options: " . count($result['processed_options']));
            
            // Show details of processed options
            foreach ($result['processed_options'] as $processed) {
                $category = $processed['category'] ?? 'unknown';
                $tiers = isset($processed['tiers_created']) ? " (Tiers: {$processed['tiers_created']})" : "";
                $this->command->line("    - {$processed['option_name']} → {$category}{$tiers}");
            }
        }
    }

    /**
     * Add delay between requests
     */
    private function addDelay($currentIndex, $totalProducts)
    {
        // Add longer delay every 5 products
        if (($currentIndex + 1) % 5 === 0 && ($currentIndex + 1) < $totalProducts) {
            $this->command->info("  ↳ Waiting 10 seconds to avoid rate limits...");
            sleep(10);
        } else {
            sleep(3);
        }
    }

    /**
     * Display final summary - UPDATED
     */
    private function displayFinalSummary($successCount, $failCount, $aiProcessedCount, $manualProcessedCount, $totalSizeTiers, $totalProducts)
    {
        $this->command->info("\n" . str_repeat('=', 60));
        $this->command->info("📊 FINAL PROCESSING SUMMARY");
        $this->command->info(str_repeat('=', 60));
        $this->command->info("Total Products: {$totalProducts}");
        $this->command->info("✅ Successfully Processed: {$successCount}");
        $this->command->info("❌ Failed: {$failCount}");

        if ($this->useAi) {
            $this->command->info("🤖 AI Processed: {$aiProcessedCount}");
        }

        $this->command->info("👨‍💻 Manual Processed: {$manualProcessedCount}");
        $this->command->info("📏 Size Tiers Created: {$totalSizeTiers}");
        $this->command->info(str_repeat('=', 60));

        if ($failCount > 0) {
            $this->command->warn("Some products failed to process. Check logs for details.");
        }

        $this->command->info("🎉 Product options processing completed!");
    }

    /**
     * Run with AI disabled (manual processing only) php artisan db:seed --class=ProductOptionsAiSeeder --method=runManualOnly
     */
    public function runManualOnly()
    {
        $this->useAi = false;
        $this->command->info("Running in manual mode (AI disabled)...");
        $this->run();
    }

    /**
     * Run for a specific product (for testing)
     */
    public function runForProduct($productId)
    {
        $this->command->info("Processing single product ID: {$productId}");

        $product = Product::find($productId);
        if (!$product) {
            $this->command->error("Product not found: {$productId}");
            return;
        }

        $this->command->info("Processing product: " . $product->name);
        
        // Fetch URL from product or use default
        $url = $product->url;
        if (!$url) {
            $this->command->error("No URL available for product");
            return;
        }

        $options = $this->optionsService->extractOptionsFromHtml($url);
        
        if (!$options) {
            $this->command->error("No options found for product");
            return;
        }

        $this->command->info("Found " . count($options) . " options");
        
        // Process with AI
        $result = $this->optionsService->processOptionsWithAi(
            $productId,
            $options,
            $product->name,
            $product->category ? $product->category->name : null
        );
        
        $this->displayProcessingSummary($result);
    }
}