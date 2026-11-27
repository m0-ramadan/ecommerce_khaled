@extends('Admin.layout.master')

@section('title', 'تفاصيل عنصر البانر')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.banners.items.index') }}">عناصر البانر</a></li>
                <li class="breadcrumb-item active">تفاصيل العنصر #{{ $bannerItem->id }}</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header table-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-0">عنصر البانر #{{ $bannerItem->id }}</h5>
                    <small class="text-muted">البانر: {{ $bannerItem->banner->title ?? 'غير محدد' }}</small>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.banners.items.edit', $bannerItem->id) }}" class="btn btn-light">
                        <i class="ti ti-edit me-1"></i> تعديل
                    </a>
                    <a href="{{ route('admin.banners.items.index') }}" class="btn btn-light">
                        <i class="ti ti-arrow-right me-1"></i> رجوع
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #edf0f5;">
                            <h6 class="fw-bold mb-3">الصورة الأساسية (سطح المكتب)</h6>
                            @if ($bannerItem->image_url)
                                <img src="{{ asset('storage/' . $bannerItem->image_url) }}"
                                    alt="{{ $bannerItem->image_alt ?? 'Banner Image' }}"
                                    class="img-fluid rounded" style="max-height:220px; object-fit:cover;">
                            @else
                                <p class="text-muted mb-0">لا توجد صورة</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #edf0f5;">
                            <h6 class="fw-bold mb-3">صورة الجوال</h6>
                            @if ($bannerItem->mobile_image_url)
                                <img src="{{ asset('storage/' . $bannerItem->mobile_image_url) }}"
                                    alt="{{ $bannerItem->image_alt ?? 'Mobile Image' }}"
                                    class="img-fluid rounded" style="max-height:220px; object-fit:cover;">
                            @else
                                <p class="text-muted mb-0">لا توجد صورة مخصصة للجوال</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #edf0f5;">
                            <h6 class="fw-bold mb-2">البيانات الأساسية</h6>
                            <p class="mb-1"><strong>الترتيب:</strong> {{ $bannerItem->item_order }}</p>
                            <p class="mb-1"><strong>النص البديل:</strong> {{ $bannerItem->image_alt ?: '-' }}</p>
                            <p class="mb-1">
                                <strong>الحالة:</strong>
                                <span class="badge {{ $bannerItem->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                    {{ $bannerItem->is_active ? 'نشط' : 'غير نشط' }}
                                </span>
                            </p>
                            @if ($bannerItem->tag_text)
                                <p class="mb-0"><strong>الوسم:</strong> {{ $bannerItem->tag_text }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #edf0f5;">
                            <h6 class="fw-bold mb-2">الرابط والربط</h6>
                            <p class="mb-1"><strong>حالة الرابط:</strong> {{ $bannerItem->is_link_active ? 'مفعل' : 'معطل' }}</p>
                            <p class="mb-1"><strong>الرابط:</strong> {{ $bannerItem->link_url ?: '-' }}</p>
                            <p class="mb-1"><strong>هدف الفتح:</strong> {{ $bannerItem->link_target ?: '_self' }}</p>
                            @if ($linkedItem)
                                <p class="mb-0"><strong>العنصر المرتبط:</strong> {{ $linkedItem->name ?? ('#' . $linkedItem->id) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
