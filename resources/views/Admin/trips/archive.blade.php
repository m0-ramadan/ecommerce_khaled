@extends('Admin.layout.master')

@section('title', 'أرشيف رحلات المندوبين والسواقين')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .status-select {
            color: black !important;
            font-size: 0.875rem;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
        }

        .status-select.status-1 {
            background-color: #ffc107;
        }

        .status-select.status-2 {
            background-color: #0dcaf0;
        }

        .status-select.status-3 {
            background-color: #198754;
        }

        .status-select.status-8 {
            background-color: #198754;
        }

        .status-select.status-default {
            background-color: #6c757d;
        }

        .center {
            text-align: center;
        }
    </style>
@endsection

@section('content')
    @if (!auth()->user()->role || auth()->user()->hasPermissionTo('عرض أرشيف الرحلات'))
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <x-breadcrumb :items="[
                        'لوحة التحكم' => '/admin',
                        'أرشيف رحلات المندوبين والسواقين' => request()->routeIs('admin.trips.archive') ? '/admin/trips/archive' : '/admin/drivers',
                    ]" />
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="GET"
                        action="{{ request()->routeIs('admin.trips.archive') ? route('admin.trips.archive') : route('admin.driver') }}"
                        class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث باستخدام الكود أو المسار" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ request()->routeIs('admin.trips.archive') ? route('admin.trips.archive') : route('admin.driver') }}"
                                    class="btn btn-secondary">إعادة تعيين</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="display" id="basic-1">
                            <thead>
                                <tr>
                                    <th class="center">#</th>
                                    <th class="center">الكود</th>
                                    <th class="center">المسؤول</th>
                                    <th class="center">الحالة</th>
                                    <th class="center">{{ request()->routeIs('admin.trips.index') ? 'المندوب' : 'السائق' }}
                                    </th>
                                    <th class="center">من فرع</th>

                                        <th class="center">إلى فرع</th>
                                        <th class="center">المدفوع</th>
                                        <th class="center">الباقي</th>

                                        <th class="center">إلى مدينة</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trips as $key => $trip)
                                    <tr>
                                        <td class="center">{{ $trips->firstItem() + $key }}</td>
                                        <td class="center">{{ $trip->code ?? 'غير محدد' }}</td>
                                        <td class="center">{{ $trip->admin_create?->name ?? 'غير محدد' }}</td>
                                        <td class="center">
                                            @php
                                                $currentBranchId = auth()->user()->branch_id;
                                                $isFromBranch =
                                                    $currentBranchId && $trip->branch_from_id == $currentBranchId;
                                                $isToBranch =
                                                    $currentBranchId && $trip->branch_to_id == $currentBranchId;
                                                $isStatusLocked = $isFromBranch && $trip->status == 2;
                                                $isToBranchEligible = $isToBranch && $trip->status == 2;
                                                $isAdmin = is_null($currentBranchId);
                                            @endphp

                                                    <select name="status"
                                                        class="form-select status-select status-{{ $trip->status ?? 'default' }}"
                                                        onchange="this.form.submit()" title="تغيير حالة الرحلة">
                                                        <option value="1"
                                                            @if ($trip->status == 1) selected @endif>تحت الإجراء
                                                        </option>
                                                        <option value="2"
                                                            @if ($trip->status == 2) selected @endif>مع المندوب
                                                        </option>
                                                        <option value="3"
                                                            @if ($trip->status == 3) selected @endif>تم التسليم
                                                        </option>
                                                        <option value="8"
                                                            @if ($trip->status == 8) selected @endif>مكتملة
                                                        </option>
                                                    </select>

  
                                        </td>
                                        <td class="center">{{ $trip->representative?->name ?? 'غير محدد' }}</td>
                                        <td class="center">{{ $trip->branchFrom?->name ?? 'غير محدد' }}</td>
                                
                                            <td class="center">{{ $trip->branchTo?->name ?? 'غير محدد' }}</td>
                                            <td class="center">{{ $trip->expense_value ?? 'غير محدد' }}</td>
                                            <td class="center">{{ $trip->refund_value ?? 'غير محدد' }}</td>
                  
                                            <td class="center">{{ $trip->region?->region_ar ?? 'غير محدد' }}</td>
                                  

 
   
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        <ul class="pagination">
                            @if ($trips->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">⏮ السابق</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $trips->previousPageUrl() }}"
                                        rel="prev">⏮ السابق</a></li>
                            @endif
                            @foreach ($trips->links()->elements[0] ?? [] as $page => $url)
                                @if ($page == $trips->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach
                            @if ($trips->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $trips->nextPageUrl() }}"
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
    <script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#basic-1').DataTable({
                paging: false,
                searching: false,
                ordering: true,
                info: false,
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
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
