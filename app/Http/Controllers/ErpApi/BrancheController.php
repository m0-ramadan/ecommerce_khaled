<?php

namespace App\Http\Controllers\ErpApi;

use App\Models\Branchs;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\BranchssPricing;
use App\Traits\TranslatableTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ERP\BranchNative;
use App\Http\Resources\ERP\BranchNativeIndex;
use App\Http\Resources\ERP\AllshipmentsResource;

class BrancheController extends Controller
{
    use TranslatableTrait;

    public function __construct()
    {
        $this->middleware('api.key');
    }

    public function index()
    {
        $shipment = Branchs::all();

        if ($shipment) {
            return response()->json([
                'status' => "success",
                'message' => $this->translate('data_retrieved'),
                'data' => BranchNativeIndex::collection($shipment)
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => $this->translate('shipment_not_found'),
        ], 404);
    }
    public function show($id, Request $request)
    {
        if (!in_array($request->type, ['customer', 'merchant'])) {
            return response()->json([
                'status' => false,
                'message' => 'يجب تحديد النوع (customer أو merchant)',
            ], 400);
        }

        $clientType = $request->type === 'customer' ? 1 : 2;

        $branch = Branchs::find($id);

        $shipments = Shipment::where('branches_from', $id)
            ->whereHas('client', function ($query) use ($clientType) {
                $query->where('type', $clientType);
            })
            ->get();

        if (!$branch || $shipments->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => $this->translate('data_retrieved'),
                'data' => $branch ? new BranchNative($branch) : null,
                'shipments' => [],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => $this->translate('data_retrieved'),
            'data' => new BranchNative($branch),
            'shipments' => AllshipmentsResource::collection($shipments),
        ], 200);
    }

}