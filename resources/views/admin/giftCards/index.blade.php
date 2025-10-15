
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
                        <h3>رسائل البرقية</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسيـــــــــة</a></li>
                            <li class="breadcrumb-item">كــل الرسائــــــــل</li>
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
                                        <th style="font-family: 'Cairo', sans-serif;">اســـم المستلم</th>
                                        <th style="font-family: 'Cairo', sans-serif;"> رقم الهاتـــــف</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الرسالـــــة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العنوان</th>
                                        <th style="font-family: 'Cairo', sans-serif;">اسم الراسل</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليـــــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($giftCards as $giftCard)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td>{{$giftCard->receiver}}</td>
                                            <td>{{$giftCard->phone}}</td>
                                            <td>{{$giftCard->message}}</td>
                                            <td>{{$giftCard->address}}</td>
                                                @if($giftCard->type ==1)
                                                    <td style="font-family: 'Cairo', sans-serif;"><span style="" >{{App\Models\Client::where('id',$giftCard->client_id)->first()->name}}</span></td>
                                                    @else
                                                    <td style="font-family: 'Cairo', sans-serif;"><span style="">لا ارغب في اظهار اسمي</span></td>
                                                @endif


                                            <td style="text-align: center">
                                                <button class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$giftCard->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                                <a href="{{route('prient',$giftCard->id)}}">
                                                <button class="btn btn-info" > <i class="fa fa-info"></i>
                                                </button>
                                            </a>

                                            </td>
                                        </tr>


                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$giftCard->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف الرسالة</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('giftCards.destroy',$giftCard->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $giftCard->id }}">

                                                        <div class="modal-body">هل أنت متاكد من حذف الرسالة</div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                            <button class="btn btn-secondary" type="submit">حذف</button>
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
