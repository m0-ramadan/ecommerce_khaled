@extends('Admin.layout.master')
<?php \Illuminate\Support\Facades\App::setLocale('ar'); ?>

@section('title', 'تعديل شحنة ' . ($shipment->code ?? '--'))

@section('css')
    <style>
        .form-label {
            font-family: 'Cairo', sans-serif;
        }

        .invalid-feedback {
            display: block !important;
            color: #dc3545 !important;
            font-weight: bold;
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
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

        .notes-table {
            width: 100%;
            margin-top: 10px;
        }

        .notes-table td {
            vertical-align: top;
            padding: 8px;
        }

        .delete-note-btn {
            background: transparent;
            border: none;
            color: #dc3545;
            cursor: pointer;
        }
    </style>
@endsection

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @if ($shipment->type == 2)
                <x-breadcrumb :items="[
                    'لوحة التحكم' => '/admin',
                    'شحنات التجار' => '/admin/shipment/1',
                    ($shipment->code ?? '--') . ' تعديل شحنة رقم' => '',
                ]" />
            @elseif ($shipment->type == 1)
                <x-breadcrumb :items="[
                    'لوحة التحكم' => '/admin',
                    'شحنات الزبائن' => '/admin/shipment/client',
                    ($shipment->code ?? '--') . ' تعديل شحنة رقم' => '',
                ]" />
            @endif
        </div>

        <div class="card-body">
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    @if ($errors->any())
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="إغلاق"></button>
                </div>
            @endif
            @php
                $route = $shipment->type == 1 ? 'admin.shipment.update' : 'admin.shipment.updateForMerchant';
            @endphp
            <form class="form theme-form" action="{{ route($route, $shipment) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('POST')

                <!-- Sender Information -->
                <p class="bg-success p-3">معلකميات الشحنة</p>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="sender_name">اسم المرسل</label>
                        <input class="form-control @error('sender_name') is-invalid @enderror" name="sender_name"
                            id="sender_name" type="text" placeholder="أدخل اسم المرسل"
                            value="{{ old('sender_name', $shipment->sender_name ?? ($shipment->client ? $shipment->client->name : '')) }}">
                        @error('sender_name')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="sender_address">عنوان المرسل</label>
                        <input class="form-control @error('sender_address') is-invalid @enderror" name="sender_address"
                            id="sender_address" type="text" placeholder="أدخل عنوان المرسل"
                            value="{{ old('sender_address', $shipment->sender_address ?? ($shipment->client ? $shipment->client->address : '')) }}">
                        @error('sender_address')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="sender_email">البريد الإلكتروني للمرسل</label>
                        <input class="form-control @error('sender_email') is-invalid @enderror" name="sender_email"
                            id="sender_email" type="email" placeholder="أدخل البريد الإلكتروني"
                            value="{{ old('sender_email', $shipment->sender_email ?? ($shipment->client ? $shipment->client->email : '')) }}">
                        @error('sender_email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="sender_phone">رقم هاتف المرسل</label>
                        <input class="form-control @error('sender_phone') is-invalid @enderror" name="sender_phone"
                            id="sender_phone" type="text" placeholder="أدخل رقم الهاتف"
                            value="{{ old('sender_phone', $shipment->sender_phone ?? ($shipment->client ? $shipment->client->phone : '')) }}">
                        @error('sender_phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="sender_region">منطقة المرسل <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('sender_region') is-invalid @enderror" name="sender_region"
                            id="sender_region">
                            <option value="" disabled>اختر المنطقة</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('sender_region', $shipment->regionSender ? $shipment->regionSender->id : ($shipment->client ? $shipment->client->region_id : '')) == $region->id ? 'selected' : '' }}>
                                    {{ $region->region_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('sender_region')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="sender_country">دولة المرسل <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('sender_country') is-invalid @enderror" name="sender_country"
                            id="sender_country">
                            <option value="" disabled>اختر الدولة</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('sender_country', $shipment->countrySender ? $shipment->countrySender->id : ($shipment->client ? $shipment->client->country_id : '')) == $country->id ? 'selected' : '' }}>
                                    {{ $country->country_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('sender_country')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Recipient Information -->
                <hr>
                <p class="bg-success p-3">معلومات المستلم</p>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="name_received">اسم المستلم</label>
                        <input class="form-control @error('name_received') is-invalid @enderror" name="name_received"
                            id="name_received" type="text" placeholder="أدخل اسم المستلم"
                            value="{{ old('name_received', $shipment->name_received ?? '') }}">
                        @error('name_received')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="address_received">عنوان المستلم</label>
                        <input class="form-control @error('address_received') is-invalid @enderror" name="address_received"
                            id="address_received" type="text" placeholder="أدخل عنوان المستلم"
                            value="{{ old('address_received', $shipment->address_received ?? '') }}">
                        @error('address_received')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="client_phone">رقم هاتف المستلم</label>
                        <input class="form-control @error('client_phone') is-invalid @enderror" name="client_phone"
                            id="client_phone" type="text" placeholder="أدخل رقم الهاتف"
                            value="{{ old('client_phone', $shipment->phone_received ?? '') }}">
                        @error('client_phone')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="client_phone2">رقم هاتف المستلم الثاني</label>
                        <input class="form-control @error('client_phone2') is-invalid @enderror" name="client_phone2"
                            id="client_phone2" type="text" placeholder="أدخل رقم الهاتف الثاني"
                            value="{{ old('client_phone2', $shipment->phone_received_2 ?? '') }}">
                        @error('client_phone2')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="client_region">منطقة المستلم <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('client_region') is-invalid @enderror" name="client_region"
                            id="client_region">
                            <option value="" disabled>اختر المنطقة</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region->id }}"
                                    {{ old('client_region', $shipment->country_region_id ?? '') == $region->id ? 'selected' : '' }}>
                                    {{ $region->region_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_region')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="client_country">دولة المستلم <span
                                class="text-danger">*</span></label>
                        <select class="form-select @error('client_country') is-invalid @enderror" name="client_country"
                            id="client_country">
                            <option value="" disabled>اختر الدولة</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}"
                                    {{ old('client_country', $shipment->country_received_id ?? '') == $country->id ? 'selected' : '' }}>
                                    {{ $country->country_ar }}
                                </option>
                            @endforeach
                        </select>
                        @error('client_country')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Shipment Details -->
                <hr>
                <p class="bg-success p-3">تفاصيل الشحنة</p>
                <div class="row">
                    <!-- من فرع -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="branches_from">من فرع</label>
                        <select class="form-control select2 @error('branches_from') is-invalid @enderror"
                            name="branches_from" id="branches_from" disabled>
                            <option value="">اختر الفرع المرسل</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branches_from', $shipment->branches_from ?? '') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} ({{ $branch->region->region_ar ?? 'غير محدد' }})
                                </option>
                            @endforeach
                        </select>
                        @error('branches_from')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- إلى فرع -->
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="branches_to">إلى فرع</label>
                        <select class="form-control select2 @error('branches_to') is-invalid @enderror"
                            name="branches_to" id="branches_to">
                            <option value="">اختر الفرع المستلم</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}"
                                    {{ old('branches_to', $shipment->branches_to ?? '') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }} ({{ $branch->region->region_ar ?? 'غير محدد' }})
                                </option>
                            @endforeach
                        </select>
                        @error('branches_to')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="size">الوزن (كجم)</label>
                        <input class="form-control @error('size') is-invalid @enderror" name="size" id="size"
                            type="number" step="0.01" placeholder="أدخل الوزن"
                            value="{{ old('size', $shipment->size ?? ($shipment->weight ?? '')) }}">
                        @error('size')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3 d-none">
                        <label class="form-label" for="box_number">العدد </label>
                        <input class="form-control @error('box_number') is-invalid @enderror" name="box_number"
                            id="box_number" type="number" placeholder="أدخل العدد " value="1">
                        @error('box_number')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="type_invoice">نوع الفاتورة</label>
                        <select class="form-control @error('type_invoice') is-invalid @enderror" name="type_invoice"
                            id="type_invoice">
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
                {{-- <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="length">الطول (سم)</label>
                        <input class="form-control @error('length') is-invalid @enderror" name="length" id="length"
                            type="number" step="0.01" placeholder="أدخل الطول"
                            value="{{ old('length', $shipment->length ?? '') }}">
                        @error('length')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="width">العرض (سم)</label>
                        <input class="form-control @error('width') is-invalid @enderror" name="width" id="width"
                            type="number" step="0.01" placeholder="أدخل العرض"
                            value="{{ old('width', $shipment->width ?? '') }}">
                        @error('width')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="height">الارتفاع (سم)</label>
                        <input class="form-control @error('height') is-invalid @enderror" name="height" id="height"
                            type="number" step="0.01" placeholder="أدخل الارتفاع"
                            value="{{ old('height', $shipment->height ?? '') }}">
                        @error('height')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div> --}}
                <div class="row">
                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label" for="cbm">الحجم (CBM)</label>
                        <input class="form-control @error('cbm') is-invalid @enderror" name="cbm" id="cbm"
                            type="number" step="0.01" placeholder="أدخل الحجم"
                            value="{{ old('cbm', $shipment->cbm ?? '') }}" readonly>
                        @error('cbm')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label" for="type_content">نوع المحتوى</label>
                        <input class="form-control @error('type_content') is-invalid @enderror" name="type_content"
                            id="type_content" type="text" placeholder="أدخل نوع المحتوى"
                            value="{{ old('type_content', $shipment->type_content ?? '') }}">
                        @error('type_content')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div> --}}
                </div>
                {{-- <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="type_invoice">نوع الفاتورة</label>
                        <input class="form-control @error('type_invoice') is-invalid @enderror" name="type_invoice"
                            id="type_invoice" type="text" placeholder="أدخل نوع الفاتورة"
                            value="{{ old('type_invoice', $shipment->type_invoice ?? '') }}">
                        @error('type_invoice')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="receipt_location">موقع الاستلام</label>
                        <input class="form-control @error('receipt_location') is-invalid @enderror"
                            name="receipt_location" id="receipt_location" type="text"
                            placeholder="أدخل موقع الاستلام"
                            value="{{ old('receipt_location', $shipment->receipt_location ?? '') }}">
                        @error('receipt_location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div> --}}
                <div class="row">
                    {{-- <div class="col-md-6 mb-3">
                        <label class="form-label" for="delivery_location">موقع التسليم</label>
                        <input class="form-control @error('delivery_location') is-invalid @enderror"
                            name="delivery_location" id="delivery_location" type="text"
                            placeholder="أدخل موقع التسليم"
                            value="{{ old('delivery_location', $shipment->delivery_location ?? '') }}">
                        @error('delivery_location')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="description">وصف الشحنة <span class="text-danger">*</span></label>
                        <input class="form-control @error('description') is-invalid @enderror" name="description"
                            id="description" type="text" placeholder="أدخل وصف الشحنة"
                            value="{{ old('description', $shipment->details ?? ($shipment->describe_shipments ?? '')) }}">
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="expense_code">المصروف</label>
                        <input class="form-control @error('expense_code') is-invalid @enderror" name="expense_code"
                            id="expense_code" type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('expense_code', $shipment->expense_code ?? '') }}">
                        @error('expense_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="col-md-4 mb-3">
                        <label class="form-label" for="refund_code">المدفوع</label>
                        <input class="form-control @error('refund_code') is-invalid @enderror" name="refund_code"
                            id="refund_code" type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('refund_code', $shipment->refund_code ?? '') }}">
                        @error('refund_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="remaining">المتبقي</label>
                        <input class="form-control" name="remaining" id="remaining" type="number" step="0.01"
                            placeholder="المتبقي"
                            value="{{ old(
                                'remaining',
                                ($shipment->price ?? 0) +
                                    ($shipment->shipping_cost ?? 0) +
                                    ($shipment->packaging_cost ?? 0) +
                                    ($shipment->customs_cost ?? 0) +
                                    ($shipment->expense_code ?? 0) -
                                    ($shipment->refund_code ?? 0),
                            ) }}"
                            readonly>

                    </div>
                </div>
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="shipping_cost">سعر التوصيل الداخلي</label>
                        <input class="form-control @error('shipping_cost') is-invalid @enderror" name="shipping_cost"
                            id="shipping_cost" type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('shipping_cost', $shipment->shipping_cost ?? '') }}">
                        @error('shipping_cost')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="packaging_cost">قيمة التغليف</label>
                        <input class="form-control @error('packaging_cost') is-invalid @enderror" name="packaging_cost"
                            id="packaging_cost" type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('packaging_cost', $shipment->packaging_cost ?? '') }}">
                        @error('packaging_cost')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="customs_cost">قيمة الجمرك</label>
                        <input class="form-control @error('customs_cost') is-invalid @enderror" name="customs_cost"
                            id="customs_cost" type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('customs_cost', $shipment->customs_cost ?? '') }}">
                        @error('customs_cost')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="row d-none">
                    <div class="col-md-6 mb-3">
                        <label class="form-label" for="price">قيمة الشحنة</label>
                        <input class="form-control @error('price') is-invalid @enderror" name="price" id="price"
                            type="number" step="0.01" placeholder="أدخل قيمة الشحنة"
                            value="{{ old('price', $shipment->price ?? '') }}">
                        @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                @if ($shipment->type == 1)
                    <!-- Shipment Contents Table -->
                    <hr>
                    <p class="bg-success p-3">محتويات الشحنة</p>
                    <div class="row">
                        <div class="col-md-12 mb-3">
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
                                    @forelse ($shipment->contents as $index => $content)
                                        <tr>
                                            <td><input type="text" name="contents[{{ $index }}][name]"
                                                    class="form-control"
                                                    value="{{ old('contents.' . $index . '.name', $content->name ?? '') }}"
                                                    placeholder="أدخل اسم المحتوى"></td>
                                            <td><input type="number" name="contents[{{ $index }}][quantity]"
                                                    class="form-control"
                                                    value="{{ old('contents.' . $index . '.quantity', $content->quantity ?? '') }}"
                                                    placeholder="أدخل الكمية" min="1"></td>
                                            <td><input type="number" name="contents[{{ $index }}][price]"
                                                    class="form-control"
                                                    value="{{ old('contents.' . $index . '.price', $content->price ?? '') }}"
                                                    placeholder="أدخل الكمية" min="1"></td>
                                            <td><button type="button" class="remove-row-btn"
                                                    onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td><input type="text" name="contents[0][name]" class="form-control"
                                                    value="{{ old('contents.0.name') }}" placeholder="أدخل اسم المحتوى">
                                            </td>
                                            <td><input type="number" name="contents[0][quantity]" class="form-control"
                                                    value="{{ old('contents.0.quantity') }}" placeholder="أدخل الكمية"
                                                    min="1"></td>
                                            <td><input type="number" name="contents[0][price]" class="form-control"
                                                    value="{{ old('contents.0.price') }}" placeholder="أدخل السعر"
                                                    min="1"></td>
                                            <td><button type="button" class="remove-row-btn"
                                                    onclick="removeRow(this)"><i class="fas fa-times"></i></button></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-outline-primary mb-3" onclick="addRow()">
                                <i class="fas fa-plus"></i> إضافة محتوى
                            </button>
                            @error('contents.*.name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            @error('contents.*.quantity')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif


                <!-- Shipment Image -->
                <hr>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        @if ($shipment->image && \Storage::disk('public')->exists($shipment->image))
                            <img src="{{ asset('public/storage/' . $shipment->image) }}" alt="صورة الشحنة"
                                class="image-preview">
                        @else
                            <p>لا يوجد صورة متاحة</p>
                        @endif
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label" for="image">صورة الشحنة <span
                                class="text-primary">(اختياري)</span></label>
                        <input class="form-control @error('image') is-invalid @enderror" name="image" id="image"
                            type="file" accept="image/jpeg,image/png,image/jpg">
                        @error('image')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Shipment Type -->
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
                <hr>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="service_id" class="form-label">نوع الشحنة</label>
                        <select class="form-control" name="service_id" id="service_id">
                            <option value="">اختر نوع الشحنة</option>
                            @foreach ($services as $service)
                                <option value="{{ $service->id }}"
                                    {{ ($shipment->service?->id ?? '') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <!-- Form Actions -->
                <div class="card-footer text-end">
                    <button class="btn btn-primary" type="submit">تحديث</button>
                    @if ($shipment->type == 2)
                        <a class="btn btn-light" href="{{ route('admin.shipment.index') }}">إلغاء</a>
                    @else
                        <a class="btn btn-light" href="{{ route('admin.shipment.client') }}">إلغاء</a>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addRow() {
            const table = document.getElementById('contentTable').getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length;
            const row = table.insertRow();
            row.innerHTML = `
            <td><input type="text" name="contents[${rowCount}][name]" class="form-control" placeholder="أدخل اسم المحتوى"></td>
            <td><input type="number" name="contents[${rowCount}][quantity]" class="form-control" placeholder="أدخل الكمية" min="1"></td>
            <td><input type="number" name="contents[${rowCount}][price]" class="form-control" placeholder="أدخل السعر" min="1"></td>

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
                inputs[1].name = `contents[${i}][price]`;

            }
        }

        // Calculate remaining amount dynamically
        function calculateRemaining() {
            const expenseCodeInput = document.getElementById('expense_code');
            const priceInput = document.getElementById('price');
            const shippingCostInput = document.getElementById('shipping_cost');
            const packagingCostInput = document.getElementById('packaging_cost');
            const customsCostInput = document.getElementById('customs_cost');
            const refundCodeInput = document.getElementById('refund_code');
            const remainingInput = document.getElementById('remaining');
            const expenseCode = parseFloat(expenseCodeInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const shippingCost = parseFloat(shippingCostInput.value) || 0;
            const packagingCost = parseFloat(packagingCostInput.value) || 0;
            const customsCost = parseFloat(customsCostInput.value) || 0;
            const refundCode = parseFloat(refundCodeInput.value) || 0;
            remainingInput.value = (expenseCode + price + shippingCost + packagingCost + customsCost - refundCode).toFixed(
                2);
        }

        // Calculate CBM dynamically
        function calculateCBM() {
            const lengthInput = document.getElementById('length');
            const widthInput = document.getElementById('width');
            const heightInput = document.getElementById('height');
            const cbmInput = document.getElementById('cbm');
            const length = parseFloat(lengthInput.value) || 0;
            const width = parseFloat(widthInput.value) || 0;
            const height = parseFloat(heightInput.value) || 0;
            cbmInput.value = ((length * width * height) / 1000000).toFixed(4); // Convert cm³ to m³
        }

        // Update remaining on input changes
        document.getElementById('expense_code').addEventListener('input', calculateRemaining);
        document.getElementById('price').addEventListener('input', calculateRemaining);
        document.getElementById('shipping_cost').addEventListener('input', calculateRemaining);
        document.getElementById('packaging_cost').addEventListener('input', calculateRemaining);
        document.getElementById('customs_cost').addEventListener('input', calculateRemaining);
        document.getElementById('refund_code').addEventListener('input', calculateRemaining);

        // Update CBM on length, width, or height change
        document.getElementById('length').addEventListener('input', calculateCBM);
        document.getElementById('width').addEventListener('input', calculateCBM);
        document.getElementById('height').addEventListener('input', calculateCBM);

        // Initialize calculations
        document.addEventListener('DOMContentLoaded', function() {
            calculateRemaining();
            calculateCBM();
        });
    </script>
@endsection
