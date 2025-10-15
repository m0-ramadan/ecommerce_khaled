<?php

namespace App\Http\Controllers\Admin;

use App\Events\OrderStatusChanged;
use App\Http\Controllers\Controller;
use App\Mail\MessageMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validate the incoming request for the 'status' parameter
        $request->validate([
            'status' => 'nullable|in:' . implode(',', Order::ORDER_STATUSES),
        ]);
    
        // Build the query to fetch orders
        $orders = Order::with(['orderitem.product', 'client'])
            ->when($request->has('status'), function ($query) use ($request) {
                // If the status is provided, filter the orders by the given status
                return $query->where('status', $request->status);
            })
            ->get();
    
        // Get all order statuses for use in the view
        $orderStatuses = Order::ORDER_STATUSES;
    
        // Return the view with the orders and statuses
        return view('admin.orders.index', compact('orders', 'orderStatuses'));
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
        $orders = Order::where('id', $id)->get();
        return view('admin.orders.details', compact('orders'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . implode(',', Order::ORDER_STATUSES),
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        event(new OrderStatusChanged($order));

        if (filter_var($order->user_email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($order->user_email)->send(new MessageMail('Order', 'order has been ' . $request->status, null));
        }

        toastr()->success('تم تحديث الحالة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        public function destroy(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        toastr()->error('تم حذف الطلب بنجاح');
        return back();
    }
}
