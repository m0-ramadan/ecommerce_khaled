@extends('Admin.layout.master')

@section('title', 'تعديل المقال')

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
            --primary-color: #696cff;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --dark-bg: #1e1e2d;
            --dark-card: #2b3b4c;
        }

        body {
            font-family: "Cairo", sans-serif !important;
            background: var(--dark-bg);
            color: #fff;
        }

        .card {
            background: var(--dark-card);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
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
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-color);
            color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(105, 108, 255, 0.25);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .summernote {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .note-editor.note-frame {
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .note-toolbar {
            background: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .note-btn {
            color: #fff;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .note-btn:hover {
            background: rgba(105, 108, 255, 0.2);
        }

        .note-editing-area {
            background: rgba(255, 255, 255, 0.05);
            color: #fff;
        }

        .note-placeholder {
            color: rgba(255, 255, 255, 0.4);
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
            box-shadow: 0 5px 15px rgba(105, 108, 255, 0.4);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: #fff;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
        }

        .btn-danger {
            background: #dc3545;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: 600;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
        }

        .select2-container--default .select2-selection--multiple {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background: var(--primary-color);
            border: none;
            border-radius: 20px;
            color: white;
            padding: 3px 10px;
        }

        .select2-container--default .select2-selection--single {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 10px;
            height: auto;
            padding: 8px 15px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #fff;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
        }

        .select2-dropdown {
            background: var(--dark-card);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .select2-search__field {
            color: #fff;
        }

        .image-preview {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .image-preview img {
            max-height: 100%;
            max-width: 100%;
            border-radius: 10px;
        }

        .image-preview:hover {
            border-color: var(--primary-color);
        }

        .image-preview .placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            color: rgba(255, 255, 255, 0.5);
        }

        .image-preview .placeholder i {
            font-size: 40px;
        }

        .image-preview .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
        }

        .image-preview:hover .overlay {
            opacity: 1;
        }

        .current-image-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .flatpickr-calendar {
            background: var(--dark-card);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .flatpickr-day.selected {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .meta-card {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .invalid-feedback {
            color: #ff6b6b;
        }

        .article-stats {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stat-item:last-child {
            border-bottom: none;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.7);
        }

        .stat-value {
            font-weight: 600;
            color: var(--primary-color);
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" dir="rtl">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">المقالات</a></li>
                <li class="breadcrumb-item active">تعديل المقال: {{ Str::limit($article->title, 50) }}</li>
            </ol>
        </nav>

        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" id="articleForm">
            @csrf
            @method('PUT')

            <div class="row">
                <!-- العمود الرئيسي -->
                <div class="col-lg-8">
                    <!-- معلومات المقال الأساسية -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-edit me-2"></i>تعديل المقال</h5>
                            <a href="{{ route('admin.articles.show', $article) }}" class="btn btn-light btn-sm" target="_blank">
                                <i class="fas fa-eye me-1"></i> معاينة
                            </a>
                        </div>
                        <div class="card-body">
                            <!-- العنوان -->
                            <div class="mb-3">
                                <label class="form-label">عنوان المقال *</label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                       value="{{ old('title', $article->title) }}" placeholder="أدخل عنوان المقال" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- slug (للقراءة فقط) -->
                            <div class="mb-3">
                                <label class="form-label">الرابط (Slug)</label>
                                <input type="text" class="form-control" value="{{ $article->slug }}" readonly disabled>
                                <div class="form-text text-white-50">يتم تحديثه تلقائياً عند تغيير العنوان</div>
                            </div>

                            <!-- المحتوى -->
                            <div class="mb-3">
                                <label class="form-label">المحتوى *</label>
                                <textarea id="summernote" name="content" class="form-control @error('content') is-invalid @enderror" required>{{ old('content', $article->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- الملخص -->
                            <div class="mb-3">
                                <label class="form-label">الملخص</label>
                                <textarea name="excerpt" class="form-control" rows="3" placeholder="ملخص مختصر للمقال (اختياري)">{{ old('excerpt', $article->excerpt) }}</textarea>
                                <div class="form-text text-white-50">أقصى حد 500 حرف. سيظهر في بطاقة المقال.</div>
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
                                        @if($article->image)
                                            <img id="imagePreviewImg" src="{{ asset('storage/' . $article->image) }}" alt="الصورة الحالية">
                                            <span class="current-image-badge">الصورة الحالية</span>
                                            <div class="overlay">
                                                <span class="text-white"><i class="fas fa-camera me-2"></i>تغيير الصورة</span>
                                            </div>
                                        @else
                                            <div class="placeholder">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                <span>اختر صورة</span>
                                            </div>
                                            <img id="imagePreviewImg" src="#" alt="معاينة" style="display:none;">
                                        @endif
                                    </div>
                                    <input type="file" name="image" id="imageInput" class="d-none" accept="image/*"
                                           onchange="previewImage(event)">
                                    <div class="form-text text-white-50 mt-2">
                                        الصيغ المدعومة: jpeg, png, jpg, gif, webp. الحجم الأقصى 2MB
                                        @if($article->image)
                                            <br><strong>ملاحظة:</strong> ترك الحقل فارغاً يبقي الصورة الحالية.
                                        @endif
                                    </div>
                                    @error('image')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">وصف الصورة (alt)</label>
                                    <input type="text" name="image_alt" class="form-control" 
                                           value="{{ old('image_alt', $article->image_alt) }}" placeholder="وصف للصورة لتحسين SEO">
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
                                        <input type="text" name="meta_title" class="form-control" 
                                               value="{{ old('meta_title', $article->meta_title) }}" placeholder="عنوان SEO">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">الكلمات المفتاحية (Meta Keywords)</label>
                                        <input type="text" name="meta_keywords" class="form-control" 
                                               value="{{ old('meta_keywords', $article->meta_keywords) }}" placeholder="كلمة1, كلمة2, ...">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">الوصف التعريفي (Meta Description)</label>
                                    <textarea name="meta_description" class="form-control" rows="3" placeholder="وصف مختصر لمحركات البحث">{{ old('meta_description', $article->meta_description) }}</textarea>
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
                                        <option value="{{ $category->id }}" {{ old('category_id', $article->category_id) == $category->id ? 'selected' : '' }}>
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
                                        <option value="{{ $author->id }}" {{ old('author_id', $article->author_id) == $author->id ? 'selected' : '' }}>
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
                                        <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', $article->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                            {{ $tag->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- تاريخ النشر -->
                            <div class="mb-3">
                                <label class="form-label">تاريخ النشر</label>
                                <input type="datetime-local" name="published_at" class="form-control" 
                                       value="{{ old('published_at', $article->published_at ? $article->published_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" id="publishedAt">
                            </div>

                            <!-- مفعل -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" 
                                       {{ old('is_active', $article->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isActive">نشط</label>
                            </div>

                            <!-- مميز -->
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" value="1" id="isFeatured"
                                       {{ old('is_featured', $article->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isFeatured">مقال مميز</label>
                            </div>
                        </div>
                    </div>

                    <!-- إحصائيات المقال -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>إحصائيات المقال</h5>
                        </div>
                        <div class="card-body">
                            <div class="article-stats">
                                <div class="stat-item">
                                    <span class="stat-label"><i class="fas fa-eye me-2"></i>المشاهدات</span>
                                    <span class="stat-value">{{ $article->views_count }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label"><i class="fas fa-clock me-2"></i>وقت القراءة</span>
                                    <span class="stat-value">{{ $article->reading_time }} دقيقة</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label"><i class="fas fa-calendar-plus me-2"></i>تاريخ الإنشاء</span>
                                    <span class="stat-value">{{ $article->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label"><i class="fas fa-calendar-check me-2"></i>آخر تحديث</span>
                                    <span class="stat-value">{{ $article->updated_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-label"><i class="fas fa-comments me-2"></i>التعليقات</span>
                                    <span class="stat-value">{{ $article->comments->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- أزرار الإجراءات -->
                    <div class="d-flex flex-column gap-3 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>حفظ التعديلات
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>إلغاء والعودة للقائمة
                        </a>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Summernote
            $('#summernote').summernote({
                placeholder: 'اكتب محتوى المقال هنا...',
                tabsize: 2,
                height: 400,
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

            // Select2 للتاغات
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
                    
                    // إخفاء placeholder إن وجد
                    var placeholder = document.querySelector('.image-preview .placeholder');
                    if (placeholder) {
                        placeholder.style.display = 'none';
                    }
                    
                    // إخفاء شارة الصورة الحالية
                    var badge = document.querySelector('.current-image-badge');
                    if (badge) {
                        badge.textContent = 'صورة جديدة';
                    }
                    
                    // إظهار overlay
                    var overlay = document.querySelector('.image-preview .overlay');
                    if (overlay) {
                        overlay.querySelector('span').innerHTML = '<i class="fas fa-check-circle me-2"></i>تم اختيار صورة جديدة';
                    }
                }
                reader.readAsDataURL(event.target.files[0]);
            }

            // تأكيد قبل مغادرة الصفحة إذا تم التعديل
            let formChanged = false;
            
            $('#articleForm input, #articleForm textarea, #articleForm select').on('change', function() {
                formChanged = true;
            });
            
            $('#summernote').on('summernote.change', function() {
                formChanged = true;
            });

            $(window).on('beforeunload', function() {
                if (formChanged) {
                    return 'لديك تغييرات غير محفوظة. هل تريد المغادرة؟';
                }
            });

            // إزالة التحذير عند تقديم النموذج
            $('#articleForm').on('submit', function() {
                $(window).off('beforeunload');
            });
        });
    </script>
@endsection