@extends('Admin.layout.master')

@section('title', 'تعديل المنتج - ' . $product->name)

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container { width: 100% !important; }
    .image-preview { max-height: 100px; margin: 5px; border-radius: 8px; position: relative; display: inline-block; }
    .remove-image { position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; font-size: 14px; }
    .primary-badge { position: absolute; bottom: 5px; left: 5px; background: #007bff; color: white; padding: 2px 8px; border-radius: 12px; font-size: 10px; }
    .price-tier { border: 1px solid #ddd; padding: 15px; border-radius: 8px; margin-bottom: 15px; background: #f8f9fa; position: relative; }
    .remove-tier { position: absolute; top: 10px; left: 10px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 30px; height: 30px; }
</style>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">المنتجات</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">تعديل المنتج: {{ $product->name }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <!-- الاسم والتصنيف -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">التصنيف <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control select2" required>
                            <option value="">اختر التصنيف</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- الوصف -->
                <div class="mb-3">
                    <label class="form-label">الوصف الكامل</label>
                    <textarea name="description" id="description" class="form-control editor">{!! old('description', $product->description) !!}</textarea>
                </div>

                <!-- الصور الحالية + إضافة جديدة -->
                <div class="mb-3">
                    <label class="form-label">الصور الحالية</label>
                    <div class="d-flex flex-wrap">
                        @foreach($product->images as $image)
                            <div class="image-preview">
                                <img src="{{ asset('storage/' . $image->path) }}" alt="صورة">
                                @if($image->is_primary)
                                    <span class="primary-badge">رئيسية</span>
                                @endif
                                <button type="button" class="remove-image" onclick="removeImage({{ $image->id }})">X</button>
                                <input type="hidden" name="existing_images[]" value="{{ $image->id }}">
                            </div>
                        @endforeach
                    </div>
                    <input type="file" name="images[]" class="form-control mt-3" multiple accept="image/*">
                    <small class="text-muted">الصورة الأولى في الرفع الجديد ستكون الرئيسية</small>
                </div>

                <!-- الألوان -->
                <div class="mb-3">
                    <label class="form-label">الألوان المتاحة</label>
                    <select name="colors[]" class="form-control select2" multiple>
                        @foreach($colors as $color)
                            <option value="{{ $color->id }}" {{ $product->colors->contains($color->id) ? 'selected' : '' }}>
                                {{ $color->name_ar }} (#{{ $color->hex }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- المقاسات والتسعير -->
                <div class="mb-4">
                    <h5>المقاسات والتسعير حسب الكمية</h5>
                    <div id="size-tiers">
                        @foreach($product->sizeTiers as $tier)
                            <div class="price-tier">
                                <button type="button" class="remove-tier" onclick="this.parentElement.remove()">X</button>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label>المقاس</label>
                                        <select name="sizes[edit][{{ $tier->id }}][size_id]" class="form-control" required>
                                            @foreach($sizes as $size)
                                                <option value="{{ $size->id }}" {{ $tier->size_id == $size->id ? 'selected' : '' }}>{{ $size->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label>الكمية</label>
                                        <input type="number" name="sizes[edit][{{ $tier->id }}][quantity]" class="form-control" value="{{ $tier->quantity }}" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>سعر القطعة</label>
                                        <input type="number" step="0.01" name="sizes[edit][{{ $tier->id }}][price_per_unit]" class="form-control" value="{{ $tier->price_per_unit }}" required>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addNewTier()">+ إضافة مقاس جديد</button>
                </div>

                <script>
                    let newTierIndex = 0;
                    function addNewTier() {
                        const html = `
                        <div class="price-tier">
                            <button type="button" class="remove-tier" onclick="this.parentElement.remove()">X</button>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label>المقاس</label>
                                    <select name="sizes[new][${newTierIndex}][size_id]" class="form-control" required>
                                        <option value="">اختر المقاس</option>
                                        @foreach($sizes as $size)
                                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label>الكمية</label>
                                    <input type="number" name="sizes[new][${newTierIndex}][quantity]" class="form-control" placeholder="مثال: 100" required>
                                </div>
                                <div class="col-md-4">
                                    <label>سعر القطعة</label>
                                    <input type="number" step="0.01" name="sizes[new][${newTierIndex}][price_per_unit]" class="form-control" required>
                                </div>
                            </div>
                        </div>`;
                        document.getElementById('size-tiers').insertAdjacentHTML('beforeend', html);
                        newTierIndex++;
                    }

                    function removeImage(id) {
                        if(confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
                            fetch(`/admin/products/{{ $product->id }}/images/${id}`, {
                                method: 'DELETE',
                                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                            }).then(() => location.reload());
                        }
                    }
                </script>

                <!-- باقي الحقول -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>عدد أوجه الطباعة</label>
                        <select name="num_faces" class="form-control">
                            <option value="1" {{ $product->num_faces == 1 ? 'selected' : '' }}>وجه واحد</option>
                            <option value="2" {{ $product->num_faces == 2 ? 'selected' : '' }}>وجهين</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>أماكن الطباعة</label>
                        <select name="print_locations[]" class="form-control select2" multiple>
                            <option value="front_a4" {{ in_array('front_a4', json_decode($product->print_locations ?? '[]', true)) ? 'selected' : '' }}>أمامي A4</option>
                            <option value="back_a4" {{ in_array('back_a4', json_decode($product->print_locations ?? '[]', true)) ? 'selected' : '' }}>خلفي A4</option>
                            <option value="chest_small" {{ in_array('chest_small', json_decode($product->print_locations ?? '[]', true)) ? 'selected' : '' }}>شعار صغير</option>
                            <option value="left_sleeve" {{ in_array('left_sleeve', json_decode($product->print_locations ?? '[]', true)) ? 'selected' : '' }}>كتف يسار</option>
                            <option value="right_sleeve" {{ in_array('right_sleeve', json_decode($product->print_locations ?? '[]', true)) ? 'selected' : '' }}>كتف يمين</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label>طرق الطباعة</label>
                    <div class="row">
                        @foreach(['dtf' => 'DTF', 'screen' => 'طباعة شاشة', 'embroidery' => 'تطريز'] as $key => $label)
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input type="checkbox" name="printing_methods[]" value="{{ $key }}" class="form-check-input"
                                        {{ in_array($key, json_decode($product->printing_methods ?? '[]', true)) ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $label }}</label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>طبقة الحماية</label>
                        <select name="protection_layer" class="form-control">
                            <option value="none" {{ $product->protection_layer == 'none' ? 'selected' : '' }}>بدون</option>
                            <option value="glossy" {{ $product->protection_layer == 'glossy' ? 'selected' : '' }}>لامع</option>
                            <option value="matte" {{ $product->protection_layer == 'matte' ? 'selected' : '' }}>مطفي</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>خدمة التصميم</label>
                        <select name="design_service" class="form-control" id="designService">
                            <option value="0" {{ $product->design_service == '0' ? 'selected' : '' }}>لا نقدم</option>
                            <option value="free" {{ $product->design_service == 'free' ? 'selected' : '' }}>مجانية</option>
                            <option value="paid" {{ $product->design_service == 'paid' ? 'selected' : '' }}>مدفوعة</option>
                        </select>
                        <input type="number" name="design_service_price" class="form-control mt-2" value="{{ $product->design_service_price }}" 
                               style="display: {{ $product->design_service == 'paid' ? 'block' : 'none' }}" placeholder="سعر الخدمة">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>مدة التنفيذ</label>
                        <input type="text" name="delivery_time" class="form-control" value="{{ old('delivery_time', $product->delivery_time) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>رسوم الشحن</label>
                        <textarea name="shipping_fees" class="form-control" rows="3">{{ old('shipping_fees', $product->shipping_fees) }}</textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label>كلمات مفتاحية (Tags)</label>
                    <input type="text" name="tags" class="form-control" value="{{ old('tags', $product->tags) }}" data-role="tagsinput">
                </div>

                <div class="mb-3">
                    <label>حالة المنتج</label>
                    <select name="status" class="form-control">
                        <option value="1" {{ $product->status ? 'selected' : '' }}>نشط</option>
                        <option value="0" {{ !$product->status ? 'selected' : '' }}>غير نشط</option>
                    </select>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">حفظ التعديلات</button>
                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-secondary">رجوع</a>
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
    ClassicEditor.create(document.querySelector('#description'), { language: 'ar' });
    $('.select2').select2();

    $('#designService').on('change', function() {
        $('input[name="design_service_price"]').toggle(this.value === 'paid');
    });
</script>
@endsection