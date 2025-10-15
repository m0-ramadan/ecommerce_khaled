@inject('settings', 'App\Models\Contact')
<!-- Loader starts-->
<div class="loader-wrapper">
    <div class="theme-loader">
        <div class="loader-p"></div>
    </div>
</div>
@inject('orders', 'App\Models\Order')
<!-- Loader ends-->
<!-- page-wrapper Start       -->
<div class="page-wrapper compact-wrapper" id="pageWrapper">
    <!-- Page Header Start-->
    <div class="page-main-header">
        <div class="main-header-right row m-0">
            <div class="main-header-left">
                <div class="logo-wrapper" style="width: 50px;"><a href="{{url('admin/dashboard')}}"><img class="img-fluid" src="{{asset('public/'.$settings->first()->image)}}" alt=""></a></div>
                <div class="dark-logo-wrapper"><a href="{{url('admin/dashboard')}}"><img class="img-fluid" src="{{asset('public/'.$settings->first()->image)}}" alt=""></a></div>
                <div class="toggle-sidebar"><i class="status_toggle middle" data-feather="align-center" id="sidebar-toggle"></i></div>
            </div>

            <div class="nav-right col pull-right right-menu p-0">
                <ul class="nav-menus">
                    <li><a class="text-dark" href="#!" onclick="javascript:toggleFullScreen()"><i data-feather="maximize"></i></a></li>

                    <li>
                        <div class="mode"><i class="fa fa-moon-o"></i></div>
                    </li>

                    <li class="onhover-dropdown p-0">
                        <button class="btn btn-primary-light" type="button"><a style="font-family: 'Cairo', sans-serif;" href="{{route('signout')}}"><i data-feather="log-out"></i>تســــجيـل الخـــــروج</a></button>
                    </li>
                </ul>
            </div>
            <div class="d-lg-none mobile-toggle pull-right w-auto"><i data-feather="more-horizontal"></i></div>
        </div>
    </div>
{{--     Page Header Ends  --}}
<!-- Page Body Start-->
    <div class="page-body-wrapper sidebar-icon">
        <!-- Page Sidebar Start-->
            <header class="main-nav">
                {{-- <div class="sidebar-user text-center"><a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a>
                    <img class="img-90 rounded-circle" src="{{asset('admin/assets/images/dashboard/1.png')}}" alt="">
                <div class="badge-bottom"></div><a href=""><h6 class="mt-3 f-14 f-w-600">{{Auth::guard('')->user()->name}}</h6></a></div> --}}
                <nav>
                    <div class="main-navbar">
                        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
                        <div id="mainnav">
                    <ul class="nav-menu custom-scrollbar" style="height: 650px">
                        <li class="back-btn">
                            <div class="mobile-back text-end"><span>Back</span><i class="fa fa-angle-right ps-2" aria-hidden="true"></i></div>
                        </li>
                        
                        <li><a href="{{route('employees.index')}}" class="nav-link"><i data-feather="plus-circle"></i><span style="font-family: 'Cairo', sans-serif;">   انشاء مستخدم جديد  </span></a></li>
                        <li><a href="{{url('admin/store/create')}}" class="nav-link"><i data-feather="plus-circle"></i><span style="font-family: 'Cairo', sans-serif;">انشــــاء متـجر جـديـد</span></a></li>
                        
                          <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)">
                              <i data-feather="settings"></i><span style="font-family: 'Cairo', sans-serif;">الأعدادات العامة</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="{{url('admin/setting')}}" class="nav-link"><i data-feather="git-pull-request"></i><span style="font-family: 'Cairo', sans-serif;">اعــــداداتــ المــوقـــع</span></a></li>
                                <li><a href="{{url('admin/banners')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">الصـــور المتحــركـــة</span></a></li>
                                <li><a href="{{url('admin/titles')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">العناوين الرئيسية</span></a></li>
                                <li><a href="{{url('admin/details')}}" class="nav-link"><i data-feather="star"></i><span style="font-family: 'Cairo', sans-serif;">التفاصيــــل</span></a></li>
                                <li><a href="{{url('admin/changePassword')}}" class="nav-link"><i data-feather="star"></i><span style="font-family: 'Cairo', sans-serif;">تغير كلمة المرور</span></a></li>
                            </ul>
                          </li>
                        <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i data-feather="home"></i><span style="font-family: 'Cairo', sans-serif;">الأماكن</span></a>
                            <ul class="nav-submenu menu-content">
                                
                        <li><a href="{{url('admin/countries')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">الدول</span></a></li>
                        <li><a href="{{url('admin/cities')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">المدن</span></a></li>
                          </ul>
                        </li>
                        <li><a href="{{url('admin/services')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">خدماتنا</span></a></li>
                        <li><a href="{{url('admin/giftCards')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">رسائل التهنئة</span></a></li>
                        <li><a href="{{url('admin/payments')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">طـــرق الدفـــع</span></a></li>
                        <li><a href="{{url('admin/groups')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">شركاء النجاح</span></a></li>

                        <!--<li><a href="{{url('admin/constructions')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">شركة انشاءات</span></a></li>-->
                        <li><a href="{{url('admin/pages')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">الصـفحات</span></a></li>
                        <li><a href="{{url('admin/inspiration')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">الالهام والافكار</span></a></li>
                        <li><a href="{{url('admin/bonuses')}}" class="nav-link"><i data-feather="image"></i><span style="font-family: 'Cairo', sans-serif;">المكافآت</span></a></li>
                        <li><a href="{{url('admin/client')}}" class="nav-link"><i data-feather="users"></i><span style="font-family: 'Cairo', sans-serif;">المستخـــــدميــن</span></a></li>
                        <?php $message= App\Models\Support::where('status','0')->count(); ?>
                        <li><a href="{{url('admin/messages')}}" class="nav-link"><i data-feather="mail"></i><span class="pull-right badge badge-primary badge-pill">{{$message}}</span>
                                <span style="font-family: 'Cairo', sans-serif;"> الدعــم الفنــي</span></a></li>
                        {{-- <li><a href="{{url('admin/contactUs')}}" class="nav-link"><i data-feather="mail"></i><span class="pull-right badge badge-primary badge-pill">{{$message}}</span>
                                <span style="font-family: 'Cairo', sans-serif;">تواصــــل معنـــا</span></a></li> --}}
                        {{-- <li><a href="{{url('admin/blog')}}" class="nav-link"><i data-feather="edit"></i><span style="font-family: 'Cairo', sans-serif;">المقـــــــــالات</span></a></li> --}}
                        {{-- <li><a href="{{url('admin/story')}}" class="nav-link"><i data-feather="edit"></i><span style="font-family: 'Cairo', sans-serif;">قـــصص النجــاح</span></a></li> --}}
                        <?php $count= App\Models\Order::where('status','pending')->count(); ?>
                        <li><a href="{{url('admin/orders')}}" class="nav-link"><i data-feather="layers"></i><span class="pull-right badge badge-primary badge-pill">{{$count}}</span>
                                <span style="font-family: 'Cairo', sans-serif;">الطلــــــباتــ</span></a></li>
                        <li><a href="{{url('admin/store')}}" class="nav-link"><i data-feather="shopping-bag"></i><span style="font-family: 'Cairo', sans-serif;">المـــــتاجـــر</span></a></li>
                        <li><a href="{{url('admin/category')}}" class="nav-link"><i data-feather="truck"></i><span style="font-family: 'Cairo', sans-serif;">الأقـــســام الرئيـــــــسية</span></a></li>
                        <li><a href="{{url('admin/subcategory')}}" class="nav-link"><i data-feather="tag"></i><span style="font-family: 'Cairo', sans-serif;">الأقســـــام الفرعــــــية</span></a></li>
                        <li><a href="{{url('admin/product')}}" class="nav-link"><i data-feather="shopping-cart"></i><span style="font-family: 'Cairo', sans-serif;">المنتجـــــــــات</span></a></li>
                        <li><a href="{{url('admin/offers')}}" class="nav-link"><i data-feather="star"></i><span style="font-family: 'Cairo', sans-serif;">العـــــــروض</span></a></li>
                        <li class="dropdown"><a class="nav-link menu-title" href="javascript:void(0)"><i data-feather="archive"></i><span style="font-family: 'Cairo', sans-serif;">الارشـــــــــيف</span></a>
                            <ul class="nav-submenu menu-content">
                                <li><a href="{{url('admin/archive')}}">ارشيف المنتجات</a></li>
                                <li><a href="{{url('admin/archiveSub')}}">ارشيف الاقسام الفرعية</a></li>
                                <li><a href="{{url('admin/archiveCate')}}">ارشيف الاقسام الرئيسية</a></li>
                                <li><a href="{{url('admin/archiveStore')}}">ارشيف المتاجر</a></li>
                                <li><a href="{{url('admin/archiveOrder')}}">ارشيف الطلبات</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
                    </div>
                </nav>
            </header>



<!-- Page Sidebar Ends-->
