@php
    $isEdit = isset($package);
    $action = $isEdit ? route('admin.packages.update', $package) : route('admin.packages.store');
    $rowsCount = $isEdit ? $package->products->count() : 0;
@endphp

<form id="packageForm" action="{{ $action }}" method="POST" enctype="multipart/form-data">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">اسم الباقة <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $package->name ?? '') }}" required>
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">الترتيب</label>
            <input type="number" min="0" name="order" class="form-control" value="{{ old('order', $package->order ?? 0) }}">
        </div>
        <div class="col-md-2 mb-3">
            <label class="form-label">نشطة</label>
            <div class="form-check form-switch mt-2">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $package->is_active ?? true) ? 'checked' : '' }}>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">الوصف</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $package->description ?? '') }}</textarea>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">الصورة الأساسية</label>
            <input type="file" name="image" accept="image/*" class="form-control">
            @if (!empty($package?->image))
                <img src="{{ asset('storage/' . $package->image) }}" alt="" class="mt-2 rounded" style="height:70px">
            @endif
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">صورة الخلفية</label>
            <input type="file" name="background_image" accept="image/*" class="form-control">
            @if (!empty($package?->background_image))
                <img src="{{ asset('storage/' . $package->background_image) }}" alt="" class="mt-2 rounded" style="height:70px">
            @endif
        </div>
    </div>

    <hr>
    <h5>منتجات الباقة</h5>
    <p class="text-muted small mb-2">
        <b>أساسي:</b> له حد أدنى وسعر أساسي، والشرائح تُضاف على السعر الأساسي.
        <b>مرشح:</b> شرائح كمية وسعر بدون حد أدنى.
        الشرائح بقت <b>رينجات</b>: اكتب كل رينج في سطر بالشكل <code>من-إلى:السعر</code> (مثال: <code>50-99:500</code>)
        أو <code>من+:السعر</code> لرينج مفتوح (مثال: <code>250+:1800</code>). العميل بيكتب الكمية بنفسه.
    </p>

    <div id="rows">
        @if ($isEdit)
            @foreach ($package->products as $i => $pp)
                @include('Admin.packages.partials.row', ['i' => $i, 'pp' => $pp])
            @endforeach
        @endif
    </div>

    <template id="rowTemplate">
        @include('Admin.packages.partials.row', ['i' => '__i', 'pp' => null])
    </template>

    <div class="d-flex gap-2 mb-3">
        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addPackageRow()">+ إضافة منتج</button>
    </div>

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-success">حفظ</button>
        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">رجوع</a>
    </div>
</form>

<script>
    let packageRowIndex = {{ $rowsCount }};

    function addPackageRow() {
        const tpl = document.getElementById('rowTemplate').innerHTML.replace(/__i/g, packageRowIndex++);
        const wrap = document.createElement('div');
        wrap.innerHTML = tpl;
        document.getElementById('rows').appendChild(wrap.firstElementChild);
    }

    document.getElementById('packageForm').addEventListener('submit', function () {
        document.querySelectorAll('#rows .product-row').forEach(function (row, idx) {
            row.querySelectorAll('[name]').forEach(function (el) {
                el.name = el.name.replace(/products\[\d+\]/, 'products[' + idx + ']');
            });
        });
    });
</script>
