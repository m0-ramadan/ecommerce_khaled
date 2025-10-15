<?php

namespace App\Http\Controllers\Admin;

use App\Models\Title;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Notification;

class TitleController extends Controller
{
    use UploadFileTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $titles = Title::get();
        return view('admin.titles.index', compact('titles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.titles.add');
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
        ]);


        $titles = new Title;
        $titles->title                 = ['en' => $request->title_en, 'ar' => $request->title_ar, 'it' => $request->title_it];
        $titles->page_name             = $request->page_name;
        $titles->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/titles');
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
        $titles = Title::findOrFail($request->id);

        if ($request->hasfile('title_en')) {
            $title_en_image = $this->uploadFile('titles', $request->title_en);
            $titles->update([
                $titles->title =  ['en' => $title_en_image, 'ar' => $titles->getTranslation('title', 'ar'), 'it' =>  $titles->getTranslation('title', 'it')],
            ]);
        } else if ($request->hasfile('title_ar')) {
            $title_ar_image = $this->uploadFile('titles', $request->title_ar);
            $titles->update([
                $titles->title =  ['en' => $titles->getTranslation('title', 'en'), 'ar' =>  $title_ar_image, 'it' =>  $titles->getTranslation('title', 'it')],
            ]);
        } else if ($request->hasfile('title_it')) {
            $title_it_image = $this->uploadFile('titles', $request->title_it);
            $titles->update([
                $titles->title =  ['en' => $titles->getTranslation('title', 'en'), 'ar' =>  $titles->getTranslation('title', 'ar'), 'it' =>  $title_it_image],
            ]);
        } else {
            $titles->update([
                $titles->title =  ['en' => $request->title_en, 'ar' => $request->title_ar, 'it' => $request->title_it],
                $titles->page_name =  $titles->page_name,
            ]);
        }

        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/titles');
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
