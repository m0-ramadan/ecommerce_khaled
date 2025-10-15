<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersVController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        ('store_id', Auth::guard('vendors')->user()->id)->where('is_active',true)->get()
        $orders = Order::where([
            ['store_id', Auth::guard('vendors')->user()->id],
            ['is_active', true],
        ])->get();
        return view('vendor.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $order = Order::findOrFail($request->id);
    //     if ($order)
    //     {
    //         $order->status='delivered';
    //         $order->save();
    //     }
    //     toastr()->error('تم تحديث الحالة بنجاح');
    //     return back();
    // }

    // /**

    // public function destroy(Request $request,$id)
    // {
    //     $order = Order::findOrFail($request->id);
    //     if ($order)
    //     {
    //         $order->is_active=false;
    //         $order->save();
    //     }
    //     toastr()->error('تم حذف الطلب بنجاح');
    //     return back();
    // }
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($request->id);
        if ($order) {
            $order->status = 'delivered';
            $order->save();
            return back()->with('success', trans('messages.order_status_updated'));
        }
    }

    public function destroy(Request $request, $id)
    {
        $order = Order::findOrFail($request->id);
        if ($order) {
            $order->is_active = false;
            $order->save();
            return back()->with('success', trans('messages.order_deleted'));
        }
    }

}