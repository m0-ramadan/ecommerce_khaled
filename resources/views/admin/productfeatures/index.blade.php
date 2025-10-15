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
                        <h3 style="font-family: 'Cairo', sans-serif;"> مميزات المنتج </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيســــية</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;"> مميزات المنتج</li>
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
                                    <a href="{{url(route('productfeatureCreate',$id))}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">إضافة ميزة جديدة للمنتج</button></a>
                            <br>
                            </div>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;"> اســــم المنتـــج</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الاســـــــم</th>
                                        <th style="font-family: 'Cairo', sans-serif;">التفــــــاصيل</th>
                                        <th  style="font-family: 'Cairo', sans-serif;">العمليــــــاتــ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($features as $feature)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$feature->product->name}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$feature->name}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$feature->description}}</td>

                                            <td>

                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$feature->id}}"><i class="fa fa-remove"></i>
                                                </button>

                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$feature->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                       <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$feature->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف ميزة المنتج</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('productfeaturesdestroy')}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $feature->id }}">
                                                                 <input id="id" type="hidden" name="product_id" class="form-control"
                                                               value="{{ $feature->product_id }}">
                                                        <div class="modal-body">هل انت متاكد من حذف ميزة المنتج </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                            <button class="btn btn-secondary" type="submit">حذف</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>




    <div class="modal fade " id="edit{{$feature->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog " style="max-width:1200px" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات المنتج</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('product.update',$feature->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-md-12">
                                                                <div class="card col-md-12">
                                                                    <div class="card-body col-md-12">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$feature->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيـة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$feature->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيـة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$feature->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـة الايطاليــة</a></li>
                                                                        </ul>
                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$feature->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المنتج باللغة العربية</label>
                                                                                            <input name="name_ar" class="form-control" id="validationCustom01" type="text" value="{{$feature->getTranslation('name', 'ar')}}" required="">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $feature->id }}">
                                                                                        </div>
                                                                                        
                                                                                       
                                                                                        
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل المنتج باللغة العربية</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_ar" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$feature->getTranslation('description', 'ar')}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="profile-icon{{$feature->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المنتج باللغــة الانجليزيـــة</label>
                                                                                            <input name="name_en" class="form-control" id="validationCustom01" type="text" value="{{$feature->getTranslation('name', 'en')}}">
                                                                                         </div>
                                                                                        
                                                                                        
                                                                                        
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل باللغـــة الانجليزيــــة</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_en" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$feature->getTranslation('description', 'en')}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="contact-icon{{$feature->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المنتج باللغــة الايطاليـــة</label>
                                                                                            <input name="name_it" class="form-control" id="validationCustom01" type="text" value="{{$feature->getTranslation('name', 'it')}}">
                                                                                         </div>
                                                                                        
                                                                                        
                                                                                       
                                                                                        
                                                                                        
                                                                                        
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل باللغـــة الايطاليــــة</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_it" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$feature->getTranslation('description', 'it')}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                              

                          
                             
                                                            </div>
                                                          
                                                    
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلـــق</button>
                                                                <button class="btn btn-secondary" type="submit">حفـــظ</button>
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
    @toastr_js
    @toastr_render
@endsection
