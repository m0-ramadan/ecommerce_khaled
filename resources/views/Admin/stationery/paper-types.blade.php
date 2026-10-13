@extends('Admin.layout.master')

@section('title', 'إدارة أنواع ورق الطباعة')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="ti ti-file-plus text-warning me-2"></i>أنواع ورق الطباعة</h4>
            <p class="text-muted mb-0">أضف أي نوع ورق جديد للملفات أو الصور وسيظهر تلقائيًا ضمن خيارات العميل وجدول الأسعار.</p>
        </div>
        <a href="{{ route('admin.stationery.index') }}" class="btn btn-outline-primary"><i class="ti ti-arrow-right me-1"></i>جدول الأسعار</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom"><h5 class="mb-0">إضافة نوع ورق جديد</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.stationery.paper-types.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label fw-bold">يُستخدم في</label>
                    <select name="service_type" class="form-select" required>
                        <option value="document" {{ old('service_type') === 'document' ? 'selected' : '' }}>طباعة الملفات والمستندات</option>
                        <option value="photo" {{ old('service_type') === 'photo' ? 'selected' : '' }}>طباعة الصور</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label class="form-label fw-bold">اسم نوع الورق</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="مثال: ورق حراري، ورق لاصق..." required>
                </div>
                <div class="col-12"><hr><div class="fw-bold mb-1">الأسعار الابتدائية</div><small class="text-muted">تُطبّق مبدئيًا على كل المقاسات والأوجه والاتجاهات، ويمكن تعديل كل تركيبة لاحقًا من جدول الأسعار.</small></div>
                <div class="col-md-3"><label class="form-label">ورقة مفردة — أبيض وأسود</label><input type="number" name="bw_price" value="{{ old('bw_price', 0.50) }}" min="0" step="0.01" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label">ورقة مفردة — ألوان</label><input type="number" name="color_price" value="{{ old('color_price', 1.50) }}" min="0" step="0.01" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label">داخل ملف — أبيض وأسود</label><input type="number" name="file_bw_price" value="{{ old('file_bw_price', 0.40) }}" min="0" step="0.01" class="form-control" required></div>
                <div class="col-md-3"><label class="form-label">داخل ملف — ألوان</label><input type="number" name="file_color_price" value="{{ old('file_color_price', 0.90) }}" min="0" step="0.01" class="form-control" required></div>
                <div class="col-12"><button type="submit" class="btn btn-success"><i class="ti ti-plus me-1"></i>إضافة النوع وإنشاء أسعاره</button></div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <div><h5 class="mb-1">أنواع الورق المسجلة</h5><small class="text-muted">التعطيل يخفي النوع من الموقع مع الاحتفاظ بأسعاره.</small></div>
            <span class="badge bg-label-primary">{{ $paperTypes->count() }} نوع</span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.stationery.paper-types.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead class="table-light"><tr><th>القسم</th><th>اسم نوع الورق</th><th class="text-center">متاح</th><th class="text-center">حذف</th></tr></thead>
                        <tbody>
                            @forelse ($paperTypes as $type)
                                <tr>
                                    <td><span class="badge {{ $type->service_type === 'photo' ? 'bg-label-info' : 'bg-label-primary' }}">{{ $type->service_type === 'photo' ? 'صور' : 'ملفات ومستندات' }}</span></td>
                                    <td><input type="text" name="paper_types[{{ $type->id }}][name]" value="{{ $type->name }}" class="form-control" required></td>
                                    <td class="text-center"><input type="checkbox" name="paper_types[{{ $type->id }}][is_active]" value="1" class="form-check-input" {{ $type->is_active ? 'checked' : '' }}></td>
                                    <td class="text-center"><button type="submit" form="delete-paper-type-{{ $type->id }}" class="btn btn-sm btn-outline-danger" onclick="return confirm('سيتم حذف نوع الورق وكل أسعاره. هل أنت متأكد؟')"><i class="ti ti-trash"></i></button></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">لا توجد أنواع ورق مسجلة.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($paperTypes->isNotEmpty())<button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy me-1"></i>حفظ التعديلات</button>@endif
            </form>
            @foreach ($paperTypes as $type)
                <form id="delete-paper-type-{{ $type->id }}" action="{{ route('admin.stationery.paper-types.destroy', $type) }}" method="POST" class="d-none">@csrf @method('DELETE')</form>
            @endforeach
        </div>
    </div>
</div>
@endsection
