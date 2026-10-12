@extends('Admin.layout.master')

@section('title', 'طلبات خدمات القرطاسية والطباعة')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="ti ti-receipt text-primary me-2"></i>طلبات خدمات القرطاسية والطباعة
            </h4>
            <p class="text-muted mb-0">متابعة وطباعة الملفات والمستندات المرفوعة من العملاء مباشرة</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stationery.index') }}" class="btn btn-outline-warning">
                <i class="ti ti-adjustments me-1"></i>
                إدارة الأسعار والمقاسات
            </a>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>
                كافة طلبات المتجر
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header border-bottom bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="card-title mb-0 fw-bold">قائمة طلبات الطباعة والقرطاسية</h5>
            <span class="badge bg-label-primary fs-6">{{ $orders->total() }} طلبات</span>
        </div>

        <div class="table-responsive text-nowrap">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>رقم الطلب</th>
                        <th>العميل</th>
                        <th>الملفات المرفوعة والمواصفات</th>
                        <th>طريقة الاستلام / الشحن</th>
                        <th>المبلغ الإجمالي</th>
                        <th>الحالة</th>
                        <th class="text-center">إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-primary">
                                    #{{ $order->id }}
                                </a>
                                <div class="text-muted small">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $order->customer_name ?? $order->user?->name ?? 'عميل زائر' }}</div>
                                <div class="text-muted small" dir="ltr">{{ $order->customer_phone ?? $order->user?->phone ?? '-' }}</div>
                            </td>
                            <td>
                                @foreach ($order->items as $item)
                                    @php
                                        $options = is_array($item->selected_options) ? $item->selected_options : json_decode($item->selected_options, true);
                                        $filesJson = null;
                                        if (is_array($options)) {
                                            foreach ($options as $opt) {
                                                if (isset($opt['option_name']) && $opt['option_name'] === 'تفاصيل ملفات الطباعة') {
                                                    $filesJson = json_decode($opt['option_value'] ?? '[]', true);
                                                }
                                            }
                                        }
                                    @endphp

                                    @if (!empty($filesJson) && is_array($filesJson))
                                        <div class="d-flex flex-column gap-1">
                                            @foreach ($filesJson as $file)
                                                <div class="d-flex align-items-center justify-content-between p-1 px-2 border rounded bg-light small gap-2">
                                                    <div class="text-truncate" style="max-width: 220px;" title="{{ $file['file_name'] ?? 'ملف' }}">
                                                        <i class="ti ti-file me-1 text-primary"></i>
                                                        <strong>{{ $file['file_name'] ?? 'ملف' }}</strong>
                                                        <span class="badge bg-secondary ms-1">{{ $file['paper_size'] ?? 'A4' }}</span>
                                                        <span class="badge {{ ($file['color_type'] ?? '') === 'color' ? 'bg-primary' : 'bg-dark' }}">
                                                            {{ ($file['color_type'] ?? '') === 'color' ? 'ألوان' : 'أبيض/أسود' }}
                                                        </span>
                                                        <span class="text-muted ms-1">({{ $file['pages_count'] ?? 1 }}ص × {{ $file['copies'] ?? 1 }}نسخ)</span>
                                                    </div>
                                                    @if (!empty($file['file_url']))
                                                        <a href="{{ $file['file_url'] }}" target="_blank" download class="btn btn-xs btn-primary d-inline-flex align-items-center gap-1">
                                                            <i class="ti ti-download"></i>
                                                            تحميل
                                                        </a>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        @if ($item->image_design)
                                            <a href="{{ asset('storage/' . $item->image_design) }}" target="_blank" class="btn btn-xs btn-outline-primary">
                                                <i class="ti ti-download me-1"></i>تحميل الملف المرفق
                                            </a>
                                        @else
                                            <span class="text-muted small">لا توجد ملفات</span>
                                        @endif
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                <div>{{ $order->shipping_method_name ?? 'توصيل / شحن' }}</div>
                                <small class="text-muted">{{ Str::limit($order->shipping_address ?? 'استلام من الفرع', 25) }}</small>
                            </td>
                            <td>
                                <div class="fw-bold text-success">{{ number_format($order->total_amount, 2) }} ر.س</div>
                                <small class="text-muted">{{ $order->payment_method ?? 'دفع إلكتروني' }}</small>
                            </td>
                            <td>
                                @php
                                    $statusBadges = [
                                        'pending' => 'bg-label-warning',
                                        'processing' => 'bg-label-info',
                                        'completed' => 'bg-label-success',
                                        'cancelled' => 'bg-label-danger',
                                    ];
                                @endphp
                                <span class="badge {{ $statusBadges[$order->status] ?? 'bg-label-secondary' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-icon btn-outline-primary" title="عرض تفاصيل الطلب">
                                    <i class="ti ti-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="ti ti-receipt-off fs-1 text-muted d-block mb-2"></i>
                                <h6 class="text-muted">لا توجد طلبات طباعة أو قرطاسية حتى الآن</h6>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

