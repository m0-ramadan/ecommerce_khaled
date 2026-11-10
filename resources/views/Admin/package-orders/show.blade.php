@extends('Admin.layout.master')
@section('title', 'تفاصيل طلب باقة')
@section('content')
    <div class="container-fluid">
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <h4 class="mb-0">طلب {{ $order->order_number }}</h4>
                <a href="{{ route('admin.package-orders.index') }}" class="btn btn-secondary btn-sm">رجوع</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><b>الباقة:</b> {{ $order->package?->name }}</p>
                        <p><b>العميل:</b> {{ $order->customer_name }}</p>
                        <p><b>الجوال:</b> {{ $order->customer_phone }}</p>
                        <p><b>العنوان:</b> {{ $order->shipping_address }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><b>الدفع:</b> {{ $order->payment_method }} / {{ $order->status_payment }}</p>
                        <p><b>الشحن:</b> {{ $order->delivery_option_name }}</p>
                        <p><b>منتجات:</b> {{ number_format((float) $order->items_total, 2) }} ريال</p>
                        <p><b>الشحن:</b> {{ number_format((float) $order->shipping_amount, 2) }} ريال</p>
                        <p class="fw-bold"><b>الإجمالي:</b> {{ number_format((float) $order->total_amount, 2) }} ريال</p>
                    </div>
                </div>
                <form action="{{ route('admin.package-orders.status', $order->id) }}" method="POST" class="row g-2 mt-2">
                    @csrf
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            @foreach (['pending' => 'قيد الانتظار', 'processing' => 'جاري التنفيذ', 'shipped' => 'تم الشحن', 'delivered' => 'تم التسليم', 'cancelled' => 'ملغي'] as $k => $v)
                                <option value="{{ $k }}" @selected($order->status === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="status_payment" class="form-select">
                            @foreach (['pending' => 'غير مدفوع', 'paid' => 'مدفوع', 'failed' => 'فشل', 'refunded' => 'مسترجع'] as $k => $v)
                                <option value="{{ $k }}" @selected($order->status_payment === $k)>{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4"><button class="btn btn-success w-100">حفظ الحالة</button></div>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h5 class="mb-0">منتجات الطلب</h5></div>
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead><tr><th>المنتج</th><th>النوع</th><th>الكمية</th><th>سعر الوحدة</th><th>الإجمالي</th></tr></thead>
                    <tbody>
                        @foreach ($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->type === 'main' ? 'أساسي' : 'مرشح' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format((float) $item->unit_price, 2) }}</td>
                                <td>{{ number_format((float) $item->line_total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
