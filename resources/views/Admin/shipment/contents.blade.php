@extends('Admin.layout.master')

@section('title')
    محتويات الشحنة #{{ $shipment->code ?? '--' }}
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
                        <h5>محتويات الشحنة #{{ $shipment->code ?? '--' }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Shipment Contents -->
                        <div class="col-md-12">
                            <h6 class="mb-3">محتويات الشحنة</h6>
                            @if ($shipment->contents->isNotEmpty())
                                <table class="table table-bordered display" id="shipmentContentsTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>كود المحتوى</th>
                                            <th>اسم المحتوى</th>
                                            <th>الكمية</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shipment->contents as $index => $content)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $content->code ?? '--' }}</td>
                                                <td>{{ $content->name ?? '--' }}</td>
                                                <td>{{ $content->quantity ?? '--' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <p>لا توجد محتويات مرتبطة بالشحنة</p>
                            @endif
                        </div>
                        <!-- Action Buttons -->
                        <div class="mt-4">
                            <a href="{{ route('admin.shipment.index') }}" class="btn btn-secondary">
                                <i class="fa fa-arrow-right"></i> العودة إلى القائمة
                            </a>
                            <a href="{{ route('admin.shipment.show', $shipment->id) }}" class="btn btn-primary">
                                <i class="fa fa-eye"></i> عرض تفاصيل الشحنة
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
    <script>
        $(document).ready(function() {
            $('#shipmentContentsTable').DataTable({
                pagingType: 'full_numbers',
                dom: 'lBfrtip',
                buttons: [
                    'copyHtml5',
                    'excelHtml5',
                    'print',
                    'colvis'
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json'
                },
                order: [
                    [0, 'asc']
                ],
                pageLength: 10,
                responsive: true
            });
        });
    </script>
@endsection
