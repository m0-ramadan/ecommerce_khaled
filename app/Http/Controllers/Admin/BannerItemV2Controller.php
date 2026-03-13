<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Models\Product;
use App\Models\Category;
use App\Models\BannerItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BannerItemV2Controller extends Controller
{
    /**
     * Display a listing of banner items.
     */
    public function index(Request $request)
    {
        //   dd('index method called with filters:');
        $query = BannerItem::with(['banner', 'promoCodes'])
            ->orderBy('banner_id')
            ->orderBy('item_order');

        // Filter by banner
        if ($request->has('banner_id') && $request->banner_id) {
            $query->where('banner_id', $request->banner_id);
        }

        // Filter by active status
        if ($request->has('is_active') && $request->is_active !== '') {
            $query->where('is_active', $request->is_active);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('image_alt', 'LIKE', "%{$search}%")
                    ->orWhere('tag_text', 'LIKE', "%{$search}%")
                    ->orWhere('link_url', 'LIKE', "%{$search}%");
            });
        }

        $bannerItems = $query->paginate(15);
        $banners = Banner::all();
        $products = Product::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();

        return view('Admin.banner_items.index', compact('bannerItems', 'banners', 'products', 'categories'));
    }

    /**
     * Show the form for creating a new banner item.
     */
    public function create(Request $request)
    {
        $banners = Banner::all();
        $products = Product::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        $selectedBannerId = $request->get('banner_id');

        // Get next order number for the selected banner
        $nextOrder = 1;
        if ($selectedBannerId) {
            $lastItem = BannerItem::where('banner_id', $selectedBannerId)
                ->orderBy('item_order', 'desc')
                ->first();
            $nextOrder = $lastItem ? $lastItem->item_order + 1 : 1;
        }

        return view('Admin.banner_items.create', compact('banners', 'products', 'categories', 'selectedBannerId', 'nextOrder'));
    }

    /**
     * Store a newly created banner item.
     */
    public function store(Request $request)
    {
        $request->validate([
            'banner_id' => 'required|exists:banners,id',
            'item_order' => 'required|integer|min:1',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'mobile_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:255',
            'link_target' => 'nullable|in:_self,_blank',
            'is_link_active' => 'boolean',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'tag_text' => 'nullable|string|max:100',
            'tag_color' => 'nullable|string|max:20',
            'tag_bg_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'promo_codes' => 'nullable|array',
            'promo_codes.*' => 'exists:promo_codes,id',
        ]);

        $data = $request->except(['image_url', 'mobile_image_url', 'promo_codes']);

        // Set default values
        $data['is_link_active'] = $request->has('is_link_active');
        $data['is_active'] = $request->has('is_active');

        // Handle image uploads
        if ($request->hasFile('image_url')) {
            $data['image_url'] = $this->uploadImage($request->file('image_url'), 'banners/items');
        }

        if ($request->hasFile('mobile_image_url')) {
            $data['mobile_image_url'] = $this->uploadImage($request->file('mobile_image_url'), 'banners/items/mobile');
        }

        // Create banner item
        $bannerItem = BannerItem::create($data);

        // Attach promo codes if any
        if ($request->has('promo_codes')) {
            $bannerItem->promoCodes()->sync($request->promo_codes);
        }

        return redirect()
            ->route('admin.banners.items.edit', $bannerItem->id)
            ->with('success', 'تم إنشاء عنصر البانر بنجاح');
    }

    /**
     * Display the specified banner item.
     */
    public function show(BannerItem $bannerItem)
    {
        $bannerItem->load(['banner', 'promoCodes']);

        // Get linked product or category if exists
        $linkedItem = null;
        if ($bannerItem->product_id) {
            $linkedItem = Product::find($bannerItem->product_id);
        } elseif ($bannerItem->category_id) {
            $linkedItem = Category::find($bannerItem->category_id);
        }

        return view('Admin.banner_items.show', compact('bannerItem', 'linkedItem'));
    }

    /**
     * Show the form for editing the specified banner item.
     */
    public function edit(BannerItem $bannerItem)
    {
        $banners = Banner::all();
        $products = Product::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        $selectedPromoCodes = $bannerItem->promoCodes->pluck('id')->toArray();

        return view('Admin.banner_items.edit', compact('bannerItem', 'banners', 'products', 'categories', 'selectedPromoCodes'));
    }

    /**
     * Update the specified banner item.
     */
    public function update(Request $request, BannerItem $bannerItem)
    {
        $request->validate([
            'banner_id' => 'required|exists:banners,id',
            'item_order' => 'required|integer|min:1',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'mobile_image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:255',
            'link_target' => 'nullable|in:_self,_blank',
            'is_link_active' => 'boolean',
            'product_id' => 'nullable|exists:products,id',
            'category_id' => 'nullable|exists:categories,id',
            'tag_text' => 'nullable|string|max:100',
            'tag_color' => 'nullable|string|max:20',
            'tag_bg_color' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'promo_codes' => 'nullable|array',
            'promo_codes.*' => 'exists:promo_codes,id',
        ]);

        $data = $request->except(['image_url', 'mobile_image_url', 'promo_codes']);

        // Set boolean values
        $data['is_link_active'] = $request->has('is_link_active');
        $data['is_active'] = $request->has('is_active');

        // Handle image uploads
        if ($request->hasFile('image_url')) {
            // Delete old image
            if ($bannerItem->image_url) {
                Storage::disk('public')->delete($bannerItem->image_url);
            }
            $data['image_url'] = $this->uploadImage($request->file('image_url'), 'banners/items');
        }

        if ($request->hasFile('mobile_image_url')) {
            // Delete old image
            if ($bannerItem->mobile_image_url) {
                Storage::disk('public')->delete($bannerItem->mobile_image_url);
            }
            $data['mobile_image_url'] = $this->uploadImage($request->file('mobile_image_url'), 'banners/items/mobile');
        }

        // Update banner item
        $bannerItem->update($data);

        // Sync promo codes
        if ($request->has('promo_codes')) {
            $bannerItem->promoCodes()->sync($request->promo_codes);
        } else {
            $bannerItem->promoCodes()->detach();
        }

        return redirect()
            ->route('admin.banners.items.edit', $bannerItem->id)
            ->with('success', 'تم تحديث عنصر البانر بنجاح');
    }

    /**
     * Remove the specified banner item.
     */
    public function destroy(BannerItem $bannerItem)
    {
        try {
            // Delete images
            if ($bannerItem->image_url) {
                Storage::disk('public')->delete($bannerItem->image_url);
            }
            if ($bannerItem->mobile_image_url) {
                Storage::disk('public')->delete($bannerItem->mobile_image_url);
            }

            // Detach promo codes
            $bannerItem->promoCodes()->detach();

            // Delete the item
            $bannerItem->delete();

            return response()->json([
                'success' => true,
                'message' => 'تم حذف عنصر البانر بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحذف: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Toggle banner item status.
     */
    public function toggleStatus(BannerItem $bannerItem)
    {
        $bannerItem->is_active = !$bannerItem->is_active;
        $bannerItem->save();

        return response()->json([
            'success' => true,
            'is_active' => $bannerItem->is_active,
            'message' => 'تم تغيير حالة عنصر البانر بنجاح'
        ]);
    }

    /**
     * Reorder banner items.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:banner_items,id',
            'items.*.order' => 'required|integer|min:1',
        ]);

        foreach ($request->items as $item) {
            BannerItem::where('id', $item['id'])->update(['item_order' => $item['order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إعادة ترتيب العناصر بنجاح'
        ]);
    }

    /**
     * Upload image helper function.
     */
    private function uploadImage($file, $path)
    {
        $fileName = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($path, $fileName, 'public');
    }
}
