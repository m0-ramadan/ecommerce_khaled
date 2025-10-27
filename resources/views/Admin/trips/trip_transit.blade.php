@extends('Admin.layout.master')

@section('title')
    رحلات المندوب
@endsection

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
    </style>
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <x-breadcrumb :items="[
                    'لوحة التحكم' => '/admin',
                    'رحلات ' . (request()->routeIs('admin.trips.index') ? 'المندوب' : 'الوارد المرحل ') => '',
                ]" />
            </div>
            <div class="card-header">
                @php
                    $currentPath = request()->path();
                    $t = $currentPath === 'admin/trips' ? 1 : 0;
                @endphp
                <a class="btn btn-success" href="{{ route('admin.createTripTransfer') }}">اضافة رحلة</a>
                <a class="btn btn-success" href="#"> استلام محتويات من رحله </a>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="basic-1">
                        <thead>
                            <tr>
                                <th class="center">#</th>
                                <th class="center">الكود</th>
                                <th class="center">الحالة</th>
                                <th class="center">المندوب</th>
                                <th class="center">من فرع</th>
                                @if ($t == 0)
                                    <th class="center">الى فرع</th>
                                    <th class="center">المدفوع</th>
                                    <th class="center">الباقي</th>
                                @endif
                                @if ($t == 1)
                                    <th class="center">الى مدينة</th>
                                @endif
                                <th class="center">عدد الشحنات</th>
                                <th class="center">اكواد الشحنات</th>

                                <th class="center">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trips as $key => $trip)
                                <tr>
                                    <td class="center">{{ $loop->iteration }}</td>
                                    <td class="center">{{ $trip->code ?? 'N/A' }}</td>
                                    <td class="center">
                                        @php
                                            $currentBranchId = auth()->user()->branch_id;
                                            $isFromBranch =
                                                $currentBranchId && $trip->branches_from == $currentBranchId;
                                            $isToBranch = $currentBranchId && $trip->branches_to == $currentBranchId;
                                            $isStatusLocked = $isFromBranch && $trip->status == 2;
                                            $isToBranchEligible = $isToBranch && $trip->status == 2;
                                            $isAdmin = is_null($currentBranchId);
                                        @endphp
                                        @if ($isAdmin)
                                            <form action="{{ route('admin.trips.changeStatus', $trip->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status"
                                                    class="form-select status-select status-{{ $trip->getAttribute('status') ?? 'default' }}"
                                                    onchange="this.form.submit()" data-bs-toggle="tooltip"
                                                    title="تغيير حالة الرحلة">
                                                    <option value="1"
                                                        @if ($trip->getAttribute('status') == 1) selected @endif>تحت الإجراء
                                                    </option>
                                                    <option value="2"
                                                        @if ($trip->getAttribute('status') == 2) selected @endif>مع المندوب
                                                    </option>
                                                    <option value="3"
                                                        @if ($trip->getAttribute('status') == 3) selected @endif>تم التسليم
                                                    </option>
                                                    <option value="8"
                                                        @if ($trip->getAttribute('status') == 8) selected @endif>مكتملة</option>
                                                </select>
                                            </form>
                                        @elseif ($isFromBranch)
                                            {{-- الفرع المرسل --}}
                                            <form action="{{ route('admin.trips.changeStatus', $trip->id) }}" method="POST"
                                                style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <select name="status"
                                                    class="form-select status-select status-{{ $trip->getAttribute('status') ?? 'default' }}"
                                                    @if ($isStatusLocked) disabled @endif
                                                    onchange="this.form.submit()" data-bs-toggle="tooltip"
                                                    title="تغيير حالة الرحلة">
                                                    <option value="1"
                                                        @if ($trip->getAttribute('status') == 1) selected @endif>تحت الإجراء
                                                    </option>
                                                    <option value="2"
                                                        @if ($trip->getAttribute('status') == 2) selected @endif>مع المندوب
                                                    </option>
                                                </select>
                                            </form>
                                        @elseif ($isToBranch)
                                            {{-- الفرع المستقبِل --}}
                                            @if ($trip->status == 2)
                                                {{-- إذا كانت مع المندوب، يمكن للفرع المستقبل تحديثها لـ "تم التسليم" --}}
                                                <form action="{{ route('admin.trips.changeStatus', $trip->id) }}"
                                                    method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('PATCH')
                                                    <select name="status"
                                                        class="form-select status-select status-{{ $trip->getAttribute('status') ?? 'default' }}"
                                                        onchange="this.form.submit()" data-bs-toggle="tooltip"
                                                        title="تغيير حالة الرحلة">
                                                        <option value="2"
                                                            @if ($trip->getAttribute('status') == 2) selected @endif>مع المندوب
                                                        </option>
                                                        <option value="3"
                                                            @if ($trip->getAttribute('status') == 3) selected @endif>تم التسليم
                                                        </option>
                                                    </select>
                                                </form>
                                            @else
                                                {{-- عرض الحالة فقط للفرع المستقبل --}}
                                                <span
                                                    class="status-select status-{{ $trip->getAttribute('status') ?? 'default' }}">
                                                    {{ $trip->getStatusLabelAttribute() }}
                                                </span>
                                            @endif
                                        @else
                                            {{-- عرض الحالة فقط للمستخدمين الآخرين --}}
                                            <span
                                                class="status-select status-{{ $trip->getAttribute('status') ?? 'default' }}">
                                                {{ $trip->getStatusLabelAttribute() }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="center">
                                        {{ $trip->representative ? $trip->representative->name : 'غير محدد' }}</td>
                                    <td class="center">{{ $trip->branchFrom->name ?? 'غير متوفر' }}</td>
                                    @if ($t == 0)
                                        <td class="center">{{ $trip->branchTo->name ?? 'غير متوفر' }}</td>
                                        <td class="center">{{ $trip->expense_value ?? ' -- ' }}</td>
                                        <td class="center">{{ $trip->refund_value ?? ' -- ' }}</td>
                                    @endif
                                    @if ($t == 1)
                                        <td class="center">{{ $trip->region->region_ar ?? 'غير متوفر' }}</td>
                                    @endif
                                    <td class="center">{{ $trip->shipments_count }}</td>
                                    <td class="center">
                                        @foreach ($trip->shipments as $shipment)
                                            <div>{{ $shipment->code }}</div>
                                        @endforeach
                                    </td>

                                    <td class="center">
                                        @if (request()->routeIs('admin.trips.index'))
                                            <a href="{{ route('admin.trips.edit', $trip->id) }}" class="btn btn-success"
                                                data-bs-toggle="tooltip" title="تعديل الرحلة">
                                                <i class="fa fa-edit text-white"></i>
                                            </a>
                                        @else
                                            <a href="{{ route('admin.trips.editDriver', $trip->id) }}"
                                                class="btn btn-success" data-bs-toggle="tooltip" title="تعديل الرحلة">
                                                <i class="fa fa-edit text-white"></i>
                                            </a>
                                        @endif
                                        <a class="btn btn-success" href="{{ route('admin.trips.printPdf', [$trip->id]) }}"
                                            data-bs-toggle="tooltip" title="طباعة الرحلة">
                                            <i class="fa-solid fa-print text-white"></i>
                                        </a>
                                        <a class="btn btn-primary" href="{{ route('admin.trips.qrCode', [$trip->id]) }}"
                                            data-bs-toggle="tooltip" title="عرض رمز QR">
                                            <i class="fa-solid fa-qrcode text-white"></i>
                                        </a>
                                        <form method="post" action="{{ route('admin.trips.destroy', $trip->id) }}"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-primary" data-bs-toggle="tooltip"
                                                title="حذف الرحلة"
                                                onclick="return confirm('هل أنت متأكد من حذف هذه الرحلة؟')">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </form>
                                        <a class="btn btn-info" href="{{ route('admin.trips.show', $trip->id) }}"
                                            title="تفاصيل الرحلة">
                                            <i class="fa fa-info-circle"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Trip Details Modal -->
    <div class="modal fade" id="tripDetailsModal" tabindex="-1" aria-labelledby="tripDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tripDetailsModalLabel">تفاصيل الرحلة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="tripDetailsContent"></div>
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
        function showTripDetails(tripId) {
            fetch(`/admin/trips/${tripId}/details`)
                .then(response => response.json())
                .then(data => {
                    const tripDetails = data.trip;
                    const shipments = data.shipments;
                    const tripContent = `
                        <p><strong>الكود:</strong> ${tripDetails.code ?? 'N/A'}</p>
                        <p><strong>الحالة:</strong> ${tripDetails.status_label ?? 'غير محدد'}</p>
                        <p><strong>المندوب:</strong> ${tripDetails.representative_name ?? 'غير محدد'}</p>
                        <p><strong>من فرع:</strong> ${tripDetails.branch_from_name ?? 'غير متوفر'}</p>
                        <p><strong>إلى فرع:</strong> ${tripDetails.branch_to_name ?? 'غير متوفر'}</p>
                        <p><strong>المدينة:</strong> ${tripDetails.region_name ?? 'غير متوفر'}</p>
                        <p><strong>عدد الشحنات:</strong> ${tripDetails.shipments_count ?? 0}</p>
                        <p><strong>تاريخ الإنشاء:</strong> ${tripDetails.created_at ?? 'N/A'}</p>
                        <p><strong>تاريخ التحديث:</strong> ${tripDetails.updated_at ?? 'N/A'}</p>
                    `;
                    let shipmentsTable = '';
                    if (shipments.length > 0) {
                        shipmentsTable = `
                            <h4>تفاصيل الشحنات</h4>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>كود الشحنة</th>
                                        <th>اسم العميل</th>
                                        <th>الحالة</th>
                                        <th>الوزن (كجم)</th>
                                        <th>التكلفة</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${shipments.map((shipment, index) => {
                                        let label = '';
                                        switch (shipment.status_id) {
                                            case 1: label = 'تحت الإجراء'; break;
                                            case 2: label = 'مع المندوب'; break;
                                            case 3: label = 'تم التسليم'; break;
                                            case 4: label = 'قيد الانتظار'; break;
                                            case 5: label = 'رفض الاستلام'; break;
                                            case 6: label = 'راجع مع المندوب'; break;
                                            case 7: label = 'داخل الفرع'; break;
                                            case 8: label = 'تم الاكتمال'; break;
                                            default: label = 'غير محدد';
                                        }
                                        return ` <
                            tr >
                            <
                            td > $ {
                                index + 1
                            } < /td> <
                        td > $ {
                            shipment.code ?? 'N/A'
                        } < /td> <
                        td > $ {
                            shipment.client_name ?? 'N/A'
                        } < /td> <
                        td > $ {
                            label
                        } < /td> <
                        td > $ {
                            shipment.weight ?? 'N/A'
                        } < /td> <
                        td > $ {
                            shipment.calculateTotalCost ?? 'N/A'
                        } < /td> < /
                        tr >
                            `;
                                    }).join('')}
                                </tbody>
                            </table>
                        `;
                    } else {
                        shipmentsTable = '<p>لا توجد شحنات مرتبطة بهذه الرحلة</p>';
                    }
                    document.getElementById('tripDetailsContent').innerHTML = tripContent + shipmentsTable;
                })
                .catch(error => {
                    console.error('Error fetching trip details:', error);
                    document.getElementById('tripDetailsContent').innerHTML = '<p>حدث خطأ أثناء جلب التفاصيل</p>';
                });
        }
    </script>
    <script>
        document.querySelectorAll('.dropdown-menu form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('تم تحديث الحالة بنجاح');
                            location.reload();
                        } else {
                            alert('حدث خطأ أثناء تحديث الحالة');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('حدث خطأ أثناء تحديث الحالة');
                    });
            });
        });
    </script>
@endsection
