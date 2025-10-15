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
                        <h3 style="font-family: 'Cairo', sans-serif;">الأقســـــــام الرئيسيـــــة</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الأقســام الرئيسيـــــــة</li>
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
                                <a href="{{url(route('category.create'))}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">أضـــــافة قســــم رئيســى جديــد</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">اســــم القســم</th>
                                        <th style="font-family: 'Cairo', sans-serif;">التفاصيـــــل</th>
                                        <th style="font-family: 'Cairo', sans-serif;">اسم المتجـــر</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الصـــورةالخاصة بالموقع</th>
                                         <th style="font-family: 'Cairo', sans-serif;">العمليـــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($categories as $category)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$category->name}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$category->description}}</td>
                                            <td>{{App\Models\Store::where('id',$category->store_id)->first()->name}}</td>
                                            <td><img width="120px" src="{{asset('public/' . $category->image)}}"></td>
                                            
                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$category->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$category->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document" style="max-width:950px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديـل بيـانـات القســم الرئيسـي</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('category.update',$category->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-sm-12 col-xl-6 xl-100">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$category->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيــة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$category->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيـــة</a></li>
                                                                         </ul>
                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$category->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الرئيســي عـربي</label>
                                                                                            <input name="name_ar" class="form-control" id="validationCustom01" type="text" value="{{$category->getTranslation('name', 'ar')}}" required="">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $category->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل</label>
                                                                                            <input name="description_ar" class="form-control" id="validationCustom01" type="text" value="{{$category->getTranslation('description', 'ar')}}" required="">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="profile-icon{{$category->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الرئيســي باللغــة الانجليزيـــة</label>
                                                                                            <input name="name_en" class="form-control" id="validationCustom01" type="text" value="{{$category->getTranslation('name', 'en')}}">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $category->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل باللغـــة الانجليزيــــة</label>
                                                                                            <input name="description_en" class="form-control" id="validationCustom01" type="text" value="{{$category->getTranslation('description', 'en')}}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="contact-icon{{$category->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الرئيســي باللغــة الايطاليـــة</label>
                                                                                            <input name="name_it" class="form-control" id="validationCustom01" type="text" value="{{$category->getTranslation('name', 'it')}}">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $category->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل باللغـــة الايطاليــــة</label>
                                                                                            <input name="description_it" class="form-control" id="validationCustom01" type="text" value="{{$category->getTranslation('description', 'it')}}">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>



                                              <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Meta description</label>
                                     <textarea name="cat_meta" style="border:solid 1px #555; height:80px" class="form-control col-md-12">{{$category->cat_meta}}</textarea>
                                    </div>

                                    </div>
                                                            <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصورة</label>
                                        <input name="img_title" value="{{$category->img_title}}" style="border:solid 1px #555" class="form-control"  type="text" >
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الأسم البديل</label>
                                        <input name="alt_txt" value="{{$category->alt_txt}}" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصفحة </label>
                                        <input name="img_title" value="{{$category->img_title}}" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Slug</label>
                                        <input name="slug" value="{{$category->slug}}" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>
                                </div>

                                                            <div class="form-group">
                                                                <label for="exampleFormControlTextarea1">اسم المتجر :</label>
                                                                <select class="form-control form-control-lg"
                                                                        id="exampleFormControlSelect1" name="store_id">
                                                                    <option value="{{$category->store->id}}">
                                                                        {{$category->store->name}}
                                                                    </option>
                                                                    @foreach($stores as $store)
                                                                        <option value="{{$store->id}}">
                                                                            {{$store->name}}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            
                                    
                                
                                
                                
                                                           <div class="row">
                                                                <div class="col-4">
                                                                    <label> صورة البانر الخاصة بالموقع للغة العربية</label>
                                                                    <img width="120" src="{{ asset('public/' . $category->getTranslation('image', 'ar')) }}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image_ar">
                                                                </div>
                                                                <br>
                                                                <div class="col-4">
                                                                    <label> صورة البانر الخاصة بالموقع للغة الانجليزية</label>
                                                                    <img width="120" src="{{ asset('public/' . $category->getTranslation('image', 'en')) }}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image_en">
                                                                </div>
                                                                <br>
                                                              
                                                                
                                                               
                                                                <br>
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

                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$category->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف القسم الرئيسي</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('category.destroy',$category->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $category->id }}">
                                                        <div class="modal-body">هل أنت متاكد من الحذف</div>
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
    @jquery
    @toastr_js
    @toastr_render
@endsection
