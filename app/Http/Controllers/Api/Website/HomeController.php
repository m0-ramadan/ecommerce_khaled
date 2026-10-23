<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Website\BannerResource;
use App\Http\Resources\Website\HomeCategoryResource;
use App\Http\Resources\Wesite\TestimonialResource;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    use ApiResponseTrait;

    /**
     * 🔹 عرض بيانات الصفحة الرئيسية
     */
    public function index(Request $request)
    {
        try {

            $subCategoriesLimit = min(max((int) $request->input('categories_limit', 4), 1), 8);
            $productsLimit = min(max((int) $request->input('products_limit', 4), 1), 12);
            $hasHomeVisibility = Schema::hasColumn('products', 'show_in_home');

            $visibleProductQuery = function ($query) use ($hasHomeVisibility) {
                $query->where('status_id', 1);

                if ($hasHomeVisibility) {
                    $query->where('show_in_home', true);
                }
            };

            // الصفحة الرئيسية تعرض الأقسام الرئيسية فقط، والأقسام الفرعية تظهر داخل صفحة القسم.
            $sub_categories = Category::where('status_id', 1)
                ->whereNull('parent_id')
                ->where(function ($query) use ($visibleProductQuery) {
                    $query->whereHas('products', $visibleProductQuery)
                        ->orWhereHas('children.products', $visibleProductQuery);
                })
                ->orderBy('order', 'asc')
                ->paginate($subCategoriesLimit)
                ->withQueryString();

            $sub_categories->getCollection()->each(function (Category $category) use ($productsLimit) {
                // منتجات القسم الرئيسي تشمل منتجات أقسامه الفرعية أيضاً.
                $categoryIds = array_merge(
                    [$category->id],
                    $category->children()->pluck('id')->all()
                );

                $productsQuery = Product::query()
                    ->where('status_id', 1)
                    ->whereIn('category_id', $categoryIds)
                    ->select(['id', 'category_id', 'name', 'slug', 'price', 'price_text', 'has_discount', 'stock', 'status_id'])
                    ->with(['primaryImage', 'discount', 'adsText'])
                    ->withCount('reviews')
                    ->withAvg('reviews', 'rating')
                    ->withMin('sizeTiers as lowest_price', 'price_per_unit');

                // ترتيب وإظهار المنتجات في الرئيسية حسب إعدادات الداشبورد (عند توفر الحقول).
                if (Schema::hasColumn('products', 'show_in_home')) {
                    $productsQuery->where('show_in_home', true);
                }
                if (Schema::hasColumn('products', 'home_order')) {
                    // المنتجات ذات ترتيب مخصص (1، 2، 3...) تظهر أولاً، ثم الباقي الأحدث.
                    $productsQuery
                        ->orderByRaw('CASE WHEN home_order = 0 THEN 1 ELSE 0 END')
                        ->orderBy('home_order', 'asc');
                }

                $products = $productsQuery
                    ->orderByDesc('id')
                    ->limit($productsLimit)
                    ->get();

                $category->setRelation('products', $products);
                $category->load('categoryBanners');
            });


            // ============================
            // 🎯 جلب السلايدر
            // ============================
            $banners = $request->boolean('include_sliders') ? Banner::with([
                'type',
                'items',
                'sliderSetting',
                'gridLayout'
            ])
                ->where('is_active', true)
                ->whereHas('type', fn($q) => $q->where('name', 'main_slider'))
                ->where(function ($q) {
                    $q->whereNull('start_date')->orWhere('start_date', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_date')->orWhere('end_date', '>=', now());
                })
                ->orderBy('section_order')
                ->first() : null;
            return $this->success([
                'sub_categories' => HomeCategoryResource::collection($sub_categories),

                'sub_categories_pagination' => [
                    'current_page' => $sub_categories->currentPage(),
                    'last_page'    => $sub_categories->lastPage(),
                    'per_page'     => $sub_categories->perPage(),
                    'total'        => $sub_categories->total(),
                    'next_page'    => $sub_categories->nextPageUrl(),
                    'prev_page'    => $sub_categories->previousPageUrl(),
                ],

                'sliders' => $banners ? new BannerResource($banners) : null,
            ], 'تم جلب بيانات الصفحة الرئيسية بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء تحميل البيانات', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
    public function testimonials()
    {
        try {
            $testimonials = \App\Models\Testimonial::where('is_active', true)
                ->orderBy('sort_order', 'asc')
                ->get();

            return $this->success(TestimonialResource::collection($testimonials), 'تم جلب الشهادات بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب الشهادات', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
