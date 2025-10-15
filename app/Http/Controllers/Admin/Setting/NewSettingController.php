<?php

namespace App\Http\Controllers\Admin\Setting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Setting\UpdateNewSettingRequest;
use App\Models\NewSetting;
use App\Traits\UploadFileTrait;
use Illuminate\Http\Request;

class NewSettingController extends Controller
{
    use UploadFileTrait;

    public function index()
    {
        $settings = NewSetting::get();
        return view('admin.settings.new-setting', compact('settings'));
    }

    public function update(NewSetting $newSetting, UpdateNewSettingRequest $request)
    {
        $image = $request->has('image') ? $this->generalUploadFile(NewSetting::UPLOAD_PATH, $request->image) : $newSetting->value;
        $newSetting->update(['value' => $image] + $request->validated());
        return redirect()->back()->with(['success' => 'Image uploaded successfully.']);
    }
}
