@extends('Admin.layout.master')

@section('title', 'تفاصيل الطلب: ' . $order->order_number)

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
            --text-muted: #6c757d;
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .order-detail-card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .order-detail-header {
            background: var(--primary-gradient);
            color: white;
            padding: 25px 30px;
            border-radius: 15px 15px 0 0;
            position: relative;
            margin: -30px -30px 30px -30px;
        }

        .badge-status {
            padding: 8px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 14px;
        }

        .status-pending {
            background: rgba(133, 100, 4, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .status-processing {
            background: rgba(0, 64, 133, 0.2);
            color: #0dcaf0;
            border: 1px solid rgba(13, 202, 240, 0.3);
        }

        .status-shipped {
            background: rgba(12, 84, 96, 0.2);
            color: #0dcaf0;
            border: 1px solid rgba(13, 202, 240, 0.3);
        }

        .status-delivered {
            background: linear-gradient(135deg, rgba(21, 87, 36, 0.2) 0%, rgba(32, 201, 151, 0.2) 100%);
            color: #20c997;
            border: 1px solid rgba(32, 201, 151, 0.3);
        }

        .status-cancelled {
            background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(253, 126, 20, 0.2) 100%);
            color: #fd7e14;
            border: 1px solid rgba(253, 126, 20, 0.3);
        }

        .status-refunded {
            background: rgba(56, 61, 65, 0.2);
            color: #adb5bd;
            border: 1px solid rgba(173, 181, 189, 0.3);
        }

        .info-section {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .info-section h6 {
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .info-row {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .info-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            min-width: 150px;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        .info-value {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
            flex-grow: 1;
            overflow-wrap: anywhere;
        }

        .details-list {
            margin: 10px 0 0;
            padding-right: 20px;
            color: rgba(255, 255, 255, 0.9);
        }

        .details-list li { margin-bottom: 6px; }

        .product-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .product-item:hover {
            background: rgba(105, 108, 255, 0.1);
            border-color: var(--primary-color);
        }

        .product-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .product-name {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
        }

        .product-price {
            font-weight: 700;
            color: #20c997;
        }

        .product-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 10px;
        }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.7);
            min-width: 80px;
        }

        .detail-value {
            color: rgba(255, 255, 255, 0.9);
        }

        .summary-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            padding-bottom: 10px;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.1);
        }

        .summary-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .summary-label {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8);
        }

        .summary-value {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
        }

        .total-row {
            font-size: 18px;
            color: #20c997;
            font-weight: 700;
        }

        .timeline {
            position: relative;
            padding-right: 30px;
        }

        .timeline:before {
            content: '';
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-color);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 25px;
        }

        .timeline-item:before {
            content: '';
            position: absolute;
            right: -33px;
            top: 5px;
            width: 13px;
            height: 13px;
            border-radius: 50%;
            background: var(--dark-card);
            border: 3px solid var(--primary-color);
        }

        .timeline-content {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .timeline-date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 5px;
        }

        .timeline-text {
            color: rgba(255, 255, 255, 0.9);
            font-weight: 500;
        }

        .action-buttons {
            position: absolute;
            left: 30px;
            top: 30px;
            display: flex;
            gap: 10px;
        }

        .btn-action {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-action:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        }

        .status-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 15px;
        }

        .status-btn {
            padding: 8px 20px;
            border-radius: 25px;
            border: 2px solid transparent;
            background: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .status-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .status-btn.active {
            background: var(--primary-gradient);
            color: white;
            border-color: var(--primary-color);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4a9a 100%);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        @media (max-width: 768px) {
            .action-buttons {
                position: relative;
                left: 0;
                top: 0;
                margin-bottom: 20px;
                justify-content: center;
            }

            .info-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-label {
                min-width: auto;
                margin-bottom: 5px;
            }

            .product-details {
                grid-template-columns: 1fr;
            }

            .product-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .product-price {
                align-self: flex-start;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" bis_skin_checked="1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.orders.index') }}">الطلبات</a>
                </li>
                <li class="breadcrumb-item active">تفاصيل</li>
            </ol>
        </nav>

        <div class="row" bis_skin_checked="1">
            <div class="col-12" bis_skin_checked="1">
                <div class="order-detail-card" bis_skin_checked="1">
                    <div class="order-detail-header" bis_skin_checked="1">
                        <div class="action-buttons" bis_skin_checked="1">
                            <a href="{{ route('admin.orders.edit', $order) }}" class="btn-action" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('admin.orders.print', $order) }}" class="btn-action" title="طباعة"
                                target="_blank">
                                <i class="fas fa-print"></i>
                            </a>
                            <a href="{{ route('admin.orders.index') }}" class="btn-action" title="رجوع">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>

                        <div class="text-center" bis_skin_checked="1">
                            <h4 class="mb-2">الطلب #{{ $order->order_number }}</h4>
                            <div class="d-flex justify-content-center align-items-center gap-3 mb-3" bis_skin_checked="1">
                                <span class="badge-status status-{{ $order->status }}">
                                    {{ $order->status_label }}
                                </span>
                                <span class="text-white opacity-75">
                                    <i class="far fa-clock me-2"></i>
                                    {{ $order->created_at->translatedFormat('d M Y - h:i A') }}
                                </span>
                            </div>
                            <div class="text-white opacity-75" bis_skin_checked="1">
                                <i class="fas fa-money-bill-wave me-2"></i>
                                {{ number_format($order->total_amount, 2) }} ر.س
                            </div>
                        </div>
                    </div>

                    <div class="order-detail-body" bis_skin_checked="1">
                        <div class="row" bis_skin_checked="1">
                            <div class="col-lg-8" bis_skin_checked="1">
                                <!-- معلومات العميل -->
                                <div class="info-section" bis_skin_checked="1">
                                    <h6><i class="fas fa-user me-2"></i>معلومات العميل</h6>

                                    <div class="info-row" bis_skin_checked="1">
                                        <div class="info-label" bis_skin_checked="1">اسم العميل:</div>
                                        <div class="info-value" bis_skin_checked="1">
                                            {{ $order->customer_name }}
                                            @if ($order->user)
                                                <small class="text-muted ms-2">
                                                    ({{ $order->user->email }})
                                                </small>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="info-row" bis_skin_checked="1">
                                        <div class="info-label" bis_skin_checked="1">البريد الإلكتروني:</div>
                                        <div class="info-value" bis_skin_checked="1">
                                            <a href="mailto:{{ $order->customer_email }}" class="text-decoration-none">
                                                {{ $order->customer_email }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="info-row" bis_skin_checked="1">
                                        <div class="info-label" bis_skin_checked="1">رقم الهاتف:</div>
                                        <div class="info-value" bis_skin_checked="1">
                                            <a href="tel:{{ $order->customer_phone }}" class="text-decoration-none">
                                                {{ $order->customer_phone }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="info-row" bis_skin_checked="1">
                                        <div class="info-label" bis_skin_checked="1">عنوان الشحن:</div>
                                        <div class="info-value" bis_skin_checked="1">
                                            {{ $order->shipping_address ?: 'غير محدد' }}
                                        </div>
                                    </div>

                                    @if ($order->address)
                                        <div class="info-row">
                                            <div class="info-label">تفاصيل العنوان:</div>
                                            <div class="info-value">
                                                {{ trim($order->address->first_name . ' ' . $order->address->last_name) }}
                                                @if ($order->address->label) — {{ $order->address->label }} @endif
                                                <br>
                                                {{ collect([$order->address->city, $order->address->area, $order->address->address_details])->filter()->implode('، ') }}
                                                @if ($order->address->apartment_number)
                                                    <br>رقم الشقة: {{ $order->address->apartment_number }}
                                                @endif
                                                @if ($order->address->phone)
                                                    <br>هاتف العنوان: <a href="tel:{{ $order->address->phone }}">{{ $order->address->phone }}</a>
                                                @endif
                                                @if ($order->address->latitude && $order->address->longitude)
                                                    <br><a href="https://www.google.com/maps?q={{ $order->address->latitude }},{{ $order->address->longitude }}" target="_blank" rel="noopener">
                                                        <i class="fas fa-map-marker-alt me-1"></i>عرض الموقع على الخريطة
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    @if ($order->notes)
                                        <div class="info-row" bis_skin_checked="1">
                                            <div class="info-label" bis_skin_checked="1">ملاحظات:</div>
                                            <div class="info-value" bis_skin_checked="1">
                                                {{ $order->notes }}
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- المنتجات -->
                                <div class="info-section" bis_skin_checked="1">
                                    <h6><i class="fas fa-shopping-cart me-2"></i>المنتجات ({{ $order->items->count() }})
                                    </h6>

                                    @foreach ($order->items as $item)
                                        <div class="product-item" bis_skin_checked="1">
                                            <div class="product-header" bis_skin_checked="1">
                                                <div class="product-name" bis_skin_checked="1">
                                                    {{ $item->product->name ?? 'منتج محذوف' }}
                                                </div>
                                                <div class="product-price" bis_skin_checked="1">
                                                    {{ number_format($item->total_price, 2) }} ر.س
                                                </div>
                                            </div>

                                            <div class="product-details" bis_skin_checked="1">
                                                <div class="detail-item" bis_skin_checked="1">
                                                    <span class="detail-label">الكمية:</span>
                                                    <span class="detail-value">{{ $item->quantity }}</span>
                                                </div>

                                                <div class="detail-item" bis_skin_checked="1">
                                                    <span class="detail-label">السعر للوحدة:</span>
                                                    <span
                                                        class="detail-value">{{ number_format($item->price_per_unit, 2) }}
                                                        ر.س</span>
                                                </div>

                                                @if ($item->color)
                                                    <div class="detail-item" bis_skin_checked="1">
                                                        <span class="detail-label">اللون:</span>
                                                        <span class="detail-value">{{ $item->color->name }}</span>
                                                    </div>
                                                @endif

                                                @if ($item->size)
                                                    <div class="detail-item" bis_skin_checked="1">
                                                        <span class="detail-label">المقاس:</span>
                                                        <span class="detail-value">{{ $item->size->name }}</span>
                                                    </div>
                                                @endif

                                                @if ($item->printingMethod)
                                                    <div class="detail-item" bis_skin_checked="1">
                                                        <span class="detail-label">طريقة الطباعة:</span>
                                                        <span
                                                            class="detail-value">{{ $item->printingMethod->name }}</span>
                                                    </div>
                                                @endif

                                                @if ($item->designService)
                                                    <div class="detail-item">
                                                        <span class="detail-label">خدمة التصميم:</span>
                                                        <span class="detail-value">{{ $item->designService->name }}</span>
                                                    </div>
                                                @endif

                                                <div class="detail-item">
                                                    <span class="detail-label">نوع الطلب:</span>
                                                    <span class="detail-value">{{ $item->is_sample ? 'عينة' : 'طلب عادي' }}</span>
                                                </div>

                                                @if ($item->quantity_id)
                                                    <div class="detail-item">
                                                        <span class="detail-label">معرّف فئة الكمية:</span>
                                                        <span class="detail-value">{{ $item->quantity_id }}</span>
                                                    </div>
                                                @endif

                                                @if ($item->note)
                                                    <div class="detail-item" bis_skin_checked="1">
                                                        <span class="detail-label">ملاحظة:</span>
                                                        <span class="detail-value">{{ $item->note }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            @php
                                                $options = is_array($item->selected_options) ? $item->selected_options : json_decode($item->selected_options, true);
                                                $filesJson = null;
                                                if (is_array($options)) {
                                                    foreach ($options as $opt) {
                                                        if (isset($opt['option_name']) && $opt['option_name'] === 'تفاصيل ملفات الطباعة') {
                                                            $filesJson = json_decode($opt['option_value'] ?? '[]', true);
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if (!empty($item->print_locations_names))
                                                <div class="mt-3">
                                                    <strong><i class="fas fa-print me-1"></i>مواقع الطباعة:</strong>
                                                    {{ implode('، ', $item->print_locations_names) }}
                                                </div>
                                            @endif

                                            @if (!empty($item->embroider_locations_names))
                                                <div class="mt-2">
                                                    <strong><i class="fas fa-cut me-1"></i>مواقع التطريز:</strong>
                                                    {{ implode('، ', $item->embroider_locations_names) }}
                                                </div>
                                            @endif

                                            @if (!empty($options) && is_array($options))
                                                <div class="mt-3">
                                                    <strong><i class="fas fa-list-check me-1"></i>الخيارات المختارة:</strong>
                                                    <ul class="details-list">
                                                        @foreach ($options as $key => $option)
                                                            @php
                                                                $optionName = is_array($option) ? ($option['option_name'] ?? $option['name'] ?? (is_string($key) ? $key : 'خيار')) : (is_string($key) ? $key : 'خيار');
                                                                $optionValue = is_array($option) ? ($option['option_value'] ?? $option['value'] ?? null) : $option;
                                                                $isFilesOption = $optionName === 'تفاصيل ملفات الطباعة';
                                                                if (is_array($optionValue)) {
                                                                    $optionValue = collect($optionValue)->flatten()->filter(fn ($value) => is_scalar($value))->implode('، ');
                                                                }
                                                            @endphp
                                                            @if (!$isFilesOption && filled($optionValue))
                                                                <li><strong>{{ $optionName }}:</strong> {{ $optionValue }}</li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            @if (!empty($filesJson) && is_array($filesJson))
                                                <div class="mt-3 p-3 bg-light rounded border">
                                                    <div class="fw-bold mb-2 text-primary d-flex align-items-center gap-1">
                                                        <i class="fas fa-print me-1"></i>ملفات الطباعة والقرطاسية المرفقة ({{ count($filesJson) }} ملفات):
                                                    </div>
                                                    <div class="d-flex flex-column gap-2">
                                                        @foreach ($filesJson as $file)
                                                            <div class="d-flex justify-content-between align-items-center p-2 bg-white rounded border flex-wrap gap-2">
                                                                <div>
                                                                    <i class="fas fa-file-alt text-primary me-1"></i>
                                                                    <strong>{{ $file['file_name'] ?? 'ملف' }}</strong>
                                                                    <span class="badge bg-secondary ms-1">{{ $file['paper_size'] ?? 'A4' }}</span>
                                                                    <span class="badge {{ ($file['color_type'] ?? '') === 'color' ? 'bg-primary' : 'bg-dark' }}">
                                                                        {{ ($file['color_type'] ?? '') === 'color' ? 'ألوان' : 'أبيض وأسود' }}
                                                                    </span>
                                                                    <span class="badge bg-info ms-1">{{ $file['orientation_name'] ?? 'طولي' }}</span>
                                                                    <span class="badge bg-light text-dark border ms-1">{{ $file['side_type_name'] ?? 'وجه واحد' }}</span>
                                                                    <span class="text-muted ms-2 small">({{ $file['pages_count'] ?? 1 }} ص × {{ $file['copies'] ?? 1 }} نسخ)</span>
                                                                </div>
                                                                @if (!empty($file['file_url']))
                                                                    <a href="{{ $file['file_url'] }}" target="_blank" download class="btn btn-sm btn-primary d-flex align-items-center gap-1">
                                                                        <i class="fas fa-download"></i>تحميل للطباعة
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif

                                            @if ($item->image_design)
                                                <div class="mt-2">
                                                    <a href="{{ asset('storage/' . $item->image_design) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-download me-1"></i>تحميل التصميم المرفق
                                                    </a>
                                                </div>
                                            @endif

                                            @if ($item->product)
                                                <div class="mt-2" bis_skin_checked="1">
                                                    <a href="{{ route('admin.products.show', $item->product->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye me-1"></i>عرض المنتج
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-lg-4" bis_skin_checked="1">
                                <!-- ملخص الطلب -->
                                <div class="summary-card" bis_skin_checked="1">
                                    <h6 class="mb-3">ملخص الطلب</h6>

                                    <div class="summary-row" bis_skin_checked="1">
                                        <span class="summary-label">المجموع الجزئي:</span>
                                        <span class="summary-value">
                                            {{ number_format($order->subtotal, 2) }} ر.س
                                        </span>
                                    </div>

                                    <div class="summary-row" bis_skin_checked="1">
                                        <span class="summary-label">الشحن:</span>
                                        <span class="summary-value">
                                            {{ number_format($order->shipping_amount, 2) }} ر.س
                                        </span>
                                    </div>

                                    @if ($order->discount_amount > 0)
                                        <div class="summary-row" bis_skin_checked="1">
                                            <span class="summary-label">الخصم:</span>
                                            <span class="summary-value text-danger">
                                                -{{ number_format($order->discount_amount, 2) }} ر.س
                                            </span>
                                        </div>
                                    @endif

                                    @if ($order->tax_amount > 0)
                                        <div class="summary-row" bis_skin_checked="1">
                                            <span class="summary-label">الضريبة:</span>
                                            <span class="summary-value">
                                                {{ number_format($order->tax_amount, 2) }} ر.س
                                            </span>
                                        </div>
                                    @endif

                                    <div class="summary-row total-row" bis_skin_checked="1">
                                        <span class="summary-label">الإجمالي:</span>
                                        <span class="summary-value">
                                            {{ number_format($order->total_amount, 2) }} ر.س
                                        </span>
                                    </div>

                                    <div class="summary-row" bis_skin_checked="1">
                                        <span class="summary-label">طريقة الدفع:</span>
                                        <span class="summary-value">{{ $order->payment_method_label ?: ($order->payment_method ?: 'غير محددة') }}</span>
                                    </div>

                                    <div class="summary-row">
                                        <span class="summary-label">حالة الدفع:</span>
                                        <span class="summary-value">
                                            {{ match ($order->status_payment) {
                                                'paid' => 'مدفوع',
                                                'failed' => 'فشل الدفع',
                                                'refunded' => 'تم الاسترداد',
                                                default => 'قيد الانتظار',
                                            } }}
                                        </span>
                                    </div>

                                    @if ($order->transaction_id)
                                        <div class="summary-row" bis_skin_checked="1">
                                            <span class="summary-label">رقم المعاملة:</span>
                                            <span class="summary-value">{{ $order->transaction_id }}</span>
                                        </div>
                                    @endif

                                    @if ($order->coupon)
                                        <div class="summary-row">
                                            <span class="summary-label">كوبون الخصم:</span>
                                            <span class="summary-value">{{ $order->coupon->code }} ({{ $order->coupon->name }})</span>
                                        </div>
                                    @endif

                                    @if ($order->shipping_method_name)
                                        <div class="summary-row">
                                            <span class="summary-label">طريقة الشحن:</span>
                                            <span class="summary-value">{{ $order->shipping_method_name }}</span>
                                        </div>
                                    @endif

                                    @if ($order->oto_order_id)
                                        <div class="summary-row">
                                            <span class="summary-label">رقم طلب OTO:</span>
                                            <span class="summary-value">{{ $order->oto_order_id }}</span>
                                        </div>
                                    @endif

                                    <div class="summary-row">
                                        <span class="summary-label">آخر تحديث:</span>
                                        <span class="summary-value">{{ $order->updated_at->translatedFormat('d M Y - h:i A') }}</span>
                                    </div>
                                </div>

                                @if ($order->shipments->isNotEmpty())
                                    <div class="info-section mt-4">
                                        <h6><i class="fas fa-truck me-2"></i>بيانات الشحن ({{ $order->shipments->count() }})</h6>
                                        @foreach ($order->shipments as $shipment)
                                            <div class="mb-3 pb-3 border-bottom">
                                                <div class="info-row"><div class="info-label">الحالة:</div><div class="info-value">{{ $shipment->status_label }}</div></div>
                                                @if ($shipment->tracking_number)
                                                    <div class="info-row"><div class="info-label">رقم التتبع:</div><div class="info-value">{{ $shipment->tracking_number }}</div></div>
                                                @endif
                                                @if ($shipment->external_shipment_id)
                                                    <div class="info-row"><div class="info-label">رقم الشحنة الخارجي:</div><div class="info-value">{{ $shipment->external_shipment_id }}</div></div>
                                                @endif
                                                @if ($shipment->carrier || $shipment->service_type)
                                                    <div class="info-row"><div class="info-label">شركة/خدمة الشحن:</div><div class="info-value">{{ collect([$shipment->carrier, $shipment->service_type, $shipment->delivery_option_name])->filter()->implode(' — ') }}</div></div>
                                                @endif
                                                @if ($shipment->estimated_delivery_date)
                                                    <div class="info-row"><div class="info-label">التسليم المتوقع:</div><div class="info-value">{{ $shipment->estimated_delivery_date->translatedFormat('d M Y - h:i A') }}</div></div>
                                                @endif
                                                @if ($shipment->pickup_date || $shipment->actual_delivery_date)
                                                    <div class="info-row"><div class="info-label">مواعيد الشحنة:</div><div class="info-value">
                                                        @if ($shipment->pickup_date) الاستلام: {{ $shipment->pickup_date->translatedFormat('d M Y - h:i A') }} @endif
                                                        @if ($shipment->actual_delivery_date) — التسليم الفعلي: {{ $shipment->actual_delivery_date->translatedFormat('d M Y - h:i A') }} @endif
                                                    </div></div>
                                                @endif
                                                @if ($shipment->weight || $shipment->length || $shipment->width || $shipment->height)
                                                    <div class="info-row"><div class="info-label">الشحنة:</div><div class="info-value">
                                                        @if ($shipment->weight) الوزن: {{ $shipment->weight }} كجم @endif
                                                        @if ($shipment->length || $shipment->width || $shipment->height) — الأبعاد: {{ $shipment->package_dimensions }} @endif
                                                        — عدد الطرود: {{ $shipment->package_count }}
                                                    </div></div>
                                                @endif
                                                @if ($shipment->shipping_cost > 0 || $shipment->insurance_amount > 0 || $shipment->customs_value)
                                                    <div class="info-row"><div class="info-label">تكلفة الشحنة:</div><div class="info-value">
                                                        الشحن: {{ number_format($shipment->shipping_cost, 2) }} ر.س
                                                        @if ($shipment->insurance_amount > 0) — التأمين: {{ number_format($shipment->insurance_amount, 2) }} ر.س @endif
                                                        @if ($shipment->customs_value) — القيمة الجمركية: {{ number_format($shipment->customs_value, 2) }} {{ $shipment->customs_currency }} @endif
                                                    </div></div>
                                                @endif
                                                @if ($shipment->tracking_url)
                                                    <a class="btn btn-sm btn-outline-primary" href="{{ $shipment->tracking_url }}" target="_blank" rel="noopener"><i class="fas fa-location-arrow me-1"></i>تتبع الشحنة</a>
                                                @endif
                                                @if ($shipment->shipping_label_url)
                                                    <a class="btn btn-sm btn-outline-primary" href="{{ $shipment->shipping_label_url }}" target="_blank" rel="noopener"><i class="fas fa-file-pdf me-1"></i>بوليصة الشحن</a>
                                                @endif
                                                @if ($shipment->notes || $shipment->failure_reason || $shipment->return_reason)
                                                    <div class="mt-2 text-white-50">{{ collect([$shipment->notes, $shipment->failure_reason, $shipment->return_reason])->filter()->implode(' — ') }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- تغيير حالة الطلب -->
                                <div class="info-section mt-4" bis_skin_checked="1">
                                    <h6><i class="fas fa-exchange-alt me-2"></i>تغيير الحالة</h6>

                                    <div class="status-buttons" id="statusButtons">
                                        <button type="button"
                                            class="status-btn {{ $order->status == 'pending' ? 'active' : '' }}"
                                            onclick="updateStatus('pending')">
                                            قيد الانتظار
                                        </button>
                                        <button type="button"
                                            class="status-btn {{ $order->status == 'processing' ? 'active' : '' }}"
                                            onclick="updateStatus('processing')">
                                            تحت المعالجة
                                        </button>
                                        <button type="button"
                                            class="status-btn {{ $order->status == 'shipped' ? 'active' : '' }}"
                                            onclick="updateStatus('shipped')">
                                            تم الشحن
                                        </button>
                                        <button type="button"
                                            class="status-btn {{ $order->status == 'delivered' ? 'active' : '' }}"
                                            onclick="updateStatus('delivered')">
                                            تم التسليم
                                        </button>
                                        <button type="button"
                                            class="status-btn {{ $order->status == 'cancelled' ? 'active' : '' }}"
                                            onclick="updateStatus('cancelled')">
                                            إلغاء
                                        </button>
                                    </div>

                                    <div class="mt-3" bis_skin_checked="1">
                                        <textarea class="form-control" id="statusNotes" placeholder="ملاحظات إضافية (اختياري)" rows="3"></textarea>
                                    </div>

                                    <button type="button" class="btn btn-primary w-100 mt-3"
                                        onclick="confirmStatusUpdate()">
                                        <i class="fas fa-save me-2"></i>تحديث الحالة
                                    </button>
                                </div>

                                <!-- الجدول الزمني -->
                                <div class="info-section mt-4" bis_skin_checked="1">
                                    <h6><i class="fas fa-history me-2"></i>سجل الطلب</h6>

                                    <div class="timeline" bis_skin_checked="1">
                                        @if ($order->delivered_at)
                                            <div class="timeline-item" bis_skin_checked="1">
                                                <div class="timeline-content" bis_skin_checked="1">
                                                    <div class="timeline-date" bis_skin_checked="1">
                                                        {{ $order->delivered_at->translatedFormat('d M Y - h:i A') }}
                                                    </div>
                                                    <div class="timeline-text" bis_skin_checked="1">
                                                        تم تسليم الطلب
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($order->shipped_at)
                                            <div class="timeline-item" bis_skin_checked="1">
                                                <div class="timeline-content" bis_skin_checked="1">
                                                    <div class="timeline-date" bis_skin_checked="1">
                                                        {{ $order->shipped_at->translatedFormat('d M Y - h:i A') }}
                                                    </div>
                                                    <div class="timeline-text" bis_skin_checked="1">
                                                        تم شحن الطلب
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <div class="timeline-item" bis_skin_checked="1">
                                            <div class="timeline-content" bis_skin_checked="1">
                                                <div class="timeline-date" bis_skin_checked="1">
                                                    {{ $order->created_at->translatedFormat('d M Y - h:i A') }}
                                                </div>
                                                <div class="timeline-text" bis_skin_checked="1">
                                                    إنشاء الطلب
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- إجراءات سريعة -->
                                <div class="info-section mt-4" bis_skin_checked="1">
                                    <h6><i class="fas fa-bolt me-2"></i>إجراءات سريعة</h6>

                                    <div class="d-grid gap-2" bis_skin_checked="1">
                                        <a href="{{ route('admin.orders.edit', $order) }}" class="btn btn-warning">
                                            <i class="fas fa-edit me-2"></i>تعديل الطلب
                                        </a>

                                        <a href="{{ route('admin.orders.print', $order) }}" class="btn btn-secondary"
                                            target="_blank">
                                            <i class="fas fa-print me-2"></i>طباعة الفاتورة
                                        </a>

                                        <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                            id="deleteForm" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger w-100"
                                                onclick="confirmDelete()">
                                                <i class="fas fa-trash me-2"></i>حذف الطلب
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let selectedStatus = '{{ $order->status }}';

        function updateStatus(status) {
            selectedStatus = status;

            // تحديث أزرار الحالة
            $('#statusButtons .status-btn').removeClass('active');
            $(`#statusButtons .status-btn[onclick="updateStatus('${status}')"]`).addClass('active');
        }

        function confirmStatusUpdate() {
            if (selectedStatus === '{{ $order->status }}') {
                Swal.fire({
                    icon: 'info',
                    title: 'لم يتغير شيء',
                    text: 'الحالة الحالية هي نفس الحالة المحددة',
                    timer: 1500,
                    showConfirmButton: false
                });
                return;
            }

            const notes = $('#statusNotes').val();

            Swal.fire({
                title: 'تأكيد تحديث الحالة',
                text: 'هل أنت متأكد من تغيير حالة الطلب؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، تحديث',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.orders.update-status', $order) }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            status: selectedStatus,
                            notes: notes
                        },
                        success: function(response) {
                            if (response.success) {
                                // تحديث عرض الحالة
                                const statusBadge = $('.badge-status');
                                statusBadge.removeClass().addClass('badge-status status-' + response
                                    .status).text(response.status_label);

                                // تحديث أزرار الحالة
                                $('#statusButtons .status-btn').removeClass('active');
                                $(`#statusButtons .status-btn[onclick="updateStatus('${response.status}')"]`)
                                    .addClass('active');

                                // مسح الملاحظات
                                $('#statusNotes').val('');

                                Swal.fire({
                                    icon: 'success',
                                    title: 'تم التحديث',
                                    text: response.message,
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'حدث خطأ أثناء تحديث الحالة',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
            });
        }

        function confirmDelete() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: 'سيتم حذف الطلب "{{ $order->order_number }}" نهائياً',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm').submit();
                }
            });
        }

        // رسائل التنبيه من الجلسة
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        @endif
    </script>
@endsection
