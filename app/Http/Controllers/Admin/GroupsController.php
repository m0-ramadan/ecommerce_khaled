<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class GroupsController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::where("type",0)->get();
        return view('admin.groups.index',compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.groups.add');
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
            'image'               => 'required',
        ]);


        if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('payments',$request->image);
        }else {
            $fileNameToStore = 'noimage.jpg';
        }

        $payment = new Payment;
        $payment->image = $fileNameToStore;
        $payment->link  = $request->link;
        $payment->type  = 0;
        $payment->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect(url('admin/groups'));
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
        try{
            $payment = Payment::findOrFail($id);
            $payment->update([
                'link'              => $request->link,
            ]);
            if ($request->hasfile('image')) {
                $filepath = $this->uploadFile('groups',$request->image);
                $payment->update([
                    'image'   => $filepath,
                ]);
            }
        }catch (\Exception $e){
            return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
        }
        toastr()->success('تم التعديل بنجاح');
        return redirect(url('admin/groups'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $banner = Payment::findOrFail($request->id)->delete();
        toastr()->error('تم الحذف بنجاح');
        return redirect(url('admin/groups'));
    }
}
