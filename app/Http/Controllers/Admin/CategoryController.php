<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Store;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Traits\UploadFileTrait;


class CategoryController extends Controller
{
    use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where(['is_active' => true])->get();
        $stores = Store::get();

        return view('admin.categories.index', compact(['categories', 'stores']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $stores = Store::get();
        return view('admin.categories.add', compact('stores'));
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
            'name_ar'                 => 'required',
            'description_ar'          => 'required',
            'store_id'                => 'required',

            'image_ar' => 'required|image',
            'image_en' => 'required|image',
            'image_it' => 'required|image',

            'image_mop_ar' => 'required|image',
            'image_mop_en' => 'required|image',
            'image_mop_it' => 'required|image',

        ]);
        // if ($request->hasFile('image')){
        //     $fileNameToStore = $this->uploadFile('category',$request->image);
        // }else {
        //     $fileNameToStore = 'images/zummXD2dvAtI.png';
        // }

        // if ($request->hasFile('image_mop')){
        //     $fileNameToStoreMop = $this->uploadFile('category',$request->image_mop);
        // }else {
        //     $fileNameToStoreMop = 'images/zummXD2dvAtI.png';
        // }

        $image['ar'] = $this->uploadFile('category', $request->image_ar);
        $image['en'] = $this->uploadFile('category', $request->image_en);
        $image['it'] = $this->uploadFile('category', $request->image_it);

        $image_mop['ar'] = $this->uploadFile('category', $request->image_mop_ar);
        $image_mop['en'] = $this->uploadFile('category', $request->image_mop_en);
        $image_mop['it'] = $this->uploadFile('category', $request->image_mop_it);

        $category = new Category;
        $category->name                = ['en' => $request->name_en, 'ar' => $request->name_ar, 'it' => $request->name_it];
        $category->description         = ['en' => $request->description_en, 'ar' => $request->description_ar, 'it' => $request->description_it];
        $category->store_id            = $request->store_id;
        $category->image               = $image;
        $category->image_mop           = $image_mop;
        $category->cat_title            = $request->cat_title;
        $category->cat_meta            = $request->cat_meta;
        $category->alt_txt            = $request->alt_txt;
        $category->img_title            = $request->img_title;
        $category->slug            = $request->slug;
$category->type            = $request->type;



        $category->save();
        toastr()->success('تمت الاضافة بنجاح');

        return redirect('admin/category');
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
        $validatedData = $request->validate([
            'image_ar'               => 'nullable|image',
            'image_en'               => 'nullable|image',
            'image_it'               => 'nullable|image',

            'image_mop_ar'               => 'nullable|image',
            'image_mop_en'               => 'nullable|image',
            'image_mop_it'               => 'nullable|image',
        ]);


        // if ($request->hasfile('image')) {
        //     $filepath = $this->uploadFile('category', $request->image);
        //     $category->update([
        //         'image'   => $filepath,
        //     ]);
        // }
        // if ($request->hasfile('image_mop')) {
        //     $filepathMop = $this->uploadFile('category', $request->image_mop);
        //     $category->update([
        //         'image_mop'   => $filepathMop,
        //     ]);
        // }
        $image['ar'] = $request->hasFile('image_ar') ? $this->uploadFile('category', $request->file('image_ar')) : $category->getTranslation('image', 'ar');
        $image['en'] = $request->hasFile('image_en') ? $this->uploadFile('category', $request->file('image_en')) : $category->getTranslation('image', 'en');
        $image['it'] = $request->hasFile('image_it') ? $this->uploadFile('category', $request->file('image_it')) : $category->getTranslation('image', 'it');
    
        $image_mop['ar'] = $request->hasFile('image_mop_ar') ? $this->uploadFile('category', $request->file('image_mop_ar')) : $category->getTranslation('image_mop', 'ar');
        $image_mop['en'] = $request->hasFile('image_mop_en') ? $this->uploadFile('category', $request->file('image_mop_en')) : $category->getTranslation('image_mop', 'en');
        $image_mop['it'] = $request->hasFile('image_mop_it') ? $this->uploadFile('category', $request->file('image_mop_it')) : $category->getTranslation('image_mop', 'it');
    
    
        $category->update([
            $category->name                 =  ['en' => $request->name_en, 'ar' => $request->name_ar, 'it' => $request->name_it],
            $category->description          =  ['en' => $request->description_en, 'ar' => $request->description_ar, 'it' => $request->description_it],
            $category->cat_title            = $request->cat_title,
            $category->cat_meta             = $request->cat_meta,
            $category->alt_txt              = $request->alt_txt,
            $category->img_title            = $request->img_title,
            $category->slug                 = $request->slug,
            $category->image                = $image,
            $category->image_mop                 = $image_mop,
        ]);
        if ($request->store_id != "") {
            $category->update([$category->store_id =  $request->store_id]);
        }

        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/category');
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
            toastr()->error('تم الحذف بنجاح');
        } else {
            toastr()->error('هذا القسم غير موجود');
        }


        return redirect('admin/category');
    }

    public function subcats(Request $request)
    {
        $data   = [];
        $subcats = SubCategory::where([
            ['category_id', $request->cat],
            ['is_active', true]
        ])->get();

        foreach ($subcats as  $stat) {
            if ($stat->category_id != $request->cat) {
                return response()->json('<option >لاتوجد بيانات</option>');
            }
            $data[] = '<option  value="' . $stat->id . '">' . $stat->name . '</option>';
        }
        return response()->json(["data" => $data]);
    }
}
