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
                            <li class="breadcrumb-item">أضافة المكافآت</li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">أضافـــة المكافآت</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('bonuses.store')}}">
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
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">بداية المكافآت</label>
                                                                <input style="border:solid 1px #555" name="start_bonus" class="form-control" id="validationCustom01" type="number" value="">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> نهاية المكافآت</label>
                                                                <input style="border:solid 1px #555" name="end_bonus" class="form-control" id="validationCustom01" type="number" value="">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">النقــاط</label>
                                                                <input style="border:solid 1px #555" name="point" class="form-control" id="validationCustom01" type="number" value="" required="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اسم القسم الرئيسي باللغــة الانجليزيـــة</label>
                                                                <input style="border:solid 1px #555" name="name_en" class="form-control" id="validationCustom01" type="text">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغـة الانجليزيـــة</label>
                                                                <input style="border:solid 1px #555" name="description_en" class="form-control" id="validationCustom01" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اسم القسم الرئيسي باللغــة الايطاليـــــة</label>
                                                                <input style="border:solid 1px #555" name="name_it" class="form-control" id="validationCustom01" type="text">
                                                            </div>
                                                        </div>
                                                        <br>
                                                        <div class="row g-3">
                                                            <div class="col-md-10">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغـة الايطاليـــــة</label>
                                                                <input style="border:solid 1px #555" name="description_it" class="form-control" id="validationCustom01" type="text">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
