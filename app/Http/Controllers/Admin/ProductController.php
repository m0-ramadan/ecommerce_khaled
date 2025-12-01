<?php

namespace App\Http\Controllers\Admin;

use App\Models\Size;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Exports\ProductsExport;
use App\Models\ProductSizeTier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'colors']);

        // تطبيق الفلاتر
        $query = $query->filtered($request)->searched($request->search);

        $products = $query->latest()->paginate(20);

        return view('Admin.product.index', [
            'products'        => $products,
            'categories'      => Category::all(),
            'colors'          => Color::all(),
            'totalProducts'   => Product::count(),
            'activeProducts'  => Product::where('status_id', 1)->count(),
            'inactiveProducts' => Product::where('status_id', 0)->count(),
        ]);
    }

    public function create()
    {
        return view('Admin.product.create', [
            'categories' => Category::all(),
            'colors'     => Color::all(),
            'sizes'      => Size::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'description'       => 'nullable|string',
            'num_faces'         => 'required|in:1,2',
            'print_locations'   => 'nullable|array',
            'printing_methods'  => 'nullable|array',
            'protection_layer'  => 'nullable|in:none,glossy,matte',
            'design_service'    => 'nullable|in:0,free,paid',
            'design_service_price' => 'nullable|numeric|min:0',
            'delivery_time'     => 'required|string',
            'shipping_fees'     => 'nullable|string',
            'tags'              => 'nullable|string',
            'status'            => 'required|in:0,1',
            'images.*'          => 'image|mimes:jpg,jpeg,png,webp|max:5048',
            'colors'            => 'array',
            'colors.*'          => 'exists:colors,id',
            'sizes.new.*.size_id'       => 'required|exists:sizes,id',
            'sizes.new.*.quantity'      => 'required|integer|min:1',
            'sizes.new.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'name'              => $request->name,
                'category_id'       => $request->category_id,
                'description'       => $request->description,
                'num_faces'         => $request->num_faces,
                'print_locations'   => $request->print_locations ? json_encode($request->print_locations) : null,
                'printing_methods'  => $request->printing_methods ? json_encode($request->printing_methods) : null,
                'protection_layer'  => $request->protection_layer,
                'design_service'    => $request->design_service,
                'design_service_price' => $request->design_service == 'paid' ? $request->design_service_price : null,
                'delivery_time'     => $request->delivery_time,
                'shipping_fees'     => $request->shipping_fees,
                'tags'              => $request->tags,
                'status'            => $request->status,
            ]);

            // حفظ الألوان
            if ($request->colors) {
                $product->colors()->sync($request->colors);
            }

            // حفظ الصور
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'path' => $path,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            // حفظ التسعير حسب المقاس والكمية
            if ($request->filled('sizes.new')) {
                foreach ($request->sizes['new'] as $tier) {
                    ProductSizeTier::create([
                        'product_id'     => $product->id,
                        'size_id'        => $tier['size_id'],
                        'quantity'       => $tier['quantity'],
                        'price_per_unit' => $tier['price_per_unit'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('Admin.product.show', $product)
                ->with('success', 'تم إضافة المنتج بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product Store Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حفظ المنتج');
        }
    }

    public function show($id)
    {
        $product = Product::with(['category', 'colors', 'images', 'sizeTiers.size', 'reviews'])->find($id);

        return view('Admin.product.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with(['category', 'colors', 'images', 'sizeTiers.size', 'reviews'])->find($id);

        return view('Admin.product.edit', [
            'product'    => $product,
            'categories' => Category::all(),
            'colors'     => Color::all(),
            'sizes'      => Size::all(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'category_id'       => 'required|exists:categories,id',
            'description'       => 'nullable|string',
            'num_faces'         => 'required|in:1,2',
            'print_locations'   => 'nullable|array',
            'printing_methods'  => 'nullable|array',
            'protection_layer'  => 'nullable|in:none,glossy,matte',
            'design_service'    => 'nullable|in:0,free,paid',
            'design_service_price' => 'nullable|numeric|min:0',
            'delivery_time'     => 'required|string',
            'shipping_fees'     => 'nullable|string',
            'tags'              => 'nullable|string',
            'status'            => 'required|in:0,1',
            'images.*'          => 'image|mimes:jpg,jpeg,png,webp|max:5048',
            'colors'            => 'array',
            'colors.*'          => 'exists:colors,id',
            'sizes.edit.*.size_id'       => 'required|exists:sizes,id',
            'sizes.edit.*.quantity'      => 'required|integer|min:1',
            'sizes.edit.*.price_per_unit' => 'required|numeric|min:0',
            'sizes.new.*.size_id'        => 'required|exists:sizes,id',
            'sizes.new.*.quantity'       => 'required|integer|min:1',
            'sizes.new.*.price_per_unit' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $product->update([
                'name'              => $request->name,
                'category_id'       => $request->category_id,
                'description'       => $request->description,
                'num_faces'         => $request->num_faces,
                'print_locations'   => $request->print_locations ? json_encode($request->print_locations) : null,
                'printing_methods'  => $request->printing_methods ? json_encode($request->printing_methods) : null,
                'protection_layer'  => $request->protection_layer,
                'design_service'    => $request->design_service,
                'design_service_price' => $request->design_service == 'paid' ? $request->design_service_price : null,
                'delivery_time'     => $request->delivery_time,
                'shipping_fees'     => $request->shipping_fees,
                'tags'              => $request->tags,
                'status'            => $request->status,
            ]);

            // تحديث الألوان
            $product->colors()->sync($request->colors ?? []);

            // إضافة صور جديدة
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $file) {
                    $path = $file->store('products', 'public');
                    $product->images()->create([
                        'path' => $path,
                        'is_primary' => $index === 0 && !$product->images()->where('is_primary', true)->exists(),
                    ]);
                }
            }

            // تحديث التسعير القديم
            if ($request->filled('sizes.edit')) {
                foreach ($request->sizes['edit'] as $id => $data) {
                    ProductSizeTier::findOrFail($id)->update($data);
                }
            }

            // إضافة تسعير جديد
            if ($request->filled('sizes.new')) {
                foreach ($request->sizes['new'] as $data) {
                    $product->sizeTiers()->create($data);
                }
            }

            DB::commit();
            return redirect()->route('Admin.product.show', $product)
                ->with('success', 'تم تحديث المنتج بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Product Update Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء التحديث');
        }
    }

    public function destroy(Product $product)
    {
        $product->images()->delete();
        $product->sizeTiers()->delete();
        $product->delete();

        return back()->with('success', 'تم حذف المنتج بنجاح');
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');
        if (!$ids || !is_array($ids)) {
            return back()->with('error', 'لم يتم تحديد أي منتجات');
        }

        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {
            $product->images()->delete();
            $product->sizeTiers()->delete();
        }
        Product::whereIn('id', $ids)->delete();

        return back()->with('success', 'تم حذف المنتجات المحددة بنجاح');
    }

    public function deleteImage($productId, $imageId)
    {
        $image = Image::where('imageable_id', $productId)
            ->where('imageable_type', Product::class)
            ->where('id', $imageId)
            ->firstOrFail();

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json(['success' => true]);
    }

    // تصدير Excel
    public function export()
    {
        return Excel::download(new ProductsExport, 'products_' . now()->format('Y-m-d') . '.xlsx');
    }
}
