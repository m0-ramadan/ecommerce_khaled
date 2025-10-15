<?php

namespace App\Http\Controllers\Admin\SalesManagement;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use App\Models\EmployeesMessages;
use App\Models\EmployeesWallet;
use App\Models\Product;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Store;
     
     
     



use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class SalesManagementController extends Controller
{
    
    
    
    
    public function showMyCode()
    {
      $promoCode = PromoCode::find( auth()->user()->promo_code_id);
       return view('admin/other_users/sales_management/my_code' , compact('promoCode'));
    }





     
    public function showMyEmailAddressPage()
    {
        return view('admin/other_users/sales_management/my_email' );
    }





    public function storeEmailAddressMessage(Request $request)
    {
          $EmployeesMessages =  EmployeesMessages::create(
                 [
                     'name' => $request->name,
                     'address' => $request->address,
                     'message' => $request->message,
                     'user_id' => auth()->id(),
                 ]
                );
                
                if(!$EmployeesMessages){
                    return redirect()->back()->with('error' , '    لم يتم ارسال الرسالة ');
                }
                    return redirect()->back()->with('success' , 'لقد تم ارسال رسالتك بنجاح');

     }






    public function showEmployeesWallet(Request $request)
    {
      $EmployeesWallet =  EmployeesWallet::with('order.orderitem.product')->where('user_id' , auth()->id())->latest()->first();
     
         return view('admin/other_users/sales_management/my_wallet' , compact('EmployeesWallet') );

     }
     
     
     
     
     public function showProducts(){
         
         $products = Product::Active()->get();
        $categories = Category::get();
        $subcategories = SubCategory::get();
        $stores = Store::get();
         return view('admin/other_users/sales_management/show_products',compact(['products','categories','subcategories','stores']));
    }

 


}
