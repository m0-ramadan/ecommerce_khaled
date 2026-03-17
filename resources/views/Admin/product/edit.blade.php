@extends('Admin.layout.master')

@section('title', 'تعديل المنتج: ' . $product->name)

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet" />
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

        .select2-results__option--highlighte {
            color: black !important;
        }

        /* Card Styles */
        .step-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border-left: 4px solid var(--primary-color);
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin-left: 15px;
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

        /* Wizard Steps */
        .wizard-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .wizard-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            right: 0;
            left: 0;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            z-index: 1;
        }

        .wizard-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .wizard-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
        }

        .wizard-step.active .wizard-step-circle {
            background: var(--primary-gradient);
            color: white;
        }

        /* ================ */
        /* ⭐ OPTIONS STYLES */
        /* ================ */

        /* Options Container - Very Smooth Drag & Drop */
        .options-sortable-container {
            min-height: 100px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* Option Item - Main Container */
        .option-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 16px;
            margin-bottom: 16px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: move;
        }

        .option-item:hover {
            background: rgba(105, 108, 255, 0.08);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(105, 108, 255, 0.2);
        }

        /* Drag & Drop States - Super Smooth */
        .option-item.sortable-ghost {
            opacity: 0.5;
            background: rgba(105, 108, 255, 0.1);
            border: 2px dashed var(--primary-color);
            transform: scale(0.98);
        }

        .option-item.sortable-chosen {
            box-shadow: 0 15px 30px rgba(105, 108, 255, 0.3);
            transform: scale(1.02) translateY(-3px);
            background: var(--dark-card);
            border-color: var(--primary-color);
        }

        .option-item.sortable-drag {
            opacity: 1;
            transform: rotate(1deg) scale(1.05);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
        }

        /* Drag Handle */
        .option-drag-handle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            color: rgba(255, 255, 255, 0.4);
            font-size: 20px;
            cursor: move;
            transition: all 0.2s;
            padding: 10px;
            border-radius: 8px;
        }

        .option-drag-handle:hover {
            color: var(--primary-color);
            background: rgba(105, 108, 255, 0.15);
        }

        /* Option Header */
        .option-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding-right: 40px;
        }

        .option-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 600;
            background: rgba(255, 255, 255, 0.1);
        }

        .badge-main {
            background: var(--primary-gradient);
            color: white;
        }

        .badge-dependent {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.4);
        }

        .badge-required {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        /* Option Type Badges */
        .type-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .type-regular {
            background: rgba(108, 117, 125, 0.2);
            color: #adb5bd;
        }

        .type-quantity {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .type-size {
            background: rgba(23, 162, 184, 0.2);
            color: #17a2b8;
        }

        .type-color {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
        }

        /* Option Content Grid */
        .option-content-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        /* Dependency Section */
        .dependency-section {
            background: rgba(255, 193, 7, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border-right: 3px solid #ffc107;
            display: none;
        }

        .dependency-section.show {
            display: block;
            animation: slideDown 0.3s ease;
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

        /* Quantity Tiers Section */
        .tiers-section {
            background: rgba(23, 162, 184, 0.05);
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
            border-right: 3px solid #17a2b8;
        }

        .tier-item {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
        }

        /* Add Option Button - Fixed at Top */
        .add-option-section {
            background: linear-gradient(135deg, rgba(105, 108, 255, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px dashed var(--primary-color);
        }

        .btn-add-option {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-add-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(105, 108, 255, 0.4);
        }

        /* Image Management */
        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .image-preview-item {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            height: 120px;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            justify-content: center;
            gap: 5px;
            padding: 8px;
            transform: translateY(100%);
            transition: transform 0.3s;
        }

        .image-preview-item:hover .image-actions {
            transform: translateY(0);
        }

        /* Preview Card */
        .preview-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .preview-image {
            width: 80px;
            height: 80px;
            border-radius: 10px;
            object-fit: cover;
        }

        .preview-price {
            font-size: 22px;
            font-weight: bold;
            color: #28a745;
        }

        /* Toggle Switch */
        .toggle-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-switch {
            position: relative;
            width: 56px;
            height: 28px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
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

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: var(--primary-color);
        }

        input:checked+.toggle-slider:before {
            transform: translateX(28px);
        }

        /* Required Field */
        .required::after {
            content: " *";
            color: var(--danger-color);
        }

        /* Alert Guide */
        .alert-guide {
            background: rgba(105, 108, 255, 0.05);
            border-right: 4px solid var(--primary-color);
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .action-buttons-fixed {
            position: fixed;
            bottom: 30px;
            left: 30px;
            display: flex;
            gap: 12px;
            z-index: 999;
        }

        .action-buttons-fixed .btn {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        .badge-new {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-4">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-primary">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}" class="text-primary">المنتجات</a>
                </li>
                <li class="breadcrumb-item"><a href="{{ route('admin.products.show', $product->id) }}"
                        class="text-primary">{{ Str::limit($product->name, 30) }}</a></li>
                <li class="breadcrumb-item active text-white">تعديل</li>
            </ol>
        </nav>

        <!-- Wizard Steps -->
        <div class="wizard-steps">
            <div class="wizard-step active" id="step1">
                <div class="wizard-step-circle">1</div>
                <div class="wizard-step-label">المعلومات الأساسية</div>
            </div>
            <div class="wizard-step" id="step2">
                <div class="wizard-step-circle">2</div>
                <div class="wizard-step-label">الصور والتسعير</div>
            </div>
            <div class="wizard-step" id="step3">
                <div class="wizard-step-circle">3</div>
                <div class="wizard-step-label">خيارات المنتج</div>
            </div>
            <div class="wizard-step" id="step4">
                <div class="wizard-step-circle">4</div>
                <div class="wizard-step-label">SEO والنشر</div>
            </div>
        </div>

        <!-- Product Preview Card -->
        <div class="preview-card">
            <img src="{{ $product->primaryImage ? get_user_image($product->primaryImage->path) : 'https://via.placeholder.com/80x80?text=No+Image' }}"
                alt="{{ $product->name }}" class="preview-image">
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-1">{{ $product->name }}</h5>
                        <div class="d-flex gap-3">
                            <span class="text-muted"><i class="fas fa-tag me-1"></i> #{{ $product->id }}</span>
                            <span class="text-muted"><i class="fas fa-folder me-1"></i>
                                {{ $product->category->name ?? 'غير مصنف' }}</span>
                        </div>
                    </div>
                    <div class="text-start">
                        <span class="preview-price">{{ number_format($product->final_price, 2) }} ج.م</span>
                        @if ($product->has_discount && $product->price > $product->final_price)
                            <div class="text-muted"><del>{{ number_format($product->price, 2) }} ج.م</del></div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Guide -->
        <div class="alert-guide">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-lightbulb fa-2x" style="color: var(--primary-color);"></i>
                <div>
                    <h6 class="mb-1">نصائح سريعة لتعديل المنتج</h6>
                    <p class="mb-0 text-muted">يمكنك سحب وإفلات الخيارات لإعادة ترتيبها - الخيارات الجديدة تُضاف من الأعلى
                    </p>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Form -->
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            id="editProductForm">
            @csrf
            @method('POST')

            <!-- ============ STEP 1: BASIC INFO ============ -->
            <div class="step-card step-1">
                <div class="step-header">
                    <div class="step-number">1</div>
                    <div>
                        <h5 class="step-title">المعلومات الأساسية</h5>
                        <p class="step-description">البيانات الرئيسية للمنتج</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="name" class="form-label required">اسم المنتج</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="category_id" class="form-label required">القسم</label>
                        <select class="form-control select2" id="category_id" name="category_id" required>
                            <option value="">اختر القسم</option>
                            @foreach ($categories as $category)
                                <option style="color: black !important;" value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control summernote" id="description" name="description" rows="6">{{ old('description', $product->description) }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status_id" class="form-label required">الحالة</label>
                        <select class="form-select" id="status_id" name="status_id" required>
                            <option value="1" {{ old('status_id', $product->status_id) == 1 ? 'selected' : '' }}>نشط
                            </option>
                            <option value="2" {{ old('status_id', $product->status_id) == 2 ? 'selected' : '' }}>غير
                                نشط</option>
                            <option value="3" {{ old('status_id', $product->status_id) == 3 ? 'selected' : '' }}>
                                مسودة</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label required">الكمية المتاحة</label>
                        <input type="number" class="form-control" id="stock" name="stock"
                            value="{{ old('stock', $product->stock) }}" min="0" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <div></div>
                    <button type="button" class="btn btn-primary next-step" data-next="2">
                        التالي <i class="fas fa-arrow-left ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ============ STEP 2: IMAGES & PRICING ============ -->
            <div class="step-card step-2" style="display: none;">
                <div class="step-header">
                    <div class="step-number">2</div>
                    <div>
                        <h5 class="step-title">الصور والتسعير</h5>
                        <p class="step-description">إدارة الصور وتحديث الأسعار</p>
                    </div>
                </div>

                <!-- Main Image -->
                <div class="mb-4">
                    <label class="form-label required">الصورة الرئيسية</label>

                    @if ($product->image)
                        <div class="image-preview-grid" id="currentMainImageContainer">
                            <div class="image-preview-item">
                                <span class="badge-new">حالية</span>
                                <img src="{{ get_product_image($product->image) }}" alt="الصورة الرئيسية">
                                <div class="image-actions">
                                    <button type="button" class="btn btn-sm btn-info"
                                        onclick="viewImage('{{ get_product_image($product->image) }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger"
                                        onclick="removeCurrentMainImage()">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="image-manager mt-3" onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-2" style="color: var(--primary-color);"></i>
                        <p class="mb-0">انقر لرفع صورة رئيسية جديدة</p>
                        <small class="text-muted">الحجم الموصى به: 800×800 بكسل</small>
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                    <input type="hidden" id="remove_main_image" name="remove_main_image" value="0">
                    <div id="newMainImagePreview" class="image-preview-grid mt-3" style="display: none;"></div>
                </div>

                <!-- Additional Images -->
                <div class="mb-4">
                    <label class="form-label">الصور الإضافية</label>

                    @if ($product->images && $product->images->count() > 0)
                        <div class="image-preview-grid sortable-image-list" id="existingImagesGrid">
                            @foreach ($product->images as $image)
                                <div class="image-preview-item" data-id="{{ $image->id }}">
                                    @if ($image->is_primary)
                                        <span class="badge-new">رئيسية</span>
                                    @endif
                                    <img src="{{ get_user_image($image->path) }}" alt="صورة إضافية">
                                    <div class="image-actions">
                                        <button type="button" class="btn btn-sm btn-info"
                                            onclick="viewImage('{{ get_user_image($image->path) }}')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger"
                                            onclick="removeAdditionalImage({{ $image->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @if (!$image->is_primary)
                                            <button type="button" class="btn btn-sm btn-primary"
                                                onclick="setAsPrimary({{ $image->id }})">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="image-manager mt-3" onclick="document.getElementById('additional_images').click()">
                        <i class="fas fa-images fa-3x mb-2" style="color: var(--primary-color);"></i>
                        <p class="mb-0">انقر لإضافة صور إضافية</p>
                        <small class="text-muted">يمكنك رفع أكثر من صورة</small>
                    </div>
                    <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple
                        style="display: none;">
                    <div id="newImagesPreview" class="image-preview-grid mt-3"></div>

                    <input type="hidden" id="removed_images" name="removed_images" value="">
                    <input type="hidden" id="primary_image_id" name="primary_image_id"
                        value="{{ $product->images->where('is_primary', true)->first()->id ?? '' }}">
                    <input type="hidden" id="images_order" name="images_order" value="">
                </div>

                <!-- Pricing -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label required">السعر الأساسي</label>
                        <div class="input-group">
                            <span class="input-group-text">ج.م</span>
                            <input type="number" class="form-control" id="price" name="price" step="0.01"
                                value="{{ old('price', $product->price) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price_text" class="form-label">نص السعر</label>
                        <input type="text" class="form-control" id="price_text" name="price_text"
                            value="{{ old('price_text', $product->price_text) }}" placeholder="مثال: ١٠٠ ريال">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="toggle-container">
                            <label class="toggle-switch">
                                <input type="checkbox" id="has_discount" name="has_discount"
                                    {{ old('has_discount', $product->has_discount) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">خصم على المنتج</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="toggle-container">
                            <label class="toggle-switch">
                                <input type="checkbox" id="includes_tax" name="includes_tax"
                                    {{ old('includes_tax', $product->includes_tax) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">يشمل الضريبة</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="toggle-container">
                            <label class="toggle-switch">
                                <input type="checkbox" id="includes_shipping" name="includes_shipping"
                                    {{ old('includes_shipping', $product->includes_shipping) ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">الشحن مجاني</span>
                        </div>
                    </div>
                </div>

                <!-- Discount Section -->
                <div id="discountSection"
                    style="{{ old('has_discount', $product->has_discount) ? '' : 'display: none;' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="discount_type" class="form-label">نوع الخصم</label>
                            <select class="form-select" id="discount_type" name="discount_type">
                                <option value="percentage"
                                    {{ old('discount_type', $product->discount->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>
                                    نسبة مئوية</option>
                                <option value="fixed"
                                    {{ old('discount_type', $product->discount->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>
                                    قيمة ثابتة</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_value" class="form-label">قيمة الخصم</label>
                            <input type="number" class="form-control" id="discount_value" name="discount_value"
                                step="0.01"
                                value="{{ old('discount_value', $product->discount->discount_value ?? '') }}">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">
                        <i class="fas fa-arrow-right me-1"></i> السابق
                    </button>
                    <button type="button" class="btn btn-primary next-step" data-next="3">
                        التالي <i class="fas fa-arrow-left ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ============ STEP 3: PRODUCT OPTIONS ============ -->
            <!-- ⭐ THIS IS THE MAIN STEP - DRAG & DROP OPTIONS ⭐ -->
            <div class="step-card step-3" style="display: none;">
                <div class="step-header">
                    <div class="step-number">3</div>
                    <div>
                        <h5 class="step-title">خيارات المنتج</h5>
                        <p class="step-description">إضافة وتعديل الخيارات - اسحب لإعادة الترتيب - الخيارات الجديدة تضاف من
                            الأعلى</p>
                    </div>
                </div>

                <!-- ========================================= -->
                <!-- ⭐ ADD NEW OPTION SECTION - ALWAYS AT TOP ⭐ -->
                <!-- ========================================= -->
                <div class="add-option-section">
                    <h6 class="mb-3"><i class="fas fa-plus-circle me-2" style="color: var(--primary-color);"></i> إضافة
                        خيار جديد (سيُضاف إلى الأعلى)</h6>

                    <div class="row align-items-end">
                        <div class="col-md-3 mb-2">
                            <label class="form-label small">اسم الخيار</label>
                            <input type="text" class="form-control" id="new_option_name"
                                placeholder="مثال: اللون، المقاس">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label class="form-label small">القيمة</label>
                            <input type="text" class="form-control" id="new_option_value"
                                placeholder="مثال: أحمر، كبير">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label small">السعر الإضافي</label>
                            <input type="number" class="form-control" id="new_option_price" placeholder="0.00"
                                step="0.01">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label small">النوع</label>
                            <select class="form-select" id="new_option_type">
                                <option value="regular">عادي</option>
                                <option value="quantity">كمية</option>
                                <option value="size">مقاس</option>
                                <option value="color">لون</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label class="form-label small">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="new_option_required">
                                    <label class="form-check-label small">مطلوب</label>
                                </div>
                                <button type="button" class="btn btn-add-option flex-grow-1" onclick="addNewOption()">
                                    <i class="fas fa-plus me-1"></i> إضافة
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Add Multiple Values (Optional) -->
                    <div class="mt-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted small">إضافة متعددة:</span>
                            <input type="text" class="form-control form-control-sm" id="bulk_option_values"
                                placeholder="قيم مفصولة بفاصلة (مثال: أحمر, أزرق, أخضر)" style="width: 300px;">
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="addBulkOptions()">
                                <i class="fas fa-copy me-1"></i> إضافة الكل
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ========================================= -->
                <!-- ⭐ DRAG & DROP OPTIONS CONTAINER ⭐ -->
                <!-- ========================================= -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0"><i class="fas fa-sort me-2"></i> خيارات المنتج الحالية - اسحب وأسفل لإعادة الترتيب
                    </h6>
                    <span class="badge bg-primary" id="optionsCount">{{ $product->options->count() }} خيار</span>
                </div>

                <div id="optionsContainer" class="options-sortable-container">
                    <!-- ⭐ ALL OPTIONS WILL BE RENDERED HERE VIA JAVASCRIPT ⭐ -->
                </div>

                <!-- ========================================= -->
                <!-- ⭐ DEPENDENCY HELPERS ⭐ -->
                <!-- ========================================= -->
                <div class="mt-4 p-3" style="background: rgba(255, 193, 7, 0.05); border-radius: 8px;">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-link fa-lg" style="color: #ffc107;"></i>
                        <div>
                            <small class="text-muted d-block">يمكنك جعل الخيار يعتمد على خيار آخر عن طريق تحديد "يعتمد على"
                                في إعدادات الخيار</small>
                            <small class="text-muted">مثال: خيار "الكمية" يعتمد على خيار "عدد أوجه الطباعة"</small>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                        <i class="fas fa-arrow-right me-1"></i> السابق
                    </button>
                    <button type="button" class="btn btn-primary next-step" data-next="4">
                        التالي <i class="fas fa-arrow-left ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ============ STEP 4: SEO & PUBLISH ============ -->
            <div class="step-card step-4" style="display: none;">
                <div class="step-header">
                    <div class="step-number">4</div>
                    <div>
                        <h5 class="step-title">SEO والنشر</h5>
                        <p class="step-description">تحسين ظهور المنتج في محركات البحث</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="slug" class="form-label">الرابط (Slug)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="slug" name="slug"
                                value="{{ old('slug', $product->slug) }}">
                            <button type="button" class="btn btn-outline-secondary" onclick="generateSlug()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="meta_title" class="form-label">عنوان الصفحة</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                            value="{{ old('meta_title', $product->meta_title) }}">
                        <small class="text-muted">50-60 حرفاً</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="meta_keywords" class="form-label">الكلمات المفتاحية</label>
                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                            value="{{ old('meta_keywords', $product->meta_keywords) }}">
                        <small class="text-muted">مفصولة بفواصل</small>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="meta_description" class="form-label">وصف الصفحة</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="4">{{ old('meta_description', $product->meta_description) }}</textarea>
                        <small class="text-muted">150-160 حرفاً</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="3">
                        <i class="fas fa-arrow-right me-1"></i> السابق
                    </button>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> حفظ التغييرات
                        </button>
                        <button type="button" class="btn btn-primary" onclick="saveAndContinue()">
                            <i class="fas fa-redo me-1"></i> حفظ ومتابعة
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Fixed Action Buttons -->
    <div class="action-buttons-fixed">
        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-info" title="عرض المنتج">
            <i class="fas fa-eye"></i>
        </a>
        <button type="button" class="btn btn-success" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            title="العودة للأعلى">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>

    <!-- Image View Modal -->
    <div class="modal fade" id="imageViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-body p-0">
                    <img src="" alt="صورة المنتج" id="viewedImage" class="img-fluid w-100 rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background: var(--dark-card);">
                <div class="modal-header" style="border-color: rgba(255,255,255,0.1);">
                    <h5 class="modal-title text-white">تأكيد الحذف</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h6>هل أنت متأكد من حذف هذا المنتج؟</h6>
                        <p class="text-muted">هذا الإجراء لا يمكن التراجع عنه</p>
                    </div>
                </div>
                <div class="modal-footer" style="border-color: rgba(255,255,255,0.1);">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    @php
        $existingOptions = $product->options
            ->map(function ($option) {
                return [
                    'id' => $option->id,
                    'option_name' => $option->option_name,
                    'option_value' => $option->option_value,
                    'additional_price' => $option->additional_price,
                    'is_required' => $option->is_required,
                    'option_type' => $option->option_type ?? 'regular',
                    'depends_on_option_id' => $option->depends_on_option_id,
                    'dependency_condition' => $option->dependency_condition ?? 'equals',
                    'dependency_value' => $option->dependency_value ?? '',
                    'parent_option' => $option->parentOption
                        ? [
                            'id' => $option->parentOption->id,
                            'option_name' => $option->parentOption->option_name,
                            'option_value' => $option->parentOption->option_value,
                        ]
                        : null,
                    'quantity_tiers' => $option->quantityTiers ?? [],
                ];
            })
            ->values();
    @endphp
    <script>
        // ============================================
        // ⭐ GLOBAL VARIABLES
        // ============================================
        let removedImages = [];
        let optionCounter = {{ $product->options->count() }};
        let sortableInstance = null;
        const existingOptions = @json($existingOptions);

        // ============================================
        // ⭐ DOCUMENT READY
        // ============================================
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 250,
                lang: 'ar-AR',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            // Initialize Select2
            $('.select2').select2({
                placeholder: 'اختر',
                allowClear: true,
                theme: "classic"
            });

            // Initialize image sortable
            if (document.getElementById('existingImagesGrid')) {
                Sortable.create(document.getElementById('existingImagesGrid'), {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    onEnd: function() {
                        updateImagesOrder();
                    }
                });
            }

            // ========================================
            // ⭐ LOAD EXISTING OPTIONS
            // ========================================
            loadExistingOptions();

            // ========================================
            // ⭐ STEP NAVIGATION
            // ========================================
            $('.next-step').click(function() {
                navigateToStep($(this).data('next'));
            });

            $('.prev-step').click(function() {
                navigateToStep($(this).data('prev'));
            });

            // Discount toggle
            $('#has_discount').change(function() {
                $('#discountSection').slideToggle();
            });

            // Image upload handlers
            $('#image').change(function(e) {
                previewMainImage(e.target.files[0]);
            });

            $('#additional_images').change(function(e) {
                previewMultipleImages(e.target.files);
            });

            // Generate slug from name
            $('#name').on('blur', function() {
                if (!$('#slug').val()) {
                    generateSlug();
                }
            });
        });

        // ============================================
        // ⭐ OPTIONS MANAGEMENT - HIERARCHICAL TREE
        // ============================================

        /**
         * Load existing options into the sortable container
         * ⭐ NEW OPTIONS WILL BE ADDED AT THE TOP
         */
        function loadExistingOptions() {
            const container = document.getElementById('optionsContainer');
            container.innerHTML = '';

            // Add existing options
            existingOptions.forEach((option, index) => {
                const optionHtml = generateOptionHtml(option, index);
                container.innerHTML += optionHtml;
            });

            // Initialize sortable after loading
            initializeSortable();
            updateOptionsCount();
        }

        /**
         * Generate HTML for an option with hierarchical support
         */
        function generateOptionHtml(option, index) {
            const isMain = !option.depends_on_option_id;
            const depth = getOptionDepth(option.id);
            
            const mainBadge = isMain ?
                '<span class="badge-main option-badge"><i class="fas fa-cube me-1"></i>رئيسي</span>' :
                '<span class="badge-dependent option-badge"><i class="fas fa-link me-1"></i>معتمد</span>';
            const requiredBadge = option.is_required ?
                '<span class="badge-required option-badge"><i class="fas fa-check-circle me-1"></i>مطلوب</span>' : '';
            const typeClass = `type-${option.option_type}`;
            const typeLabel = getOptionTypeLabel(option.option_type);
            
            const depthIndicator = !isMain ? 
                `<span class="badge bg-info" style="position: absolute; right: 50px; top: 50%; transform: translateY(-50%); font-size: 10px;">
                    <i class="fas fa-level-down-alt me-1"></i>عمق ${depth}
                </span>` : '';

            // Dependency section (show with hierarchy info)
            let dependencyHtml = '';
            if (!isMain && option.parent_option) {
                const hierarchy = getOptionHierarchy(option.id);
                let hierarchyHtml = '';
                hierarchy.forEach((opt, idx) => {
                    hierarchyHtml += `<span class="badge bg-secondary me-1">${opt.option_name} (${opt.option_value})</span>`;
                    if (idx < hierarchy.length - 1) {
                        hierarchyHtml += ' <i class="fas fa-arrow-left text-muted mx-1"></i> ';
                    }
                });
                
                dependencyHtml = `
                    <div class="dependency-section show">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-sitemap text-warning me-1"></i> 
                                    التسلسل الهرمي:
                                </small>
                                <div class="hierarchy-chain">
                                    ${hierarchyHtml}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Quantity tiers (only for quantity type)
            let tiersHtml = '';
            if (option.option_type === 'quantity' && option.quantity_tiers && option.quantity_tiers.length > 0) {
                tiersHtml = `
                    <div class="tiers-section">
                        <h6 class="mb-3" style="color: #17a2b8;"><i class="fas fa-layer-group me-2"></i>شرائح الكمية</h6>
                        <div id="tiersContainer_${index}">
                            ${option.quantity_tiers.map((tier, tierIndex) => `
                                <div class="tier-item">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="product_options[${index}][quantity_tiers][${tierIndex}][quantity]" 
                                           value="${tier.quantity}" placeholder="الكمية">
                                    <input type="number" class="form-control form-control-sm" 
                                           name="product_options[${index}][quantity_tiers][${tierIndex}][price_per_unit]" 
                                           value="${tier.price_per_unit}" placeholder="السعر للوحدة">
                                    <input type="text" class="form-control form-control-sm" 
                                           name="product_options[${index}][quantity_tiers][${tierIndex}][tier_name]" 
                                           value="${tier.tier_name || ''}" placeholder="اسم الشريحة">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTier(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            `).join('')}
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-info mt-2" onclick="addQuantityTier(${index})">
                            <i class="fas fa-plus me-1"></i> إضافة شريحة
                        </button>
                    </div>
                `;
            }

            return `
                <div class="option-item" data-index="${index}" data-id="${option.id || ''}" data-depth="${depth}">
                    <div class="option-drag-handle">
                        <i class="fas fa-grip-vertical"></i>
                        ${depthIndicator}
                    </div>
                    
                    <div class="option-header">
                        ${mainBadge}
                        ${requiredBadge}
                        <span class="type-badge ${typeClass}">${typeLabel}</span>
                    </div>

                    <input type="hidden" name="product_options[${index}][id]" value="${option.id || ''}">

                    <div class="option-content-grid">
                        <div>
                            <label class="form-label small">اسم الخيار</label>
                            <input type="text" class="form-control" name="product_options[${index}][option_name]" 
                                   value="${option.option_name}" placeholder="اسم الخيار" onchange="updateOptionPreview(this)">
                        </div>
                        <div>
                            <label class="form-label small">القيمة</label>
                            <input type="text" class="form-control" name="product_options[${index}][option_value]" 
                                   value="${option.option_value}" placeholder="القيمة" onchange="updateOptionPreview(this)">
                        </div>
                        <div>
                            <label class="form-label small">السعر الإضافي</label>
                            <input type="number" class="form-control" name="product_options[${index}][additional_price]" 
                                   value="${option.additional_price}" placeholder="0.00" step="0.01">
                        </div>
                        <div>
                            <label class="form-label small">النوع</label>
                            <select class="form-select" name="product_options[${index}][option_type]" onchange="updateOptionType(this, ${index})">
                                <option value="regular" ${option.option_type === 'regular' ? 'selected' : ''}>عادي</option>
                                <option value="quantity" ${option.option_type === 'quantity' ? 'selected' : ''}>كمية</option>
                                <option value="size" ${option.option_type === 'size' ? 'selected' : ''}>مقاس</option>
                                <option value="color" ${option.option_type === 'color' ? 'selected' : ''}>لون</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label small">&nbsp;</label>
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="product_options[${index}][is_required]" 
                                       value="1" ${option.is_required ? 'checked' : ''} id="required_${index}">
                                <label class="form-check-label small" for="required_${index}">مطلوب</label>
                            </div>
                        </div>
                        <div>
                            <label class="form-label small">&nbsp;</label>
                            <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="deleteOption(${index})">
                                <i class="fas fa-trash me-1"></i> حذف
                            </button>
                        </div>
                    </div>

                    <!-- Hierarchical Dependency Selector -->
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label small">يعتمد على الخيار</label>
                                <select class="form-select form-select-sm" name="product_options[${index}][depends_on_option_id]" 
                                        onchange="toggleDependencySection(this, ${index})">
                                    <option value="">لا يعتمد (رئيسي)</option>
                                    ${generateParentOptionsTree(index, option.depends_on_option_id)}
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">شرط الاعتماد</label>
                                <select class="form-select form-select-sm" name="product_options[${index}][dependency_condition]">
                                    <option value="equals" ${option.dependency_condition === 'equals' ? 'selected' : ''}>يساوي (=)</option>
                                    <option value="not_equals" ${option.dependency_condition === 'not_equals' ? 'selected' : ''}>لا يساوي (!=)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">القيمة المطلوبة</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="product_options[${index}][dependency_value]" 
                                       value="${option.dependency_value || ''}" placeholder="القيمة">
                            </div>
                        </div>
                    </div>

                    ${dependencyHtml}
                    ${tiersHtml}
                </div>
            `;
        }

        /**
         * Generate hierarchical parent options tree
         * يعرض الخيارات بشكل هرمي متداخل
         */
        function generateParentOptionsTree(currentIndex, selectedValue, level = 0, parentId = null) {
            let options = `<option value="">-- لا يعتمد (رئيسي) --</option>`;
            
            // فلترة الخيارات الأساسية (التي لا تعتمد على أحد) في المستوى الأول
            const mainOptions = existingOptions.filter((opt, idx) => 
                idx !== currentIndex && !opt.depends_on_option_id
            );
            
            // إضافة كل خيار رئيسي وأبنائه بشكل متداخل
            mainOptions.forEach(opt => {
                options += generateOptionWithChildren(opt, existingOptions.indexOf(opt), currentIndex, selectedValue, 0);
            });
            
            return options;
        }

        /**
         * توليد خيار مع جميع أبنائه بشكل متداخل
         */
        function generateOptionWithChildren(option, optionIndex, currentIndex, selectedValue, level) {
            const prefix = '─'.repeat(level * 2) + (level > 0 ? ' ' : '');
            const spacing = level > 0 ? '&nbsp;&nbsp;' + '&nbsp;&nbsp;'.repeat(level) : '';
            const selected = selectedValue == option.id ? 'selected' : '';
            
            let html = `<option value="${option.id}" ${selected} style="padding-right: ${level * 20}px">
                ${spacing}${prefix} ${option.option_name} (${option.option_value})
            </option>`;
            
            // البحث عن أبناء هذا الخيار (الخيارات التي تعتمد عليه)
            const children = existingOptions.filter((opt, idx) => 
                idx !== currentIndex && opt.depends_on_option_id == option.id
            );
            
            // إضافة الأبناء بشكل متكرر
            children.forEach(child => {
                const childIndex = existingOptions.indexOf(child);
                html += generateOptionWithChildren(child, childIndex, currentIndex, selectedValue, level + 1);
            });
            
            return html;
        }

        /**
         * الحصول على عمق الخيار في الشجرة
         */
        function getOptionDepth(optionId) {
            const option = existingOptions.find(opt => opt.id == optionId);
            if (!option || !option.depends_on_option_id) return 0;
            
            let depth = 1;
            let currentId = option.depends_on_option_id;
            
            while (currentId) {
                const parent = existingOptions.find(opt => opt.id == currentId);
                if (!parent) break;
                
                depth++;
                currentId = parent.depends_on_option_id;
            }
            
            return depth;
        }

        /**
         * الحصول على التسلسل الهرمي الكامل لخيار معين (من الجذر إلى هذا الخيار)
         */
        function getOptionHierarchy(optionId) {
            const hierarchy = [];
            let currentId = optionId;
            
            while (currentId) {
                const option = existingOptions.find(opt => opt.id == currentId);
                if (!option) break;
                
                hierarchy.unshift({
                    id: option.id,
                    option_name: option.option_name,
                    option_value: option.option_value
                });
                
                currentId = option.depends_on_option_id;
            }
            
            return hierarchy;
        }

        /**
         * الحصول على جميع الأبناء المباشرين لخيار معين
         */
        function getDirectChildren(optionId) {
            return existingOptions.filter(opt => opt.depends_on_option_id == optionId);
        }

        /**
         * الحصول على جميع أحفاد خيار معين (بما فيهم الأبناء والأحفاد)
         */
        function getAllDescendants(optionId) {
            const descendants = [];
            const directChildren = getDirectChildren(optionId);
            
            directChildren.forEach(child => {
                descendants.push(child);
                const grandChildren = getAllDescendants(child.id);
                descendants.push(...grandChildren);
            });
            
            return descendants;
        }

        /**
         * التحقق من إمكانية جعل خيار يعتمد على خيار آخر (لمنع الدوران اللا نهائي)
         */
        function canDependOn(optionIndex, parentId) {
            if (!parentId) return true;
            
            const option = existingOptions[optionIndex];
            if (!option.id) return true; // خيار جديد
            
            // منع الاعتماد على النفس
            if (option.id == parentId) return false;
            
            // منع الاعتماد الدائري (الأب يعتمد على الابن)
            const parentOption = existingOptions.find(opt => opt.id == parentId);
            if (!parentOption) return true;
            
            // التحقق من أن الأب لا يعتمد على هذا الخيار بشكل غير مباشر
            let currentParentId = parentOption.depends_on_option_id;
            while (currentParentId) {
                if (currentParentId == option.id) return false;
                const grandParent = existingOptions.find(opt => opt.id == currentParentId);
                currentParentId = grandParent ? grandParent.depends_on_option_id : null;
            }
            
            return true;
        }

        /**
         * تحديث دالة toggleDependencySection لإضافة التحقق الهرمي
         */
        function toggleDependencySection(select, index) {
            const optionItem = $(select).closest('.option-item');
            const hasDependency = select.value !== '';
            const parentId = select.value;
            
            // التحقق من صحة العلاقة الهرمية
            if (hasDependency && !canDependOn(index, parentId)) {
                Swal.fire({
                    icon: 'error',
                    title: 'علاقة غير صالحة',
                    text: 'لا يمكن جعل هذا الخيار يعتمد على الخيار المحدد لأنه سيسبب تدويراً في الاعتماديات'
                });
                select.value = '';
                return;
            }
            
            if (hasDependency) {
                // تغيير الشارة إلى معتمد
                optionItem.find('.badge-main').removeClass('badge-main').addClass('badge-dependent');
                optionItem.find('.badge-dependent').html('<i class="fas fa-link me-1"></i>معتمد');
                
                // إضافة معلومات عن الأب والتسلسل الهرمي
                const parentOption = existingOptions.find(opt => opt.id == parentId);
                if (parentOption) {
                    const hierarchy = getOptionHierarchy(parentId);
                    let hierarchyHtml = '';
                    hierarchy.forEach((opt, idx) => {
                        hierarchyHtml += `<span class="badge bg-secondary me-1">${opt.option_name} (${opt.option_value})</span>`;
                        if (idx < hierarchy.length - 1) {
                            hierarchyHtml += ' <i class="fas fa-arrow-left text-muted mx-1"></i> ';
                        }
                    });
                    
                    let dependencyInfo = optionItem.find('.dependency-section');
                    if (dependencyInfo.length === 0) {
                        dependencyInfo = $(`
                            <div class="dependency-section show">
                                <div class="row">
                                    <div class="col-md-12 mb-2">
                                        <small class="text-muted d-block mb-2">
                                            <i class="fas fa-sitemap text-warning me-1"></i> 
                                            التسلسل الهرمي:
                                        </small>
                                    </div>
                                </div>
                            </div>
                        `);
                        optionItem.append(dependencyInfo);
                    }
                    
                    optionItem.find('.dependency-section .row').html(`
                        <div class="col-md-12">
                            <div class="hierarchy-chain">
                                ${hierarchyHtml}
                            </div>
                        </div>
                    `);
                }
            } else {
                // تغيير الشارة إلى رئيسي
                optionItem.find('.badge-dependent').removeClass('badge-dependent').addClass('badge-main');
                optionItem.find('.badge-main').html('<i class="fas fa-cube me-1"></i>رئيسي');
                optionItem.find('.dependency-section').remove();
            }
        }

        /**
         * Add new option at the TOP of the container
         */
        function addNewOption() {
            const name = $('#new_option_name').val();
            const value = $('#new_option_value').val();
            const price = $('#new_option_price').val() || 0;
            const type = $('#new_option_type').val();
            const isRequired = $('#new_option_required').is(':checked');

            if (!name || !value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'بيانات ناقصة',
                    text: 'يرجى إدخال اسم الخيار والقيمة'
                });
                return;
            }

            // Create new option object
            const newOption = {
                id: null,
                option_name: name,
                option_value: value,
                additional_price: price,
                is_required: isRequired,
                option_type: type,
                depends_on_option_id: null,
                dependency_condition: 'equals',
                dependency_value: '',
                parent_option: null,
                quantity_tiers: []
            };

            // Add to beginning of array
            existingOptions.unshift(newOption);

            // Refresh container (adds to top because we unshifted)
            refreshOptionsContainer();

            // Reset form
            $('#new_option_name').val('');
            $('#new_option_value').val('');
            $('#new_option_price').val('');
            $('#new_option_required').prop('checked', false);
            $('#new_option_type').val('regular');
            $('#bulk_option_values').val('');

            Swal.fire({
                icon: 'success',
                title: 'تم!',
                text: 'تم إضافة الخيار في الأعلى',
                timer: 1500,
                showConfirmButton: false
            });
        }

        /**
         * Add multiple options at once
         */
        function addBulkOptions() {
            const name = $('#new_option_name').val();
            const values = $('#bulk_option_values').val().split(',').map(v => v.trim()).filter(v => v);

            if (!name) {
                Swal.fire({
                    icon: 'warning',
                    title: 'خطأ',
                    text: 'يرجى إدخال اسم الخيار أولاً'
                });
                return;
            }

            if (values.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'خطأ',
                    text: 'يرجى إدخال قيم مفصولة بفاصلة'
                });
                return;
            }

            const price = $('#new_option_price').val() || 0;
            const type = $('#new_option_type').val();
            const isRequired = $('#new_option_required').is(':checked');

            values.reverse().forEach(value => {
                const newOption = {
                    id: null,
                    option_name: name,
                    option_value: value,
                    additional_price: price,
                    is_required: isRequired,
                    option_type: type,
                    depends_on_option_id: null,
                    dependency_condition: 'equals',
                    dependency_value: '',
                    parent_option: null,
                    quantity_tiers: []
                };
                existingOptions.unshift(newOption);
            });

            refreshOptionsContainer();

            Swal.fire({
                icon: 'success',
                title: 'تم!',
                text: `تم إضافة ${values.length} خيار في الأعلى`,
                timer: 1500,
                showConfirmButton: false
            });

            $('#bulk_option_values').val('');
        }

        /**
         * Delete an option with all its descendants
         */
        function deleteOption(index) {
            const option = existingOptions[index];
            
            if (!option) return;
            
            // البحث عن الأبناء الذين يعتمدون على هذا الخيار
            const descendants = option.id ? getAllDescendants(option.id) : [];
            
            let warningMessage = 'سيتم حذف هذا الخيار';
            if (descendants.length > 0) {
                warningMessage += ` بالإضافة إلى ${descendants.length} خيار${descendants.length > 1 ? 'ات' : ''} فرعي${descendants.length > 1 ? 'ة' : ''} تعتمد عليه`;
            }
            
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: warningMessage,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (option.id) {
                        // حذف الخيار وجميع أبنائه
                        const descendantIndices = descendants.map(d => existingOptions.indexOf(d)).filter(i => i !== -1);
                        
                        // حذف من الأكبر للأصغر (من الأسفل للأعلى)
                        descendantIndices.sort((a, b) => b - a);
                        descendantIndices.forEach(i => existingOptions.splice(i, 1));
                    }
                    
                    // حذف الخيار الأصلي
                    const originalIndex = existingOptions.indexOf(option);
                    if (originalIndex !== -1) {
                        existingOptions.splice(originalIndex, 1);
                    }
                    
                    refreshOptionsContainer();
                    Swal.fire('تم الحذف!', `تم حذف الخيار${descendants.length > 0 ? ' وجميع الخيارات المرتبطة به' : ''} بنجاح`, 'success');
                }
            });
        }

        /**
         * Show hierarchical tree view
         */
        function showHierarchyTree() {
            // إزالة أي شجرة موجودة
            $('.hierarchy-tree').remove();
            
            const mainOptions = existingOptions.filter(opt => !opt.depends_on_option_id);
            
            let treeHtml = '<div class="hierarchy-tree mt-4 p-3" style="background: rgba(0,0,0,0.2); border-radius: 8px;">';
            treeHtml += '<h6 class="mb-3"><i class="fas fa-sitemap me-2"></i> الشجرة الهرمية للخيارات</h6>';
            
            if (mainOptions.length === 0) {
                treeHtml += '<p class="text-muted">لا توجد خيارات رئيسية</p>';
            } else {
                treeHtml += '<ul class="list-unstyled">';
                mainOptions.forEach(opt => {
                    treeHtml += generateHierarchyTreeItem(opt, existingOptions.indexOf(opt));
                });
                treeHtml += '</ul>';
            }
            
            treeHtml += '</div>';
            
            // إضافة الشجرة بعد حاوية الخيارات
            $('#optionsContainer').after(treeHtml);
        }

        /**
         * Generate hierarchical tree item
         */
        function generateHierarchyTreeItem(option, index) {
            const children = existingOptions.filter(opt => opt.depends_on_option_id == option.id);
            const typeLabel = getOptionTypeLabel(option.option_type);
            
            let html = `
                <li style="margin-bottom: 8px;">
                    <div class="d-flex align-items-center gap-2" style="background: rgba(105, 108, 255, 0.1); padding: 8px; border-radius: 6px;">
                        <span class="badge bg-primary">${index + 1}</span>
                        <span class="fw-bold">${option.option_name}</span>
                        <span class="text-muted">(${option.option_value})</span>
                        <span class="type-badge type-${option.option_type}">${typeLabel}</span>
                        ${option.is_required ? '<span class="badge bg-danger">مطلوب</span>' : ''}
                        ${option.additional_price > 0 ? `<span class="badge bg-success">+${option.additional_price} ج.م</span>` : ''}
                        ${option.id ? `<span class="badge bg-info">ID: ${option.id}</span>` : ''}
                    </div>
            `;
            
            if (children.length > 0) {
                html += '<ul class="list-unstyled" style="margin-right: 30px; margin-top: 8px;">';
                children.forEach(child => {
                    html += generateHierarchyTreeItem(child, existingOptions.indexOf(child));
                });
                html += '</ul>';
            }
            
            html += '</li>';
            return html;
        }

        /**
         * Refresh the entire options container
         */
        function refreshOptionsContainer() {
            const container = document.getElementById('optionsContainer');
            container.innerHTML = '';

            existingOptions.forEach((option, index) => {
                option.index = index;
                const optionHtml = generateOptionHtml(option, index);
                container.innerHTML += optionHtml;
            });

            initializeSortable();
            updateOptionsCount();
        }

        /**
         * Initialize Sortable.js for smooth drag & drop
         */
        function initializeSortable() {
            const container = document.getElementById('optionsContainer');

            if (sortableInstance) {
                sortableInstance.destroy();
            }

            sortableInstance = Sortable.create(container, {
                animation: 200,
                handle: '.option-drag-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',
                dragClass: 'sortable-drag',
                easing: 'cubic-bezier(0.4, 0, 0.2, 1)',
                scrollSensitivity: 30,
                scrollSpeed: 10,
                onEnd: function(evt) {
                    // Reorder existingOptions array
                    const movedItem = existingOptions.splice(evt.oldIndex, 1)[0];
                    existingOptions.splice(evt.newIndex, 0, movedItem);

                    // Refresh indices
                    refreshOptionsContainer();
                }
            });
        }

        /**
         * Update options count badge
         */
        function updateOptionsCount() {
            $('#optionsCount').text(existingOptions.length + ' خيار');
        }

        /**
         * Add quantity tier to an option
         */
        function addQuantityTier(index) {
            const option = existingOptions[index];
            if (!option.quantity_tiers) {
                option.quantity_tiers = [];
            }

            option.quantity_tiers.push({
                quantity: '',
                price_per_unit: '',
                tier_name: ''
            });

            refreshOptionsContainer();
        }

        /**
         * Remove a tier
         */
        function removeTier(button) {
            $(button).closest('.tier-item').remove();
        }

        /**
         * Update option preview (badge text)
         */
        function updateOptionPreview(input) {
            // Optional: update badge text if needed
        }

        /**
         * Update option type
         */
        function updateOptionType(select, index) {
            const type = $(select).val();
            const optionItem = $(select).closest('.option-item');
            const typeBadge = optionItem.find('.type-badge');

            typeBadge.removeClass('type-regular type-quantity type-size type-color')
                .addClass(`type-${type}`)
                .text(getOptionTypeLabel(type));

            // تحديث نوع الخيار في المصفوفة
            if (existingOptions[index]) {
                existingOptions[index].option_type = type;
            }

            // Add/remove quantity tiers section
            const existingTiers = optionItem.find('.tiers-section');
            if (type === 'quantity' && existingTiers.length === 0) {
                // Add tiers section
                const tiersHtml = `
                    <div class="tiers-section">
                        <h6 class="mb-3" style="color: #17a2b8;"><i class="fas fa-layer-group me-2"></i>شرائح الكمية</h6>
                        <div id="tiersContainer_${index}"></div>
                        <button type="button" class="btn btn-sm btn-outline-info mt-2" onclick="addQuantityTier(${index})">
                            <i class="fas fa-plus me-1"></i> إضافة شريحة
                        </button>
                    </div>
                `;
                optionItem.append(tiersHtml);
            } else if (type !== 'quantity' && existingTiers.length > 0) {
                existingTiers.remove();
                if (existingOptions[index]) {
                    existingOptions[index].quantity_tiers = [];
                }
            }
        }

        /**
         * Get option type label in Arabic
         */
        function getOptionTypeLabel(type) {
            const labels = {
                'regular': 'عادي',
                'quantity': 'كمية',
                'size': 'مقاس',
                'color': 'لون'
            };
            return labels[type] || 'عادي';
        }

        // ============================================
        // ⭐ STEP NAVIGATION
        // ============================================
        function navigateToStep(step) {
            $('.step-card').hide();
            $(`.step-${step}`).show();

            $('.wizard-step').removeClass('active completed');
            for (let i = 1; i <= step; i++) {
                $(`#step${i}`).addClass(i === step ? 'active' : 'completed');
            }

            $('html, body').animate({
                scrollTop: 0
            }, 300);
        }

        // ============================================
        // ⭐ IMAGE MANAGEMENT
        // ============================================
        function previewMainImage(file) {
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const html = `
                    <div class="image-preview-item">
                        <span class="badge-new">جديد</span>
                        <img src="${e.target.result}" alt="صورة جديدة">
                        <div class="image-actions">
                            <button type="button" class="btn btn-sm btn-info" onclick="viewImage('${e.target.result}')">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" onclick="removeNewMainImage()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;
                $('#newMainImagePreview').html(html).show();
                $('.preview-image').attr('src', e.target.result);
                $('#remove_main_image').val('0');
            };
            reader.readAsDataURL(file);
        }

        function previewMultipleImages(files) {
            const container = $('#newImagesPreview');
            container.empty();

            Array.from(files).forEach((file, index) => {
                if (!file.type.match('image.*')) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const html = `
                        <div class="image-preview-item" data-new-index="${index}">
                            <span class="badge-new">جديد</span>
                            <img src="${e.target.result}" alt="صورة جديدة">
                            <div class="image-actions">
                                <button type="button" class="btn btn-sm btn-info" onclick="viewImage('${e.target.result}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="removeNewImage(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    container.append(html);
                };
                reader.readAsDataURL(file);
            });
        }

        function viewImage(src) {
            $('#viewedImage').attr('src', src);
            new bootstrap.Modal(document.getElementById('imageViewModal')).show();
        }

        function removeCurrentMainImage() {
            $('#remove_main_image').val('1');
            $('#currentMainImageContainer').slideUp();
            $('.preview-image').attr('src', 'https://via.placeholder.com/80x80?text=No+Image');
        }

        function removeNewMainImage() {
            $('#newMainImagePreview').hide().empty();
            $('#image').val('');
            if ($('#remove_main_image').val() === '1') {
                $('.preview-image').attr('src', 'https://via.placeholder.com/80x80?text=No+Image');
            }
        }

        function removeAdditionalImage(imageId) {
            removedImages.push(imageId);
            $('#removed_images').val(removedImages.join(','));
            $(`.image-preview-item[data-id="${imageId}"]`).remove();
        }

        function removeNewImage(index) {
            $(`.image-preview-item[data-new-index="${index}"]`).remove();
        }

        function setAsPrimary(imageId) {
            $('#primary_image_id').val(imageId);
            Swal.fire({
                icon: 'success',
                title: 'تم',
                text: 'تم تعيين الصورة كرئيسية',
                timer: 1500,
                showConfirmButton: false
            });
        }

        function updateImagesOrder() {
            const order = [];
            $('#existingImagesGrid .image-preview-item').each(function() {
                const id = $(this).data('id');
                if (id) order.push(id);
            });
            $('#images_order').val(order.join(','));
        }

        // ============================================
        // ⭐ SEO & UTILITIES
        // ============================================
        function generateSlug() {
            const name = $('#name').val();
            if (!name) return;

            let slug = name
                .toLowerCase()
                .replace(/[^\u0600-\u06FF\w\s]/g, '')
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-')
                .trim();

            slug += '-' + {{ $product->id }};
            $('#slug').val(slug);
        }

        function saveAndContinue() {
            const formData = new FormData(document.getElementById('editProductForm'));

            fetch('{{ route('admin.products.update', $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ',
                            text: 'تم حفظ التعديلات بنجاح',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(data.message || 'حدث خطأ');
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: error.message
                    });
                });
        }

        function confirmDelete() {
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endsection