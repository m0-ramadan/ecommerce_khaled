@extends('Admin.layout.master')

@section('title', '{{ isset($banner) ? "تعديل البانر" : "إضافة بانر جديد" }}')

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
    }

    .slider-settings {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
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
            <li class="breadcrumb-item active">{{ isset($banner) ? 'تعديل' : 'إضافة' }}</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-12">
            <div class="form-card">
                <div class="form-header">
                    <h4 class="mb-0">{{ isset($banner) ? 'تعديل البانر' : 'إضافة بانر جديد' }}</h4>
                    <small>املأ النموذج لإضافة بانر جديد إلى النظام</small>
                </div>

                <form action="{{ isset($banner) ? route('admin.banners.update', $banner) : route('admin.banners.store') }}" 
                      method="POST" id="bannerForm" enctype="multipart/form-data">
                    @csrf
                    @if(isset($banner))
                        @method('PUT')
                    @endif

                    <!-- المعلومات الأساسية -->
                    <div class="form-section">
                        <h5><i class="fas fa-info-circle me-2"></i>المعلومات الأساسية</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="title" class="form-label">عنوان البانر *</label>
                                <input type="text" class="form-control" id="title" name="title" 
                                       value="{{ old('title', $banner->title ?? '') }}" required>
                                @error('title')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="section_order" class="form-label">ترتيب العرض *</label>
                                <input type="number" class="form-control" id="section_order" name="section_order" 
                                       value="{{ old('section_order', $banner->section_order ?? 1 ) }}" required min="1">
                                @error('section_order')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label">القسم (اختياري)</label>
                                <select class="form-control" id="category_id" name="category_id">
                                    <option value="">-- اختر قسم --</option>
                                    <option value="main" {{ (old('category_id', $banner->category_id ?? '') == 'main') ? 'selected' : '' }}>
                                        الرئيسية
                                    </option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ (old('category_id', $banner->category_id ?? '') == $category->id) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="text-muted">اختر القسم الذي سيظهر فيه البانر أو اتركه فارغاً للصفحة الرئيسية</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">الحالة</label>
                                <div class="d-flex align-items-center">
                                    <label class="toggle-switch me-3">
                                        <input type="checkbox" name="is_active" 
                                               {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
                                        <span class="toggle-slider"></span>
                                    </label>
                                    <span class="badge-custom {{ (old('is_active', $banner->is_active ?? true)) ? 'badge-active' : 'badge-inactive' }}">
                                        {{ (old('is_active', $banner->is_active ?? true)) ? 'نشط' : 'غير نشط' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- نوع البانر -->
                    <div class="form-section">
                        <h5><i class="fas fa-sliders-h me-2"></i>نوع البانر</h5>
                        
                        <div class="row">
                            @foreach($bannerTypes as $type)
                                <div class="col-md-3 mb-3">
                                    <div class="type-card {{ (old('banner_type_id', $banner->banner_type_id ?? '') == $type->id) ? 'active' : '' }}" 
                                         onclick="selectType({{ $type->id }})">
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
                                           {{ (old('banner_type_id', $banner->banner_type_id ?? '') == $type->id) ? 'checked' : '' }}
                                           style="display: none;">
                                </div>
                            @endforeach
                        </div>
                        <input type="hidden" id="selected_type" value="{{ old('banner_type_id', $banner->banner_type_id ?? '') }}">
                    </div>

                    <!-- إعدادات الشبكة (تظهر فقط للنوع grid) -->
                    <div class="grid-settings" id="gridSettings" style="display: none;">
                        <h6><i class="fas fa-cog me-2"></i>إعدادات الشبكة</h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="desktop_columns" class="form-label">عدد الأعمدة (كمبيوتر)</label>
                                <input type="number" class="form-control" id="desktop_columns" name="desktop_columns" 
                                       value="{{ old('desktop_columns', $banner->gridLayout->desktop_columns ?? 3 ) }}" min="1" max="6">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="tablet_columns" class="form-label">عدد الأعمدة (تابلت)</label>
                                <input type="number" class="form-control" id="tablet_columns" name="tablet_columns" 
                                       value="{{ old('tablet_columns', $banner->gridLayout->tablet_columns ?? 2 ) }}" min="1" max="4">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="mobile_columns" class="form-label">عدد الأعمدة (موبايل)</label>
                                <input type="number" class="form-control" id="mobile_columns" name="mobile_columns" 
                                       value="{{ old('mobile_columns', $banner->gridLayout->mobile_columns ?? 1 ) }}" min="1" max="2">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="grid_type" class="form-label">نوع الشبكة</label>
                                <select class="form-control" id="grid_type" name="grid_type">
                                    <option value="responsive" {{ (old('grid_type', $banner->gridLayout->grid_type ?? '') == 'responsive') ? 'selected' : '' }}>متجاوب</option>
                                    <option value="fixed" {{ (old('grid_type', $banner->gridLayout->grid_type ?? '') == 'fixed') ? 'selected' : '' }}>ثابت</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="row_gap" class="form-label">المسافة بين الصفوف (بكسل)</label>
                                <input type="number" class="form-control" id="row_gap" name="row_gap" 
                                       value="{{ old('row_gap', $banner->gridLayout->row_gap ?? 20 ) }}" min="0" max="100">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="column_gap" class="form-label">المسافة بين الأعمدة (بكسل)</label>
                                <input type="number" class="form-control" id="column_gap" name="column_gap" 
                                       value="{{ old('column_gap', $banner->gridLayout->column_gap ?? 20 ) }}" min="0" max="100">
                            </div>
                        </div>
                    </div>

                    <!-- إعدادات السلايدر (تظهر فقط للنوع slider) -->
                    <div class="slider-settings" id="sliderSettings" style="display: none;">
                        <h6><i class="fas fa-cog me-2"></i>إعدادات السلايدر</h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="autoplay" name="autoplay" 
                                           {{ old('autoplay', $banner->sliderSetting->autoplay ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="autoplay">التشغيل التلقائي</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="arrows" name="arrows" 
                                           {{ old('arrows', $banner->sliderSetting->arrows ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="arrows">أزرار التنقل</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="dots" name="dots" 
                                           {{ old('dots', $banner->sliderSetting->dots ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="dots">النقاط</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="infinite" name="infinite" 
                                           {{ old('infinite', $banner->sliderSetting->infinite ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="infinite">لانهائي</label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="autoplay_speed" class="form-label">سرعة التشغيل (ملي ثانية)</label>
                                <input type="number" class="form-control" id="autoplay_speed" name="autoplay_speed" 
                                       value="{{ old('autoplay_speed', $banner->sliderSetting->autoplay_speed ?? 3000 ) }}" min="1000" max="10000" step="500">
                            </div>
                        </div>
                    </div>

                    <!-- الفترة الزمنية -->
                    <div class="form-section">
                        <h5><i class="fas fa-calendar-alt me-2"></i>الفترة الزمنية (اختياري)</h5>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">تاريخ البدء</label>
                                <div class="date-input-group">
                                    <i class="fas fa-calendar date-icon"></i>
                                    <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                                           value="{{ old('start_date', isset($banner->start_date) ? $banner->start_date->format('Y-m-d\TH:i') : '') }}">
                                </div>
                                @error('start_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">تاريخ الانتهاء</label>
                                <div class="date-input-group">
                                    <i class="fas fa-calendar date-icon"></i>
                                    <input type="datetime-local" class="form-control" id="end_date" name="end_date" 
                                           value="{{ old('end_date', isset($banner->end_date) ? $banner->end_date->format('Y-m-d\TH:i') : '') }}">
                                </div>
                                @error('end_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="permanent" name="permanent">
                            <label class="form-check-label" for="permanent">
                                دائم (بدون فترة محددة)
                            </label>
                        </div>
                    </div>

                    <!-- إدارة العناصر (للأنواع التي تدعم عناصر متعددة) -->
                    <div class="form-section" id="itemsSection">
                        <h5><i class="fas fa-layer-group me-2"></i>عناصر البانر</h5>
                        
                        <div id="itemsContainer" class="items-container">
                            @if(isset($banner) && $banner->items->count() > 0)
                                @foreach($banner->items->sortBy('item_order') as $item)
                                    <div class="item-card d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            @if($item->image_url)
                                                <img src="{{ Storage::url($item->image_url) }}" 
                                                     alt="{{ $item->image_alt }}" 
                                                     class="item-image me-3">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $item->image_alt ?? 'بدون عنوان' }}</h6>
                                                <small class="text-muted">
                                                    الترتيب: {{ $item->item_order }} | 
                                                    {{ $item->is_active ? 'نشط' : 'غير نشط' }}
                                                </small>
                                            </div>
                                        </div>
                                        <div class="item-actions">
                                            <button type="button" class="btn btn-action btn-warning edit-item" 
                                                    data-id="{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="btn btn-action btn-danger delete-item" 
                                                    data-id="{{ $item->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-items">
                                    <i class="fas fa-image"></i>
                                    <p>لا توجد عناصر مضافة</p>
                                </div>
                            @endif
                        </div>
                        
                        @if(isset($banner))
                        <button type="button" class="btn btn-primary" id="addItemBtn">
                            <i class="fas fa-plus me-2"></i>إضافة عنصر جديد
                        </button>
                        @endif
                    </div>

                    <!-- زر الحفظ -->
                    <div class="text-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" onclick="window.history.back()">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>{{ isset($banner) ? 'تحديث' : 'حفظ' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Adding/Editing Item -->
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="itemModalLabel">إضافة عنصر جديد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="itemForm">
                    @csrf
                    <input type="hidden" name="banner_id" value="{{ $banner->id ?? '' }}">
                    <input type="hidden" name="item_id" id="item_id">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="item_order" class="form-label">ترتيب العنصر *</label>
                            <input type="number" class="form-control" id="item_order" name="item_order" 
                                   value="{{ old('item_order', $banner->items->count() + 1 ?? 1) }}" required min="1">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">حالة العنصر</label>
                            <div class="d-flex align-items-center">
                                <label class="toggle-switch me-3">
                                    <input type="checkbox" name="is_active" id="item_is_active" checked>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="badge-custom badge-active">نشط</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">صورة العنصر *</label>
                            <div class="image-upload-box" onclick="document.getElementById('image').click()">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>انقر لرفع صورة</p>
                                <p class="text-muted">الحجم المقترح: 1200x400 بكسل</p>
                                <img id="imagePreview" class="image-preview" alt="صورة المعاينة">
                                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)">
                            </div>
                            <small class="text-muted">الصورة الأساسية للعرض على جميع الشاشات</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="mobile_image" class="form-label">صورة الموبايل (اختياري)</label>
                            <div class="image-upload-box" onclick="document.getElementById('mobile_image').click()">
                                <i class="fas fa-mobile-alt"></i>
                                <p>انقر لرفع صورة للموبايل</p>
                                <p class="text-muted">الحجم المقترح: 600x200 بكسل</p>
                                <img id="mobileImagePreview" class="image-preview" alt="صورة الموبايل المعاينة">
                                <input type="file" id="mobile_image" name="mobile_image" accept="image/*" onchange="previewMobileImage(event)">
                            </div>
                            <small class="text-muted">صورة خاصة للعرض على الشاشات الصغيرة</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="image_alt" class="form-label">النص البديل للصورة</label>
                            <input type="text" class="form-control" id="image_alt" name="image_alt" 
                                   placeholder="وصف مختصر للصورة">
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="link_url" class="form-label">رابط التوجيه (URL)</label>
                            <input type="url" class="form-control" id="link_url" name="link_url" 
                                   placeholder="https://example.com">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="link_target" class="form-label">فتح الرابط في</label>
                            <select class="form-control" id="link_target" name="link_target">
                                <option value="_self">نفس النافذة</option>
                                <option value="_blank">نافذة جديدة</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" id="is_link_active" name="is_link_active" checked>
                                <label class="form-check-label" for="is_link_active">تفعيل الرابط</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="product_id" class="form-label">ربط بمنتج (اختياري)</label>
                            <select class="form-control select2" id="product_id" name="product_id">
                                <option value="">-- اختر منتج --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="category_id_item" class="form-label">ربط بقسم (اختياري)</label>
                            <select class="form-control select2" id="category_id_item" name="category_id">
                                <option value="">-- اختر قسم --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="tag_text" class="form-label">نص الوسم (اختياري)</label>
                            <input type="text" class="form-control" id="tag_text" name="tag_text" 
                                   placeholder="مثال: جديد، مميز">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tag_color" class="form-label">لون النص</label>
                            <input type="color" class="form-control" id="tag_color" name="tag_color" 
                                   value="#ffffff">
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="tag_bg_color" class="form-label">لون الخلفية</label>
                            <input type="color" class="form-control" id="tag_bg_color" name="tag_bg_color" 
                                   value="#696cff">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="promo_codes" class="form-label">رموز التخفيض (اختياري)</label>
                            <select class="form-control select2" id="promo_codes" name="promo_codes[]" multiple>
                                @foreach($promoCodes as $promo)
                                    <option value="{{ $promo->id }}">{{ $promo->code }} - {{ $promo->discount_percentage }}%</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                <button type="button" class="btn btn-primary" id="saveItemBtn">حفظ العنصر</button>
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
            allowClear: true
        });

        // Show/Hide settings based on banner type
        const selectedType = $('#selected_type').val();
        toggleSettings(selectedType);

        // Permanent checkbox
        $('#permanent').on('change', function() {
            if ($(this).is(':checked')) {
                $('#start_date, #end_date').prop('disabled', true).val('');
            } else {
                $('#start_date, #end_date').prop('disabled', false);
            }
        });

        // Toggle active badge
        $('input[name="is_active"]').on('change', function() {
            const badge = $(this).closest('.d-flex').find('.badge-custom');
            if ($(this).is(':checked')) {
                badge.removeClass('badge-inactive').addClass('badge-active').text('نشط');
            } else {
                badge.removeClass('badge-active').addClass('badge-inactive').text('غير نشط');
            }
        });

        // Add item button
        $('#addItemBtn').on('click', function() {
            $('#itemModalLabel').text('إضافة عنصر جديد');
            $('#itemForm')[0].reset();
            $('#item_id').val('');
            $('#imagePreview, #mobileImagePreview').hide();
            $('.select2').val(null).trigger('change');
            $('#itemModal').modal('show');
        });

        // Edit item button
        $('.edit-item').on('click', function() {
            const itemId = $(this).data('id');
            loadItemData(itemId);
        });

        // Delete item button
        $('.delete-item').on('click', function() {
            const itemId = $(this).data('id');
            deleteItem(itemId);
        });

        // Save item
        $('#saveItemBtn').on('click', function() {
            saveItem();
        });

        // Form submission
        $('#bannerForm').on('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const title = $('#title').val();
            if (!title.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'يرجى إدخال عنوان البانر',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }

            // Submit form
            this.submit();
        });
    });

    function selectType(typeId) {
        $('.type-card').removeClass('active');
        $(`#type_${typeId}`).prop('checked', true).closest('.col-md-3').find('.type-card').addClass('active');
        $('#selected_type').val(typeId);
        toggleSettings(typeId);
    }

    function toggleSettings(typeId) {
        // Hide all settings first
        $('#gridSettings, #sliderSettings').hide();
        
        // Show relevant settings
        if (typeId == 2) { // Grid type
            $('#gridSettings').show();
        } else if (typeId == 1) { // Slider type
            $('#sliderSettings').show();
        }
    }

    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewMobileImage(event) {
        const input = event.target;
        const preview = document.getElementById('mobileImagePreview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function loadItemData(itemId) {
        $.ajax({
            url: "{{ route('admin.banners.show', ['banner' => ':bannerId']) }}".replace(':bannerId', $('#banner_id').val()),
            type: 'GET',
            data: { item_id: itemId },
            success: function(response) {
                if (response.item) {
                    const item = response.item;
                    $('#itemModalLabel').text('تعديل العنصر');
                    $('#item_id').val(item.id);
                    $('#item_order').val(item.item_order);
                    $('#item_is_active').prop('checked', item.is_active).trigger('change');
                    $('#image_alt').val(item.image_alt);
                    $('#link_url').val(item.link_url);
                    $('#link_target').val(item.link_target);
                    $('#is_link_active').prop('checked', item.is_link_active);
                    $('#product_id').val(item.product_id).trigger('change');
                    $('#category_id_item').val(item.category_id).trigger('change');
                    $('#tag_text').val(item.tag_text);
                    $('#tag_color').val(item.tag_color || '#ffffff');
                    $('#tag_bg_color').val(item.tag_bg_color || '#696cff');
                    
                    // Set promo codes
                    if (item.promo_codes && item.promo_codes.length > 0) {
                        const promoIds = item.promo_codes.map(promo => promo.id);
                        $('#promo_codes').val(promoIds).trigger('change');
                    }
                    
                    $('#itemModal').modal('show');
                }
            }
        });
    }

    function saveItem() {
        const formData = new FormData($('#itemForm')[0]);
        const itemId = $('#item_id').val();
        const url = itemId 
            ? "{{ route('admin.banners.items.update', ':id') }}".replace(':id', itemId)
            : "{{ route('admin.banners.items.store') }}";
        const method = itemId ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-HTTP-Method-Override': method
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'نجاح',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        $('#itemModal').modal('hide');
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: xhr.responseJSON?.message || 'حدث خطأ أثناء الحفظ',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    function deleteItem(itemId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم حذف العنصر نهائياً',
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
                    url: "{{ route('admin.banners.items.destroy', ':id') }}".replace(':id', itemId),
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'تم الحذف',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'خطأ',
                            text: 'حدث خطأ أثناء الحذف',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                });
            }
        });
    }
</script>
@endsection
