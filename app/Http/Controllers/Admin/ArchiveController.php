<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Inspiration;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function archiveProduct()
    {
        $archiveProducts = Product::onlyTrashed()->get();
        $categories = Category::get();
        $stores = Store::get();

        return view('admin.archives.product', compact('archiveProducts', 'stores', 'categories'));
    }

    public function archiveinspiration()
    {
        $inspirations = Inspiration::onlyTrashed()->get();
        return view('admin.archives.inspiration', compact('inspirations'));
    }


    public function InsRestore(Request $request)
    {


        $restoreProduct = Inspiration::findOrFail($request->id);

        if ($restoreProduct) {
            $restoreProduct->status = 1;
            $restoreProduct->save();
            $restoreProduct->restore();
            toastr()->success('تم استعادة المنتج بنجاح');
        } else {
            toastr()->error('هذا المنتج غير تابع لقسم رئيسي او قسم فرعي مفعل يرجي التاكد من القسم التابع له هذا المنتج');
        }
        return back();
    }


    public function archiveRestore(Request $request)
    {
          $restoreProduct = Product::withTrashed()->findOrFail($request->id);
         Category::where([
            'id' => $restoreProduct->category_id,
            'is_active' => true
        ])->firstOr(
            function () {
                toastr()->error('هذا المنتج غير تابع لقسم رئيسي او قسم فرعي مفعل يرجي التاكد من القسم التابع له هذا المنتج');
                return redirect()->back();
            }
        );

        $restoreProduct->update(['is_active' => true]);
        $restoreProduct->restore();
        toastr()->success('تم استعادة المنتج بنجاح');

        return redirect()->back();
    }


    public function archivedelete(Request $request)
    { 
        
         $restoreProduct = Product::where('id',$request->id);
   
        $product =$restoreProduct->forceDelete();
        // // $subCate = SubCategory::findOrFail($request->id)->delete();
        // // $cate = Category::findOrFail($request->id)->delete();
        // // $store = Store::findOrFail($request->id)->delete();
        // dd($product);
        toastr()->success('تم الحذف بنجاح');
        return redirect()->back();
    }

    public function inspirationrest(Request $request)
    {
        // $restoreProduct = Inspiration::findOrFail($request->id);
        //     $restoreProduct->status=true;
        //     $restoreProduct->save();
        return back();
    }


    public function archiveSub(Request $request)
    {

        $restoreProduct = Inspiration::findOrFail($request->id);

        if ($restoreProduct) {
            $restoreProduct->status = 1;
            $restoreProduct->save();
            $restoreProduct->restore();
            toastr()->success('تم استعادة المنتج بنجاح');
        } else {
            toastr()->error('هذا المنتج غير تابع لقسم رئيسي او قسم فرعي مفعل يرجي التاكد من القسم التابع له هذا المنتج');
        }
        return back();
    }

    public function subRestore(Request $request)
    {

        dd($request->id);
        $subRestore = SubCategory::findOrFail($request->id);
        $cate = Category::where([
            ['id', $subRestore->category_id],
            ['is_active', true]
        ])->first();
        if ($cate) {
            $subRestore->is_active = true;
            $subRestore->save();
            $products = Product::where('sub_category_id', $request->id)->get();
            foreach ($products as $product) {
                $product->is_Active = true;
                $product->save();
            }
            toastr()->success('تم استعادة القسم الفرعي بنجاح');
        } else {
            toastr()->error('هذا القسم الفرعي غير تابع لقسم رئيسي مفعل يرجي التاكد من القسم التابع له');
        }
        return back();
    }

    public function archiveCate()
    {
        $archiveCate = Category::where(['is_active' => false])->get();
        return view('admin.archives.cate', compact('archiveCate'));
    }

    public function cateRestore(Request $request)
    {
        $cateRestore = Category::findOrFail($request->id);
        $store = Store::where([
            ['id', $cateRestore->store_id],
            ['is_active', true]
        ])->first();
        if ($store) {
            $cateRestore->is_active = true;
            $cateRestore->save();
            $subcategories = SubCategory::where('category_id', $request->id)->get();
            foreach ($subcategories as $sucategory) {
                $sucategory->is_active = true;
                $sucategory->save();
            }

            $products = Product::where('category_id', $request->id)->get();
            foreach ($products as $product) {
                $product->is_active = true;
                $product->save();
            }
            toastr()->success('تم استعادة القسم الرئيسي بنجاح');
        } else {
            toastr()->error('هذا القسم الرئيسي غير تابع لمتجر مفعل يرجي التاكد من المتجر التابع له');
        }
        return back();
    }

    public function archiveStore()
    {
        $archiveStore = Store::where(['is_active' => false])->get();
        return view('admin.archives.store', compact('archiveStore'));
    }

    public function storeRestore(Request $request)
    {
        $storeRestore = Store::findOrFail($request->id);
        $cates = Category::where('store_id', $request->id)->get();
        foreach ($cates as $cate) {
            $cate->is_active = true;
            $cate->save();
        }
        $subcategories = SubCategory::where('category_id', $request->id)->get();
        foreach ($subcategories as $sucategory) {
            $sucategory->is_active = true;
            $sucategory->save();
        }

        $products = Product::where('category_id', $request->id)->get();
        foreach ($products as $product) {
            $product->is_active = true;
            $product->save();
        }
        $storeRestore->is_active = true;
        $storeRestore->save();
        toastr()->success('تم استعادة المتجر بنجاح');
        return back();
    }

    public function archiveOrder()
    {
        $archiveOrder = Order::where(['is_active' => false])->get();
        return view('admin.archives.order', compact('archiveOrder'));
    }

    public function orderRestore(Request $request)
    {
        $orderRestore = Order::findOrFail($request->id);
        $orderRestore->is_Active = true;
        $orderRestore->save();
        toastr()->success('تم استعادة الطلب بنجاح');
        return back();
    }

    public function dele(Request $request)
    {
        $product = Product::findOrFail($request->id)->delete();
        $subCate = SubCategory::findOrFail($request->id)->delete();
        $cate = Category::findOrFail($request->id)->delete();
        $store = Store::findOrFail($request->id)->delete();
    }
    public function delete(Request $request)
    {
        $product = Product::findOrFail($request->id)->delete();
        // $subCate = SubCategory::findOrFail($request->id)->delete();
        // $cate = Category::findOrFail($request->id)->delete();
        // $store = Store::findOrFail($request->id)->delete();
    }
    
    
}
