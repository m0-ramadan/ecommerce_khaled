@extends('admin.app')
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid dashboard-default-sec" dir="rtl">
            <div class="row">
                <div class="col-xl-12 box-col-12 des-xl-100">

                    <!-- Table for Visits Today -->
                    <div class="card">
                        <div class="card-header">
                            <h5>الزيارات - اليوم</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الدولة</th>
                                    <th>عدد الزيارات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalVisitsToday = 0; @endphp
                                @foreach ($visitsToday as $visit)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($visit->date)->format('Y-m-d') }}</td>
                                        <td>{{ $visit->country }}</td>
                                        <td>{{ $visit->total_visits }}</td>
                                    </tr>
                                    @php $totalVisitsToday += $visit->total_visits; @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2">إجمالي الزيارات (اليوم)</th>
                                    <th>{{ $totalVisitsToday }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Table for Visits in the Last Week -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>الزيارات - الأسبوع الماضي</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الدولة</th>
                                    <th>عدد الزيارات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalVisitsWeek = 0; @endphp
                                @foreach ($visitsLastWeek as $visit)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($visit->date)->format('Y-m-d') }}</td>
                                        <td>{{ $visit->country }}</td>
                                        <td>{{ $visit->total_visits }}</td>
                                    </tr>
                                    @php $totalVisitsWeek += $visit->total_visits; @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2">إجمالي الزيارات (أسبوع)</th>
                                    <th>{{ $totalVisitsWeek }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Table for Visits in the Last Month -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>الزيارات - الشهر الماضي</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الدولة</th>
                                    <th>عدد الزيارات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalVisitsMonth = 0; @endphp
                                @foreach ($visitsLastMonth as $visit)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($visit->date)->format('Y-m-d') }}</td>
                                        <td>{{ $visit->country }}</td>
                                        <td>{{ $visit->total_visits }}</td>
                                    </tr>
                                    @php $totalVisitsMonth += $visit->total_visits; @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2">إجمالي الزيارات (شهر)</th>
                                    <th>{{ $totalVisitsMonth }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Table for Visits Per Month -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>الزيارات - لكل شهر</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>الشهر</th>
                                    <th>الدولة</th>
                                    <th>عدد الزيارات</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalVisitsYear = 0; @endphp
                                @foreach ($visitsPerMonth as $visit)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($visit->month)->locale('ar')->translatedFormat('F Y') }}</td>
                                        <td>{{ $visit->country }}</td>
                                        <td>{{ $visit->total_visits }}</td>
                                    </tr>
                                    @php $totalVisitsYear += $visit->total_visits; @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2">إجمالي الزيارات (السنة)</th>
                                    <th>{{ $totalVisitsYear }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
