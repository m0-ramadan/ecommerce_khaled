@extends('admin.app')
@section('content')

    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">اضـــــافة مقــال جديــــــد</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــة</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/blog')}}" style="font-family: 'Cairo', sans-serif;">المقـــــــالات</a></li>
                            <li class="breadcrumb-item">اضافة مقال جديد</li>
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
                <div class="col-sm-12 col-xl-6 xl-100">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>اضـــــــافة مقــــال جديـــــد </h5>
                        </div>
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true"><i class="icofont icofont-ui-home"></i>اللغة العربية</a></li>
                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>اللغة الانجليزية</a></li>
                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon" role="tab" aria-controls="contact-icon" aria-selected="false"><i class="icofont icofont-contacts"></i>اللغة الايطالية</a></li>
                            </ul>
                            <div class="tab-content" id="icon-tabContent">
                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                    <p class="mb-0 m-t-30">
                                            <form class="needs-validation" novalidate="" method="post" action="{{url(route('blog.store'))}}" enctype="multipart/form-data">
                                                @csrf
                                                 <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="mr-sm-2" for="validationCustom01">عنوان المقال عربي</label>
                                                        <input style="border:solid 1px #555" name="title_ar" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                    </div>
                                                </div>
                                                <br>
                                                <div class="row">
                                                    <label class="mr-sm-2">محتوي المقال عربي</label>
                                                    <div class="">
                                                        <div ><textarea name="content_ar" class="col-md-12" style="height: 200px"></textarea></div>
                                                    </div>
                                                </div>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                    <p class="mb-0 m-t-30">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="mr-sm-2" for="validationCustom01">عنوان المقال انجليزي</label>
                                                <input style="border:solid 1px #555" name="title_en" class="form-control" id="validationCustom01" type="text" value="" required="">
                                            </div>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <label class="mr-sm-2">محتوي المقال انجليزي</label>
                                            <div class="">
                                                <div ><textarea name="content_en" class="col-md-12" style="height: 200px"></textarea></div>
                                            </div>
                                        </div>
                                        <br>
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                    <p class="mb-0 m-t-30">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="mr-sm-2" for="validationCustom01">عنوان المقال باللغة الايطالية</label>
                                            <input style="border:solid 1px #555" name="title_it" class="form-control" id="validationCustom01" type="text" value="" required="">
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <label class="mr-sm-2">محتوي المقال باللغة الايطالية</label>
                                        <div class="">
                                            <div ><textarea name="content_it" class="col-md-12" style="height: 200px"></textarea></div>
                                        </div>
                                    </div>
                                    <br>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
{{--                            <form class="needs-validation" novalidate="" method="post" action="{{url(route('blog.store'))}}" enctype="multipart/form-data">--}}
{{--                                @csrf--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <label class="form-label" for="validationCustom01">Title</label>--}}
{{--                                        <input name="title" class="form-control" id="validationCustom01" type="text" value="" required="">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <br>--}}
{{--                                <div class="row">--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <label class="form-label" for="validationCustom01">Title ar</label>--}}
{{--                                        <input name="title_ar" class="form-control" id="validationCustom01" type="text" value="" required="">--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <br>--}}
{{--                                <div class="row">--}}
{{--                                    <label class="form-label">Content</label>--}}
{{--                                    <div class="">--}}
{{--                                        <div ><textarea name="content" class="col-md-12" style="height: 200px"></textarea></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <br>--}}
{{--                                <div class="row">--}}
{{--                                    <label class="form-label">Content ar</label>--}}
{{--                                    <div class="">--}}
{{--                                        <div ><textarea name="content_ar" class="col-md-12" style="height: 200px"></textarea></div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                <br>--}}
{{--                                <br>--}}
                                <div>
                                    <input class="form-control" type="file" aria-label="file example" required="" name="image">
                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                </div>
                                <br>
                                <br>
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection
