<style>
    /*
    ملف الأنماط الموحد للبانرات
    يحتوي على كل الأنماط للصفحات: الإنشاء، التعديل، العرض، الفهرس
*/

    /* متغيرات الألوان - تيم مظلم */
    :root {
        --primary-color: #14213d;
        --primary-gradient: linear-gradient(135deg, #14213d 0%, #0c182a 100%);
        --success-color: #14213d;
        --danger-color: #ef4444;
        --warning-color: #f59e0b;
        --info-color: #06b6d4;
        --light-bg: #f8f9fa;
        --border-color: #e9ecef;
        --text-muted: #6c757d;
        --dark-bg: #f6f8fb;
        --dark-card: #ffffff;
        --light-text: #1e293b;
        --dark-text: #212529;
    }

    /* أنماط عامة */
    body {
        font-family: "Cairo", sans-serif !important;
        background: var(--dark-bg);
        color: var(--light-text);
    }

    /* ===== أنماط النماذج (إنشاء وتعديل) ===== */
    .form-card {
        background: var(--dark-card);
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        padding: 30px;
        margin-bottom: 30px;
        border: 1px solid #e2e8f0;
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
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
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

    /* خيارات الأقسام */
    .category-options {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .category-option {
        flex: 1;
        text-align: center;
        padding: 15px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .category-option:hover {
        border-color: var(--primary-color);
        background: rgba(20, 33, 61, 0.1);
    }

    .category-option.active {
        border-color: var(--primary-color);
        background: rgba(20, 33, 61, 0.2);
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

    /* بطاقات الأنواع */
    .type-card {
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        background: #f8fafc;
        height: 100%;
    }

    .type-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-5px);
        background: rgba(20, 33, 61, 0.1);
    }

    .type-card.active {
        border-color: var(--primary-color);
        background: rgba(20, 33, 61, 0.2);
        box-shadow: 0 5px 15px rgba(20, 33, 61, 0.3);
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

    /* إعدادات السلايدر والشبكة */
    .grid-settings,
    .slider-settings {
        background: #f8fafc;
        padding: 20px;
        border-radius: 10px;
        margin-top: 20px;
        border: 2px dashed #e2e8f0;
    }

    /* رفع الصور */
    .image-upload-box {
        border: 2px dashed #e2e8f0;
        border-radius: 10px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .image-upload-box:hover {
        border-color: var(--primary-color);
        background: rgba(20, 33, 61, 0.1);
    }

    .image-upload-box i {
        font-size: 48px;
        color: var(--text-muted);
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
        border: 2px solid #e2e8f0;
    }

    .mobile-image-preview {
        width: 150px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 10px;
        border: 2px solid #e2e8f0;
    }

    /* الباجات */
    .badge-custom {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-active {
        background: linear-gradient(135deg, #14213d 0%, #14213d 100%);
        color: white;
    }

    .badge-inactive {
        background: linear-gradient(135deg, #ef4444 0%, #f59e0b 100%);
        color: white;
    }

    /* باجات الأنواع */
    .badge-slider {
        background: linear-gradient(135deg, #14213d 0%, #1e3a5f 100%);
        color: white;
    }

    .badge-grid {
        background: #e7f5ff;
        color: #0284c7;
    }

    .badge-static {
        background: #ebf0f8;
        color: #14213d;
    }

    .badge-category {
        background: #fff3cd;
        color: #856404;
    }

    .badge-main {
        background: #cfe2ff;
        color: #084298;
    }

    .badge-cat {
        background: #d1e7dd;
        color: #0f5132;
    }

    /* مفتاح التبديل */
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

    /* العناصر */
    .items-container {
        margin-top: 30px;
    }

    .item-card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 15px;
        background: #f8fafc;
        transition: all 0.3s ease;
    }

    .item-card:hover {
        border-color: var(--primary-color);
        background: rgba(20, 33, 61, 0.1);
    }

    .item-image {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
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
        color: var(--text-muted);
    }

    .empty-items i {
        font-size: 48px;
        margin-bottom: 15px;
        color: #94a3b8;
    }

    /* ===== أنماط صفحة العرض (show) ===== */
    .detail-card {
        background: var(--dark-card);
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .detail-header {
        background: var(--primary-gradient);
        color: white;
        padding: 25px;
    }

    .detail-content {
        padding: 30px;
    }

    .info-section {
        margin-bottom: 30px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #f8fafc;
    }

    .info-section h5 {
        color: var(--primary-color);
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 10px;
        margin-bottom: 20px;
    }

    .info-item {
        display: flex;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #e2e8f0;
    }

    .info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        font-weight: 600;
        color: #adb5bd;
        min-width: 150px;
    }

    .info-value {
        color: var(--text-muted);
        flex: 1;
    }

    .preview-container {
        background: #f8fafc;
        border-radius: 10px;
        padding: 20px;
        margin-top: 20px;
        border: 1px solid #e2e8f0;
    }

    /* ===== أنماط صفحة الفهرس (index) ===== */
    .table-card {
        background: var(--dark-card);
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .table-header {
        background: var(--primary-gradient);
        color: white;
        padding: 20px 25px;
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        padding-right: 40px;
        border-radius: 25px;
        border: 1px solid #e2e8f0;
        background: rgba(255, 255, 255, 0.1);
        color: #1e293b;
    }

    .search-box input::placeholder {
        color: #64748b;
    }

    .search-box .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #1e293b;
    }

    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .filter-tab {
        padding: 8px 20px;
        border-radius: 25px;
        background: rgba(255, 255, 255, 0.1);
        color: #1e293b;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
    }

    .filter-tab:hover {
        background: #f1f5f9;
    }

    .filter-tab.active {
        background: var(--primary-color);
        color: white;
        border-color: var(--primary-color);
    }

    .stats-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        border-top: 4px solid var(--primary-color);
        transition: transform 0.3s ease;
        margin-bottom: 20px;
    }

    .stats-card:hover {
        transform: translateY(-5px);
    }

    .stats-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 15px;
    }

    .icon-banner {
        background: var(--primary-gradient);
        color: white;
    }

    .icon-slider {
        background: rgba(231, 245, 255, 0.1);
        color: #0284c7;
    }

    .icon-grid {
        background: rgba(248, 249, 250, 0.1);
        color: #adb5bd;
    }

    .icon-active {
        background: rgba(212, 237, 218, 0.1);
        color: #14213d;
    }

    .stats-number {
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 5px;
        color: #1e293b;
    }

    .stats-label {
        color: #64748b;
        font-size: 14px;
    }

    .banner-preview {
        width: 100px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 5px rgba(15, 23, 42, 0.05);
    }

    .banner-preview-placeholder {
        width: 100px;
        height: 60px;
        border-radius: 8px;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        border: 2px solid rgba(255, 255, 255, 0.1);
    }

    .banner-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .banner-details h6 {
        margin-bottom: 5px;
        font-weight: 600;
        color: #1e293b;
    }

    .banner-details p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
    }

    .type-icons {
        display: flex;
        gap: 5px;
        margin-top: 5px;
    }

    .type-icon-small {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        color: #1e293b;
    }

    .icon-slider-type {
        background: #14213d;
    }

    .icon-grid-type {
        background: #0284c7;
    }

    .icon-static-type {
        background: #14213d;
    }

    .category-badge {
        display: inline-block;
        padding: 4px 12px;
        background: rgba(231, 245, 255, 0.1);
        color: #0284c7;
        border-radius: 15px;
        font-size: 12px;
        margin-top: 5px;
    }

    .items-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: var(--primary-color);
        color: #1e293b;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 600;
    }

    .sort-dropdown {
        position: relative;
        display: inline-block;
    }

    .sort-btn {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #1e293b;
        padding: 8px 15px;
        border-radius: 25px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sort-dropdown-content {
        display: none;
        position: absolute;
        background: var(--dark-card);
        min-width: 200px;
        box-shadow: 0 8px 16px rgba(15, 23, 42, 0.05);
        border-radius: 10px;
        z-index: 1;
        padding: 10px 0;
        margin-top: 5px;
        border: 1px solid #e2e8f0;
    }

    .sort-dropdown:hover .sort-dropdown-content {
        display: block;
    }

    .sort-item {
        padding: 10px 20px;
        cursor: pointer;
        transition: background 0.3s;
        color: #1e293b;
    }

    .sort-item:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .sort-item.active {
        background: var(--primary-color);
        color: white;
    }

    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #64748b;
    }

    .empty-state-icon {
        font-size: 60px;
        color: #94a3b8;
        margin-bottom: 20px;
    }

    .empty-state-text {
        color: #64748b;
        margin-bottom: 20px;
    }

    /* ===== أنماط عامة للمكونات ===== */
    .form-control {
        background: #f8fafc;
        border-color: #e2e8f0;
        color: #1e293b;
    }

    .form-control:focus {
        background: rgba(255, 255, 255, 0.1);
        border-color: var(--primary-color);
        color: #1e293b;
        box-shadow: 0 0 0 0.25rem rgba(20, 33, 61, 0.25);
    }

    .form-control::placeholder {
        color: #64748b;
    }

    .input-group-text {
        background: rgba(255, 255, 255, 0.1);
        border-color: #e2e8f0;
        color: #1e293b;
    }

    .form-check-input {
        background-color: rgba(255, 255, 255, 0.1);
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
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #14213d 0%, #1e3a5f 100%);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(20, 33, 61, 0.4);
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid #e2e8f0;
        color: #1e293b;
    }

    .btn-secondary:hover {
        background: #f1f5f9;
        border-color: #e2e8f0;
    }

    .btn-outline-primary {
        color: var(--primary-color);
        border-color: var(--primary-color);
    }

    .btn-outline-primary:hover {
        background: var(--primary-color);
        color: white;
    }

    .breadcrumb {
        background: #f8fafc;
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

    /* Select2 أنماط */
    .select2-container--default .select2-selection--multiple,
    .select2-container--default .select2-selection--single {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        min-height: 38px;
        color: #1e293b;
    }

    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: var(--primary-color);
        color: #1e293b;
        border: none;
        border-radius: 15px;
        padding: 2px 10px;
    }

    .select2-container--default .select2-selection__rendered {
        color: #1e293b !important;
    }

    /* Modal أنماط */
    .modal-content {
        background: var(--dark-card);
        border: 1px solid #e2e8f0;
    }

    .modal-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .modal-title {
        color: #1e293b;
    }

    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .modal-footer {
        border-top: 1px solid #e2e8f0;
    }

    /* المساعدات */
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

    /* الرسوم المتحركة */
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

    /* التجاوب */
    @media (max-width: 768px) {

        .category-options,
        .filter-tabs {
            flex-direction: column;
        }

        .type-card {
            margin-bottom: 15px;
        }

        .item-card,
        .banner-info {
            flex-direction: column;
        }

        .item-image {
            margin-bottom: 15px;
        }

        .item-actions,
        .action-buttons {
            justify-content: center;
        }

        .info-item {
            flex-direction: column;
        }

        .info-label {
            margin-bottom: 5px;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
        }
    }
</style>
