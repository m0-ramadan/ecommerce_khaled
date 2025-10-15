<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Construction;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class ConstructionController extends Controller
{
        use UploadFileTrait;

    public function constriction()
    {
        $construction = Construction::first();
        return view('admin.constructions.index',compact('construction'));
    }

    public function constructionSave(Request $request)
    {
        $validatedData = $request->validate([
            'title_ar'                      => 'required',
            'link'                          => 'required',
        ]);
            $construction = Construction::first();
            if ($request->hasfile('image')) {
                $filepath = $this->uploadFile('construction',$request->image);
                $construction->update([
                    'image'   => $filepath,
                ]);
            }


            $construction = Construction::first();
                $construction->title                     =['en'=>$request->title_en,'ar'=>$request->title_ar,'it'=>$request->title_it];
                $construction->link                      =$request->link;
            $construction->save();
        toastr()->success('success');
        return redirect('admin/constructions');
    }
}
