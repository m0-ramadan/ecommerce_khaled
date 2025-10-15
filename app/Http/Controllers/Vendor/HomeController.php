<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class HomeController extends Controller
{
    public function vendorLogin()
    {
        return view('vendor.login');
    }

    // public function doLogin(Request $request)
    // {
    //     $this->validate($request, [
    //         'phone' => 'required',
    //         'password' => 'required'
    //     ]);
    //     if (Auth::guard('vendors')->attempt(['phone' => request('phone'), 'password' => request('password'), 'is_active' => 1])) {
    //         toastr()->success('تــم تسجيــل الدخول بنجــاح');
    //         return redirect('Vendor-profile');
    //     } else {
    //         toastr()->error('يوجـــد مشكلة في البيــانات يرجي التأكد والمحـاولة مرة أخري');
    //         return back()->withInput($request->only('phone'));
    //     }
    // }

    // public function logout()
    // {
    //     Auth::logout();
    //     toastr()->success('تـــم تسجيـل الخـروج بنجــــاح');
    //     return redirect('vendor-login');
    // }

    public function doLogin(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'password' => 'required'
        ]);

        if (Auth::guard('vendors')->attempt(['phone' => $request->phone, 'password' => $request->password, 'is_active' => 1])) {
            return redirect('Vendor-profile')->with('success', trans('messages.login_success'));
        } else {
            return back()->with('error', trans('messages.login_failed'))->withInput($request->only('phone'));
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('vendor-login')->with('success', trans('messages.logout_success'));
    }

}