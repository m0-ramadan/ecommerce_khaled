<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;

class VendorController extends Controller
{
    public function vendorLogin()
    {
        return view('admin.vendors.login');
    }

    public function doLogin(Request $request)
    {
        if (Auth::guard('vendors')->attempt(['phone'=>request('phone'),'password'=>request('password')])){
            return redirect('Vendor-profile');
        }
    }

    public function profile()
    {

    }
}
