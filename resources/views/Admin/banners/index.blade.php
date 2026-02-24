@extends('Admin.layout.master')

@section('title', 'إدارة البنرات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sortable/0.8.0/css/sortable-theme-bootstrap.min.css">
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: #28a745;
            --danger-gradient: #dc3545;
            --warning-gradient: #ffc107;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
            --text-muted: #6c757d;
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            /* background: #f8f9fa; */
        }

        /* بطاقات الإحصائيات المتطورة */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            /* background: white; */
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
            border: 1px solid rgba(0, 0, 0, 0.03);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(105, 108, 255, 0.15);
        }

        .stat-info h3 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .stat-info p {
            color: #7f8c8d;
            margin: 0;
            font-size: 14px;
            font-weight: 500;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }

        .stat-icon.banners {
            background: var(--primary-gradient);
            color: white;
        }

        .stat-icon.categories {
            background: var(--success-gradient);
            color: #2c3e50;
        }

        .stat-icon.products {
            background: var(--warning-gradient);
            color: #2c3e50;
        }

        .stat-icon.promos {
            background: var(--danger-gradient);
            color: #2c3e50;
        }

        /* شريط الأدوات المتقدم */
        .advanced-toolbar {
            /* background: white; */
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.03);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            justify-content: space-between;
        }

        .toolbar-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-toolbar {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            /* background: white; */
            color: #495057;
        }

        .btn-toolbar:hover {
            background: #f8f9fa;
            border-color: #dee2e6;
        }

        .btn-toolbar.primary {
            background: #696cff;
            color: white;
            border: none;
        }

        .btn-toolbar.primary:hover {
            background: #5a5cdb;
        }

        /* مربع البحث المتقدم */
        .search-advanced {
            position: relative;
            min-width: 300px;
        }

        .search-advanced input {
            width: 100%;
            padding: 12px 20px 12px 45px;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            /* background: #f8f9fa; */
            transition: all 0.3s ease;
        }

        .search-advanced input:focus {
            background: white;
            border-color: #696cff;
            box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
            outline: none;
        }

        .search-advanced i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #adb5bd;
        }

        /* فلاتر متقدمة */
        .filters-wrapper {
            /* background: white; */
            border-radius: 15px;
            padding: 15px 20px;
            margin-bottom: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            border: 1px solid #e9ecef;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .filter-badge {
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid #e9ecef;
            background: white;
        }

        .filter-badge:hover {
            background: #f8f9fa;
        }

        .filter-badge.active {
            background: #696cff;
            color: white;
            border-color: #696cff;
        }

        .filter-badge i {
            margin-left: 5px;
            font-size: 12px;
        }

        /* الجدول الرئيسي */
        .table-wrapper {
            /* background: white; */
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            /* background: #f8f9fa; */
            color: #495057;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 18px 15px;
            border-bottom: 2px solid #e9ecef;
        }

        .table tbody td {
            padding: 20px 15px;
            vertical-align: middle;
            border-bottom: 1px solid #e9ecef;
        }

        /* معاينة البانر المحسنة */
        .banner-preview-cell {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .banner-thumb {
            width: 90px;
            height: 60px;
            border-radius: 12px;
            overflow: hidden;
            position: relative;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .banner-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .banner-thumb:hover img {
            transform: scale(1.1);
        }

        .banner-thumb-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .banner-info h6 {
            font-weight: 600;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .banner-meta {
            display: flex;
            gap: 15px;
            font-size: 12px;
            color: #7f8c8d;
        }

        .banner-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* بطاقة الارتباط بالقسم/المنتج */
        .relation-card {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .relation-card:hover {
            background: white;
            border-color: #696cff;
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.1);
        }

        .relation-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .relation-icon {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .relation-icon.category {
            background: #e7f5ff;
            color: #0c63e4;
        }

        .relation-icon.product {
            background: #d4edda;
            color: #155724;
        }

        .relation-icon.promo {
            background: #fff3cd;
            color: #856404;
        }

        .relation-title {
            font-weight: 600;
            font-size: 14px;
            color: #2c3e50;
        }

        .relation-path {
            font-size: 12px;
            color: #7f8c8d;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .relation-path i {
            font-size: 10px;
        }

        /* شارات الحالة */
        .status-badge {
            padding: 6px 15px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-badge.active {
            background: #d4edda;
            color: #155724;
        }

        .status-badge.inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .status-badge.expired {
            background: #e2e3e5;
            color: #383d41;
        }

        .status-badge.scheduled {
            background: #cce5ff;
            color: #004085;
        }

        /* عداد العناصر */
        .items-counter {
            background: #696cff;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        /* شارات النوع */
        .type-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .type-badge.slider {
            background: #667eea;
            color: white;
        }

        .type-badge.grid {
            background: #20c997;
            color: white;
        }

        .type-badge.static {
            background: #6c757d;
            color: white;
        }

        /* زر الإجراءات */
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: all 0.3s ease;
        }

        .btn-icon:hover {
            transform: translateY(-2px);
        }

        /* زر السحب للترتيب */
        .drag-handle {
            color: #adb5bd;
            cursor: move;
            padding: 0 10px;
            font-size: 18px;
        }

        .drag-handle:hover {
            color: #696cff;
        }

        /* القائمة المنسدلة للتصفية */
        .filter-dropdown {
            position: relative;
            display: inline-block;
        }

        .filter-dropdown-content {
            display: none;
            position: absolute;
            background: white;
            min-width: 220px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            z-index: 1000;
            padding: 10px 0;
            margin-top: 5px;
            right: 0;
        }

        .filter-dropdown:hover .filter-dropdown-content {
            display: block;
        }

        .filter-item {
            padding: 10px 20px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #495057;
        }

        .filter-item:hover {
            background: #f8f9fa;
            color: #696cff;
        }

        .filter-item.active {
            background: #696cff;
            color: white;
        }

        /* عرض مسار القسم الكامل */
        .category-path {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
        }

        .category-path i {
            font-size: 10px;
            color: #adb5bd;
        }

        /* تاجات العناصر */
        .item-tag {
            background: #f8f9fa;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: 1px solid #e9ecef;
        }

        .item-tag i {
            color: #696cff;
        }

        /* حالة البانر حسب الوقت */
        .time-status {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 12px;
            background: #f8f9fa;
        }

        /* تصميم متجاوب */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .advanced-toolbar {
                flex-direction: column;
                align-items: stretch;
            }

            .search-advanced {
                min-width: auto;
            }

            .banner-preview-cell {
                flex-direction: column;
                text-align: center;
            }

            .action-buttons {
                justify-content: center;
            }
        }

        /* أنيميشن للعناصر الجديدة */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .banner-row {
            animation: slideIn 0.3s ease;
        }

        /* شارة العدد */
        .count-badge {
            background: #e9ecef;
            color: #495057;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
            margin-left: 8px;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- مسار التنقل -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item active">البنرات</li>
            </ol>
        </nav>

        <!-- الإحصائيات المتقدمة -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-info">
                    <h3>{{ $banners->total() }}</h3>
                    <p>إجمالي البنرات</p>
                </div>
                <div class="stat-icon banners">
                    <i class="fas fa-images"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    <h3>{{ $banners->where('is_active', true)->count() }}</h3>
                    <p>البنرات النشطة</p>
                </div>
                <div class="stat-icon categories">
                    <i class="fas fa-check-circle"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    @php
                        $categoriesCount = $banners
                            ->filter(function ($banner) {
                                return $banner->category_id ||
                                    $banner->items->where('category_id', '!=', null)->count() > 0;
                            })
                            ->count();
                    @endphp
                    <h3>{{ $categoriesCount }}</h3>
                    <p>مرتبطة بأقسام</p>
                </div>
                <div class="stat-icon products">
                    <i class="fas fa-tags"></i>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-info">
                    @php
                        $productsCount = $banners
                            ->filter(function ($banner) {
                                return $banner->items->where('product_id', '!=', null)->count() > 0;
                            })
                            ->count();
                    @endphp
                    <h3>{{ $productsCount }}</h3>
                    <p>مرتبطة بمنتجات</p>
                </div>
                <div class="stat-icon promos">
                    <i class="fas fa-cube"></i>
                </div>
            </div>
        </div>

        <!-- شريط الأدوات المتقدم -->
        <div class="advanced-toolbar">
            <div class="toolbar-actions">
                <a href="{{ route('admin.banners.create') }}" class="btn-toolbar primary">
                    <i class="fas fa-plus me-2"></i>
                    إضافة بانر جديد
                </a>
                <button class="btn-toolbar" onclick="exportBanners()">
                    <i class="fas fa-download me-2"></i>
                    تصدير
                </button>
                <div class="filter-dropdown">
                    <button class="btn-toolbar">
                        <i class="fas fa-sliders-h me-2"></i>
                        إجراءات جماعية
                        <i class="fas fa-chevron-down ms-2"></i>
                    </button>
                    <div class="filter-dropdown-content">
                        <div class="filter-item" onclick="bulkAction('activate')">
                            <i class="fas fa-check-circle"></i>
                            تفعيل المحدد
                        </div>
                        <div class="filter-item" onclick="bulkAction('deactivate')">
                            <i class="fas fa-times-circle"></i>
                            إلغاء تفعيل المحدد
                        </div>
                        <div class="filter-item" onclick="bulkAction('delete')">
                            <i class="fas fa-trash"></i>
                            حذف المحدد
                        </div>
                    </div>
                </div>
            </div>

            <div class="search-advanced">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="بحث بالعنوان، النوع، القسم، المنتج..."
                    value="{{ request('search') }}">
            </div>
        </div>

        <!-- الفلاتر المتقدمة -->
        <div class="filters-wrapper">
            <div class="filter-group">
                <span
                    class="filter-badge {{ !request('type') && !request('status') && !request('category') ? 'active' : '' }}"
                    onclick="filterBy('all')">
                    <i class="fas fa-list"></i>
                    الكل
                </span>
                <span class="filter-badge {{ request('type') == 'slider' ? 'active' : '' }}"
                    onclick="filterByType('slider')">
                    <i class="fas fa-sliders-h"></i>
                    سلايدر
                </span>
                <span class="filter-badge {{ request('type') == 'grid' ? 'active' : '' }}" onclick="filterByType('grid')">
                    <i class="fas fa-th-large"></i>
                    شبكة
                </span>
                <span class="filter-badge {{ request('type') == 'static' ? 'active' : '' }}"
                    onclick="filterByType('static')">
                    <i class="fas fa-image"></i>
                    ثابت
                </span>
            </div>

            <div class="filter-group">
                <span class="filter-badge {{ request('status') == 'active' ? 'active' : '' }}"
                    onclick="filterByStatus('active')">
                    <i class="fas fa-check-circle"></i>
                    نشط
                </span>
                <span class="filter-badge {{ request('status') == 'inactive' ? 'active' : '' }}"
                    onclick="filterByStatus('inactive')">
                    <i class="fas fa-times-circle"></i>
                    غير نشط
                </span>
                <span class="filter-badge {{ request('status') == 'expired' ? 'active' : '' }}"
                    onclick="filterByStatus('expired')">
                    <i class="fas fa-clock"></i>
                    منتهي
                </span>
                <span class="filter-badge {{ request('status') == 'scheduled' ? 'active' : '' }}"
                    onclick="filterByStatus('scheduled')">
                    <i class="fas fa-calendar-alt"></i>
                    مجدول
                </span>
            </div>

            <div class="filter-group">
                <span class="filter-badge {{ request('has_category') ? 'active' : '' }}"
                    onclick="filterByRelation('has_category')">
                    <i class="fas fa-tag"></i>
                    مرتبط بأقسام
                </span>
                <span class="filter-badge {{ request('has_product') ? 'active' : '' }}"
                    onclick="filterByRelation('has_product')">
                    <i class="fas fa-cube"></i>
                    مرتبط بمنتجات
                </span>
                <span class="filter-badge {{ request('has_promo') ? 'active' : '' }}"
                    onclick="filterByRelation('has_promo')">
                    <i class="fas fa-percent"></i>
                    مرتبط بكوبونات
                </span>
            </div>
        </div>

        <!-- الجدول الرئيسي -->
        <div class="table-wrapper">
            <table class="table" id="bannersTable">
                <thead>
                    <tr>
                        <th width="40">
                            <input type="checkbox" id="selectAll" class="form-check-input">
                        </th>
                        <th width="40">#</th>
                        <th>الترتيب</th>
                        <th>معلومات البانر</th>
                        <th>النوع والربط</th>
                        <th>عناصر البانر</th>
                        <th>الحالة والفترة</th>
                        <th width="120">الإجراءات</th>
                    </tr>
                </thead>
                <tbody id="sortableBody">
                    @forelse($banners as $index => $banner)
                        <tr class="banner-row" data-id="{{ $banner->id }}" data-order="{{ $banner->section_order }}">
                            <td>
                                <input type="checkbox" class="row-checkbox form-check-input"
                                    value="{{ $banner->id }}">
                            </td>
                            <td>{{ $loop->iteration + $banners->perPage() * ($banners->currentPage() - 1) }}</td>
                            <td>
                                <div class="drag-handle">
                                    <i class="fas fa-grip-vertical"></i>
                                    <span class="badge bg-light text-dark ms-2">{{ $banner->section_order }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="banner-preview-cell">
                                    <div class="banner-thumb">
                                        @php
                                            $firstItem = $banner->items->sortBy('item_order')->first();
                                        @endphp
                                        @if ($firstItem && $firstItem->image_url)
                                            <img src="{{ get_user_image($firstItem->image_url) }}"
                                                alt="{{ $firstItem->image_alt }}">
                                        @else
                                            <div class="banner-thumb-placeholder">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="banner-info">
                                        <h6>{{ $banner->title }}</h6>
                                        <div class="banner-meta">
                                            <span>
                                                <i class="fas fa-hashtag"></i>
                                                ID: {{ $banner->id }}
                                            </span>
                                            <span>
                                                <i class="fas fa-clock"></i>
                                                {{ $banner->created_at }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <!-- نوع البانر -->
                                    <span class="type-badge {{ $banner->type ? $banner->type->name : 'static' }}">
                                        <i
                                            class="fas 
                                            {{ $banner->type && $banner->type->name == 'slider' ? 'fa-sliders-h' : '' }}
                                            {{ $banner->type && $banner->type->name == 'grid' ? 'fa-th-large' : '' }}
                                            {{ !$banner->type || $banner->type->name == 'static' ? 'fa-image' : '' }}">
                                        </i>
                                        {{ $banner->type ? $banner->type->name : 'ثابت' }}
                                    </span>

                                    <!-- ربط القسم الرئيسي للبانر -->
                                    @if ($banner->category_id)
                                        <div class="relation-card">
                                            <div class="relation-header">
                                                <div class="relation-icon category">
                                                    <i class="fas fa-tag"></i>
                                                </div>
                                                <div class="relation-title">قسم رئيسي</div>
                                            </div>
                                            <div class="relation-path">
                                                <i class="fas fa-folder-open"></i>
                                                @if ($banner->category)
                                                    {{ $banner->category->name }}
                                                    @if ($banner->category->parent)
                                                        <i class="fas fa-chevron-left"></i>
                                                        {{ $banner->category->parent->name }}
                                                    @endif
                                                @endif
                                            </div>
                                            @if ($banner->category && $banner->category->full_slug)
                                                <div class="category-path">
                                                    <i class="fas fa-link"></i>
                                                    {{ $banner->category->full_slug }}
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <!-- عرض العناصر المرتبطة المباشرة -->
                                    @php
                                        $linkedCategories = $banner->items
                                            ->where('category_id', '!=', null)
                                            ->pluck('category')
                                            ->filter();
                                        $linkedProducts = $banner->items
                                            ->where('product_id', '!=', null)
                                            ->pluck('product')
                                            ->filter();
                                    @endphp

                                    @if ($linkedCategories->count() > 0)
                                        <div class="relation-card">
                                            <div class="relation-header">
                                                <div class="relation-icon category">
                                                    <i class="fas fa-tags"></i>
                                                </div>
                                                <div class="relation-title">
                                                    أقسام مرتبطة
                                                    <span class="count-badge">{{ $linkedCategories->count() }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($linkedCategories->take(2) as $category)
                                                    <span class="item-tag">
                                                        <i class="fas fa-tag"></i>
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                                @if ($linkedCategories->count() > 2)
                                                    <span class="item-tag">
                                                        +{{ $linkedCategories->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if ($linkedProducts->count() > 0)
                                        <div class="relation-card">
                                            <div class="relation-header">
                                                <div class="relation-icon product">
                                                    <i class="fas fa-cube"></i>
                                                </div>
                                                <div class="relation-title">
                                                    منتجات مرتبطة
                                                    <span class="count-badge">{{ $linkedProducts->count() }}</span>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-wrap gap-1">
                                                @foreach ($linkedProducts->take(2) as $product)
                                                    <span class="item-tag">
                                                        <i class="fas fa-cube"></i>
                                                        {{ $product->name }}
                                                    </span>
                                                @endforeach
                                                @if ($linkedProducts->count() > 2)
                                                    <span class="item-tag">
                                                        +{{ $linkedProducts->count() - 2 }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="items-counter">
                                        <i class="fas fa-layer-group"></i>
                                        {{ $banner->items->count() }}
                                    </span>
                                    @if ($banner->items->count() > 0)
                                        <button class="btn btn-sm btn-outline-primary preview-items"
                                            data-banner-id="{{ $banner->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- عرض مختصر للعناصر -->
                                @if ($banner->items->count() > 0)
                                    <div class="d-flex flex-column gap-1">
                                        @foreach ($banner->items->sortBy('item_order')->take(2) as $item)
                                            <div class="item-tag w-100 justify-content-between">
                                                <span>
                                                    @if ($item->category_id)
                                                        <i class="fas fa-tag text-info"></i>
                                                    @elseif($item->product_id)
                                                        <i class="fas fa-cube text-success"></i>
                                                    @else
                                                        <i class="fas fa-image text-secondary"></i>
                                                    @endif
                                                    @if ($item->category)
                                                        {{ $item->category->name }}
                                                    @elseif($item->product)
                                                        {{ $item->product->name }}
                                                    @else
                                                        عنصر {{ $item->item_order }}
                                                    @endif
                                                </span>
                                                <small class="text-muted">#{{ $item->item_order }}</small>
                                            </div>
                                        @endforeach
                                        @if ($banner->items->count() > 2)
                                            <small class="text-muted text-center">
                                                +{{ $banner->items->count() - 2 }} عناصر أخرى
                                            </small>
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <!-- حالة التفعيل -->
                                <div class="mb-2">
                                    @php
                                        $now = now();
                                        $status = 'active';
                                        $statusClass = 'active';
                                        $statusText = 'نشط';

                                        if (!$banner->is_active) {
                                            $status = 'inactive';
                                            $statusClass = 'inactive';
                                            $statusText = 'غير نشط';
                                        } elseif ($banner->end_date && $banner->end_date < $now) {
                                            $status = 'expired';
                                            $statusClass = 'expired';
                                            $statusText = 'منتهي';
                                        } elseif ($banner->start_date && $banner->start_date > $now) {
                                            $status = 'scheduled';
                                            $statusClass = 'scheduled';
                                            $statusText = 'مجدول';
                                        }
                                    @endphp

                                    <span class="status-badge {{ $statusClass }}">
                                        <i
                                            class="fas 
                                            {{ $status == 'active' ? 'fa-check-circle' : '' }}
                                            {{ $status == 'inactive' ? 'fa-times-circle' : '' }}
                                            {{ $status == 'expired' ? 'fa-clock' : '' }}
                                            {{ $status == 'scheduled' ? 'fa-calendar-alt' : '' }}">
                                        </i>
                                        {{ $statusText }}
                                    </span>
                                </div>

                                <!-- الفترة الزمنية -->
                                @if ($banner->start_date || $banner->end_date)
                                    <div class="small">
                                        @if ($banner->start_date)
                                            <div class="text-muted">
                                                <i class="fas fa-play"></i>
                                                {{ $banner->start_date->format('Y-m-d') }}
                                            </div>
                                        @endif
                                        @if ($banner->end_date)
                                            <div class="text-muted">
                                                <i class="fas fa-stop"></i>
                                                {{ $banner->end_date->format('Y-m-d') }}
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <span class="time-status">
                                        <i class="fas fa-infinity"></i>
                                        دائم
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('admin.banners.show', $banner) }}" class="btn-icon btn-info"
                                        title="عرض التفاصيل">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn-icon btn-warning"
                                        title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" class="btn-icon btn-danger delete-btn" title="حذف"
                                        data-id="{{ $banner->id }}" data-title="{{ $banner->title }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    {{-- <a href="{{ route('admin.banners.items.index', $banner->id) }}"  --}}
                                    <a href="#" class="btn-icon btn-success" title="إدارة العناصر">
                                        <i class="fas fa-layer-group"></i>
                                    </a>
                                </div>

                                <!-- تبديل الحالة السريع -->
                                <div class="mt-2 text-center">
                                    <label class="switch">
                                        <input type="checkbox" class="status-toggle" data-id="{{ $banner->id }}"
                                            {{ $banner->is_active ? 'checked' : '' }}>
                                        <span class="slider round"></span>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-images fa-4x text-muted"></i>
                                    </div>
                                    <h5 class="mt-3">لا توجد بنرات</h5>
                                    <p class="text-muted">لم يتم إضافة أي بنرات بعد. قم بإضافة أول بانر الآن</p>
                                    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-plus me-2"></i>
                                        إضافة بانر جديد
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($banners->hasPages())
                <div class="m-3">
                    <nav>
                        <ul class="pagination">
                            {{-- Previous Page Link --}}
                            @if ($banners->onFirstPage())
                                <li class="page-item disabled" aria-disabled="true">
                                    <span class="page-link waves-effect" aria-hidden="true">‹</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="{{ $banners->previousPageUrl() }}"
                                        rel="prev">‹</a>
                                </li>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($banners->links()->elements[0] as $page => $url)
                                @if ($page == $banners->currentPage())
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
                            @if ($banners->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link waves-effect" href="{{ $banners->nextPageUrl() }}"
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

    <!-- مودال عرض العناصر -->
    <div class="modal fade" id="itemsPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">عناصر البانر</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="itemsPreviewContent">
                    <!-- يتم تحميل المحتوى عبر AJAX -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // تهيئة السحب والترتيب
            const sortableBody = document.getElementById('sortableBody');
            if (sortableBody) {
                new Sortable(sortableBody, {
                    animation: 150,
                    ghostClass: 'bg-light',
                    dragClass: 'shadow-lg',
                    handle: '.drag-handle',
                    onEnd: function(evt) {
                        updateBannersOrder();
                    }
                });
            }

            // تحديث ترتيب البنرات
            function updateBannersOrder() {
                const items = [];
                $('#sortableBody tr').each(function(index) {
                    const bannerId = $(this).data('id');
                    items.push({
                        id: bannerId,
                        order: index + 1
                    });
                });

                $.ajax({
                    url: "{{ route('admin.banners.update-order') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        banners: items
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotification('تم حفظ الترتيب بنجاح', 'success');
                            updateOrderNumbers();
                        }
                    },
                    error: function() {
                        showNotification('حدث خطأ أثناء حفظ الترتيب', 'error');
                    }
                });
            }

            // تحديث أرقام الترتيب المعروضة
            function updateOrderNumbers() {
                $('#sortableBody tr').each(function(index) {
                    $(this).find('.drag-handle .badge').text(index + 1);
                });
            }

            // البحث
            let searchTimeout;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    updateUrl({
                        search: $(this).val()
                    });
                }, 500);
            });

            // تبديل حالة البانر
            $('.status-toggle').on('change', function() {
                const bannerId = $(this).data('id');
                const isChecked = $(this).is(':checked');

                $.ajax({
                    url: "{{ route('admin.banners.toggle-status', '') }}/" + bannerId,
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'PATCH'
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotification('تم تغيير حالة البانر بنجاح', 'success');
                        }
                    },
                    error: function() {
                        showNotification('حدث خطأ أثناء تغيير الحالة', 'error');
                    }
                });
            });

            // حذف البانر
            $('.delete-btn').on('click', function() {
                const bannerId = $(this).data('id');
                const bannerTitle = $(this).data('title');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف البانر "${bannerTitle}" وجميع عناصره`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.banners.destroy', '') }}/" + bannerId,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                showNotification('تم حذف البانر بنجاح', 'success');
                                setTimeout(() => location.reload(), 1500);
                            },
                            error: function() {
                                showNotification('حدث خطأ أثناء الحذف', 'error');
                            }
                        });
                    }
                });
            });

            // اختيار الكل
            $('#selectAll').on('change', function() {
                $('.row-checkbox').prop('checked', $(this).is(':checked'));
            });

            // عرض معاينة العناصر
            $('.preview-items').on('click', function() {
                const bannerId = $(this).data('banner-id');

                $.get(`/admin/banners/${bannerId}`, function(response) {
                    let html = '<div class="list-group">';

                    response.item.forEach(item => {
                        html += `
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>#${item.item_order}</strong>
                                        ${item.image_url ? '<img src="' + getImageUrl(item.image_url) + '" style="width: 50px; height: 30px; object-fit: cover; border-radius: 4px;">' : ''}
                                        ${item.category ? '<span class="badge bg-info"><i class="fas fa-tag"></i> ' + item.category.name + '</span>' : ''}
                                        ${item.product ? '<span class="badge bg-success"><i class="fas fa-cube"></i> ' + item.product.name + '</span>' : ''}
                                    </div>
                                    <div>
                                        <span class="badge bg-${item.is_active ? 'success' : 'secondary'}">
                                            ${item.is_active ? 'نشط' : 'غير نشط'}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    html += '</div>';
                    $('#itemsPreviewContent').html(html);
                    $('#itemsPreviewModal').modal('show');
                });
            });

            // إظهار الإشعارات
            function showNotification(message, type) {
                Swal.fire({
                    icon: type,
                    title: type === 'success' ? 'تم بنجاح' : 'خطأ',
                    text: message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            // تصدير البنرات
            window.exportBanners = function() {
                window.location.href = "{{ route('admin.banners.export') }}" + window.location.search;
            };

            // الإجراءات الجماعية
            window.bulkAction = function(action) {
                const selectedIds = $('.row-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedIds.length === 0) {
                    showNotification('الرجاء تحديد بنر واحد على الأقل', 'error');
                    return;
                }

                let confirmText = '';
                let actionText = '';

                switch (action) {
                    case 'activate':
                        confirmText = 'سيتم تفعيل البنرات المحددة';
                        actionText = 'تفعيل';
                        break;
                    case 'deactivate':
                        confirmText = 'سيتم إلغاء تفعيل البنرات المحددة';
                        actionText = 'إلغاء تفعيل';
                        break;
                    case 'delete':
                        confirmText = 'سيتم حذف البنرات المحددة نهائياً';
                        actionText = 'حذف';
                        break;
                }

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: confirmText,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: action === 'delete' ? '#d33' : '#3085d6',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `نعم، ${actionText}`,
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.banners.bulk-actions') }}",
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                action: action,
                                ids: selectedIds
                            },
                            success: function(response) {
                                showNotification(response.message, 'success');
                                setTimeout(() => location.reload(), 1500);
                            },
                            error: function() {
                                showNotification('حدث خطأ أثناء المعالجة', 'error');
                            }
                        });
                    }
                });
            };

            // دوال التصفية
            window.filterBy = function(type) {
                updateUrl({
                    type: null,
                    status: null,
                    has_category: null,
                    has_product: null,
                    has_promo: null
                });
            };

            window.filterByType = function(type) {
                updateUrl({
                    type: type,
                    status: null,
                    has_category: null,
                    has_product: null,
                    has_promo: null
                });
            };

            window.filterByStatus = function(status) {
                updateUrl({
                    type: null,
                    status: status,
                    has_category: null,
                    has_product: null,
                    has_promo: null
                });
            };

            window.filterByRelation = function(relation) {
                const params = {};
                params[relation] = true;
                updateUrl(params);
            };

            function updateUrl(params) {
                const url = new URL(window.location.href);
                Object.keys(params).forEach(key => {
                    if (params[key] === null || params[key] === '') {
                        url.searchParams.delete(key);
                    } else {
                        url.searchParams.set(key, params[key]);
                    }
                });
                url.searchParams.set('page', '1');
                window.location.href = url.toString();
            }

            function getImageUrl(path) {
                return '/storage/' + path;
            }
        });
    </script>

    <style>
        /* إضافات CSS للعناصر الجديدة */
        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #696cff;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .empty-state {
            padding: 60px 20px;
        }

        .empty-state-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 20px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* تخصيص شريط التمرير */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    </style>
@endsection
