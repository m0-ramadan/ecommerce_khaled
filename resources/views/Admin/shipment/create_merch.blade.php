@extends('Admin.layout.master')

@section('title', 'إنشاء شحنة زبون')

@section('css')
    <style>
        label {
            font-family: 'Cairo', sans-serif;
        }

        .textarea-group {
            position: relative;
            margin-bottom: 15px;
        }

        .remove-btn {
            position: absolute;
            top: 8px;
            left: 8px;
            background: transparent;
            border: none;
            color: red;
            font-size: 2.2rem;
            cursor: pointer;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .content-table th,
        .content-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .content-table th {
            background-color: #f8f9fa;
            font-family: 'Cairo', sans-serif;
        }

        .content-table td select,
        .content-table td input,
        .content-table td textarea {
            width: 100%;
            padding: 5px;
            box-sizing: border-box;
        }

        .remove-row-btn {
            background: transparent;
            border: none;
            color: red;
            font-size: 1.5rem;
            cursor: pointer;
        }

        .new-product-fields {
            margin-top: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <x-breadcrumb :items="[
                'لوحة التحكم' => '/admin',
                'شحنات التجار' => '/admin/shipment/1',
                'انشاء شحنة تاجر' => '',
            ]" />
        </div>

        <div class="card-body">
            <form class="form theme-form" action="{{ route('admin.shipment.store_merch') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-12">
                        <!-- Sender Information -->
                        <h6 class="mb-3">معلومات المرسل</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label for="sender_phone">رقم هاتف المرسل</label>
                                <input class="form-control" name="sender_phone" id="sender_phone" type="text"
                                    placeholder="أدخل رقم الهاتف" value="{{ old('sender_phone') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="sender_name">اسم المرسل</label>
                                <input class="form-control" name="sender_name" id="sender_name" type="text"
                                    placeholder="أدخل اسم المرسل" value="{{ old('sender_name') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="sender_country">دولة المرسل</label>
                                <select class="form-control" name="sender_country" id="sender_country">
                                    <option value="">اختر الدولة</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('sender_country') == $country->id ? 'selected' : '' }}>
                                            {{ $country->country_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="sender_region">منطقة المرسل</label>
                                <select class="form-control" name="sender_region" id="sender_region">
                                    <option value="">اختر المنطقة</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}"
                                            {{ old('sender_region') == $region->id ? 'selected' : '' }}>
                                            {{ $region->region_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="sender_address">عنوان المرسل</label>
                                <textarea class="form-control" name="sender_address" id="sender_address" placeholder="أدخل عنوان المرسل">{{ old('sender_address') }}</textarea>
                            </div>
                        </div>

                        <!-- Receiver Information -->
                        <h6 class="mb-3">معلومات المستلم</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label for="name_received">اسم المستلم</label>
                                <input class="form-control" name="name_received" id="name_received" type="text"
                                    placeholder="أدخل اسم المستلم" value="{{ old('name_received') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="phone_received">رقم هاتف المستلم</label>
                                <input class="form-control" name="phone_received" id="phone_received" type="text"
                                    placeholder="أدخل رقم الهاتف" value="{{ old('phone_received') }}">
                            </div>
                            <div class="col-md-3">
                                <label for="country_received_id">دولة المستلم</label>
                                <select class="form-control" name="country_received_id" id="country_received_id">
                                    <option value="">اختر الدولة</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country_received_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->country_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="country_region_id">منطقة المستلم</label>
                                <select class="form-control" name="country_region_id" id="country_region_id">
                                    <option value="">اختر المنطقة</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}"
                                            {{ old('country_region_id') == $region->id ? 'selected' : '' }}>
                                            {{ $region->region_ar }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="address_received">عنوان المستلم</label>
                                <textarea class="form-control" name="address_received" id="address_received" placeholder="أدخل عنوان المستلم">{{ old('address_received') }}</textarea>
                            </div>
                        </div>

                        <!-- Shipment Details -->
                        <h6 class="mb-3">تفاصيل الشحنة</h6>
                        <div class="row g-3 mb-3">
                            @if (auth()->user()->branch_id)
                                <div class="col-md-3">
                                    <label for="branches_from">الفرع المرسل</label>
                                    <input type="text" class="form-control"
                                        value="{{ $branches->firstWhere('id', auth()->user()->branch_id)?->name }}"
                                        disabled>
                                    <input type="hidden" name="branches_from" value="{{ auth()->user()->branch_id }}">
                                </div>
                            @else
                                <div class="col-md-3">
                                    <label for="branches_from">الفرع المرسل</label>
                                    <select class="form-control" name="branches_from" id="branches_from">
                                        <option value="">اختر الفرع</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branches_from') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label for="country_region_id_to">مدينة المستلم</label>
                                <select class="form-control" name="country_region_id_to" id="country_region_id_to">
                                    <option value="">اختر الفرع</option>
                                    @foreach ($regions as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('country_region_id_to') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="shipping_cost">سعر التوصيل</label>
                                <input class="form-control" name="shipping_cost" id="shipping_cost" type="number"
                                    step="0.01" placeholder="أدخل القيمة" value="{{ old('shipping_cost') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="price">قيمة الشحنه</label>
                                <input class="form-control" name="price" id="price" type="number" step="0.01"
                                    placeholder="أدخل القيمة" value="{{ old('price') }}">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label" for="return_value">قيمة الراجع</label>
                                <input class="form-control" name="return_value" id="return_value" type="number"
                                    step="0.01" placeholder="أدخل القيمة" value="{{ old('return_value') }}">
                            </div>
                            <div class="col-md-3 d-none">
                                <label for="customs_cost">قيمة الجمرك</label>
                                <input class="form-control" name="customs_cost" id="customs_cost" type="number"
                                    step="0.01" placeholder="أدخل القيمة" value="0">
                            </div>
                            <div class="col-md-3">
                                <label for="remaining">المتبقي</label>
                                <input class="form-control" name="remaining" id="remaining" type="number"
                                    step="0.01" placeholder="المتبقي" value="{{ old('remaining', 0) }}" readonly>
                            </div>
                            <div class="col-md-3">
                                <label for="currency_id">نوع العملة</label>
                                <select class="form-control" name="currency_id" id="currency_id">
                                    <option value="">من فضلك حدد عملة الخزينة</option>
                                    <option value="1" {{ old('currency_id') == '1' ? 'selected' : '' }}>LYD</option>
                                    <option value="2" {{ old('currency_id') == '2' ? 'selected' : '' }}>EGP</option>
                                    <option value="3" {{ old('currency_id') == '3' ? 'selected' : '' }}>$ (USD)
                                    </option>
                                    <option value="4" {{ old('currency_id') == '4' ? 'selected' : '' }}>TRY</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="natural_shipments">طبيعة الشحن</label>
                                <select class="form-control" name="natural_shipments" id="natural_shipments">
                                    <option value="">اختر نوع الشحن</option>
                                    <option value="1" {{ old('natural_shipments') == 1 ? 'selected' : '' }}>
                                        بطئ
                                    </option>
                                    <option value="2" {{ old('natural_shipments') == 2 ? 'selected' : '' }}>
                                        سريع
                                    </option>
                                    <option value="3" {{ old('natural_shipments') == 3 ? 'selected' : '' }}>
                                        شحن خاص
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="properties_shipment_id">خصائص الشحنة</label>
                                <select name="properties_shipment_id[]" id="properties_shipment_id"
                                    class="form-control select2" multiple="multiple">
                                    @foreach ($propertiesShipments as $property)
                                        <option value="{{ $property->id }}"
                                            {{ in_array($property->id, old('properties_shipment_id', [])) ? 'selected' : '' }}>
                                            {{ $property->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="service_id">نوع الشحن</label>
                                <select class="form-control" name="service_id" id="service_id">
                                    <option value="">اختر نوع الشحنة</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}"
                                            {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="type_vehicle_id">نوع المركبة</label>
                                <select class="form-control" name="type_vehicle_id" id="type_vehicle_id">
                                    <option value="">اختر نوع المركبة</option>
                                    @foreach ($vehicleTypes as $vehicleType)
                                        <option value="{{ $vehicleType->id }}"
                                            {{ old('type_vehicle_id') == $vehicleType->id ? 'selected' : '' }}>
                                            {{ $vehicleType->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="weight">الوزن (كجم)</label>
                                <input class="form-control" name="weight" id="weight" type="number" step="0.01"
                                    placeholder="أدخل الوزن" value="{{ old('weight') }}">
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="type_invoice">نوع الفاتورة</label>
                                    <select class="form-control" name="type_invoice" id="type_invoice">
                                        <option value="">اختر نوع الفاتورة</option>
                                        <option value="1" {{ old('type_invoice') == 1 ? 'selected' : '' }}>
                                            بدون فاتورة
                                        </option>
                                        <option value="2" {{ old('type_invoice') == 2 ? 'selected' : '' }}>
                                            نصف فاتورة
                                        </option>
                                        <option value="3" {{ old('type_invoice') == 3 ? 'selected' : '' }}>
                                            فاتورة كاملة
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="receipt_location">موقع الاستلام</label>
                                    <select class="form-control" name="receipt_location" id="receipt_location">
                                        <option value="">اختر موقع الاستلام</option>
                                        <option value="1" {{ old('receipt_location') == 1 ? 'selected' : '' }}>
                                            فرع
                                        </option>
                                        <option value="2" {{ old('receipt_location') == 2 ? 'selected' : '' }}>
                                            توصيل داخلي
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="delivery_location">موقع التسليم</label>
                                    <select class="form-control" name="delivery_location" id="delivery_location">
                                        <option value="">اختر موقع التسليم</option>
                                        <option value="1" {{ old('delivery_location') == 1 ? 'selected' : '' }}>
                                            من الفرع
                                        </option>
                                        <option value="2" {{ old('delivery_location') == 2 ? 'selected' : '' }}>
                                            توصيل للمنزل
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="cost_shipment_by">دافع تكلفة الشحنه</label>
                                <select class="form-control" name="cost_shipment_by" id="cost_shipment_by">
                                    <option value="">اختر دافع التكلفة</option>
                                    <option value="1" {{ old('cost_shipment_by') == '1' ? 'selected' : '' }}>مرسل
                                    </option>
                                    <option value="2" {{ old('cost_shipment_by') == '2' ? 'selected' : '' }}>مستلم
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="additional_shipping_cost">تكلفة التوصيل علي من</label>
                                <select class="form-control" name="additional_shipping_cost"
                                    id="additional_shipping_cost">
                                    <option value="">اختر دافع التكلفة</option>
                                    <option value="1" {{ old('additional_shipping_cost') == '1' ? 'selected' : '' }}>
                                        مرسل
                                    </option>
                                    <option value="2" {{ old('additional_shipping_cost') == '2' ? 'selected' : '' }}>
                                        مستلم
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="customs_included">الجمارك</label>
                                <select class="form-control" name="customs_included" id="customs_included">
                                    <option value="">اختر نوع الجمارك</option>
                                    <option value="0" {{ old('customs_included') == '0' ? 'selected' : '' }}>شامل
                                        الجمرك
                                    </option>
                                    <option value="1" {{ old('customs_included') == '1' ? 'selected' : '' }}>غير شامل
                                        الجمرك
                                    </option>
                                </select>
                            </div>
                        </div>

                        <!-- Products Selection Table -->
                        <h6 class="mb-3">المنتجات</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <table class="content-table" id="productTable">
                                    <thead>
                                        <tr>
                                            <th>اسم المنتج</th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>الإجمالي</th>
                                            <th>الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $productsInput = old('products', [[]]);
                                        @endphp
                                        @foreach ($productsInput as $index => $product)
                                            <tr>
                                                <td>
                                                    <div class="product-input-group">
                                                        <div class="form-check form-switch mb-2">
                                                            <input class="form-check-input toggle-product-type"
                                                                type="checkbox" id="toggle_product_{{ $index }}"
                                                                onchange="toggleProductInput(this, {{ $index }})"
                                                                {{ old('products.' . $index . '.is_new_product') ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="toggle_product_{{ $index }}">إضافة منتج
                                                                جديد</label>
                                                        </div>
                                                        <input type="hidden"
                                                            name="products[{{ $index }}][is_new_product]"
                                                            class="is-new-product"
                                                            value="{{ old('products.' . $index . '.is_new_product', 0) }}">
                                                        <select name="products[{{ $index }}][product_id]"
                                                            class="form-control product-select"
                                                            onchange="updateProductDetails(this)"
                                                            style="{{ old('products.' . $index . '.is_new_product') ? 'display: none;' : 'display: block;' }}">
                                                            <option value="">اختر المنتج</option>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}"
                                                                    data-price="{{ $product->sale_price }}"
                                                                    data-stock="{{ $product->in_stock_quantity }}"
                                                                    {{ old('products.' . $index . '.product_id') == $product->id ? 'selected' : '' }}>
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="new-product-fields"
                                                            style="{{ old('products.' . $index . '.is_new_product') ? 'display: block;' : 'display: none;' }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][name]"
                                                                class="form-control mb-2" placeholder="اسم المنتج"
                                                                value="{{ old('products.' . $index . '.new_product.name') }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][code]"
                                                                class="form-control mb-2" placeholder="كود المنتج"
                                                                value="{{ old('products.' . $index . '.new_product.code') }}">
                                                            <textarea name="products[{{ $index }}][new_product][description]" class="form-control mb-2"
                                                                placeholder="وصف المنتج">{{ old('products.' . $index . '.new_product.description') }}</textarea>
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][category]"
                                                                class="form-control mb-2" placeholder="الفئة"
                                                                value="{{ old('products.' . $index . '.new_product.category') }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][brand]"
                                                                class="form-control mb-2" placeholder="العلامة التجارية"
                                                                value="{{ old('products.' . $index . '.new_product.brand') }}">
                                                            <input type="number"
                                                                name="products[{{ $index }}][new_product][in_stock_quantity]"
                                                                class="form-control mb-2" placeholder="الكمية في المخزون"
                                                                value="{{ old('products.' . $index . '.new_product.in_stock_quantity') }}">
                                                            <input type="number"
                                                                name="products[{{ $index }}][new_product][reorder_limit]"
                                                                class="form-control mb-2" placeholder="حد إعادة الطلب"
                                                                value="{{ old('products.' . $index . '.new_product.reorder_limit') }}">
                                                            <input type="number"
                                                                name="products[{{ $index }}][new_product][minimum_stock]"
                                                                class="form-control mb-2"
                                                                placeholder="الحد الأدنى للمخزون"
                                                                value="{{ old('products.' . $index . '.new_product.minimum_stock') }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][location_in_stock]"
                                                                class="form-control mb-2" placeholder="موقع المخزون"
                                                                value="{{ old('products.' . $index . '.new_product.location_in_stock') }}">
                                                            <textarea name="products[{{ $index }}][new_product][product_details]" class="form-control mb-2"
                                                                placeholder="تفاصيل المنتج">{{ old('products.' . $index . '.new_product.product_details') }}</textarea>
                                                            <input type="number"
                                                                name="products[{{ $index }}][new_product][purchase_price]"
                                                                class="form-control mb-2" placeholder="سعر الشراء"
                                                                step="any"
                                                                value="{{ old('products.' . $index . '.new_product.purchase_price') }}">
                                                            <input type="number"
                                                                name="products[{{ $index }}][new_product][sale_price]"
                                                                class="form-control mb-2 new-product-price"
                                                                placeholder="سعر البيع" step="any"
                                                                value="{{ old('products.' . $index . '.new_product.sale_price') }}"
                                                                oninput="updateNewProductPrice(this, {{ $index }})">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][discounts]"
                                                                class="form-control mb-2" placeholder="الخصومات"
                                                                value="{{ old('products.' . $index . '.new_product.discounts') }}">
                                                            <input type="number"
                                                                name="products[{{ $index }}][new_product][expected_profit_margin]"
                                                                class="form-control mb-2" placeholder="هامش الربح المتوقع"
                                                                step="any"
                                                                value="{{ old('products.' . $index . '.new_product.expected_profit_margin') }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][supplier_name]"
                                                                class="form-control mb-2" placeholder="اسم المورد"
                                                                value="{{ old('products.' . $index . '.new_product.supplier_name') }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][supplier_contact_information]"
                                                                class="form-control mb-2"
                                                                placeholder="معلومات الاتصال بالمورد"
                                                                value="{{ old('products.' . $index . '.new_product.supplier_contact_information') }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][expected_delivery_time]"
                                                                class="form-control mb-2"
                                                                placeholder="وقت التسليم المتوقع"
                                                                value="{{ old('products.' . $index . '.new_product.expected_delivery_time') }}">

                                                            <input type="date"
                                                                name="products[{{ $index }}][new_product][date_added_to_stock]"
                                                                class="form-control mb-2"
                                                                value="{{ old('products.' . $index . '.new_product.date_added_to_stock', now()->format('Y-m-d')) }}">
                                                            <input type="date"
                                                                name="products[{{ $index }}][new_product][date_last_updated_to_stock]"
                                                                class="form-control mb-2"
                                                                value="{{ old('products.' . $index . '.new_product.date_last_updated_to_stock', now()->format('Y-m-d')) }}">
                                                            <input type="date"
                                                                name="products[{{ $index }}][new_product][expiry_date]"
                                                                class="form-control mb-2"
                                                                value="{{ old('products.' . $index . '.new_product.expiry_date') }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][unit_type]"
                                                                class="form-control mb-2" placeholder="نوع الوحدة"
                                                                value="{{ old('products.' . $index . '.new_product.unit_type') }}">
                                                            <input type="text"
                                                                name="products[{{ $index }}][new_product][product_size]"
                                                                class="form-control mb-2" placeholder="حجم المنتج"
                                                                value="{{ old('products.' . $index . '.new_product.product_size') }}">
                                                            <div class="col-md-4 mt-3">
                                                                <label class="mr-sm-2"
                                                                    for="products[{{ $index }}][new_product][currency]"
                                                                    style="font-family: 'Cairo', sans-serif;">
                                                                    نوع العملة
                                                                </label>
                                                                <select class="form-control select2"
                                                                    name="products[{{ $index }}][new_product][currency]"
                                                                    id="products_{{ $index }}_currency">
                                                                    <option disabled selected>من فضلك حدد العملة</option>
                                                                    <option value="1"
                                                                        {{ old("products.$index.new_product.currency") == '1' ? 'selected' : '' }}>
                                                                        LYD</option>
                                                                    <option value="2"
                                                                        {{ old("products.$index.new_product.currency") == '2' ? 'selected' : '' }}>
                                                                        EGP</option>
                                                                    <option value="3"
                                                                        {{ old("products.$index.new_product.currency") == '3' ? 'selected' : '' }}>
                                                                        USD ($)</option>
                                                                    <option value="4"
                                                                        {{ old("products.$index.new_product.currency") == '4' ? 'selected' : '' }}>
                                                                        TRY</option>
                                                                </select>
                                                            </div>

                                                            <input type="file"
                                                                name="products[{{ $index }}][new_product][image]"
                                                                class="form-control mb-2" accept="image/*">
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][quantity]"
                                                        class="form-control quantity" placeholder="أدخل الكمية"
                                                        value="{{ old('products.' . $index . '.quantity', 1) }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][price]"
                                                        class="form-control price" placeholder="السعر" step="0.01"
                                                        value="{{ old('products.' . $index . '.price') }}" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" name="products[{{ $index }}][total]"
                                                        class="form-control total" placeholder="الإجمالي" step="0.01"
                                                        value="{{ old('products.' . $index . '.total') }}" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="remove-row-btn"
                                                        onclick="removeRow(this)">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-outline-primary mb-3" onclick="addProductRow()">
                                    <i class="fas fa-plus"></i> إضافة منتج
                                </button>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div>
                                <label for="describe_shipments">وصف الشحنه</label>
                                <div class="textarea-group">
                                    <textarea class="form-control" name="describe_shipments" placeholder="أدخل ملاحظة">{{ old('describe_shipments') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="image">صورة الشحنة</label>
                                <input class="form-control" name="image" id="image" type="file"
                                    accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button class="btn btn-primary" type="submit">إنشاء الشحنة</button>
                    <a class="btn btn-light" href="{{ route('admin.shipment.client') }}">إلغاء</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('admin/assets/js/tooltip-init.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to calculate total for a single row
            function updateTotal($row) {
                const $price = $row.find('.price');
                const $quantity = $row.find('.quantity');
                const $total = $row.find('.total');
                const price = parseFloat($price.val()) || 0;
                const quantity = parseFloat($quantity.val()) || 0;
                const total = price * quantity;
                $total.val(total.toFixed(2));
                calculateRemaining();
            }

            // Function to calculate remaining amount
            function calculateRemaining() {
                let totalProducts = 0;
                $('.total').each(function() {
                    totalProducts += parseFloat($(this).val()) || 0;
                });
                const shippingCost = parseFloat($('#shipping_cost').val()) || 0;
                const returnValue = parseFloat($('#return_value').val()) || 0;
                const customsCost = parseFloat($('#customs_cost').val()) || 0;
                const remaining = totalProducts + shippingCost + customsCost - returnValue;
                $('#remaining').val(remaining.toFixed(2));
            }

            // Initialize calculations for existing rows
            $('#productTable tbody tr').each(function() {
                updateTotal($(this));
            });

            // Real-time updates for product rows
            $(document).on('input change', '.quantity, .new-product-price', function() {
                const $row = $(this).closest('tr');
                updateTotal($row);
            });

            // Real-time updates for shipping cost, return value, and customs cost
            $('#shipping_cost, #return_value, #customs_cost').on('input change', function() {
                calculateRemaining();
            });

            // Handle sender_phone input change
            $('#sender_phone').on('input', function() {
                const phone = $(this).val().trim();
                if (phone.length >= 10) {
                    $.ajax({
                        url: '{{ route('admin.client.search-by-phone') }}',
                        method: 'GET',
                        data: {
                            phone: phone
                        },
                        success: function(response) {
                            if (response.success && response.client) {
                                // Populate sender information
                                $('#sender_name').val(response.client.name || '');
                                $('#sender_country').val(response.client.country_id || '')
                                    .trigger('change');
                                $('#sender_region').val(response.client.region_id || '')
                                    .trigger('change');
                                $('#sender_address').val(response.client.address || '');

                                // Populate products in all rows
                                const $selects = $('#productTable tbody .product-select');
                                $selects.each(function() {
                                    const $select = $(this);
                                    $select.empty().append(
                                        '<option value="">اختر المنتج</option>');
                                    if (response.client.products && response.client
                                        .products.length > 0) {
                                        response.client.products.forEach(product => {
                                            $select.append(
                                                `<option value="${product.id}" data-price="${product.sale_price}" data-stock="${product.in_stock_quantity}">${product.name}</option>`
                                            );
                                        });
                                    } else {
                                        $select.append(
                                            '<option value="">لا توجد منتجات متاحة</option>'
                                        );
                                    }
                                    $select.trigger('change');
                                });
                            } else {
                                // Clear fields if no client found
                                $('#sender_name').val('');
                                $('#sender_country').val('').trigger('change');
                                $('#sender_region').val('').trigger('change');
                                $('#sender_address').val('');
                                const $selects = $('#productTable tbody .product-select');
                                $selects.each(function() {
                                    const $select = $(this);
                                    $select.empty().append(
                                        '<option value="">اختر المنتج</option>');
                                    $select.trigger('change');
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching client:', xhr.responseText);
                        }
                    });
                }
            });

            // Disable first option after selection
            $('#sender_country, #sender_region').on('change', function() {
                $(this).find('option:first').prop('disabled', true);
            });

            // Initialize is_new_product values
            $('.toggle-product-type').each(function() {
                const $checkbox = $(this);
                const $row = $checkbox.closest('tr');
                const $isNewProduct = $row.find('.is-new-product');
                $isNewProduct.val($checkbox.is(':checked') ? 1 : 0);
                updateTotal($row);
            });
        });

        function toggleProductInput(checkbox, index) {
            const $row = $(checkbox).closest('tr');
            const $select = $row.find('.product-select');
            const $newProductFields = $row.find('.new-product-fields');
            const $isNewProduct = $row.find('.is-new-product');
            const $price = $row.find('.price');
            const $total = $row.find('.total');
            const $quantity = $row.find('.quantity');

            if (checkbox.checked) {
                $select.hide().val('');
                $newProductFields.show();
                $isNewProduct.val(1);
                $price.prop('readonly', false);
                $price.val('');
                $total.val('');
            } else {
                $select.show();
                $newProductFields.hide();
                $newProductFields.find('input, textarea').val('');
                $isNewProduct.val(0);
                $price.prop('readonly', true);
                updateProductDetails($select);
            }
            updateTotal($row);
        }

        function updateProductDetails(select) {
            const $select = $(select);
            const $row = $select.closest('tr');
            const $price = $row.find('.price');
            const $quantity = $row.find('.quantity');
            const $total = $row.find('.total');
            const selectedOption = $select.find('option:selected');
            const price = parseFloat(selectedOption.data('price')) || 0;

            $price.val(price.toFixed(2));
            updateTotal($row);
        }

        function updateNewProductPrice(input, index) {
            const $row = $(input).closest('tr');
            const $price = $row.find('.price');
            const price = parseFloat($(input).val()) || 0;
            $price.val(price.toFixed(2));
            updateTotal($row);
        }

        function addProductRow() {
            const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length;
            const phone = $('#sender_phone').val().trim();
            let options = '<option value="">اختر المنتج</option>';

            $.ajax({
                url: '{{ route('admin.client.search-by-phone') }}',
                method: 'GET',
                data: {
                    phone: phone
                },
                success: function(response) {
                    if (response.success && response.client && response.client.products && response.client
                        .products.length > 0) {
                        response.client.products.forEach(product => {
                            options +=
                                `<option value="${product.id}" data-price="${product.sale_price}" data-stock="${product.in_stock_quantity}">${product.name}</option>`;
                        });
                    } else {
                        options += '<option value="">لا توجد منتجات متاحة</option>';
                    }

                    const row = table.insertRow();
                    row.innerHTML = `
                        <td>
                            <div class="product-input-group">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input toggle-product-type" type="checkbox"
                                        id="toggle_product_${rowCount}" onchange="toggleProductInput(this, ${rowCount})">
                                    <label class="form-check-label" for="toggle_product_${rowCount}">إضافة منتج جديد</label>
                                </div>
                                <input type="hidden" name="products[${rowCount}][is_new_product]" class="is-new-product" value="0">
                                <select name="products[${rowCount}][product_id]"
                                    class="form-control product-select" onchange="updateProductDetails(this)">
                                    ${options}
                                </select>
                                <div class="new-product-fields" style="display: none;">
                                    <input type="text" name="products[${rowCount}][new_product][name]"
                                        class="form-control mb-2" placeholder="اسم المنتج">
                                    <input type="text" name="products[${rowCount}][new_product][code]"
                                        class="form-control mb-2" placeholder="كود المنتج">
                                    <textarea name="products[${rowCount}][new_product][description]"
                                        class="form-control mb-2" placeholder="وصف المنتج"></textarea>
                                    <input type="text" name="products[${rowCount}][new_product][category]"
                                        class="form-control mb-2" placeholder="الفئة">
                                    <input type="text" name="products[${rowCount}][new_product][brand]"
                                        class="form-control mb-2" placeholder="العلامة التجارية">
                                    <input type="number" name="products[${rowCount}][new_product][in_stock_quantity]"
                                        class="form-control mb-2" placeholder="الكمية في المخزون">
                                    <input type="number" name="products[${rowCount}][new_product][reorder_limit]"
                                        class="form-control mb-2" placeholder="حد إعادة الطلب">
                                    <input type="number" name="products[${rowCount}][new_product][minimum_stock]"
                                        class="form-control mb-2" placeholder="الحد الأدنى للمخزون">
                                    <input type="text" name="products[${rowCount}][new_product][location_in_stock]"
                                        class="form-control mb-2" placeholder="موقع المخزون">
                                    <textarea name="products[${rowCount}][new_product][product_details]"
                                        class="form-control mb-2" placeholder="تفاصيل المنتج"></textarea>
                                    <input type="number" name="products[${rowCount}][new_product][purchase_price]"
                                        class="form-control mb-2" placeholder="سعر الشراء" step="any">
                                    <input type="number" name="products[${rowCount}][new_product][sale_price]"
                                        class="form-control mb-2 new-product-price" placeholder="سعر البيع" step="any"
                                        oninput="updateNewProductPrice(this, ${rowCount})">
                                    <input type="text" name="products[${rowCount}][new_product][discounts]"
                                        class="form-control mb-2" placeholder="الخصومات">
                                    <input type="number" name="products[${rowCount}][new_product][expected_profit_margin]"
                                        class="form-control mb-2" placeholder="هامش الربح المتوقع" step="any">
                                    <input type="text" name="products[${rowCount}][new_product][supplier_name]"
                                        class="form-control mb-2" placeholder="اسم المورد">
                                    <input type="text" name="products[${rowCount}][new_product][supplier_contact_information]"
                                        class="form-control mb-2" placeholder="معلومات الاتصال بالمورد">
                                    <input type="text" name="products[${rowCount}][new_product][expected_delivery_time]"
                                        class="form-control mb-2" placeholder="وقت التسليم المتوقع">
                                
                                    <input type="date" name="products[${rowCount}][new_product][date_added_to_stock]"
                                        class="form-control mb-2" value="{{ now()->format('Y-m-d') }}">
                                    <input type="date" name="products[${rowCount}][new_product][date_last_updated_to_stock]"
                                        class="form-control mb-2" value="{{ now()->format('Y-m-d') }}">
                                    <input type="date" name="products[${rowCount}][new_product][expiry_date]"
                                        class="form-control mb-2" placeholder="تاريخ الانتهاء">
                                    <input type="text" name="products[${rowCount}][new_product][unit_type]"
                                        class="form-control mb-2" placeholder="نوع الوحدة">
                                    <input type="text" name="products[${rowCount}][new_product][product_size]"
                                        class="form-control mb-2" placeholder="حجم المنتج">
               <div class="col-md-4 mt-3">
        <label class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">نوع العملة</label>
        <select class="form-control select2" name="products[${rowCount}][new_product][currency]">
            <option disabled selected>من فضلك حدد العملة</option>
            <option value="1">LYD</option>
            <option value="2">EGP</option>
            <option value="3">$ (USD)</option>
            <option value="4">TRY</option>
        </select>
    </div>
                                    <input type="file" name="products[${rowCount}][new_product][image]"
                                        class="form-control mb-2" accept="image/*">
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="number" name="products[${rowCount}][quantity]"
                                class="form-control quantity" placeholder="أدخل الكمية" value="1">
                        </td>
                        <td>
                            <input type="number" name="products[${rowCount}][price]"
                                class="form-control price" placeholder="السعر" step="0.01" readonly>
                        </td>
                        <td>
                            <input type="number" name="products[${rowCount}][total]"
                                class="form-control total" placeholder="الإجمالي" step="0.01" readonly>
                        </td>
                        <td>
                            <button type="button" class="remove-row-btn" onclick="removeRow(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    `;
                    const $newRow = $(row);
                    updateProductDetails($newRow.find('.product-select'));
                    updateTotal($newRow);
                },
                error: function(xhr) {
                    console.error('Error fetching products:', xhr.responseText);
                    const row = table.insertRow();
                    row.innerHTML = `
                        <td>
                            <div class="product-input-group">
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input toggle-product-type" type="checkbox"
                                        id="toggle_product_${rowCount}" onchange="toggleProductInput(this, ${rowCount})">
                                    <label class="form-check-label" for="toggle_product_${rowCount}">إضافة منتج جديد</label>
                                </div>
                                <input type="hidden" name="products[${rowCount}][is_new_product]" class="is-new-product" value="0">
                                <select name="products[${rowCount}][product_id]"
                                    class="form-control product-select" onchange="updateProductDetails(this)">
                                    <option value="">لا توجد منتجات متاحة</option>
                                </select>
                                <div class="new-product-fields" style="display: none;">
                                    <input type="text" name="products[${rowCount}][new_product][name]"
                                        class="form-control mb-2" placeholder="اسم المنتج">
                                    <input type="text" name="products[${rowCount}][new_product][code]"
                                        class="form-control mb-2" placeholder="كود المنتج">
                                    <textarea name="products[${rowCount}][new_product][description]"
                                        class="form-control mb-2" placeholder="وصف المنتج"></textarea>
                                    <input type="text" name="products[${rowCount}][new_product][category]"
                                        class="form-control mb-2" placeholder="الفئة">
                                    <input type="text" name="products[${rowCount}][new_product][brand]"
                                        class="form-control mb-2" placeholder="العلامة التجارية">
                                    <input type="number" name="products[${rowCount}][new_product][in_stock_quantity]"
                                        class="form-control mb-2" placeholder="الكمية في المخزون">
                                    <input type="number" name="products[${rowCount}][new_product][reorder_limit]"
                                        class="form-control mb-2" placeholder="حد إعادة الطلب">
                                    <input type="number" name="products[${rowCount}][new_product][minimum_stock]"
                                        class="form-control mb-2" placeholder="الحد الأدنى للمخزون">
                                    <input type="text" name="products[${rowCount}][new_product][location_in_stock]"
                                        class="form-control mb-2" placeholder="موقع المخزون">
                                    <textarea name="products[${rowCount}][new_product][product_details]"
                                        class="form-control mb-2" placeholder="تفاصيل المنتج"></textarea>
                                    <input type="number" name="products[${rowCount}][new_product][purchase_price]"
                                        class="form-control mb-2" placeholder="سعر الشراء" step="any">
                                    <input type="number" name="products[${rowCount}][new_product][sale_price]"
                                        class="form-control mb-2 new-product-price" placeholder="سعر البيع" step="any"
                                        oninput="updateNewProductPrice(this, ${rowCount})">
                                    <input type="text" name="products[${rowCount}][new_product][discounts]"
                                        class="form-control mb-2" placeholder="الخصومات">
                                    <input type="number" name="products[${rowCount}][new_product][expected_profit_margin]"
                                        class="form-control mb-2" placeholder="هامش الربح المتوقع" step="any">
                                    <input type="text" name="products[${rowCount}][new_product][supplier_name]"
                                        class="form-control mb-2" placeholder="اسم المورد">
                                    <input type="text" name="products[${rowCount}][new_product][supplier_contact_information]"
                                        class="form-control mb-2" placeholder="معلومات الاتصال بالمورد">
                                    <input type="text" name="products[${rowCount}][new_product][expected_delivery_time]"
                                        class="form-control mb-2" placeholder="وقت التسليم المتوقع">
                                  
                                    <input type="date" name="products[${rowCount}][new_product][date_added_to_stock]"
                                        class="form-control mb-2" value="{{ now()->format('Y-m-d') }}">
                                    <input type="date" name="products[${rowCount}][new_product][date_last_updated_to_stock]"
                                        class="form-control mb-2" value="{{ now()->format('Y-m-d') }}">
                                    <input type="date" name="products[${rowCount}][new_product][expiry_date]"
                                        class="form-control mb-2" placeholder="تاريخ الانتهاء">
                                    <input type="text" name="products[${rowCount}][new_product][unit_type]"
                                        class="form-control mb-2" placeholder="نوع الوحدة">
                                    <input type="text" name="products[${rowCount}][new_product][product_size]"
                                        class="form-control mb-2" placeholder="حجم المنتج">
                                    <input type="text" name="products[${rowCount}][new_product][currency]"
                                        class="form-control mb-2" placeholder="العملة" value="LYD">
                                    <input type="file" name="products[${rowCount}][new_product][image]"
                                        class="form-control mb-2" accept="image/*">
                                </div>
                            </div>
                        </td>
                        <td>
                            <input type="number" name="products[${rowCount}][quantity]"
                                class="form-control quantity" placeholder="أدخل الكمية" value="1">
                        </td>
                        <td>
                            <input type="number" name="products[${rowCount}][price]"
                                class="form-control price" placeholder="السعر" step="0.01" readonly>
                        </td>
                        <td>
                            <input type="number" name="products[${rowCount}][total]"
                                class="form-control total" placeholder="الإجمالي" step="0.01" readonly>
                        </td>
                        <td>
                            <button type="button" class="remove-row-btn" onclick="removeRow(this)">
                                <i class="fas fa-times"></i>
                            </button>
                        </td>
                    `;
                    const $newRow = $(row);
                    updateProductDetails($newRow.find('.product-select'));
                    updateTotal($newRow);
                }
            });
        }

        function removeRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateProductIndices();
            calculateRemaining();
        }

        function updateProductIndices() {
            const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
            const rows = table.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const $row = $(rows[i]);
                $row.find('.product-select').attr('name', `products[${i}][product_id]`);
                $row.find('.is-new-product').attr('name', `products[${i}][is_new_product]`);
                $row.find('.quantity').attr('name', `products[${i}][quantity]`);
                $row.find('.price').attr('name', `products[${i}][price]`);
                $row.find('.total').attr('name', `products[${i}][total]`);
                $row.find('input[name$="[new_product][name]"]').attr('name', `products[${i}][new_product][name]`);
                $row.find('input[name$="[new_product][code]"]').attr('name', `products[${i}][new_product][code]`);
                $row.find('textarea[name$="[new_product][description]"]').attr('name',
                    `products[${i}][new_product][description]`);
                $row.find('input[name$="[new_product][category]"]').attr('name', `products[${i}][new_product][category]`);
                $row.find('input[name$="[new_product][brand]"]').attr('name', `products[${i}][new_product][brand]`);
                $row.find('input[name$="[new_product][in_stock_quantity]"]').attr('name',
                    `products[${i}][new_product][in_stock_quantity]`);
                $row.find('input[name$="[new_product][reorder_limit]"]').attr('name',
                    `products[${i}][new_product][reorder_limit]`);
                $row.find('input[name$="[new_product][minimum_stock]"]').attr('name',
                    `products[${i}][new_product][minimum_stock]`);
                $row.find('input[name$="[new_product][location_in_stock]"]').attr('name',
                    `products[${i}][new_product][location_in_stock]`);
                $row.find('textarea[name$="[new_product][product_details]"]').attr('name',
                    `products[${i}][new_product][product_details]`);
                $row.find('input[name$="[new_product][purchase_price]"]').attr('name',
                    `products[${i}][new_product][purchase_price]`);
                $row.find('input[name$="[new_product][sale_price]"]').attr('name',
                    `products[${i}][new_product][sale_price]`);
                $row.find('input[name$="[new_product][discounts]"]').attr('name', `products[${i}][new_product][discounts]`);
                $row.find('input[name$="[new_product][expected_profit_margin]"]').attr('name',
                    `products[${i}][new_product][expected_profit_margin]`);
                $row.find('input[name$="[new_product][supplier_name]"]').attr('name',
                    `products[${i}][new_product][supplier_name]`);
                $row.find('input[name$="[new_product][supplier_contact_information]"]').attr('name',
                    `products[${i}][new_product][supplier_contact_information]`);
                $row.find('input[name$="[new_product][expected_delivery_time]"]').attr('name',
                    `products[${i}][new_product][expected_delivery_time]`);

                $row.find('input[name$="[new_product][date_added_to_stock]"]').attr('name',
                    `products[${i}][new_product][date_added_to_stock]`);
                $row.find('input[name$="[new_product][date_last_updated_to_stock]"]').attr('name',
                    `products[${i}][new_product][date_last_updated_to_stock]`);
                $row.find('input[name$="[new_product][expiry_date]"]').attr('name',
                    `products[${i}][new_product][expiry_date]`);
                $row.find('input[name$="[new_product][unit_type]"]').attr('name', `products[${i}][new_product][unit_type]`);
                $row.find('input[name$="[new_product][product_size]"]').attr('name',
                    `products[${i}][new_product][product_size]`);
                $row.find('select[name$="[new_product][currency]"]').attr('name', `products[${i}][new_product][currency]`);

                $row.find('input[name$="[new_product][image]"]').attr('name', `products[${i}][new_product][image]`);
                $row.find('.toggle-product-type').attr('id', `toggle_product_${i}`)
                    .attr('onchange', `toggleProductInput(this, ${i})`);
                $row.find('.form-check-label').attr('for', `toggle_product_${i}`);
            }
        }
    </script>
@endsection
