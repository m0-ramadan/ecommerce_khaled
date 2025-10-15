<?php

namespace App\Http\Controllers\Api;
use App\Events\ClientViewCategory;
use Session;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\City;
use App\Models\Faqs;
use App\Models\Page;
use App\Models\User;
use App\Models\Bouns;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Store;
use App\Models\Story;
use App\Models\Banner;
use App\Models\Bonuss;
use App\Models\Client;
use App\Models\MyList;
use App\Models\Review;
use App\Models\Roster;
use App\Models\Address;
use App\Models\Contact;
use App\Models\Country;
use App\Models\Product;
use App\Models\Support;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\District;
use App\Models\Favorite;
use App\Models\GiftCard;
use App\Models\ListCards;
use App\Models\OrderItem;
use App\Models\PromoCode;
use App\Models\ShareList;
use App\Models\subscribe;
use App\Models\BounsPoint;
use App\Models\GiftWallet;
use App\Models\ReviewImgs;
use App\Models\RosterItem;
use App\Models\Inspiration;
use App\Models\ProductItem;
use App\Models\SubCategory;
use App\Models\Construction;
use App\Models\MyCouponList;
use App\Models\Notification;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\FaqListAnswer;
use App\Models\Listcardprice;
use App\Models\ListFavorites;
use App\Models\Listcardimages;
use App\Models\ProductFeature;
use App\Models\ProductFeatures;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use App\Http\Resources\FaqsResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PagesResource;
use App\Http\Resources\StoreResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\BannerResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\OffersResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CountriesResource;
use App\Http\Resources\ListCardsResource;
use App\Http\Resources\ReviewimgResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\GiftWalletResource;
use App\Http\Resources\InspirationResource;
use App\Http\Resources\ProductitemResource;
use App\Http\Resources\ProductsizeResource;
use App\Http\Resources\SettingResource;
use Illuminate\Validation\Rule;

// use App\Services\SmsaService;
use App\Http\Resources\SubCategoryResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ConstructionResource;
use App\Http\Resources\ListFavoritesResource;
use App\Http\Resources\ProductObjectResource;
use App\Http\Resources\ProductDetailsResource;
use App\Http\Resources\ProductFeaturesResource;
use App\Traits\ApiTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Validation\ValidationException;

class MainController extends Controller
{
    use GeneralTrait, ApiTrait, UploadFileTrait;

    // public SmsaService $smsaService;

    // public function __construct($smsaService)
    // {
    //     $this->smsaService = $smsaService;
    // }



 public function deleteaccount(Request $request)
    {
        $user = Client::where('phone',$request->phone);
     $user->where("phone", $request->phone)->update(['is_active' => 0]);
      $user->delete(); 
         return (' The account has been successfully deleted ');
    }
    
    public function countries()
    {
        $countries = CountriesResource::collection(Country::get());
        return $this->returnData('data', $countries, 'success');
        //        return response()->json();
    }

    public function cities(Request $request)
    {
        $cities = City::where(function ($query) use ($request) {
            if ($request->has('countries_id')) {
                $query->where('countries_id', $request->countries_id);
            }
        })->get();
        return $this->returnData('data', $cities, 'success');
        //         return response()->json($cities);
    }


    public function faqlist()
    {
        $countries = FaqsResource::collection(Faqs::get());
        if (count($countries) > 0) {
            foreach ($countries as $countries) {
                $cat['name'] = $countries->questions;
                $cat['id'] = $countries->id;
                $cat['type'] = false;
                $cat['list_answers'] = [];
                $offerDetails = Faqs::where('id', $countries->id)->get();
                $cities = FaqsResource::collection($offerDetails);
                if (count($cities) > 0) {
                    foreach ($cities as $cities) {
                        $cities_res['answer'] = $cities->answer;
                        $cities_res['id_answer'] = $cities->id;
                        $cat['list_answers'][] = $cities_res;
                    }
                } else {
                    $cat['list_answers'] = [];
                }
                $data["questions_list"][] = $cat;
            }
        } else {
            $data["questions_list"] = [];
        }

        return $this->returnData('data', $data, 'success');
        //        return response()->json();
    }

    public function faqlist_answer(Request $request)
    {

        $cat['list_answers'] = [];
        $offerDetails = Faqs::where('id', $request->id)->get();
        $cities = FaqsResource::collection($offerDetails);
        if (count($cities) > 0) {
            foreach ($cities as $cities) {
                $cities_res['answer'] = $cities->answer;
                $cities_res['id_answer'] = $cities->id;
                $cat['list_answers'][] = $cities_res;
            }
        } else {
            $cat['list_answers'] = [];
        }


        return $this->returnData('data', $cat, 'success');
        //        return response()->json();
    }


    public function list_locations()
    {
        $countries = CountriesResource::collection(Country::get());
        if (count($countries) > 0) {
            foreach ($countries as $countries) {
                $cat['name'] = $countries->name;
                $cat['id'] = $countries->id;
                $cat['country_ref_code'] = $countries->country_ref_code;
                $cat['list_cites'] = [];
                $offerDetails = City::where('countries_id', $countries->id)->get();
                $cities = CountriesResource::collection($offerDetails);
                if (count($cities) > 0) {
                    foreach ($cities as $cities) {
                        $cities_res['name_city'] = $cities->name;
                        $cities_res['id_city'] = $cities->id;
                        $cat['list_cites'][] = $cities_res;
                    }
                } else {
                    $cat['list_cites'] = [];
                }
                $data["country_list"][] = $cat;
            }
        } else {
            $data["country_list"] = [];
        }

        return $this->returnData('data', $data, 'success');
        //        return response()->json();
    }


    public function offers()
    {
        $offers = OffersResource::collection(Offer::get());
        return $this->returnData('data', $offers, 'success');
    }

    public function offerDetails(Request $request)
    {
        $offerDetails = Offer::where(function ($query) use ($request) {
            if ($request->has('id')) {
                $query->where('id', $request->id);
            }
        })->get();
        $offers = OffersResource::collection($offerDetails);
        return $this->returnData('data', $offers, 'success');
    }

    public function blogs()
    {
        $data['blogs'] = BlogResource::collection(Blog::get());
        $data['lastblog'] = BlogResource::collection(Blog::latest()->take(5)->get());
        return $this->returnData('data', $data, 'success');
    }
    public function listcards()
    {
        $data['listcards'] = ListCardsResource::collection(ListCards::get());

        return $this->returnData('data', $data, 'success');
    }

    public function carddetails(Request $request)
    {
        // dd(Listcardimages::where("list_id", $request->list_id)->get(),Listcardprice::where("list_id", $request->list_id)->get());
        $data['Listcardimages'] = ListCardsResource::collection(Listcardimages::where("list_id", $request->list_id)->get());
        $data['Listcardprice'] = Listcardprice::where("list_id", $request->list_id)->get();
        return $this->returnData('data', $data, 'success');
    }


    public function sendcard(Request $request)
    {

        $client = auth('api')->user();
        if ($client) {
            $support = GiftWallet::create([
                'sender_name'                => $request->sender_name,
                'phone'                      => $request->phone,
                'message'                    => $request->message,
                'price'                      => $request->price,
                'client_id'                  => $client->id,
                'cart_id'                    => $request->cart_id,
                'price_id'                   => $request->price_id,
                'remaining'                  => 1
            ]);
            return $this->returnData('data', '', 'success');
        } else {
            return $this->returnData('data', '', 'error');
        }
    }


    public function giftwallet()
    {
        $client = auth('api')->user();
        if ($client) {
            $data['listgiftwallets'] = GiftWalletResource::collection(
                GiftWallet::hasRemainPoints()
                    ->active()
                    ->where("user_id", $client->id)
                    ->get()
            );

            return $this->returnData('data', $data, 'success');
        } else {
            return $this->returnData('data', '', 'error');
        }
    }


    public function giftwallet_old()
    {
        $client = auth('api')->user();
        if ($client) {
            $data['listgiftwallets'] = GiftWalletResource::collection(GiftWallet::where("remaining", 1)->where("user_id", $client->id)->get());

            return $this->returnData('data', $data, 'success');
        } else {
            return $this->returnData('data', '', 'error');
        }
    }

    public function deletegiftwallet(Request $request)
    {
        $address = GiftWallet::findOrFail($request->id)->delete();
        return $this->returnSuccessMessage('تم الحذف بنجاح', '200');
    }



    public function stores()
    {
        $store = Store::where('is_Active', true)->where("id", '!=', 34)->get();
        $stores = StoreResource::collection($store);
        return $this->returnData('data', $stores, 'success');
    }

    public function allStore($id)
    {
        $category = Category::where([
            ['store_id', $id],
            ['is_active', true]
        ])->get();
        $data['category'] = CategoriesResource::collection($category);

        $sub = SubCategory::where([
            ['store_id', $id],
            ['is_active', true]
        ])->get();
        $data['subCategory'] = SubCategoryResource::collection($sub);

        $pro = Product::where([
            ['store_id', $id],
            ['is_active', true]
        ])->get();
        $data['product'] = ProductResource::collection($pro);

        return $this->returnData('data', $data, 'success');
    }

    public function categories(Request $request)
    {
        $categories = Category::where(function ($query) use ($request) {
            if ($request->has('store_id')) {
                $query->where('store_id', $request->store_id)->where('type',0);
            }
        })->get();
        
               $list_categories = Category::where(function ($query) use ($request) {
            if ($request->has('store_id')) {
                $query->where('store_id', $request->store_id)->where('type',1);;
            }
        })->get();
        
        $result = Product::selectRaw('MAX(current_price) as max_price, MIN(current_price) as min_price')->first();
        $maxPrice = $result->max_price;
        $minPrice = $result->min_price;

        $cate = CategoriesResource::collection($categories);
          $list_cat = CategoriesResource::collection($list_categories);
        return $this->returnData('data', $cate, [
            'max_price' => $maxPrice,
            'min_price' => $minPrice,
            'list_cat'=>$list_cat,
        ]
        
        );
        
        
        // return $this->returnData('data', $cate, 'success');
    }


    public function listcategories(Request $request)
    {
        $categories = Category::where(function ($query) use ($request) {
            if ($request->has('store_id')) {
                $query->where('store_id', $request->store_id);
            }
        })->get();
        
         $cate = CategoriesResource::collection($categories);
        return $this->returnData('data', $cate,null);
        
        
        // return $this->returnData('data', $cate, '');
    }



    public function subCategory(Request $request)
    {
        $subCategory = SubCategory::where(function ($query) use ($request) {
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }
        })->get();
        $sub = SubCategoryResource::collection($subCategory);

        return $this->returnData('date', $sub, 'success');
    }


    public function products(Request $request)
    {
        $products = Product::where(function ($query) use ($request) {
            if ($request->has('sub_category_id')) {
                $query->where('is_active', 1)
                    ->where('sub_category_id', $request->sub_category_id);
            } else {
                $query->where('is_active', 1)
                    ->where('category_id', $request->category_id);
                event(new ClientViewCategory($request->category_id));
            }
        })->with(['Favorites' => function ($query) {
            // Assuming 'client_id' is the foreign key in the favorites table
            $query->where('client_id', auth('api')->id());
        }])->inRandomOrder()->get();
        $pro = ProductResource::collection($products);
        return $this->returnData('data', $pro, 'success');
    }
    
    
       public function get_all_catoffers(Request $request){
        
        
      $products = Product::where(function ($query) use ($request) {
            if ($request->has('sub_category_id')) {
                $query->where('is_active', 1)
                    ->where('sub_category_id', $request->sub_category_id)
                    ->where('old_price', '>', 0)->inRandomOrder();
            } else {
                $query->where('is_active', 1)
                    ->where('category_id', $request->category_id)
                    ->where('old_price', '>', 0)->inRandomOrder();
                event(new ClientViewCategory($request->category_id));
            }
        })->with(['Favorites' => function ($query) {
            // Assuming 'client_id' is the foreign key in the favorites table
            $query->where('client_id', auth('api')->id());
        }])->inRandomOrder()->get();
        $pro = ProductResource::collection($products);
        return $this->returnData('data', $pro, 'success');
        
    }



    public function get_all_offers()
    {
        $products = Product::where('is_active', 1)->where('old_price', '>', 0)->inRandomOrder()->get();
        $pro = ProductResource::collection($products);
        return $this->returnData('data', $pro, 'success');
    }


    public function get_all_products()
    {
        $products = Product::where('is_active', 1)->get();
        $pro = ProductResource::collection($products);
        return $this->returnData('data', $pro, 'success');
    }

    public function productDetails(Request $request)
    {
        $user = auth('api')->user();
        if ($user) {
            $myList = MyList::where(['client_id' => auth('api')->user()->id, 'product_id' => $request->product_id])->first();
            if (auth('api')->check() && !$myList) {
                $mylists = new MyList;
                $mylists->client_id                = auth('api')->user()->id;
                $mylists->product_id               = $request->product_id;
                $mylists->save();
            }
        }

        $products = Product::where('id', $request->product_id)->get();
        $productsize = ProductFeature::select('id', 'name')->where('product_id', $request->product_id)->where('type', 1)->get();
        $productfeatures = ProductFeatures::where('product_id', $request->product_id)->where('type', 0)->get();

        $pro = Product::where('id', $request->product_id)->with(['Favorites' => function ($query) {
            // Assuming 'client_id' is the foreign key in the favorites table
            $query->where('client_id', auth('api')->id());
        }])->first();
        $cate = $pro->category_id;
        $catesId = Category::where('id', $cate)->first();
        $productUrl = Contact::first()->product_url;
        $related = Product::where('category_id', $catesId->id)->latest()->take(2)->get();
        $data = [
            'Number-of-times-to-buy'                   => OrderItem::where('product_id', $request->product_id)->count(),
            'Product-Details'                          => ProductDetailsResource::collection($products),
            'productsize'                              => ProductsizeResource::collection($productsize),
            'productfeatures'                         => ProductFeaturesResource::collection($productfeatures),
            'Related Products'                         => ProductResource::collection($related),
            'productUrl'                               => $productUrl . '/' . $request->product_id,
        ];
        return $this->returnData('Data', $data, 'success');
    }



    public function productprice(Request $request)
    {

        //$fav = Favorite::where(['client_id' => auth('api')->user()->id,'product_id' => ])->first();
        $arr = explode(",", $request->product_id);
        for ($i = 0; $i < count($arr); $i++) {
            $products = Product::select('quantity', 'current_price', 'old_price', 'smart_price', 'id')->where('id', $arr[$i])->first();
            $data['list_products'][] = $products;
        }
        return $this->returnData('Data', $data, 'success');
    }




    public function contacts()
    {
        $contact = new ContactResource(Contact::first());
        return $this->returnData('data', $contact, 'success');
    }

    public function cart()
    {
        $cart = Cart::where('client_id', auth('api')->user()->id)->first();
        $ids = [];
        if ($cart) {
            $ids[] = $cart->items()->product_id;
        }
        $products = Product::whereIn('id', $ids)->get();
        $data['products'] = $products;
        return $this->returnData('data', $data, 'تمت العملية بنجاح');
    }


    public function story()
    {
        $stories = StoryResource::collection(Story::get());
        return $this->returnData('data', $stories, 'success');
    }


    public function reviews(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required',
            'rate' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }



        $order = Review::create([
            'client_id'            =>  auth('api')->user()->id,
            'comment'              =>  $request->comment,
            'rate'                =>  $request->rate,
            'product_id'           =>  $request->product_id,
        ]);

        $id_order =  $order->id;
        OrderItem::where('order_id', $request->order_id)->where("product_id", $request->product_id)->update(array("rate" => 1));
        $ratefind = OrderItem::where('order_id', $request->order_id)->where("rate", 0)->first();
        if (!$ratefind) {
            Order::where('id', $request->order_id)->update(array("rate_status" => 1));
        }
        if ($request->img != "") {
            for ($i = 0; $i < count($request->img); $i++) {

                if ($request->hasFile('img')) {
                    $fileNameToStore = $this->uploadFile('reviewimgs', $request->img[$i]);
                } else {
                    $fileNameToStore = 'noimage.jpg';
                }
                $order = ReviewImgs::create([
                    'list_id'            =>  $id_order,
                    'img'              =>  $fileNameToStore,
                ]);
            }
        }
        return $this->returnData('data', $order, 'success');
    }




    public function allReviews(Request $request)
    {

        $reviews_app = Review::where("product_id", $request->prod_id)->get();
        if (count($reviews_app) > 0) {
            foreach ($reviews_app as $list_review) {
                $productlist['comment'] = $list_review->comment;
                $productlist['rate'] = $list_review->rate;
                $productlist['date'] = $list_review->created_at;
                $productlist['product_id'] = $list_review->product_id;
                $client_name = Client::select("name")->where('id', $list_review->client_id)->first();
                $productlist['client_name'] = $client_name->name;
                $productlist['list_img'] = [];
                $imglist = ReviewImgs::where('list_id', $list_review->id)->get();
                if (count($imglist) > 0) {
                    foreach ($imglist as $imglist) {

                        $productlist['list_img'][] = asset('/') . 'public/' . $imglist->img;;
                    }
                } else {
                }
                $data['list'][] = $productlist;
            }
        } else {
            $data['list'] = [];
        }
        return $this->returnData('data', $data, 'success');
    }

    public function address(Request $request)
    {
        $address = Address::where('client_id', $request->user_id)->get();
        return $this->returnData('data', $address, 'success');
    }



    public function checkoffer(Request $request)
    {


//         $contact =  Setting::select("key_offer","promo_code_name","offer_image")->get();
// $contact = SettingResource::collection($contact);
// return $this->returnData('data', $contact, 'success');
        $contact = new SettingResource(Setting::select("offer_image", "key_offer", "promo_code_name")->first());
        return $this->returnData('data', $contact, 'success');
    }



    public function deleteAddress(Request $request)
    {
        $address = Address::findOrFail($request->id)->delete();
        return $this->returnSuccessMessage('تم الحذف بنجاح', '1');
    }

    public function editAddress(Request $request)
    {
        $address = Address::findOrFail($request->id)->update([
            'location'                 =>      $request->location,
        ]);
        return $this->returnSuccessMessage('تم التعديل بنجاح', '1');
    }

    public function addresses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $address = auth('api')->user()->addresses()->create([
            'location' => $request->location,
        ]);
        return $this->returnData('data', $address, 'success');
    }

    public function PromoCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => [
                'required',
                Rule::exists('promo_codes', 'code')->where(function ($query) {
                    $today = now()->toDateString();

                    $query->where('start_date', '<=', $today)
                        ->where('end', '>=', $today);
                }),
            ],
        ]);
        
        if ($validator->fails()) {
            return $this->returnError('001', 'error end usage');
        }
        
        $code = PromoCode::where('code', $request->code)->first();

        if (Order::where('client_id', auth('api')->id())
        ->where('promo_code_id', $code->id)
        ->where('payment_status', Order::PAYMENT_STATUSES['PAID'])
        ->count() >= $code->counts) {
            return $this->returnError('001', 'Cant use this pomo code anymore.');
        }

        if ($code->end <= date('Y-m-d') || $code->status == 0) {
            return $this->returnError('001', 'error end usage');
        } 
        
        $data['code'] = $code;
        return $this->returnData('data', $data, 'success');
    }
    
    public function checkCashBack(Request $request)
    {
         $validated = $request->validate([
            'amount' => 'required|integer|min:1',
        ]);
        
        $client = auth('api')->user();
        
        if(!$client || $client->total_point < $request->amount)
        {
            return $this->apiResponse(message: "ليس لديك نقاط كافية.", code : false);
        }
        
        return $this->apiResponse(message: "لديك نقاط كافية.", code: true);

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

                // $this->smsaService->createShipment();
                $cart->delete();

                DB::commit();


                return $this->returnData('data', $order, 'تمت العملية بنجاح');
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->returnError(404, $e->getMessage());
            }
        }
    }


    public function AddToCart(Request $request)
    {
        $cart = Cart::where('client_id', auth('api')->user()->id)->first();
        if (!$cart) {
            $cart = Cart::create(['client_id' => auth('api')->user()->id]);
            $cartitem = CartItem::where('product_id', $request->product_id)->first();
            $cartitem = CartItem::create([
                'cart_id'           => $cart->id,
                'product_id'        => $request->product_id,
                'quantity'          => $request->quantity,
                'price'             =>    $request->price,
                'smart_price'      =>    $request->smart_price,
                'total'             => ($request->quantity * $request->price),
                'features'          => $request->features,
                'smart_type'        =>  $request->smart_type
            ]);
        } else {
            $cartitem = CartItem::where(['product_id' => $request->product_id, 'smart_type' => $request->smart_type, 'cart_id' => $cart->id])->first();

            if ($cartitem) {
                $cartitem->update([
                    'cart_id'           => $cart->id,
                    'product_id'        => $request->product_id,
                    'quantity'          => $request->quantity,
                    'price'             =>    $request->price,
                    'smart_price'        =>    $request->smart_price,
                    'total'               =>   $request->quantity * $request->price,
                    'features'              =>    $request->features,
                    'smart_type'            =>    $request->smart_type
                ]);
            } else {


                CartItem::create([
                    'price'             =>    $request->price,
                    'features'          => $request->features,
                    'product_id'          => $request->product_id,
                    'quantity'            => $request->quantity,
                    'smart_price'      =>    $request->smart_price,
                    'total'             =>   $request->quantity * $request->price,
                    'cart_id'             => $cart->id,
                    'smart_type'            =>    $request->smart_type
                ]);
            }
        }

        return $this->returnSuccessMessage($request->features);
    }
    public function delete_account()
    {
        try {
            $user_id = auth('api')->user()->id;
            Client::where("id", $user_id)->update(['is_active' => 0]);
            $data['message'] = 'User successfully signed out';
            return $this->returnData('data', $user_id, 'success');
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function AddListToCart(Request $request)
    {
        $params =  json_decode(file_get_contents('php://input'), TRUE);
        $cart = Cart::where('client_id', auth('api')->user()->id)->first();
        if (!$cart) {
            $cart = Cart::create(['client_id' => auth('api')->user()->id]);
            $userid = auth('api')->user()->id;

            for ($i = 0; $i < count($params['product_list']); $i++) {
                $cartitem = CartItem::create([
                    'cart_id'           => $cart->id,
                    'product_id'        => $params['product_list'][$i]['prod_id'],
                    'quantity'          => $params['product_list'][$i]['prod_qty'],
                    'price'             => $params['product_list'][$i]['prod_price'],
                    'features'          => $params['product_list'][$i]['prod_fearture'],
                    'smart_price'          => $params['product_list'][$i]['smart_price'],
                    'total'            => $params['product_list'][$i]['total_price'],
                ]);
            }
        } else {

            for ($i = 0; $i < count($params['product_list']); $i++) {


                $cartitem = CartItem::where(['product_id' => $request->product_id, 'cart_id' => $cart->id])->first();
                if ($cartitem && $request->quantity + $cartitem->quantity < 1) {
                    $cartitem->delete();
                }
                if ($cartitem) {
                    $cartitem->update([
                        'cart_id'           => $cart->id,
                        'product_id'        => $params['product_list'][$i]['prod_id'],
                        'quantity'             => $params['product_list'][$i]['prod_qty'],
                        'price'                => $params['product_list'][$i]['prod_price'],
                        'features'             => $params['product_list'][$i]['prod_fearture'],
                        'smart_price'          => $params['product_list'][$i]['smart_price'],
                        'total'              => $params['product_list'][$i]['total_price'],
                    ]);
                } else {
                    CartItem::create([
                        'product_id'        => $params['product_list'][$i]['prod_id'],
                        'quantity'          => $params['product_list'][$i]['prod_qty'],
                        'price'             => $params['product_list'][$i]['prod_price'],
                        'features'          => $params['product_list'][$i]['prod_fearture'],
                        'smart_price'       => $params['product_list'][$i]['smart_price'],
                        'total'             => $params['product_list'][$i]['total_price'],
                        'cart_id'             => $cart->id
                    ]);
                }
            }
        }
        return $this->returnSuccessMessage('تم التنفيذ بنجاح');
    }
    public function myCart()
    {
        //   try{
        $cart = Cart::where('client_id', auth('api')->user()->id)->first();
        if ($cart) {
            $cartitems = $cart->items;
            $cartitems = $cart->items;
            $items = [];
            $sub_total = 0;
            foreach ($cartitems as $item) {
                $product = ProductItem::select('id', 'name', 'image')->where('id', $item->product_id)->first();
                $product->quantity = $item->quantity;
                $product->card_id = $item->id;
                $product->smart_price = $item->smart_price;
                $product->price = (float)$item->price;
                $sub_total = $sub_total + $product->quantity * $product->price;
                if ($item->features == null) {
                    $product->features = "";
                } else {
                    $product->features = $item->features;
                }
                $items[] = $product;
            }

            $giftwallet = GiftWallet::where('user_id', auth('api')->user()->id)->where('remaining', '!=', 1)->get();
            // $pro = ProductitemResource::collection($items);
            $pro = ProductitemResource::collection($items);
            $data['cashback'] = (int)auth('api')->user()->total_point;
            $data['total_gift'] = $giftwallet->sum('price');;
            //$data['total_coupons']=3;
            $data['list_item'] = $pro;
            return $this->returnData('data', $data, 'success');
        } else {
            $data['cashback'] = 0;
            $data['list_item'] = [];
            return $this->returnData('data', $data, 'success');
        }
        //  }catch(\Exception $e){
        //      return $this->returnError('500', $e->getMessage());
        //  }
    }

    public function FavProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'         => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $toggle = Favorite::where('product_id', $request->product_id)->first();
        if (!$toggle) {
            $fav = Favorite::create([
                'client_id'   => auth('api')->user()->id,
                'product_id'  =>  $request->product_id,
                'list_id'     =>  $request->list_id
            ]);
            return $this->returnSuccessMessage('تمت الاضافة للمفضلة');
        } else {
            $toggle->delete();
            return $this->returnSuccessMessage('تم الحذف من قائمة المفضلة');
        }
    }


    public function createRoster(Request $request)
    {
        try {
            $user = auth('api')->user()->rosters()->create([
                'name' => $request->name,
                'client_id' => auth('api')->user()->id,
            ]);
            return $this->returnSuccessMessage('تم انشاء القائمة بنجاح');
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function addToRoster(Request $request)
    {
        try {

            $user = Roster::where(['client_id' => auth('api')->user()->id, 'id' => $request->roster_id])->first();
            if ($user) {
                $rosterItem = RosterItem::where(['product_id' => $request->product_id, 'roster_id' => $request->roster_id])->first();
                if (!isset($rosterItem)) {
                    $items = RosterItem::create([
                        'product_id'            => $request->product_id,
                        'roster_id'             => $request->roster_id
                    ]);
                    return $this->returnData('data', $items, 'success');
                } else {
                    return $this->returnError('404', 'هذا المنتج قد تمت اضافته من قبل');
                }
            } else {
                return $this->returnError('500', 'هذه القائمة غير صحيحة');
            }
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }


    public function addfavlist(Request $request)
    {

        if (auth('api')->user()->id) {

            if ($request->prod_id == "") {
                $items = ListFavorites::create([
                    'client_id'            => auth('api')->user()->id,
                    'name'             => $request->name
                ]);
                return $this->returnData('data', $items, 'success');
            }
            if ($request->prod_id != "") {
                $items = ListFavorites::create([
                    'client_id'            => auth('api')->user()->id,
                    'name'             => $request->name
                ]);

                $toggle = Favorite::where('product_id', $request->prod_id)->where('list_id', $items->id)->first();
                if (!$toggle) {
                    $fav = Favorite::create([
                        'client_id'   => auth('api')->user()->id,
                        'product_id'  =>  $request->prod_id,
                        'list_id'     =>  $items->id
                    ]);
                    return $this->returnSuccessMessage('تمت الاضافة للمفضلة');
                } else {
                    $toggle->delete();
                    return $this->returnSuccessMessage('تم الحذف من قائمة المفضلة');
                }
            }
        } else {
            return $this->returnError('500', 'هذه القائمة غير صحيحة');
        }
    }


    public function deletefavproduct(Request $request)
    {

        if (auth('api')->user()->id) {


            if ($request->id != "") {
                $toggle = Favorite::where('product_id', $request->id)->where('client_id', auth('api')->user()->id)->first();
                $toggle->delete();
                return $this->returnSuccessMessage('تم الحذف من قائمة المفضلة');
            }
        } else {
            return $this->returnError('500', 'هذه القائمة غير صحيحة');
        }
    }

    public function myRoster(Request $request)
    {
        try {
            $data = [];
            $ids = [];
            $roster = Roster::where(['client_id' => auth('api')->user()->id])->get();
            //             for ($i = 0; $i < count($roster->items); $i++) {
            //                 $ids[] = $roster->items[$i]->product_id;
            //             }
            //             $products = Product::whereIn('id', $ids)->get();
            //             $pro = ProductResource::collection($products);
            //             $data = [
            //                 'Roster'               => $roster,
            //                 'products'             => $pro,
            //             ];
            return $this->returnData('data', $roster, 'success');
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function rosterItem(Request $request)
    {
        try {
            $data = [];
            $ids = [];
            $roster = Roster::where(['id' => $request->roster_id, 'client_id' => auth('api')->user()->id])->first();
            for ($i = 0; $i < count($roster->items); $i++) {
                $ids[] = $roster->items[$i]->product_id;
            }
            $products = Product::whereIn('id', $ids)->get();
            $pro = ProductResource::collection($products);
            $data = [
                'Roster'               => $roster,
                'products'             => $pro,
            ];
            return $this->returnData('data', $data, 'success');
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function mainList()
    {

        $data = [
            'MainList'                 => Roster::where('client_id', auth('api')->user()->id)->get(),
            'ShareList'                => ShareList::where('client_id', auth('api')->user()->id)->get(),
        ];
        return $this->returnData('data', $data, 'success');
    }



    public function favlist()
    {

        $data = ListFavorites::where('client_id', auth('api')->user()->id)->get();

        $pro = ListFavoritesResource::collection($data);
        return $this->returnData('data', $pro, 'success');
    }

    public function sharedList(Request $request)
    {
        try {
            $user = Client::where('phone', $request->phone)->first();
            if ($user) {
                $sharedList = new ShareList;
                $sharedList->client_id         = $user->id;
                $sharedList->roster_id         = $request->roster_id;
                $sharedList->type              = $request->type;
                $sharedList->save();
                return $this->returnData('data', $sharedList, 'success');
            } else {
                return $this->returnError(404, 'لا يوجد بيانات خاصة بهذا الرقم');
            }
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function deleteRosterItem(Request $request)
    {
        try {
            $roster = Roster::where('client_id', auth('api')->user()->id)->first();
            if ($roster) {
                $item = RosterItem::where('product_id', $request->id)->first();
                $item->delete();
                return $this->returnSuccessMessage('تم الحذف بنجاح');
            } else {
                return $this->returnError('500', 'يوجد بعض الاخطاء');
            }
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }



    public function deletefavlist(Request $request)
    {
        try {
            $user = ListFavorites::where(['id' => $request->id, 'client_id' => auth('api')->user()->id])->first();
            if ($user) {
                $roster = ListFavorites::findOrFail($request->id)->delete();
                $roster = Favorite::where(['list_id' => $request->id, 'client_id' => auth('api')->user()->id])->delete();
                return $this->returnSuccessMessage('تم حذف القائمة');
            }
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }
    public function deleteRoster(Request $request)
    {
        try {
            $user = Roster::where('client_id', auth('api')->user()->id)->first();
            if ($user) {
                $roster = Roster::findOrFail($request->id)->delete();
                return $this->returnSuccessMessage('تم حذف القائمة');
            }
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function AllFavProduct()
    {
        $allFav = Favorite::get();
        return $this->returnData('data', $allFav, 'success');
    }


    public function profile(Request $request)
    {

        $client = auth('api')->user();
        if ($client->image != null) {
            $client->image = asset('public/' . $client->image);
        } else {
            $client->image = "";
        }
        return $this->returnData('data', $client, 'success');
    }

    public function supports(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                    => 'required',
            'phone'                   => 'required',
            'message'                 => 'required',
            'email'                   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $client = auth('api')->user();
        $support = Support::create([
            'name'                => $request->name,
            'phone'               => $request->phone,
            'message'             => $request->message,
            'type'                => 0,
            'email'               => $request->email,
        ]);
        return $this->returnData('data', $support, 'success');
    }

    public function contactUs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                    => 'required|string|min:3',
            'phone'                   => 'required|string|min:3',
            'message'                 => 'required|string|min:3',
            'email'                   => 'required|email',
        ]);
        $validator->validate();
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        $client = auth('api')->user();
        $support = Support::create([
            'name'                => $request->name,
            'phone'               => $request->phone,
            'message'             => $request->message,
            'type'                => 1,
            'email'               => $request->email,
        ]);
        return $this->returnData('data', $support, 'success');
    }


    public function  ideas_taqiviolet(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                    => 'required',
            'phone'                   => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        if ($request->hasFile('file')) {
            $fileNameToStore = $this->uploadFile('business_ideas', $request->file);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        $support = Support::create([
            'name'                => $request->name,
            'phone'               => $request->phone,
            'message'             => $request->message,
            'type'                => 2,
            'email'               => $request->email,
            'file'               => $fileNameToStore,

        ]);
        return $this->returnData('data', $support, 'success');
    }

    public function allSupports()
    {
        $support = Support::get();
        return $this->returnData('data', $support, 'success');
    }

    public function detailsSupport(Request $request)
    {
        $messages = Support::where('id', $request->message)->get();
        return $this->returnData('data', $messages, 'success');
    }

    public function myOrders(Request $request)
    {
        $client = auth('api')->user();
        $myOrder = Order::where('client_id', $client->id)
                ->when($request->has('code_order'), function($query) use($request)
                {
                    return $query->where('code_order',"LIKE",  "%" . $request->code_order . "%");
                })
                ->get();

        return $this->returnData('data', $myOrder, 'success');
    }
    
        public function clientOrders()
        {
            $clientOrders = Order::with(['promoCode'])
                ->withCount('orderitem')
                ->where('client_id', auth('api')->user()->id)
                ->get();

            $orderDetails = $clientOrders->map(function ($order) {
                return self::prepareOrderHelper($order);
            });
        
            $holdingOrders = $orderDetails->filter(function ($order) {
                return in_array($order['status'], [Order::ORDER_STATUSES['HOLDING']]);
            })->values()->all();
            
            $waitingOrders = $orderDetails->filter(function ($order) {
                return in_array($order['status'], [Order::ORDER_STATUSES['PENDING']]);
            })->values()->all();
            
            $doneOrders = $orderDetails->filter(function ($order) {
                return in_array($order['status'], [Order::ORDER_STATUSES['DONE'], Order::ORDER_STATUSES['REJECTED']]);
            })->values()->all();
            
            $currentOrders = $orderDetails->filter(function ($order) {
                return in_array($order['status'], [Order::ORDER_STATUSES['ACCEPTED'], Order::ORDER_STATUSES['PREPAREING'], Order::ORDER_STATUSES['DELIVERING']]);
            })->values()->all();

        
            $response = [
                'all' => $orderDetails->all(),
                'waiting' => $waitingOrders,
                'done' => $doneOrders,
                'current' => $currentOrders,
                'hold' => $holdingOrders,

            ];
        
            return $this->returnData('data', $response, 'success');
        }

    private static function prepareOrderHelper($order)
    {
        return [
            'id' => $order->id,
            'code_order' => $order->code_order,
            'product_rate' => $order->rate,
            'client_id' => $order->client_id,
            'store_id' => $order->store_id,
            'address' => $order->address,
            'promo_code_id' => $order->promo_code_id ?? 0,
            'promo_code_value' => optional($order->promoCode)->value ?? 0,
            'delivery_type' => $order->delivery_type,
            'delivery_cost' => $order->delivery_cost,
            'sub_total' => $order->sub_total,
            'total' => $order->total,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_type' => $order->payment_type,
            'username' => $order->user_name,
            'userphone' => $order->user_phone,
            'order_date' => $order->created_at,
            'items_count' => $order->orderitem_count,
        ];
    }

    public function waiting_myOrders()
    {
        $client = auth('api')->user();
        $myOrder = Order::where("status", Order::ORDER_STATUSES['PENDING'])->where('client_id', $client->id)->get();
        if ($myOrder->count() > 0) {
            foreach ($myOrder as $list) {
                $maindetails['id'] = $list->id;
                $maindetails['code_order'] = $list->code_order;
                $maindetails['product_rate'] = $list->rate;
                $maindetails['client_id'] = $list->client_id;
                $maindetails['store_id'] = $list->store_id;
                $maindetails['address'] = $list->address;
                if ($list->promo_code_id == null) {
                    $maindetails['promo_code_id'] = 0;
                    $maindetails['promo_code_value'] = 0;
                } else {
                    $code = PromoCode::where('id', $list->promo_code_id)->first();

                    $maindetails['promo_code_id'] = $list->promo_code_id;
                    $maindetails['promo_code_value'] = $code->value;
                }
                $maindetails['delivery_type'] = $list->delivery_type;
                $maindetails['delivery_cost'] = $list->delivery_cost;
                $maindetails['sub_total'] = $list->sub_total;
                $maindetails['total'] = $list->total;
                $maindetails['status'] = $list->status;
                $maindetails['payment_status'] = $list->payment_status;
                $maindetails['payment_type'] = $list->payment_type;
                $maindetails['username'] = $list->user_name;
                $maindetails['userphone'] = $list->user_phone;
                $maindetails['order_date'] = $list->created_at;
                $maindetails['items_count'] = $list->orderitem_count;
                $products[] = $maindetails;
            }
            return $this->returnData('data', $products, 'success');
        } else {
            $products = [];
            return $this->returnData('data', $products, 'success');
        }
    }

    public function pendingOrders()
    {
        $client = auth('api')->user();
        $myOrder = Order::where("status", Order::ORDER_STATUSES['HOLDING'])->where('client_id', $client->id)->get();
        if ($myOrder->count() > 0) {
            foreach ($myOrder as $list) {
                $maindetails['id'] = $list->id;
                $maindetails['code_order'] = $list->code_order;
                $maindetails['product_rate'] = $list->rate;
                $maindetails['client_id'] = $list->client_id;
                $maindetails['store_id'] = $list->store_id;
                $maindetails['address'] = $list->address;
                if ($list->promo_code_id == null) {
                    $maindetails['promo_code_id'] = 0;
                    $maindetails['promo_code_value'] = 0;
                } else {
                    $code = PromoCode::where('id', $list->promo_code_id)->first();

                    $maindetails['promo_code_id'] = $list->promo_code_id;
                    $maindetails['promo_code_value'] = $code->value;
                }
                $maindetails['delivery_type'] = $list->delivery_type;
                $maindetails['delivery_cost'] = $list->delivery_cost;
                $maindetails['sub_total'] = $list->sub_total;
                $maindetails['total'] = $list->total;
                $maindetails['status'] = $list->status;
                $maindetails['payment_status'] = $list->payment_status;
                $maindetails['payment_type'] = $list->payment_type;
                $maindetails['username'] = $list->user_name;
                $maindetails['userphone'] = $list->user_phone;
                $maindetails['order_date'] = $list->created_at;
                $maindetails['items_count'] = $list->orderitem_count;
                $products[] = $maindetails;
            }
            return $this->returnData('data', $products, 'success');
        } else {
            $products = [];
            return $this->returnData('data', $products, 'success');
        }
    }


    public function current_myOrders()
    {
        $client = auth('api')->user();
        $myOrder = Order::whereIn('status', [Order::ORDER_STATUSES['ACCEPTED'], Order::ORDER_STATUSES['DELIVERING'], Order::ORDER_STATUSES['PREPAREING']])->where('client_id', $client->id)->get();
        if ($myOrder->count() > 0) {
            foreach ($myOrder as $list) {
                $maindetails['id'] = $list->id;
                $maindetails['code_order'] = $list->code_order;
                $maindetails['product_rate'] = $list->rate;
                $maindetails['client_id'] = $list->client_id;
                $maindetails['store_id'] = $list->store_id;
                $maindetails['address'] = $list->address;
                if ($list->promo_code_id == null) {
                    $maindetails['promo_code_id'] = 0;
                    $maindetails['promo_code_value'] = 0;
                } else {
                    $code = PromoCode::where('id', $list->promo_code_id)->first();

                    $maindetails['promo_code_id'] = $list->promo_code_id;
                    $maindetails['promo_code_value'] = $code->value;
                }
                $maindetails['delivery_type'] = $list->delivery_type;
                $maindetails['delivery_cost'] = $list->delivery_cost;
                $maindetails['sub_total'] = $list->sub_total;
                $maindetails['total'] = $list->total;
                $maindetails['status'] = $list->status;
                $maindetails['payment_status'] = $list->payment_status;
                $maindetails['payment_type'] = $list->payment_type;
                $maindetails['username'] = $list->user_name;
                $maindetails['userphone'] = $list->user_phone;
                $maindetails['order_date'] = $list->created_at;
                $maindetails['items_count'] = $list->orderitem_count;
                $products[] = $maindetails;
            }
            return $this->returnData('data', $products, 'success');
        } else {
            $products = [];
            return $this->returnData('data', $products, 'success');
        }
    }


    public function old_myOrders()
    {
        $client = auth('api')->user();
        $myOrder = Order::whereIn('status', [Order::ORDER_STATUSES['DONE'], Order::ORDER_STATUSES['REJECTED']])->where('client_id', $client->id)->withCount('orderitem')->get();
        if ($myOrder->count() > 0) {
            foreach ($myOrder as $list) {

                $maindetails['id'] = $list->id;
                $maindetails['code_order'] = $list->code_order;
                $maindetails['product_rate'] = $list->rate;
                $maindetails['client_id'] = $list->client_id;
                $maindetails['store_id'] = $list->store_id;
                $maindetails['address'] = $list->address;
                if ($list->promo_code_id == null) {
                    $maindetails['promo_code_id'] = 0;
                    $maindetails['promo_code_value'] = 0;
                } else {
                    $code = PromoCode::where('id', $list->promo_code_id)->first();

                    $maindetails['promo_code_id'] = $list->promo_code_id;
                    $maindetails['promo_code_value'] = $code->value;
                }
                $maindetails['delivery_type'] = $list->delivery_type;
                $maindetails['delivery_cost'] = $list->delivery_cost;
                $maindetails['sub_total'] = $list->sub_total;
                $maindetails['total'] = $list->total;
                $maindetails['status'] = $list->status;
                $maindetails['payment_status'] = $list->payment_status;
                $maindetails['payment_type'] = $list->payment_type;
                $maindetails['username'] = $list->user_name;
                $maindetails['userphone'] = $list->user_phone;
                $maindetails['order_date'] = $list->created_at;
                $maindetails['items_count'] = $list->orderitem_count;
                $products[] = $maindetails;
            }
            return $this->returnData('data', $products, 'success');
        } else {
            $products = [];
            return $this->returnData('data', $products, 'success');
        }
    }



    public function orderDetails(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order) {
            return $this->returnError('404', 'هذا الطلب غير موجود');
        }
        $orderlist = OrderItem::where('order_id', $order->id)->get();

        if (!$orderlist) {
            return $this->returnError('404', '22هذا الطلب غير موجود');
        }



        $client = auth('api')->user();
        $myOrder = Order::where('id', $order->id)->get();
        foreach ($myOrder as $list) {
            $maindetails['id'] = $list->id;
            $maindetails['code_order'] = $list->code_order;
            $maindetails['address'] = $list->address;
            if ($list->promo_code_id == null) {
                $maindetails['promo_code_value'] = 0;
            } else {
                $code = PromoCode::where('id', $list->promo_code_id)->first();
                $maindetails['promo_code_value'] = $code->value;
            }
            $maindetails['delivery_type'] = $list->delivery_type;
            $maindetails['delivery_cost'] = $list->delivery_cost;
            $maindetails['sub_total'] = $list->sub_total;
            $maindetails['total'] = $list->total;
            $maindetails['status'] = $list->status;
            $maindetails['payment_status'] = $list->payment_status;
            $maindetails['payment_type'] = $list->payment_type;
            $maindetails['username'] = $list->user_name;
            $maindetails['userphone'] = $list->user_phone;
            $maindetails['order_date'] = $list->created_at;
            $maindetails['items_count'] = $list->orderitem_count;
            $maindetails['delivery_date'] = $list->delivery_date;
        }
        $products = [];

        foreach ($orderlist as $list) {
            $main_app = Product::select("name","current_price", "image", 'old_price', 'sub_category_id', 'store_id')->where('id', $list->product_id)->get();
            $prodResource = ProductResource::collection($main_app);
            foreach ($prodResource as $prodResource)
                $productdetails['name'] = $prodResource->name;
            $productdetails['image'] = asset('/') . 'public/' . $prodResource->image;
            $productdetails['product_id'] = $list->product_id;
            $productdetails['price'] = (float)$main_app->first()->current_price;
            $productdetails['product_rate'] = $list->rate;
            $productdetails['qty'] = $list->quantity;
            $products[] = $productdetails;
        }
        $data['order_details'] = $maindetails;
        $data['list_products'] = $products;
        return $this->returnData('data', $data, 'success');
    }

    public function banners()
    {
        $banner = BannerResource::collection(Banner::latest()->paginate(4));
        return $this->returnData('data', $banner, 'success');
    }

    // public function allNotifications(Request $request)
    // {
    //     $user = $request->user('api');
    //     $notifications = $user->unreadNotifications([
    //         'data', 'id'
    //     ]);
    //     return $this->apiResponse(data: $notifications);
    // }

    // public function DeleteNotification(Request $request)
    // {
    //     $notification = Notification::findOrFail($request->id)->delete();
    //     return $this->returnSuccessMessage('تم الحذف بنجاح');
    // }

    // public function DeleteAllNotification(Request $request)
    // {
    //     $notification = Notification::truncate();
    //     return $this->returnSuccessMessage('تم حذف الكل');
    // }

    /***** api for phones store safsofa in home page *****/
    public function phones()
    {
        $phone = Store::where('id', 37)->get();
        $phones = StoreResource::collection($phone);
        return $this->returnData('data', $phones, 'success');
    }


    public function MyFav()
    {
        $listFavorites = ListFavorites::where('client_id', auth('api')->user()->id)->get();
        if (count($listFavorites) > 0) {
            foreach ($listFavorites as $fav) {
                // dd($fav->lists);
                $favlists = Favorite::OrderBy("id", "desc")
                    ->where(['client_id' => auth('api')->user()->id, 'list_id' => $fav->id])
                    ->get();

                $productlist['list_name'] = $fav->name;
                $productlist['list_id'] = $fav->id; 

                foreach ($favlists as $key => $fav_list) {
                // dd($fav_list->product);
                    $productlist['list_img' . ($key + 1)] = $fav_list->product ? "https://taqiviolet.com/public/" . $fav_list->product->image : null;
                }

                // $productlist['list_img1'] = null;
                // $productlist['list_img2'] = null;
                // $productlist['list_img3'] = null;
                // $productlist['list_img4'] = null;
                // $productlist['list_img5'] = null;
                $productlist['mainList'] = $favlists;
                $data['list'][] = $productlist;
                $productlist = [];
            }
            return $this->returnData('data', $data, 'success');
        } else {
            $data['list'] = [];
            return $this->returnData('data', $data, 'success');
        }
        // $fav = ListFavorites::where('client_id', auth('api')->user()->id)->get();
        // if (count($fav) > 0) {
        //     foreach ($fav as $fav) {
        //         $favlist = Favorite::OrderBy("id", "desc")
        //              ->where(['client_id' => auth('api')->user()->id, 'list_id' => $fav->id])
        //              ->get();

        //         $productlist['list_name'] = $fav->name;
        //         $productlist['list_id'] = $fav->id;
        //         $productlist['list_img1'] = "https://www.almrsal.com/wp-content/uploads/2021/05/Depositphotos_209552576_s-2019.jpg";
        //         $productlist['list_img2'] = "https://luxuryav.net/image/catalog/mdwnh/Gnral/oct07-20/photography/photo-1556667885-a6e05b14f2eb.jpg";
        //         $productlist['list_img3'] = $fav;
        //         $productlist['list_img4'] = null;
        //         $productlist['list_img5'] = null;
        //         $productlist['total_products'] = count($favlist);
        //         $productlist['mainList'] = $favlist;
        //         $data['list'][] = $productlist;
        //     }
        //     return $this->returnData('data', $data, 'success');
        // } else {
        //     $data['list'] = [];
        //     return $this->returnData('data', $data, 'success');
        // }
    }

    public function app_testMyFav()
    {
        $listFavorites = ListFavorites::where('client_id', auth('api')->user()->id)->get();
        if (count($listFavorites) > 0) {
            foreach ($listFavorites as $fav) {
                // dd($fav->lists);
                $favlists = Favorite::take(5)
                    ->OrderBy("id", "desc")
                    ->where(['client_id' => auth('api')->user()->id, 'list_id' => $fav->id])
                    ->get();

                foreach ($favlists as $key => $fav_list) {
                    $productlist['list_img' . ($key + 1)] = "https://taqiviolet.com/public/" . $fav_list->product->image;
                }
                $productlist['list_name'] = $fav->name;
                $productlist['list_id'] = $fav->id;
                // $productlist['list_img1'] = null;
                // $productlist['list_img2'] = null;
                // $productlist['list_img3'] = null;
                // $productlist['list_img4'] = null;
                // $productlist['list_img5'] = null;
                $productlist['mainList'] = $favlists;
                $data['list'][] = $productlist;
            }
            return $this->returnData('data', $data, 'success');
        } else {
            $data['list'] = [];
            return $this->returnData('data', $data, 'success');
        }
    }


    public function MyCouponList(Request $request)
    {
        $favlist = MyCouponList::OrderBy("id", "desc")->where(['status' => 0, 'client_id' => auth('api')->user()->id])->get();
        if (count($favlist) > 0) {
            foreach ($favlist as $favlist) {
                $ids = $favlist->coupon_id;
                $mainList = PromoCode::where('id', $ids)->get();

                foreach ($mainList as $mainList)
                    $prodlist['id'] = $mainList->id;
                $prodlist['value'] = $mainList->value;
                $prodlist['start_date'] = $mainList->start_date;
                $prodlist['end_date'] = $mainList->end;
                $prodlist['coupon'] = $mainList->code;
                $prodlist['status'] = $mainList->status;
                $data['coupon_lists'][] = $prodlist;
            }
        } else {
            $data['coupon_lists'] = [];
        }
        return $this->returnData('data', $data, 'success');
    }

    public function MyCouponListold(Request $request)
    {
        $favlist = MyCouponList::OrderBy("id", "desc")->where(['status' => 1, 'client_id' => auth('api')->user()->id])->get();
        if (count($favlist) > 0) {
            foreach ($favlist as $favlist) {
                $ids = $favlist->coupon_id;
                $mainList = PromoCode::where('id', $ids)->get();

                foreach ($mainList as $mainList)
                    $prodlist['id'] = $mainList->id;
                $prodlist['value'] = $mainList->value;
                $prodlist['start_date'] = $mainList->start_date;
                $prodlist['end_date'] = $mainList->end;
                $prodlist['coupon'] = $mainList->code;
                $prodlist['status'] = $mainList->status;
                $data['coupon_lists'][] = $prodlist;
            }
        } else {
            $data['coupon_lists'] = [];
        }
        return $this->returnData('data', $data, 'success');
    }


    public function MyFavList(Request $request)
    {
         $data['list_products'] = [];
        $favlist = Favorite::OrderBy("id", "desc")->where(['client_id' => auth('api')->user()->id, 'list_id' => $request->id])->get();
        if (count($favlist) > 0) {
            foreach ($favlist as $favlist) {
                $ids = $favlist->product_id;
                $mainList = Product::select("name", "old_price", 'id', 'image', 'sub_category_id', 'current_price', 'store_id', 'smart_price', 'quantity')->where('id', $ids)->first();
                // foreach ($mainList as $mainList)
                if(!$mainList)
                {
                    continue;
                }
                    $prodlist['name'] = $mainList->name;
                $prodlist['old_price'] = $mainList->old_price;
                $prodlist['id'] = $mainList->id;
                if ($mainList->image != "") {
                    $img = asset('public/' . $mainList->image);
                } else {
                    $img = "";
                }
                $prodlist['image'] = $img;
                $prodlist['current_price'] = $mainList->current_price;
                $prodlist['smart_price'] = $mainList->smart_price;
                $prodlist['quantity'] = $mainList->quantity;

                $data['list_products'][] = $prodlist;
            }
        } else {
            $data['list_products'] = [];
        }
        return $this->returnData('data', $data, 'success');
    }

    public function UserProfile()
    {
        $data = [];
        $user = auth('api')->user();
        if ($user->image != null) {
            $user->image = asset('public/' . $user->image);
        } else {
            $user->image = "";
        }
        $lists = MyList::where('client_id', auth('api')->user()->id)->get();
        $productlist = [];
        $cateList = [];
        if ($lists) {
            foreach ($lists as $list) {
                $productlist = Product::where('is_active',1)->where('id',$list->product_id)->get();
            }
        }
        $categories = MyList::where('client_id', auth('api')->user()->id)->first();
        if ($categories) {
            $pro = Product::where('id', $categories->product_id)->first();
            if ($pro) {
                $cateList = Product::Active()->where('category_id', $pro->category_id)->latest()->take(4)->get();
            }
        }
        $lastorder = Order::where('client_id', auth('api')->user()->id)->latest('updated_at')->first();

        if ($user->total_point == null) {
            $total_points = "";
        } else {
            $total_points = $user->total_point;
        }
        $data = [
            'userProfile'                 =>          $user,
            'MyOrder'                  =>          Order::where('client_id', auth('api')->user()->id)->get()->count(),
            'lastOrder'                =>          $lastorder ? OrderResource::make($lastorder) : null,
            'bonus'                    =>          $total_points,
            'myList'                   =>          ProductResource::collection($productlist),
            'Suggestion'               =>          ProductDetailsResource::collection($cateList),
        ];
        return $this->returnData('data', $data, 'success');
    }


    public function editProfile(Request $request)
    {
        $client = Client::findOrFail($request->id);
        $filePath = $client->image;
        if ($request->has('image')) {
            $filePath = $this->uploadFile('clients', $request->image);
        }
        $client->update([
            'name'                    =>      $request->name,
            'email'                   =>      $request->email,
            'phone'                   =>      $request->phone,
            'address'                 =>      $request->address,
            'image'                   =>      $filePath,
        ]);
        return $this->returnSuccessMessage('تم التعديل بنجاح');
    }

    public function deleteItemCart(Request $request)
    {

        $cart = Cart::where('client_id', auth('api')->user()->id)->first();
        if ($cart) {
            $id_cart = $cart->id;
            $item = CartItem::where('id', $request->id)->where('cart_id', $id_cart)->delete();
            $item = CartItem::where('cart_id', $id_cart)->get();
            if (!$item) {
                $item = Cart::where('id', $id_cart)->delete();
            }
            return $this->returnSuccessMessage('تم الحذف بنجاح');
        } else {
            return $this->returnError('500', 'يوجد بعض الاخطاء');
        }
    }


    public function emptycart()
    {

        $cart = Cart::where('client_id', auth('api')->user()->id)->first();

        if ($cart) {
            $id_cart = $cart->id;
            $item = CartItem::where('cart_id', $id_cart)->delete();
            $item = CartItem::where('cart_id', $id_cart)->get();
            if (!$item) {
                $item = Cart::where('id', $id_cart)->delete();
            }
            return $this->returnSuccessMessage('تم الحذف بنجاح');
        } else {
            return $this->returnError('500', 'يوجد بعض الاخطاء');
        }
    }

    public function sumItemCart(Request $request)
    {
        try {
            $cart = Cart::where('client_id', auth('api')->user()->id)->first();
            $item = CartItem::where(['cart_id' => $cart->id, 'id' => $request->cart_id])->first();
            $item->update([
                'quantity'         => $request->product_quantity,
            ]);
            return $this->returnData('data', $item, 'success');
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }


    public function giftCards(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'receiver'                 => 'required',
            'phone'                    => 'required',
            'message'                  => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }
        if ($request->hasFile('qr_image')) {
            $fileNameToStore = $this->uploadFile('QRImages', $request->qr_image);
        } else {
            $fileNameToStore = null;
        }
        $giftCard = new GiftCard;
        $giftCard->client_id              = auth('api')->user()->id;
        $giftCard->receiver               = $request->receiver;
        $giftCard->phone                  = $request->phone;
        $giftCard->message                = $request->message;
        $giftCard->link                   = $request->link;
        $giftCard->qr_image               = $fileNameToStore;
        $giftCard->type                   = $request->type;
        $giftCard->order_id               = $request->id_order;

        $giftCard->save();
        return $this->returnSuccessMessage('تم ارسال كارت التهنئة بنجاح');
    }

    public function giftCardDetails()
    {
        $giftCard = GiftCard::where('client_id', auth('api')->user()->id)->latest()->take(1)->get();
        return $this->returnData('data', $giftCard, 'success');
    }

    public function editGiftCard(Request $request)
    {
        $giftCart = GiftCard::findOrFail($request->id);
        $giftCart->update([
            'receiver'                     =>      $request->receiver,
            'phone'                        =>      $request->phone,
            'message'                      =>      $request->message,
            'address'                      =>      $request->address,
            'link'                         =>      $request->link,
        ]);
        return $this->returnSuccessMessage('تم التعديل بنجاح');
    }

    public function constructionLink()
    {
        // dd(Auth::guard('api')->id());
        $data = [];
        //  $constraction = ;
        $offerImage = Contact::first();
        $pro = Product::where([['is_active', true]])->orderBy('id', 'desc')->inRandomOrder()->limit(10)->get();
         $special_offers = Product::where([['is_active', true]])->orderBy('current_price', 'asc')->inRandomOrder()->limit(10)->get();
        if (Auth::guard('api')->user()) {
            $order_find = Order::where('client_id', Auth::guard('api')->id())->whereNull('rate_comment')->first();
            $order_id = $order_find->id ?? null;
        } else {
            $order_id = null;
        }
        
        $data = [
            'offerImage'            => 'https://taqiviolet.com/public/' . $offerImage->offer_image,
            'videoLink'             => 'https://taqiviolet.com/public/' . $offerImage->video_link,
            'insp_mop_img'            => 'https://taqiviolet.com/public/' . $offerImage->insp_mop_img,
            'product_list'          => ProductResource::collection($pro),
                'list_best_offers'          => ProductResource::collection($special_offers),
            'order_id'           => $order_id
        ];
        
        
        
        return $this->returnData('data', $data, 'success');
    }


    public function reOrder()
    {
        try {
            $data = [];
            $orders = Order::where([
                'client_id' => Auth::guard('api')->user()->id,
                'status' => 'delivered',
            ])->get();
            return $this->returnData('data', $orders, 'success');




            //             $Items = [];
            //             for ($i = 0; $i < count($order->orderitem); $i++) {
            //                 $Items[] = $order->orderitem[$i]->product_id;
            //                 $pro = Product::where('id', $order->orderitem[$i]->product_id)->first();
            //             }
            //             $products = Product::whereIn('id', $Items)->get();
            //             $prodResource = ProductResource::collection($products);
            //             $data = [
            //                 'OrderDetails'             => $order,
            //                 'ProductsItems'            => $prodResource
            //             ];
            //             return $this->returnData('data',$data,'success');
        } catch (\Exception $e) {
            return $this->returnError(404, $e->getMessage());
        }
    }

    public function reOrderItem($id)
    {
        $items = [];
        $items = OrderItem::where('order_id', $id)->get();
        $order = Order::where('id', $id)->first();
        for ($i = 0; $i < count($order->orderitem); $i++) {
            $Items[] = $order->orderitem[$i]->product_id;
            $pro = Product::where('id', $order->orderitem[$i]->product_id)->first();
        }
        $products = Product::whereIn('id', $Items)->get();
        $prodResource = ProductResource::collection($products);
        return $this->returnData('data', $prodResource, 'success');
    }

    public function pagesFooter()
    {
        $pageFooter = Page::select('title', 'content_app', 'image', 'id')->where(['app_view' => 0, 'type' => 0])->get();
        $pages = PagesResource::collection($pageFooter);
        return $this->returnData('data', $pages, 'success');
    }

    public function companywork()
    {
        $pageFooter = Page::select('title', 'content_app', 'image', 'id')->where('app_view', '>=', 1)->where(['type' => 0])->get();
        $pages = PagesResource::collection($pageFooter);
        return $this->returnData('data', $pages, 'success');
    }


    public function privacyPolicy()

    {
        $pageFooter = Page::where(['app_view' => 1, 'type' => 1])->get();
        $pages = PagesResource::collection($pageFooter);
        return $this->returnData('data', $pages, 'success');
    }

    public function get_pages(Request $request)

    {
        $pageFooter = Page::where(['id_cat' => $request->id, 'type' => 1])->get();
        $pages = PagesResource::collection($pageFooter);
        return $this->returnData('data', $pages, 'success');
    }


    public function subscribe(Request $request)
    {
        $roles = ['email'   => 'required|email|unique:subscribes',];
        $validator = Validator::make($request->all(), $roles, [
            'email.required' => 'the email must be required',
            'email.unique' => 'the email has been token',
        ]);
        if ($validator->fails()) {
            // return $this->returnError(12, $validator->errors());
            // dd($validator->errors()->messages());
            throw ValidationException::withMessages($validator->errors()->messages());
        }
        $support = subscribe::create([
            'email'                => $request->email,
        ]);
        return $this->apiResponse(message: "You have subscribed successfully.");
        // return $this->returnSuccessMessage(true, 200, 'success');
    }

    public function myData()
    {
        $data = [
            'Multiplication_registration_number'               => '310856446400003',
            'Commercial Registration No'                       => '205152080',
        ];

        return $this->returnData('data', $data, 'success');
    }

    public function inspiration()
    {
        $inspiration = Inspiration::where('status',1)->with(['product.Favorites' => function($query){
            return $query->where('client_id',  auth('api')->id());
        }])->get();
        
        // dd($inspiration->first()->product->Favorites);
        $inspir = InspirationResource::collection($inspiration);
        return $this->returnData('data', $inspir, 'success');
    }


    public function privacies(Request $request)
    {
        $privacy = Page::where(function ($query) use ($request) {
            if ($request->has('privacy_id')) {
                $query->where('id', $request->privacy_id);
            }
        })->get();
        $pr =  PagesResource::collection($privacy);
        return $this->returnData('data', $pr, 'success');
    }

    public function searchByPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'start_price' => 'nullable|numeric|min:0',
            'end_price' => 'nullable|numeric|min:1',
            'productName' => 'nullable|string'
        ]);
        $validated = $validator->validated();

        $query = Product::query();
        $query->active();
        if ($request->has('category_id') && !is_null($request->category_id)) {
            $query->where('category_id', $validated['category_id']);
        }

        if ($request->has('start_price') && !is_null($request->start_price)) {
            $query->where('current_price', '>=', $validated['start_price']);
        }

        if ($request->has('end_price') && !is_null($request->end_price)) {
            $query->where('current_price', '<=', $validated['end_price']);
        }

        if ($request->has('price_order') && $request->price_order == '1') { // الاعلي سعر.
            $query->orderBy('current_price', 'desc');
        } elseif ($request->has('price_order') && $request->price_order == '2') { // الاقل سعر
            $query->orderBy('current_price', 'asc');
        }elseif ($request->has('price_order') && $request->price_order == '4') {// الاقدم
            $query->latest();
        } elseif ($request->has('price_order') && $request->price_order == '3') {// الاحدث
            $query->oldest();
        }elseif ($request->has('price_order') && $request->price_order == '5') {// الاكثر مبيعا
            $query->withCount('orderItems') 
            ->orderBy('order_items_count', 'desc');
        }elseif ($request->has('price_order') && $request->price_order == '6') {// الاعلي تقييما
          $query->join('reviews', 'reviews.product_id', '=', 'products.id')
            ->select(DB::raw('avg(rate) as average, products.*'))
            ->groupBy('products.id') 
            ->orderBy('average', 'desc');
        }elseif ($request->has('price_order') && $request->price_order == '7') {// العروض
            $query->where('old_price', '!=', 0);
        }

        if ($request->has('productName') && !is_null($request->productName)) {
            $query->where('name_search', 'LIKE', "%" . $validated['productName'] . "%");
        }

        $products = $query->get();
        $products = ProductResource::collection($products);

        return $this->returnData('data', $products, 'success');
    }
    public function login_admin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //return $this->returnData('data',$request->all(),'success');

        $all_user = User::where(['email' => $request->email, 'txt' => ($request->password)])->first();
        if ($all_user) {



            $data['name'] = $all_user->name;
            $data['id'] = $all_user->id;
            $data['type'] = $all_user->type;
            $data['email'] =  $all_user->email;


            return $this->returnData('data', $data, 'success');
        } else {
            return $this->returnError('E001', __('messages.login information is incorrect'));
        }
    }
}
