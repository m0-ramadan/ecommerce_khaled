@extends('Admin.layout.master')

@section('title', 'إضافة بانر جديد')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    body {
        font-family: "Cairo", sans-serif !important;
    }

    .form-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        padding: 30px;
        margin-bottom: 30px;
    }

    .form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        border-radius: 15px 15px 0 0;
        margin-bottom: 30px;
    }

    .form-section {
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        background: #f8f9fa;
    }

    .form-section h5 {
        color: #696cff;
        border-bottom: 2px solid #696cff;
        padding-bottom: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .image-upload-box {
        border: 2px dashed #dee2e6;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .image-upload-box:hover {
        border-color: #696cff;
        background: #f0f2ff;
    }

    .image-upload-box i {
        font-size: 48px;
        color: #6c757d;
        margin-bottom: 15px;
    }

    .image-upload-box input {
        display: none;
    }

    .image-preview {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 10px;
        margin-top: 15px;
        display: none;
    }

    .mobile-image-preview {
        width: 150px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 10px;
    }

    .badge-custom {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .badge-slider {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .badge-grid {
        background: #e7f5ff;
        color: #0c63e4;
    }

    .badge-static {
        background: #d4edda;
        color: #155724;
    }

    .badge-category {
        background: #fff3cd;
        color: #856404;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .badge-main {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-cat {
        background: #d1e7dd;
        color: #0f5132;
    }

    .type-card {
        border: 2px solid #e9ecef;
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        background: white;
    }

    .type-card:hover {
        border-color: #696cff;
        transform: translateY(-5px);
    }

    .type-card.active {
        border-color: #696cff;
        background: #f0f2ff;
        box-shadow: 0 5px 15px rgba(105, 108, 255, 0.2);
    }

    .type-icon {
        font-size: 32px;
        color: #696cff;
        margin-bottom: 15px;
    }

    .type-desc {
        color: #6c757d;
        font-size: 12px;
        margin-top: 10px;
    }

    .toggle-switch {
        position: relative;
        width: 50px;
        height: 26px;
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
        height: 18px;
        width: 18px;
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
        transform: translateX(24px);
    }

    .date-input-group {
        position: relative;
    }

    .date-input-group .form-control {
        padding-left: 40px;
    }

    .date-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }

    .preview-container {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-top: 30px;
    }

    .preview-item {
        width: 100%;
        height: 150px;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .preview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .items-container {
        margin-top: 30px;
    }

    .item-card {
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        background: white;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        border-color: #696cff;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
    }

    .item-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
    }

    .item-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
    }

    .empty-items {
        text-align: center;
        padding: 40px 20px;
        color: #6c757d;
    }

    .empty-items i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #dee2e6;
    }

    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        min-height: 38px;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #696cff;
        color: white;
        border: none;
        border-radius: 15px;
        padding: 2px 10px;
    }

    .grid-settings {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        border: 2px dashed #dee2e6;
    }

    .slider-settings {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        border: 2px dashed #dee2e6;
    }

    .section-title {
        color: #696cff;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #696cff;
    }

    .help-text {
        color: #6c757d;
        font-size: 12px;
        margin-top: 5px;
    }

    .required-field::after {
        content: " *";
        color: #dc3545;
    }

    .preview-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 10px;
        margin-bottom: 15px;
    }

    .preview-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 10px;
        font-size: 12px;
        text-align: center;
    }

    .mobile-preview {
        width: 300px;
        height: 100px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        margin-top: 10px;
        overflow: hidden;
        position: relative;
    }

    .desktop-preview {
        width: 100%;
        height: 200px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        margin-top: 10px;
        overflow: hidden;
        position: relative;
    }

    .form-step {
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        padding: 10px;
        background: white;
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }

    .step-number {
        width: 40px;
        height: 40px;
        background: #696cff;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
    }

    .step-content h6 {
        margin-bottom: 5px;
        color: #495057;
    }

    .step-content p {
        margin: 0;
        color: #6c757d;
        font-size: 13px;
    }

    .form-alert {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .alert-info {
        background: #e7f5ff;
        border: 1px solid #0c63e4;
        color: #0c63e4;
    }

    .alert-warning {
        background: #fff3cd;
        border: 1px solid #856404;
        color: #856404;
    }

    @media (max-width: 768px) {
        .type-card {
            margin-bottom: 15px;
        }
        
        .item-card {
            flex-direction: column;
        }
        
        .item-image {
            margin-bottom: 15px;
        }
        
        .item-actions {
            justify-content: center;
        }
        
        .form-step {
            flex-direction: column;
            text-align: center;
        }
        
        .step-number {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }

    .category-options {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .category-option {
        flex: 1;
        text-align: center;
        padding: 15px;
        border: 2px solid #e9ecef;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .category-option:hover {
        border-color: #696cff;
        background: #f8f9fa;
    }

    .category-option.active {
        border-color: #696cff;
        background: #f0f2ff;
    }

    .category-option i {
        font-size: 24px;
        color: #696cff;
        margin-bottom: 10px;
    }

    .category-select {
        display: none;
    }

    .category-select.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
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
                <a href="{{ route('admin.banners.index') }}">البنرات</a>
            </li>
            <li class="breadcrumb-item active">إضافة بانر جديد</li>
        </ol>
    </nav>

    <!-- خطوات التنفيذ -->
    <div class="form-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>المعلومات الأساسية</h6>
            <p>أدخل عنوان البانر وحدد القسم المناسب</p>
        </div>
    </div>
    
    <div class="form-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>اختيار النوع</h6>
            <p>اختر نوع البانر المناسب (سلايدر، شبكة، ثابت، أقسام)</p>
        </div>
    </div>
    
    <div class="form-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>الإعدادات</h6>
            <p>اضبط الإعدادات وفقاً لنوع البانر المختار</p>
        </div>
    </div>
    
    <div class="form-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>العناصر</h6>
            <p>أضف الصور والعناصر المطلوبة للبانر</p>
        </div>
    </div>

    <!-- تنبيهات مهمة -->
    <div class="form-alert alert-info">
        <i class="fas fa-info-circle fa-2x"></i>
        <div>
            <h6 class="mb-1">معلومات مهمة</h6>
            <p class="mb-0">• تأكد من اختيار القسم المناسب للبانر (الرئيسية أو قسم معين)</p>
            <p class="mb-0">• اختر النوع المناسب بناءً على الغرض من البانر</p>
            <p class="mb-0">• أضف الصور بدقة مناسبة للعرض على جميع الشاشات</p>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="form-header">
                    <h4 class="mb-0"><i class="fas fa-plus-circle me-2"></i>إضافة بانر جديد</h4>
                    <small class="opacity-75">املأ النموذج لإضافة بانر جديد إلى النظام</small>
                </div>

                <form action="{{ route('admin.banners.store') }}" method="POST" id="bannerForm">
                    @csrf

                    <!-- القسم والموقع -->
                    <div class="form-section">
                        <h5><i class="fas fa-map-marker-alt"></i>القسم والموقع</h5>
                        
                        <div class="category-options">
                            <div class="category-option active" onclick="selectCategory('main')">
                                <i class="fas fa-home"></i>
                                <h6>الرئيسية</h6>
                                <p class="type-desc">عرض البانر في الصفحة الرئيسية</p>
                            </div>
                            <div class="category-option" onclick="selectCategory('specific')">
                                <i class="fas fa-tag"></i>
                                <h6>قسم محدد</h6>
                                <p class="type-desc">عرض البانر في قسم معين</p>
                            </div>
                        </div>
                        
                        <input type="hidden" name="category_type" id="category_type" value="main">
                        
                        <div class="category-select" id="specificCategorySelect">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="category_id" class="form-label required-field">اختر القسم</label>
                                    <select class="form-control select2" id="category_id" name="category_id">
                                        <option value="">-- اختر قسم من القائمة --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-text">سيتم عرض البانر في صفحة هذا القسم فقط</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- المعلومات الأساسية -->
                    <div class="form-section">
                        <h5><i class="fas fa-info-circle"></i>المعلومات الأساسية</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label required-field">عنوان البانر</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="{{ old('title') }}" required
                                       placeholder="أدخل عنوان واضح للبانر">
                                @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="help-text">العنوان سيساعدك في التعرف على البانر لاحقاً</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="section_order" class="form-label required-field">ترتيب العرض</label>
                                <input type="number" class="form-control" id="section_order" name="section_order" 
                                       value="{{ old('section_order', 1) }}" required min="1">
                                @error('section_order')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="help-text">الأرقام الأقل تظهر أولاً</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label class="form-label required-field">حالة البانر</label>
                                <div class="d-flex align-items-center mt-2">
                                    <label class="toggle-switch me-3">
                                        <input type="checkbox" name="is_active" value="1" checked>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="badge-custom badge-active">نشط</span>
                                </div>
                                <div class="help-text">يمكنك تعطيل البانر لاحقاً</div>
                            </div>
                        </div>
                    </div>

                    <!-- نوع البانر -->
                    <div class="form-section">
                        <h5><i class="fas fa-sliders-h"></i>اختيار نوع البانر</h5>
                        
                        <div class="row">
                            @foreach($bannerTypes as $type)
                                <div class="col-md-3 mb-3">
                                    <div class="type-card" onclick="selectType({{ $type->id }}, '{{ $type->name }}')">
                                        <div class="type-icon">
                                            @if($type->name == 'slider')
                                                <i class="fas fa-sliders-h"></i>
                                            @elseif($type->name == 'grid')
                                                <i class="fas fa-th-large"></i>
                                            @elseif($type->name == 'static')
                                                <i class="fas fa-image"></i>
                                            @elseif($type->name == 'category_slider')
                                                <i class="fas fa-tags"></i>
                                            @endif
                                        </div>
                                        <h6 class="mb-2">
                                            @if($type->name == 'slider') سلايدر
                                            @elseif($type->name == 'grid') شبكة
                                            @elseif($type->name == 'static') ثابت
                                            @elseif($type->name == 'category_slider') أقسام
                                            @endif
                                        </h6>
                                        <p class="type-desc">{{ $type->description }}</p>
                                    </div>
                                    <input type="radio" name="banner_type_id" value="{{ $type->id }}" 
                                           id="type_{{ $type->id }}" 
                                           {{ old('banner_type_id') == $type->id ? 'checked' : '' }}
                                           style="display: none;">
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" id="selected_type" value="{{ old('banner_type_id', 1) }}">
                    </div>

                    <!-- إعدادات الشبكة (تظهر فقط للنوع grid) -->
                    <div class="grid-settings" id="gridSettings" style="display: none;">
                        <h6 class="section-title"><i class="fas fa-cog me-2"></i>إعدادات الشبكة</h6>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="grid_type" class="form-label">نوع الشبكة</label>
                                <select class="form-control" id="grid_type" name="grid_type">
                                    <option value="responsive">متجاوب</option>
                                    <option value="fixed">ثابت</option>
                                </select>
                                <div class="help-text">المتجاوب يناسب جميع الشاشات</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="desktop_columns" class="form-label">الأعمدة (كمبيوتر)</label>
                                <input type="number" class="form-control" id="desktop_columns" name="desktop_columns" 
                                       value="3" min="1" max="6">
                                <div class="help-text">عدد الأعمدة في شاشات الكمبيوتر</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="tablet_columns" class="form-label">الأعمدة (تابلت)</label>
                                <input type="number" class="form-control" id="tablet_columns" name="tablet_columns" 
                                       value="2" min="1" max="4">
                                <div class="help-text">عدد الأعمدة في شاشات التابلت</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <label for="mobile_columns" class="form-label">الأعمدة (موبايل)</label>
                                <input type="number" class="form-control" id="mobile_columns" name="mobile_columns" 
                                       value="1" min="1" max="2">
                                <div class="help-text">عدد الأعمدة في شاشات الموبايل</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="row_gap" class="form-label">المسافة بين الصفوف</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="row_gap" name="row_gap" 
                                           value="20" min="0" max="100">
                                    <span class="input-group-text">بكسل</span>
                                </div>
                                <div class="help-text">المسافة الرأسية بين العناصر</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="column_gap" class="form-label">المسافة بين الأعمدة</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="column_gap" name="column_gap" 
                                           value="20" min="0" max="100">
                                    <span class="input-group-text">بكسل</span>
                                </div>
                                <div class="help-text">المسافة الأفقية بين العناصر</div>
                            </div>
                        </div>
                        
                        <!-- معاينة الشبكة -->
                        <div class="preview-wrapper mt-4">
                            <h6 class="section-title"><i class="fas fa-eye me-2"></i>معاينة الشبكة</h6>
                            <div id="gridPreview" class="row g-3">
                                <!-- سيتم توليد معاينة ديناميكية -->
                            </div>
                        </div>
                    </div>

                    <!-- إعدادات السلايدر (تظهر فقط للنوع slider) -->
                    <div class="slider-settings" id="sliderSettings">
                        <h6 class="section-title"><i class="fas fa-cog me-2"></i>إعدادات السلايدر</h6>
                        
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="autoplay" name="autoplay" checked>
                                    <label class="form-check-label" for="autoplay">التشغيل التلقائي</label>
                                </div>
                                <div class="help-text">التقدم التلقائي بين الشرائح</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="arrows" name="arrows" checked>
                                    <label class="form-check-label" for="arrows">أزرار التنقل</label>
                                </div>
                                <div class="help-text">عرض أزرار التالي والسابق</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="dots" name="dots" checked>
                                    <label class="form-check-label" for="dots">النقاط</label>
                                </div>
                                <div class="help-text">عرض نقاط التنقل أسفل السلايدر</div>
                            </div>
                            
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="infinite" name="infinite" checked>
                                    <label class="form-check-label" for="infinite">لانهائي</label>
                                </div>
                                <div class="help-text">التنقل اللانهائي بين الشرائح</div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="autoplay_speed" class="form-label">سرعة التشغيل</label>
                                <div class="input-group">
                                    <input type="number" class="form-control" id="autoplay_speed" name="autoplay_speed" 
                                           value="3000" min="1000" max="10000" step="500">
                                    <span class="input-group-text">ملي ثانية</span>
                                </div>
                                <div class="help-text">المدة بين الشرائح (3000 = 3 ثواني)</div>
                            </div>
                        </div>
                    </div>

                    <!-- الفترة الزمنية -->
                    <div class="form-section">
                        <h5><i class="fas fa-calendar-alt"></i>الفترة الزمنية</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">تاريخ البدء</label>
                                <div class="date-input-group">
                                    <i class="fas fa-calendar date-icon"></i>
                                    <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                                           value="{{ old('start_date') }}">
                                </div>
                                @error('start_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="help-text">تاريخ بدء عرض البانر (اختياري)</div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">تاريخ الانتهاء</label>
                                <div class="date-input-group">
                                    <i class="fas fa-calendar date-icon"></i>
                                    <input type="datetime-local" class="form-control" id="end_date" name="end_date" 
                                           value="{{ old('end_date') }}">
                                </div>
                                @error('end_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="help-text">تاريخ إيقاف عرض البانر (اختياري)</div>
                            </div>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="permanent" name="permanent" checked>
                            <label class="form-check-label" for="permanent">
                                دائم (بدون فترة محددة)
                            </label>
                        </div>
                    </div>

                    <!-- العناصر (سيتم إضافتها بعد إنشاء البانر) -->
                    <div class="form-section">
                        <h5><i class="fas fa-layer-group"></i>عناصر البانر</h5>
                        
                        <div class="form-alert alert-warning">
                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                            <div>
                                <h6 class="mb-1">ملاحظة مهمة</h6>
                                <p class="mb-0">يمكنك إضافة عناصر البانر (الصور والروابط) بعد إنشاء البانر مباشرة</p>
                                <p class="mb-0">ستظهر صفحة خاصة لإدارة العناصر مباشرة بعد الحفظ</p>
                            </div>
                        </div>
                        
                        <div class="empty-items">
                            <i class="fas fa-plus-circle"></i>
                            <p>سيتم إضافة العناصر بعد إنشاء البانر</p>
                            <p class="help-text">يمكنك إضافة صور متعددة، روابط، وأكواد خصم</p>
                        </div>
                    </div>

                    <!-- أزرار التحكم -->
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <div>
                            <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>إلغاء
                            </a>
                        </div>
                        
                        <div>
                            <button type="button" class="btn btn-outline-primary me-2" onclick="previewBanner()">
                                <i class="fas fa-eye me-2"></i>معاينة
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>حفظ وإنشاء البانر
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Preview -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">معاينة البانر</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="preview-container">
                    <div id="previewContent">
                        <!-- سيتم عرض المعاينة هنا -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            placeholder: 'اختر من القائمة',
            allowClear: true,
            language: {
                noResults: function() {
                    return "لا توجد نتائج";
                }
            }
        });

        // Set default type (Slider)
        selectType(1, 'slider');

        // Handle category selection
        selectCategory('main');

        // Handle permanent checkbox
        $('#permanent').on('change', function() {
            if ($(this).is(':checked')) {
                $('#start_date, #end_date').prop('disabled', true).val('');
            } else {
                $('#start_date, #end_date').prop('disabled', false);
            }
        });

        // Handle form submission
        $('#bannerForm').on('submit', function(e) {
            e.preventDefault();
            
            // Validate form
            if (!validateForm()) {
                return;
            }

            // Show loading
            Swal.fire({
                title: 'جاري الإنشاء...',
                text: 'يرجى الانتظار',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Submit form
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم!',
                        text: 'تم إنشاء البانر بنجاح',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        // Redirect to banner items management
                        window.location.href = "{{ route('admin.banners.index') }}";
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'حدث خطأ أثناء الحفظ';
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        const errors = xhr.responseJSON.errors;
                        errorMessage = Object.values(errors).flat().join('<br>');
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        html: errorMessage,
                        confirmButtonText: 'حسناً'
                    });
                }
            });
        });

        // Update grid preview
        $('#desktop_columns, #tablet_columns, #mobile_columns').on('input', updateGridPreview);
        updateGridPreview();
    });

    function selectCategory(type) {
        $('.category-option').removeClass('active');
        $(`.category-option:contains(${type === 'main' ? 'الرئيسية' : 'قسم محدد'})`).addClass('active');
        $('#category_type').val(type);
        
        if (type === 'specific') {
            $('#specificCategorySelect').addClass('show');
            $('#category_id').prop('required', true);
        } else {
            $('#specificCategorySelect').removeClass('show');
            $('#category_id').prop('required', false).val('');
        }
    }

    function selectType(typeId, typeName) {
        // Remove active class from all type cards
        $('.type-card').removeClass('active');
        
        // Add active class to selected type
        $(`#type_${typeId}`).prop('checked', true).closest('.col-md-3').find('.type-card').addClass('active');
        
        // Update hidden input
        $('#selected_type').val(typeId);
        
        // Show/Hide settings based on type
        toggleSettings(typeId, typeName);
    }

    function toggleSettings(typeId, typeName) {
        // Hide all settings first
        $('#gridSettings, #sliderSettings').hide();
        
        // Show relevant settings
        if (typeName === 'grid') {
            $('#gridSettings').show();
            updateGridPreview();
        } else if (typeName === 'slider') {
            $('#sliderSettings').show();
        }
        
        // Update form steps
        updateFormSteps(typeName);
    }

    function updateFormSteps(typeName) {
        const steps = [
            { number: 1, title: 'المعلومات الأساسية', desc: 'أدخل عنوان البانر وحدد القسم' },
            { number: 2, title: 'اختيار النوع', desc: `نوع البانر: ${getTypeName(typeName)}` },
            { number: 3, title: 'الإعدادات', desc: `إعدادات ${getTypeName(typeName)}` },
            { number: 4, title: 'العناصر', desc: 'أضف الصور والروابط للبانر' }
        ];
        
        steps.forEach((step, index) => {
            $(`.form-step:nth-child(${index + 1}) .step-content h6`).text(step.title);
            $(`.form-step:nth-child(${index + 1}) .step-content p`).text(step.desc);
        });
    }

    function getTypeName(typeName) {
        const types = {
            'slider': 'السلايدر',
            'grid': 'الشبكة',
            'static': 'الثابت',
            'category_slider': 'الأقسام'
        };
        return types[typeName] || typeName;
    }

    function updateGridPreview() {
        const desktopColumns = parseInt($('#desktop_columns').val()) || 3;
        const tabletColumns = parseInt($('#tablet_columns').val()) || 2;
        const mobileColumns = parseInt($('#mobile_columns').val()) || 1;
        const rowGap = parseInt($('#row_gap').val()) || 20;
        const colGap = parseInt($('#column_gap').val()) || 20;
        
        let previewHtml = `
            <div class="row g-${colGap}">
                <div class="col-12 mb-2">
                    <small class="text-muted">معاينة الكمبيوتر (${desktopColumns} أعمدة)</small>
                    <div class="desktop-preview">
                        <div class="row g-${colGap} m-1">
        `;
        
        // Desktop preview
        for (let i = 0; i < desktopColumns; i++) {
            previewHtml += `
                <div class="col" style="height: ${200 - rowGap}px; background: #696cff; opacity: ${0.7 - (i * 0.1)}; border-radius: 5px;"></div>
            `;
        }
        
        previewHtml += `
                        </div>
                        <div class="preview-overlay">
                            ${desktopColumns} أعمدة | مسافة أفقية: ${colGap}px | مسافة رأسية: ${rowGap}px
                        </div>
                    </div>
                </div>
                
                <div class="col-12 mb-2">
                    <small class="text-muted">معاينة التابلت (${tabletColumns} أعمدة)</small>
                    <div class="desktop-preview" style="height: 150px;">
                        <div class="row g-${colGap} m-1">
        `;
        
        // Tablet preview
        for (let i = 0; i < tabletColumns; i++) {
            previewHtml += `
                <div class="col" style="height: ${150 - rowGap}px; background: #0c63e4; opacity: ${0.7 - (i * 0.1)}; border-radius: 5px;"></div>
            `;
        }
        
        previewHtml += `
                        </div>
                        <div class="preview-overlay">
                            ${tabletColumns} أعمدة | مسافة أفقية: ${colGap}px | مسافة رأسية: ${rowGap}px
                        </div>
                    </div>
                </div>
                
                <div class="col-12">
                    <small class="text-muted">معاينة الموبايل (${mobileColumns} أعمدة)</small>
                    <div class="mobile-preview">
                        <div class="row g-${colGap} m-1">
        `;
        
        // Mobile preview
        for (let i = 0; i < mobileColumns; i++) {
            previewHtml += `
                <div class="col" style="height: ${100 - rowGap}px; background: #28a745; opacity: ${0.7 - (i * 0.1)}; border-radius: 5px;"></div>
            `;
        }
        
        previewHtml += `
                        </div>
                        <div class="preview-overlay" style="font-size: 10px;">
                            ${mobileColumns} أعمدة | مسافة أفقية: ${colGap}px | مسافة رأسية: ${rowGap}px
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        $('#gridPreview').html(previewHtml);
    }

    function validateForm() {
        const title = $('#title').val().trim();
        const categoryType = $('#category_type').val();
        const categoryId = $('#category_id').val();
        
        // Check title
        if (!title) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يرجى إدخال عنوان البانر',
                confirmButtonText: 'حسناً'
            });
            $('#title').focus();
            return false;
        }
        
        // Check category
        if (categoryType === 'specific' && !categoryId) {
            Swal.fire({
                icon: 'error',
                title: 'خطأ',
                text: 'يرجى اختيار قسم للبانر',
                confirmButtonText: 'حسناً'
            });
            $('#category_id').select2('open');
            return false;
        }
        
        return true;
    }

    function previewBanner() {
        const title = $('#title').val();
        const categoryType = $('#category_type').val();
        const categoryId = $('#category_id').val();
        const typeName = $('.type-card.active h6').text();
        
        let previewHtml = `
            <div class="preview-wrapper">
                <h6 class="mb-3">معاينة البانر</h6>
                
                <div class="info-item mb-3">
                    <span class="info-label">العنوان:</span>
                    <span class="info-value">${title || 'بدون عنوان'}</span>
                </div>
                
                <div class="info-item mb-3">
                    <span class="info-label">الموقع:</span>
                    <span class="info-value">
                        ${categoryType === 'main' ? 'الرئيسية' : 'قسم: ' + $('#category_id option:selected').text()}
                    </span>
                </div>
                
                <div class="info-item mb-3">
                    <span class="info-label">النوع:</span>
                    <span class="info-value">${typeName}</span>
                </div>
                
                <div class="info-item mb-3">
                    <span class="info-label">الحالة:</span>
                    <span class="info-value">
                        <span class="badge-custom badge-active">نشط</span>
                    </span>
                </div>
        `;
        
        // Add settings preview based on type
        if ($('#selected_type').val() == 2) { // Grid
            previewHtml += `
                <div class="info-item mb-3">
                    <span class="info-label">إعدادات الشبكة:</span>
                    <span class="info-value">
                        ${$('#desktop_columns').val()} أعمدة (كمبيوتر) | 
                        ${$('#tablet_columns').val()} أعمدة (تابلت) | 
                        ${$('#mobile_columns').val()} أعمدة (موبايل)
                    </span>
                </div>
            `;
        } else if ($('#selected_type').val() == 1) { // Slider
            previewHtml += `
                <div class="info-item mb-3">
                    <span class="info-label">إعدادات السلايدر:</span>
                    <span class="info-value">
                        ${$('#autoplay').is(':checked') ? 'تشغيل تلقائي' : 'بدون تشغيل تلقائي'} | 
                        ${$('#autoplay_speed').val()} ملي ثانية
                    </span>
                </div>
            `;
        }
        
        // Add period preview
        if (!$('#permanent').is(':checked')) {
            previewHtml += `
                <div class="info-item mb-3">
                    <span class="info-label">الفترة:</span>
                    <span class="info-value">
                        ${$('#start_date').val() ? 'من ' + $('#start_date').val() : 'بدون تاريخ بدء'} 
                        ${$('#end_date').val() ? 'إلى ' + $('#end_date').val() : 'بدون تاريخ انتهاء'}
                    </span>
                </div>
            `;
        } else {
            previewHtml += `
                <div class="info-item mb-3">
                    <span class="info-label">الفترة:</span>
                    <span class="info-value">دائم</span>
                </div>
            `;
        }
        
        previewHtml += `
                <div class="alert alert-info mt-3">
                    <i class="fas fa-info-circle me-2"></i>
                    هذه معاينة أساسية. سيمكنك إضافة العناصر والصور بعد إنشاء البانر.
                </div>
            </div>
        `;
        
        $('#previewContent').html(previewHtml);
        $('#previewModal').modal('show');
    }

    // Set default dates for better UX
    function setDefaultDates() {
        const now = new Date();
        const tomorrow = new Date(now);
        tomorrow.setDate(tomorrow.getDate() + 30);
        
        // Format to YYYY-MM-DDTHH:mm
        const formatDate = (date) => {
            return date.toISOString().slice(0, 16);
        };
        
        $('#start_date').val(formatDate(now));
        $('#end_date').val(formatDate(tomorrow));
    }

    // Initialize on page load
    setDefaultDates();
</script>
@endsection