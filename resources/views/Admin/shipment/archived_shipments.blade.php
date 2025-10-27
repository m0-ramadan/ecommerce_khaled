@extends('Admin.layout.master')

@php
    $currentRoute = request()->path();
@endphp

@section('title')
    أرشيف الشحنات
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>أرشيف الشحنات</h4>
                <x-breadcrumb :items="[
                    'لوحة التحكم' => '/admin',
                    'أرشيف الشحنات' => '',
                ]" />
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="archivedShipmentsTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>المسئول</th>
                                <th>الكود</th>
                                <th>المسار</th>
                                <th>الوزن</th>
                                <th>المقاس</th>
                                <th>قيمة الشحنة</th>
                                <th>الحالة</th>
                                <th>النوع</th>
                                <th>التوصيل</th>
                                <th>التاريخ</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shipments as $key => $shipment)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $shipment->admin_create->name ?? 'غير محدد' }}</td>
                                    <td>{{ $shipment->code }}</td>
                                    <td>
                                        {{ $shipment->branchFrom->name ?? '-' }} -
                                        @if ($shipment->type == 2)
                                            {{ $shipment->region->region_ar ?? '-' }}
                                        @else
                                            {{ $shipment->branchTo->name ?? '-' }}
                                        @endif
                                    </td>
                                    <td>{{ $shipment->weight ?? '--' }}</td>
                                    <td>{{ $shipment->size ?? '--' }}</td>
                                    <td>{{ number_format($shipment->price, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge {{ $shipment->status_badge_class }}">{{ $shipment->status_label }}</span>
                                    </td>
                                    <td>{{ $shipment->type_shipment_name ?? '--' }}</td>
                                    <td>{{ number_format($shipment->shipping_cost, 2) }}</td>
                                    <td>{{ date('Y-m-d', strtotime($shipment->created_at)) }}</td>
                                    <td class="d-flex gap-1">
                                        <a class="btn btn-success btn-sm"
                                            href="{{ route('admin.shipment.show', $shipment->id) }}" title="عرض التفاصيل">
                                            <i class="fa fa-eye text-white"></i>
                                        </a>
                                        <form action="{{ route('admin.force_delete', $shipment->id) }}" method="POST"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="حذف">
                                                <i class="fa-solid fa-trash text-white"></i>
                                            </button>
                                        </form>
                                        @if (!auth()->user()->role)
                                            <a class="btn btn-warning btn-sm"
                                                href="{{ route('admin.restore', $shipment->id) }}" title="استرجاع">
                                                <i class="fa-solid fa-rotate-left text-white"></i>
                                            </a>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#archivedShipmentsTable').DataTable({
                pagingType: 'full_numbers',
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    },
                    'colvis'
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                },
                order: [
                    [10, 'desc']
                ], // Sort by created_at descending
                pageLength: 10,
                responsive: true
            });
        });
    </script>
@endsection
