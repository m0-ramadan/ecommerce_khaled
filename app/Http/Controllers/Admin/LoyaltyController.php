<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltySetting;
use App\Models\PointTransaction;
use App\Models\User;
use App\Services\LoyaltyService;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function __construct(private LoyaltyService $loyalty)
    {
    }

    public function index()
    {
        $settings = LoyaltySetting::current();

        $users = User::query()
            ->where('points', '>', 0)
            ->orderByDesc('points')
            ->limit(30)
            ->get(['id', 'name', 'phone', 'email', 'points']);

        $transactions = PointTransaction::with(['user:id,name,phone'])
            ->latest()
            ->limit(50)
            ->get();

        $stats = [
            'members' => User::where('points', '>', 0)->count(),
            'points_balance' => (int) User::sum('points'),
            'points_earned' => (int) PointTransaction::where('points', '>', 0)->sum('points'),
            'points_redeemed' => (int) abs(PointTransaction::where('type', 'redeem')->sum('points')),
        ];

        return view('Admin.loyalty.index', compact('settings', 'users', 'transactions', 'stats'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'nullable|boolean',
            'earn_mode' => 'required|in:amount,per_order,both',
            'earn_rate' => 'required|numeric|min:0',
            'points_per_order' => 'nullable|integer|min:0',
            'redeem_rate' => 'required|numeric|min:0.01',
            'min_redeem_points' => 'required|integer|min:0',
            'max_redeem_percent' => 'required|numeric|min:0|max:100',
            'points_expiry_days' => 'nullable|integer|min:0',
            'terms' => 'nullable|string|max:2000',
        ]);

        LoyaltySetting::current()->update([
            'is_active' => $request->boolean('is_active'),
            'earn_mode' => $validated['earn_mode'],
            'earn_rate' => $validated['earn_rate'],
            'points_per_order' => $validated['points_per_order'] ?? 0,
            'redeem_rate' => $validated['redeem_rate'],
            'min_redeem_points' => $validated['min_redeem_points'],
            'max_redeem_percent' => $validated['max_redeem_percent'],
            'points_expiry_days' => $validated['points_expiry_days'] ?? null,
            'terms' => $validated['terms'] ?? null,
        ]);

        return back()->with('success', 'تم تحديث إعدادات نظام النقاط.');
    }

    public function adjust(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|not_in:0',
            'description' => 'nullable|string|max:255',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $newBalance = $this->loyalty->adjust($user, (int) $validated['points'], $validated['description'] ?? null);

        return back()->with('success', "تم تعديل نقاط {$user->name}. الرصيد الجديد: {$newBalance} نقطة.");
    }
}
