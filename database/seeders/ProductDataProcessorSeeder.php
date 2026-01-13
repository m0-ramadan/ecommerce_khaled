<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\SallaDataProcessorService;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductDataProcessorSeeder extends Seeder
{
    protected $processorService;

    public function __construct()
    {
        $this->processorService = new SallaDataProcessorService();
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting product data processing from Salla...');

        // جلب قائمة المنتجات من API
        $this->command->info('Fetching products list from Salla API...');
        
        // إذا كان لديك API لجلب المنتجات، استخدمه هنا
        // أو يمكنك جلب المنتجات من قاعدة البيانات
        $products = Product::whereNotNull('external_id')
            ->whereNotNull('url')
            ->limit(50) // معالجة 50 منتج في المرة الواحدة
            ->get();

        $this->command->info('Found ' . $products->count() . ' products to process');

        $successCount = 0;
        $failCount = 0;

        foreach ($products as $index => $product) {
            $productNumber = $index + 1;
            $totalProducts = $products->count();
            
            $this->command->info("[$productNumber/$totalProducts] Processing: {$product->name}");

            try {
                // استخراج البيانات من صفحة المنتج
                $options = $this->processorService->extractDataFromHtml($product->url);
                
                if (!$options || !is_array($options)) {
                    $this->command->warn("  ↳ No data extracted from URL");
                    $failCount++;
                    continue;
                }

                $this->command->info("  ↳ Extracted " . count($options) . " options");

                // معالجة البيانات وتخزينها
                $results = $this->processorService->processProductOptions($product->id, $options);
                
                if ($results) {
                    // عرض ملخص المعالجة
                    $this->displayProcessingSummary($results);
                    
                    // بناء التركيبات الممكنة
                    $combinations = $this->processorService->buildAllCombinations($product->id);
                    $this->command->info("  ↳ Built " . count($combinations) . " possible combinations");
                    
                    $successCount++;
                    $this->command->info("  ↳ ✓ Successfully processed");
                } else {
                    $failCount++;
                    $this->command->error("  ↳ ✗ Failed to process");
                }

            } catch (\Exception $e) {
                $this->command->error("  ↳ ✗ Error: " . $e->getMessage());
                $failCount++;
                Log::error('Error processing product', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage()
                ]);
            }

            // تأخير لتجنب حظر الطلبات
            $this->addDelay($index, $totalProducts);
        }

        // عرض النتائج النهائية
        $this->displayFinalSummary($successCount, $failCount, $products->count());
    }

    /**
     * عرض ملخص المعالجة
     */
    private function displayProcessingSummary($results)
    {
        $summary = [];
        
        if (!empty($results['sizes'])) {
            $summary[] = "Sizes: " . count($results['sizes']);
        }
        if (!empty($results['materials'])) {
            $summary[] = "Materials: " . count($results['materials']);
        }
        if (!empty($results['design_services'])) {
            $summary[] = "Design Services: " . count($results['design_services']);
        }
        if (!empty($results['printing_methods'])) {
            $summary[] = "Printing Methods: " . count($results['printing_methods']);
        }
        if (!empty($results['general_options'])) {
            $summary[] = "General Options: " . count($results['general_options']);
        }
        
        if (!empty($summary)) {
            $this->command->info("  ↳ Processed: " . implode(', ', $summary));
        }
    }

    /**
     * إضافة تأخير بين الطلبات
     */
    private function addDelay($currentIndex, $totalProducts)
    {
        // إضافة تأخير أطول كل 5 منتجات
        if (($currentIndex + 1) % 5 === 0 && ($currentIndex + 1) < $totalProducts) {
            $this->command->info("  ↳ Waiting 5 seconds...");
            sleep(5);
        } else {
            sleep(2);
        }
    }

    /**
     * عرض النتائج النهائية
     */
    private function displayFinalSummary($successCount, $failCount, $totalProducts)
    {
        $this->command->info("\n" . str_repeat('=', 50));
        $this->command->info("📊 PROCESSING COMPLETED");
        $this->command->info(str_repeat('=', 50));
        $this->command->info("Total Products: {$totalProducts}");
        $this->command->info("✅ Successfully Processed: {$successCount}");
        $this->command->info("❌ Failed: {$failCount}");
        $this->command->info(str_repeat('=', 50));
        
        if ($successCount > 0) {
            $this->command->info("🎉 Data has been successfully stored in appropriate tables!");
        }
    }

    /**
     * معالجة منتج واحد (للتجربة)
     */
    public function runForSingleProduct($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) {
            $this->command->error("Product not found: {$productId}");
            return;
        }

        $this->command->info("Processing single product: {$product->name}");
        
        try {
            $options = $this->processorService->extractDataFromHtml($product->url);
            
            if ($options) {
                $results = $this->processorService->processProductOptions($product->id, $options);
                $this->displayProcessingSummary($results);
                $this->command->info("✓ Product processed successfully");
            } else {
                $this->command->error("✗ No data extracted");
            }
        } catch (\Exception $e) {
            $this->command->error("✗ Error: " . $e->getMessage());
        }
    }
}