<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Models\Clients;
use App\Models\Representative;

class MainController extends Controller
{
    public function deleteByPhone($phone): JsonResponse
    {
        try {
            $client = Clients::where('phone', $phone)->first();
            $representative = Representative::where('phone', $phone)->first();

            // if (!$client && !$representative) {
            //     return response()->json([
            //         'status' => false,
            //         'message' => 'Client or representative not found.',   
            //     ], 404);
            // }

            if ($client) {
                $client->delete();
            }

            if ($representative) {
                $representative->delete();
            }

            return response()->json([
                'status' => true,
                'message' => 'Account deleted successfully.',   
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),  
            ], 500);
        }
    }
}