@extends('Admin.layout.master')

@section('title', 'الرئيسية')

@section('css')
 <!-- External CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    
    <!-- Local CSS -->
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/color-1.css') }}" media="screen">
    <link rel="stylesheet" href="{{ asset('dashboard/assets/css/responsive.css') }}">
    
    <style>
        .modal {
            position: fixed !important;
            top: 0px !important;
            left: 0 !important;
        }
        /* Ensure charts container exists before rendering */
        .chart-container {
            min-height: 350px;
            position: relative;
        }
    </style>
<style>
    .dashboard-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .round-box {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        background: rgba(255,255,255,0.2);
    }
    .card-body h5 {
        font-size: 1.8rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .card-body p {
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    .chart-container {
        position: relative;
        height: 350px;
        direction: ltr; /* Charts work better with LTR */
    }
    @media (max-width: 768px) {
        .chart-container {
            height: 300px;
        }
        .card-body h5 {
            font-size: 1.5rem;
        }
    }
    @media (max-width: 576px) {
        .chart-container {
            height: 250px;
        }
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="row">

            <!-- عدد المستخدمين -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card income-card card-primary dashboard-card">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="user"></i>
                            </div>
                            <h5>{{ \App\Models\User::count() ?? 0 }}</h5>
                            <p style="font-family: 'Cairo', sans-serif;">عدد المستخدمين</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- عدد الزوار -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card income-card card-primary dashboard-card">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="user-check"></i>
                            </div>
                            <h5>{{ \App\Models\Visitor::count() ?? 0 }}</h5>
                            <p style="font-family: 'Cairo', sans-serif;">عدد الزوار</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- عدد المنتجات -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card income-card card-primary dashboard-card">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="package"></i>
                            </div>
                            <h5>{{ \App\Models\Product::count() ?? 0 }}</h5>
                            <p style="font-family: 'Cairo', sans-serif;">عدد المنتجات</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- عدد الطلبات -->
            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 mb-4">
                <a href="#" class="text-decoration-none">
                    <div class="card income-card card-primary dashboard-card">
                        <div class="card-body text-center">
                            <div class="round-box">
                                <i data-feather="shopping-cart"></i>
                            </div>
                            <h5>4</h5>
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
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 style="font-family: 'Cairo', sans-serif;">الدول الأكثر زيارة</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <div id="visitsByCountry"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- الزيارات خلال آخر 10 أيام -->
    <div class="col-xl-6 col-lg-12 mb-4">
        <div class="card dashboard-card">
            <div class="card-header">
                <h5 style="font-family: 'Cairo', sans-serif;">الزيارات خلال آخر 10 أيام</h5>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <div id="visitsChart"></div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <!-- JavaScript Libraries - FIXED ORDER -->
    <script src="{{ asset('dashboard/assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/bootstrap/bootstrap.min.js') }}"></script>
    
    <!-- Feather Icons -->
    <script src="{{ asset('dashboard/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    
    <!-- Sidebar Menu - Load after jQuery -->
    <script src="{{ asset('dashboard/assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('dashboard/assets/js/config.js') }}"></script>
    
    <!-- Theme Script -->
    <script src="{{ asset('dashboard/assets/js/script.js') }}"></script>

    <!-- ApexCharts - Load only once -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Feather Icons
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // البيانات من الـ Controller
    const visitsLabels = @json($visitsLabels);
    const visitsData = @json($visitsData);
    const countriesData = @json($countriesData);

    // استخراج البيانات للدول
    const countryNames = Object.keys(countriesData);
    const countryVisits = Object.values(countriesData);

    // شارت الدول الأكثر زيارة
    var visitsChartOptions = {
        chart: {
            type: 'bar',
            height: '100%',
            fontFamily: 'Cairo, sans-serif',
            toolbar: {
                show: true
            }
        },
        series: [{
            name: 'عدد الزيارات',
            data: countryVisits
        }],
        xaxis: {
            categories: countryNames,
            labels: {
                style: {
                    fontFamily: 'Cairo, sans-serif',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    fontFamily: 'Cairo, sans-serif'
                }
            }
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded',
                borderRadius: 5
            },
        },
        dataLabels: {
            enabled: false
        },
        colors: ['#007bff'],
        grid: {
            borderColor: '#f1f1f1',
        },
        tooltip: {
            style: {
                fontFamily: 'Cairo, sans-serif'
            }
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 300
                },
                plotOptions: {
                    bar: {
                        columnWidth: '60%'
                    }
                }
            }
        }]
    };

    var visitsChart = new ApexCharts(document.querySelector("#visitsByCountry"), visitsChartOptions);
    visitsChart.render();

    // شارت الزيارات آخر 10 أيام
    var dailyVisitsChartOptions = {
        chart: {
            type: 'line',
            height: '100%',
            fontFamily: 'Cairo, sans-serif',
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            }
        },
        series: [{
            name: 'عدد الزيارات',
            data: visitsData
        }],
        xaxis: {
            categories: visitsLabels,
            labels: {
                style: {
                    fontFamily: 'Cairo, sans-serif'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    fontFamily: 'Cairo, sans-serif'
                }
            }
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        colors: ['#28a745'],
        markers: {
            size: 5,
            hover: {
                size: 7
            }
        },
        grid: {
            borderColor: '#f1f1f1',
        },
        tooltip: {
            style: {
                fontFamily: 'Cairo, sans-serif'
            }
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 300
                }
            }
        }]
    };

    var dailyVisitsChart = new ApexCharts(document.querySelector("#visitsChart"), dailyVisitsChartOptions);
    dailyVisitsChart.render();

    // Handle window resize
    window.addEventListener('resize', function() {
        visitsChart.updateOptions(visitsChartOptions);
        dailyVisitsChart.updateOptions(dailyVisitsChartOptions);
    });
});
</script>
@endsection