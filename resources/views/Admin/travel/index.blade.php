@extends('Admin.layout.master')

@php
    $currentRoute = request()->path(); // مثل admin/travel/trips
@endphp

@section('title')
    @if ($currentRoute == 'admin/travel/trips')
        قائمة الرحلات
    @elseif ($currentRoute == 'admin/travel/trips/pending')
        الرحلات في انتظار التأكيد
    @elseif ($currentRoute == 'admin/travel/trips/confirmed')
        الرحلات المؤكدة
    @endif
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                @if ($currentRoute == 'admin/travel/trips')
                    <x-breadcrumb :items="[
                        'لوحة التحكم' => '/admin',
                        'قائمة الرحلات' => '',
                    ]" />
                @elseif ($currentRoute == 'admin/travel/trips/pending')
                    <x-breadcrumb :items="[
                        'لوحة التحكم' => '/admin',
                        'قائمة الرحلات' => '/admin/travel/trips',
                        'الرحلات في انتظار التأكيد' => '',
                    ]" />
                @elseif ($currentRoute == 'admin/travel/trips/confirmed')
                    <x-breadcrumb :items="[
                        'لوحة التحكم' => '/admin',
                        'قائمة الرحلات' => '/admin/travel/trips',
                        'الرحلات المؤكدة' => '',
                    ]" />
                @endif
            </div>
            <div class="card-header">
                <a class="btn btn-success" href="{{ route('admin.travels.create') }}">اضافة رحلة</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>رقم الرحلة</th>
                                <th>الوجهة</th>
                                <th>نوع المركبة</th>
                                <th>من المحطة</th>
                                <th>إلى المحطة</th>
                                <th>عدد الركاب</th>
                                <th>إجمالي المبلغ</th>
                                <th>حالة الدفع</th>
                                <th>تاريخ الرحلة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($trips as $key => $trip)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $trip->trip_number }}</td>
                                    <td>{{ $trip->destination }}</td>
                                    <td>{{ $trip->vehicle_type }}</td>
                                    <td>{{ $trip->from_station }}</td>
                                    <td>{{ $trip->to_station }}</td>
                                    <td>{{ $trip->tripPassengers->count() }}</td>
                                    <td>
                                        {{ $trip->tripPassengers->sum('total_amount') }}
                                    </td>
                                    <td>
                                        <span
                                            class="badge {{ $trip->tripPassengers->first()->payment_status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                                            {{ $trip->tripPassengers->first()->payment_status ?? 'غير مدفوع' }}
                                        </span>
                                    </td>
                                    <td>{{ date('Y-m-d', strtotime($trip->created_at)) }}</td>
                                    <td class="d-flex gap-1">
                                        <!-- زر عرض التفاصيل -->
                                        <a class="btn btn-success"
                                            href="{{ route('admin.travel.trips.show', $trip->id) }}">
                                            <i class="fa fa-eye text-white"></i>
                                        </a>

                                        <!-- زر تعديل -->
                                        <a class="btn btn-success"
                                            href="{{ route('admin.travel.trips.edit', $trip->id) }}">
                                            <i class="fa fa-edit text-white"></i>
                                        </a>

                                        <!-- زر حذف -->
                                        <form method="post" action="{{ route('admin.travel.trips.destroy', $trip->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-trash-o"></i>
                                            </button>
                                        </form>

                                        <!-- زر طباعة التذكرة -->
                                        <a class="btn btn-success"
                                            href="{{ route('admin.travel.trips.printTicket', $trip->id) }}">
                                            <i class="fa-solid fa-print text-white"></i>
                                        </a>

                                        <!-- زر تأكيد الرحلة -->
                                        @if ($currentRoute == 'admin/travel/trips/pending')
                                            <form action="{{ route('admin.travel.trips.confirm', $trip->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" title="تأكيد الرحلة">
                                                    <i class="fa fa-check text-white"></i>
                                                </button>
                                            </form>
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
            $('#example').DataTable({
                pagingType: 'full_numbers',
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                        }
                    },
                    'colvis'
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });
        });
    </script>
@endsection
