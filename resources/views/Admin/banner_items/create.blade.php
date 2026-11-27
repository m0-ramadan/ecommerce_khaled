@extends('Admin.layout.master')

@section('title', 'إضافة عنصر بانر جديد')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #14213d;
            --primary-gradient: linear-gradient(135deg, #14213d 0%, #0c182a 100%);
        }

        body {
            font-family: "Cairo", sans-serif;
            background: #f6f8fb;
            color: #1e293b;
        }

        .form-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
            padding: 30px;
            border: 1px solid #e2e8f0;
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 30px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .form-label {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            border-radius: 10px;
            padding: 10px 15px;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #1e293b;
            box-shadow: 0 0 0 0.25rem rgba(20, 33, 61, 0.25);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-control[type="file"] {
            padding: 8px 15px;
        }

        .form-control[type="color"] {
            height: 45px;
            padding: 5px;
        }

        .form-check-input {
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            color: #1e293b;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(20, 33, 61, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #e2e8f0;
            color: #1e293b;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            border-color: #e2e8f0;
        }

        .image-preview {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            border: 2px dashed #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 10px;
            overflow: hidden;
            position: relative;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-preview .placeholder {
            color: #64748b;
            text-align: center;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--multiple {
            background: #f8fafc;
            border-color: #e2e8f0;
            border-radius: 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #1e293b;
            margin-right: 5px;
        }

        .select2-dropdown {
            background: #ffffff;
            border-color: #e2e8f0;
        }

        .select2-results__option {
            color: #1e293b;
        }

        .select2-results__option--highlighted {
            background: var(--primary-gradient) !important;
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
                    <a href="{{ route('admin.banners.index') }}">البانرات</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.banners.items.index') }}">عناصر البانرات</a>
                </li>
                <li class="breadcrumb-item active">إضافة عنصر جديد</li>
            </ol>
        </nav>

        <div class="form-card">
            <div class="form-header">
                <h5 class="mb-0">إضافة عنصر بانر جديد</h5>
            </div>

            <form action="{{ route('admin.banners.items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">البانر <span class="text-danger">*</span></label>
                        <select name="banner_id" class="form-select @error('banner_id') is-invalid @enderror" required>
                            <option value="">اختر البانر</option>
                            @foreach ($banners as $banner)
                                <option value="{{ $banner->id }}"
                                    {{ old('banner_id', $selectedBannerId) == $banner->id ? 'selected' : '' }}>
                                    {{ $banner->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('banner_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">الترتيب <span class="text-danger">*</span></label>
                        <input type="number" name="item_order"
                            class="form-control @error('item_order') is-invalid @enderror"
                            value="{{ old('item_order', $nextOrder) }}" min="1" required>
                        @error('item_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Images -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">الصورة الأساسية (سطح المكتب - Desktop) <span class="text-danger">*</span></label>
                        <input type="file" name="image_url" class="form-control @error('image_url') is-invalid @enderror"
                            accept="image/*" onchange="previewImage(this, 'desktop-preview')">
                        <div class="image-preview" id="desktop-preview">
                            <div class="placeholder">
                                <i class="fas fa-desktop fa-2x mb-2"></i>
                                <p>معاينة صورة سطح المكتب</p>
                            </div>
                        </div>
                        <small class="text-muted">الصيغ المسموحة: jpeg, png, jpg, gif, svg, webp - الحد الأقصى: 2MB</small>
                        @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">صورة الموبايل (الجوال - Mobile) <span class="text-muted fw-normal">(اختياري)</span></label>
                        <input type="file" name="mobile_image_url"
                            class="form-control @error('mobile_image_url') is-invalid @enderror" accept="image/*"
                            onchange="previewImage(this, 'mobile-preview')">
                        <div class="image-preview" id="mobile-preview">
                            <div class="placeholder">
                                <i class="fas fa-mobile-alt fa-2x mb-2"></i>
                                <p>معاينة صورة الموبايل</p>
                            </div>
                        </div>
                        <small class="text-muted">الصيغ المسموحة: jpeg, png, jpg, gif, svg, webp - الحد الأقصى: 2MB</small>
                        @error('mobile_image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Alt -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">نص بديل للصورة (Alt)</label>
                        <input type="text" name="image_alt" class="form-control @error('image_alt') is-invalid @enderror"
                            value="{{ old('image_alt') }}" placeholder="وصف مختصر للصورة">
                        @error('image_alt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Link Settings -->
                    <div class="col-md-12">
                        <h6 class="mb-3">إعدادات الرابط</h6>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">رابط الصورة</label>
                        <input type="url" name="link_url" class="form-control @error('link_url') is-invalid @enderror"
                            value="{{ old('link_url') }}" placeholder="https://example.com">
                        @error('link_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">فتح الرابط</label>
                        <select name="link_target" class="form-select @error('link_target') is-invalid @enderror">
                            <option value="_self" {{ old('link_target') == '_self' ? 'selected' : '' }}>نفس النافذة
                            </option>
                            <option value="_blank" {{ old('link_target') == '_blank' ? 'selected' : '' }}>نافذة جديدة
                            </option>
                        </select>
                        @error('link_target')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_link_active" class="form-check-input" id="is_link_active"
                                value="1" {{ old('is_link_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_link_active">
                                الرابط نشط
                            </label>
                        </div>
                    </div>

                    <!-- Product/Category Linking -->
                    <div class="col-md-12">
                        <h6 class="mb-3">الربط مع المنتج أو التصنيف</h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">المنتج</label>
                        <select name="product_id" class="form-select @error('product_id') is-invalid @enderror"
                            id="productSelect">
                            <option value="">اختر المنتج</option>
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}"
                                    {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">إذا تم اختيار منتج، سيتم تجاهل الرابط المخصص</small>
                        @error('product_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">التصنيف</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror"
                            id="categorySelect">
                            <option value="">اختر التصنيف</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">إذا تم اختيار تصنيف، سيتم تجاهل الرابط المخصص</small>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tag Settings -->
                    <div class="col-md-12">
                        <h6 class="mb-3">إعدادات الوسم (Tag)</h6>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">نص الوسم</label>
                        <input type="text" name="tag_text"
                            class="form-control @error('tag_text') is-invalid @enderror" value="{{ old('tag_text') }}"
                            placeholder="مثال: جديد">
                        @error('tag_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">لون النص</label>
                        <input type="color" name="tag_color"
                            class="form-control @error('tag_color') is-invalid @enderror"
                            value="{{ old('tag_color', '#ffffff') }}">
                        @error('tag_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">لون الخلفية</label>
                        <input type="color" name="tag_bg_color"
                            class="form-control @error('tag_bg_color') is-invalid @enderror"
                            value="{{ old('tag_bg_color', '#14213d') }}">
                        @error('tag_bg_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                العنصر نشط
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ
                        </button>
                        <a href="{{ route('admin.banners.items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('#productSelect, #categorySelect').select2({
                placeholder: 'اختر...',
                allowClear: true,
                dir: 'rtl',
                width: '100%'
            });

            // Prevent selecting both product and category
            $('#productSelect').on('change', function() {
                if ($(this).val()) {
                    $('#categorySelect').val(null).trigger('change');
                }
            });

            $('#categorySelect').on('change', function() {
                if ($(this).val()) {
                    $('#productSelect').val(null).trigger('change');
                }
            });
        });

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.innerHTML = `
                <div class="placeholder">
                    <i class="fas ${previewId.includes('mobile') ? 'fa-mobile-alt' : 'fa-image'} fa-2x mb-2"></i>
                    <p>معاينة الصورة</p>
                </div>
            `;
            }
        }
    </script>
@endsection
