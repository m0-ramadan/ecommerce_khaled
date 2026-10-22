<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use App\Models\PointTransaction;
use App\Services\LoyaltyService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    use ApiResponseTrait;

    public function __construct(private LoyaltyService $loyalty)
    {
    }

    /**
     * رصيد النقاط وقيمتها وسجل الحركات.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $settings = $this->loyalty->settings();

        $transactions = PointTransaction::where('user_id', $user->id)
            ->latest()
            ->limit(50)
            ->get()
            ->map(fn ($transaction) => [
                'id' => $transaction->id,
                'type' => $transaction->type,
                'points' => $transaction->points,
                'balance_after' => $transaction->balance_after,
                'description' => $transaction->description,
                'order_id' => $transaction->order_id,
                'created_at' => $transaction->created_at?->format('Y-m-d H:i'),
            ]);

        return $this->success([
            'balance' => (int) $user->points,
            'value' => $settings->pointsToSar((float) $user->points),
            'settings' => [
                'is_active' => (bool) $settings->is_active,
                'earn_rate' => (float) $settings->earn_rate,
                'redeem_rate' => (float) $settings->redeem_rate,
                'min_redeem_points' => (int) $settings->min_redeem_points,
                'max_redeem_percent' => (float) $settings->max_redeem_percent,
                'points_expiry_days' => $settings->points_expiry_days,
                'terms' => $settings->terms,
            ],
            'transactions' => $transactions,
        ], 'تم جلب رصيد النقاط بنجاح');
    }

    /**
     * حساب قيمة نقاط مطلوب استخدامها قبل إنشاء الطلب.
     */
    public function quote(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:0',
            'amount' => 'required|numeric|min:0',
        ]);

        $quote = $this->loyalty->quote(
            $request->user(),
            (int) $request->input('points'),
            (float) $request->input('amount')
        );

        return $this->success([
            'points' => $quote['points'],
            'discount' => $quote['discount'],
            'max_points' => $quote['max_points'],
            'balance' => $quote['balance'],
            'min_points' => $quote['min_points'],
        ], 'تم حساب قيمة النقاط');
    }
}
