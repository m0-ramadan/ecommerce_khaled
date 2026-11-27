@extends('Admin.layout.master')

@section('title', 'إدارة عناصر البانرات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #14213d;
            --primary-gradient: linear-gradient(135deg, #14213d 0%, #0c182a 100%);
            --success-color: #14213d;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
            --text-muted: #6c757d;
            --dark-bg: #f6f8fb;
            --dark-card: #ffffff;
        }

        body {
            font-family: "Cairo", sans-serif;
            background: var(--dark-bg);
            color: #1e293b;
        }

        .items-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
            padding: 25px;
            border: 1px solid #e2e8f0;
        }

        .items-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 25px;
            border-radius: 15px 15px 0 0;
            margin: -25px -25px 20px -25px;
        }

        .filter-section {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .table {
            color: #1e293b;
        }

        .table thead th {
            border-bottom-color: #e2e8f0;
            color: #1e293b;
            font-weight: 600;
        }

        .table td {
            border-bottom-color: #e2e8f0;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: rgba(20, 33, 61, 0.1);
        }

        .banner-item-preview {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #e2e8f0;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-active {
            background: rgba(30, 58, 95, 0.2);
            color: #14213d;
            border: 1px solid rgba(30, 58, 95, 0.3);
        }

        .badge-inactive {
            background: rgba(220, 53, 69, 0.2);
            color: #ef4444;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-icon {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        .btn-view {
            background: rgba(23, 162, 184, 0.2);
            color: #06b6d4;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }

        .btn-view:hover {
            background: #06b6d4;
            color: white;
        }

        .btn-edit {
            background: rgba(255, 193, 7, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .btn-edit:hover {
            background: #f59e0b;
            color: #000;
        }

        .btn-delete {
            background: rgba(220, 53, 69, 0.2);
            color: #ef4444;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .btn-delete:hover {
            background: #ef4444;
            color: white;
        }

        .btn-toggle {
            background: rgba(108, 117, 125, 0.2);
            color: #6c757d;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        .btn-toggle.active {
            background: rgba(30, 58, 95, 0.2);
            color: #14213d;
            border-color: rgba(30, 58, 95, 0.3);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state-icon {
            font-size: 70px;
            color: #94a3b8;
            margin-bottom: 20px;
        }

        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #1e293b;
        }

        .page-link:hover {
            background: var(--primary-color);
            color: #1e293b;
            border-color: var(--primary-color);
        }

        .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: var(--primary-color);
        }

        .page-item.disabled .page-link {
            background: #f8fafc;
            color: #94a3b8;
        }

        .form-control,
        .form-select {
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #1e293b;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #1e293b;
            box-shadow: 0 0 0 0.25rem rgba(20, 33, 61, 0.25);
        }

        .form-control::placeholder {
            color: #64748b;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.banners.index') }}">البانرات</a>
                </li>
                <li class="breadcrumb-item active">عناصر البانرات</li>
            </ol>
        </nav>

        <div class="items-card">
            <div class="items-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">عناصر البانرات</h5>
                    <small class="opacity-75">إدارة عناصر البانرات والربط مع المنتجات والتصنيفات</small>
                </div>
                <a href="{{ route('admin.banners.items.create') }}" class="btn btn-light">
                    <i class="fas fa-plus me-2"></i>إضافة عنصر جديد
                </a>
            </div>

            <!-- Filter Section -->
            <div class="filter-section">
                <form method="GET" action="{{ route('admin.banners.items.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">البانر</label>
                            <select name="banner_id" class="form-select" onchange="this.form.submit()">
                                <option value="">جميع البانرات</option>
                                @foreach ($banners as $banner)
                                    <option value="{{ $banner->id }}"
                                        {{ request('banner_id') == $banner->id ? 'selected' : '' }}>
                                        {{ $banner->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">الحالة</label>
                            <select name="is_active" class="form-select" onchange="this.form.submit()">
                                <option value="">الكل</option>
                                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">بحث</label>
                            <input type="text" name="search" class="form-control" placeholder="بحث عن عنصر..."
                                value="{{ request('search') }}" onchange="this.form.submit()">
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <a href="{{ route('admin.banners.items.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-redo me-2"></i>إعادة تعيين
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Items Table -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th width="80">الصورة</th>
                            <th>البانر</th>
                            <th>الترتيب</th>
                            <th>الربط</th>
                            <th>الوسم</th>
                            <th>الحالة</th>
                            <th width="200">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bannerItems as $index => $item)
                            <tr>
                                <td>{{ $bannerItems->firstItem() + $index }}</td>
                                <td>
                                    @if ($item->image_url)
                                        <img src="{{ Storage::url($item->image_url) }}" alt="{{ $item->image_alt }}"
                                            class="banner-item-preview">
                                    @else
                                        <div
                                            class="banner-item-preview bg-secondary d-flex align-items-center justify-content-center">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $item->banner?->name ?? '--' }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $item->banner?->type?->name ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $item->item_order }}</span>
                                </td>
                                <td>
                                    @if ($item->product_id)
                                        <span class="badge bg-primary">
                                            <i class="fas fa-box me-1"></i>منتج
                                        </span>
                                    @elseif($item->category_id)
                                        <span class="badge bg-success">
                                            <i class="fas fa-folder me-1"></i>تصنيف
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-link me-1"></i>رابط عادي
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($item->tag_text)
                                        <span class="badge"
                                            style="background: {{ $item->tag_bg_color }}; color: {{ $item->tag_color }}">
                                            {{ $item->tag_text }}
                                        </span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge-status {{ $item->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $item->is_active ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        {{-- <a href="{{ route('admin.banners.items.show', $item->id) }}"
                                            class="btn-icon btn-view" title="عرض">
                                            <i class="fas fa-eye"></i>
                                        </a> --}}

                                        <a href="{{ route('admin.banners.items.edit', $item->id) }}"
                                            class="btn-icon btn-edit" title="تعديل">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button"
                                            class="btn-icon btn-toggle {{ $item->is_active ? 'active' : '' }}"
                                            onclick="toggleStatus({{ $item->id }})"
                                            title="{{ $item->is_active ? 'تعطيل' : 'تفعيل' }}">
                                            <i class="fas {{ $item->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                        </button>

                                        <button type="button" class="btn-icon btn-delete"
                                            onclick="deleteItem({{ $item->id }}, '{{ $item->image_alt }}')"
                                            title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-images"></i>
                                        </div>
                                        <h5>لا توجد عناصر بانر</h5>
                                        <p class="text-muted">لم يتم إضافة أي عناصر بانر بعد</p>
                                        <a href="{{ route('admin.banners.items.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>إضافة عنصر جديد
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if ($bannerItems->hasPages())
                <div class="m-3">
                    <nav>
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($bannerItems->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link waves-effect" aria-hidden="true">‹</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="{{ $bannerItems->previousPageUrl() }}"
                                        rel="prev">‹</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($bannerItems->links()->elements[0] as $page => $url)
                                @if ($page == $bannerItems->currentPage())
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link waves-effect">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link waves-effect"
                                            href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($bannerItems->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="{{ $bannerItems->nextPageUrl() }}"
                                        rel="next">›</a>
                                </li>
                            @else
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link waves-effect" aria-hidden="true">›</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleStatus(id) {
            Swal.fire({
                title: 'تغيير الحالة',
                text: 'هل أنت متأكد من تغيير حالة هذا العنصر؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#14213d',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، تغيير',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/banners/items') }}/" + id + "/toggle-status",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم بنجاح',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء تغيير الحالة',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        function deleteItem(id, name) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: `سيتم حذف العنصر "${name}" نهائياً`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/banners/items') }}/" + id,
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'DELETE'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: response.message
                                });
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: xhr.responseJSON?.message || 'حدث خطأ أثناء الحذف'
                            });
                        }
                    });
                }
            });
        }

        // Auto-submit form when filters change
        $('.filter-section select, .filter-section input').on('change', function() {
            $('#filterForm').submit();
        });

        // Debounce search input
        let searchTimeout;
        $('input[name="search"]').on('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                $('#filterForm').submit();
            }, 500);
        });

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: "{{ session('error') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection
