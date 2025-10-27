<?php

namespace App\Http\Controllers\ErpApi;

use App\Models\Trip;
use App\Models\Branchs;
use App\Traits\TranslatableTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\TripResource;
use Illuminate\Http\Request;
use App\Http\Resources\ERP\BranchNativeIndex;

class TripController extends Controller
{
    use TranslatableTrait;

    public function __construct()
    {
        $this->middleware('api.key');
    }

    public function index(Request $request)
    {
        $query = Trip::query();

        if ($request->filled('representative_id')) {
            $query->where('representative_id', $request->representative_id);
        }
        $trips = $query->where(function ($q) {
            $q->where('expense_value', 0)
                ->orWhereNull('expense_value')
                ->orWhereNotNull('refund_value');
        })->get();

        if ($trips->isNotEmpty()) {
            return response()->json([
                'status' => "success",
                'message' => $this->translate('data_retrieved'),
                'data' => TripResource::collection($trips)
            ], 200);
        }

        return response()->json([
            'status' => true,
            'message' => $this->translate('shipment_not_found'),
            'data' => []
        ], 200);
    }

    public function updatePayments(Request $request)
    {
        try {
            $request->validate([
                'trips' => 'required|array|min:1',
                'trips.*.trip_id' => 'required|exists:trips,id',
                'trips.*.expense_value' => 'required|numeric|min:0',
            ]);

            $results = [];

            foreach ($request->trips as $tripData) {
                $trip = Trip::findOrFail($tripData['trip_id']);

                $totalCost = $trip->value_drive ?? 0;

                $trip->expense_value = ($trip->expense_value ?? 0) + $tripData['expense_value'];

                if ($trip->expense_value > $totalCost) {
                    $overPaid = $trip->expense_value - $totalCost;

                    $message = 'المبلغ المدفوع أكبر من المطلوب. سيتم اعتبار الرحلة مدفوعة بالكامل.';
                } elseif ($trip->expense_value == $totalCost) {

                    $message = 'تم الدفع بالكامل';
                    $overPaid = 0;
                } elseif ($trip->expense_value > 0) {
                    $message = 'تم الدفع جزئياً';
                    $overPaid = 0;
                } else {
                    $message = 'لم يتم الدفع';
                    $overPaid = 0;
                }
                $trip->refund_value = ($trip->refund_value ?? 0) - $tripData['expense_value'];

                $trip->save();

                $results[] = [
                    'trip_id' => $trip->id,
                    'expense_value' => $trip->expense_value,
                    'total_cost' => $totalCost,
                    'over_paid' => $overPaid,
                    'message' => $message,
                ];
            }

            return response()->json([
                'message' => 'تم تحديث مدفوعات الرحلات بنجاح',
                'results' => $results,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'خطأ في التحقق من البيانات',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'رحلة غير موجودة',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('خطأ أثناء تحديث مدفوعات الرحلات: ' . $e->getMessage());

            return response()->json([
                'message' => 'حدث خطأ غير متوقع أثناء تحديث مدفوعات الرحلات',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
