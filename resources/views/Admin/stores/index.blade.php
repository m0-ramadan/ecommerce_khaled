@extends('Admin.layout.master')

@section('title')
المخازن
@endsection

@section('css')
<!-- Plugins css start-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Plugins css Ends-->
@endsection

@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <a class="btn btn-success" href="{{ route('admin.stores.create') }}">إضافة مخزن</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th class="center">#</th>
                            <th class="center">الاسم</th>
                            <th class="center">الوصف</th>
                            <th class="center">الكمية</th>
                            <th class="center">الفرع</th>

                            <th class="center">الشحنة</th>
                            <th class="center">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stores as $key => $store)
                        <tr>
                            <td class="center">{{ $loop->iteration }}</td>
                            <td class="center">{{ $store->name ?? '--' }}</td>
                            <td class="center">{{ $store->desc ?? '--' }}</td>
                            <td class="center">{{ $store->quantity ?? '0' }}</td>
                            <td class="center">{{ $store->branch->name ?? '--' }}</td>

                            <td class="center">{{ $store->shipment ? $store->shipment->code : 'غير محدد' }}</td>
                            <td class="center">
                                <a class="btn btn-success" href="{{ route('admin.stores.edit', $store->id) }}"
                                    data-bs-toggle="tooltip" title="تعديل المخزن">
                                    <i class="fa fa-edit text-white"></i>
                                </a>
                                <form method="post" action="{{ route('admin.stores.destroy', $store->id) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip"
                                        title="حذف المخزن" onclick="return confirm('هل أنت متأكد من حذف هذا المخزن؟')">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
<!-- Plugins JS start-->
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Plugins JS Ends-->
@endsection