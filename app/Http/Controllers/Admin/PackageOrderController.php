<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PackageOrder;
use Illuminate\Http\Request;

class PackageOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = PackageOrder::withCount('items')
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = trim((string) $request->q);
                $q->where(function ($inner) use ($term) {
                    $inner->where('order_number', 'like', "%{$term}%")
                        ->orWhere('customer_name', 'like', "%{$term}%")
                        ->orWhere('customer_phone', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(20);

        return view('Admin.package-orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = PackageOrder::with('items')->findOrFail($id);

        return view('Admin.package-orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = PackageOrder::findOrFail($id);
        $request->validate(['status' => 'required|string|max:30', 'status_payment' => 'nullable|string|max:30']);

        $order->update([
            'status' => $request->status,
            'status_payment' => $request->input('status_payment', $order->status_payment),
        ]);

        return back()->with('success', 'تم تحديث حالة الطلب');
    }
}
