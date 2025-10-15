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
                        <h3 style="font-family: 'Cairo', sans-serif;">الأسئلة الشائعة</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيســــية</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الأسئلة الشائعة</li>
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
                            <div>
                                <a href="{{url(route('faqs.create'))}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">أضـــــافة سؤال </button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;"> السؤال </th>
                                       <th  style="font-family: 'Cairo', sans-serif;">الأجابات</th>
                                        <th    style="font-family: 'Cairo', sans-serif;">العمليــــــاتــ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($faqs as $product)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{Str::words($product->questions, 60)}}</td>

                                            <td style="font-family: 'Cairo', sans-serif;">{{Str::words($product->answer, 20)}}</td>

                                            <td  class="fitwidth">

                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$product->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$product->id}}"><i class="fa fa-remove"></i>
                                                </button>

                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade " id="edit{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog " style="max-width:1200px" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">تعديل  </h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('faqs.update',$product->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-md-12">
                                                                <div class="card col-md-12">
                                                                    <div class="card-body col-md-12">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$product->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيـة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$product->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيـة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$product->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـة الايطاليــة</a></li>
                                                                        </ul>
                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$product->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">السؤال</label>
                                                                                            <input name="name_ar" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('questions', 'ar')}}" required="">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $product->id }}">
                                                                                        </div>


                                                                                    </div>
                                                                                          <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> الأجابة بالعربى</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_ar" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$product->getTranslation('answer', 'ar')}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                </div>
                                                                            </div>

                                                                     <div class="tab-pane fade" id="profile-icon{{$product->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">السؤال باللغة الأنجليزية</label>
                                                                                            <input name="name_en" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('questions', 'en')}}">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $product->id }}">
                                                                                        </div>
                                                                                    </div>

                                                                                        <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">الأجابة باللغة الأنجليزية</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_en" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$product->getTranslation('answer', 'en')}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                    </div>

                                                                                                                                                <div class="tab-pane fade" id="contact-icon{{$product->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">السؤال باللغة الأطالية</label>
                                                                                            <input name="name_it" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('questions', 'it')}}">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $product->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                          <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">الأجابة باللغة الأطالية</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_it" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$product->getTranslation('answer', 'it')}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>




                                                            <br>



                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلـــق</button>
                                                                <button class="btn btn-secondary" type="submit">حفـــظ</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                           </div>
                        </div>

                                         <div class="modal fade" id="exampleModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog"  role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف المنتج</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('faqs.destroy',$product->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $product->id }}">
                                                        <div class="modal-body">هل أنت متاكد من حذف هذا المنتج</div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                            <button class="btn btn-secondary" type="submit">حذف</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>

                                        <!-- delete_modal_Grade -->

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
