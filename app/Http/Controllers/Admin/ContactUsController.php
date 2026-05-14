<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 
use App\Models\ContactUs;

class ContactUsController extends Controller
{
   
    public function index(){
        $contacts= ContactUs::latest()->get();
        
        return view('Admin.contact.index',compact('contacts'));
       
    }

    public function store(Request $request){
        ContactUs::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'email',
            'company',
            'message',
        ]));

         toastr()->success('تمت الإضافة بنجاح');
       return redirect()->route('admin.contactus.index');
    }

    public function update(Request $request){
         

        $country= ContactUs::find($request->id);
        
        
        $country->update($request->only([
            'first_name',
            'last_name',
            'phone',
            'email',
            'company',
            'message',
        ]));

           toastr()->success('تم التعديل بنجاح');
       return redirect()->route('admin.contactus.index');


    }

    public function destroy($id){

        $country=ContactUs::find($id);
        if($country){
            $country->delete();

            return redirect()->route('admin.contactus.index')->with('success','تم حذف الرسالة بنجاح');
        }
            toastr()->error('الرسالة غير موجودة');
            return redirect()->route('admin.contactus.index');
         
    }

}
