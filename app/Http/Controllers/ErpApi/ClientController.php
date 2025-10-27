<?php

namespace App\Http\Controllers\ErpApi;

use App\Models\Clients;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\BranchssPricing;
use App\Traits\TranslatableTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ERP\ClientsResource;
use App\Http\Resources\ERP\ShowClientResource;
use App\Http\Resources\ERP\AllshipmentsResource;

class ClientController extends Controller
{
    use TranslatableTrait;

    public function __construct()
    {
        $this->middleware('api.key');
    }

    public function index(Request $request)
    {
        $shipment = Clients::where('type', $request->type)->get();

        if ($shipment) {
            return response()->json([
                'status' => "success",
                'message' => $this->translate('data_retrieved'),
                'data' => ClientsResource::collection($shipment)
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => $this->translate('shipment_not_found'),
        ], 404);
    }
    public function show($id)
    {
        $client = Clients::find($id);

        if ($client) {
            return response()->json([
                'status' => "success",
                'message' => $this->translate('data_retrieved'),
                'data' => new ShowClientResource($client)
            ], 200);
        }
        return response()->json([
            'status' => false,
            'message' => $this->translate('shipment_not_found'),
        ], 404);
    }
}