<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerType;
use App\Models\Category;
use App\Models\Product;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::with(['type', 'items', 'gridLayout', 'sliderSetting']);

        // Filter by type
        if ($request->has('type') && $request->type != 'all') {
            $query->whereHas('type', function ($q) use ($request) {
                $q->where('name', $request->type);
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('is_active', $request->status == 'active');
        }

        $banners = $query->orderBy('section_order')->paginate(10);
        $bannerTypes = BannerType::all();

        return view('Admin.banners.index', compact('banners', 'bannerTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $bannerTypes = BannerType::all();
        $categories = Category::where('status_id', 1)->get();
        $products = Product::where('status', 'active')->limit(100)->get();
        $promoCodes = PromoCode::where('is_active', true)
            ->where('end_date', '>=', now())
            ->get();

        return view('Admin.banners.create', compact('bannerTypes', 'categories', 'products', 'promoCodes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'banner_type_id' => 'required|exists:banner_types,id',
            'section_order' => 'required|integer',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $banner = Banner::create($validated);

        // Create grid layout if banner type is grid
        if ($request->banner_type_id == 2) { // Assuming 2 is grid type
            $this->createGridLayout($banner->id, $request);
        }

        // Create slider setting if banner type is slider
        if ($request->banner_type_id == 1) { // Assuming 1 is slider type
            $this->createSliderSetting($banner->id, $request);
        }

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        $banner->load(['type', 'items', 'gridLayout', 'sliderSetting']);
        return view('Admin.banners.show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        $banner->load(['items', 'gridLayout', 'sliderSetting']);
        $bannerTypes = BannerType::all();
        $categories = Category::where('status_id', 1)->get();
        $products = Product::where('status', 'active')->limit(100)->get();
        $promoCodes = PromoCode::where('is_active', true)
            ->where('end_date', '>=', now())
            ->get();

        return view('Admin.banners.edit', compact('banner', 'bannerTypes', 'categories', 'products', 'promoCodes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'banner_type_id' => 'required|exists:banner_types,id',
            'section_order' => 'required|integer',
            'is_active' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $banner->update($validated);

        // Update grid layout
        if ($request->banner_type_id == 2) {
            $this->updateGridLayout($banner->id, $request);
        }

        // Update slider setting
        if ($request->banner_type_id == 1) {
            $this->updateSliderSetting($banner->id, $request);
        }

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        // Delete associated items first
        foreach ($banner->items as $item) {
            $this->deleteItemImages($item);
        }

        $banner->delete();

        return redirect()->route('admin.banners.index')
            ->with('success', 'Banner deleted successfully.');
    }

    /**
     * Toggle banner status
     */
    public function toggleStatus(Banner $banner)
    {
        $banner->update(['is_active' => !$banner->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $banner->is_active
        ]);
    }

    /**
     * Create grid layout
     */
    private function createGridLayout($bannerId, $request)
    {
        \App\Models\BannerGridLayout::create([
            'banner_id' => $bannerId,
            'grid_type' => $request->grid_type ?? 'responsive',
            'desktop_columns' => $request->desktop_columns ?? 3,
            'tablet_columns' => $request->tablet_columns ?? 2,
            'mobile_columns' => $request->mobile_columns ?? 1,
            'row_gap' => $request->row_gap ?? 20,
            'column_gap' => $request->column_gap ?? 20,
        ]);
    }

    /**
     * Update grid layout
     */
    private function updateGridLayout($bannerId, $request)
    {
        $gridLayout = \App\Models\BannerGridLayout::where('banner_id', $bannerId)->first();
        
        if ($gridLayout) {
            $gridLayout->update([
                'grid_type' => $request->grid_type ?? 'responsive',
                'desktop_columns' => $request->desktop_columns ?? 3,
                'tablet_columns' => $request->tablet_columns ?? 2,
                'mobile_columns' => $request->mobile_columns ?? 1,
                'row_gap' => $request->row_gap ?? 20,
                'column_gap' => $request->column_gap ?? 20,
            ]);
        } else {
            $this->createGridLayout($bannerId, $request);
        }
    }

    /**
     * Create slider setting
     */
    private function createSliderSetting($bannerId, $request)
    {
        \App\Models\SliderSetting::create([
            'banner_id' => $bannerId,
            'autoplay' => $request->autoplay ?? true,
            'autoplay_speed' => $request->autoplay_speed ?? 3000,
            'arrows' => $request->arrows ?? true,
            'dots' => $request->dots ?? true,
            'infinite' => $request->infinite ?? true,
        ]);
    }

    /**
     * Update slider setting
     */
    private function updateSliderSetting($bannerId, $request)
    {
        $sliderSetting = \App\Models\SliderSetting::where('banner_id', $bannerId)->first();
        
        if ($sliderSetting) {
            $sliderSetting->update([
                'autoplay' => $request->autoplay ?? true,
                'autoplay_speed' => $request->autoplay_speed ?? 3000,
                'arrows' => $request->arrows ?? true,
                'dots' => $request->dots ?? true,
                'infinite' => $request->infinite ?? true,
            ]);
        } else {
            $this->createSliderSetting($bannerId, $request);
        }
    }

    /**
     * Delete item images
     */
    private function deleteItemImages($item)
    {
        if ($item->image_url && Storage::exists($item->image_url)) {
            Storage::delete($item->image_url);
        }
        
        if ($item->mobile_image_url && Storage::exists($item->mobile_image_url)) {
            Storage::delete($item->mobile_image_url);
        }
    }
}