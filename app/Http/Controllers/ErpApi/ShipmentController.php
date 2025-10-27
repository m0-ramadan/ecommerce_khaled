<?php

namespace App\Http\Controllers\ErpApi;

use App\Models\Branchs;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\BranchssPricing;
use App\Traits\TranslatableTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\BranchNative;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ERP\AllshipmentsResource;

class ShipmentController extends Controller
{
    use TranslatableTrait;

    public function __construct()
    {
        $this->middleware('api.key'); // Apply API key middleware
    }

    public function getShipment($id)
    {
        $user = auth()->user();
        $shipment = Shipment::find($id);

        if ($shipment) {
            return response()->json([
                'status' => "success",
                'message' => $this->translate('data_retrieved'),
                'data' => new AllshipmentsResource($shipment)
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => $this->translate('shipment_not_found'),
        ], 404);
    }

    public function getShipmentBranch(Request $request, $id)
    {
        try {
            $branch = Branchs::find($id);

            if (!in_array($request->type, ['customer', 'merchant'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'يجب تحديد النوع (customer أو merchant)',
                ], 400);
            }

            $clientType = $request->type === 'customer' ? 1 : 2;

            $shipments = Shipment::where('active', 1)->where(function ($query) use ($id) {
                $query->where('branches_from', $id);
            })
                ->whereHas('client', function ($query) use ($clientType) {
                    $query->where('type', $clientType);
                })->get();

            if ($shipments->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $this->translate('data_retrieved'),
                    'data' => [],
                    'branch' => null,
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'message' => $this->translate('data_retrieved'),
                'data' => AllshipmentsResource::collection($shipments),
                'branch' => new BranchNative($branch),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء استرجاع الشحنات',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updatePayments(Request $request)
    {
        try {
            $request->validate([
                'shipments' => 'required|array|min:1',
                'shipments.*.shipment_id' => 'required|exists:shipments,id',
                'shipments.*.refund_code' => 'required|numeric|min:0',
            ]);

            $results = [];

            foreach ($request->shipments as $shipmentData) {
                $shipment = Shipment::findOrFail($shipmentData['shipment_id']);

                $totalCost =
                    ($shipment->price ?? 0) +
                    ($shipment->shipping_cost ?? 0) +
                    ($shipment->expense_code ?? 0) +
                    ($shipment->packaging_cost ?? 0);

                // تحديث المدفوع
                $shipment->refund_code = ($shipment->refund_code ?? 0) + $shipmentData['refund_code'];

                // تحقق من الحالة
                if ($shipment->refund_code > $totalCost) {
                    $overPaid = $shipment->refund_code - $totalCost;
                    $paymentStatusId = 1; // مدفوع بالكامل
                    $message = 'المبلغ المدفوع أكبر من التكلفة المطلوبة. سيتم اعتبار الطلب مدفوع بالكامل.';
                } elseif ($shipment->refund_code == $totalCost) {
                    $paymentStatusId = 1; // مدفوع بالكامل
                    $message = 'تم الدفع بالكامل';
                    $overPaid = 0;
                } elseif ($shipment->refund_code > 0) {
                    $paymentStatusId = 4; // مدفوع جزئي
                    $message = 'تم الدفع جزئياً';
                    $overPaid = 0;
                } else {
                    $paymentStatusId = 2; // غير مدفوع
                    $message = 'لم يتم الدفع';
                    $overPaid = 0;
                }

                $shipment->payment_type_id = $paymentStatusId;
                $shipment->save();

                $results[] = [
                    'shipment_id' => $shipment->id,
                    'refund_code' => $shipment->refund_code,
                    'total_cost' => $totalCost,
                    'over_paid' => $overPaid,
                    'payment_status' => $shipment->paymentType->name,
                    'message' => $message,
                ];
            }

            return response()->json([
                'message' => 'تم تحديث المدفوعات بنجاح',
                'results' => $results,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'خطأ في التحقق من البيانات',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'شحنة غير موجودة',
            ], 404);
        } catch (\Exception $e) {
            \Log::error('خطأ أثناء تحديث المدفوعات للشحنات: ' . $e->getMessage());

            return response()->json([
                'message' => 'حدث خطأ غير متوقع أثناء تحديث المدفوعات',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
