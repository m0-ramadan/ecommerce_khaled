@extends('Admin.layout.master')

@section('title', 'الصفحات الثابتة')

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
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #1e293b;
        }

        .card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
            border: 1px solid #e2e8f0;
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            border-radius: 15px 15px 0 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .stats-card {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05);
            border-top: 4px solid var(--primary-color);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.05);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 15px;
        }

        .icon-total {
            background: var(--primary-gradient);
            color: white;
        }

        .icon-active {
            background: linear-gradient(135deg, rgba(20, 33, 61, 0.2) 0%, rgba(30, 58, 95, 0.2) 100%);
            color: #14213d;
            border: 1px solid rgba(30, 58, 95, 0.3);
        }

        .icon-inactive {
            background: rgba(133, 100, 4, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .stats-number {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #1e293b;
        }

        .stats-label {
            color: #64748b;
            font-size: 14px;
        }

        .badge-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
        }

        .status-active {
            background: linear-gradient(135deg, rgba(20, 33, 61, 0.2) 0%, rgba(30, 58, 95, 0.2) 100%);
            color: #14213d;
            border: 1px solid rgba(30, 58, 95, 0.3);
        }

        .status-inactive {
            background: rgba(133, 100, 4, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .page-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }

        .table {
            color: #1e293b;
        }

        .table th {
            background: #f8fafc;
            border-color: #e2e8f0;
            font-weight: 600;
            color: #1e293b;
        }

        .table td {
            border-color: #e2e8f0;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(20, 33, 61, 0.1);
        }

        .filter-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-right: 40px;
            border-radius: 25px;
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #1e293b;
        }

        .search-box input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #1e293b;
            box-shadow: 0 0 0 0.25rem rgba(20, 33, 61, 0.25);
        }

        .search-box .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #14213d 0%, #1e3a5f 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(20, 33, 61, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #14213d 0%, #14213d 100%);
            border: none;
        }

        .btn-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #f59e0b 100%);
            border: none;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #f59e0b 100%);
            border: none;
        }

        .bulk-actions {
            background: #f8fafc;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .action-dropdown {
            position: relative;
            display: inline-block;
        }

        .action-dropdown-content {
            display: none;
            position: absolute;
            background: var(--dark-card);
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(15, 23, 42, 0.05);
            border-radius: 10px;
            z-index: 1;
            padding: 10px 0;
            margin-top: 5px;
            border: 1px solid #e2e8f0;
        }

        .action-dropdown:hover .action-dropdown-content {
            display: block;
        }

        .action-item {
            padding: 10px 20px;
            cursor: pointer;
            transition: background 0.3s;
            color: #1e293b;
            display: block;
            width: 100%;
            text-align: right;
            border: none;
            background: transparent;
        }

        .action-item:hover {
            background: rgba(20, 33, 61, 0.1);
            color: #1e293b;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
        }

        .empty-state-icon {
            font-size: 60px;
            color: #94a3b8;
            margin-bottom: 20px;
        }

        .empty-state-text {
            color: #64748b;
            margin-bottom: 20px;
        }

        .content-preview {
            max-height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            color: #64748b;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" bis_skin_checked="1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item active">الصفحات الثابتة</li>
            </ol>
        </nav>

        <!-- الإحصائيات -->
        <div class="row mb-4" bis_skin_checked="1">
            <div class="col-lg-4 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-total" bis_skin_checked="1">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ \App\Models\StaticPage::count() }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">إجمالي الصفحات</div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-active" bis_skin_checked="1">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ \App\Models\StaticPage::where('status', 'active')->count() }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">صفحات نشطة</div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-inactive" bis_skin_checked="1">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ \App\Models\StaticPage::where('status', 'inactive')->count() }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">صفحات غير نشطة</div>
                </div>
            </div>
        </div>

        <!-- فلترة -->
        <div class="filter-card" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-8 mb-3 mb-md-0" bis_skin_checked="1">
                    <div class="search-box" bis_skin_checked="1">
                        <i class="fas fa-search search-icon"></i>
                        <form id="searchForm" action="{{ route('admin.static-pages.index') }}" method="GET">
                            <input type="text" class="form-control" name="search"
                                placeholder="بحث بالعنوان أو المحتوى أو الرابط..." value="{{ request('search') }}">
                        </form>
                    </div>
                </div>
                <div class="col-md-4" bis_skin_checked="1">
                    <div class="d-flex gap-2 justify-content-end" bis_skin_checked="1">
                        <select class="form-select" name="status" id="statusFilter" style="max-width: 200px;">
                            <option value="">جميع الحالات</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غير نشط
                            </option>
                        </select>
                        <button class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-filter me-2"></i>فلترة
                        </button>
                        <a href="{{ route('admin.static-pages.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>إضافة صفحة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bulk Actions -->
        <div class="bulk-actions" bis_skin_checked="1">
            <div class="d-flex justify-content-between align-items-center" bis_skin_checked="1">
                <div class="d-flex align-items-center gap-2" bis_skin_checked="1">
                    <div class="form-check" bis_skin_checked="1">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label" for="selectAll">
                            تحديد الكل
                        </label>
                    </div>
                    <div class="action-dropdown" bis_skin_checked="1">
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-cog me-2"></i>إجراءات جماعية
                        </button>
                        <div class="action-dropdown-content" bis_skin_checked="1">
                            <form id="bulkActionForm" method="POST" action="{{ route('admin.static-pages.bulk-action') }}">
                                @csrf
                                <input type="hidden" name="ids" id="selectedIds">
                                <button type="submit" name="action" value="activate" class="action-item">
                                    <i class="fas fa-check-circle me-2"></i>تفعيل
                                </button>
                                <button type="submit" name="action" value="deactivate" class="action-item">
                                    <i class="fas fa-times-circle me-2"></i>تعطيل
                                </button>
                                <button type="submit" name="action" value="delete" class="action-item text-danger">
                                    <i class="fas fa-trash me-2"></i>حذف
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div bis_skin_checked="1">
                    <span id="selectedCount">0</span> محدد
                </div>
            </div>
        </div>

        <!-- جدول الصفحات -->
        <div class="card" bis_skin_checked="1">
            <div class="card-header" bis_skin_checked="1">
                <div class="d-flex justify-content-between align-items-center" bis_skin_checked="1">
                    <div bis_skin_checked="1">
                        <h5 class="mb-0">قائمة الصفحات الثابتة</h5>
                        <small class="opacity-75">إدارة جميع الصفحات الثابتة للموقع</small>
                    </div>
                    <div class="d-flex gap-2" bis_skin_checked="1">
                        <button class="btn btn-light" onclick="exportPages()">
                            <i class="fas fa-download me-2"></i>تصدير
                        </button>
                        <a href="{{ route('admin.static-pages.create') }}" class="btn btn-light">
                            <i class="fas fa-plus me-2"></i>إضافة جديدة
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body" bis_skin_checked="1">
                @if ($pages->isEmpty())
                    <div class="empty-state" bis_skin_checked="1">
                        <div class="empty-state-icon" bis_skin_checked="1">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h5 class="empty-state-text">لا توجد صفحات ثابتة</h5>
                        <p class="text-muted">لم يتم إنشاء أي صفحات ثابتة حتى الآن</p>
                        <a href="{{ route('admin.static-pages.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>إنشاء صفحة جديدة
                        </a>
                    </div>
                @else
                    <div class="table-responsive" bis_skin_checked="1">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">
                                        <div class="form-check" bis_skin_checked="1">
                                            <input class="form-check-input" type="checkbox" id="tableSelectAll">
                                        </div>
                                    </th>
                                    <th>العنوان</th>
                                    <th>الرابط</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>تاريخ التحديث</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pages as $page)
                                    <tr data-id="{{ $page->id }}">
                                        <td>
                                            <div class="form-check" bis_skin_checked="1">
                                                <input class="form-check-input page-checkbox" type="checkbox"
                                                    value="{{ $page->id }}">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold" bis_skin_checked="1">{{ $page->title }}</div>
                                            @if ($page->meta_title)
                                                <div class="content-preview" bis_skin_checked="1">
                                                    {{ Str::limit(strip_tags($page->meta_title), 60) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <code>/page/{{ $page->slug }}</code>
                                        </td>
                                        <td>
                                            <span class="badge-status status-{{ $page->status }}">
                                                @if ($page->status == 'active')
                                                    <i class="fas fa-check-circle me-1"></i> نشط
                                                @else
                                                    <i class="fas fa-times-circle me-1"></i> غير نشط
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="text-muted small" bis_skin_checked="1">
                                                {{ $page->created_at->format('Y-m-d') }}
                                            </div>
                                            <div class="small" bis_skin_checked="1">
                                                {{ $page->created_at->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-muted small" bis_skin_checked="1">
                                                {{ $page->updated_at->format('Y-m-d') }}
                                            </div>
                                            <div class="small" bis_skin_checked="1">
                                                {{ $page->updated_at->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2" bis_skin_checked="1">
                                                <a href="{{ route('admin.static-pages.show', $page) }}"
                                                    class="btn btn-sm btn-info" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.static-pages.edit', $page) }}"
                                                    class="btn btn-sm btn-warning" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if (!in_array($page->slug, ['syas-alkhsosy', 'syas-alastrgaaa', 'aldman', 'mn-nhn', 'alshrot-oalahkam']))
                                                    <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                        data-id="{{ $page->id }}" data-name="{{ $page->title }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- الترقيم -->
                    @if ($pages->hasPages())
                        <div class="mt-3" bis_skin_checked="1">
                            <nav>
                                <ul class="pagination justify-content-center">
                                    {{-- Previous Page Link --}}
                                    @if ($pages->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link">‹</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $pages->previousPageUrl() }}">‹</a>
                                        </li>
                                    @endif

                                    {{-- Pagination Elements --}}
                                    @foreach ($pages->links()->elements[0] as $page => $url)
                                        @if ($page == $pages->currentPage())
                                            <li class="page-item active">
                                                <span class="page-link">{{ $page }}</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                            </li>
                                        @endif
                                    @endforeach

                                    {{-- Next Page Link --}}
                                    @if ($pages->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $pages->nextPageUrl() }}">›</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">›</span>
                                        </li>
                                    @endif
                                </ul>
                            </nav>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // اختيار/إلغاء اختيار الكل
            $('#selectAll, #tableSelectAll').on('change', function() {
                const isChecked = $(this).prop('checked');
                $('.page-checkbox').prop('checked', isChecked);
                updateSelectedCount();
            });

            // تحديث العدد المحدد
            $('.page-checkbox').on('change', function() {
                updateSelectedCount();
            });

            // حذف صفحة
            $('.delete-btn').on('click', function() {
                const pageId = $(this).data('id');
                const pageName = $(this).data('name');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف الصفحة "${pageName}" نهائياً`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#14213d',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.static-pages.destroy', '') }}/" + pageId,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف',
                                    text: response.success,
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: xhr.responseJSON?.error ||
                                        'حدث خطأ أثناء الحذف',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    }
                });
            });

            // رسائل التنبيه من الجلسة
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
        });

        function updateSelectedCount() {
            const selectedIds = [];
            $('.page-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            $('#selectedIds').val(JSON.stringify(selectedIds));
            $('#selectedCount').text(selectedIds.length);
        }

        function applyFilters() {
            const search = $('input[name="search"]').val();
            const status = $('#statusFilter').val();
            const url = new URL(window.location.href);

            if (search) {
                url.searchParams.set('search', search);
            } else {
                url.searchParams.delete('search');
            }

            if (status) {
                url.searchParams.set('status', status);
            } else {
                url.searchParams.delete('status');
            }

            url.searchParams.set('page', '1');
            window.location.href = url.toString();
        }

        function exportPages() {
            // هنا يمكن إضافة وظيفة التصدير
            Swal.fire({
                icon: 'info',
                title: 'قريباً',
                text: 'ستتوفر هذه الميزة قريباً',
                timer: 1500,
                showConfirmButton: false
            });
        }
    </script>
@endsection
