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

        $products = Product::whereNotNull('external_id')
            ->whereNotNull('url')
            ->limit(10) // تقليل العدد للاختبار
            ->get();

        $this->command->info('Found ' . $products->count() . ' products to process');

        $successCount = 0;
        $failCount = 0;

        foreach ($products as $index => $product) {
            $productNumber = $index + 1;
            $totalProducts = $products->count();

            $this->command->info("\n[$productNumber/$totalProducts] Processing: {$product->name}");

            try {
                // استخراج البيانات من صفحة المنتج
                $options = $this->processorService->extractDataFromHtml($product->url);

                if (!$options || !is_array($options)) {
                    $this->command->warn("  ↳ No data extracted from URL");
                    $failCount++;
                    continue;
                }

                $this->command->info("  ↳ Extracted " . count($options) . " options");

                // تحليل العلاقات
                $this->processorService->analyzeOptionRelationships($options);

                // بناء التركيبات الذكية أولاً
                $this->command->info("  ↳ Building smart combinations...");
                $smartCombinations = $this->processorService->buildSmartCombinations($options, 50);

                $this->command->info("  ↳ Built " . $smartCombinations->count() . " smart combinations");

                // تخزين التركيبات
                $this->storeSmartCombinations($product->id, $smartCombinations);

                // معالجة باقي البيانات
                $results = [
                    'smart_combinations' => $smartCombinations->count(),
                    'sizes' => [],
                    'materials' => [],
                    'design_services' => [],
                    'printing_methods' => [],
                    'print_locations' => [],
                    'general_options' => [],
                    'conditions' => [],
                    'quantity_tiers' => []
                ];

                // معالجة الخيارات حسب النوع
                foreach ($options as $option) {
                    $name = $option['name'] ?? '';

                    if (str_contains($name, 'المقاس') || str_contains($name, 'الحجم') || str_contains($name, 'Size')) {
                        $this->processorService->processSizeOption($product, $option, $results);
                    } elseif (str_contains($name, 'الكمية') || str_contains($name, 'عدد') || str_contains($name, 'Quantity')) {
                        $this->processorService->processQuantityOption($product, $option, $results);
                    } elseif (str_contains($name, 'الخامة') || str_contains($name, 'المادة') || str_contains($name, 'Material')) {
                        $this->processorService->processMaterialOption($product, $option, $results);
                    } elseif (str_contains($name, 'خدمة التصميم') || str_contains($name, 'تصميم') || str_contains($name, 'Design')) {
                        $this->processorService->processDesignServiceOption($product, $option, $results);
                    } elseif (str_contains($name, 'طريقة الطباعة') || str_contains($name, 'الطباعة') || str_contains($name, 'Printing')) {
                        $this->processorService->processPrintingMethodOption($product, $option, $results);
                    } elseif (str_contains($name, 'مكان الطباعة') || str_contains($name, 'موقع الطباعة') || str_contains($name, 'Print Location')) {
                        $this->processorService->processPrintLocationOption($product, $option, $results);
                    } else {
                        $this->processorService->processGeneralOption($product, $option, $results);
                    }
                }

                // عرض ملخص
                $this->displayProcessingSummary($results);

                // عرض أمثلة للتركيبات
                $this->displayCombinationExamples($smartCombinations);

                $successCount++;
                $this->command->info("  ↳ ✓ Successfully processed");
            } catch (\Exception $e) {
                $this->command->error("  ↳ ✗ Error: " . $e->getMessage());
                $this->command->error("  ↳ Stack trace: " . $e->getTraceAsString());
                $failCount++;
                Log::error('Error processing product', [
                    'product_id' => $product->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            // تأخير لتجنب حظر الطلبات
            $this->addDelay($index, $totalProducts);
        }

        // عرض النتائج النهائية
        $this->displayFinalSummary($successCount, $failCount, $products->count());
    }

    /**
     * تخزين التركيبات الذكية
     */
    private function storeSmartCombinations($productId, $combinations)
    {
        $product = Product::find($productId);
        if (!$product) return;

        $product->valid_combinations = $combinations->toArray();
        $product->combination_count = $combinations->count();
        $product->save();
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
        if (isset($results['smart_combinations'])) {
            $summary[] = "Smart Combinations: " . $results['smart_combinations'];
        }

        if (!empty($summary)) {
            $this->command->info("  ↳ Processed: " . implode(', ', $summary));
        }
    }

    /**
     * عرض أمثلة للتركيبات
     */
    private function displayCombinationExamples($combinations)
    {
        if ($combinations->count() > 0) {
            $examples = $combinations->take(2)->map(function ($combination, $index) {
                $options = collect($combination['combination'])->map(function ($value, $key) {
                    return "{$key}: {$value}";
                })->implode(', ');

                return "    #" . ($index + 1) . ": {$options}";
            })->implode("\n");

            $this->command->info("  ↳ Example combinations:");
            $this->command->info($examples);
        }
    }

    /**
     * إضافة تأخير بين الطلبات
     */
    private function addDelay($currentIndex, $totalProducts)
    {
        if (($currentIndex + 1) % 3 === 0 && ($currentIndex + 1) < $totalProducts) {
            sleep(3);
        } else {
            sleep(1);
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
    }
};
