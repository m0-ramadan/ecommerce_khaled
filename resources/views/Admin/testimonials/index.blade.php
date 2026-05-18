@extends('Admin.layout.master')

@section('title', 'آراء العملاء')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
            --star-color: #ffc107;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        /* Page Header */
        .page-header {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border-left: 4px solid var(--primary-color);
        }

        .page-header h4 {
            color: #fff;
            margin-bottom: 5px;
        }

        /* Statistics Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        .stat-card .stat-icon {
            position: absolute;
            left: 20px;
            top: 20px;
            font-size: 40px;
            opacity: 0.15;
        }

        .stat-card .stat-number {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
        }

        .stat-card.stat-total {
            border-bottom: 3px solid var(--primary-color);
        }
        .stat-card.stat-total .stat-number {
            color: var(--primary-color);
        }

        .stat-card.stat-active {
            border-bottom: 3px solid var(--success-color);
        }
        .stat-card.stat-active .stat-number {
            color: var(--success-color);
        }

        .stat-card.stat-inactive {
            border-bottom: 3px solid var(--danger-color);
        }
        .stat-card.stat-inactive .stat-number {
            color: var(--danger-color);
        }

        .stat-card.stat-rating {
            border-bottom: 3px solid var(--star-color);
        }
        .stat-card.stat-rating .stat-number {
            color: var(--star-color);
        }

        /* Toolbar */
        .toolbar-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        /* Form Controls */
        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .form-select option {
            background: var(--dark-card);
            color: #fff;
        }

        /* Table Card */
        .table-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
        }

        .table-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
            flex-wrap: wrap;
            gap: 15px;
        }

        /* Table Styles */
        .table {
            color: #fff;
            margin-bottom: 0;
        }

        .table thead th {
            background: rgba(105, 108, 255, 0.1);
            border-bottom: 2px solid var(--primary-color);
            color: #fff;
            font-weight: 600;
            white-space: nowrap;
            padding: 12px 15px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .table thead th:hover {
            background: rgba(105, 108, 255, 0.2);
        }

        .table thead th i {
            margin-right: 5px;
            font-size: 12px;
            opacity: 0.5;
            transition: all 0.3s;
        }

        .table thead th.sorted i {
            opacity: 1;
            color: var(--primary-color);
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: rgba(255, 255, 255, 0.05);
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover {
            background: rgba(105, 108, 255, 0.05);
        }

        /* Drag Handle */
        .drag-handle {
            cursor: move;
            color: rgba(255, 255, 255, 0.3);
            font-size: 18px;
            padding: 5px 10px;
            transition: all 0.2s;
        }

        .drag-handle:hover {
            color: var(--primary-color);
        }

        .sortable-ghost {
            opacity: 0.4;
            background: rgba(105, 108, 255, 0.1) !important;
        }

        .sortable-chosen {
            background: rgba(105, 108, 255, 0.08) !important;
        }

        /* Customer Info */
        .customer-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 220px;
        }

        .customer-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .customer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .customer-avatar-placeholder {
            font-size: 20px;
            color: var(--primary-color);
            font-weight: bold;
        }

        .customer-info .customer-name {
            font-weight: 600;
            color: #fff;
            display: block;
        }

        .customer-info .customer-city {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        /* Stars Rating */
        .stars-display {
            display: flex;
            gap: 2px;
            direction: ltr;
            justify-content: flex-start;
        }

        .stars-display .star {
            color: rgba(255, 255, 255, 0.2);
            font-size: 16px;
        }

        .stars-display .star.filled {
            color: var(--star-color);
        }

        .stars-display .star.half {
            position: relative;
            color: rgba(255, 255, 255, 0.2);
        }

        .stars-display .star.half::before {
            content: '\f005';
            position: absolute;
            left: 0;
            width: 50%;
            overflow: hidden;
            color: var(--star-color);
        }

        /* Review Preview */
        .review-preview {
            max-width: 300px;
            color: rgba(255, 255, 255, 0.7);
            font-size: 13px;
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Status Badges */
        .badge-status {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-active {
            background: rgba(40, 167, 69, 0.15);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .badge-active::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #28a745;
            animation: pulse 2s infinite;
        }

        .badge-inactive {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .badge-inactive::before {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #dc3545;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Rating Badge */
        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            background: rgba(255, 193, 7, 0.1);
            color: var(--star-color);
            border: 1px solid rgba(255, 193, 7, 0.2);
        }

        .rating-badge.high {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
            border-color: rgba(40, 167, 69, 0.2);
        }

        .rating-badge.medium {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
            border-color: rgba(255, 193, 7, 0.2);
        }

        .rating-badge.low {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            border-color: rgba(220, 53, 69, 0.2);
        }

        /* Action Buttons */
        .btn-action {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.3s;
            margin: 0 3px;
        }

        .btn-action:hover {
            transform: translateY(-2px);
        }

        .btn-action.btn-view {
            background: rgba(23, 162, 184, 0.15);
            color: #17a2b8;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }
        .btn-action.btn-view:hover {
            background: rgba(23, 162, 184, 0.3);
            box-shadow: 0 5px 15px rgba(23, 162, 184, 0.2);
        }

        .btn-action.btn-edit {
            background: rgba(255, 193, 7, 0.15);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }
        .btn-action.btn-edit:hover {
            background: rgba(255, 193, 7, 0.3);
            box-shadow: 0 5px 15px rgba(255, 193, 7, 0.2);
        }

        .btn-action.btn-delete {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }
        .btn-action.btn-delete:hover {
            background: rgba(220, 53, 69, 0.3);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.2);
        }

        .btn-action.btn-toggle {
            background: rgba(105, 108, 255, 0.15);
            color: var(--primary-color);
            border: 1px solid rgba(105, 108, 255, 0.3);
        }
        .btn-action.btn-toggle:hover {
            background: rgba(105, 108, 255, 0.3);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.2);
        }

        /* Checkbox */
        .form-check-input {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        /* Bulk Actions Bar */
        .bulk-actions-bar {
            background: rgba(105, 108, 255, 0.1);
            border-radius: 10px;
            padding: 12px 20px;
            margin-bottom: 15px;
            display: none;
            align-items: center;
            gap: 15px;
            border: 1px solid rgba(105, 108, 255, 0.3);
            animation: slideDown 0.3s ease;
        }

        .bulk-actions-bar.show {
            display: flex;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .selected-count {
            background: var(--primary-gradient);
            color: #fff;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        /* Sort Order Badge */
        .sort-order-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.7);
            font-weight: 600;
            font-size: 14px;
        }

        /* Pagination */
        .pagination {
            gap: 5px;
        }

        .page-item .page-link {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 8px;
            padding: 8px 15px;
            transition: all 0.3s;
        }

        .page-item .page-link:hover {
            background: rgba(105, 108, 255, 0.2);
            border-color: var(--primary-color);
        }

        .page-item.active .page-link {
            background: var(--primary-gradient);
            border-color: transparent;
        }

        .page-item.disabled .page-link {
            background: rgba(255, 255, 255, 0.02);
            color: rgba(255, 255, 255, 0.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 80px;
            color: rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: rgba(255, 255, 255, 0.3);
        }

        /* Quick View Modal */
        .modal-content {
            background: var(--dark-card);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-footer {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-close {
            filter: invert(1);
        }

        /* Review Card in Modal */
        .review-modal-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .review-modal-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            border: 3px solid var(--primary-color);
        }

        .review-modal-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Toggle Switch for inline status change */
        .toggle-switch-sm {
            position: relative;
            width: 44px;
            height: 22px;
            display: inline-block;
        }

        .toggle-switch-sm input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider-sm {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.2);
            transition: 0.3s;
            border-radius: 34px;
        }

        .toggle-slider-sm:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider-sm {
            background-color: var(--success-color);
        }

        input:checked+.toggle-slider-sm:before {
            transform: translateX(22px);
        }

        /* Toast */
        .toast-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
        }

        .custom-toast {
            background: var(--dark-card);
            border-radius: 10px;
            padding: 15px 20px;
            color: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border-right: 4px solid var(--success-color);
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideInRight 0.3s ease;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
            }

            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .customer-cell {
                min-width: 180px;
            }

            .review-preview {
                max-width: 180px;
            }

            .bulk-actions-bar {
                flex-wrap: wrap;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}" class="text-primary">الرئيسية</a>
                </li>
                <li class="breadcrumb-item active text-white">آراء العملاء</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-star me-2" style="color: var(--star-color);"></i>
                        آراء العملاء
                    </h4>
                    <p class="text-muted mb-0">إدارة تقييمات وآراء العملاء</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.testimonials.export') }}" class="btn btn-outline-info">
                        <i class="fas fa-file-export me-1"></i> تصدير CSV
                    </a>
                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> إضافة رأي جديد
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-card stat-total">
                <i class="fas fa-users stat-icon"></i>
                <div class="stat-number">{{ $statistics['total'] }}</div>
                <div class="stat-label">إجمالي الآراء</div>
            </div>
            <div class="stat-card stat-active">
                <i class="fas fa-check-circle stat-icon"></i>
                <div class="stat-number">{{ $statistics['active'] }}</div>
                <div class="stat-label">نشط</div>
            </div>
            <div class="stat-card stat-inactive">
                <i class="fas fa-times-circle stat-icon"></i>
                <div class="stat-number">{{ $statistics['inactive'] }}</div>
                <div class="stat-label">غير نشط</div>
            </div>
            <div class="stat-card stat-rating">
                <i class="fas fa-star stat-icon"></i>
                <div class="stat-number">{{ number_format($statistics['avg_rating'], 1) }}</div>
                <div class="stat-label">متوسط التقييم</div>
            </div>
        </div>

        <!-- Toolbar -->
        <div class="toolbar-card">
            <form action="{{ route('admin.testimonials.index') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="search" class="form-label small">بحث</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5);">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="اسم العميل، المدينة، المراجعة...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label small">الحالة</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">الكل</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="rating" class="form-label small">التقييم</label>
                        <select class="form-select" id="rating" name="rating">
                            <option value="">الكل</option>
                            <option value="5" {{ request('rating') === '5' ? 'selected' : '' }}>⭐ 5 نجوم</option>
                            <option value="4" {{ request('rating') === '4' ? 'selected' : '' }}>⭐ 4 نجوم</option>
                            <option value="3" {{ request('rating') === '3' ? 'selected' : '' }}>⭐ 3 نجوم</option>
                            <option value="2" {{ request('rating') === '2' ? 'selected' : '' }}>⭐ 2 نجوم</option>
                            <option value="1" {{ request('rating') === '1' ? 'selected' : '' }}>⭐ 1 نجمة</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="per_page" class="form-label small">عرض</label>
                        <select class="form-select" id="per_page" name="per_page">
                            <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-filter me-1"></i> تصفية
                            </button>
                            <a href="{{ route('admin.testimonials.index') }}" class="btn btn-outline-secondary flex-grow-1">
                                <i class="fas fa-redo me-1"></i> إعادة تعيين
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert"
                style="background: rgba(40,167,69,0.1); border-color: rgba(40,167,69,0.3); color: #28a745;">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="background: rgba(220,53,69,0.1); border-color: rgba(220,53,69,0.3); color: #dc3545;">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Table -->
        <div class="table-card">
            <!-- Bulk Actions Bar -->
            <div class="bulk-actions-bar" id="bulkActionsBar">
                <span class="selected-count" id="selectedCount">0 محدد</span>
                <button type="button" class="btn btn-sm btn-success" onclick="bulkAction('activate')">
                    <i class="fas fa-check me-1"></i> تفعيل
                </button>
                <button type="button" class="btn btn-sm btn-warning" onclick="bulkAction('deactivate')">
                    <i class="fas fa-times me-1"></i> تعطيل
                </button>
                <button type="button" class="btn btn-sm btn-danger" onclick="bulkAction('delete')">
                    <i class="fas fa-trash me-1"></i> حذف
                </button>
                <button type="button" class="btn btn-sm btn-outline-light ms-auto" onclick="clearSelection()">
                    <i class="fas fa-times me-1"></i> إلغاء التحديد
                </button>
            </div>

            <!-- Table Header -->
            <div class="table-header">
                <div class="d-flex align-items-center gap-3">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2" style="color: var(--primary-color);"></i>
                        قائمة آراء العملاء
                    </h5>
                    <span class="badge bg-primary rounded-pill">{{ $testimonials->total() }} رأي</span>
                </div>
                <small class="text-muted">
                    <i class="fas fa-grip-vertical me-1"></i> اسحب الصفوف لإعادة الترتيب
                </small>
            </div>

            @if ($testimonials->count() > 0)
                <form id="bulkActionForm" action="{{ route('admin.testimonials.bulk-action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" id="bulkActionType">
                    <input type="hidden" name="ids" id="bulkActionIds">

                    <div class="table-responsive">
                        <table class="table table-hover" id="testimonialsTable">
                            <thead>
                                <tr>
                                    <th width="40">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="selectAll"
                                                onchange="toggleSelectAll(this)">
                                        </div>
                                    </th>
                                    <th width="50">#</th>
                                    <th class="sortable" data-sort="sort_order">
                                        الترتيب
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable" data-sort="name">
                                        العميل
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable" data-sort="rating">
                                        التقييم
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th>المراجعة</th>
                                    <th class="sortable" data-sort="is_active">
                                        الحالة
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable" data-sort="created_at">
                                        تاريخ الإنشاء
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th width="180">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="sortableTableBody">
                                @foreach ($testimonials as $testimonial)
                                    <tr data-id="{{ $testimonial->id }}" data-sort="{{ $testimonial->sort_order }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input testimonial-checkbox" type="checkbox"
                                                    value="{{ $testimonial->id }}" onchange="updateBulkBar()">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">#{{ $testimonial->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="drag-handle">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </span>
                                                <span class="sort-order-badge">{{ $testimonial->sort_order }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="customer-cell">
                                                <div class="customer-avatar">
                                                    @if ($testimonial->avatar)
                                                        <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                                            alt="{{ $testimonial->name }}">
                                                    @else
                                                        <span class="customer-avatar-placeholder">
                                                            {{ mb_substr($testimonial->name, 0, 1) }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="customer-info">
                                                    <span class="customer-name">{{ $testimonial->name }}</span>
                                                    @if ($testimonial->city)
                                                        <span class="customer-city">
                                                            <i class="fas fa-map-marker-alt"></i>
                                                            {{ $testimonial->city }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="stars-display">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star star {{ $i <= $testimonial->rating ? 'filled' : '' }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="rating-badge {{ $testimonial->rating >= 4 ? 'high' : ($testimonial->rating >= 3 ? 'medium' : 'low') }}">
                                                    {{ $testimonial->rating }}/5
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="review-preview">
                                                "{{ $testimonial->review }}"
                                            </div>
                                        </td>
                                        <td>
                                            <label class="toggle-switch-sm" onclick="event.stopPropagation()">
                                                <input type="checkbox" {{ $testimonial->is_active ? 'checked' : '' }}
                                                    onchange="toggleStatus({{ $testimonial->id }}, this)">
                                                <span class="toggle-slider-sm"></span>
                                            </label>
                                            <span class="me-2 small">
                                                {{ $testimonial->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $testimonial->created_at->format('Y-m-d') }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $testimonial->created_at->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <button type="button" class="btn-action btn-toggle"
                                                    onclick="toggleStatus({{ $testimonial->id }})"
                                                    title="{{ $testimonial->is_active ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas {{ $testimonial->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-view"
                                                    onclick="quickView({{ $testimonial->id }})" title="عرض سريع">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <a href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                                    class="btn-action btn-edit" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn-action btn-delete"
                                                    onclick="deleteTestimonial({{ $testimonial->id }})" title="حذف">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-3">
                    <div class="text-muted small">
                        عرض <strong>{{ $testimonials->firstItem() ?? 0 }}</strong> -
                        <strong>{{ $testimonials->lastItem() ?? 0 }}</strong>
                        من أصل <strong>{{ $testimonials->total() }}</strong> رأي
                    </div>
                    <div>
                        {{ $testimonials->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-star"></i>
                    <h5>لا توجد آراء عملاء حالياً</h5>
                    <p>ابدأ بإضافة أول رأي عميل للموقع</p>
                    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-1"></i> إضافة رأي جديد
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick View Modal -->
    <div class="modal fade" id="quickViewModel" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-star me-2" style="color: var(--star-color);"></i>
                        تفاصيل الرأي
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="quickViewContent">
                    <!-- Content loaded via AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        تأكيد الحذف
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-white">
                    <p>هل أنت متأكد من حذف هذا الرأي؟</p>
                    <p class="text-danger small">لا يمكن التراجع عن هذا الإجراء.</p>
                    <p class="mb-0"><strong id="deleteItemName"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // ============================================
        // ⭐ SORTABLE DRAG & DROP
        // ============================================
        $(document).ready(function() {
            const sortableTableBody = document.getElementById('sortableTableBody');

            if (sortableTableBody) {
                new Sortable(sortableTableBody, {
                    handle: '.drag-handle',
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function(evt) {
                        updateSortOrders();
                    }
                });
            }
        });

        function updateSortOrders() {
            const rows = $('#sortableTableBody tr');
            const orders = [];

            rows.each(function(index) {
                const id = $(this).data('id');
                const newOrder = index + 1;

                $(this).find('.sort-order-badge').text(newOrder);
                $(this).data('sort', newOrder);

                orders.push({
                    id: id,
                    sort_order: newOrder
                });
            });

            $.ajax({
                url: '{{ route('admin.testimonials.update-order') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    orders: orders
                },
                success: function(response) {
                    showToast('تم تحديث الترتيب بنجاح', 'success');
                },
                error: function() {
                    showToast('حدث خطأ أثناء تحديث الترتيب', 'error');
                }
            });
        }

        // ============================================
        // ⭐ TABLE SORTING
        // ============================================
        $('.sortable').click(function() {
            const sortBy = $(this).data('sort');
            const currentUrl = new URL(window.location.href);
            const currentSortOrder = currentUrl.searchParams.get('sort_order') || 'asc';
            const newSortOrder = (currentUrl.searchParams.get('sort_by') === sortBy && currentSortOrder === 'asc') ? 'desc' : 'asc';

            currentUrl.searchParams.set('sort_by', sortBy);
            currentUrl.searchParams.set('sort_order', newSortOrder);

            window.location.href = currentUrl.toString();
        });

        // Highlight sorted column on load
        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const sortBy = urlParams.get('sort_by');
            const sortOrder = urlParams.get('sort_order');

            if (sortBy) {
                const th = $(`.sortable[data-sort="${sortBy}"]`);
                th.addClass('sorted');
                const icon = th.find('i');
                icon.removeClass('fa-sort').addClass(sortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down');
            }
        });

        // ============================================
        // ⭐ CHECKBOX / BULK ACTIONS
        // ============================================
        function toggleSelectAll(selectAllCheckbox) {
            $('.testimonial-checkbox').prop('checked', selectAllCheckbox.checked);
            updateBulkBar();
        }

        function updateBulkBar() {
            const checkedCount = $('.testimonial-checkbox:checked').length;
            const bulkBar = $('#bulkActionsBar');

            if (checkedCount > 0) {
                bulkBar.addClass('show');
                $('#selectedCount').text(`${checkedCount} محدد`);
            } else {
                bulkBar.removeClass('show');
                $('#selectAll').prop('checked', false);
            }
        }

        function clearSelection() {
            $('.testimonial-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
            updateBulkBar();
        }

        function bulkAction(action) {
            const checkedIds = $('.testimonial-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (checkedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'لا توجد عناصر محددة',
                    text: 'يرجى تحديد الآراء المطلوبة أولاً',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            let confirmTitle, confirmText, confirmButtonText, confirmIcon;

            switch (action) {
                case 'delete':
                    confirmTitle = 'حذف الآراء المحددة؟';
                    confirmText = `سيتم حذف ${checkedIds.length} رأي. لا يمكن التراجع عن هذا الإجراء.`;
                    confirmButtonText = 'نعم، احذف';
                    confirmIcon = 'warning';
                    break;
                case 'activate':
                    confirmTitle = 'تفعيل الآراء المحددة؟';
                    confirmText = `سيتم تفعيل ${checkedIds.length} رأي.`;
                    confirmButtonText = 'نعم، فعل';
                    confirmIcon = 'info';
                    break;
                case 'deactivate':
                    confirmTitle = 'تعطيل الآراء المحددة؟';
                    confirmText = `سيتم تعطيل ${checkedIds.length} رأي.`;
                    confirmButtonText = 'نعم، عطل';
                    confirmIcon = 'info';
                    break;
            }

            Swal.fire({
                title: confirmTitle,
                text: confirmText,
                icon: confirmIcon,
                showCancelButton: true,
                confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#bulkActionType').val(action);
                    $('#bulkActionIds').val(checkedIds.join(','));
                    $('#bulkActionForm').submit();
                }
            });
        }

        // ============================================
        // ⭐ TESTIMONIAL ACTIONS
        // ============================================
        function deleteTestimonial(id) {
            const name = $(`tr[data-id="${id}"] .customer-name`).text();

            $('#deleteItemName').text(name);
            $('#deleteForm').attr('action', `{{ route('admin.testimonials.destroy', '') }}/${id}`);

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        function toggleStatus(id, checkbox = null) {
            $.ajax({
                url: `{{ route('admin.testimonials.toggle-status', '') }}/${id}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH'
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');

                        const row = $(`tr[data-id="${id}"]`);
                        const toggleSwitch = row.find('.toggle-switch-sm input');
                        const statusText = row.find('td').eq(6).find('span.me-2');

                        if (response.is_active) {
                            toggleSwitch.prop('checked', true);
                            statusText.text('نشط');
                        } else {
                            toggleSwitch.prop('checked', false);
                            statusText.text('غير نشط');
                        }
                    }
                },
                error: function() {
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                    }
                    showToast('حدث خطأ أثناء تغيير الحالة', 'error');
                }
            });
        }

        function quickView(id) {
            $.ajax({
                url: `{{ route('admin.testimonials.show', '') }}/${id}`,
                method: 'GET',
                data: { quick: 1 },
                success: function(response) {
                    const testimonial = response.testimonial;
                    let starsHtml = '';
                    for (let i = 1; i <= 5; i++) {
                        starsHtml += `<i class="fas fa-star star ${i <= testimonial.rating ? 'filled' : ''}"></i>`;
                    }

                    const html = `
                        <div class="review-modal-card text-center">
                            <div class="review-modal-avatar mx-auto mb-3">
                                ${testimonial.avatar 
                                    ? `<img src="${testimonial.avatar_url}" alt="${testimonial.name}">`
                                    : `<div class="customer-avatar-placeholder" style="width:60px;height:60px;display:flex;align-items:center;justify-content:center;font-size:24px;">${testimonial.name.charAt(0)}</div>`
                                }
                            </div>
                            <h5 class="text-white mb-1">${testimonial.name}</h5>
                            ${testimonial.city ? `<p class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i>${testimonial.city}</p>` : ''}
                            <div class="stars-display justify-content-center mb-3" style="font-size: 24px;">
                                ${starsHtml}
                            </div>
                            <span class="rating-badge high mb-3 d-inline-block">${testimonial.rating}/5</span>
                            <div class="text-white mt-3 p-3" style="background: rgba(255,255,255,0.03); border-radius: 8px; line-height: 1.8;">
                                "${testimonial.review}"
                            </div>
                            <div class="mt-3">
                                <span class="badge ${testimonial.is_active ? 'bg-success' : 'bg-secondary'}">
                                    ${testimonial.is_active ? 'نشط' : 'غير نشط'}
                                </span>
                            </div>
                        </div>
                    `;

                    $('#quickViewContent').html(html);
                    const quickViewModel = new bootstrap.Modal(document.getElementById('quickViewModel'));
                    quickViewModel.show();
                }
            });
        }

        // ============================================
        // ⭐ TOAST NOTIFICATION
        // ============================================
        function showToast(message, type = 'success') {
            $('.custom-toast').remove();

            const borderColor = type === 'success' ? 'var(--success-color)' : 'var(--danger-color)';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const iconColor = type === 'success' ? 'var(--success-color)' : 'var(--danger-color)';

            const toast = $(`
                <div class="custom-toast" style="border-right-color: ${borderColor}">
                    <i class="fas ${icon}" style="color: ${iconColor}"></i>
                    <span>${message}</span>
                </div>
            `);

            $('body').append('<div class="toast-container"></div>');
            $('.toast-container').append(toast);

            setTimeout(() => {
                toast.fadeOut(300, function() {
                    $(this).remove();
                    if ($('.custom-toast').length === 0) {
                        $('.toast-container').remove();
                    }
                });
            }, 3000);
        }

        // ============================================
        // ⭐ KEYBOARD SHORTCUTS
        // ============================================
        $(document).keydown(function(e) {
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                $('#search').focus();
            }

            if (e.key === 'Escape') {
                clearSelection();
            }
        });

        // ============================================
        // ⭐ AUTO SUBMIT FILTER ON SELECT CHANGE
        // ============================================
        $('#status, #rating, #per_page').change(function() {
            $('#filterForm').submit();
        });

        // Search with debounce
        let searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                $('#filterForm').submit();
            }, 500);
        });
    </script>
@endsection