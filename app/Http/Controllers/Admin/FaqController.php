<?php

namespace App\Http\Controllers\Admin;

use App\Models\Faq;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FaqController extends Controller
{
    /**
     * Display a listing of the faqs.
     */
    public function index(Request $request)
    {
        $query = Faq::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'sort_order');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSorts = ['sort_order', 'question', 'status', 'created_at', 'updated_at'];
        
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('sort_order', 'asc');
        }

        $faqs = $query->paginate($request->get('per_page', 15))->withQueryString();

        // Get statistics
        $statistics = [
            'total' => Faq::count(),
            'active' => Faq::where('status', 1)->count(),
            'inactive' => Faq::where('status', 0)->count(),
        ];

        return view('Admin.faqs.index', compact('faqs', 'statistics'));
    }

    /**
     * Show the form for creating a new faq.
     */
    public function create()
    {
        // Get the next sort order number
        $nextSortOrder = Faq::max('sort_order') + 1;
        
        return view('Admin.faqs.create', compact('nextSortOrder'));
    }

    /**
     * Store a newly created faq in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ], [
            'question.required' => 'يرجى إدخال السؤال',
            'question.max' => 'يجب ألا يتجاوز السؤال 500 حرف',
            'answer.required' => 'يرجى إدخال الإجابة',
            'status.required' => 'يرجى تحديد حالة السؤال',
            'status.in' => 'الحالة يجب أن تكون نشط أو غير نشط',
        ]);

        // Set default sort order if not provided
        if (!isset($validated['sort_order']) || $validated['sort_order'] === '') {
            $validated['sort_order'] = Faq::max('sort_order') + 1;
        }

        // Reorder other faqs if needed
        $this->reorderFaqs($validated['sort_order']);

        $faq = Faq::create($validated);

        // Log activity (if you have activity log)
        // activity()->performedOn($faq)->log('تم إنشاء سؤال شائع جديد');

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'تم إضافة السؤال الشائع بنجاح');
    }

    /**
     * Display the specified faq.
     */
    public function show(Faq $faq)
    {
        $faq->loadCount(['views']); // if you have views relationship
        
        // Get related faqs (same category or similar questions)
        $relatedFaqs = Faq::where('id', '!=', $faq->id)
            ->where('status', 1)
            ->orderBy('sort_order')
            ->limit(5)
            ->get();

        return view('Admin.faqs.show', compact('faq', 'relatedFaqs'));
    }

    /**
     * Show the form for editing the specified faq.
     */
    public function edit(Faq $faq)
    {
        $statistics = [
            'created_at' => $faq->created_at->diffForHumans(),
            'updated_at' => $faq->updated_at->diffForHumans(),
            'word_count' => str_word_count(strip_tags($faq->answer)),
        ];

        return view('Admin.faqs.edit', compact('faq', 'statistics'));
    }

    /**
     * Update the specified faq in storage.
     */
    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'sort_order' => 'nullable|integer|min:0',
            'status' => 'required|in:0,1',
        ], [
            'question.required' => 'يرجى إدخال السؤال',
            'question.max' => 'يجب ألا يتجاوز السؤال 500 حرف',
            'answer.required' => 'يرجى إدخال الإجابة',
            'status.required' => 'يرجى تحديد حالة السؤال',
            'status.in' => 'الحالة يجب أن تكون نشط أو غير نشط',
        ]);

        // Reorder if sort_order changed
        if ($faq->sort_order != $validated['sort_order']) {
            $this->reorderFaqs($validated['sort_order'], $faq->id);
        }

        $faq->update($validated);

        // Log activity
        // activity()->performedOn($faq)->log('تم تحديث السؤال الشائع');

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'تم تحديث السؤال الشائع بنجاح');
    }

    /**
     * Remove the specified faq from storage.
     */
    public function destroy(Faq $faq)
    {
        $faqName = $faq->question;
        $faq->delete();

        // Reorder remaining faqs
        $this->normalizeSortOrders();

        // Log activity
        // activity()->log("تم حذف السؤال الشائع: {$faqName}");

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'تم حذف السؤال الشائع بنجاح');
    }

    /**
     * Handle bulk actions (delete, activate, deactivate, reorder).
     */
    public function bulkAction(Request $request)
    {
        if (is_string($request->ids)) {
            $request->merge([
                'ids' => array_filter(explode(',', $request->ids)),
            ]);
        }

        $request->validate([
            'action' => 'required|in:delete,activate,deactivate,reorder',
            'ids' => 'required|array',
            'ids.*' => 'exists:faqs,id',
        ]);

        $ids = $request->ids;
        $action = $request->action;

        switch ($action) {
            case 'delete':
                Faq::whereIn('id', $ids)->delete();
                $this->normalizeSortOrders();
                $message = 'تم حذف الأسئلة المحددة بنجاح';
                break;

            case 'activate':
                Faq::whereIn('id', $ids)->update(['status' => 1]);
                $message = 'تم تفعيل الأسئلة المحددة بنجاح';
                break;

            case 'deactivate':
                Faq::whereIn('id', $ids)->update(['status' => 0]);
                $message = 'تم تعطيل الأسئلة المحددة بنجاح';
                break;

            case 'reorder':
                $request->validate([
                    'orders' => 'required|array',
                    'orders.*' => 'integer|min:0',
                ]);

                foreach ($request->orders as $id => $order) {
                    Faq::where('id', $id)->update(['sort_order' => $order]);
                }
                $message = 'تم إعادة ترتيب الأسئلة بنجاح';
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
            ->route('admin.faqs.index')
            ->with('success', $message);
    }

    /**
     * Duplicate the specified faq.
     */
    public function duplicate(Faq $faq)
    {
        $newFaq = $faq->replicate();
        $newFaq->question = $faq->question . ' (نسخة)';
        $newFaq->sort_order = Faq::max('sort_order') + 1;
        $newFaq->status = 0; // Default to inactive for duplicates
        $newFaq->save();

        // Log activity
        // activity()->performedOn($newFaq)->log("تم نسخ السؤال الشائع من #{$faq->id}");

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'تم نسخ السؤال الشائع بنجاح. يمكنك تعديله من نفس الصفحة.');
    }

    /**
     * Search faqs (for AJAX autocomplete).
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $faqs = Faq::where('question', 'like', "%{$search}%")
            ->where('status', 1)
            ->orderBy('sort_order')
            ->limit(10)
            ->get(['id', 'question']);

        return response()->json($faqs);
    }

    /**
     * Export faqs to CSV.
     */
    public function export()
    {
        $faqs = Faq::orderBy('sort_order')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="faqs-' . now()->format('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($faqs) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Arabic support in Excel
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));
            
            // Headers
            fputcsv($file, ['#', 'السؤال', 'الإجابة', 'الترتيب', 'الحالة', 'تاريخ الإنشاء']);
            
            // Data
            foreach ($faqs as $faq) {
                fputcsv($file, [
                    $faq->id,
                    $faq->question,
                    strip_tags($faq->answer),
                    $faq->sort_order,
                    $faq->status == 1 ? 'نشط' : 'غير نشط',
                    $faq->created_at->format('Y-m-d H:i'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Toggle faq status (for AJAX quick toggle).
     */
    public function toggleStatus(Faq $faq)
    {
        $faq->status = !$faq->status;
        $faq->save();

        return response()->json([
            'success' => true,
            'status' => $faq->status,
            'message' => $faq->status ? 'تم تفعيل السؤال' : 'تم تعطيل السؤال',
        ]);
    }

    /**
     * Update sort order via AJAX drag & drop.
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'orders' => 'required|array',
            'orders.*.id' => 'required|exists:faqs,id',
            'orders.*.sort_order' => 'required|integer|min:0',
        ]);

        foreach ($request->orders as $order) {
            Faq::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الترتيب بنجاح',
        ]);
    }

    /**
     * Reorder faqs when a new sort_order is assigned.
     */
    private function reorderFaqs($newSortOrder, $excludeId = null)
    {
        $query = Faq::where('sort_order', '>=', $newSortOrder);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $faqsToShift = $query->orderBy('sort_order')->get();

        foreach ($faqsToShift as $index => $faq) {
            $faq->sort_order = $newSortOrder + $index + 1;
            $faq->save();
        }
    }

    /**
     * Normalize sort orders after deletion.
     */
    private function normalizeSortOrders()
    {
        $faqs = Faq::orderBy('sort_order')->orderBy('id')->get();
        
        foreach ($faqs as $index => $faq) {
            $newOrder = $index + 1;
            if ($faq->sort_order != $newOrder) {
                $faq->sort_order = $newOrder;
                $faq->save();
            }
        }
    }
}
