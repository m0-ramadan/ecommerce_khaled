<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /* ================= TABBY & TAMARA SUCCESS ================= */

    public function handleSuccess(Request $request)
    {
        $orderId = $this->extractOrderId($request);

        if (!$orderId) {
            return response()->json([
                'status' => false,
                'message' => 'Order ID not found'
            ], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        if ($order->status_payment === Order::PAYMENT_STATUS_PAID) {
            return response()->json([
                'status' => true,
                'message' => 'Order already paid'
            ]);
        }

        $order->update([
            'status_payment' => Order::PAYMENT_STATUS_PAID,
            'transaction_id' => $request->get('payment_id')
                ?? $request->get('checkout_id')
                ?? $request->get('id'),
            'status' => 'processing',
        ]);

        return redirect()->to(config('app.frontend_url') . '/ordercomplete?orderId=' . $order->id);
    }


    /* ================= TABBY & TAMARA FAILURE ================= */

    public function handleFailure(Request $request)
    {
        $orderId = $request->get('order_id')
            ?? $request->get('reference_id');

        if (!$orderId) {
            return response()->json([
                'status' => false,
                'message' => 'Order ID not found'
            ], 400);
        }

        $order = Order::find($orderId);

        if ($order) {
            $order->update([
                'status_payment' => Order::PAYMENT_STATUS_FAILED,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Payment failed'
        ]);
    }

    /* ================= TABBY & TAMARA CANCEL ================= */

    public function handleCancel(Request $request)
    {
        $orderId = $request->get('order_id')
            ?? $request->get('reference_id');

        if ($orderId) {
            Order::where('id', $orderId)->update([
                'status_payment' => Order::PAYMENT_STATUS_FAILED,
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Payment cancelled'
        ]);
    }

    /* ================= TAMARA WEBHOOK ================= */

    public function handleWebhook(Request $request)
    {
        Log::channel('payment')->info('Tamara Webhook Received', $request->all());

        $orderId = data_get($request, 'order.reference_id');
        $status  = data_get($request, 'event_type');

        if (!$orderId) {
            return response()->json(['message' => 'Invalid webhook'], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($status === 'order_approved') {
            $order->update([
                'status_payment' => Order::PAYMENT_STATUS_PAID,
                'status' => 'processing',
            ]);
        }

        if (in_array($status, ['order_declined', 'order_cancelled'])) {
            $order->update([
                'status_payment' => Order::PAYMENT_STATUS_FAILED,
            ]);
        }

        return response()->json(['status' => true]);
    }

    /* ================= PAYMOB CALLBACKD ================= */

    public function handlePaymobCallback(Request $request)
    {
        // Paymob بيرجع order id غالبًا في merchant_order_id
        $orderId = $request->get('merchant_order_id')
            ?? $request->get('order_id');

        if (!$orderId) {
            return response()->json([
                'status' => false,
                'message' => 'Order ID missing'
            ], 400);
        }

        $order = Order::find($orderId);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Paymob indicators
        $success = $request->get('success');
        $txnId   = $request->get('id'); // transaction id

        if ($success === 'true' || $success === true) {
            $order->update([
                'status_payment' => Order::PAYMENT_STATUS_PAID,
                'transaction_id' => $txnId,
                'status' => 'processing',
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Payment successful',
                'order_id' => $order->id
            ]);
        }

        $order->update([
            'status_payment' => Order::PAYMENT_STATUS_FAILED,
        ]);

        return response()->json([
            'status' => false,
            'message' => 'Payment failed or cancelled'
        ]);
    }

    /* =================  HELPER ================= */

    private function extractOrderId(Request $request): ?int
    {
        if ($request->get('order_id')) {
            return (int) $request->get('order_id');
        }

        if ($request->get('reference_id')) {
            return (int) $request->get('reference_id');
        }

        // Tabby weird redirect: ?60=
        $keys = array_keys($request->query());
        return is_numeric($keys[0] ?? null) ? (int) $keys[0] : null;
    }
}
