@extends('Admin.layout.master')

@section('title', 'المنتجات')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container { width: 100% !important; }
    .badge-status { font-size: 0.8rem; padding: 0.4em 0.8em; }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">الرئيسية</a></li>
            <li class="breadcrumb-item active">المنتجات</li>
        </ol>
    </nav>

    <!-- إحصائيات سريعة -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="badge rounded-pill bg-label-primary p-3 mb-3">
                        <i class="ti ti-package ti-lg"></i>
                    </div>
                    <h4 class="mb-1">{{ $totalProducts }}</h4>
                    <small>إجمالي المنتجات</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="badge rounded-pill bg-label-success p-3 mb-3">
                        <i class="ti ti-check ti-lg"></i>
                    </div>
                    <h4 class="mb-1">{{ $activeProducts }}</h4>
                    <small>منتجات نشطة</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="badge rounded-pill bg-label-warning p-3 mb-3">
                        <i class="ti ti-eye-off ti-lg"></i>
                    </div>
                    <h4 class="mb-1">{{ $inactiveProducts }}</h4>
                    <small>منتجات غير نشطة</small>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="badge rounded-pill bg-label-info p-3 mb-3">
                        <i class="ti ti-star ti-lg"></i>
                    </div>
                    <h4 class="mb-1">{{ $featuredCount ?? 0 }}</h4>
                    <small>منتجات مميزة</small>
                </div>
            </div>
        </div>
    </div>

    <!-- الجدول مع الفلاتر -->
    <div class="card">
        <div class="card-body">
            <!-- شريط البحث والتصدير -->
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div>
                    <form method="GET" action="{{ route('admin.products.index') }}" class="d-inline-flex">
                        <div class="input-group">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="ابحث بالاسم أو الكود..." aria-label="Search">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i> بحث
                            </button>
                        </div>
                    </form>
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.products.export') }}" class="btn btn-outline-success">
                        <i class="fa-solid fa-file-export"></i> تصدير Excel
                    </a>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fa-solid fa-circle-plus"></i> إضافة منتج
                    </a>
                </div>
            </div>

            <!-- زر الفلاتر المتقدمة -->
            <div class="d-flex justify-content-between align-items-center mb-3">
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse">
                    <i class="ti ti-adjustments-horizontal"></i> فلاتر متقدمة
                </button>

                <!-- حذف متعدد -->
                <div class="btn-group" id="bulkActions" style="display:none;">
                    <button type="button" class="btn btn-danger">إجراءات على المحدد ( <span id="selectedCount">0</span> )</button>
                    <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                        <span class="visually-hidden">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="#" class="dropdown-item text-danger" onclick="bulkDelete()">
                                <i class="ti ti-trash"></i> حذف نهائي
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- الفلاتر المتقدمة -->
            <div class="collapse border rounded p-4 mb-4" id="filterCollapse">
                <form method="GET" action="{{ route('admin.products.index') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">التصنيف</label>
                            <select name="category_id" class="form-control select2">
                                <option value="">كل التصنيفات</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">الحالة</label>
                            <select name="status" class="form-control">
                                <option value="">الكل</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">اللون</label>
                            <select name="color_id" class="form-control select2">
                                <option value="">كل الألوان</option>
                                @foreach($colors as $color)
                                    <option value="{{ $color->id }}" {{ request('color_id') == $color->id ? 'selected' : '' }}>
                                        {{ $color->name_ar }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">السعر من / إلى</label>
                            <div class="input-group">
                                <input type="number" name="price_from" class="form-control" placeholder="من" value="{{ request('price_from') }}">
                                <input type="number" name="price_to" class="form-control" placeholder="إلى" value="{{ request('price_to') }}">
                            </div>
                        </div>
                    </div>

                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">تطبيق الفلاتر</button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>

            <!-- الجدول -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="selectAll">
                                </div>
                            </th>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>اسم المنتج</th>
                            <th>التصنيف</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>التقييم</th>
                            <th>التاريخ</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input class="form-check-input rowCheckbox" type="checkbox" value="{{ $product->id }}">
                                </div>
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($product->image)
                                    <img src="{{ get_user_image($product->image)  }}" alt="{{ $product->name }}" width="50" class="rounded">

                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.products.show', $product) }}" class="text-primary fw-medium">
                                    {{ Str::limit($product->name, 40) }}
                                </a>
                            </td>
                            <td>{{ $product->category?->name ?? 'غير محدد' }}</td>
                            <td>{{ number_format($product->final_price, 2) }} ر.س</td>
                            <td>
                                <span class="badge {{ $product->status ? 'bg-label-success' : 'bg-label-danger' }} badge-status">
                                    {{ $product->status ? 'نشط' : 'غير نشط' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="ti ti-star text-warning me-1"></i>
                                    <span>{{ number_format($product->average_rating, 1) }}</span>
                                </div>
                            </td>
                            <td>{{ $product->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="{{ route('admin.products.show', $product->id) }}">
                                            <i class="ti ti-eye me-2"></i> عرض
                                        </a>
                                        <a class="dropdown-item" href="{{ route('admin.products.edit', $product->id) }}">
                                            <i class="ti ti-pencil me-2"></i> تعديل
                                        </a>
                                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                                           onclick="confirmDelete({{ $product->id }}, '{{ $product->name }}')">
                                            <i class="ti ti-trash me-2"></i> حذف
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center py-5">
                                <i class="ti ti-package-off ti-lg text-muted"></i>
                                <p class="mt-3 text-muted">لا توجد منتجات حاليًا</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<!-- حذف نهائي -->
<form id="deleteForm" method="POST" style="display:none;">
    @csrf @method('DELETE')
</form>

<!-- حذف متعدد -->
{{-- <form id="bulkDeleteForm" action="{{ route('admin.products.bulkDelete') }}" method="POST" style="display:none;">
    @csrf @method('DELETE')
    <input type="hidden" name="ids" id="bulkIds">
</form> --}}
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Select All
        $('#selectAll').on('change', function() {
            $('.rowCheckbox').prop('checked', this.checked);
            toggleBulkActions();
        });

        $('.rowCheckbox').on('change', function() {
            toggleBulkActions();
        });

        function toggleBulkActions() {
            const checked = $('.rowCheckbox:checked').length;
            $('#selectedCount').text(checked);
            $('#bulkActions').toggle(checked > 0);
        }
    });

    function confirmDelete(id, name) {
        Swal.fire({
            title: 'تأكيد الحذف',
            text: `هل أنت متأكد من حذف المنتج "${name}"؟ هذا الإجراء لا يمكن التراجع عنه!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-secondary' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').action = `/admin/products/${id}`;
                document.getElementById('deleteForm').submit();
            }
        });
    }

    function bulkDelete() {
        const ids = $('.rowCheckbox:checked').map(function() { return this.value; }).get();
        if (ids.length === 0) return;

        Swal.fire({
            title: 'حذف متعدد',
            text: `هل تريد حذف ${ids.length} منتج؟ لا يمكن التراجع!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف الكل',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#bulkIds').val(ids.join(','));
                $('#bulkDeleteForm').submit();
            }
        });
    }
</script>
@endsection