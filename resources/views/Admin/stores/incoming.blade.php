@extends('Admin.layout.master')

@section('title')
    الشحنات الواردة
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .center {
            text-align: center !important;
        }

        .action-btn {
            font-size: 14px;
            padding: 5px 10px;
        }

        .modal-content {
            font-family: 'Cairo', sans-serif;
        }

        .modal-content table {
            width: 100%;
            border-collapse: collapse;
        }

        .modal-content th,
        .modal-content td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .modal-content th {
            background-color: #f8f9fa;
        }

        .modal-content td.status-1 {
            color: green;
        }

        /* اتسلمت */
        .modal-content td.status-2 {
            color: blue;
        }

        /* في الفرع */
        .modal-content td.status-3 {
            color: orange;
        }

        /* انتقل لفرع آخر */
        .modal-content td.status-4 {
            color: red;
        }

        /* تلف */
        .modal-content td.status-5 {
            color: purple;
        }

        /* ناقص */
    </style>
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5>الشحنات الواردة</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="basic-1">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th class="center">كود الشحنة</th>
                                <th class="center">صاحب الشحنة</th>
                                <th class="center">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach ($shipments as $shipment)
                                @if ($shipment->warehouse->isNotEmpty())
                                    <tr>
                                        <td class="center">{{ $i++ }}</td>
                                        <td class="center">{{ $shipment->code ?? '--' }}</td>
                                        <td class="center">{{ $shipment->person->name ?? '--' }}</td>
                                        <td class="center">
                                            <button class="btn btn-success action-btn" data-bs-toggle="modal"
                                                data-bs-target="#contentModal"
                                                data-content='{{ json_encode(
                                                    $shipment->warehouse->flatMap(function ($warehouse) use ($shipment) {
                                                            return $warehouse->trContent
                                                                ? [
                                                                    [
                                                                        'code' => $warehouse->trContent->shipmentContent->code ?? '--',
                                                                        'name' => $warehouse->trContent->shipmentContent->name ?? '--',
                                                                        'price' => $warehouse->trContent->shipmentContent->price
                                                                            ? number_format($warehouse->trContent->shipmentContent->price, 2)
                                                                            : '0.00',
                                                                        'currency' => $shipment->effective_currency_label ?? 'غير معروف',
                                                                        'total' =>
                                                                            $warehouse->trContent->shipmentContent->price && $warehouse->trContent->quantity
                                                                                ? number_format(
                                                                                    $warehouse->trContent->shipmentContent->price * $warehouse->trContent->quantity,
                                                                                    2,
                                                                                )
                                                                                : '0.00',
                                                                        'quantity' => $warehouse->trContent->quantity ?? 0,
                                                                        'branch' => $warehouse->branchFrom->name ?? 0,
                                                
                                                                        'taken' => $warehouse->trContent->taken ?? 0,
                                                                        'remaining' => ($warehouse->trContent->quantity ?? 0) - ($warehouse->trContent->taken ?? 0),
                                                                        'status' => match ($warehouse->trContent->status_id) {
                                                                            1 => 'تحت الاجراء',
                                                                            7 => 'داخل الفرع',
                                                                            9 => 'راجع الفرع',
                                                                            10 => 'مرحلة إلى فرع آخر',
                                                                            11 => 'مفقودة',
                                                                            12 => 'ناقص',
                                                                            13 => 'تلف',
                                                                            14 => 'داخل الجمرك',
                                                                            default => 'غير معروف',
                                                                        },
                                                                    ],
                                                                ]
                                                                : [];
                                                        })->values()->toArray(),
                                                ) }}'>
                                                <i class="fa fa-eye text-white"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Modal -->
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentModalLabel">تفاصيل المحتويات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table>
                        <thead>
                            <tr>
                                <th>كود المحتوى</th>
                                <th>الاسم</th>
                                <th>سعر القطعة</th>
                                <th>الإجمالي</th>
                                <th>الكمية</th>
                                <th>الكمية المأخوذة</th>
                                <th>الكمية المتبقية</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody id="modalContentBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Display Flash Messages -->
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
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-1').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json'
                },
                columnDefs: [{
                    orderable: false,
                    targets: 3
                }]
            });

            $('.action-btn').on('click', function() {
                const contents = $(this).data('content');
                console.log('Contents:', contents);
                const $modalBody = $('#modalContentBody');
                $modalBody.empty();

                if (contents && Array.isArray(contents) && contents.length > 0) {
                    contents.forEach(content => {
                        // تنسيق السعر والإجمالي مع العملة
                        const price = content.price !== null && content.price !== undefined ?
                            `${parseFloat(content.price).toFixed(2)} ${content.currency || ''}` :
                            'غير معروف';
                        const total = content.total !== null && content.total !== undefined ?
                            `${parseFloat(content.total).toFixed(2)} ${content.currency || ''}` :
                            'غير معروف';

                        $modalBody.append(`
                            <tr>
                                <td>${String(content.code || '--')}</td>
                                <td>${String(content.name || '--')}</td>
                                <td>${price}</td>
                                <td>${total}</td>
                                <td>${content.quantity || 0}</td>
                                <td>${content.taken || 0}</td>
                                <td>${content.remaining || 0}</td>
                              <td class="status-${
                                content.status === 'تحت الاجراء' ? 1 :
                                content.status === 'داخل الفرع' ? 7 :
                                content.status === 'راجع الفرع' ? 9 :
                                content.status === 'مرحلة إلى فرع آخر' ? 10 :
                                content.status === 'مفقودة' ? 11 :
                                content.status === 'ناقص' ? 12 :
                                content.status === 'تلف' ? 13 :
                                content.status === 'داخل الجمرك' ? 14 :
                                0
                            }">
                        ${content.status || 'غير معروف'} ${content.branch || 'لم تحدد بعد'}
                            </td>

                            </tr>
                        `);
                    });
                } else {
                    $modalBody.append('<tr><td colspan="8" class="center">لا توجد محتويات متاحة</td></tr>');
                }
            });
        });
    </script>
@endsection
