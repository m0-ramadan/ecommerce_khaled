@extends('Admin.layout.master')

@section('title')
    تفاصيل الشحنة
@endsection

@section('css')
    <!-- Plugins css start-->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Plugins css Ends-->
    <style>
        .table th,
        .table td {
            vertical-align: middle;
            font-family: 'Cairo', sans-serif;
        }

        h6 {
            font-family: 'Cairo', sans-serif;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h5>تفاصيل الشحنة #{{ $shipment->code ?? '--' }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Shipment Details -->
                        <div class="row">
                            <!-- General Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">معلومات عامة</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>الكود</th>
                                        <td>{{ $shipment->code ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>رقم الشحنة</th>
                                        <td>{{ $shipment->shipment_no ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الحالة</th>
                                        <td>
                                            <span
                                                class="badge {{ $shipment->status_badge_class }}">{{ $shipment->status_label }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>نوع الشحنة</th>
                                        <td>{{ $shipment->type_shipment_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>التكلفة الإجمالية</th>
                                        <td>{{ ($shipment->price ?? 0) +
                                            ($shipment->shipping_cost ?? 0) +
                                            ($shipment->expense_code ?? 0) +
                                            ($shipment->packaging_cost ?? 0) }}
                                            {{ $shipment->type_coin ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ الإنشاء</th>
                                        <td>{{ $shipment->created_at->toDateTimeString() }}</td>
                                    </tr>
                                    <tr>
                                        <th>تاريخ التحديث</th>
                                        <td>{{ $shipment->updated_at->toDateTimeString() }}</td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Sender Information -->
                            <div class="col-md-4">
                                <h6 class="mb-3">معلومات المرسل</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>الاسم</th>
                                        <td>{{ $shipment->sender_name ?? $shipment->person->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>العنوان</th>
                                        <td>{{ $shipment->sender_address ?? $shipment->person->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>البريد الإلكتروني</th>
                                        <td>{{ $shipment->sender_email ?? $shipment->person->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>الهاتف</th>
                                        <td>{{ $shipment->sender_phone ?? $shipment->person->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>الدولة</th>
                                        <td>{{ $shipment->countrySender->country_ar ?? $shipment->person->country->country_ar }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>المنطقة</th>
                                        <td>{{ $shipment->regionSender?->region_ar ?? $shipment->person?->region?->region_ar }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Receiver Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">معلومات المستلم</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>الاسم</th>
                                        <td>{{ $shipment->name_received ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>العنوان</th>
                                        <td>{{ $shipment->getFullAddress() }}</td>
                                    </tr>
                                    <tr>
                                        <th>الهاتف</th>
                                        <td>{{ $shipment->phone_received ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الهاتف الثاني</th>
                                        <td>{{ $shipment->phone_received_2 ?? '--' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Shipment Specifications -->
                            <div class="col-md-6">
                                <h6 class="mb-3">مواصفات الشحنة</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>الوزن</th>
                                        <td>{{ $shipment->weight ?? '--' }} كجم</td>
                                    </tr>
                                    <tr>
                                        <th>الأبعاد</th>
                                        <td>{{ $shipment->length ?? '--' }} x {{ $shipment->width ?? '--' }} x
                                            {{ $shipment->height ?? '--' }} سم</td>
                                    </tr>
                                    <tr>
                                        <th>الحجم (CBM)</th>
                                        <td>{{ $shipment->cbm ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الكمية</th>
                                        <td>{{ $shipment->products_in->count() ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>نوع المحتوى</th>
                                        <td>{{ $shipment->type_content ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>الوصف</th>
                                        <td>{{ $shipment->describe_shipments ?? '--' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Financial Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">معلومات مالية</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>سعر الشحنة</th>
                                        <td>{{ $shipment->price ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>
                                            @if ($shipment->type == 1)
                                                التوصيل الداخلى
                                            @else
                                                تكلفة الشحن
                                            @endif
                                        </th>
                                        <td>{{ $shipment->shipping_cost ?? '--' }}</td>
                                    </tr>
                                    @if ($shipment->type == 2)
                                        <tr>
                                            <th>عمولة التجميع</th>
                                            <td>{{ $shipment->assembly_commission ?? '--' }}</td>
                                        </tr>
                                        <tr>
                                            <th>سعر المندوب</th>
                                            <td>{{ $shipment->price_representative ?? '--' }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th>قيمة الراجع</th>
                                        <td>{{ $shipment->return_value ?? '-' }}</td>
                                    </tr>
                                    {{-- <tr>
                                        <th>المصروف</th>
                                        <td>{{ $shipment->expense_code ?? '--' }}</td>
                                    </tr> --}}
                                    <tr>
                                        <th>المدفوع</th>
                                        <td>{{ $shipment->refund_code ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>المتبقى</th>
                                        <td>
                                            {{ ($shipment->price ?? 0) +
                                                ($shipment->shipping_cost ?? 0) +
                                                ($shipment->expense_code ?? 0) +
                                                ($shipment->packaging_cost ?? 0) -
                                                ($shipment->refund_code ?? 0) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2" class="text-center">الملغى</th>
                                    </tr>
                                    <tr>
                                        <th>عمولة التجميع</th>
                                        <td>{{ $shipment->collection_commission == 1 ? 'مرسل' : 'مستلم' }}</td>
                                    </tr>
                                    <tr>
                                        <th>تكلفة الشحن الإضافية</th>
                                        <td>{{ $shipment->additional_shipping_cost == 1 ? 'مرسل' : 'مستلم' }}</td>
                                    </tr>
                                    <tr>
                                        <th>تكلفة الشحن</th>
                                        <td>{{ $shipment->cost_shipment_by == 1 ? 'مرسل' : 'مستلم' }}</td>
                                    </tr>
                                    <tr>
                                        <th>طبيعة الشحن</th>
                                        <td>
                                            @switch($shipment->natural_shipment)
                                                @case(1)
                                                    بطئ
                                                @break

                                                @case(2)
                                                    سريع
                                                @break

                                                @case(3)
                                                    شحن خاص
                                                @break

                                                @default
                                                    --
                                            @endswitch
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Additional Information -->
                            <div class="col-md-6">
                                <h6 class="mb-3">معلومات إضافية</h6>
                                <table class="table table-bordered">
                                    <tr>
                                        <th>نوع الفاتورة</th>
                                        <td>
                                            @switch($shipment->type_invoice)
                                                @case(1)
                                                    بدون فاتورة
                                                @break

                                                @case(2)
                                                    نصف فاتورة
                                                @break

                                                @case(3)
                                                    فاتورة كاملة
                                                @break

                                                @default
                                                    --
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>موقع الاستلام</th>
                                        <td>
                                            @switch($shipment->receipt_location)
                                                @case(1)
                                                    فرع
                                                @break

                                                @case(2)
                                                    توصيل داخلي
                                                @break

                                                @default
                                                    --
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>موقع التسليم</th>
                                        <td>
                                            @switch($shipment->delivery_location)
                                                @case(1)
                                                    من الفرع
                                                @break

                                                @case(2)
                                                    توصيل للمنزل
                                                @break

                                                @default
                                                    --
                                            @endswitch
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>ملاحظات</th>
                                        <td>{{ $shipment->notes ?? '--' }}</td>
                                    </tr>
                                    <tr>
                                        <th>سبب الإلغاء</th>
                                        <td>{{ $shipment->cancel_reason ?? '--' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Images -->
                            <div class="col-md-12">
                                <h6 class="mb-3">صور الشحنة</h6>
                                @if ($shipment->images->isNotEmpty())
                                    <div class="row">
                                        @foreach ($shipment->images as $image)
                                            <div class="col-md-3">
                                                <img src="{{ asset('storage/' . $image->path) }}" alt="Shipment Image"
                                                    class="img-fluid mb-2" style="max-height: 150px;">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p>لا توجد صور متاحة</p>
                                @endif
                            </div>
                            <!-- Status History -->
                            <div class="col-md-12">
                                <h6 class="mb-3">سجل الحالة</h6>
                                @if ($shipment->statusHistory->isNotEmpty())
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>الحالة</th>
                                                <th>التاريخ</th>
                                                <th>ملاحظات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shipment->statusHistory as $history)
                                                <tr>
                                                    <td>{{ \App\Models\Shipment::$statuses[$history->status] ?? 'غير معروف' }}
                                                    </td>
                                                    <td>{{ $history->created_at->toDateTimeString() }}</td>
                                                    <td>{{ $history->notes ?? '--' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>لا يوجد سجل حالة متاح</p>
                                @endif
                            </div>
                            <!-- Shipment Products -->
                            <div class="col-md-12">
                                <h6 class="mb-3">منتجات الشحنة</h6>
                                @if ($shipment->products_in->isNotEmpty())
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>كود المنتج</th>
                                                <th>اسم المنتج</th>
                                                <th>الكمية</th>
                                                <th>السعر</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shipment->products_in as $index => $product_in)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $product_in->product->code ?? '--' }}</td>
                                                    <td>{{ $product_in->product->name ?? '--' }}</td>
                                                    <td>{{ $product_in->quantity ?? '--' }}</td>
                                                    <td>{{ $product_in->price ?? '--' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>لا توجد منتجات مرتبطة بالشحنة</p>
                                @endif
                            </div>
                            <!-- Trip Details -->
                            <div class="col-md-12">
                                <h6 class="mb-3">تفاصيل الرحلة</h6>
                                @if ($shipment->trips->isNotEmpty())
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>كود الرحلة</th>
                                                <th>اسم السائق/المندوب</th>
                                                <th>من فرع</th>
                                                <th>إلى فرع/مدينة</th>
                                                <th>تاريخ الإنشاء</th>
                                                <th>الحالة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shipment->trips as $index => $trip)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $trip->code ?? '--' }}</td>
                                                    <td>{{ $trip->representative?->name ?? '--' }}</td>
                                                    <td>{{ $trip->branchFrom?->name ?? '--' }}</td>
                                                    <td>
                                                        @if ($trip->type_driver == 0)
                                                            {{ $trip->branchTo?->name ?? '--' }}
                                                        @else
                                                            {{ $trip->region?->region_ar ?? '--' }}
                                                        @endif
                                                    </td>
                                                    <td>{{ $trip->created_at->toDateTimeString() }}</td>
                                                    <td>{{ $trip->status_label ?? '--' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p>لا توجد رحلات مرتبطة بالشحنة</p>
                                @endif
                            </div>
                            <!-- Branch Details -->
                            <div class="col-md-12">
                                <h6 class="mb-3">مسار الشحنة</h6>
                                @if (
                                    $shipment->branchFrom ||
                                        ($shipment->type == 1 && $shipment->branchTo) ||
                                        ($shipment->type == 0 && $shipment->region))
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>الفرع</th>
                                                <th>اسم الفرع</th>
                                                <th>العنوان</th>
                                                <th>المنطقة</th>
                                                <th>الدولة</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($shipment->branchFrom)
                                                <tr>
                                                    <td>من فرع</td>
                                                    <td>{{ $shipment->branchFrom->name ?? '--' }}</td>
                                                    <td>{{ $shipment->branchFrom->address ?? '--' }}</td>
                                                    <td>{{ $shipment->branchFrom->region?->region_ar ?? '--' }}</td>
                                                    <td>{{ $shipment->branchFrom->country?->country_ar ?? '--' }}</td>
                                                </tr>
                                            @endif
                                            @if ($shipment->type == 1 && !empty($shipment->branchTo))
                                                <tr>
                                                    <td>إلى فرع</td>
                                                    <td>{{ $shipment->branchTo->name ?? '--' }}</td>
                                                    <td>{{ $shipment->branchTo->address ?? '--' }}</td>
                                                    <td>{{ $shipment->branchTo->region?->region_ar ?? '--' }}</td>
                                                    <td>{{ $shipment->branchTo->country?->country_ar ?? '--' }}</td>
                                                </tr>
                                            @elseif ($shipment->type == 1 && empty($shipment->branchTo))
                                                <div class="text-center" style='font-weight:bold'>الفرع المتوجه له الشحنة
                                                    ليس محدد</div>
                                            @elseif ($shipment->type == 2 && $shipment->region)
                                                <tr>
                                                    <td>إلى مدينة</td>
                                                    <td>--</td>
                                                    <td>--</td>
                                                    <td>{{ $shipment->region->region_ar ?? '--' }}</td>
                                                    <td>{{ $shipment->region->country?->country_ar ?? '--' }}</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                @else
                                    <p>لا توجد معلومات فروع متاحة</p>
                                @endif
                            </div>
                        </div>
                        <!-- Action Buttons -->
                        <div class="mt-4">
                            <a href="{{ route('admin.shipment.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-right"></i> العودة إلى القائمة
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Plugins JS start-->
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Plugins JS Ends-->
@endsection
