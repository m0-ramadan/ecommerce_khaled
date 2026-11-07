<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Package;
use App\Models\PackageProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::active()->withCount('products')->orderBy('order')->latest()->get();

        return response()->json([
            'status' => true,
            'data' => $packages->map(fn ($p) => $this->formatPackage($p, false)),
        ]);
    }

    public function show($id)
    {
        $package = Package::active()->with(['products.product', 'products.tiers'])->findOrFail($id);

        return response()->json(['status' => true, 'data' => $this->formatPackage($package, true)]);
    }

    public function order(Request $request, $id)
    {
        $user = auth('sanctum')->user();
        if (! $user) {
            return response()->json(['status' => false, 'message' => 'يجب تسجيل الدخول أولاً'], 401);
        }

        $package = Package::active()->with(['products.product', 'products.tiers'])->findOrFail($id);

        $selections = collect($request->input('selections', []))
            ->keyBy(fn ($row) => (int) ($row['package_product_id'] ?? 0));

        if ($selections->isEmpty()) {
            return response()->json(['status' => false, 'message' => 'اختر منتجات الباقة أولاً'], 422);
        }

        $cart = $this->currentCart($user);

        DB::transaction(function () use ($package, $selections, $cart) {
            foreach ($package->products as $packageProduct) {
                $selection = $selections->get($packageProduct->id);
                if (! $selection) {
                    continue;
                }

                $tier = null;
                if (! empty($selection['tier_id'])) {
                    $tier = $packageProduct->tiers->firstWhere('id', (int) $selection['tier_id']);
                }

                if ($packageProduct->type === 'main') {
                    $quantity = max(1, (int) $packageProduct->base_quantity + (int) ($tier->quantity ?? 0));
                    $total = (float) $packageProduct->base_price + (float) ($tier->price ?? 0);
                } else {
                    if (! $tier) {
                        continue;
                    }
                    $quantity = max(1, (int) $tier->quantity);
                    $total = (float) $tier->price;
                }

                if ($total <= 0) {
                    continue;
                }

                $unit = round($total / $quantity, 2);
                $hash = md5(json_encode([
                    'package_id' => $package->id,
                    'package_product_id' => $packageProduct->id,
                    'tier_id' => $tier->id ?? null,
                    'user_id' => $cart->user_id,
                ]));

                CartItem::updateOrCreate(
                    ['cart_id' => $cart->id, 'hash_key' => $hash],
                    [
                        'product_id' => $packageProduct->product_id,
                        'quantity' => $quantity,
                        'price_per_unit' => $unit,
                        'line_total' => $total,
                        'selected_options' => [
                            [
                                'option_name' => 'الباقة: ' . $package->name,
                                'option_value' => $packageProduct->product?->name,
                                'additional_price' => $total,
                                'package_id' => $package->id,
                                'type' => $packageProduct->type,
                                'tier_id' => $tier->id ?? null,
                            ],
                        ],
                    ]
                );
            }

            $subtotal = CartItem::where('cart_id', $cart->id)->sum('line_total');
            $cart->update(['subtotal' => $subtotal, 'total' => $subtotal]);
        });

        return response()->json([
            'status' => true,
            'message' => 'تمت إضافة الباقة إلى السلة',
            'data' => ['redirect' => '/payment'],
        ]);
    }

    private function currentCart($user): Cart
    {
        return Cart::firstOrCreate(['user_id' => $user->id], ['subtotal' => 0, 'total' => 0]);
    }

    private function formatPackage(Package $package, bool $detailed): array
    {
        $data = [
            'id' => $package->id,
            'name' => $package->name,
            'slug' => $package->slug,
            'description' => $package->description,
            'image' => $package->image ? asset('storage/' . $package->image) : null,
            'background_image' => $package->background_image ? asset('storage/' . $package->background_image) : null,
            'products_count' => $package->products_count ?? $package->products->count(),
        ];

        if ($detailed) {
            $data['products'] = $package->products->map(function (PackageProduct $pp) {
                return [
                    'id' => $pp->id,
                    'type' => $pp->type,
                    'base_quantity' => (int) $pp->base_quantity,
                    'base_price' => (float) $pp->base_price,
                    'product' => [
                        'id' => $pp->product?->id,
                        'name' => $pp->product?->name,
                        'image' => $pp->product?->image ? get_user_image($pp->product->image) : null,
                        'price' => (float) ($pp->product?->price ?? 0),
                    ],
                    'tiers' => $pp->tiers->map(fn ($t) => [
                        'id' => $t->id,
                        'quantity' => (int) $t->quantity,
                        'price' => (float) $t->price,
                    ])->values(),
                ];
            })->values();
        }

        return $data;
    }
}
