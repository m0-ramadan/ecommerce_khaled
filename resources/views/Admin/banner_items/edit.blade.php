@extends('Admin.layout.master')

@section('title', 'تعديل عنصر البانر')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        body {
            font-family: "Cairo", sans-serif;
            background: #1e1e2d;
            color: #fff;
        }

        .form-card {
            background: #2b3b4c;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .form-header {
            background: var(--primary-gradient);
            color: white;
            padding: 20px 30px;
            border-radius: 15px 15px 0 0;
            margin: -30px -30px 30px -30px;
        }

        .form-label {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            padding: 10px 15px;
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

        .form-check-input {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
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
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #dc3545;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-danger:hover {
            background: #dc3545;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }

        .image-preview {
            width: 150px;
            height: 150px;
            border-radius: 10px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
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
            color: rgba(255, 255, 255, 0.5);
            text-align: center;
            font-size: 14px;
        }

        .current-image {
            margin-bottom: 10px;
            padding: 10px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
        }

        .current-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            margin-left: 10px;
        }

        .select2-container--default .select2-selection--multiple {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--primary-gradient);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
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
                <li class="breadcrumb-item active">تعديل عنصر #{{ $bannerItem->id }}</li>
            </ol>
        </nav>

        <div class="form-card">
            <div class="form-header">
                <h5 class="mb-0">تعديل عنصر البانر</h5>
            </div>

            <form action="{{ route('admin.banners.items.update', $bannerItem->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Basic Information -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">البانر <span class="text-danger">*</span></label>
                        <select name="banner_id" class="form-select @error('banner_id') is-invalid @enderror" required>
                            <option value="">اختر البانر</option>
                            @foreach ($banners as $banner)
                                <option value="{{ $banner->id }}"
                                    {{ old('banner_id', $bannerItem->banner_id) == $banner->id ? 'selected' : '' }}>
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
                            value="{{ old('item_order', $bannerItem->item_order) }}" min="1" required>
                        @error('item_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Images -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الصورة الأساسية</label>

                        @if ($bannerItem->image_url)
                            <div class="current-image d-flex align-items-center">
                                <img src="{{ Storage::url($bannerItem->image_url) }}" alt="Current Image">
                                <span class="text-muted">الصورة الحالية</span>
                            </div>
                        @endif

                        <input type="file" name="image_url" class="form-control @error('image_url') is-invalid @enderror"
                            accept="image/*" onchange="previewImage(this, 'desktop-preview')">
                        <div class="image-preview" id="desktop-preview">
                            @if ($bannerItem->image_url)
                                <img src="{{ Storage::url($bannerItem->image_url) }}" alt="Preview">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-image fa-2x mb-2"></i>
                                    <p>معاينة الصورة</p>
                                </div>
                            @endif
                        </div>
                        <small class="text-muted">اتركه فارغاً للاحتفاظ بالصورة الحالية</small>
                        @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">صورة الموبايل</label>

                        @if ($bannerItem->mobile_image_url)
                            <div class="current-image d-flex align-items-center">
                                <img src="{{ Storage::url($bannerItem->mobile_image_url) }}" alt="Current Mobile Image">
                                <span class="text-muted">الصورة الحالية</span>
                            </div>
                        @endif

                        <input type="file" name="mobile_image_url"
                            class="form-control @error('mobile_image_url') is-invalid @enderror" accept="image/*"
                            onchange="previewImage(this, 'mobile-preview')">
                        <div class="image-preview" id="mobile-preview">
                            @if ($bannerItem->mobile_image_url)
                                <img src="{{ Storage::url($bannerItem->mobile_image_url) }}" alt="Preview">
                            @else
                                <div class="placeholder">
                                    <i class="fas fa-mobile-alt fa-2x mb-2"></i>
                                    <p>معاينة الصورة</p>
                                </div>
                            @endif
                        </div>
                        <small class="text-muted">اتركه فارغاً للاحتفاظ بالصورة الحالية</small>
                        @error('mobile_image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Image Alt -->
                    <div class="col-md-12 mb-3">
                        <label class="form-label">نص بديل للصورة (Alt)</label>
                        <input type="text" name="image_alt" class="form-control @error('image_alt') is-invalid @enderror"
                            value="{{ old('image_alt', $bannerItem->image_alt) }}" placeholder="وصف مختصر للصورة">
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
                            value="{{ old('link_url', $bannerItem->link_url) }}" placeholder="https://example.com">
                        @error('link_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">فتح الرابط</label>
                        <select name="link_target" class="form-select @error('link_target') is-invalid @enderror">
                            <option value="_self"
                                {{ old('link_target', $bannerItem->link_target) == '_self' ? 'selected' : '' }}>نفس النافذة
                            </option>
                            <option value="_blank"
                                {{ old('link_target', $bannerItem->link_target) == '_blank' ? 'selected' : '' }}>نافذة
                                جديدة</option>
                        </select>
                        @error('link_target')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_link_active" class="form-check-input" id="is_link_active"
                                value="1" {{ old('is_link_active', $bannerItem->is_link_active) ? 'checked' : '' }}>
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
                                    {{ old('product_id', $bannerItem->product_id) == $product->id ? 'selected' : '' }}>
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
                                    {{ old('category_id', $bannerItem->category_id) == $category->id ? 'selected' : '' }}>
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
                            class="form-control @error('tag_text') is-invalid @enderror"
                            value="{{ old('tag_text', $bannerItem->tag_text) }}" placeholder="مثال: جديد">
                        @error('tag_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">لون النص</label>
                        <input type="color" name="tag_color"
                            class="form-control @error('tag_color') is-invalid @enderror"
                            value="{{ old('tag_color', $bannerItem->tag_color ?? '#ffffff') }}">
                        @error('tag_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">لون الخلفية</label>
                        <input type="color" name="tag_bg_color"
                            class="form-control @error('tag_bg_color') is-invalid @enderror"
                            value="{{ old('tag_bg_color', $bannerItem->tag_bg_color ?? '#696cff') }}">
                        @error('tag_bg_color')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="col-md-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="is_active"
                                value="1" {{ old('is_active', $bannerItem->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                العنصر نشط
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>تحديث
                        </button>
                        <a href="{{ route('admin.banners.items.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                        <button type="button" class="btn btn-danger float-end"
                            onclick="deleteItem({{ $bannerItem->id }})">
                            <i class="fas fa-trash me-2"></i>حذف
                        </button>
                    </div>
                </div>
            </form>
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
            }
        }

        function deleteItem(id) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف هذا العنصر نهائياً',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/banners/items') }}/" + id,
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
                                    window.location.href =
                                        "{{ route('admin.banners.items.index') }}";
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء الحذف'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
