<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\PromoCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;


use Illuminate\Http\Request;

class EmployeeUserController extends Controller
{
    // public function archiveProduct()
    // {
    //     $archiveProducts = Product::where(['is_active'=>false])->get();
    //     return view('admin.archives.product',compact('archiveProducts'));
    // }

    // public function archiveRestore(Request $request)
    // {
    //     $restoreProduct = Product::findOrFail($request->id);
    //     $cate = Category::where([
    //         ['id',$restoreProduct->category_id],
    //         ['is_active',true]
    //     ])->first();
    //     $sub = SubCategory::where([
    //         ['id',$restoreProduct->sub_category_id],
    //         ['is_active',true]
    //     ])->first();
    //     if($cate)
    //     {
    //         $restoreProduct->is_active=true;
    //         $restoreProduct->save();
    //         toastr()->success('تم استعادة المنتج بنجاح');
    //     }else{
    //         toastr()->error('هذا المنتج غير تابع لقسم رئيسي او قسم فرعي مفعل يرجي التاكد من القسم التابع له هذا المنتج');
    //     }
    //     return back();
    // }

    public function index()
    {
        $employees = Client::where('type','!=', 1)->get(); 
        return view('admin.employees.index',compact('employees'));
    }
    
        public function show($id)
    {
        $employee = Client::find($id);
        return view('admin.employees.add',compact('employee'));

     }
    
    public function create()
    {
        
     }
     
         public function store(Request $request)
    {
        
        Client::create([
             'name' => $request->name,
             'email'=> $request->email,
             'password' => Hash::make($request->password),
            'capital' => $request->capital,
                 'percentage' => $request->percentage,
                 'profit' => $request->profit,
                 'residual' => $request->residual,
                'type' => $request->type,

            ]);
            return redirect()->route('employees.index');
     }

     
    
    
    public function edit(Request $request)
    {
     }

     
        public function update( $id , Request $request)
    {
        
      
           $user = Client::find( $id);
            
           $user->update([
                 'name' => $request->name,
                 'capital' => $request->capital,
                 'percentage' => $request->percentage,
                 'profit' => $request->profit,
                 'residual' => $request->residual,
                 'email'=> $request->email,
                  'phone' => $request->phone,
                  'type'  => $request->type,
                ]);
                if(isset($request->password)){
                          $user->password = Hash::make($request->password);
                          $user->save();
                }
            
                        return redirect()->back();

    }
    

    public function promoCodes ()
    {
        $codes = PromoCode::where(['status' => "1", 'main' => "0"])->get();
        $clients = Client::where('type', 3)->get();
        return view('admin.employees.promo-code',compact('codes', 'clients'));
    }
    
    public function updatePromoCode (Request $request, PromoCode $promoCode)
    {
        $validated = $request->validate([
            'code' => 'required',
            'value' => 'required',
            'start_date' => 'required',
            'end' => 'required',
            'counts' => 'required',
            'client_id' => 'required',
        ]);
        $promoCode->update([
            'status' => "1",
            'main' => "0",
        ] + $validated);
        toastr()->success('تم التعديل بنجاح.');
        return redirect()->back();
    }
    
    public function storePromoCode (Request $request)
    {
        $validated = $request->validate([
            'code' => 'required',
            'value' => 'required',
            'start_date' => 'required',
            'end' => 'required',
            'counts' => 'required',
            'client_id' => 'required',
        ]);
        PromoCode::create([
            'status' => "1",
            'main' => "0",
        ] + $validated);
        toastr()->success('تم الانشاء بنجاح.');
        return redirect()->back();
    }
    
    public function destroyPromoCode (PromoCode $promoCode)
    {
        $promoCode->update([
            'status' => "0"
        ]);
        toastr()->success('تم التعطيل بنجاح.');
        return redirect()->back();
    }



}
