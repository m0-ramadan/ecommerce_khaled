@extends('Admin.layout.master')

@section('title', 'إضافة رأي عميل جديد')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
            --star-color: #ffc107;
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
            color: rgba(255, 255, 255, 0.4);
        }

        .form-select option {
            background: var(--dark-card);
            color: #fff;
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

        /* Avatar Upload */
        .avatar-upload-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .avatar-preview-wrapper {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            overflow: hidden;
            border: 4px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .avatar-preview-wrapper:hover {
            border-color: var(--primary-color);
            box-shadow: 0 0 25px rgba(105, 108, 255, 0.3);
        }

        .avatar-preview-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: rgba(255, 255, 255, 0.3);
        }

        .avatar-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .avatar-preview-wrapper:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-overlay i {
            font-size: 30px;
            color: #fff;
        }

        .avatar-actions {
            display: flex;
            gap: 10px;
        }

        /* Star Rating Input */
        .star-rating-input {
            display: flex;
            gap: 8px;
            direction: ltr;
            justify-content: flex-end;
            flex-direction: row-reverse;
        }

        .star-rating-input input {
            display: none;
        }

        .star-rating-input label {
            font-size: 36px;
            color: rgba(255, 255, 255, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .star-rating-input label:hover,
        .star-rating-input label:hover ~ label,
        .star-rating-input input:checked ~ label {
            color: var(--star-color);
            transform: scale(1.1);
        }

        .star-rating-input input:checked ~ label {
            color: var(--star-color);
        }

        .rating-text {
            font-size: 16px;
            font-weight: 600;
            margin-top: 8px;
        }

        .rating-text.excellent {
            color: #28a745;
        }
        .rating-text.good {
            color: #17a2b8;
        }
        .rating-text.average {
            color: #ffc107;
        }
        .rating-text.poor {
            color: #fd7e14;
        }
        .rating-text.bad {
            color: #dc3545;
        }

        /* Preview Card */
        .preview-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .preview-card:hover {
            border-color: var(--primary-color);
            background: rgba(105, 108, 255, 0.03);
        }

        .preview-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 15px;
            border: 3px solid var(--primary-color);
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .preview-avatar i {
            font-size: 35px;
            color: rgba(255, 255, 255, 0.3);
        }

        .preview-stars {
            display: flex;
            justify-content: center;
            gap: 4px;
            margin-bottom: 15px;
            font-size: 22px;
        }

        .preview-stars .star {
            color: rgba(255, 255, 255, 0.2);
        }

        .preview-stars .star.filled {
            color: var(--star-color);
        }

        .preview-review {
            color: rgba(255, 255, 255, 0.8);
            font-style: italic;
            line-height: 1.8;
            margin-bottom: 15px;
            min-height: 60px;
        }

        .preview-name {
            font-weight: 600;
            color: #fff;
            font-size: 18px;
        }

        .preview-city {
            color: rgba(255, 255, 255, 0.5);
            font-size: 14px;
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
            background-color: var(--success-color);
        }

        input:checked+.toggle-slider:before {
            transform: translateX(28px);
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

        /* Character Counter */
        .char-counter {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
            text-align: left;
            margin-top: 5px;
        }

        .char-counter.warning {
            color: var(--warning-color);
        }

        .char-counter.danger {
            color: var(--danger-color);
        }

        /* Action Buttons Fixed */
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

        /* Quick Fill Buttons */
        .quick-fill-section {
            background: rgba(255, 193, 7, 0.05);
            border-radius: 8px;
            padding: 15px;
            border-right: 3px solid var(--warning-color);
        }

        /* Textarea */
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
            line-height: 1.8;
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

            .star-rating-input label {
                font-size: 28px;
            }

            .avatar-preview-wrapper {
                width: 120px;
                height: 120px;
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
                    <a href="{{ route('admin.testimonials.index') }}" class="text-primary">آراء العملاء</a>
                </li>
                <li class="breadcrumb-item active text-white">إضافة رأي جديد</li>
            </ol>
        </nav>

        <!-- Wizard Steps -->
        <div class="wizard-steps">
            <div class="wizard-step active" id="step1">
                <div class="wizard-step-circle">1</div>
                <div class="wizard-step-label">بيانات العميل</div>
            </div>
            <div class="wizard-step" id="step2">
                <div class="wizard-step-circle">2</div>
                <div class="wizard-step-label">التقييم والمراجعة</div>
            </div>
            <div class="wizard-step" id="step3">
                <div class="wizard-step-circle">3</div>
                <div class="wizard-step-label">المعاينة والتأكيد</div>
            </div>
        </div>

        <!-- Quick Guide -->
        <div class="alert-guide">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-lightbulb fa-2x" style="color: var(--primary-color);"></i>
                <div>
                    <h6 class="mb-1">نصائح سريعة لإضافة رأي عميل</h6>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-star me-1" style="color: var(--star-color);"></i> اختر تقييماً مناسباً |
                        <i class="fas fa-pen me-1"></i> اكتب مراجعة حقيقية ومعبرة |
                        <i class="fas fa-image me-1"></i> أضف صورة العميل (اختياري)
                    </p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert"
                style="background: rgba(220,53,69,0.1); border-color: rgba(220,53,69,0.3); color: #dc3545;">
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
        <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data" id="testimonialForm">
            @csrf

            <!-- ============ STEP 1: CUSTOMER DATA ============ -->
            <div class="step-card step-1">
                <div class="step-header">
                    <div class="step-number">1</div>
                    <div>
                        <h5 class="step-title">بيانات العميل</h5>
                        <p class="step-description">أدخل اسم العميل والمدينة والصورة</p>
                    </div>
                </div>

                <div class="row">
                    <!-- Avatar Upload -->
                    <div class="col-md-3 mb-3">
                        <label class="form-label">صورة العميل</label>
                        <div class="avatar-upload-container">
                            <div class="avatar-preview-wrapper" onclick="document.getElementById('avatar').click()">
                                <div class="avatar-placeholder" id="avatarPlaceholder">
                                    <i class="fas fa-user"></i>
                                </div>
                                <img id="avatarPreview" src="" alt="صورة العميل" style="display: none;">
                                <div class="avatar-overlay">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                            <input type="file" id="avatar" name="avatar" accept="image/*" style="display: none;"
                                onchange="previewAvatar(this)">
                            <div class="avatar-actions">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="document.getElementById('avatar').click()">
                                    <i class="fas fa-upload me-1"></i> رفع صورة
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAvatar()" id="removeAvatarBtn" style="display: none;">
                                    <i class="fas fa-trash me-1"></i> حذف
                                </button>
                            </div>
                            <small class="text-muted">الحجم الموصى به: 200×200 بكسل</small>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label required">اسم العميل</label>
                                <input type="text" class="form-control form-control-lg" id="name" name="name"
                                    value="{{ old('name') }}" placeholder="أدخل اسم العميل"
                                    oninput="updatePreview()" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">المدينة</label>
                                <input type="text" class="form-control form-control-lg" id="city" name="city"
                                    value="{{ old('city') }}" placeholder="مثال: الرياض، جدة، القاهرة..."
                                    oninput="updatePreview()">
                            </div>
                        </div>

                        <!-- Quick Fill Names -->
                        <div class="quick-fill-section mt-3">
                            <small class="text-muted d-block mb-2">
                                <i class="fas fa-bolt me-1" style="color: var(--warning-color);"></i>
                                أسماء مقترحة (انقر للإضافة):
                            </small>
                            <div class="d-flex flex-wrap gap-2">
                                @php
                                    $suggestedNames = [
                                        'أحمد محمد' => 'الرياض',
                                        'سارة عبدالله' => 'جدة',
                                        'خالد العمري' => 'الدمام',
                                        'نورة القحطاني' => 'مكة',
                                        'فيصل الشمري' => 'المدينة',
                                    ];
                                @endphp
                                @foreach ($suggestedNames as $name => $city)
                                    <button type="button" class="btn btn-outline-warning btn-sm"
                                        onclick="fillCustomer('{{ $name }}', '{{ $city }}')">
                                        {{ $name }} - {{ $city }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <div></div>
                    <button type="button" class="btn btn-primary next-step" data-next="2">
                        التالي <i class="fas fa-arrow-left ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ============ STEP 2: RATING & REVIEW ============ -->
            <div class="step-card step-2" style="display: none;">
                <div class="step-header">
                    <div class="step-number">2</div>
                    <div>
                        <h5 class="step-title">التقييم والمراجعة</h5>
                        <p class="step-description">حدد التقييم واكتب المراجعة</p>
                    </div>
                </div>

                <!-- Star Rating -->
                <div class="mb-4">
                    <label class="form-label required">التقييم</label>
                    <div class="star-rating-input">
                        @for ($i = 5; $i >= 1; $i--)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}"
                                {{ old('rating') == $i ? 'checked' : '' }} onchange="updateRating(this.value)">
                            <label for="star{{ $i }}" title="{{ $i }} نجوم">
                                <i class="fas fa-star"></i>
                            </label>
                        @endfor
                    </div>
                    <div class="rating-text" id="ratingText">
                        @if (old('rating'))
                            {{ getRatingText(old('rating')) }}
                        @endif
                    </div>
                </div>

                <!-- Review -->
                <div class="mb-4">
                    <label for="review" class="form-label required">المراجعة</label>
                    <textarea class="form-control" id="review" name="review" rows="5"
                        placeholder="اكتب مراجعة العميل هنا..." oninput="updatePreview(); updateCharCount()"
                        maxlength="500" required>{{ old('review') }}</textarea>
                    <div class="char-counter" id="charCounter">
                        <span id="charCount">0</span>/500 حرف
                    </div>
                </div>

                <!-- Quick Review Templates -->
                <div class="quick-fill-section mt-3">
                    <small class="text-muted d-block mb-2">
                        <i class="fas fa-magic me-1" style="color: var(--warning-color);"></i>
                        قوالب مراجعات سريعة:
                    </small>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="fillReviewTemplate('excellent')">
                            <i class="fas fa-star me-1"></i> ممتاز
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="fillReviewTemplate('good')">
                            <i class="fas fa-star me-1"></i> جيد جداً
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="fillReviewTemplate('average')">
                            <i class="fas fa-star me-1"></i> متوسط
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm" onclick="fillReviewTemplate('service')">
                            <i class="fas fa-headset me-1"></i> خدمة العملاء
                        </button>
                    </div>
                </div>

                <!-- Status Toggle -->
                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <label for="sort_order" class="form-label">ترتيب العرض</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order"
                            value="{{ old('sort_order', $nextSortOrder ?? 0) }}" min="0">
                        <small class="text-muted">الأقل يظهر أولاً</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الحالة</label>
                        <div class="toggle-container mt-2">
                            <label class="toggle-switch">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label" id="statusLabel">
                                {{ old('is_active', '1') == '1' ? 'نشط' : 'غير نشط' }}
                            </span>
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

            <!-- ============ STEP 3: PREVIEW & CONFIRM ============ -->
            <div class="step-card step-3" style="display: none;">
                <div class="step-header">
                    <div class="step-number">3</div>
                    <div>
                        <h5 class="step-title">المعاينة والتأكيد</h5>
                        <p class="step-description">راجع البيانات قبل الحفظ</p>
                    </div>
                </div>

                <!-- Live Preview Card -->
                <div class="mb-4">
                    <h6 class="mb-3">
                        <i class="fas fa-eye me-2" style="color: var(--primary-color);"></i>
                        معاينة مباشرة - هكذا سيظهر الرأي للزوار
                    </h6>
                    <div class="col-md-6 mx-auto">
                        <div class="preview-card">
                            <div class="preview-avatar" id="previewAvatarContainer">
                                <i class="fas fa-user" id="previewAvatarIcon"></i>
                                <img id="previewAvatarImg" src="" alt="" style="display: none;">
                            </div>
                            <div class="preview-stars" id="previewStars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star"></i>
                                @endfor
                            </div>
                            <div class="preview-review" id="previewReview">
                                المراجعة تظهر هنا...
                            </div>
                            <div class="preview-name" id="previewName">اسم العميل</div>
                            <div class="preview-city" id="previewCity">المدينة</div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="summary-card">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>ملخص الرأي</h6>

                    <div class="summary-row">
                        <span class="summary-label">اسم العميل:</span>
                        <span class="summary-value" id="summaryName">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">المدينة:</span>
                        <span class="summary-value" id="summaryCity">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">التقييم:</span>
                        <span class="summary-value" id="summaryRating">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">عدد كلمات المراجعة:</span>
                        <span class="summary-value" id="summaryWordCount">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">الحالة:</span>
                        <span class="summary-value" id="summaryStatus">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">ترتيب العرض:</span>
                        <span class="summary-value" id="summaryOrder">-</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                        <i class="fas fa-arrow-right me-1"></i> السابق
                    </button>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-1"></i> حفظ الرأي
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="saveAsDraft()">
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
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // ============================================
        // ⭐ STEP NAVIGATION
        // ============================================
        $('.next-step').click(function() {
            const nextStep = $(this).data('next');
            const currentStep = $(this).closest('.step-card');

            if (validateStep(currentStep)) {
                currentStep.hide();
                $(`.step-${nextStep}`).show();
                updateWizardSteps(nextStep);

                if (nextStep == 3) {
                    updateSummary();
                    updatePreview();
                }
            }
        });

        $('.prev-step').click(function() {
            const prevStep = $(this).data('prev');
            $(this).closest('.step-card').hide();
            $(`.step-${prevStep}`).show();
            updateWizardSteps(prevStep);
        });

        function updateWizardSteps(activeStep) {
            $('.wizard-step').removeClass('active completed');

            for (let i = 1; i <= 3; i++) {
                if (i < activeStep) {
                    $('#step' + i).addClass('completed');
                } else if (i == activeStep) {
                    $('#step' + i).addClass('active');
                }
            }
        }

        // ============================================
        // ⭐ VALIDATION
        // ============================================
        function validateStep(step) {
            let isValid = true;

            step.find('input[required], select[required], textarea[required]').each(function() {
                const value = $(this).val();

                // For radio buttons (rating)
                if ($(this).attr('type') === 'radio') {
                    const name = $(this).attr('name');
                    if (!$(`input[name="${name}"]:checked`).length) {
                        isValid = false;
                        if (!$('#rating-error').length) {
                            $('.star-rating-input').after(
                                '<div id="rating-error" class="invalid-feedback text-danger d-block">يرجى اختيار التقييم</div>'
                                );
                        }
                    } else {
                        $('#rating-error').remove();
                    }
                    return;
                }

                if (!value || !value.toString().trim()) {
                    $(this).addClass('is-invalid');
                    isValid = false;

                    if (!$(this).next('.invalid-feedback').length) {
                        $(this).after(
                            '<div class="invalid-feedback text-danger">هذا الحقل مطلوب</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            return isValid;
        }

        function validateAllSteps() {
            for (let i = 1; i <= 2; i++) {
                const step = $(`.step-${i}`);
                if (!validateStep(step)) {
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
            return true;
        }

        // Form submission
        $('#testimonialForm').on('submit', function(e) {
            if (!validateAllSteps()) {
                e.preventDefault();
                return false;
            }
        });

        // ============================================
        // ⭐ AVATAR UPLOAD
        // ============================================
        function previewAvatar(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatarPreview').attr('src', e.target.result).show();
                    $('#avatarPlaceholder').hide();
                    $('#removeAvatarBtn').show();
                    updatePreview();
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeAvatar() {
            $('#avatar').val('');
            $('#avatarPreview').attr('src', '').hide();
            $('#avatarPlaceholder').show();
            $('#removeAvatarBtn').hide();
            updatePreview();
        }

        // ============================================
        // ⭐ STAR RATING
        // ============================================
        function updateRating(value) {
            const ratingTexts = {
                '5': 'ممتاز! 🌟',
                '4': 'جيد جداً 👍',
                '3': 'متوسط 🙂',
                '2': 'مقبول 😐',
                '1': 'ضعيف 😞'
            };

            const ratingClasses = {
                '5': 'excellent',
                '4': 'good',
                '3': 'average',
                '2': 'poor',
                '1': 'bad'
            };

            $('#ratingText').text(ratingTexts[value] || '')
                .removeClass('excellent good average poor bad')
                .addClass(ratingClasses[value] || '');

            updatePreview();
        }

        // ============================================
        // ⭐ CHARACTER COUNTER
        // ============================================
        function updateCharCount() {
            const maxLength = 500;
            const currentLength = $('#review').val().length;
            const counter = $('#charCount');
            const charCounter = $('#charCounter');

            counter.text(currentLength);

            charCounter.removeClass('warning danger');
            if (currentLength >= maxLength * 0.9) {
                charCounter.addClass('danger');
            } else if (currentLength >= maxLength * 0.7) {
                charCounter.addClass('warning');
            }

            updatePreview();
        }

        // ============================================
        // ⭐ LIVE PREVIEW
        // ============================================
        function updatePreview() {
            // Name
            const name = $('#name').val() || 'اسم العميل';
            $('#previewName').text(name);

            // City
            const city = $('#city').val() || 'المدينة';
            $('#previewCity').text(city);

            // Avatar
            const avatarSrc = $('#avatarPreview').attr('src');
            if (avatarSrc) {
                $('#previewAvatarImg').attr('src', avatarSrc).show();
                $('#previewAvatarIcon').hide();
            } else {
                $('#previewAvatarImg').hide();
                $('#previewAvatarIcon').show();
            }

            // Stars
            const rating = $('input[name="rating"]:checked').val() || 0;
            $('#previewStars .star').each(function(index) {
                if (index < rating) {
                    $(this).addClass('filled');
                } else {
                    $(this).removeClass('filled');
                }
            });

            // Review
            const review = $('#review').val() || 'المراجعة تظهر هنا...';
            $('#previewReview').text('"' + review + '"');
        }

        // ============================================
        // ⭐ SUMMARY
        // ============================================
        function updateSummary() {
            $('#summaryName').text($('#name').val() || '-');
            $('#summaryCity').text($('#city').val() || '-');

            const rating = $('input[name="rating"]:checked').val();
            $('#summaryRating').text(rating ? rating + '/5 ⭐' : '-');

            const reviewText = $('#review').val() || '';
            const wordCount = reviewText.trim() ? reviewText.trim().split(/\s+/).length : 0;
            $('#summaryWordCount').text(wordCount + ' كلمة');

            const isActive = $('#is_active').is(':checked');
            $('#summaryStatus').text(isActive ? 'نشط' : 'غير نشط');

            $('#summaryOrder').text($('#sort_order').val() || '0');
        }

        // ============================================
        // ⭐ QUICK FILL FUNCTIONS
        // ============================================
        function fillCustomer(name, city) {
            $('#name').val(name);
            $('#city').val(city);
            updatePreview();
        }

        function fillReviewTemplate(type) {
            const templates = {
                'excellent': {
                    rating: 5,
                    review: 'تجربة رائعة! خدمة ممتازة ومنتج عالي الجودة. أنصح الجميع بالتعامل مع هذا المتجر. سأكرر التجربة بالتأكيد.'
                },
                'good': {
                    rating: 4,
                    review: 'خدمة جيدة جداً وتوصيل سريع. المنتج مطابق للوصف. شكراً لكم على الاحترافية في التعامل.'
                },
                'average': {
                    rating: 3,
                    review: 'تجربة متوسطة. المنتج جيد ولكن التوصيل تأخر قليلاً. أتمنى تحسين سرعة التوصيل في المستقبل.'
                },
                'service': {
                    rating: 5,
                    review: 'خدمة عملاء رائعة! فريق الدعم متعاون جداً وسريع في الرد على الاستفسارات. شكر خاص لفريق الخدمة.'
                }
            };

            if (templates[type]) {
                // Set rating
                $(`#star${templates[type].rating}`).prop('checked', true);
                updateRating(templates[type].rating);

                // Set review
                $('#review').val(templates[type].review);
                updateCharCount();
                updatePreview();

                // Scroll to review textarea
                $('html, body').animate({
                    scrollTop: $('#review').offset().top - 100
                }, 500);
            }
        }

        function saveAsDraft() {
            $('#is_active').prop('checked', false);
            $('#statusLabel').text('غير نشط');

            if (validateAllSteps()) {
                $('#testimonialForm').submit();
            }
        }

        // ============================================
        // ⭐ STATUS TOGGLE LABEL
        // ============================================
        $('#is_active').change(function() {
            $('#statusLabel').text($(this).is(':checked') ? 'نشط' : 'غير نشط');
        });

        // ============================================
        // ⭐ INITIAL SETUP
        // ============================================
        $(document).ready(function() {
            // Set initial rating if exists
            const initialRating = $('input[name="rating"]:checked').val();
            if (initialRating) {
                updateRating(initialRating);
            }

            // Set initial char count
            updateCharCount();

            // Set initial preview
            updatePreview();
        });
    </script>
@endsection