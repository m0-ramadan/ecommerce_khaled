<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CountriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $countries = Country::where(['is_active'=>1])->get();
        return view('admin.countries.index',compact(['countries']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.countries.add');
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
            'name_en'                 => 'required',
            'name_it'                 => 'required',
            'country_prefix' => 'required',
        ]);

        $country = Country::create([
            'name' => ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it],
            'phone_prefix' => $request->country_prefix,
            'country_ref_code' => $request->name_en,
        ]);
        // $category = new Country;
        // $category->name                = ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it];
        // $category->save();
        toastr()->success('تمت الاضافة بنجاح');

        return redirect('admin/countries');

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

            $category = Country::findOrFail($request->id);
            $category->update([
                $category->name                 =  ['en'=>$request->name_en,'ar'=>$request->name_ar,'it'=>$request->name_it]
            ]);
  if( $request->store_id!=""){
      $category->update([ $category->store_id=  $request->store_id]);
  }

        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/countries');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {


    Country::find($request->id)->delete();

            toastr()->error('تم الحذف بنجاح');


        return redirect('admin/countries');
    }


}
