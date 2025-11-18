@extends('Admin.auth.layouts.master')
@section('content')
    <form class="theme-form login-form" action="{{ route('admin.login') }}" method="post">
        @csrf
        <h4>تسجيل الدخول</h4>
        <h6>مرحباً بعودتك! قم بتسجيل الدخول إلى حسابك.</h6>

        <div class="form-group">
            <label>البريد الإلكتروني</label>
            <div class="input-group">
                <span class="input-group-text"><i class="icon-email"></i></span>
                <input class="form-control" type="email" name="email" required placeholder="Test@gmail.com" dir="ltr"
                    style="text-align:left">
            </div>
        </div>

        <div class="form-group">
            <label>كلمة المرور</label>
            <div class="input-group">
                <span class="input-group-text"><i class="icon-lock"></i></span>

                <input class="form-control" type="password" name="password" id="password" required placeholder="*********"
                    aria-describedby="togglePasswordBtn" autocomplete="current-password">

                <!-- استخدم زر type="button" حتى لا يرسل الفورم عند النقر -->
                <button type="button" class="input-group-text cursor-pointer" id="togglePasswordBtn"
                    aria-label="Toggle password visibility" title="إظهار/إخفاء كلمة المرور">
                    <i class="icon-eye-off" id="toggleIcon" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <div class="form-group">
            <a href="{{ route('admin.password.request') }}" class="text-sm text-primary">هل نسيت كلمة المرور؟</a>
        </div>
        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit">تسجيل الدخول</button>
        </div>
    </form>

    <script>
        (function() {
            const password = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const icon = document.getElementById('toggleIcon');

            if (!password || !toggleBtn || !icon) return;

            toggleBtn.addEventListener('click', function() {
                const isHidden = password.type === 'password';

                // بدل نوع الحقل
                password.type = isHidden ? 'text' : 'password';

                // ---- طريقة آمنة لتبديل كلاسات الأيقونة ----
                // حذف كل كلاسات العيون الشائعة أولاً (تضمن إزالة أي اسم قديم)
                icon.classList.remove('icon-eye', 'icon-eye-off', 'fa-eye', 'fa-eye-slash', 'bi-eye',
                    'bi-eye-slash');

                // أضف الكلاس المناسب لمكتبة الأيقونات عندك
                if (isHidden) {
                    // كانت مخفية والآن أظهرناها -> نريد أيقونة "eye" المفتوح
                    icon.classList.add('icon-eye'); // مثال لـالمشروع الحالي
                    // إذا تستخدم FontAwesome: icon.classList.add('fa', 'fa-eye');
                    // إذا تستخدم Bootstrap Icons: icon.classList.add('bi', 'bi-eye');
                } else {
                    // كانت ظاهرة والآن أخفيناها -> نريد أيقونة "eye-off"
                    icon.classList.add('icon-eye-off');
                    // FontAwesome: icon.classList.add('fa', 'fa-eye-slash');
                    // Bootstrap Icons: icon.classList.add('bi', 'bi-eye-slash');
                }

                // تحديث سمات الوصول
                toggleBtn.setAttribute('aria-pressed', String(isHidden));
                toggleBtn.setAttribute('aria-label', isHidden ? 'إخفاء كلمة المرور' : 'إظهار كلمة المرور');
            });
        })();
    </script>
@endsection
