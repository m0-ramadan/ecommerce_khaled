@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/category')}}"> الأقسام الرئيسية</a></li>
                            <li class="breadcrumb-item">أضافة قسم رئيسي</li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">أضافـــة قســم رئيســي</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('category.store')}}">
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
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اسم القسم الرئيسي باللغــة العربيـــة</label>
                                                                <input style="border:solid 1px #555" name="name_ar" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغـة العربيــة</label>
                                                                <input style="border:solid 1px #555" name="description_ar" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اسم القسم الرئيسي باللغــة الانجليزيـــة</label>
                                                                <input style="border:solid 1px #555" name="name_en" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغـة الانجليزيـــة</label>
                                                                <input style="border:solid 1px #555" name="description_en" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اسم القسم الرئيسي باللغــة الايطاليـــــة</label>
                                                                <input style="border:solid 1px #555" name="name_it" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغـة الايطاليـــــة</label>
                                                                <input style="border:solid 1px #555" name="description_it" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                            </div>
                                                        </div>
                                                        
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                              <div class="row g-3">
                                    <div class="col-md-12">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Meta description</label>
                                     <textarea name="cat_meta" style="border:solid 1px #555; height:80px" class="form-control col-md-12"></textarea>
                                    </div>
                                    
                                    </div>
                                                            <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصورة</label>
                                        <input name="img_title" style="border:solid 1px #555" class="form-control"  type="text" value="">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الأسم البديل</label>
                                        <input name="alt_txt" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصفحة </label>
                                        <input name="img_title" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Slug</label>
                                        <input name="slug" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>
                                </div>
                         <div class="row g-3">       
                               <div class="col-md-4">
                                    <label for="store_id"
                                           class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">اســـــــم المتجر
                                        :</label>
                                    <div class="box">
                                        <select class="fancyselect form-control" style="border:solid 1px #555" name="store_id">
                                            <option value="">اختـر اســــم المتجر</option>
                                            @foreach ($stores as $store)
                                                <option value="{{ $store->id}}">{{ $store->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                                 <div class="col-md-4">
                                    <label for="store_id"
                                           class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">مكان الظهور
                                        :</label>
                                    <div class="box">
                                        <select class="fancyselect form-control" style="border:solid 1px #555" name="type">
                                            <option value="1">قسم ثانوى</option>
                                             <option value="0">قسم رئيسى</option>
                                        </select>
                                    </div>
                                </div>
                                
                                
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>اختر الصورة الخاصة بالموقع للغة العربية </label>
                                        <input style="border:solid 1px #555" name="image_ar" class="form-control" type="file" aria-label="file example" required="">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>اختر الصورة الخاصة بالموقع للغة الانجليزية </label>
                                        <input style="border:solid 1px #555" name="image_en" class="form-control" type="file" aria-label="file example" required="">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>اختر الصورة الخاصة بالموقع للغة الايطالية </label>
                                        <input style="border:solid 1px #555" name="image_it" class="form-control" type="file" aria-label="file example" required="">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>اختر الصورة الخاصة بالموبيل للغة العربية </label>
                                        <input style="border:solid 1px #555" name="image_mop_ar" class="form-control" type="file" aria-label="file example" required="">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>اختر الصورة الخاصة بالموبيل للغة الانجليزية </label>
                                        <input style="border:solid 1px #555" name="image_mop_en" class="form-control" type="file" aria-label="file example" required="">
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <label>اختر الصورة الخاصة بالموبيل للغة الايطالية </label>
                                        <input style="border:solid 1px #555" name="image_mop_it" class="form-control" type="file" aria-label="file example" required="">
                                    </div>

                                </div>
                                
                                <div class="row g-3">       
                               <div class="col-md-12" style="text-align:center;padding-top:30px">
                                <button class="btn btn-primary" type="submit">حفظ</button>
                                    </div>
                                </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                
                                
                                                          
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection
