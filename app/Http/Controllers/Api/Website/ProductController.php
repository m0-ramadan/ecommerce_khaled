<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\Website\ProductResource;

class ProductController extends Controller
{
    use ApiResponseTrait;

    /**
     * 🔹 عرض جميع المنتجات
     */
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            // 🔍 فلترة حسب القسم (اختياري)
            if ($request->filled('category_id')) {
                $query->where('category_id', $request->get('category_id'));
            }

            // 🔍 فلترة حسب الحالة (مثلاً: متاح / غير متاح)
            if ($request->filled('status_id')) {
                $query->where('status_id', $request->get('status_id'));
            }

            // 🔍 بحث بالاسم
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->get('search') . '%');
            }

            // 🔽 ترتيب
            $query->orderBy('id', 'desc');

            // 📄 ترقيم النتائج (10 عناصر بالصفحة)
            $products = $query->paginate(10);

            return $this->paginated(ProductResource::collection($products), 'تم جلب المنتجات بنجاح');
        } catch (\Exception $e) {
            return $this->error('حدث خطأ أثناء جلب المنتجات', 500, [
                'exception' => $e->getMessage(),
            ]);
        }
    }

    /**
     * 🔹 عرض منتج واحد بالتفصيل
     */
    public function show($id)
    {
        try {
            $product = Product::find($id);

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
}
