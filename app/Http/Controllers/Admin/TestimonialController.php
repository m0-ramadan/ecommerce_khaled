<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the testimonials.
     */
    public function index(Request $request)
    {
        $query = Testimonial::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('review', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSorts = ['sort_order', 'name', 'rating', 'is_active', 'created_at', 'updated_at'];
        
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        $testimonials = $query->paginate($request->get('per_page', 15))->withQueryString();

        // Get statistics
        $statistics = [
            'total' => Testimonial::count(),
            'active' => Testimonial::where('is_active', true)->count(),
            'inactive' => Testimonial::where('is_active', false)->count(),
            'avg_rating' => round(Testimonial::where('is_active', true)->avg('rating') ?? 0, 1),
        ];

        return view('Admin.testimonials.index', compact('testimonials', 'statistics'));
    }

    /**
     * Show the form for creating a new testimonial.
     */
    public function create()
    {
        // Get the next sort order
        $nextSortOrder = Testimonial::max('sort_order') + 1;
        
        return view('Admin.testimonials.create', compact('nextSortOrder'));
    }

    /**
     * Store a newly created testimonial in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'يرجى إدخال اسم العميل',
            'name.max' => 'يجب ألا يتجاوز الاسم 255 حرف',
            'rating.required' => 'يرجى اختيار التقييم',
            'rating.min' => 'التقييم يجب أن يكون بين 1 و 5',
            'rating.max' => 'التقييم يجب أن يكون بين 1 و 5',
            'review.required' => 'يرجى كتابة المراجعة',
            'review.max' => 'يجب ألا تتجاوز المراجعة 1000 حرف',
            'avatar.image' => 'الملف يجب أن يكون صورة',
            'avatar.mimes' => 'صيغ الصور المدعومة: jpeg, png, jpg, webp',
            'avatar.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        }

        // Set default values
        $validated['is_active'] = $request->has('is_active');
        
        if (empty($validated['sort_order']) && $validated['sort_order'] !== '0') {
            $validated['sort_order'] = Testimonial::max('sort_order') + 1;
        }

        // Reorder if needed
        $this->reorderTestimonials($validated['sort_order']);

        $testimonial = Testimonial::create($validated);

        // Log activity (if using activity log)
        // activity()->performedOn($testimonial)->log('تم إضافة رأي عميل جديد');

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'تم إضافة رأي العميل بنجاح');
    }

    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial)
    {
        // For AJAX quick view
        if (request()->ajax() || request()->has('quick')) {
            return response()->json([
                'success' => true,
                'testimonial' => [
                    'id' => $testimonial->id,
                    'name' => $testimonial->name,
                    'city' => $testimonial->city,
                    'rating' => $testimonial->rating,
                    'review' => $testimonial->review,
                    'avatar_url' => $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : null,
                    'is_active' => $testimonial->is_active,
                    'sort_order' => $testimonial->sort_order,
                    'created_at' => $testimonial->created_at->format('Y-m-d H:i'),
                ]
            ]);
        }

        // Get related testimonials
        $relatedTestimonials = Testimonial::where('id', '!=', $testimonial->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(5)
            ->get();

        return view('Admin.testimonials.show', compact('testimonial', 'relatedTestimonials'));
    }

    /**
     * Show the form for editing the specified testimonial.
     */
    public function edit(Testimonial $testimonial)
    {
        $statistics = [
            'created_at' => $testimonial->created_at->diffForHumans(),
            'updated_at' => $testimonial->updated_at->diffForHumans(),
            'word_count' => str_word_count($testimonial->review),
            'rating_text' => getRatingText($testimonial->rating),
        ];

        return view('Admin.testimonials.edit', compact('testimonial', 'statistics'));
    }

    /**
     * Update the specified testimonial in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'remove_avatar' => 'boolean',
        ], [
            'name.required' => 'يرجى إدخال اسم العميل',
            'name.max' => 'يجب ألا يتجاوز الاسم 255 حرف',
            'rating.required' => 'يرجى اختيار التقييم',
            'rating.min' => 'التقييم يجب أن يكون بين 1 و 5',
            'rating.max' => 'التقييم يجب أن يكون بين 1 و 5',
            'review.required' => 'يرجى كتابة المراجعة',
            'review.max' => 'يجب ألا تتجاوز المراجعة 1000 حرف',
            'avatar.image' => 'الملف يجب أن يكون صورة',
            'avatar.mimes' => 'صيغ الصور المدعومة: jpeg, png, jpg, webp',
            'avatar.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت',
        ]);

        // Handle avatar
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('testimonials', 'public');
        } elseif ($request->has('remove_avatar') && $request->remove_avatar) {
            // Remove avatar
            if ($testimonial->avatar) {
                Storage::disk('public')->delete($testimonial->avatar);
            }
            $validated['avatar'] = null;
        }

        // Set is_active
        $validated['is_active'] = $request->has('is_active');

        // Reorder if sort_order changed
        if ($testimonial->sort_order != $validated['sort_order']) {
            $this->reorderTestimonials($validated['sort_order'], $testimonial->id);
        }

        $testimonial->update($validated);

        // Log activity
        // activity()->performedOn($testimonial)->log('تم تحديث رأي العميل');

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'تم تحديث رأي العميل بنجاح');
    }

    /**
     * Remove the specified testimonial from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonialName = $testimonial->name;

        // Delete avatar file
        if ($testimonial->avatar) {
            Storage::disk('public')->delete($testimonial->avatar);
        }

        $testimonial->delete();

        // Normalize sort orders
        $this->normalizeSortOrders();

        // Log activity
        // activity()->log("تم حذف رأي العميل: {$testimonialName}");

        // For AJAX requests
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف رأي العميل بنجاح',
            ]);
        }

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', 'تم حذف رأي العميل بنجاح');
    }

    /**
     * Handle bulk actions.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'ids' => 'required|string',
        ]);

        // Parse IDs from comma-separated string
        $ids = explode(',', $request->ids);
        $ids = array_filter($ids, 'is_numeric');
        
        if (empty($ids)) {
            return back()->with('error', 'لم يتم تحديد أي عناصر');
        }

        $action = $request->action;
        $count = count($ids);

        switch ($action) {
            case 'delete':
                // Delete avatars first
                $testimonials = Testimonial::whereIn('id', $ids)->get();
                foreach ($testimonials as $testimonial) {
                    if ($testimonial->avatar) {
                        Storage::disk('public')->delete($testimonial->avatar);
                    }
                }
                Testimonial::whereIn('id', $ids)->delete();
                $this->normalizeSortOrders();
                $message = "تم حذف {$count} آراء بنجاح";
                break;

            case 'activate':
                Testimonial::whereIn('id', $ids)->update(['is_active' => true]);
                $message = "تم تفعيل {$count} آراء بنجاح";
                break;

            case 'deactivate':
                Testimonial::whereIn('id', $ids)->update(['is_active' => false]);
                $message = "تم تعطيل {$count} آراء بنجاح";
                break;

            default:
                return back()->with('error', 'إجراء غير معروف');
        }

        // For AJAX requests
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success', $message);
    }

    /**
     * Toggle testimonial status (AJAX).
     */
    public function toggleStatus(Testimonial $testimonial)
    {
        $testimonial->is_active = !$testimonial->is_active;
        $testimonial->save();

        return response()->json([
            'success' => true,
            'is_active' => $testimonial->is_active,
            'message' => $testimonial->is_active ? 'تم تفعيل الرأي' : 'تم تعطيل الرأي',
        ]);
    }

    /**
     * Update sort order via AJAX drag & drop.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:testimonials,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->orders as $order) {
            Testimonial::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الترتيب بنجاح',
        ]);
    }

    /**
     * Duplicate a testimonial.
     */
    public function duplicate(Testimonial $testimonial)
    {
        $newTestimonial = $testimonial->replicate();
        $newTestimonial->name = $testimonial->name . ' (نسخة)';
        $newTestimonial->sort_order = Testimonial::max('sort_order') + 1;
        $newTestimonial->is_active = false;
        
        // Copy avatar if exists
        if ($testimonial->avatar) {
            $newAvatarPath = 'testimonials/' . uniqid() . '_' . basename($testimonial->avatar);
            Storage::disk('public')->copy($testimonial->avatar, $newAvatarPath);
            $newTestimonial->avatar = $newAvatarPath;
        }
        
        $newTestimonial->save();

        // Log activity
        // activity()->performedOn($newTestimonial)->log("تم نسخ رأي العميل من #{$testimonial->id}");

        return redirect()
            ->route('admin.testimonials.edit', $newTestimonial)
            ->with('success', 'تم نسخ رأي العميل بنجاح. يمكنك تعديله الآن.');
    }

    /**
     * Export testimonials to CSV.
     */
    public function export(Request $request)
    {
        $query = Testimonial::query();

        // Apply same filters as index
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $testimonials = $query->orderBy('sort_order')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="testimonials-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($testimonials) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Arabic support in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Headers
            fputcsv($file, ['#', 'اسم العميل', 'المدينة', 'التقييم', 'المراجعة', 'الحالة', 'الترتيب', 'تاريخ الإنشاء']);
            
            // Data
            foreach ($testimonials as $testimonial) {
                fputcsv($file, [
                    $testimonial->id,
                    $testimonial->name,
                    $testimonial->city,
                    $testimonial->rating . '/5',
                    $testimonial->review,
                    $testimonial->is_active ? 'نشط' : 'غير نشط',
                    $testimonial->sort_order,
                    $testimonial->created_at->format('Y-m-d H:i'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Search testimonials (AJAX autocomplete).
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $testimonials = Testimonial::where('name', 'like', "%{$search}%")
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->limit(10)
            ->get(['id', 'name', 'city']);

        return response()->json($testimonials);
    }

    /**
     * Reorder testimonials when a new sort_order is assigned.
     */
    private function reorderTestimonials($newSortOrder, $excludeId = null)
    {
        $query = Testimonial::where('sort_order', '>=', $newSortOrder);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $testimonialsToShift = $query->orderBy('sort_order')->get();

        foreach ($testimonialsToShift as $index => $testimonial) {
            $testimonial->sort_order = $newSortOrder + $index + 1;
            $testimonial->save();
        }
    }

    /**
     * Normalize sort orders after deletion.
     */
    private function normalizeSortOrders()
    {
        $testimonials = Testimonial::orderBy('sort_order')->orderBy('id')->get();
        
        foreach ($testimonials as $index => $testimonial) {
            $newOrder = $index + 1;
            if ($testimonial->sort_order != $newOrder) {
                $testimonial->sort_order = $newOrder;
                $testimonial->save();
            }
        }
    }
}