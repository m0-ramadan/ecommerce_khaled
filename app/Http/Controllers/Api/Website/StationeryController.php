<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\StationeryPrice;
use App\Models\StationeryDeliveryArea;
use App\Models\StationeryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                'min_paper_sheets' => [
                    'A0' => 500,
                    'A1' => 500,
                    'A2' => 500,
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
            'services' => 'nullable|array',
            'services.*' => 'string|max:50',
            'note' => 'nullable|string|max:1000',
        ]);

        // الخدمات المدفوعة الإضافية (سلك بلاستيك/حديد، تيب، تغليف شفاف)
        $requestedServices = collect($request->input('services', []))
            ->filter()
            ->unique()
            ->values();

        $activeServices = StationeryService::where('is_active', true)
            ->whereIn('key', $requestedServices)
            ->orderBy('sort_order')
            ->get();

        if ($requestedServices->count() !== $activeServices->count()) {
            return response()->json([
                'status' => false,
                'message' => 'إحدى الخدمات الإضافية المختارة غير متاحة حالياً.',
            ], 422);
        }

        $servicesTotal = (float) $activeServices->sum('price');

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
        $prices = StationeryPrice::where('is_active', true)->get()->keyBy(function ($item) {
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

            // المقاسات الكبيرة A0/A1/A2 تتطلب 500 ورقة على الأقل،
            // وعدد الورق = عدد صفحات الملف × عدد النسخ.
            $sheetsCount = $pagesCount * $copies;
            if (in_array($paperSize, ['A0', 'A1', 'A2'], true) && $sheetsCount < 500) {
                return response()->json([
                    'status' => false,
                    'message' => "المقاس {$paperSize} يتطلب حدًا أدنى 500 ورقة. "
                        . "عدد الورق الحالي = {$pagesCount} صفحة × {$copies} نسخة = {$sheetsCount} ورقة.",
                ], 422);
            }

            // التمييز بين سعر الورقة لوحدها وسعر الورقة داخل الملف
            $isSingleSheet = ($pagesCount === 1);
            if (isset($fileItem['print_mode'])) {
                $isSingleSheet = ($fileItem['print_mode'] === 'single');
            }

            // سعر الصفحة من الداشبورد
            $key = implode('_', [$serviceType, $paperType, $paperSize, $sideType, $orientation]);
            $priceRow = $prices->get($key);

            if (!$priceRow) {
                return response()->json([
                    'status' => false,
                    'message' => 'تركيبة الطباعة المختارة غير متاحة حالياً. يرجى تحديث الصفحة واختيار الخيارات المتاحة.',
                ], 422);
            }

            if ($isSingleSheet) {
                $pagePrice = ($colorType === 'color') ? (float) $priceRow->color_price : (float) $priceRow->bw_price;
            } else {
                $fileBw = $priceRow->file_bw_price ?? $priceRow->bw_price;
                $fileColor = $priceRow->file_color_price ?? $priceRow->color_price;
                $pagePrice = ($colorType === 'color') ? (float) $fileColor : (float) $fileBw;
            }

            $fileTotal = round($pagesCount * $pagePrice * $copies, 2);
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
                'file_total' => $fileTotal,
            ];
        }

        $totalJobPrice = max(0.50, round($totalJobPrice + $servicesTotal, 2));

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

        try {
            $content = file_get_contents($filepath);
            if (preg_match_all("/\/Count\s+(\d+)/", $content, $matches)) {
                $max = max(array_map('intval', $matches[1]));
                if ($max > 0) return $max;
            }
            if (preg_match_all("/\/Type\s*\/Page[^s]/", $content, $matches)) {
                $count = count($matches[0]);
                if ($count > 0) return $count;
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return 1;
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

