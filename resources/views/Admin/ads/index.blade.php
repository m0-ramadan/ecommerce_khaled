{{-- resources/views/Admin/ads/index.blade.php --}}
@extends('Admin.layout.master')

@section('title', 'إدارة الإعلانات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
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
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #1e293b;
        }

        .ads-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
            padding: 30px;
            border: 1px solid #e2e8f0;
        }

        .ads-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            border-radius: 15px 15px 0 0;
        }

        .stats-card {
            background: var(--dark-card);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(15, 23, 42, 0.05);
            border-top: 4px solid var(--primary-color);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(15, 23, 42, 0.05);
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

        .icon-total {
            background: var(--primary-gradient);
            color: white;
        }

        .icon-types {
            background: rgba(12, 99, 228, 0.2);
            color: #0284c7;
            border: 1px solid rgba(12, 99, 228, 0.3);
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

        .filter-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding-right: 40px;
            border-radius: 25px;
            background: #f8fafc;
            border-color: #e2e8f0;
            color: #1e293b;
        }

        .search-box input:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #1e293b;
            box-shadow: 0 0 0 0.25rem rgba(20, 33, 61, 0.25);
        }

        .search-box .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        .ads-item {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            border-right: 4px solid transparent;
            border: 1px solid #e2e8f0;
        }

        .ads-item:hover {
            transform: translateX(-5px);
            box-shadow: 0 5px 15px rgba(15, 23, 42, 0.05);
            background: rgba(20, 33, 61, 0.1);
            border-color: var(--primary-color);
        }

        .ads-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            background: #ebf0f8;
            color: #1e3a5f;
            border: 1px solid #cbd7ea;
        }

        .ads-type {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .type-banner {
            background: rgba(20, 33, 61, 0.2);
            color: #14213d;
            border: 1px solid rgba(20, 33, 61, 0.3);
        }

        .type-popup {
            background: rgba(30, 58, 95, 0.2);
            color: #14213d;
            border: 1px solid rgba(30, 58, 95, 0.3);
        }

        .type-sidebar {
            background: rgba(255, 193, 7, 0.2);
            color: #f59e0b;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .type-footer {
            background: rgba(23, 162, 184, 0.2);
            color: #06b6d4;
            border: 1px solid rgba(23, 162, 184, 0.3);
        }

        .ads-header-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ads-description {
            color: #1e293b;
            line-height: 1.6;
            margin: 15px 0;
        }

        .ads-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .empty-state {
            text-align: center;
            padding: 50px 20px;
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

        .btn-info {
            background: rgba(23, 162, 184, 0.2);
            border: 1px solid rgba(23, 162, 184, 0.3);
            color: #06b6d4;
        }

        .btn-info:hover {
            background: #06b6d4;
            color: white;
        }

        .btn-warning {
            background: rgba(255, 193, 7, 0.2);
            border: 1px solid rgba(255, 193, 7, 0.3);
            color: #f59e0b;
        }

        .btn-warning:hover {
            background: #f59e0b;
            color: #000;
        }

        .btn-danger {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.3);
            color: #ef4444;
        }

        .btn-danger:hover {
            background: #ef4444;
            color: white;
        }

        /* Modal Styles */
        .modal-content {
            background: var(--dark-card);
            border: 1px solid #e2e8f0;
            border-radius: 15px;
        }

        .modal-header {
            background: var(--primary-gradient);
            color: white;
            border-radius: 15px 15px 0 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .modal-title {
            color: #1e293b;
        }

        .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .modal-body {
            color: #1e293b;
        }

        .form-label {
            color: #1e293b;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control, .form-select {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            border-radius: 10px;
            padding: 10px 15px;
        }

        .form-control:focus, .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #1e293b;
            box-shadow: 0 0 0 0.25rem rgba(20, 33, 61, 0.25);
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-select option {
            background: var(--dark-card);
            color: #1e293b;
        }

        textarea.form-control {
            min-height: 100px;
        }

        .icon-preview {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: var(--primary-gradient);
            color: white;
            margin-top: 10px;
        }

        @media (max-width: 768px) {
            .ads-header-info {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .ads-actions {
                flex-wrap: wrap;
            }
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
                <li class="breadcrumb-item active">الإعلانات</li>
            </ol>
        </nav>

        <!-- الإحصائيات -->
        <div class="row mb-4" bis_skin_checked="1">
            <div class="col-lg-4 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-total" bis_skin_checked="1">
                        <i class="fas fa-ad"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ number_format($stats['total'] ?? 0) }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">إجمالي الإعلانات</div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-types" bis_skin_checked="1">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ number_format($stats['types_count'] ?? 0) }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">أنواع الإعلانات</div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon" style="background: rgba(30, 58, 95, 0.2); color: #14213d;" bis_skin_checked="1">
                        <i class="fas fa-icons"></i>
                    </div>
                    <div class="stats-number" bis_skin_checked="1">
                        {{ number_format($stats['with_icons'] ?? 0) }}
                    </div>
                    <div class="stats-label" bis_skin_checked="1">إعلانات بأيقونات</div>
                </div>
            </div>
        </div>

        <!-- شريط البحث والإضافة -->
        <div class="filter-card" bis_skin_checked="1">
            <div class="row" bis_skin_checked="1">
                <div class="col-md-8" bis_skin_checked="1">
                    <div class="search-box" bis_skin_checked="1">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" class="form-control" placeholder="بحث في الإعلانات..." id="searchInput" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4" bis_skin_checked="1">
                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#addAdsModal">
                        <i class="fas fa-plus me-2"></i>إضافة إعلان جديد
                    </button>
                </div>
            </div>
        </div>

        <!-- قائمة الإعلانات -->
        <div class="row" bis_skin_checked="1">
            <div class="col-12" bis_skin_checked="1">
                <div class="ads-card" bis_skin_checked="1">
                    <div class="ads-header" bis_skin_checked="1">
                        <div class="d-flex justify-content-between align-items-center" bis_skin_checked="1">
                            <div bis_skin_checked="1">
                                <h5 class="mb-0">قائمة الإعلانات</h5>
                                <small class="opacity-75">إدارة جميع إعلانات الموقع</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body" bis_skin_checked="1">
                        @if ($ads->isEmpty())
                            <div class="empty-state" bis_skin_checked="1">
                                <div class="empty-state-icon" bis_skin_checked="1">
                                    <i class="fas fa-ad"></i>
                                </div>
                                <h5 class="empty-state-text">لا توجد إعلانات</h5>
                                <p class="text-muted">لم يتم إضافة أي إعلانات حتى الآن</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAdsModal">
                                    <i class="fas fa-plus me-2"></i>إضافة إعلان جديد
                                </button>
                            </div>
                        @else
                            @foreach ($ads as $ad)
                                <div class="ads-item" bis_skin_checked="1" id="ad-{{ $ad->id }}">
                                    <div class="ads-header-info" bis_skin_checked="1">
                                        <div class="d-flex align-items-center gap-3" bis_skin_checked="1">
                                            <div class="ads-icon" bis_skin_checked="1">
                                                @php
                                                    $adIcon = trim($ad->icon ?? 'fas fa-bullhorn');
                                                    if (!str_contains($adIcon, 'fa ') && !str_starts_with($adIcon, 'fas ') && !str_starts_with($adIcon, 'far ') && !str_starts_with($adIcon, 'fab ') && !str_starts_with($adIcon, 'ti ')) {
                                                        $adIcon = 'fas ' . $adIcon;
                                                    }
                                                @endphp
                                                <i class="{{ $adIcon }}"></i>
                                            </div>
                                            <div bis_skin_checked="1">
                                                <span class="ads-type type-{{ $ad->type }}">
                                                    {{ $ad->type }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="text-muted" bis_skin_checked="1">
                                            <small>تاريخ الإضافة: {{ $ad->created_at->translatedFormat('d M Y - h:i A') }}</small>
                                        </div>
                                    </div>

                                    <div class="ads-description" bis_skin_checked="1">
                                        {{ $ad->description }}
                                    </div>

                                    <div class="ads-actions" bis_skin_checked="1">
                                        <button type="button" class="btn btn-sm btn-info" onclick="editAd({{ $ad->id }})" data-bs-toggle="modal" data-bs-target="#editAdsModal">
                                            <i class="fas fa-eye me-1"></i>عرض
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="editAd({{ $ad->id }})" data-bs-toggle="modal" data-bs-target="#editAdsModal">
                                            <i class="fas fa-edit me-1"></i>تعديل
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" data-id="{{ $ad->id }}" data-description="{{ Str::limit($ad->description, 30) }}">
                                            <i class="fas fa-trash me-1"></i>حذف
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                            @if ($ads->hasPages())
                                <div class="m-3">
                                    <nav>
                                        <ul class="pagination">
                                            {{-- Previous Page Link --}}
                                            @if ($ads->onFirstPage())
                                                <li class="page-item disabled" aria-disabled="true">
                                                    <span class="page-link waves-effect" aria-hidden="true">‹</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link waves-effect" href="{{ $ads->previousPageUrl() }}" rel="prev">‹</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach ($ads->links()->elements[0] as $page => $url)
                                                @if ($page == $ads->currentPage())
                                                    <li class="page-item active" aria-current="page">
                                                        <span class="page-link waves-effect">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link waves-effect" href="{{ $url }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            {{-- Next Page Link --}}
                                            @if ($ads->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link waves-effect" href="{{ $ads->nextPageUrl() }}" rel="next">›</a>
                                                </li>
                                            @else
                                                <li class="page-item disabled" aria-disabled="true">
                                                    <span class="page-link waves-effect" aria-hidden="true">›</span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal إضافة إعلان جديد -->
    <div class="modal fade" id="addAdsModal" tabindex="-1" aria-labelledby="addAdsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdsModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>إضافة إعلان جديد
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.ads.store') }}" method="POST" id="addAdsForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">نوع الإعلان</label>
                                <select class="form-select" id="type" name="type" required>
                                    <option value="">اختر نوع الإعلان</option>
                                    <option value="banner">بانر</option>
                                    <option value="popup">نافذة منبثقة</option>
                                    <option value="sidebar">شريط جانبي</option>
                                    <option value="footer">تذييل</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="icon" class="form-label">أيقونة الإعلان</label>
                                <input type="text" class="form-control" id="icon" name="icon" placeholder="مثال: fas fa-ad" required>
                                <small class="text-muted">أدخل كلاس الأيقونة من Font Awesome</small>
                                <div class="icon-preview mt-2" id="iconPreview">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="description" class="form-label">وصف الإعلان</label>
                                <textarea class="form-control" id="description" name="description" rows="4" placeholder="أدخل نص الإعلان هنا..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitAddForm">
                            <i class="fas fa-save me-2"></i>حفظ الإعلان
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal تعديل إعلان -->
    <div class="modal fade" id="editAdsModal" tabindex="-1" aria-labelledby="editAdsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAdsModalLabel">
                        <i class="fas fa-edit me-2"></i>تعديل الإعلان
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" id="editAdsForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_type" class="form-label">نوع الإعلان</label>
                                <select class="form-select" id="edit_type" name="type" required>
                                    <option value="">اختر نوع الإعلان</option>
                                    <option value="banner">بانر</option>
                                    <option value="popup">نافذة منبثقة</option>
                                    <option value="sidebar">شريط جانبي</option>
                                    <option value="footer">تذييل</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_icon" class="form-label">أيقونة الإعلان</label>
                                <input type="text" class="form-control" id="edit_icon" name="icon" placeholder="مثال: fas fa-ad" required>
                                <small class="text-muted">أدخل كلاس الأيقونة من Font Awesome</small>
                                <div class="icon-preview mt-2" id="editIconPreview">
                                    <i class="fas fa-image"></i>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="edit_description" class="form-label">وصف الإعلان</label>
                                <textarea class="form-control" id="edit_description" name="description" rows="4" placeholder="أدخل نص الإعلان هنا..." required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitEditForm">
                            <i class="fas fa-save me-2"></i>تحديث الإعلان
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal عرض إعلان -->
    <div class="modal fade" id="viewAdsModal" tabindex="-1" aria-labelledby="viewAdsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewAdsModalLabel">
                        <i class="fas fa-info-circle me-2"></i>تفاصيل الإعلان
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="ads-icon mx-auto" style="width: 80px; height: 80px; font-size: 36px;" id="viewIcon">
                            <i class="fas fa-ad"></i>
                        </div>
                    </div>
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">نوع الإعلان:</th>
                            <td>
                                <span class="ads-type" id="viewType">banner</span>
                            </td>
                        </tr>
                        <tr>
                            <th>الأيقونة:</th>
                            <td id="viewIconClass">fas fa-ad</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإضافة:</th>
                            <td id="viewDate">2024-01-01</td>
                        </tr>
                        <tr>
                            <th>آخر تحديث:</th>
                            <td id="viewUpdated">2024-01-01</td>
                        </tr>
                        <tr>
                            <th>الوصف:</th>
                            <td id="viewDescription">نص الإعلان هنا</td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>إغلاق
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // معاينة الأيقونة في نموذج الإضافة
            $('#icon').on('keyup', function() {
                const iconClass = $(this).val() || 'fas fa-image';
                $('#iconPreview i').attr('class', iconClass);
            });

            // معاينة الأيقونة في نموذج التعديل
            $('#edit_icon').on('keyup', function() {
                const iconClass = $(this).val() || 'fas fa-image';
                $('#editIconPreview i').attr('class', iconClass);
            });

            // البحث مع تأخير
            let searchTimeout;
            $('#searchInput').on('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const search = $(this).val();
                    const url = new URL(window.location.href);
                    if (search) {
                        url.searchParams.set('search', search);
                    } else {
                        url.searchParams.delete('search');
                    }
                    url.searchParams.set('page', '1');
                    window.location.href = url.toString();
                }, 500);
            });

            // حذف الإعلان
            $('.delete-btn').on('click', function() {
                const adId = $(this).data('id');
                const adDescription = $(this).data('description');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: `سيتم حذف الإعلان "${adDescription}" نهائياً`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#14213d',
                    confirmButtonText: 'نعم، احذف',
                    cancelButtonText: 'إلغاء',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.ads.destroy', '') }}/" + adId,
                            type: 'POST',
                            data: {
                                _token: "{{ csrf_token() }}",
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم الحذف',
                                    text: response.success || 'تم حذف الإعلان بنجاح',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    $('#ad-' + adId).fadeOut(300, function() {
                                        $(this).remove();
                                        if ($('.ads-item').length === 0) {
                                            location.reload();
                                        }
                                    });
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: xhr.responseJSON?.error || 'حدث خطأ أثناء الحذف',
                                });
                            }
                        });
                    }
                });
            });

            // رسائل التنبيه من الجلسة
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: "{{ session('error') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            @endif
        });

        // دالة تعديل الإعلان
        function editAd(id) {
            $.ajax({
                url: "{{ route('admin.ads.show', '') }}/" + id,
                type: 'GET',
                success: function(response) {
                    // تعبئة النموذج ببيانات الإعلان
                    $('#edit_type').val(response.type);
                    $('#edit_icon').val(response.icon);
                    $('#edit_description').val(response.description);
                    $('#editIconPreview i').attr('class', response.icon);
                    
                    // تحديث رابط النموذج
                    $('#editAdsForm').attr('action', "{{ route('admin.ads.update', '') }}/" + id);
                    
                    // فتح المودال
                    $('#editAdsModal').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء جلب بيانات الإعلان',
                    });
                }
            });
        }

        // دالة عرض الإعلان
        function viewAd(id) {
            $.ajax({
                url: "{{ route('admin.ads.show', '') }}/" + id,
                type: 'GET',
                success: function(response) {
                    // تعبيلة التفاصيل
                    $('#viewIcon i').attr('class', response.icon);
                    $('#viewType').text(response.type).attr('class', 'ads-type type-' + response.type);
                    $('#viewIconClass').text(response.icon);
                    $('#viewDescription').text(response.description);
                    $('#viewDate').text(response.created_at);
                    $('#viewUpdated').text(response.updated_at);
                    
                    // فتح المودال
                    $('#viewAdsModal').modal('show');
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ',
                        text: 'حدث خطأ أثناء جلب بيانات الإعلان',
                    });
                }
            });
        }

        // منع إرسال النموذج إذا كانت الحقول فارغة
        $('#addAdsForm').on('submit', function(e) {
            const type = $('#type').val();
            const icon = $('#icon').val();
            const description = $('#description').val();

            if (!type || !icon || !description) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'يرجى ملء جميع الحقول المطلوبة',
                });
            }
        });

        $('#editAdsForm').on('submit', function(e) {
            const type = $('#edit_type').val();
            const icon = $('#edit_icon').val();
            const description = $('#edit_description').val();

            if (!type || !icon || !description) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: 'يرجى ملء جميع الحقول المطلوبة',
                });
            }
        });
    </script>
@endsection