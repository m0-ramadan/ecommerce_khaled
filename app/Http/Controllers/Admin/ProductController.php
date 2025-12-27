<?php

namespace App\Http\Controllers\Admin;

use PDF;
use App\Models\Size;
use App\Models\Color;
use App\Models\Image;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Category;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\PrintLocation;
use App\Models\ProductTextAd;
use App\Models\PrintingMethod;
use App\Exports\ProductsExport;
use App\Models\ProductSizeTier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\Admin\Product\StoreProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @param  \Illuminate\Http\Request  $request
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

        // Apply additional filters
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

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
                $q->whereIn('colors.id', (array)$request->color_id);
            });
        }

        if ($request->filled('material_id')) {
            $query->whereHas('materials', function ($q) use ($request) {
                $q->whereIn('materials.id', (array)$request->material_id);
            });
        }

        if ($request->filled('printing_method_id')) {
            $query->whereHas('printingMethods', function ($q) use ($request) {
                $q->whereIn('printing_methods.id', (array)$request->printing_method_id);
            });
        }

        if ($request->filled('offer_id')) {
            $query->whereHas('offers', function ($q) use ($request) {
                $q->whereIn('offers.id', (array)$request->offer_id);
            });
        }

        // الحصول على النتائج مع Pagination
        $perPage = $request->get('per_page', 20);
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
        $categories = Category::where('status_id', 1)->get();
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
            'colors',
            'materials',
            'printingMethods',
            'offers'
        ));
    }
    /**
     * Handle bulk actions on products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string',
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id'
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
                    $request->validate(['category_id' => 'required|exists:categories,id']);
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
                'message' => $message
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk action error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Export products.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:excel,csv,pdf',
            'columns' => 'nullable|array'
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
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicate(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        try {
            DB::beginTransaction();

            // Duplicate the product
            $newProduct = $product->replicate();
            $newProduct->name = $request->name;
            $newProduct->save();

            // Duplicate images
            if ($product->image) {
                $newImagePath = $this->duplicateImage($product->image, 'products');
                $newProduct->update(['image' => $newImagePath]);
            }

            // Duplicate relationships
            $product->colors()->each(function ($color) use ($newProduct) {
                $newProduct->colors()->attach($color->id);
            });

            $product->materials()->each(function ($material) use ($newProduct) {
                $newProduct->materials()->attach($material->id, [
                    'quantity' => $material->pivot->quantity,
                    'unit' => $material->pivot->unit
                ]);
            });

            // Add more relationship duplications as needed

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم نسخ المنتج بنجاح',
                'data' => $newProduct
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error duplicating product: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'فشل في نسخ المنتج'
            ], 500);
        }
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Load all necessary data
        $categories = Category::where('status_id', 1)->get();
        $colors = Color::all();
        $materials = Material::all();
        $printingMethods = PrintingMethod::all();
        $printLocations = PrintLocation::all();
        $offers = Offer::all();

        return view('Admin.product.create', compact(
            'categories',
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
                'price_text' => $validated['price_text'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'stock' => $validated['stock'] ?? 0,
                'status_id' => $validated['status_id'],
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
                    'order' => 1
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
                    if (!empty($materialData['material_id'])) {
                        $materialsData[$materialData['material_id']] = [
                            'quantity' => $materialData['quantity'] ?? 0,
                            'unit' => $materialData['unit'] ?? 'piece',
                            'additional_price' => $materialData['additional_price'] ?? 0
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

            // Handle delivery time
            if ($request->filled('from_days') || $request->filled('to_days')) {
                $product->deliveryTime()->create([
                    'from_days' => $request->input('from_days'),
                    'to_days' => $request->input('to_days'),
                ]);
            }

            // Handle warranty
            if ($request->filled('warranty_months')) {
                $product->warranty()->create([
                    'months' => $request->input('warranty_months')
                ]);
            }
            // Create text ads
            if ($request->has('text_ads')) {
                foreach ($request->text_ads as $ad) {
                    if (!empty($ad['name'])) {
                        ProductTextAd::create([
                            'product_id' => $product->id,
                            'name' => $ad['name']
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
                        'order' => $order++
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
                        'hex_code' => 'required|string|max:7'
                    ]);

                    $color = Color::create([
                        'name' => $request->name,
                        'hex_code' => $request->hex_code
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة اللون بنجاح',
                        'data' => $color
                    ]);

                case 'material':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'description' => 'nullable|string'
                    ]);

                    $material = Material::create([
                        'name' => $request->name,
                        'description' => $request->description
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة المادة بنجاح',
                        'data' => $material
                    ]);

                case 'printing_method':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'description' => 'nullable|string',
                        'base_price' => 'required|numeric|min:0'
                    ]);

                    $printingMethod = PrintingMethod::create([
                        'name' => $request->name,
                        'description' => $request->description,
                        'base_price' => $request->base_price
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة طريقة الطباعة بنجاح',
                        'data' => $printingMethod
                    ]);

                case 'print_location':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'type' => 'required|in:front,back,side,sleeve',
                        'additional_price' => 'required|numeric|min:0'
                    ]);

                    $printLocation = PrintLocation::create([
                        'name' => $request->name,
                        'type' => $request->type,
                        'additional_price' => $request->additional_price
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة مكان الطباعة بنجاح',
                        'data' => $printLocation
                    ]);

                case 'offer':
                    $request->validate([
                        'name' => 'required|string|max:255'
                    ]);

                    $offer = Offer::create([
                        'name' => $request->name
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة العرض بنجاح',
                        'data' => $offer
                    ]);

                case 'category':
                    $request->validate([
                        'name' => 'required|string|max:255',
                        'parent_id' => 'nullable|exists:categories,id'
                    ]);

                    $category = Category::create([
                        'name' => $request->name,
                        'parent_id' => $request->parent_id,
                        'status_id' => 1
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'تم إضافة القسم بنجاح',
                        'data' => $category
                    ]);

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'النوع غير معروف'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ: ' . $e->getMessage()
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
            'pricingTiers',
            'sizeTiers',
            'images',
            'discount'
        ]);

        // Get all necessary data for selects
        $categories = Category::with('children')->whereNull('parent_id')->get();
        $colors = Color::all();
        $materials = Material::all();
        $printingMethods = PrintingMethod::all();
        $printLocations = PrintLocation::all();
        $offers = Offer::all();

        return view('Admin.product.edit', compact(
            'product',
            'categories',
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
 * @param  \App\Http\Requests\Admin\Product\StoreProductRequest  $request
 * @param  int  $id
 * @return \Illuminate\Http\RedirectResponse
 */
public function update(StoreProductRequest $request, $id)
{
    DB::beginTransaction();

    try {
        $product = Product::with([
            'colors', 'materials', 'printingMethods', 
            'printLocations', 'offers', 'deliveryTime', 
            'warranty', 'discount', 'images', 'adsText'
        ])->findOrFail($id);

        // Validate main product data
        $validated = $request->validated();

        // Handle main image update
        $imagePath = $product->image;
        
        // If new main image uploaded
        if ($request->hasFile('image')) {
            // Delete old main image from storage
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Upload new main image
            $imagePath = $request->file('image')->store('products', 'public');
        }
        
        // If remove existing main image is requested
        if ($request->filled('remove_existing_main_image') && $request->remove_existing_main_image == '1') {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = null;
        }

        // Update product basic info
        $product->update([
            'name' => $validated['name'],
            'price_text' => $validated['price_text'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'stock' => $validated['stock'] ?? 0,
            'status_id' => $validated['status_id'],
            'has_discount' => $request->boolean('has_discount'),
            'includes_tax' => $request->boolean('includes_tax'),
            'includes_shipping' => $request->boolean('includes_shipping'),
            'image' => $imagePath,
        ]);

        // Handle discount update
        if ($request->boolean('has_discount') && $request->filled('discount_value')) {
            if ($product->discount) {
                $product->discount()->update([
                    'discount_value' => $request->input('discount_value'),
                    'discount_type' => $request->input('discount_type', 'percentage'),
                ]);
            } else {
                $product->discount()->create([
                    'discount_value' => $request->input('discount_value'),
                    'discount_type' => $request->input('discount_type', 'percentage'),
                ]);
            }
        } elseif ($product->discount) {
            $product->discount()->delete();
        }

        // Handle colors with prices
        if ($request->filled('colors')) {
            $colors = [];
            foreach ($request->input('colors') as $colorId) {
                $additionalPrice = $request->input("color_prices.{$colorId}", 0);
                $colors[$colorId] = ['additional_price' => $additionalPrice];
            }
            $product->colors()->sync($colors);
        } else {
            $product->colors()->detach();
        }

        // Handle materials update
        if ($request->filled('materials')) {
            $materialsData = [];
            foreach ($request->input('materials') as $materialData) {
                if (!empty($materialData['material_id'])) {
                    $materialsData[$materialData['material_id']] = [
                        'quantity' => $materialData['quantity'] ?? 0,
                        'unit' => $materialData['unit'] ?? 'piece',
                        'additional_price' => $materialData['additional_price'] ?? 0
                    ];
                }
            }
            $product->materials()->sync($materialsData);
        } else {
            $product->materials()->detach();
        }

        // Handle printing methods with prices
        if ($request->filled('printing_methods')) {
            $printingMethods = [];
            foreach ($request->input('printing_methods') as $methodId) {
                $additionalPrice = $request->input("printing_method_prices.{$methodId}", 0);
                $printingMethods[$methodId] = ['additional_price' => $additionalPrice];
            }
            $product->printingMethods()->sync($printingMethods);
        } else {
            $product->printingMethods()->detach();
        }

        // Handle print locations with prices
        if ($request->filled('print_locations')) {
            $printLocations = [];
            foreach ($request->input('print_locations') as $locationId) {
                $additionalPrice = $request->input("print_location_prices.{$locationId}", 0);
                $printLocations[$locationId] = ['additional_price' => $additionalPrice];
            }
            $product->printLocations()->sync($printLocations);
        } else {
            $product->printLocations()->detach();
        }

        // Handle offers
        if ($request->filled('offers')) {
            $product->offers()->sync($request->input('offers'));
        } else {
            $product->offers()->detach();
        }

        // Handle delivery time update
        if ($request->filled('from_days') || $request->filled('to_days')) {
            if ($product->deliveryTime) {
                $product->deliveryTime()->update([
                    'from_days' => $request->input('from_days'),
                    'to_days' => $request->input('to_days'),
                ]);
            } else {
                $product->deliveryTime()->create([
                    'from_days' => $request->input('from_days'),
                    'to_days' => $request->input('to_days'),
                ]);
            }
        } elseif ($product->deliveryTime) {
            $product->deliveryTime()->delete();
        }

        // Handle warranty update
        if ($request->filled('warranty_months')) {
            if ($product->warranty) {
                $product->warranty()->update([
                    'months' => $request->input('warranty_months')
                ]);
            } else {
                $product->warranty()->create([
                    'months' => $request->input('warranty_months')
                ]);
            }
        } elseif ($product->warranty) {
            $product->warranty()->delete();
        }

        // Handle text ads
        $product->adsText()->delete();
        if ($request->filled('text_ads')) {
            foreach ($request->text_ads as $ad) {
                if (!empty($ad['name'])) {
                    $product->adsText()->create([
                        'name' => $ad['name']
                    ]);
                }
            }
        }

        // Handle additional images
        if ($request->hasFile('additional_images')) {
            $order = $product->images()->where('type', 'additional')->max('order') ?? 1;
            
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('products/additional', 'public');
                
                $product->images()->create([
                    'path' => $path,
                    'alt' => $validated['name'] ?? 'product_additional_image',
                    'is_primary' => false,
                    'type' => 'additional',
                    'order' => ++$order
                ]);
            }
        }

        // Handle removal of existing additional images
        if ($request->filled('removed_existing_images')) {
            $removedIds = explode(',', $request->removed_existing_images);
            foreach ($removedIds as $imageId) {
                if ($imageId) {
                    $image = Image::find($imageId);
                    if ($image && $image->imageable_id == $product->id) {
                        Storage::disk('public')->delete($image->path);
                        $image->delete();
                    }
                }
            }
        }

        DB::commit();

        return redirect()->route('admin.products.show', $product->id)
            ->with('success', 'تم تحديث المنتج بنجاح');
            
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error updating product: ' . $e->getMessage());
        
        return redirect()->back()
            ->withInput()
            ->with('error', 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage());
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
    // public function export()
    // {
    //     return Excel::download(new ProductsExport, 'products_' . now()->format('Y-m-d') . '.xlsx');
    // }
}
