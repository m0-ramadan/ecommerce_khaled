<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::where([
            ['store_id', Auth::guard('vendors')->user()->id],
            ['is_active', true],
        ])->get();
        $categories = Category::where('store_id', Auth::guard('vendors')->user()->id)->get();
        return view('vendor.products.index', compact(['products', 'categories']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subcategories = SubCategory::get();
        $categories = Category::where('store_id', Auth::guard('vendors')->user()->id)->get();
        $stores = Store::get();
        return view('vendor.products.add', compact(['subcategories', 'categories', 'stores']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name_ar' => 'required',
            'details_ar' => 'required',
            'price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'category_id' => 'required',
        ]);


        $product = new Product;
        $product->name = ['en' => $request->name_en, 'ar' => $request->name_ar, 'it' => $request->name_it];
        $product->details = ['en' => $request->details_en, 'ar' => $request->details_ar, 'it' => $request->details_it];
        $product->price = $request->price;
        $product->discount = $request->discount;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->sub_category_id = $request->sub_category_id ?? null;
        $product->store_id = Auth::guard('vendors')->user()->id;
        $product->save();
        if ($request->images) {
            foreach ($request->images as $image) {
                $fileNameToStore = $this->uploadFile('product', $image);
                $image = Image::create([
                    'product_id' => $product->id,
                    'src' => $fileNameToStore,
                ]);
            }
        } else {
            $image = Image::create([
                'product_id' => $product->id,
                'src' => Null,
            ]);
        }
        // toastr()->success('تم اضافة المنتج بنجاح');
        // return redirect('vendor/productsV');
        return redirect('vendor/productsV')->with('success', trans('messages.product_added'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($request->id);
        if ($product) {
            $product->is_active = false;
            $product->save();
        }
        // toastr()->error('تم حذف المنتج بنجاح');
        // return back();
        return back()->with('success', trans('messages.product_deleted'));
    }

    public function subCate(Request $request)
    {
        $data = [];
        $subcats = SubCategory::where([
            ['category_id', $request->cat],
            ['store_id', Auth::guard('vendors')->user()->id],
        ])->get();

        foreach ($subcats as $stat) {
            if ($stat->category_id != $request->cat) {
                return response()->json('<option >لاتوجد بيانات</option>');
            }
            $data[] = '<option  value="' . $stat->id . '">' . $stat->name . '</option>';
        }
        return response()->json(["data" => $data]);
    }
}