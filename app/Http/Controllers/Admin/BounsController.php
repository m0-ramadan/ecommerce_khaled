<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bouns;
use App\Traits\UploadFileTrait;
use Illuminate\Http\Request;

class BounsController extends Controller
{
    use UploadFileTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bonuses = Bouns::get();
        return view('admin.bonuses.index',compact('bonuses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.bonuses.add');
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
            'bonus'                 => 'required',
            'point'                 => 'required',
        ]);

        $bonus = new Bouns;
        $bonus->start_bouns               = $request->start_bonus;
        $bonus->end_bonus                 = $request->end_bonus;
        $bonus->point                     = $request->point;
        $bonus->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/bonuses');
        //$blog->title     =['en'=>$request->title,'ar'=>$request->title_ar,'it'=>$request->title_it];

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
        $bonuses = Bouns::findOrFail($request->id);
        $bonuses->update([
            $bonuses->start_bouns                 = $request->start_bonus,
            $bonuses->end_bonus                   = $request->end_bonus,
            $bonuses->point                       = $request->point,
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
    public function destroy($id)
    {
        $bonus = Bouns::findOrFail($id)->delete();
        toastr()->error('تم الحذف بنجاح');
        return back();
    }
}
