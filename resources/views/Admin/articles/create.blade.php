@extends('Admin.layout.master')

@section('title', 'إنشاء مقال جديد')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Summernote Editor -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Flatpickr (DateTime Picker) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <style>
        :root {
            --primary-color: #14213d;
            --primary-gradient: linear-gradient(135deg, #14213d 0%, #0c182a 100%);
            --dark-bg: #f6f8fb;
            --dark-card: #ffffff;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #1e293b;
        }

        .card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(15, 23, 42, 0.05);
            border: 1px solid #e2e8f0;
            margin-bottom: 25px;
        }

        .card-header {
            background: var(--primary-gradient);
            color: white;
            padding: 15px 25px;
            border-radius: 15px 15px 0 0;
            border-bottom: none;
        }

        .card-body {
            padding: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #1e293b;
            box-shadow: 0 0 0 0.25rem rgba(20, 33, 61, 0.25);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .summernote {
            background: #f8fafc;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
        }

        .note-editor.note-frame {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
        }

        .note-toolbar {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }

        .note-btn {
            color: #1e293b;
            background: transparent;
            border: 1px solid #e2e8f0;
        }

        .note-btn:hover {
            background: rgba(20, 33, 61, 0.2);
        }

        .note-editing-area {
            background: #f8fafc;
            color: #1e293b;
        }

        .note-placeholder {
            color: #94a3b8;
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(20, 33, 61, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid #e2e8f0;
            color: #1e293b;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }

        .select2-container--default .select2-selection--multiple {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #1e293b;
            border-radius: 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--primary-color);
            border: none;
            border-radius: 20px;
            color: #1e293b;
            padding: 3px 10px;
        }

        .select2-dropdown {
            background: var(--dark-card);
            border: 1px solid #e2e8f0;
        }

        .select2-search__field {
            color: #1e293b;
        }

        .image-preview {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px dashed #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .image-preview img {
            max-height: 100%;
            max-width: 100%;
            border-radius: 10px;
            display: none;
        }

        .image-preview:hover {
            border-color: var(--primary-color);
        }

        .image-preview i {
            font-size: 40px;
            color: #94a3b8;
        }

        .flatpickr-calendar {
            background: var(--dark-card);
            border: 1px solid #e2e8f0;
        }

        .flatpickr-day.selected {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .meta-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: 20px;
            border: 1px solid #e2e8f0;
        }

        .invalid-feedback {
            color: #f86c6b;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" dir="rtl">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">المقالات</a></li>
                <li class="breadcrumb-item active">إنشاء مقال جديد</li>
            </ol>
        </nav>

        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data" id="articleForm">
            @csrf

            <div class="row">
                <!-- العمود الرئيسي -->
                <div class="col-lg-8">
                    <!-- معلومات المقال الأساسية -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>معلومات المقال</h5>
                        </div>
                        <div class="card-body">
                            <!-- العنوان -->
                            <div class="mb-3">
                                <label class="form-label">عنوان المقال *</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title') }}" placeholder="أدخل عنوان المقال" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- المحتوى -->
                            <div class="mb-3">
                                <label class="form-label">المحتوى *</label>
                                <textarea id="summernote" name="content" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الملخص -->
                            <div class="mb-3">
                                <label class="form-label">الملخص</label>
                                <textarea name="excerpt" class="form-control" rows="3" placeholder="ملخص مختصر للمقال (اختياري)">{{ old('excerpt') }}</textarea>
                                <div class="form-text text-muted">أقصى حد 500 حرف. سيظهر في بطاقة المقال.</div>
                            </div>
                        </div>
                    </div>

                    <!-- الصورة المميزة -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-image me-2"></i>الصورة المميزة</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="image-preview" onclick="document.getElementById('imageInput').click()">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <span class="ms-2">اختر صورة</span>
                                        <img id="imagePreviewImg" src="#" alt="معاينة" style="display:none;">
                                    </div>
                                    <input type="file" name="image" id="imageInput" class="d-none" accept="image/*"
                                           onchange="previewImage(event)">
                                    <div class="form-text text-muted mt-2">الصيغ المدعومة: jpeg, png, jpg, gif, webp. الحجم الأقصى 2MB</div>
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">وصف الصورة (alt)</label>
                                    <input type="text" name="image_alt" class="form-control" value="{{ old('image_alt') }}" placeholder="وصف للصورة لتحسين SEO">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO وميتا -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-search me-2"></i>تحسين محركات البحث (SEO)</h5>
                        </div>
                        <div class="card-body">
                            <div class="meta-card">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">العنوان التعريفي (Meta Title)</label>
                                        <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title') }}" placeholder="عنوان SEO">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الكلمات المفتاحية (Meta Keywords)</label>
                                        <input type="text" name="meta_keywords" class="form-control" value="{{ old('meta_keywords') }}" placeholder="كلمة1, كلمة2, ...">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">الوصف التعريفي (Meta Description)</label>
                                    <textarea name="meta_description" class="form-control" rows="3" placeholder="وصف مختصر لمحركات البحث">{{ old('meta_description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- العمود الجانبي -->
                <div class="col-lg-4">
                    <!-- حالة النشر -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-cog me-2"></i>إعدادات النشر</h5>
                        </div>
                        <div class="card-body">
                            <!-- التصنيف -->
                            <div class="mb-3">
                                <label class="form-label">التصنيف *</label>
                                <select name="category_id" class="form-select select2" required>
                                    <option value="">اختر التصنيف</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الكاتب -->
                            <div class="mb-3">
                                <label class="form-label">الكاتب *</label>
                                <select name="author_id" class="form-select select2" required>
                                    <option value="">اختر الكاتب</option>
                                    @foreach ($authors as $author)
                                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('author_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- التاغات -->
                            <div class="mb-3">
                                <label class="form-label">الوسوم (Tags)</label>
                                <select name="tags[]" class="form-select select2-tags" multiple>
                                    @foreach ($tags as $tag)
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- تاريخ النشر -->
                            <div class="mb-3">
                                <label class="form-label">تاريخ النشر</label>
                                <input type="datetime-local" name="published_at" class="form-control" 
                                       value="{{ old('published_at', now()->format('Y-m-d\TH:i')) }}" id="publishedAt">
                                <div class="form-text text-muted">إذا تُرك فارغاً، يمكن نشره لاحقاً كمسودة.</div>
                            </div>

                            <!-- مفعل -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                                <label class="form-check-label" for="isActive">نشط</label>
                            </div>

                            <!-- مميز -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured">
                                <label class="form-check-label" for="isFeatured">مقال مميز</label>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="d-flex justify-content-end gap-3 mt-4">
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>إلغاء
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>نشر المقال
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/ar.js"></script>
    <script>
        $(document).ready(function() {
            // Summernote
            $('#summernote').summernote({
                placeholder: 'اكتب محتوى المقال هنا...',
                tabsize: 2,
                height: 350,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });

            // Select2 للتصنيف والكاتب
            $('.select2').select2({
                placeholder: 'اختر...',
                allowClear: true,
                width: 'resolve'
            });

            // Select2 للتاغات متعددة مع إمكانية الإضافة
            $('.select2-tags').select2({
                placeholder: 'اختر الوسوم',
                tags: true,
                tokenSeparators: [',', '،'],
                width: 'resolve'
            });

            // Flatpickr للتاريخ
            flatpickr("#publishedAt", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                locale: "ar",
                time_24hr: true
            });

            // معاينة الصورة
            window.previewImage = function(event) {
                var reader = new FileReader();
                reader.onload = function() {
                    var output = document.getElementById('imagePreviewImg');
                    output.src = reader.result;
                    output.style.display = 'block';
                    var parent = output.parentElement;
                    parent.querySelector('i').style.display = 'none';
                    parent.querySelector('span').style.display = 'none';
                }
                reader.readAsDataURL(event.target.files[0]);
            }
        });
    </script>
@endsection