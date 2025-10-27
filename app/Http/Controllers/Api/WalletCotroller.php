<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use App\Models\Shipment;
use App\Models\AdminWallet;
use App\Models\WalletDetail;
use Illuminate\Http\Request;
use App\Models\ShipmentsCompany;
use App\Traits\TranslatableTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class WalletCotroller extends Controller
{
    use TranslatableTrait;

    public function wallet(Request $request)
    {
        try {
            $shipments = Shipment::where('person_id', Auth::id())->where('status_id', '!=', 5)->get();

            $from = $request->query('from');
            $to = $request->query('to');
            $user = auth()->user();

            // $operationsQuery = Shipment::where('person_id', Auth::id())->where('status_id', 2)->select(['id', 'price', 'created_at', 'code'])->orderBy('created_at', 'desc');
            $operationsQuery = Shipment::where('person_id', Auth::id())->select(['id', 'price', 'created_at', 'code'])->orderBy('created_at', 'desc');

            if ($from && $to) {
                $operationsQuery->whereBetween('created_at', [$from, $to]);
            }

            $operations = $operationsQuery->get()->map(function ($operation) {
                return [
                    'id' => $operation->id,
                    'price' => (int) $operation->price,
                    'code' => $operation->code,
                    'created_at' => $operation->created_at->format('Y-m-d'),
                ];
            });

            // $totalPrice = max(0, $shipments->where('status_id', 2)->sum('price'));
            $totalPrice = max(0, $shipments->sum('price'));
            $wallet = WalletDetail::where('model_id', $user->id)->where('model_type', get_class($user))->first();

            $totalPriceCanGet = max(0, $totalPrice - AdminWallet::where('user_id', Auth::id())->where('type', 'withdraw')->sum('amount'));
            $totalPriceForYou = max(0, Task::where('person_id', Auth::id())
                ->where('type_task', 2)
                ->where('status_operation', 1)
                ->sum('price_operation'));

            return response()->json([
                'status' => true,
                'message' => $this->translate('data_retrieved_successfully'), // ترجمة الرسالة
                'data' => [
                    'total_price' => $totalPrice,
                    'total_price_for_you' => $totalPriceForYou,
                    'total_price_can_get' => $wallet->amount ?? 0,
                    'operations' => $operations->isEmpty() ? [] : $operations,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $this->translate('error_fetching_wallet_data'), // ترجمة الرسالة
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
