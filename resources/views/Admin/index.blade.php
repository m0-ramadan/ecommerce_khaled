@extends('Admin.layout.master')

@section('title', 'الرئيسية')

@section('css')
@endsection

@section('content')
<div class="row">
    <div class="col-xl-12 box-col-12 des-xl-100">
        <div class="row">

            <!-- عدد المستخدمين -->
            <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                <a href="#">
                    <div class="card income-card card-primary">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="user"></i>
                            </div>
                            <h5>{{ \App\Models\User::count() }}</h5>
                            <p style="font-family: 'Cairo', sans-serif;">عدد المستخدمين</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- عدد الزوار -->
            <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                <a href="#">
                    <div class="card income-card card-primary">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="user-check"></i>
                            </div>
                            <h5>{{ \App\Models\Visitor::count() }}</h5>
                            <p style="font-family: 'Cairo', sans-serif;">عدد الزوار</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- عدد المنتجات -->
            <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                <a href="#">
                    <div class="card income-card card-primary">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="package"></i>
                            </div>
                            <h5>{{ \App\Models\Product::count() }}</h5>
                            <p style="font-family: 'Cairo', sans-serif;">عدد المنتجات</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- عدد الأقسام -->
            <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                <a href="#">
                    <div class="card income-card card-primary">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="grid"></i>
                            </div>
                            <h5>{{ \App\Models\Category::count() }}</h5>
                            <p style="font-family: 'Cairo', sans-serif;">عدد الأقسام</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- عدد الطلبات -->
            <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                <a href="#">
                    <div class="card income-card card-primary">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="shopping-cart"></i>
                            </div>
                            <h5>5</h5>
                            <p style="font-family: 'Cairo', sans-serif;">عدد الطلبات</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

{{-- Charts --}}
<div class="row mt-4">

    <!-- زيارات حسب الدول -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5>الزيارات حسب الدول</h5>
            </div>
            <div class="card-body">
                <div id="visitsByCountry"></div>
            </div>
        </div>
    </div>

    <!-- الطلبات خلال آخر 10 أيام -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header">
                <h5>الطلبات خلال آخر 10 أيام</h5>
            </div>
            <div class="card-body">
                <div id="ordersChart"></div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // بيانات الدول
    const visitsLabels = {!! json_encode(array_keys($countriesData)) !!};
    const visitsData = {!! json_encode(array_values($countriesData)) !!};

    // بيانات الطلبات
    const ordersLabels = {!! json_encode($visitsLabels) !!};
    const ordersData = {!! json_encode($visitsData) !!};

    // شارت زيارات حسب الدول
    var visitsChart = new ApexCharts(document.querySelector("#visitsByCountry"), {
        chart: {
            type: 'bar',
            height: 350
        },
        series: [{
            name: 'الزيارات',
            data: visitsData
        }],
        xaxis: {
            categories: visitsLabels
        }
    });
    visitsChart.render();

    // شارت الطلبات آخر 10 أيام
    var ordersChart = new ApexCharts(document.querySelector("#ordersChart"), {
        chart: {
            type: 'line',
            height: 350
        },
        series: [{
            name: 'الطلبات',
            data: ordersData
        }],
        xaxis: {
            categories: ordersLabels
        }
    });
    ordersChart.render();
});
</script>
@endsection
