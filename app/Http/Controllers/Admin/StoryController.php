<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Story;
use Illuminate\Http\Request;

use App\Traits\UploadFileTrait;

class StoryController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stories = Story::get();
        return view('admin.story.index',compact('stories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.story.add');
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
            'title_ar'          => 'required',
            'body_ar'           => 'required',
            'image'             => 'required',
        ]);

        $data = [];
        if ($request->hasfile('image')) {
            foreach ($request->file('image') as $file) {
                $data[] = $this->uploadFile('stories', $file);
            }
        }else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }
        $story = new Story;
        $story->title           = ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it];
        $story->content         = ['en'=>$request->body_en,'ar'=>$request->body_ar,'it'=>$request->body_it];
        $story->image           = implode(',', $data);
        $story->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/story');
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
        $story = Story::findOrFail($request->id);
        $data = [];
        if ($request->hasfile('image')) {
            foreach ($request->file('image') as $file) {
                $data[] = $this->uploadFile('stories', $file);
            }
            Story::where('id', $id)
                ->update([
                    'image'   => implode(',', $data),
                ]);
        }else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }
        $story->update([
            $story->title                           = ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it],
            $story->content                         = ['en'=>$request->content_en,'ar'=>$request->content_ar,'it'=>$request->content_it],
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
        $story = Story::findOrFail($id)->delete();
        toastr()->error('تم الحذف بنجاح');
        return back();
    }
}
