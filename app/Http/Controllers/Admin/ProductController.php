<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\MetaTag;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Inspiration;
use App\Models\Favorite;
use App\Models\Store;
use App\Models\SubCategory;
use App\Models\ProductFeature;
use Illuminate\Http\Request;
use Auth;
use App\Traits\UploadFileTrait;


class ProductController extends Controller
{
    use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::Active()->get();
        $categories = Category::get();
        $subcategories = SubCategory::get();
        $stores = Store::where('is_active', 1)->get();;

        return view('admin.products.index', compact(['products', 'categories', 'subcategories', 'stores']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subcategories = SubCategory::where('is_active', 1)->get();
        $categories = Category::where('is_active', 1)->get();
        $stores = Store::where('is_active', 1)->get();
        return view('admin.products.add', compact(['subcategories', 'categories', 'stores']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        
        if ($request->hasFile('image')) {

            $imageNameToStore = $this->uploadImage('product', $request->image);
        }
        
         


        $setting = Contact::first();
        $current_price = $request->current_price;
        $old_price = $request->old_price;
        $tax_rate = $setting->tax_rate;
        $profit_product    = $setting->profit_product;
        $profit_product_old    = $setting->profit_product_old;

        //old price
        $profit_product_price = ($current_price + (($current_price * $profit_product) / 100));
        $tax_rate_finalprice = ($profit_product_price + (($tax_rate * $profit_product_price) / 100));

        //old price
        $profit_product_price_old = ($current_price + (($old_price * $profit_product_old) / 100));
        $tax_rate_finalprice_old = ($profit_product_price_old + (($tax_rate * $profit_product_price_old) / 100));


        $finaloldprice = str_replace(',', '', number_format($tax_rate_finalprice_old, 2));
        $finalcrrentprice = str_replace(',', '', number_format($tax_rate_finalprice, 2));




        $validatedData = $request->validate([
            'name_ar'                          => 'required',
            'category_id'                      => 'required',
        ]);
 
    
        $product = new Product;
        $product->name                             = ['en' => $request->name_en, 'ar' => $request->name_ar, 'it' => $request->name_it];
        $product->name_url                             = ['en' => $request->name_url_en, 'ar' => $request->name_url_ar, 'it' => $request->name_url_it];
       $product->detailsweb                         = ['en' => $request->detailsweb_en, 'ar' => $request->detailsweb_ar, 'it' => $request->detailsweb_it];
        $product->name_search                      = $request->name_en . ',' . $request->name_ar . ',' . $request->name_it;
        $product->details                          = ['en' => $request->details_en, 'ar' => $request->details_ar, 'it' => $request->details_it];
        $product->title_img                        = $request->img_title;
        $product->alt_text                         = $request->alt_title;
        $product->slug                             = $request->slug;
        $product->serial_no                        = $request->serail_no;
        $product->tax_amount                       = $request->tax_amount;
        $product->store_id                         = $request->store_id;
        $product->smart_price                      = $request->smart_price;
        $product->old_price                        = $finaloldprice;
        $product->ref_name                         = $request->ref_name;
        $product->url                              = $request->url;
        $product->ref_name1                        = $request->ref_name1;
        $product->mainoldprice                     = $request->old_price;
        $product->mainprice                        = $request->current_price;
        $product->aliexpress                       = $request->aliexpress_url;
        $product->keywords                         = $request->keywords;
        $product->current_price                    = $finalcrrentprice;
        $product->quantity                         = $request->quantity;
        $product->original_quantity                = $request->quantity;
        $product->category_id                      = $request->category_id;
        $product->delivery_time                    = ['en' => $request->delivery_time_en, 'ar' => $request->delivery_time_ar, 'it' => $request->delivery_time_it];
        $product->shippingcharges                  = ['en' => $request->shippingcharges_en, 'ar' => $request->shippingcharges_ar, 'it' => $request->shippingcharges_it];
        $product->shippingcharges_value            = $request->shippingcharges_value;
        $product->sub_category_id                  = $request->sub_category_id ?? null;
        $product->image                            = $imageNameToStore ?? null;
         $product->deleted_at                       = Now();
        $product->save();





        $feature = ProductFeature::create([
            'name' =>   ['en' => $request->productfeaturename_en, 'ar' => $request->productfeaturename_ar, 'it' => $request->productfeaturename_it],
            'description' =>  ['en' => $request->productfeaturedescription_en, 'ar' => $request->productfeaturedescription_ar, 'it' => $request->productfeaturedescription_it],
            'product_id' => $product->id

        ]);


        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $key => $image) {
 
                $filename = time() . $key . '.' . $image->extension();
                $fileNameToStore = $this->uploadImages($image, 'product', $filename);
                $imagenew = Image::create([
                    'product_id'       => $product->id,
                    'src'               => $fileNameToStore,
                ]);
            }
        } else {
            $image = Image::create([
                'product_id'       => $product->id,
                'src'              => Null,
            ]);
        }
        $metaTags = new MetaTag;
        $metaTags->product_id                   = $product->id;
        $metaTags->text                         = $product->details;
        $metaTags->save();

        toastr()->success('تم اضافة المنتج بنجاح');
        return redirect('admin/product');
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
        $setting = Contact::first();
        $current_price = $request->mainprice;
        $old_price = $request->mainoldprice;
        $tax_rate = $setting->tax_rate;
        $profit_product    = $setting->profit_product;
        $profit_product_old    = $setting->profit_product_old;

        //old price
        $profit_product_price = ($current_price + (($current_price * $profit_product) / 100));
        $tax_rate_finalprice = ($profit_product_price + (($tax_rate * $profit_product_price) / 100));

        //old price
        $profit_product_price_old = ($old_price + (($old_price * $profit_product_old) / 100));
        $tax_rate_finalprice_old = ($profit_product_price_old + (($tax_rate * $profit_product_price_old) / 100));




        $product = Product::withTrashed()->findOrFail($request->id);

        $ProductFeature = ProductFeature::where('product_id', $request->id)->first();
        if ($ProductFeature) {
            $feature = ProductFeature::where('product_id', $request->id)->update([
                'name' =>   ['en' => $request->productfeaturename_en, 'ar' => $request->productfeaturename_ar, 'it' => $request->productfeaturename_it],
                'description' =>  ['en' => $request->productfeaturedescription_en, 'ar' => $request->productfeaturedescription_ar, 'it' => $request->productfeaturedescription_it],
            ]);
        } else {

            $feature = ProductFeature::create([
                'name' =>   ['en' => $request->productfeaturename_en, 'ar' => $request->productfeaturename_ar, 'it' => $request->productfeaturename_it],
                'description' =>  ['en' => $request->productfeaturedescription_en, 'ar' => $request->productfeaturedescription_ar, 'it' => $request->productfeaturedescription_it],
                'product_id' => $request->id

            ]);
        }

        if ($request->hasFile('image')) {
            $imageNameToStore = $this->uploadImage('product', $request->image);
        }

        if ($request->hasFile('images')) {

            $oldimages = Image::where('product_id', $id)->get();

            foreach ($oldimages as $oldimage) {
                $oldimage->delete();
            }
            $images = $request->file('images');
            foreach ($images as $key => $image) {

                $filename = time() . $key . '.' . $image->extension();
                $fileNameToStore = $this->uploadImages($image, 'product', $filename);
                $imagenew = Image::create([
                    'product_id'       => $product->id,
                    'src'               => $fileNameToStore,
                ]);
            }
        };

        $finaloldprice = str_replace(',', '', number_format($tax_rate_finalprice_old, 2));
        $finalcrrentprice = str_replace(',', '', number_format($tax_rate_finalprice, 2));

    

        $product->update([
            $product->name                          = ['en' => $request->name_en, 'ar' => $request->name_ar, 'it' => $request->name_it],
             $product->name_url                             = ['en' => $request->name_url_en, 'ar' => $request->name_url_ar, 'it' => $request->name_url_it],
            $product->details                       = ['en' => $request->details_en, 'ar' => $request->details_ar, 'it' => $request->details_it],
            $product->detailsweb                    = ['en' => $request->detailsweb_en, 'ar' => $request->detailsweb_ar, 'it' => $request->detailsweb_it],
            $product->old_price                     =  $finaloldprice,
            $product->current_price                 =  $finalcrrentprice,
            $product->title_img                     = $request->img_title,
            $product->alt_text                      = $request->alt_title,
            $product->slug                          = $request->slug,
            $product->serial_no                     = $request->serail_no,
            $product->tax_amount                    = $request->tax_amount,
            $product->mainoldprice                  = $request->mainoldprice,
            $product->mainprice                     = $request->mainprice,
            $product->aliexpress                    = $request->aliexpress_url,
            $product->keywords                      = $request->keywords,
            $product->metadescription               = $request->metadescription,
            $product->smart_price                   = $request->smart_price,
            $product->quantity                      = $request->quantity,
            $product->ref_name                      = $request->ref_name,
            $product->url                           = $request->url,
            $product->ref_name1                     = $request->ref_name1,
            $product->delivery_time                 = ['en' => $request->delivery_time_en, 'ar' => $request->delivery_time_ar, 'it' => $request->delivery_time_it],
            $product->shippingcharges               = ['en' => $request->shippingcharges_en, 'ar' => $request->shippingcharges_ar, 'it' => $request->shippingcharges_it],
            $product->shippingcharges_value         = $request->shippingcharges_value,
            $product->sub_category_id               = $request->sub_category_id ?? null,
            $product->image                         =  ($request->has('image')) ? $imageNameToStore : $product->image,
        ]);

        if ($request->category_id != "") {
            $product->update([
                $product->category_id                     = $request->category_id
            ]);
        }


        if ($request->store_id != "") {
            $product->update([
                $product->store_id                     = $request->store_id
            ]);
        }
        toastr()->success('تم التعديل بنجاح');
        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = Product::findOrFail($request->id);
        if ($product) {
            $product->delete();
            $product->is_Active = false;
            $product->save();
            Favorite::where("product_id", $request->id)->delete();
            Inspiration::where("link_id", $request->id)->delete();
        }

        toastr()->error('تم حذف المنتج بنجاح');
        return redirect('admin/product');
    }
}
