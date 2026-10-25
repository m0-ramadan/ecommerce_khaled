<?php

namespace App\Services;

use App\Models\LoyaltySetting;
use App\Models\Order;
use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LoyaltyService
{
    public function settings(): LoyaltySetting
    {
        return LoyaltySetting::current();
    }

    /**
     * حساب أقصى نقاط يمكن استخدامها وقيمتها.
     *
     * @return array{points:int,discount:float,max_points:int,balance:int,min_points:int,settings:LoyaltySetting}
     */
    public function quote(User $user, int $requestedPoints, float $eligibleAmount): array
    {
        $settings = $this->settings();
        $balance = (int) $user->points;

        if (! $settings->is_active || $settings->redeem_rate <= 0) {
            return [
                'points' => 0,
                'discount' => 0.0,
                'max_points' => 0,
                'balance' => $balance,
                'min_points' => (int) $settings->min_redeem_points,
                'settings' => $settings,
            ];
        }

        $maxByPercent = (int) floor(
            max(0, $eligibleAmount) * ((float) $settings->max_redeem_percent / 100) * (float) $settings->redeem_rate
        );
        $maxPoints = max(0, min($balance, $maxByPercent));

        $points = max(0, min($requestedPoints, $maxPoints));

        if ($points < (int) $settings->min_redeem_points) {
            $points = 0;
        }

        $discount = $points > 0 ? round($points / (float) $settings->redeem_rate, 2) : 0.0;

        return [
            'points' => $points,
            'discount' => $discount,
            'max_points' => $maxPoints,
            'balance' => $balance,
            'min_points' => (int) $settings->min_redeem_points,
            'settings' => $settings,
        ];
    }

    /**
     * خصم النقاط عند إنشاء الطلب.
     */
    public function redeemForOrder(Order $order, int $points, float $discount): void
    {
        if ($points <= 0) {
            return;
        }

        $user = $order->user;
        if (! $user) {
            throw new RuntimeException('لا يمكن استخدام النقاط بدون حساب مستخدم.');
        }

        DB::transaction(function () use ($user, $order, $points, $discount) {
            $fresh = User::query()->lockForUpdate()->find($user->id);

            if (! $fresh || (int) $fresh->points < $points) {
                throw new RuntimeException('رصيد النقاط غير كافٍ.');
            }

            $fresh->decrement('points', $points);

            PointTransaction::create([
                'user_id' => $fresh->id,
                'order_id' => $order->id,
                'type' => 'redeem',
                'points' => -$points,
                'balance_after' => (int) $fresh->points,
                'description' => 'استخدام نقاط في الطلب ' . $order->order_number,
                'meta' => ['discount' => $discount],
            ]);
        });
    }

    /**
     * منح نقاط الطلب عند التسليم (مرة واحدة فقط).
     */
    public function awardForOrder(Order $order): int
    {
        if ($order->points_awarded_at) {
            return 0;
        }

        $settings = $this->settings();
        if (! $settings->is_active || ! $order->user_id) {
            return 0;
        }

        $eligibleAmount = max(0, (float) $order->total_amount - (float) $order->shipping_amount);
        $earnMode = $settings->earn_mode ?: 'amount';

        $points = 0;
        if (in_array($earnMode, ['amount', 'both'], true)) {
            $points += (int) floor($eligibleAmount * (float) $settings->earn_rate);
        }
        if (in_array($earnMode, ['per_order', 'both'], true)) {
            $points += max(0, (int) $settings->points_per_order);
        }

        if ($points <= 0) {
            $order->forceFill(['points_awarded_at' => now()])->save();

            return 0;
        }

        DB::transaction(function () use ($order, $points) {
            $user = User::query()->lockForUpdate()->find($order->user_id);

            if (! $user) {
                return;
            }

            $user->increment('points', $points);

            PointTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'type' => 'earn',
                'points' => $points,
                'balance_after' => (int) $user->points,
                'description' => 'نقاط مكتسبة من الطلب ' . $order->order_number,
            ]);

            $order->forceFill([
                'points_awarded' => $points,
                'points_awarded_at' => now(),
            ])->save();
        });

        return $points;
    }

    /**
     * استرجاع النقاط عند إلغاء الطلب (بدون تكرار).
     */
    public function refundForCancelledOrder(Order $order): void
    {
        if (! $order->user_id || $order->points_refunded_at) {
            return;
        }

        DB::transaction(function () use ($order) {
            $user = User::query()->lockForUpdate()->find($order->user_id);
            if (! $user) {
                return;
            }

            if ((int) $order->points_used > 0
                && ! PointTransaction::where('order_id', $order->id)->where('type', 'refund')->exists()) {
                $user->increment('points', (int) $order->points_used);

                PointTransaction::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'type' => 'refund',
                    'points' => (int) $order->points_used,
                    'balance_after' => (int) $user->points,
                    'description' => 'استرجاع نقاط ملغاة من الطلب ' . $order->order_number,
                ]);
            }

            if ((int) $order->points_awarded > 0
                && ! PointTransaction::where('order_id', $order->id)->where('type', 'reversal')->exists()) {
                $deduct = min((int) $order->points_awarded, (int) $user->points);
                if ($deduct > 0) {
                    $user->decrement('points', $deduct);

                    PointTransaction::create([
                        'user_id' => $user->id,
                        'order_id' => $order->id,
                        'type' => 'reversal',
                        'points' => -$deduct,
                        'balance_after' => (int) $user->points,
                        'description' => 'خصم نقاط طلب ملغي ' . $order->order_number,
                    ]);
                }
            }

            $order->forceFill(['points_refunded_at' => now()])->save();
        });
    }

    /**
     * تعديل يدوي من الداشبورد.
     */
    public function adjust(User $user, int $points, ?string $description = null): int
    {
        DB::transaction(function () use ($user, $points, $description) {
            $fresh = User::query()->lockForUpdate()->find($user->id);
            $actualPoints = $points;

            if ($points >= 0) {
                $fresh->increment('points', $points);
            } else {
                $actualPoints = -min(abs($points), (int) $fresh->points);
                $fresh->decrement('points', abs($actualPoints));
            }

            PointTransaction::create([
                'user_id' => $fresh->id,
                'type' => 'adjust',
                'points' => $actualPoints,
                'balance_after' => (int) $fresh->points,
                'description' => $description ?: 'تعديل يدوي من الداشبورد',
            ]);
        });

        return (int) $user->fresh()->points;
    }
}
