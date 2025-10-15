<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

use App\Traits\UploadFileTrait;

class BlogController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::get();
        return view('admin.blog.index',compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     return view('admin.blog.add');
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
            'title_ar'               => 'required',
            'content_ar'             => 'required',
        ]);


        if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('blogs',$request->image);
        }else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }
        $blog = new Blog;
        $blog->title     =['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it];
        $blog->content   =['en'=>$request->content_en,'ar'=>$request->content_ar,'it'=>$request->content_it];
        $blog->image = $fileNameToStore;

        $blog->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/blog');
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
            $blog = Blog::findOrFail($request->id);

            if ($request->hasfile('image')) {
                $filepath = $this->uploadFile('blogs',$request->image);
                $blog->update([
                        'image'   => $filepath,
                    ]);
            }
            $blog->update([
                $blog->title            =  ['en'=>$request->title,'ar'=>$request->title_ar],
                $blog->content          =  ['en'=>$request->body,'ar'=>$request->content_ar],
            ]);

        }catch (\Exception $e){
            return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
        }
        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/blog');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $blog = Blog::findOrFail($request->id)->delete();
        toastr()->error('تم الحذف بنجاح');
        return redirect('admin/blog');
    }
}
