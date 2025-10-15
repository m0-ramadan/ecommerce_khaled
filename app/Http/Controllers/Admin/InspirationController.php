<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inspiration;
use App\Models\Product;
use App\Traits\UploadFileTrait;
use Illuminate\Http\Request;


class InspirationController extends Controller
{
    use UploadFileTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
//  $pps = Product::onlyTrashed()->get();
// foreach($pps as $pp){
//     echo "<br>".$pp->id;
//      $inspirations = Inspiration::where('link_id',$pp->id)->get();
//      foreach($inspirations as $inspiration)
//       if($inspiration->id){
//           echo "<br> inspirations-id".$inspiration->id;
//       }
// }
// dd("end");

        $inspirations = Inspiration::where('status',1)->get();
        return view('admin.inspiration.index',compact('inspirations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.inspiration.add');
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
            'image'                 => 'required',
            'url_link'              => 'required',
        ]);
        if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('inspiration',$request->image);
        }else {
            $fileNameToStore = 'images/zummXD2dvAtI.png';
        }
        $inspiration = new Inspiration;
        $inspiration->url_link              = 'https://taqiviolet.com/product-Details/'.$request->url_link;
        $inspiration->link_id               = $request->url_link;
         $inspiration->link_posh               = $request->location_h;
          $inspiration->link_posv               = $request->location_v;
           $inspiration->link_id1               = $request->url_link1;
            $inspiration->link_posh1               = $request->location_h1;
         $inspiration->link_posv1               = $request->location_v1;
          $inspiration->link_id2               = $request->url_link2;
           $inspiration->link_posh2               = $request->location_h2;
            $inspiration->link_posv2               = $request->location_v2;

        $inspiration->image                 = $fileNameToStore;
        $inspiration->type                  = $request->type;
        $inspiration->save();
        toastr()->success('تمت الاضافة بنجاح');
        return redirect('admin/inspiration');
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
        $inspiration = Inspiration::findOrFail($id);
        if ($request->hasfile('image')) {
            $filepath = $this->uploadFile('inspiration',$request->image);
            $inspiration->update([
                'image'   => $filepath,
            ]);
        }
        $inspiration->update([
            $inspiration->url_link                      = 'https://taqiviolet.com/product-Details/'.$request->url_link,
                    $inspiration->link_id               = $request->url_link,
         $inspiration->link_posh               = $request->location_h,
          $inspiration->link_posv               = $request->location_v,

           $inspiration->link_id1               = $request->url_link1,
            $inspiration->link_posh1               = $request->location_h1,
         $inspiration->link_posv1               = $request->location_v1,

          $inspiration->link_id2               = $request->url_link2,
           $inspiration->link_posh2               = $request->location_h2,
            $inspiration->link_posv2               = $request->location_v2,
       
        ]);
       
         if($request->type!=""){
                $inspiration->update([
            $inspiration->type= $request->type
               ]);
        }
        
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
         
       
         $inspiration = Inspiration::findOrFail($id);
          $inspirationstatus = Inspiration::where("id",$id)->first();
        //$inspiration = Inspiration::findOrFail($id)->delete();
        
        if ($inspiration&&$inspirationstatus->status==1)
        {
            $inspiration->status=false;
            $inspiration->save();
            toastr()->error('تم الحذف بنجاح');
        }
        else {
            $inspiration->status=true;
            $inspiration->save();
            toastr()->error('تم الأستراجع بنجاح');
        }
        
        return back();

    }
}
