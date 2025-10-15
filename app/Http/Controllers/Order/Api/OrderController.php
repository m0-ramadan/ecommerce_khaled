<?php

namespace App\Http\Controllers\Order\Api;

use App\Models\Order;
use App\Models\Client;
use App\Events\NewOrder;
use App\Traits\ApiTrait;
use App\Models\PromoCode;
use App\Services\OrderService;
use App\Services\PaytabsService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PromoCodeResource;
use App\Http\Requests\Order\OrderRateRequest;
use App\Http\Requests\Order\StoreClientOrderRequest;
use App\Http\Requests\Order\UpdateClientOrderRequest;

class OrderController extends Controller
{
    use ApiTrait;

    public function index()
    {
        $user = Auth::guard('api')->user();
        $promoCode = PromoCode::where('client_id', $user->id)->where('status', 1)->first();
        $orders = Order::whereHas('PromoCode', function ($query) use ($user) {
            return $query->where('client_id',  $user->id);
        })->select(['id', 'code_order', 'total', 'status', 'created_at'])->get();

        return $this->apiResponse(
            data: [
                "promo_code" => $promoCode ?  PromoCodeResource::make($promoCode) : null,
                "orders" => Order::filterOrders($orders),
            ]
        );
    }

    public function store(StoreClientOrderRequest $request)
    {
        $user = Client::findOrFail(Auth::guard('api')->user()->id);
        $user->orders()->doesntHave('orderitem')->delete();

        $orderService = new OrderService($request->validated(), $user);
        $orderService->calcTotalCartPrice()->calcOrderSubTotal()->handleOrderCreation();

        $last_order = $user->orders()->where('payment_status', Order::PAYMENT_STATUSES['PAID'])->latest()->first();
        return $this->apiResponse(
            data: [
                'order' => OrderResource::make($orderService->order),
                'last_order' => $last_order ? OrderResource::make($last_order) : null,
            ]
        );
    }

    public function toCheckoutPage()
    {
        $user = Client::where('phone', '0555')->first();
        Auth::guard('clientsWeb')->login($user);
        return redirect()->route('profileDetails');
    }

    public function update(UpdateClientOrderRequest $request, Order $order)
    {
        $orderService = new OrderService($request->validated(), Auth::guard('api')->user());
        $orderService->order = $order;
        $orderService->updateOrder();
        $orderService->moveCartItemsToOrderItems();

        $paytabs_response_code = Order::PAYTABS_RESPONSE_STATUSES[$request->response_status];

        if (
            array_search(
                $paytabs_response_code,
                Order::PAYMENT_STATUSES
            ) ==
            array_search(
                Order::PAYMENT_STATUSES['PAID'],
                Order::PAYMENT_STATUSES
            )
        ) {
            event(new NewOrder($order));
            $message = 'تم الطلب بنجاح';
        } else {

            $message = 'حدث خطأ اثناء عملة الدفع, برجاء التواصل مع ادارة الموقع.';
        }

        return $this->apiResponse(data: new OrderResource($orderService->order), message: $message);;
    }

    public function rate(OrderRateRequest $request, Order $order)
    {
        $orderService = new OrderService($request->validated(), Auth::guard('api')->user());
        $orderService->order = $order;
        $orderService->rateOrder();
        return $this->apiResponse(data: new OrderResource($orderService->order), message: "Rate send successfully.");;
    }
}
