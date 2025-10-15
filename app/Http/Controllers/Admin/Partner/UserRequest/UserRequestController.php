<?php

namespace App\Http\Controllers\Admin\Partner\UserRequest;

use App\Models\Client;
use App\Models\Product;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest\StoreUserRequestRequest;

class UserRequestController extends Controller
{
    public function index()
    {
        $userRequests = UserRequest::with(['product', 'client'])->get();
        return view('admin.partner.user-request.index', compact('userRequests'));
    }

    public function create()
    {
        $clients = Client::where(['type' => 0])->get(['id', 'name']);
        $products = Product::get(['id', 'name']);
        return view('admin.partner.user-request.create', compact('clients', 'products'));
    }

    public function store(StoreUserRequestRequest $request)
    {
        UserRequest::create([
            'original_quantity' => Product::find($request->product_id)->original_quantity,
            'status' => UserRequest::REQUEST_STATUS['pending'],
        ] + $request->validated());
        toastr()->success('تمت الاضافة بنجاح');
        return redirect()->back();
    }

    public function destroy(UserRequest $userRequest)
    {
        $userRequest->delete();
        toastr()->success('تمت الحذف بنجاح');
        return redirect()->back();
    }
}
