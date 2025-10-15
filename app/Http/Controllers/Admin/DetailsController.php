<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Detail;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class DetailsController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $details = Detail::get();
        return view('admin.details.index',compact('details'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      return view('admin.details.add');
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
            'description_ar'               => 'required',

        ]);
        $fileNameToStore = $this->uploadFile('Details',$request->image);

        $detail = new Detail;
        $detail->title                = ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it];
        $detail->description          = ['en'=>$request->description_en,'ar'=>$request->description_ar,'it'=>$request->description_it];
        $detail->image                = $fileNameToStore;
        $detail->save();
        toastr()->success('تمت الاضافة بنجاح');

        return redirect('admin/details');
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


            $detail = Detail::findOrFail($id);
            if ($request->hasfile('image')) {
                $filepath = $this->uploadFile('details',$request->image);
                $detail->update([
                    'image'   => $filepath,
                ]);

            }

         $detail->update([
              $detail->title                = ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it],
              $detail->description          = ['en'=>$request->description_en,'ar'=>$request->description_ar,'it'=>$request->description_it],
             ]);
        toastr()->success('تم التعديل بنجاح');
        return redirect(url('admin/details'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
