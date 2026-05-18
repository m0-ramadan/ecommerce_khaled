@extends('Admin.layout.master')

@section('title', 'الأسئلة الشائعة')

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
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

        /* FAQ Question */
        .faq-question-cell {
            max-width: 300px;
        }

        .faq-question-text {
            font-weight: 600;
            color: #fff;
            display: block;
            margin-bottom: 3px;
        }

        .faq-answer-preview {
            color: rgba(255, 255, 255, 0.5);
            font-size: 12px;
            line-height: 1.4;
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
            0%,
            100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
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

        .btn-action.btn-duplicate {
            background: rgba(108, 117, 125, 0.15);
            color: #adb5bd;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        .btn-action.btn-duplicate:hover {
            background: rgba(108, 117, 125, 0.3);
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.2);
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

        /* Quick Edit Modal */
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

        /* Responsive */
        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: 1fr;
            }

            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .table-responsive {
                border-radius: 10px;
            }

            .faq-question-cell {
                max-width: 200px;
            }

            .bulk-actions-bar {
                flex-wrap: wrap;
            }
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

        /* FAQ Modal Details */
        .faq-modal-summary {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .faq-modal-label {
            color: rgba(255, 255, 255, 0.55);
            font-size: 12px;
            margin-bottom: 6px;
        }

        .faq-modal-value {
            color: #fff;
            font-weight: 600;
        }

        .faq-answer-box {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            padding: 18px;
            color: rgba(255, 255, 255, 0.86);
            line-height: 1.8;
            max-height: 360px;
            overflow-y: auto;
        }

        .modal-content .invalid-feedback {
            color: #ff7b8a;
            display: block;
        }

        .required::after {
            content: " *";
            color: var(--danger-color);
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
                <li class="breadcrumb-item active text-white">الأسئلة الشائعة</li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1">
                        <i class="fas fa-question-circle me-2" style="color: var(--primary-color);"></i>
                        الأسئلة الشائعة
                    </h4>
                    <p class="text-muted mb-0">إدارة الأسئلة الشائعة وتنظيمها</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.faqs.export') }}" class="btn btn-outline-info">
                        <i class="fas fa-file-export me-1"></i> تصدير CSV
                    </a>
                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> إضافة سؤال جديد
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="stats-row">
            <div class="stat-card stat-total">
                <i class="fas fa-layer-group stat-icon"></i>
                <div class="stat-number">{{ $statistics['total'] }}</div>
                <div class="stat-label">إجمالي الأسئلة</div>
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
        </div>

        <!-- Toolbar -->
        <div class="toolbar-card">
            <form action="{{ route('admin.faqs.index') }}" method="GET" id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="search" class="form-label small">بحث</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: rgba(255,255,255,0.05); border-color: rgba(255,255,255,0.1); color: rgba(255,255,255,0.5);">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="search" name="search"
                                value="{{ request('search') }}" placeholder="ابحث عن سؤال...">
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
                        <label for="per_page" class="form-label small">عرض</label>
                        <select class="form-select" id="per_page" name="per_page">
                            <option value="10" {{ request('per_page', 15) == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-1"></i> تصفية
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-redo me-1"></i> إعادة تعيين
                        </a>
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
                        قائمة الأسئلة الشائعة
                    </h5>
                    <span class="badge bg-primary rounded-pill">{{ $faqs->total() }} سؤال</span>
                </div>
                <small class="text-muted">
                    <i class="fas fa-grip-vertical me-1"></i> اسحب الصفوف لإعادة الترتيب
                </small>
            </div>

            @if ($faqs->count() > 0)
                <form id="bulkActionForm" action="{{ route('admin.faqs.bulk-action') }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" id="bulkActionType">
                    <input type="hidden" name="ids" id="bulkActionIds">

                    <div class="table-responsive">
                        <table class="table table-hover" id="faqsTable">
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
                                    <th class="sortable" data-sort="question">
                                        السؤال
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable" data-sort="status">
                                        الحالة
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th class="sortable" data-sort="created_at">
                                        تاريخ الإنشاء
                                        <i class="fas fa-sort"></i>
                                    </th>
                                    <th width="200">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody id="sortableTableBody">
                                @foreach ($faqs as $faq)
                                    <tr data-id="{{ $faq->id }}" data-sort="{{ $faq->sort_order }}">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input faq-checkbox" type="checkbox"
                                                    value="{{ $faq->id }}" onchange="updateBulkBar()">
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">#{{ $faq->id }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="drag-handle">
                                                    <i class="fas fa-grip-vertical"></i>
                                                </span>
                                                <span class="sort-order-badge">{{ $faq->sort_order }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="faq-question-cell">
                                                <span class="faq-question-text">{{ $faq->short_question }}</span>
                                                <span class="faq-answer-preview">{{ $faq->short_answer }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <label class="toggle-switch-sm" onclick="event.stopPropagation()">
                                                <input type="checkbox" {{ $faq->status ? 'checked' : '' }}
                                                    onchange="toggleFaqStatus({{ $faq->id }}, this)">
                                                <span class="toggle-slider-sm"></span>
                                            </label>
                                            <span class="me-2 small">{{ $faq->status_text }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $faq->created_at->format('Y-m-d') }}
                                            </small>
                                            <br>
                                            <small class="text-muted">
                                                <i class="far fa-clock me-1"></i>
                                                {{ $faq->created_at->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <button type="button" class="btn-action btn-toggle"
                                                    onclick="toggleFaqStatus({{ $faq->id }})"
                                                    title="{{ $faq->status ? 'تعطيل' : 'تفعيل' }}">
                                                    <i class="fas {{ $faq->status ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-view"
                                                    onclick="openViewModal({{ $faq->id }})" title="عرض">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-edit"
                                                    onclick="openEditModal({{ $faq->id }})" title="تعديل">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-duplicate"
                                                    onclick="duplicateFaq({{ $faq->id }})" title="نسخ">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <button type="button" class="btn-action btn-delete"
                                                    onclick="deleteFaq({{ $faq->id }})" title="حذف">
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
                        عرض <strong>{{ $faqs->firstItem() ?? 0 }}</strong> -
                        <strong>{{ $faqs->lastItem() ?? 0 }}</strong>
                        من أصل <strong>{{ $faqs->total() }}</strong> سؤال
                    </div>
                    <div>
                        {{ $faqs->links() }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-question-circle"></i>
                    <h5>لا توجد أسئلة شائعة حالياً</h5>
                    <p>ابدأ بإضافة أول سؤال شائع للموقع</p>
                    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-plus me-1"></i> إضافة سؤال جديد
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- View FAQ Modal -->
    <div class="modal fade" id="viewFaqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white">
                        <i class="fas fa-eye text-info me-2"></i>
                        عرض السؤال الشائع
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-white">
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="faq-modal-summary">
                                <div class="faq-modal-label">رقم السؤال</div>
                                <div class="faq-modal-value" id="viewFaqId"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="faq-modal-summary">
                                <div class="faq-modal-label">الترتيب</div>
                                <div class="faq-modal-value" id="viewFaqSortOrder"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="faq-modal-summary">
                                <div class="faq-modal-label">الحالة</div>
                                <div class="faq-modal-value" id="viewFaqStatus"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="faq-modal-label">السؤال</div>
                        <h5 class="text-white mb-0" id="viewFaqQuestion"></h5>
                    </div>

                    <div>
                        <div class="faq-modal-label">الإجابة</div>
                        <div class="faq-answer-box" id="viewFaqAnswer"></div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <div class="faq-modal-label">تاريخ الإنشاء</div>
                            <div class="faq-modal-value" id="viewFaqCreatedAt"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="faq-modal-label">آخر تحديث</div>
                            <div class="faq-modal-value" id="viewFaqUpdatedAt"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">إغلاق</button>
                    <button type="button" class="btn btn-warning" id="viewFaqEditButton">
                        <i class="fas fa-edit me-1"></i> تعديل
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit FAQ Modal -->
    <div class="modal fade" id="editFaqModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form id="editFaqForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="edit_faq_id" id="editFaqId" value="{{ old('edit_faq_id') }}">
                    <div class="modal-header">
                        <h5 class="modal-title text-white">
                            <i class="fas fa-edit text-warning me-2"></i>
                            تعديل السؤال الشائع
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-white">
                        <div class="mb-3">
                            <label for="editFaqQuestion" class="form-label required">السؤال</label>
                            <textarea class="form-control @error('question') is-invalid @enderror" id="editFaqQuestion" name="question"
                                rows="3" maxlength="500" required>{{ old('question') }}</textarea>
                            @error('question')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="editFaqAnswer" class="form-label required">الإجابة</label>
                            <textarea class="form-control @error('answer') is-invalid @enderror" id="editFaqAnswer" name="answer"
                                rows="8" required>{{ old('answer') }}</textarea>
                            @error('answer')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="editFaqSortOrder" class="form-label">الترتيب</label>
                                <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                    id="editFaqSortOrder" name="sort_order" min="0" value="{{ old('sort_order') }}">
                                @error('sort_order')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block required">الحالة</label>
                                <div class="toggle-container">
                                    <label class="toggle-switch-sm mb-0">
                                        <input type="hidden" name="status" value="0">
                                        <input type="checkbox" id="editFaqStatus" name="status" value="1"
                                            {{ old('status', '1') == '1' ? 'checked' : '' }}>
                                        <span class="toggle-slider-sm"></span>
                                    </label>
                                    <span id="editFaqStatusText">نشط</span>
                                </div>
                                @error('status')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> حفظ التعديل
                        </button>
                    </div>
                </form>
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
                    <p>هل أنت متأكد من حذف هذا السؤال الشائع؟</p>
                    <p class="text-danger small">لا يمكن التراجع عن هذا الإجراء.</p>
                    <p class="mb-0"><strong id="deleteFaqName"></strong></p>
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
    @php
        $faqModalData = $faqs->getCollection()->mapWithKeys(function ($faq) {
            return [
                $faq->id => [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'sort_order' => $faq->sort_order,
                    'status' => (bool) $faq->status,
                    'status_text' => $faq->status_text,
                    'created_at' => $faq->created_at->format('Y-m-d H:i'),
                    'updated_at' => $faq->updated_at->format('Y-m-d H:i'),
                ],
            ];
        });
    @endphp
    <script>
        const faqsData = @json($faqModalData);
        const faqUpdateUrlTemplate = @json(route('admin.faqs.update', '__FAQ_ID__'));

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

        /**
         * Update sort orders after drag & drop
         */
        function updateSortOrders() {
            const rows = $('#sortableTableBody tr');
            const orders = [];

            rows.each(function(index) {
                const id = $(this).data('id');
                const newOrder = index + 1;

                // Update badge
                $(this).find('.sort-order-badge').text(newOrder);
                $(this).data('sort', newOrder);

                orders.push({
                    id: id,
                    sort_order: newOrder
                });
            });

            // Send to server
            $.ajax({
                url: '{{ route('admin.faqs.update-order') }}',
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

            // Toggle sort order
            const newSortOrder = (currentUrl.searchParams.get('sort_by') === sortBy && currentSortOrder ===
                'asc') ? 'desc' : 'asc';

            // Update URL
            currentUrl.searchParams.set('sort_by', sortBy);
            currentUrl.searchParams.set('sort_order', newSortOrder);

            // Remove sorted class from all
            $('.sortable').removeClass('sorted');
            // Add sorted class to current
            $(this).addClass('sorted');

            // Update icon
            const icon = $(this).find('i');
            if (newSortOrder === 'asc') {
                icon.removeClass('fa-sort fa-sort-down').addClass('fa-sort-up');
            } else {
                icon.removeClass('fa-sort fa-sort-up').addClass('fa-sort-down');
            }

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
                if (sortOrder === 'asc') {
                    icon.removeClass('fa-sort').addClass('fa-sort-up');
                } else {
                    icon.removeClass('fa-sort').addClass('fa-sort-down');
                }
            }
        });

        // ============================================
        // ⭐ CHECKBOX / BULK ACTIONS
        // ============================================
        function toggleSelectAll(selectAllCheckbox) {
            $('.faq-checkbox').prop('checked', selectAllCheckbox.checked);
            updateBulkBar();
        }

        function updateBulkBar() {
            const checkedCount = $('.faq-checkbox:checked').length;
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
            $('.faq-checkbox').prop('checked', false);
            $('#selectAll').prop('checked', false);
            updateBulkBar();
        }

        function bulkAction(action) {
            const checkedIds = $('.faq-checkbox:checked').map(function() {
                return $(this).val();
            }).get();

            if (checkedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'لا توجد عناصر محددة',
                    text: 'يرجى تحديد الأسئلة المطلوبة أولاً',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            let confirmTitle, confirmText, confirmButtonText, confirmIcon;

            switch (action) {
                case 'delete':
                    confirmTitle = 'حذف الأسئلة المحددة؟';
                    confirmText = `سيتم حذف ${checkedIds.length} سؤال. لا يمكن التراجع عن هذا الإجراء.`;
                    confirmButtonText = 'نعم، احذف';
                    confirmIcon = 'warning';
                    break;
                case 'activate':
                    confirmTitle = 'تفعيل الأسئلة المحددة؟';
                    confirmText = `سيتم تفعيل ${checkedIds.length} سؤال.`;
                    confirmButtonText = 'نعم، فعل';
                    confirmIcon = 'info';
                    break;
                case 'deactivate':
                    confirmTitle = 'تعطيل الأسئلة المحددة؟';
                    confirmText = `سيتم تعطيل ${checkedIds.length} سؤال.`;
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
        // ⭐ FAQ ACTIONS
        // ============================================
        function getFaq(id) {
            return faqsData[id] || faqsData[String(id)];
        }

        function statusBadgeHtml(faq) {
            const className = faq.status ? 'badge-active' : 'badge-inactive';
            return `<span class="badge-status ${className}">${faq.status_text}</span>`;
        }

        function openViewModal(id) {
            const faq = getFaq(id);

            if (!faq) {
                showToast('تعذر العثور على بيانات السؤال', 'error');
                return;
            }

            $('#viewFaqId').text(`#${faq.id}`);
            $('#viewFaqSortOrder').text(faq.sort_order);
            $('#viewFaqStatus').html(statusBadgeHtml(faq));
            $('#viewFaqQuestion').text(faq.question);
            $('#viewFaqAnswer').html(faq.answer || '<span class="text-muted">لا توجد إجابة</span>');
            $('#viewFaqCreatedAt').text(faq.created_at);
            $('#viewFaqUpdatedAt').text(faq.updated_at);
            $('#viewFaqEditButton').off('click').on('click', function() {
                bootstrap.Modal.getInstance(document.getElementById('viewFaqModal')).hide();
                openEditModal(id);
            });

            const viewModal = new bootstrap.Modal(document.getElementById('viewFaqModal'));
            viewModal.show();
        }

        function openEditModal(id, oldValues = null) {
            const faq = getFaq(id);

            if (!faq && !oldValues) {
                showToast('تعذر العثور على بيانات السؤال', 'error');
                return;
            }

            const values = oldValues || faq;
            const faqId = values.id || id;
            const statusValue = oldValues ? oldValues.status === '1' : Boolean(values.status);

            $('#editFaqForm').attr('action', faqUpdateUrlTemplate.replace('__FAQ_ID__', faqId));
            $('#editFaqId').val(faqId);
            $('#editFaqQuestion').val(values.question || '');
            $('#editFaqAnswer').val(values.answer || '');
            $('#editFaqSortOrder').val(values.sort_order ?? '');
            $('#editFaqStatus').prop('checked', statusValue);
            $('#editFaqStatusText').text(statusValue ? 'نشط' : 'غير نشط');

            const editModal = new bootstrap.Modal(document.getElementById('editFaqModal'));
            editModal.show();
        }

        function deleteFaq(id) {
            const faqName = $(`tr[data-id="${id}"] .faq-question-text`).text();

            $('#deleteFaqName').text(faqName);
            $('#deleteForm').attr('action', `{{ route('admin.faqs.destroy', '') }}/${id}`);

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            deleteModal.show();
        }

        function duplicateFaq(id) {
            Swal.fire({
                title: 'نسخ السؤال؟',
                text: 'سيتم إنشاء نسخة من هذا السؤال مع إضافة "(نسخة)" إلى العنوان',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، انسخ',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ route('admin.faqs.duplicate', '') }}/${id}`;
                    form.innerHTML = '@csrf';
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function toggleFaqStatus(id, checkbox = null) {
            // If checkbox is provided, use its state
            const newStatus = checkbox ? checkbox.checked : !$(checkbox).prop('checked');

            $.ajax({
                url: `{{ route('admin.faqs.toggle-status', '') }}/${id}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH'
                },
                success: function(response) {
                    if (response.success) {
                        showToast(response.message, 'success');

                        // Update the row status badge
                        const row = $(`tr[data-id="${id}"]`);
                        const statusCell = row.find('td').eq(4);
                        const toggleSwitch = statusCell.find('.toggle-switch-sm input');

                        if (response.status) {
                            toggleSwitch.prop('checked', true);
                            statusCell.find('span.me-2').text('نشط');
                            row.find('.btn-toggle i').removeClass('fa-toggle-off').addClass('fa-toggle-on');
                        } else {
                            toggleSwitch.prop('checked', false);
                            statusCell.find('span.me-2').text('غير نشط');
                            row.find('.btn-toggle i').removeClass('fa-toggle-on').addClass('fa-toggle-off');
                        }

                        const faq = getFaq(id);
                        if (faq) {
                            faq.status = Boolean(response.status);
                            faq.status_text = response.status ? 'نشط' : 'غير نشط';
                        }
                    }
                },
                error: function() {
                    // Revert checkbox
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                    }
                    showToast('حدث خطأ أثناء تغيير الحالة', 'error');
                }
            });
        }

        // ============================================
        // ⭐ TOAST NOTIFICATION
        // ============================================
        function showToast(message, type = 'success') {
            // Remove existing toasts
            $('.custom-toast').remove();

            const toast = $(`
                <div class="custom-toast" style="border-right-color: ${type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'}">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}" 
                       style="color: ${type === 'success' ? 'var(--success-color)' : 'var(--danger-color)'}"></i>
                    <span>${message}</span>
                </div>
            `);

            $('body').append(`<div class="toast-container"></div>`);
            $('.toast-container').append(toast);

            // Auto remove after 3 seconds
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
            // Ctrl+F to focus search
            if (e.ctrlKey && e.key === 'f') {
                e.preventDefault();
                $('#search').focus();
            }

            // Escape to clear selection
            if (e.key === 'Escape') {
                clearSelection();
            }
        });

        // ============================================
        // ⭐ AUTO SUBMIT FILTER ON SELECT CHANGE
        // ============================================
        $('#status, #per_page').change(function() {
            $('#filterForm').submit();
        });

        $('#editFaqStatus').change(function() {
            $('#editFaqStatusText').text(this.checked ? 'نشط' : 'غير نشط');
        });

        // Search with debounce
        let searchTimeout;
        $('#search').on('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                $('#filterForm').submit();
            }, 500); // Wait 500ms after user stops typing
        });

        @if ($errors->any() && old('edit_faq_id'))
            $(document).ready(function() {
                openEditModal(@json(old('edit_faq_id')), {
                    id: @json(old('edit_faq_id')),
                    question: @json(old('question')),
                    answer: @json(old('answer')),
                    sort_order: @json(old('sort_order')),
                    status: @json(old('status', '0'))
                });
            });
        @endif
    </script>
@endsection
