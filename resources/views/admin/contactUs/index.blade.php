
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
                        <h3>رسائـــــــل تواصـــل معنـا</h3>
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
                                        <th style="font-family: 'Cairo', sans-serif;">اســـم المستخـــــدم</th>
                                        <th style="font-family: 'Cairo', sans-serif;">رقم الهاتـــــف</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الرسالـــــة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">ملاحظــــــات</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليـــــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($supports as $support)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td>{{$support->name}}</td>
                                            <td>{{$support->phone}}</td>
                                            @if($support->status)
                                                <td style="font-family: 'Cairo', sans-serif;"><span style="background: #3B8403; padding: 6px; color: #fff; width: 100%; border-radius: 2px; display: block;" >{{$support->message}}</span></td>
                                            @else
                                                <td style="font-family: 'Cairo', sans-serif;"><span style="background: #ED6B27; padding: 6px; color: #fff; width: 100%; border-radius: 2px; display: block;">{{$support->message}}</span></td>
                                            @endif
                                            <td>{{$support->notes}}</td>
                                            <td>
                                                <button class="btn btn-sm btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$support->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                                <button class="btn btn-sm btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#confirmed{{$support->id}}"><i class="fa fa-check"></i>
                                                </button>

                                            </td>
                                        </tr>

                                        <!-- confirmed_modal_Grade -->
                                        <div class="modal fade" id="confirmed{{$support->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تفـــاصيـــــل الرسالــــة</h5>

                                                    </div>
                                                    <form action="{{route('messages.update',$support->id)}}" method="post">
                                                        {{ method_field('patch') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $support->id }}">
                                                        <div class="modal-body" style="font-family: 'Cairo', sans-serif;">تفاصيـــــل الرسالــــة</div>
                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <div class="col-md-11">
                                                                    <textarea disabled style="border:solid 1px #555; height: 250px" class="form-control" name="judgments">{{$support->message}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-body" style="font-family: 'Cairo', sans-serif;">ملاحظـــــات</div>
                                                        <div class="mb-3">
                                                            <div class="form-check">
                                                                <div class="col-md-11">
                                                                    <textarea style="border:solid 1px #555; height: 100px" class="form-control" name="notes">{{$support->notes}}</textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-secondary" type="submit">تاكيد</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$support->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف الرسالة</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('messages.destroy',$support->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $support->id }}">
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
