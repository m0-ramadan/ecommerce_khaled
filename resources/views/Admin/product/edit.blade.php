@extends('Admin.layout.master')

@section('title', 'تعديل المنتج: ' . $product->name)

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css" />
    <style>
        :root {
            --primary-color: #696cff;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --info-color: #3498db;
            --dark-color: #2c3e50;
            --light-color: #6d9dce;
            --border-color: #435c75;
            --text-muted: #7f8c8d;
        }

        body {
            font-family: "Cairo", sans-serif !important;
        }

        /* تحسين تصميم البطاقات */
        .step-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: 5px solid var(--primary-color);
            transition: all 0.3s ease;
        }

        .step-card:hover {
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.12);
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 2px solid var(--light-color);
        }

        .step-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 22px;
            margin-left: 20px;
            box-shadow: 0 4px 10px rgba(105, 108, 255, 0.3);
        }

        .step-title {
            font-size: 22px;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .step-description {
            color: var(--text-muted);
            font-size: 15px;
        }

        /* تحسين تصميم الحقول */
        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
            font-size: 15px;
        }

        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid var(--border-color);
            padding: 12px 15px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        /* تحسين تصميم الأزرار */
        .btn {
            border-radius: 10px;
            padding: 10px 25px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: #5a5dd8;
            border-color: #5a5dd8;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(105, 108, 255, 0.3);
        }

        .btn-outline-secondary {
            border-color: var(--border-color);
            color: var(--text-muted);
        }

        .btn-outline-secondary:hover {
            background: var(--light-color);
            border-color: var(--text-muted);
            transform: translateY(-2px);
        }

        /* تحسين تصميم الأقسام الديناميكية */
        .dynamic-section {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 20px;
            border: 2px solid var(--light-color);
            transition: all 0.3s ease;
        }

        .dynamic-section:hover {
            border-color: var(--primary-color);
            box-shadow: 0 4px 15px rgba(105, 108, 255, 0.1);
        }

        .dynamic-section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--light-color);
        }

        .dynamic-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dynamic-section-title i {
            color: var(--primary-color);
        }

        .drag-handle {
            cursor: move;
            padding: 8px;
            color: var(--text-muted);
            transition: all 0.3s ease;
        }

        .drag-handle:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }

        /* تحسين تصميم الصور */
        .image-upload-container {
            border: 3px dashed var(--border-color);
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            background: var(--light-color);
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
            background: rgba(105, 108, 255, 0.05);
            transform: translateY(-2px);
        }

        .image-upload-container i {
            font-size: 60px;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .image-preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .image-preview-item {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            height: 180px;
            transition: all 0.3s ease;
        }

        .image-preview-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
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
            background: rgba(0, 0, 0, 0.8);
            padding: 15px;
            display: flex;
            justify-content: center;
            gap: 10px;
            transform: translateY(100%);
            transition: transform 0.3s ease;
        }

        .image-preview-item:hover .image-actions {
            transform: translateY(0);
        }

        .image-actions .btn {
            width: 35px;
            height: 35px;
            padding: 0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .primary-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--success-color);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* تحسين تصميم خطوات التوجيه */
        .wizard-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            position: relative;
            padding: 0 20px;
        }

        .wizard-steps::before {
            content: '';
            position: absolute;
            top: 25px;
            right: 50px;
            left: 50px;
            height: 3px;
            background: var(--border-color);
            z-index: 1;
        }

        .wizard-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .wizard-step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-weight: bold;
            font-size: 20px;
            border: 4px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .wizard-step.active .wizard-step-circle {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(105, 108, 255, 0.3);
        }

        .wizard-step.completed .wizard-step-circle {
            background: var(--success-color);
            color: white;
            border-color: var(--success-color);
        }

        .wizard-step-label {
            font-size: 15px;
            color: var(--text-muted);
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .wizard-step.active .wizard-step-label {
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        /* تحسين تصميم الألوان */
        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .color-item {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 4px solid white;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
            cursor: pointer;
            position: relative;
            transition: all 0.3s ease;
        }

        .color-item:hover {
            transform: scale(1.15);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .color-item.selected {
            border-color: var(--primary-color);
            transform: scale(1.15);
        }

        .color-item.selected::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-weight: bold;
            font-size: 18px;
            text-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* تحسين تصميم التنبيهات */
        .alert-guide {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
        }

        .alert-guide h6 {
            color: white;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: 600;
        }

        .alert-guide ul {
            margin-bottom: 0;
            padding-left: 20px;
        }

        .alert-guide li {
            margin-bottom: 8px;
            font-size: 15px;
            opacity: 0.9;
        }

        .alert-guide li i {
            margin-left: 8px;
        }

        /* تحسين تصميم معاينة المنتج */
        .preview-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 2px solid var(--light-color);
            transition: all 0.3s ease;
        }

        .preview-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .preview-image {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            object-fit: cover;
            border: 3px solid var(--light-color);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .preview-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .preview-price {
            font-size: 28px;
            font-weight: 800;
            color: var(--success-color);
        }

        .preview-old-price {
            font-size: 18px;
            color: var(--text-muted);
            text-decoration: line-through;
        }

        /* تحسين التصميم للجداول */
        .table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 15px;
            font-weight: 600;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        /* تحسين التصميم للتسميات */
        .badge {
            padding: 6px 12px;
            font-weight: 600;
            border-radius: 8px;
        }

        .badge.bg-success {
            background: var(--success-color) !important;
        }

        .badge.bg-danger {
            background: var(--danger-color) !important;
        }

        .badge.bg-warning {
            background: var(--warning-color) !important;
            color: white;
        }

        /* تحسين التصميم للأيقونات */
        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(105, 108, 255, 0.1);
            color: var(--primary-color);
            font-size: 18px;
        }

        /* تحسين التصميم للسويتشر */
        .toggle-switch {
            position: relative;
            width: 70px;
            height: 34px;
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
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .toggle-slider {
            background-color: var(--success-color);
        }

        input:checked + .toggle-slider:before {
            transform: translateX(36px);
        }

        /* تحسين التصميم للترقيم */
        .counter-badge {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
            margin-left: 10px;
        }

        /* تحسين التصميم للموديل */
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #5a5dd8 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 20px 30px;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        /* تحسين التصميم للشريط الجانبي */
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 20px;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb-item.active {
            color: var(--text-muted);
        }

        /* تحسين التصميم للتعليقات */
        .form-text {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        /* تحسين التصميم للسحب والإفلات */
        .sortable-ghost {
            opacity: 0.4;
            background: var(--light-color);
        }

        .sortable-chosen {
            box-shadow: 0 8px 30px rgba(105, 108, 255, 0.2);
            transform: scale(1.02);
        }

        .sortable-drag {
            cursor: grabbing !important;
        }

        /* تحسين التصميم للكمبيوتر اللوحي والموبايل */
        @media (max-width: 768px) {
            .wizard-steps {
                flex-direction: column;
                gap: 20px;
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
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .step-card {
                padding: 20px;
            }

            .step-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .step-number {
                margin-left: 0;
                width: 40px;
                height: 40px;
                font-size: 18px;
            }

            .image-preview-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 15px;
            }

            .image-preview-item {
                height: 140px;
            }

            .color-grid {
                grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
                gap: 12px;
            }

            .color-item {
                width: 40px;
                height: 40px;
            }
        }

        /* تحسين التصميم للتحميل */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid var(--light-color);
            border-top-color: var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* تحسين التصميم للرسائل */
        .message {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9998;
            max-width: 400px;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.5s ease;
        }

        .message.show {
            opacity: 1;
            transform: translateX(0);
        }

        /* تحسين التصميم للأقسام المنقسمة */
        .split-section {
            background: white;
            border-radius: 12px;
            padding: 0;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        .split-section-header {
            padding: 20px;
            background: var(--light-color);
            border-bottom: 2px solid var(--border-color);
        }

        .split-section-body {
            padding: 25px;
        }

        /* تحسين التصميم للعناوين */
        h1, h2, h3, h4, h5, h6 {
            color: var(--dark-color);
            font-weight: 700;
        }

        h5 {
            margin-bottom: 20px;
        }

        /* تحسين التصميم للحقول المطلوبة */
        .required::after {
            content: " *";
            color: var(--danger-color);
            font-weight: bold;
        }

        /* تحسين التصميم للإختيارات */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .form-check-label {
            cursor: pointer;
        }

        /* تحسين التصميم للبطاقات */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: white;
            border-bottom: 2px solid var(--light-color);
            padding: 25px;
            border-radius: 15px 15px 0 0 !important;
        }

        .card-body {
            padding: 30px;
        }

        /* تحسين التصميم للأقسام المخفية */
        .collapse-section {
            margin-bottom: 20px;
        }

        .collapse-toggle {
            width: 100%;
            text-align: right;
            background: var(--light-color);
            border: none;
            padding: 15px 20px;
            border-radius: 10px;
            color: var(--dark-color);
            font-weight: 600;
            font-size: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .collapse-toggle:hover {
            background: #e9ecef;
        }

        .collapse-toggle i {
            transition: transform 0.3s ease;
        }

        .collapse-toggle.collapsed i {
            transform: rotate(-90deg);
        }

        .collapse-content {
            padding: 20px;
            background: white;
            border-radius: 0 0 10px 10px;
            margin-top: -5px;
            border: 2px solid var(--light-color);
            border-top: none;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" bis_skin_checked="1">
        <!-- مسار التنقل -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}" class="text-decoration-none">
                        <i class="fas fa-home me-1"></i> الرئيسية
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                        <i class="fas fa-box me-1"></i> المنتجات
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.products.show', $product->id) }}" class="text-decoration-none">
                        {{ Str::limit($product->name, 25) }}
                    </a>
                </li>
                <li class="breadcrumb-item active">
                    <i class="fas fa-edit me-1"></i> تعديل
                </li>
            </ol>
        </nav>

        <div class="row" bis_skin_checked="1">
            <div class="col-12" bis_skin_checked="1">
                <div class="card mb-4" bis_skin_checked="1">
                    <div class="card-header d-flex justify-content-between align-items-center" bis_skin_checked="1">
                        <div bis_skin_checked="1">
                            <h5 class="mb-1">
                                <i class="fas fa-edit me-2"></i> تعديل المنتج
                            </h5>
                            <div class="d-flex align-items-center gap-3 mt-2" bis_skin_checked="1">
                                <small class="badge bg-primary">
                                    <i class="fas fa-hashtag me-1"></i> #{{ $product->id }}
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i> 
                                    آخر تحديث: {{ $product->updated_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        <div class="btn-group" role="group" bis_skin_checked="1">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-info">
                                <i class="fas fa-eye me-1"></i> عرض
                            </a>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-right me-1"></i> رجوع
                            </a>
                        </div>
                    </div>

                    <div class="card-body" bis_skin_checked="1">
                        <!-- دليل إرشادي -->
                        <div class="alert-guide" bis_skin_checked="1">
                            <h6><i class="fas fa-lightbulb me-2"></i> نصائح سريعة للتعديل:</h6>
                            <ul class="mb-0">
                                <li><i class="fas fa-check-circle me-1"></i> يمكنك تحديث أي معلومات عن المنتج</li>
                                <li><i class="fas fa-images me-1"></i> يمكنك إضافة أو إزالة الصور بسهولة</li>
                                <li><i class="fas fa-palette me-1"></i> يمكنك تحديث الألوان والمواد والخيارات</li>
                                <li><i class="fas fa-calculator me-1"></i> تأكد من تحديث الأسعار والمخزون بدقة</li>
                                <li><i class="fas fa-save me-1"></i> احفظ التغييرات قبل الانتقال إلى قسم آخر</li>
                            </ul>
                        </div>

                        <!-- رسائل التنبيه -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                <div>{{ session('success') }}</div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <div>
                                        <h6 class="mb-1">حدثت الأخطاء التالية:</h6>
                                        <ul class="mb-0 ps-3">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- معاينة المنتج -->
                        <div class="preview-card mb-4" bis_skin_checked="1">
                            <div class="row align-items-center" bis_skin_checked="1">
                                <div class="col-auto" bis_skin_checked="1">
                                    <img src="{{ $product->primaryImage ? get_user_image($product->primaryImage->path) : 'https://via.placeholder.com/120x120?text=No+Image' }}"
                                         alt="{{ $product->name }}" 
                                         class="preview-image shadow-sm">
                                </div>
                                <div class="col" bis_skin_checked="1">
                                    <h5 class="mb-3">{{ $product->name }}</h5>
                                    <div class="mb-3" bis_skin_checked="1">
                                        <span class="preview-price">
                                            {{ number_format($product->final_price, 2) }} ج.م
                                        </span>
                                        @if ($product->has_discount && $product->price > $product->final_price)
                                            <span class="preview-old-price ms-3">
                                                {{ number_format($product->price, 2) }} ج.م
                                            </span>
                                            <span class="badge bg-success ms-2">
                                                <i class="fas fa-tag me-1"></i> خصم {{ $product->discount->discount_value }}{{ $product->discount->discount_type === 'percentage' ? '%' : ' ج.م' }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-wrap gap-4" bis_skin_checked="1">
                                        <div class="d-flex align-items-center" bis_skin_checked="1">
                                            <span class="icon-box me-2">
                                                <i class="fas fa-box"></i>
                                            </span>
                                            <div>
                                                <small class="text-muted d-block">المخزون</small>
                                                <strong>{{ $product->stock }}</strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center" bis_skin_checked="1">
                                            <span class="icon-box me-2">
                                                <i class="fas fa-folder"></i>
                                            </span>
                                            <div>
                                                <small class="text-muted d-block">القسم</small>
                                                <strong>{{ $product->category->name ?? 'غير مصنف' }}</strong>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center" bis_skin_checked="1">
                                            <span class="icon-box me-2">
                                                <i class="fas fa-chart-line"></i>
                                            </span>
                                            <div>
                                                <small class="text-muted d-block">الحالة</small>
                                                @if ($product->status_id == 1)
                                                    <span class="badge bg-success">نشط</span>
                                                @elseif($product->status_id == 2)
                                                    <span class="badge bg-danger">غير نشط</span>
                                                @else
                                                    <span class="badge bg-warning">مسودة</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if($product->average_rating > 0)
                                        <div class="d-flex align-items-center" bis_skin_checked="1">
                                            <span class="icon-box me-2">
                                                <i class="fas fa-star"></i>
                                            </span>
                                            <div>
                                                <small class="text-muted d-block">التقييم</small>
                                                <strong>{{ number_format($product->average_rating, 1) }}/5</strong>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- خطوات التعديل -->
                        <div class="wizard-steps" bis_skin_checked="1">
                            <div class="wizard-step active" id="step1" data-step="1">
                                <div class="wizard-step-circle">1</div>
                                <div class="wizard-step-label">المعلومات الأساسية</div>
                            </div>
                            <div class="wizard-step" id="step2" data-step="2">
                                <div class="wizard-step-circle">2</div>
                                <div class="wizard-step-label">الصور والتسعير</div>
                            </div>
                            <div class="wizard-step" id="step3" data-step="3">
                                <div class="wizard-step-circle">3</div>
                                <div class="wizard-step-label">المواصفات</div>
                            </div>
                            <div class="wizard-step" id="step4" data-step="4">
                                <div class="wizard-step-circle">4</div>
                                <div class="wizard-step-label">خيارات إضافية</div>
                            </div>
                        </div>

                        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" id="editProductForm">
                            @csrf
                            @method('POST')

                            <!-- الخطوة 1: المعلومات الأساسية -->
                            <div class="step-card step-1" bis_skin_checked="1">
                                <div class="step-header" bis_skin_checked="1">
                                    <div class="step-number" bis_skin_checked="1">1</div>
                                    <div bis_skin_checked="1">
                                        <h5 class="step-title">المعلومات الأساسية</h5>
                                        <p class="step-description">تحديث المعلومات الأساسية والوصف للمنتج</p>
                                    </div>
                                </div>

                                <div class="row" bis_skin_checked="1">
                                    <div class="col-lg-8 mb-4" bis_skin_checked="1">
                                        <label for="name" class="form-label required">
                                            <i class="fas fa-tag me-1"></i> اسم المنتج
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{ old('name', $product->name) }}" 
                                               placeholder="أدخل اسم المنتج بالكامل"
                                               required>
                                        <div class="form-text">اسم واضح ومعبر عن المنتج للعملاء</div>
                                    </div>

                                    <div class="col-lg-4 mb-4" bis_skin_checked="1">
                                        <label for="category_id" class="form-label required">
                                            <i class="fas fa-folder me-1"></i> القسم
                                        </label>
                                        <div class="input-group" bis_skin_checked="1">
                                            <select class="form-control select2" id="category_id" name="category_id" required>
                                                <option value="">اختر القسم</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                        @if (!$category->isParent())
                                                            (تابع لـ: {{ $category->parent->name ?? '' }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="button" class="btn btn-outline-primary" onclick="openQuickAddModal('category')">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="form-text">اختر القسم المناسب للمنتج</div>
                                    </div>

                                    <div class="col-12 mb-4" bis_skin_checked="1">
                                        <label for="description" class="form-label">
                                            <i class="fas fa-align-left me-1"></i> الوصف التفصيلي
                                        </label>
                                        <textarea class="form-control summernote" id="description" name="description" rows="6"
                                                  placeholder="أدخل وصفًا تفصيليًا للمنتج">{{ old('description', $product->description) }}</textarea>
                                        <div class="form-text">يمكنك إضافة صور وتنسيقات مختلفة في الوصف</div>
                                    </div>

                                    <div class="col-md-6 mb-4" bis_skin_checked="1">
                                        <label for="status_id" class="form-label required">
                                            <i class="fas fa-toggle-on me-1"></i> حالة المنتج
                                        </label>
                                        <select class="form-select" id="status_id" name="status_id" required>
                                            <option value="1" {{ old('status_id', $product->status_id) == 1 ? 'selected' : '' }}>
                                                <i class="fas fa-check-circle me-1"></i> نشط
                                            </option>
                                            <option value="2" {{ old('status_id', $product->status_id) == 2 ? 'selected' : '' }}>
                                                <i class="fas fa-times-circle me-1"></i> غير نشط
                                            </option>
                                            <option value="3" {{ old('status_id', $product->status_id) == 3 ? 'selected' : '' }}>
                                                <i class="fas fa-pause-circle me-1"></i> قيد المراجعة
                                            </option>
                                        </select>
                                        <div class="form-text">تحديد حالة ظهور المنتج في المتجر</div>
                                    </div>

                                    <div class="col-md-6 mb-4" bis_skin_checked="1">
                                        <label for="stock" class="form-label required">
                                            <i class="fas fa-boxes me-1"></i> الكمية المتاحة
                                        </label>
                                        <input type="number" class="form-control" id="stock" name="stock"
                                               value="{{ old('stock', $product->stock) }}" 
                                               min="0" 
                                               placeholder="أدخل كمية المخزون"
                                               required>
                                        @if ($product->stock < 10)
                                            <div class="alert alert-warning mt-2 d-flex align-items-center" bis_skin_checked="1">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                <div>
                                                    <strong>تحذير!</strong> المخزون منخفض. نوصي بإضافة المزيد من الكمية.
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-text">الكمية المتاحة للبيع حالياً</div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4" bis_skin_checked="1">
                                    <div></div>
                                    <button type="button" class="btn btn-primary next-step" data-next="2">
                                        التالي <i class="fas fa-arrow-left ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- الخطوة 2: الصور والتسعير -->
                            <div class="step-card step-2" style="display: none;" bis_skin_checked="1">
                                <div class="step-header" bis_skin_checked="1">
                                    <div class="step-number" bis_skin_checked="1">2</div>
                                    <div bis_skin_checked="1">
                                        <h5 class="step-title">الصور والتسعير</h5>
                                        <p class="step-description">إدارة صور المنتج وتحديث الأسعار</p>
                                    </div>
                                </div>

                                <!-- قسم الصور -->
                                <div class="dynamic-section mb-5" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-images"></i> إدارة الصور
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="showImageTips()">
                                            <i class="fas fa-info-circle me-1"></i> نصائح
                                        </button>
                                    </div>

                                    <!-- الصورة الرئيسية -->
                                    <div class="mb-4" bis_skin_checked="1">
                                        <h6 class="mb-3">
                                            <i class="fas fa-star me-1 text-warning"></i> الصورة الرئيسية
                                        </h6>
                                        
                                        @if ($product->image)
                                            <div class="mb-4">
                                                <p class="text-muted mb-3">الصورة الرئيسية الحالية:</p>
                                                <div class="image-preview-grid" id="currentMainImageContainer">
                                                    <div class="image-preview-item">
                                                        <span class="primary-badge">
                                                            <i class="fas fa-crown me-1"></i> رئيسية
                                                        </span>
                                                        <img src="{{ get_product_image($product->image) }}" 
                                                             alt="الصورة الرئيسية الحالية"
                                                             class="img-fluid">
                                                        <div class="image-actions">
                                                            <button type="button" class="btn btn-info btn-sm" 
                                                                    onclick="viewImage('{{ get_product_image($product->image) }}')">
                                                                <i class="fas fa-expand"></i>
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                    onclick="removeCurrentMainImage()">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="alert alert-warning mb-4">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                لا توجد صورة رئيسية للمنتج
                                            </div>
                                        @endif

                                        <!-- رفع صورة رئيسية جديدة -->
                                        <div class="mb-4">
                                            <p class="text-muted mb-3">تغيير الصورة الرئيسية:</p>
                                            <div class="image-upload-container" onclick="document.getElementById('image').click()">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <h6 class="mt-3 mb-2">انقر لرفع صورة جديدة</h6>
                                                <p class="text-muted mb-0">الحجم الموصى به: 800×800 بكسل</p>
                                                <small class="text-muted">الدعم: JPG, PNG, GIF - الحد الأقصى: 5MB</small>
                                            </div>
                                            <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                                            <input type="hidden" id="remove_main_image" name="remove_main_image" value="0">
                                        </div>

                                        <!-- معاينة الصورة الجديدة -->
                                        <div id="newMainImagePreview" class="mt-4" style="display: none;">
                                            <p class="text-muted mb-3">الصورة الجديدة المختارة:</p>
                                            <div class="image-preview-grid">
                                                <div class="image-preview-item">
                                                    <span class="primary-badge" style="background: #2ecc71;">
                                                        <i class="fas fa-plus me-1"></i> جديدة
                                                    </span>
                                                    <img id="newMainImagePreviewImg" src="" alt="الصورة الرئيسية الجديدة">
                                                    <div class="image-actions">
                                                        <button type="button" class="btn btn-info btn-sm" onclick="viewNewMainImage()">
                                                            <i class="fas fa-expand"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="removeNewMainImage()">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="alert alert-info mt-2">
                                                <i class="fas fa-info-circle me-1"></i>
                                                سيتم استبدال الصورة الرئيسية الحالية بهذه الصورة بعد الحفظ
                                            </div>
                                        </div>
                                    </div>

                                    <!-- الصور الإضافية -->
                                    <div class="mb-4" bis_skin_checked="1">
                                        <h6 class="mb-3">
                                            <i class="fas fa-layer-group me-1"></i> الصور الإضافية
                                        </h6>

                                        @if ($product->images && $product->images->count() > 0)
                                            <div class="mb-4">
                                                <p class="text-muted mb-3">الصور الإضافية الحالية:</p>
                                                <div class="image-preview-grid sortable-image-list" id="existingImagesGrid">
                                                    @foreach ($product->images as $image)
                                                        @if (!$image->is_primary)
                                                            <div class="image-preview-item" data-id="{{ $image->id }}">
                                                                @if ($image->is_primary)
                                                                    <span class="primary-badge">رئيسية</span>
                                                                @endif
                                                                <img src="{{ get_user_image($image->path) }}" alt="صورة إضافية">
                                                                <div class="image-actions">
                                                                    <button type="button" class="btn btn-info btn-sm"
                                                                            onclick="viewImage('{{ get_user_image($image->path) }}')">
                                                                        <i class="fas fa-expand"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger btn-sm"
                                                                            onclick="removeAdditionalImage({{ $image->id }})">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                    @if (!$image->is_primary)
                                                                        <button type="button" class="btn btn-primary btn-sm"
                                                                                onclick="setAsPrimary({{ $image->id }})">
                                                                            <i class="fas fa-star"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        <!-- رفع صور إضافية -->
                                        <div class="image-manager"
                                             onclick="document.getElementById('additional_images').click()">
                                            <i class="fas fa-images"></i>
                                            <h6 class="mt-3 mb-2">انقر لإضافة صور إضافية</h6>
                                            <p class="text-muted mb-0">يمكنك رفع أكثر من صورة في نفس الوقت</p>
                                            <small class="text-muted">اختر عدة صور باستخدام Ctrl+Click</small>
                                        </div>
                                        <input type="file" id="additional_images" name="additional_images[]"
                                               accept="image/*" multiple style="display: none;">
                                        <div id="newImagesPreview" class="image-preview-grid mt-4"></div>
                                        
                                        <input type="hidden" id="removed_images" name="removed_images" value="">
                                        <input type="hidden" id="primary_image_id" name="primary_image_id"
                                               value="{{ $product->images->where('is_primary', true)->first()->id ?? '' }}">
                                        <input type="hidden" id="images_order" name="images_order" value="">
                                    </div>
                                </div>

                                <!-- قسم التسعير -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-tags"></i> إدارة الأسعار
                                        </div>
                                        <div class="badge bg-info">ID: {{ $product->id }}</div>
                                    </div>

                                    <div class="row" bis_skin_checked="1">
                                        <div class="col-md-6 mb-4" bis_skin_checked="1">
                                            <label for="price" class="form-label required">
                                                <i class="fas fa-money-bill-wave me-1"></i> السعر الأساسي
                                            </label>
                                            <div class="input-group" bis_skin_checked="1">
                                                <span class="input-group-text bg-light">ج.م</span>
                                                <input type="number" class="form-control" id="price" name="price"
                                                       step="0.01" 
                                                       value="{{ old('price', $product->price) }}"
                                                       placeholder="0.00"
                                                       required>
                                            </div>
                                            <div class="form-text">السعر الأساسي للمنتج بدون خصم</div>
                                        </div>

                                        <div class="col-md-6 mb-4" bis_skin_checked="1">
                                            <label for="price_text" class="form-label">
                                                <i class="fas fa-text-width me-1"></i> نص السعر
                                            </label>
                                            <div class="input-group" bis_skin_checked="1">
                                                <span class="input-group-text bg-light">ج.م</span>
                                                <input type="text" class="form-control" id="price_text" name="price_text"
                                                       value="{{ old('price_text', $product->price_text) }}"
                                                       placeholder="نص السعر المعروض">
                                            </div>
                                            <div class="form-text">النص المعروض بجانب السعر (اختياري)</div>
                                        </div>
                                    </div>

                                    <!-- خيارات التسعير الإضافية -->
                                    <div class="row" bis_skin_checked="1">
                                        <div class="col-md-4 mb-4" bis_skin_checked="1">
                                            <div class="d-flex align-items-center mb-3" bis_skin_checked="1">
                                                <label class="toggle-switch me-3">
                                                    <input type="checkbox" id="has_discount" name="has_discount"
                                                           {{ old('has_discount', $product->has_discount) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <label class="toggle-label mb-0">
                                                    <i class="fas fa-percentage me-1"></i> تطبيق خصم
                                                </label>
                                            </div>

                                            <div id="discountSection"
                                                 style="{{ old('has_discount', $product->has_discount) ? '' : 'display: none;' }}">
                                                <div class="p-3 border rounded bg-light" bis_skin_checked="1">
                                                    <div class="row g-2" bis_skin_checked="1">
                                                        <div class="col-6" bis_skin_checked="1">
                                                            <label for="discount_type" class="form-label">نوع الخصم</label>
                                                            <select class="form-select" id="discount_type" name="discount_type">
                                                                <option value="percentage"
                                                                    {{ old('discount_type', $product->discount->discount_type ?? '') == 'percentage' ? 'selected' : '' }}>
                                                                    نسبة مئوية %
                                                                </option>
                                                                <option value="fixed"
                                                                    {{ old('discount_type', $product->discount->discount_type ?? '') == 'fixed' ? 'selected' : '' }}>
                                                                    قيمة ثابتة
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="col-6" bis_skin_checked="1">
                                                            <label for="discount_value" class="form-label">قيمة الخصم</label>
                                                            <input type="number" class="form-control" id="discount_value"
                                                                   name="discount_value" step="0.01"
                                                                   value="{{ old('discount_value', $product->discount->discount_value ?? '') }}"
                                                                   placeholder="0.00">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4 mb-4" bis_skin_checked="1">
                                            <div class="d-flex align-items-center" bis_skin_checked="1">
                                                <label class="toggle-switch me-3">
                                                    <input type="checkbox" id="includes_tax" name="includes_tax"
                                                           {{ old('includes_tax', $product->includes_tax) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <label class="toggle-label mb-0">
                                                    <i class="fas fa-receipt me-1"></i> يشمل الضريبة
                                                </label>
                                            </div>
                                            <div class="form-text mt-2">يشمل سعر المنتج قيمة الضريبة</div>
                                        </div>

                                        <div class="col-md-4 mb-4" bis_skin_checked="1">
                                            <div class="d-flex align-items-center" bis_skin_checked="1">
                                                <label class="toggle-switch me-3">
                                                    <input type="checkbox" id="includes_shipping" name="includes_shipping"
                                                           {{ old('includes_shipping', $product->includes_shipping) ? 'checked' : '' }}>
                                                    <span class="toggle-slider"></span>
                                                </label>
                                                <label class="toggle-label mb-0">
                                                    <i class="fas fa-shipping-fast me-1"></i> يشمل الشحن
                                                </label>
                                            </div>
                                            <div class="form-text mt-2">يشمل سعر المنتج تكلفة الشحن</div>
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

                            <!-- الخطوة 3: المواصفات -->
                            <div class="step-card step-3" style="display: none;" bis_skin_checked="1">
                                <div class="step-header" bis_skin_checked="1">
                                    <div class="step-number" bis_skin_checked="1">3</div>
                                    <div bis_skin_checked="1">
                                        <h5 class="step-title">المواصفات والخصائص</h5>
                                        <p class="step-description">تحديث مواصفات المنتج وخياراته المختلفة</p>
                                    </div>
                                </div>

                                <!-- الألوان -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-palette"></i> الألوان المتاحة
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="openQuickAddModal('color')">
                                            <i class="fas fa-plus me-1"></i> إضافة لون جديد
                                        </button>
                                    </div>

                                    <!-- الألوان المختارة -->
                                    <div class="mb-4" bis_skin_checked="1">
                                        <label class="form-label mb-3">الألوان المختارة:</label>
                                        <div id="selectedColorsContainer" class="d-flex flex-wrap gap-3 mb-4">
                                            @foreach ($product->colors as $color)
                                                <div class="color-swatch-card" data-id="{{ $color->id }}">
                                                    <div class="color-preview" style="background-color: {{ $color->hex_code }};"></div>
                                                    <div class="color-info">
                                                        <strong>{{ $color->name }}</strong>
                                                        <small class="text-muted d-block">{{ $color->hex_code }}</small>
                                                    </div>
                                                    <input type="hidden" name="colors[]" value="{{ $color->id }}">
                                                    <button type="button" class="btn btn-sm btn-outline-danger" 
                                                            onclick="removeColor({{ $color->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- الألوان المتاحة -->
                                    <div bis_skin_checked="1">
                                        <label class="form-label mb-3">اختر من الألوان المتاحة:</label>
                                        <div class="color-grid" id="availableColorsGrid">
                                            @foreach ($colors as $color)
                                                @if (!$product->colors->contains($color->id))
                                                    <div class="color-item"
                                                         style="background-color: {{ $color->hex_code }};"
                                                         data-id="{{ $color->id }}" 
                                                         data-name="{{ $color->name }}"
                                                         data-hex="{{ $color->hex_code }}" 
                                                         onclick="addColor(this)"
                                                         title="{{ $color->name }}">
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <!-- المواد -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-cube"></i> المواد المستخدمة
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="openQuickAddModal('material')">
                                            <i class="fas fa-plus me-1"></i> إضافة مادة جديدة
                                        </button>
                                    </div>

                                    <!-- المواد الحالية -->
                                    <div id="materialsContainer">
                                        @foreach ($product->materials as $index => $material)
                                            <div class="material-item" data-id="{{ $material->id }}">
                                                <div class="material-header">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                                                        <i class="fas fa-arrows-alt"></i>
                                                    </button>
                                                    <div class="material-title" bis_skin_checked="1">
                                                        <strong>{{ $material->name }}</strong>
                                                        @if($material->pivot->additional_price > 0)
                                                            <span class="badge bg-success ms-2">
                                                                +{{ number_format($material->pivot->additional_price, 2) }} ج.م
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <button type="button" class="material-remove" onclick="removeMaterial(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row" bis_skin_checked="1">
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <input type="hidden" name="materials[{{ $loop->index }}][material_id]"
                                                               value="{{ $material->id }}">
                                                        <label class="form-label">الكمية</label>
                                                        <input type="number" class="form-control"
                                                               name="materials[{{ $loop->index }}][quantity]"
                                                               value="{{ $material->pivot->quantity }}" 
                                                               placeholder="الكمية"
                                                               min="0" step="0.01">
                                                    </div>
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">الوحدة</label>
                                                        <select class="form-select" name="materials[{{ $loop->index }}][unit]">
                                                            <option value="piece" {{ $material->pivot->unit == 'piece' ? 'selected' : '' }}>
                                                                قطعة
                                                            </option>
                                                            <option value="meter" {{ $material->pivot->unit == 'meter' ? 'selected' : '' }}>
                                                                متر
                                                            </option>
                                                            <option value="kg" {{ $material->pivot->unit == 'kg' ? 'selected' : '' }}>
                                                                كجم
                                                            </option>
                                                            <option value="liter" {{ $material->pivot->unit == 'liter' ? 'selected' : '' }}>
                                                                لتر
                                                            </option>
                                                            <option value="gram" {{ $material->pivot->unit == 'gram' ? 'selected' : '' }}>
                                                                جرام
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">السعر الإضافي</label>
                                                        <input type="number" class="form-control"
                                                               name="materials[{{ $loop->index }}][additional_price]"
                                                               value="{{ $material->pivot->additional_price ?? 0 }}"
                                                               placeholder="سعر إضافي" step="0.01" min="0">
                                                    </div>
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">التكلفة الإضافية</label>
                                                        <div class="input-group" bis_skin_checked="1">
                                                            <input type="number" class="form-control" 
                                                                   value="{{ $material->pivot->additional_price ?? 0 }}" 
                                                                   readonly>
                                                            <span class="input-group-text">ج.م</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- زر إضافة مادة -->
                                    <button type="button" class="btn btn-outline-primary w-100 mt-3"
                                            onclick="addMaterialField()">
                                        <i class="fas fa-plus me-1"></i> إضافة مادة أخرى
                                    </button>
                                </div>

                                <!-- المواصفات -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-sliders-h"></i> المواصفات الإضافية
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="addFeatureField()">
                                            <i class="fas fa-plus me-1"></i> إضافة مواصفة
                                        </button>
                                    </div>

                                    <div id="featuresContainer">
                                        @foreach ($product->features as $index => $feature)
                                            <div class="feature-item">
                                                <div class="feature-header">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                                                        <i class="fas fa-arrows-alt"></i>
                                                    </button>
                                                    <div class="feature-title">مواصفة {{ $index + 1 }}</div>
                                                    <button type="button" class="feature-remove" onclick="removeField(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row" bis_skin_checked="1">
                                                    <div class="col-md-5 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">اسم المواصفة</label>
                                                        <input type="text" class="form-control"
                                                               name="features[{{ $index }}][name]"
                                                               value="{{ $feature->name }}" 
                                                               placeholder="مثل: الوزن، الأبعاد، الماركة"
                                                               required>
                                                    </div>
                                                    <div class="col-md-7 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">القيمة</label>
                                                        <input type="text" class="form-control"
                                                               name="features[{{ $index }}][value]"
                                                               value="{{ $feature->value }}" 
                                                               placeholder="مثل: 2 كجم، 20×30 سم، Nike"
                                                               required>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- وقت التوصيل والضمان -->
                                <div class="row" bis_skin_checked="1">
                                    <div class="col-md-6 mb-4" bis_skin_checked="1">
                                        <div class="dynamic-section h-100" bis_skin_checked="1">
                                            <div class="dynamic-section-header" bis_skin_checked="1">
                                                <div class="dynamic-section-title">
                                                    <i class="fas fa-truck"></i> وقت التوصيل
                                                </div>
                                            </div>
                                            <div class="row" bis_skin_checked="1">
                                                <div class="col-md-6 mb-3" bis_skin_checked="1">
                                                    <label for="from_days" class="form-label">من (أيام)</label>
                                                    <input type="number" class="form-control" id="from_days" name="from_days"
                                                           value="{{ old('from_days', $product->deliveryTime->from_days ?? '') }}"
                                                           min="0" placeholder="0">
                                                </div>
                                                <div class="col-md-6 mb-3" bis_skin_checked="1">
                                                    <label for="to_days" class="form-label">إلى (أيام)</label>
                                                    <input type="number" class="form-control" id="to_days" name="to_days"
                                                           value="{{ old('to_days', $product->deliveryTime->to_days ?? '') }}"
                                                           min="0" placeholder="0">
                                                </div>
                                            </div>
                                            <div class="form-text">الفترة الزمنية المتوقعة لتوصيل المنتج</div>
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4" bis_skin_checked="1">
                                        <div class="dynamic-section h-100" bis_skin_checked="1">
                                            <div class="dynamic-section-header" bis_skin_checked="1">
                                                <div class="dynamic-section-title">
                                                    <i class="fas fa-shield-alt"></i> الضمان
                                                </div>
                                            </div>
                                            <div bis_skin_checked="1">
                                                <label for="warranty_months" class="form-label">مدة الضمان (بالأشهر)</label>
                                                <input type="number" class="form-control" id="warranty_months"
                                                       name="warranty_months"
                                                       value="{{ old('warranty_months', $product->warranty->months ?? '') }}"
                                                       min="0" placeholder="0">
                                                <div class="form-text mt-2">فترة الضمان المقدمة للمنتج بالأشهر</div>
                                            </div>
                                        </div>
                                    </div>
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

                            <!-- الخطوة 4: الخيارات الإضافية -->
                            <div class="step-card step-4" style="display: none;" bis_skin_checked="1">
                                <div class="step-header" bis_skin_checked="1">
                                    <div class="step-number" bis_skin_checked="1">4</div>
                                    <div bis_skin_checked="1">
                                        <h5 class="step-title">خيارات إضافية</h5>
                                        <p class="step-description">إدارة الخيارات الخاصة والعروض والـ SEO</p>
                                    </div>
                                </div>

                                <!-- خيارات المنتج -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-cog"></i> خيارات المنتج
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="addProductOptionField()">
                                            <i class="fas fa-plus me-1"></i> إضافة خيار
                                        </button>
                                    </div>

                                    <div id="productOptionsContainer">
                                        @foreach ($product->options as $index => $option)
                                            <div class="option-item">
                                                <div class="option-header">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                                                        <i class="fas fa-arrows-alt"></i>
                                                    </button>
                                                    <div class="option-title">خيار {{ $index + 1 }}</div>
                                                    <button type="button" class="option-remove" onclick="removeField(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row" bis_skin_checked="1">
                                                    <div class="col-md-4 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">اسم الخيار</label>
                                                        <input type="text" class="form-control"
                                                               name="product_options[{{ $index }}][option_name]"
                                                               value="{{ $option->option_name }}" 
                                                               placeholder="مثل: اللون، المقاس، النسيج"
                                                               required>
                                                    </div>
                                                    <div class="col-md-4 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">القيمة</label>
                                                        <input type="text" class="form-control"
                                                               name="product_options[{{ $index }}][option_value]"
                                                               value="{{ $option->option_value }}" 
                                                               placeholder="مثل: أحمر، كبير، قطن"
                                                               required>
                                                    </div>
                                                    <div class="col-md-2 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">السعر الإضافي</label>
                                                        <input type="number" class="form-control"
                                                               name="product_options[{{ $index }}][additional_price]"
                                                               value="{{ $option->additional_price }}"
                                                               placeholder="0.00" step="0.01" min="0">
                                                    </div>
                                                    <div class="col-md-2 mb-2" bis_skin_checked="1">
                                                        <div class="form-check form-switch mt-4" bis_skin_checked="1">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="product_options[{{ $index }}][is_required]"
                                                                   value="1" {{ $option->is_required ? 'checked' : '' }}>
                                                            <label class="form-check-label">مطلوب</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- طرق الطباعة -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-print"></i> طرق الطباعة
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="openQuickAddModal('printing_method')">
                                            <i class="fas fa-plus me-1"></i> إضافة طريقة طباعة جديدة
                                        </button>
                                    </div>
                                    <div class="mb-3" bis_skin_checked="1">
                                        <label class="form-label">اختر طرق الطباعة المتاحة:</label>
                                        <select class="form-select select2" id="printingMethodsSelect"
                                                name="printing_methods[]" multiple style="width: 100%;">
                                            @foreach ($printingMethods as $method)
                                                <option value="{{ $method->id }}"
                                                        data-price="{{ $method->base_price }}"
                                                        {{ $product->printingMethods->contains($method->id) ? 'selected' : '' }}>
                                                    {{ $method->name }} - {{ $method->base_price }} ج.م
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text mt-2">اختر طرق الطباعة المتاحة لهذا المنتج</div>
                                    </div>
                                </div>

                                <!-- أماكن الطباعة -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-map-marker-alt"></i> أماكن الطباعة
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="openQuickAddModal('print_location')">
                                            <i class="fas fa-plus me-1"></i> إضافة مكان طباعة جديد
                                        </button>
                                    </div>
                                    <div class="mb-3" bis_skin_checked="1">
                                        <label class="form-label">اختر أماكن الطباعة:</label>
                                        <select class="form-select select2" id="printLocationsSelect"
                                                name="print_locations[]" multiple style="width: 100%;">
                                            @foreach ($printLocations as $location)
                                                <option value="{{ $location->id }}" 
                                                        data-type="{{ $location->type }}"
                                                        data-price="{{ $location->additional_price }}"
                                                        {{ $product->printLocations->contains($location->id) ? 'selected' : '' }}>
                                                    {{ $location->name }} ({{ $location->type }}) -
                                                    {{ $location->additional_price }} ج.م
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text mt-2">اختر الأماكن المتاحة للطباعة على المنتج</div>
                                    </div>
                                </div>

                                <!-- العروض -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-gift"></i> العروض والتخفيضات
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="openQuickAddModal('offer')">
                                            <i class="fas fa-plus me-1"></i> إضافة عرض جديد
                                        </button>
                                    </div>
                                    <div class="mb-3" bis_skin_checked="1">
                                        <label class="form-label">اختر العروض المطبقة:</label>
                                        <select class="form-select select2" id="offersSelect" name="offers[]" multiple style="width: 100%;">
                                            @foreach ($offers as $offer)
                                                <option value="{{ $offer->id }}"
                                                        {{ $product->offers->contains($offer->id) ? 'selected' : '' }}>
                                                    {{ $offer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="form-text mt-2">اختر العروض التي تنطبق على هذا المنتج</div>
                                    </div>
                                </div>

                                <!-- التسعير حسب الكمية -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-chart-line"></i> التسعير حسب الكمية
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="addPricingTierField()">
                                            <i class="fas fa-plus me-1"></i> إضافة شرائح تسعير
                                        </button>
                                    </div>

                                    <div id="pricingTiersContainer">
                                        @foreach ($product->pricingTiers as $index => $tier)
                                            <div class="pricing-tier-item">
                                                <div class="pricing-tier-header">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                                                        <i class="fas fa-arrows-alt"></i>
                                                    </button>
                                                    <div class="pricing-tier-title">شريحة تسعير {{ $index + 1 }}</div>
                                                    <button type="button" class="pricing-tier-remove" onclick="removeField(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row" bis_skin_checked="1">
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">الكمية</label>
                                                        <input type="number" class="form-control"
                                                               name="pricing_tiers[{{ $index }}][quantity]"
                                                               value="{{ $tier->quantity }}" 
                                                               placeholder="الكمية"
                                                               min="1" required>
                                                    </div>
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">السعر للوحدة</label>
                                                        <input type="number" class="form-control"
                                                               name="pricing_tiers[{{ $index }}][price_per_unit]"
                                                               value="{{ $tier->price_per_unit }}"
                                                               placeholder="السعر للوحدة" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">خصم %</label>
                                                        <input type="number" class="form-control"
                                                               name="pricing_tiers[{{ $index }}][discount_percentage]"
                                                               value="{{ $tier->discount_percentage }}" 
                                                               placeholder="خصم %"
                                                               min="0" max="100" step="0.01">
                                                    </div>
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <div class="form-check form-switch mt-4" bis_skin_checked="1">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="pricing_tiers[{{ $index }}][is_sample]"
                                                                   value="1" {{ $tier->is_sample ? 'checked' : '' }}>
                                                            <label class="form-check-label">عينة</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- التسعير حسب المقاس -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-ruler-combined"></i> أسعار حسب المقاس والكمية
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="addSizeTierField()">
                                            <i class="fas fa-plus me-1"></i> إضافة سعر للمقاس
                                        </button>
                                    </div>

                                    <div id="sizeTiersContainer">
                                        @foreach ($product->sizeTiers as $index => $tier)
                                            <div class="size-tier-item">
                                                <div class="size-tier-header">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                                                        <i class="fas fa-arrows-alt"></i>
                                                    </button>
                                                    <div class="size-tier-title">سعر حسب المقاس {{ $index + 1 }}</div>
                                                    <button type="button" class="size-tier-remove" onclick="removeField(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                                <div class="row" bis_skin_checked="1">
                                                    <div class="col-md-4 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">اسم المقاس</label>
                                                        <input type="text" class="form-control"
                                                               name="size_tiers[{{ $index }}][size_name]"
                                                               value="{{ $tier->size->name ?? $tier->size_name }}" 
                                                               placeholder="اسم المقاس" required>
                                                    </div>
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">الكمية</label>
                                                        <input type="number" class="form-control"
                                                               name="size_tiers[{{ $index }}][quantity]"
                                                               value="{{ $tier->quantity }}" 
                                                               placeholder="الكمية"
                                                               min="1" required>
                                                    </div>
                                                    <div class="col-md-3 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">السعر للوحدة</label>
                                                        <input type="number" class="form-control"
                                                               name="size_tiers[{{ $index }}][price_per_unit]"
                                                               value="{{ $tier->price_per_unit }}"
                                                               placeholder="السعر للوحدة" step="0.01" required>
                                                    </div>
                                                    <div class="col-md-2 mb-2" bis_skin_checked="1">
                                                        <label class="form-label">السعر الإجمالي</label>
                                                        <div class="input-group" bis_skin_checked="1">
                                                            <input type="number" class="form-control" 
                                                                   value="{{ $tier->quantity * $tier->price_per_unit }}" 
                                                                   readonly>
                                                            <span class="input-group-text">ج.م</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- إعدادات SEO -->
                                <div class="dynamic-section mb-4" bis_skin_checked="1">
                                    <div class="dynamic-section-header" bis_skin_checked="1">
                                        <div class="dynamic-section-title">
                                            <i class="fas fa-search"></i> إعدادات SEO
                                        </div>
                                        <span class="badge bg-info">تحسين محركات البحث</span>
                                    </div>

                                    <div class="row" bis_skin_checked="1">
                                        <div class="col-md-6 mb-3" bis_skin_checked="1">
                                            <label for="slug" class="form-label">
                                                <i class="fas fa-link me-1"></i> الرابط (Slug)
                                            </label>
                                            <div class="input-group" bis_skin_checked="1">
                                                <input type="text" class="form-control" id="slug" name="slug"
                                                       value="{{ old('slug', $product->slug) }}"
                                                       placeholder="اسم-المنتج">
                                                <button type="button" class="btn btn-outline-secondary" onclick="generateSlug()">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                            <div class="form-text">رابط SEO الخاص بالمنتج في محركات البحث</div>
                                        </div>

                                        <div class="col-md-6 mb-3" bis_skin_checked="1">
                                            <label for="meta_title" class="form-label">
                                                <i class="fas fa-heading me-1"></i> عنوان الصفحة (Meta Title)
                                            </label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                                   value="{{ old('meta_title', $product->meta_title) }}"
                                                   placeholder="عنوان يعبر عن المنتج">
                                            <div class="form-text">الطول الموصى به: 50-60 حرفاً</div>
                                        </div>

                                        <div class="col-md-12 mb-3" bis_skin_checked="1">
                                            <label for="meta_description" class="form-label">
                                                <i class="fas fa-align-left me-1"></i> وصف الصفحة (Meta Description)
                                            </label>
                                            <textarea class="form-control" id="meta_description" name="meta_description" 
                                                      rows="3" placeholder="وصف مختصر للمنتج">{{ old('meta_description', $product->meta_description) }}</textarea>
                                            <div class="form-text">الطول الموصى به: 150-160 حرفاً</div>
                                        </div>

                                        <div class="col-md-12 mb-3" bis_skin_checked="1">
                                            <label for="meta_keywords" class="form-label">
                                                <i class="fas fa-key me-1"></i> الكلمات المفتاحية
                                            </label>
                                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                                   value="{{ old('meta_keywords', $product->meta_keywords) }}"
                                                   placeholder="كلمة1, كلمة2, كلمة3">
                                            <div class="form-text">كلمات مفتاحية مفصولة بفواصل لتحسين البحث</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-4" bis_skin_checked="1">
                                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="3">
                                        <i class="fas fa-arrow-right me-1"></i> السابق
                                    </button>
                                    <div class="btn-group" role="group" bis_skin_checked="1">
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-save me-1"></i> حفظ التعديلات
                                        </button>
                                        <button type="button" class="btn btn-primary" onclick="saveAndContinue()">
                                            <i class="fas fa-redo me-1"></i> حفظ ومتابعة
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal الإضافة السريعة -->
    <div class="modal fade quick-add-modal" id="quickAddModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quickAddModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="quickAddForm">
                        <div id="quickAddFormContent">
                            <!-- سيتم تحميل محتوى النموذج هنا ديناميكياً -->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> إلغاء
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveQuickAdd()">
                        <i class="fas fa-check me-1"></i> إضافة
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal عرض الصورة -->
    <div class="modal fade" id="imageViewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body p-0">
                    <img src="" alt="صورة المنتج" id="viewedImage" class="img-fluid w-100 rounded">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Templates -->
    <template id="materialFieldTemplate">
        <div class="material-item">
            <div class="material-header">
                <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                    <i class="fas fa-arrows-alt"></i>
                </button>
                <div class="material-title">
                    <strong>مادة جديدة</strong>
                </div>
                <button type="button" class="material-remove" onclick="removeMaterial(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">المادة</label>
                    <select class="form-select material-select" name="materials[][material_id]" required>
                        <option value="">اختر المادة</option>
                        @foreach ($materials as $material)
                            <option value="{{ $material->id }}">{{ $material->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">الكمية</label>
                    <input type="number" class="form-control" name="materials[][quantity]" 
                           placeholder="الكمية" min="0" step="0.01">
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">الوحدة</label>
                    <select class="form-select" name="materials[][unit]">
                        <option value="piece">قطعة</option>
                        <option value="meter">متر</option>
                        <option value="kg">كجم</option>
                        <option value="liter">لتر</option>
                        <option value="gram">جرام</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">السعر الإضافي</label>
                    <input type="number" class="form-control" name="materials[][additional_price]"
                           placeholder="سعر إضافي" step="0.01" min="0">
                </div>
            </div>
        </div>
    </template>

    <template id="featureFieldTemplate">
        <div class="feature-item">
            <div class="feature-header">
                <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                    <i class="fas fa-arrows-alt"></i>
                </button>
                <div class="feature-title">مواصفة جديدة</div>
                <button type="button" class="feature-remove" onclick="removeField(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-5 mb-2">
                    <label class="form-label">اسم المواصفة</label>
                    <input type="text" class="form-control" name="features[][name]" 
                           placeholder="اسم المواصفة" required>
                </div>
                <div class="col-md-7 mb-2">
                    <label class="form-label">القيمة</label>
                    <input type="text" class="form-control" name="features[][value]" 
                           placeholder="القيمة" required>
                </div>
            </div>
        </div>
    </template>

    <template id="productOptionFieldTemplate">
        <div class="option-item">
            <div class="option-header">
                <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                    <i class="fas fa-arrows-alt"></i>
                </button>
                <div class="option-title">خيار جديد</div>
                <button type="button" class="option-remove" onclick="removeField(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label">اسم الخيار</label>
                    <input type="text" class="form-control" name="product_options[][option_name]"
                           placeholder="اسم الخيار" required>
                </div>
                <div class="col-md-4 mb-2">
                    <label class="form-label">القيمة</label>
                    <input type="text" class="form-control" name="product_options[][option_value]"
                           placeholder="القيمة" required>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">السعر الإضافي</label>
                    <input type="number" class="form-control" name="product_options[][additional_price]"
                           placeholder="السعر الإضافي" step="0.01">
                </div>
                <div class="col-md-2 mb-2">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" 
                               name="product_options[][is_required]" value="1">
                        <label class="form-check-label">مطلوب</label>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="pricingTierFieldTemplate">
        <div class="pricing-tier-item">
            <div class="pricing-tier-header">
                <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                    <i class="fas fa-arrows-alt"></i>
                </button>
                <div class="pricing-tier-title">شريحة تسعير جديدة</div>
                <button type="button" class="pricing-tier-remove" onclick="removeField(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-3 mb-2">
                    <label class="form-label">الكمية</label>
                    <input type="number" class="form-control" name="pricing_tiers[][quantity]" 
                           placeholder="الكمية" min="1" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">السعر للوحدة</label>
                    <input type="number" class="form-control" name="pricing_tiers[][price_per_unit]"
                           placeholder="السعر للوحدة" step="0.01" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">خصم %</label>
                    <input type="number" class="form-control" name="pricing_tiers[][discount_percentage]"
                           placeholder="خصم %" min="0" max="100" step="0.01">
                </div>
                <div class="col-md-3 mb-2">
                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" 
                               name="pricing_tiers[][is_sample]" value="1">
                        <label class="form-check-label">عينة</label>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="sizeTierFieldTemplate">
        <div class="size-tier-item">
            <div class="size-tier-header">
                <button type="button" class="btn btn-sm btn-outline-secondary drag-handle">
                    <i class="fas fa-arrows-alt"></i>
                </button>
                <div class="size-tier-title">سعر حسب المقاس جديد</div>
                <button type="button" class="size-tier-remove" onclick="removeField(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label">اسم المقاس</label>
                    <input type="text" class="form-control" name="size_tiers[][size_name]"
                           placeholder="اسم المقاس" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">الكمية</label>
                    <input type="number" class="form-control" name="size_tiers[][quantity]" 
                           placeholder="الكمية" min="1" required>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">السعر للوحدة</label>
                    <input type="number" class="form-control" name="size_tiers[][price_per_unit]"
                           placeholder="السعر للوحدة" step="0.01" required>
                </div>
                <div class="col-md-2 mb-2">
                    <label class="form-label">السعر الإجمالي</label>
                    <div class="input-group">
                        <input type="number" class="form-control" value="0" readonly>
                        <span class="input-group-text">ج.م</span>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <template id="colorSwatchTemplate">
        <div class="color-swatch-card" data-id="{id}">
            <div class="color-preview" style="background-color: {hex};"></div>
            <div class="color-info">
                <strong>{name}</strong>
                <small class="text-muted d-block">{hex}</small>
            </div>
            <input type="hidden" name="colors[]" value="{id}">
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeColor({id})">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </template>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        // المتغيرات العامة
        let materialCounter = {{ $product->materials->count() }};
        let featureCounter = {{ $product->features->count() }};
        let optionCounter = {{ $product->options->count() }};
        let pricingTierCounter = {{ $product->pricingTiers->count() }};
        let sizeTierCounter = {{ $product->sizeTiers->count() }};
        let removedImages = [];
        let currentQuickAddType = '';
        let productId = {{ $product->id }};

        $(document).ready(function() {
            // تهيئة محرر النصوص
            $('.summernote').summernote({
                height: 250,
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

            // تهيئة Select2
            $('.select2').select2({
                placeholder: 'اختر الخيارات',
                allowClear: true,
                dir: 'rtl',
                width: '100%'
            });

            // تهيئة السحب والإفلات للصور
            initializeImageSortable();

            // تهيئة السحب والإفلات للعناصر الديناميكية
            initializeDynamicElementsSortable();

            // التنقل بين الخطوات
            $('.next-step').click(function() {
                const nextStep = $(this).data('next');
                navigateToStep(nextStep);
            });

            $('.prev-step').click(function() {
                const prevStep = $(this).data('prev');
                navigateToStep(prevStep);
            });

            // التحكم في تفعيل الخصم
            $('#has_discount').change(function() {
                if ($(this).is(':checked')) {
                    $('#discountSection').slideDown(300);
                } else {
                    $('#discountSection').slideUp(300);
                }
            });

            // معالجة رفع الصور
            $('#image').change(function(e) {
                previewMainImage(e.target.files[0]);
            });

            $('#additional_images').change(function(e) {
                previewMultipleImages(e.target.files);
            });

            // توليد الرابط التلقائي
            $('#name').on('blur', function() {
                if (!$('#slug').val()) {
                    generateSlug();
                }
            });

            // تحديث السعر الإجمالي للمقاس
            $(document).on('input', '.size-tier-item input', function() {
                updateSizeTierTotalPrice($(this).closest('.size-tier-item'));
            });

            // إظهار نصائح الصور
            window.showImageTips = function() {
                Swal.fire({
                    title: 'نصائح للصور',
                    html: `
                        <div class="text-start">
                            <h6>أفضل الممارسات لصور المنتج:</h6>
                            <ul>
                                <li>استخدم صوراً عالية الجودة (800×800 بكسل على الأقل)</li>
                                <li>تأكد من إضاءة جيدة وخلفية محايدة</li>
                                <li>أظهر المنتج من زوايا متعددة</li>
                                <li>استخدم صورة رئيسية واضحة وجذابة</li>
                                <li>حافظ على تناسق الألوان بين الصور</li>
                                <li>تجنب الصور المشوشة أو المنخفضة الجودة</li>
                            </ul>
                        </div>
                    `,
                    icon: 'info',
                    confirmButtonText: 'حسناً'
                });
            };
        });

        // تهيئة السحب والإفلات للصور
        function initializeImageSortable() {
            const existingImagesGrid = document.getElementById('existingImagesGrid');
            if (existingImagesGrid) {
                new Sortable(existingImagesGrid, {
                    animation: 200,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    onEnd: function() {
                        updateImagesOrder();
                    }
                });
            }
        }

        // تهيئة السحب والإفلات للعناصر الديناميكية
        function initializeDynamicElementsSortable() {
            // المواد
            const materialsContainer = document.getElementById('materialsContainer');
            if (materialsContainer) {
                new Sortable(materialsContainer, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: updateMaterialsOrder
                });
            }

            // المواصفات
            const featuresContainer = document.getElementById('featuresContainer');
            if (featuresContainer) {
                new Sortable(featuresContainer, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: updateFeaturesOrder
                });
            }

            // خيارات المنتج
            const optionsContainer = document.getElementById('productOptionsContainer');
            if (optionsContainer) {
                new Sortable(optionsContainer, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: updateOptionsOrder
                });
            }

            // شرائح التسعير
            const pricingTiersContainer = document.getElementById('pricingTiersContainer');
            if (pricingTiersContainer) {
                new Sortable(pricingTiersContainer, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: updatePricingTiersOrder
                });
            }

            // أسعار المقاسات
            const sizeTiersContainer = document.getElementById('sizeTiersContainer');
            if (sizeTiersContainer) {
                new Sortable(sizeTiersContainer, {
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    onEnd: updateSizeTiersOrder
                });
            }
        }

        // تحديث ترتيب العناصر
        function updateMaterialsOrder() {
            reindexElements('#materialsContainer', 'materials');
        }

        function updateFeaturesOrder() {
            reindexElements('#featuresContainer', 'features');
        }

        function updateOptionsOrder() {
            reindexElements('#productOptionsContainer', 'product_options');
        }

        function updatePricingTiersOrder() {
            reindexElements('#pricingTiersContainer', 'pricing_tiers');
        }

        function updateSizeTiersOrder() {
            reindexElements('#sizeTiersContainer', 'size_tiers');
            $('.size-tier-item').each(function() {
                updateSizeTierTotalPrice($(this));
            });
        }

        function reindexElements(containerSelector, fieldName) {
            $(`${containerSelector} > .material-item, ${containerSelector} > .feature-item, ${containerSelector} > .option-item, ${containerSelector} > .pricing-tier-item, ${containerSelector} > .size-tier-item`).each(function(index) {
                $(this).find('[name]').each(function() {
                    const name = $(this).attr('name');
                    const newName = name.replace(new RegExp(`\\[\\d+\\]\\[${fieldName === 'materials' ? 'material' : fieldName === 'product_options' ? 'option' : fieldName === 'pricing_tiers' ? 'tier' : fieldName === 'size_tiers' ? 'size' : fieldName}\\]`), `[${index}][${fieldName === 'materials' ? 'material' : fieldName === 'product_options' ? 'option' : fieldName === 'pricing_tiers' ? 'tier' : fieldName === 'size_tiers' ? 'size' : fieldName}]`);
                    $(this).attr('name', newName);
                });
            });
        }

        // التنقل بين الخطوات
        function navigateToStep(step) {
            // إخفاء جميع الخطوات
            $('.step-card').hide();

            // إظهار الخطوة المطلوبة
            $(`.step-${step}`).show();

            // تحديث حالة خطوات التوجيه
            $('.wizard-step').removeClass('active completed');
            
            for (let i = 1; i <= step; i++) {
                $(`#step${i}`).addClass(i === step ? 'active' : 'completed');
            }

            // التمرير للأعلى
            $('html, body').animate({
                scrollTop: $('.step-card:visible').offset().top - 100
            }, 300);
        }

        // إدارة الصور
        function previewMainImage(file) {
            if (!file || !file.type.match('image.*')) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: 'الملف يجب أن يكون صورة (JPG, PNG, GIF)'
                });
                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                Swal.fire({
                    icon: 'error',
                    title: 'حجم كبير',
                    text: 'حجم الصورة يجب أن لا يتجاوز 5MB'
                });
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#newMainImagePreview').show();
                $('#newMainImagePreviewImg').attr('src', e.target.result);
                $('#remove_main_image').val('0');
                
                // تحديث معاينة المنتج
                $('.preview-image').attr('src', e.target.result);
                
                Swal.fire({
                    icon: 'success',
                    title: 'تم اختيار الصورة',
                    text: 'تم اختيار الصورة الرئيسية الجديدة بنجاح',
                    timer: 1500,
                    showConfirmButton: false
                });
            };
            reader.readAsDataURL(file);
        }

        function previewMultipleImages(files) {
            const container = $('#newImagesPreview');
            container.empty();

            Array.from(files).forEach((file, index) => {
                if (!file.type.match('image.*')) return;
                
                if (file.size > 5 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'حجم كبير',
                        text: `الصورة ${file.name} حجمها كبير جداً (5MB كحد أقصى)`
                    });
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewHtml = `
                        <div class="image-preview-item" data-new-index="${index}">
                            <span class="primary-badge" style="background: #3498db;">
                                <i class="fas fa-plus me-1"></i> جديد
                            </span>
                            <img src="${e.target.result}" alt="صورة جديدة">
                            <div class="image-actions">
                                <button type="button" class="btn btn-info btn-sm" 
                                        onclick="viewImage('${e.target.result}')">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="removeNewImage(${index})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                    container.append(previewHtml);
                };
                reader.readAsDataURL(file);
            });
        }

        function viewImage(src) {
            $('#viewedImage').attr('src', src);
            new bootstrap.Modal(document.getElementById('imageViewModal')).show();
        }

        function removeCurrentMainImage() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم إزالة الصورة الرئيسية الحالية من المنتج',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#remove_main_image').val('1');
                    $('#currentMainImageContainer').slideUp();
                    $('.preview-image').attr('src', 'https://via.placeholder.com/120x120?text=No+Image');
                    Swal.fire('تم الحذف!', 'سيتم إزالة الصورة الرئيسية بعد حفظ التعديلات', 'success');
                }
            });
        }

        function removeNewMainImage() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم إلغاء اختيار الصورة الجديدة',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، ألغِ',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#newMainImagePreview').hide();
                    $('#image').val('');
                    if ($('#remove_main_image').val() === '1' || !$('#currentMainImageContainer').is(':visible')) {
                        $('.preview-image').attr('src', 'https://via.placeholder.com/120x120?text=No+Image');
                    }
                    Swal.fire('تم الإلغاء!', 'تم إلغاء اختيار الصورة الجديدة', 'success');
                }
            });
        }

        function removeAdditionalImage(imageId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم إزالة هذه الصورة من المنتج',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    removedImages.push(imageId);
                    $('#removed_images').val(removedImages.join(','));
                    $(`.image-preview-item[data-id="${imageId}"]`).remove();
                    Swal.fire('تم الحذف!', 'تم إزالة الصورة من المنتج', 'success');
                }
            });
        }

        function removeNewImage(index) {
            $(`.image-preview-item[data-new-index="${index}"]`).remove();
            const files = Array.from($('#additional_images')[0].files);
            files.splice(index, 1);
            const dt = new DataTransfer();
            files.forEach(file => dt.items.add(file));
            $('#additional_images')[0].files = dt.files;
        }

        function setAsPrimary(imageId) {
            $('#primary_image_id').val(imageId);
            $('.primary-badge').text('رئيسية');
            $(`.image-preview-item[data-id="${imageId}"] .primary-badge`).text('رئيسية');
            
            Swal.fire({
                icon: 'success',
                title: 'تم التحديث',
                text: 'تم تعيين الصورة كرئيسية للمنتج',
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

        // إدارة الألوان
        function addColor(element) {
            const colorId = $(element).data('id');
            const colorName = $(element).data('name');
            const colorHex = $(element).data('hex');

            if ($(`#selectedColorsContainer .color-swatch-card[data-id="${colorId}"]`).length > 0) {
                return;
            }

            const template = $('#colorSwatchTemplate').html();
            const swatchHtml = template
                .replace(/{id}/g, colorId)
                .replace(/{name}/g, colorName)
                .replace(/{hex}/g, colorHex);

            $('#selectedColorsContainer').append(swatchHtml);
            $(element).remove();
            
            // إضافة حقل سعر للون
            const priceInputHtml = `
                <div class="col-md-4 mb-3 color-price-${colorId}">
                    <label class="form-label">سعر إضافي للون: ${colorName}</label>
                    <input type="number" class="form-control" 
                           name="color_prices[${colorId}]" 
                           placeholder="السعر الإضافي" step="0.01" min="0">
                </div>
            `;
            if (!$('#colorPricesContainer').length) {
                $('#selectedColorsContainer').after('<div id="colorPricesContainer" class="row mt-3"></div>');
            }
            $('#colorPricesContainer').append(priceInputHtml);
        }

        function removeColor(colorId) {
            $(`.color-swatch-card[data-id="${colorId}"]`).remove();
            $(`.color-price-${colorId}`).remove();

            const colorName = $(`.color-swatch-card[data-id="${colorId}"] .color-info strong`).text();
            const colorHex = $(`.color-swatch-card[data-id="${colorId}"] .color-preview`).css('background-color');

            const colorItem = `
                <div class="color-item" 
                     style="background-color: ${colorHex};"
                     data-id="${colorId}"
                     data-name="${colorName}"
                     data-hex="${colorHex}"
                     onclick="addColor(this)"
                     title="${colorName}">
                </div>
            `;
            $('#availableColorsGrid').append(colorItem);
        }

        // العناصر الديناميكية
        function addMaterialField() {
            const template = $('#materialFieldTemplate').html();
            const newField = $(template);
            
            newField.find('[name]').each(function() {
                const name = $(this).attr('name');
                $(this).attr('name', name.replace('[]', `[${materialCounter}][]`));
            });

            newField.find('.material-select').select2({
                placeholder: 'اختر المادة'
            });

            $('#materialsContainer').append(newField);
            materialCounter++;
            
            // إعادة تهيئة السحب والإفلات
            initializeDynamicElementsSortable();
        }

        function addFeatureField() {
            const template = $('#featureFieldTemplate').html();
            const newField = $(template);
            
            newField.find('[name]').each(function() {
                const name = $(this).attr('name');
                $(this).attr('name', name.replace('[]', `[${featureCounter}][]`));
            });

            $('#featuresContainer').append(newField);
            featureCounter++;
            
            initializeDynamicElementsSortable();
        }

        function addProductOptionField() {
            const template = $('#productOptionFieldTemplate').html();
            const newField = $(template);
            
            newField.find('[name]').each(function() {
                const name = $(this).attr('name');
                $(this).attr('name', name.replace('[]', `[${optionCounter}][]`));
            });

            $('#productOptionsContainer').append(newField);
            optionCounter++;
            
            initializeDynamicElementsSortable();
        }

        function addPricingTierField() {
            const template = $('#pricingTierFieldTemplate').html();
            const newField = $(template);
            
            newField.find('[name]').each(function() {
                const name = $(this).attr('name');
                $(this).attr('name', name.replace('[]', `[${pricingTierCounter}][]`));
            });

            $('#pricingTiersContainer').append(newField);
            pricingTierCounter++;
            
            initializeDynamicElementsSortable();
        }

        function addSizeTierField() {
            const template = $('#sizeTierFieldTemplate').html();
            const newField = $(template);
            
            newField.find('[name]').each(function() {
                const name = $(this).attr('name');
                $(this).attr('name', name.replace('[]', `[${sizeTierCounter}][]`));
            });

            $('#sizeTiersContainer').append(newField);
            sizeTierCounter++;
            
            initializeDynamicElementsSortable();
            updateSizeTierTotalPrice(newField);
        }

        function updateSizeTierTotalPrice(element) {
            const quantity = element.find('input[name*="[quantity]"]').val() || 0;
            const price = element.find('input[name*="[price_per_unit]"]').val() || 0;
            const total = quantity * price;
            element.find('input[readonly]').val(total.toFixed(2));
        }

        function removeMaterial(button) {
            $(button).closest('.material-item').remove();
        }

        function removeField(button) {
            $(button).closest('.material-item, .feature-item, .option-item, .pricing-tier-item, .size-tier-item').remove();
        }

        // Modal الإضافة السريعة
        function openQuickAddModal(type) {
            currentQuickAddType = type;
            let title = '';
            let formContent = '';

            switch (type) {
                case 'category':
                    title = 'إضافة قسم جديد';
                    formContent = `
                        <div class="mb-3">
                            <label class="form-label">اسم القسم</label>
                            <input type="text" class="form-control" id="category_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">القسم الرئيسي</label>
                            <select class="form-select" id="parent_category_id">
                                <option value="">بدون قسم رئيسي</option>
                                @foreach ($categories as $category)
                                    @if ($category->isParent())
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    `;
                    break;
                    
                case 'color':
                    title = 'إضافة لون جديد';
                    formContent = `
                        <div class="mb-3">
                            <label class="form-label">اسم اللون</label>
                            <input type="text" class="form-control" id="color_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الكود اللوني</label>
                            <div class="d-flex align-items-center gap-3">
                                <input type="color" id="color_hex" value="#696cff" 
                                       style="width: 60px; height: 60px; border-radius: 10px;">
                                <input type="text" class="form-control" id="color_hex_code" value="#696cff">
                            </div>
                        </div>
                    `;
                    break;
                    
                case 'material':
                    title = 'إضافة مادة جديدة';
                    formContent = `
                        <div class="mb-3">
                            <label class="form-label">اسم المادة</label>
                            <input type="text" class="form-control" id="material_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea class="form-control" id="material_description" rows="3"></textarea>
                        </div>
                    `;
                    break;
                    
                case 'printing_method':
                    title = 'إضافة طريقة طباعة جديدة';
                    formContent = `
                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" class="form-control" id="printing_method_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الوصف</label>
                            <textarea class="form-control" id="printing_method_description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">السعر الأساسي</label>
                            <input type="number" class="form-control" id="printing_method_price" 
                                   step="0.01" min="0" required>
                        </div>
                    `;
                    break;
                    
                case 'print_location':
                    title = 'إضافة مكان طباعة جديد';
                    formContent = `
                        <div class="mb-3">
                            <label class="form-label">الاسم</label>
                            <input type="text" class="form-control" id="print_location_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">النوع</label>
                            <select class="form-select" id="print_location_type">
                                <option value="front">أمامي</option>
                                <option value="back">خلفي</option>
                                <option value="side">جانبي</option>
                                <option value="sleeve">كم</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">السعر الإضافي</label>
                            <input type="number" class="form-control" id="print_location_price" 
                                   step="0.01" min="0" required>
                        </div>
                    `;
                    break;
                    
                case 'offer':
                    title = 'إضافة عرض جديد';
                    formContent = `
                        <div class="mb-3">
                            <label class="form-label">اسم العرض</label>
                            <input type="text" class="form-control" id="offer_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">الصورة</label>
                            <input type="file" class="form-control" id="offer_image" accept="image/*">
                        </div>
                    `;
                    break;
            }

            $('#quickAddModalTitle').text(title);
            $('#quickAddFormContent').html(formContent);

            if (type === 'color') {
                $('#color_hex').change(function() {
                    $('#color_hex_code').val($(this).val());
                });
            }

            new bootstrap.Modal(document.getElementById('quickAddModal')).show();
        }

        async function saveQuickAdd() {
            const formData = new FormData();
            
            switch (currentQuickAddType) {
                case 'category':
                    formData.append('name', $('#category_name').val());
                    formData.append('parent_id', $('#parent_category_id').val());
                    break;
                    
                case 'color':
                    formData.append('name', $('#color_name').val());
                    formData.append('hex_code', $('#color_hex_code').val());
                    break;
                    
                case 'material':
                    formData.append('name', $('#material_name').val());
                    formData.append('description', $('#material_description').val());
                    break;
                    
                case 'printing_method':
                    formData.append('name', $('#printing_method_name').val());
                    formData.append('description', $('#printing_method_description').val());
                    formData.append('base_price', $('#printing_method_price').val());
                    break;
                    
                case 'print_location':
                    formData.append('name', $('#print_location_name').val());
                    formData.append('type', $('#print_location_type').val());
                    formData.append('additional_price', $('#print_location_price').val());
                    break;
                    
                case 'offer':
                    formData.append('name', $('#offer_name').val());
                    if ($('#offer_image')[0].files[0]) {
                        formData.append('image', $('#offer_image')[0].files[0]);
                    }
                    break;
            }

            try {
                showLoading();
                
                const response = await fetch(`/admin/quick-add/${currentQuickAddType}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                hideLoading();

                if (response.ok) {
                    bootstrap.Modal.getInstance(document.getElementById('quickAddModal')).hide();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'تمت الإضافة',
                        text: data.message || 'تم الإضافة بنجاح',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    updateUIAfterQuickAdd(data);
                } else {
                    throw new Error(data.message || 'حدث خطأ أثناء الإضافة');
                }

            } catch (error) {
                hideLoading();
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: error.message
                });
            }
        }

        function updateUIAfterQuickAdd(data) {
            switch (currentQuickAddType) {
                case 'category':
                    const option = new Option(data.item.name, data.item.id);
                    $('#category_id').append(option).val(data.item.id).trigger('change');
                    break;
                    
                case 'color':
                    const colorItem = `
                        <div class="color-item" 
                             style="background-color: ${data.item.hex_code};"
                             data-id="${data.item.id}"
                             data-name="${data.item.name}"
                             data-hex="${data.item.hex_code}"
                             onclick="addColor(this)"
                             title="${data.item.name}">
                        </div>
                    `;
                    $('#availableColorsGrid').append(colorItem);
                    break;
                    
                case 'material':
                    $('.material-select').each(function() {
                        const option = new Option(data.item.name, data.item.id);
                        $(this).append(option);
                    });
                    break;
                    
                case 'printing_method':
                    const printingOption = new Option(
                        `${data.item.name} - ${data.item.base_price} ج.م`,
                        data.item.id
                    );
                    $('#printingMethodsSelect').append(printingOption);
                    break;
                    
                case 'print_location':
                    const locationOption = new Option(
                        `${data.item.name} (${data.item.type}) - ${data.item.additional_price} ج.م`,
                        data.item.id
                    );
                    $('#printLocationsSelect').append(locationOption);
                    break;
                    
                case 'offer':
                    const offerOption = new Option(data.item.name, data.item.id);
                    $('#offersSelect').append(offerOption);
                    break;
            }
        }

        // توليد الرابط التلقائي
        function generateSlug() {
            const name = $('#name').val();
            if (!name) return;

            let slug = name
                .toLowerCase()
                .replace(/[^\u0600-\u06FF\w\s]/g, '')
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-')
                .trim();

            slug += '-' + productId;
            $('#slug').val(slug);
        }

        // حفظ ومتابعة
        function saveAndContinue() {
            if (validateForm()) {
                $('#editProductForm').submit();
            }
        }

        // التحقق من النموذج
        function validateForm() {
            const name = $('#name').val();
            const price = $('#price').val();
            const stock = $('#stock').val();
            const category = $('#category_id').val();

            if (!name || !price || !stock || !category) {
                Swal.fire({
                    icon: 'warning',
                    title: 'بيانات ناقصة',
                    text: 'يرجى ملء جميع الحقول المطلوبة',
                    confirmButtonColor: '#3085d6'
                });
                return false;
            }

            if ($('#has_discount').is(':checked')) {
                const discountValue = $('#discount_value').val();
                if (!discountValue || discountValue <= 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'قيمة خصم غير صالحة',
                        text: 'يرجى إدخال قيمة خصم صحيحة',
                        confirmButtonColor: '#3085d6'
                    });
                    return false;
                }
            }

            return true;
        }

        // وظائف المساعدة
        function showLoading() {
            if (!$('#loadingOverlay').length) {
                $('body').append(`
                    <div class="loading-overlay" id="loadingOverlay">
                        <div class="spinner"></div>
                        <div class="mt-3">جاري المعالجة...</div>
                    </div>
                `);
            }
            $('#loadingOverlay').addClass('active');
        }

        function hideLoading() {
            $('#loadingOverlay').removeClass('active');
        }

        function showMessage(type, text) {
            const messageId = 'message-' + Date.now();
            const messageHtml = `
                <div class="alert alert-${type} alert-dismissible fade show message" id="${messageId}">
                    ${text}
                    <button type="button" class="btn-close" onclick="$('#${messageId}').remove()"></button>
                </div>
            `;
            $('body').append(messageHtml);
            setTimeout(() => {
                $(`#${messageId}`).addClass('show');
                setTimeout(() => {
                    $(`#${messageId}`).removeClass('show');
                    setTimeout(() => $(`#${messageId}`).remove(), 500);
                }, 3000);
            }, 100);
        }

        // منع الإرسال إذا كانت هناك أخطاء
        $('#editProductForm').on('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
            }
        });
    </script>
@endsection