<?php

namespace App\Http\Controllers\ErpApi;

use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class SettlementController extends Controller
{
    /**
     * Update the 'closed' status for settlements based on a list of shipment IDs.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function closeSettlements(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'shipment_ids' => 'required|array',
                'shipment_ids.*' => 'required|integer|exists:shipments,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $shipmentIds = $request->input('shipment_ids');

            // Update settlements where closed = 0
            $updatedCount = Settlement::whereIn('shipment_id', $shipmentIds)
                ->where('closed', 0)
                ->update(['closed' => 1]);

            if ($updatedCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم تحديث أي اصول. إما أنه لم يتم العثور على اصول مطابقة، أو أنها مغلقة بالفعل.',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully updated $updatedCount settlements to closed.",
            ], 200);
        } catch (\Exception $e) {
            Log::error('Close Settlements Failed: ' . $e->getMessage(), [
                'shipment_ids' => $request->input('shipment_ids', []),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating settlements.',
            ], 500);
        }
    }
}
