<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreProductRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Material;
use App\Models\Offer;
use App\Models\PrintingMethod;
use App\Models\PrintLocation;
use App\Models\Product;
use App\Models\ProductOptions;
use App\Models\ProductTextAd;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Get statistics
        $totalProducts = Product::count();
        $activeProducts = Product::where('status_id', 1)->count();
        $inactiveProducts = Product::where('status_id', 2)->count();
        $lowStockProducts = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();

        // Query products with filters
        $query = Product::with(['category', 'discount', 'colors', 'materials', 'primaryImage'])
            ->withCount('reviews')
            ->sorted($request)
            ->filtered($request);

        // تطبيق خاصية البحث GLOBAL إذا كان موجوداً
        if ($request->filled('search')) {
            $searchTerm = $request->get('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('sku', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        // فلتر التصنيف بيتطبق جوه scopeFiltered (وبيشمل الفروع كلها).

        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('price_from') && $request->filled('price_to')) {
            $query->whereBetween('price', [$request->price_from, $request->price_to]);
        } elseif ($request->filled('price_from')) {
            $query->where('price', '>=', $request->price_from);
        } elseif ($request->filled('price_to')) {
            $query->where('price', '<=', $request->price_to);
        }

        if ($request->filled('stock_from') && $request->filled('stock_to')) {
            $query->whereBetween('stock', [$request->stock_from, $request->stock_to]);
        } elseif ($request->filled('stock_from')) {
            $query->where('stock', '>=', $request->stock_from);
        } elseif ($request->filled('stock_to')) {
            $query->where('stock', '<=', $request->stock_to);
        }

        if ($request->filled('color_id')) {
            $query->whereHas('colors', function ($q) use ($request) {
                $q->whereIn('colors.id', (array) $request->color_id);
            });
        }

        if ($request->filled('material_id')) {
            $query->whereHas('materials', function ($q) use ($request) {
                $q->whereIn('materials.id', (array) $request->material_id);
            });
        }

        if ($request->filled('printing_method_id')) {
            $query->whereHas('printingMethods', function ($q) use ($request) {
                $q->whereIn('printing_methods.id', (array) $request->printing_method_id);
            });
        }

        if ($request->filled('offer_id')) {
            $query->whereHas('offers', function ($q) use ($request) {
                $q->whereIn('offers.id', (array) $request->offer_id);
            });
        }

        // الحصول على النتائج مع Pagination
        $perPage = $request->get('per_page', 30);
        $products = $query->paginate($perPage)->withQueryString();

        // Calculate average rating for each product
        foreach ($products as $product) {
            $product->average_rating = $product->reviews()->avg('rating') ?? 0;
            $product->final_price = $product->has_discount && $product->discount ?
                ($product->discount->discount_type === 'percentage' ?
                    $product->price - ($product->price * $product->discount->discount_value / 100) :
                    $product->price - $product->discount->discount_value) :
                $product->price;
        }

        // Get filter options
        $categories = Category::with('parent')
            ->whereNotNull('parent_id')
            ->get();

        // شجرة التصنيفات للفلتر: الرئيسي وفروعه بكل المستويات.
        $categoryTree = Category::with('childrenRecursive')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $colors = Color::all();
        $materials = Material::all();
        $printingMethods = PrintingMethod::all();
        $offers = Offer::all();

        return view('Admin.product.index', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'lowStockProducts',
            'categories',
            'categoryTree',
            'colors',
            'materials',
            'printingMethods',
            'offers'
        ));
    }

    /**
     * Handle bulk actions on products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);

        try {
            DB::beginTransaction();

            $productIds = $request->product_ids;
            $message = '';

            switch ($request->action) {
                case 'activate':
                    Product::whereIn('id', $productIds)->update(['status_id' => 1]);
                    $message = 'تم تفعيل المنتجات المختارة';
                    break;

                case 'deactivate':
                    Product::whereIn('id', $productIds)->update(['status_id' => 2]);
                    $message = 'تم تعطيل المنتجات المختارة';
                    break;

                case 'move_to_category':
                    $request->validate([
                        'category_id' => [
                            'required',
                            Rule::exists('categories', 'id')->whereNotNull('parent_id'),
                        ],
                    ]);
                    Product::whereIn('id', $productIds)->update(['category_id' => $request->category_id]);
                    $message = 'تم نقل المنتجات إلى التصنيف المحدد';
                    break;

                case 'add_to_offer':
                    $request->validate(['offer_id' => 'required|exists:offers,id']);
                    $products = Product::whereIn('id', $productIds)->get();
                    foreach ($products as $product) {
                        $product->offers()->syncWithoutDetaching([$request->offer_id]);
                    }
                    $message = 'تم إضافة المنتجات إلى العرض';
                    break;

                case 'remove_from_offer':
                    $request->validate(['offer_id' => 'required|exists:offers,id']);
                    $products = Product::whereIn('id', $productIds)->get();
                    foreach ($products as $product) {
                        $product->offers()->detach($request->offer_id);
                    }
                    $message = 'تم إزالة المنتجات من العرض';
                    break;

                case 'delete':
                    Product::whereIn('id', $productIds)->delete();
                    $message = 'تم حذف المنتجات المختارة';
                    break;

                default:
                    throw new \Exception('إجراء غير معروف');
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Export products.
     *
     * @return mixed
     */
    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:excel,csv,pdf',
            'columns' => 'nullable|array',
        ]);

        $query = Product::with(['category', 'colors', 'materials'])
            ->sorted($request)
            ->filtered($request);

        $products = $query->get();

        $columns = $request->columns ?? ['id', 'name', 'category', 'price', 'stock', 'status', 'created_at'];

        if ($request->type === 'excel') {
            return Excel::download(new ProductsExport($products, $columns), 'products_' . date('Y-m-d') . '.xlsx');
        } elseif ($request->type === 'csv') {
            return Excel::download(new ProductsExport($products, $columns), 'products_' . date('Y-m-d') . '.csv');
        } else {
            // PDF export logic
            $pdf = PDF::loadView('admin.products.export-pdf', compact('products', 'columns'));

            return $pdf->download('products_' . date('Y-m-d') . '.pdf');
        }
    }

    /**
     * Duplicate a product.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicate(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $product->load([
                'images',
                'discount',
                'colors',
                'materials',
                'printingMethods',
                'printLocations',
                'offers',
                'deliveryTime',
                'warranty',
                'features',
                'adsText',
                'options',
                'sizeTiers',
            ]);

            // Duplicate the product itself
            $newProduct = $product->replicate();
            $newProduct->name = $request->name;
            $newProduct->slug = Product::generateUniqueSlug($request->name);
            // external_id فريد في جدول المنتجات، لذلك لا ننسخه حتى لا يفشل الحفظ.
            $newProduct->external_id = null;
            $newProduct->save();

            // ---------- Images ----------
            $mainImagePath = null;
            foreach ($product->images as $image) {
                if (empty($image->path)) {
                    continue;
                }

                $newPath = $this->duplicateImage($image->path, 'products');
                if (! $newPath) {
                    continue;
                }

                $newImage = $newProduct->images()->make();
                $newImage->forceFill([
                    'path' => $newPath,
                    'alt' => $image->alt ?? $newProduct->name,
                    'type' => $image->type ?? 'additional',
                    'order' => $image->order ?? 1,
                    'is_primary' => (bool) ($image->is_primary ?? false),
                    'is_active' => (bool) ($image->is_active ?? true),
                ]);
                $newImage->save();

                if (($image->type === 'main' || $image->is_primary) && ! $mainImagePath) {
                    $mainImagePath = $newPath;
                }
            }

            if (! $mainImagePath && $product->image) {
                $mainImagePath = $this->duplicateImage($product->image, 'products');
                if ($mainImagePath) {
                    $newProduct->images()->create([
                        'path' => $mainImagePath,
                        'alt' => $newProduct->name,
                        'type' => 'main',
                        'order' => 1,
                    ]);
                }
            }

            if ($mainImagePath) {
                $newProduct->update(['image' => $mainImagePath]);
            }

            // ---------- Discount ----------
            if ($product->discount) {
                $discount = $product->discount->replicate();
                $discount->product_id = $newProduct->id;
                $discount->save();
            }

            // ---------- Colors ----------
            foreach ($product->colors as $color) {
                $newProduct->colors()->attach($color->id, [
                    'additional_price' => $color->pivot->additional_price ?? 0,
                ]);
            }

            // ---------- Materials ----------
            foreach ($product->materials as $material) {
                $newProduct->materials()->attach($material->id, [
                    'quantity' => $material->pivot->quantity ?? 0,
                    'unit' => $material->pivot->unit ?? 'piece',
                    'additional_price' => $material->pivot->additional_price ?? 0,
                ]);
            }

            // ---------- Printing methods ----------
            foreach ($product->printingMethods as $method) {
                $newProduct->printingMethods()->attach($method->id, [
                    'additional_price' => $method->pivot->additional_price ?? 0,
                ]);
            }

            // ---------- Print locations ----------
            foreach ($product->printLocations as $location) {
                $newProduct->printLocations()->attach($location->id, [
                    'additional_price' => $location->pivot->additional_price ?? 0,
                ]);
            }

            // ---------- Offers ----------
            foreach ($product->offers as $offer) {
                $pivot = [];
                if ($offer->pivot) {
                    $pivot = collect($offer->pivot->getAttributes())
                        ->except(['product_id', 'offer_id', 'created_at', 'updated_at'])
                        ->all();
                }
                $newProduct->offers()->attach($offer->id, $pivot);
            }

            // ---------- Delivery time / warranty / features / text ads ----------
            if ($product->deliveryTime) {
                $delivery = $product->deliveryTime->replicate();
                $delivery->product_id = $newProduct->id;
                $delivery->save();
            }

            if ($product->warranty) {
                $warranty = $product->warranty->replicate();
                $warranty->product_id = $newProduct->id;
                $warranty->save();
            }

            foreach ($product->features as $feature) {
                $newFeature = $feature->replicate();
                $newFeature->product_id = $newProduct->id;
                $newFeature->save();
            }

            foreach ($product->adsText as $ad) {
                $newAd = $ad->replicate();
                $newAd->product_id = $newProduct->id;
                $newAd->save();
            }

            // ---------- Product options + quantity/size tiers ----------
            $optionMap = [];
            foreach ($product->options as $option) {
                $newOption = $option->replicate();
                $newOption->product_id = $newProduct->id;
                $newOption->depends_on_option_id = null;
                $newOption->save();
                $optionMap[$option->id] = $newOption->id;
            }

            foreach ($product->options as $option) {
                if (! $option->depends_on_option_id) {
                    continue;
                }

                $newOptionId = $optionMap[$option->id] ?? null;
                $newDependencyId = $optionMap[$option->depends_on_option_id] ?? null;

                if ($newOptionId && $newDependencyId) {
                    ProductOptions::whereKey($newOptionId)->update([
                        'depends_on_option_id' => $newDependencyId,
                    ]);
                }
            }

            foreach (DB::table('product_size_tiers')->where('product_id', $product->id)->get() as $tier) {
                $tierData = (array) $tier;
                unset($tierData['id']);

                $tierData['product_id'] = $newProduct->id;
                $tierData['option_id'] = $optionMap[$tier->option_id] ?? $tier->option_id;
                $tierData['related_option_id'] = ! empty($tier->related_option_id)
                    ? ($optionMap[$tier->related_option_id] ?? $tier->related_option_id)
                    : null;
                if (array_key_exists('depends_on_option_id', $tierData) && $tierData['depends_on_option_id']) {
                    $tierData['depends_on_option_id'] = $optionMap[$tierData['depends_on_option_id']] ?? $tierData['depends_on_option_id'];
                }
                if (array_key_exists('parent_option_id', $tierData) && $tierData['parent_option_id']) {
                    $tierData['parent_option_id'] = $optionMap[$tierData['parent_option_id']] ?? $tierData['parent_option_id'];
                }
                $tierData['created_at'] = now();
                $tierData['updated_at'] = now();

                DB::table('product_size_tiers')->insert($tierData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم نسخ المنتج بنجاح',
                'data' => $newProduct->fresh(),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error duplicating product: ' . $e->getMessage(), [
                'product_id' => $product->id,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في نسخ المنتج: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * نسخ ملف صورة إلى مسار جديد وإرجاع المسار الجديد.
     */
    private function duplicateImage(string $sourcePath, string $folder): ?string
    {
        $disk = Storage::disk('public');

        if (! $disk->exists($sourcePath)) {
            return null;
        }

        $extension = pathinfo($sourcePath, PATHINFO_EXTENSION) ?: 'jpg';
        $newPath = trim($folder, '/') . '/' . Str::uuid() . '.' . $extension;

        if (! $disk->copy($sourcePath, $newPath)) {
            return null;
        }

        return $newPath;
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Load all necessary data
        $mainCategories = Category::with('children')
            ->whereNull('parent_id')
            ->get();
        $colors = Color::all();
        $materials = Material::all();
        $printingMethods = PrintingMethod::all();
        $printLocations = PrintLocation::all();
        $offers = Offer::all();

        return view('Admin.product.create', compact(
            'mainCategories',
            'colors',
            'materials',
            'printingMethods',
            'printLocations',
            'offers'
        ));
    }

    /**
     * Handle quick addition of colors, materials, etc.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Store a newly created product
     */
    public function store(StoreProductRequest $request)
    {
        DB::beginTransaction();

        try {
            // Validate main product data
            $validated = $request->validated();

            // Create product
            $product = Product::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? null,
                'price_text' => $validated['price_text'],
                'category_id' => $validated['category_id'] ?? $validated['main_category_id'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'] ?? 0,
                'production_type' => $request->input('production_type') === 'offset' ? 'offset' : 'digital',
                'status_id' => $validated['status_id'],
                'show_in_home' => $request->has('show_in_home') ? $request->boolean('show_in_home') : true,
                'home_order' => max(0, (int) $request->input('home_order', 0)),
                'has_discount' => $request->boolean('has_discount'),
                'includes_tax' => $request->boolean('includes_tax'),
                'includes_shipping' => $request->boolean('includes_shipping'),
            ]);

            // Handle main image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $product->update(['image' => $imagePath]);

                // Create image record
                $product->images()->create([
                    'path' => $imagePath,
                    'is_primary' => true,
                    'alt' => 'ww',

                    'type' => 'main',
                    'order' => 1,
                ]);
            }

            // Handle discount
            if ($request->boolean('has_discount') && $request->filled('discount_value')) {
                $product->discount()->create([
                    'discount_value' => $request->input('discount_value'),
                    'discount_type' => $request->input('discount_type', 'percentage'),
                ]);
            }

            // Handle colors with prices
            if ($request->filled('colors')) {
                $colors = [];
                foreach ($request->input('colors') as $colorId) {
                    $additionalPrice = $request->input("color_prices.{$colorId}", 0);
                    $colors[$colorId] = ['additional_price' => $additionalPrice];
                }
                $product->colors()->sync($colors);
            }

            // Handle materials
            if ($request->filled('materials')) {
                $materialsData = [];
                foreach ($request->input('materials') as $materialData) {
                    if (! empty($materialData['material_id'])) {
                        $materialsData[$materialData['material_id']] = [
                            'quantity' => $materialData['quantity'] ?? 0,
                            'unit' => $materialData['unit'] ?? 'piece',
                            'additional_price' => $materialData['additional_price'] ?? 0,
                        ];
                    }
                }
                $product->materials()->sync($materialsData);
            }

            // Handle printing methods with prices
            if ($request->filled('printing_methods')) {
                $printingMethods = [];
                foreach ($request->input('printing_methods') as $methodId) {
                    $additionalPrice = $request->input("printing_method_prices.{$methodId}", 0);
                    $printingMethods[$methodId] = ['additional_price' => $additionalPrice];
                }
                $product->printingMethods()->sync($printingMethods);
            }

            // Handle print locations with prices
            if ($request->filled('print_locations')) {
                $printLocations = [];
                foreach ($request->input('print_locations') as $locationId) {
                    $additionalPrice = $request->input("print_location_prices.{$locationId}", 0);
                    $printLocations[$locationId] = ['additional_price' => $additionalPrice];
                }
                $product->printLocations()->sync($printLocations);
            }

            // Handle offers
            if ($request->filled('offers')) {
                $product->offers()->sync($request->input('offers'));
            }

            // Handle delivery time (driven by production type)
            [$fromDays, $toDays] = $request->input('production_type') === 'offset' ? [10, 15] : [4, 8];
            $product->deliveryTime()->updateOrCreate([], [
                'from_days' => $fromDays,
                'to_days' => $toDays,
                'note' => 'المدة من تاريخ تعميد التصميم',
            ]);

            // Handle warranty
            if ($request->filled('warranty_months')) {
                $product->warranty()->create([
                    'months' => $request->input('warranty_months'),
                ]);
            }
            // Create text ads
            if ($request->has('text_ads')) {
                foreach ($request->text_ads as $ad) {
                    if (! empty($ad['name'])) {
                        ProductTextAd::create([
                            'product_id' => $product->id,
                            'name' => $ad['name'],
                        ]);
                    }
                }
            }
            // Handle additional images
            if ($request->hasFile('additional_images')) {
                $order = 2;
                foreach ($request->file('additional_images') as $image) {
                    $path = $image->store('products/additional', 'public');

                    $product->images()->create([
                        'path' => $path,
                        'alt' => 'ww',

                        'is_primary' => false,
                        'type' => 'additional',
                        'order' => $order++,
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.products.show', $product->id)
                ->with('success', 'تم إضافة المنتج بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء إضافة المنتج: ' . $e->getMessage());
        }
    }

    /**
     * Quick add functionality
     */
    public function quickAdd(Request $request, $type)
    {
        try {
            switch ($type) {
                case 'color':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'hex_code' => 'required|string|max:7',
                    ]);

                    $color = Color::create([
                        'name' => $request->name,
                        'hex_code' => $request->hex_code,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة اللون بنجاح',
                        'data' => $color,
                    ]);

                case 'material':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'description' => 'nullable|string',
                    ]);

                    $material = Material::create([
                        'name' => $request->name,
                        'description' => $request->description,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة المادة بنجاح',
                        'data' => $material,
                    ]);

                case 'printing_method':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'description' => 'nullable|string',
                        'base_price' => 'required|numeric|min:0',
                    ]);

                    $printingMethod = PrintingMethod::create([
                        'name' => $request->name,
                        'description' => $request->description,
                        'base_price' => $request->base_price,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة طريقة الطباعة بنجاح',
                        'data' => $printingMethod,
                    ]);

                case 'print_location':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'type' => 'required|in:front,back,side,sleeve',
                        'additional_price' => 'required|numeric|min:0',
                    ]);

                    $printLocation = PrintLocation::create([
                        'name' => $request->name,
                        'type' => $request->type,
                        'additional_price' => $request->additional_price,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة مكان الطباعة بنجاح',
                        'data' => $printLocation,
                    ]);

                case 'offer':
                    $request->validate([
                        'name' => 'required|string|max:255',
                    ]);

                    $offer = Offer::create([
                        'name' => $request->name,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة العرض بنجاح',
                        'data' => $offer,
                    ]);

                case 'category':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'parent_id' => 'nullable|exists:categories,id',
                    ]);

                    $category = Category::create([
                        'name' => $request->name,
                        'parent_id' => $request->parent_id,
                        'status_id' => 1,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة القسم بنجاح',
                        'data' => $category,
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'النوع غير معروف',
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $product = Product::with(['category', 'colors', 'images', 'sizeTiers.size', 'reviews'])->find($id);

        return view('Admin.product.show', compact('product'));
    }

    /**
     * Display the edit form for product
     */
    public function edit($id)
    {
        $product = Product::find($id);
        // Load all related data
        $product->load([
            'category',
            'colors',
            'materials',
            'deliveryTime',
            'warranty',
            'features',
            'options',
            'printingMethods',
            'printLocations',
            'offers',

            'sizeTiers',
            'images',
            'discount',
        ]);

        // Get all necessary data for selects
        $mainCategories = Category::with('children')
            ->whereNull('parent_id')
            ->get();
        $colors = Color::all();
        $materials = Material::all();
        $printingMethods = PrintingMethod::all();
        $printLocations = PrintLocation::all();
        $offers = Offer::all();

        return view('Admin.product.edit', compact(
            'product',
            'mainCategories',
            'colors',
            'materials',
            'printingMethods',
            'printLocations',
            'offers'
        ));
    }

    /**
     * Update the specified product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreProductRequest $request, $id)
    {
        DB::beginTransaction();

        try {
            $product = Product::with([
                'colors',
                'materials',
                'printingMethods',
                'printLocations',
                'offers',
                'deliveryTime',
                'warranty',
                'discount',
                'images',
                'adsText',
                'options', // إضافة خيارات المنتج
                'sizeTiers', // إضافة شرائح التسعير
            ])->findOrFail($id);

            $validated = $request->validated();

            // حذف شرائح الكمية التي أزالها المستخدم صراحةً، مع قصر الحذف على المنتج الحالي.
            $deletedQuantityTierIds = collect($request->input('deleted_quantity_tier_ids', []))
                ->filter(fn ($id) => is_numeric($id))
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            if ($deletedQuantityTierIds->isNotEmpty()) {
                $product->sizeTiers()
                    ->whereIn('id', $deletedQuantityTierIds)
                    ->delete();
            }

            /* =====================================================
            | MAIN IMAGE (products.image + product_images)
            ===================================================== */
            if ($request->hasFile('image')) {
                // حذف القديمة
                $product->images()->where('type', 'main')->each(function ($img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                });

                $path = $request->file('image')->store('products', 'public');
                $product->update(['image' => $path]);

                $product->images()->create([
                    'path' => $path,
                    'type' => 'main',
                    'is_primary' => 1,
                    'order' => 1,
                    'is_active' => 1,
                    'alt' => $product->name,
                ]);
            }

            // حذف الصورة الأساسية يدويًا
            if ($request->boolean('remove_existing_main_image')) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }

                $product->update(['image' => null]);
                $product->images()->where('is_primary', true)->delete();
            }
            /* =====================================================
            | PRODUCT OPTIONS WITH DEPENDENCIES
            ===================================================== */

            $optionMapping = []; // map old id -> current id
            $submittedOptionIds = []; // كل الخيارات التي بقيت في الفورم بعد الحذف
            $originalOptions = $product->options->keyBy('id');

            if ($request->filled('product_options')) {
                // أولًا ننشئ أو نحدث كل الخيارات بدون dependencies
                foreach ($request->product_options as $optionData) {
                    if (empty($optionData['option_name']) || empty($optionData['option_value'])) {
                        continue;
                    }

                    // لا نسمح بتعديل خيار يخص منتجًا آخر عبر تمرير id غير تابع لهذا المنتج
                    $oldId = ! empty($optionData['id']) ? (int) $optionData['id'] : null;
                    $createdOption = $oldId
                        ? $product->options()->whereKey($oldId)->first()
                        : null;

                    if (! $createdOption) {
                        $createdOption = $product->options()->make();
                    }

                    $createdOption->fill([
                        'option_name' => $optionData['option_name'],
                        'option_value' => $optionData['option_value'],
                        'additional_price' => $optionData['additional_price'] ?? 0,
                        'is_required' => isset($optionData['is_required']) && $optionData['is_required'] == '1',
                        'external_option_id' => $optionData['external_option_id'] ?? null,
                        'external_detail_id' => $optionData['external_detail_id'] ?? null,
                        'depends_on_detail_id' => $optionData['depends_on_detail_id'] ?? null,
                    ]);
                    $createdOption->save();

                    $submittedOptionIds[] = $createdOption->id;
                    $optionMapping[$createdOption->id] = $createdOption->id;
                    if ($oldId) {
                        $optionMapping[$oldId] = $createdOption->id;
                    }

                    // مزامنة شرائح الكمية: نحذف المحذوف من الفورم ونضيف/نحدّث الباقي
                    $product->sizeTiers()
                        ->where('option_id', $createdOption->id)
                        ->where('is_quantity_tier', true)
                        ->delete();

                    foreach (($optionData['quantity_tiers'] ?? []) as $tierData) {
                        if (! empty($tierData['quantity']) && ! empty($tierData['price_per_unit'])) {
                            $product->sizeTiers()->create([
                                'option_id' => $createdOption->id,
                                'quantity' => $tierData['quantity'],
                                'price_per_unit' => $tierData['price_per_unit'],
                                'tier_name' => $tierData['tier_name'] ?? "شريحة {$tierData['quantity']}+",
                                'is_quantity_tier' => true,
                                'tier_type' => 'quantity',
                            ]);
                        }
                    }
                }

                // الآن حدث الـ depends_on_option_id لكل خيار
                foreach ($request->product_options as $optionData) {
                    $oldId = ! empty($optionData['id']) ? (int) $optionData['id'] : null;
                    if (! $oldId) {
                        continue;
                    }

                    $createdOptionId = $optionMapping[$oldId] ?? null;
                    if (! $createdOptionId) {
                        continue;
                    }

                    $dependsOnId = null;
                    if (! empty($optionData['depends_on_option_id'])) {
                        $parentId = (int) $optionData['depends_on_option_id'];
                        $dependsOnId = $optionMapping[$parentId] ?? null;
                    }

                    $product->options()->whereKey($createdOptionId)->update([
                        'depends_on_option_id' => $dependsOnId,
                        'dependency_condition' => $dependsOnId
                            ? ($optionData['dependency_condition'] ?? null)
                            : null,
                    ]);
                }

                // حذف الخيار المحذوف وكل ذريته حتى لو أُرسل أحد الأبناء بطلب معدل يدويًا.
                $removedOptionIds = $originalOptions->keys()
                    ->diff($submittedOptionIds)
                    ->map(fn ($id) => (int) $id)
                    ->values();
                $cascadeOptionIds = $removedOptionIds->all();

                do {
                    $descendantIds = $originalOptions
                        ->filter(fn ($option) => in_array((int) $option->depends_on_option_id, $cascadeOptionIds, true))
                        ->keys()
                        ->map(fn ($id) => (int) $id)
                        ->diff($cascadeOptionIds)
                        ->values()
                        ->all();
                    $cascadeOptionIds = array_values(array_unique(array_merge($cascadeOptionIds, $descendantIds)));
                } while ($descendantIds !== []);

                if ($cascadeOptionIds !== []) {
                    $product->sizeTiers()->whereIn('option_id', $cascadeOptionIds)->delete();
                    $product->options()->whereIn('id', $cascadeOptionIds)->delete();
                }
            } else {
                // لو مفيش خيارات في الفورم، امسح كل الخيارات وشرائح الكمية التابعة لها
                $product->sizeTiers()->whereNotNull('option_id')->delete();
                $product->options()->delete();
            }

            /* =====================================================
            | SIZE TIERS (الشرائح حسب المقاس والكمية)
            ===================================================== */
            $product->sizeTiers()->where('is_quantity_tier', false)->delete();

            if ($request->filled('size_tiers')) {
                foreach ($request->size_tiers as $tierData) {
                    if (! empty($tierData['option_id']) && ! empty($tierData['quantity']) && ! empty($tierData['price_per_unit'])) {
                        $product->sizeTiers()->create([
                            'option_id' => $tierData['option_id'],
                            'related_option_id' => $tierData['related_option_id'] ?? null,
                            'quantity' => $tierData['quantity'],
                            'price_per_unit' => $tierData['price_per_unit'],
                            'total_price' => $tierData['price_per_unit'] * $tierData['quantity'],
                            'tier_name' => $tierData['tier_name'] ?? "شريحة {$tierData['quantity']}+",
                            'is_quantity_tier' => false,
                            'tier_type' => $tierData['tier_type'] ?? 'size',
                            'dependency_conditions' => ! empty($tierData['dependency_conditions']) ?
                                json_decode($tierData['dependency_conditions'], true) : null,
                        ]);
                    }
                }
            }

            /* =====================================================
            | BASIC PRODUCT DATA
            ===================================================== */
            $product->update([
                'name' => $validated['name'],
                'slug' => $validated['slug'] ?? $product->slug,
                'price_text' => $validated['price_text'] ?? null,
                'category_id' => $validated['category_id'] ?? $validated['main_category_id'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'] ?? 0,
                'production_type' => $request->input('production_type') === 'offset' ? 'offset' : 'digital',
                'status_id' => $validated['status_id'],
                'show_in_home' => $request->has('show_in_home') ? $request->boolean('show_in_home') : true,
                'home_order' => max(0, (int) $request->input('home_order', 0)),
                'has_discount' => $request->boolean('has_discount'),
                'includes_tax' => $request->boolean('includes_tax'),
                'includes_shipping' => $request->boolean('includes_shipping'),
                // الحقول الجديدة لتركيبات الخيارات
                'valid_combinations' => $request->filled('valid_combinations') ?
                    json_decode($request->valid_combinations, true) : null,
                'combination_count' => $request->filled('combination_count') ?
                    $request->combination_count : 0,
                'options_conditions' => $request->filled('options_conditions') ?
                    json_decode($request->options_conditions, true) : null,
            ]);

            /* =====================================================
            | DISCOUNT
            ===================================================== */
            if ($request->boolean('has_discount') && $request->filled('discount_value')) {
                $product->discount()->updateOrCreate(
                    [],
                    [
                        'discount_value' => $request->discount_value,
                        'discount_type' => $request->input('discount_type', 'percentage'),
                        'is_active' => true,
                    ]
                );
            } else {
                $product->discount()?->delete();
            }

            /* =====================================================
            | COLORS
            ===================================================== */
            if ($request->filled('colors')) {
                $colors = [];
                foreach ($request->colors as $colorId) {
                    $colors[$colorId] = [
                        'additional_price' => $request->input("color_prices.$colorId", 0),
                    ];
                }
                $product->colors()->sync($colors);
            } else {
                $product->colors()->detach();
            }

            /* =====================================================
            | MATERIALS
            ===================================================== */
            if ($request->filled('materials')) {
                $materials = [];
                foreach ($request->materials as $item) {
                    if (! empty($item['material_id'])) {
                        $materials[$item['material_id']] = [
                            'quantity' => $item['quantity'] ?? 0,
                            'unit' => $item['unit'] ?? 'piece',
                            'additional_price' => $item['additional_price'] ?? 0,
                        ];
                    }
                }
                $product->materials()->sync($materials);
            } else {
                $product->materials()->detach();
            }

            /* =====================================================
            | FEATURES (المواصفات الإضافية)
            ===================================================== */
            $product->features()->delete();
            if ($request->filled('features')) {
                foreach ($request->features as $featureData) {
                    if (! empty($featureData['name']) && ! empty($featureData['value'])) {
                        $product->features()->create([
                            'name' => $featureData['name'],
                            'value' => $featureData['value'],
                            'is_active' => true,
                        ]);
                    }
                }
            }

            /* =====================================================
            | PRINTING METHODS
            ===================================================== */
            if ($request->filled('printing_methods')) {
                $methods = [];
                foreach ($request->printing_methods as $methodId) {
                    $methods[$methodId] = [
                        'additional_price' => $request->input("printing_method_prices.$methodId", 0),
                    ];
                }
                $product->printingMethods()->sync($methods);
            } else {
                $product->printingMethods()->detach();
            }

            /* =====================================================
            | PRINT LOCATIONS
            ===================================================== */
            if ($request->filled('print_locations')) {
                $locations = [];
                foreach ($request->print_locations as $locationId) {
                    $locations[$locationId] = [
                        'additional_price' => $request->input("print_location_prices.$locationId", 0),
                    ];
                }
                $product->printLocations()->sync($locations);
            } else {
                $product->printLocations()->detach();
            }

            /* =====================================================
            | OFFERS
            ===================================================== */
            $request->filled('offers')
                ? $product->offers()->sync($request->offers)
                : $product->offers()->detach();

            /* =====================================================
            | DELIVERY TIME (driven by production type)
            ===================================================== */
            [$fromDays, $toDays] = $request->input('production_type') === 'offset' ? [10, 15] : [4, 8];
            $product->deliveryTime()->updateOrCreate([], [
                'from_days' => $fromDays,
                'to_days' => $toDays,
                'note' => 'المدة من تاريخ تعميد التصميم',
            ]);

            /* =====================================================
            | WARRANTY
            ===================================================== */
            if ($request->filled('warranty_months')) {
                $product->warranty()->updateOrCreate([], [
                    'months' => $request->warranty_months,
                    'description' => 'ضمان المصنع',
                    'is_active' => true,
                ]);
            } else {
                $product->warranty()?->delete();
            }

            /* =====================================================
            | TEXT ADS
            ===================================================== */
            $product->adsText()->delete();
            if ($request->filled('text_ads')) {
                foreach ($request->text_ads as $ad) {
                    if (! empty($ad['name'])) {
                        $product->adsText()->create([
                            'name' => $ad['name'],
                            'is_active' => true,
                        ]);
                    }
                }
            }

            /* =====================================================
            | ADDITIONAL IMAGES
            ===================================================== */
            if ($request->hasFile('additional_images')) {
                $order = $product->images()->where('type', 'additional')->max('order') ?? 0;

                foreach ($request->file('additional_images') as $image) {
                    $path = $image->store('products/additional', 'public');

                    $product->images()->create([
                        'path' => $path,
                        'alt' => $product->name,
                        'is_primary' => false,
                        'type' => 'additional',
                        'order' => ++$order,
                        'is_active' => 1,
                    ]);
                }
            }

            /* =====================================================
            | REMOVE ADDITIONAL IMAGES
            ===================================================== */
            if ($request->filled('removed_images')) {
                foreach (explode(',', $request->removed_images) as $imageId) {
                    $image = Image::find($imageId);
                    if ($image && $image->imageable_id === $product->id) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    }
                }
            }

            /* =====================================================
            | UPDATE PRIMARY IMAGE
            ===================================================== */
            if ($request->filled('primary_image_id')) {
                // إلغاء تعيين جميع الصور كرئيسية
                $product->images()->update(['is_primary' => false]);

                // تعيين الصورة الجديدة كرئيسية
                $primaryImage = Image::find($request->primary_image_id);
                if ($primaryImage && $primaryImage->imageable_id === $product->id) {
                    $primaryImage->update(['is_primary' => true]);

                    // تحديث الصورة الرئيسية في جدول المنتجات
                    $product->update(['image' => $primaryImage->path]);
                }
            }

            /* =====================================================
            | UPDATE IMAGES ORDER
            ===================================================== */
            if ($request->filled('images_order')) {
                $orderArray = explode(',', $request->images_order);
                foreach ($orderArray as $position => $imageId) {
                    $image = Image::find($imageId);
                    if ($image && $image->imageable_id === $product->id) {
                        $image->update(['order' => $position + 1]);
                    }
                }
            }

            /* =====================================================
            | UPDATE SEO FIELDS
            ===================================================== */
            $seoFields = ['meta_title', 'meta_description', 'meta_keywords'];
            foreach ($seoFields as $field) {
                if ($request->filled($field)) {
                    $product->update([$field => $request->$field]);
                }
            }

            /* =====================================================
            | GENERATE VALID COMBINATIONS (للمنتجات المتغيرة)
            ===================================================== */
            $this->generateProductCombinations($product);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث المنتج بنجاح',
                    'product_id' => $product->id
                ]);
            }

            return redirect()
                ->route('admin.products.show', $product->id)
                ->with('success', 'تم تحديث المنتج بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Update product error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'product_id' => $id,
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage());
        }
    }

    /* =====================================================
    | HELPER FUNCTION: Generate Product Combinations
    ===================================================== */
    private function generateProductCombinations(Product $product)
    {
        try {
            // الحصول على الخيارات الرئيسية فقط
            $mainOptions = $product->options()->whereNull('depends_on_option_id')->get();

            if ($mainOptions->isEmpty()) {
                $product->update([
                    'valid_combinations' => null,
                    'combination_count' => 0,
                ]);

                return;
            }

            // إنشاء مصفوفة للقيم لكل خيار
            $optionValues = [];
            foreach ($mainOptions as $option) {
                // الحصول على القيم الممكنة لهذا الخيار
                $values = $product->options()
                    ->where('option_name', $option->option_name)
                    ->pluck('option_value')
                    ->toArray();

                if (! empty($values)) {
                    $optionValues[$option->option_name] = $values;
                }
            }

            // إنشاء جميع التركيبات الممكنة
            $combinations = $this->generateCombinations($optionValues);

            // تصفية التركيبات بناءً على شروط الاعتماد
            $filteredCombinations = $this->filterCombinationsByDependencies($combinations, $product);

            // تحديث قاعدة البيانات
            $product->update([
                'valid_combinations' => json_encode($filteredCombinations, JSON_UNESCAPED_UNICODE),
                'combination_count' => count($filteredCombinations),
            ]);

            return $filteredCombinations;
        } catch (\Exception $e) {
            Log::error('Error generating product combinations', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /* =====================================================
    | HELPER FUNCTION: Generate All Combinations
    ===================================================== */
    private function generateCombinations($arrays, $i = 0)
    {
        if (! isset($arrays[$i])) {
            return [[]];
        }

        $result = [];
        foreach ($arrays[$i] as $value) {
            $tmp = $this->generateCombinations($arrays, $i + 1);
            foreach ($tmp as $t) {
                $result[] = array_merge([$value], $t);
            }
        }

        return $result;
    }

    /* =====================================================
    | HELPER FUNCTION: Filter Combinations by Dependencies
    ===================================================== */
    private function filterCombinationsByDependencies($combinations, Product $product)
    {
        $filtered = [];

        // الحصول على جميع الخيارات المعتمدة
        $dependentOptions = $product->options()
            ->whereNotNull('depends_on_option_id')
            ->with('parentOption')
            ->get()
            ->groupBy('depends_on_option_id');

        foreach ($combinations as $combination) {
            $isValid = true;

            // التحقق من شروط الاعتماد لكل خيار معتمد
            foreach ($dependentOptions as $parentOptionId => $dependents) {
                $parentOption = $product->options()->find($parentOptionId);
                if (! $parentOption) {
                    continue;
                }

                // البحث عن قيمة الخيار الأب في التركيبة
                $parentValueIndex = array_search($parentOption->option_name, array_keys($combination));
                if ($parentValueIndex !== false) {
                    $parentValue = $combination[$parentValueIndex];

                    // التحقق من كل خيار معتمد
                    foreach ($dependents as $dependent) {
                        if (! $this->checkDependencyCondition(
                            $parentValue,
                            $dependent->dependency_condition,
                            $dependent->parentOption->option_value
                        )) {
                            $isValid = false;
                            break 2;
                        }
                    }
                }
            }

            if ($isValid) {
                $filtered[] = $combination;
            }
        }

        return $filtered;
    }

    /* =====================================================
    | HELPER FUNCTION: Check Dependency Condition
    ===================================================== */
    private function checkDependencyCondition($actualValue, $condition, $expectedValue)
    {
        switch ($condition) {
            case 'equals':
                return $actualValue == $expectedValue;
            case 'not_equals':
                return $actualValue != $expectedValue;
            case 'greater_than':
                return floatval($actualValue) > floatval($expectedValue);
            case 'less_than':
                return floatval($actualValue) < floatval($expectedValue);
            case 'contains':
                return strpos($actualValue, $expectedValue) !== false;
            default:
                return true;
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
        if (! $ids || ! is_array($ids)) {
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
    // public function export()
    // {
    //     return Excel::download(new ProductsExport, 'products_' . now()->format('Y-m-d') . '.xlsx');
    // }
    public function updateImage(Request $request, Product $product)
    {
        \Log::info('Update Image Request', [
            'product_id' => $product->id,
            'has_file' => $request->hasFile('image'),
            'all_data' => $request->all(),
        ]);

        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            ]);

            // حذف الصور القديمة أولاً
            $oldImages = $product->images ?? collect();

            if ($oldImages->isNotEmpty()) {
                foreach ($oldImages as $oldImage) {
                    if ($oldImage->path && Storage::disk('public')->exists($oldImage->path)) {
                        Storage::disk('public')->delete($oldImage->path);
                    }
                    $oldImage->delete();
                }
            }

            // حفظ الصورة الجديدة
            $imagePath = $request->file('image')->store('products', 'public');

            // إنشاء سجل جديد في جدول الصور
            $image = new \App\Models\ProductImage;
            $image->product_id = $product->id;
            $image->path = $imagePath;
            $image->is_primary = 1; // تعيين كصورة رئيسية
            $image->save();

            // تحديث المنتج للإشارة إلى الصورة الرئيسية
            $product->primary_image_id = $image->id;
            $product->save();

            $imageUrl = asset('storage/' . $imagePath);

            \Log::info('Image updated successfully', [
                'image_path' => $imagePath,
                'image_url' => $imageUrl,
                'image_id' => $image->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث صورة المنتج بنجاح',
                'image_url' => $imageUrl,
                'image_id' => $image->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to update product image', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديث الصورة: ' . $e->getMessage(),
            ], 500);
        }
    }
}
