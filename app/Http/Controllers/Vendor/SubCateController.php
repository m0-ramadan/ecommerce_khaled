<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubCateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCategories = SubCategory::where([
            ['store_id', Auth::guard('vendors')->user()->id],
            ['is_Active', true]
        ])->get();
        $categories = Category::where('store_id', Auth::guard('vendors')->user()->id)->get();
        return view('vendor.subCategories.index', compact(['subCategories', 'categories']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('store_id', Auth::guard('vendors')->user()->id)->get();
        $stores = Store::get();
        return view('vendor.subCategories.add', compact(['categories', 'stores']));
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
            'category_id' => 'required',
        ]);

        if ($request->hasFile('image')) {
            $fileNameToStore = $this->uploadFile('subcategories', $request->image);
        } else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }
        $subcategory = new SubCategory;
        $subcategory->name = ['en' => $request->name_en, 'ar' => $request->name_ar, 'it' => $request->name_it];
        $subcategory->details = ['en' => $request->details_en, 'ar' => $request->details_ar, 'it' => $request->details_it];
        $subcategory->category_id = $request->category_id;
        $subcategory->store_id = Auth::guard('vendors')->user()->id;
        $subcategory->image = $fileNameToStore;

        $subcategory->save();
        // toastr()->success('تمت الاضافة بنجاح');
        // return redirect('vendor/subCateVendor');
        return redirect('vendor/subCateVendor')->with('success', trans('messages.subcategory_added'));

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
        $subCategory = SubCategory::findOrFail($request->id);
        if ($request->hasfile('image')) {
            $filepath = $this->uploadFile('subcategories', $request->image);
            $subCategory->update([
                'image' => $filepath,
            ]);
        }
        $subCategory->update([
            'name' => $request->name,
            'details' => $request->details,
            'category_id ' => $request->category_id,
        ]);
        // toastr()->success('تم التعديل بنجاح');
        // return redirect('vendor/subCateVendor');
        return redirect('vendor/subCateVendor')->with('success', trans('messages.subcategory_updated'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $subCate = SubCategory::findOrFail($request->id);
        if ($subCate) {
            $subCate->is_active = false;
            $subCate->save();
            $products = Product::where('sub_category_id', $request->id)->get();
            foreach ($products as $product) {
                $product->is_active = false;
                $product->save();
            }
            //     toastr()->error('تم حذف المنتج بنجاح');
            // } else {
            //     toastr()->error('لا يوجد');
            // }
            // return back();
            return back()->with('success', trans('messages.subcategory_deleted'));
        } else {
            return back()->with('error', trans('messages.subcategory_not_found'));
        }
    }
}