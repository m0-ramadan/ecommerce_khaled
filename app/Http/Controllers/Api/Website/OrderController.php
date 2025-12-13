<?php

namespace App\Http\Controllers\Api\Website;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\HandlesPaymobPayment;
use App\Http\Resources\Website\OrderResource;
use App\Http\Requests\Website\ApplyCouponRequest;
use App\Http\Requests\Website\CreateOrderRequest;
use App\Http\Resources\Website\OrderDetailsResource;
use App\Http\Resources\Website\PaymentMethodResource;
use App\Models\PaymentMethod;

class OrderController extends Controller
{
    use ApiResponseTrait, HandlesPaymobPayment;

    /**
     * عرض طلبات المستخدم المسجل
     */
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return $this->errorResponse('يجب تسجيل الدخول لعرض الطلبات', 401);
        }

        $orders = Order::with(['address', 'items.product', 'items'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(15);

        return $this->successResponse(
            OrderResource::collection($orders)->response()->getData(true),
            'تم جلب الطلبات بنجاح'
        );
    }

    /**
     * إنشاء طلب جديد من السلة
     */
    public function store(CreateOrderRequest $request)
    {
        $user = auth('sanctum')->user();
        $cart = $this->getCurrentCart();

        if ($cart->items()->count() === 0) {
            return $this->errorResponse('السلة فارغة، لا يمكن إنشاء طلب', 400);
        }

        return DB::transaction(function () use ($request, $user, $cart) {

            // تحديد العنوان
            $address = null;
            if ($request->filled('address_id')) {
                $address = UserAddress::where('user_id', $user?->id)
                    ->where('id', $request->address_id)
                    ->firstOrFail();
            }

            // تحديد الكوبون لو موجود
            $coupon = null;
            $discountAmount = 0;

            if ($request->filled('coupon_code')) {
                $coupon = Coupon::where('code', strtoupper($request->coupon_code))
                    ->first();

                if (!$coupon || !$coupon->isValidForOrder($cart->total, $user?->id, session()->getId())) {
                    return $this->errorResponse('كوبون الخصم غير صالح أو منتهي الصلاحية', 400);
                }

                $discountAmount = $coupon->calculateDiscount($cart->total);
            }
            //dd($cart);
            // إنشاء الطلب
            $order = Order::create([
                'user_id'           => $user?->id,
                'order_number'      => $this->generateUniqueOrderNumber(),
                'address_id'        => $address?->id,
                'customer_name'     => $request->customer_name ?? $user?->name,
                'customer_phone'    => $request->customer_phone ?? $user?->phone,
                'customer_email'    => $request->customer_email ?? $user?->email,
                'shipping_address'  => $request->shipping_address,
                'subtotal'          => $cart->subtotal,
                'shipping_amount'   => 0, // لاحقًا: حسب المنطقة
                'discount_amount'   => $discountAmount,
                'tax_amount'        => 0,
                'total_amount'      => $cart->total - $discountAmount,
                'payment_method'    => $request->payment_method ?? 'cash_on_delivery',
                'status'            => 'pending',
                'notes'             => $request->notes,
                'coupon_id'         => $coupon?->id
            ]);

            // نقل العناصر من السلة إلى الطلب
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'              => $order->id,
                    'product_id'            => $item->product_id,
                    'size_id'               => $item->size_id,
                    'color_id'              => $item->color_id,
                    'printing_method_id'    => $item->printing_method_id,
                    'print_locations'       => $item->print_locations,
                    'embroider_locations'   => $item->embroider_locations,
                    'selected_options'      => $item->selected_options,
                    'design_service_id'     => $item->design_service_id,
                    'quantity'              => $item->quantity,
                    'price_per_unit'        => $item->price_per_unit,
                    'total_price'           => $item->line_total ?? $item->price_per_unit * $item->quantity,
                    'is_sample'             => $item->is_sample,
                    'note'                  => $item->note,
                    'quantity_id'           => $item->quantity_id,
                    'image_design'          => $item->image_design,
                ]);
            }

            // تفريغ السلة بعد الطلب
            //  $cart->items()->delete();
            //  $cart->update(['subtotal' => 0, 'total' => 0]);

            if ($request->payment_method === 'credit_card') {

                $payment = $this->initiatePaymobPayment($order);

                if (!$payment['success']) {
                    return $this->errorResponse([$payment['message']], 400);
                }

                return $this->successResponse([
                    'payment_url'  => $payment['payment_url'],
                    'shorten_url'  => $payment['shorten_url'],
                    'order_number' => $order->order_number,
                    'message'      => 'جاري توجيهك إلى بوابة الدفع الآمنة...'
                ]);
            }

            return $this->successResponse(
                new OrderDetailsResource($order->load(['items.product', 'items.size', 'items.color', 'address'])),
                'تم إنشاء الطلب بنجاح',
                201
            );
        });
    }

    /**
     * إلغاء الطلب (فقط إذا كان pending أو processing)
     */
    public function cancelled($codeOrder)
    {
        $order = Order::where('order_number', $codeOrder)->firstOrFail();

        $user = auth('sanctum')->user();

        // تحقق من الصلاحية
        if ($user && $order->user_id !== $user->id) {
            return $this->errorResponse('غير مصرح لك بإلغاء هذا الطلب', 403);
        }

        if (!$user && !$this->guestCanAccessOrder($order)) {
            return $this->errorResponse('رقم الهاتف مطلوب لإلغاء الطلب', 403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return $this->errorResponse('لا يمكن إلغاء الطلب في هذه الحالة', 400);
        }

        $order->update(['status' => 'cancelled']);

        return $this->successResponse(
            new OrderDetailsResource($order),
            'تم إلغاء الطلب بنجاح'
        );
    }

    /**
     * تتبع الطلب برقم الطلب (للمسجلين والزوار)
     */
    public function traceOrder($codeOrder, Request $request)
    {
        $order = Order::with(['items.product', 'items.size', 'items.color', 'address'])
            ->where('order_number', $codeOrder)
            ->firstOrFail();

        $user = auth('sanctum')->user();

        // لو مسجل دخول → تأكد إنه صاحب الطلب
        // if ($user && $order->user_id !== $user->id) {
        //     return $this->errorResponse('هذا الطلب ليس لك', 403);
        // }

        // لو زائر → يطلب رقم التليفون
        // if (!$user) {
        //     $phone = $request->input('phone');
        //     if (!$phone || $order->customer_phone !== $phone) {
        //         return $this->errorResponse('رقم الهاتف غير صحيح', 403);
        //     }
        // }

        return $this->successResponse(
            new OrderDetailsResource($order),
            'تم جلب تفاصيل الطلب'
        );
    }

    /**
     * Summary of show
     * @param mixed $orderID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($orderID)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return $this->errorResponse('يجب تسجيل الدخول لعرض تفاصيل الطلب', 401);
        }

        $order = Order::with(['address', 'items.product', 'items.size', 'items.color'])
            ->where('id', $orderID)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return $this->successResponse(
            new OrderDetailsResource($order),
            'تم جلب تفاصيل الطلب بنجاح'
        );
    }

    /**
     * تطبيق كوبون خصم على السلة الحالية
     */
    public function applyCoupon(ApplyCouponRequest $request)
    {
        $user = auth('sanctum')->user();
        $cart = $this->getCurrentCart();

        if ($cart->items()->count() === 0) {
            return $this->errorResponse('السلة فارغة، لا يمكن تطبيق كوبون', 400);
        }

        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

        if (!$coupon || !$coupon->isValidForOrder($cart->total, $user?->id, session()->getId())) {
            return $this->errorResponse('كوبون الخصم غير صالح أو منتهي الصلاحية', 400);
        }

        $discountAmount = $coupon->calculateDiscount($cart->total);

        return $this->successResponse(
            [
                'coupon_id'       => $coupon->id,
                'discount_amount' => $discountAmount,
                'new_total'       => $cart->total - $discountAmount,
            ],
            'تم تطبيق الكوبون بنجاح'
        );
    }

    public function webhook(Request $request)
    {
        $hmacSecret = config('services.paymob.hmac_secret');

        $receivedHmac = $request->header('X-Paymob-Hmac-Signature')
            ?? $request->input('hmac');

        if (!$receivedHmac || empty($hmacSecret)) {
            return response('Unauthorized', 401);
        }

        $obj = $request->input('obj');
        $concatenated = collect($obj)->flatten()->implode('');
        $calculatedHmac = hash_hmac('sha512', $concatenated, $hmacSecret);

        if (!hash_equals($calculatedHmac, $receivedHmac)) {
            \Illuminate\Support\Facades\Log::warning('PayMob Webhook HMAC Invalid', ['ip' => $request->ip()]);
            return response('Invalid HMAC', 400);
        }

        if ($request->input('type') === 'TRANSACTION' && $obj['success'] && $obj['is_capture']) {
            $orderNumber = $obj['merchant_reference'] ?? null;

            if (!$orderNumber) return response('No reference', 400);

            $order = Order::where('order_number', $orderNumber)->first();

            if ($order && $order->status === 'pending') {
                $order->update([
                    'status'         => 'paid',
                    'payment_method' => 'paymob',
                    'transaction_id' => $obj['id'],
                    'paid_at'        => now(),
                ]);

                // تفريغ السلة دلوقتي (آمن لأن الدفع تم)
                $cart = Cart::where('user_id', $order->user_id)->orWhere('session_id', session()->getId())->first();
                if ($cart) {
                    $cart->items()->delete();
                    $cart->update(['subtotal' => 0, 'total' => 0]);
                }

                \Illuminate\Support\Facades\Log::info("Order Paid via PayMob: {$orderNumber}");
            }
        }

        return response('OK', 200);
    }

    public function paymentMethods(Request $request)
    {

        return $this->successResponse(
            PaymentMethodResource::collection(PaymentMethod::where('is_active', 1)->where('is_payment', $request->is_payment)->get()),
            'تم جلب تفاصيل الطلب بنجاح'

        );
    }
    // ==================== Helpers ====================

    private function getCurrentCart(): Cart
    {
        $user = auth('sanctum')->user();
        $sessionId = request()->header('X-Session-Id') ?: session()->getId();

        return Cart::firstOrCreate(
            $user ? ['user_id' => $user->id] : ['session_id' => $sessionId],
            ['subtotal' => 0, 'total' => 0]
        );
    }

    private function generateUniqueOrderNumber(): string
    {
        do {
            $number = 'ORD-' . strtoupper(substr(bin2hex(random_bytes(5)), 0, 10));
        } while (Order::where('order_number', $number)->exists());

        return $number;
    }

    private function guestCanAccessOrder(Order $order): bool
    {
        $phone = request()->input('phone');
        return $phone && $order->customer_phone === $phone;
    }
}
