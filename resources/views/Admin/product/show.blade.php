@extends('Admin.layout.master')

@section('title', 'تفاصيل المنتج - ' . $product->name)

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .product-image { max-height: 120px; object-fit: cover; border-radius: 8px; margin: 5px; }
    .price-table th { background: #f8f9fa; }
    .badge-print { font-size: 0.75rem; padding: 0.4em 0.8em; }
    .location-item { display: inline-block; background: #e3f2fd; padding: 5px 12px; border-radius: 20px; margin: 3px; font-size: 0.9rem; }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">المنتجات</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- الصور + المعلومات الأساسية -->
        <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $product->name }}</h5>
                    <div>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                            تعديل
                        </a>
                        <button onclick="confirmDelete({{ $product->id }})" class="btn btn-danger btn-sm">
                            حذف
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- الصور -->
                    <div class="mb-4">
                        <h6>صور المنتج</h6>
                        <div class="d-flex flex-wrap">
                            @if($product->images->count() > 0)
                                @foreach($product->images as $image)
                                    <img src="{{ asset('storage/' . $image->path) }}"
                                         alt="صورة المنتج"
                                         class="product-image shadow-sm {{ $image->is_primary ? 'border border-primary border-3' : '' }}">
                                @endforeach
                            @else
                                <p class="text-muted">لا توجد صور</p>
                            @endif
                        </div>
                    </div>

                    <!-- الوصف -->
                    <div class="mb-4">
                        <h6>الوصف</h6>
                        <div class="border rounded p-3 bg-light">
                            {!! $product->description ?? '<em class="text-muted">لا يوجد وصف</em>' !!}
                        </div>
                    </div>

                    <!-- معلومات أساسية -->
                    <div class="row g-4">
                        <div class="col-md-6">
                            <strong>التصنيف:</strong> {{ $product->category?->name ?? 'غير محدد' }}
                        </div>
                        <div class="col-md-6">
                            <strong>الحالة:</strong>
                            <span class="badge {{ $product->status ? 'bg-label-success' : 'bg-label-danger' }}">
                                {{ $product->status ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>تاريخ الإضافة:</strong> {{ $product->created_at->format('d/m/Y - h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>آخر تحديث:</strong> {{ $product->updated_at->diffForHumans() }}
                        </div>
                        <div class="col-md-6">
                            <strong>التقييم المتوسط:</strong>
                            <span class="text-warning">
                                <i class="ti ti-star-filled"></i> {{ number_format($product->average_rating, 1) }} ({{ $product->reviews->count() }} تقييم)
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- جدول التسعير حسب المقاس والكمية -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">جدول التسعير حسب المقاس والكمية</h5>
                </div>
                <div class="card-body">
                    @if($product->sizeTiers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered price-table">
                                <thead>
                                    <tr>
                                        <th>المقاس</th>
                                        <th>الكمية</th>
                                        <th>سعر القطعة</th>
                                        <th>السعر الإجمالي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->sizeTiers->sortBy('size.name')->sortBy('quantity') as $tier)
                                    <tr>
                                        <td><strong>{{ $tier->size?->name ?? 'غير محدد' }}</strong></td>
                                        <td>{{ $tier->quantity }} قطعة</td>
                                        <td>{{ number_format($tier->price_per_unit, 2) }} ر.س</td>
                                        <td><strong>{{ number_format($tier->price_per_unit * $tier->quantity, 2) }} ر.س</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">لا توجد أسعار محددة لهذا المنتج بعد</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- الشريط الجانبي (Sidebar) -->
        <div class="col-xl-4">
            <!-- الألوان -->
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">الألوان المتاحة</h6></div>
                <div class="card-body">
                    @if($product->colors->count() > 0)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($product->colors as $color)
                                <div class="text-center">
                                    <div class="rounded-circle border" style="width:50px;height:50px;background:#{{ $color->hex }};border:2px solid #ddd;"></div>
                                    <small>{{ $color->name_ar }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">لا توجد ألوان محددة</p>
                    @endif
                </div>
            </div>

            <!-- تفاصيل الطباعة -->
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">إعدادات الطباعة</h6></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>عدد أوجه الطباعة</span>
                            <strong>{{ $product->num_faces == 2 ? 'وجهين' : 'وجه واحد' }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>طرق الطباعة</span>
                            <div>
                                @if($product->printing_methods)
                                    @foreach(json_decode($product->printing_methods, true) ?? [] as $method)
                                        <span class="badge badge-print bg-primary me-1">{{ __('dtf') == $method ? 'DTF' : ($method == 'screen' ? 'شاشة' : 'تطريز') }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>أماكن الطباعة</span>
                            <div>
                                @if($product->print_locations)
                                    @foreach(json_decode($product->print_locations, true) ?? [] as $loc)
                                        <span class="location-item">
                                            {{ 
                                                $loc == 'front_a4' ? 'أمامي A4' :
                                                ($loc == 'back_a4' ? 'خلفي A4' :
                                                ($loc == 'chest_small' ? 'شعار صغير' :
                                                ($loc == 'left_sleeve' ? 'كتف يسار' : 'كتف يمين')))
                                            }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="text-muted">غير محدد</span>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>طبقة الحماية</span>
                            <strong>
                                {{ $product->protection_layer == 'glossy' ? 'لامع' : ($product->protection_layer == 'matte' ? 'مطفي' : 'بدون') }}
                            </strong>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- خدمة التصميم ومدة التنفيذ -->
            <div class="card mb-4">
                <div class="card-header"><h6 class="mb-0">خدمات إضافية</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>خدمة التصميم:</strong><br>
                        @if($product->design_service == 'paid')
                            مدفوعة ({{ $product->design_service_price }} ر.س)
                        @elseif($product->design_service == 'free')
                            <span class="text-success">مجانية</span>
                        @else
                            <span class="text-muted">غير متوفرة</span>
                        @endif
                    </div>
                    <div class="mb-3">
                        <strong>مدة التنفيذ:</strong><br>
                        <strong>{{ $product->delivery_time ?? 'غير محدد' }}</strong>
                    </div>
                    <div>
                        <strong>رسوم الشحن:</strong><br>
                        <small>{!! nl2br(e($product->shipping_fees ?? 'غير محدد')) !!}</small>
                    </div>
                </div>
            </div>

            <!-- Tags -->
            <div class="card">
                <div class="card-header"><h6 class="mb-0">كلمات مفتاحية (Tags)</h6></div>
                <div class="card-body">
                    @if($product->tags)
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(explode(',', $product->tags) as $tag)
                                <span class="badge bg-label-info">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">لا توجد كلمات مفتاحية</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- حذف المنتج -->
<form id="deleteForm" action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>
@endsection

@section('js')
<script>
function confirmDelete(id) {
    Swal.fire({
        title: 'تأكيد الحذف',
        text: "هل أنت متأكد من حذف هذا المنتج نهائيًا؟ لا يمكن التراجع!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'نعم، احذف',
        cancelButtonText: 'إلغاء',
        customClass: {
            confirmButton: 'btn btn-danger me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>
@endsection