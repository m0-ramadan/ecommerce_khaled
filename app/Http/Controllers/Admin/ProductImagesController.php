<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Traits\UploadFileTrait;

class ProductImagesController extends Controller
{
    use UploadFileTrait;
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        
        $features= Image::where('product_id',$id)->with('product')->get();
     
        return view('admin.productimages.index',compact('features','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        
         return view('admin.productimages.add',compact('id'));
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
        } else {
           $imageNameToStore=NULL;
        }
       
        $feature=Image::create([
             'titletxt'    =>$request->titletxt,
            'alttxt'      =>$request->alttxt,
            'product_id'  =>$request->product_id,
            'src'         =>$imageNameToStore
             ]);


            
        toastr()->success('تم اضافة بنجاح');
       return redirect('admin/productimages/'.$request->product_id);
       

        
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
   

        $product = Image::findOrFail($request->id);

 
        if ($request->hasFile('image')) {
            $imageNameToStore = $this->uploadImage('product', $request->image);
        }

 
        $product->update([
             $product->titletxt                      = $request->titletxt,
            $product->alttxt                        = $request->alttxt,
            $product->src                         =  ($request->has('image')) ? $imageNameToStore : $product->src,
        ]);

 
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
        
            $feature= Image::findorfail($request->id);
            if($feature){
                 $feature->delete();
            }
   
         toastr()->error('تم الحذف بنجاح');
        return redirect('admin/productimages/'.$request->product_id);
      
    }
}