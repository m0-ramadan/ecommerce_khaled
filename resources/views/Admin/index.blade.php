@extends('Admin.layout.master')

@section('title', 'الرئيسية')

@section('css')
    <link rel="stylesheet" href="{{ asset('dashboard/assets/vendor/css/pages/app-logistics-dashboard.css') }}" />
@endsection

@section('content')
    @php
        $monthlyOrdersCount = \App\Models\Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $totalOrdersCount = \App\Models\Order::count();
        $cancelledOrdersCount = \App\Models\Order::where('status', 'cancelled')->count();

        $totalUsersCount = \App\Models\User::count();
        $monthlyUsersCount = \App\Models\User::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $adminsCount = \App\Models\Admin::count();

        $totalVisitorsCount = \App\Models\Visitor::count();
        $monthlyVisitorsCount = \App\Models\Visitor::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $totalProductsCount = \App\Models\Product::count();

        $monthlyProductsCount = \App\Models\Product::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $totalCategoriesCount = \App\Models\Category::count();
    @endphp

    <div class="container-xxl flex-grow-1 container-p-y tala-dashboard-container">
        <!-- Greeting & Breadcrumb -->
        <div class="tala-header-block">
            <div class="tala-breadcrumb-label">الرئيسية</div>
            <h4 class="tala-greeting-title">صباح الخير، {{ auth()->user()->name ?? 'المدير' }} 👋</h4>
            <p class="tala-greeting-subtitle">مرحباً بك في لوحة تحكم {{ env('APP_NAME', 'تالا الجزيرة') }}</p>
        </div>

        <!-- Primary Dashboard Row: 13 KPI Cards (Right 8 cols) + Profit & Payment Methods (Left 4 cols) -->
        <div class="row g-4 align-items-start mb-4">
            <!-- Right Column (in RTL): 13 KPI Cards Grid -->
            <div class="col-lg-8">
                <div class="tala-kpi-grid">
                    {{-- 1. الطلبات في الشهر --}}
                    <div class="tala-kpi-card kpi-amber">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $monthlyOrdersCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">الطلبات في الشهر</p>
                    </div>

                    {{-- 2. إجمالي الطلبات --}}
                    <div class="tala-kpi-card kpi-purple">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $totalOrdersCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">إجمالي الطلبات</p>
                    </div>

                    {{-- 3. الطلبات الملغية --}}
                    <div class="tala-kpi-card kpi-red">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $cancelledOrdersCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">الطلبات الملغية</p>
                    </div>

                    {{-- 4. عدد العملاء --}}
                    <div class="tala-kpi-card kpi-purple">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $totalUsersCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">عدد العملاء</p>
                    </div>

                    {{-- 5. العملاء في الشهر --}}
                    <div class="tala-kpi-card kpi-slate">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $monthlyUsersCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">العملاء في الشهر</p>
                    </div>

                    {{-- 6. عدد الموظفين --}}
                    <div class="tala-kpi-card kpi-green">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $adminsCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">عدد الموظفين</p>
                    </div>

                    {{-- 7. عدد الزيارات --}}
                    <div class="tala-kpi-card kpi-cyan">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $totalVisitorsCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">عدد الزيارات</p>
                    </div>

                    {{-- 8. الزيارات في الشهر --}}
                    <div class="tala-kpi-card kpi-purple">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $monthlyVisitorsCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">الزيارات في الشهر</p>
                    </div>

                    {{-- 9. عدد المنتجات --}}
                    <div class="tala-kpi-card kpi-amber">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $totalProductsCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">عدد المنتجات</p>
                    </div>

                    {{-- 10. المنتجات المضافة هذا الشهر --}}
                    <div class="tala-kpi-card kpi-green">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l2-1.14"/><path d="m7.5 4.27 9 5.15"/><polyline points="3.29 7 12 12 20.71 7"/><line x1="12" x2="12" y1="22" y2="12"/><path d="M16 18h6"/><path d="M19 15v6"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $monthlyProductsCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">المنتجات المضافة هذا الشهر</p>
                    </div>

                    {{-- 11. عدد الأقسام --}}
                    <div class="tala-kpi-card kpi-slate">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">{{ $totalCategoriesCount }}</h4>
                        </div>
                        <p class="tala-kpi-label">عدد الأقسام</p>
                    </div>

                    {{-- 12. إجمالي الإيرادات --}}
                    <div class="tala-kpi-card kpi-purple">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 7c0-5.333-8-5.333-8 0"/><path d="M10 7v14"/><path d="M6 21h12"/><path d="M6 13h10"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">EGP 6034.71</h4>
                        </div>
                        <p class="tala-kpi-label">إجمالي الإيرادات</p>
                    </div>

                    {{-- 13. أرباح المنصة --}}
                    <div class="tala-kpi-card kpi-amber tala-kpi-card--profit">
                        <div class="tala-kpi-top">
                            <span class="tala-kpi-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
                            </span>
                            <h4 class="tala-kpi-value">EGP 1206.95</h4>
                        </div>
                        <p class="tala-kpi-label">أرباح المنصة</p>
                    </div>
                </div>
            </div>

            <!-- Left Column (in RTL): Profit Last Month + Payment Methods -->
            <div class="col-lg-4 d-flex flex-column gap-4">
                {{-- Card 1: أرباح المنصة اخر شهر --}}
                <div class="tala-analytics-card tala-profit-card">
                    <svg class="tala-profit-wave" viewBox="0 0 400 120" preserveAspectRatio="none" aria-hidden="true">
                        <defs>
                            <linearGradient id="talaWaveGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#dbe6f6" stop-opacity="0.75" />
                                <stop offset="100%" stop-color="#f3f6fb" stop-opacity="0.95" />
                            </linearGradient>
                        </defs>
                        <path d="M0,78 C95,28 195,95 295,48 C345,25 378,34 400,45 L400,120 L0,120 Z"
                            fill="url(#talaWaveGrad)" />
                    </svg>

                    <div class="tala-profit-content">
                        <div class="tala-profit-header">
                            <span class="tala-analytics-icon">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
                            </span>
                            <div class="text-start">
                                <div class="tala-profit-amount">EGP 0</div>
                                <div class="tala-profit-subtitle">أرباح المنصة اخر شهر</div>
                            </div>
                        </div>

                        <div class="tala-gauge-wrap">
                            <svg class="tala-gauge-svg" viewBox="0 0 180 104" aria-label="نسبة نمو أرباح المنصة">
                                <path d="M 22 86 A 68 68 0 0 1 158 86" fill="none" stroke="#cbd7ea"
                                    stroke-width="11" stroke-linecap="round" />
                                <circle cx="22" cy="86" r="5.5" fill="#1e3a5f" stroke="#ffffff" stroke-width="1.5" />
                                <text x="90" y="78" text-anchor="middle" fill="#0f172a" font-size="22"
                                    font-weight="700" font-family="Cairo, sans-serif">0%</text>
                            </svg>
                        </div>

                        <div class="tala-profit-footer">
                            زيادة أرباح المنصة بـ 0% عن الشهر الماضي
                        </div>
                    </div>
                </div>

                {{-- Card 2: طرق الدفع --}}
                <div class="tala-analytics-card">
                    <div class="tala-payment-header">
                        <h5 class="tala-payment-title">طرق الدفع</h5>
                        <span class="tala-analytics-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                        </span>
                    </div>

                    <div class="tala-payment-labels">
                        <div class="tala-payment-label-item" style="width: 27.27%;">المحفظة</div>
                        <div class="tala-payment-label-item" style="width: 63.64%;">كاش</div>
                        <div class="tala-payment-label-item" style="width: 9.09%;">بطاقة بنكية</div>
                    </div>

                    <div class="tala-payment-bar" role="progressbar" aria-label="توزيع طرق الدفع">
                        <div class="tala-payment-seg tala-seg-wallet" style="width: 27.27%;">27.27%</div>
                        <div class="tala-payment-seg tala-seg-cash" style="width: 63.64%;">63.64%</div>
                        <div class="tala-payment-seg tala-seg-card" style="width: 9.09%;">9.09%</div>
                    </div>

                    <div class="tala-payment-list">
                        <div class="tala-payment-row">
                            <div class="tala-payment-row-right">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M19 7V4a1 1 0 0 0-1-1H5a2 2 0 0 0 0 4h15a1 1 0 0 1 1 1v4h-3a2 2 0 0 0 0 4h3a1 1 0 0 0 1-1v-2a1 1 0 0 0-1-1"/><path d="M3 5v14a2 2 0 0 0 2 2h15a1 1 0 0 0 1-1v-4"/></svg>
                                <span>المحفظة</span>
                            </div>
                            <span class="tala-payment-row-pct">27.27%</span>
                        </div>

                        <div class="tala-payment-row">
                            <div class="tala-payment-row-right">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="12" x="2" y="6" rx="2"/><circle cx="12" cy="12" r="2"/><path d="M6 12h.01M18 12h.01"/></svg>
                                <span>كاش</span>
                            </div>
                            <span class="tala-payment-row-pct">63.64%</span>
                        </div>

                        <div class="tala-payment-row">
                            <div class="tala-payment-row-right">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                                <span>بطاقة بنكية</span>
                            </div>
                            <span class="tala-payment-row-pct">9.09%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Secondary Analytics Section (Below the Fold) -->
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold" id="visitors_data">إحصائيات الزيارات حسب الدولة</h5>
                        <div style="min-width: 140px;">
                            <select name="visitors_year" id="visitors_year" class="form-select select2">
                                <option value="">اختر السنة</option>
                                <option value="{{ now()->year }}" selected>{{ now()->year }}</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="visitorsAreaChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="mb-0 fw-bold">أكثر العملاء طلباً وتقييماً</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless border-top mb-0">
                            <thead class="border-bottom">
                                <tr>
                                    <th>العميل</th>
                                    <th class="text-end">عدد الطلبات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topCustomers as $row)
                                    @php
                                        $custName = $row->user?->name ?? 'بدون اسم';
                                        $custImg = $row->user?->image ? get_user_image($row->user->image) : null;
                                    @endphp
                                    <tr>
                                        <td class="py-2">
                                            <div class="d-flex align-items-center gap-2">
                                                @if ($custImg)
                                                    <div class="avatar avatar-sm">
                                                        <img src="{{ $custImg }}" alt="{{ $custName }}"
                                                            class="rounded-circle"
                                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';" />
                                                        <span class="tala-customer-initial" style="display:none;">
                                                            {{ mb_substr($custName, 0, 1) }}
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="tala-customer-initial">
                                                        {{ mb_substr($custName, 0, 1) }}
                                                    </span>
                                                @endif
                                                <span class="fw-medium">{{ $custName }}</span>
                                            </div>
                                        </td>
                                        <td class="text-end py-2">
                                            <span class="fw-semibold">{{ $row->orders_count }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center text-muted py-4">لا توجد بيانات عملاء حالياً</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold" id="sales_data">إحصائيات الطلبات {{ now()->year }}</h5>
                        <div style="min-width: 140px;">
                            <select name="sales_year" id="year" class="form-select select2 year">
                                <option value="">اختر السنة</option>
                                <option value="{{ now()->year }}" selected>{{ now()->year }}</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="lineAreaChart"></div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold" id="all_data">الإحصائيات لسنة {{ now()->year }}</h5>
                        <div style="min-width: 140px;">
                            <select name="data_year" id="data_year" class="form-select select2 year">
                                <option value="">اختر السنة</option>
                                <option value="{{ now()->year }}" selected>{{ now()->year }}</option>
                                <option value="2025">2025</option>
                                <option value="2024">2024</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="lineAreaChart1"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function loadVisitorsChart(year = null) {
            $.ajax({
                url: "{{ route('admin.visitors.chart') }}",
                data: { year: year },
                success: function(response) {
                    var chartEl = document.querySelector("#visitorsAreaChart");
                    if (!chartEl) return;
                    chartEl.innerHTML = '';

                    var chartOptions = {
                        series: [{
                            name: "عدد الزيارات",
                            data: response.count || []
                        }],
                        chart: {
                            height: 320,
                            type: "area",
                            toolbar: false,
                            fontFamily: "Cairo, sans-serif"
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: "smooth", width: 2.5 },
                        grid: { padding: { bottom: 18 } },
                        xaxis: { categories: response.countries || [] },
                        colors: ['#1e3a5f'],
                        fill: {
                            type: "gradient",
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.35,
                                opacityTo: 0.05,
                                stops: [0, 90, 100]
                            }
                        }
                    };

                    var chart = new ApexCharts(chartEl, chartOptions);
                    chart.render();
                }
            });
        }

        $(function() {
            loadVisitorsChart();

            $('#visitors_year').on('change', function() {
                loadVisitorsChart($(this).val());
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            function loadOrderStatsChart(year) {
                fetch('/orders/stats/' + encodeURIComponent(year))
                    .then(function(res) { return res.text(); })
                    .then(function(text) {
                        if (!text) return;
                        var data;
                        try {
                            data = JSON.parse(text);
                        } catch (e) {
                            return;
                        }
                        if (!Array.isArray(data)) return;

                        var chartDiv = document.querySelector('#lineAreaChart');
                        if (!chartDiv) return;
                        chartDiv.innerHTML = '';

                        var chart = new ApexCharts(chartDiv, {
                            chart: { height: 320, type: 'area', toolbar: false, fontFamily: 'Cairo, sans-serif' },
                            grid: { padding: { bottom: 18 } },
                            colors: ['#1e3a5f'],
                            series: [{ name: 'عدد الطلبات', data: data.map(function(i) { return i.total; }) }],
                            xaxis: { categories: data.map(function(i) { return i.month; }) }
                        });
                        chart.render();
                    })
                    .catch(function() {});
            }

            var yearSelect = document.querySelector('#year');
            if (yearSelect) {
                yearSelect.addEventListener('change', function() {
                    if (this.value) {
                        document.getElementById('sales_data').textContent = 'إحصائيات الطلبات ' + this.value;
                        loadOrderStatsChart(this.value);
                    }
                });
                loadOrderStatsChart(yearSelect.value || '{{ now()->year }}');
            }
        });
    </script>
@endsection
