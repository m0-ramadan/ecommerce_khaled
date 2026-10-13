@extends('Admin.layout.master')

@section('title', 'مناطق وأسعار توصيل الطباعة')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="ti ti-truck-delivery text-warning me-2"></i>مناطق وأسعار توصيل الطباعة</h4>
            <p class="text-muted mb-0">إدارة الأماكن المتاحة للتوصيل للبيت والسعر الخاص بكل مكان.</p>
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
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom"><h5 class="mb-0">إضافة مكان جديد</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.stationery.delivery-areas.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-7">
                    <label class="form-label fw-bold">اسم المنطقة / المكان</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="مثال: القاهرة الجديدة" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">سعر التوصيل</label>
                    <div class="input-group"><input type="number" name="price" value="{{ old('price') }}" min="0" step="0.01" class="form-control" required><span class="input-group-text">ر.س</span></div>
                </div>
                <div class="col-md-2 d-grid"><button class="btn btn-success" type="submit"><i class="ti ti-plus me-1"></i>إضافة مكان</button></div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div><h5 class="mb-1">الأماكن المسجلة</h5><small class="text-muted">الاستلام من الفرع متاح مجانًا ولا يحتاج إلى إضافته هنا.</small></div>
            <span class="badge bg-label-primary">{{ $deliveryAreas->count() }} مكان</span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.stationery.delivery-areas.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light"><tr><th>المكان</th><th>السعر</th><th class="text-center">متاح</th><th class="text-center">حذف</th></tr></thead>
                        <tbody>
                            @forelse ($deliveryAreas as $area)
                                <tr>
                                    <td><input type="text" name="delivery_areas[{{ $area->id }}][name]" value="{{ $area->name }}" class="form-control" required></td>
                                    <td><div class="input-group"><input type="number" name="delivery_areas[{{ $area->id }}][price]" value="{{ $area->price }}" min="0" step="0.01" class="form-control" required><span class="input-group-text">ر.س</span></div></td>
                                    <td class="text-center"><input type="checkbox" name="delivery_areas[{{ $area->id }}][is_active]" value="1" class="form-check-input" {{ $area->is_active ? 'checked' : '' }}></td>
                                    <td class="text-center"><button type="submit" form="delete-delivery-area-{{ $area->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('حذف منطقة التوصيل؟')"><i class="ti ti-trash"></i></button></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">لم تتم إضافة مناطق توصيل بعد.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($deliveryAreas->isNotEmpty())<button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>حفظ التعديلات</button>@endif
            </form>
            @foreach ($deliveryAreas as $area)
                <form id="delete-delivery-area-{{ $area->id }}" action="{{ route('admin.stationery.delivery-areas.destroy', $area) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
            @endforeach
        </div>
    </div>
</div>
@endsection
