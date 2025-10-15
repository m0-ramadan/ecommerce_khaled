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
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الطلبــــــات</li>
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
                                    @foreach($orders as $order)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td>ثثث{{$order->code_order}}</td>
                                                
                                                @if($order->status =='0')<td>قيد الانتظار</td>@elseif($order->status =='3')<td>جارى التوصيل</td>
                                                @elseif($order->status =='4')<td>تم التوصيل</td>@elseif($order->status =='2')<td>تم الرفض</td>
                                                @elseif($order->status =='1')<td>تم الموافقة</td>@endif
                                                <td>{{$order->user_phone}}</td>
                                                <td>{{$order->user_name}}</td>
                                                <td>{{$order->address}}</td>
                                                <td>{{$order->total}}</td>
                                                <td>{{$order->created_at}}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$order->id}}"  title="تفاصيل الطلب"><i class="fa fa-remove"></i>
                                                </button>
                                                <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#details{{$order->id}}" title="بيانات الطلب"><i class="fa fa-info"></i>
                                                </button>
                                                <button class="btn btn-warning" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#clients{{$order->id}}"  title="بيانات المستخدم"><i class="fa fa-user"></i>
                                                </button>
                                                <a href="{{route('orders.edit',$order->id)}}"  class="btn btn-sm btn-success"  title="تغير حالة الطلب">
                                                    <i class="fa fa-check"></i>
                                                </a>
                                                    <!--<button class="btn btn-sm btn-success" type="button" data-bs-toggle="modal"-->
                                                    <!--        data-original-title="test" data-bs-target="#confirmed{{$order->id}}" title="تغير حالة الطلب"><i class="fa fa-check"></i>-->
                                                    <!--</button>-->
                                                
                                            </td>
                                        </tr>

                                        <!-- confirmed_modal_Grade -->
                                        <div class="modal fade" id="confirmed{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">تاكيد الاوردر</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('orders.update',$order->id)}}" method="post">
                                                        {{ method_field('patch') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $order->id }}">
                                                        <div class="modal-body">هل أنت متاكد من تاكيد الاوردر</div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                            <button class="btn btn-secondary" type="submit">تاكيد</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف الاوردر</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('orders.destroy',$order->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $order->id }}">
                                                        <div class="modal-body">هل أنت متاكد من حذف الاوردر</div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                            <button class="btn btn-secondary" type="submit">حذف</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- details_modal_Grade -->
                                        <div class="modal fade" id="details{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">بيانات الاوردر</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <?php
                                                            $orderItems = App\Models\OrderItem::where('order_id',$order->id)->get();
                                                    ?>
                                                        <div class="form-control">
                                                            <div class="modal-body">بيانات الاوردر</div>
                                                            <div class="row g-3">
                                                                @foreach($orderItems as $orderItem)
                                                                <div class="col-md-4">
                                                                    <label class="form-label" for="validationCustom01">اسم المنتج</label>
                                                                    <input class="form-control" disabled value="<?php echo(App\Models\Product::find($orderItem->product_id)->name);?>">
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <label class="form-label" for="validationCustom01">الكمية</label>
                                                                    <input class="form-control" disabled value="{{$orderItem->quantity}}">
                                                                </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label" for="validationCustom02">سعر القطعة</label>
                                                                        <input class="form-control" disabled value="<?php echo(App\Models\Product::find($orderItem->product_id)->current_price);?>">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label" for="validationCustom02">الأجمالى</label>
                                                                        <input class="form-control" disabled value="{{$orderItem->total}}">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="form-label" for="validationCustom02">الخصم</label>
                                                                        <input class="form-control" disabled value="<?php echo(App\Models\Product::find($orderItem->product_id)->old_price);?>">
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <label class="form-label" for="validationCustom02">تفاصيل المنتج</label>
                                                                        <textarea disabled class="form-control" style="border:solid 1px #555"><?php echo(App\Models\Product::find($orderItem->product_id)->details);?></textarea>
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <?php
                                                                        $productImage = App\Models\Image::where('product_id',App\Models\Product::find($orderItem->product_id)->id)->first();
                                                                        ?>
                                                                        <img width="70" src="{{asset('public/' . $productImage->src)}}">
                                                                    </div>
                                                                    <hr>
                                                                @endforeach
                                                            <br>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                        </div>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- clients_details_modal_Grade -->
                                        <div class="modal fade" id="clients{{$order->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">بيانات العميل</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="validationCustom01">اسم العميل</label>
                                                                <input style="border:solid 1px #555" class="form-control" disabled value="{{App\Models\Client::where('id',$order->client_id)->first()->name}}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="validationCustom01">رقم هاتف العميل</label>
                                                                <input style="border:solid 1px #555" class="form-control" disabled value="{{App\Models\Client::where('id',$order->client_id)->first()->phone}}">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="validationCustom01">البريد الالكتروني</label>
                                                                <input style="border:solid 1px #555" class="form-control" disabled value="{{App\Models\Client::where('id',$order->client_id)->first()->email}}">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label" for="validationCustom01">عنوان العميل</label>
                                                                <input style="border:solid 1px #555" class="form-control" disabled value="{{App\Models\Client::where('id',$order->client_id)->first()->address}}">
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                        </div>
                                                    </div>
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
