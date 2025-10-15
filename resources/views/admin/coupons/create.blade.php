@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>إعدادات الكوبونات</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">لوحة التحكم</a></li>
                            <li class="breadcrumb-item">الإعدادات</li>
                        </ol>
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

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5>إعدادات الكوبونات</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" action="{{ route('coupons.store') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">الرمز</label>
                                        <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">قيمة الخصم</label>
                                        <input type="number" name="mount" class="form-control" value="{{ old('mount') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">تاريخ البداية</label>
                                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">تاريخ الانتهاء</label>
                                        <input type="date" name="expiry_date" class="form-control" value="{{ old('expiry_date') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">أقصى عدد للاستخدام لكل مستخدم</label>
                                        <input type="number" name="num_use_user" class="form-control" value="{{ old('num_use_user') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">إجمالي عدد مرات الاستخدام</label>
                                        <input type="number" name="num_times" class="form-control" value="{{ old('num_times') }}" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">الحالة</label>
                                        <select name="status" class="form-control" required>
                                            <option value="1">نشط</option>
                                            <option value="0">غير نشط</option>
                                        </select>
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
    </div>
@endsection
@section('js')
    @toastr_js
    @toastr_render
@endsection
