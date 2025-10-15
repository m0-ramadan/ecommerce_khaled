@extends('admin.app')
@section('content')

    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــة</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/store')}}" style="font-family: 'Cairo', sans-serif;">المتاجـــر</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">أضــافـة متجــر</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 style="font-family: 'Cairo', sans-serif;">أضــافــــة متجــر جـديــد</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('store.store')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـــــة العربيـــــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــــة الانجليزيــــــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــــة الايطاليــــــة</a></li>
                                            </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> اســـم المتجــر باللغـــة العربيـــة</label>
                                                            <input style="border:solid 1px #555" name="name_ar" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> العنوان باللغـــة العربيـــة</label>
                                                            <input style="border:solid 1px #555" name="address_ar" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">الاحكام والشروط باللغـــة العربيـــة</label>
                                                                <textarea style="border:solid 1px #555; height:150px" class="form-control" name="judgments_ar"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">عن المتجر باللغـــة العربيـــة</label>
                                                                <textarea style="border:solid 1px #555; height: 150px;" class="form-control" name="about_ar"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">سياسية الاستبدال باللغـــة العربيـــة</label>
                                                                <textarea style="border:solid 1px #555;height: 150px" class="form-control" name="replacement_ar"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> اســـم المتجــر باللغـــة الانجليزيـــــة</label>
                                                            <input style="border:solid 1px #555" name="name_en" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> العنوان باللغـــة الانجليزيـــــة</label>
                                                            <input style="border:solid 1px #555" name="address_en" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">الاحكام والشروط باللغـــة الانجليزيـــــة</label>
                                                                <textarea style="border:solid 1px #555; height: 150px;" class="form-control" name="judgments_en"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">عن المتجر باللغـــة الانجليزيـــــة</label>
                                                                <textarea style="border:solid 1px #555; height: 150px" class="form-control" name="about_en"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">سياسية الاستبدال باللغـــة الانجليزيـــــة</label>
                                                                <textarea style="border:solid 1px #555; height: 150px" class="form-control" name="replacement_en"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> اســـم المتجــر باللغـــة الايطاليـــــة</label>
                                                            <input style="border:solid 1px #555" name="name_it" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> العنوان باللغـــة الايطاليـــــة</label>
                                                            <input style="border:solid 1px #555" name="address_it" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">الاحكام والشروط باللغـــة الايطاليـــــة</label>
                                                                <textarea style="border:solid 1px #555; height: 150px" class="form-control" name="judgments_it"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">عن المتجر باللغـــة الايطاليـــــة</label>
                                                                <textarea style="border:solid 1px #555; height: 150px" class="form-control" name="about_it"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="form-check">
                                                            <div class="col-md-10">
                                                                <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">سياسية الاستبدال باللغـــة الايطاليـــــة</label>
                                                                <textarea style="border:solid 1px #555; height: 150px" class="form-control" name="replacement_it"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">رابط الفيس يوك</label>
                                        <input style="border:solid 1px #555" name="facebook" class="form-control" id="validationCustom01" type="text" value="" required="">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">رابط تويتر</label>
                                        <input style="border:solid 1px #555" name="twitter" class="form-control" id="validationCustom01" type="text" value="" required="">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">رابط انستاجرام</label>
                                        <input style="border:solid 1px #555" name="instagram" class="form-control" id="validationCustom01" type="text" value="" required="">
                                    </div>
                                </div>
                                <br>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">رقـم الهاتــف</label>
                                        <input style="border:solid 1px #555" name="phone" class="form-control" id="validationCustom01" type="text" value="" required="">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">كلمة المرور</label>
                                        <input style="border:solid 1px #555" name="password" class="form-control" id="validationCustom01" type="text" value="" required="">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اختـــر صورة</label>
                                        <input style="border:solid 1px #555" name="image" class="form-control" type="file" aria-label="file example" required="">
                                    </div>
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
