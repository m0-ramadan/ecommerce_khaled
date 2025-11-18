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

            // 🟢 جلب الأقسام الرئيسية
            $categories = Category::where('status_id', 1)
                ->whereNull('parent_id')
                ->get();

            // 🟢 جلب الأقسام الفرعية
            $sub_categories = Category::where('status_id', 1)
                ->whereNotNull('parent_id')
                ->take(7)
                ->get();

            // 🟢 جلب المنتجات
            $products = Product::where('status_id', 1)
                ->take(10)
                ->get();

            // ============================
            // 🎯 جلب السلايدر فقط (main_slider)
            // ============================

            $query = Banner::with([
                'type',
                'items',
                'sliderSetting',
                'gridLayout'
            ])->where('is_active', true);

            $query->whereHas('type', function ($q) {
                $q->where('name', 'main_slider');
            });

            $today = now();

            $query->where(function ($q) use ($today) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })->where(function ($q) use ($today) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $today);
            });

            $banners = $query->orderBy('section_order')->first();

            // ============================
            // 📦 البيانات النهائية
            // ============================

            $data = [
                'categories'     => CategoryResource::collection($categories),
                'sub_categories' => CategoryWithProductResource::collection($sub_categories),
                //  'products'       => ProductResource::collection($products),
                'sliders'        => new BannerResource($banners),
            ];

            return $this->success($data, 'تم جلب بيانات الصفحة الرئيسية بنجاح');
        } catch (\Exception $e) {

            return $this->error('حدث خطأ أثناء تحميل بيانات الصفحة الرئيسية', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }
}
