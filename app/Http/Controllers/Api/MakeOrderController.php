<?php
namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\GiftWallet;
use App\Services\SmsaService;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class MakeOrderController
{
    use GeneralTrait;

    public $smsaService;

    public function __construct()
    {
        $this->smsaService = new SmsaService;
    }

    public function makeOrder(Request $request)
    {
        DB::beginTransaction();
        $user = auth('api')->user();
        $cart = Cart::where('client_id', $user->id)->first();
        if (!$cart || count($cart->items) < 1) {
            return $this->returnError(404, 'السلة فارغة');
        } else {
            try {
                $validator = Validator::make($request->all(), [
                    'payment_status' => 'required',
                    'payment_type' => 'required',
                    'sub_total' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json($validator->errors()->toJson(), 400);
                }
                //  $address_id=$request->address_id;
                //  if ($request->address_save ==1)
                //  {
                //      $address = Address::create(['location'=>$request->location]);
                //      $address_id=$address->id;
                //  }
                if ($request->promo_code_id) {
                    $promo = PromoCode::where([
                        'id'           => $request->promo_code_id
                    ])->first();
                }

                for ($i = 0; $i < count($cart->items); $i++) {
                    $ids[] = $cart->items[$i]->product_id;
                    $qt[] = $cart->items[$i]->qt;
                }
                $oldtotalbonus = $user->total_point;
                $product = Product::whereIn('id', $ids)->first();
                $subtotal = $request->sub_total;
                $cashback = $request->cashback;
                $totalgift = $request->totalgift;
                $coupon_id = $request->coupon_id;

                if ($totalgift > 0) {
                    $giftwallet = GiftWallet::where('user_id', auth('api')->user()->id)->where('remaining', '!=', 1)->get();
                    if ($giftwallet->sum("price") > 0) {
                        $total_aftergift = $subtotal - $totalgift;
                    } else {
                        $total_aftergift = $subtotal;
                    }
                } else {
                    $total_aftergift = $subtotal;
                }

                if ($cashback > 0 && auth('api')->user()->total_point >= $cashback) {

                    $total_cash = $total_aftergift - $cashback;
                    $final_cashback = auth('api')->user()->total_point - $cashback;
                    if ($final_cashback < 0) {
                        $final_cashback = 0;
                    }
                    $clientbonus = Client::where('id', auth('api')->user()->id)->update([
                        'total_point'          => $final_cashback,
                    ]);
                } else {
                    $total_cash = $total_aftergift;
                }
                if ($coupon_id > 0) {

                    $promocoup = PromoCode::where(['id' => $request->coupon_id])->first();
                    if ($promocoup) {
                        $couo_precnt = (100 - $promocoup->value);
                        $total_coup = ($couo_precnt * $total_cash) / (100);
                    } else {
                        $total_coup = $total_cash;
                    }
                } else {
                    $total_coup = $total_cash;
                }

                $order = Order::create([
                    'code_order'           =>  $this->generateOrderNumber(),
                    'client_id'            =>  auth('api')->user()->id,
                    'payment_status'       =>  $request->payment_status,
                    'payment_type'         =>  $request->payment_type,
                    'promo_code_id'        =>  $request->promo_code_id ? $promo->id : null,
                    'status'               => '0',
                    'sub_total'            =>  $total_coup,
                    'total'                =>  $request->promo_code_id ? ($total_coup + 10) - $promo->value : $total_coup + 10,
                    'delivery_cost'        =>  10, // from smsa
                    'delivery_type'        =>  $request->delivery_type, // remove
                    'order_date'           =>  date("Y-m-d"),
                    'username'             =>  $request->name,
                    'userphone'            =>  $request->phone,
                    'store_id'             =>  $product->category->store_id, // remove
                    'country_id'           =>  $request->country_id,
                    'city_id'              =>  $request->city_id,
                    'address'              =>  $request->address,
                ]);


                $cashbak = Contact::select('cashback_info_total_up', 'cashback_info_total_down', 'first_cashback', 'second_cashback')->where('id', 1)->first();
                if ($cashbak->cashback_info_total_down == $request->sub_total) {
                    $newtotalbouns = $oldtotalbonus + $cashbak->first_cashback;
                } else if ($request->sub_total > $cashbak->cashback_info_total_up) {
                    $newtotalbouns = $oldtotalbonus + $cashbak->second_cashback;
                } else {
                    $newtotalbouns = $oldtotalbonus;
                }


                $clientbonus = Client::where('id', auth('api')->user()->id)->update([
                    'total_point'          =>       $newtotalbouns,
                ]);

                //  foreach ($bonuses as $bonus)
                //  {
                //      if($order->total>= $bonus->start_bouns && $order->total<= $bonus->end_bonus)
                //      {
                //          $bonusPoint =new BounsPoint;
                //          $bonusPoint->bouns_id               = $bonus->id;
                //          $bonusPoint->order_id               = $order->id;
                //          $bonusPoint->client_id              = auth('api')->user()->id;
                //          $bonusPoint->order_point            = $bonus->point;
                //          $bonusPoint->save();

                //          $oldtotalbonus = $user->total_point;
                //          $newtotalbouns = $oldtotalbonus+$bonus->point;

                //      }
                //  }

                //   return $this->returnData('data',$cart->items,'تمت العملية بنج');
                foreach ($cart->items as $item) {
                    $product = Product::where('id', $item->product_id)->first();
                    OrderItem::create([
                        'order_id'   => $order->id,
                        'product_id' => $item->product_id,
                        'quantity'   => $item->quantity,
                        'features'   => $item->features,
                        'price'      => $item->price,
                        'total'      => $item->total,
                        'smart_price'      => $item->smart_price,
                    ]);
                    $item->delete();
                }
                // create shipment here

                $this->smsaService->createShipment();
                $cart->delete();

                DB::commit();


                return $this->returnData('data', $order, 'تمت العملية بنجاح');
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->returnError(404, $e->getMessage());
            }
        }
    }
}
