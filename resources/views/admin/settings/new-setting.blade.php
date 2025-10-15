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
                        <h3 style="font-family: 'Cairo', sans-serif;">اعــــدادات الموقـــــــع</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الاعـــــدادات</li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">اعـــدادات التطبيق</h5>
                        </div>
                        <div class="card-body">


                            <div class="row g-3">
                                @foreach ($settings as $setting)
                                    <form method="post" enctype="multipart/form-data"
                                        action="{{ route('app-settings.update', $setting) }}">
                                        @csrf
                                        @method('put')
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="title_ar" class="form-label">Ar. Title</label>
                                                <input type="text" class="form-control" name="title_ar" id="title_ar" value="{{ $setting->getTranslation('title', 'ar') }}">
                                            </div>

                                            <div class="mb-3">
                                                <label for="title_en" class="form-label">En. Title</label>
                                                <input type="text" class="form-control" name="title_en" id="title_en" value="{{ $setting->getTranslation('title', 'en') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="title_en" class="form-label">It. Title</label>
                                                <input type="text" class="form-control" name="title_it" id="title_it" value="{{ $setting->getTranslation('title', 'it') }}">
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label" for="validationCustom03"
                                                    style="font-family: 'Cairo', sans-serif;">{{ $setting->name }}</label>
                                                <input name="image" style="border:solid 1px #555" class="form-control"
                                                    type="file" aria-label="file example">

                                            </div>
                                            <div class="col-md-12">
                                                <img width="100" src="">
                                            </div>
                                        </div>
                                        <button class="btn btn-sm btn-primary" type="submit">حفـــظ البيانــات</button>
                                    </form>
                                @endforeach
                            </div>

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
