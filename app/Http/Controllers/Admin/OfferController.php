<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class OfferController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $offers = Offer::get();
        return view('admin.offers.index',compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.offers.add');
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
            'title_ar'      => 'required',
            'details_ar'    => 'required',
            'discount'      => 'required',
        ]);
        
        $image['ar'] = $this->uploadFile('offers',$request->image_ar);
        $image['en'] = $this->uploadFile('offers',$request->image_en);
        $image['it'] = $this->uploadFile('offers',$request->image_it);

        
        $offer = new Offer;
        $offer->title           = ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it];
         $offer->title2           = ['en'=>$request->title2_en,'ar'=>$request->title2_ar,'it'=>$request->title2_it];
         $offer->title3           = ['en'=>$request->title3_en,'ar'=>$request->title3_ar,'it'=>$request->title3_it];
          $offer->title4           = ['en'=>$request->title4_en,'ar'=>$request->title4_ar,'it'=>$request->title4_it];
           $offer->title5           = ['en'=>$request->title5_en,'ar'=>$request->title5_ar,'it'=>$request->title5_it];
        $offer->content         = ['en'=>$request->body_en,'ar'=>$request->body_ar,'it'=>$request->body_it];
        $offer->details         = ['en'=>$request->details_en,'ar'=>$request->details_ar,'it'=>$request->details_it];
        $offer->discount        = $request->discount;
        $offer->image           = $image;
        $offer->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/offers');
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
        $offer = Offer::findOrFail($request->id);
        
        
        $image['ar'] = $request->hasFile('image_ar') ? $this->uploadFile('offers', $request->file('image_ar')) : $offer->getTranslation('image', 'ar');
        $image['en'] = $request->hasFile('image_en') ? $this->uploadFile('offers', $request->file('image_en')) : $offer->getTranslation('image', 'en');
        $image['it'] = $request->hasFile('image_it') ? $this->uploadFile('offers', $request->file('image_it')) : $offer->getTranslation('image', 'it');
        $offer->update(['image' => $image]);
        
        
        $imagemob['ar'] = $request->hasFile('mob_image_ar') ? $this->uploadFile('offers', $request->file('mob_image_ar')) : $offer->getTranslation('mob_image', 'ar');
        $imagemob['en'] = $request->hasFile('mob_image_en') ? $this->uploadFile('offers', $request->file('mob_image_en')) : $offer->getTranslation('mob_image', 'en');
        $imagemob['it'] = $request->hasFile('mob_image_it') ? $this->uploadFile('offers', $request->file('mob_image_it')) : $offer->getTranslation('mob_image', 'it');
        
         $offer->update(['mob_image' => $imagemob]);
    
        $offer->update([
            $offer->title                   = ['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it],
            $offer->title2                  = ['en'=>$request->title2_en,'ar'=>$request->title2_ar,'it'=>$request->title2_it],
            $offer->title3                  = ['en'=>$request->title3_en,'ar'=>$request->title3_ar,'it'=>$request->title3_it],
            $offer->title4                  = ['en'=>$request->title4_en,'ar'=>$request->title4_ar,'it'=>$request->title4_it],
            $offer->title5                  = ['en'=>$request->title5_en,'ar'=>$request->title5_ar,'it'=>$request->title5_it],
            $offer->content                 = ['en'=>$request->body_en,'ar'=>$request->body_ar,'it'=>$request->body_it],
            $offer->details                 = ['en'=>$request->details_en,'ar'=>$request->details_ar,'it'=>$request->details_it],
            $offer->discount                =  $request->discount,
        ]);
        
        toastr()->success('تم التعديل بنجاح');
        return redirect('admin/offers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $offer = Offer::findOrFail($id)->delete();
        toastr()->error('تم الحذف بنجاح');
        return back();
    }
}
