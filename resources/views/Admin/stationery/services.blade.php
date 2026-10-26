@extends('Admin.layout.master')

@section('title', 'الخدمات الإضافية للطباعة')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="ti ti-tools text-warning me-2"></i>الخدمات الإضافية للطباعة</h4>
            <p class="text-muted mb-0">خدمات مدفوعة تُضاف لطلب الطباعة (سلك بلاستيك/حديد، تيب، تغليف شفاف). السعر الافتراضي 10 ر.س لكل خدمة.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stationery.index') }}" class="btn btn-outline-primary"><i class="ti ti-arrow-right me-1"></i>جدول الأسعار</a>
            <a href="{{ route('admin.stationery.paper-types.index') }}" class="btn btn-outline-secondary"><i class="ti ti-file-plus me-1"></i>أنواع الورق</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom"><h5 class="mb-0">إضافة خدمة جديدة</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.stationery.services.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-7">
                    <label class="form-label fw-bold">اسم الخدمة</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="مثال: سلك بلاستيك" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">السعر</label>
                    <div class="input-group"><input type="number" name="price" value="{{ old('price', 10) }}" min="0" step="0.01" class="form-control" required><span class="input-group-text">ر.س</span></div>
                </div>
                <div class="col-md-2 d-grid"><button class="btn btn-success" type="submit"><i class="ti ti-plus me-1"></i>إضافة</button></div>
                <div class="col-12">
                    <label class="form-label fw-bold mb-1">المقاسات المتاحة</label>
                    <div class="d-flex flex-wrap gap-3">
                        @foreach ($sizes as $size)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="available_sizes[]"
                                       id="new-size-{{ $size }}" value="{{ $size }}" checked>
                                <label class="form-check-label" for="new-size-{{ $size }}">{{ $size }}</label>
                            </div>
                        @endforeach
                    </div>
                    <small class="text-muted">شيل العلامة من أي مقاس عشان تمنع الخدمة عليه. مثال: شيل A1 وA2 عشان الخدمة تظهر للمقاسات الأصغر بس.</small>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div><h5 class="mb-1">الخدمات المسجلة</h5><small class="text-muted">التعطيل يخفي الخدمة من صفحة الطباعة.</small></div>
            <span class="badge bg-label-primary">{{ $services->count() }} خدمة</span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.stationery.services.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light"><tr><th>اسم الخدمة</th><th>السعر</th><th>المقاسات المتاحة</th><th class="text-center">متاح</th><th class="text-center">حذف</th></tr></thead>
                        <tbody>
                            @forelse ($services as $service)
                                @php
                                    // null = كل المقاسات، [] = ولا مقاس.
                                    $serviceSizes = is_array($service->available_sizes)
                                        ? array_map('strtoupper', $service->available_sizes)
                                        : array_map('strtoupper', $sizes);
                                @endphp
                                <tr>
                                    <td><input type="text" name="services[{{ $service->id }}][name]" value="{{ $service->name }}" class="form-control" required></td>
                                    <td><div class="input-group"><input type="number" name="services[{{ $service->id }}][price]" value="{{ $service->price }}" min="0" step="0.01" class="form-control" required><span class="input-group-text">ر.س</span></div></td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($sizes as $size)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="services[{{ $service->id }}][available_sizes][]"
                                                           id="size-{{ $service->id }}-{{ $size }}" value="{{ $size }}"
                                                           {{ in_array(strtoupper($size), $serviceSizes, true) ? 'checked' : '' }}>
                                                    <label class="form-check-label small" for="size-{{ $service->id }}-{{ $size }}">{{ $size }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center"><input type="checkbox" name="services[{{ $service->id }}][is_active]" value="1" class="form-check-input" {{ $service->is_active ? 'checked' : '' }}></td>
                                    <td class="text-center"><button type="submit" form="delete-service-{{ $service->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف الخدمة؟')"><i class="ti ti-trash"></i></button></td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">لا توجد خدمات مضافة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($services->isNotEmpty())<button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>حفظ التعديلات</button>@endif
            </form>
            @foreach ($services as $service)
                <form id="delete-service-{{ $service->id }}" action="{{ route('admin.stationery.services.destroy', $service) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
            @endforeach
        </div>
    </div>
</div>
@endsection
