<?php

namespace App\Http\Controllers\Front;
use App\Models\Clients;
use App\Models\Slider;
use App\Models\Contact;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shipment;
use App\Models\Subscribe;
use App\Models\ShipmentPrice;
use App\Models\Service;
use App\Models\ShipmentCompany;
use App\Models\Faq;
use App\Http\Services\ClickPayService;
use App\Http\Services\AjexLogisticsService;
use App\Http\Interfaces\Front\FrontInterface;
use App\Http\Traits\SendNotificationTrait;
use Clickpaysa\Laravel_package\Facades\paypage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontController extends Controller
{



 public function index()
    {
      $companies=ShipmentCompany::where('status',1)->get();
      $logistics=Service::whereNotNull('parent_id')->get();
      $sliders= Slider::where('type',0)->orderBy('item_order')->get();
      $mobsliders= Slider::where('type',1)->get();
      $shipments=Shipment::count();
      $users= Clients::count();

        return view('index',compact('companies','logistics','sliders','mobsliders','shipments','users'));
    }


    public function  accounts(){
        return view('Front.Pages.accounts');
   }

    public function  privacyPolicy(){
         return view('Front.Pages.privacypolicy');
    }
    
        public function  privacy_tranfer(){
         return view('Front.Pages.privacy_tranfer');
    }
    
    
    
    public function termsandconditions(){
       return view('Front.Pages.termsandconditions');
    }
    public function contact(){
        return view('Front.Pages.contact');
    }

    public function storeContact(Request $request){
       


        $contact= Contact::create([
            'name'=>$request->name,
            'phone'=>$request->phone,
            'email'=>$request->email,
            'subject' => $request->subject,
            'message' =>$request->message

        ]);


        return redirect()->back()->with('message', ' تم ارسال  رسالتك  بنجاح ');


    }



    public function about(){
        $shipments=Shipment::count();
        $users= Clients::count();
        return view('Front.Pages.aboutUs',compact('shipments','users'));
    }

    public function faqs(){
        $faqs=Faq::orderBy('item_order')->get();
        return view('Front.Pages.questions',compact('faqs'));
    }

  
}
