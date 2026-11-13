<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::withCount('products')->orderBy('order')->latest()->paginate(15);

        return view('Admin.packages.index', compact('packages'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get(['id', 'name', 'image']);

        return view('Admin.packages.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = Package::makeSlug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['order'] = (int) $request->input('order', 0);
        $data['image'] = $request->hasFile('image') ? $request->file('image')->store('packages', 'public') : null;
        $data['background_image'] = $request->hasFile('background_image') ? $request->file('background_image')->store('packages', 'public') : null;
        $data['design_price'] = (float) $request->input('design_price', 0);

        DB::transaction(function () use ($request, $data) {
            $package = Package::create($data);
            $this->syncProducts($package, $request);
        });

        return redirect()->route('admin.packages.index')->with('success', 'تم إضافة الباقة بنجاح');
    }

    public function edit(Package $package)
    {
        $package->load('products.tiers');
        $products = Product::orderBy('name')->get(['id', 'name', 'image']);

        return view('Admin.packages.edit', compact('package', 'products'));
    }

    public function update(Request $request, Package $package)
    {
        $data = $this->validated($request);
        $data['slug'] = $package->slug ?: Package::makeSlug($data['name'], $package->id);
        $data['is_active'] = $request->boolean('is_active');
        $data['order'] = (int) $request->input('order', 0);
        $data['design_price'] = (float) $request->input('design_price', $package->design_price ?? 0);
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('packages', 'public');
        }
        if ($request->hasFile('background_image')) {
            $data['background_image'] = $request->file('background_image')->store('packages', 'public');
        }

        DB::transaction(function () use ($request, $package, $data) {
            $package->update($data);
            $this->syncProducts($package, $request);
        });

        return redirect()->route('admin.packages.index')->with('success', 'تم تحديث الباقة بنجاح');
    }

    public function destroy(Package $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'تم حذف الباقة');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:4096',
            'background_image' => 'nullable|image|max:4096',
            'design_price' => 'nullable|numeric|min:0',
            'order' => 'nullable|integer',
            'products' => 'nullable|array',
            'products.*.product_id' => 'nullable|exists:products,id',
            'products.*.type' => 'nullable|in:main,optional',
            'products.*.base_quantity' => 'nullable|integer|min:0',
            'products.*.base_price' => 'nullable|numeric|min:0',
            'products.*.tiers' => 'nullable|string',
        ]);
    }

    private function syncProducts(Package $package, Request $request): void
    {
        $package->products()->delete();

        foreach (($request->input('products') ?? []) as $index => $row) {
            if (empty($row['product_id'])) {
                continue;
            }

            $packageProduct = $package->products()->create([
                'product_id' => (int) $row['product_id'],
                'type' => ($row['type'] ?? 'main') === 'optional' ? 'optional' : 'main',
                'base_quantity' => max(0, (int) ($row['base_quantity'] ?? 0)),
                'base_price' => max(0, (float) ($row['base_price'] ?? 0)),
                'order' => (int) $index,
            ]);

            // الشرائح بقت رينجات: من-إلى:السعر  (أو من:السعر لرينج مفتوح)
            foreach (preg_split('/\r?\n/', (string) ($row['tiers'] ?? '')) as $line) {
                $line = trim($line);
                if ($line === '') {
                    continue;
                }

                if (preg_match('/^\s*(\d+)\s*-\s*(\d+)\s*[:=،,]\s*([\d.]+)\s*$/', $line, $m)) {
                    $from = (int) $m[1];
                    $to = (int) $m[2];
                    $price = (float) $m[3];
                } elseif (preg_match('/^\s*(\d+)\s*\+?\s*[:=،,]\s*([\d.]+)\s*$/', $line, $m)) {
                    $from = (int) $m[1];
                    $to = null;
                    $price = (float) $m[2];
                } else {
                    continue;
                }

                $packageProduct->tiers()->create([
                    'quantity' => max(0, $from),
                    'max_quantity' => $to !== null && $to >= $from ? $to : null,
                    'price' => $price,
                ]);
            }
        }
    }
}
