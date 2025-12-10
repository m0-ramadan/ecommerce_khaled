@extends('Admin.layout.master')

@section('title', 'إحصائيات الطلبات')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css">
    <style>
        body {
            font-family: "Cairo", sans-serif !important;
        }

        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            border-top: 4px solid #696cff;
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 15px;
        }

        .icon-total {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .icon-revenue {
            background: #e7f5ff;
            color: #0c63e4;
        }

        .icon-average {
            background: #d4edda;
            color: #155724;
        }

        .icon-pending {
            background: #fff3cd;
            color: #856404;
        }

        .icon-delivered {
            background: #d1ecf1;
            color: #0c5460;
        }

        .icon-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .icon-today {
            background: #e9ecef;
            color: #495057;
        }

        .icon-week {
            background: #f8f9fa;
            color: #6c757d;
        }

        .stats-number {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stats-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .stats-change {
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .change-up {
            color: #198754;
        }

        .change-down {
            color: #dc3545;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 25px;
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .chart-header h6 {
            color: #696cff;
            margin-bottom: 0;
        }

        .chart-controls {
            display: flex;
            gap: 10px;
        }

        .chart-control {
            padding: 6px 15px;
            border-radius: 20px;
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 13px;
        }

        .chart-control:hover {
            background: #e9ecef;
        }

        .chart-control.active {
            background: #696cff;
            color: white;
            border-color: #696cff;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 25px;
            margin-bottom: 25px;
        }

        .table-header {
            border-bottom: 2px solid #f8f9fa;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .product-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .product-rank {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #696cff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 14px;
        }

        .rank-1 {
            background: #ffd700;
        }

        .rank-2 {
            background: #c0c0c0;
        }

        .rank-3 {
            background: #cd7f32;
        }

        .product-info {
            flex-grow: 1;
        }

        .product-name {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .product-sales {
            color: #6c757d;
            font-size: 13px;
        }

        .sales-count {
            font-weight: 700;
            color: #198754;
        }

        .empty-chart {
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }

        .empty-chart i {
            font-size: 60px;
            margin-bottom: 20px;
            color: #dee2e6;
        }

        .date-filter {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .chart-controls {
                width: 100%;
                justify-content: center;
            }

            .filter-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" bis_skin_checked="1">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.orders.index') }}">الطلبات</a>
                </li>
                <li class="breadcrumb-item active">الإحصائيات</li>
            </ol>
        </nav>

        <!-- فلترة التاريخ -->
        <div class="date-filter" bis_skin_checked="1">
            <h6 class="mb-3"><i class="fas fa-calendar-alt me-2"></i>فلترة حسب التاريخ</h6>

            <div class="filter-row" bis_skin_checked="1">
                <div class="input-group" bis_skin_checked="1">
                    <span class="input-group-text">من</span>
                    <input type="date" class="form-control" id="dateFrom"
                        value="{{ now()->subDays(30)->format('Y-m-d') }}">
                    <span class="input-group-text">إلى</span>
                    <input type="date" class="form-control" id="dateTo" value="{{ now()->format('Y-m-d') }}">
                </div>

                <select class="form-select" id="chartType">
                    <option value="daily">يومي</option>
                    <option value="weekly">أسبوعي</option>
                    <option value="monthly">شهري</option>
                </select>

                <button class="btn btn-primary" onclick="loadStatistics()">
                    <i class="fas fa-filter me-2"></i>تطبيق الفلتر
                </button>
            </div>
        </div>

        <!-- الإحصائيات الرئيسية -->
        <div class="row mb-4" bis_skin_checked="1">
            <div class="col-lg-3 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-total" bis_skin_checked="1">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="stats-number" id="totalOrders">
                        {{ number_format($stats['total_orders']) }}
                    </div>
                    <div class="stats-label">إجمالي الطلبات</div>
                    <div class="stats-change change-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>12% عن الشهر الماضي</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-revenue" bis_skin_checked="1">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="stats-number" id="totalRevenue">
                        {{ number_format($stats['total_revenue'], 2) }} ج.م
                    </div>
                    <div class="stats-label">إجمالي الإيرادات</div>
                    <div class="stats-change change-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>18% عن الشهر الماضي</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-average" bis_skin_checked="1">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stats-number" id="averageOrder">
                        {{ number_format($stats['average_order_value'], 2) }} ج.م
                    </div>
                    <div class="stats-label">متوسط قيمة الطلب</div>
                    <div class="stats-change change-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>5% عن الشهر الماضي</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 mb-4" bis_skin_checked="1">
                <div class="stats-card" bis_skin_checked="1">
                    <div class="stats-icon icon-today" bis_skin_checked="1">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stats-number" id="todayOrders">
                        {{ number_format($stats['today_orders']) }}
                    </div>
                    <div class="stats-label">طلبات اليوم</div>
                    <div class="stats-change change-up">
                        <i class="fas fa-arrow-up"></i>
                        <span>3 عن الأمس</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- المخططات -->
        <div class="row" bis_skin_checked="1">
            <!-- مخطط الطلبات حسب الحالة -->
            <div class="col-lg-6 mb-4" bis_skin_checked="1">
                <div class="chart-card" bis_skin_checked="1">
                    <div class="chart-header" bis_skin_checked="1">
                        <h6><i class="fas fa-chart-pie me-2"></i>توزيع الطلبات حسب الحالة</h6>
                        <div class="chart-controls" bis_skin_checked="1">
                            <button class="chart-control active" onclick="changeChartType('pie')">
                                دائري
                            </button>
                            <button class="chart-control" onclick="changeChartType('doughnut')">
                                حلقي
                            </button>
                        </div>
                    </div>
                    <div class="chart-container" bis_skin_checked="1">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- مخطط الإيرادات -->
            <div class="col-lg-6 mb-4" bis_skin_checked="1">
                <div class="chart-card" bis_skin_checked="1">
                    <div class="chart-header" bis_skin_checked="1">
                        <h6><i class="fas fa-chart-bar me-2"></i>الإيرادات خلال الشهر</h6>
                        <div class="chart-controls" bis_skin_checked="1">
                            <button class="chart-control active" onclick="changeRevenueChart('bar')">
                                أعمدة
                            </button>
                            <button class="chart-control" onclick="changeRevenueChart('line')">
                                خطي
                            </button>
                        </div>
                    </div>
                    <div class="chart-container" bis_skin_checked="1">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- المنتجات الأكثر مبيعاً -->
            <div class="col-lg-6 mb-4" bis_skin_checked="1">
                <div class="table-card" bis_skin_checked="1">
                    <div class="table-header" bis_skin_checked="1">
                        <h6 class="mb-0"><i class="fas fa-trophy me-2"></i>المنتجات الأكثر مبيعاً</h6>
                    </div>

                    @if ($stats['top_products']->isEmpty())
                        <div class="empty-chart" bis_skin_checked="1">
                            <i class="fas fa-box"></i>
                            <p>لا توجد بيانات عن المبيعات</p>
                        </div>
                    @else
                        @foreach ($stats['top_products'] as $index => $product)
                            <div class="product-item" bis_skin_checked="1">
                                <div class="product-rank rank-{{ $index + 1 }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="product-info" bis_skin_checked="1">
                                    <div class="product-name" bis_skin_checked="1">
                                        {{ $product->name }}
                                    </div>
                                    <div class="product-sales" bis_skin_checked="1">
                                        تم بيع <span class="sales-count">{{ $product->total_quantity }}</span> وحدة
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- إحصائيات الحالة -->
            <div class="col-lg-6 mb-4" bis_skin_checked="1">
                <div class="table-card" bis_skin_checked="1">
                    <div class="table-header" bis_skin_checked="1">
                        <h6 class="mb-0"><i class="fas fa-chart-bar me-2"></i>إحصائيات الحالات</h6>
                    </div>

                    <div class="row" bis_skin_checked="1">
                        @php
                            $statusColors = [
                                'pending' => '#ffc107',
                                'processing' => '#0d6efd',
                                'shipped' => '#0dcaf0',
                                'delivered' => '#198754',
                                'cancelled' => '#dc3545',
                                'refunded' => '#6c757d',
                            ];

                            $statusLabels = [
                                'pending' => 'قيد الانتظار',
                                'processing' => 'تحت المعالجة',
                                'shipped' => 'تم الشحن',
                                'delivered' => 'تم التسليم',
                                'cancelled' => 'ملغي',
                                'refunded' => 'مسترجع',
                            ];
                        @endphp

                        @foreach ($stats['status_counts'] as $status => $count)
                            @if (isset($statusColors[$status]))
                                <div class="col-md-6 mb-3" bis_skin_checked="1">
                                    <div class="stats-card" style="border-top-color: {{ $statusColors[$status] }};"
                                        bis_skin_checked="1">
                                        <div class="stats-icon"
                                            style="background: {{ $statusColors[$status] }}; color: white;">
                                            @switch($status)
                                                @case('pending')
                                                    <i class="fas fa-clock"></i>
                                                @break

                                                @case('processing')
                                                    <i class="fas fa-cog"></i>
                                                @break

                                                @case('shipped')
                                                    <i class="fas fa-truck"></i>
                                                @break

                                                @case('delivered')
                                                    <i class="fas fa-check-circle"></i>
                                                @break

                                                @case('cancelled')
                                                    <i class="fas fa-times-circle"></i>
                                                @break

                                                @case('refunded')
                                                    <i class="fas fa-redo"></i>
                                                @break

                                                @default
                                                    <i class="fas fa-question-circle"></i>
                                            @endswitch
                                        </div>
                                        <div class="stats-number">{{ number_format($count) }}</div>
                                        <div class="stats-label">{{ $statusLabels[$status] ?? $status }}</div>
                                        <div class="stats-change">
                                            @php
                                                $percentage =
                                                    $stats['total_orders'] > 0
                                                        ? ($count / $stats['total_orders']) * 100
                                                        : 0;
                                            @endphp
                                            <span>{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        let statusChart, revenueChart;
        let currentChartType = 'pie';
        let currentRevenueChartType = 'bar';

        $(document).ready(function() {
            // تحميل الإحصائيات الأولية
            loadCharts();

            // تحميل الإحصائيات عند تغيير التاريخ
            $('#dateFrom, #dateTo, #chartType').on('change', function() {
                loadStatistics();
            });
        });

        function loadStatistics() {
            const dateFrom = $('#dateFrom').val();
            const dateTo = $('#dateTo').val();
            const chartType = $('#chartType').val();

            // هنا يمكنك إضافة AJAX لجلب الإحصائيات المحدثة
            console.log('جلب الإحصائيات:', {
                dateFrom,
                dateTo,
                chartType
            });

            // في التطبيق الحقيقي، ستقوم بإرسال طلب AJAX وجلب البيانات
            // ثم تحديث المخططات والإحصائيات
        }

        function loadCharts() {
            // بيانات مخطط الحالة
            const statusData = {
                labels: ['قيد الانتظار', 'تحت المعالجة', 'تم الشحن', 'تم التسليم', 'ملغي'],
                datasets: [{
                    data: [
                        {{ $stats['status_counts']['pending'] ?? 0 }},
                        {{ $stats['status_counts']['processing'] ?? 0 }},
                        {{ $stats['status_counts']['shipped'] ?? 0 }},
                        {{ $stats['status_counts']['delivered'] ?? 0 }},
                        {{ $stats['status_counts']['cancelled'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#ffc107',
                        '#0d6efd',
                        '#0dcaf0',
                        '#198754',
                        '#dc3545'
                    ],
                    borderWidth: 1
                }]
            };

            // بيانات مخطط الإيرادات (بيانات وهمية للعرض)
            const revenueData = {
                labels: ['1', '5', '10', '15', '20', '25', '30'],
                datasets: [{
                    label: 'الإيرادات (ج.م)',
                    data: [1200, 1900, 3000, 2500, 2200, 3000, 4000],
                    backgroundColor: 'rgba(105, 108, 255, 0.2)',
                    borderColor: 'rgba(105, 108, 255, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            };

            // إنشاء مخطط الحالة
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(statusCtx, {
                type: currentChartType,
                data: statusData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            rtl: true,
                            labels: {
                                font: {
                                    family: 'Cairo',
                                    size: 12
                                },
                                padding: 20
                            }
                        },
                        tooltip: {
                            rtl: true,
                            bodyFont: {
                                family: 'Cairo'
                            },
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.formattedValue + ' طلب';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // إنشاء مخطط الإيرادات
            const revenueCtx = document.getElementById('revenueChart').getContext('2d');
            revenueChart = new Chart(revenueCtx, {
                type: currentRevenueChartType,
                data: revenueData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            rtl: true,
                            bodyFont: {
                                family: 'Cairo'
                            },
                            callbacks: {
                                label: function(context) {
                                    return context.formattedValue + ' ج.م';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value + ' ج.م';
                                }
                            }
                        }
                    }
                }
            });
        }

        function changeChartType(type) {
            currentChartType = type;
            statusChart.destroy();
            loadCharts();

            // تحديث أزرار التحكم
            $('.chart-control').removeClass('active');
            $(`.chart-control[onclick="changeChartType('${type}')"]`).addClass('active');
        }

        function changeRevenueChart(type) {
            currentRevenueChartType = type;
            revenueChart.destroy();
            loadCharts();

            // تحديث أزرار التحكم
            $('.chart-control').removeClass('active');
            $(`.chart-control[onclick="changeRevenueChart('${type}')"]`).addClass('active');
        }
    </script>
@endsection
