@extends('admin.app')
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid dashboard-default-sec" dir="rtl">
            <div class="row">
                <div class="col-xl-12 box-col-12 des-xl-100">

                    <!-- Table for Unique Visitors Per Month -->
                    <div class="card">
                        <div class="card-header">
                            <h5>الزوار   - لكل شهر</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>الشهر</th>
                                    <th>الدولة</th>
                                    <th>الزوار  </th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalUniqueVisitors = 0; @endphp
                                @foreach ($visitorsPerMonth as $visitor)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($visitor->month)->locale('ar')->translatedFormat('F Y') }}</td>
                                        <td>{{ $visitor->country }}</td>
                                        <td>{{ $visitor->unique_visitors }}</td>
                                    </tr>
                                    @php $totalUniqueVisitors += $visitor->unique_visitors; @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2">إجمالي الزوار  </th>
                                    <th>{{ $totalUniqueVisitors }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Table for Unique Visitors in the Last Week -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>الزوار   - الأسبوع الماضي</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الدولة</th>
                                    <th>الزوار  </th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalUniqueVisitorsWeek = 0; @endphp
                                @foreach ($visitorsLastWeek as $visitor)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($visitor->date)->format('Y-m-d') }}</td>
                                        <td>{{ $visitor->country }}</td>
                                        <td>{{ $visitor->unique_visitors }}</td>
                                    </tr>
                                    @php $totalUniqueVisitorsWeek += $visitor->unique_visitors; @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2">إجمالي الزوار   (أسبوع)</th>
                                    <th>{{ $totalUniqueVisitorsWeek }}</th>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Table for Unique Visitors in the Last Month -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>الزوار   - الشهر الماضي</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>التاريخ</th>
                                    <th>الدولة</th>
                                    <th>الزوار  </th>
                                </tr>
                                </thead>
                                <tbody>
                                @php $totalUniqueVisitorsMonth = 0; @endphp
                                @foreach ($visitorsLastMonth as $visitor)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($visitor->date)->format('Y-m-d') }}</td>
                                        <td>{{ $visitor->country }}</td>
                                        <td>{{ $visitor->unique_visitors }}</td>
                                    </tr>
                                    @php $totalUniqueVisitorsMonth += $visitor->unique_visitors; @endphp
                                @endforeach
                                </tbody>
                                <tfoot>
                                <tr>
                                    <th colspan="2">إجمالي الزوار   (شهر)</th>
                                    <th>{{ $totalUniqueVisitorsMonth }}</th>
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
