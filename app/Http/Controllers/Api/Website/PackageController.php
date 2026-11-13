<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    // ملاحظة: طلبات الباكدجات بقت منفصلة تمامًا عن السلة
    // (POST /package-orders → PackageOrderController)، ومفيش أي إضافة للسلة من الباقات.

    private function formatPackage(Package $package, bool $detailed): array
    {
        $data = [
            'id' => $package->id,
            'name' => $package->name,
            'slug' => $package->slug,
            'description' => $package->description,
            'image' => $package->image ? asset('storage/' . $package->image) : null,
            'background_image' => $package->background_image ? asset('storage/' . $package->background_image) : null,
            'design_price' => (float) ($package->design_price ?? 0),
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
