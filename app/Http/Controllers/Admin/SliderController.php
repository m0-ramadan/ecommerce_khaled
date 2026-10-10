<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerItem;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SliderController extends Controller
{
    /**
     * Get or create the main homepage slider banner
     */
    protected function getMainSlider(): Banner
    {
        $slider = Banner::where('banner_type_id', 1)->first();

        if (!$slider) {
            $slider = Banner::create([
                'title' => 'السلايدر الرئيسي',
                'banner_type_id' => 1,
                'section_order' => 1,
                'is_active' => true,
            ]);
        }

        return $slider;
    }

    /**
     * Display a listing of slides
     */
    public function index()
    {
        $mainSlider = $this->getMainSlider();

        $slides = BannerItem::where('banner_id', $mainSlider->id)
            ->orderBy('item_order', 'asc')
            ->orderBy('id', 'desc')
            ->get();

        $totalSlides = $slides->count();
        $activeSlides = $slides->where('is_active', true)->count();
        $inactiveSlides = $slides->where('is_active', false)->count();

        return view('Admin.slider.index', compact(
            'mainSlider',
            'slides',
            'totalSlides',
            'activeSlides',
            'inactiveSlides'
        ));
    }

    /**
     * Show the form for creating a new slide
     */
    public function create()
    {
        $mainSlider = $this->getMainSlider();

        $lastOrder = BannerItem::where('banner_id', $mainSlider->id)->max('item_order');
        $nextOrder = $lastOrder ? $lastOrder + 1 : 1;

        $categories = Category::select('id', 'name', 'slug')->orderBy('name')->get();
        $products = Product::select('id', 'name', 'slug')->orderBy('name')->get();

        return view('Admin.slider.create', compact('mainSlider', 'nextOrder', 'categories', 'products'));
    }

    /**
     * Store a newly created slide
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'image_alt' => 'nullable|string|max:255',
            'link_type' => 'nullable|in:none,custom,category,product',
            'custom_link' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'link_target' => 'nullable|in:_self,_blank',
            'item_order' => 'required|integer|min:1',
            'is_active' => 'nullable',
        ], [
            'image.required' => 'صورة السلايدر مطلوبة',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 5 ميجابايت',
            'mobile_image.image' => 'ملف صورة الجوال يجب أن يكون صورة',
            'mobile_image.max' => 'حجم صورة الجوال لا يجب أن يتجاوز 5 ميجابايت',
            'item_order.required' => 'ترتيب الشريحة مطلوب',
        ]);

        $mainSlider = $this->getMainSlider();

        // Handle link destination
        $linkUrl = null;
        $categoryId = null;
        $productId = null;
        $linkType = $request->input('link_type', 'none');

        if ($linkType === 'category' && $request->filled('category_id')) {
            $categoryId = $request->category_id;
            $cat = Category::find($categoryId);
            $linkUrl = $cat ? '/category/' . ($cat->slug ?: $cat->id) : null;
        } elseif ($linkType === 'product' && $request->filled('product_id')) {
            $productId = $request->product_id;
            $prod = Product::find($productId);
            $linkUrl = $prod ? '/product/' . ($prod->slug ?: $prod->id) : null;
        } elseif ($linkType === 'custom' && $request->filled('custom_link')) {
            $linkUrl = $request->custom_link;
        }

        // Upload desktop image
        $imagePath = $this->uploadImageFile($request->file('image'));

        // Upload mobile image if present
        $mobileImagePath = null;
        if ($request->hasFile('mobile_image')) {
            $mobileImagePath = $this->uploadImageFile($request->file('mobile_image'));
        }

        BannerItem::create([
            'banner_id' => $mainSlider->id,
            'item_order' => $request->input('item_order', 1),
            'image_url' => $imagePath,
            'mobile_image_url' => $mobileImagePath,
            'image_alt' => $request->input('image_alt'),
            'link_url' => $linkUrl,
            'link_target' => $request->input('link_target', '_self'),
            'is_link_active' => !empty($linkUrl),
            'category_id' => $categoryId,
            'product_id' => $productId,
            'is_active' => $request->has('is_active') ? (bool)$request->is_active : true,
        ]);

        return redirect()->route('admin.sliders.index')->with('success', 'تمت إضافة شريحة السلايدر بنجاح!');
    }

    /**
     * Show the form for editing the slide
     */
    public function edit($id)
    {
        $slide = BannerItem::findOrFail($id);
        $categories = Category::select('id', 'name', 'slug')->orderBy('name')->get();
        $products = Product::select('id', 'name', 'slug')->orderBy('name')->get();

        // Determine link type
        $linkType = 'none';
        if ($slide->category_id) {
            $linkType = 'category';
        } elseif ($slide->product_id) {
            $linkType = 'product';
        } elseif (!empty($slide->link_url)) {
            $linkType = 'custom';
        }

        return view('Admin.slider.edit', compact('slide', 'categories', 'products', 'linkType'));
    }

    /**
     * Update the slide
     */
    public function update(Request $request, $id)
    {
        $slide = BannerItem::findOrFail($id);

        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'mobile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg,gif|max:5120',
            'image_alt' => 'nullable|string|max:255',
            'link_type' => 'nullable|in:none,custom,category,product',
            'custom_link' => 'nullable|string|max:500',
            'category_id' => 'nullable|exists:categories,id',
            'product_id' => 'nullable|exists:products,id',
            'link_target' => 'nullable|in:_self,_blank',
            'item_order' => 'required|integer|min:1',
            'is_active' => 'nullable',
        ], [
            'image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'image.max' => 'حجم الصورة لا يجب أن يتجاوز 5 ميجابايت',
            'mobile_image.image' => 'ملف صورة الجوال يجب أن يكون صورة',
            'mobile_image.max' => 'حجم صورة الجوال لا يجب أن يتجاوز 5 ميجابايت',
            'item_order.required' => 'ترتيب الشريحة مطلوب',
        ]);

        // Handle link destination
        $linkUrl = null;
        $categoryId = null;
        $productId = null;
        $linkType = $request->input('link_type', 'none');

        if ($linkType === 'category' && $request->filled('category_id')) {
            $categoryId = $request->category_id;
            $cat = Category::find($categoryId);
            $linkUrl = $cat ? '/category/' . ($cat->slug ?: $cat->id) : null;
        } elseif ($linkType === 'product' && $request->filled('product_id')) {
            $productId = $request->product_id;
            $prod = Product::find($productId);
            $linkUrl = $prod ? '/product/' . ($prod->slug ?: $prod->id) : null;
        } elseif ($linkType === 'custom' && $request->filled('custom_link')) {
            $linkUrl = $request->custom_link;
        }

        // Update desktop image if new file uploaded
        if ($request->hasFile('image')) {
            $this->deleteStoredFile($slide->image_url);
            $slide->image_url = $this->uploadImageFile($request->file('image'));
        }

        // Update mobile image if new file uploaded
        if ($request->hasFile('mobile_image')) {
            $this->deleteStoredFile($slide->mobile_image_url);
            $slide->mobile_image_url = $this->uploadImageFile($request->file('mobile_image'));
        } elseif ($request->has('remove_mobile_image') && $request->remove_mobile_image == '1') {
            $this->deleteStoredFile($slide->mobile_image_url);
            $slide->mobile_image_url = null;
        }

        $slide->item_order = $request->input('item_order', $slide->item_order);
        $slide->image_alt = $request->input('image_alt');
        $slide->link_url = $linkUrl;
        $slide->link_target = $request->input('link_target', '_self');
        $slide->is_link_active = !empty($linkUrl);
        $slide->category_id = $categoryId;
        $slide->product_id = $productId;
        $slide->is_active = $request->has('is_active') ? (bool)$request->is_active : false;

        $slide->save();

        return redirect()->route('admin.sliders.index')->with('success', 'تم تحديث شريحة السلايدر بنجاح!');
    }

    /**
     * Remove the slide
     */
    public function destroy($id)
    {
        $slide = BannerItem::findOrFail($id);

        $this->deleteStoredFile($slide->image_url);
        $this->deleteStoredFile($slide->mobile_image_url);

        $slide->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف شريحة السلايدر بنجاح!',
            ]);
        }

        return redirect()->route('admin.sliders.index')->with('success', 'تم حذف شريحة السلايدر بنجاح!');
    }

    /**
     * Toggle slide active status
     */
    public function toggleStatus($id)
    {
        $slide = BannerItem::findOrFail($id);
        $slide->is_active = !$slide->is_active;
        $slide->save();

        return response()->json([
            'success' => true,
            'is_active' => (bool)$slide->is_active,
            'message' => $slide->is_active ? 'تم تفعيل الشريحة بنجاح' : 'تم تعطيل الشريحة بنجاح',
        ]);
    }

    /**
     * Reorder slides
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:banner_items,id',
            'orders.*.order' => 'required|integer',
        ]);

        foreach ($request->orders as $item) {
            BannerItem::where('id', $item['id'])->update(['item_order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث ترتيب الشرائح بنجاح',
        ]);
    }

    /**
     * Helper to store uploaded image in public storage
     */
    protected function uploadImageFile($file): string
    {
        $extension = $file->getClientOriginalExtension() ?: 'webp';
        $fileName = Str::random(24) . '.' . $extension;
        $file->storeAs('banners', $fileName, 'public');

        return 'banners/' . $fileName;
    }

    /**
     * Helper to safely delete file from storage
     */
    protected function deleteStoredFile(?string $path): void
    {
        if ($path && !str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}

