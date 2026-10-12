<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StationeryPrice;
use Illuminate\Http\Request;

class StationeryPriceController extends Controller
{
    /**
     * عرض أسعار خدمات القرطاسية والطباعة
     */
    public function index()
    {
        $documentPrices = StationeryPrice::where('service_type', 'document')
            ->orderBy('sort_order')
            ->get();

        $photoPrices = StationeryPrice::where('service_type', 'photo')
            ->orderBy('sort_order')
            ->get();

        $sizes = StationeryPrice::defaultSizes();
        $dimensions = StationeryPrice::sizeDimensions();

        // إحصائيات سريعة للطلبات
        $stationeryOrdersCount = OrderItem::whereHas('product', function ($q) {
            $q->where('slug', 'stationery-print-service');
        })->count();

        return view('Admin.stationery.index', compact(
            'documentPrices',
            'photoPrices',
            'sizes',
            'dimensions',
            'stationeryOrdersCount'
        ));
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
                    'double_sided_price' => max(0, (float) ($values['double_sided_price'] ?? 0)),
                    'is_active' => isset($values['is_active']) ? 1 : 0,
                ]);
            }
        }

        return redirect()->route('admin.stationery.index')
            ->with('success', 'تم حفظ وتحديث أسعار خدمات القرطاسية والطباعة بنجاح!');
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

