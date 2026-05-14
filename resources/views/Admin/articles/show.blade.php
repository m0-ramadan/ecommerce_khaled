@extends('Admin.layout.master')

@section('title', 'عرض المقال')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

        .badge-status {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .badge-active {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 1px solid rgba(40, 167, 69, 0.3);
        }

        .badge-inactive {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        .badge-featured {
            background: rgba(255, 193, 7, 0.2);
            color: #ffc107;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        .badge-draft {
            background: rgba(108, 117, 125, 0.2);
            color: #adb5bd;
            border: 1px solid rgba(108, 117, 125, 0.3);
        }

        .tag-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            background: rgba(105, 108, 255, 0.2);
            color: var(--primary-color);
            margin: 3px;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-box:hover {
            background: rgba(105, 108, 255, 0.1);
            transform: translateY(-3px);
        }

        .stat-icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 22px;
            font-weight: 700;
            color: #fff;
        }

        .stat-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            margin-top: 5px;
        }

        .content-preview {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 20px;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.9);
        }

        .content-preview img {
            max-width: 100%;
            border-radius: 10px;
        }

        .article-image {
            width: 100%;
            max-height: 400px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.1);
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .meta-item:last-child {
            border-bottom: none;
        }

        .meta-label {
            color: rgba(255, 255, 255, 0.7);
            min-width: 120px;
        }

        .meta-value {
            color: #fff;
            font-weight: 500;
        }

        .comment-card {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .comment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .comment-author {
            font-weight: 600;
            color: var(--primary-color);
        }

        .comment-date {
            font-size: 12px;
            color: rgba(255, 255, 255, 0.5);
        }

        .comment-content {
            color: rgba(255, 255, 255, 0.8);
        }

        .btn-primary {
            background: var(--primary-gradient);
            border: none;
            padding: 10px 20px;
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
            padding: 10px 20px;
            border-radius: 10px;
        }

        .btn-danger {
            background: #dc3545;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            color: white;
        }

        .btn-success {
            background: #28a745;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: rgba(255, 255, 255, 0.5);
        }

        .empty-state i {
            font-size: 50px;
            margin-bottom: 15px;
            display: block;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" dir="rtl">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">المقالات</a></li>
                <li class="breadcrumb-item active">{{ Str::limit($article->title, 50) }}</li>
            </ol>
        </nav>

        <!-- الهيدر والإجراءات -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">{{ $article->title }}</h4>
                        <div class="d-flex gap-2 mt-2 flex-wrap">
                            @if($article->is_active)
                                <span class="badge-status badge-active"><i class="fas fa-check-circle me-1"></i>نشط</span>
                            @else
                                <span class="badge-status badge-inactive"><i class="fas fa-times-circle me-1"></i>غير نشط</span>
                            @endif
                            
                            @if($article->is_featured)
                                <span class="badge-status badge-featured"><i class="fas fa-star me-1"></i>مميز</span>
                            @endif

                            @if(!$article->published_at || $article->published_at->isFuture())
                                <span class="badge-status badge-draft"><i class="fas fa-pencil-alt me-1"></i>مسودة</span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-1"></i> تعديل
                        </a>
                        <button class="btn btn-danger" onclick="deleteArticle({{ $article->id }})">
                            <i class="fas fa-trash me-1"></i> حذف
                        </button>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-right me-1"></i> العودة
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- المحتوى الرئيسي -->
            <div class="col-lg-8">
                <!-- الصورة المميزة -->
                @if($article->image)
                    <div class="card">
                        <div class="card-body p-0">
                            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->image_alt ?? $article->title }}" class="article-image">
                        </div>
                    </div>
                @endif

                <!-- محتوى المقال -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>محتوى المقال</h5>
                    </div>
                    <div class="card-body">
                        @if($article->excerpt)
                            <div class="mb-4 p-3" style="background: rgba(105,108,255,0.1); border-radius: 10px; border-right: 4px solid var(--primary-color);">
                                <strong><i class="fas fa-quote-right me-2"></i>الملخص:</strong>
                                <p class="mb-0 mt-2">{{ $article->excerpt }}</p>
                            </div>
                        @endif

                        <div class="content-preview">
                            {!! $article->content !!}
                        </div>
                    </div>
                </div>

                <!-- التعليقات -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-comments me-2"></i>التعليقات ({{ $article->comments->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @forelse($article->comments as $comment)
                            <div class="comment-card">
                                <div class="comment-header">
                                    <div>
                                        <span class="comment-author">{{ $comment->user->name ?? 'مستخدم' }}</span>
                                        <small class="text-muted ms-2">{{ $comment->user->email ?? '' }}</small>
                                    </div>
                                    <span class="comment-date">{{ $comment->created_at->format('Y-m-d H:i') }}</span>
                                </div>
                                <div class="comment-content">
                                    {{ $comment->content }}
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <i class="far fa-comment-dots"></i>
                                <p>لا توجد تعليقات على هذا المقال حتى الآن</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- العمود الجانبي -->
            <div class="col-lg-4">
                <!-- معلومات المقال -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>معلومات المقال</h5>
                    </div>
                    <div class="card-body">
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-user me-2"></i>الكاتب:</span>
                            <span class="meta-value">{{ $article->author->name ?? 'غير محدد' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-folder me-2"></i>التصنيف:</span>
                            <span class="meta-value">{{ $article->category->name ?? 'غير محدد' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-calendar-plus me-2"></i>تاريخ الإنشاء:</span>
                            <span class="meta-value">{{ $article->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-calendar-check me-2"></i>تاريخ النشر:</span>
                            <span class="meta-value">
                                @if($article->published_at && $article->published_at->isPast())
                                    {{ $article->published_at->format('Y-m-d H:i') }}
                                @else
                                    <span class="text-warning">غير منشور</span>
                                @endif
                            </span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-clock me-2"></i>وقت القراءة:</span>
                            <span class="meta-value">{{ $article->reading_time }} دقيقة</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label"><i class="fas fa-link me-2"></i>الرابط:</span>
                            <span class="meta-value" dir="ltr">{{ $article->slug }}</span>
                        </div>
                    </div>
                </div>

                <!-- الإحصائيات -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>إحصائيات</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="stat-icon" style="color: #696cff;">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                    <div class="stat-number">{{ $article->views_count }}</div>
                                    <div class="stat-label">مشاهدة</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="stat-icon" style="color: #28a745;">
                                        <i class="fas fa-comment"></i>
                                    </div>
                                    <div class="stat-number">{{ $article->comments->count() }}</div>
                                    <div class="stat-label">تعليق</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="stat-icon" style="color: #ffc107;">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="stat-number">{{ $article->reading_time }}</div>
                                    <div class="stat-label">دقيقة قراءة</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="stat-box">
                                    <div class="stat-icon" style="color: #17a2b8;">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="stat-number">{{ $article->tags->count() }}</div>
                                    <div class="stat-label">وسم</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- الوسوم -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tags me-2"></i>الوسوم</h5>
                    </div>
                    <div class="card-body">
                        @forelse($article->tags as $tag)
                            <span class="tag-badge">{{ $tag->name }}</span>
                        @empty
                            <p class="text-muted mb-0">لا توجد وسوم</p>
                        @endforelse
                    </div>
                </div>

                <!-- SEO -->
                @if($article->meta_title || $article->meta_description || $article->meta_keywords)
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-search me-2"></i>معلومات SEO</h5>
                        </div>
                        <div class="card-body">
                            @if($article->meta_title)
                                <div class="mb-3">
                                    <small class="text-muted">Meta Title:</small>
                                    <p class="mb-0">{{ $article->meta_title }}</p>
                                </div>
                            @endif
                            @if($article->meta_description)
                                <div class="mb-3">
                                    <small class="text-muted">Meta Description:</small>
                                    <p class="mb-0">{{ $article->meta_description }}</p>
                                </div>
                            @endif
                            @if($article->meta_keywords)
                                <div>
                                    <small class="text-muted">Meta Keywords:</small>
                                    <p class="mb-0">{{ $article->meta_keywords }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteArticle(articleId) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "سيتم حذف هذا المقال نهائياً!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء',
                background: '#2b3b4c',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.articles.destroy", ":id") }}'.replace(':id', articleId),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'تم الحذف!',
                                text: response.success || 'تم حذف المقال بنجاح',
                                icon: 'success',
                                background: '#2b3b4c',
                                color: '#fff'
                            }).then(() => {
                                window.location.href = '{{ route("admin.articles.index") }}';
                            });
                        },
                        error: function() {
                            Swal.fire({
                                title: 'خطأ!',
                                text: 'حدث خطأ أثناء حذف المقال',
                                icon: 'error',
                                background: '#2b3b4c',
                                color: '#fff'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection