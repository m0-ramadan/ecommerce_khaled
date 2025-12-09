@extends('Admin.layout.master')

@section('title', 'إضافة منتج جديد')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .step-card {
            /* background: white; */
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #696cff;
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f8f9fa;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #696cff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin-left: 15px;
        }

        .step-title {
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .step-description {
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Image Management */
        .image-manager {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            /* background: #f8f9fa; */
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-manager:hover {
            border-color: #696cff;
            background: rgba(105, 108, 255, 0.05);
        }

        .image-manager i {
            font-size: 48px;
            color: #696cff;
            margin-bottom: 15px;
        }

        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .image-preview-item {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            height: 150px;
        }

        .image-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-actions {
            position: absolute;
            bottom: 0;
            right: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.7);
            padding: 10px;
            display: flex;
            justify-content: center;
            gap: 5px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .image-preview-item:hover .image-actions {
            transform: translateY(0);
        }

        .image-actions .btn {
            width: 30px;
            height: 30px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .primary-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #696cff;
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: 600;
        }

        /* Color Management */
        .color-management {
            margin-top: 20px;
        }

        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
            gap: 10px;
            margin-bottom: 15px;
        }

        .color-item {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            border: 3px solid white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }

        .color-item:hover {
            transform: scale(1.1);
        }

        .color-item.selected {
            border-color: #696cff;
            transform: scale(1.1);
        }

        .color-item.selected::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Material Management */
        .material-item {
            /* background: white; */
            border: 1px solid #dee2e6;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .material-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .material-remove {
            color: #e74c3c;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }

        /* Dynamic Fields */
        .dynamic-field {
            /* background: #f8f9fa; */
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .dynamic-field-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
        }

        .dynamic-field-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .dynamic-field-remove {
            color: #e74c3c;
            cursor: pointer;
            background: none;
            border: none;
            font-size: 18px;
        }

        .add-more-btn {
            width: 100%;
            padding: 10px;
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            /* background: #f8f9fa; */
            color: #696cff;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .add-more-btn:hover {
            border-color: #696cff;
            background: rgba(105, 108, 255, 0.05);
        }

        /* Toggle Switch */
        .toggle-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toggle-switch {
            position: relative;
            width: 60px;
            height: 30px;
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
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: #696cff;
        }

        input:checked+.toggle-slider:before {
            transform: translateX(30px);
        }

        .toggle-label {
            font-weight: 500;
            color: #6fb7ff;
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
            /* background: #e0e0e0; */
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
            /* background: #e0e0e0; */
            color: #7f8c8d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            border: 4px solid white;
        }

        .wizard-step.active .wizard-step-circle {
            background: #696cff;
            color: white;
        }

        .wizard-step.completed .wizard-step-circle {
            background: #2ecc71;
            color: white;
        }

        .wizard-step-label {
            font-size: 14px;
            color: #7f8c8d;
            font-weight: 500;
        }

        .wizard-step.active .wizard-step-label {
            color: #696cff;
        }

        /* Required field indicator */
        .required::after {
            content: " *";
            color: #dc3545;
        }

        /* Alert Guide */
        .alert-guide {
            background: #26253d;
            border-right: 4px solid #696cff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-guide h6 {
            color: #696cff;
            margin-bottom: 10px;
        }

        .alert-guide ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .alert-guide li {
            margin-bottom: 5px;
            font-size: 14px;
        }

        /* Price Input */
        .price-input-group {
            position: relative;
        }

        .price-input-group .form-control {
            padding-left: 40px;
        }

        .price-input-group .input-group-text {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            background: none;
            border: none;
            z-index: 3;
        }

        /* Tabs */
        .form-tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 25px;
        }

        .form-tabs .nav-link {
            border: none;
            color: #7f8c8d;
            font-weight: 500;
            padding: 10px 20px;
            border-bottom: 3px solid transparent;
        }

        .form-tabs .nav-link.active {
            color: #696cff;
            border-bottom-color: #696cff;
            background: none;
        }

        /* Preview Card */
        .preview-card {
            /* background: white; */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            border: 2px solid #e9ecef;
        }

        .preview-card h6 {
            color: #2c3e50;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #e9ecef;
        }

        .preview-image {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .preview-price {
            font-size: 20px;
            font-weight: bold;
            color: #2ecc71;
        }

        .preview-old-price {
            font-size: 16px;
            color: #95a5a6;
            text-decoration: line-through;
        }

        /* Quick Add Modal */
        .quick-add-modal .modal-content {
            border-radius: 15px;
            /* overflow: hidden; */
        }

        .quick-add-modal .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-bottom: none;
            padding: 20px 30px;
        }

        .quick-add-modal .modal-body {
            padding: 30px;
        }

        .quick-add-modal .modal-footer {
            border-top: 1px solid #dee2e6;
            padding: 20px 30px;
        }

        /* Color Picker Input */
        .color-picker-input {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .color-picker-input input[type="color"] {
            width: 40px;
            height: 40px;
            padding: 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Stock Warning */
        .stock-warning {
            /* background: #fff3cd; */
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .stock-warning i {
            color: #f39c12;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .wizard-steps {
                flex-direction: column;
                gap: 15px;
            }

            .wizard-steps::before {
                display: none;
            }

            .wizard-step {
                text-align: right;
                display: flex;
                align-items: center;
                gap: 15px;
            }

            .wizard-step-circle {
                margin: 0;
            }
        }

        /* Tab Content */
        .tab-content {
            padding: 20px 0;
        }

        /* Image Sortable */
        .sortable-image-list {
            position: relative;
        }

        .sortable-image-list .image-preview-item {
            cursor: move;
        }

        .sortable-image-list .image-preview-item.sortable-chosen {
            box-shadow: 0 0 20px rgba(105, 108, 255, 0.3);
        }

        /* Color Price Input */
        .color-price-input {
            width: 100px;
            padding: 5px 10px;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            font-size: 14px;
        }

        /* Material Additional Price */
        .material-additional-price {
            /* background: #e7f7ff; */
            border: 1px dashed #3498db;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
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
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.products.index') }}">المنتجات</a>
                </li>
                <li class="breadcrumb-item active">إضافة منتج جديد</li>
            </ol>
        </nav>

        <div class="row" bis_skin_checked="1">
            <div class="col-12" bis_skin_checked="1">
                <div class="card mb-4" bis_skin_checked="1">
                    <div class="card-header d-flex justify-content-between align-items-center" bis_skin_checked="1">
                        <h5 class="mb-0">إضافة منتج جديد</h5>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-right me-1"></i> رجوع للقائمة
                        </a>
                    </div>

                    <div class="card-body" bis_skin_checked="1">
                        <!-- Quick Guide -->
                        <div class="alert-guide" bis_skin_checked="1">
                            <h6><i class="fas fa-lightbulb me-2"></i>نصائح سريعة لإضافة منتج ناجح:</h6>
                            <ul>
                                <li>يمكنك إضافة ألوان، مواد، طرق طباعة، وأماكن طباعة جديدة مباشرة من النموذج</li>
                                <li>استخدم زر "إضافة جديد" بجانب كل قسم لإضافة عناصر جديدة</li>
                                <li>يمكنك تحديد سعر إضافي لكل عنصر تضيفه</li>
                                <li>جميع العناصر المضافة سيتم حفظها تلقائياً في قاعدة البيانات</li>
                            </ul>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Wizard Steps -->
                        <div class="wizard-steps" bis_skin_checked="1">
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
                                <div class="wizard-step-label">المواصفات والخيارات</div>
                            </div>
                            <div class="wizard-step" id="step4">
                                <div class="wizard-step-circle">4</div>
                                <div class="wizard-step-label">التأكيد والإرسال</div>
                            </div>
                        </div>

                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data"
                            id="productForm">
                            @csrf

                            <!-- Step 1: Basic Information -->
                            <div class="step-card step-1" bis_skin_checked="1">
                                <div class="step-header" bis_skin_checked="1">
                                    <div class="step-number" bis_skin_checked="1">1</div>
                                    <div bis_skin_checked="1">
                                        <h5 class="step-title">المعلومات الأساسية</h5>
                                        <p class="step-description">أدخل المعلومات الأساسية للمنتج</p>
                                    </div>
                                </div>

                                <div class="row" bis_skin_checked="1">
                                    <div class="col-md-8 mb-3" bis_skin_checked="1">
                                        <label for="name" class="form-label required">اسم المنتج</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" required>
                                        <small class="text-muted">اسم واضح ومعبر عن المنتج</small>
                                    </div>

                                    <div class="col-md-4 mb-3" bis_skin_checked="1">
                                        <label for="category_id" class="form-label required">القسم</label>
                                        <div class="input-group" bis_skin_checked="1">
                                            <select class="form-control select2" id="category_id" name="category_id"
                                                required>
                                                <option value="">اختر القسم</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                        @if (!$category->isParent())
                                                            (تابع لـ: {{ $category->parent->name ?? '' }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick="openQuickAddModal('category')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3" bis_skin_checked="1">
                                        <label for="description" class="form-label">الوصف</label>
                                        <textarea class="form-control summernote" id="description" name="description" rows="6">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="col-md-6 mb-3" bis_skin_checked="1">
                                        <label for="status_id" class="form-label required">الحالة</label>
                                        <select class="form-select" id="status_id" name="status_id" required>
                                            <option value="1" {{ old('status_id', 1) == 1 ? 'selected' : '' }}>نشط
                                            </option>
                                            <option value="2" {{ old('status_id') == 2 ? 'selected' : '' }}>غير نشط
                                            </option>
                                            <option value="3" {{ old('status_id') == 3 ? 'selected' : '' }}>قيد
                                                المراجعة</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3" bis_skin_checked="1">
                                        <label for="stock" class="form-label required">الكمية المتاحة</label>
                                        <input type="number" class="form-control" id="stock" name="stock"
                                            value="{{ old('stock', 0) }}" min="0" required>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4" bis_skin_checked="1">
                                    <div></div>
                                    <button type="button" class="btn btn-primary next-step" data-next="2">
                                        التالي <i class="fas fa-arrow-left ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 2: Images & Pricing -->
                            <div class="step-card step-2" style="display: none;" bis_skin_checked="1">
                                <div class="step-header" bis_skin_checked="1">
                                    <div class="step-number" bis_skin_checked="1">2</div>
                                    <div bis_skin_checked="1">
                                        <h5 class="step-title">الصور والتسعير</h5>
                                        <p class="step-description">أضف صور المنتج وحدد التسعير</p>
                                    </div>
                                </div>

                                <!-- Main Image -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <label class="form-label required">الصورة الرئيسية</label>
                                    <div class="image-upload-container"
                                        onclick="document.getElementById('main_image').click()">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p class="mb-0">انقر لرفع الصورة الرئيسية</p>
                                        <small class="text-muted">الحجم الموصى به: 800×800 بكسل</small>
                                    </div>
                                    <input type="file" id="main_image" name="image" accept="image/*"
                                        style="display: none;" onchange="previewMainImage(this)" required>
                                    <div id="mainImagePreview" class="mt-3" style="display: none;">
                                        <div class="image-preview" style="max-width: 200px;">
                                            <img id="mainImagePreviewImg" src="" alt="صورة المنتج">
                                            <div class="image-overlay">
                                                <button type="button" class="btn btn-danger btn-sm"
                                                    onclick="removeMainImage()">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Images -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <label class="form-label">صور إضافية</label>
                                    <div class="image-upload-container"
                                        onclick="document.getElementById('additional_images').click()">
                                        <i class="fas fa-images"></i>
                                        <p class="mb-0">انقر لرفع صور إضافية</p>
                                        <small class="text-muted">يمكنك رفع أكثر من صورة</small>
                                    </div>
                                    <input type="file" id="additional_images" name="additional_images[]"
                                        accept="image/*" multiple style="display: none;"
                                        onchange="previewAdditionalImages(this)">
                                    <div id="additionalImagesPreview" class="row mt-3 g-3"></div>
                                </div>

                                <!-- Pricing -->
                                <div class="row" bis_skin_checked="1">
                                    <div class="col-md-6 mb-3" bis_skin_checked="1">
                                        <label for="price" class="form-label required">السعر الأساسي</label>
                                        <div class="price-input-group" bis_skin_checked="1">
                                            <span class="input-group-text">ج.م</span>
                                            <input type="number" class="form-control" id="price" name="price"
                                                step="0.01" value="{{ old('price') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Pricing Options -->
                                <div class="row" bis_skin_checked="1">
                                    <div class="col-md-4 mb-3" bis_skin_checked="1">
                                        <div class="toggle-container" bis_skin_checked="1">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="includes_tax" name="includes_tax"
                                                    {{ old('includes_tax') ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">يشمل الضريبة</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3" bis_skin_checked="1">
                                        <div class="toggle-container mb-3" bis_skin_checked="1">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="has_discount" name="has_discount"
                                                    {{ old('has_discount') ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">هل يحتوي المنتج على خصم؟</span>
                                        </div>

                                        <div id="discountSection"
                                            style="{{ old('has_discount') ? '' : 'display: none;' }}">
                                            <div class="row" bis_skin_checked="1">
                                                <div class="col-6" bis_skin_checked="1">
                                                    <label for="discount_type" class="form-label">نوع الخصم</label>
                                                    <select class="form-select" id="discount_type" name="discount_type">
                                                        <option value="percentage"
                                                            {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>
                                                            نسبة مئوية %</option>
                                                        <option value="fixed"
                                                            {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>قيمة
                                                            ثابتة</option>
                                                    </select>
                                                </div>
                                                <div class="col-6" bis_skin_checked="1">
                                                    <label for="discount_value" class="form-label">قيمة الخصم</label>
                                                    <input type="number" class="form-control" id="discount_value"
                                                        name="discount_value" step="0.01"
                                                        value="{{ old('discount_value') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3" bis_skin_checked="1">
                                        <div class="toggle-container" bis_skin_checked="1">
                                            <label class="toggle-switch">
                                                <input type="checkbox" id="includes_shipping" name="includes_shipping"
                                                    {{ old('includes_shipping') ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            <span class="toggle-label">يشمل الشحن</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4" bis_skin_checked="1">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">
                                        <i class="fas fa-arrow-right me-1"></i> السابق
                                    </button>
                                    <button type="button" class="btn btn-primary next-step" data-next="3">
                                        التالي <i class="fas fa-arrow-left ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Specifications & Options -->
                            <div class="step-card step-3" style="display: none;" bis_skin_checked="1">
                                <div class="step-header" bis_skin_checked="1">
                                    <div class="step-number" bis_skin_checked="1">3</div>
                                    <div bis_skin_checked="1">
                                        <h5 class="step-title">المواصفات والخيارات</h5>
                                        <p class="step-description">حدد مواصفات المنتج والخيارات المتاحة</p>
                                    </div>
                                </div>

                                <!-- Colors -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <div class="d-flex justify-content-between align-items-center mb-3"
                                        bis_skin_checked="1">
                                        <label class="form-label mb-0">الألوان المتاحة</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="openQuickAddModal('color')">
                                            <i class="fas fa-plus me-1"></i> إضافة لون جديد
                                        </button>
                                    </div>

                                    <!-- Existing Colors -->
                                    <div class="mb-3" bis_skin_checked="1">
                                        <label class="form-label">اختر من الألوان الموجودة:</label>
                                        <div class="color-picker-grid" id="existingColorsGrid">
                                            @foreach ($colors as $color)
                                                <div class="color-picker-item"
                                                    style="background-color: {{ $color->hex_code }};"
                                                    data-id="{{ $color->id }}" data-name="{{ $color->name }}"
                                                    data-hex="{{ $color->hex_code }}"
                                                    onclick="toggleColorSelection(this)">
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Selected Colors -->
                                    <div bis_skin_checked="1">
                                        <label class="form-label">الألوان المختارة:</label>
                                        <div id="selectedColorsContainer" class="selected-items-container">
                                            <!-- Selected colors will appear here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Materials -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <div class="d-flex justify-content-between align-items-center mb-3"
                                        bis_skin_checked="1">
                                        <label class="form-label mb-0">المواد المستخدمة</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="openQuickAddModal('material')">
                                            <i class="fas fa-plus me-1"></i> إضافة مادة جديدة
                                        </button>
                                    </div>

                                    <!-- Materials Selection -->
                                    <div class="mb-3" bis_skin_checked="1">
                                        <label class="form-label">اختر المواد:</label>
                                        <select class="form-select select2" id="materialsSelect" multiple>
                                            @foreach ($materials as $material)
                                                <option value="{{ $material->id }}"
                                                    data-description="{{ $material->description }}">
                                                    {{ $material->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Selected Materials with Quantity & Price -->
                                    <div bis_skin_checked="1">
                                        <label class="form-label">المواد المختارة مع الكميات:</label>
                                        <div id="materialsContainer" class="row g-3 mt-2">
                                            <!-- Dynamic material fields will be added here -->
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-3"
                                            onclick="addMaterialField()">
                                            <i class="fas fa-plus me-1"></i> إضافة مادة أخرى
                                        </button>
                                    </div>
                                </div>

                                <!-- Printing Methods -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <div class="d-flex justify-content-between align-items-center mb-3"
                                        bis_skin_checked="1">
                                        <label class="form-label mb-0">طرق الطباعة</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="openQuickAddModal('printing_method')">
                                            <i class="fas fa-plus me-1"></i> إضافة طريقة طباعة جديدة
                                        </button>
                                    </div>

                                    <!-- Printing Methods Selection -->
                                    <div class="mb-3" bis_skin_checked="1">
                                        <label class="form-label">اختر طرق الطباعة:</label>
                                        <select class="form-select select2" id="printingMethodsSelect"
                                            name="printing_methods[]" multiple>
                                            @foreach ($printingMethods as $method)
                                                <option value="{{ $method->id }}"
                                                    data-price="{{ $method->base_price }}">
                                                    {{ $method->name }} - {{ $method->base_price }} ج.م
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Selected Printing Methods -->
                                    <div bis_skin_checked="1">
                                        <label class="form-label">طرق الطباعة المختارة:</label>
                                        <div id="selectedPrintingMethods" class="selected-items-container">
                                            <!-- Selected printing methods will appear here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Print Locations -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <div class="d-flex justify-content-between align-items-center mb-3"
                                        bis_skin_checked="1">
                                        <label class="form-label mb-0">أماكن الطباعة</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="openQuickAddModal('print_location')">
                                            <i class="fas fa-plus me-1"></i> إضافة مكان طباعة جديد
                                        </button>
                                    </div>

                                    <!-- Print Locations Selection -->
                                    <div class="mb-3" bis_skin_checked="1">
                                        <label class="form-label">اختر أماكن الطباعة:</label>
                                        <select class="form-select select2" id="printLocationsSelect"
                                            name="print_locations[]" multiple>
                                            @foreach ($printLocations as $location)
                                                <option value="{{ $location->id }}" data-type="{{ $location->type }}"
                                                    data-price="{{ $location->additional_price }}">
                                                    {{ $location->name }} ({{ $location->type }}) -
                                                    {{ $location->additional_price }} ج.م
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Selected Print Locations -->
                                    <div bis_skin_checked="1">
                                        <label class="form-label">أماكن الطباعة المختارة:</label>
                                        <div id="selectedPrintLocations" class="selected-items-container">
                                            <!-- Selected print locations will appear here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Offers -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <div class="d-flex justify-content-between align-items-center mb-3"
                                        bis_skin_checked="1">
                                        <label class="form-label mb-0">العروض</label>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                            onclick="openQuickAddModal('offer')">
                                            <i class="fas fa-plus me-1"></i> إضافة عرض جديد
                                        </button>
                                    </div>

                                    <!-- Offers Selection -->
                                    <div class="mb-3" bis_skin_checked="1">
                                        <label class="form-label">اختر العروض:</label>
                                        <select class="form-select select2" id="offersSelect" name="offers[]" multiple>
                                            @foreach ($offers as $offer)
                                                <option value="{{ $offer->id }}">
                                                    {{ $offer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Selected Offers -->
                                    <div bis_skin_checked="1">
                                        <label class="form-label">العروض المختارة:</label>
                                        <div id="selectedOffers" class="selected-items-container">
                                            <!-- Selected offers will appear here -->
                                        </div>
                                    </div>
                                </div>

                                <!-- Delivery Time -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <label class="form-label">وقت التوصيل</label>
                                    <div class="row" bis_skin_checked="1">
                                        <div class="col-md-6 mb-3" bis_skin_checked="1">
                                            <label for="from_days" class="form-label">من (أيام)</label>
                                            <input type="number" class="form-control" id="from_days" name="from_days"
                                                value="{{ old('from_days') }}" min="0">
                                        </div>
                                        <div class="col-md-6 mb-3" bis_skin_checked="1">
                                            <label for="to_days" class="form-label">إلى (أيام)</label>
                                            <input type="number" class="form-control" id="to_days" name="to_days"
                                                value="{{ old('to_days') }}" min="0">
                                        </div>
                                    </div>
                                </div>

                                <!-- Warranty -->
                                <div class="mb-4" bis_skin_checked="1">
                                    <label for="warranty_months" class="form-label">الضمان (بالأشهر)</label>
                                    <input type="number" class="form-control" id="warranty_months"
                                        name="warranty_months" value="{{ old('warranty_months') }}" min="0">
                                </div>

                                <div class="d-flex justify-content-between mt-4" bis_skin_checked="1">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                                        <i class="fas fa-arrow-right me-1"></i> السابق
                                    </button>
                                    <button type="button" class="btn btn-primary next-step" data-next="4">
                                        التالي <i class="fas fa-arrow-left ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 4: Confirmation & Submit -->
                            <div class="step-card step-4" style="display: none;" bis_skin_checked="1">
                                <div class="step-header" bis_skin_checked="1">
                                    <div class="step-number" bis_skin_checked="1">4</div>
                                    <div bis_skin_checked="1">
                                        <h5 class="step-title">التأكيد والإرسال</h5>
                                        <p class="step-description">راجع المعلومات ثم أرسل النموذج</p>
                                    </div>
                                </div>

                                <!-- Summary -->
                                <div class="alert alert-info mb-4" bis_skin_checked="1">
                                    <h6 class="alert-heading mb-2"><i class="fas fa-info-circle me-2"></i>ملخص المنتج</h6>
                                    <div class="row" bis_skin_checked="1">
                                        <div class="col-md-6" bis_skin_checked="1">
                                            <p class="mb-1"><strong>اسم المنتج:</strong> <span id="summary_name"></span>
                                            </p>
                                            <p class="mb-1"><strong>السعر الأساسي:</strong> <span
                                                    id="summary_price"></span> ج.م</p>
                                            <p class="mb-1"><strong>الكمية:</strong> <span id="summary_stock"></span>
                                            </p>
                                        </div>
                                        <div class="col-md-6" bis_skin_checked="1">
                                            <p class="mb-1"><strong>الألوان:</strong> <span id="summary_colors"></span>
                                            </p>
                                            <p class="mb-1"><strong>المواد:</strong> <span
                                                    id="summary_materials"></span></p>
                                            <p class="mb-1"><strong>خيارات الطباعة:</strong> <span
                                                    id="summary_printing"></span></p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden Inputs for Dynamic Data -->
                                <div id="hiddenInputsContainer">
                                    <!-- Hidden inputs for selected items will be added here -->
                                </div>

                                <div class="d-flex justify-content-between mt-4" bis_skin_checked="1">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="3">
                                        <i class="fas fa-arrow-right me-1"></i> السابق
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-save me-1"></i> حفظ المنتج
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="saveAsDraft()">
                                        <i class="fas fa-file-alt me-1"></i> حفظ كمسودة
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Add Modals -->
    <div class="modal fade quick-add-modal" id="quickAddModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickAddModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quickAddForm">
                        <div id="quickAddFormContent">
                            <!-- Form content will be dynamically loaded here -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-primary" onclick="saveQuickAdd()">إضافة</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates -->
    <template id="materialFieldTemplate">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <select class="form-select material-select" name="materials[][material_id]" required>
                                <option value="">اختر المادة</option>
                                @foreach ($materials as $material)
                                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="materials[][quantity]" placeholder="الكمية"
                                min="0" step="0.01" required>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" name="materials[][unit]" required>
                                <option value="piece">قطعة</option>
                                <option value="meter">متر</option>
                                <option value="kg">كجم</option>
                                <option value="liter">لتر</option>
                                <option value="gram">جرام</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="materials[][additional_price]"
                                placeholder="سعر إضافي" step="0.01" min="0">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger btn-sm"
                                onclick="removeMaterialField(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="colorItemTemplate">
        <div class="selected-item color-item">
            <div class="selected-item-remove" onclick="removeSelectedItem(this, 'color')">×</div>
            <div class="selected-item-content">
                <div class="color-preview" style="background-color: {hex};"></div>
                <div class="selected-item-title">{name}</div>
                <input type="hidden" name="colors[]" value="{id}">
                <input type="hidden" name="color_prices[{id}]" value="{price}">
            </div>
        </div>
    </template>

    <template id="printingMethodItemTemplate">
        <div class="selected-item">
            <div class="selected-item-remove" onclick="removeSelectedItem(this, 'printing_method')">×</div>
            <div class="selected-item-content">
                <div class="selected-item-icon">
                    <i class="fas fa-print"></i>
                </div>
                <div class="selected-item-title">{name}</div>
                <div class="selected-item-price">{price} ج.م</div>
                <input type="hidden" name="printing_methods[]" value="{id}">
            </div>
        </div>
    </template>

    <template id="printLocationItemTemplate">
        <div class="selected-item">
            <div class="selected-item-remove" onclick="removeSelectedItem(this, 'print_location')">×</div>
            <div class="selected-item-content">
                <div class="selected-item-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <div class="selected-item-title">{name}</div>
                <div class="selected-item-price">{price} ج.م</div>
                <input type="hidden" name="print_locations[]" value="{id}">
            </div>
        </div>
    </template>

    <template id="offerItemTemplate">
        <div class="selected-item">
            <div class="selected-item-remove" onclick="removeSelectedItem(this, 'offer')">×</div>
            <div class="selected-item-content">
                <div class="selected-item-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="selected-item-title">{name}</div>
                <input type="hidden" name="offers[]" value="{id}">
            </div>
        </div>
    </template>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Global variables
        let selectedColors = new Map();
        let selectedPrintingMethods = new Map();
        let selectedPrintLocations = new Map();
        let selectedOffers = new Map();
        let selectedMaterials = new Set();
        let currentQuickAddType = '';
        let materialCounter = 0;

        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 200,
                lang: 'ar-AR',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Initialize Select2 with custom options
            $('.select2').select2({
                placeholder: 'اختر الخيارات',
                allowClear: true,
                templateResult: formatSelectOption,
                templateSelection: formatSelectSelection,
                language: {
                    noResults: function() {
                        return "لم يتم العثور على نتائج";
                    }
                }
            });

            // Custom select2 templates
            function formatSelectOption(item) {
                if (!item.id) {
                    return item.text;
                }

                let $option = $(
                    '<div>' + item.text + '</div>'
                );

                if ($(item.element).data('price')) {
                    $option.append('<small class="text-muted ms-2">' + $(item.element).data('price') +
                        ' ج.م</small>');
                }

                if ($(item.element).data('description')) {
                    $option.append('<br><small class="text-muted">' + $(item.element).data('description') +
                        '</small>');
                }

                return $option;
            }

            function formatSelectSelection(item) {
                return item.text;
            }

            // Wizard Navigation
            $('.next-step').on('click', function() {
                const currentStep = $(this).closest('.step-card');
                const nextStepNum = $(this).data('next');
                const nextStep = $('.step-' + nextStepNum);

                if (!validateStep(currentStep)) {
                    return;
                }

                if (nextStepNum == 4) {
                    updateSummary();
                }

                currentStep.hide();
                nextStep.show();
                updateWizardSteps(nextStepNum);
            });

            $('.prev-step').on('click', function() {
                const currentStep = $(this).closest('.step-card');
                const prevStepNum = $(this).data('prev');
                const prevStep = $('.step-' + prevStepNum);

                currentStep.hide();
                prevStep.show();
                updateWizardSteps(prevStepNum);
            });

            function validateStep(step) {
                let isValid = true;

                step.find('input[required], select[required], textarea[required]').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).addClass('is-invalid');
                        isValid = false;

                        if (!$(this).next('.invalid-feedback').length) {
                            $(this).after('<div class="invalid-feedback">هذا الحقل مطلوب</div>');
                        }
                    } else {
                        $(this).removeClass('is-invalid');
                        $(this).next('.invalid-feedback').remove();
                    }
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'بيانات ناقصة',
                        text: 'يرجى ملء جميع الحقول المطلوبة',
                        confirmButtonText: 'حسناً'
                    });
                }

                return isValid;
            }

            function updateWizardSteps(activeStep) {
                $('.wizard-step').removeClass('active completed');

                for (let i = 1; i <= 4; i++) {
                    const step = $('#step' + i);
                    if (i < activeStep) {
                        step.addClass('completed');
                    } else if (i == activeStep) {
                        step.addClass('active');
                    }
                }
            }

            // Toggle discount section
            $('#has_discount').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#discountSection').show();
                } else {
                    $('#discountSection').hide();
                }
            });

            // Initialize select2 change events
            $('#printingMethodsSelect').on('change', function() {
                updateSelectedPrintingMethods();
            });

            $('#printLocationsSelect').on('change', function() {
                updateSelectedPrintLocations();
            });

            $('#offersSelect').on('change', function() {
                updateSelectedOffers();
            });

            // Form submission
            $('#productForm').on('submit', function(e) {
                // Collect all dynamic data
                collectFormData();

                // Validate all steps
                for (let i = 1; i <= 4; i++) {
                    const step = $('.step-' + i);
                    if (step.is(':visible') && !validateStep(step)) {
                        e.preventDefault();
                        return;
                    }
                }

                // Show loading
                Swal.fire({
                    title: 'جاري حفظ المنتج...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        });

        // Color Selection
        function toggleColorSelection(element) {
            const colorId = $(element).data('id');
            const colorName = $(element).data('name');
            const colorHex = $(element).data('hex');

            if (selectedColors.has(colorId)) {
                // Remove color
                selectedColors.delete(colorId);
                $(element).removeClass('selected');
                $(`#colorItem_${colorId}`).remove();
            } else {
                // Add color with additional price
                Swal.fire({
                    title: 'إضافة سعر إضافي للون',
                    input: 'number',
                    inputLabel: `سعر إضافي للون ${colorName}`,
                    inputPlaceholder: '0.00',
                    showCancelButton: true,
                    confirmButtonText: 'إضافة',
                    cancelButtonText: 'إلغاء',
                    inputValidator: (value) => {
                        if (value === '' || parseFloat(value) < 0) {
                            return 'يرجى إدخال سعر صحيح';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const additionalPrice = parseFloat(result.value) || 0;
                        selectedColors.set(colorId, {
                            id: colorId,
                            name: colorName,
                            hex: colorHex,
                            price: additionalPrice
                        });

                        $(element).addClass('selected');

                        // Add to selected colors container
                        const template = document.getElementById('colorItemTemplate').innerHTML;
                        const html = template
                            .replace(/{id}/g, colorId)
                            .replace(/{name}/g, colorName)
                            .replace(/{hex}/g, colorHex)
                            .replace(/{price}/g, additionalPrice);

                        $('#selectedColorsContainer').append(html);
                    }
                });
            }
        }

        // Material Field Management
        function addMaterialField() {
            const template = document.getElementById('materialFieldTemplate').content.cloneNode(true);
            const newField = $(template);
            newField.find('.material-select').select2();
            $('#materialsContainer').append(newField);
            materialCounter++;
        }

        function removeMaterialField(button) {
            $(button).closest('.col-md-12').remove();
        }

        // Update Selected Items
        function updateSelectedPrintingMethods() {
            const selected = $('#printingMethodsSelect').select2('data');
            const container = $('#selectedPrintingMethods');
            container.empty();
            selectedPrintingMethods.clear();

            selected.forEach(item => {
                const id = item.id;
                const name = item.text.split(' - ')[0];
                const price = $(item.element).data('price') || 0;

                selectedPrintingMethods.set(id, {
                    id,
                    name,
                    price
                });

                const template = document.getElementById('printingMethodItemTemplate').innerHTML;
                const html = template
                    .replace(/{id}/g, id)
                    .replace(/{name}/g, name)
                    .replace(/{price}/g, price);

                container.append(html);
            });
        }

        function updateSelectedPrintLocations() {
            const selected = $('#printLocationsSelect').select2('data');
            const container = $('#selectedPrintLocations');
            container.empty();
            selectedPrintLocations.clear();

            selected.forEach(item => {
                const id = item.id;
                const fullText = item.text;
                const name = fullText.split(' - ')[0];
                const price = $(item.element).data('price') || 0;

                selectedPrintLocations.set(id, {
                    id,
                    name,
                    price
                });

                const template = document.getElementById('printLocationItemTemplate').innerHTML;
                const html = template
                    .replace(/{id}/g, id)
                    .replace(/{name}/g, name)
                    .replace(/{price}/g, price);

                container.append(html);
            });
        }

        function updateSelectedOffers() {
            const selected = $('#offersSelect').select2('data');
            const container = $('#selectedOffers');
            container.empty();
            selectedOffers.clear();

            selected.forEach(item => {
                const id = item.id;
                const name = item.text;

                selectedOffers.set(id, {
                    id,
                    name
                });

                const template = document.getElementById('offerItemTemplate').innerHTML;
                const html = template
                    .replace(/{id}/g, id)
                    .replace(/{name}/g, name);

                container.append(html);
            });
        }

        // Remove Selected Item
        function removeSelectedItem(button, type) {
            const item = $(button).closest('.selected-item');
            const id = item.find('input[type="hidden"]').val();

            switch (type) {
                case 'color':
                    selectedColors.delete(parseInt(id));
                    $(`.color-picker-item[data-id="${id}"]`).removeClass('selected');
                    break;
                case 'printing_method':
                    selectedPrintingMethods.delete(parseInt(id));
                    $('#printingMethodsSelect').val($('#printingMethodsSelect').val().filter(v => v != id)).trigger(
                        'change');
                    break;
                case 'print_location':
                    selectedPrintLocations.delete(parseInt(id));
                    $('#printLocationsSelect').val($('#printLocationsSelect').val().filter(v => v != id)).trigger('change');
                    break;
                case 'offer':
                    selectedOffers.delete(parseInt(id));
                    $('#offersSelect').val($('#offersSelect').val().filter(v => v != id)).trigger('change');
                    break;
            }

            item.remove();
        }

        // Quick Add Modal
        function openQuickAddModal(type) {
            currentQuickAddType = type;
            const modal = $('#quickAddModal');
            const formContent = $('#quickAddFormContent');
            formContent.empty();

            let title = '';
            let formHtml = '';

            switch (type) {
                case 'color':
                    title = 'إضافة لون جديد';
                    formHtml = `
                    <div class="mb-3">
                        <label class="form-label">اسم اللون</label>
                        <input type="text" class="form-control" id="quick_color_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الكود اللوني (Hex)</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="quick_color_hex" placeholder="#000000" required>
                            <input type="color" class="form-control form-control-color" id="quick_color_picker" 
                                   onchange="document.getElementById('quick_color_hex').value = this.value">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">صورة اللون (اختياري)</label>
                        <input type="file" class="form-control" id="quick_color_image" accept="image/*">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">سعر إضافي</label>
                        <input type="number" class="form-control" id="quick_color_price" step="0.01" min="0" value="0">
                    </div>
                `;
                    break;

                case 'material':
                    title = 'إضافة مادة جديدة';
                    formHtml = `
                    <div class="mb-3">
                        <label class="form-label">اسم المادة</label>
                        <input type="text" class="form-control" id="quick_material_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" id="quick_material_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">سعر إضافي للوحدة</label>
                        <input type="number" class="form-control" id="quick_material_price" step="0.01" min="0" value="0">
                    </div>
                `;
                    break;

                case 'printing_method':
                    title = 'إضافة طريقة طباعة جديدة';
                    formHtml = `
                    <div class="mb-3">
                        <label class="form-label">اسم طريقة الطباعة</label>
                        <input type="text" class="form-control" id="quick_printing_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" id="quick_printing_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">السعر الأساسي</label>
                        <input type="number" class="form-control" id="quick_printing_price" step="0.01" min="0" required>
                    </div>
                `;
                    break;

                case 'print_location':
                    title = 'إضافة مكان طباعة جديد';
                    formHtml = `
                    <div class="mb-3">
                        <label class="form-label">اسم مكان الطباعة</label>
                        <input type="text" class="form-control" id="quick_location_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">النوع</label>
                        <select class="form-select" id="quick_location_type">
                            <option value="front">أمامي</option>
                            <option value="back">خلفي</option>
                            <option value="sleeve">كم</option>
                            <option value="pocket">جيب</option>
                            <option value="other">أخرى</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">سعر إضافي</label>
                        <input type="number" class="form-control" id="quick_location_price" step="0.01" min="0" required>
                    </div>
                `;
                    break;

                case 'offer':
                    title = 'إضافة عرض جديد';
                    formHtml = `
                    <div class="mb-3">
                        <label class="form-label">اسم العرض</label>
                        <input type="text" class="form-control" id="quick_offer_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">صورة العرض (اختياري)</label>
                        <input type="file" class="form-control" id="quick_offer_image" accept="image/*">
                    </div>
                `;
                    break;

                case 'category':
                    title = 'إضافة قسم جديد';
                    formHtml = `
                    <div class="mb-3">
                        <label class="form-label">اسم القسم</label>
                        <input type="text" class="form-control" id="quick_category_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">الوصف</label>
                        <textarea class="form-control" id="quick_category_description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">القسم الرئيسي</label>
                        <select class="form-select" id="quick_category_parent">
                            <option value="">قسم رئيسي</option>
                            @foreach ($categories as $category)
                                @if ($category->isParent())
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                `;
                    break;
            }

            $('#quickAddModalTitle').text(title);
            formContent.html(formHtml);
            modal.modal('show');
        }

        function saveQuickAdd() {
            const modal = $('#quickAddModal');
            const type = currentQuickAddType;

            // Validate
            let isValid = true;
            modal.find('input[required], select[required]').each(function() {
                if (!$(this).val().trim()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire({
                    icon: 'error',
                    title: 'بيانات ناقصة',
                    text: 'يرجى ملء جميع الحقول المطلوبة',
                    confirmButtonText: 'حسناً'
                });
                return;
            }

            // Prepare data
            const data = new FormData();
            data.append('_token', '{{ csrf_token() }}');
            data.append('type', type);

            switch (type) {
                case 'color':
                    data.append('name', $('#quick_color_name').val());
                    data.append('hex_code', $('#quick_color_hex').val());
                    data.append('additional_price', $('#quick_color_price').val());
                    if ($('#quick_color_image')[0].files[0]) {
                        data.append('image', $('#quick_color_image')[0].files[0]);
                    }
                    break;

                case 'material':
                    data.append('name', $('#quick_material_name').val());
                    data.append('description', $('#quick_material_description').val());
                    data.append('additional_price', $('#quick_material_price').val());
                    break;

                case 'printing_method':
                    data.append('name', $('#quick_printing_name').val());
                    data.append('description', $('#quick_printing_description').val());
                    data.append('base_price', $('#quick_printing_price').val());
                    break;

                case 'print_location':
                    data.append('name', $('#quick_location_name').val());
                    data.append('type', $('#quick_location_type').val());
                    data.append('additional_price', $('#quick_location_price').val());
                    break;

                case 'offer':
                    data.append('name', $('#quick_offer_name').val());
                    if ($('#quick_offer_image')[0].files[0]) {
                        data.append('image', $('#quick_offer_image')[0].files[0]);
                    }
                    break;

                case 'category':
                    data.append('name', $('#quick_category_name').val());
                    data.append('description', $('#quick_category_description').val());
                    data.append('parent_id', $('#quick_category_parent').val());
                    break;
            }

            // Send AJAX request
            $.ajax({
                url: '{{ route('admin.products.quick-add') }}',
                type: 'POST',
                data: data,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    Swal.fire({
                        title: 'جاري الإضافة...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'تمت الإضافة!',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Update the appropriate select
                        updateSelectAfterQuickAdd(type, response.data);

                        modal.modal('hide');
                        modal.find('input, textarea, select').val('');
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ!',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: xhr.responseJSON?.message || 'حدث خطأ أثناء الإضافة'
                    });
                }
            });
        }

        function updateSelectAfterQuickAdd(type, data) {
            switch (type) {
                case 'color':
                    // Add to color picker grid
                    const colorItem = `
                    <div class="color-picker-item" 
                         style="background-color: ${data.hex_code};"
                         data-id="${data.id}"
                         data-name="${data.name}"
                         data-hex="${data.hex_code}"
                         onclick="toggleColorSelection(this)">
                    </div>
                `;
                    $('#existingColorsGrid').append(colorItem);
                    break;

                case 'material':
                    // Add to materials select
                    const materialOption = new Option(data.name, data.id, false, false);
                    $('#materialsSelect').append(materialOption).trigger('change');
                    break;

                case 'printing_method':
                    // Add to printing methods select
                    const printingOption = new Option(`${data.name} - ${data.base_price} ج.م`, data.id, false, false);
                    $(printingOption).data('price', data.base_price);
                    $('#printingMethodsSelect').append(printingOption).trigger('change');
                    break;

                case 'print_location':
                    // Add to print locations select
                    const locationOption = new Option(`${data.name} (${data.type}) - ${data.additional_price} ج.م`, data.id,
                        false, false);
                    $(locationOption).data('type', data.type);
                    $(locationOption).data('price', data.additional_price);
                    $('#printLocationsSelect').append(locationOption).trigger('change');
                    break;

                case 'offer':
                    // Add to offers select
                    const offerOption = new Option(data.name, data.id, false, false);
                    $('#offersSelect').append(offerOption).trigger('change');
                    break;

                case 'category':
                    // Add to category select
                    const categoryOption = new Option(data.name, data.id, false, false);
                    $('#category_id').append(categoryOption).trigger('change');
                    break;
            }
        }

        // Collect Form Data for Submission
        function collectFormData() {
            const container = $('#hiddenInputsContainer');
            container.empty();

            // Collect colors with prices
            selectedColors.forEach((color, id) => {
                container.append(`<input type="hidden" name="colors[]" value="${id}">`);
                container.append(`<input type="hidden" name="color_prices[${id}]" value="${color.price}">`);
            });

            // Collect other selected items
            selectedPrintingMethods.forEach((method, id) => {
                container.append(`<input type="hidden" name="printing_methods[]" value="${id}">`);
            });

            selectedPrintLocations.forEach((location, id) => {
                container.append(`<input type="hidden" name="print_locations[]" value="${id}">`);
            });

            selectedOffers.forEach((offer, id) => {
                container.append(`<input type="hidden" name="offers[]" value="${id}">`);
            });
        }

        // Update Summary
        function updateSummary() {
            $('#summary_name').text($('#name').val());
            $('#summary_price').text($('#price').val());
            $('#summary_stock').text($('#stock').val());

            // Colors
            const colors = Array.from(selectedColors.values()).map(c => c.name);
            $('#summary_colors').text(colors.join(', ') || 'لا يوجد');

            // Materials
            const materialNames = [];
            $('#materialsContainer .material-select').each(function() {
                const selectedOption = $(this).find('option:selected');
                if (selectedOption.val()) {
                    materialNames.push(selectedOption.text());
                }
            });
            $('#summary_materials').text(materialNames.join(', ') || 'لا يوجد');

            // Printing options
            const printingOptions = [];
            selectedPrintingMethods.forEach(method => {
                printingOptions.push(method.name);
            });
            selectedPrintLocations.forEach(location => {
                printingOptions.push(location.name);
            });
            $('#summary_printing').text(printingOptions.join(', ') || 'لا يوجد');
        }

        // Save as Draft
        function saveAsDraft() {
            $('#status_id').val('3'); // Draft status
            collectFormData();
            $('#productForm').submit();
        }

        // Image Preview Functions
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
            $('#main_image').val('');
            $('#mainImagePreview').hide();
        }

        function previewAdditionalImages(input) {
            const previewContainer = $('#additionalImagesPreview');
            previewContainer.empty();

            if (input.files) {
                Array.from(input.files).forEach((file, index) => {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = `
                        <div class="col-md-3">
                            <div class="image-preview">
                                <img src="${e.target.result}" alt="صورة إضافية">
                                <div class="image-overlay">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeAdditionalImage(this)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
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
            $(button).closest('.col-md-3').remove();
        }

        // Auto-calculate final price
        $('#price, #discount_value, #discount_type').on('input', function() {
            calculateFinalPrice();
        });

        function calculateFinalPrice() {
            const price = parseFloat($('#price').val()) || 0;
            const discountValue = parseFloat($('#discount_value').val()) || 0;
            const discountType = $('#discount_type').val();

            let finalPrice = price;

            if ($('#has_discount').is(':checked')) {
                if (discountType === 'percentage') {
                    finalPrice = price - (price * discountValue / 100);
                } else {
                    finalPrice = price - discountValue;
                }
            }

            $('#finalPricePreview').text(finalPrice.toFixed(2) + ' ج.م');
        }
    </script>
@endsection
