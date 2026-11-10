<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageOrder;
use App\Models\PackageOrderItem;
use App\Models\PaymentMethod;
use App\Traits\HandlesPaymobPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageOrderController extends Controller
{
    use HandlesPaymobPayment;
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();
        if (! $user) {
            return response()->json(['status' => false, 'message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        $orders = PackageOrder::where('user_id', $user->id)
            ->with('items')
            ->latest()
            ->get();

        return response()->json(['status' => true, 'data' => $orders]);
    }

    public function show($id)
    {
        $user = auth('sanctum')->user();
        $order = PackageOrder::with('items')->where('user_id', $user?->id)->findOrFail($id);

        return response()->json(['status' => true, 'data' => $order]);
    }

    public function store(Request $request)
    {
        $user = auth('sanctum')->user();
        if (! $user) {
            return response()->json(['status' => false, 'message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        $data = $request->validate([
            'package_id' => 'required|exists:packages,id',
            'selections' => 'required|array|min:1',
            'selections.*.package_product_id' => 'required|integer',
            'selections.*.quantity' => 'required|integer|min:1',
            'address_id' => 'nullable|integer',
            'shipping_address' => 'nullable|string',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'customer_email' => 'nullable|string',
            'shipping_price' => 'nullable|numeric|min:0',
            'delivery_option_name' => 'nullable|string',
            'delivery_option_id' => 'nullable|string',
            'oto_order_id' => 'nullable|string',
            'payment_method' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $package = Package::active()->with(['products.product', 'products.tiers'])->findOrFail($data['package_id']);

        $selections = collect($data['selections'])->keyBy(fn ($row) => (int) $row['package_product_id']);

        $packageOrder = DB::transaction(function () use ($package, $selections, $data, $user) {
            $itemsTotal = 0.0;
            $items = [];

            foreach ($package->products as $packageProduct) {
                $selection = $selections->get($packageProduct->id);
                if (! $selection) {
                    continue;
                }

                $quantity = (int) $selection['quantity'];
                $tiers = $packageProduct->tiers;
                $tier = $tiers->first(function ($t) use ($quantity) {
                    return $quantity >= (int) $t->quantity
                        && ($t->max_quantity === null || $quantity <= (int) $t->max_quantity);
                }) ?: $tiers->filter(fn ($t) => $quantity >= (int) $t->quantity)->sortByDesc('quantity')->first();

                if ($packageProduct->type === 'main') {
                    $quantity = max($quantity, (int) $packageProduct->base_quantity);
                    $total = $tier ? round($quantity * (float) $tier->price, 2) : (float) $packageProduct->base_price;
                } else {
                    $total = $tier ? round($quantity * (float) $tier->price, 2) : round((float) ($packageProduct->product?->price ?? 0) * $quantity, 2);
                }

                if ($total <= 0) {
                    continue;
                }

                $unit = round($total / max(1, $quantity), 2);
                $itemsTotal += $total;
                $items[] = [
                    'package_id' => $package->id,
                    'package_product_id' => $packageProduct->id,
                    'product_id' => $packageProduct->product_id,
                    'package_name' => $package->name,
                    'product_name' => $packageProduct->product?->name,
                    'type' => $packageProduct->type,
                    'quantity' => $quantity,
                    'unit_price' => $unit,
                    'line_total' => $total,
                ];
            }

            if (empty($items)) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'selections' => ['اختر منتجات الباقة أولاً'],
                ]);
            }

            $shipping = round((float) ($data['shipping_price'] ?? 0), 2);

            $order = PackageOrder::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'order_number' => 'PKG-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'status_payment' => 'pending',
                'payment_method' => $data['payment_method'] ?? null,
                'items_total' => $itemsTotal,
                'shipping_amount' => $shipping,
                'total_amount' => round($itemsTotal + $shipping, 2),
                'shipping_address' => $data['shipping_address'] ?? null,
                'customer_name' => $data['customer_name'] ?? $user->name,
                'customer_phone' => $data['customer_phone'] ?? $user->phone,
                'customer_email' => $data['customer_email'] ?? $user->email,
                'delivery_option_name' => $data['delivery_option_name'] ?? null,
                'delivery_option_id' => $data['delivery_option_id'] ?? null,
                'oto_order_id' => $data['oto_order_id'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            foreach ($items as $row) {
                $packageOrderItem = $order->items()->create($row);
            }

            return $order;
        });

        // لو طريقة الدفع إلكترونية (Paymob / Apple Pay) ننشئ رابط دفع لطلب الباقة.
        $paymentUrl = null;
        $shortenUrl = null;

        $paymentMethod = $data['payment_method'] ? PaymentMethod::find($data['payment_method']) : null;
        if ($paymentMethod && in_array($paymentMethod->key, ['paymob', 'applepay'], true)) {
            $result = $this->initiatePaymobPayment($packageOrder, $paymentMethod->key === 'applepay');

            if (! ($result['success'] ?? false)) {
                return response()->json([
                    'status' => false,
                    'message' => $result['message'] ?? 'تعذر بدء عملية الدفع. يرجى المحاولة مرة أخرى.',
                ], 422);
            }

            $paymentUrl = $result['payment_url'] ?? null;
            $shortenUrl = $result['shorten_url'] ?? null;
        }

        return response()->json([
            'status' => true,
            'message' => 'تم إنشاء طلب الباقة بنجاح',
            'data' => $packageOrder->load('items'),
            'payment_url' => $paymentUrl,
            'shorten_url' => $shortenUrl,
        ], 201);
    }
}
