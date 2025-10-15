@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/category')}}">الاقسام الفرعية</a></li>
                            <li class="breadcrumb-item">أضافة قسم فرعي</li>
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
                            <h5>أضافة قسم فرعي</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('subcategory.store')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغــــة العربيـــــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــــة الانجليزيــــــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــــة الايطاليـــــة</a></li>
                                            </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الفرعــي باللغــة العربيــــة</label>
                                                            <input name="name_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <!--<div class="row g-3">-->
                                                    <!--    <div class="col-md-10">-->
                                                    <!--        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــــل باللغــة العربيــــة</label>-->
                                                    <!--        <input name="details_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الفرعــي باللغــة الانجليزيـــة</label>
                                                            <input name="name_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <!--<div class="row g-3">-->
                                                    <!--    <div class="col-md-10">-->
                                                    <!--        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــــل باللغــة الانجليزيـــة</label>-->
                                                    <!--        <input name="details_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الفرعــي باللغــة الايطاليــــة</label>
                                                            <input name="name_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <!--<div class="row g-3">-->
                                                    <!--    <div class="col-md-10">-->
                                                    <!--        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــــل باللغــة الايطاليــــة</label>-->
                                                    <!--        <input name="details_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">-->
                                                    <!--    </div>-->
                                                    <!--</div>-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                          
                                            
                                <!--              <div class="row g-3">-->
                                <!--    <div class="col-md-12">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Meta description</label>-->
                                <!--     <textarea name="cat_meta" style="border:solid 1px #555; height:80px" class="form-control col-md-12"></textarea>-->
                                <!--    </div>-->
                                    
                                <!--    </div>-->
                                <!--                            <div class="row g-3">-->
                                <!--    <div class="col-md-3">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصورة</label>-->
                                <!--        <input name="img_title" style="border:solid 1px #555" class="form-control"  type="text" value="">-->
                                <!--    </div>-->
                                <!--    <div class="col-md-3">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الأسم البديل</label>-->
                                <!--        <input name="alt_txt" style="border:solid 1px #555" class="form-control"  type="text">-->
                                <!--    </div>-->

                                <!--    <div class="col-md-3">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصفحة </label>-->
                                <!--        <input name="img_title" style="border:solid 1px #555" class="form-control"  type="text">-->
                                <!--    </div>-->
                                <!--    <div class="col-md-3">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Slug</label>-->
                                <!--        <input name="slug" style="border:solid 1px #555" class="form-control"  type="text">-->
                                <!--    </div>-->
                                <!--</div>-->
                                    
                                    
                                <div class="row g-3">
                                    <div class="col-md-5">
                                        <label for="store_id" class="mr-sm-2">اسم المتجر :</label>
                                        <div class="box">
                                            <select style="border:solid 1px #555" class="form-control mainSt" name="store_id" id="st">
                                                <option value="">اختر اسم المتجر</option>
                                                @foreach ($stores as $atore)
                                                    <option value="{{ $atore->id}}">{{ $atore->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="store_id" class="mr-sm-2">اسم القسم الرئيسي :</label>
                                        <div class="box">
                                            <select style="border:solid 1px #555" class="fancyselect form-control" id="category_id" name="category_id">
                                                <option value="">اختر المتجر اولا"</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                 <div class="col-md-10"> 
                                      <input name="image" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example" required="">
                                   <div class="invalid-feedback">Example invalid form file feedback</div>
                                </div>
                                
                                <br>
                                <br>
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

@section('script')
    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on('click','.form-control.mainSt', function(e) {
                e.preventDefault();
                var cat = $('.form-control.mainSt').val();
                /**Ajax code**/
                $.ajax({
                    type: "POST",
                    url:"{{route('cateStore')}}",
                    data:{cat:cat},
                    success: function (data) {
                        $('select[name="category_id"]').empty();
                        $('select[name="category_id"]').append('<option selected value="">غير تابع لمتجر</option>');
                        $('select[name="category_id"]').append(data.data);
                    },
                    error:function(){
                        toastr.error('برجاء اختيار متجر');
                    }
                });
                /**Ajax code ends**/
            });
        });
    </script>
@endsection
