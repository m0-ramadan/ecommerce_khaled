<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('store_id', Auth::guard('vendors')->user()->id)->get();

        return view('vendor.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vendor.categories.add');
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
            'description_ar' => 'required',
        ]);
        if ($request->hasFile('image')) {
            $fileNameToStore = $this->uploadFile('category', $request->image);
        } else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }

        $category = new Category;
        $category->name = ['en' => $request->name_en, 'ar' => $request->name_ar, 'it' => $request->name_it];
        $category->description = ['en' => $request->description_en, 'ar' => $request->description_ar, 'it' => $request->description_it];
        $category->store_id = Auth::guard('vendors')->user()->id;
        $category->image = $fileNameToStore;
        $category->save();
        // toastr()->success('تمت الاضافة بنجاح');
        // return redirect('vendor/categories');

        return redirect('vendor/categories')->with('success', trans('messages.category_added'));
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
        $category = Category::findOrFail($request->id);
        if ($request->hasfile('image')) {
            $filepath = $this->uploadFile('category', $request->image);
            $category->update([
                'image' => $filepath,
            ]);
        }
        $category->update([
            $category->name = $request->name,
            $category->description = $request->description,
        ]);
        // toastr()->success('تم التعديل بنجاح');
        // return back();

        return back()->with('success', trans('messages.category_updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $category = Category::findOrFail($request->id);
        if ($category) {
            $category->is_active = false;
            $category->save();
            $subcategories = SubCategory::where('category_id', $request->id)->get();
            foreach ($subcategories as $sucategory) {
                $sucategory->is_active = false;
                $sucategory->save();
            }
            $products = Product::where('category_id', $request->id)->get();
            foreach ($products as $product) {
                $product->is_active = false;
                $product->save();
            }
            // toastr()->error('تم الحذف بنجاح');
            return back()->with('success', trans('messages.category_deleted'));

        } else {
            // toastr()->error('هذا القسم غير موجود');
            return back()->with('error', trans('messages.category_not_found'));

        }
        // return back();
    }
}