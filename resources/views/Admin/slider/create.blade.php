@extends('Admin.layout.master')

@section('title', 'إضافة شريحة سلايدر جديدة')

@section('css')
    <style>
        .dropzone-box {
            border: 2px dashed #d9dee3;
            border-radius: 12px;
            padding: 30px 20px;
            text-align: center;
            background-color: #fcfdfd;
            cursor: pointer;
            transition: all 0.25s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        .dropzone-box:hover, .dropzone-box.dragover {
            border-color: #696cff;
            background-color: #f8f9ff;
        }
        .preview-img-container {
            position: relative;
            display: none;
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 12px rgba(0,0,0,0.1);
        }
        .preview-img-container img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
        }
        .preview-img-container-mobile img {
            width: 100%;
            height: 220px;
            object-fit: contain;
            background: #f8fafc;
            display: block;
        }
        .remove-img-btn {
            position: absolute;
            top: 10px;
            left: 10px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 34px;
            height: 34px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .remove-img-btn:hover {
            background: #dc3545;
            transform: scale(1.1);
        }
        .link-type-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 16px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .link-type-card:hover {
            border-color: #696cff;
            background-color: #fcfdfd;
        }
        .link-type-card.selected {
            border-color: #696cff;
            background-color: #f0f2ff;
            color: #696cff;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold py-1 mb-1">
                <span class="text-muted fw-light">السلايدر الرئيسي /</span> إضافة شريحة جديدة
            </h4>
            <p class="text-muted mb-0">ارفع صورة جديدة واضبط رابط التوجيه والترتيب لتظهر مباشرة على واجهة المتجر</p>
        </div>
        <div>
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-label-secondary d-flex align-items-center gap-2">
                <i class="ti ti-arrow-right fs-5"></i>
                <span>العودة للقائمة</span>
            </a>
        </div>
    </div>

    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h6 class="alert-heading fw-bold mb-1"><i class="ti ti-alert-triangle me-1"></i> يرجى تصحيح الأخطاء التالية:</h6>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.sliders.store') }}" method="POST" enctype="multipart/form-data" id="sliderForm">
        @csrf

        <div class="row g-4">
            <!-- Left / Main Column: Images & Content -->
            <div class="col-lg-8">
                <!-- 1. Desktop Image (Required) -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header py-3 bg-transparent border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-photo text-primary fs-4"></i>
                            <span>صورة الشريحة (سطح المكتب - Desktop)</span>
                            <span class="badge bg-label-danger fs-tiny">مطلوبة *</span>
                        </h5>
                        <small class="text-muted">المقاس الموصى به: 1920 × 600 بكسل</small>
                    </div>
                    <div class="card-body pt-3">
                        <div class="dropzone-box" id="desktopDropzone" onclick="document.getElementById('imageInput').click()">
                            <i class="ti ti-cloud-upload text-primary" style="font-size: 48px;"></i>
                            <h6 class="mt-2 mb-1 fw-bold">اضغط لاختيار صورة السلايدر أو اسحبها إلى هنا</h6>
                            <p class="text-muted small mb-0">يدعم صيغ WEBP, PNG, JPG, JPEG (الحد الأقصى: 5 ميجابايت)</p>
                        </div>
                        <input type="file" name="image" id="imageInput" class="d-none" accept="image/*" required>

                        <div class="preview-img-container mt-3" id="desktopPreviewContainer">
                            <button type="button" class="remove-img-btn" id="removeDesktopBtn" title="إزالة الصورة">
                                <i class="ti ti-x"></i>
                            </button>
                            <img src="" alt="Desktop Preview" id="desktopPreviewImg">
                        </div>
                    </div>
                </div>

                <!-- 2. Mobile Image (Optional) -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header py-3 bg-transparent border-bottom d-flex align-items-center justify-content-between">
                        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-device-mobile text-primary fs-4"></i>
                            <span>صورة الجوال (Mobile Image)</span>
                            <span class="badge bg-label-secondary fs-tiny">اختيارية</span>
                        </h5>
                        <small class="text-muted">المقاس الموصى به: 800 × 600 بكسل</small>
                    </div>
                    <div class="card-body pt-3">
                        <div class="dropzone-box" id="mobileDropzone" onclick="document.getElementById('mobileImageInput').click()">
                            <i class="ti ti-device-mobile-upload text-muted" style="font-size: 40px;"></i>
                            <h6 class="mt-2 mb-1 fw-semibold">اختر صورة مخصصة لشاشات الجوال (اختياري)</h6>
                            <p class="text-muted small mb-0">إذا لم يتم اختيارها، سيتم استخدام صورة سطح المكتب تلقائياً</p>
                        </div>
                        <input type="file" name="mobile_image" id="mobileImageInput" class="d-none" accept="image/*">

                        <div class="preview-img-container preview-img-container-mobile mt-3" id="mobilePreviewContainer">
                            <button type="button" class="remove-img-btn" id="removeMobileBtn" title="إزالة صورة الجوال">
                                <i class="ti ti-x"></i>
                            </button>
                            <img src="" alt="Mobile Preview" id="mobilePreviewImg">
                        </div>
                    </div>
                </div>

                <!-- 3. Link & Destination -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header py-3 bg-transparent border-bottom">
                        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-link text-primary fs-4"></i>
                            <span>وجهة الرابط عند النقر على الشريحة</span>
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <!-- Link Type Selector -->
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-3">
                                <div class="link-type-card selected" data-type="none">
                                    <input type="radio" name="link_type" value="none" id="type_none" class="d-none" checked>
                                    <i class="ti ti-ban fs-5"></i>
                                    <span>بدون رابط</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="link-type-card" data-type="category">
                                    <input type="radio" name="link_type" value="category" id="type_category" class="d-none">
                                    <i class="ti ti-category fs-5"></i>
                                    <span>قسم بالمتجر</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="link-type-card" data-type="product">
                                    <input type="radio" name="link_type" value="product" id="type_product" class="d-none">
                                    <i class="ti ti-shopping-bag fs-5"></i>
                                    <span>منتج محدد</span>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="link-type-card" data-type="custom">
                                    <input type="radio" name="link_type" value="custom" id="type_custom" class="d-none">
                                    <i class="ti ti-world fs-5"></i>
                                    <span>رابط مخصص</span>
                                </div>
                            </div>
                        </div>

                        <!-- Category Selector -->
                        <div class="mb-3 d-none" id="categoryGroup">
                            <label for="category_id" class="form-label fw-semibold">اختر القسم المستهدف</label>
                            <select name="category_id" id="category_id" class="form-select select2">
                                <option value="">-- اختر القسم --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }} ({{ $category->slug }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Product Selector -->
                        <div class="mb-3 d-none" id="productGroup">
                            <label for="product_id" class="form-label fw-semibold">اختر المنتج المستهدف</label>
                            <select name="product_id" id="product_id" class="form-select select2">
                                <option value="">-- اختر المنتج --</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Custom URL Input -->
                        <div class="mb-3 d-none" id="customLinkGroup">
                            <label for="custom_link" class="form-label fw-semibold">أدخل رابط التوجيه (URL)</label>
                            <div class="input-group" dir="ltr">
                                <span class="input-group-text"><i class="ti ti-link"></i></span>
                                <input type="text" name="custom_link" id="custom_link" class="form-control" 
                                       placeholder="https://example.com/page أو /offers" 
                                       value="{{ old('custom_link') }}">
                            </div>
                            <small class="text-muted">يمكنك كتابة رابط خارجي كامل أو رابط داخلي مثل /offers</small>
                        </div>

                        <!-- Link Target -->
                        <div class="form-check form-switch mt-3" id="linkTargetGroup">
                            <input class="form-check-input" type="checkbox" role="switch" name="link_target" value="_blank" id="link_target">
                            <label class="form-check-label fw-semibold" for="link_target">
                                فتح الرابط في نافذة جديدة (علامة تبويب جديدة)
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right / Side Column: Settings & Publish -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 sticky-top" style="top: 90px;">
                    <div class="card-header py-3 bg-transparent border-bottom">
                        <h5 class="card-title mb-0 d-flex align-items-center gap-2">
                            <i class="ti ti-adjustments text-primary fs-4"></i>
                            <span>إعدادات الشريحة</span>
                        </h5>
                    </div>
                    <div class="card-body pt-3">
                        <!-- Active Status Switch -->
                        <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light mb-3">
                            <div>
                                <h6 class="mb-0 fw-bold">حالة التفعيل</h6>
                                <small class="text-muted">عرض الشريحة في المتجر مباشرة</small>
                            </div>
                            <div class="form-check form-switch m-0">
                                <input class="form-check-input fs-4" type="checkbox" role="switch" name="is_active" value="1" id="is_active" checked>
                            </div>
                        </div>

                        <!-- Slide Order -->
                        <div class="mb-3">
                            <label for="item_order" class="form-label fw-semibold">ترتيب الشريحة <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ti ti-sort-ascending-numbers"></i></span>
                                <input type="number" name="item_order" id="item_order" class="form-control" 
                                       value="{{ old('item_order', $nextOrder) }}" min="1" required>
                            </div>
                            <small class="text-muted">الرقم الأقل يظهر أولاً في السلايدر</small>
                        </div>

                        <!-- Slide Alt / Title -->
                        <div class="mb-4">
                            <label for="image_alt" class="form-label fw-semibold">العنوان / النص البديل (Alt)</label>
                            <input type="text" name="image_alt" id="image_alt" class="form-control" 
                                   placeholder="مثال: عروض اليوم الوطني، خصومات الصيف" 
                                   value="{{ old('image_alt') }}">
                            <small class="text-muted">يساعد في محركات البحث وتسهيل التعرف على الشريحة</small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2" id="submitBtn">
                                <i class="ti ti-device-floppy fs-4"></i>
                                <span>حفظ ونشر الشريحة</span>
                            </button>
                            <a href="{{ route('admin.sliders.index') }}" class="btn btn-label-secondary">
                                إلغاء
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // --- Desktop Image Upload & Preview ---
        const imageInput = document.getElementById('imageInput');
        const desktopDropzone = document.getElementById('desktopDropzone');
        const desktopPreviewContainer = document.getElementById('desktopPreviewContainer');
        const desktopPreviewImg = document.getElementById('desktopPreviewImg');
        const removeDesktopBtn = document.getElementById('removeDesktopBtn');

        imageInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    desktopPreviewImg.src = e.target.result;
                    desktopPreviewContainer.style.display = 'block';
                    desktopDropzone.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        removeDesktopBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            imageInput.value = '';
            desktopPreviewImg.src = '';
            desktopPreviewContainer.style.display = 'none';
            desktopDropzone.style.display = 'block';
        });

        // --- Mobile Image Upload & Preview ---
        const mobileImageInput = document.getElementById('mobileImageInput');
        const mobileDropzone = document.getElementById('mobileDropzone');
        const mobilePreviewContainer = document.getElementById('mobilePreviewContainer');
        const mobilePreviewImg = document.getElementById('mobilePreviewImg');
        const removeMobileBtn = document.getElementById('removeMobileBtn');

        mobileImageInput.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    mobilePreviewImg.src = e.target.result;
                    mobilePreviewContainer.style.display = 'block';
                    mobileDropzone.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        removeMobileBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            mobileImageInput.value = '';
            mobilePreviewImg.src = '';
            mobilePreviewContainer.style.display = 'none';
            mobileDropzone.style.display = 'block';
        });

        // Drag & Drop effects
        [desktopDropzone, mobileDropzone].forEach(dropzone => {
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.classList.add('dragover');
                }, false);
            });
            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('dragover');
                }, false);
            });
        });

        desktopDropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files[0]) {
                imageInput.files = files;
                imageInput.dispatchEvent(new Event('change'));
            }
        });

        mobileDropzone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files && files[0]) {
                mobileImageInput.files = files;
                mobileImageInput.dispatchEvent(new Event('change'));
            }
        });

        // --- Link Type Selector ---
        const linkTypeCards = document.querySelectorAll('.link-type-card');
        const categoryGroup = document.getElementById('categoryGroup');
        const productGroup = document.getElementById('productGroup');
        const customLinkGroup = document.getElementById('customLinkGroup');

        function updateLinkType(type) {
            categoryGroup.classList.add('d-none');
            productGroup.classList.add('d-none');
            customLinkGroup.classList.add('d-none');

            if (type === 'category') {
                categoryGroup.classList.remove('d-none');
            } else if (type === 'product') {
                productGroup.classList.remove('d-none');
            } else if (type === 'custom') {
                customLinkGroup.classList.remove('d-none');
            }
        }

        linkTypeCards.forEach(card => {
            card.addEventListener('click', function () {
                linkTypeCards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                const radio = this.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                    updateLinkType(radio.value);
                }
            });
        });

        // Initial link type
        const checkedType = document.querySelector('input[name="link_type"]:checked')?.value || 'none';
        updateLinkType(checkedType);

        // Prevent double submit
        const form = document.getElementById('sliderForm');
        form.addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> جاري الحفظ...';
        });
    });
</script>
@endsection
