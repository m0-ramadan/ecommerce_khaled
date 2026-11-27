<footer class="content-footer footer bg-footer-theme">
    <div class="container-xxl">
        <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
            <div class="small text-muted">
                جميع الحقوق محفوظة &copy; {{ date('Y') }}
                <a href="{{ env('APP_URL') }}" target="_blank" class="footer-link fw-semibold" style="color: #14213d;">
                    {{ env('APP_NAME', 'تالا الجزيرة') }}
                </a>
            </div>
        </div>
    </div>
</footer>
