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
                        <h3 style="font-family: 'Cairo', sans-serif;">خدماتنا</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">خدماتنا</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <!-- DOM / jQuery  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <a href="{{url(route('services.create'))}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">أضـــــافة </button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;"> الخدمـــة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">المحتـــوي</th>
                                        <th style="font-family: 'Cairo', sans-serif;">التفاصيــــل </th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليـــــات</th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($services as $service)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$service->title}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$service->content}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$service->details}}</td>
                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$service->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$service->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديـل بيـانـات القســم الرئيسـي</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('services.update',$service->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-sm-12 col-xl-6 xl-100">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$service->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيــة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$service->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـة الانجليزيــة</a></li>
                                                                            {{-- <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$service->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـة الايطاليـة</a></li> --}}
                                                                        </ul>
                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$service->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم  الخدمة عـربي</label>
                                                                                            <input name="title_ar" class="form-control" id="validationCustom01" type="text" value="{{$service->getTranslation('title', 'ar')}}" required="">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $service->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المحتوي باللغة العربية</label>
                                                                                            <textarea name="content_ar" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px">{{$service->getTranslation('content', 'ar')}} </textarea>                                                                                        </div>
                                                                                    </div>

                                                                                <!--<br>-->
                                                                                <!--        <div class="row">-->
                                                                                <!--        <div class="col-md-12">-->
                                                                                <!--            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيل باللغة العربية </label>-->
                                                                                <!--            <textarea name="details_ar" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px">-->
                                                                                <!--                {{$service->getTranslation('details', 'ar')}}-->
                                                                                <!--            </textarea>                                                                                        </div>-->
                                                                                <!--    </div>-->
                                                                                </div>

                                                                            </div>
                                                                            <div class="tab-pane fade" id="profile-icon{{$service->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم الخدمة باللغــة الانجليزيـــة</label>
                                                                                            <input name="title_en" class="form-control" id="validationCustom01" type="text" value="{{$service->getTranslation('title', 'en')}}">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $service->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">الخدمة باللغـــة الانجليزيــــة</label>
                                                                                            <textarea name="content_en" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px">{{$service->getTranslation('content', 'en')}} </textarea>
                                                                                        </div>
                                                                                    </div>

                                                                                <br>
                                                                                    <!--    <div class="row">-->
                                                                                    <!--    <div class="col-md-12">-->
                                                                                    <!--        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيل باللغة الانـجليزيــــة   </label>-->
                                                                                    <!--        <textarea name="details_en" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px">-->
                                                                                    <!--            {{$service->getTranslation('details', 'en')}}-->
                                                                                    <!--        </textarea>                                                                                        </div>-->
                                                                                    <!--</div>-->
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="contact-icon{{$service->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الرئيســي باللغــة الايطاليـــة</label>
                                                                                            <input name="title_it" class="form-control" id="validationCustom01" type="text" value="{{$service->getTranslation('title', 'it')}}">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $service->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">الخدمة باللغـــة الايطاليـــة</label>
                                                                                            <textarea name="content_it" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px">
                                                                                                {{$service->getTranslation('content', 'it')}}
                                                                                            </textarea>
                                                                                        </div>
                                                                                    </div>

                                                                                <br>
                                                                                    <!--<div class="row">-->
                                                                                    <!--    <div class="col-md-12">-->
                                                                                    <!--        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل باللغـــة الايطاليــــة</label>-->
                                                                                    <!--        <div class="row">-->
                                                                                    <!--            <div class="">-->
                                                                                    <!--                <textarea name="details_it" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px">-->
                                                                                    <!--                    {{$service->getTranslation('details', 'it')}}-->
                                                                                    <!--                </textarea>-->
                                                                                    <!--            </div>-->
                                                                                    <!--        </div> -->
                                                                                    <!--    </div>-->
                                                                                    <!--</div>-->



                                                                                </div>
                                                                            </div>


                                                                       <div class="row g-3">
                                                                          <div class="col-md-3">
                                                                <label style="display: block;">  الصورة الرئيسية </label>
                                                                <img width="120px" src="{{asset('public/'.$service->img)}}">
                                                                <input class="form-control"  type="file" aria-label="file example" name="image">
                                                            </div>
                                                              </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                                <button class="btn btn-secondary" type="submit">حفظ</button>
                                                            </div>
                                                        </form>
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
    @jquery
    @toastr_js
    @toastr_render
@endsection
