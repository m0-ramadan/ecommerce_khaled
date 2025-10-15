<?php

namespace App\Http\Controllers\Admin\Partner\Obligation;

use App\Models\Client;
use App\Models\Obligations;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Obligation\StoreObligationRequest;
use App\Traits\UploadFileTrait;

class ObligationController extends Controller
{
    use UploadFileTrait;

    public function index()
    {
        $obligations = Obligations::with(['client'])->get();
        return view('admin.partner.obligation.index', compact('obligations'));
    }

    public function create()
    {
        $clients = Client::where(['type' => 0])->get(['id', 'name']);
        return view('admin.partner.obligation.create', compact('clients'));
    }

    public function store(StoreObligationRequest $request)
    {
        $file = $this->uploadFile(Obligations::PATH, $request->file);
        Obligations::create(['file' => $file] + $request->validated());
        toastr()->success('تمت الاضافة بنجاح');
        return redirect()->back();
    }

    public function destroy(Obligations $obligation)
    {
        $obligation->delete();
        toastr()->success('تمت الحذف بنجاح');
        return redirect()->back();
    }
}
