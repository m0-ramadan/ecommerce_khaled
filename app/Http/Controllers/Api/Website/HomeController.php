<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\Website\BannerResource;
use App\Http\Resources\Website\CategoryResource;
use App\Http\Resources\Website\CategoryWithProductResource;
use App\Http\Resources\Website\ProductResource;
use App\Http\Resources\Wesite\TestimonialResource;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponseTrait;

    /**
     * 🔹 عرض بيانات الصفحة الرئيسية
     */
    public function index(Request $request)
    {
        try {

            $subCategoriesLimit = $request->input('categories_limit', 5);

            $sub_categories = Category::where('status_id', 1)
                ->whereHas('products', function ($q) {
                    $q->where('status_id', 1);
                })
                ->orderBy('order', 'asc')
                ->paginate($subCategoriesLimit);


            // ============================
            // 🎯 جلب السلايدر
            // ============================
            $banners = Banner::with([
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
                ->first();
            return $this->success([
                'sub_categories' => CategoryWithProductResource::collection($sub_categories),

                'sub_categories_pagination' => [
                    'current_page' => $sub_categories->currentPage(),
                    'last_page'    => $sub_categories->lastPage(),
                    'per_page'     => $sub_categories->perPage(),
                    'total'        => $sub_categories->total(),
                    'next_page'    => $sub_categories->nextPageUrl(),
                    'prev_page'    => $sub_categories->previousPageUrl(),
                ],

                'sliders'    => new BannerResource($banners)
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
