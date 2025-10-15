<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $supports = Support::get();
        return view('admin.messages.index',compact(['supports']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    public function update(Request $request, $id)
    {
        $message = Support::findOrFail($request->id);
        if ($message)
        {
            $message->status=true;
            $message->notes             = $request->notes;
            $message->save();
            return back();
        }
    }
    public function destroyAll()
    {
        $messageCount = Support::count();
        if ($messageCount > 0) {
            Support::truncate(); 
            toastr()->success('تم حذف كل الرسائل بنجاح.');
        } else {
            toastr()->error('لا يوجد رسائل.');
        }
    
        return back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $message = Support::findOrFail($request->id)->delete();
        toastr()->error('تم الحذف بنجاح');
        return back();
    }
}
