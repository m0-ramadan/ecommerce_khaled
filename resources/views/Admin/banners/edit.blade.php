@extends('Admin.layout.master')

@section('title', 'تعديل البانر')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
            --text-muted: #6c757d;
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .form-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            padding: 30px;
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .form-section {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
        }

        .form-section h5 {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
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
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.05);
        }

        .category-option:hover {
            border-color: var(--primary-color);
            background: rgba(105, 108, 255, 0.1);
        }

        .category-option.active {
            border-color: var(--primary-color);
            background: rgba(105, 108, 255, 0.2);
        }

        .category-option i {
            font-size: 24px;
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        .category-select {
            display: none;
        }

        .category-select.show {
            display: block;
            animation: fadeIn 0.3s ease;
        }

        .type-card {
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            background: rgba(255, 255, 255, 0.05);
            height: 100%;
        }

        .type-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-5px);
            background: rgba(105, 108, 255, 0.1);
        }

        .type-card.active {
            border-color: var(--primary-color);
            background: rgba(105, 108, 255, 0.2);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.3);
        }

        .type-icon {
            font-size: 32px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .type-desc {
            color: var(--text-muted);
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
            background-color: var(--primary-color);
        }

        input:checked+.toggle-slider:before {
            transform: translateX(24px);
        }

        .badge-custom {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-active {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }

        .badge-inactive {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }

        .date-input-group {
            position: relative;
        }

        .date-input-group .form-control {
            padding-left: 40px;
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .date-input-group .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
        }

        .date-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            z-index: 4;
        }

        .grid-settings,
        .slider-settings {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px dashed rgba(255, 255, 255, 0.1);
        }

        .help-text {
            color: var(--text-muted);
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .required-field::after {
            content: " *";
            color: var(--danger-color);
        }

        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4a9a 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .breadcrumb {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: rgba(255, 255, 255, 0.7);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                <li class="breadcrumb-item active">تعديل البانر</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-12">
                <div class="form-card">
                    <div class="form-header">
                        <h4 class="mb-0">تعديل البانر: {{ $banner->title }}</h4>
                        <small>قم بتحديث بيانات البانر</small>
                    </div>

                    <form action="{{ route('admin.banners.update', $banner) }}" method="POST" id="bannerForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- القسم والموقع -->
                        <div class="form-section">
                            <h5><i class="fas fa-map-marker-alt me-2"></i>القسم والموقع</h5>

                            <div class="category-options">
                                <div class="category-option {{ old('category_type', $banner->category_id ? 'specific' : 'main') == 'main' ? 'active' : '' }}"
                                    data-category-type="main">
                                    <i class="fas fa-home"></i>
                                    <h6 class="mb-2">الرئيسية</h6>
                                    <p class="type-desc">عرض البانر في الصفحة الرئيسية</p>
                                </div>
                                <div class="category-option {{ old('category_type', $banner->category_id ? 'specific' : 'main') == 'specific' ? 'active' : '' }}"
                                    data-category-type="specific">
                                    <i class="fas fa-tag"></i>
                                    <h6 class="mb-2">قسم محدد</h6>
                                    <p class="type-desc">عرض البانر في قسم معين</p>
                                </div>
                            </div>

                            <input type="hidden" name="category_type" id="category_type"
                                value="{{ old('category_type', $banner->category_id ? 'specific' : 'main') }}">

                            <div class="category-select {{ old('category_type', $banner->category_id ? 'specific' : 'main') == 'specific' ? 'show' : '' }}"
                                id="specificCategorySelect">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="category_id" class="form-label required-field">اختر القسم</label>
                                        <select class="form-control select2" id="category_id" name="category_id">
                                            <option value="">-- اختر قسم من القائمة --</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $banner->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="help-text">سيتم عرض البانر في صفحة هذا القسم فقط</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- المعلومات الأساسية -->
                        <div class="form-section">
                            <h5><i class="fas fa-info-circle me-2"></i>المعلومات الأساسية</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label required-field">عنوان البانر</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        id="title" name="title" value="{{ old('title', $banner->title) }}"
                                        required placeholder="أدخل عنوان واضح للبانر">
                                    @error('title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <span class="help-text">العنوان سيساعدك في التعرف على البانر لاحقاً</span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="section_order" class="form-label required-field">ترتيب العرض</label>
                                    <input type="number" class="form-control @error('section_order') is-invalid @enderror"
                                        id="section_order" name="section_order"
                                        value="{{ old('section_order', $banner->section_order) }}" required
                                        min="1">
                                    @error('section_order')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <span class="help-text">الأرقام الأقل تظهر أولاً</span>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label required-field">حالة البانر</label>
                                    <div class="d-flex align-items-center mt-2">
                                        <label class="toggle-switch me-3">
                                            <input type="hidden" name="is_active" value="0">
                                            <input type="checkbox" name="is_active" value="1"
                                                {{ old('is_active', $banner->is_active) ? 'checked' : '' }}>
                                            <span class="toggle-slider"></span>
                                        </label>
                                        <span
                                            class="badge-custom {{ old('is_active', $banner->is_active) ? 'badge-active' : 'badge-inactive' }}">
                                            {{ old('is_active', $banner->is_active) ? 'نشط' : 'غير نشط' }}
                                        </span>
                                    </div>
                                    <span class="help-text">يمكنك تعطيل البانر لاحقاً</span>
                                </div>
                            </div>
                        </div>

                        <!-- نوع البانر -->
                        <div class="form-section">
                            <h5><i class="fas fa-sliders-h me-2"></i>نوع البانر</h5>

                            <div class="row">
                                @foreach ($bannerTypes as $type)
                                    @php
                                        $typeNames = [
                                            'slider' => 'سلايدر',
                                            'grid' => 'شبكة',
                                            'static' => 'ثابت',
                                            'category_slider' => 'أقسام',
                                        ];

                                        $typeIcons = [
                                            'slider' => 'fa-sliders-h',
                                            'grid' => 'fa-th-large',
                                            'static' => 'fa-image',
                                            'category_slider' => 'fa-tags',
                                        ];
                                    @endphp

                                    <div class="col-md-3 mb-3">
                                        <div class="type-card {{ old('banner_type_id', $banner->banner_type_id) == $type->id ? 'active' : '' }}"
                                            data-type-id="{{ $type->id }}" data-type-name="{{ $type->name }}">
                                            <div class="type-icon">
                                                <i class="fas {{ $typeIcons[$type->name] ?? 'fa-image' }}"></i>
                                            </div>
                                            <h6 class="mb-2">{{ $typeNames[$type->name] ?? $type->name }}</h6>
                                            <p class="type-desc">{{ $type->description }}</p>
                                        </div>
                                        <input type="radio" name="banner_type_id" value="{{ $type->id }}"
                                            id="type_{{ $type->id }}"
                                            {{ old('banner_type_id', $banner->banner_type_id) == $type->id ? 'checked' : '' }}
                                            class="d-none">
                                    </div>
                                @endforeach
                            </div>
                            <input type="hidden" id="selected_type" value="{{ old('banner_type_id', $banner->banner_type_id) }}">
                        </div>

                        <!-- إعدادات الشبكة -->
                        <div class="grid-settings" id="gridSettings" style="display: none;">
                            <h6><i class="fas fa-cog me-2"></i>إعدادات الشبكة</h6>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="desktop_columns" class="form-label">عدد الأعمدة (كمبيوتر)</label>
                                    <input type="number" class="form-control" id="desktop_columns"
                                        name="desktop_columns"
                                        value="{{ old('desktop_columns', $banner->gridLayout->desktop_columns ?? 3) }}"
                                        min="1" max="6">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="tablet_columns" class="form-label">عدد الأعمدة (تابلت)</label>
                                    <input type="number" class="form-control" id="tablet_columns" name="tablet_columns"
                                        value="{{ old('tablet_columns', $banner->gridLayout->tablet_columns ?? 2) }}"
                                        min="1" max="4">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="mobile_columns" class="form-label">عدد الأعمدة (موبايل)</label>
                                    <input type="number" class="form-control" id="mobile_columns" name="mobile_columns"
                                        value="{{ old('mobile_columns', $banner->gridLayout->mobile_columns ?? 1) }}"
                                        min="1" max="2">
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="grid_type" class="form-label">نوع الشبكة</label>
                                    <select class="form-control" id="grid_type" name="grid_type">
                                        <option value="responsive"
                                            {{ old('grid_type', $banner->gridLayout->grid_type ?? '') == 'responsive' ? 'selected' : '' }}>
                                            متجاوب</option>
                                        <option value="fixed"
                                            {{ old('grid_type', $banner->gridLayout->grid_type ?? '') == 'fixed' ? 'selected' : '' }}>
                                            ثابت</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="row_gap" class="form-label">المسافة بين الصفوف</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="row_gap" name="row_gap"
                                            value="{{ old('row_gap', $banner->gridLayout->row_gap ?? 20) }}"
                                            min="0" max="100">
                                        <span class="input-group-text">بكسل</span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="column_gap" class="form-label">المسافة بين الأعمدة</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="column_gap" name="column_gap"
                                            value="{{ old('column_gap', $banner->gridLayout->column_gap ?? 20) }}"
                                            min="0" max="100">
                                        <span class="input-group-text">بكسل</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- إعدادات السلايدر -->
                        <div class="slider-settings" id="sliderSettings" style="display: none;">
                            <h6><i class="fas fa-cog me-2"></i>إعدادات السلايدر</h6>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="autoplay" value="0">
                                        <input class="form-check-input" type="checkbox" id="autoplay" name="autoplay"
                                            value="1"
                                            {{ old('autoplay', $banner->sliderSetting->autoplay ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="autoplay">التشغيل التلقائي</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="arrows" value="0">
                                        <input class="form-check-input" type="checkbox" id="arrows" name="arrows"
                                            value="1"
                                            {{ old('arrows', $banner->sliderSetting->arrows ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="arrows">أزرار التنقل</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="dots" value="0">
                                        <input class="form-check-input" type="checkbox" id="dots" name="dots"
                                            value="1"
                                            {{ old('dots', $banner->sliderSetting->dots ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="dots">النقاط</label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="infinite" value="0">
                                        <input class="form-check-input" type="checkbox" id="infinite" name="infinite"
                                            value="1"
                                            {{ old('infinite', $banner->sliderSetting->infinite ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="infinite">لانهائي</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="autoplay_speed" class="form-label">سرعة التشغيل</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="autoplay_speed"
                                            name="autoplay_speed"
                                            value="{{ old('autoplay_speed', $banner->sliderSetting->autoplay_speed ?? 3000) }}"
                                            min="1000" max="10000" step="500">
                                        <span class="input-group-text">ملي ثانية</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- الفترة الزمنية -->
                        <div class="form-section">
                            <h5><i class="fas fa-calendar-alt me-2"></i>الفترة الزمنية</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="start_date" class="form-label">تاريخ البدء</label>
                                    <div class="date-input-group">
                                        <i class="fas fa-calendar date-icon"></i>
                                        <input type="datetime-local"
                                            class="form-control @error('start_date') is-invalid @enderror" id="start_date"
                                            name="start_date"
                                            value="{{ old('start_date', $banner->start_date ? $banner->start_date->format('Y-m-d\TH:i') : '') }}">
                                    </div>
                                    @error('start_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="end_date" class="form-label">تاريخ الانتهاء</label>
                                    <div class="date-input-group">
                                        <i class="fas fa-calendar date-icon"></i>
                                        <input type="datetime-local"
                                            class="form-control @error('end_date') is-invalid @enderror" id="end_date"
                                            name="end_date"
                                            value="{{ old('end_date', $banner->end_date ? $banner->end_date->format('Y-m-d\TH:i') : '') }}">
                                    </div>
                                    @error('end_date')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input type="hidden" name="permanent" value="0">
                                <input class="form-check-input" type="checkbox" id="permanent" name="permanent"
                                    value="1"
                                    {{ old('permanent', !$banner->start_date && !$banner->end_date) ? 'checked' : '' }}>
                                <label class="form-check-label" for="permanent">
                                    دائم (بدون فترة محددة)
                                </label>
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>تحديث
                                </button>
                            </div>
                        </div>
                    </form>
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
            // تهيئة Select2
            $('.select2').select2({
                placeholder: 'اختر من القائمة',
                allowClear: true,
                language: {
                    noResults: function() {
                        return "لا توجد نتائج";
                    }
                }
            });

            // اختيار القسم
            $('.category-option').on('click', function() {
                const categoryType = $(this).data('category-type');
                
                $('.category-option').removeClass('active');
                $(this).addClass('active');
                $('#category_type').val(categoryType);

                if (categoryType === 'specific') {
                    $('#specificCategorySelect').addClass('show');
                    $('#category_id').prop('required', true);
                } else {
                    $('#specificCategorySelect').removeClass('show');
                    $('#category_id').prop('required', false).val('');
                }
            });

            // اختيار نوع البانر
            $('.type-card').on('click', function() {
                const typeId = $(this).data('type-id');
                const typeName = $(this).data('type-name');
                
                $('.type-card').removeClass('active');
                $(this).addClass('active');
                $(`#type_${typeId}`).prop('checked', true);
                $('#selected_type').val(typeId);

                // إظهار/إخفاء الإعدادات حسب النوع
                $('#gridSettings, #sliderSettings').hide();

                if (typeName === 'grid') {
                    $('#gridSettings').show();
                } else if (typeName === 'slider') {
                    $('#sliderSettings').show();
                }
            });

            // تغيير حالة البانر
            $('input[name="is_active"]').on('change', function() {
                const isChecked = $(this).is(':checked');
                const badge = $(this).closest('.d-flex').find('.badge-custom');
                
                if (badge.length) {
                    badge.removeClass('badge-active badge-inactive')
                          .addClass(isChecked ? 'badge-active' : 'badge-inactive')
                          .text(isChecked ? 'نشط' : 'غير نشط');
                }
            });

            // تغيير الحالة الدائمة
            $('#permanent').on('change', function() {
                const isPermanent = $(this).is(':checked');
                $('#start_date, #end_date').prop('disabled', isPermanent);
                
                if (isPermanent) {
                    $('#start_date, #end_date').val('');
                }
            }).trigger('change');

            // تفعيل النوع المحدد مسبقاً
            const selectedType = $('#selected_type').val();
            if (selectedType) {
                const typeCard = $(`.type-card[data-type-id="${selectedType}"]`);
                if (typeCard.length) {
                    typeCard.trigger('click');
                }
            }

            // تفعيل القسم المحدد مسبقاً
            const categoryType = $('#category_type').val();
            if (categoryType === 'specific') {
                $(`.category-option[data-category-type="specific"]`).trigger('click');
            }

            // التحقق من صحة النموذج قبل الإرسال
            $('#bannerForm').on('submit', function(e) {
                const title = $('#title').val().trim();
                const categoryType = $('#category_type').val();
                const categoryId = $('#category_id').val();

                if (!title) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'يرجى إدخال عنوان البانر'
                    });
                    return false;
                }

                if (categoryType === 'specific' && !categoryId) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'يرجى اختيار قسم للبانر'
                    });
                    return false;
                }
            });
        });
    </script>
@endsection