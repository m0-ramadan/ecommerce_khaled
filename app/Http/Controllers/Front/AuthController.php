<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\City;
use App\Models\Bouns;
use App\Models\Order;
use App\Models\Client;
use App\Models\Review;
use App\Models\Address;
use App\Models\Country;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Favorite;
use App\Models\OrderItem;
use App\Models\BounsPoint;
use App\Models\GiftWallet;
use App\Traits\GeneralTrait;
use App\Models\ListFavorites;
use Illuminate\Http\Request;
use App\Services\OrderService;
use App\Traits\UploadFileTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Requests\Order\StoreClientOrderRequest;
use App\Models\Coupons;
use App\Models\CouponUsage;
use App\Models\Cashback;
use App\Models\Setting;

class AuthController extends Controller
{
    use GeneralTrait, UploadFileTrait;

    public function login()
    {
        return view('Front.login');
    }


    public function loginPage() // for api users
    {
        return view('Front.api.login');
    }

    public function loginToCheckout(Request $request) // for api users
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('clientsWeb')->attempt(['email' => request('email'), 'password' => request('password')]) || Auth::guard('clientsWeb')->attempt(['phone' => request('email'), 'password' => request('password')])) {
            toastr()->success('تــم تسجيــل الدخول بنجــاح');
            return redirect()->route('checkout.page');
        }
        dd("error");
        // toastr()->error('يوجـــد مشكلة في البيــانات يرجي التأكد والمحـاولة مرة أخري');
        // return back()->withInput($request->only('email'));
        toastr()->error(trans('front.login_error'));
        return back()->withInput($request->only('email'));
    }

    public function checkoutPage() // for api users
    {
        $client = auth('clientsWeb')->user();

        $cart = Cart::where(['client_id' => $client->id])
            ->withCount('items')
            ->latest()->first();

        if (!$cart || $cart->items_count <= 0) {
            // toastr()->error('لا يوجد منتجات في السلة اضف بعض المنتجات اولا');
            // return back();
            toastr()->error(trans('front.cart_empty'));
            return back();
        }


        $countries = Country::get(['id', 'name']);

        $cities = City::whereIn('countries_id', $countries->pluck('id')->toArray())->get(['id', 'name']);

        return view('Front.api.checkout', compact(['cart', 'client', 'countries', 'cities']));
    }


    public function signup()
    {
        $countries = Country::get();
        $cities = City::get();
        return view('Front.register', compact('countries', 'cities'));
    }

    public function doLogin(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('clientsWeb')->attempt(['email' => request('email'), 'password' => request('password')]) || Auth::guard('clientsWeb')->attempt(['phone' => request('email'), 'password' => request('password')])) {
            self::updateCartItems();
            // $mess = __('front.deletess');
            return redirect('/')->with(['success' => trans('front.success_message')]);
        } else {
            // $mess = __('front.errorlogin');
            return redirect('/')->withInput($request->only('email'))->with(['error' => trans('front.login_failed')]);
        }
    }

    public static function updateCartItems()
    {
        $user = auth()->guard('clientsWeb')->user();
        $myIp = substr(str_replace('.', '', request()->ip()), 0, 10);
        $userCart = Cart::whereNull('ip_address')->firstOrCreate(['client_id' => $user->id]);

        $offlineCart = Cart::where('ip_address', $myIp)->whereNull('client_id')->with(['items'])->latest()->first();
        if ($offlineCart) {
            foreach ($offlineCart->items as $item) {
                if (!$userCart->items->contains('product_id', $item->product_id)) {
                    $userCart->items()->create([
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'total' => $item->total,
                        'price' => $item->price,
                        'features' => $item->features,
                        'smart_price' => $item->smart_price,
                        'smart_type' => $item->smart_type,
                    ]);
                }
            }
            $offlineCart->items()->delete();
            $offlineCart->delete();
        }
    }

    public function logout()
    {
        Auth::logout();
        $mess = __('front.deletess');
        return redirect('client-login')->with(['success' => $mess]);
    }

    public function register(RegisterRequest $request)
    {

        $client = Client::create(['password' => Hash::make($request->password)] + $request->validated());
        //   dd ($client);
        // $client->addresses()->create(['location' => $request->address]);
        $client->cart()->create();
        // toastr()->success('تــم التسجيــل بنجــاح');
        // return redirect('/');
        toastr()->success(trans('front.registration_success')); // Updated for localization
        return redirect('/');
    }

    public function profileDetails()
    {
        $userAddress = Address::where('client_id', Auth::guard('')->user()->id)->get();
        $details = Auth::guard('')->user();
        $cities = City::get();
        return view('Front.pages.profileDetails', compact(['userAddress', 'details', 'cities']));
    }

    public function profileEdit(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'email' => 'nullable|unique:clients,email,' . Auth::user()->id,
            'phone' => 'required|unique:clients,phone,' . Auth::user()->id,
            'state_id' => 'nullable',
            'address' => 'nullable',
        ]);
        $profile = Client::where('id', Auth::guard('')->user()->id)->first();

        if ($request->hasfile('image')) {
            $filepath = $this->uploadFile('clients', $request->image);
            $profile->update([
                'image' => $filepath,
            ]);
        }
        // if ($request->password) {
        //     $profile->update([
        //         'password' => bcrypt($request->password),
        //     ]);
        // }
        $profile->name = $request->name;
        $profile->email = $request->email;
        $profile->phone = $request->phone;
        $profile->state_id = $request->state_id;
        $profile->save();
        $address = Address::firstOrCreate(['client_id' => Auth::guard('')->user()->id, 'location' => $request->address]);
        $address->location = $request->address;
        $address->save();
        // toastr()->success('تم التعديل بنجاح');
        // return back();
        toastr()->success(trans('front.profile_update_success')); // Updated for localization
        return back();
    }

    public function addressClient()
    {
        $address = Address::where('client_id', Auth::guard('clientsWeb')->user()->id)->get();
        return view('Front.pages.profile.addressClient', compact('address'));
    }

    public function profileFav()
    {
        $favorites = Favorite::where('client_id', Auth::guard('clientsWeb')->user()->id)->get();
        return view('Front.pages.profile.profileFavUser', compact('favorites'));
    }

    public function profileRequestUser()
    {
        $orders = Order::where('client_id', Auth::guard('clientsWeb')->user()->id)->with(['orderitem.product'])->get();

        return view('Front.pages.profile.profileRequests', compact(['orders']));
    }
    public function myLastOrder(Request $request)
    {

        $myIp = substr(str_replace('.', '', $request->ip()), 0, 10);


        $user = Auth::guard('clientsWeb')->user();


        $order = Order::where('client_id', $myIp)
            ->when($user, function ($query) use ($user) {
                return $query->orWhere('client_id', $user->id);
            })
            ->latest()
            ->first();

        // Return the order to the view
        return view('Front.pages.last_order', compact('order'));
    }

    public function changePasswordView()
    {
        $userAddress = Address::where('client_id', Auth::guard('')->user()->id)->get();
        $details = Auth::guard('')->user();
        return view('Front.pages.change_password', compact(['userAddress', 'details']));
    }

    public function changePassword(Request $request)
    {
        $validatedData = $request->validate([
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        $profile = Client::where('id', Auth::guard('')->user()->id)->first();

        if ($request->password) {
            $profile->update([
                'password' => bcrypt($request->password),
            ]);
        }
        // toastr()->success('تم التعديل بنجاح');
        toastr()->success(trans('front.password_updated'));
        return back();

    }

    public function myCart(Request $request)
    {
        /************************part of authinticated registered ones( guests )*********************/
        if (Auth::guard('clientsWeb')->check() && Auth::guard('clientsWeb')->user()->id > 0) {

            $cart = Cart::where('client_id', Auth::guard('clientsWeb')->user()->id)->whereNull('ip_address')->latest()->first();
            $ids = [];
            if (isset($cart->items)) {
                for ($i = 0; $i < count($cart->items); $i++) {
                    $ids[] = $cart->items[$i]->product_id;
                    $pro = Product::where('id', $cart->items[$i]->product_id)->first();
                    $total[] = $pro->price * $cart->items[$i]->quantity;
                }
            }
            $products = Product::whereIn('id', $ids)->get();
            if ($products->count() > 0) {
                $total_price = 0;
                for ($i = 0; $i < count($total); $i++) {
                    $total_price = $total_price + $total[$i];
                }
                return view('Front.pages.myCart', compact(['products', 'total_price', 'cart']));
            }
            return view('Front.pages.myCart', compact(['products', 'cart']));
            /************************end part of authinticated registered ones( guests )*********************/
        } else {
            /************************part of non authinticated registered ones( guests )*********************/
            $myIp = substr(str_replace('.', '', $request->ip()), 0, 10);
            $cart = Cart::where('ip_address', $myIp)->whereNull('client_id')->latest()->first();
            $ids = [];
            if (isset($cart->items)) {
                for ($i = 0; $i < count($cart->items); $i++) {
                    $ids[] = $cart->items[$i]->product_id;
                    $pro = Product::where('id', $cart->items[$i]->product_id)->first();
                    $total[] = $pro->price * $cart->items[$i]->quantity;
                }
            }
            $products = Product::whereIn('id', $ids)->get();
            if ($products->count() > 0) {
                $total_price = 0;
                for ($i = 0; $i < count($total); $i++) {
                    $total_price = $total_price + $total[$i];
                }
                return view('Front.pages.myCart', compact(['products', 'total_price', 'cart']));
            }
            return view('Front.pages.myCart', compact(['products', 'cart']));


            /************************end part of non authinticated registered ones( guests )*********************/
        }
    }

    public function addToCart(Request $request, $id)
    {
        /************************part of authinticated registered ones( guests )*********************/

        if (Auth::guard('clientsWeb')->check() && Auth::guard('clientsWeb')->user()->id > 0) {
            $cart = Cart::where('client_id', Auth::guard('clientsWeb')->user()->id)->whereNull('ip_address')->latest()->first();
            if (!$cart) {
                $cart = new Cart;
                $cart->client_id = Auth::guard('clientsWeb')->user()->id;
                $cart->save();

                $cartItem = CartItem::where(['product_id' => $id, 'cart_id' => $cart->id])->latest()->first();
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $id,
                    'quantity' => 1,
                ]);
            } else {
                $cartItem = CartItem::where(['product_id' => $id, 'cart_id' => $cart->id])->latest()->first();
                if ($cartItem) {
                    toastr()->error('تم الاضافة للسلة من قبل');
                    return back();
                } else {
                    CartItem::create([
                        'cart_id' => $cart->id,
                        'product_id' => $id,
                        'quantity' => 1,
                    ]);
                }
            }
            // toastr()->success('تم الاضافة للسلة بنجاح');
            // return back();
            toastr()->success(trans('front.added_to_cart_successfully'));
            return back();
            /************************end part of authinticated registered ones( guests )*********************/
        } else {

            /************************part of non registered ones( guests )*********************/
            $myIp = substr(str_replace('.', '', $request->ip()), 0, 10);
            $cart = Cart::where('ip_address', $myIp)->whereNull('client_id')->latest()->first();

            if (!$cart) {

                $cart = new Cart;
                $cart->client_id = null;
                $cart->ip_address = $myIp;
                $cart->save();
            }

            $cartItem = CartItem::where(['product_id' => $id, 'cart_id' => $cart->id])->latest()->first();

            // $cartItem = CartItem::where(['product_id'=>$id,'cart_id'=>$_COOKIE['my_coockie']])->first();
            if ($cartItem) {
                toastr()->error('تم الاضافة للسلة من قبل');
                return back();
            } else {
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $id,
                    'quantity' => 1,
                ]);
            }
        }
        // toastr()->success(trans('front.added_to_cart_successfully'));
        // return back();
        toastr()->success(trans('front.added_to_cart_successfully'));
        return back();
    }

    public function updateQuantity($id, $type, Request $request)
    {
        /************************part of authinticated registered ones( guests )*********************/
        if (Auth::guard('clientsWeb')->check() && Auth::guard('clientsWeb')->user()->id > 0) {
            $cart = Cart::where('client_id', Auth::guard('clientsWeb')->user()->id)->whereNull('ip_address')->latest()->first();
            $cartItem = CartItem::where(['product_id' => $id, 'cart_id' => $cart->id])->latest()->first();
            $quantity = 0;
            if ($cartItem) {
                if ($type == 0) {
                    if ($cartItem->quantity - 1 < 1) {
                        $quantity = 1;
                    } else {
                        $quantity = $cartItem->quantity - 1;
                        //return $quantity;
                        $cartItem->update([
                            'cart_id' => $cart->id,
                            'product_id' => $id,
                            'quantity' => $quantity,
                        ]);
                    }
                } else {
                    $cartItem->update([
                        'cart_id' => $cart->id,
                        'product_id' => $id,
                        'quantity' => $cartItem->quantity + 1,
                    ]);
                }
            }
            return back();
        } else {

            /************************part of non registered ones( guests )*********************/
            $myIp = substr(str_replace('.', '', $request->ip()), 0, 10);

            $cart = Cart::where('ip_address', $myIp)->whereNull('client_id')->latest()->first();
            $cartItem = CartItem::where(['product_id' => $id, 'cart_id' => $cart->id])->latest()->first();
            $quantity = 0;
            if ($cartItem) {
                if ($type == 0) {
                    if ($cartItem->quantity - 1 < 1) {
                        $quantity = 1;
                    } else {
                        $quantity = $cartItem->quantity - 1;
                        //return $quantity;
                        $cartItem->update([
                            'cart_id' => $cart->id,
                            'product_id' => $id,
                            'quantity' => $quantity,
                        ]);
                    }
                } else {
                    $cartItem->update([
                        'cart_id' => $cart->id,
                        'product_id' => $id,
                        'quantity' => $cartItem->quantity + 1,
                    ]);
                }
            }
            return back();
        }
        /************************end part of non registered ones( guests )*********************/
    }

    public function removeFromCart($id, Request $request)
    {
        /************************part of authinticated registered ones( guests )*********************/
        if (Auth::guard('clientsWeb')->check() && Auth::guard('clientsWeb')->user()->id > 0) {

            $cart = Cart::where('client_id', Auth::guard('clientsWeb')->user()->id)->latest()->first();
            $item = CartItem::where(['cart_id' => $cart->id, 'product_id' => $id])->latest()->first();
            if ($item) {
                $item->delete();
            }
            // toastr()->success('تم الحذف بنجاح');
            // return back();
            toastr()->success(trans('front.deleted_successfully'));
            return back();
        } else {
            /************************part of non registered ones( guests )*********************/
            $myIp = substr(str_replace('.', '', $request->ip()), 0, 10);
            $cart = Cart::where('ip_address', $myIp)->whereNull('client_id')->latest()->first();

            // $cart = Cart::where('client_id', Auth::guard('clientsWeb')->user()->id)->first();
            $item = CartItem::where(['cart_id' => $cart->id, 'product_id' => $id])->first();
            if ($item) {
                $item->delete();
            }
            // toastr()->success('تم الحذف بنجاح');
            // return back();

            toastr()->success(trans('front.deleted_successfully'));
            return back();
            /************************end part of non registered ones( guests )*********************/
        }
    }

    public function checkout(Request $request)
    {
        $discount=0;
        $message=null;
        $cashback=0;
        $points=0;
        $myIp = substr(str_replace('.', '', $request->ip()), 0, 10);
        
        
        if( $request->promo_code){
            $request->validate([
                'promo_code' => 'string|exists:coupons,code'
            ]);
         $coupon = Coupons::where('code', $request->promo_code)->first();

        if ($coupon && $coupon->start_date <= now() && $coupon->expiry_date > now() && $coupon->status == 1 && $coupon->num_times > 0) {
            $usedTimes = CouponUsage::where('coupon_id', $coupon->id)->where(function ($query) use ($myIp) {$query->where('ip_address', $myIp)->orWhere('user_id', Auth::guard('clientsWeb')->check() ? Auth::guard('clientsWeb')->user()->id : null);})->count();

            if ($usedTimes <= $coupon->num_use_user) {
             $discount=Coupons::where('code', $request->promo_code)->first();
                $coupon->num_times -= 1;
                $coupon->save();
        
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'ip_address' => $myIp,
                    'user_id' => Auth::guard('clientsWeb')->check() ? Auth::guard('clientsWeb')->user()->id : null,
                ]);
       
            } else {
                $message= 'لقد استخدمت هذا الرمز من قبل.';
            }
        } else {
             $message='رمز الخصم غير صالح أو منتهي الصلاحية.';
            }
        }

        /************************part of authinticated registered ones( guests )*********************/
        if (Auth::guard('clientsWeb')->check() && Auth::guard('clientsWeb')->user()->id > 0) {

            $giftWallets = GiftWallet::where('client_id', auth('clientsWeb')->id())
                // ->with(['card'])
                ->hasRemainPoints()
                ->active()
                ->get();

            $cart = Cart::where(['client_id' => Auth::guard('clientsWeb')->user()->id, 'ip_address' => null])
                ->latest()->first();
            if (isset($cart)) {
                if (count($cart->items) <= 0) {
                    // toastr()->error('لا يوجد منتجات في السلة اضف بعض المنتجات اولا');
                    toastr()->error(trans('front.no_products_in_cart'));

                    return back();
                } elseif (isset($cart->items) && count($cart->items) > 0) {
                    $ids = [];
                    for ($i = 0; $i < count($cart->items); $i++) {
                        $ids[] = $cart->items[$i]->product_id;
                        $pro = Product::where('id', $cart->items[$i]->product_id)->first();
                        $total[] = $pro->price * $cart->items[$i]->quantity;
                    }
           
                    // for ($i = 0; $i < count($total); $i++) {
                    //     $total_price = $total_price + $total[$i];
                    // }
                    $products = Product::whereIn('id', $ids)->get();
                    $total_price = $products->sum('current_price');
                    $client = Client::where('id', Auth::guard('clientsWeb')->user()->id)->first();
                    $countries = Country::get(['id', 'name']);
                    $cities = City::get(['id', 'name']);
                    $setting = Setting::first();
                    $mountPound = $setting->mount_pound ?? 0;
                    $points = (int)($total_price * $mountPound);                    
                     if ($request->cashback) {
                         
                        $cashback = Cashback::firstOrNew(['client_id' => Auth::guard('clientsWeb')->user()->id]);
                        $cashback->point_usage = $cashback->point_usage + $points; 
                        $cashback->save();
                       $cashback=$points * $setting->cashback_value;
                       $points=0;

                    }
                    if($message){
                      toastr()->success($message);
                    }
                    
                    return view('Front.pages.checkout', compact(['giftWallets', 'products', 'total_price', 'cart', 'client', 'countries', 'cities','discount','cashback','points']));
                }
            }
            /***********************end *part of authinticated registered ones( guests )*********************/
        } else {
            $myIp = substr(str_replace('.', '', $request->ip()), 0, 10);

            $giftWallets = GiftWallet::where('client_id', auth('clientsWeb')?->id())
                // ->with(['card'])
                ->hasRemainPoints()
                ->active()
                ->get();

            $cart = Cart::where(['ip_address' => $myIp])
                ->latest()->first();
            if (isset($cart)) {
                if (count($cart->items) <= 0) {
                    // toastr()->error('لا يوجد منتجات في السلة اضف بعض المنتجات اولا');
                    toastr()->error(trans('front.no_products_in_cart'));
                    return back();
                } elseif (isset($cart->items) && count($cart->items) > 0) {
                    $ids = [];
                    for ($i = 0; $i < count($cart->items); $i++) {
                        $ids[] = $cart->items[$i]->product_id;
                        $pro = Product::where('id', $cart->items[$i]->product_id)->first();
                        $total[] = $pro->price * $cart->items[$i]->quantity;
                    }
                    // $total_price = 0;
                    // for ($i = 0; $i < count($total); $i++) {
                    //     $total_price = $total_price + $total[$i];
                    // }
                    $products = Product::whereIn('id', $ids)->get();
                    $total_price = $products->sum('current_price');
                    $client = Client::where('id', Auth::guard('clientsWeb')->user()?->id)->first();
                    $countries = Country::get(['id', 'name']);
                    $cities = City::get(['id', 'name']);
                    if($message){
                       toastr()->success(trans($message)); 
                    }
                    
                    return view('Front.pages.checkout', compact(['giftWallets', 'products', 'total_price', 'cart', 'client', 'countries', 'cities','discount','cashback','points']));
                }

            }
            return view('Front.login', compact('myIp'));
        }
    }


    /*******************************************************************************/
    // public function makeOrder(StoreClientOrderRequest $request)
    // {
    //     $orderService = new OrderService($request->validated(), auth('clientsWeb')->user());
    //     $orderService
    //         ->calcTotalCartPrice()
    //         ->calcOrderSubTotal()
    //         ->handleOrderCreation();
    //     dd($orderService);
    //     toastr()->success('تم الطلب بنجاح');
    //     // return redirect(route('check', ['order_id' => $order_service->order->id, 'client_id' => $order_service->order->client_id]));
    // }


    public function reOrder($id)
    {
        $orderItem = OrderItem::where('order_id', $id)->get();
        $order = Order::where('id', $id)->first();
        $user = Auth::guard('clientsWeb')->user();
        if ($user) {
            $reorder = Order::create([
                'code_order' => $this->generateOrderNumber(),
                'client_id' => $user->id,
                'status' => 'pending',
                'payment_type' => 1,
                'payment_status' => 1,
                'delivery_cost' => 10,
                'address_id' => $order->address_id,
                'store_id' => 1,
                'sub_total' => $order->sub_total,
                'total' => $order->total,
            ]);
            foreach ($orderItem as $item) {
                OrderItem::create([
                    'order_id' => $reorder->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                ]);
            }
            // toastr()->success('تم الطلب بنجاح');
            toastr()->success(trans('front.order_success'));

            return redirect(url(route('check')));
        } else {
            // toastr()->error('يوجد خطا في الطلب');
            toastr()->error(trans('front.order_error'));

            return back();
        }
    }

    public function check($order_id, $client_id)
    {
        return view('Front.pages.check', compact(['order_id', 'client_id']));
    }

    // public function addToFav(Request $request, $id)
    // {
    //     $toggle = Favorite::where([
    //         ['product_id', $id],
    //         ['client_id', Auth::guard('clientsWeb')->user()->id]
    //     ])->first();
    //     if (!$toggle) {
    //         $fav = Favorite::create([
    //             'client_id' => Auth::guard('clientsWeb')->user()->id,
    //             'product_id' => $id
    //         ]);
    //         $mess = __('front.deletess');
    //         return redirect()->back()->with(['success' => $mess]);
    //     } else {
    //         $toggle->delete();
    //         $mess = __('front.deletess');
    //         return redirect()->back()->with(['success' => $mess]);
    //     }
    // }

    public function addToFav(Request $request, $id)
    {
        $toggle = Favorite::where([
            ['product_id', $id],
            ['client_id', Auth::guard('clientsWeb')->user()->id]
        ])->first();

        if (!$toggle) {
            Favorite::create([
                'client_id' => Auth::guard('clientsWeb')->user()->id,
                'product_id' => $id
            ]);
            $mess = trans('front.added_to_favorites');
            return redirect()->back()->with(['success' => $mess]);
        } else {
            $toggle->delete();
            $mess = trans('front.removed_from_favorites');
            return redirect()->back()->with(['success' => $mess]);
        }
    }
    public function myFav()
    {
        $favorites = Favorite::where('client_id', Auth::guard('clientsWeb')->user()->id)->get();
        return view('Front.pages.myFavorites', compact('favorites'));
    }

    public function removeAddress($id)
    {
        $user = Auth::guard('clientsWeb')->user();
        if ($user) {
            $address = Address::findOrFail($id);
            $address->delete();
            //     toastr()->success('تم الحذف بنجاح');
            //     return back();
            // } else {
            //     toastr()->error('يوجد خطأ برجاء المحاولة مرة اخري');
            //     return back();
            // }
            toastr()->success(trans('front.address_deleted'));
            return back();
        } else {
            toastr()->error(trans('front.address_error'));
            return back();
        }
    }

    public function editAddress(Request $request, $id)
    {
        $user = Auth::guard('clientsWeb')->user();
        if ($user) {
            $address = Address::findOrFail($id);
            $address->update([
                'location' => $request->address,
            ]);
            //     toastr()->success('تم التعديل بنجاح');
            //     return back();
            // } else {
            //     toastr()->error('يوجد خطأ برجاء المحاولة مرة اخري');
            //     return back();
            // }
            toastr()->success(trans('front.address_updated'));
            return back();
        } else {
            toastr()->error(trans('front.address_error'));
            return back();
        }
    }

    public function profileReward()
    {
        // $points = BounsPoint::where('client_id', Auth::guard('clientsWeb')->user()->id)->get();
        // $total_Points = $points->sum('order_point');
        // $cost = $points->sum('order_point') != 0 ? $points->sum('order_point') / 1000 : "0";
        // $cost = $cost == 0 ? 0 : round($cost);
         $setting = Setting::first(); 
$points = Cashback::where('client_id', Auth::guard('clientsWeb')->user()->id)->first();

        return view('Front.pages.profile.reward', compact(['setting', 'points']));
        
    }
}