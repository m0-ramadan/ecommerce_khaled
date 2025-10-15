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
                        <h3 style="font-family: 'Cairo', sans-serif;">الدول</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الدول</li>
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
                                <a href="{{ url(route('countries.create')) }}"><button class="btn-success btn-lg"
                                        style="font-family: 'Cairo', sans-serif;">أضافة</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الأسم</th>
                                            <th style="font-family: 'Cairo', sans-serif;">{{ __('front.country_prefix')}}</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العمليـــــات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; ?>
                                        @foreach ($countries as $res)
                                            <tr>
                                                <?php $i++; ?>
                                                <td>{{ $i }}</td>
                                                <td style="font-family: 'Cairo', sans-serif;">{{ $res->name }}</td>
                                                <td style="font-family: 'Cairo', sans-serif;">{{ $res->phone_prefix }}</td>
                                                <td>
                                                    <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#edit{{ $res->id }}"><i class="fa fa-edit"></i>
                                                    </button>
                                                    <a href="{{ route('countriesdestroy', $res->id) }}">
                                                        <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                            data-original-title="test"
                                                            data-bs-target="#exampleModal{{ $res->id }}"><i
                                                                class="fa fa-remove"></i>
                                                        </button></a>
                                                </td>
                                            </tr>

                                            <!-- edit_modal_Grade -->
                                            <div class="modal fade" id="edit{{ $res->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document" style="max-width:950px">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"
                                                                style="font-family: 'Cairo', sans-serif;">تعديـل بيـانـات
                                                                القســم الرئيسـي</h5>
                                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('countries.update', $res->id) }}"
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
                                                                                        href="#icon-home{{ $res->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="icon-home"
                                                                                        aria-selected="true"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغـة
                                                                                        العربيــة</a></li>
                                                                                <li class="nav-item"><a class="nav-link"
                                                                                        id="profile-icon-tab"
                                                                                        data-bs-toggle="tab"
                                                                                        href="#profile-icon{{ $res->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="profile-icon"
                                                                                        aria-selected="false"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغــة
                                                                                        الانجليزيـــة</a></li>
                                                                                {{-- <li class="nav-item"><a class="nav-link"
                                                                                        id="contact-icon-tab"
                                                                                        data-bs-toggle="tab"
                                                                                        href="#contact-icon{{ $res->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="contact-icon"
                                                                                        aria-selected="false"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغــة
                                                                                        الايطاليــة</a></li> --}}
                                                                            </ul>
                                                                            <br>
                                                                            <div class="tab-content" id="icon-tabContent">
                                                                                <div class="tab-pane fade show active"
                                                                                    id="icon-home{{ $res->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="icon-home-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">الاسم
                                                                                                    باللغة العربية</label>
                                                                                                <input name="name_ar"
                                                                                                    class="form-control"
                                                                                                    id="validationCustom01"
                                                                                                    type="text"
                                                                                                    value="{{ $res->getTranslation('name', 'ar') }}"
                                                                                                    required="">
                                                                                                <input id="id"
                                                                                                    type="hidden"
                                                                                                    name="id"
                                                                                                    class="form-control"
                                                                                                    value="{{ $res->id }}">
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="profile-icon{{ $res->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="profile-icon-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">الأسم
                                                                                                    باللغة
                                                                                                    الأنجليزية</label>
                                                                                                <input name="name_en"
                                                                                                    class="form-control"
                                                                                                    id="validationCustom01"
                                                                                                    type="text"
                                                                                                    value="{{ $res->getTranslation('name', 'en') }}">
                                                                                                <input id="id"
                                                                                                    type="hidden"
                                                                                                    name="id"
                                                                                                    class="form-control"
                                                                                                    value="{{ $res->id }}">
                                                                                            </div>
                                                                                        </div>


                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="contact-icon{{ $res->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="contact-icon-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <label class="form-label"
                                                                                                    for="validationCustom01"
                                                                                                    style="font-family: 'Cairo', sans-serif;">الأسم
                                                                                                    باللغة الأيطالية</label>
                                                                                                <input name="name_it"
                                                                                                    class="form-control"
                                                                                                    id="validationCustom01"
                                                                                                    type="text"
                                                                                                    value="{{ $res->getTranslation('name', 'it') }}">
                                                                                                <input id="id"
                                                                                                    type="hidden"
                                                                                                    name="id"
                                                                                                    class="form-control"
                                                                                                    value="{{ $res->id }}">
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mb-3">
                                                                                <label for="country_prefix" class="form-label">{{ __('front.country_prefix')}}</label>
                                                                                <input value="{{ old('country_prefix', $res->phone_prefix) }}" type="text" class="form-control" id="country_prefix" name="country_prefix">
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>



                                                                <div class="row g-3">


                                                                </div>

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
    @jquery
    @toastr_js
    @toastr_render
@endsection
