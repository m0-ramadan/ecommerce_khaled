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

                $quantity = (int) ($selection['quantity'] ?? 0);
                $tiers = $packageProduct->tiers;

                // الرينج اللي الكمية بتقع فيه (أو أعلى رينج بدايته أقل من الكمية).
                $tier = $tiers->first(function ($t) use ($quantity) {
                    return $quantity >= (int) $t->quantity
                        && ($t->max_quantity === null || $quantity <= (int) $t->max_quantity);
                }) ?: $tiers->filter(fn ($t) => $quantity >= (int) $t->quantity)->sortByDesc('quantity')->first();

                // سعر الشريحة = سعر القطعة الواحدة، والإجمالي = الكمية × سعر القطعة.
                if ($packageProduct->type === 'main') {
                    $min = (int) $packageProduct->base_quantity;
                    if ($quantity < $min) {
                        $quantity = $min;
                    }
                    $total = $tier
                        ? round($quantity * (float) $tier->price, 2)
                        : (float) $packageProduct->base_price;
                } else {
                    if ($quantity <= 0) {
                        continue;
                    }
                    $total = $tier
                        ? round($quantity * (float) $tier->price, 2)
                        : round((float) ($packageProduct->product?->price ?? 0) * $quantity, 2);
                }

                if ($total <= 0) {
                    continue;
                }

                $unit = round($total / $quantity, 2);
                $hash = md5(json_encode([
                    'package_id' => $package->id,
                    'package_product_id' => $packageProduct->id,
                    'quantity' => $quantity,
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
                                'quantity' => $quantity,
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
                        'min_quantity' => (int) $t->quantity,
                        'max_quantity' => $t->max_quantity !== null ? (int) $t->max_quantity : null,
                        'price' => (float) $t->price,
                    ])->values(),
                ];
            })->values();
        }

        return $data;
    }
}
