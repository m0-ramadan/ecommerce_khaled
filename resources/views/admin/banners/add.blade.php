@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;"> الصــــور المتحـــــركــة</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/banners')}}">الصـور المتحركــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">أضافــة صـوره متحركـة</li>
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 style="font-family: 'Cairo', sans-serif;">أضـــــافة صــــوره متحركـة</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('banners.store')}}">
                                @csrf
                                <br>

                                <div class="col-md-10">
                                    <label> اختر صورة البانر الخاصة بالموقع للغة العربية</label>
                                    <input style="border:solid 1px #555" name="image_ar" class="form-control" type="file" aria-label="file example" required="">
                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                </div>
                                 <div class="col-md-10">
                                    <label> اختر صورة البانر الخاصة بالموقع للغة الانجليزية</label>
                                    <input style="border:solid 1px #555" name="image_en" class="form-control" type="file" aria-label="file example" required="">
                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                </div>
                                 <div class="col-md-10">
                                    <label> اختر صورة البانر الخاصة بالموقع للغة الايطالية</label>
                                    <input style="border:solid 1px #555" name="image_it" class="form-control" type="file" aria-label="file example" required="">
                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                </div>
                                
                                <br>
                                <div class="col-md-10">
                                    <label> اختر صورة البانر الخاصة بالموبيل للغة العربية</label>
                                    <input style="border:solid 1px #555" name="image_mop_ar" class="form-control" type="file" aria-label="file example" required="">
                                </div>
                                 <div class="col-md-10">
                                    <label> اختر صورة البانر الخاصة بالموبيل للغة الانجليزية</label>
                                    <input style="border:solid 1px #555" name="image_mop_en" class="form-control" type="file" aria-label="file example" required="">
                                </div>
                                 <div class="col-md-10">
                                    <label> اختر صورة البانر الخاصة بالموبيل للغة الايطالية</label>
                                    <input style="border:solid 1px #555" name="image_mop_it" class="form-control" type="file" aria-label="file example" required="">
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
