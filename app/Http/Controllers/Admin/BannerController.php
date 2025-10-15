<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Traits\UploadFileTrait;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    use UploadFileTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::get();
        return view('admin.banners.index',compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.banners.add');
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
            'image_ar'               => 'required|image',
            'image_en'               => 'required|image',
            'image_it'               => 'required|image',
            
            'image_mop_ar'               => 'required|image',
            'image_mop_en'               => 'required|image',
            'image_mop_it'               => 'required|image',

        ]);
        
        $image['ar'] = $this->uploadFile('banners',$request->image_ar);
        $image['en'] = $this->uploadFile('banners',$request->image_en);
        $image['it'] = $this->uploadFile('banners',$request->image_it);
        
        $image_mop['ar'] = $this->uploadFile('banners',$request->image_mop_ar);
        $image_mop['en'] = $this->uploadFile('banners',$request->image_mop_en);
        $image_mop['it'] = $this->uploadFile('banners',$request->image_mop_it);
        
        Banner::create([
            'image' => ($image),
            'image_mop' => ($image_mop),
        ]);

        // if ($request->hasFile('image')){
        //     $fileNameToStore = $this->uploadFile('banners',$request->image);
        // }else {
        //     $fileNameToStore = 'noimage.jpg';
        // }

        // if ($request->hasFile('image_mop')){
        //     $fileNameToStoreMop = $this->uploadFile('banners',$request->image_mop);
        // }else {
        //     $fileNameToStoreMop = 'noimage.jpg';
        // }

        // $banner = new Banner;
        // $banner->image = $fileNameToStore;
        // $banner->image_mop = $fileNameToStoreMop;
        // $banner->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect(url('admin/banners'));
    }

    public function update(Request $request, $id)
    {
         $banner = Banner::findOrFail($id);
         $validatedData = $request->validate([
            'image_ar'               => 'nullable|image',
            'image_en'               => 'nullable|image',
            'image_it'               => 'nullable|image',
            
            'image_mop_ar'               => 'nullable|image',
            'image_mop_en'               => 'nullable|image',
            'image_mop_it'               => 'nullable|image',
        ]);
    
        $image['ar'] = $request->hasFile('image_ar') ? $this->uploadFile('banners', $request->file('image_ar')) : $banner->getTranslation('image', 'ar');
        $image['en'] = $request->hasFile('image_en') ? $this->uploadFile('banners', $request->file('image_en')) : $banner->getTranslation('image', 'en');
        $image['it'] = $request->hasFile('image_it') ? $this->uploadFile('banners', $request->file('image_it')) : $banner->getTranslation('image', 'it');
    
        $image_mop['ar'] = $request->hasFile('image_mop_ar') ? $this->uploadFile('banners', $request->file('image_mop_ar')) : $banner->getTranslation('image_mop', 'ar');
        $image_mop['en'] = $request->hasFile('image_mop_en') ? $this->uploadFile('banners', $request->file('image_mop_en')) : $banner->getTranslation('image_mop', 'en');
        $image_mop['it'] = $request->hasFile('image_mop_it') ? $this->uploadFile('banners', $request->file('image_mop_it')) : $banner->getTranslation('image_mop', 'it');
    
        $banner->update([
            'image' => $image,
            'image_mop' => $image_mop,
        ]);
    
        toastr()->success('تم التحديث بنجاح');
        return redirect(url('admin/banners'));
    
        // try{
        //     $banner = Banner::findOrFail($id);
        //     if ($request->hasfile('image')) {
        //         $filepath = $this->uploadFile('banners',$request->image);
        //         $banner->update([
        //             'image'   => $filepath,
        //         ]);
        //     }
        //     if ($request->hasfile('image_mop')) {
        //         $filepath = $this->uploadFile('banners',$request->image_mop);
        //         $banner->update([
        //             'image_mop'   => $filepath,
        //         ]);
        //     }
        // }catch (\Exception $e){
        //     return redirect()->back()->withErrors(['error'=>$e->getMessage()]);
        // }
        // toastr()->success('تم التعديل بنجاح');
        // return redirect(url('admin/banners'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $banner = Banner::findOrFail($request->id)->delete();
        toastr()->error('تم الحذف بنجاح');
        return redirect(url('admin/banners'));
    }
}
