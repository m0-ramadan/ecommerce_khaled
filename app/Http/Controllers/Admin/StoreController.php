<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class StoreController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stores = Store::where(['is_active'=>true])->get();
        return view('admin.store.index',compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.store.add');
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
            'name_ar'                          => 'required',
            'image'                            => 'required',
            'phone'                            => 'required',
            'address_ar'                       => 'required',
            'facebook'                         => 'required',
            'twitter'                          => 'required',
            'judgments_ar'                     => 'required',
            'about_ar'                         => 'required',
            'replacement_ar'                   => 'required',
        ]);
        if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('store',$request->image);
        }else {
            $fileNameToStore = 'noimage.jpg';
        }
        $store = new Store;
        $store->name             = ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it];
        $store->phone            = $request->phone;
        $store->address          = ['en'=>$request->address_en,'ar'=>$request->address_ar,'it'=>$request->address_it];
        $store->facebook         = $request->facebook;
        $store->twitter          = $request->twitter;
        $store->instagram        = $request->instagram;
        $store->password         = bcrypt($request->password);
        $store->judgments        = ['en'=>$request->judgments_en,'ar'=>$request->judgments_ar,'it'=>$request->judgments_it];
        $store->about            = ['en'=>$request->about_en,'ar'=>$request->about_ar,'it'=>$request->about_it];
        $store->replacement      = ['en'=>$request->replacement_en,'ar'=>$request->replacement_ar,'it'=>$request->replacement_it];
        $store->image            = $fileNameToStore;
        $store->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/store');
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
            $store = Store::findOrFail($request->id);
            if ($request->hasfile('image')) {
                $filepath = $this->uploadFile('store',$request->image);
                $store->update([
                    'image'   => $filepath,
                ]);
            }
            $store->update([
                $store->name            = ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it],
                $store->phone           =$request->phone,
                $store->address         =$request->address,
                $store->facebook        =$request->facebook,
                $store->twitter         =$request->twitter,
                $store->instagram       =$request->instagram,
                $store->judgments       = ['en'=>$request->judgments_en,'ar'=>$request->judgments_ar,'it'=>$request->judgments_it],
                $store->about           = ['en'=>$request->about_en,'ar'=>$request->about_ar,'it'=>$request->about_it],
                $store->replacement     = ['en'=>$request->replacement_en,'ar'=>$request->replacement_ar,'it'=>$request->replacement_it],
            ]);
        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/store');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $store = Store::findOrFail($request->id);
        if ($store){
            $store->is_Active=false;
            $store->save();
            $categories = Category::where('store_id',$request->id)->get();
            foreach ($categories as $category)
            {
                $category->is_Active=false;
                $category->save();
                $subCats= SubCategory::where('category_id',$category->id)->get();
                foreach ($subCats as $subCat)
                {
                    $subCat->is_Active=false;
                    $subCat->save();
                }
                $products= Product::where('category_id',$category->id)->get();
                foreach ($products as $product)
                {
                    $product->is_Active=false;
                    $product->save();
                }

            }
            toastr()->error('تم الحذف بنجاح');
        }else{
            toastr()->error('لا يوجد');
        }

        return redirect('admin/store');
    }
}
