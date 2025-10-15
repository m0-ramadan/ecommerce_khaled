<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class SubCategoryController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subCategories = SubCategory::where(['is_active'=>true])->get();
        $categories = Category::get();
        return view('admin.subcategories.index',compact(['subCategories','categories']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::get();
        $stores = Store::get();
        return view('admin.subcategories.add',compact(['categories','stores']));
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
            'name_ar'           => 'required',
            'category_id'       => 'required',
        ]);


        if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('subcategories',$request->image);
        }else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }
        $subcategory = new SubCategory;
        $subcategory->name      = ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it];
        $subcategory->details   = ['en'=>$request->details_en,'ar'=>$request->details_ar,'it'=>$request->details_it];
        $subcategory->category_id   =$request->category_id;
          $subcategory->cat_title            =$request->cat_title;
                $subcategory->cat_meta             =$request->cat_meta;
                $subcategory->alt_txt              =$request->alt_txt;
                $subcategory->img_title            =$request->img_title;
                $subcategory->slug                 =$request->slug;
        $subcategory->image = $fileNameToStore;

        $subcategory->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/subcategory');
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
       // try{
            $subcategory = SubCategory::findOrFail($request->id);

            if ($request->hasfile('image')) {
                $filepath = $this->uploadFile('subcategories',$request->image);
                $subcategory->update([
                    'image'   => $filepath,
                ]);
            }
            $subcategory->update([
                'name'                 => ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it],
                'details'              => ['en'=>$request->details_en,'ar'=>$request->details_ar,'it'=>$request->details_it],
                'category_id '         => $request->category_id,
            ]);

        //}catch (\Exception $e){
           // return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
        //}
        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/subcategory');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $subcategory = SubCategory::findOrFail($request->id);
        if ($subcategory)
        {
            $subcategory->is_Active=false;
            $subcategory->save();
            $products = Product::where('sub_category_id',$request->id)->get();
            foreach ($products as $product)
            {
                $product->is_Active=false;
                $product->save();

            }
            toastr()->error('تم الحذف بنجاح');
        }else{
            toastr()->error('لا يوجد');
        }

        return redirect('admin/subcategory');
    }

    public function cateStore(Request $request)
    {
        $data   = [];
        $subcats = Category::where([
            ['store_id',$request->cat],
            ['is_active',true]
        ])->get();

        foreach( $subcats as  $stat){
            if( $stat->store_id != $request->cat){
                return response()->json('<option >لاتوجد بيانات</option>') ;
            }
            $data[] = '<option  value="'.$stat->id.'">'. $stat->name .'</option>';
        }
        return response()->json(["data" => $data]) ;
    }
}
