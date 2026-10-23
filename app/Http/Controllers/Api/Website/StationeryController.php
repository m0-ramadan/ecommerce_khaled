<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\StationeryPrice;
use App\Models\StationeryDeliveryArea;
use App\Models\StationeryService;
use App\Services\StationeryPricingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StationeryController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب قائمة الأسعار الحالية وإعدادات القرطاسية
     */
    public function getPrices()
    {
        $prices = StationeryPrice::where('is_active', true)
            ->whereIn('paper_size', StationeryPrice::defaultSizes())
            ->orderBy('sort_order')
            ->get();

        $product = Product::where('slug', 'stationery-print-service')->first();

        return response()->json([
            'status' => true,
            'message' => 'تم جلب أسعار خدمات القرطاسية والطباعة بنجاح',
            'data' => [
                'product_id' => $product ? $product->id : 366,
                'sizes' => StationeryPrice::defaultSizes(),
                'size_dimensions' => StationeryPrice::sizeDimensions(),
                'paper_types' => StationeryPrice::paperTypes(),
                'prices' => $prices,
                'services' => StationeryService::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get(['id', 'key', 'name', 'price']),
                'pricing_rules' => [
                    // كل أسعار A1 إلى A5 تُدار يدوياً من الداشبورد بدون معادلات.
                    'A1' => ['minimum_total' => 500, 'minimum_total_double' => 900, 'minimum_sheets' => 100, 'color_extra' => 100],
                    'A2' => ['minimum_total' => 400, 'minimum_total_double' => 750, 'minimum_sheets' => 100, 'color_extra' => 100],
                ],
                'min_order_price' => 0.50,
            ],
        ]);
    }

    public function getDeliveryOptions()
    {
        $riyadh = StationeryDeliveryArea::riyadh();

        return response()->json([
            'status' => true,
            'data' => [
                'pickup' => [
                    'id' => 'pickup',
                    'name' => 'استلام من الفرع',
                    'price' => 0,
                ],
                'areas' => [
                    [
                        'id' => $riyadh->id,
                        'name' => StationeryDeliveryArea::RIYADH_NAME,
                        'price' => StationeryDeliveryArea::RIYADH_PRICE,
                    ],
                ],
                'delivery_note' => 'التوصيل داخل الرياض فقط — 20 ريال لأي مكان',
            ],
        ]);
    }

    /**
     * رفع ملف للطباعة (مستند PDF أو Word أو صورة)
     */
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:30720', // حتى 30MB
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mimeType = $file->getMimeType();
        $sizeBytes = $file->getSize();

        // تحديد ما إذا كان صورة أو مستند
        $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'heic', 'heif', 'webp']) || str_starts_with($mimeType, 'image/');
        $isPdf = $extension === 'pdf' || $mimeType === 'application/pdf';

        // تخزين الملف
        $folder = 'stationery_uploads/' . date('Y/m');
        $filename = Str::random(24) . '.' . $extension;
        $path = $file->storeAs($folder, $filename, 'public');
        $fileUrl = asset('storage/' . $path);

        // حساب عدد الصفحات التقريبي
        $pagesCount = 1;
        if ($isPdf) {
            $pagesCount = $this->countPdfPages(storage_path('app/public/' . $path));
        }

        return response()->json([
            'status' => true,
            'message' => 'تم رفع الملف بنجاح',
            'data' => [
                'file_path' => $path,
                'file_url' => $fileUrl,
                'original_name' => $originalName,
                'extension' => $extension,
                'is_image' => $isImage,
                'is_pdf' => $isPdf,
                'size_formatted' => $this->formatBytes($sizeBytes),
                'pages_count' => max(1, $pagesCount),
            ],
        ]);
    }

    /**
     * رفع ملف كبير على أجزاء (chunks) لتجاوز حد حجم الطلب على السيرفر (1MB)
     */
    public function uploadChunk(Request $request)
    {
        $validated = $request->validate([
            'upload_id' => ['required', 'string', 'regex:/^[A-Za-z0-9_-]{8,64}$/'],
            'chunk_index' => ['required', 'integer', 'min:0'],
            'total_chunks' => ['required', 'integer', 'min:1', 'max:2000'],
            'original_name' => ['required', 'string', 'max:255'],
            'chunk' => ['required', 'file', 'max:1024'], // 1MB كحد أقصى لكل جزء
        ]);

        $uploadId = $validated['upload_id'];
        $chunkIndex = (int) $validated['chunk_index'];
        $totalChunks = (int) $validated['total_chunks'];
        $originalName = $validated['original_name'];

        if ($chunkIndex >= $totalChunks) {
            return response()->json([
                'status' => false,
                'message' => 'رقم الجزء غير صالح',
            ], 422);
        }

        if ($chunkIndex === 0) {
            $this->cleanupStaleStationeryChunks();
        }

        $chunkDir = storage_path('app/stationery_chunks/' . $uploadId);
        File::ensureDirectoryExists($chunkDir);

        $request->file('chunk')->move($chunkDir, $chunkIndex . '.part');

        if ($chunkIndex < $totalChunks - 1) {
            return response()->json([
                'status' => true,
                'message' => 'تم استلام جزء من الملف',
                'completed' => false,
            ]);
        }

        // الجزء الأخير: تجميع الملف ثم التحقق منه وحفظه
        $mergedPath = storage_path('app/stationery_chunks/' . $uploadId . '.merged');

        try {
            for ($i = 0; $i < $totalChunks; $i++) {
                if (!is_file($chunkDir . '/' . $i . '.part')) {
                    return response()->json([
                        'status' => false,
                        'message' => 'أجزاء الملف غير مكتملة، يرجى إعادة المحاولة',
                    ], 422);
                }
            }

            $out = fopen($mergedPath, 'wb');
            if ($out === false) {
                throw new \RuntimeException('فشل تجهيز الملف');
            }

            for ($i = 0; $i < $totalChunks; $i++) {
                $in = fopen($chunkDir . '/' . $i . '.part', 'rb');
                if ($in === false) {
                    fclose($out);
                    throw new \RuntimeException('فشل تجهيز الملف');
                }
                stream_copy_to_stream($in, $out);
                fclose($in);
            }
            fclose($out);

            $sizeBytes = filesize($mergedPath) ?: 0;
            if ($sizeBytes <= 0) {
                throw new \RuntimeException('الملف فارغ');
            }

            if ($sizeBytes > 30 * 1024 * 1024) {
                return response()->json([
                    'status' => false,
                    'message' => 'حجم الملف أكبر من الحد المسموح (30 ميجابايت)',
                ], 422);
            }

            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'heic', 'heif', 'webp'];
            if (!in_array($extension, $allowedExtensions, true)) {
                return response()->json([
                    'status' => false,
                    'message' => 'نوع الملف غير مدعوم',
                ], 422);
            }

            $mimeType = File::mimeType($mergedPath) ?: 'application/octet-stream';
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'heic', 'heif', 'webp'], true)
                || str_starts_with($mimeType, 'image/');
            $isPdf = $extension === 'pdf' || $mimeType === 'application/pdf';

            $folder = 'stationery_uploads/' . date('Y/m');
            $filename = Str::random(24) . '.' . $extension;
            $path = $folder . '/' . $filename;

            Storage::disk('public')->makeDirectory($folder);
            $stream = fopen($mergedPath, 'rb');
            if ($stream === false) {
                throw new \RuntimeException('فشل حفظ الملف');
            }
            Storage::disk('public')->writeStream($path, $stream);
            if (is_resource($stream)) {
                fclose($stream);
            }

            $pagesCount = 1;
            if ($isPdf) {
                $pagesCount = $this->countPdfPages(storage_path('app/public/' . $path));
            }

            return response()->json([
                'status' => true,
                'message' => 'تم رفع الملف بنجاح',
                'completed' => true,
                'data' => [
                    'file_path' => $path,
                    'file_url' => asset('storage/' . $path),
                    'original_name' => $originalName,
                    'extension' => $extension,
                    'is_image' => $isImage,
                    'is_pdf' => $isPdf,
                    'size_formatted' => $this->formatBytes($sizeBytes),
                    'pages_count' => max(1, $pagesCount),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء تجهيز الملف المرفوع، يرجى المحاولة ثانية',
            ], 500);
        } finally {
            File::deleteDirectory($chunkDir);
            if (is_file($mergedPath)) {
                File::delete($mergedPath);
            }
        }
    }

    /**
     * حذف الأجزاء المؤقتة القديمة (أقدم من 6 ساعات)
     */
    private function cleanupStaleStationeryChunks(): void
    {
        $baseDir = storage_path('app/stationery_chunks');
        if (!is_dir($baseDir)) {
            return;
        }

        $expiry = time() - (6 * 60 * 60);

        foreach (glob($baseDir . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
            if (@filemtime($dir) < $expiry) {
                File::deleteDirectory($dir);
            }
        }

        foreach (glob($baseDir . '/*.merged') ?: [] as $file) {
            if (@filemtime($file) < $expiry) {
                @unlink($file);
            }
        }
    }

    /**
     * إضافة طلب القرطاسية / الطباعة إلى السلة
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'files' => 'required|array|min:1',
            'files.*.file_url' => 'required|string',
            'files.*.file_name' => 'required|string',
            'files.*.service_type' => 'required|in:document,photo',
            'files.*.paper_size' => 'required|string',
            'files.*.paper_type' => 'required|string',
            'files.*.color_type' => 'required|in:bw,color',
            'files.*.pages_count' => 'required|integer|min:1',
            'files.*.copies' => 'required|integer|min:1',
            'files.*.orientation' => 'nullable|in:portrait,landscape',
            'files.*.side_type' => 'nullable|in:single,double',
            'files.*.services' => 'nullable|array',
            'files.*.services.*' => 'string|max:50',
            'note' => 'nullable|string|max:1000',
        ]);

        // الخدمات المدفوعة الإضافية تُختار لكل ملف على حدة، وتُحتسب لكل نسخة.
        $requestedServices = collect($request->input('files', []))
            ->flatMap(fn ($file) => (array) ($file['services'] ?? []))
            ->filter()
            ->unique()
            ->values();

        $activeServices = StationeryService::where('is_active', true)
            ->whereIn('key', $requestedServices)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('key');

        if ($requestedServices->count() !== $activeServices->count()) {
            return response()->json([
                'status' => false,
                'message' => 'إحدى الخدمات الإضافية المختارة غير متاحة حالياً.',
            ], 422);
        }

        $servicesTotal = 0.0;

        $product = Product::where('slug', 'stationery-print-service')->first();
        if (!$product) {
            $product = Product::create([
                'category_id' => 97,
                'name' => 'خدمات القرطاسية والطباعة الفورية',
                'slug' => 'stationery-print-service',
                'description' => 'طباعة مستندات وصور بكافة المقاسات والخيارات',
                'price' => 0.50,
                'stock' => 99999,
                'status_id' => 1,
            ]);
        }

        // جلب السلة الحالية
        $user = auth('sanctum')->user();
        $sessionId = $request->header('X-Session-Id') ?: session()->getId();

        $cart = Cart::firstOrCreate(
            $user ? ['user_id' => $user->id] : ['session_id' => $sessionId],
            ['subtotal' => 0, 'total' => 0]
        );

        // جلب الأسعار المعتمدة من الداشبورد لحساب التكلفة السيرفرية بدقة
        $prices = StationeryPrice::where('is_active', true)
            ->whereIn('paper_size', StationeryPrice::defaultSizes())
            ->get()->keyBy(function ($item) {
            return implode('_', [
                $item->service_type,
                $item->paper_type,
                strtoupper($item->paper_size),
                $item->side_type,
                $item->orientation,
            ]);
        });

        $processedFiles = [];
        $totalJobPrice = 0;
        $totalJobPages = 0;
        $firstImagePreview = null;

        foreach ($request->files_input ?? $request->input('files') as $fileItem) {
            $serviceType = $fileItem['service_type'];
            $paperSize = strtoupper($fileItem['paper_size']);
            $paperType = $fileItem['paper_type'];
            $colorType = $fileItem['color_type'];
            $pagesCount = (int) $fileItem['pages_count'];
            $copies = (int) $fileItem['copies'];
            $sideType = $fileItem['side_type'] ?? 'single';
            $orientation = $fileItem['orientation'] ?? 'portrait';

            $sheetsCount = $pagesCount * $copies;
            if (in_array($paperSize, ['A1', 'A2'], true) && $sheetsCount < 100) {
                return response()->json([
                    'status' => false,
                    'message' => "المقاس {$paperSize} يتطلب حدًا أدنى 100 ورقة. "
                        . "عدد الورق الحالي = {$sheetsCount} ورقة.",
                ], 422);
            }

            // الورق العادي متاح فقط حتى مقاس A3.
            if (in_array($paperSize, ['A1', 'A2'], true) && $paperType === 'plain') {
                return response()->json([
                    'status' => false,
                    'message' => "الورق العادي غير متاح لمقاس {$paperSize}. يرجى اختيار الكوشيه أو المقوّى.",
                ], 422);
            }

            // التمييز بين سعر الورقة لوحدها وسعر الورقة داخل الملف
            $isSingleSheet = ($pagesCount === 1);
            if (isset($fileItem['print_mode'])) {
                $isSingleSheet = ($fileItem['print_mode'] === 'single');
            }

            // A1 وA2 والمقاسات الأخرى تُسعَّر مباشرة من صفوف الداشبورد دون مضاعفات.
            $basePaperType = $paperType;
            $baseSize = $paperSize;
            $key = implode('_', [$serviceType, $basePaperType, $baseSize, $sideType, $orientation]);
            $priceRow = $prices->get($key);

            if (!$priceRow) {
                return response()->json([
                    'status' => false,
                    'message' => 'تركيبة الطباعة المختارة غير متاحة حالياً. يرجى تحديث الصفحة واختيار الخيارات المتاحة.',
                ], 422);
            }

            if ($isSingleSheet) {
                $basePrice = ($colorType === 'color') ? (float) $priceRow->color_price : (float) $priceRow->bw_price;
            } else {
                $fileBw = $priceRow->file_bw_price ?? $priceRow->bw_price;
                $fileColor = $priceRow->file_color_price ?? $priceRow->color_price;
                $basePrice = ($colorType === 'color') ? (float) $fileColor : (float) $fileBw;
            }

            $pricing = app(StationeryPricingService::class);
            $pagePrice = $pricing->pagePrice($basePrice, $paperSize, $paperType);
            $printTotal = $pricing->total(
                $basePrice,
                $paperSize,
                $paperType,
                $sheetsCount,
                $colorType,
                $sideType
            );

            // خدمات هذا الملف فقط — السعر × عدد النسخ.
            $fileServiceKeys = collect($fileItem['services'] ?? [])
                ->filter()
                ->unique()
                ->values();
            $fileServices = $activeServices->filter(
                fn ($service) => $fileServiceKeys->contains($service->key)
            );
            $fileServicesTotal = round((float) $fileServices->sum('price') * $copies, 2);

            $fileTotal = round($printTotal + $fileServicesTotal, 2);
            $servicesTotal += $fileServicesTotal;
            $totalJobPrice += $fileTotal;
            $totalJobPages += ($pagesCount * $copies);

            if (!$firstImagePreview && !empty($fileItem['file_url'])) {
                $firstImagePreview = $fileItem['file_url'];
            }

            $processedFiles[] = [
                'file_name' => $fileItem['file_name'],
                'file_url' => $fileItem['file_url'],
                'file_path' => $fileItem['file_path'] ?? null,
                'service_type' => $serviceType,
                'service_type_name' => $serviceType === 'photo' ? 'طباعة صور' : 'طباعة مستندات',
                'paper_size' => $paperSize,
                'paper_type' => $paperType,
                'paper_type_name' => StationeryPrice::paperTypes()[$serviceType][$paperType] ?? $paperType,
                'color_type' => $colorType,
                'color_type_name' => $colorType === 'color' ? 'ألوان' : 'أبيض وأسود',
                'orientation' => $orientation,
                'orientation_name' => $orientation === 'landscape' ? 'أفقي (عرضي)' : 'طولي',
                'side_type' => $sideType,
                'side_type_name' => $sideType === 'double' ? 'وجهين' : 'وجه واحد',
                'pages_count' => $pagesCount,
                'copies' => $copies,
                'is_single_sheet' => $isSingleSheet,
                'pricing_mode' => $isSingleSheet ? 'ورقة مفردة' : 'ورقة داخل ملف',
                'price_per_page' => $pagePrice,
                'print_total' => $printTotal,
                'services' => $fileServices->map(fn ($service) => [
                    'key' => $service->key,
                    'name' => $service->name,
                    'price' => (float) $service->price,
                ])->values(),
                'services_total' => $fileServicesTotal,
                'file_total' => $fileTotal,
            ];
        }

        $totalJobPrice = max(0.50, round($totalJobPrice, 2));

        // بناء خيارات السلة المنسقة
        $selectedOptions = [
            [
                'option_name' => 'نوع الخدمة',
                'option_value' => 'خدمات القرطاسية والطباعة الفورية',
            ],
            [
                'option_name' => 'عدد الملفات المرفوعة',
                'option_value' => count($processedFiles) . ' ملفات',
            ],
            [
                'option_name' => 'إجمالي الصفحات والنسخ',
                'option_value' => $totalJobPages . ' صفحة',
            ],
        ];

        if ($activeServices->isNotEmpty()) {
            $selectedOptions[] = [
                'option_name' => 'الخدمات الإضافية',
                'option_value' => $activeServices->map(fn ($service) => $service->name . ' (' . number_format((float) $service->price, 2) . ' ر.س)')->implode(' + '),
            ];
        }

        $selectedOptions[] = [
            'option_name' => 'تفاصيل ملفات الطباعة',
            'option_value' => json_encode($processedFiles, JSON_UNESCAPED_UNICODE),
        ];

        // إنشاء عنصر السلة
        $hashKey = md5(json_encode([
            'stationery' => true,
            'time' => microtime(true),
            'files_count' => count($processedFiles),
            'total_price' => $totalJobPrice,
        ]));

        $cartItem = CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price_per_unit' => $totalJobPrice,
            'line_total' => $totalJobPrice,
            'selected_options' => json_encode($selectedOptions, JSON_UNESCAPED_UNICODE),
            'note' => $request->input('note'),
            'image_design' => $firstImagePreview,
        ]);

        // تحديث إجمالي السلة
        $subtotal = $cart->items()->sum('line_total');
        $cart->update([
            'subtotal' => $subtotal,
            'total' => $subtotal,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'تمت إضافة طلب الطباعة إلى السلة بنجاح',
            'data' => [
                'cart_item_id' => $cartItem->id,
                'total_price' => $totalJobPrice,
                'files_count' => count($processedFiles),
                'total_pages' => $totalJobPages,
                'services' => $activeServices->map(fn ($service) => [
                    'key' => $service->key,
                    'name' => $service->name,
                    'price' => (float) $service->price,
                ])->values(),
                'services_total' => $servicesTotal,
            ],
        ]);
    }

    /**
     * حساب عدد صفحات الـ PDF
     */
    private function countPdfPages(string $filepath): int
    {
        if (!file_exists($filepath)) {
            return 1;
        }

        $handle = @fopen($filepath, 'rb');
        if ($handle === false) {
            return 1;
        }

        $maxCount = 0;
        $pageObjects = 0;
        $seenPageObjects = [];
        $carry = '';
        $position = 0;

        try {
            while (!feof($handle)) {
                $chunk = fread($handle, 1024 * 1024);
                if ($chunk === false || $chunk === '') {
                    break;
                }

                $buffer = $carry . $chunk;
                $bufferStart = $position - strlen($carry);

                if (preg_match_all('/\/Count\s+(\d+)/', $buffer, $matches, PREG_OFFSET_CAPTURE)) {
                    foreach ($matches[1] as $match) {
                        $maxCount = max($maxCount, (int) $match[0]);
                    }
                }

                if (preg_match_all('/\/Type\s*\/Page[^s]/', $buffer, $pageMatches, PREG_OFFSET_CAPTURE)) {
                    foreach ($pageMatches[0] as $match) {
                        $absoluteOffset = $bufferStart + $match[1];
                        if (!isset($seenPageObjects[$absoluteOffset])) {
                            $seenPageObjects[$absoluteOffset] = true;
                            $pageObjects++;
                        }
                    }
                }

                $position += strlen($chunk);
                $carry = substr($buffer, -64);
            }
        } finally {
            fclose($handle);
        }

        if ($maxCount > 0) {
            return $maxCount;
        }

        return max(1, $pageObjects);
    }

    /**
     * تنسيق حجم الملف
     */
    private function formatBytes($bytes, $precision = 1)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

