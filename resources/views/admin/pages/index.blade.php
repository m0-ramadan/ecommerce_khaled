@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Modal -->
        <!-- Button trigger modal -->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">الصفحات</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;"> الصفحات</li>
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
                <!-- DOM / jQuery  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <a href="{{ url(route('pages.create')) }}"><button class="btn-success btn-lg"
                                        style="font-family: 'Cairo', sans-serif;">أضـــــافة </button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العنوان</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الصـــورة</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العمليـــــات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0;

                                        ?>
                                        @foreach ($pages as $page)
                                            <tr>
                                                <?php $i++; ?>
                                                <td>{{ $i }}</td>
                                                <td style="font-family: 'Cairo', sans-serif;">{{ $page->title }}</td>
                                                <td><img width="120px" src="{{ asset('public/' . $page->image) }}"></td>
                                                <td>
                                                    {{-- <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target=""><i class="fa fa-remove"></i>
                                                </button> --}}
                                                    <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#edit{{ $page->id }}"><i class="fa fa-edit"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <!-- edit_modal_Grade -->
                                            <div class="modal fade" id="edit{{ $page->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="max-width:950px">
                                                    <div class="modal-content" style="width:950px">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"
                                                                style="font-family: 'Cairo', sans-serif;">تعديـل </h5>
                                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <form action="{{ route('pages.update', $page->id) }}"
                                                                method="post" enctype="multipart/form-data">
                                                                {{ method_field('patch') }}
                                                                @csrf
                                                                <div class="col-sm-12 col-xl-6 xl-100">
                                                                    <div class="card">
                                                                        <div class="card-body">
                                                                            <ul class="nav nav-tabs" id="icon-tab"
                                                                                role="tablist">
                                                                                <li class="nav-item"><a
                                                                                        class="nav-link active"
                                                                                        id="icon-home-tab"
                                                                                        data-bs-toggle="tab"
                                                                                        href="#icon-home{{ $page->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="icon-home"
                                                                                        aria-selected="true"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغـة
                                                                                        العربيــة</a></li>
                                                                                <li class="nav-item"><a class="nav-link"
                                                                                        id="profile-icon-tab"
                                                                                        data-bs-toggle="tab"
                                                                                        href="#profile-icon{{ $page->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="profile-icon"
                                                                                        aria-selected="false"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغـة
                                                                                        الانجليزيـــة</a></li>
                                                                                {{-- <li class="nav-item"><a class="nav-link"
                                                                                        id="contact-icon-tab"
                                                                                        data-bs-toggle="tab"
                                                                                        href="#contact-icon{{ $page->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="contact-icon"
                                                                                        aria-selected="false"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغــة
                                                                                        الايطاليـة</a></li> --}}
                                                                            </ul>
                                                                            <br>
                                                                            <div class="tab-content" id="icon-tabContent">
                                                                                <div class="tab-pane fade show active"
                                                                                    id="icon-home{{ $page->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="icon-home-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">عنوان
                                                                                                    الصفحة عـربي</label>
                                                                                                <input name="title_ar"
                                                                                                    class="form-control"
                                                                                                    type="text"
                                                                                                    value="{{ $page->getTranslation('title', 'ar') }}"
                                                                                                    required="">
                                                                                                <input id="id"
                                                                                                    type="hidden"
                                                                                                    name="id"
                                                                                                    class="form-control"
                                                                                                    value="{{ $page->id }}">
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="row g-3">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">محتوي
                                                                                                    الصفحة باللغـة
                                                                                                    العربيــة</label>
                                                                                                <div class="row">
                                                                                                    <div class="">
                                                                                                        <textarea name="body_ar" cols="30" rows="10" class="ckeditor">{!! $page->getTranslation('content', 'ar') !!}</textarea>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>


                                                                                        {{-- <div class="row g-3">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">محتوي
                                                                                                    الصفحة باللغـة العربية
                                                                                                    فى التطبيق </label>
                                                                                                <div class="row">
                                                                                                    <div class="">
                                                                                                        <textarea name="contact_app_ar" class="form-control" style="width:100%;border:solid 1px #555; height:400px">{!! $page->getTranslation('content_app', 'ar') !!}</textarea>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div> --}}

                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="profile-icon{{ $page->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="profile-icon-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">عنوان
                                                                                                    الصفحة باللغــة
                                                                                                    الانجليزيـــة</label>
                                                                                                <input name="title_en"
                                                                                                    class="form-control"
                                                                                                    id="validationCustom01"
                                                                                                    type="text"
                                                                                                    value="{{ $page->getTranslation('title', 'en') }}">

                                                                                            </div>
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">المحتوي
                                                                                                    باللغـــة
                                                                                                    الانجليزيــــة</label>
                                                                                                <div class="row">
                                                                                                    <div class="">
                                                                                                        <textarea name="body_en" cols="30" rows="10" class="ckeditor">{!! $page->getTranslation('content', 'en') !!}</textarea>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
{{-- 
                                                                                        <div class="row g-3">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">محتوي
                                                                                                    الصفحة باللغـة
                                                                                                    الأنجليزية فى التطبيق
                                                                                                </label>
                                                                                                <div class="row">
                                                                                                    <div class="">
                                                                                                        <textarea name="contact_app_en" class="form-control" style="width:100%;border:solid 1px #555; height:400px">{!! $page->getTranslation('content_app', 'en') !!}</textarea>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div> --}}

                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="contact-icon{{ $page->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="contact-icon-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">عنوان
                                                                                                    الصفحة باللغــة
                                                                                                    الايطاليـــة</label>
                                                                                                <input name="title_it"
                                                                                                    class="form-control"
                                                                                                    id="validationCustom01"
                                                                                                    type="text"
                                                                                                    value="{{ $page->getTranslation('title', 'it') }}">
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">المحتوي
                                                                                                    بللغـــة الأيطالية
                                                                                                    الموقع </label>
                                                                                                <div class="row">
                                                                                                    <div class="">
                                                                                                        <textarea name="body_it" cols="30" rows="10" class="ckeditor">{!! $page->getTranslation('content', 'it') !!}</textarea>

                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>


                                                                                            <div class="row g-3">
                                                                                                <div class="col-md-12">
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="validationCustom01"
                                                                                                        style="font-family: 'Cairo', sans-serif;">محتوي
                                                                                                        الصفحة باللغـة
                                                                                                        الايطاليـــــة فى
                                                                                                        التطبيق </label>
                                                                                                    <div class="row">
                                                                                                        <div
                                                                                                            class="">
                                                                                                            <textarea class="form-control" name="contact_app_it" style="width:100%;border:solid 1px #555; height:400px">{!! $page->getTranslation('content_app', 'it') !!}</textarea>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>


                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <br>
                                                                <div>
                                                                    <img width="120px"
                                                                        src="{{ asset('public/' . $page->image) }}">
                                                                    <input class="form-control" type="file"
                                                                        aria-label="file example" name="image">
                                                                </div>
                                                                <br>
                                                                <div>
                                                                    <img width="120px"
                                                                        src="{{ asset('public/' . $page->bg_image) }}">
                                                                    <input class="form-control" type="file"
                                                                        aria-label="file example" name="bg_image">
                                                                </div>
                                                                <br>
                                                                <div class="modal-footer">
                                                                    <button class="btn btn-primary" type="button"
                                                                        data-bs-dismiss="modal">غلق</button>
                                                                    <button class="btn btn-secondary"
                                                                        type="submit">حفظ</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- delete_modal_Grade -->
                                            <div class="modal fade" id="exampleModal{{ $page->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">حذف الصفحة</h5>
                                                            <button class="btn-close" type="button"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('pages.destroy', $page->id) }}"
                                                            method="post">
                                                            {{ method_field('Delete') }}
                                                            @csrf
                                                            <input id="id" type="hidden" name="id"
                                                                class="form-control" value="{{ $page->id }}">
                                                            <div class="modal-body">هل أنت متاكد من الحذف</div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button"
                                                                    data-bs-dismiss="modal">غلق</button>
                                                                <button class="btn btn-secondary"
                                                                    type="submit">حذف</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DOM / jQuery  Ends-->
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
@section('toaster')
    <!-- Plugins JS start-->
    <script src="{{ asset('admin/assets/js/editor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/assets/js/editor/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ asset('admin/assets/js/editor/ckeditor/styles.js') }}"></script>
    <script src="{{ asset('admin/assets/js/editor/ckeditor/ckeditor.custom.js') }}"></script>
    <!-- Plugins JS Ends-->
    <!-- Theme js-->
    <script src="{{ asset('admin/assets/js/script.js') }}"></script>
    <script src="{{ asset('admin/assets/js/theme-customizer/customizer.js') }}"></script>
    <!-- login js-->
    <!-- Plugin used-->
    @jquery
    @toastr_js
    @toastr_render
@endsection
