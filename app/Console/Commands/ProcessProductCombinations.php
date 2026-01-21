<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SallaDataProcessorService;
use App\Models\Product;

class ProcessProductCombinations extends Command
{
    protected $signature = 'products:process-combinations 
                            {productId? : ID المنتج} 
                            {--all : معالجة جميع المنتجات} 
                            {--limit=50 : حد المنتجات}';

    protected $description = 'معالجة وبناء التركيبات الصالحة للمنتجات';

    protected $processorService;

    public function __construct()
    {
        parent::__construct();
        $this->processorService = new SallaDataProcessorService();
    }

    public function handle()
    {
        if ($this->option('all')) {
            $this->processAllProducts();
        } elseif ($this->argument('productId')) {
            $this->processSingleProduct($this->argument('productId'));
        } else {
            $this->error('الرجاء تحديد إما معرف المنتج أو استخدام --all');
        }
    }

    private function processSingleProduct($productId)
    {
        $product = Product::find($productId);

        if (!$product) {
            $this->error("المنتج غير موجود: {$productId}");
            return;
        }

        $this->info("⚙️ معالجة المنتج: {$product->name}");

        $options = $this->processorService->extractDataFromHtml($product->url);

        if (!$options) {
            $this->error("❌ فشل استخراج البيانات");
            return;
        }

        $this->info("📊 استخراج " . count($options) . " خيار");

        $results = $this->processorService->processProductOptions($product->id, $options);

        if ($results) {
            $combinations = $this->processorService->getStoredCombinations($productId);

            $this->info("✅ تم بنجاح بناء {$combinations->count()} تركيب صالح");

            // عرض أمثلة
            if ($combinations->count() > 0) {
                $this->info("\n🎯 أمثلة للتركيبات:");
                foreach ($combinations->take(5) as $index => $combo) {
                    $this->info("\n  التركيبة #" . ($index + 1) . ":");
                    foreach ($combo['combination'] as $key => $value) {
                        $this->info("    {$key}: {$value}");
                    }
                }
            }
        } else {
            $this->error("❌ فشل معالجة المنتج");
        }
    }

    private function processAllProducts()
    {
        $limit = $this->option('limit');

        $products = Product::whereNotNull('url')
            ->whereNull('valid_combinations')
            ->limit($limit)
            ->get();

        $this->info("🎯 معالجة {$products->count()} منتج");

        $successCount = 0;

        foreach ($products as $index => $product) {
            $this->info("\n" . ($index + 1) . "/{$products->count()}: {$product->name}");

            try {
                $options = $this->processorService->extractDataFromHtml($product->url);

                if ($options) {
                    $results = $this->processorService->processProductOptions($product->id, $options);

                    if ($results) {
                        $successCount++;
                        $comboCount = $results['valid_combinations'] ?? 0;
                        $this->info("  ✅ تم بناء {$comboCount} تركيب");
                    }
                }

                sleep(2); // تأخير بين الطلبات

            } catch (\Exception $e) {
                $this->error("  ❌ خطأ: " . $e->getMessage());
            }
        }

        $this->info("\n📊 تم الانتهاء: {$successCount}/{$products->count()} تمت معالجتهم بنجاح");
    }
}
