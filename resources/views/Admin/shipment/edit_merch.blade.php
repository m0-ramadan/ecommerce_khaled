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

        .quantity-error {
            color: #dc3545;
            font-size: 12px;
            font-weight: bold;
        }

        .in-stock-quantity {
            font-weight: bold;
            color: #28a745;
        }

        .out-of-stock {
            color: #dc3545 !important;
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
                <p class="bg-success p-3">معلومات الشحنة</p>
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
                            name="branches_from" id="branches_from">
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
                    {{-- <div class="col-md-4 mb-3 d-none">
                        <label class="form-label" for="expense_code">المصروف</label>
                        <input class="form-control @error('expense_code') is-invalid @enderror" name="expense_code"
                            id="expense_code" type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('expense_code', $shipment->expense_code ?? '') }}">
                        @error('expense_code')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    {{-- <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="price">قيمة الشحنة</label>
                            <input class="form-control @error('price') is-invalid @enderror" name="price"
                                id="price" type="number" step="0.01" placeholder="أدخل قيمة الشحنة"
                                value="{{ old('price', $shipment->price ?? '') }}">
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
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
                    <div class="col-md-4 mb-3 ">
                        <label class="form-label" for="shipping_cost">سعر التوصيل </label>
                        <input class="form-control @error('shipping_cost') is-invalid @enderror" name="shipping_cost"
                            id="shipping_cost" type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('shipping_cost', $shipment->shipping_cost ?? '') }}">
                        @error('shipping_cost')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- <div class="col-md-4 mb-3">
                        <label class="form-label" for="price">قيمة الشحنه</label>
                        <input class="form-control @error('price') is-invalid @enderror" name="price" id="price"
                            type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('price', $shipment->price ?? '') }}">
                        @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div> --}}
                    <div class="col-md-4 mb-3">
                        <label class="form-label" for="price">قيمة الراجع </label>
                        <input class="form-control @error('price') is-invalid @enderror" name="price" id="price"
                            type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('price', $shipment->price ?? '') }}">
                        @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3 d-none">
                        <label class="form-label" for="customs_cost">قيمة الجمرك</label>
                        <input class="form-control @error('customs_cost') is-invalid @enderror" name="customs_cost"
                            id="customs_cost" type="number" step="0.01" placeholder="أدخل القيمة"
                            value="{{ old('customs_cost', $shipment->customs_cost ?? '') }}">
                        @error('customs_cost')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>


                <!-- Shipment Products Table -->
                <hr>
                <p class="bg-success p-3">منتجات الشحنة</p>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <table class="content-table" id="productTable">
                            <thead>
                                <tr>
                                    <th>اسم المنتج</th>
                                    <th>الكمية المطلوبة</th>
                                    <th>الكمية المتاحة</th>
                                    <th>الإجراء</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($shipment->products_in as $index => $product_in)
                                    <tr>
                                        <td>
                                            <select name="products[{{ $index }}][product_id]"
                                                class="form-control select2 product-select"
                                                data-row-index="{{ $index }}">
                                                <option value="">اختر المنتج</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-in-stock="{{ $product->in_stock_quantity ?? 0 }}"
                                                        {{ old('products.' . $index . '.product_id', $product_in->product_id) == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="products[{{ $index }}][quantity]"
                                                class="form-control quantity-input" data-row-index="{{ $index }}"
                                                value="{{ old('products.' . $index . '.quantity', $product_in->quantity ?? 1) }}"
                                                placeholder="أدخل الكمية" min="1">
                                            <div class="quantity-error" id="quantity-error-{{ $index }}"
                                                style="display: none;"></div>
                                        </td>
                                        <td class="in-stock-quantity" id="stock-{{ $index }}">
                                            {{ $product_in->product ? $product_in->product->in_stock_quantity ?? 0 : 0 }}
                                        </td>
                                        <td>
                                            <button type="button" class="remove-row-btn" onclick="removeRow(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td>
                                            <select name="products[0][product_id]"
                                                class="form-control select2 product-select" data-row-index="0">
                                                <option value="">اختر المنتج</option>
                                                @foreach ($products as $product)
                                                    <option value="{{ $product->id }}"
                                                        data-in-stock="{{ $product->in_stock_quantity ?? 0 }}"
                                                        {{ old('products.0.product_id') == $product->id ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" name="products[0][quantity]"
                                                class="form-control quantity-input" data-row-index="0"
                                                value="{{ old('products.0.quantity', 1) }}" placeholder="أدخل الكمية"
                                                min="1">
                                            <div class="quantity-error" id="quantity-error-0" style="display: none;">
                                            </div>
                                        </td>
                                        <td class="in-stock-quantity" id="stock-0">0</td>
                                        <td>
                                            <button type="button" class="remove-row-btn" onclick="removeRow(this)">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-outline-primary mb-3" onclick="addProductRow()">
                            <i class="fas fa-plus"></i> إضافة منتج
                        </button>
                        @error('products.*.product_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        @error('products.*.quantity')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

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
                    <button class="btn btn-primary" type="submit" id="submitBtn">تحديث</button>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        let productData = {};

        // Initialize product data from PHP
        @foreach ($products as $product)
            productData[{{ $product->id }}] = {
                name: '{{ $product->name }}',
                in_stock_quantity: {{ $product->in_stock_quantity ?? 0 }}
            };
        @endforeach

        // Initialize Select2 for existing product selects
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "اختر المنتج",
                allowClear: true
            });

            // Initialize existing rows
            initializeExistingRows();

            // Attach events to existing elements
            attachProductEvents();
            attachQuantityEvents();
        });

        function initializeExistingRows() {
            $('.product-select').each(function() {
                const rowIndex = $(this).data('row-index');
                const selectedValue = $(this).val();

                if (selectedValue && productData[selectedValue]) {
                    const stockQuantity = productData[selectedValue].in_stock_quantity;
                    updateStockDisplay(rowIndex, stockQuantity);

                    // Validate existing quantity
                    const quantityInput = $(`input[name="products[${rowIndex}][quantity]"]`);
                    if (quantityInput.length) {
                        validateQuantity(rowIndex, parseInt(quantityInput.val()) || 0, stockQuantity);
                    }
                }
            });
        }

        function attachProductEvents() {
            $(document).on('select2:select', '.product-select', function(e) {
                const rowIndex = $(this).data('row-index');
                const selectedValue = e.params.data.id;

                if (selectedValue && productData[selectedValue]) {
                    const stockQuantity = productData[selectedValue].in_stock_quantity;
                    updateStockDisplay(rowIndex, stockQuantity);

                    // Reset quantity input and validate
                    const quantityInput = $(`input[name="products[${rowIndex}][quantity]"]`);
                    const currentQuantity = parseInt(quantityInput.val()) || 0;
                    validateQuantity(rowIndex, currentQuantity, stockQuantity);
                } else {
                    updateStockDisplay(rowIndex, 0);
                    clearQuantityError(rowIndex);
                }
            });

            $(document).on('select2:clear', '.product-select', function(e) {
                const rowIndex = $(this).data('row-index');
                updateStockDisplay(rowIndex, 0);
                clearQuantityError(rowIndex);
            });
        }

        function attachQuantityEvents() {
            $(document).on('input change', '.quantity-input', function() {
                const rowIndex = $(this).data('row-index');
                const quantity = parseInt($(this).val()) || 0;
                const productSelect = $(`.product-select[data-row-index="${rowIndex}"]`);
                const selectedValue = productSelect.val();

                if (selectedValue && productData[selectedValue]) {
                    const stockQuantity = productData[selectedValue].in_stock_quantity;
                    validateQuantity(rowIndex, quantity, stockQuantity);
                } else {
                    clearQuantityError(rowIndex);
                }
            });
        }

        function updateStockDisplay(rowIndex, stockQuantity) {
            const stockCell = $(`#stock-${rowIndex}`);
            stockCell.text(stockQuantity);

            if (stockQuantity <= 0) {
                stockCell.addClass('out-of-stock');
            } else {
                stockCell.removeClass('out-of-stock');
            }
        }

        function validateQuantity(rowIndex, requestedQuantity, availableStock) {
            const errorDiv = $(`#quantity-error-${rowIndex}`);
            const quantityInput = $(`input[name="products[${rowIndex}][quantity]"]`);

            if (requestedQuantity > availableStock) {
                const errorMessage = availableStock > 0 ?
                    `الكمية المطلوبة (${requestedQuantity}) أكبر من المتاحة (${availableStock})` :
                    'هذا المنتج غير متاح في المخزون';

                errorDiv.text(errorMessage).show();
                quantityInput.addClass('is-invalid');

                // Disable submit button
                $('#submitBtn').prop('disabled', true);
                return false;
            } else {
                clearQuantityError(rowIndex);

                // Check if all quantities are valid before enabling submit
                if (!hasQuantityErrors()) {
                    $('#submitBtn').prop('disabled', false);
                }
                return true;
            }
        }

        function clearQuantityError(rowIndex) {
            const errorDiv = $(`#quantity-error-${rowIndex}`);
            const quantityInput = $(`input[name="products[${rowIndex}][quantity]"]`);

            errorDiv.hide();
            quantityInput.removeClass('is-invalid');
        }

        function hasQuantityErrors() {
            return $('.quantity-error:visible').length > 0;
        }

        function addProductRow() {
            const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length;
            const row = table.insertRow();

            row.innerHTML = `
                <td>
                    <select name="products[${rowCount}][product_id]" class="form-control select2 product-select" data-row-index="${rowCount}">
                        <option value="">اختر المنتج</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-in-stock="{{ $product->in_stock_quantity ?? 0 }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="products[${rowCount}][quantity]" class="form-control quantity-input" 
                           data-row-index="${rowCount}" placeholder="أدخل الكمية" min="1" value="1">
                    <div class="quantity-error" id="quantity-error-${rowCount}" style="display: none;"></div>
                </td>
                <td class="in-stock-quantity" id="stock-${rowCount}">0</td>
                <td>
                    <button type="button" class="remove-row-btn" onclick="removeRow(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            `;

            // Initialize Select2 for the new select element
            $(row).find('.select2').select2({
                placeholder: "اختر المنتج",
                allowClear: true
            });
        }

        function removeRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
            updateIndices();

            // Re-check if submit should be enabled after removing row
            if (!hasQuantityErrors()) {
                $('#submitBtn').prop('disabled', false);
            }
        }

        function updateIndices() {
            const table = document.getElementById('productTable').getElementsByTagName('tbody')[0];
            const rows = table.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const select = $(rows[i]).find('.product-select');
                const input = $(rows[i]).find('.quantity-input');
                const stockCell = $(rows[i]).find('.in-stock-quantity');
                const errorDiv = $(rows[i]).find('.quantity-error');

                // Update names
                select.attr('name', `products[${i}][product_id]`);
                input.attr('name', `products[${i}][quantity]`);

                // Update data attributes and IDs
                select.attr('data-row-index', i);
                input.attr('data-row-index', i);
                stockCell.attr('id', `stock-${i}`);
                errorDiv.attr('id', `quantity-error-${i}`);
            }
        }

        // Calculate remaining amount dynamically
        function calculateRemaining() {
            const expenseCodeInput = document.getElementById('expense_code');
            const priceInput = document.getElementById('price');
            const shippingCostInput = document.getElementById('shipping_cost');
            const packagingCostInput = document.getElementById('packaging_cost');
            const customsCostInput = document.getElementById('customs_cost');
            const remainingInput = document.getElementById('remaining');

            const expenseCode = parseFloat(expenseCodeInput.value) || 0;
            const price = parseFloat(priceInput.value) || 0;
            const shippingCost = parseFloat(shippingCostInput.value) || 0;
            const packagingCost = parseFloat(packagingCostInput.value) || 0;
            const customsCost = parseFloat(customsCostInput.value) || 0;

            remainingInput.value = (expenseCode + price + shippingCost + packagingCost + customsCost).toFixed(2);
        }

        // Update remaining on input changes
        document.getElementById('expense_code').addEventListener('input', calculateRemaining);
        document.getElementById('price').addEventListener('input', calculateRemaining);
        document.getElementById('shipping_cost').addEventListener('input', calculateRemaining);
        document.getElementById('packaging_cost').addEventListener('input', calculateRemaining);
        document.getElementById('customs_cost').addEventListener('input', calculateRemaining);

        // Prevent form submission if there are quantity errors
        $('form').on('submit', function(e) {
            if (hasQuantityErrors()) {
                e.preventDefault();
                alert('يرجى تصحيح أخطاء الكمية قبل الحفظ');
                return false;
            }
        });

        // Initialize calculations on page load
        document.addEventListener('DOMContentLoaded', function() {
            calculateRemaining();
        });
    </script>
@endsection
