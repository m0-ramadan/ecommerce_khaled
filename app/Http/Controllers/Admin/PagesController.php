<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class PagesController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::get();
        return view('admin.pages.index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.add');
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
            'body_ar'                  => 'required',
            'type'                     => 'required',
        ]);
        if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('pages',$request->image);
        }else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }

        $pages = new Page;
        $pages->title                 = ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it];
        $pages->content               = ['en'=>$request->body_en,'ar'=>$request->body_ar,'it'=>$request->body_it];
        $pages->content_app               = ['en'=>$request->contact_app_en,'ar'=>$request->contact_app_ar,'it'=>$request->contact_app_it];
        $pages->image                 = $fileNameToStore;
        $pages->type                  = $request->type;
        $pages->footer                = 1;
        $pages->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/pages');
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
        $page = Page::findOrFail($request->id);


            if ($request->hasfile('image')) {
                $filepath = $this->uploadFile('pages',$request->image);
                $page->update([
                    'image'   => $filepath,
                ]);
            }

            if ($request->hasfile('bg_image')) {
                $filepath = $this->uploadFile('pages',$request->bg_image);
                $page->update([
                    'bg_image'   => $filepath,
                ]);
            }


            $page->update([
                $page->title                 =  ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it],
                $page->content               =  ['en'=>$request->body_en,'ar'=>$request->body_ar,'it'=>$request->body_it],
                $page->content_app         = ['en'=>$request->contact_app_en,'ar'=>$request->contact_app_ar,'it'=>$request->contact_app_it],
            ]);

        toastr()->success('تم التعديل بنجاح');
       return redirect('admin/pages');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pages = Page::findOrFail($id)->delete();
        toastr()->error('تم الحذف بنجاح');
        return back();
    }
}
