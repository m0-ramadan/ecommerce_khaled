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
                        <h3 style="font-family: 'Cairo', sans-serif;">العناوين الرئيسيـــــة</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">العناوين الرئيسيـــــــة
                            </li>
                        </ol>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>2
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
                            {{-- <div>
                                <a href="{{url(route('titles.create'))}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">أضـــــافة </button></a>
                            </div> --}}
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العنوان</th>
                                            <th style="font-family: 'Cairo', sans-serif;">اسم الصفحة</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العمليـــــات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; ?>
                                        @foreach ($titles as $title)
                                            <tr style="text-align: center">
                                                <?php $i++; ?>
                                                <td>{{ $i }}</td>
                                                <td style="font-family: 'Cairo', sans-serif;">{{ $title->title }}</td>
                                                <td style="font-family: 'Cairo', sans-serif;">{{ $title->page_name }}</td>
                                                <td>
                                                    <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test"
                                                        data-bs-target="#edit{{ $title->id }}"><i class="fa fa-edit"></i>
                                                    </button>
                                                    {{-- <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$title->id}}"><i class="fa fa-remove"></i>
                                                </button> --}}
                                                </td>
                                            </tr>

                                            <!-- edit_modal_Grade -->
                                            <div class="modal fade" id="edit{{ $title->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel"
                                                                style="font-family: 'Cairo', sans-serif;">تعديـل </h5>
                                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('titles.update', $title->id) }}"
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
                                                                                        href="#icon-home{{ $title->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="icon-home"
                                                                                        aria-selected="true"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغـة
                                                                                        العربيــة</a></li>
                                                                                <li class="nav-item"><a class="nav-link"
                                                                                        id="profile-icon-tab"
                                                                                        data-bs-toggle="tab"
                                                                                        href="#profile-icon{{ $title->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="profile-icon"
                                                                                        aria-selected="false"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغــة
                                                                                        الانجليزيــة</a></li>
                                                                                <li class="nav-item"><a class="nav-link"
                                                                                        id="contact-icon-tab"
                                                                                        data-bs-toggle="tab"
                                                                                        href="#contact-icon{{ $title->id }}"
                                                                                        role="tab"
                                                                                        aria-controls="contact-icon"
                                                                                        aria-selected="false"
                                                                                        style="font-family: 'Cairo', sans-serif;">اللغة
                                                                                        الايطاليـة</a></li>
                                                                            </ul>
                                                                            <br>
                                                                            <div class="tab-content" id="icon-tabContent">
                                                                                <div class="tab-pane fade show active"
                                                                                    id="icon-home{{ $title->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="icon-home-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                @if (is_null($title->key_word))
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="validationCustom01"
                                                                                                        style="font-family: 'Cairo', sans-serif;">العنوان
                                                                                                        الرئيســي
                                                                                                        عـربي</label>
                                                                                                    <input name="title_ar"
                                                                                                        class="form-control"
                                                                                                        id="validationCustom01"
                                                                                                        type="text"
                                                                                                        value="{{ $title->getTranslation('title', 'ar') }}"
                                                                                                        required="">
                                                                                                @else
                                                                                                    <div>
                                                                                                        <img width="120px"
                                                                                                            src="{{ asset('public/' . $title->getTranslation('title', 'ar')) }}">
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            type="file"
                                                                                                            aria-label="file example"
                                                                                                            name="title_ar">
                                                                                                    </div>
                                                                                                @endif

                                                                                                <input id="id"
                                                                                                    type="hidden"
                                                                                                    name="id"
                                                                                                    class="form-control"
                                                                                                    value="{{ $title->id }}">
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="profile-icon{{ $title->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="profile-icon-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">

                                                                                                @if (is_null($title->key_word))
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="validationCustom01"
                                                                                                        style="font-family: 'Cairo', sans-serif;">العنوان
                                                                                                        الرئيســي باللغــة
                                                                                                        الانجليزيـــة</label>
                                                                                                    <input name="title_en"
                                                                                                        class="form-control"
                                                                                                        id="validationCustom01"
                                                                                                        type="text"
                                                                                                        value="{{ $title->getTranslation('title', 'en') }}">
                                                                                                @else
                                                                                                    <div>
                                                                                                        <img width="120px"
                                                                                                            src="{{ asset('public/' . $title->getTranslation('title', 'en')) }}">
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            type="file"
                                                                                                            aria-label="file example"
                                                                                                            name="title_en">
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="contact-icon{{ $title->id }}"
                                                                                    role="tabpanel"
                                                                                    aria-labelledby="contact-icon-tab">
                                                                                    <div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                @if (is_null($title->key_word))
                                                                                                    <label
                                                                                                        class="form-label"
                                                                                                        for="validationCustom01"
                                                                                                        style="font-family: 'Cairo', sans-serif;">اســـم
                                                                                                        القســم الرئيســي
                                                                                                        باللغــة
                                                                                                        الايطاليـــة</label>
                                                                                                    <input name="title_it"
                                                                                                        class="form-control"
                                                                                                        id="validationCustom01"
                                                                                                        type="text"
                                                                                                        value="{{ $title->getTranslation('title', 'it') }}">
                                                                                                @else
                                                                                                    <div>
                                                                                                        <img width="120px"
                                                                                                            src="{{ asset('public/' . $title->getTranslation('title', 'it')) }}">
                                                                                                        <input
                                                                                                            class="form-control"
                                                                                                            type="file"
                                                                                                            aria-label="file example"
                                                                                                            name="title_it">
                                                                                                    </div>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                        <br>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div>
                                                                    <label> اسم الصفحة</label>
                                                                    <input class="form-control" type="text"
                                                                        name="page_name" value="{{ $title->page_name }}"
                                                                        disabled>
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
                                            <div class="modal fade" id="exampleModal{{ $title->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">حذف القسم
                                                                الرئيسي</h5>
                                                            <button class="btn-close" type="button"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('titles.destroy', $title->id) }}"
                                                            method="post">
                                                            {{ method_field('Delete') }}
                                                            @csrf
                                                            <input id="id" type="hidden" name="id"
                                                                class="form-control" value="{{ $title->id }}">
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
    @jquery
    @toastr_js
    @toastr_render
@endsection
