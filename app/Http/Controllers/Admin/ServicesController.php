<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Traits\UploadFileTrait;


class ServicesController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::get();
        return view('admin.services.index',compact(['services']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.services.add');
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
            'title_ar'                 => 'required',
            'content_ar'               => 'required',
        ]);



                    if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('services',$request->image);
        }else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }

        $service = new Service;
        $service->title                = ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it];
        $service->content              = ['en'=>$request->content_en,'ar'=>$request->content_ar,'it'=>$request->content_it];
        // $service->details              = ['en'=>$request->details_en,'ar'=>$request->details_ar,'it'=>$request->details_it];
         $service->img                 = $fileNameToStore;
        $service->save();
        toastr()->success('تمت الاضافة بنجاح');

        return redirect('admin/services');

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
            $service = Service::findOrFail($request->id);

        if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('services',$request->image);
        } 

            $service->update([
                $service->title                  =  ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it],
                $service->content                =  ['en'=>$request->content_en,'ar'=>$request->content_ar,'it'=>$request->content_it],
                $service->img                 = $fileNameToStore ?? $service->img  ,
            ]);
        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/services');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
    }
}
