<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Traits\UploadFileTrait;


class ContactsController extends Controller
{
        use UploadFileTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contacts = Contact::get();
        return view('admin.settings.index',compact('contacts'));
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
    public function edit(Request $request ,$id)
    {
        $this->validate($request, [
            'app_title'                    => 'required',
            'facebook'                     => 'required',
            'twitter'                      => 'required',
            'instagram'                    => 'required',
            'phone'                        => 'required',
            'address'                      => 'required',
            'location'                     => 'required',
            'details'                      => 'required',
            'replacement'                  => 'required',
            'judgments'                    => 'required',
            'app_logo'                     => 'image|mimes:jpg,jpeg,png,gif|max:100000',
        ]);

        $settings = Contact::find($id);
        if ($request->hasFile('image')){
            $fileNameToStore = $this->uploadFile('settings',$request->image);
            Contact::where('id', $id)
                ->update([
                    'image' => $fileNameToStore,
                ]);
        }else {
            $fileNameToStore = 'noimage.jpg';
        }
        $settings->facebook                     =$request->facebook;
        $settings->twitter                      =$request->twitter;
        $settings->instagram                    =$request->instagram;
        $settings->phone                        =$request->phone;
        $settings->address                      =$request->address;
        $settings->location                     =$request->location;
        $settings->details                      =$request->details;
        $settings->replacement                  =$request->replacement;
        $settings->judgments                    =$request->judgments;
        $settings->image                        = $fileNameToStore;
        $settings->save();
        return redirect()->back()->with('success', 'Data Updated Successfully');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
