@extends('Admin.layout.master')
@section('title', 'طلبات الباكدجات')
@section('content')
    <div class="container-fluid">
        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h4 class="mb-0">طلبات الباكدجات</h4>
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="رقم الطلب / الاسم / الجوال">
                    <button class="btn btn-primary">بحث</button>
                </form>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th><th>رقم الطلب</th><th>الباقة</th><th>العميل</th><th>الجوال</th>
                            <th>منتجات</th><th>المنتجات</th><th>الشحن</th><th>الإجمالي</th><th>الدفع</th><th>الحالة</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td class="fw-bold">{{ $order->order_number }}</td>
                                <td>{{ $order->package?->name }}</td>
                                <td>{{ $order->customer_name }}</td>
                                <td>{{ $order->customer_phone }}</td>
                                <td>{{ $order->items_count }}</td>
                                <td>{{ number_format((float) $order->items_total, 2) }}</td>
                                <td>{{ number_format((float) $order->shipping_amount, 2) }}</td>
                                <td class="fw-bold">{{ number_format((float) $order->total_amount, 2) }}</td>
                                <td>{{ $order->payment_method }} / {{ $order->status_payment }}</td>
                                <td><span class="badge bg-info">{{ $order->status }}</span></td>
                                <td><a href="{{ route('admin.package-orders.show', $order->id) }}" class="btn btn-sm btn-warning">عرض</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="12" class="text-center">لا توجد طلبات باكدجات بعد</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
