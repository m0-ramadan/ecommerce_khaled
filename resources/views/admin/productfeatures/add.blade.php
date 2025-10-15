@extends('admin.app')
@section('content')

    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">مميزات المنتج </h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيســـــية</a></li>
                            <li class="breadcrumb-item"> <a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">المنتجــــات</a>  </li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">اضــــافة ميزة جديدة للمنتج</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" method="POST" enctype="multipart/form-data" action="{{route('productfeaturestore')}}">
                                @csrf
                                
                                <input type="hidden"  name="product_id"  value={{$id}}>
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـــة العربية</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــة الانجليزيـــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــة الايطاليــــة</a></li>
                                            </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-8">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـــم باللغــة العربيـــة</label>
                                                            <input name="name_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{ old('name') }}" required>

                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="col-md-8">
                                                        <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغــة العربيـــة</label>
                                                        <div class="row">
                                                            <div class="">
                                                                <textarea name="description_ar" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-8">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـــم بللغــة الانجليــزيــة</label>
                                                            <input name="name_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{ old('name') }}" required>

                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="col-md-8">
                                                        <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغــة الانجليــزيــة</label>
                                                        <div class="row">
                                                            <div class="">
                                                                <textarea name="description_en" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-8">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـــم بللغــة الايطاليـــة</label>
                                                            <input name="name_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{ old('name') }}" required>

                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="col-md-8">
                                                        <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغــة الايطاليـــة</label>
                                                        <div class="row">
                                                            <div class="">
                                                                <textarea name="description_it" style="border:solid 1px #555; height:200px" class="form-control col-md-12"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                          
                                <button class="btn btn-primary" type="submit">حفـــظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection



