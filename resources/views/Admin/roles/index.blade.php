@extends('Admin.layout.master')

@section('title', 'إدارة الرتب')

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

        .role-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
            padding: 30px;
            border: 1px solid #e2e8f0;
            margin-bottom: 30px;
        }

        .role-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            border-radius: 15px 15px 0 0;
            margin-bottom: 25px;
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

        .icon-admin {
            background: rgba(220, 53, 69, 0.2);
            color: #ef4444;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .icon-editor {
            background: rgba(12, 99, 228, 0.2);
            color: #0284c7;
            border: 1px solid rgba(12, 99, 228, 0.3);
        }

        .icon-viewer {
            background: rgba(30, 58, 95, 0.2);
            color: #14213d;
            border: 1px solid rgba(30, 58, 95, 0.3);
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

        .role-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border: 1px solid #e2e8f0;
            border-left: 4px solid transparent;
        }

        .role-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(15, 23, 42, 0.05);
            background: rgba(20, 33, 61, 0.1);
            border-color: var(--primary-color);
        }

        .role-item.super-admin {
            border-left-color: #ef4444;
        }

        .role-item.admin {
            border-left-color: #f59e0b;
        }

        .role-item.editor {
            border-left-color: #22b8cf;
        }

        .role-item.viewer {
            border-left-color: #14213d;
        }

        .role-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 13px;
            display: inline-block;
        }

        .badge-super-admin {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(253, 126, 20, 0.2) 100%);
            color: #f59e0b;
            border: 1px solid rgba(253, 126, 20, 0.3);
        }

        .badge-admin {
            background: rgba(133, 100, 4, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .badge-editor {
            background: rgba(12, 84, 96, 0.2);
            color: #22b8cf;
            border: 1px solid rgba(13, 202, 240, 0.3);
        }

        .badge-viewer {
            background: linear-gradient(135deg, rgba(20, 33, 61, 0.2) 0%, rgba(30, 58, 95, 0.2) 100%);
            color: #14213d;
            border: 1px solid rgba(30, 58, 95, 0.3);
        }

        .role-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-label {
            font-weight: 600;
            color: #1e293b;
            min-width: 100px;
        }

        .detail-value {
            color: #1e293b;
        }

        .permissions-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .permission-badge {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 4px 12px;
            font-size: 12px;
            color: #1e293b;
        }

        .permission-badge:hover {
            background: rgba(20, 33, 61, 0.2);
            color: #1e293b;
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

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #14213d 0%, #1e3a5f 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(20, 33, 61, 0.4);
        }

        @media (max-width: 768px) {
            .role-details {
                grid-template-columns: 1fr;
            }

            .permissions-list {
                justify-content: center;
            }
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
                <li class="breadcrumb-item active">الرتب</li>
            </ol>
        </nav>

        <!-- الإحصائيات -->
        <div class="row mb-4" bis_skin_checked="1">
            <div class="col-lg-3 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-total" bis_skin_checked="1">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ number_format($stats['total']) }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">إجمالي الرتب</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-admin" bis_skin_checked="1">
                        <i class="fas fa-crown"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ number_format($stats['super_admins']) }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">مشرفين رئيسيين</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-editor" bis_skin_checked="1">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ number_format($stats['admins']) }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">مديرين</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-viewer" bis_skin_checked="1">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ number_format($stats['editors']) }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">محررين</div>
                </div>
            </div>
        </div>

        <!-- البحث -->
        <div class="row mb-4" bis_skin_checked="1">
            <div class="col-12" bis_skin_checked="1">
                <div class="role-card" bis_skin_checked="1">
                    <div class="d-flex justify-content-between align-items-center mb-4" bis_skin_checked="1">
                        <div bis_skin_checked="1">
                            <h5 class="mb-1">إدارة الرتب</h5>
                            <small class="opacity-75">عرض وتعديل صلاحيات المستخدمين</small>
                        </div>
                        <div class="d-flex gap-3" bis_skin_checked="1">
                            <div class="search-box" bis_skin_checked="1">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control" placeholder="بحث عن رتبة..." id="searchInput"
                                    value="{{ request('search') }}">
                            </div>
                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>إضافة رتبة جديدة
                            </a>
                        </div>
                    </div>

                    <div class="card-body" bis_skin_checked="1">
                        @if ($roles->isEmpty())
                            <div class="empty-state" bis_skin_checked="1">
                                <div class="empty-state-icon" bis_skin_checked="1">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <h5 class="empty-state-text">لا توجد رتب</h5>
                                <p class="text-muted">لم يتم إنشاء أي رتب حتى الآن</p>
                                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>إضافة أول رتبة
                                </a>
                            </div>
                        @else
                            @foreach ($roles as $role)
                                <div class="role-item {{ $role->name }}" bis_skin_checked="1">
                                    <div class="d-flex justify-content-between align-items-start mb-3" bis_skin_checked="1">
                                        <div bis_skin_checked="1">
                                            <h6 class="mb-2">{{ $role->display_name ?: $role->name }}</h6>
                                            <div class="d-flex align-items-center gap-3" bis_skin_checked="1">
                                                <span class="role-badge badge-{{ $role->name }}">
                                                    {{ $role->name }}
                                                </span>
                                                @if ($role->is_default)
                                                    <span class="badge bg-success">
                                                        <i class="fas fa-star me-1"></i>افتراضي
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-muted" bis_skin_checked="1">
                                            <small>
                                                <i class="far fa-clock me-1"></i>
                                                {{ $role->created_at->translatedFormat('d M Y') }}
                                            </small>
                                        </div>
                                    </div>

                                    <div class="role-details" bis_skin_checked="1">
                                        <div class="detail-item" bis_skin_checked="1">
                                            <span class="detail-label">الوصف:</span>
                                            <span class="detail-value">
                                                {{ $role->description ?? 'لا يوجد وصف' }}
                                            </span>
                                        </div>

                                        <div class="detail-item" bis_skin_checked="1">
                                            <span class="detail-label">عدد الصلاحيات:</span>
                                            <span class="detail-value">
                                                {{ $role->permissions_count }} صلاحية
                                            </span>
                                        </div>

                                        <div class="detail-item" bis_skin_checked="1">
                                            <span class="detail-label">عدد المستخدمين:</span>
                                            <span class="detail-value">
                                                {{ $role->users_count }} مستخدم
                                            </span>
                                        </div>
                                    </div>

                                    @if ($role->permissions->count() > 0)
                                        <div class="mb-3" bis_skin_checked="1">
                                            <small class="text-muted d-block mb-2">الصلاحيات الممنوحة:</small>
                                            <div class="permissions-list" bis_skin_checked="1">
                                                @foreach ($role->permissions->take(8) as $permission)
                                                    <span class="permission-badge">
                                                        <i class="fas fa-key me-1"></i>
                                                        {{ $permission->display_name }}
                                                    </span>
                                                @endforeach
                                                @if ($role->permissions->count() > 8)
                                                    <span class="permission-badge">
                                                        +{{ $role->permissions->count() - 8 }} أخرى
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex gap-2" bis_skin_checked="1">
                                        <a href="{{ route('admin.roles.show', $role) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye me-1"></i>عرض التفاصيل
                                        </a>
                                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit me-1"></i>تعديل
                                        </a>
                                        @if (!$role->is_default && $role->name !== 'super_admin')
                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-id="{{ $role->id }}"
                                                data-name="{{ $role->display_name ?: $role->name }}">
                                                <i class="fas fa-trash me-1"></i>حذف
                                            </button>
                                        @endif
                                        <a href="{{ route('admin.roles.permissions', $role) }}"
                                            class="btn btn-sm btn-secondary">
                                            <i class="fas fa-key me-1"></i>إدارة الصلاحيات
                                        </a>
                                    </div>
                                </div>
                            @endforeach

                            @if ($roles->hasPages())
                                <div class="mt-4">
                                    <nav>
                                        <ul class="pagination justify-content-center">
                                            {{-- Previous Page Link --}}
                                            @if ($roles->onFirstPage())
                                                <li class="page-item disabled" aria-disabled="true">
                                                    <span class="page-link waves-effect" aria-hidden="true">‹</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link waves-effect"
                                                        href="{{ $roles->previousPageUrl() }}" rel="prev">‹</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($roles->links()->elements[0] as $page => $url)
                                                @if ($page == $roles->currentPage())
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
                                            @if ($roles->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link waves-effect" href="{{ $roles->nextPageUrl() }}"
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // البحث مع تأخير
            let searchTimeout;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchValue = $(this).val();
                    const url = new URL(window.location.href);
                    if (searchValue) {
                        url.searchParams.set('search', searchValue);
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                }, 500);
            });

            // حذف الرتبة
            $('.delete-btn').on('click', function() {
                const roleId = $(this).data('id');
                const roleName = $(this).data('name');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف الرتبة "${roleName}" نهائياً`,
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
                            url: "{{ route('admin.roles.destroy', '') }}/" + roleId,
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
                                    text: xhr.responseJSON?.message ||
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
    </script>
@endsection
