<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StationeryLargeFormatRule;
use App\Models\StationeryPrice;
use App\Models\StationeryDeliveryArea;
use App\Models\StationeryPaperType;
use App\Models\StationeryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StationeryPriceController extends Controller
{
    /**
     * عرض أسعار خدمات القرطاسية والطباعة
     */
    public function index()
    {
        // كل المقاسات (A1 - A5) وكل أنواع الورق بما فيها الورق المقوّى أصبحت أسعارها يدوية،
        // لذلك نعرضها جميعاً في الداشبورد حتى يتمكن المدير من تعديل سعر كل حالة.
        $documentPrices = StationeryPrice::where('service_type', 'document')
            ->whereIn('paper_size', StationeryPrice::defaultSizes())
            ->orderBy('paper_type')
            ->orderBy('sort_order')
            ->orderBy('side_type')
            ->orderBy('orientation')
            ->get();

        $photoPrices = StationeryPrice::where('service_type', 'photo')
            ->whereIn('paper_size', StationeryPrice::defaultSizes())
            ->orderBy('paper_type')
            ->orderBy('sort_order')
            ->orderBy('side_type')
            ->orderBy('orientation')
            ->get();

        $sizes = StationeryPrice::defaultSizes();
        $dimensions = StationeryPrice::sizeDimensions();
        $paperTypes = StationeryPrice::paperTypes(false);
        $orientations = StationeryPrice::orientations();
        $sideTypes = StationeryPrice::sideTypes();

        // إعدادات سعر البداية للمقاسات الكبيرة (A1 / A2)
        $largeFormatRules = StationeryLargeFormatRule::orderBy('paper_size')->get()
            ->keyBy(fn ($rule) => strtoupper($rule->paper_size));

        // إحصائيات سريعة للطلبات
        $stationeryOrdersCount = OrderItem::whereHas('product', function ($q) {
            $q->where('slug', 'stationery-print-service');
        })->count();

        return view('Admin.stationery.index', compact(
            'documentPrices',
            'photoPrices',
            'sizes',
            'dimensions',
            'paperTypes',
            'orientations',
            'sideTypes',
            'largeFormatRules',
            'stationeryOrdersCount'
        ));
    }

    public function deliveryAreas()
    {
        // التوصيل داخل الرياض فقط بسعر ثابت 20 ريال
        StationeryDeliveryArea::riyadh();
        $deliveryAreas = StationeryDeliveryArea::where('name', StationeryDeliveryArea::RIYADH_NAME)->get();

        return view('Admin.stationery.delivery-areas', compact('deliveryAreas'));
    }

    public function services()
    {
        $services = StationeryService::orderBy('sort_order')->orderBy('name')->get();
        $sizes = StationeryService::supportedSizes();

        return view('Admin.stationery.services', compact('services', 'sizes'));
    }

    public function storeService(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:stationery_services,name',
            'price' => 'required|numeric|min:0',
            'available_sizes' => 'nullable|array',
            'available_sizes.*' => 'string|in:' . implode(',', StationeryService::supportedSizes()),
        ]);

        $baseKey = Str::slug($validated['name']) ?: 'service-' . Str::lower(Str::random(8));
        $key = $baseKey;
        $suffix = 2;
        while (StationeryService::where('key', $key)->exists()) {
            $key = $baseKey . '-' . $suffix++;
        }

        StationeryService::create([
            'key' => $key,
            'name' => trim($validated['name']),
            'price' => $validated['price'],
            'available_sizes' => $this->normalizeAvailableSizes($validated['available_sizes'] ?? null),
            'is_active' => true,
            'sort_order' => (StationeryService::max('sort_order') ?? 0) + 1,
        ]);

        return back()->with('success', 'تمت إضافة الخدمة الإضافية بنجاح.');
    }

    public function updateServices(Request $request)
    {
        foreach ($request->input('services', []) as $id => $values) {
            $service = StationeryService::find($id);
            if ($service) {
                $service->update([
                    'name' => trim((string) ($values['name'] ?? $service->name)),
                    'price' => max(0, (float) ($values['price'] ?? $service->price)),
                    'available_sizes' => $this->normalizeAvailableSizes($values['available_sizes'] ?? null),
                    'is_active' => isset($values['is_active']),
                ]);
            }
        }

        return back()->with('success', 'تم تحديث الخدمات الإضافية.');
    }

    /**
     * المقاسات المختارة للخدمة. لو كل المقاسات متاحة نخزن null
     * (يعني الخدمة متاحة للكل) بدل ما نخزن مصفوفة كاملة.
     */
    protected function normalizeAvailableSizes($sizes): ?array
    {
        if (! is_array($sizes)) {
            return null;
        }

        $supported = array_map('strtoupper', StationeryService::supportedSizes());
        $selected = array_values(array_intersect(
            array_map(fn ($size) => strtoupper(trim((string) $size)), $sizes),
            $supported
        ));

        // كل المقاسات مختارة = null (متاحة للكل).
        if (count($selected) === count($supported)) {
            return null;
        }

        // ولا مقاس مختار = الخدمة متاحة (مؤقتاً) لكن مش هتظهر لأي مقاس.
        if ($selected === []) {
            return [];
        }

        // نحافظ على ترتيب المقاسات المنطقي (A1, A2, ...).
        return array_values(array_intersect($supported, $selected));
    }

    public function destroyService(StationeryService $service)
    {
        $service->delete();

        return back()->with('success', 'تم حذف الخدمة الإضافية.');
    }

    public function paperTypes()
    {
        $paperTypes = StationeryPaperType::orderBy('service_type')->orderBy('sort_order')->orderBy('name')->get();

        return view('Admin.stationery.paper-types', compact('paperTypes'));
    }

    public function storePaperType(Request $request)
    {
        $validated = $request->validate([
            'service_type' => 'required|in:document,photo',
            'name' => [
                'required',
                'string',
                'max:150',
                Rule::unique('stationery_paper_types', 'name')
                    ->where(fn ($query) => $query->where('service_type', $request->input('service_type'))),
            ],
            'bw_price' => 'required|numeric|min:0',
            'color_price' => 'required|numeric|min:0',
            'file_bw_price' => 'required|numeric|min:0',
            'file_color_price' => 'required|numeric|min:0',
        ]);

        $baseKey = Str::slug($validated['name']) ?: 'paper-' . Str::lower(Str::random(8));
        $key = $baseKey;
        $suffix = 2;
        while (StationeryPaperType::where('service_type', $validated['service_type'])->where('key', $key)->exists()) {
            $key = $baseKey . '-' . $suffix++;
        }

        DB::transaction(function () use ($validated, $key) {
            StationeryPaperType::create([
                'service_type' => $validated['service_type'],
                'key' => $key,
                'name' => trim($validated['name']),
                'is_active' => true,
                'sort_order' => (StationeryPaperType::where('service_type', $validated['service_type'])->max('sort_order') ?? 0) + 1,
            ]);

            foreach (StationeryPrice::defaultSizes() as $sortOrder => $paperSize) {
                foreach (StationeryPrice::sideTypes() as $sideType => $unusedSideLabel) {
                    foreach (StationeryPrice::orientations() as $orientation => $unusedOrientationLabel) {
                        StationeryPrice::create([
                            'service_type' => $validated['service_type'],
                            'paper_type' => $key,
                            'paper_size' => $paperSize,
                            'side_type' => $sideType,
                            'orientation' => $orientation,
                            'bw_price' => $validated['bw_price'],
                            'color_price' => $validated['color_price'],
                            'file_bw_price' => $validated['file_bw_price'],
                            'file_color_price' => $validated['file_color_price'],
                            'double_sided_price' => 0,
                            'is_active' => true,
                            'sort_order' => $sortOrder,
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.stationery.paper-types.index')
            ->with('success', 'تمت إضافة نوع الورق وإنشاء جميع تركيبات الأسعار الخاصة به.');
    }

    public function updatePaperTypes(Request $request)
    {
        foreach ($request->input('paper_types', []) as $id => $values) {
            $paperType = StationeryPaperType::find($id);
            if ($paperType) {
                $paperType->update([
                    'name' => trim((string) ($values['name'] ?? $paperType->name)),
                    'is_active' => isset($values['is_active']),
                ]);
                StationeryPrice::where('service_type', $paperType->service_type)
                    ->where('paper_type', $paperType->key)
                    ->update(['is_active' => isset($values['is_active'])]);
            }
        }

        return back()->with('success', 'تم تحديث أنواع الورق.');
    }

    public function destroyPaperType(StationeryPaperType $paperType)
    {
        DB::transaction(function () use ($paperType) {
            StationeryPrice::where('service_type', $paperType->service_type)
                ->where('paper_type', $paperType->key)
                ->delete();
            $paperType->delete();
        });

        return back()->with('success', 'تم حذف نوع الورق وأسعاره.');
    }

    public function storeDeliveryArea(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:stationery_delivery_areas,name',
            'price' => 'required|numeric|min:0',
        ]);

        StationeryDeliveryArea::create([
            ...$validated,
            'is_active' => true,
            'sort_order' => (StationeryDeliveryArea::max('sort_order') ?? 0) + 1,
        ]);

        return back()->with('success', 'تمت إضافة منطقة التوصيل بنجاح.');
    }

    public function updateDeliveryAreas(Request $request)
    {
        foreach ($request->input('delivery_areas', []) as $id => $values) {
            $area = StationeryDeliveryArea::find($id);
            if ($area) {
                $area->update([
                    'name' => trim((string) ($values['name'] ?? $area->name)),
                    'price' => max(0, (float) ($values['price'] ?? 0)),
                    'is_active' => isset($values['is_active']),
                ]);
            }
        }

        return back()->with('success', 'تم تحديث أسعار ومناطق توصيل الطباعة.');
    }

    public function destroyDeliveryArea(StationeryDeliveryArea $deliveryArea)
    {
        $deliveryArea->delete();

        return back()->with('success', 'تم حذف منطقة التوصيل.');
    }

    /**
     * تحديث الأسعار دفعة واحدة من لوحة التحكم
     */
    public function update(Request $request)
    {
        $data = $request->input('prices', []);

        foreach ($data as $id => $values) {
            $priceItem = StationeryPrice::find($id);
            if ($priceItem) {
                $priceItem->update([
                    'bw_price' => max(0, (float) ($values['bw_price'] ?? 0)),
                    'color_price' => max(0, (float) ($values['color_price'] ?? 0)),
                    'file_bw_price' => isset($values['file_bw_price']) ? max(0, (float) $values['file_bw_price']) : max(0, (float) ($values['bw_price'] ?? 0)),
                    'file_color_price' => isset($values['file_color_price']) ? max(0, (float) $values['file_color_price']) : max(0, (float) ($values['color_price'] ?? 0)),
                    'is_active' => isset($values['is_active']) ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('admin.stationery.index')
            ->with('success', 'تم حفظ وتحديث أسعار خدمات القرطاسية والطباعة بنجاح!');
    }

    /**
     * تحديث أسعار البداية للمقاسات الكبيرة (A1 / A2) وعدد الورق المشمول،
     * وتفعيل/إلغاء الحد الأدنى للفاتورة.
     */
    public function updateLargeFormatRules(Request $request)
    {
        $validated = $request->validate([
            'rules' => 'required|array',
            // لازم يكون موجود هنا عشان validate() يرجّعه؛ وإلا الحقل بيتشال
            // ويفضل الحد الأدنى مقفول دايمًا.
            'rules.*.is_minimum_enabled' => 'nullable|in:0,1',
            'rules.*.included_sheets' => 'nullable|integer|min:0|max:100000',
            'rules.*.single_bw_price' => 'nullable|numeric|min:0',
            'rules.*.single_color_price' => 'nullable|numeric|min:0',
            'rules.*.double_bw_price' => 'nullable|numeric|min:0',
            'rules.*.double_color_price' => 'nullable|numeric|min:0',
        ]);

        foreach ($validated['rules'] as $size => $values) {
            $size = strtoupper((string) $size);

            if (! in_array($size, StationeryLargeFormatRule::sizes(), true)) {
                continue;
            }

            StationeryLargeFormatRule::updateOrCreate(
                ['paper_size' => $size],
                [
                    'is_minimum_enabled' => ! empty($values['is_minimum_enabled']),
                    'included_sheets' => max(0, (int) ($values['included_sheets'] ?? 0)),
                    'single_bw_price' => max(0, (float) ($values['single_bw_price'] ?? 0)),
                    'single_color_price' => max(0, (float) ($values['single_color_price'] ?? 0)),
                    'double_bw_price' => max(0, (float) ($values['double_bw_price'] ?? 0)),
                    'double_color_price' => max(0, (float) ($values['double_color_price'] ?? 0)),
                ]
            );
        }

        StationeryLargeFormatRule::flushCache();

        return redirect()->route('admin.stationery.index')
            ->with('success', 'تم تحديث أسعار البداية للمقاسات الكبيرة بنجاح!');
    }

    /**
     * استعراض طلبات خدمات القرطاسية والطباعة
     */
    public function orders(Request $request)
    {
        $orders = Order::whereHas('items.product', function ($q) {
            $q->where('slug', 'stationery-print-service');
        })
        ->with(['items' => function ($q) {
            $q->whereHas('product', function ($pq) {
                $pq->where('slug', 'stationery-print-service');
            });
        }, 'user'])
        ->latest()
        ->paginate(15);

        return view('Admin.stationery.orders', compact('orders'));
    }
}

