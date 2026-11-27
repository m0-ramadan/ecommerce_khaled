<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme tala-sidebar">
    <div class="app-brand demo tala-sidebar-brand">
        <a href="{{ route('admin.index') }}" class="app-brand-link tala-brand-link">
            <span class="tala-brand-badge" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                    <path d="M3 6h18" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
            </span>
            <span class="tala-brand-text-wrap">
                <span class="tala-brand-title">{{ env('APP_NAME', 'تالا الجزيرة') }}</span>
                <span class="tala-brand-subtitle">Tala Al Jazeera</span>
            </span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-home"></i>
                <div>الرئيسية</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.admins.*', 'admin.users.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-user-plus"></i>
                <div>المستخدمين</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.admins.index') }}" class="menu-link">
                        <div>مديرين الموقع</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.users.index') }}" class="menu-link">
                        <div>العملاء</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.roles.*', 'admin.permissions.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-shield-lock"></i>
                <div>الصلاحيات</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.roles.index', 'admin.roles.create', 'admin.roles.edit', 'admin.roles.show') ? 'active' : '' }}">
                    <a href="{{ route('admin.roles.index') }}" class="menu-link">
                        <div>الرتب</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.permissions.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.permissions.index') }}" class="menu-link">
                        <div>الصلاحيات</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.roles.assign.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.roles.assign.index') }}" class="menu-link">
                        <div>تخصيص الصلاحيات</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.payment-methods.*') ? 'active' : '' }}">
            <a href="{{ route('admin.payment-methods.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-credit-card"></i>
                <div>طرق الدفع</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <a href="{{ route('admin.products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shopping-bag"></i>
                <div>المنتجات</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.package-orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.package-orders.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-packages"></i>
                <div>طلبات البكجات</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
            <a href="{{ route('admin.packages.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-package"></i>
                <div>الباقات المميزة</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <a href="{{ route('admin.categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-category"></i>
                <div>الأقسام</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.loyalty.*') ? 'active' : '' }}">
            <a href="{{ route('admin.loyalty.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-gift"></i>
                <div>نظام النقاط</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.sliders.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-slideshow"></i>
                <div>السلايدر الرئيسي</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.banners.index', 'admin.banners.create', 'admin.banners.edit', 'admin.banners.show') ? 'active' : '' }}">
            <a href="{{ route('admin.banners.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-photo"></i>
                <div>البانرات</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <a href="{{ route('admin.orders.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-shopping-cart"></i>
                <div>الطلبات</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.stationery.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-printer"></i>
                <div>خدمات القرطاسية</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.stationery.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.stationery.index') }}" class="menu-link">
                        <div>إدارة الأسعار والمقاسات</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.stationery.delivery-areas.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.stationery.delivery-areas.index') }}" class="menu-link">
                        <div>توصيل الطباعة (الرياض)</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.stationery.paper-types.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.stationery.paper-types.index') }}" class="menu-link">
                        <div>أنواع الورق</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.stationery.services.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.stationery.services.index') }}" class="menu-link">
                        <div>الخدمات الإضافية</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.stationery.orders') ? 'active' : '' }}">
                    <a href="{{ route('admin.stationery.orders') }}" class="menu-link">
                        <div>طلبات القرطاسية المرفوعة</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
            <a href="{{ route('admin.coupons.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-discount"></i>
                <div>الكوبونات</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
            <a href="{{ route('admin.articles.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-article"></i>
                <div>المقالات</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.contactus.*') ? 'active' : '' }}">
            <a href="{{ route('admin.contactus.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-messages"></i>
                <div>تواصل معنا</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.ads.*') ? 'active' : '' }}">
            <a href="{{ route('admin.ads.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-speakerphone"></i>
                <div>الإعلانات</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.banners.items.*') ? 'active' : '' }}">
            <a href="{{ route('admin.banners.items.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-photo-plus"></i>
                <div>بنارات المنتجات والأقسام</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
            <a href="{{ route('admin.testimonials.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-quote"></i>
                <div>الآراء</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.static-pages.*', 'admin.faqs.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-info-circle"></i>
                <div>عنا</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.static-pages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.static-pages.index') }}" class="menu-link">
                        <div>الصفحات الثابتة</div>
                    </a>
                </li>
                <li class="menu-item {{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.faqs.index') }}" class="menu-link">
                        <div>الأسئلة الشائعة</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.errors.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-file-pencil"></i>
                <div>السجلات</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.errors.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.errors.index') }}" class="menu-link">
                        <div>الأخطاء البرمجية</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->routeIs('admin.social-media.*', 'admin.setting.*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-settings"></i>
                <div>الإعدادات</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->routeIs('admin.social-media.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.social-media.index') }}" class="menu-link">
                        <div>إعدادات التواصل</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
