@extends('Admin.layout.master')

@section('title', 'إدارة السلايدر الرئيسي')

@section('css')
    <style>
        .slider-thumb {
            width: 140px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.12);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            border: 1px solid #e2e8f0;
        }
        .slider-thumb:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .slider-thumb-mobile {
            width: 50px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
            cursor: pointer;
        }
        .stat-card {
            border-radius: 12px;
            border: none;
            transition: all 0.2s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }
        .preview-carousel-item {
            height: 280px;
            background-size: cover;
            background-position: center;
            border-radius: 12px;
        }
        .order-badge {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #f1f5f9;
            font-weight: 700;
            color: #475569;
        }
    </style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">لوحة التحكم /</span> السلايدر الرئيسي
            </h4>
            <p class="text-muted mb-0">إدارة شرائح البنر المتحرك في واجهة المتجر الرئيسية بسهولة وسرعة</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="ti ti-plus fs-5"></i>
                <span>إضافة شريحة جديدة</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="ti ti-check-circle fs-4 me-2"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="ti ti-alert-circle fs-4 me-2"></i>
            <div>{{ session('error') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-primary p-3 me-3">
                        <i class="ti ti-slideshow fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">إجمالي الشرائح</small>
                        <h4 class="mb-0 fw-bold">{{ $totalSlides }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-success p-3 me-3">
                        <i class="ti ti-circle-check fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">الشرائح المفعلة</small>
                        <h4 class="mb-0 fw-bold text-success">{{ $activeSlides }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-secondary p-3 me-3">
                        <i class="ti ti-eye-off fs-3"></i>
                    </div>
                    <div>
                        <small class="text-muted d-block">الشرائح المعطلة</small>
                        <h4 class="mb-0 fw-bold text-secondary">{{ $inactiveSlides }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Slides List Card -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3 border-bottom">
            <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                <i class="ti ti-list-details text-primary"></i>
                <span>قائمة شرائح السلايدر</span>
            </h5>
            <span class="badge bg-label-info">{{ $slides->count() }} عنصر</span>
        </div>

        <div class="card-body p-0">
            @if($slides->isEmpty())
                <div class="text-center py-5">
                    <div class="avatar avatar-xl bg-label-primary rounded-circle mx-auto mb-3" style="width: 70px; height: 70px;">
                        <i class="ti ti-photo-plus fs-1 d-flex align-items-center justify-content-center h-100"></i>
                    </div>
                    <h5 class="fw-bold">لا توجد شرائح في السلايدر حتى الآن</h5>
                    <p class="text-muted mb-3">ابدأ بإضافة أول شريحة ترويجية لتظهر في الصفحة الرئيسية لمتجرك</p>
                    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
                        <i class="ti ti-plus me-1"></i> إضافة شريحة الآن
                    </a>
                </div>
            @else
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;" class="text-center">#الترتيب</th>
                                <th>صورة سطح المكتب</th>
                                <th>صورة الجوال</th>
                                <th>العنوان / النص</th>
                                <th>رابط التوجيه</th>
                                <th style="width: 120px;" class="text-center">الحالة</th>
                                <th style="width: 140px;" class="text-center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($slides as $slide)
                                <tr id="slide-row-{{ $slide->id }}">
                                    <td class="text-center">
                                        <span class="order-badge">{{ $slide->item_order }}</span>
                                    </td>
                                    <td>
                                        @if($slide->image_url)
                                            <a href="{{ get_user_image($slide->image_url) }}" target="_blank" title="عرض الصورة بالحجم الكامل">
                                                <img src="{{ get_user_image($slide->image_url) }}" 
                                                     alt="{{ $slide->image_alt ?: 'Slide ' . $slide->id }}" 
                                                     class="slider-thumb">
                                            </a>
                                        @else
                                            <span class="badge bg-label-secondary">لا توجد صورة</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($slide->mobile_image_url)
                                            <a href="{{ get_user_image($slide->mobile_image_url) }}" target="_blank" title="عرض صورة الجوال">
                                                <img src="{{ get_user_image($slide->mobile_image_url) }}" 
                                                     alt="Mobile Slide" 
                                                     class="slider-thumb-mobile">
                                            </a>
                                        @else
                                            <span class="badge bg-label-light text-muted border">نفس المكتب</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $slide->image_alt ?: '—' }}</div>
                                        <small class="text-muted">ID: #{{ $slide->id }}</small>
                                    </td>
                                    <td>
                                        @if($slide->link_url)
                                            <div class="d-flex align-items-center gap-1">
                                                <i class="ti ti-link text-primary"></i>
                                                <a href="{{ $slide->link_url }}" target="_blank" class="text-truncate d-inline-block" style="max-width: 220px;" dir="ltr">
                                                    {{ $slide->link_url }}
                                                </a>
                                                @if($slide->link_target === '_blank')
                                                    <span class="badge bg-label-secondary ms-1" title="تفتح في نافذة جديدة">
                                                        <i class="ti ti-external-link fs-6"></i>
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-muted">بدون رابط</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check form-switch d-inline-block m-0">
                                            <input class="form-check-input status-toggle" 
                                                   type="checkbox" 
                                                   role="switch" 
                                                   data-id="{{ $slide->id }}"
                                                   {{ $slide->is_active ? 'checked' : '' }}>
                                        </div>
                                        <div class="small status-text-{{ $slide->id }} {{ $slide->is_active ? 'text-success' : 'text-muted' }}">
                                            {{ $slide->is_active ? 'مفعل' : 'معطل' }}
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-inline-flex gap-1">
                                            <a href="{{ route('admin.sliders.edit', $slide->id) }}" 
                                               class="btn btn-sm btn-icon btn-label-primary" 
                                               title="تعديل الشريحة">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-sm btn-icon btn-label-danger delete-btn" 
                                                    data-id="{{ $slide->id }}"
                                                    data-title="{{ $slide->image_alt ?: 'الشريحة #' . $slide->id }}"
                                                    title="حذف الشريحة">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

        // Toggle Status via AJAX
        document.querySelectorAll('.status-toggle').forEach(function(toggle) {
            toggle.addEventListener('change', function() {
                const slideId = this.getAttribute('data-id');
                const isChecked = this.checked;
                const statusText = document.querySelector('.status-text-' + slideId);

                fetch(`{{ url('admin/sliders') }}/${slideId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (statusText) {
                            statusText.textContent = data.is_active ? 'مفعل' : 'معطل';
                            statusText.className = 'small status-text-' + slideId + (data.is_active ? ' text-success' : ' text-muted');
                        }
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: data.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    } else {
                        toggle.checked = !isChecked;
                        Swal.fire('خطأ', data.message || 'حدث خطأ أثناء تغيير الحالة', 'error');
                    }
                })
                .catch(error => {
                    toggle.checked = !isChecked;
                    Swal.fire('خطأ', 'تعذر الاتصال بالخادم', 'error');
                });
            });
        });

        // Delete Slide via AJAX with confirmation
        document.querySelectorAll('.delete-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const slideId = this.getAttribute('data-id');
                const slideTitle = this.getAttribute('data-title');

                Swal.fire({
                    title: 'هل أنت متأكد من الحذف؟',
                    text: `سيتم حذف "${slideTitle}" نهائياً ولن تظهر في السلايدر.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`{{ url('admin/sliders') }}/${slideId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const row = document.getElementById(`slide-row-${slideId}`);
                                if (row) {
                                    row.style.transition = 'all 0.3s ease';
                                    row.style.opacity = '0';
                                    row.style.transform = 'scale(0.95)';
                                    setTimeout(() => row.remove(), 300);
                                }
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: data.message,
                                    showConfirmButton: false,
                                    timer: 2500
                                });
                            } else {
                                Swal.fire('خطأ', data.message || 'حدث خطأ أثناء الحذف', 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('خطأ', 'تعذر إتمام عملية الحذف', 'error');
                        });
                    }
                });
            });
        });
    });
</script>
@endsection

