<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Resources\Website\ColorResource;
use App\Models\Category;
use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\MaterialResource;
use App\Http\Resources\Website\ProductListResource;
use App\Http\Resources\Website\ProductResource;
use App\Models\Color;

class ProductController extends Controller
{
    use ApiResponseTrait;


    public function index(Request $request)
    {
        try {
            $query = Product::with([
                'category',
                'discount',
                'colors',
                'deliveryTime',
                'warranty',
                'features',
                'reviews',
                'sizes',
                'offers',
                'materials'
            ])->where('status_id', 1);
            $perPage = min(max((int) $request->get('per_page', 8), 1), 24);
            $products = $query
                ->filtered($request)
                ->searched($request->get('search'))
                ->sorted($request)
                ->paginate($perPage);

            return $this->paginated(
                ProductResource::collection($products),
                'تم جلب المنتجات بنجاح'
            );
        } catch (\Throwable $e) {
            return $this->error('حدث خطأ أثناء جلب المنتجات', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }


    public function show($identifier)
    {
        try {
            $product = Product::with([
                'category',
                'discount',
                'colors',
                'deliveryTime',
                'warranty',
                'features',
                'reviews',
                'sizes',
                'offers',
                'materials'
            ])
            ->where(function($query) use ($identifier) {
                $query->where('id', $identifier)
                      ->orWhere('slug', $identifier);
            })
            ->first();

            if (!$product) {
                return $this->error('المنتج غير موجود', 404);
            }

            return $this->success(new ProductResource($product), 'تم جلب بيانات المنتج بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب بيانات المنتج', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
    /**
     * منتجات مشابهة (منتجات قد تعجبك) مع Pagination.
     */
    public function related(Request $request, $identifier)
    {
        try {
            $product = Product::select(['id', 'category_id'])
                ->where(function ($query) use ($identifier) {
                    $query->where('id', $identifier)
                          ->orWhere('slug', $identifier);
                })
                ->first();

            if (!$product) {
                return $this->error('المنتج غير موجود', 404);
            }

            $categoryIds = [];
            $category = $product->category_id ? Category::find($product->category_id) : null;

            if ($category) {
                $rootId = $category->parent_id ?: $category->id;
                $categoryIds = Category::where('parent_id', $rootId)
                    ->pluck('id')
                    ->push($rootId)
                    ->unique()
                    ->values()
                    ->all();
            }

            $perPage = min(max((int) $request->input('per_page', 12), 1), 24);

            $query = Product::query()
                ->where('status_id', 1)
                ->where('id', '!=', $product->id)
                ->when(!empty($categoryIds), fn ($q) => $q->whereIn('category_id', $categoryIds))
                ->with(['primaryImage', 'discount', 'adsText'])
                ->withCount('reviews')
                ->withAvg('reviews', 'rating')
                ->withMin('sizeTiers as lowest_price', 'price_per_unit')
                ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$product->category_id])
                ->orderByDesc('id');

            $products = $query->paginate($perPage)->withQueryString();

            return $this->success([
                'products' => ProductListResource::collection($products),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page'    => $products->lastPage(),
                    'per_page'     => $products->perPage(),
                    'total'        => $products->total(),
                ],
            ], 'تم جلب المنتجات المشابهة بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب المنتجات المشابهة', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 جلب جميع الألوان المتاحة للمنتجات
     */
    public function getColor()
    {
        try {
            $colors = Color::get();
            return $this->success(ColorResource::collection($colors), 'تم جلب الألوان بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب الألوان', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
    /**
     * 🔹 جلب جميع المواد المتاحة للمنتجات
     */
    public function getMaterial()
    {
        try {
            $materials = Material::get();
            return $this->success(MaterialResource::collection($materials), 'تم جلب المواد بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب المواد', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
