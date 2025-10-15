<?php

namespace App\Http\Controllers\Front;

use App\Models\Visitor;
use App\Services\CountryService;
use DB;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Offer;
use App\Models\Story;
use App\Models\Banner;
use App\Models\Detail;
use App\Models\Review;
use App\Models\Contact;
use App\Models\MetaTag;
use App\Models\Product;
use App\Models\Service;
use App\Models\Support;
use App\Models\Category;
use App\Models\Employee;
use App\Models\GiftCard;
use App\Models\OrderItem;
use App\Models\subscribe;
use App\Models\Inspiration;
use App\Models\ListFavorites;
use App\Models\Favorite;
use App\Models\SubCategory;
use App\Models\Payment;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Coupons;


class FrontController extends Controller
{
    private CountryService $countryService;

    public function __construct(CountryService $countryService)
    {
        $this->countryService = $countryService;
    }

    public function index()
    {

        $ipAddress = request()->ip();
        $country = $this->countryService->getCountryFromIp($ipAddress);
        Visitor::create([
            'ip_address' => $ipAddress,
            'country' => $country,
            'date' => today(),
        ]);


        $search = request()->query('search');

        if ($search) {
             $products = Product::where(function ($query) use ($search) {
            $query->where('details', 'LIKE', '%' . $search . '%')
                  ->orWhere('name_search', 'LIKE', '%' . $search . '%')
                  ->orWhere('name', 'LIKE', '%' . $search . '%')
                   ->orWhere('name->ar', 'LIKE', '%' . $search . '%')  // Search in Arabic
              ->orWhere('name->en', 'LIKE', '%' . $search . '%'); // Search in English;
        })
        ->active()
        ->paginate(20);
            if ($products) {
                return view('Front.pages.search', compact('products'));
            } 
        }

       
        //$smartHome = Category::skip(5)->take(1)->first();
       
        $ceramics = Category::take(1)->first();
        $sanitaryware= Category::skip(1)->take(1)->first();
        $marble = Category::skip(2)->take(1)->first();
      
        $lighting = Category::skip(3)->take(1)->first();
        $drayesh = Category::skip(4)->take(1)->first();
        $blogs = Blog::latest()->take(4)->get();
        $story = Story::latest()->take(1)->first();
        $products = Product::active()->inRandomOrder()->take(4)->get();
        $pros = Product::take(4)->get();
        $offers = Offer::latest()->take(2)->get();
        $banners = Banner::get();
        $video = Contact::where('video_link')->first();
        $services = Service::get();
        $sett = Contact::first();
        $details = Detail::take(4)->get();
        $brands = Payment::get();
        
        return view('Front.index', compact([
            'sanitaryware', 'blogs', 'story', 'offers', 'lighting', 'drayesh',
            'ceramics', 'products', 'pros', 'banners', 'video', 'services', 'sett', 'details', 'marble','brands'
        ]));
    }

    public function about()
    {
        $settings = Contact::get();
        return view('Front.pages.about', compact('settings'));
    }

    public function offers()
    {
        $offers = Offer::latest()->take(2)->get();
        $offerAll = Offer::get();
        return view('Front.pages.offers', compact(['offers', 'offerAll']));
    }

    public function ContactUsForm()
    {
        return view('Front.pages.contactUs');
    }

    public function ContactUs(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required',
            'phone'                 => 'required',
            'email'                 => 'required',
        ]);

        $message = new Support;
        $message->name                      = $request->name;
        $message->phone                     = $request->phone;
        $message->message                   = $request->message;
        $message->type                      = 0;
        $message->email                     = $request->email;
        $message->save();
        toastr()->success('تم ارسال الرسالة بنجاح');
        return back();
    }

    public function subscribe(Request $request)
    {
        $validated = $request->validate([
            'email'                  => 'required',
        ]);
        $subscribe = new subscribe;
        $subscribe->email                   = $request->email;
        $subscribe->save();
        toastr()->success('تم التسجيل بنجاح');
        return back();
    }

    public function blogs()
    {
        $blogs = Blog::get();
        return view('Front.pages.blogs', compact('blogs'));
    }

    public function blogDetails($id)
    {
        $allBlogs = Blog::latest()->take(6)->get();
        $blog = Blog::findOrFail($id);
        return view('Front.pages.blogDetails', compact(['blog', 'allBlogs']));
    }

    public function successStories()
    {
        $stories = Story::get();
        return view('Front.pages.successStories', compact('stories'));
    }

    public function storyDetails($id)
    {
        $story = Story::findOrFail($id);
        $stories = Story::get();
        return view('Front.pages.storyDetails', compact(['story', 'stories']));
    }

    public function inspiration()
    {

        $inspirations = Inspiration::whereHas('product', function ($query) {
            $query->where('is_active', 1);
        })->get();
        return view('Front.pages.inspiration', compact('inspirations'));
    }


   public function furniture($id)
{
    $subCategories = SubCategory::where('category_id', $id)->get();
    $ids = $id;
    $category = Category::where('id', $id)->withCount('products')->first();
    $cate = Category::where('id', $id)->withCount('products')->first();
    // dd( $category);
    $search = request()->query('search');

    if ($search) {
        $products = Product::inRandomOrder()->where(['category_id' => $id])
            ->withCount(['Favorites' => function ($query) {
                $query->where('client_id', auth('clientsWeb')->id());
            }])
            ->where('details', 'LIKE', '%' . $search . '%')
            ->orWhere('name_search', 'LIKE', '%' . $search . '%')
            ->orWhere('name', 'LIKE', '%' . $search . '%')
              ->orWhere('name', 'LIKE', '%' . $search . '%')
                   ->orWhere('name->ar', 'LIKE', '%' . $search . '%')  // Search in Arabic
              ->orWhere('name->en', 'LIKE', '%' . $search . '%')// Search in English;
            ->active()
            ->paginate(100)
            ->appends(['search' => $search]); // Append the search query to pagination links
    } else {
        $products = Product::inRandomOrder()->where(['category_id' => $id])
            ->active()
            ->withCount(['Favorites' => function ($query) {
                $query->where('client_id', auth('clientsWeb')->id());
            }])
            ->paginate(100);
    }

    return view('Front.pages.furniture', compact(['subCategories', 'products', 'ids', 'category','cate']));
}
    public function SaleFurniture()
    {
        $search = request()->query('search');
        if ($search) {
            dd(request()->query('search'));
        }

        $products = Product::where('old_price', '>', 0)->active()->paginate(100);
        return view('Front.pages.sale_products', compact('products'));
    }

    public function furnitureSub($id)
    {
        $products = Product::where('sub_category_id', $id)->active()->paginate(6);
        $sub_categories = SubCategory::where('id', $id)->get();
        $cat = SubCategory::find($id)->category->id;
        $category = Category::where('id', $cat)->get();
        $subcats = SubCategory::where('category_id', $cat)->get();
        $ids = $cat;
        return view('Front.pages.furnitureSub', compact(['products', 'subcats', 'ids']));
    }


    public function productDetails($title,$id)
    {
        $product = Product::with('productFeatures')->findOrFail($id);
        $description = $product->metadescription; // Assuming 'description' is a field in your Product model
        $keywords = $product->keywords; // Assuming 'keywords' is a field in your Product model
        $sliders=Image::where('product_id', $product->id)->get();
        $numbers = OrderItem::where('product_id', $id)->count();
        $cate = $product->category_id;
        $catesId = Category::where('id', $cate)->first();
        $RelatedProducts = Product::inRandomOrder()->where('category_id', $catesId->id)->active()->latest()->take(4)->get();
        $reviews = Review::where('product_id', $id)->get();
        return view('Front.pages.productDetails', compact(['product', 'numbers', 'RelatedProducts', 'reviews','description','keywords','sliders']));
    }

    public function AddReview(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $validated = $request->validate([
            'comment'                  => 'required',
            'rate'                     => 'required',
        ]);
        $data = [];
        if ($request->hasfile('image')) {
            foreach ($request->file('image') as $file) {
                $data[] = $this->uploadFile('reviews', $file);
            }
        } else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }

        $review = new Review;
        $review->comment                = $request->comment;
        $review->product_id             = $request->id;
        $review->client_id              = Auth::guard('clientsWeb')->user()->id;
        $review->rate                   = $request->rate;
        $review->image                  = implode(',', $data);
        $review->save();
        toastr()->success('تم اضافة التقيم بنجاح');
        return back();
    }

    public function offerDetails($id)
    {
        $offer = Offer::findOrFail($id);
        $allOffer = Offer::get();
        return view('Front.pages.offerDetails', compact(['offer', 'allOffer']));
    }

    public function safsoofaPhones()
    {
        $categories = Category::where(['store_id' => 37, 'is_active' => 1])->get();
        $products = Product::where('store_id', 37)->active()->paginate(6);
        return view('Front.pages.phones', compact(['categories', 'products']));
    }

    public function phonesCategory($id)
    {
        $categories = Category::where(['store_id' => 37, 'is_active' => 1])->get();
        $products = Product::where('category_id', $id)->active()->paginate(3);
        return view('Front.pages.phonesCategory', compact(['categories', 'products']));
    }

    public function filter(Request $request)
    {
        $ids = $request->category_id;

        $query = Product::query();
        $query->active();
        $query->where('category_id',  $request->category_id);
        $subCategories = SubCategory::where('category_id', $request->category_id)->get();

        if ($request->filter == 1) {
            $between = [10, 250];
        } elseif ($request->filter == 2) {
            $between = [251, 500];
        } elseif ($request->filter == 3) {
            $between = [501, 1000];
        } elseif ($request->filter == 4) {
            $between = [1001, 1500];
        }

        if (isset($between)) {
            $query->whereBetween('current_price', $between);
        }

        if ($request->has('price_max')) {
            if ($request->price_max == 'on') {
                $query->orderBy('current_price', 'DESC');
            } else {
                $query->orderBy('current_price', 'ASC');
            }
        }

        if ($request->has('sorting_option') && $request->sorting_option == 'newest') {
            $query->latest();
        }elseif($request->has('sorting_option') && $request->sorting_option == 'oldest'){
            $query->oldest();
        }



        if ($request->most_rated == 'on') {
           $query->join('reviews', 'reviews.product_id', '=', 'products.id')
            ->select(DB::raw('avg(rate) as average, products.*'))
            ->groupBy('products.id')
            ->orderBy('average', 'desc');
        }

        if ($request->most_selling == 'on') {
            $query->withCount('orderItems')
            ->orderBy('order_items_count', 'desc');
        }

        if ($request->offers == 'on') {
            $query->where('old_price', '!=', 0);
        }

        // if ($request->has('reviews')) {
        //     $query->with(['reviews' => function ($query) {
        //         $query->select('product_id', DB::raw('avg(rate) as average_rating'))
        //               ->groupBy('product_id');
        //     }]);

        //     $query->orderBy(
        //         DB::raw('(SELECT AVG(rate) FROM reviews WHERE reviews.product_id = products.id)')
        //     );
        // }


        $products = $query->paginate(100);
        return view('Front.pages.filterProducts', compact(['products', 'subCategories', 'ids']));

        //return $request->category_id;
        // if ($request->filter == 1) {
        //     $ids = $request->category_id;
        //     $products = Product::where('category_id', $request->category_id)->whereBetween('current_price', [10, 250])->paginate(9);
        //     $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        //     return view('Front.pages.filterProducts', compact(['products', 'subCategories', 'ids']));
        // } elseif ($request->filter == 2) {
        //     $ids = $request->category_id;
        //     $products = Product::where('category_id', $request->category_id)->whereBetween('current_price', [251, 500])->paginate(9);
        //     $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        //     return view('Front.pages.filterProducts', compact(['products', 'subCategories', 'ids']));
        // } elseif ($request->filter == 3) {
        //     $ids = $request->category_id;
        //     $products = Product::where('category_id', $request->category_id)->whereBetween('current_price', [501, 1000])->paginate(9);
        //     $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        //     return view('Front.pages.filterProducts', compact(['products', 'subCategories', 'ids']));
        // } elseif ($request->filter == 4) {
        //     $ids = $request->category_id;
        //     $products = Product::where('category_id', $request->category_id)->whereBetween('current_price', [1001, 1500])->paginate(9);
        //     $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        //     return view('Front.pages.filterProducts', compact(['products', 'subCategories', 'ids']));
        // } elseif ($request->filter == 0) {
        //     $ids = $request->category_id;
        //     $products = Product::where('category_id', $request->category_id)->paginate(9);
        //     $subCategories = SubCategory::where('category_id', $request->category_id)->get();
        //     return view('Front.pages.filterProducts', compact(['products', 'subCategories', 'ids']));
        // }
    }


    public function pagesFooter($id)
    {
        $id = $id;
        $pages = Page::where('id', $id)->first();
        return view('Front.pages.pagesFooter', compact(['pages', 'id']));
    }

    public function employmentForm()
    {
        return view('Front.pages.employmentForm');
    }

    public function doEmploymentForm(Request $request)
    {
        $validated = $request->validate([
            'name'                  => 'required',
            'address'               => 'required',
            'message'               => 'required',
            'email'                 => 'required',
        ]);
        if ($request->hasFile('file')) {
            $fileNameToStore = $this->uploadFile('employees', $request->file);
        } else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }

        $message = new Employee;
        $message->name                      = $request->name;
        $message->address                   = $request->address;
        $message->message                   = $request->message;
        $message->email                     = $request->email;
        $message->file                      = $fileNameToStore;
        $message->save();
        toastr()->success('تم ارسال الرسالة بنجاح');
        return back();
    }

    public function doGiftCard(Request $request)
    {
        $giftCard = new GiftCard;
        $giftCard->receiver                 = $request->receiver;
        $giftCard->phone                    = $request->phone;
        $giftCard->address                  = $request->address;
        $giftCard->message                  = $request->message;
        $giftCard->order_id                 = $request->order_id;
        $giftCard->client_id                = $request->client_id;
        $giftCard->type                     = $request->sender ? 1 : 0;
        $giftCard->save();
        toastr()->success('تم ارسال البرقية بنجاح');
        return redirect(route('/'));
    }
    public function ServiceDetails($id)
    {
        $service = Service::findOrFail($id);
        return view('Front.pages.serviceDetails', compact('service'));
    }

    public function createFavList(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $items = ListFavorites::create([
            'client_id' => auth('clientsWeb')->id(),
            'name' => $request->name
        ]);

        return redirect()->back()->with(['success' => "List Created successfully."]);
    }

   public function removeFavList(Request $request)
    {
 

        Favorite::where('id', $request->id)->delete();
        $mess= __('front.deletess');
        return redirect()->back()->with(['success' => $mess]);
    }

    public function addProductToFavList(Request $request)
    {
        $request->validate([
            'list_id' => 'required|exists:list_favorites,id,client_id,' . auth('clientsWeb')->id(),
            'product_id' => 'required|exists:products,id',
        ]);

        Favorite::create([
            'client_id' => auth('clientsWeb')->id(),
            'product_id' => $request->product_id,
            'list_id' => $request->list_id,
        ]);

        return redirect()->route('productDetails', $request->product_id)->with(['success' => "Product Added to list successfully."]);
    }

    public function removeProductFromFavList(Request $request)
    {
        $request->validate([
            'list_id' => 'bail|required|exists:list_favorites,id,client_id,' . auth('clientsWeb')->id(),
            'product_id' => 'bail|required|exists:products,id|exists:favorites,product_id,list_id,' . $request->list_id,
        ]);

        Favorite::where([
            'client_id' => auth('clientsWeb')->id(),
            'product_id' => $request->product_id,
            'list_id' => $request->list_id,
        ])->delete();

        return redirect()->back()->with(['success' => "Product deleted from the favorite list successfully."]);
    }


    public function showFavorite(ListFavorites $listFavorites)
    {
        $favorites = $listFavorites->favorites()->with(['product'])->get();
        return view('Front.pages.show-favorite', compact('favorites'));
    }
    
    /******************************************************/
public function applyPromoCode(Request $request)
{
    try {
        // Validate the promo code
        $request->validate([
            'promo_code' => 'required|string|exists:coupons,code',
        ]);

        $coupon = Coupons::where('code', $request->promo_code)->first();
        if ($coupon && $coupon->expiry_date > now()) {
            $discount = $coupon->mount;
            $products = Product::all(); // Replace this with your actual product data logic
            $totalPrice = $products->sum('current_price') - $discount;

            return response()->json(['totalPrice' => $totalPrice]);
        } else {
            return response()->json(['error' => 'Invalid or expired promo code.']);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()]);
    }
}


}
