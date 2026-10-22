@extends('Admin.layout.master')

@section('title', 'توصيل الطباعة')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="ti ti-truck-delivery text-warning me-2"></i>توصيل الطباعة</h4>
            <p class="text-muted mb-0">سياسة التوصيل المعتمدة لطلبات القرطاسية والطباعة.</p>
        </div>
        <a href="{{ route('admin.stationery.index') }}" class="btn btn-outline-primary">
            <i class="ti ti-arrow-right me-1"></i>أسعار الطباعة
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <div class="d-flex align-items-start gap-3">
                <div class="avatar avatar-lg">
                    <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-map-pin ti-md"></i></span>
                </div>
                <div>
                    <h5 class="mb-2">التوصيل داخل الرياض فقط</h5>
                    <p class="mb-2 text-muted">
                        التوصيل متاح داخل مدينة الرياض فقط، بسعر ثابت <strong>20 ريال</strong> لأي مكان داخل الرياض.
                    </p>
                    <p class="mb-0 text-muted">
                        الاستلام من الفرع متاح مجانًا. لا يمكن تعديل سعر التوصيل من لوحة التحكم لأنه سعر ثابت معتمد.
                    </p>
                </div>
            </div>

            <hr class="my-4">

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>المنطقة</th>
                            <th>السعر</th>
                            <th class="text-center">الحالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>{{ \App\Models\StationeryDeliveryArea::RIYADH_NAME }}</strong></td>
                            <td>{{ number_format(\App\Models\StationeryDeliveryArea::RIYADH_PRICE, 2) }} ر.س</td>
                            <td class="text-center"><span class="badge bg-label-success">مفعّل</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
