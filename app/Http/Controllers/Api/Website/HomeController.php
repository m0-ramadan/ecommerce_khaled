<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Banner;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\BannerResource;
use App\Http\Resources\Website\ProductResource;
use App\Http\Resources\Website\CategoryResource;
use App\Http\Resources\Website\CategoryWithProductResource;

class HomeController extends Controller
{
    use ApiResponseTrait;

    /**
     * 🔹 عرض بيانات الصفحة الرئيسية
     */
    public function index(Request $request)
    {
        try {

            // 🟢 limit جاي من الفرونت أو default = 5
            $subCategoriesLimit = $request->input('categories_limit', 5);

            // 🟢 جلب الأقسام الفرعية
            $sub_categories = Category::where('status_id', 1)
                ->whereHas('products', function ($q) {
                    $q->where('status_id', 1);
                })
                ->orderBy('order', 'asc')
                ->limit($subCategoriesLimit)
                ->get();

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
                'sliders'        => new BannerResource($banners),
            ], 'تم جلب بيانات الصفحة الرئيسية بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء تحميل البيانات', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
