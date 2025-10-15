@extends('admin.other_users.apps.tester.app')
@section('content')
    @inject('users','App\Models\Client')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid dashboard-default-sec">
            <div class="row">
                <h2>testing and maitenance      </h2>
                <div class="col-xl-12 box-col-12 des-xl-100">
                    <div class="row">
                        <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="users"></i>
                                    </div>
                                    <?php $clients= App\Models\Client::count(); ?>
                                    <h5>{{$clients}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">عدد المستخـدميــن</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="mail"></i>
                                    </div>
                                    <?php $messages= App\Models\Support::count(); ?>
                                    <h5>{{$messages}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">عدد الرسائــــل</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="book-open"></i>
                                    </div>
                                    <?php $messages= App\Models\Blog::count(); ?>
                                    <h5>{{$messages}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">عدد المقــالات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="zap"></i>
                                    </div>
                                    <?php $stores= App\Models\Store::count(); ?>
                                    <h5>{{$stores}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">عدد المتــاجـر</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12 box-col-12 des-xl-100">
                    <div class="row">
                        <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="heart"></i>
                                    </div>
                                    <?php $orders= App\Models\Order::count(); ?>
                                    <h5>{{$orders}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">اجمـالـي عـدد الطلبــات</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="loader"></i>
                                    </div>
                                    <?php $OrderPending= App\Models\Order::where('status','pending')->count(); ?>
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
                                    <?php $Orderdelivered= App\Models\Order::where('status','delivered')->count(); ?>
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
                                    <?php $orderCanceled= App\Models\Order::where('status','canceled')->count(); ?>
                                    <h5>{{$orderCanceled}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">الطلبـــات الملغيـــــة</p>
                                </div>
                            </div>
                        </div>
                        
                         <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                              <a href="{{url('admin/faqs')}}">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i class="fa fa-question" style="font-size:37px"></i>
                                    </div>
                                    <?php $faqs= App\Models\Faqs::count(); ?>
                                    <h5>{{$faqs}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">الاسئلة الشائعة</p>
                                </div>
                            </div>
                            </a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>


@endsection
