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
                        <h3 style="font-family: 'Cairo', sans-serif;">المتــاجــــــر</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">كــــل المتـاجــر</li>
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
                                <a href="{{url(route('store.create'))}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">أضــــــافة متجــر جديـــد</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">اســـم المتجــر</th>
                                        <th style="font-family: 'Cairo', sans-serif;">رقــم الهاتـــف</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العنـــــوان</th>
                                        <th style="font-family: 'Cairo', sans-serif;">رابط الفيس بوك</th>
                                        <th style="font-family: 'Cairo', sans-serif;">رابط تويتر</th>
                                        <th style="font-family: 'Cairo', sans-serif;">رابط الانستاجرام</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الاحكام والشروط</th>
                                        <th style="font-family: 'Cairo', sans-serif;">عــن المتـجر</th>
                                        <th style="font-family: 'Cairo', sans-serif;">سيـاسية الاستبدال</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الصــورة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليـــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($stores as $store)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td>{{$store->name}}</td>
                                            <td>{{$store->phone}}</td>
                                            <td>{{$store->address}}</td>
                                            <td>{{$store->facebook}}</td>
                                            <td>{{$store->twitter}}</td>
                                            <td>{{$store->instagram}}</td>
                                            <td>{{$store->judgments}}</td>
                                            <td>{{$store->about}}</td>
                                            <td>{{$store->replacement}}</td>
                                            <td><img width="100" src="{{asset('public/' . $store->image)}}"></td>
                                            <td>
                                                @if($store->type==0)

                                                        <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                                data-original-title="test" data-bs-target="#edit{{$store->id}}"><i class="fa fa-edit"></i>
                                                        </button>
                                                        <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                                data-original-title="test" data-bs-target="#exampleModal{{$store->id}}"><i class="fa fa-remove"></i>
                                                        </button>

                                                @endif
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$store->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Edit Row</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('store.update',$store->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-md-12">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$store->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيــة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$store->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيـــة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$store->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الايطاليــة</a></li>
                                                                        </ul>
                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$store->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المتجر باللغة العربية</label>
                                                                                            <input name="name_ar" class="form-control" id="validationCustom01" type="text" value="{{$store->getTranslation('name', 'ar')}}" required="">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $store->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">الاحكام والشروط</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="judgments_ar">{{$store->judgments}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">عن المتجر</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="about_ar">{{$store->about}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">سياسية الاستبدال</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="replacement_ar">{{$store->replacement}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="profile-icon{{$store->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المتجر باللغة الانجليزية</label>
                                                                                            <input name="name_en" class="form-control" id="validationCustom01" type="text" value="{{$store->getTranslation('name', 'en')}}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">الاحكام والشروط</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="judgments_en">{{$store->judgments}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">عن المتجر</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="about_en">{{$store->about}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">سياسية الاستبدال</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="replacement_en">{{$store->replacement}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="contact-icon{{$store->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المتجر باللغــة الايطاليـــة</label>
                                                                                            <input name="name_it" class="form-control" id="validationCustom01" type="text" value="{{$store->getTranslation('name', 'it')}}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">الاحكام والشروط</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="judgments_it">{{$store->judgments}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">عن المتجر</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="about_it">{{$store->about}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="mb-3">
                                                                                        <div class="form-check">
                                                                                            <div class="col-md-12">
                                                                                                <label class="mr-sm-2" for="validationCustom04">سياسية الاستبدال</label>
                                                                                                <textarea style="border:solid 1px #555" class="form-control" name="replacement_it">{{$store->replacement}}</textarea>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <div class="row g-3">
                                                                    <div>
                                                                        <img width="120" src="{{asset('public/' . $store->image)}}">
                                                                        <input class="form-control" type="file" aria-label="file example"  name="image">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <label class="mr-sm-2" for="validationCustom01">رقم الهاتف</label>
                                                                        <input name="phone" class="form-control" id="validationCustom01" type="text" value="{{ $store->phone }}" required="">
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row g-3">
                                                                    <div class="col-md-4">
                                                                        <label class="mr-sm-2" for="validationCustom01">رابط الفيس بوك</label>
                                                                        <input name="facebook" class="form-control" id="validationCustom01" type="text" value="{{ $store->facebook }}" required="">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="mr-sm-2" for="validationCustom01">رابط تويتر</label>
                                                                        <input name="twitter" class="form-control" id="validationCustom01" type="text" value="{{ $store->twitter }}" required="">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <label class="mr-sm-2" for="validationCustom01">رابط تويتر</label>
                                                                        <input name="instagram" class="form-control" id="validationCustom01" type="text" value="{{ $store->instagram }}" required="">
                                                                    </div>
                                                                </div>

                                                                <br>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                                                                <button class="btn btn-secondary" type="submit">Submit</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$store->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف المتجر</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('store.destroy',$store->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $store->id }}">
                                                        <div class="modal-body">هل أنت متاكد من عملية الحذف</div>
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
