@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">شركة الانشاءات</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">شركة الانشاءات</li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">شركة الانشاءات</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" action="{{route('constructionSave')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$construction->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$construction->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيـــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$construction->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الايطاليــة</a></li>
                                            </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home{{$construction->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">العنوان باللغة العربية</label>
                                                                <input name="title_ar" class="form-control" id="validationCustom01" type="text" value="{{$construction->getTranslation('title', 'ar')}}" required="">
                                                                <input id="id" type="hidden" name="id" class="form-control" value="{{ $construction->id }}">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon{{$construction->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">العنوان باللغــة الانجليزيـــة</label>
                                                                <input name="title_en" class="form-control" id="validationCustom01" type="text" value="{{$construction->getTranslation('title', 'en')}}">
                                                            </div>
                                                        </div>
                                                        <br>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon{{$construction->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">العنوان باللغــة الايطاليـــة</label>
                                                                <input name="title_it" class="form-control" id="validationCustom01" type="text" value="{{$construction->getTranslation('title', 'it')}}">
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
                                        <label> الرابط</label>
                                        <input type="text" name="link" class="form-control" value="{{$construction->link}}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">اختـــر الصورة</label>
                                        <input name="image" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">
                                    </div>

                                    <div class="col-md-2">
                                        <img width="100" src="{{asset('public/' . $construction->image)}}">
                                    </div>
                                </div>
                                <hr>


                                <button class="btn btn-sm btn-primary" type="submit">حفـــظ البيانــات</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
@section('js')
    @toastr_js
    @toastr_render
@endsection
