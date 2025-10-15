@extends('admin.other_users.apps.sales_management.app')
@section('content')
    @inject('users','App\Models\Client')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Container-fluid starts-->
        <div class="container-fluid dashboard-default-sec">
            <div class="row">
                <h2>sales mangement    </h2>
                <div class="col-xl-12 box-col-12 des-xl-100">
                    <div class="row">
                        <div class="col-xl-3 col-md-4 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center btn"   onclick='window.location.href="{{route('show_my_code.sales_management')}}"'>
                                    <div class="round-box">
                                        <i data-feather="users"></i>
                                    </div>
                                     <h5>1</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">الكود الخاص بالمستخدم  </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary" >
                                <!--showMyEmailAddressPage.sales_management-->
                                <div class="card-body text-center btn" onclick='window.location.href="{{route('showMyEmailAddressPage.sales_management')}}"'>
                                    <div class="round-box">
                                        <i data-feather="mail"></i>
                                    </div>
                                    <?php $messages= App\Models\EmployeesMessages::where('user_id' , auth()->id())->count(); ?>
                                    <h5>{{$messages}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">   البريد الالكتروني      </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center btn" onclick='window.location.href="{{route('messages.index')}}"'>
                                    <div class="round-box">
                                        <i data-feather="book-open"></i>
                                        
                                    </div>
                                    <?php $messages= App\Models\Support::count(); ?>
                                    <h5>{{$messages}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">صندوق الدعم الفني  </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center">
                                    <div class="round-box">
                                        <i data-feather="zap"></i>
                                    </div>
                                     <h5>0</h5>
                                    <p style="font-family: 'Cairo', sans-serif;"> GT ChatBot  </p>
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
                                <?php $wallet =  App\Models\EmployeesWallet::where('user_id' , auth()->id())->count(); ?>
                                <div class="card-body text-center btn" <?php if( $wallet > 0) { ?>   onclick='window.location.href="{{route('employees_wallets.index')}}"'  <?php } ?>  >
                                    <div class="round-box">
                                        <i data-feather="heart"></i>
                                    </div>
                                      <h5>{{ $wallet > 0 ? 1 : 0}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;">    المحفظة</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center btn"  onclick='window.location.href="{{route('showProducts.index')}}"'>
                                    <div class="round-box">
                                        <i data-feather="loader"></i>
                                    </div>
                                     <h5>{{App\Models\Product::count()??0}}</h5>
                                    <p style="font-family: 'Cairo', sans-serif;"> المنتجات  </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-xl-3 col-md-3 col-sm-6 box-col-3 des-xl-25 rate-sec">
                            <div class="card income-card card-primary">
                                <div class="card-body text-center"  >
                                    <div class="round-box">
                                        <i data-feather="loader"></i>
                                    </div>
                                     <h5>0</h5>
                                    <p style="font-family: 'Cairo', sans-serif;"> رفع طلب لخدمة العميل  </p>
                                </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>


@endsection
