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
        الشرائح بقت <b>رينجات</b>: اكتب كل رينج في سطر بالشكل <code>من-إلى:سعر القطعة</code> (مثال: <code>20-200:300.00</code>)
        أو <code>من+:سعر القطعة</code> لرينج مفتوح (مثال: <code>250+:15.00</code>).<br>
        <b>مهم:</b> السعر المكتوب في الشريحة هو <b>سعر القطعة الواحدة</b>، والإجمالي = الكمية × سعر القطعة
        (مثال: العميل اختار 200 قطعة من رينج سعره 300.00 → 200 × 300.00).
        السعر الأساسي يُستخدم فقط لو الكمية مش داخلة في أي رينج.
    </p>

    <!-- عناوين الأعمدة -->
    <div class="row g-2 mb-1 d-none d-md-flex align-items-end fw-bold small text-muted">
        <div class="col-md-3">المنتج</div>
        <div class="col-md-2">النوع (أساسي / مرشح)</div>
        <div class="col-md-2">الحد الأدنى للكمية</div>
        <div class="col-md-2">السعر الأساسي (ريال)</div>
        <div class="col-md-2">شرائح الكمية (من-إلى:سعر القطعة)</div>
        <div class="col-md-1 text-center">حذف</div>
    </div>

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

    function initProductSelect(scope) {
        if (!window.jQuery || !jQuery.fn.select2) return;

        jQuery(scope || document).find('.product-select').each(function () {
            const $el = jQuery(this);
            if ($el.hasClass('select2-hidden-accessible')) return;

            const normalizeArabic = function (text) {
                return String(text || '')
                    .replace(/[\u064B-\u065F\u0670\u06D6-\u06ED]/g, '')
                    .replace(/[\u0623\u0625\u0622]/g, '\u0627')
                    .replace(/\u0649/g, '\u064A')
                    .replace(/\u0629/g, '\u0647')
                    .replace(/\u0640/g, '')
                    .replace(/\s+/g, ' ')
                    .trim()
                    .toLowerCase();
            };

            $el.select2({
                width: '100%',
                dir: 'rtl',
                placeholder: { id: '', text: 'ابحث عن المنتج بالاسم...' },
                allowClear: false,
                matcher: function (params, data) {
                    const term = normalizeArabic(params.term || '');
                    if (!term) return data;
                    const text = normalizeArabic(data.text || '');
                    if (text.indexOf(term) > -1) return data;
                    return null;
                },
                language: {
                    noResults: function () { return 'لا توجد نتائج — جرّب كلمة أخرى'; },
                    searching: function () { return 'جارٍ البحث...'; },
                },
                templateResult: function (item) {
                    if (!item.id || !item.element) return item.text;
                    const img = item.element.dataset ? item.element.dataset.image : '';
                    const $wrap = jQuery('<span class="d-flex align-items-center gap-2"></span>');
                    if (img) {
                        jQuery('<img>')
                            .attr('src', img)
                            .css({ width: '30px', height: '30px', objectFit: 'contain', borderRadius: '6px', background: '#f1f5f9' })
                            .appendTo($wrap);
                    }
                    jQuery('<span>').text(item.text).appendTo($wrap);
                    return $wrap;
                },
                templateSelection: function (item) {
                    return (item && item.text) ? item.text : 'ابحث عن المنتج بالاسم...';
                },
            });

            // تأكيد عرض النص المحدد بعد التهيئة
            $el.trigger('change.select2');
        });
    }

    function addPackageRow() {
        const tpl = document.getElementById('rowTemplate').innerHTML.replace(/__i/g, packageRowIndex++);
        const wrap = document.createElement('div');
        wrap.innerHTML = tpl;
        const row = wrap.firstElementChild;
        document.getElementById('rows').appendChild(row);
        initProductSelect(row);
    }

    document.getElementById('packageForm').addEventListener('submit', function () {
        document.querySelectorAll('#rows .product-row').forEach(function (row, idx) {
            row.querySelectorAll('[name]').forEach(function (el) {
                el.name = el.name.replace(/products\[\d+\]/, 'products[' + idx + ']');
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        initProductSelect(document);
    });
</script>
