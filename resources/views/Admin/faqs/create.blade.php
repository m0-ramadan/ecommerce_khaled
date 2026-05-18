@extends('Admin.layout.master')

@section('title', 'إضافة سؤال شائع جديد')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        /* Card Styles */
        .step-card {
            background: var(--dark-card);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border-left: 4px solid var(--primary-color);
        }

        .step-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--primary-gradient);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            margin-left: 15px;
        }

        /* Form Controls */
        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.5);
        }

        /* Wizard Steps */
        .wizard-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .wizard-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            right: 0;
            left: 0;
            height: 2px;
            background: rgba(255, 255, 255, 0.1);
            z-index: 1;
        }

        .wizard-step {
            position: relative;
            z-index: 2;
            text-align: center;
            flex: 1;
        }

        .wizard-step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
        }

        .wizard-step.active .wizard-step-circle {
            background: var(--primary-gradient);
            color: white;
        }

        .wizard-step.completed .wizard-step-circle {
            background: var(--success-color);
            color: white;
        }

        /* Toggle Switch */
        .toggle-container {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .toggle-switch {
            position: relative;
            width: 56px;
            height: 28px;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.2);
            transition: 0.3s;
            border-radius: 34px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked+.toggle-slider {
            background-color: var(--primary-color);
        }

        input:checked+.toggle-slider:before {
            transform: translateX(28px);
        }

        /* Required Field */
        .required::after {
            content: " *";
            color: var(--danger-color);
        }

        /* Alert Guide */
        .alert-guide {
            background: rgba(105, 108, 255, 0.05);
            border-right: 4px solid var(--primary-color);
            border-radius: 8px;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .alert-guide h6 {
            color: var(--primary-color);
            margin-bottom: 10px;
        }

        /* Summary Card */
        .summary-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .summary-label {
            color: rgba(255, 255, 255, 0.7);
        }

        .summary-value {
            font-weight: 600;
            color: var(--primary-color);
        }

        .action-buttons-fixed {
            position: fixed;
            bottom: 30px;
            left: 30px;
            display: flex;
            gap: 12px;
            z-index: 999;
        }

        .action-buttons-fixed .btn {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        }

        /* FAQ Preview Card */
        .faq-preview-card {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .faq-preview-card:hover {
            background: rgba(105, 108, 255, 0.05);
            border-color: var(--primary-color);
        }

        .faq-preview-question {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .faq-preview-question i {
            color: var(--warning-color);
            margin-top: 3px;
        }

        .faq-preview-answer {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.8;
            padding-right: 25px;
        }

        .faq-preview-status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            margin-top: 15px;
        }

        .status-active {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
        }

        .status-inactive {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
        }

        @media (max-width: 768px) {
            .wizard-steps {
                flex-direction: column;
                gap: 15px;
            }

            .wizard-steps::before {
                display: none;
            }

            .wizard-step {
                display: flex;
                align-items: center;
                gap: 15px;
                text-align: right;
            }

            .wizard-step-circle {
                margin: 0;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent p-0 mb-4">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}" class="text-primary">الرئيسية</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.faqs.index') }}" class="text-primary">الأسئلة الشائعة</a>
                </li>
                <li class="breadcrumb-item active text-white">إضافة سؤال شائع جديد</li>
            </ol>
        </nav>

        <!-- Wizard Steps -->
        <div class="wizard-steps">
            <div class="wizard-step active" id="step1">
                <div class="wizard-step-circle">1</div>
                <div class="wizard-step-label">المعلومات الأساسية</div>
            </div>
            <div class="wizard-step" id="step2">
                <div class="wizard-step-circle">2</div>
                <div class="wizard-step-label">الإجابة والإعدادات</div>
            </div>
            <div class="wizard-step" id="step3">
                <div class="wizard-step-circle">3</div>
                <div class="wizard-step-label">المعاينة والتأكيد</div>
            </div>
        </div>

        <!-- Quick Guide -->
        <div class="alert-guide">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-lightbulb fa-2x" style="color: var(--primary-color);"></i>
                <div>
                    <h6 class="mb-1">نصائح سريعة لإضافة سؤال شائع</h6>
                    <p class="mb-0 text-muted">
                        <i class="fas fa-question-circle me-1"></i> اجعل السؤال واضحاً ومباشراً |
                        <i class="fas fa-reply me-1"></i> قدم إجابة شاملة ومفيدة |
                        <i class="fas fa-sort me-1"></i> رتب الأسئلة حسب الأهمية
                    </p>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Main Form -->
        <form action="{{ route('admin.faqs.store') }}" method="POST" id="faqForm">
            @csrf

            <!-- ============ STEP 1: BASIC INFO ============ -->
            <div class="step-card step-1">
                <div class="step-header">
                    <div class="step-number">1</div>
                    <div>
                        <h5 class="step-title">المعلومات الأساسية</h5>
                        <p class="step-description">أدخل السؤال والترتيب</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="question" class="form-label required">السؤال</label>
                        <input type="text" class="form-control form-control-lg" id="question" name="question"
                            value="{{ old('question') }}" placeholder="أدخل السؤال الشائع هنا..."
                            oninput="updatePreviewQuestion()" required>
                        <small class="text-muted">مثال: كيف يمكنني تتبع طلبي؟</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="sort_order" class="form-label">ترتيب العرض</label>
                        <input type="number" class="form-control" id="sort_order" name="sort_order"
                            value="{{ old('sort_order', 0) }}" min="0" placeholder="0">
                        <small class="text-muted">الأقل يظهر أولاً</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label required">الحالة</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>نشط</option>
                            <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>غير نشط</option>
                        </select>
                    </div>
                </div>

                <!-- Quick Questions Suggestions -->
                <div class="mt-3 p-3" style="background: rgba(105, 108, 255, 0.05); border-radius: 8px;">
                    <small class="text-muted d-block mb-2">
                        <i class="fas fa-magic me-1" style="color: var(--primary-color);"></i>
                        أسئلة مقترحة (انقر للإضافة):
                    </small>
                    <div class="d-flex flex-wrap gap-2">
                        @php
                            $suggestedQuestions = [
                                'كيف يمكنني تتبع طلبي؟',
                                'ما هي طرق الدفع المتاحة؟',
                                'كم تستغرق عملية التوصيل؟',
                                'هل يمكنني إرجاع المنتج؟',
                                'كيف أتواصل مع خدمة العملاء؟',
                            ];
                        @endphp
                        @foreach ($suggestedQuestions as $sq)
                            <button type="button" class="btn btn-outline-primary btn-sm"
                                onclick="document.getElementById('question').value = '{{ $sq }}'; updatePreviewQuestion();">
                                {{ $sq }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <div></div>
                    <button type="button" class="btn btn-primary next-step" data-next="2">
                        التالي <i class="fas fa-arrow-left ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ============ STEP 2: ANSWER & SETTINGS ============ -->
            <div class="step-card step-2" style="display: none;">
                <div class="step-header">
                    <div class="step-number">2</div>
                    <div>
                        <h5 class="step-title">الإجابة والإعدادات</h5>
                        <p class="step-description">اكتب إجابة مفصلة وواضحة</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="answer" class="form-label required">الإجابة</label>
                    <textarea class="form-control summernote" id="answer" name="answer" rows="8" oninput="updatePreviewAnswer()">{{ old('answer') }}</textarea>
                    <small class="text-muted">استخدم المحرر لتنسيق الإجابة وإضافة روابط أو صور</small>
                </div>

                <!-- Quick Answer Templates -->
                <div class="mt-4 p-3" style="background: rgba(40, 167, 69, 0.05); border-radius: 8px; border-right: 3px solid var(--success-color);">
                    <h6 class="mb-3" style="color: var(--success-color);">
                        <i class="fas fa-file-alt me-2"></i>قوالب سريعة للإجابة
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="setAnswerTemplate('shipping')">
                            <i class="fas fa-shipping-fast me-1"></i> قالب الشحن
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="setAnswerTemplate('payment')">
                            <i class="fas fa-credit-card me-1"></i> قالب الدفع
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="setAnswerTemplate('return')">
                            <i class="fas fa-undo me-1"></i> قالب الإرجاع
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm" onclick="setAnswerTemplate('support')">
                            <i class="fas fa-headset me-1"></i> قالب الدعم
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="1">
                        <i class="fas fa-arrow-right me-1"></i> السابق
                    </button>
                    <button type="button" class="btn btn-primary next-step" data-next="3">
                        التالي <i class="fas fa-arrow-left ms-1"></i>
                    </button>
                </div>
            </div>

            <!-- ============ STEP 3: PREVIEW & CONFIRM ============ -->
            <div class="step-card step-3" style="display: none;">
                <div class="step-header">
                    <div class="step-number">3</div>
                    <div>
                        <h5 class="step-title">المعاينة والتأكيد</h5>
                        <p class="step-description">راجع السؤال والإجابة قبل الحفظ</p>
                    </div>
                </div>

                <!-- Live Preview -->
                <div class="mb-4">
                    <h6 class="mb-3"><i class="fas fa-eye me-2" style="color: var(--primary-color);"></i>معاينة مباشرة</h6>
                    <div class="faq-preview-card">
                        <div class="faq-preview-question">
                            <i class="fas fa-question-circle"></i>
                            <span id="previewQuestion">{{ old('question', 'السؤال يظهر هنا...') }}</span>
                        </div>
                        <div class="faq-preview-answer" id="previewAnswer">
                            {{ old('answer', 'الإجابة تظهر هنا...') }}
                        </div>
                        <div>
                            <span class="faq-preview-status" id="previewStatus">
                                @if (old('status', '1') == '1')
                                    <span class="status-active">● نشط</span>
                                @else
                                    <span class="status-inactive">● غير نشط</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="summary-card">
                    <h6 class="mb-3"><i class="fas fa-info-circle me-2"></i>ملخص السؤال الشائع</h6>

                    <div class="summary-row">
                        <span class="summary-label">السؤال:</span>
                        <span class="summary-value" id="summaryQuestion">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">ترتيب العرض:</span>
                        <span class="summary-value" id="summaryOrder">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">الحالة:</span>
                        <span class="summary-value" id="summaryStatus">-</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-label">عدد كلمات الإجابة:</span>
                        <span class="summary-value" id="summaryWordCount">-</span>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary prev-step" data-prev="2">
                        <i class="fas fa-arrow-right me-1"></i> السابق
                    </button>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-1"></i> حفظ السؤال الشائع
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="saveAsDraft()">
                            <i class="fas fa-file-alt me-1"></i> حفظ كمسودة
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Fixed Action Buttons -->
    <div class="action-buttons-fixed">
        <button type="button" class="btn btn-success" onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
            title="العودة للأعلى">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 300,
                lang: 'ar-AR',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onChange: function(contents, $editable) {
                        updatePreviewAnswer();
                    }
                }
            });

            // Initial preview update
            updatePreviewQuestion();
            updatePreviewAnswer();
            updatePreviewStatus();
        });

        // ============================================
        // ⭐ STEP NAVIGATION
        // ============================================
        $('.next-step').click(function() {
            const nextStep = $(this).data('next');
            const currentStep = $(this).closest('.step-card');

            if (validateStep(currentStep)) {
                currentStep.hide();
                $(`.step-${nextStep}`).show();
                updateWizardSteps(nextStep);

                if (nextStep == 3) {
                    updateSummary();
                }
            }
        });

        $('.prev-step').click(function() {
            const prevStep = $(this).data('prev');
            $(this).closest('.step-card').hide();
            $(`.step-${prevStep}`).show();
            updateWizardSteps(prevStep);
        });

        // Form submission
        $('#faqForm').on('submit', function(e) {
            if (!validateAllSteps()) {
                e.preventDefault();
                return false;
            }
        });

        // ============================================
        // ⭐ VALIDATION
        // ============================================
        function validateStep(step) {
            let isValid = true;

            step.find('input[required], select[required], textarea[required]').each(function() {
                const value = $(this).val();
                // For summernote, check if it has content
                if ($(this).hasClass('summernote')) {
                    if (!$(this).summernote('isEmpty')) {
                        $(this).removeClass('is-invalid');
                        $(this).closest('.note-editor').next('.invalid-feedback').remove();
                        return;
                    }
                }

                if (!value || !value.toString().trim()) {
                    $(this).addClass('is-invalid');
                    isValid = false;

                    if (!$(this).next('.invalid-feedback').length &&
                        !$(this).closest('.note-editor').next('.invalid-feedback').length) {
                        if ($(this).hasClass('summernote')) {
                            $(this).closest('.note-editor').after(
                                '<div class="invalid-feedback text-danger">هذا الحقل مطلوب</div>');
                        } else {
                            $(this).after(
                                '<div class="invalid-feedback text-danger">هذا الحقل مطلوب</div>');
                        }
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                    $(this).closest('.note-editor').next('.invalid-feedback').remove();
                }
            });

            return isValid;
        }

        function validateAllSteps() {
            for (let i = 1; i <= 2; i++) {
                const step = $(`.step-${i}`);
                if (!validateStep(step)) {
                    $('.step-card').hide();
                    step.show();
                    updateWizardSteps(i);

                    Swal.fire({
                        icon: 'error',
                        title: 'بيانات ناقصة',
                        text: `يرجى إكمال جميع الحقول المطلوبة في الخطوة ${i}`,
                        confirmButtonText: 'حسناً'
                    });

                    return false;
                }
            }
            return true;
        }

        function updateWizardSteps(activeStep) {
            $('.wizard-step').removeClass('active completed');

            for (let i = 1; i <= 3; i++) {
                if (i < activeStep) {
                    $('#step' + i).addClass('completed');
                } else if (i == activeStep) {
                    $('#step' + i).addClass('active');
                }
            }
        }

        // ============================================
        // ⭐ LIVE PREVIEW FUNCTIONS
        // ============================================
        function updatePreviewQuestion() {
            const question = $('#question').val() || 'السؤال يظهر هنا...';
            $('#previewQuestion').text(question);
            updatePreviewStatus();
        }

        function updatePreviewAnswer() {
            let answer;
            if ($('#answer').hasClass('summernote')) {
                answer = $('#answer').summernote('isEmpty') ? 'الإجابة تظهر هنا...' : $('#answer').summernote('code');
            } else {
                answer = $('#answer').val() || 'الإجابة تظهر هنا...';
            }
            $('#previewAnswer').html(answer);
        }

        function updatePreviewStatus() {
            const status = $('#status').val();
            let statusHtml = '';

            if (status == '1') {
                statusHtml = '<span class="status-active">● نشط</span>';
            } else {
                statusHtml = '<span class="status-inactive">● غير نشط</span>';
            }

            $('#previewStatus').html(statusHtml);
        }

        // Listen to status changes
        $('#status').on('change', function() {
            updatePreviewStatus();
            updateSummary();
        });

        // Listen to sort order changes
        $('#sort_order').on('input', function() {
            updateSummary();
        });

        // ============================================
        // ⭐ SUMMARY UPDATE
        // ============================================
        function updateSummary() {
            const question = $('#question').val() || '-';
            const order = $('#sort_order').val() || '0';
            const statusText = $('#status').val() == '1' ? 'نشط' : 'غير نشط';

            // Count words in answer
            let answerText = '';
            if ($('#answer').hasClass('summernote')) {
                answerText = $('#answer').summernote('isEmpty') ? '' : $($('#answer').summernote('code')).text();
            } else {
                answerText = $('#answer').val() || '';
            }
            const wordCount = answerText.trim() ? answerText.trim().split(/\s+/).length : 0;

            $('#summaryQuestion').text(question);
            $('#summaryOrder').text(order);
            $('#summaryStatus').text(statusText);
            $('#summaryWordCount').text(wordCount + ' كلمة');
        }

        // ============================================
        // ⭐ ANSWER TEMPLATES
        // ============================================
        function setAnswerTemplate(type) {
            const templates = {
                'shipping': `
                        <p>نحن نقدم عدة خيارات للتوصيل لتناسب احتياجاتك:</p>
                        <ul>
                            <li><strong>التوصيل العادي:</strong> يستغرق من 3-5 أيام عمل</li>
                            <li><strong>التوصيل السريع:</strong> يستغرق 1-2 يوم عمل</li>
                            <li><strong>التوصيل في نفس اليوم:</strong> للطلبات داخل المدينة قبل الساعة 12 ظهراً</li>
                        </ul>
                        <p>يمكنك تتبع طلبك من خلال <a href="#">رابط التتبع</a> باستخدام رقم الطلب.</p>
                    `,
                'payment': `
                        <p>نوفر طرق دفع متعددة وآمنة:</p>
                        <ul>
                            <li>الدفع عند الاستلام</li>
                            <li>البطاقات الائتمانية (فيزا - ماستركارد)</li>
                            <li>المحافظ الإلكترونية</li>
                            <li>التحويل البنكي</li>
                        </ul>
                        <p>جميع المعاملات مشفرة وآمنة بالكامل.</p>
                    `,
                'return': `
                        <p>سياسة الإرجاع والاستبدال:</p>
                        <ul>
                            <li>يمكنك إرجاع المنتج خلال 14 يوم من تاريخ الاستلام</li>
                            <li>يجب أن يكون المنتج في حالته الأصلية</li>
                            <li>يتم استرداد المبلغ خلال 7 أيام عمل</li>
                        </ul>
                        <p>للبدء في عملية الإرجاع، يرجى التواصل مع خدمة العملاء.</p>
                    `,
                'support': `
                        <p>فريق خدمة العملاء جاهز لمساعدتك:</p>
                        <ul>
                            <li>📞 الهاتف: 000-000-0000</li>
                            <li>📧 البريد الإلكتروني: support@example.com</li>
                            <li>💬 الدردشة المباشرة: متاحة 24/7</li>
                            <li>⏰ ساعات العمل: من السبت إلى الخميس، 9 صباحاً - 10 مساءً</li>
                        </ul>
                    `
            };

            if (templates[type] && $('#answer').hasClass('summernote')) {
                $('#answer').summernote('code', templates[type]);
                updatePreviewAnswer();
            }
        }

        // ============================================
        // ⭐ SAVE AS DRAFT
        // ============================================
        function saveAsDraft() {
            $('#status').val('0');
            if (validateAllSteps()) {
                $('#faqForm').submit();
            }
        }
    </script>
@endsection