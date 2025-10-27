@extends('Admin.layout.master')

@section('title')
    تفاصيل الرحلة
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .status-select {
            color: black !important;
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        .status-select.status-1,
        .status-select.status-4,
        .status-select.status-7 {
            background-color: #ffc107;
        }

        .status-select.status-2,
        .status-select.status-6,
        .status-select.status-9,
        .status-select.status-10 {
            background-color: #0dcaf0;
        }

        .status-select.status-3,
        .status-select.status-8 {
            background-color: #198754;
        }

        .status-select.status-5,
        .status-select.status-11,
        .status-select.status-12,
        .status-select.status-13 {
            background-color: #dc3545;
        }

        .status-select.status-14 {
            background-color: #6f42c1;
        }

        .status-select.status-default,
        .status-select.status-0 {
            background-color: #6c757d;
        }

        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .content-table th,
        .content-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-family: 'Cairo', sans-serif;
            text-align: center;
        }

        .content-table th {
            background-color: #f8f9fa;
        }

        .action-btn {
            font-size: 14px;
            padding: 5px 10px;
        }

        .modal-content {
            font-family: 'Cairo', sans-serif;
        }

        .taken-input {
            width: 80px;
            padding: 5px;
            margin-top: 5px;
            display: block;
        }

        .save-btn {
            margin-top: 5px;
            padding: 5px 10px;
            font-size: 0.875rem;
        }

        .invalid-quantity {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-family: 'Cairo', sans-serif;
            color: #dc3545;
            font-size: 0.875rem;
            display: none;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            #printableBarcodes,
            #printableBarcodes * {
                visibility: visible;
            }

            #printableBarcodes {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                font-family: 'Cairo', sans-serif;
            }

            .barcode-print {
                display: block;
                margin: 20px auto;
                text-align: center;
                page-break-inside: avoid;
            }

            .barcode-print img {
                max-width: 200px;
                height: auto;
            }

            .barcode-print p {
                margin: 5px 0;
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>تفاصيل الرحلة</h5>
                <a class="btn btn-success" href="{{ route('admin.trips.index') }}">رجوع إلى قائمة الرحلات</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>الكود:</strong> {{ $trip->code ?? 'N/A' }}</p>
                        <p><strong>الحالة:</strong>
                            @php
                                $currentBranchId = auth()->user()->branch_id;
                                $isFromBranch = $currentBranchId && $trip->branches_from == $currentBranchId;
                                $isToBranch = $currentBranchId && $trip->branches_to == $currentBranchId;
                                $isStatusLocked = $isFromBranch && $trip->status == 2;
                                $isToBranchEligible = $isToBranch && $trip->status == 2;
                            @endphp
                            @if ($isFromBranch || $isToBranchEligible)
                                <form action="{{ route('admin.trips.changeStatus', $trip->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status"
                                        class="form-select status-select status-{{ $trip->status ?? 'default' }}"
                                        @if ($isStatusLocked) disabled @endif onchange="this.form.submit()">
                                        @if ($isFromBranch)
                                            <option value="1" @if ($trip->status == 1) selected @endif>
                                                تحت الإجراء
                                            </option>
                                            <option value="2" @if ($trip->status == 2) selected @endif>
                                                مع المندوب
                                            </option>
                                        @elseif ($isToBranchEligible)
                                            <option value="2" @if ($trip->status == 2) selected @endif>
                                                مع المندوب
                                            </option>
                                            <option value="3" @if ($trip->status == 3) selected @endif>
                                                تم التسليم
                                            </option>
                                        @endif
                                    </select>
                                </form>
                            @else
                                <span class="status-select status-{{ $trip->status ?? 'default' }}">
                                    {{ $trip->getStatusLabelAttribute() }}
                                </span>
                            @endif
                        </p>
                        <p><strong>الاسم:</strong>
                            {{ $trip->representative ? $trip->representative->name : '' }}
                        </p>
                        {{-- <p><strong>الكود:</strong> {{ $trip->representative ? $trip->representative->code : ' --' }}</p> --}}
                        <p><strong>من فرع:</strong> {{ $trip->branchFrom->name ?? 'غير متوفر' }}</p>
                        <p><strong>تاريخ الإنشاء:</strong> {{ $trip->created_at ?? 'N/A' }}</p>
                        <p><strong>تاريخ التحديث:</strong> {{ $trip->updated_at ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6">
                        @if ($trip->type_driver == 0)
                            <p><strong>إلى فرع:</strong> {{ $trip->branchTo->name ?? 'غير متوفر' }}</p>

                            @php
                                $currencyLabel = '';

                                if (!empty($trip->type_coin)) {
                                    $typeCoin = (int) $trip->type_coin;

                                    $currencyLabel = match ($typeCoin) {
                                        1 => 'LYD',
                                        2 => 'EGP',
                                        3 => '$',
                                        4 => 'TRY',
                                        default => 'غير معروف',
                                    };
                                }

                                $valueDrive = $trip->value_drive
                                    ? $trip->value_drive . ' ' . $currencyLabel
                                    : 'غير متوفر';
                                $expenseValue = $trip->expense_value
                                    ? $trip->expense_value . ' ' . $currencyLabel
                                    : 'غير متوفر';
                                $refundValue = $trip->refund_value
                                    ? $trip->refund_value . ' ' . $currencyLabel
                                    : 'غير متوفر';
                            @endphp

                            <p><strong>حساب الرحلة:</strong> {{ $valueDrive }}</p>
                            <p><strong>المدفوع:</strong> {{ $expenseValue }}</p>
                            <p><strong>المتبقي:</strong> {{ $refundValue }}</p>
                        @else
                            <p><strong>إلى مدينة:</strong> {{ $trip->region->region_ar ?? 'غير متوفر' }}</p>
                            <p><strong>الملاحظات:</strong> {{ $trip->description ?? 'غير متوفر' }}</p>
                        @endif
                        <p><strong>عدد الشحنات:</strong> {{ $trip->shipments->count() ?? 0 }}</p>
                        <p><strong> المسؤول:</strong> {{ $trip->admin_create->name ?? '' }}</p>


                    </div>
                </div>

                <div class="mt-4">
                    <h4>تفاصيل الشحنات</h4>
                    @if ($trip->shipments->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>كود الشحنة</th>
                                        <th>اسم العميل</th>
                                        <th>رقم التليفون</th>
                                        <th>عنوان العميل</th>
                                        <th>الحالة</th>
                                        <th>الوزن (كجم)</th>
                                        <th>التكلفة</th>
                                        <th>محتوى الشحنة</th>
                                        <th>الملاحظات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trip->shipments as $index => $shipment)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $shipment->code ?? 'N/A' }}</td>
                                            <td>{{ $shipment->name_received ?? 'N/A' }}</td>
                                            <td>{{ $shipment->phone_received ?? 'N/A' }}</td>
                                            <td>{{ $shipment->address_received ?? 'N/A' }}</td>
                                            <td>
                                                <span class="status-select status-{{ $shipment->status_id ?? 'default' }}">
                                                    @switch($shipment->status_id)
                                                        @case(1)
                                                            تحت الإجراء
                                                        @break

                                                        @case(2)
                                                            مع المندوب
                                                        @break

                                                        @case(3)
                                                            تم التسليم
                                                        @break

                                                        @case(4)
                                                            قيد الانتظار
                                                        @break

                                                        @case(5)
                                                            رفض الاستلام
                                                        @break

                                                        @case(6)
                                                            راجع مع المندوب
                                                        @break

                                                        @case(7)
                                                            داخل الفرع
                                                        @break

                                                        @case(8)
                                                            تم الاكتمال
                                                        @break

                                                        @case(9)
                                                            راجع الفرع
                                                        @break

                                                        @case(10)
                                                            مرحلة إلى فرع آخر
                                                        @break

                                                        @case(11)
                                                            مفقودة
                                                        @break

                                                        @case(12)
                                                            ناقص
                                                        @break

                                                        @case(13)
                                                            تلف
                                                        @break

                                                        @case(14)
                                                            داخل الجمرك
                                                        @break

                                                        @default
                                                            غير محدد
                                                    @endswitch
                                                </span>
                                            </td>
                                            <td>{{ $shipment->weight ?? 'N/A' }}</td>
                                            @if ($shipment->type == 0)
                                                <td>{{ $shipment->calculateTotalCost() ?? 'N/A' }}</td>
                                            @else
                                                <td>{{ $shipment->calculateTotalCost2() ?? 'N/A' }}</td>
                                            @endif
                                            <td>
                                                <button class="btn btn-success action-btn" data-bs-toggle="modal"
                                                    data-bs-target="#contentModal"
                                                    data-content='{{ json_encode(
                                                        $trip->contents->where('shipment_id', $shipment->id)->map(function ($content) use ($trip) {
                                                                $tripContent = \App\Models\TripShipmentContent::where('shipment_content_id', $content->id)->where('trip_id', $trip->id)->first();
                                                                return [
                                                                    'id' => $content->id,
                                                                    'code' => $content->code ?? '--',
                                                                    'name' => $content->name ?? '--',
                                                                    'quantity' => $content->pivot->quantity ?? 0,
                                                                    'status_id' => $tripContent ? $tripContent->status_id : 0,
                                                                    'taken' => $tripContent ? $tripContent->taken : 0,
                                                                    'barcode' => $content->barcode ?? '--',
                                                                ];
                                                            })->values()->toArray(),
                                                    ) }}'>
                                                    <i class="fa fa-eye text-white"></i>
                                                </button>
                                            </td>
                                            <td>{{ $shipment->notes ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>لا توجد شحنات مرتبطة بهذه الرحلة</p>
                    @endif
                </div>

                <div class="mt-4">
                    <h4>سجل حالات الرحلة</h4>
                    @if ($trip->statusHistory->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>الحالة</th>
                                        <th>تاريخ التحديث</th>
                                        <th>الملاحظات</th>
                                        <th>الصورة</th>
                                        <th>محدث بواسطة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trip->statusHistory as $index => $history)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <span class="status-select status-{{ $history->status_id ?? 'default' }}">
                                                    @if (isset(\App\Models\Trip::$statuses[$history->status_id]))
                                                        {{ \App\Models\Trip::$statuses[$history->status_id] }}
                                                    @else
                                                        غير معروف
                                                    @endif
                                                </span>
                                            </td>
                                            <td>{{ $history->created_at ? $history->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                                            </td>
                                            <td>{{ $history->explain ?? 'لا توجد ملاحظات' }}</td>
                                            <td>
                                                @if ($history->image)
                                                    <a href="#" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal{{ $index }}">
                                                        <img src="{{ asset('storage/' . $history->image) }}"
                                                            alt="Status Image" style="max-width: 100px;">
                                                    </a>
                                                    <div class="modal fade" id="imageModal{{ $index }}"
                                                        tabindex="-1" aria-labelledby="imageModalLabel{{ $index }}"
                                                        aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title"
                                                                        id="imageModalLabel{{ $index }}">صورة الحالة
                                                                    </h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <img src="{{ asset('storage/' . $history->image) }}"
                                                                        alt="Status Image" class="img-fluid">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    لا توجد صورة
                                                @endif
                                            </td>
                                            <td>{{ $history->admin_update ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>لا توجد سجلات حالات مرتبطة بهذه الرحلة</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Content Modal -->
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentModalLabel">محتوى الشحنة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="contentStatusForm" action="{{ route('admin.content.changeStatus', '') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                    <div class="modal-body">
                        <table class="content-table">
                            <thead>
                                <tr>
                                    <th class="center">كود المحتوى</th>
                                    <th class="center">اسم المحتوى</th>
                                    <th class="center">الكمية</th>
                                    <th class="center">الحالة</th>
                                    <th class="center">الكمية المأخوذة</th>
                                </tr>
                            </thead>
                            <tbody id="modalContentBody"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                        @if ($isToBranchEligible || (!$isFromBranch && !$isToBranch))
                            <button type="submit" class="btn btn-primary save-btn">حفظ الكل</button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Hidden div for printing barcodes -->
    <div id="printableBarcodes" style="display: none;"></div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Pass PHP variables to JavaScript
        const currentBranchId = {{ auth()->user()->branch_id ?? 'null' }};
        const tripStatus = {{ $trip->status ?? 'null' }};
        const branchFromId = {{ $trip->branches_from ?? 'null' }};
        const branchToId = {{ $trip->branches_to ?? 'null' }};
        const storageBase = "{{ asset('public/storage') }}";
        const statusMap = {
            1: {
                name: 'تحت الإجراء',
                class: 'status-1'
            },
            7: {
                name: 'داخل الفرع',
                class: 'status-7'
            },
            9: {
                name: 'راجع الفرع',
                class: 'status-9'
            },
            10: {
                name: 'مرحلة إلى فرع آخر',
                class: 'status-10'
            },
            11: {
                name: 'مفقودة',
                class: 'status-11'
            },
            12: {
                name: 'ناقص',
                class: 'status-12'
            },
            13: {
                name: 'تلف',
                class: 'status-13'
            },
            14: {
                name: 'داخل الجمرك',
                class: 'status-14'
            }
        };

        $(document).ready(function() {
            // Debug branch and status
            console.log('Current Branch ID:', currentBranchId);
            console.log('Trip Status:', tripStatus);
            console.log('Branch From ID:', branchFromId);
            console.log('Branch To ID:', branchToId);

            // Initialize DataTables
            $('.table').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });

            // Store the base route for content status change
            const contentStatusRouteBase = "{{ route('admin.content.changeStatus', ':id') }}";

            // Debounce action button clicks to prevent multiple modal triggers
            let isModalOpening = false;
            $('.action-btn').on('click', function() {
                if (isModalOpening) return;
                isModalOpening = true;

                // Remove any existing backdrops
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');

                try {
                    const contents = $(this).data('content');
                    const $modalBody = $('#modalContentBody');
                    $modalBody.empty();

                    // Branch and status checks for content edit permissions
                    const isFromBranch = currentBranchId === branchFromId;
                    const isToBranch = currentBranchId === branchToId;
                    const isFromBranchLocked = isFromBranch && tripStatus === 2;
                    const isToBranchEligible = isToBranch && tripStatus === 2;

                    console.log('Content data:', contents);
                    console.log('isFromBranch:', isFromBranch);
                    console.log('isToBranch:', isToBranch);
                    console.log('isFromBranchLocked:', isFromBranchLocked);
                    console.log('isToBranchEligible:', isToBranchEligible);
                    if (contents && Array.isArray(contents) && contents.length > 0) {
                        contents.forEach(content => {
                            if (!content.id) {
                                console.warn(`Invalid content data:`, content);
                                return;
                            }
                            const status = statusMap[content.status_id] || {
                                name: 'غير محدد',
                                class: 'status-0'
                            };
                            console.log(
                                `Rendering content ID ${content.id} with status_id ${content.status_id} (${status.name}), taken: ${content.taken}`
                            );
                            const isLocked = [7, 11, 12, 10, 3, 13].includes(content.status_id);
                            const isEditable = !isLocked && !isFromBranchLocked && (
                                isToBranchEligible || (!isFromBranch && !isToBranch));
                            $modalBody.append(`
                                <tr data-content-id="${content.id}">
                                    <td class="center">${content.code || '--'}</td>
                                    <td class="center">${content.name || '--'}</td>
                                    <td class="center">${content.quantity || 0}</td>
                                    <td class="center">
                                        ${isEditable ? `
                                                                                                                        <select name="contents[${content.id}][status_id]" class="form-select status-select ${status.class}"
                                                                                                                                data-content-id="${content.id}" data-max-quantity="${content.quantity}">
                                                                                                                            ${Object.entries(statusMap).map(([value, status]) => `
                                                    <option value="${value}" ${content.status_id == value ? 'selected' : ''}>
                                                        ${status.name}
                                                    </option>
                                                `).join('')}
                                                                                                                        </select>
                                                                                                                        <input type="number" name="contents[${content.id}][taken]" class="form-control taken-input"
                                                                                                                               min="0" max="${content.quantity}" value="${content.taken || 0}">
                                                                                                                        <span class="invalid-feedback">الكمية المأخوذة يجب أن تكون بين 0 و ${content.quantity}</span>
                                                                                                                    ` : `
                                                                                                                        <span class="status-select ${status.class}">${status.name}</span>
                                                                                                                    `}
                                    </td>
                                    <td class="center">${content.taken || 0}</td>
                                </tr>
                            `);
                        });

                        // Update form action with a placeholder ID (will be handled by backend)
                        $('#contentStatusForm').attr('action', contentStatusRouteBase.replace(':id',
                            contents[0].id));

                        // Store barcodes and details for printing
                        $('#printableBarcodes').data('printData', contents.filter(c => c.barcode).map(c =>
                            ({
                                barcode: `${storageBase}/${c.barcode}`,
                                code: c.code || '--',
                                name: c.name || '--',
                                quantity: c.quantity || 0
                            })));
                    } else {
                        console.log('No contents available for this shipment');
                        $modalBody.append(
                            '<tr><td colspan="5" class="center">لا توجد محتويات مرتبطة بالشحنة</td></tr>'
                        );
                        $('#printableBarcodes').data('printData', []);
                    }
                } catch (error) {
                    console.error('Error opening modal:', error);
                    alert('حدث خطأ أثناء فتح نافذة المحتويات. يرجى التحقق من وحدة التحكم.');
                } finally {
                    isModalOpening = false;
                }
            });

            // Validate taken input for all statuses
            $(document).on('change input', '.taken-input', function() {
                const $input = $(this);
                const $error = $input.siblings('.invalid-feedback');
                const maxQuantity = parseInt($input.siblings('.status-select').data('max-quantity')) || 0;
                const value = parseInt($input.val()) || 0;

                if (value < 0 || value > maxQuantity) {
                    $input.addClass('invalid-quantity');
                    $error.show();
                    $input.val('');
                } else {
                    $input.removeClass('invalid-quantity');
                    $error.hide();
                }
            });

            // Validate all inputs before form submission
            $('#contentStatusForm').on('submit', function(e) {
                let isValid = true;
                $('.taken-input').each(function() {
                    const $input = $(this);
                    const $error = $input.siblings('.invalid-feedback');
                    const maxQuantity = parseInt($input.siblings('.status-select').data(
                        'max-quantity')) || 0;
                    const value = parseInt($input.val()) || 0;

                    if (value < 0 || value > maxQuantity) {
                        $input.addClass('invalid-quantity');
                        $error.show();
                        isValid = false;
                    } else {
                        $input.removeClass('invalid-quantity');
                        $error.hide();
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('يرجى تصحيح الكميات المأخوذة قبل الحفظ.');
                }
            });

            // Ensure backdrop is removed when modal is hidden
            $('#contentModal').on('hidden.bs.modal', function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
                console.log('Content modal hidden, backdrop removed');
            });

            // Ensure backdrop is removed for image modals
            $('[id^=imageModal]').on('hidden.bs.modal', function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
                console.log('Image modal hidden, backdrop removed');
            });
        });
    </script>
@endsection
