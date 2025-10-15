@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/category')}}">الضفحات</a></li>
                            <li class="breadcrumb-item">أضافة صفحة</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
                        <!-- Bookmark Start-->
                        <div class="bookmark">
                        </div>
                        <!-- Bookmark Ends-->
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 style="font-family: 'Cairo', sans-serif;">أضافـــة صفحة</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('pages.store')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغــــة العربيــــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــة الانجليزيــــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــــة الايطاليـــــة</a></li>
                                            </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان الصفحة باللغــة العربيـــة</label>
                                                                <input style="border:solid 1px #555" name="title_ar" class="form-control" id="validationCustom01" type="text">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> محتوي الصفحة باللغـة العربية فى الموقع</label>
                                                                <div class="row">
                                                                    <div class="">
                                                                        <textarea name="body_ar" style="border:solid 1px #555; height:200px" class="ckeditor"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                           <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتوي الصفحة باللغـة العربية فى التطبيق </label>
                                                                <div class="row">
                                                                    <div class="">
                                                                        <textarea class="form-control" name="contact_app_ar" style="width:100%;border:solid 1px #555; height:400px"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان الصفحة باللغــة الانجليزيـــة</label>
                                                                <input style="border:solid 1px #555" name="title_en" class="form-control" id="validationCustom01" type="text">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتوي الصفحة باللغـة الانجليزيـــة  فى الموقع</label>
                                                                <div class="row">
                                                                    <div class="">
                                                                        <textarea name="body_en" style="width:100%;border:solid 1px #555; height:200px" class="ckeditor"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                         <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتوي الصفحة باللغـة الانجليزيـــة فى التطبيق </label>
                                                                <div class="row">
                                                                    <div class="">
                                                                        <textarea name="contact_app_en" class="form-control" style="width:100%;border:solid 1px #555; height:400px"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان الصفحة باللغــة الايطاليـــــة</label>
                                                                <input style="border:solid 1px #555" name="title_it" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="width:100%;font-family: 'Cairo', sans-serif;"> محتوي الصفحة باللغـة الايطالية فى الموقع</label>
                                                                <div class="row">
                                                                    <div class="">
                                                                        <textarea name="body_it" style="width:100%;border:solid 1px #555; height:200px" class="ckeditor"  style="height: 200px"></textarea>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                             <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتوي الصفحة باللغـة الايطاليـــــة فى التطبيق </label>
                                                                <div class="row">
                                                                    <div class="">
                                                                        <textarea name="contact_app_it" class="form-control" style="width:100%;border:solid 1px #555; height:400px"></textarea>
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
                                <div class="col">
                                    <label for="store_id"
                                           class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">القسم
                                        :</label>
                                    <div class="box col-md-4">
                                        <select class="fancyselect form-control" style="border:solid 1px #555" name="type">
                                            <option value="">اختـر القسم</option>

                                            <option value="0">هذة هي تافي فيولت</option>
                                            <option value="1">السياسات</option>
                                            <option value="2">التطبيق</option>
                                            <option value="3">الاحكام والشروط</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <br>
                                <div class="col-md-10">
                                    <input style="border:solid 1px #555" name="image" class="form-control" type="file" aria-label="file example" required="">
                                </div>
                                <br>
                                <hr>
                                
                                <button class="btn btn-primary" type="submit">حفظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection
@section('toaster')
 <!-- Plugins JS start-->
    <script src="{{asset('admin/assets/js/editor/ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('admin/assets/js/editor/ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('admin/assets/js/editor/ckeditor/styles.js')}}"></script>
    <script src="{{asset('admin/assets/js/editor/ckeditor/ckeditor.custom.js')}}"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{asset('admin/assets/js/script.js')}}"></script>
    <script src="{{asset('admin/assets/js/theme-customizer/customizer.js')}}"></script>
    <!-- login js-->
    <!-- Plugin used-->
 @endsection