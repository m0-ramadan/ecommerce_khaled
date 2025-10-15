@extends('admin.app')
@section('content')
    @inject('users','App\Models\Client')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid dashboard-default-sec">
            <div class="row">
                <div class="col-xl-12 box-col-12 des-xl-100">
                    <div class="row">
                        <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <a href="{{url('admin/visitors')}}">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="users"></i>
                                        </div>
                                        <?php $visitors = \App\Models\Visitor::distinct('ip_address')->count('ip_address'); ?>
                                        <h5>{{$visitors}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">عدد الزائرين</p>
                                    </div>
                            </div>
                            </a>
                        </div>
                       
                    @can('view details')
                        <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <a href="{{url('admin/visits')}}">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="users"></i>
                                        </div>
                                        <?php $visitors = \App\Models\Visitor::where('date', today())->count(); ?>
                                        <h5>{{$visitors}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">عدد الزيارات</p>
                                    </div>
                            </div>
                            </a>
                        </div>
                         @endcan
                    @can('view users')
                        <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <a href="{{url('admin/client')}}">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="users"></i>
                                        </div>
                                        <?php $clients = App\Models\Client::count(); ?>
                                        <h5>{{$clients}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">عدد المستخـدميــن</p>
                                    </div>
                            </div>
                            </a>
                        </div>

                   @endcan
                    @can('view products')
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <a href="{{url('admin/storehouse')}}">
                                <div class="card income-card card-primary">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="truck"></i>
                                        </div>
                                        <?php $products = App\Models\Product::where('is_active' ,'=',1 )->count(); ?>
                                        <h5>{{$products}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">تقارير مبيعات المنتجات  </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                     @endcan
                    @can('view categories')
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <a href="{{url('admin/messages')}}">
                                <div class="card income-card card-primary">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="mail"></i>
                                        </div>
                                        <?php $messages = App\Models\Support::count(); ?>
                                        <h5>{{$messages}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">عدد الرسائــــل</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <a href="{{url('admin/category')}}">
                                <div class="card income-card card-primary">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="truck"></i>
                                        </div>
                                        <?php $Categories = App\Models\Category::count(); ?>
                                        <h5>{{$Categories}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">الأقسام الرئيسية</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                         @endcan
                    @can('view products')
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <a href="{{url('admin/product')}}">
                                <div class="card income-card card-primary">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="shopping-cart"></i>
                                        </div>
                                        <?php $Categories = App\Models\Product::count(); ?>
                                        <h5>{{$Categories}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">المنتجات</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('view stores')
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <a href="{{url('admin/store')}}">
                                <div class="card income-card card-primary">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="zap"></i>
                                        </div>
                                        <?php $stores = App\Models\Store::where('is_active', true)->count(); ?>
                                        <h5>{{$stores}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">عدد المتــاجـر</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan
                    @can('view orders')
                        <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <a href="{{url('admin/orders?status=0')}}">
                                <div class="card income-card card-primary">
                                    <div class="card-body text-center">
                                        <div class="round-box">
                                            <i data-feather="heart"></i>
                                        </div>
                                        <?php $orders = App\Models\Order::where('status', '0')->count(); ?>
                                        <h5>{{$orders}}</h5>
                                        <p style="font-family: 'Cairo', sans-serif;">اجمـالـي عـدد الطلبــات</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="loader"></i>
                                    </div>
                                    <?php $OrderPending = App\Models\Order::where('status', '1')->count(); ?>
                                    <h5>{{$OrderPending}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">طلبـات قيــد التنفيـــذ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="thumbs-up"></i>
                                    </div>
                                    <?php $Orderdelivered = App\Models\Order::where('status', '3')->count(); ?>
                                    <h5>{{$Orderdelivered}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">طلبـات مستلمـــــة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="thumbs-down"></i>
                                    </div>
                                    <?php $orderCanceled = App\Models\Order::where('status', '2')->count(); ?>
                                    <h5>{{$orderCanceled}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">الطلبـــات الملغيـــــة</p>
                                </div>
                            </div>
                        </div>
                        @endcan

                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>


@endsection
