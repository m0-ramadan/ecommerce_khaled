<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Category;
use App\Models\Color;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\CategoryResource;
use App\Http\Resources\Website\CategoryWithProductResource;
use App\Http\Resources\Website\ProductListResource;

class CategoryController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        try {

            $query = Category::where('status_id', 1);

            switch ($request->get('type')) {
                case 'parent':
                    $query->whereNull('parent_id');
                    $resource = CategoryResource::class;
                    break;
                case 'child':
                    $query->whereNotNull('parent_id');
                    $resource = CategoryWithProductResource::class;
                    break;
                default:
                    $resource = CategoryResource::class;

                    break;
            }

            $categories = $query->get();

            return $this->success($resource::collection($categories), 'تم جلب الأقسام بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب الأقسام', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    public function show($identifier)
    {
        try {
            $category = Category::with(['children', 'categoryBanners'])
                ->where(function ($query) use ($identifier) {
                    $query->where('id', $identifier)
                          ->orWhere('slug', $identifier);
                })->first();

            if (!$category) {
                return $this->error('القسم غير موجود', 404);
            }

            return $this->success(new CategoryWithProductResource($category), 'تم جلب بيانات القسم بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب بيانات القسم', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * منتجات القسم مع Pagination وفلترة وترتيب من السيرفر.
     * الأقسام الرئيسية تشمل منتجات الأقسام الفرعية التابعة لها.
     */
    public function products(Request $request, $identifier)
    {
        try {
            $category = Category::where(function ($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })->first();

            if (!$category) {
                return $this->error('القسم غير موجود', 404);
            }

            $categoryIds = [$category->id];
            if ($category->isParent()) {
                $categoryIds = array_merge($categoryIds, $category->children()->pluck('id')->all());
            }

            $perPage = min(max((int) $request->input('per_page', 12), 1), 24);

            $query = Product::query()
                ->where('status_id', 1)
                ->whereIn('category_id', $categoryIds)
                ->with(['primaryImage', 'discount', 'adsText'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->withMin('sizeTiers as lowest_price', 'price_per_unit');

            if ($request->boolean('available')) {
                $query->where('stock', '>', 0);
            }

            $selectedCategories = array_values(array_filter((array) $request->input('categories', [])));
            if (!empty($selectedCategories)) {
                $query->whereIn('category_id', $selectedCategories);
            }

            $materials = array_values(array_filter((array) $request->input('materials', [])));
            if (!empty($materials)) {
                $query->whereHas('materials', fn ($q) => $q->whereIn('name', $materials));
            }

            $colors = array_values(array_filter((array) $request->input('colors', [])));
            if (!empty($colors)) {
                $query->whereHas('colors', fn ($q) => $q->whereIn('colors.name', $colors));
            }

            switch ($request->input('sort')) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'rating':
                    $query->orderByDesc('reviews_avg_rating');
                    break;
                default:
                    $query->orderByDesc('id');
                    break;
            }

            $products = $query->paginate($perPage)->withQueryString();

            $filters = null;
            if ($request->boolean('include_filters')) {
                $filters = [
                    'materials' => Material::whereHas('products', function ($q) use ($categoryIds) {
                        $q->whereIn('category_id', $categoryIds)->where('status_id', 1);
                    })->orderBy('name')->pluck('name')->unique()->values(),

                    'colors' => Color::whereHas('products', function ($q) use ($categoryIds) {
                        $q->whereIn('category_id', $categoryIds)->where('status_id', 1);
                    })->orderBy('name')->pluck('name')->unique()->values(),
                ];
            }

            return $this->success([
                'products' => ProductListResource::collection($products),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page'    => $products->lastPage(),
                    'per_page'     => $products->perPage(),
                    'total'        => $products->total(),
                ],
                'filters' => $filters,
            ], 'تم جلب منتجات القسم بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب منتجات القسم', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
