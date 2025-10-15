<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Expenses;
use App\Traits\ApiTrait;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\Obligations;
use App\Models\UserRequest;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\UserCoordinate;
use App\Events\OrderChangeStatus;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpensesResource;
use App\Http\Resources\ListStoreResource;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\ListRequestResource;
use App\Http\Resources\ViewObligationsResource;
use App\Http\Requests\Partner\UserRequest\ReplyUserRequestRequest;

class PartnerController extends Controller
{
    use GeneralTrait, ApiTrait;

    public function listRequest()
    {
        return $this->apiResponse(
            data: ListRequestResource::collection(
                UserRequest::where([
                    "is_active" => 1, 'client_id' => auth('api')->id(), 'status' => UserRequest::REQUEST_STATUS['pending'],
                ])
                    ->with([
                        'product' => fn ($q) => $q->select([
                            'id',
                            'name',
                            'image',
                            'old_price',
                            'current_price',
                        ]),
                        'product.productFeatures:product_id,description'
                    ])
                    ->get()
            ),
        );
    }

    public function replayUserRequest(ReplyUserRequestRequest $request, UserRequest $userRequest)
    {
        $userRequest->update(['status' => UserRequest::REQUEST_STATUS[$request->status]] + $request->validated());
        return $this->apiResponse(message: "Received reply successfully.");
    }

    public function orders()
    {
        $orders = self::partnerOrders();
        return $this->apiResponse(data: ['statistics' => self::getStatistics($orders), 'orders' => Order::filterOrders($orders)]);
    }

    private static function partnerOrders()
    {
        return Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereIn(
                'order_items.product_id',
                UserRequest::where('client_id', auth('api')->user()->id)
                    ->pluck('product_id')
            )
            ->select([
                'orders.id',
                'orders.code_order',
                'orders.total',
                'orders.status',
                'orders.created_at',
                DB::raw('COUNT(order_items.id) as items_count')
            ])
            ->groupBy('orders.id', 'orders.code_order', 'orders.total', 'orders.status', 'orders.created_at')
            ->get();
    }

    private static function getStatistics($orders): array
    {
        /*
//remain($user->capital - ($expenses_total + $obligations_total +  $userProducts->priceSum))
//profit(($order_total_sum - ($userProducts->priceSum + ($order_total_sum * $settings->tax_rate)
*/

        $user = auth('api')->user();
        $settings = Contact::select(['tax_rate', 'zakat_percentage'])->first();
        $order_total_sum = $orders->sum('total');
        $userProducts = Product::whereIn(
            'id',
            UserRequest::where('client_id', $user->id)->pluck('product_id')
        )->selectRaw('SUM(mainprice) as priceSum, SUM(quantity) as quantitySum')->first();

        $expenses_total = Expenses::where('client_id', $user->id)->sum('total_money');
        $obligations_total = Obligations::where('client_id', $user->id)->sum('total_money');
        return [
            'user_capital' => (string) $user->capital,
            'total_orders' => (string) $order_total_sum,
            'expenses' => (string) $expenses_total,
            'remain' => (string)  $user->residual,
            'profit' => (string)  $user->profit,
            'products_quantities' => $userProducts->quantitySum ? (string)  $userProducts->quantitySum : (string)  0,
            'users_count' => (string) Client::count(),
            'visiters' => (string) UserCoordinate::count(),
        ];
    }

    // public function partner_waiting_orders()
    // {

    //     $myOrder = Order::where("status", Order::ORDER_STATUSES['PENDING'])->get();
    //     if ($myOrder->count() > 0) {
    //         foreach ($myOrder as $list) {
    //             $maindetails['id'] = $list->id;
    //             $maindetails['code_order'] = $list->code_order;
    //             $maindetails['product_rate'] = $list->rate;
    //             $maindetails['client_id'] = $list->client_id;
    //             $maindetails['store_id'] = $list->store_id;
    //             $maindetails['address'] = $list->address;
    //             if ($list->promo_code_id == null) {
    //                 $maindetails['promo_code_id'] = 0;
    //                 $maindetails['promo_code_value'] = 0;
    //             } else {
    //                 $code = PromoCode::where('id', $list->promo_code_id)->first();

    //                 $maindetails['promo_code_id'] = $list->promo_code_id;
    //                 $maindetails['promo_code_value'] = $code->value;
    //             }
    //             $maindetails['delivery_type'] = $list->delivery_type;
    //             $maindetails['delivery_cost'] = $list->delivery_cost;
    //             $maindetails['sub_total'] = $list->sub_total;
    //             $maindetails['total'] = $list->total;
    //             $maindetails['status'] = $list->status;
    //             $maindetails['payment_status'] = $list->payment_status;
    //             $maindetails['payment_type'] = $list->payment_type;
    //             $maindetails['username'] = $list->username;
    //             $maindetails['userphone'] = $list->userphone;
    //             $maindetails['order_date'] = $list->created_at;
    //             $products[] = $maindetails;
    //         }
    //         return $this->returnData('data', $products, 'success');
    //     } else {
    //         $products = [];
    //         return $this->returnData('data', $products, 'success');
    //     }
    // }

    // public function partner_old_orders()
    // {

    //     $myOrder = Order::whereIn("status", [Order::ORDER_STATUSES['DONE'], Order::ORDER_STATUSES['REJECTED']])->get();
    //     if ($myOrder->count() > 0) {
    //         foreach ($myOrder as $list) {
    //             $maindetails['id'] = $list->id;
    //             $maindetails['code_order'] = $list->code_order;
    //             $maindetails['product_rate'] = $list->rate;
    //             $maindetails['client_id'] = $list->client_id;
    //             $maindetails['store_id'] = $list->store_id;
    //             $maindetails['address'] = $list->address;
    //             if ($list->promo_code_id == null) {
    //                 $maindetails['promo_code_id'] = 0;
    //                 $maindetails['promo_code_value'] = 0;
    //             } else {
    //                 $code = PromoCode::where('id', $list->promo_code_id)->first();

    //                 $maindetails['promo_code_id'] = $list->promo_code_id;
    //                 $maindetails['promo_code_value'] = $code->value;
    //             }
    //             $maindetails['delivery_type'] = $list->delivery_type;
    //             $maindetails['delivery_cost'] = $list->delivery_cost;
    //             $maindetails['sub_total'] = $list->sub_total;
    //             $maindetails['total'] = $list->total;
    //             $maindetails['status'] = $list->status;
    //             $maindetails['payment_status'] = $list->payment_status;
    //             $maindetails['payment_type'] = $list->payment_type;
    //             $maindetails['username'] = $list->username;
    //             $maindetails['userphone'] = $list->userphone;
    //             $maindetails['order_date'] = $list->created_at;
    //             $products[] = $maindetails;
    //         }
    //         return $this->returnData('data', $products, 'success');
    //     } else {
    //         $products = [];
    //         return $this->returnData('data', $products, 'success');
    //     }
    // }

    // public function partner_current_orders()
    // {

    //     $myOrder = Order::whereIn('status', [Order::ORDER_STATUSES['ACCEPTED'], Order::ORDER_STATUSES['DELIVERING'], Order::ORDER_STATUSES['PREPAREING']])->get();
    //     //dd($myOrder);
    //     if ($myOrder->count() > 0) {
    //         foreach ($myOrder as $list) {
    //             $maindetails['id'] = $list->id;
    //             $maindetails['code_order'] = $list->code_order;
    //             $maindetails['product_rate'] = $list->rate;
    //             $maindetails['client_id'] = $list->client_id;
    //             $maindetails['store_id'] = $list->store_id;
    //             $maindetails['address'] = $list->address;
    //             if ($list->promo_code_id == null) {
    //                 $maindetails['promo_code_id'] = 0;
    //                 $maindetails['promo_code_value'] = 0;
    //             } else {
    //                 $code = PromoCode::where('id', $list->promo_code_id)->first();

    //                 $maindetails['promo_code_id'] = $list->promo_code_id;
    //                 $maindetails['promo_code_value'] = $code->value;
    //             }
    //             $maindetails['delivery_type'] = $list->delivery_type;
    //             $maindetails['delivery_cost'] = $list->delivery_cost;
    //             $maindetails['sub_total'] = $list->sub_total;
    //             $maindetails['total'] = $list->total;
    //             $maindetails['status'] = $list->status;
    //             $maindetails['payment_status'] = $list->payment_status;
    //             $maindetails['payment_type'] = $list->payment_type;
    //             $maindetails['username'] = $list->username;
    //             $maindetails['userphone'] = $list->userphone;
    //             $maindetails['order_date'] = $list->created_at;
    //             $products[] = $maindetails;
    //         }
    //         return $this->returnData('data', $products, 'success');
    //     } else {
    //         $products = [];
    //         return $this->returnData('data', $products, 'success');
    //     }
    // }

    // public function partner_holding_orders()
    // {

    //     $myOrder = Order::where("status", Order::ORDER_STATUSES['HOLDING'])->get();
    //     if ($myOrder->count() > 0) {
    //         foreach ($myOrder as $list) {
    //             $maindetails['id'] = $list->id;
    //             $maindetails['code_order'] = $list->code_order;
    //             $maindetails['product_rate'] = $list->rate;
    //             $maindetails['client_id'] = $list->client_id;
    //             $maindetails['store_id'] = $list->store_id;
    //             $maindetails['address'] = $list->address;
    //             if ($list->promo_code_id == null) {
    //                 $maindetails['promo_code_id'] = 0;
    //                 $maindetails['promo_code_value'] = 0;
    //             } else {
    //                 $code = PromoCode::where('id', $list->promo_code_id)->first();

    //                 $maindetails['promo_code_id'] = $list->promo_code_id;
    //                 $maindetails['promo_code_value'] = $code->value;
    //             }
    //             $maindetails['delivery_type'] = $list->delivery_type;
    //             $maindetails['notes'] = $list->notes;
    //             $maindetails['delivery_cost'] = $list->delivery_cost;
    //             $maindetails['sub_total'] = $list->sub_total;
    //             $maindetails['total'] = $list->total;
    //             $maindetails['status'] = $list->status;
    //             $maindetails['payment_status'] = $list->payment_status;
    //             $maindetails['payment_type'] = $list->payment_type;
    //             $maindetails['username'] = $list->username;
    //             $maindetails['userphone'] = $list->userphone;
    //             $maindetails['order_date'] = $list->created_at;
    //             $products[] = $maindetails;
    //         }
    //         return $this->returnData('data', $products, 'success');
    //     } else {
    //         $products = [];
    //         return $this->returnData('data', $products, 'success');
    //     }
    // }


    public function change_status(Request $request)
    {
        $client = auth('api')->user();
        $order = Order::where("id", $request->order_id)->first();
        $myOrder = $order->update(['status' => (string)$request->status, 'notes' => $request->note]);
        $message = match ((string)$request->status) {
            "0" => 'The order is pending.',
            "1" => 'The order has been accepted.',
            "2" => 'The order has been rejected.',
            "3" => 'The order is currently being delivered.',
            "4" => 'The order is done and completed.',
            "5" => 'The order is holding now.',
            "6" => 'The order is preparing.',
            default => 'Invalid status provided.',
        };

        event(new OrderChangeStatus($order));
        return $this->returnSuccessMessage('تم التعديل بنجاح');
    }

    public function liststore()
    {
        return $this->apiResponse(
            data: ListStoreResource::collection(
                UserRequest::where([
                    "is_active" => 1,
                    'status' => UserRequest::REQUEST_STATUS['accept'],
                    'client_id' => auth('api')->user()->id,
                ])
                    ->withSum('orderItems', 'quantity')
                    ->with(['product.productFeatures'])
                    ->get()
            ),
        );
    }

    public function obligations(Request $request)
    {
        return $this->apiResponse(data: ViewObligationsResource::collection(Obligations::where("client_id", auth('api')->id())->where('type', $request->type)->get()));
    }

    public function expenses(Request $request)
    {
        return $this->apiResponse(
            data: ExpensesResource::collection(
                Expenses::where("client_id", auth('api')->id())
                    ->select(['id', 'details', 'total_money', 'file', 'created_at'])
                    ->get()
            )
        );
    }

    public function assignment(Request $request)
    {
        return $this->apiResponse(
            data: AssignmentResource::collection(
                UserRequest::where([
                    "is_active" => 1,
                    'status' => 1,
                    'client_id' => auth('api')->user()->id,
                ])
                    ->withSum('orderItems', 'quantity')
                    ->with([
                        'client',
                        'product.productFeatures',
                    ])
                    ->get()
            ),
        );
    }
}
