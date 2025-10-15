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
                        <h3> ارشيف الطلبات</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item">ارشيف الطلبات</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
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
                                        <th>حالة الدفع</th>
                                        <th>طريقة الدفع</th>
                                        <th>حالة الطلب</th>
                                        <th>العنوان</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                        <th>العمليات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($archiveOrder as $order)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td>{{$order->code_order}}</td>
                                            @if($order->payment_status == 0) <td>تم الدفع</td>@else <td> لم يتم الدفع</td>@endif
                                            @if($order->payment_type ==0)<td> الدفع عند الاستلام</td>@else<td>الدفع اونلين</td>@endif
                                            @if($order->status =='pending')<td>قيد الانتظار</td>@elseif($order->status =='canceled')<td>تم الالغاء</td>
                                            @elseif($order->status =='delivered')<td>تم التوصيل</td>@elseif($order->status =='refuse')<td>تم الرفض</td>
                                            @elseif($order->status =='confirmed')<td>تم الموافقة</td>@endif
                                            <td>{{App\Models\Address::where('id',$order->address_id)->first()->location}}</td>
                                            <td>{{$order->total}}</td>
                                            <td>{{$order->created_at}}</td>
                                            <td>
                                                <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$order->id}}"><i class="fa fa-window-restore"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- restore_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">استرجاع الملفات</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('orderRestore',$order->id)}}" method="post">
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $order->id }}">
                                                        <div class="modal-body">هل أنت متاكد من استرجاع هذا الطلب</div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                            <button class="btn btn-secondary" type="submit">تاكيد</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
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
