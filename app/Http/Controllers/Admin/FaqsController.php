<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faqs;
use Illuminate\Http\Request;
use Auth;

class FaqsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faqs = Faqs::get();
        return view('admin.faqs.index',compact(['faqs']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
        return view('admin.faqs.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
       
        $product = new Faqs;
        $product->questions                            = ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it];
        $product->answer                         = ['en'=>$request->details_en,'ar'=>$request->details_ar,'it'=>$request->details_it];
        $product->save();
        toastr()->success('تم اضافة المنتج بنجاح');
        return redirect('admin/faqs');
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
       $product = Faqs::findOrFail($request->id);
       
     
        $product->update([
            $product->questions                            = ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it],
            $product->answer                         = ['en'=>$request->details_en,'ar'=>$request->details_ar,'it'=>$request->details_it],
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
        $product = Faqs::findOrFail($request->id);
        if ($product)
        {
           $product->delete(); //returns true/false

        }
        toastr()->error('تم حذف المنتج بنجاح');
        return redirect('admin/faqs');
    }
}
