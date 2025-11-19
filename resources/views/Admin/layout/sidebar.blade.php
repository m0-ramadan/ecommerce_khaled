@php
    use App\Models\Admin;
@endphp

<header class="main-nav">
    <div class="text-center">
        <img class="img-90 rounded-circle" src="{{ asset('assets/images/user/avatar.jpg') }}" alt="Admin Avatar">
        <h6 class="mt-3 f-14 f-w-600">{{ Auth::guard('admin')->user()->name ?? 'مدير النظام' }}</h6>
    </div>

    <nav>
        <div class="main-navbar">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>

            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar" style="height:600px">
                    <li class="back-btn">
                        <div class="mobile-back text-end">
                            <span>رجوع</span>
                            <i class="fa fa-angle-right ps-2" aria-hidden="true"></i>
                        </div>
                    </li>

                    <!-- لوحة التحكم الرئيسية -->
                    <li class="sidebar-main-title">
                        <div>
                            <h6 class="lan-1" style="font-family: 'Cairo', sans-serif;">الرئيسية</h6>
                        </div>
                    </li>
                    <li>
                        <a class="nav-link" href="#">
                            <i data-feather="home"></i>
                            <span style="font-family: 'Cairo', sans-serif;">لوحة التحكم</span>
                        </a>
                    </li>

                    <!-- إعدادات عامة -->
                    <li class="sidebar-main-title mt-4">
                        <div>
                            <h6 class="lan-1" style="font-family: 'Cairo', sans-serif;">الإعدادات العامة</h6>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="settings"></i>
                            <span style="font-family: 'Cairo', sans-serif;">إعدادات عامة</span>
                            <div class="according-menu"><i class="fa fa-angle-down"></i></div>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#">الصفحات الأساسية</a></li>
                            <li><a href="#">إعدادات الموقع</a></li>
                            <li><a href="#">بيانات التواصل</a></li>
                            <li><a href="#">الدول</a></li>
                            <li><a href="#">المدن والمناطق</a></li>
                            <li><a href="#">السلايدر الرئيسي</a></li>
                            <li><a href="#">شركاء النجاح</a></li>
                            <li><a href="#">آراء العملاء</a></li>
                            <li><a href="#">الأسئلة الشائعة</a></li>
                            <li><a href="#">من نحن</a></li>
                            <li><a href="#">سياسة الخصوصية</a></li>
                            <li><a href="#">الشروط والأحكام</a></li>
                        </ul>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="truck"></i>
                            <span style="font-family: 'Cairo', sans-serif;">إعدادات الشحن</span>
                            <div class="according-menu"><i class="fa fa-angle-down"></i></div>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#">شركات الشحن</a></li>
                            <li><a href="#">تكاليف الشحن حسب المنطول/الوزن</a></li>
                            <li><a href="#">المناطق المحظورة</a></li>
                            <li><a href="#">أسعار تحويل العملات</a></li>
                            <li><a href="#">طرق الدفع</a></li>
                            <li><a href="#">إعدادات الضرائب</a></li>
                        </ul>
                    </li>

                    <!-- مستخدمين النظام -->
                    <li class="sidebar-main-title mt-4">
                        <div>
                            <h6 class="lan-1" style="font-family: 'Cairo', sans-serif;">مستخدمي النظام</h6>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="users"></i>
                            <span style="font-family: 'Cairo', sans-serif;">مستخدمين النظام</span>
                            <div class="according-menu"><i class="fa fa-angle-down"></i></div>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#">الإداريين (أدمن)</a></li>
                            <li><a href="#">المندوبين</a></li>
                            <li><a href="#">السائقين</a></li>
                            <li><a href="#">العملاء (الزبائن)</a></li>
                            <li><a href="#">التجار / البائعين</a></li>
                            <li><a href="#">الصلاحيات</a></li>
                            <li><a href="#">الأدوار (Roles)</a></li>
                        </ul>
                    </li>

                    <!-- المنتجات والتصنيفات -->
                    <li class="sidebar-main-title mt-4">
                        <div>
                            <h6 class="lan-1" style="font-family: 'Cairo', sans-serif;">المنتجات</h6>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="package"></i>
                            <span style="font-family: 'Cairo', sans-serif;">الأقسام والمنتجات</span>
                            <div class="according-menu"><i class="fa fa-angle-down"></i></div>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#">الأقسام الرئيسية</a></li>
                            <li><a href="#">الأقسام الفرعية</a></li>
                            <li><a href="#">العلامات التجارية</a></li>
                            <li><a href="#">المنتجات</a></li>
                            <li><a href="#">تقييمات المنتجات</a></li>
                            <li><a href="#">كوبونات الخصم</a></li>
                        </ul>
                    </li>

                    <!-- الطلبات والمبيعات -->
                    <li class="sidebar-main-title mt-4">
                        <div>
                            <h6 class="lan-1" style="font-family: 'Cairo', sans-serif;">الطلبات والمبيعات</h6>
                        </div>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <i data-feather="shopping-cart"></i>
                            <span style="font-family: 'Cairo', sans-serif;">الطلبات</span>
                            <div class="according-menu"><i class="fa fa-angle-down"></i></div>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#">جميع الطلبات</a></li>
                            <li><a href="#">طلبات قيد الانتظار</a></li>
                            <li><a href="#">طلبات تم التجهيز</a></li>
                            <li><a href="#">طلبات في الطريق</a></li>
                            <li><a href="#">طلبات تم التسليم</a></li>
                            <li><a href="#">طلبات ملغاة</a></li>
                            <li><a href="#">إرجاع الطلبات</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="nav-link" href="#">
                            <i data-feather="bar-chart-2"></i>
                            <span style="font-family: 'Cairo', sans-serif;">تقارير المبيعات والإحصائيات</span>
                        </a>
                    </li>

                    <!-- التواصل والرسائل -->
                    <li class="sidebar-main-title mt-4">
                        <div>
                            <h6 class="lan-1" style="font-family: 'Cairo', sans-serif;">التواصل</h6>
                        </div>
                    </li>

                    <li>
                        <a class="nav-link" href="#">
                            <i data-feather="message-square"></i>
                            <span style="font-family: 'Cairo', sans-serif;">رسائل "تواصل معنا"</span>
                        </a>
                    </li>

                    <li>
                        <a class="nav-link" href="#">
                            <i data-feather="bell"></i>
                            <span style="font-family: 'Cairo', sans-serif;">الإشعارات</span>
                            <span class="badge badge-light-danger ms-2">جديد</span>
                        </a>
                    </li>

                </ul>
            </div>

            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>