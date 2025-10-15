@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/story')}}">قصص النجاح</a></li>
                            <li class="breadcrumb-item">أضافة قصـة جديدة</li>
                        </ol>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">أضـافــة قصـة جديدة</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('story.store')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغــــة العربيـــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــــة الانجليزيـــــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــــة الايطاليـــــة</a></li>
                                            </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-5">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنـــوان القصة باللغـــة العربية</label>
                                                            <input name="title_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتـوي القصـة باللغـــة العربية</label>
                                                            <textarea name="body_ar" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12" style="height: 200px"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-5">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنـــوان القصة باللغـــة الانجليزيــــة</label>
                                                            <input name="title_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتـوي القصـة باللغـــة الانجليزيــــة</label>
                                                            <textarea name="body_en" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12" style="height: 200px"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-5">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنـــوان القصة باللغـــة الايطاليـــــة</label>
                                                            <input name="title_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتـوي القصـة باللغـــة الايطاليـــــة</label>
                                                            <textarea name="body_it" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12" style="height: 200px"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اختر الصورة</label>
                                        <input name="image[]" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example" required="" multiple>
                                    </div>
                                </div>

                                <br>
                                <br>
                                <button class="btn btn-primary" type="submit" style="font-family: 'Cairo', sans-serif;">حفـــظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection

