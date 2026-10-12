@extends('Admin.layout.master')

@section('title', 'خدمات القرطاسية والطباعة - إدارة الأسعار')

@section('css')
<style>
    .card-header-gradient {
        background: linear-gradient(135deg, #14213d 0%, #1f3158 100%);
        color: #fff;
    }
    .badge-size {
        font-size: 1rem;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 8px;
    }
    .price-input-group .input-group-text {
        background-color: #f8fafc;
        font-weight: 600;
        color: #64748b;
    }
    .nav-tabs .nav-link {
        font-weight: 700;
        font-size: 1rem;
        color: #475569;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 24px;
    }
    .nav-tabs .nav-link.active {
        color: #ff7300;
        border-bottom: 3px solid #ff7300;
        background: transparent;
    }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="ti ti-printer text-warning me-2"></i>خدمات القرطاسية والطباعة الفورية
            </h4>
            <p class="text-muted mb-0">تحكم كامل في أسعار طباعة المستندات والصور لجميع المقاسات (A0 - A5)</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.stationery.orders') }}" class="btn btn-outline-primary d-flex align-items-center gap-1 shadow-sm">
                <i class="ti ti-receipt me-1"></i>
                طلبات القرطاسية
                <span class="badge bg-primary rounded-pill ms-1">{{ $stationeryOrdersCount }}</span>
            </a>
            <button type="submit" form="pricingForm" class="btn btn-primary d-flex align-items-center gap-1 shadow-sm">
                <i class="ti ti-device-floppy me-1"></i>
                حفظ كافة الأسعار
            </button>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm mb-4" role="alert">
            <i class="ti ti-check me-2 fs-4"></i>
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Main Form --}}
    <form id="pricingForm" action="{{ route('admin.stationery.update') }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Tabs --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header border-bottom bg-white p-2">
                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active d-flex align-items-center gap-2" data-bs-toggle="tab" data-bs-target="#documents-tab">
                            <i class="ti ti-file-text fs-5"></i>
                            <span>طباعة المستندات (Documents)</span>
                            <span class="badge bg-label-primary rounded-pill">{{ $documentPrices->count() }} مقاسات</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link d-flex align-items-center gap-2" data-bs-toggle="tab" data-bs-target="#photos-tab">
                            <i class="ti ti-photo fs-5"></i>
                            <span>طباعة الصور (Photos)</span>
                            <span class="badge bg-label-info rounded-pill">{{ $photoPrices->count() }} مقاسات</span>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body p-0">
                <div class="tab-content p-4">
                    {{-- Documents Tab --}}
                    <div class="tab-pane fade show active" id="documents-tab" role="tabpanel">
                        <div class="alert alert-primary bg-primary-subtle border-0 mb-4 d-flex align-items-center">
                            <i class="ti ti-info-circle fs-3 me-2 text-primary"></i>
                            <div>
                                <strong>طباعة المستندات والملفات (PDF / Word):</strong>
                                يمكنك تحديد <strong>سعر الورقة الواحدة (المفردة)</strong> بشكل منفصل عن <strong>سعر الورقة داخل ملف كامل</strong> لكل مقاس ورق (A0 - A5)، مع خيار إضافة تكلفة طباعة الوجهين.
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle text-center" style="width: 14%">المقاس والأبعاد</th>
                                        <th colspan="2" class="text-center bg-primary-subtle text-primary border-bottom py-2 fw-bold" style="width: 32%">
                                            <i class="ti ti-file me-1"></i> طباعة الورقة الواحدة (ورقة مفردة)
                                        </th>
                                        <th colspan="2" class="text-center bg-info-subtle text-info border-bottom py-2 fw-bold" style="width: 32%">
                                            <i class="ti ti-folders me-1"></i> طباعة ملف كامل (سعر الورقة فيه)
                                        </th>
                                        <th rowspan="2" class="text-center align-middle" style="width: 14%">إضافة وجهين</th>
                                        <th rowspan="2" class="text-center align-middle" style="width: 8%">الحالة</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="bg-primary-subtle text-primary small py-1.5">أبيض وأسود</th>
                                        <th class="bg-primary-subtle text-primary small py-1.5">ألوان</th>
                                        <th class="bg-info-subtle text-info small py-1.5">أبيض وأسود</th>
                                        <th class="bg-info-subtle text-info small py-1.5">ألوان</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($documentPrices as $price)
                                        <tr>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <span class="badge bg-label-primary badge-size">{{ $price->paper_size }}</span>
                                                    <div class="text-start">
                                                        <div class="fw-bold">{{ $price->paper_size }}</div>
                                                        <small class="text-muted">{{ $dimensions[$price->paper_size] ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- سعر الورقة لوحدها: أبيض وأسود --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][bw_price]" 
                                                           value="{{ $price->bw_price }}" 
                                                           class="form-control fw-bold text-center" required>
                                                    <span class="input-group-text">ر.س</span>
                                                </div>
                                            </td>
                                            {{-- سعر الورقة لوحدها: ألوان --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][color_price]" 
                                                           value="{{ $price->color_price }}" 
                                                           class="form-control fw-bold text-center text-primary" required>
                                                    <span class="input-group-text">ر.س</span>
                                                </div>
                                            </td>
                                            {{-- سعر ورقة الملف: أبيض وأسود --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][file_bw_price]" 
                                                           value="{{ $price->file_bw_price ?? $price->bw_price }}" 
                                                           class="form-control fw-bold text-center text-info" required>
                                                    <span class="input-group-text">ر.س</span>
                                                </div>
                                            </td>
                                            {{-- سعر ورقة الملف: ألوان --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][file_color_price]" 
                                                           value="{{ $price->file_color_price ?? $price->color_price }}" 
                                                           class="form-control fw-bold text-center text-info" required>
                                                    <span class="input-group-text">ر.س</span>
                                                </div>
                                            </td>
                                            {{-- طباعة وجهين --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][double_sided_price]" 
                                                           value="{{ $price->double_sided_price }}" 
                                                           class="form-control fw-bold text-center text-secondary">
                                                    <span class="input-group-text">ر.س إضافي</span>
                                                </div>
                                            </td>
                                            {{-- الحالة --}}
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="prices[{{ $price->id }}][is_active]" 
                                                           value="1" {{ $price->is_active ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Photos Tab --}}
                    <div class="tab-pane fade" id="photos-tab" role="tabpanel">
                        <div class="alert alert-info bg-info-subtle border-0 mb-4 d-flex align-items-center">
                            <i class="ti ti-info-circle fs-3 me-2 text-info"></i>
                            <div>
                                <strong>طباعة الصور وتصاميم الجرافيك:</strong>
                                خيارات طباعة الصور على ورق صور لامع أو مطفي بجودة عالية. حدد سعر الصورة المفردة وسعر الصورة في ملف صور مجمع لكل مقاس.
                            </div>
                        </div>

                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th rowspan="2" class="align-middle text-center" style="width: 16%">المقاس والأبعاد</th>
                                        <th colspan="2" class="text-center bg-info-subtle text-info border-bottom py-2 fw-bold" style="width: 36%">
                                            <i class="ti ti-photo me-1"></i> الصورة الواحدة (مفردة)
                                        </th>
                                        <th colspan="2" class="text-center bg-secondary-subtle text-secondary border-bottom py-2 fw-bold" style="width: 36%">
                                            <i class="ti ti-photo-album me-1"></i> في ملف صور / ألبوم
                                        </th>
                                        <th rowspan="2" class="text-center align-middle" style="width: 12%">الحالة</th>
                                    </tr>
                                    <tr class="text-center">
                                        <th class="bg-info-subtle text-info small py-1.5">أبيض وأسود</th>
                                        <th class="bg-info-subtle text-info small py-1.5">ألوان</th>
                                        <th class="bg-secondary-subtle text-secondary small py-1.5">أبيض وأسود</th>
                                        <th class="bg-secondary-subtle text-secondary small py-1.5">ألوان</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($photoPrices as $price)
                                        <tr>
                                            <td class="text-center">
                                                <div class="d-flex align-items-center justify-content-center gap-2">
                                                    <span class="badge bg-label-info badge-size">{{ $price->paper_size }}</span>
                                                    <div class="text-start">
                                                        <div class="fw-bold">{{ $price->paper_size }}</div>
                                                        <small class="text-muted">{{ $dimensions[$price->paper_size] ?? '' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            {{-- الصورة المفردة: أبيض وأسود --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][bw_price]" 
                                                           value="{{ $price->bw_price }}" 
                                                           class="form-control fw-bold text-center" required>
                                                    <span class="input-group-text">ر.س</span>
                                                </div>
                                            </td>
                                            {{-- الصورة المفردة: ألوان --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][color_price]" 
                                                           value="{{ $price->color_price }}" 
                                                           class="form-control fw-bold text-center text-primary" required>
                                                    <span class="input-group-text">ر.س</span>
                                                </div>
                                            </td>
                                            {{-- في ملف صور: أبيض وأسود --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][file_bw_price]" 
                                                           value="{{ $price->file_bw_price ?? $price->bw_price }}" 
                                                           class="form-control fw-bold text-center text-secondary" required>
                                                    <span class="input-group-text">ر.س</span>
                                                </div>
                                            </td>
                                            {{-- في ملف صور: ألوان --}}
                                            <td>
                                                <div class="input-group input-group-sm price-input-group">
                                                    <input type="number" step="0.05" min="0" 
                                                           name="prices[{{ $price->id }}][file_color_price]" 
                                                           value="{{ $price->file_color_price ?? $price->color_price }}" 
                                                           class="form-control fw-bold text-center text-secondary" required>
                                                    <span class="input-group-text">ر.س</span>
                                                </div>
                                            </td>
                                            {{-- الحالة --}}
                                            <td class="text-center">
                                                <div class="form-check form-switch d-inline-block">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="prices[{{ $price->id }}][is_active]" 
                                                           value="1" {{ $price->is_active ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Footer Action Bar --}}
                <div class="card-footer bg-light p-3 d-flex justify-content-end gap-2 border-top">
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold shadow-sm">
                        <i class="ti ti-device-floppy me-1"></i>
                        حفظ كافة الأسعار والتعديلات
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

