<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupons;

class couponController extends Controller
{
     public function index()
    {
        $coupons = Coupons::all();
        return view('admin.coupons.index', compact('coupons'));
    }
    
    public function create()
    {
        return view('admin.coupons.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code',
            'mount' => 'required|numeric|max:99',
            'start_date' => 'nullable|string',
            'num_use_user' => 'nullable|string',
            'expiry_date' => 'nullable|date',
            'status' => 'required|integer',
            'num_times' => 'required|string',
        ]);
    
        try {
            Coupons::create($request->all());
            return redirect()->route('coupons.index')->with('success', 'تم إنشاء الكوبون بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'حدث خطأ أثناء إنشاء الكوبون: ' . $e->getMessage());
        }
    }

    
    public function edit($id)
    {
        $coupon = Coupons::findOrFail($id);
        return view('admin.coupons.edit', compact('coupon'));
    }
        public function show($id)
    {
        abort('404');
    }
    public function update(Request $request, $id)
    {
        $coupon = Coupons::findOrFail($id);
        $coupon->update($request->all());
        return redirect()->route('coupons.index')->with('success', 'تم تحديث الكوبون بنجاح.');
    }
    
    public function destroy($id)
    {
        $coupon = Coupons::findOrFail($id);
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'تم حذف الكوبون بنجاح.');
    }

}
