@extends('Admin.layout.master')

@section('title', 'عرض المنتج: ' . $product->name)

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
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
            --text-light: rgba(255, 255, 255, 0.9);
            --text-muted: rgba(255, 255, 255, 0.7);
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: var(--text-light);
        }

        .product-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .product-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.1);
            transform: rotate(45deg);
        }

        .product-main-image {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            height: 400px;
            position: relative;
            background: var(--dark-card);
        }

        .product-main-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .product-main-image:hover img {
            transform: scale(1.05);
        }

        .product-main-image:hover .image-overlay-buttons {
            opacity: 1;
        }

        .product-gallery {
            margin-top: 15px;
        }

        .gallery-thumb {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            overflow: hidden;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            background: var(--dark-card);
        }

        .gallery-thumb.active {
            border-color: var(--primary-color);
        }

        .gallery-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            height: 100%;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-price-section {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .original-price {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: line-through;
        }

        .current-price {
            font-size: 32px;
            font-weight: bold;
            margin: 5px 0;
        }

        .discount-badge {
            background: var(--danger-color);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }

        .stock-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.1);
        }

        .stock-status.in-stock {
            color: #20c997;
            background: rgba(32, 201, 151, 0.1);
        }

        .stock-status.low-stock {
            color: #ffc107;
            background: rgba(255, 193, 7, 0.1);
        }

        .stock-status.out-of-stock {
            color: #dc3545;
            background: rgba(220, 53, 69, 0.1);
        }

        .stock-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .stock-indicator.in-stock {
            background-color: #20c997;
        }

        .stock-indicator.low-stock {
            background-color: #ffc107;
        }

        .stock-indicator.out-of-stock {
            background-color: #dc3545;
        }

        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            background: rgba(255, 255, 255, 0.1);
        }

        .status-active {
            color: #20c997;
        }

        .status-inactive {
            color: #dc3545;
        }

        .status-draft {
            color: #ffc107;
        }

        .detail-card {
            background: var(--dark-card);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-card h5 {
            color: var(--primary-color);
            border-bottom: 2px solid rgba(105, 108, 255, 0.3);
            padding-bottom: 10px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .detail-card h5 i {
            margin-left: 10px;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-item:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: var(--text-muted);
            font-weight: 500;
        }

        .detail-value {
            color: var(--text-light);
            font-weight: 600;
            text-align: left;
        }

        /* Product Options Styles - Enhanced */
        .options-group {
            background: rgba(105, 108, 255, 0.05);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid rgba(105, 108, 255, 0.2);
        }

        .option-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(105, 108, 255, 0.2);
        }

        .option-id {
            background: var(--primary-color);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .option-category {
            background: rgba(255, 193, 7, 0.2);
            color: var(--warning-color);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            text-transform: uppercase;
        }

        .option-required {
            background: var(--danger-color);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
        }

        .detail-item-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .detail-item-card:hover {
            background: rgba(105, 108, 255, 0.1);
            border-color: var(--primary-color);
        }

        .detail-value {
            font-weight: 600;
            color: var(--text-light);
        }

        .detail-price {
            background: var(--success-color);
            color: white;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .dependency-badge {
            background: rgba(255, 193, 7, 0.15);
            color: var(--warning-color);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 8px;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .dependency-chain {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 10px;
            margin-top: 10px;
            border-right: 3px solid var(--warning-color);
        }

        .dependency-item {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            padding: 5px 0;
        }

        .dependency-arrow {
            color: var(--warning-color);
            font-size: 14px;
        }

        .operator-badge {
            background: var(--info-color);
            color: white;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }

        .image-overlay-buttons {
            position: absolute;
            bottom: 15px;
            right: 15px;
            display: flex;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-overlay-buttons .btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: none;
        }

        .badge-new {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 12px;
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 2;
        }

        .meta-tag {
            display: inline-block;
            padding: 5px 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 5px;
            margin: 3px;
            font-size: 12px;
            color: var(--text-light);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-description {
            line-height: 1.8;
            color: var(--text-light);
        }

        .product-description img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
            margin: 10px 0;
        }

        .action-buttons {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1000;
        }

        .action-buttons .btn {
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            border: none;
        }

        .qr-code {
            width: 150px;
            height: 150px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .social-share {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 15px;
        }

        .social-share .btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            border: none;
        }

        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            right: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: rgba(255, 255, 255, 0.1);
        }

        .timeline-item {
            position: relative;
            padding-right: 40px;
            margin-bottom: 20px;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-dot {
            position: absolute;
            right: 8px;
            top: 5px;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--info-color);
            border: 3px solid var(--dark-card);
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.1);
        }

        .timeline-content {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .empty-state {
            text-align: center;
            padding: 30px;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 48px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        .review-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .rating-stars {
            color: #f1c40f;
            font-size: 18px;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" bis_skin_checked="1">
        <!-- Product Header -->
        <div class="product-header" bis_skin_checked="1">
            <div class="row align-items-center" bis_skin_checked="1">
                <div class="col-auto" bis_skin_checked="1">
                    @if ($product->created_at->gt(now()->subDays(7)))
                        <span class="badge-new">جديد</span>
                    @endif
                </div>
                <div class="col" bis_skin_checked="1">
                    <div class="d-flex justify-content-between align-items-start" bis_skin_checked="1">
                        <div bis_skin_checked="1">
                            <h1 class="mb-2">{{ $product->name }}</h1>
                            <p class="mb-1 opacity-75">
                                <i class="fas fa-hashtag"></i> ID: {{ $product->id }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-folder"></i> {{ $product->category->name ?? 'غير مصنف' }}
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar-alt"></i> {{ $product->created_at->format('Y/m/d') }}
                            </p>
                        </div>
                        <div class="btn-group" bis_skin_checked="1">
                            <button class="btn btn-light" onclick="window.print()">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="btn btn-light" onclick="shareProduct()">
                                <i class="fas fa-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row" bis_skin_checked="1">
            <!-- Left Column - Images & Basic Info -->
            <div class="col-lg-8" bis_skin_checked="1">
                <!-- Main Image & Gallery -->
                <div class="detail-card" bis_skin_checked="1">
                    <div class="row" bis_skin_checked="1">
                        <div class="col-md-8" bis_skin_checked="1">
                            <div class="product-main-image" bis_skin_checked="1">
                                <img src="{{ $product->primaryImage ? get_user_image($product->primaryImage->path) : 'https://via.placeholder.com/600x400?text=No+Image' }}"
                                    alt="{{ $product->name }}" id="mainProductImage">
                                <div class="image-overlay-buttons" bis_skin_checked="1">
                                    <button class="btn btn-primary" onclick="zoomImage()">
                                        <i class="fas fa-search-plus"></i>
                                    </button>
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- Gallery Thumbs -->
                            @if ($product->images && $product->images->count() > 0)
                                <div class="product-gallery" bis_skin_checked="1">
                                    <div class="d-flex flex-wrap gap-3" bis_skin_checked="1">
                                        @foreach ($product->images as $image)
                                            <div class="gallery-thumb {{ $loop->first ? 'active' : '' }}"
                                                onclick="changeMainImage('{{ get_user_image($image->path) }}', this)">
                                                <img src="{{ get_user_image($image->path) }}" alt="صورة المنتج">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-4" bis_skin_checked="1">
                            <div class="product-info-card" bis_skin_checked="1">
                                <!-- Price Section -->
                                <div class="product-price-section" bis_skin_checked="1">
                                    @if ($product->has_discount && $product->discount)
                                        <div class="original-price" bis_skin_checked="1">
                                            {{ number_format($product->price, 2) }} ج.م
                                        </div>
                                        <div class="current-price" bis_skin_checked="1">
                                            {{ number_format($product->final_price, 2) }} ج.م
                                        </div>
                                        <div class="discount-badge" bis_skin_checked="1">
                                            @if ($product->discount->discount_type === 'percentage')
                                                خصم {{ $product->discount->discount_value }}%
                                            @else
                                                خصم {{ number_format($product->discount->discount_value, 2) }} ج.م
                                            @endif
                                        </div>
                                    @else
                                        <div class="current-price" bis_skin_checked="1">
                                            {{ number_format($product->price, 2) }} ج.م
                                        </div>
                                    @endif
                                </div>

                                <!-- Stock Status -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <span
                                        class="stock-status {{ $product->stock == 0 ? 'out-of-stock' : ($product->stock < 10 ? 'low-stock' : 'in-stock') }}">
                                        <span
                                            class="stock-indicator {{ $product->stock == 0 ? 'out-of-stock' : ($product->stock < 10 ? 'low-stock' : 'in-stock') }}"></span>
                                        @if ($product->stock == 0)
                                            نفذ من المخزون
                                        @elseif($product->stock < 10)
                                            مخزون منخفض ({{ $product->stock }} قطعة)
                                        @else
                                            متوفر في المخزون ({{ $product->stock }} قطعة)
                                        @endif
                                    </span>
                                </div>

                                <!-- Product Status -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <span
                                        class="status-badge {{ $product->status_id == 1 ? 'status-active' : ($product->status_id == 2 ? 'status-inactive' : 'status-draft') }}">
                                        @if ($product->status_id == 1)
                                            <i class="fas fa-check-circle me-1"></i> نشط
                                        @elseif($product->status_id == 2)
                                            <i class="fas fa-times-circle me-1"></i> غير نشط
                                        @else
                                            <i class="fas fa-file-alt me-1"></i> مسودة
                                        @endif
                                    </span>
                                </div>

                                <!-- Quick Stats -->
                                <div class="row text-center mb-4" bis_skin_checked="1">
                                    <div class="col-4" bis_skin_checked="1">
                                        <div class="h4 mb-1" bis_skin_checked="1">
                                            {{ number_format($product->average_rating, 1) }}</div>
                                        <small class="text-muted">التقييم</small>
                                    </div>
                                    <div class="col-4" bis_skin_checked="1">
                                        <div class="h4 mb-1" bis_skin_checked="1">{{ $product->reviews_count ?? 0 }}
                                        </div>
                                        <small class="text-muted">التقييمات</small>
                                    </div>
                                    <div class="col-4" bis_skin_checked="1">
                                        <div class="h4 mb-1" bis_skin_checked="1">
                                            {{ $product->favouritedBy->count() ?? 0 }}</div>
                                        <small class="text-muted">المفضلة</small>
                                    </div>
                                </div>

                                <!-- Quick Actions -->
                                <div class="d-grid gap-2" bis_skin_checked="1">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-2"></i> تعديل المنتج
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                        <i class="fas fa-trash me-2"></i> حذف المنتج
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                @if ($product->description)
                    <div class="detail-card" bis_skin_checked="1">
                        <h5><i class="fas fa-align-right"></i> وصف المنتج</h5>
                        <div class="product-description" bis_skin_checked="1">
                            {!! $product->description !!}
                        </div>
                    </div>
                @endif

                <!-- ============================================= -->
                <!-- ⭐ PRODUCT OPTIONS - ONLY FROM product_options ⭐ -->
                <!-- ============================================= -->
                @if ($product->options && $product->options->count() > 0)
                    <div class="detail-card" bis_skin_checked="1">
                        <h5><i class="fas fa-cogs"></i> خيارات المنتج</h5>

                        @php
                            // Group options by external_option_id
                            $groupedOptions = $product->options->groupBy('external_option_id');

                            // Separate independent and dependent options
                            $independentOptions = $groupedOptions->filter(function ($group) {
                                return $group->first()->depends_on_option_id === null;
                            });

                            $dependentOptions = $groupedOptions->filter(function ($group) {
                                return $group->first()->depends_on_option_id !== null;
                            });
                        @endphp

                        <!-- Independent Options (Main Options) -->
                        @if ($independentOptions->count() > 0)
                            <div class="mb-4" bis_skin_checked="1">
                                <h6 class="mb-3 text-primary">
                                    <i class="fas fa-cube"></i> الخيارات الرئيسية
                                </h6>

                                @foreach ($independentOptions as $optId => $optionGroup)
                                    @php $firstOption = $optionGroup->first(); @endphp
                                    <div class="options-group" bis_skin_checked="1">
                                        <div class="option-header" bis_skin_checked="1">
                                            <span class="option-id">#{{ $firstOption->external_option_id }}</span>
                                            <span class="option-category">{{ $firstOption->category ?? 'عام' }}</span>
                                            @if ($firstOption->is_required)
                                                <span class="option-required">مطلوب</span>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between align-items-center mb-2"
                                            bis_skin_checked="1">
                                            <h6 class="mb-0">{{ $firstOption->option_name }}</h6>
                                        </div>

                                        <div class="details-grid" bis_skin_checked="1">
                                            @foreach ($optionGroup as $detail)
                                                <div class="detail-item-card" bis_skin_checked="1">
                                                    <div>
                                                        <div class="detail-value">{{ $detail->option_value }}</div>
                                                        @if ($detail->external_detail_id)
                                                            <small class="text-muted">ID:
                                                                {{ $detail->external_detail_id }}</small>
                                                        @endif
                                                    </div>
                                                    @if ($detail->additional_price > 0)
                                                        <span
                                                            class="detail-price">+{{ number_format($detail->additional_price, 2) }}
                                                            ج.م</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Dependent Options (Conditional Options) -->
                        @if ($dependentOptions->count() > 0)
                            <div class="mb-4" bis_skin_checked="1">
                                <h6 class="mb-3 text-warning">
                                    <i class="fas fa-link"></i> الخيارات المشروطة (تبعيات)
                                </h6>

                                @foreach ($dependentOptions as $optId => $optionGroup)
                                    @php
                                        $firstOption = $optionGroup->first();
                                        $parentOption = $product->options
                                            ->where('external_option_id', $firstOption->depends_on_option_id)
                                            ->first();
                                    @endphp

                                    <div class="options-group" bis_skin_checked="1"
                                        style="border-color: rgba(255, 193, 7, 0.3);">
                                        <div class="option-header" bis_skin_checked="1">
                                            <span class="option-id">#{{ $firstOption->external_option_id }}</span>
                                            <span class="option-category">{{ $firstOption->category ?? 'عام' }}</span>
                                            @if ($firstOption->is_required)
                                                <span class="option-required">مطلوب</span>
                                            @endif
                                        </div>

                                        <!-- Dependency Information -->
                                        <div class="dependency-badge" bis_skin_checked="1">
                                            <i class="fas fa-link"></i>
                                            يعتمد على:
                                            <strong>{{ $parentOption->option_name ?? 'خيار غير معروف' }}</strong>
                                            <span
                                                class="operator-badge">{{ $firstOption->dependency_operator ?? '=' }}</span>
                                            <strong>{{ $firstOption->depends_on_detail_id }}</strong>
                                        </div>

                                        <div class="details-grid mt-3" bis_skin_checked="1">
                                            @foreach ($optionGroup as $detail)
                                                <div class="detail-item-card" bis_skin_checked="1">
                                                    <div>
                                                        <div class="detail-value">{{ $detail->option_value }}</div>
                                                        <small class="text-muted">ID:
                                                            {{ $detail->external_detail_id }}</small>
                                                    </div>
                                                    @if ($detail->additional_price > 0)
                                                        <span
                                                            class="detail-price">+{{ number_format($detail->additional_price, 2) }}
                                                            ج.م</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Dependency Chain Visualization -->
                        @if ($dependentOptions->count() > 0)
                            <div class="mt-4" bis_skin_checked="1">
                                <h6 class="mb-3 text-info">
                                    <i class="fas fa-project-diagram"></i> سلسلة التبعيات
                                </h6>

                                @php
                                    $dependencyTree = [];
                                    foreach ($product->options as $option) {
                                        if ($option->depends_on_option_id) {
                                            $parentId = $option->depends_on_option_id;
                                            if (!isset($dependencyTree[$parentId])) {
                                                $parentOpt = $product->options
                                                    ->where('external_option_id', $parentId)
                                                    ->first();
                                                $dependencyTree[$parentId] = [
                                                    'name' => $parentOpt->option_name ?? "خيار $parentId",
                                                    'children' => [],
                                                ];
                                            }
                                            $dependencyTree[$parentId]['children'][] = [
                                                'id' => $option->external_option_id,
                                                'name' => $option->option_name,
                                                'condition' => $option->dependency_condition ?? 'equals',
                                                'operator' => $option->dependency_operator ?? '=',
                                                'required_detail' => $option->depends_on_detail_id,
                                            ];
                                        }
                                    }
                                @endphp

                                @foreach ($dependencyTree as $parentId => $parent)
                                    <div class="dependency-chain" bis_skin_checked="1">
                                        <div class="dependency-item" bis_skin_checked="1">
                                            <i class="fas fa-cube text-primary"></i>
                                            <strong>{{ $parent['name'] }}</strong>
                                            <span class="text-muted">(ID: {{ $parentId }})</span>
                                        </div>

                                        @foreach ($parent['children'] as $child)
                                            <div class="dependency-item" bis_skin_checked="1">
                                                <span class="dependency-arrow">↳</span>
                                                <i class="fas fa-cube text-warning"></i>
                                                <span>{{ $child['name'] }}</span>
                                                <span class="operator-badge">{{ $child['operator'] }}</span>
                                                <span class="badge bg-secondary">detail:
                                                    {{ $child['required_detail'] }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Options Summary -->
                        <div class="mt-3 text-muted small" bis_skin_checked="1">
                            <i class="fas fa-info-circle"></i>
                            إجمالي الخيارات: {{ $product->options->count() }} خيار |
                            خيارات رئيسية: {{ $independentOptions->count() }} |
                            خيارات مشروطة: {{ $dependentOptions->count() }}
                        </div>
                    </div>
                @else
                    <div class="detail-card" bis_skin_checked="1">
                        <div class="empty-state" bis_skin_checked="1">
                            <i class="fas fa-cogs"></i>
                            <h6>لا توجد خيارات لهذا المنتج</h6>
                            <p class="text-muted">يمكنك إضافة خيارات مثل المقاسات والألوان والكميات</p>
                        </div>
                    </div>
                @endif

                <!-- Product Specifications from Options -->
                @if ($product->options->where('category', '!=', 'general')->count() > 0)
                    <div class="detail-card" bis_skin_checked="1">
                        <h5><i class="fas fa-list-check"></i> المواصفات والخصائص</h5>

                        @php
                            $categories = [
                                'color' => 'الألوان المتاحة',
                                'size' => 'المقاسات المتاحة',
                                'material' => 'المواد المستخدمة',
                                'printing_method' => 'طرق الطباعة',
                                'print_location' => 'أماكن الطباعة',
                                'embroider_location' => 'أماكن التطريز',
                                'delivery_time' => 'وقت التوصيل',
                                'quantity' => 'شرائح الكمية',
                                'design_service' => 'خدمات التصميم',
                            ];
                        @endphp

                        @foreach ($categories as $catKey => $catName)
                            @php
                                $categoryOptions = $product->options
                                    ->where('category', $catKey)
                                    ->groupBy('external_option_id');
                            @endphp

                            @if ($categoryOptions->count() > 0)
                                <div class="mb-4" bis_skin_checked="1">
                                    <h6 class="mb-3">{{ $catName }}:</h6>
                                    <div class="d-flex flex-wrap" bis_skin_checked="1">
                                        @foreach ($categoryOptions as $optId => $optionGroup)
                                            @foreach ($optionGroup as $detail)
                                                @if ($catKey === 'color')
                                                    <div class="color-swatch">
                                                        <div class="color-preview"
                                                            style="background-color: {{ $detail->extra_data['hex_code'] ?? '#ccc' }};">
                                                        </div>
                                                        <span>{{ $detail->option_value }}</span>
                                                        @if ($detail->additional_price > 0)
                                                            <small class="text-success">+{{ $detail->additional_price }}
                                                                ج.م</small>
                                                        @endif
                                                    </div>
                                                @elseif($catKey === 'material')
                                                    <div class="material-badge">
                                                        <i class="fas fa-cube"></i>
                                                        <span>{{ $detail->option_value }}</span>
                                                        @if ($detail->additional_price > 0)
                                                            <small class="text-success">+{{ $detail->additional_price }}
                                                                ج.م</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="feature-badge">
                                                        <strong>{{ $detail->option_name }}:</strong>
                                                        {{ $detail->option_value }}
                                                        @if ($detail->additional_price > 0)
                                                            <small class="text-success">+{{ $detail->additional_price }}
                                                                ج.م</small>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Column - Details & Actions -->
            <div class="col-lg-4" bis_skin_checked="1">
                <!-- Product Details -->
                <div class="detail-card" bis_skin_checked="1">
                    <h5><i class="fas fa-info-circle"></i> تفاصيل المنتج</h5>
                    <div class="detail-item" bis_skin_checked="1">
                        <span class="detail-label">رقم المنتج</span>
                        <span class="detail-value">#{{ $product->id }}</span>
                    </div>
                    <div class="detail-item" bis_skin_checked="1">
                        <span class="detail-label">التصنيف</span>
                        <span class="detail-value">
                            <a href="{{ route('admin.categories.show', $product->category_id) }}" class="text-primary">
                                {{ $product->category->name ?? 'غير مصنف' }}
                            </a>
                        </span>
                    </div>
                    <div class="detail-item" bis_skin_checked="1">
                        <span class="detail-label">تاريخ الإنشاء</span>
                        <span class="detail-value">{{ $product->created_at->format('Y/m/d h:i A') }}</span>
                    </div>
                    <div class="detail-item" bis_skin_checked="1">
                        <span class="detail-label">آخر تحديث</span>
                        <span class="detail-value">{{ $product->updated_at->format('Y/m/d h:i A') }}</span>
                    </div>
                    <div class="detail-item" bis_skin_checked="1">
                        <span class="detail-label">يشمل الضريبة</span>
                        <span class="detail-value">
                            @if ($product->includes_tax)
                                <span class="badge bg-success">نعم</span>
                            @else
                                <span class="badge bg-secondary">لا</span>
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Options Summary by Category -->
                @if ($product->options->where('category', '!=', 'general')->count() > 0)
                    <div class="detail-card" bis_skin_checked="1">
                        <h5><i class="fas fa-chart-pie"></i> ملخص الخيارات</h5>
                        <div class="d-flex flex-wrap gap-2" bis_skin_checked="1">
                            @php
                                $categoryCounts = $product->options->groupBy('category')->map(function ($group) {
                                    return $group->groupBy('external_option_id')->count();
                                });
                            @endphp

                            @foreach ($categoryCounts as $cat => $count)
                                <span
                                    class="badge bg-{{ $cat === 'color' ? 'danger' : ($cat === 'size' ? 'info' : ($cat === 'quantity' ? 'success' : 'primary')) }}">
                                    {{ __('categories.' . $cat) ?? $cat }}: {{ $count }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- SEO Information -->
                <div class="detail-card" bis_skin_checked="1">
                    <h5><i class="fas fa-search"></i> معلومات SEO</h5>
                    <div class="mb-3" bis_skin_checked="1">
                        <label class="form-label">الرابط (Slug)</label>
                        <div class="input-group" bis_skin_checked="1">
                            <input type="text" class="form-control" value="{{ $product->slug }}" readonly
                                style="background: rgba(255,255,255,0.05); color: white; border-color: rgba(255,255,255,0.1);">
                            <button class="btn btn-outline-secondary" type="button" onclick="copySlug()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>

                    @if ($product->meta_title)
                        <div class="mb-3" bis_skin_checked="1">
                            <label class="form-label">عنوان الصفحة</label>
                            <div class="bg-dark p-2 rounded" style="background: rgba(255,255,255,0.05);"
                                bis_skin_checked="1">
                                {{ $product->meta_title }}
                            </div>
                        </div>
                    @endif

                    @if ($product->meta_description)
                        <div class="mb-3" bis_skin_checked="1">
                            <label class="form-label">وصف الصفحة</label>
                            <div class="bg-dark p-2 rounded" style="background: rgba(255,255,255,0.05);"
                                bis_skin_checked="1">
                                {{ $product->meta_description }}
                            </div>
                        </div>
                    @endif

                    @if ($product->meta_keywords)
                        <div class="mb-3" bis_skin_checked="1">
                            <label class="form-label">الكلمات المفتاحية</label>
                            <div class="d-flex flex-wrap gap-1" bis_skin_checked="1">
                                @foreach (explode(',', $product->meta_keywords) as $keyword)
                                    <span class="meta-tag">{{ trim($keyword) }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Reviews -->
                @if ($product->reviews && $product->reviews->count() > 0)
                    <div class="detail-card" bis_skin_checked="1">
                        <h5><i class="fas fa-star"></i> آخر التقييمات</h5>
                        <div class="rating-stars mb-3" bis_skin_checked="1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($product->average_rating))
                                    <i class="fas fa-star"></i>
                                @elseif($i - 0.5 <= $product->average_rating)
                                    <i class="fas fa-star-half-alt"></i>
                                @else
                                    <i class="far fa-star"></i>
                                @endif
                            @endfor
                            <span class="ms-2">{{ number_format($product->average_rating, 1) }}
                                ({{ $product->reviews_count ?? 0 }} تقييم)</span>
                        </div>

                        @foreach ($product->reviews->take(3) as $review)
                            <div class="review-card" bis_skin_checked="1">
                                <div class="review-header" bis_skin_checked="1">
                                    <div class="reviewer-name" bis_skin_checked="1">{{ $review->user->name ?? 'مستخدم' }}
                                    </div>
                                    <div class="review-rating" bis_skin_checked="1">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $review->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <div class="review-date mb-2" bis_skin_checked="1">
                                    {{ $review->created_at->diffForHumans() }}
                                </div>
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Activity Timeline -->
                <div class="detail-card" bis_skin_checked="1">
                    <h5><i class="fas fa-history"></i> سجل النشاط</h5>
                    <div class="timeline" bis_skin_checked="1">
                        <div class="timeline-item" bis_skin_checked="1">
                            <div class="timeline-dot" bis_skin_checked="1"></div>
                            <div class="timeline-content" bis_skin_checked="1">
                                <div class="timeline-date" bis_skin_checked="1">آخر تحديث</div>
                                <div class="timeline-title" bis_skin_checked="1">تم تحديث المنتج</div>
                                <p class="mb-0">{{ $product->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="timeline-item" bis_skin_checked="1">
                            <div class="timeline-dot" bis_skin_checked="1"></div>
                            <div class="timeline-content" bis_skin_checked="1">
                                <div class="timeline-date" bis_skin_checked="1">تاريخ الإنشاء</div>
                                <div class="timeline-title" bis_skin_checked="1">تم إنشاء المنتج</div>
                                <p class="mb-0">{{ $product->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="detail-card" bis_skin_checked="1">
                    <h5><i class="fas fa-qrcode"></i> رمز QR</h5>
                    <div class="text-center" bis_skin_checked="1">
                        <div class="qr-code mb-3" id="qrCode" bis_skin_checked="1">
                            <div class="text-muted" bis_skin_checked="1">
                                <i class="fas fa-qrcode fa-3x"></i>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="generateQRCode()">
                            <i class="fas fa-sync-alt me-1"></i> إنشاء رمز QR
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Floating) -->
    <div class="action-buttons" bis_skin_checked="1">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary" title="تعديل">
            <i class="fas fa-edit"></i>
        </a>
        <button type="button" class="btn btn-info" onclick="copyProductLink()" title="نسخ الرابط">
            <i class="fas fa-link"></i>
        </button>
        <button type="button" class="btn btn-success" onclick="shareProduct()" title="مشاركة">
            <i class="fas fa-share-alt"></i>
        </button>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background: var(--dark-card);">
                <div class="modal-header" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="modal-title text-white">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3" bis_skin_checked="1">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>هل أنت متأكد من حذف هذا المنتج؟</h5>
                        <p class="text-muted">سيتم حذف المنتج "<strong>{{ $product->name }}</strong>" بشكل دائم.</p>
                        <div class="alert alert-danger mt-3"
                            style="background: rgba(220, 53, 69, 0.1); border-color: rgba(220, 53, 69, 0.3); color: var(--danger-color);"
                            bis_skin_checked="1">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <strong>تحذير:</strong> هذا الإجراء لا يمكن التراجع عنه
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: rgba(255,255,255,0.1);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" id="deleteForm">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i> حذف المنتج
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal fade" id="shareModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background: var(--dark-card);">
                <div class="modal-header" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="modal-title text-white">مشاركة المنتج</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3" bis_skin_checked="1">
                        <label class="form-label">رابط المنتج</label>
                        <div class="input-group" bis_skin_checked="1">
                            <input type="text" class="form-control" id="shareUrl" readonly
                                value="{{ url('/products/' . $product->slug) }}"
                                style="background: rgba(255,255,255,0.05); color: white; border-color: rgba(255,255,255,0.1);">
                            <button class="btn btn-outline-secondary" type="button" onclick="copyShareUrl()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="mb-3" bis_skin_checked="1">
                        <label class="form-label">مشاركة عبر</label>
                        <div class="social-share" bis_skin_checked="1">
                            <button class="btn btn-primary" onclick="shareOnFacebook()">
                                <i class="fab fa-facebook-f"></i>
                            </button>
                            <button class="btn btn-info" onclick="shareOnTwitter()">
                                <i class="fab fa-twitter"></i>
                            </button>
                            <button class="btn btn-success" onclick="shareOnWhatsApp()">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                            <button class="btn btn-danger" onclick="shareViaEmail()">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Zoom Modal -->
    <div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="background: transparent; border: none;">
                <div class="modal-body p-0">
                    <img src="" alt="صورة مكبرة" id="zoomedImage" class="img-fluid w-100 rounded">
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode@1.5.3/build/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Image Gallery
        function changeMainImage(src, element) {
            $('#mainProductImage').attr('src', src);
            $('.gallery-thumb').removeClass('active');
            $(element).addClass('active');
        }

        function zoomImage() {
            const src = $('#mainProductImage').attr('src');
            $('#zoomedImage').attr('src', src);
            $('#imageZoomModal').modal('show');
        }

        // Delete confirmation
        function confirmDelete() {
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }

        // Share product
        function shareProduct() {
            const modal = new bootstrap.Modal(document.getElementById('shareModal'));
            modal.show();
        }

        // Copy share URL
        function copyShareUrl() {
            const urlInput = document.getElementById('shareUrl');
            urlInput.select();
            urlInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(urlInput.value).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم النسخ!',
                    text: 'تم نسخ الرابط إلى الحافظة',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        // Copy slug
        function copySlug() {
            const slug = '{{ $product->slug }}';
            navigator.clipboard.writeText(slug).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم النسخ!',
                    text: 'تم نسخ الرابط إلى الحافظة',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        // Copy product link
        function copyProductLink() {
            const url = '{{ url('/products/' . $product->slug) }}';
            navigator.clipboard.writeText(url).then(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'تم النسخ!',
                    text: 'تم نسخ رابط المنتج إلى الحافظة',
                    timer: 1500,
                    showConfirmButton: false
                });
            });
        }

        // Share on social media
        function shareOnFacebook() {
            const url = encodeURIComponent('{{ url('/products/' . $product->slug) }}');
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
        }

        function shareOnTwitter() {
            const url = encodeURIComponent('{{ url('/products/' . $product->slug) }}');
            const text = encodeURIComponent('{{ $product->name }}');
            window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank');
        }

        function shareOnWhatsApp() {
            const url = encodeURIComponent('{{ url('/products/' . $product->slug) }}');
            const text = encodeURIComponent('{{ $product->name }}');
            window.open(`https://wa.me/?text=${text}%20${url}`, '_blank');
        }

        function shareViaEmail() {
            const url = '{{ url('/products/' . $product->slug) }}';
            const subject = encodeURIComponent('{{ $product->name }}');
            const body = encodeURIComponent(`تفضل بزيارة المنتج: ${url}`);
            window.location.href = `mailto:?subject=${subject}&body=${body}`;
        }

        // Generate QR Code
        function generateQRCode() {
            const qrCodeDiv = document.getElementById('qrCode');
            qrCodeDiv.innerHTML = '';

            const url = '{{ url('/products/' . $product->slug) }}';

            QRCode.toCanvas(url, {
                width: 150,
                margin: 1,
                color: {
                    dark: '#696cff',
                    light: '#1e1e2d'
                }
            }, function(error, canvas) {
                if (error) {
                    console.error(error);
                    qrCodeDiv.innerHTML = '<div class="text-danger">خطأ في إنشاء رمز QR</div>';
                    return;
                }

                qrCodeDiv.appendChild(canvas);

                const downloadBtn = document.createElement('button');
                downloadBtn.className = 'btn btn-sm btn-outline-primary mt-2';
                downloadBtn.innerHTML = '<i class="fas fa-download me-1"></i> تحميل';
                downloadBtn.onclick = function() {
                    const link = document.createElement('a');
                    link.download = 'qr-code-{{ $product->slug }}.png';
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                };
                qrCodeDiv.appendChild(downloadBtn);
            });
        }

        // Preview product in store
        function previewProduct() {
            const url = '{{ url('/products/' . $product->slug) }}';
            window.open(url, '_blank');
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            generateQRCode();
            $('[data-fancybox="gallery"]').fancybox({
                buttons: ["zoom", "share", "slideShow", "fullScreen", "download", "thumbs", "close"]
            });
        });
    </script>
@endsection
