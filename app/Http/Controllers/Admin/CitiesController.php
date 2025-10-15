<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
          $countries = Country::get();
        $cities =  City::where(['is_active'=>1])->get();
        return view('admin.cities.index',compact(['cities','countries']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::get();
        return view('admin.cities.add',compact('countries'));
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
            'country_id'          => 'required',
            'name_en'                => 'required',
        ]);
        

        $category = new City;
        $category->name                = ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it];
        $category->countries_id            =$request->country_id;
    
        
        $category->save();
        toastr()->success('تمت الاضافة بنجاح');

        return redirect('admin/cities');

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
        
            $category = City::findOrFail($request->id);

            
            $category->update([
                $category->name                 =  ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it],
                
            ]);
  if( $request->country_id!=""){
      $category->update([ $category->countries_id=  $request->country_id]);
  }

        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/cities');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        
    City::find($request->id)->delete();
     
            toastr()->error('تم الحذف بنجاح');
       

        return redirect('admin/cities');
    }

}
