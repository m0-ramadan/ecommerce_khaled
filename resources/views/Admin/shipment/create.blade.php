@extends('Admin.layout.master')

@section('title', 'إنشاء شحنة زبون')

@section('css')
    <style>
        label {
            font-family: 'Cairo', sans-serif;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-family: 'Cairo', sans-serif;
            color: #dc3545;
            font-weight: bold;
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

        .content-table td input {
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
    </style>
@endsection

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <x-breadcrumb :items="[
                'لوحة التحكم' => '/admin',
                'شحنات الزبائن' => '/admin/shipment/client',
                'انشاء شحنة زبون' => '',
            ]" />
        </div>

        <div class="card-body">
            <form class="form theme-form" action="{{ route('admin.shipment.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-12">
                        <!-- Sender Information -->
                        <h6 class="mb-3">معلومات المرسل</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label for="sender_phone">رقم هاتف المرسل</label>
                                <input class="form-control @error('sender_phone') is-invalid @enderror" name="sender_phone"
                                    id="sender_phone" type="text" placeholder="أدخل رقم الهاتف"
                                    value="{{ old('sender_phone') }}">
                                @error('sender_phone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="sender_name">اسم المرسل</label>
                                <input class="form-control @error('sender_name') is-invalid @enderror" name="sender_name"
                                    id="sender_name" type="text" placeholder="أدخل اسم المرسل"
                                    value="{{ old('sender_name') }}">
                                @error('sender_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="sender_country">دولة المرسل</label>
                                <select class="form-control @error('sender_country') is-invalid @enderror"
                                    name="sender_country" id="sender_country">
                                    <option value="">اختر الدولة</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('sender_country') == $country->id ? 'selected' : '' }}>
                                            {{ $country->country_ar }}</option>
                                    @endforeach
                                </select>
                                @error('sender_country')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="sender_region">منطقة المرسل</label>
                                <select class="form-control @error('sender_region') is-invalid @enderror"
                                    name="sender_region" id="sender_region">
                                    <option value="">اختر المنطقة</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}"
                                            {{ old('sender_region') == $region->id ? 'selected' : '' }}>
                                            {{ $region->region_ar }}</option>
                                    @endforeach
                                </select>
                                @error('sender_region')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="sender_address">عنوان المرسل</label>
                                <textarea class="form-control @error('sender_address') is-invalid @enderror" name="sender_address" id="sender_address"
                                    placeholder="أدخل عنوان المرسل">{{ old('sender_address') }}</textarea>
                                @error('sender_address')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Receiver Information -->
                        <h6 class="mb-3">معلومات المستلم</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-3">
                                <label for="name_received">اسم المستلم</label>
                                <input class="form-control @error('name_received') is-invalid @enderror"
                                    name="name_received" id="name_received" type="text" placeholder="أدخل اسم المستلم"
                                    value="{{ old('name_received') }}">
                                @error('name_received')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="phone_received">رقم هاتف المستلم</label>
                                <input class="form-control @error('phone_received') is-invalid @enderror"
                                    name="phone_received" id="phone_received" type="text" placeholder="أدخل رقم الهاتف"
                                    value="{{ old('phone_received') }}">
                                @error('phone_received')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="country_received_id">دولة المستلم</label>
                                <select class="form-control @error('country_received_id') is-invalid @enderror"
                                    name="country_received_id" id="country_received_id">
                                    <option value="">اختر الدولة</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}"
                                            {{ old('country_received_id') == $country->id ? 'selected' : '' }}>
                                            {{ $country->country_ar }}</option>
                                    @endforeach
                                </select>
                                @error('country_received_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="country_region_id">منطقة المستلم</label>
                                <select class="form-control @error('country_region_id') is-invalid @enderror"
                                    name="country_region_id" id="country_region_id">
                                    <option value="">اختر المنطقة</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}"
                                            {{ old('country_region_id') == $region->id ? 'selected' : '' }}>
                                            {{ $region->region_ar }}</option>
                                    @endforeach
                                </select>
                                @error('country_region_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="address_received">عنوان المستلم</label>
                                <textarea class="form-control @error('address_received') is-invalid @enderror" name="address_received"
                                    id="address_received" placeholder="أدخل عنوان المستلم">{{ old('address_received') }}</textarea>
                                @error('address_received')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
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
                                    <select class="form-control @error('branches_from') is-invalid @enderror"
                                        name="branches_from" id="branches_from">
                                        <option value="">اختر الفرع</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branches_from') == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('branches_from')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label for="branches_to">الفرع المستلم</label>
                                <select class="form-control @error('branches_to') is-invalid @enderror"
                                    name="branches_to" id="branches_to">
                                    <option value="">اختر الفرع</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branches_to') == $branch->id ? 'selected' : '' }}>{{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('branches_to')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            {{-- <div class="col-md-3">
                                <label for="price">قيمة الشحنة</label>
                                <input class="form-control @error('price') is-invalid @enderror" name="price"
                                    id="price" type="number" step="0.01" placeholder="قيمة الشحنة"
                                    value="{{ old('price') }}">
                                @error('price')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div> --}}
                            <div class="col-md-3">
                                <label for="shipping_cost">سعر التوصيل الداخلي</label>
                                <input class="form-control @error('shipping_cost') is-invalid @enderror"
                                    name="shipping_cost" id="shipping_cost" type="number" step="0.01"
                                    placeholder="أدخل القيمة" value="{{ old('shipping_cost') }}">
                                @error('shipping_cost')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="packaging_cost">قيمة التغليف</label>
                                <input class="form-control @error('packaging_cost') is-invalid @enderror"
                                    name="packaging_cost" id="packaging_cost" type="number" step="0.01"
                                    placeholder="أدخل القيمة" value="{{ old('packaging_cost') }}">
                                @error('packaging_cost')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="expense_code">المصروف</label>
                                <input class="form-control @error('expense_code') is-invalid @enderror"
                                    name="expense_code" id="expense_code" type="number" step="0.01"
                                    placeholder="أدخل القيمة" value="{{ old('expense_code') }}">
                                @error('expense_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3 d-none">
                                <label for="refund_code">المدفوع</label>
                                <input class="form-control @error('refund_code') is-invalid @enderror" name="refund_code"
                                    id="refund_code" type="number" step="0.01" placeholder="أدخل القيمة"
                                    value="0">
                                @error('refund_code')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-3">
                                <label for="remaining">المتبقي</label>
                                <input class="form-control" name="remaining" id="remaining" type="number"
                                    step="0.01" placeholder="المتبقي" value="{{ old('remaining', 0) }}" readonly>
                            </div>
                            {{-- <div class="col-md-3">
                                <label for="payment_type_id">نوع الدفع</label>
                                <select class="form-control @error('payment_type_id') is-invalid @enderror"
                                    name="payment_type_id" id="payment_type_id">
                                    <option value="">اختر نوع الدفع</option>
                                    @foreach ($paymentTypes as $paymentType)
                                        <option value="{{ $paymentType->id }}"
                                            {{ old('payment_type_id') == $paymentType->id ? 'selected' : '' }}>
                                            {{ $paymentType->name }}</option>
                                    @endforeach
                                </select>
                                @error('payment_type_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div> --}}
                            <div class="col-md-3">
                                <label for="currency_id">نوع العملة</label>
                                <select class="form-control @error('currency_id') is-invalid @enderror"
                                    name="currency_id" id="currency_id">
                                    <option value="">من فضلك حدد عملة الخزينة</option>
                                    <option value="1" {{ old('currency_id') == '1' ? 'selected' : '' }}>LYD</option>
                                    <option value="2" {{ old('currency_id') == '2' ? 'selected' : '' }}>EGP</option>
                                    <option value="3" {{ old('currency_id') == '3' ? 'selected' : '' }}>$ (USD)
                                    </option>
                                    <option value="4" {{ old('currency_id') == '4' ? 'selected' : '' }}>TRY</option>
                                </select>
                                @error('currency_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="natural_shipments">طبيعة الشحن</label>
                                <select class="form-control @error('natural_shipments') is-invalid @enderror"
                                    name="natural_shipments" id="natural_shipments">
                                    <option value="">اختر نوع الشحن</option>
                                    <option value="1"
                                        {{ old('natural_shipments', $shipment->natural_shipments ?? '') == 1 ? 'selected' : '' }}>
                                        بطئ
                                    </option>
                                    <option value="2"
                                        {{ old('natural_shipments', $shipment->natural_shipments ?? '') == 2 ? 'selected' : '' }}>
                                        سريع
                                    </option>
                                    <option value="3"
                                        {{ old('natural_shipments', $shipment->natural_shipments ?? '') == 3 ? 'selected' : '' }}>
                                        شحن خاص
                                    </option>
                                </select>

                                @error('natural_shipments')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label" for="properties_shipment_id">خصائص الشحنة</label>

                                <select name="properties_shipment_id[]" id="properties_shipment_id"
                                    class="form-control select2 @error('properties_shipment_id') is-invalid @enderror"
                                    multiple="multiple">
                                    @foreach ($propertiesShipments as $property)
                                        <option value="{{ $property->id }}">
                                            {{ $property->name }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('properties_shipment_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>


                            <div class="col-md-3">
                                <label for="service_id">نوع الشحن</label>
                                <select class="form-control @error('service_id') is-invalid @enderror" name="service_id"
                                    id="service_id">
                                    <option value="">اختر نوع الشحنة</option>
                                    @foreach ($services as $service)
                                        <option value="{{ $service->id }}"
                                            {{ old('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-3">
                                <label for="type_vehicle_id">نوع المركبة</label>
                                <select class="form-control @error('type_vehicle_id') is-invalid @enderror"
                                    name="type_vehicle_id" id="type_vehicle_id">
                                    <option value="">اختر نوع المركبة</option>
                                    @foreach ($vehicleTypes as $vehicleType)
                                        <option value="{{ $vehicleType->id }}"
                                            {{ old('type_vehicle_id') == $vehicleType->id ? 'selected' : '' }}>
                                            {{ $vehicleType->name }}</option>
                                    @endforeach
                                </select>
                                @error('type_vehicle_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="weight">الوزن (كجم)</label>
                                <input class="form-control @error('weight') is-invalid @enderror" name="weight"
                                    id="weight" type="number" step="0.01" placeholder="أدخل الوزن"
                                    value="{{ old('weight') }}">
                                @error('weight')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="type_invoice">نوع الفاتورة</label>
                                    <select class="form-control @error('type_invoice') is-invalid @enderror"
                                        name="type_invoice" id="type_invoice">
                                        <option value="">اختر نوع الفاتورة</option>
                                        <option value="1"
                                            {{ old('type_invoice', $shipment->type_invoice ?? '') == 1 ? 'selected' : '' }}>
                                            بدون فاتورة
                                        </option>
                                        <option value="2"
                                            {{ old('type_invoice', $shipment->type_invoice ?? '') == 2 ? 'selected' : '' }}>
                                            نصف فاتورة
                                        </option>
                                        <option value="3"
                                            {{ old('type_invoice', $shipment->type_invoice ?? '') == 3 ? 'selected' : '' }}>
                                            فاتورة كاملة
                                        </option>
                                    </select>

                                    @error('type_invoice')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="receipt_location">موقع الاستلام</label>
                                    <select class="form-control @error('receipt_location') is-invalid @enderror"
                                        name="receipt_location" id="receipt_location">
                                        <option value="">اختر موقع الاستلام</option>
                                        <option value="1"
                                            {{ old('receipt_location', $shipment->receipt_location ?? '') == 1 ? 'selected' : '' }}>
                                            فرع
                                        </option>
                                        <option value="2"
                                            {{ old('receipt_location', $shipment->receipt_location ?? '') == 2 ? 'selected' : '' }}>
                                            توصيل داخلي
                                        </option>
                                    </select>

                                    @error('receipt_location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="delivery_location">موقع التسليم</label>
                                    <select class="form-control @error('delivery_location') is-invalid @enderror"
                                        name="delivery_location" id="delivery_location">
                                        <option value="">اختر موقع التسليم</option>
                                        <option value="1"
                                            {{ old('delivery_location', $shipment->delivery_location ?? '') == 1 ? 'selected' : '' }}>
                                            من الفرع
                                        </option>
                                        <option value="2"
                                            {{ old('delivery_location', $shipment->delivery_location ?? '') == 2 ? 'selected' : '' }}>
                                            توصيل للمنزل
                                        </option>
                                    </select>

                                    @error('delivery_location')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3 d-none">
                                <label for="customs_cost">قيمة الجمرك</label>
                                <input class="form-control @error('customs_cost') is-invalid @enderror"
                                    name="customs_cost" id="customs_cost" type="number" step="0.01"
                                    placeholder="أدخل القيمة" value="0">
                                @error('customs_cost')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="cost_shipment_by">دافع تكلفة الشحنه</label>
                                <select class="form-control @error('cost_shipment_by') is-invalid @enderror"
                                    name="cost_shipment_by" id="cost_shipment_by">
                                    <option value="">اختر دافع التكلفة</option>
                                    <option value="1" {{ old('cost_shipment_by') == '1' ? 'selected' : '' }}>مرسل
                                    </option>
                                    <option value="2" {{ old('cost_shipment_by') == '2' ? 'selected' : '' }}>مستلم
                                    </option>
                                </select>
                                @error('cost_shipment_by')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label for="additional_shipping_cost">تكلفة التوصيل الداخلي علي من</label>
                                <select class="form-control @error('additional_shipping_cost') is-invalid @enderror"
                                    name="additional_shipping_cost" id="additional_shipping_cost">
                                    <option value="">اختر دافع التكلفة</option>
                                    <option value="1" {{ old('additional_shipping_cost') == '1' ? 'selected' : '' }}>
                                        مرسل
                                    </option>
                                    <option value="2" {{ old('additional_shipping_cost') == '2' ? 'selected' : '' }}>
                                        مستلم
                                    </option>
                                </select>
                                @error('additional_shipping_cost')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="customs_included"> الجمارك </label>
                            <select class="form-control @error('customs_included') is-invalid @enderror"
                                name="customs_included" id="customs_included">
                                <option value="">اختر نوع الجمارك</option>
                                <option value="0" {{ old('customs_included') == '0' ? 'selected' : '' }}>شامل الجمرك
                                </option>
                                <option value="1" {{ old('customs_included') == '1' ? 'selected' : '' }}>غير شامل
                                    الجمرك
                                </option>
                            </select>
                            @error('customs_included')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Shipment Contents Table -->
                        <!-- Shipment Contents Table -->
                        <h6 class="mb-3">محتويات الشحنة</h6>
                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <table class="content-table" id="contentTable">
                                    <thead>
                                        <tr>
                                            <th>اسم المحتوى</th>
                                            <th>الكمية</th>
                                            <th>السعر</th>
                                            <th>الإجراء</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $contents = old('contents', [[]]); // إذا لم يكن هناك old، ابدأ بصف واحد فارغ
                                        @endphp
                                        @foreach ($contents as $index => $content)
                                            <tr>
                                                <td>
                                                    <input type="text" name="contents[{{ $index }}][name]"
                                                        class="form-control" placeholder="أدخل الاسم"
                                                        value="{{ old('contents.' . $index . '.name', $content['name'] ?? '') }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="contents[{{ $index }}][quantity]"
                                                        class="form-control" placeholder="أدخل الكمية"
                                                        value="{{ old('contents.' . $index . '.quantity', $content['quantity'] ?? '') }}"
                                                        min="1">
                                                </td>
                                                <td>
                                                    <input type="number" name="contents[{{ $index }}][price]"
                                                        class="form-control" placeholder="أدخل السعر"
                                                        value="{{ old('contents.' . $index . '.price', $content['price'] ?? '') }}"
                                                        min="0" step="any">
                                                </td>
                                                <td>
                                                    <button type="button" class="remove-row-btn"
                                                        onclick="removeRow(this)"><i class="fas fa-times"></i></button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <button type="button" class="btn btn-outline-primary mb-3" onclick="addRow()">
                                    <i class="fas fa-plus"></i> إضافة محتوى
                                </button>
                                @error('contents.*.name')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                @error('contents.*.quantity')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                                @error('contents.*.price')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            {{-- <div id="textareaContainer"> --}}
                            <div>

                                <label for="notes"> وصف الشحنه </label>
                                <div class="textarea-group">
                                    <textarea class="form-control @error('notes.*') is-invalid @enderror" name="describe_shipments"
                                        placeholder="أدخل ملاحظة">{{ old('notes.0') }}</textarea>
                                    @error('notes.*')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            {{-- <button type="button" class="btn btn-outline-primary mb-3" onclick="addTextarea()">
                                <i class="fas fa-plus"></i> إضافة ملاحظة
                            </button> --}}
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-12">
                                <label for="image">صورة الشحنة</label>
                                <input class="form-control @error('image') is-invalid @enderror" name="image"
                                    id="image" type="file" accept="image/*">
                                @error('image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
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
            // Handle sender_phone input change
            $('#sender_phone').on('input', function() {
                const phone = $(this).val().trim();
                console.log('📞 Phone input changed:', phone); // log phone value

                if (phone.length >= 10) {
                    console.log('🔍 Searching for client by phone...');

                    $.ajax({
                        url: '{{ route('admin.client.search-by-phone') }}',
                        method: 'GET',
                        data: {
                            phone: phone
                        },
                        success: function(response) {
                            console.log('✅ Server response:', response); // log response

                            if (response.success && response.client) {
                                console.log('👤 Client found:', response.client);

                                $('#sender_name').val(response.client.name || '');
                                $('#sender_country').val(response.client.country_id || '')
                                    .trigger('change');
                                $('#sender_region').val(response.client.region_id || '')
                                    .trigger('change');
                                $('#sender_address').val(response.client.address || '');
                            } else {
                                console.log('⚠️ No client found for this phone.');
                            }
                        },
                        error: function(xhr) {
                            console.error('❌ Error fetching client:', xhr.status, xhr
                                .responseText);
                        }
                    });
                }
            });


            // Calculate remaining amount
            function calculateRemaining() {
                const price = parseFloat($('#price').val()) || 0;
                const shippingCost = parseFloat($('#shipping_cost').val()) || 0;
                const expenseCode = parseFloat($('#expense_code').val()) || 0;
                const refundCode = parseFloat($('#refund_code').val()) || 0;
                const packagingCost = parseFloat($('#packaging_cost').val()) || 0;

                const remaining = (price + shippingCost + expenseCode + packagingCost) - refundCode;
                $('#remaining').val(remaining.toFixed(2));
            }

            $('#price, #shipping_cost, #expense_code, #refund_code, #packaging_cost').on('input',
                calculateRemaining);

            calculateRemaining();

            $('#sender_country, #sender_region').on('change', function() {
                $(this).find('option:first').prop('disabled', true);
            });
        });


        function addRow() {
            const table = document.getElementById('contentTable').getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length;
            const row = table.insertRow();
            row.innerHTML = `
                <td><input type="text" name="contents[${rowCount}][name]" class="form-control" placeholder="أدخل الاسم"></td>
                <td><input type="number" name="contents[${rowCount}][quantity]" class="form-control" placeholder="أدخل الكمية" min="1"></td>
                 <td><input type="number" name="contents[${rowCount}][price]" class="form-control" placeholder="أدخل السعر" min="0"step="any"></td>
                <td><button type="button" class="remove-row-btn" onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
            `;
        }

        function removeRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateIndices();
        }

        function updateIndices() {
            const table = document.getElementById('contentTable').getElementsByTagName('tbody')[0];
            const rows = table.getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                const inputs = rows[i].getElementsByTagName('input');
                inputs[0].name = `contents[${i}][name]`;
                inputs[1].name = `contents[${i}][quantity]`;
            }
        }

        function addTextarea() {
            const container = document.getElementById('textareaContainer');
            const group = document.createElement('div');
            group.className = 'textarea-group';
            const textarea = document.createElement('textarea');
            textarea.className = 'form-control mb-2';
            textarea.name = 'notes[]';
            textarea.placeholder = 'أدخل ملاحظة';
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-btn';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.onclick = () => container.removeChild(group);
            group.appendChild(textarea);
            group.appendChild(removeBtn);
            container.appendChild(group);
        }
    </script>
@endsection
