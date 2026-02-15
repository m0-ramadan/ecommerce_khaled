<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\ProductOptionsAiService;

/**
 * ProductOptionsAiSeeder
 * 
 * ⭐ التخزين الكامل في product_options فقط
 * ⭐ الـ dependencies تُربط باستخدام external_option_id (ثابت)
 * ⭐ لا يُستخدم أي موديل آخر
 */
class ProductOptionsAiSeeder extends Seeder
{
    protected ProductOptionsAiService $service;
    protected bool $useAi = true;
    protected int $maxPages = 0;
    protected int $productsPerPage = 32;
    protected int $gcEvery = 10;

    public function __construct()
    {
        $this->service = new ProductOptionsAiService();
    }

    public function run(): void
    {
        $this->info('🚀 ProductOptionsAiSeeder started');
        $this->info('AI mode: ' . ($this->useAi ? 'ON' : 'OFF'));
        $this->info('Max pages: ' . ($this->maxPages ?: 'unlimited'));

        $progress = $this->service->loadProgress();
        $nextCursor = $progress['cursor_url'] ?? null;
        $startPage = ($progress['pages_processed'] ?? 0) + 1;

        if ($nextCursor) {
            $this->info("▶ Resuming from page {$startPage}");
        } else {
            $this->info('▶ Starting from page 1');
        }

        $totals = [
            'pages'        => 0,
            'products'     => 0,
            'success'      => 0,
            'fail'         => 0,
            'options_rows' => 0,
            'dependencies' => 0,
        ];

        $startedAt = now();
        $currentPage = $startPage - 1;

        do {
            $currentPage++;
            $this->separator("Page {$currentPage}");

            $page = $this->service->fetchProductsPage($nextCursor, $this->productsPerPage);

            if (!$page) {
                $this->error("❌ Failed to fetch page {$currentPage}. Stopping.");
                break;
            }

            $products = $page['data'];
            $nextCursor = $page['next_cursor_url'] ?? null;
            $hasMore = !empty($nextCursor);

            $this->info('  Fetched ' . count($products) . ' products | more: ' . ($hasMore ? 'yes' : 'no'));

            if (empty($products)) {
                $this->info('  No products on this page – done.');
                break;
            }

            $pageResult = $this->processPage($products);

            $totals['pages']++;
            $totals['products']     += $pageResult['total'];
            $totals['success']      += $pageResult['success'];
            $totals['fail']         += $pageResult['fail'];
            $totals['options_rows'] += $pageResult['options_rows'];
            $totals['dependencies'] += $pageResult['dependencies'];

            $this->info("  ✅ Page summary: success={$pageResult['success']} fail={$pageResult['fail']} rows={$pageResult['options_rows']} deps={$pageResult['dependencies']}");

            if ($hasMore) {
                $this->service->saveProgress($nextCursor, $totals['products'], $totals['pages']);
            }

            if ($this->maxPages > 0 && $currentPage >= $this->maxPages) {
                $this->info("⏸ Reached maxPages={$this->maxPages}. Stopping.");
                break;
            }

            if ($hasMore) {
                sleep(2);
            }
        } while ($hasMore);

        if (!$hasMore || ($this->maxPages > 0 && $currentPage >= $this->maxPages)) {
            $this->service->resetProgressFile();
        }

        $elapsed = $startedAt->diffForHumans(now(), true);
        $this->printFinalSummary($totals, $elapsed);
    }

    private function processPage(array $productsData): array
    {
        $result = ['total' => count($productsData), 'success' => 0, 'fail' => 0, 'options_rows' => 0, 'dependencies' => 0];
        $idx = 0;

        foreach ($productsData as $productData) {
            $idx++;
            $name = $productData['name'] ?? 'Unknown';
            $externalId = $productData['id'] ?? null;
            $url = $productData['url'] ?? null;

            $this->info("\n  [{$idx}/{$result['total']}] {$name}");

            $product = Product::where('external_id', $externalId)->first();
            if (!$product) {
                $this->warn("     ⚠ Not found in DB (external_id={$externalId}) – skipped");
                $result['fail']++;
                $this->maybeGc($idx);
                continue;
            }

            if (!$url) {
                $this->warn('     ⚠ No URL – skipped');
                $result['fail']++;
                $this->maybeGc($idx);
                continue;
            }

            $options = $this->service->extractOptionsFromHtml($url);
            if (!$options) {
                $this->warn('     ⚠ No options extracted – skipped');
                $result['fail']++;
                $this->maybeGc($idx);
                continue;
            }

            $this->info('     Options found: ' . count($options));

            try {
                if ($this->useAi) {
                    $categoryName = null;
                    if (method_exists($product, 'category') && $product->category) {
                        $categoryName = $product->category->name ?? null;
                    }

                    $summary = $this->service->processOptionsWithAi(
                        $product->id,
                        $options,
                        $product->name,
                        $categoryName
                    );
                } else {
                    $summary = $this->service->processOptionsWithoutAi($product->id, $options);
                }

                if ($summary && empty($summary['errors'])) {
                    $result['success']++;
                    $result['options_rows'] += (int)($summary['total_rows'] ?? 0);
                    $result['dependencies'] += (int)($summary['dependencies'] ?? 0);
                    $this->info('     ✅ Stored: rows=' . ($summary['total_rows'] ?? '?') . ' deps=' . ($summary['dependencies'] ?? '?'));
                } else {
                    $errors = $summary['errors'] ?? ['unknown error'];
                    $this->warn('     ❌ Processing failed: ' . implode(' | ', array_slice($errors, 0, 2)));
                    $result['fail']++;
                }
            } catch (\Throwable $e) {
                $this->error('     ❌ Exception: ' . $e->getMessage());
                Log::error('ProductOptionsAiSeeder product error', ['product_id' => $product->id, 'error' => $e->getMessage()]);
                $result['fail']++;
            }

            $this->maybeGc($idx);
            sleep(1);
        }

        return $result;
    }

    private function maybeGc(int $idx): void
    {
        if ($idx % $this->gcEvery === 0) {
            gc_collect_cycles();
            DB::purge();
        }
    }

    private function printFinalSummary(array $totals, string $elapsed): void
    {
        $this->separator('DONE');
        $this->info("📄 Pages processed : {$totals['pages']}");
        $this->info("📦 Products fetched : {$totals['products']}");
        $this->info("✅ Success          : {$totals['success']}");
        $this->info("❌ Failed           : {$totals['fail']}");
        $this->info("🗂  Option rows saved: {$totals['options_rows']}");
        $this->info("🔗 Dependencies set : {$totals['dependencies']}");
        $this->info("⏱  Elapsed          : {$elapsed}");
        $this->info("💾 Peak memory      : " . $this->formatBytes(memory_get_peak_usage(true)));
        $this->separator();
    }

    private function separator(string $label = ''): void
    {
        $line = str_repeat('─', 60);
        $this->info($label ? "── {$label} " . str_repeat('─', max(0, 58 - strlen($label))) : $line);
    }

    private function info(string $msg): void
    {
        if (isset($this->command)) {
            $this->command->info($msg);
        } else {
            echo $msg . PHP_EOL;
        }
    }

    private function warn(string $msg): void
    {
        if (isset($this->command)) {
            $this->command->warn($msg);
        } else {
            echo '[WARN] ' . $msg . PHP_EOL;
        }
    }

    private function error(string $msg): void
    {
        if (isset($this->command)) {
            $this->command->error($msg);
        } else {
            echo '[ERR] ' . $msg . PHP_EOL;
        }
    }

    private function formatBytes(int $bytes): string
    {
        foreach (['B', 'KB', 'MB', 'GB'] as $unit) {
            if ($bytes < 1024) {
                return round($bytes, 2) . ' ' . $unit;
            }
            $bytes /= 1024;
        }
        return round($bytes, 2) . ' TB';
    }

    public function resetProgress(): void
    {
        $this->service->resetProgressFile();
        $this->info('🔄 Progress reset – will start from page 1 on next run');
    }

    public function testMode(): void
    {
        $this->useAi = false;
        $this->maxPages = 2;
        $this->productsPerPage = 5;
        $this->info('🧪 TEST MODE – 2 pages × 5 products, AI off');
        $this->run();
    }

    public function manualMode(): void
    {
        $this->useAi = false;
        $this->info('🛠  MANUAL MODE – keyword-only categorisation');
        $this->run();
    }

    public function setMaxPages(int $pages): void
    {
        $this->maxPages = $pages;
        $this->run();
    }
}
