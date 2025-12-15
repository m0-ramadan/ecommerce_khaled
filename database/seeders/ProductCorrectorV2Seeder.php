<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ProductCorrectorV2Seeder extends Seeder
{
    private $apiKey;
    private $model = 'deepseek-chat'; // نموذج DeepSeek
    private $baseUrl = 'https://api.deepseek.com/v1/chat/completions';
    private $maxRetries = 3;
    private $retryDelay = 5; // ثواني

    /**
     * تشغيل سيدي (Seeder) قاعدة البيانات.
     */
    public function run()
    {
        $this->apiKey = env('DEEPSEEK_API_KEY', 'sk-97536bc2a134431aa194412221882ca2');

        if (!$this->apiKey) {
            $this->command->error('❌ DEEPSEEK_API_KEY is missing');
            return;
        }

        // 1. جلب جميع المنتجات من قاعدة البيانات
        $products = Product::all();

        $this->command->info("Starting correction and enhancement for {$products->count()} products using DeepSeek...");
        
        $updatedCount = 0;
        $failedCount = 0;
        $skippedCount = 0;

        foreach ($products as $index => $product) {
            $this->command->info("Processing product " . ($index + 1) . " of {$products->count()} - ID: {$product->id}");
            
            // تحقق إذا كان المنتج يحتاج بالفعل تحديث
            if (!$this->needsUpdate($product)) {
                $this->command->info("Skipping product ID: {$product->id} - already updated");
                $skippedCount++;
                continue;
            }

            // معالجة المنتج كاملاً في request واحد
            $result = $this->processProduct($product);
            
            if ($result === true) {
                $updatedCount++;
                $this->command->info("✅ Product ID: {$product->id} updated successfully.");
            } elseif ($result === false) {
                $failedCount++;
                $this->command->error("❌ Failed to update product ID: {$product->id}");
            } else {
                $skippedCount++;
                $this->command->info("⏭️ Skipping product ID: {$product->id}");
            }

            // تأخير بين المنتجات لتجنب rate limits
            if (($index + 1) % 5 === 0) {
                $this->command->info("Waiting 5 seconds to avoid rate limits...");
                sleep(5);
            } else {
                sleep(2);
            }
        }

        $this->command->info("===============================================");
        $this->command->info("📊 Summary:");
        $this->command->info("✅ Successfully updated: {$updatedCount} products");
        $this->command->info("❌ Failed to update: {$failedCount} products");
        $this->command->info("⏭️ Skipped: {$skippedCount} products");
        $this->command->info("--- Product data correction and enhancement completed. ---");
    }

    /**
     * تحقق إذا كان المنتج يحتاج تحديث
     */
    private function needsUpdate(Product $product): bool
    {
        // تحقق من SKU - إذا كان يحتوي على TALA- فهذا يعني تم تحديثه
        if (Str::startsWith($product->sku ?? '', 'TALA-')) {
            return false;
        }
        
        // تحقق إذا كانت البيانات فارغة أو لم يتم تحديثها بعد
        if (empty($product->description) || empty($product->meta_title)) {
            return true;
        }
        
        return true;
    }

    /**
     * معالجة المنتج كاملاً في request واحد
     */
    private function processProduct(Product $product)
    {
        try {
            // جلب جميع التعديلات في request واحد
            $productData = $this->getAllProductUpdates($product);
            
            if ($productData && !empty($productData)) {
                // توليد SKU
                $productData['sku'] = $this->generateStandardSku($product);
                
                // تطبيق التحديثات
                $product->update($productData);
                return true;
            }
            
            return false;
            
        } catch (\Exception $e) {
            $this->command->error("  ↳ Error processing product ID: {$product->id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * جلب جميع تحديثات المنتج في request واحد
     */
    private function getAllProductUpdates(Product $product): ?array
    {
        // تنظيف البيانات قبل إرسالها
        $productName = $this->cleanUtf8($product->name ?? '');
        $categoryName = $product->category ? $this->cleanUtf8($product->category->name) : 'غير مصنف';
        $currentDescription = $this->cleanUtf8(substr($product->description ?? '', 0, 500));
        
        $prompt = "أنت مساعد متخصص في كتابة محتوى المنتجات التجارية لمصنع 'تلا الجزيرة'.\n\n";
        $prompt .= "المنتج: {$productName}\n";
        $prompt .= "الفئة: {$categoryName}\n\n";
        $prompt .= "الوصف الحالي (للإلهام فقط): {$currentDescription}\n\n";
        $prompt .= "أريد منك إنشاء المحتوى التالي:\n\n";
        $prompt .= "1. وصف HTML للمنتج:\n";
        $prompt .= "   - استخدم تنسيق HTML بسيط (h2, h3, p, ul, li)\n";
        $prompt .= "   - أضف مميزات المنتج\n";
        $prompt .= "   - لا تذكر أي شركات منافسة\n";
        $prompt .= "   - أضف في النهاية: 'من إنتاج مصنع تلا الجزيرة - جودة تضمنها خبرة سنوات'\n\n";
        $prompt .= "2. بيانات SEO:\n";
        $prompt .= "   - عنوان فرعي جذاب (subtitle)\n";
        $prompt .= "   - عنوان ميتا (meta_title) بين 50-60 حرف، يجب أن يحتوي على 'تلا الجزيرة'\n";
        $prompt .= "   - وصف ميتا (meta_description) بين 150-160 حرف، يجب أن يحتوي على 'تلا الجزيرة'\n";
        $prompt .= "   - كلمات مفتاحية مفصولة بفاصلة، يجب أن تحتوي على 'تلا الجزيرة'\n\n";
        $prompt .= "أعد الرد بصيغة JSON فقط بالشكل التالي:\n";
        $prompt .= "{\n";
        $prompt .= "  \"description\": \"وصف HTML كامل\",\n";
        $prompt .= "  \"subtitle\": \"عنوان فرعي\",\n";
        $prompt .= "  \"meta_title\": \"عنوان ميتا\",\n";
        $prompt .= "  \"meta_description\": \"وصف ميتا\",\n";
        $prompt .= "  \"meta_keywords\": \"كلمات مفتاحية\"\n";
        $prompt .= "}\n\n";
        $prompt .= "ملاحظة: تأكد من أن جميع النصوص باللغة العربية وبجودة عالية.";

        for ($attempt = 1; $attempt <= $this->maxRetries; $attempt++) {
            try {
                $response = Http::timeout(60)->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])->post($this->baseUrl, [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.4,
                    'max_tokens' => 2000,
                    'response_format' => ['type' => 'json_object'] // لضمان استجابة JSON
                ]);

                if (!$response->successful()) {
                    throw new \Exception('HTTP request failed: ' . $response->status() . ' - ' . $response->body());
                }

                $data = $response->json();
                
                if (isset($data['error'])) {
                    if ($attempt < $this->maxRetries) {
                        $this->command->warn("  ↳ Attempt {$attempt} failed, retrying in {$this->retryDelay} seconds...");
                        sleep($this->retryDelay);
                        continue;
                    }
                    throw new \Exception($data['error']['message'] ?? 'API error');
                }

                $content = $data['choices'][0]['message']['content'] ?? null;
                
                if ($content) {
                    // تنظيف الـ JSON
                    $content = $this->cleanUtf8($content);
                    $content = str_replace(['```json', '```'], '', $content);
                    
                    $productData = json_decode(trim($content), true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        // تنظيف وتأكيد وجود البيانات المطلوبة
                        $result = [];
                        
                        // وصف المنتج
                        if (isset($productData['description'])) {
                            $description = $this->cleanHtmlDescription($productData['description']);
                            // تأكد من وجود فقرة تلا الجزيرة
                            if (!Str::contains($description, 'تلا الجزيرة')) {
                                $description .= "\n\n<p><strong>من إنتاج مصنع تلا الجزيرة</strong> - جودة تضمنها خبرة سنوات في مجال الصناعة والتصنيع، نلتزم بأعلى معايير الجودة والتميز.</p>";
                            }
                            $result['description'] = $description;
                        } else {
                            $result['description'] = $this->generateDefaultDescription($product);
                        }
                        
                        // عنوان فرعي
                        if (isset($productData['subtitle'])) {
                            $result['subtitle'] = $this->cleanUtf8($productData['subtitle']);
                        } else {
                            $result['subtitle'] = 'منتج عالي الجودة من مصنع تلا الجزيرة';
                        }
                        
                        // عنوان ميتا
                        if (isset($productData['meta_title'])) {
                            $metaTitle = $this->cleanUtf8($productData['meta_title']);
                            if (!Str::contains($metaTitle, 'تلا الجزيرة')) {
                                $metaTitle .= ' | تلا الجزيرة';
                            }
                            // تأكد من الطول المناسب
                            if (mb_strlen($metaTitle) > 60) {
                                $metaTitle = mb_substr($metaTitle, 0, 57) . '...';
                            }
                            $result['meta_title'] = $metaTitle;
                        } else {
                            $cleanName = $this->cleanUtf8($product->name ?? '');
                            $result['meta_title'] = substr($cleanName, 0, 40) . ' | تلا الجزيرة';
                        }
                        
                        // وصف ميتا
                        if (isset($productData['meta_description'])) {
                            $metaDesc = $this->cleanUtf8($productData['meta_description']);
                            if (!Str::contains($metaDesc, 'تلا الجزيرة')) {
                                $metaDesc .= ' من مصنع تلا الجزيرة.';
                            }
                            // تأكد من الطول المناسب
                            if (mb_strlen($metaDesc) > 160) {
                                $metaDesc = mb_substr($metaDesc, 0, 157) . '...';
                            }
                            $result['meta_description'] = $metaDesc;
                        } else {
                            $cleanName = $this->cleanUtf8($product->name ?? '');
                            $result['meta_description'] = 'اشتري ' . substr($cleanName, 0, 100) . ' من مصنع تلا الجزيرة. جودة عالية وأسعار منافسة وتوصيل سريع.';
                        }
                        
                        // كلمات مفتاحية
                        if (isset($productData['meta_keywords'])) {
                            $keywords = $this->cleanUtf8($productData['meta_keywords']);
                            if (!Str::contains($keywords, 'تلا الجزيرة')) {
                                $keywords .= ', تلا الجزيرة, مصنع تلا الجزيرة';
                            }
                            $result['meta_keywords'] = $keywords;
                        } else {
                            $cleanName = $this->cleanUtf8($product->name ?? '');
                            $result['meta_keywords'] = $cleanName . ', تلا الجزيرة, مصنع تلا الجزيرة, جودة عالية';
                        }
                        
                        return $result;
                    } else {
                        $this->command->warn("  ↳ JSON parsing failed, using default data");
                        return $this->generateDefaultProductData($product);
                    }
                }
                
                break;
            } catch (\Exception $e) {
                if ($attempt < $this->maxRetries) {
                    $this->command->warn("  ↳ Attempt {$attempt} failed: " . $e->getMessage());
                    sleep($this->retryDelay);
                } else {
                    throw $e;
                }
            }
        }
        
        // استخدم البيانات الافتراضية إذا فشل كل شيء
        return $this->generateDefaultProductData($product);
    }

    /**
     * تنظيف UTF-8
     */
    private function cleanUtf8(string $text): string
    {
        // التحقق مما إذا كان النص يحتوي على UTF-8 صحيح
        if (!mb_check_encoding($text, 'UTF-8')) {
            // محاولة تحويل النص إلى UTF-8
            $text = mb_convert_encoding($text, 'UTF-8', 'UTF-8');
        }
        
        // إزالة الأحرف غير الصالحة في UTF-8
        $text = preg_replace('/[^\x{0000}-\x{FFFF}]/u', '', $text);
        
        // إزالة أي أحرف تحكم غير ضرورية
        $text = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
        
        // إزالة علامات BOM إذا وجدت
        $text = preg_replace('/^\xEF\xBB\xBF/', '', $text);
        
        // إزالة المسافات الزائدة
        $text = trim(preg_replace('/\s+/', ' ', $text));
        
        return $text;
    }

    /**
     * تنظيف وصف HTML
     */
    private function cleanHtmlDescription(string $html): string
    {
        $html = $this->cleanUtf8($html);
        
        $allowedTags = '<h2><h3><h4><p><br><strong><b><em><i><u><ul><ol><li>';
        $html = strip_tags($html, $allowedTags);
        $html = preg_replace('/<(\w+)[^>]*>/', '<$1>', $html);
        $html = preg_replace('/\s+/', ' ', $html);
        $html = preg_replace('/<(\w+)>\s*<\/\1>/', '', $html);
        
        return trim($html);
    }

    /**
     * توليد SKU موحد
     */
    private function generateStandardSku(Product $product): string
    {
        $categoryCode = 'PR';
        
        if ($product->category) {
            $categoryName = $product->category->name;
            
            $categoryMap = [
                'طباعة' => 'PR',
                'بوكس' => 'BX',
                'استاند' => 'ST',
                'شماسية' => 'SH',
                'كرتون' => 'KT',
                'ورق' => 'WR',
                'بلاستيك' => 'PL',
                'خدمة' => 'SV',
                'تغليف' => 'PK',
                'دعاية' => 'AD',
                'إعلان' => 'AD',
            ];
            
            foreach ($categoryMap as $key => $code) {
                if (Str::contains($categoryName, $key)) {
                    $categoryCode = $code;
                    break;
                }
            }
        }
        
        $sequence = str_pad($product->id, 6, '0', STR_PAD_LEFT);
        return "TALA-{$categoryCode}-{$sequence}";
    }

    /**
     * إنشاء بيانات المنتج الافتراضية
     */
    private function generateDefaultProductData(Product $product): array
    {
        $productName = $this->cleanUtf8($product->name ?? '');
        $shortName = mb_substr($productName, 0, 30);
        
        return [
            'description' => $this->generateDefaultDescription($product),
            'subtitle' => 'منتج عالي الجودة من مصنع تلا الجزيرة',
            'meta_title' => $shortName . ' | تلا الجزيرة',
            'meta_description' => 'اشتري ' . $shortName . ' من مصنع تلا الجزيرة. جودة عالية وأسعار منافسة وتوصيل سريع.',
            'meta_keywords' => $productName . ', تلا الجزيرة, مصنع تلا الجزيرة, جودة عالية'
        ];
    }

    /**
     * إنشاء وصف افتراضي
     */
    private function generateDefaultDescription(Product $product): string
    {
        $productName = $this->cleanUtf8($product->name ?? '');
        $categoryName = $product->category ? $this->cleanUtf8($product->category->name) : 'منتجات عامة';
        
        $description = "<h2>{$productName}</h2>\n";
        $description .= "<p>نقدم لكم {$productName} من فئة {$categoryName} بجودة عالية وأفضل الأسعار.</p>\n";
        $description .= "<h3>مميزات المنتج:</h3>\n";
        $description .= "<ul>\n";
        $description .= "<li>جودة عالية تضمن الرضا التام</li>\n";
        $description .= "<li>تصميم متين وعملي</li>\n";
        $description .= "<li>أسعار منافسة وجودة لا مثيل لها</li>\n";
        $description .= "<li>مناسب للاستخدام المتكرر والطويل</li>\n";
        $description .= "</ul>\n";
        $description .= "<p><strong>من إنتاج مصنع تلا الجزيرة</strong> - جودة تضمنها خبرة سنوات في مجال الصناعة والتصنيع، نلتزم بأعلى معايير الجودة والتميز.</p>";
        
        return $description;
    }

    /**
     * طريقة لمعالجة دفعات صغيرة مع إعادة المحاولة
     */
    public function runSmallBatch($batchSize = 10)
    {
        $this->apiKey = env('DEEPSEEK_API_KEY', 'sk-97536bc2a134431aa194412221882ca2');

        if (!$this->apiKey) {
            $this->command->error('❌ DEEPSEEK_API_KEY is missing');
            return;
        }

        $products = Product::where(function($query) {
            $query->whereNull('sku')
                  ->orWhereNotLike('sku', 'TALA-%');
        })->limit($batchSize)->get();

        $this->command->info("Processing small batch of {$products->count()} products...");
        
        foreach ($products as $index => $product) {
            $this->command->info("Product " . ($index + 1) . " of {$products->count()} - ID: {$product->id}");
            
            try {
                // استخدم الطريقة الرئيسية للمعالجة
                $result = $this->processProduct($product);
                
                if ($result) {
                    $this->command->info("✅ Updated product ID: {$product->id}");
                } else {
                    $this->command->error("❌ Failed to update product ID: {$product->id}");
                }
                
                sleep(3); // تأخير بين المنتجات
            } catch (\Exception $e) {
                $this->command->error("❌ Error: " . $e->getMessage());
            }
        }
    }
}