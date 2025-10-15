@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Modal -->
        <!-- Button trigger modal -->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">الطلبــــــات</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الطلبــــــات</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
                        <form action="" method="get" class="d-flex">
                            <div class="me-2 col">
                                <select class="form-select" aria-label="Select order status" name="status">
                                      <option value="0">طلبات منتظرة الموافقة</option>
                                      <option value="1">طلبات مقبولة</option>
                                      <option value="2">طلبات مرفوضة</option>
                                      <option value="3">طلبات جارى توصلها</option>
                                      <option value="4">طلبات تم توصلها</option>
                                      <option value="5">طلبات معلقة</option>
                                    <option value="6">طلبات فى مرحلة التجهيز</option>
                                      <option value="7"> طلبات ملغاه  </option>
                                    <!--@foreach ($orderStatuses as $name => $value)-->
                                    <!--    <option value="{{ $value }}">{{ strtolower($name) }}</option>-->
                                    <!--@endforeach-->
                                </select>
                            </div>
                            <button type="submit" class="btn btn-info">Filter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <!-- DOM / jQuery  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">

                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>كود الاوردر</th>
                                            <th>حالة الطلب</th>
                                            <th>رقم الهاتف</th>
                                            <th>الأسم</th>
                                            <th>العنوان</th>
                                            <th>المبلغ</th>
                                            <th>التاريخ</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; ?>
                                        @foreach ($orders as $order)
                                            @include('admin.orders._order-row', ['order' => $order])
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DOM / jQuery  Ends-->
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
@section('toaster')
    @toastr_js
    @toastr_render
@endsection
