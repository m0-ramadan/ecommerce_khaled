@extends('Admin.layout.master')

@section('title', 'إضافة منتج جديد')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
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

        .wizard-step.completed .wizard-step-circle {
            background: var(--success-color);
            color: white;
        }

        /* ================ */
        /* ⭐ OPTIONS STYLES */
        /* ================ */

        /* Add Option Section - Always at Top */
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
            width: 100%;
        }

        .btn-add-option:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(105, 108, 255, 0.4);
        }

        /* Options Container - Smooth Drag & Drop */
        .options-sortable-container {
            min-height: 100px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 20px;
        }

        /* Option Item */
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

        /* Drag & Drop States */
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
            flex-wrap: wrap;
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
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
        }

        /* Image Management */
        .image-upload-container {
            border: 2px dashed rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-upload-container:hover {
            border-color: var(--primary-color);
            background: rgba(105, 108, 255, 0.1);
        }

        .image-upload-container i {
            font-size: 48px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .grid-item {
            position: relative;
            height: 120px;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .grid-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .grid-item-actions {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.8);
            padding: 5px;
            display: flex;
            justify-content: center;
            gap: 5px;
            transform: translateY(100%);
            transition: transform 0.3s;
        }

        .grid-item:hover .grid-item-actions {
            transform: translateY(0);
        }

        .main-image-preview {
            max-width: 300px;
            position: relative;
            margin-top: 15px;
        }

        .main-image-preview .image-preview {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            height: 200px;
        }

        .main-image-preview .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .main-image-preview:hover .image-overlay {
            opacity: 1;
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

        .alert-guide h6 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        /* Summary Card */
        .summary-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.7);
        }

        .summary-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        .badge-new {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .select2-container--default .select2-selection--single,
        .select2-container--default .select2-selection--multiple {
            background-color: rgba(255, 255, 255, 0.05) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff !important;
        }

        .select2-dropdown {
            background-color: var(--dark-card) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .select2-results__option {
            color: #fff !important;
        }

        .select2-results__option--highlighted[aria-selected] {
            background-color: var(--primary-color) !important;
        }

        /* Text Ads Section */
        .text-ad-item {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .text-ad-remove {
            position: absolute;
            top: -10px;
            left: -10px;
            width: 28px;
            height: 28px;
            background: var(--danger-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            cursor: pointer;
            border: 2px solid var(--dark-card);
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

        @media (max-width: 768px) {
            .wizard-steps {
                flex-direction: column;
                gap: 15px;
            }

            .wizard-steps::before {
                display: none;
            }

            .wizard-step {
                display: flex;
                align-items: center;
                gap: 15px;
                text-align: right;
            }

            .wizard-step-circle {
                margin: 0;
            }

            .option-content-grid {
                grid-template-columns: 1fr;
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
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.products.index') }}" class="text-primary">المنتجات</a>
                </li>
                <li class="breadcrumb-item active text-white">إضافة منتج جديد</li>
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
                <div class="wizard-step-label">التأكيد والإرسال</div>
            </div>
        </div>

        <!-- Quick Guide -->
        <div class="alert-guide">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-lightbulb fa-2x" style="color: var(--primary-color);"></i>
                <div>
                    <h6 class="mb-1">نصائح سريعة لإضافة المنتج</h6>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-grip-vertical me-1"></i> اسحب وأسفل الخيارات لإعادة ترتيبها |
                        <i class="fas fa-arrow-up me-1"></i> الخيارات الجديدة تُضاف في الأعلى |
                        <i class="fas fa-link me-1"></i> يمكن جعل الخيار يعتمد على خيار آخر
                    </p>
                </div>
            </div>
        </div>

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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

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
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                            placeholder="أدخل اسم المنتج" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="category_id" class="form-label required">القسم</label>
                        <select class="form-control select2" id="category_id" name="category_id" required>
                            <option value="">اختر القسم</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 mb-3">
                        <label for="description" class="form-label">الوصف</label>
                        <textarea class="form-control summernote" id="description" name="description" rows="6">{{ old('description') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status_id" class="form-label required">الحالة</label>
                        <select class="form-select" id="status_id" name="status_id" required>
                            <option value="1" {{ old('status_id', 1) == 1 ? 'selected' : '' }}>نشط</option>
                            <option value="2" {{ old('status_id') == 2 ? 'selected' : '' }}>غير نشط</option>
                            <option value="3" {{ old('status_id') == 3 ? 'selected' : '' }}>مسودة</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stock" class="form-label required">الكمية المتاحة</label>
                        <input type="number" class="form-control" id="stock" name="stock"
                            value="{{ old('stock', 0) }}" min="0" required>
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
                        <p class="step-description">أضف صور المنتج وحدد التسعير الأساسي</p>
                    </div>
                </div>

                <!-- Main Image -->
                <div class="mb-4">
                    <label class="form-label required">الصورة الرئيسية</label>
                    <div class="image-upload-container" onclick="document.getElementById('image').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p class="mb-0">انقر لرفع الصورة الرئيسية</p>
                        <small class="text-muted">الحجم الموصى به: 800×800 بكسل</small>
                    </div>
                    <input type="file" id="image" name="image" accept="image/*" style="display: none;"
                        onchange="previewMainImage(this)" required>

                    <div id="mainImagePreview" class="main-image-preview mt-3" style="display: none;">
                        <div class="image-preview">
                            <img id="mainImagePreviewImg" src="" alt="الصورة الرئيسية">
                            <div class="image-overlay">
                                <button type="button" class="btn btn-info btn-sm" onclick="viewMainImage()">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" onclick="removeMainImage()">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Images -->
                <div class="mb-4">
                    <label class="form-label">صور إضافية</label>
                    <div class="image-upload-container" onclick="document.getElementById('additional_images').click()">
                        <i class="fas fa-images"></i>
                        <p class="mb-0">انقر لرفع صور إضافية</p>
                        <small class="text-muted">يمكنك رفع أكثر من صورة</small>
                    </div>
                    <input type="file" id="additional_images" name="additional_images[]" accept="image/*" multiple
                        style="display: none;" onchange="previewAdditionalImages(this)">

                    <div id="additionalImagesPreview" class="preview-grid mt-3"></div>
                </div>

                <!-- Basic Pricing -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label required">السعر الأساسي</label>
                        <div class="input-group">
                            <span class="input-group-text">ر.س</span>
                            <input type="number" class="form-control" id="price" name="price" step="0.01"
                                value="{{ old('price') }}" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="price_text" class="form-label">نص السعر</label>
                        <input type="text" class="form-control" id="price_text" name="price_text"
                            value="{{ old('price_text') }}" placeholder="مثال: ١٠٠ ريال">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="toggle-container">
                            <label class="toggle-switch">
                                <input type="checkbox" id="has_discount" name="has_discount"
                                    {{ old('has_discount') ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">خصم على المنتج</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="toggle-container">
                            <label class="toggle-switch">
                                <input type="checkbox" id="includes_tax" name="includes_tax"
                                    {{ old('includes_tax') ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">يشمل الضريبة</span>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="toggle-container">
                            <label class="toggle-switch">
                                <input type="checkbox" id="includes_shipping" name="includes_shipping"
                                    {{ old('includes_shipping') ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">الشحن مجاني</span>
                        </div>
                    </div>
                </div>

                <!-- Discount Section -->
                <div id="discountSection" style="{{ old('has_discount') ? '' : 'display: none;' }}">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="discount_type" class="form-label">نوع الخصم</label>
                            <select class="form-select" id="discount_type" name="discount_type">
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                    نسبة مئوية</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>قيمة ثابتة
                                </option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="discount_value" class="form-label">قيمة الخصم</label>
                            <input type="number" class="form-control" id="discount_value" name="discount_value"
                                step="0.01" value="{{ old('discount_value') }}" placeholder="0.00">
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
                                <button type="button" class="btn btn-add-option" onclick="addNewOption()">
                                    <i class="fas fa-plus me-1"></i> إضافة
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Add Multiple Values -->
                    <div class="mt-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="text-muted small">إضافة متعددة:</span>
                            <input type="text" class="form-control form-control-sm" id="bulk_option_values"
                                placeholder="قيم مفصولة بفاصلة (مثال: أحمر, أزرق, أخضر)" style="width: 350px;">
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
                    <h6 class="mb-0"><i class="fas fa-sort me-2"></i> خيارات المنتج - اسحب وأسفل لإعادة الترتيب</h6>
                    <span class="badge bg-primary" id="optionsCount">0 خيار</span>
                </div>

                <div id="optionsContainer" class="options-sortable-container">
                    <!-- ⭐ OPTIONS WILL BE ADDED HERE VIA JAVASCRIPT ⭐ -->
                    <!-- Initially empty - options are added by the user -->
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

                <!-- Text Ads Section -->
                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <label class="form-label mb-0 fw-bold">النصوص الإعلانية</label>
                            <small class="text-muted d-block">أضف نصوصاً إعلانية تظهر مع المنتج</small>
                        </div>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addTextAd()">
                            <i class="fas fa-plus me-1"></i> إضافة نص إعلاني
                        </button>
                    </div>
                    <div id="textAdsContainer"></div>
                </div>

                <!-- Delivery Time & Warranty -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">وقت التوصيل</label>
                        <div class="row">
                            <div class="col-6">
                                <label for="from_days" class="form-label small">من (أيام)</label>
                                <input type="number" class="form-control" id="from_days" name="from_days"
                                    value="{{ old('from_days') }}" min="0" placeholder="0">
                            </div>
                            <div class="col-6">
                                <label for="to_days" class="form-label small">إلى (أيام)</label>
                                <input type="number" class="form-control" id="to_days" name="to_days"
                                    value="{{ old('to_days') }}" min="0" placeholder="0">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="warranty_months" class="form-label">الضمان (بالأشهر)</label>
                        <input type="number" class="form-control" id="warranty_months" name="warranty_months"
                            value="{{ old('warranty_months') }}" min="0" placeholder="0">
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

            <!-- ============ STEP 4: CONFIRMATION ============ -->
            <div class="step-card step-4" style="display: none;">
                <div class="step-header">
                    <div class="step-number">4</div>
                    <div>
                        <h5 class="step-title">التأكيد والإرسال</h5>
                        <p class="step-description">راجع المعلومات ثم أرسل النموذج</p>
                    </div>
                </div>

                <!-- Summary -->
                <div class="summary-card">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>ملخص المنتج</h6>

                    <div class="summary-row">
                        <span class="summary-label">اسم المنتج:</span>
                        <span class="summary-value" id="summary_name">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">السعر الأساسي:</span>
                        <span class="summary-value" id="summary_price">- ر.س</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">الكمية:</span>
                        <span class="summary-value" id="summary_stock">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">الحالة:</span>
                        <span class="summary-value" id="summary_status">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">عدد الخيارات:</span>
                        <span class="summary-value" id="summary_options">0</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">النصوص الإعلانية:</span>
                        <span class="summary-value" id="summary_text_ads">لا يوجد</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="3">
                        <i class="fas fa-arrow-right me-1"></i> السابق
                    </button>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> حفظ المنتج
                        </button>
                        <button type="button" class="btn btn-primary" onclick="saveAsDraft()">
                            <i class="fas fa-file-alt me-1"></i> حفظ كمسودة
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Fixed Action Buttons -->
    <div class="action-buttons-fixed">
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
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // ============================================
        // ⭐ GLOBAL VARIABLES
        // ============================================
        let productOptions = [];
        let sortableInstance = null;
        let textAdCounter = 0;

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

            // ========================================
            // ⭐ STEP NAVIGATION
            // ========================================
            $('.next-step').click(function() {
                const nextStep = $(this).data('next');
                const currentStep = $(this).closest('.step-card');

                if (validateStep(currentStep)) {
                    currentStep.hide();
                    $(`.step-${nextStep}`).show();
                    updateWizardSteps(nextStep);

                    if (nextStep == 4) {
                        updateSummary();
                    }
                }
            });

            $('.prev-step').click(function() {
                const prevStep = $(this).data('prev');
                $(this).closest('.step-card').hide();
                $(`.step-${prevStep}`).show();
                updateWizardSteps(prevStep);
            });

            // Discount toggle
            $('#has_discount').change(function() {
                $('#discountSection').slideToggle();
            });

            // Form submission
            $('#productForm').on('submit', function(e) {
                if (!validateAllSteps()) {
                    e.preventDefault();
                    return false;
                }
            });

            // Initialize options sortable
            initializeSortable();
            updateOptionsCount();
        });

        // ============================================
        // ⭐ VALIDATION
        // ============================================
        function validateStep(step) {
            let isValid = true;

            step.find('input[required], select[required], textarea[required]').each(function() {
                if (!$(this).val().trim()) {
                    $(this).addClass('is-invalid');
                    isValid = false;

                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after('<div class="invalid-feedback text-danger">هذا الحقل مطلوب</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            // Special validation for step 3 (at least one option is recommended)
            if (step.hasClass('step-3')) {
                if (productOptions.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'لا توجد خيارات',
                        text: 'من الأفضل إضافة خيار واحد على الأقل للمنتج',
                        confirmButtonText: 'متابعة'
                    });
                }
            }

            return isValid;
        }

        function validateAllSteps() {
            for (let i = 1; i <= 3; i++) {
                const step = $(`.step-${i}`);
                if (!validateStep(step)) {
                    // Show the step with errors
                    $('.step-card').hide();
                    step.show();
                    updateWizardSteps(i);

                    Swal.fire({
                        icon: 'error',
                        title: 'بيانات ناقصة',
                        text: `يرجى إكمال جميع الحقول المطلوبة في الخطوة ${i}`,
                        confirmButtonText: 'حسناً'
                    });

                    return false;
                }
            }

            // Collect form data before submission
            collectFormData();
            return true;
        }

        function updateWizardSteps(activeStep) {
            $('.wizard-step').removeClass('active completed');

            for (let i = 1; i <= 4; i++) {
                if (i < activeStep) {
                    $('#step' + i).addClass('completed');
                } else if (i == activeStep) {
                    $('#step' + i).addClass('active');
                }
            }
        }

        // ============================================
        // ⭐ OPTIONS MANAGEMENT - CORE FUNCTIONS
        // ============================================

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
                option_name: name,
                option_value: value,
                additional_price: price,
                is_required: isRequired,
                option_type: type,
                depends_on_option_id: null,
                dependency_condition: 'equals',
                dependency_value: '',
                quantity_tiers: []
            };

            // ⭐ ADD TO BEGINNING OF ARRAY (TOP)
            productOptions.unshift(newOption);

            // Refresh the container
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

            // Add in reverse order so they appear in the order entered
            values.reverse().forEach(value => {
                const newOption = {
                    option_name: name,
                    option_value: value,
                    additional_price: price,
                    is_required: isRequired,
                    option_type: type,
                    depends_on_option_id: null,
                    dependency_condition: 'equals',
                    dependency_value: '',
                    quantity_tiers: []
                };
                productOptions.unshift(newOption);
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
         * Generate HTML for an option
         */
        function generateOptionHtml(option, index) {
            const isMain = !option.depends_on_option_id;
            const mainBadge = isMain ?
                '<span class="badge-main option-badge"><i class="fas fa-cube me-1"></i>رئيسي</span>' :
                '<span class="badge-dependent option-badge"><i class="fas fa-link me-1"></i>معتمد</span>';

            const requiredBadge = option.is_required ?
                '<span class="badge-required option-badge"><i class="fas fa-check-circle me-1"></i>مطلوب</span>' : '';

            const typeClass = `type-${option.option_type}`;
            const typeLabel = getOptionTypeLabel(option.option_type);

            // Dependency section
            let dependencyHtml = '';
            if (!isMain) {
                dependencyHtml = `
                    <div class="dependency-section show">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <small class="text-muted d-block mb-2">
                                    <i class="fas fa-link text-warning me-1"></i> 
                                    يعتمد على: <strong>${option.parent_option_name || ''} = ${option.dependency_value || ''}</strong>
                                </small>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Quantity tiers
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
                                               value="${tier.quantity || ''}" placeholder="الكمية">
                                        <input type="number" class="form-control form-control-sm" 
                                               name="product_options[${index}][quantity_tiers][${tierIndex}][price_per_unit]" 
                                               value="${tier.price_per_unit || ''}" placeholder="السعر للوحدة">
                                        <input type="text" class="form-control form-control-sm" 
                                               name="product_options[${index}][quantity_tiers][${tierIndex}][tier_name]" 
                                               value="${tier.tier_name || ''}" placeholder="اسم الشريحة">
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeTier(this, ${index}, ${tierIndex})">
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

            // Parent options for dependency dropdown
            let parentOptions = '<option value="">لا يعتمد (رئيسي)</option>';
            productOptions.forEach((opt, idx) => {
                if (idx !== index && !opt.depends_on_option_id) {
                    const selected = option.depends_on_option_id == idx ? 'selected' : '';
                    parentOptions +=
                        `<option value="${idx}" ${selected}>${opt.option_name} (${opt.option_value})</option>`;
                }
            });

            return `
                <div class="option-item" data-index="${index}">
                    <div class="option-drag-handle">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                    
                    <div class="option-header">
                        ${mainBadge}
                        ${requiredBadge}
                        <span class="type-badge ${typeClass}">${typeLabel}</span>
                    </div>

                    <div class="option-content-grid">
                        <div>
                            <label class="form-label small">اسم الخيار</label>
                            <input type="text" class="form-control" name="product_options[${index}][option_name]" 
                                   value="${option.option_name}" placeholder="اسم الخيار" onchange="updateOptionName(this, ${index})">
                        </div>
                        <div>
                            <label class="form-label small">القيمة</label>
                            <input type="text" class="form-control" name="product_options[${index}][option_value]" 
                                   value="${option.option_value}" placeholder="القيمة" onchange="updateOptionValue(this, ${index})">
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

                    <!-- Dependency Selector -->
                    <div class="mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label small">يعتمد على الخيار</label>
                                <select class="form-select form-select-sm" name="product_options[${index}][depends_on_option_id]" 
                                        onchange="toggleDependency(this, ${index})">
                                    ${parentOptions}
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
         * Refresh the entire options container
         */
        function refreshOptionsContainer() {
            const container = document.getElementById('optionsContainer');
            container.innerHTML = '';

            productOptions.forEach((option, index) => {
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

            if (container) {
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
                        // Reorder productOptions array
                        const movedItem = productOptions.splice(evt.oldIndex, 1)[0];
                        productOptions.splice(evt.newIndex, 0, movedItem);

                        // Refresh indices
                        refreshOptionsContainer();
                    }
                });
            }
        }

        /**
         * Delete an option
         */
        function deleteOption(index) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف هذا الخيار',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    productOptions.splice(index, 1);
                    refreshOptionsContainer();
                    Swal.fire('تم الحذف!', 'تم حذف الخيار بنجاح', 'success');
                }
            });
        }

        /**
         * Update options count badge
         */
        function updateOptionsCount() {
            $('#optionsCount').text(productOptions.length + ' خيار');
        }

        /**
         * Toggle dependency for an option
         */
        function toggleDependency(select, index) {
            const optionItem = $(select).closest('.option-item');
            const parentIndex = select.value;

            if (parentIndex) {
                // Update the option to be dependent
                productOptions[index].depends_on_option_id = parseInt(parentIndex);
                productOptions[index].parent_option_name = productOptions[parentIndex].option_name;
                productOptions[index].dependency_value = productOptions[parentIndex].option_value;

                // Change badge
                optionItem.find('.badge-main').remove();
                optionItem.find('.option-header').prepend(
                    '<span class="badge-dependent option-badge"><i class="fas fa-link me-1"></i>معتمد</span>'
                );
            } else {
                // Make it independent again
                productOptions[index].depends_on_option_id = null;
                productOptions[index].parent_option_name = null;

                // Change badge
                optionItem.find('.badge-dependent').remove();
                optionItem.find('.option-header').prepend(
                    '<span class="badge-main option-badge"><i class="fas fa-cube me-1"></i>رئيسي</span>'
                );
            }

            refreshOptionsContainer();
        }

        /**
         * Add quantity tier to an option
         */
        function addQuantityTier(index) {
            if (!productOptions[index].quantity_tiers) {
                productOptions[index].quantity_tiers = [];
            }

            productOptions[index].quantity_tiers.push({
                quantity: '',
                price_per_unit: '',
                tier_name: ''
            });

            refreshOptionsContainer();
        }

        /**
         * Remove a tier
         */
        function removeTier(button, optionIndex, tierIndex) {
            productOptions[optionIndex].quantity_tiers.splice(tierIndex, 1);
            refreshOptionsContainer();
        }

        /**
         * Update option name in the productOptions array
         */
        function updateOptionName(input, index) {
            productOptions[index].option_name = input.value;
        }

        /**
         * Update option value in the productOptions array
         */
        function updateOptionValue(input, index) {
            productOptions[index].option_value = input.value;
        }

        /**
         * Update option type
         */
        function updateOptionType(select, index) {
            const type = select.value;
            productOptions[index].option_type = type;

            // Add quantity tiers array if type is quantity
            if (type === 'quantity' && !productOptions[index].quantity_tiers) {
                productOptions[index].quantity_tiers = [];
            }

            refreshOptionsContainer();
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
        // ⭐ TEXT ADS MANAGEMENT
        // ============================================
        function addTextAd() {
            const container = $('#textAdsContainer');
            const index = textAdCounter++;

            const adHtml = `
                <div class="text-ad-item position-relative" data-index="${index}">
                    <div class="text-ad-remove" onclick="removeTextAd(this)">
                        <i class="fas fa-times"></i>
                    </div>
                    <textarea class="form-control" name="text_ads[${index}][name]" 
                              placeholder="أدخل النص الإعلاني هنا..." rows="3" required></textarea>
                    <small class="text-muted mt-2 d-block">يظهر هذا النص مع المنتج لجذب العملاء</small>
                </div>
            `;

            container.append(adHtml);
        }

        function removeTextAd(button) {
            $(button).closest('.text-ad-item').remove();
        }

        // ============================================
        // ⭐ IMAGE MANAGEMENT
        // ============================================
        function previewMainImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#mainImagePreviewImg').attr('src', e.target.result);
                    $('#mainImagePreview').show();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeMainImage() {
            $('#image').val('');
            $('#mainImagePreview').hide();
        }

        function viewMainImage() {
            const src = $('#mainImagePreviewImg').attr('src');
            $('#viewedImage').attr('src', src);
            new bootstrap.Modal(document.getElementById('imageViewModal')).show();
        }

        function previewAdditionalImages(input) {
            const previewContainer = $('#additionalImagesPreview');
            previewContainer.empty();

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = `
                            <div class="grid-item">
                                <img src="${e.target.result}" alt="صورة إضافية">
                                <div class="grid-item-actions">
                                    <button type="button" class="btn btn-info btn-sm" onclick="viewImage('${e.target.result}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeAdditionalImage(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                        previewContainer.append(preview);
                    }
                    reader.readAsDataURL(file);
                });
            }
        }

        function removeAdditionalImage(button) {
            $(button).closest('.grid-item').remove();
        }

        function viewImage(src) {
            $('#viewedImage').attr('src', src);
            new bootstrap.Modal(document.getElementById('imageViewModal')).show();
        }

        // ============================================
        // ⭐ FORM COLLECTION & SUMMARY
        // ============================================
        function collectFormData() {
            // Clear existing hidden inputs for options
            $('input[name^="product_options"]').remove();

            // Add options to form
            productOptions.forEach((option, index) => {
                const prefix = `product_options[${index}]`;

                $('#productForm').append(
                    `<input type="hidden" name="${prefix}[option_name]" value="${option.option_name}">`);
                $('#productForm').append(
                    `<input type="hidden" name="${prefix}[option_value]" value="${option.option_value}">`);
                $('#productForm').append(
                    `<input type="hidden" name="${prefix}[additional_price]" value="${option.additional_price}">`
                    );
                $('#productForm').append(
                    `<input type="hidden" name="${prefix}[is_required]" value="${option.is_required ? 1 : 0}">`);
                $('#productForm').append(
                    `<input type="hidden" name="${prefix}[option_type]" value="${option.option_type}">`);
                $('#productForm').append(
                    `<input type="hidden" name="${prefix}[depends_on_option_id]" value="${option.depends_on_option_id || ''}">`
                    );
                $('#productForm').append(
                    `<input type="hidden" name="${prefix}[dependency_condition]" value="${option.dependency_condition || 'equals'}">`
                    );
                $('#productForm').append(
                    `<input type="hidden" name="${prefix}[dependency_value]" value="${option.dependency_value || ''}">`
                    );

                // Add quantity tiers
                if (option.quantity_tiers && option.quantity_tiers.length > 0) {
                    option.quantity_tiers.forEach((tier, tierIndex) => {
                        $('#productForm').append(
                            `<input type="hidden" name="${prefix}[quantity_tiers][${tierIndex}][quantity]" value="${tier.quantity || ''}">`
                            );
                        $('#productForm').append(
                            `<input type="hidden" name="${prefix}[quantity_tiers][${tierIndex}][price_per_unit]" value="${tier.price_per_unit || ''}">`
                            );
                        $('#productForm').append(
                            `<input type="hidden" name="${prefix}[quantity_tiers][${tierIndex}][tier_name]" value="${tier.tier_name || ''}">`
                            );
                    });
                }
            });
        }

        function updateSummary() {
            $('#summary_name').text($('#name').val() || '-');
            $('#summary_price').text(($('#price').val() || '0') + ' ر.س');
            $('#summary_stock').text($('#stock').val() || '0');
            $('#summary_options').text(productOptions.length);

            const statusText = {
                '1': 'نشط',
                '2': 'غير نشط',
                '3': 'مسودة'
            } [$('#status_id').val()] || '-';
            $('#summary_status').text(statusText);

            const textAdsCount = $('#textAdsContainer .text-ad-item').length;
            $('#summary_text_ads').text(textAdsCount > 0 ? textAdsCount + ' نص إعلاني' : 'لا يوجد');
        }

        function saveAsDraft() {
            $('#status_id').val('3');
            collectFormData();
            $('#productForm').submit();
        }
    </script>
@endsection
