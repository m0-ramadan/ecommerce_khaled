<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductFeature;


class ProductFeaturesController extends Controller
{
    
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        
        $features= ProductFeature::where('product_id',$id)->with('product')->get();
     
        return view('admin.productfeatures.index',compact('features','id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        
         return view('admin.productfeatures.add',compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       
       
       
        $feature=ProductFeature::create([
            
            'name'=>   ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it],
            'description' =>  ['en'=>$request->description_en,'ar'=>$request->description_ar,'it'=>$request->description_it],
            'product_id' => $request->product_id
        
            ]);
            
        toastr()->success('تم اضافة بنجاح');
       return redirect('admin/productfeatures/'.$request->product_id);
       

        
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
      
     
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
       $validatedData = $request->validate([
               'id'=>  'required |exists:product_features,id'
            
            ]);
            
            $feature= ProductFeature::findorfail($request->id);
            if($feature){
                 $feature->delete();
            }
   
         toastr()->error('تم الحذف بنجاح');
        return redirect('admin/productfeatures/'.$request->product_id);
      
    }
}