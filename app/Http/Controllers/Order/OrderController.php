<?php

namespace App\Http\Controllers\Order;

use App\Events\NewOrder;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Services\PaytabsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreClientOrderRequest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\Cashback;
class OrderController extends Controller
{
    public function store(StoreClientOrderRequest $request)
    {
       
        $myIp = substr(str_replace('.', '', $request->ip()), 0, 10);
        $user = auth('clientsWeb')->user();
        if($request->points){
         Cashback::updateOrCreate(
                ['client_id' => $user->id], 
                [
                    'points' =>$request->points 
                ]
            );            
        }

        $orderService = new OrderService($request->validated(), $user ,$myIp,$request->discount,$request->cashback);
        $orderService
            ->calcTotalCartPrice()
            ->calcOrderSubTotal()
            ->handleOrderCreation();
        if($request->paying == 'visa') {
            $paytabsService = new PaytabsService($orderService->order, ['id' => (string)$user->id] + $request->validated());
            $pay = $paytabsService->pay();
             $orderService->moveCartItemsToOrderItems();
            return $pay;
        }
       
        $orderService->moveCartItemsToOrderItems();
        return redirect()->route('lastOrder')->with('success', trans('front.order_confirmed'));

    }

    public function callback(Request $request)
    {
        // dd($request);
        // $order = Order::where(['id' => $request->cartId, 'code_order' => $request->codeId])->first();
        // if (!$order) {
        //     throw new NotFoundHttpException();
        // }
        // $paytabs_response_code = Order::PAYTABS_RESPONSE_STATUSES[$request->respStatus];

        // $order->update([
        //     'payment_status' => $paytabs_response_code,
        //     'payment_ref_code' => $request->tranRef,
        // ]);
    }

    public function redirect(Request $request)
    {
        $order = Order::where(['id' => $request->cartId, 'code_order' => $request->codeId])->first();
        if (!$order) {
            throw new NotFoundHttpException();
        }
        $paytabs_response_code = Order::PAYTABS_RESPONSE_STATUSES[$request->respStatus];

        $order->update([
            'payment_status' => $paytabs_response_code,
            'payment_ref_code' => $request->tranRef,
        ]);


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
            $oderService = new OrderService(user: $order->client);
            $oderService->setOrder($order)->moveCartItemsToOrderItems();

            toastr()->success('تم الطلب بنجاح');
            return redirect(route('check', ['order_id' => $order->id, 'client_id' => $order->client_id]));
        }

        toastr()->error('حدث خطأ اثناء عملة الدفع, برجاء التواصل مع ادارة الموقع.');
        return redirect()->route('/');
    }
        public function cancelOrder(Request $request, $id)
{
    $order = Order::find($id);

    if (!$order) {
        return redirect()->back()->withErrors(['order' => trans('front.order_not_found')]);
    }

    $order->status = 8;
    $order->save();

    return redirect()->back()->with('success', trans('front.order_cancelled_success'));
}
}
