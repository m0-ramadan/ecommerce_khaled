
@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item">أضافة الالهام والافكار</li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">أضافـــة</h5>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data" method="post" action="{{route('inspiration.store')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="form-control">
                                                        <div class="row g-3">
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">كود الاول -المستخدم فى الموبايل</label>
                                                                <input style="border:solid 1px #555" name="url_link" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                             <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الأول افقى</label>
                                                                <input style="border:solid 1px #555" name="location_h" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الأول رائيسى</label>
                                                                <input style="border:solid 1px #555" name="location_v" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">الكود الثانى</label>
                                                                <input style="border:solid 1px #555" name="url_link1" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>
                                                            
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الثانى افقى</label>
                                                                <input style="border:solid 1px #555" name="location_h1" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الثانى رئيسى </label>
                                                                <input style="border:solid 1px #555" name="location_v1" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                            
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> الكود الثالث</label>
                                                                <input style="border:solid 1px #555" name="url_link2" class="form-control" id="validationCustom01" type="text"   >
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الثالث افقى</label>
                                                                <input style="border:solid 1px #555" name="location_h2" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الثالث رائيسى</label>
                                                                <input style="border:solid 1px #555" name="location_v2" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>
                                                            
                                                            <div class="col-md-6">
                                                                <label class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">مكان الصورة ف الصفحة:</label>
                                                                <div class="box ">
                                                                    <select class="fancyselect form-control" style="border:solid 1px #555" name="type">
                                                                        <option value="0">القسم الايمن</option>
                                                                        <option value="1">القسم الوسط</option>
                                                                        <option value="2">القسم الايسر</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <br>
                                                        
                                                          <br>
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اختر الصورة</label>
                                                            <input style="border:solid 1px #555" name="image" class="form-control" type="file" aria-label="file example" required="">
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
