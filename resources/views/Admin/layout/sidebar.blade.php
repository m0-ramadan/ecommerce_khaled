@php
    use App\Models\Admin;
@endphp

<header class="main-nav">
    <div class="text-center">
        <img class="img-90" src="#" alt="">
        <h6 class="mt-3 f-14 f-w-600"></h6>
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

                    <!-- مستخدمين النظام -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <span style="font-family: 'Cairo', sans-serif;">مستخدمين النظام</span>
                            <div class="according-menu"><i class="fa fa-angle-down"></i></div>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#" class="nav-link">الإدمن</a></li>
                            <li><a href="#" class="nav-link">المندوبين</a></li>
                            <li><a href="#" class="nav-link">السائقين</a></li>
                            <li><a href="#" class="nav-link">الزبائن</a></li>
                            <li><a href="#" class="nav-link">التجار</a></li>
                            <li><a href="#" class="nav-link">الصلاحيات</a></li>
                            <li><a href="#" class="nav-link">الأدوار</a></li>
                        </ul>
                    </li>

                    <!-- إعدادات عامة -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <span style="font-family: 'Cairo', sans-serif;">إعدادات عامة</span>
                            <div class="according-menu"><i class="fa fa-angle-down"></i></div>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#" class="nav-link">الصفحات الأساسية</a></li>
                            <li><a href="#" class="nav-link">إعدادات عامة</a></li>
                            <li><a href="#" class="nav-link">بيانات التواصل</a></li>
                            <li><a href="#" class="nav-link">الدول</a></li>
                            <li><a href="#" class="nav-link">المدن / المناطق</a></li>
                            <li><a href="#" class="nav-link">الصور المتحركة (سلايدر)</a></li>
                            <li><a href="#" class="nav-link">الخدمات اللوجستية</a></li>
                            <li><a href="#" class="nav-link">رسائل تواصل معانا</a></li>
                            <li><a href="#" class="nav-link">الأسئلة الشائعة</a></li>
                            <li><a href="#" class="nav-link">طرق الدفع</a></li>
                            <li><a href="#" class="nav-link">أسعار تحويل العملات</a></li>
                        </ul>
                    </li>

                    <!-- يمكنك إضافة أقسام أخرى هنا بنفس الطريقة -->
                    <!-- مثال سريع -->
                    <li class="dropdown">
                        <a class="nav-link menu-title" href="javascript:void(0)">
                            <span style="font-family: 'Cairo', sans-serif;">المنتجات والطلبات</span>
                            <div class="according-menu"><i class="fa fa-angle-down"></i></div>
                        </a>
                        <ul class="nav-submenu menu-content">
                            <li><a href="#" class="nav-link">الأقسام</a></li>
                            <li><a href="#" class="nav-link">المنتجات</a></li>
                            <li><a href="#" class="nav-link">الطلبات</a></li>
                            <li><a href="#" class="nav-link">تقارير المبيعات</a></li>
                        </ul>
                    </li>

                </ul>
            </div>

            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>