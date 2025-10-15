<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorSettingController extends Controller
{
    public function index()
    {
        $settings = Store::where('id', Auth::guard('vendors')->user()->id)->first();
        return view('vendor.settings.index', compact('settings'));
    }

    public function doSetting(Request $request)
    {

        $validatedData = $request->validate([
            'facebook' => 'required',
            'phone' => 'required',
            'replacement' => 'required',
            'judgments' => 'required',
            'name' => 'required',
        ]);
        $setting = Store::where('id', Auth::guard('vendors')->user()->id)->first();
        if ($request->hasfile('image')) {
            $filepath = $this->uploadFile('settings', $request->image);
            $setting->update([
                'image' => $filepath,
            ]);
        }

        if ($request->password) {
            $setting->update([
                'password' => bcrypt($request->password),
            ]);
        }
        $setting->facebook = $request->facebook;
        $setting->twitter = $request->twitter;
        $setting->instagram = $request->instagram;
        $setting->phone = $request->phone;
        $setting->address = $request->address;
        $setting->location = $request->location;
        $setting->details = $request->details;
        $setting->replacement = $request->replacement;
        $setting->judgments = $request->judgments;
        $setting->name = $request->name;
        $setting->save();
        // toastr()->success('success');
        // return back();
        return back()->with('success', trans('messages.settings_updated'));

    }
}