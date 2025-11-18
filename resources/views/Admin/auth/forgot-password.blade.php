@extends('Admin.auth.layouts.master')
@section('content')
    <form class="theme-form login-form" action="{{ route('admin.password.email') }}" method="post">
        @csrf
        <h4>استعادة كلمة المرور</h4>
        <h6>أدخل بريدك الإلكتروني لاستلام رابط إعادة تعيين كلمة المرور.</h6>

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <div class="input-group">
                <span class="input-group-text"><i class="icon-email"></i></span>
                <input class="form-control" type="email" name="email" required placeholder="Test@gmail.com">
            </div>
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit">إرسال رابط إعادة التعيين</button>
        </div>

        <div class="form-group">
            <a href="{{ route('admin.login') }}" class="text-sm text-primary">العودة لتسجيل الدخول</a>
        </div>
    </form>
@endsection
