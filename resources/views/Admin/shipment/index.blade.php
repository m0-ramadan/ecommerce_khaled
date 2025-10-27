@extends('Admin.layout.master')
@php
    $currentRoute = request()->path(); // مثل admin/shipment/client أو admin/shipment/no_price
@endphp
@section('title')
    @if ($currentRoute == 'admin/shipment/all/shipments/priced')
        شحنات الزبائن تم التسعيرها منتظرة التأكيد
    @elseif ($currentRoute == 'admin/shipment/all/shipments/n-active-Merch')
        شحنات التجار منتظرة التأكيد
    @elseif ($currentRoute == 'admin/shipment/all/shipments/n-active')
        شحنات الزبائن منتظرة تأكيد الأدارة
    @elseif (request()->is('admin/shipment/*'))
        شحنات التجار
    @endif
@endsection
@section('content')
    @if (!auth()->user()->role || auth()->user()->hasPermissionTo('عرض شحنات التجار'))
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @if (request()->routeIs('admin.shipment.priced'))
                        <x-breadcrumb :items="[
                            'لوحة التحكم' => '/admin',
                            'شحنات الزبائن' => '/admin/shipment/client',
                            'شحنات الزبائن تم تسعيرها منتظرة التأكيد' => '',
                        ]" />
                    @elseif (request()->routeIs('admin.shipment.*'))
                        <x-breadcrumb :items="[
                            'لوحة التحكم' => '/admin',
                            'شحنات التجار' => '/admin/shipment/index',
                        ]" />
                    @endif
                </div>
                <div class="card-body">
                    <!-- Display Success and Error Messages -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.shipment.index') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث باستخدام الكود أو المسار" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.shipment.index') }}" class="btn btn-secondary">إعادة تعيين</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="display" id="example">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">المسئول</th>
                                    <th style="text-align: center;">الكود</th>
                                    <th style="text-align: center;">المسار</th>
                                    <th style="text-align: center;">قيمة الشحنة</th>
                                    <th style="text-align: center;">الحالة</th>
                                    <th style="text-align: center;">النوع</th>
                                    <th style="text-align: center;">التوصيل</th>
                                    <th style="text-align: center;">التاريخ</th>
                                    <th style="text-align: center;">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipments as $key => $shipment)
                                    <tr>
                                        <td style="text-align: center;">{{ $shipments->firstItem() + $key }}</td>
                                        <td style="text-align: center;">{{ $shipment->person?->name ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">{{ $shipment->code }}</td>
                                        <td style="text-align: center;">
                                            {{ $shipment->branchFrom?->name ?? 'غير محدد' }} -
                                            @if ($shipment->type == 2)
                                                {{ $shipment->region?->region_ar ?? 'غير محدد' }}
                                            @else
                                                {{ $shipment->branchTo?->name ?? 'غير محدد' }}
                                            @endif
                                        </td>
                                        <td style="text-align: center;">{{ $shipment->price ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">
                                            <span
                                                class="badge {{ $shipment->status_badge_class }}">{{ $shipment->status_label }}</span>
                                        </td>
                                        <td style="text-align: center;">{{ $shipment->service?->name ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">{{ $shipment->shipping_cost ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">
                                            {{ date('Y-m-d', strtotime($shipment->created_at)) }}</td>
                                        <td class="d-flex gap-1 justify-content-center">
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('طباعة شحنة'))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.printPdf', [$shipment->id]) }}"
                                                    title="طباعة PDF">
                                                    <i class="fa fa-print text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('إعادة طباعة شحنة'))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.printPdfReset', [$shipment->id]) }}"
                                                    title="إعادة طباعة PDF">
                                                    <i class="fa fa-receipt text-white"></i>
                                                </a>
                                            @endif
                                            @if (request()->routeIs(['admin.shipment.n_active', 'admin.shipment.n_activeMerch']) &&
                                                    (!auth()->user()->role || auth()->user()->hasPermissionTo('تأكيد الشحنة')))
                                                <form
                                                    action="{{ route('admin.shipment.confirm_shipment', $shipment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        title="تأكيد الشحنة">
                                                        <i class="fa fa-check text-white"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if (
                                                !request()->routeIs('admin.shipment.recieved') &&
                                                    $shipment->status_id != 2 &&
                                                    (!auth()->user()->role || auth()->user()->hasPermissionTo('تعديل الشحنة')))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.edit', [$shipment->id]) }}"
                                                    title="تعديل">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('عرض تفاصيل الشحنة'))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.show', [$shipment->id]) }}"
                                                    title="عرض">
                                                    <i class="fa fa-eye text-white"></i>
                                                </a>
                                            @endif
                                            @if (
                                                !request()->routeIs('admin.shipment.recieved') &&
                                                    (!auth()->user()->role || auth()->user()->hasPermissionTo('حذف الشحنة')))
                                                <form method="POST"
                                                    action="{{ route('admin.shipment.destroy', $shipment->id) }}"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الشحنة؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-primary" title="حذف">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        <ul class="pagination">
                            @if ($shipments->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">⏮ السابق</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $shipments->previousPageUrl() }}"
                                        rel="prev">⏮ السابق</a></li>
                            @endif
                            @foreach ($shipments->links()->elements[0] ?? [] as $page => $url)
                                @if ($page == $shipments->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                            @if ($shipments->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $shipments->nextPageUrl() }}"
                                        rel="next">التالي ⏭</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">التالي ⏭</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            ليس لديك صلاحية لعرض هذه الصفحة.
        </div>
    @endif
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                paging: false, // تعطيل تصفح DataTables لاستخدام تصفح Laravel
                searching: false, // تعطيل البحث الافتراضي لـ DataTable
                ordering: true,
                info: false,
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    },
                    'colvis'
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
                }
            });

        });
    </script>
@endsection
