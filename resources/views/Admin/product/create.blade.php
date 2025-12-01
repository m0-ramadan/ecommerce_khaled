@extends('Admin.layout.master')

@section('title', 'إضافة منتج جديد')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container { width: 100% !important; }
    .image-preview { max-height: 100px; margin: 5px; border-radius: 8px; }
    .remove-image { position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; width: 25px; height: 25px; }
    .price-tier { border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 15px; background: #f9f9f9; }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4">إضافة منتج جديد</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- الاسم والتصنيف -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">التصنيف <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control select2" required>
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- الوصف -->
                <div class="mb-3">
                    <label class="form-label">الوصف الكامل</label>
                    <textarea name="description" id="description" class="form-control editor"></textarea>
                </div>

                <!-- الصور -->
                <div class="mb-3">
                    <label class="form-label">صور المنتج (متعددة)</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                    <small class="text-muted">الصورة الأولى ستكون الصورة الرئيسية</small>
                </div>

                <!-- الألوان -->
                <div class="mb-3">
                    <label class="form-label">الألوان المتاحة</label>
                    <select name="colors[]" class="form-control select2" multiple>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}">{{ $color->name_ar }} (#{{ $color->hex }})</option>
                        @endforeach
                    </select>
                </div>

                <!-- المقاسات مع الأسعار لكل كمية -->
                <div class="mb-4">
                    <h5>المقاسات والتسعير حسب الكمية</h5>
                    <div id="size-tiers">
                        <!-- يتم إضافته ديناميكيًا -->
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addSizeTier()">+ إضافة مقاس جديد</button>
                </div>

                <script>
                    let tierIndex = 0;
                    function addSizeTier() {
                        const html = `
                        <div class="price-tier">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>المقاس</label>
                                    <select name="sizes[${tierIndex}][size_id]" class="form-control" required>
                                        <option value="">اختر المقاس</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>الكمية</label>
                                    <input type="number" name="sizes[${tierIndex}][quantity]" class="form-control" placeholder="مثال: 10" required>
                                </div>
                                <div class="col-md-3">
                                    <label>السعر للقطعة</label>
                                    <input type="number" step="0.01" name="sizes[${tierIndex}][price_per_unit]" class="form-control" required>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.parentElement.remove()">حذف</button>
                                </div>
                            </div>
                        </div>`;
                        document.getElementById('size-tiers').insertAdjacentHTML('beforeend', html);
                        tierIndex++;
                    }
                    // إضافة مقاس واحد افتراضي
                    window.onload = () => addSizeTier();
                </script>

                <!-- عدد أوجه الطباعة -->
                <div class="mb-3">
                    <label class="form-label">عدد أوجه الطباعة</label>
                    <select name="num_faces" class="form-control">
                        <option value="1">وجه واحد</option>
                        <option value="2">وجهين</option>
                    </select>
                </div>

                <!-- أماكن الطباعة -->
                <div class="mb-3">
                    <label class="form-label">أماكن الطباعة المتاحة (متعدد)</label>
                    <select name="print_locations[]" class="form-control select2" multiple>
                        <option value="front_a4">الصدر (A4)</option>
                        <option value="back_a4">الظهر (A4)</option>
                        <option value="chest_small">شعار صغير أمامي</option>
                        <option value="left_sleeve">كتف يسار</option>
                        <option value="right_sleeve">كتف يمين</option>
                    </select>
                </div>

                <!-- طريقة الطباعة -->
                <div class="mb-3">
                    <label class="form-label">طرق الطباعة المتاحة</label>
                    <div>
                        <div class="form-check">
                            <input type="checkbox" name="printing_methods[]" value="dtf" class="form-check-input" id="dtf">
                            <label class="form-check-label" for="dtf">DTF</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="printing_methods[]" value="screen" class="form-check-input" id="screen">
                            <label class="form-check-label" for="screen">طباعة شاشة</label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="printing_methods[]" value="embroidery" class="form-check-input" id="embroidery">
                            <label class="form-check-label" for="embroidery">تطريز</label>
                        </div>
                    </div>
                </div>

                <!-- طبقة الحماية -->
                <div class="mb-3">
                    <label class="form-label">طبقة الحماية</label>
                    <select name="protection_layer" class="form-control">
                        <option value="none">بدون</option>
                        <option value="glossy">لامع</option>
                        <option value="matte">مطفي</option>
                    </select>
                </div>

                <!-- خدمة التصميم -->
                <div class="mb-3">
                    <label class="form-label">خدمة التصميم</label>
                    <select name="design_service" class="form-control">
                        <option value="0">لا نقدم خدمة تصميم</option>
                        <option value="free">مجانية</option>
                        <option value="paid">مدفوعة (أدخل السعر)</option>
                    </select>
                    <input type="number" name="design_service_price" class="form-control mt-2" placeholder="سعر خدمة التصميم" style="display:none;">
                </div>

                <!-- مدة التنفيذ -->
                <div class="mb-3">
                    <label class="form-label">مدة التنفيذ (أيام عمل)</label>
                    <input type="text" name="delivery_time" class="form-control" placeholder="مثال: 5-10 أيام عمل" required>
                </div>

                <!-- رسوم الشحن -->
                <div class="mb-3">
                    <label class="form-label">رسوم الشحن (جدول حسب الكمية)</label>
                    <textarea name="shipping_fees" class="form-control" rows="4" placeholder='مثال:
10-25 قطعة: شحن مجاني
50 قطعة: 15 ريال
100 قطعة: 35 ريال
1000 قطعة: 230 ريال'></textarea>
                </div>

                <!-- Tags -->
                <div class="mb-3">
                    <label class="form-label">كلمات مفتاحية (Tags)</label>
                    <input type="text" name="tags" class="form-control" data-role="tagsinput" placeholder="أضف تاج واضغط Enter">
                </div>

                <!-- الحالة -->
                <div class="mb-3">
                    <label class="form-label">حالة المنتج</label>
                    <select name="status" class="form-control">
                        <option value="1">نشط</option>
                        <option value="0">غير نشط</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">حفظ المنتج</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">رجوع</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js"></script>

<script>
    ClassicEditor.create(document.querySelector('#description'), {
        language: 'ar',
        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote']
    });

    $('.select2').select2({
        placeholder: "اختر...",
        allowClear: true
    });

    // إظهار حقل سعر خدمة التصميم
    $('select[name="design_service"]').on('change', function() {
        if ($(this).val() === 'paid') {
            $('input[name="design_service_price"]').show();
        } else {
            $('input[name="design_service_price"]').hide().val('');
        }
    });
</script>
@endsection