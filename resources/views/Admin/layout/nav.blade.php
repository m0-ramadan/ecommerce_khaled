@php
    $user = auth()->user();
@endphp
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme tala-navbar"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="ti ti-menu-2 ti-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item navbar-search-wrapper mb-0">
                <a class="nav-item nav-link search-toggler tala-search-trigger px-0" href="javascript:void(0);">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                    </svg>
                    <span class="d-none d-md-inline-block">البحث (Ctrl + /)</span>
                </a>
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto gap-2">
            <!-- Notification -->
            <li class="nav-item dropdown-notifications navbar-dropdown dropdown">
                <a class="nav-link dropdown-toggle hide-arrow tala-bell-btn" href="javascript:void(0);"
                    data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                    <svg id="btn_notify" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"></path>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"></path>
                    </svg>
                    <span class="tala-bell-dot"></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-end py-0">
                    <li class="dropdown-menu-header border-bottom">
                        <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">الإشعارات</h5>
                            <a href="javascript:void(0)" class="dropdown-notifications-all text-body"
                                data-bs-toggle="tooltip" data-bs-placement="top" title="تحديد الكل كمقروء">
                                <i class="ti ti-mail-opened fs-4"></i>
                            </a>
                        </div>
                    </li>

                    <li class="dropdown-notifications-list scrollable-container notification-body">
                        <ul class="list-group list-group-flush" id="list_notifications">
                            <li class="list-group item list-group-item-action dropdown-notifications-item">
                                <div class="d-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">انضمام مستخدم جديد</h6>
                                        <p class="mb-0 text-muted small">انضم مستخدم جديد إلى المنصة</p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="dropdown-menu-footer border-top">
                        <a href="{{ route('admin.orders.index') }}"
                            class="dropdown-item d-flex justify-content-center text-primary p-2 h-px-40 mb-1 align-items-center"
                            id="view-all-notifications">
                            عرض جميع الطلبات
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="tala-user-avatar">
                        @if ($user?->avatar)
                            <img src="{{ get_user_image($user->avatar) }}" alt="{{ $user->name }}" />
                        @else
                            <span class="tala-user-avatar-dot"></span>
                        @endif
                        <span class="tala-user-online-badge"></span>
                    </div>
                </a>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.index') }}">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <div class="tala-user-avatar">
                                        @if ($user?->avatar)
                                            <img src="{{ get_user_image($user->avatar) }}" alt="{{ $user->name }}" />
                                        @else
                                            <span class="tala-user-avatar-dot"></span>
                                        @endif
                                        <span class="tala-user-online-badge"></span>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ $user?->name ?? 'مدير النظام' }}</span>
                                    <small class="text-muted">{{ $user?->email ?? '' }}</small>
                                </div>
                            </div>
                        </a>
                    </li>

                    <li>
                        <div class="dropdown-divider"></div>
                    </li>

                    <li>
                        <a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ti ti-logout me-2 ti-sm"></i>
                            <span class="align-middle">تسجيل الخروج</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>

    <!-- Search Input Wrapper -->
    <div class="navbar-search-wrapper search-input-wrapper d-none">
        <input type="text" class="form-control search-input container-xxl border-0" placeholder="ابحث عن صفحة في لوحة التحكم..."
            aria-label="البحث..." />
        <i class="ti ti-x ti-sm search-toggler cursor-pointer"></i>
    </div>
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</nav>
