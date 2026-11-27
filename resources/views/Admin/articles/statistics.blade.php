@extends('Admin.layout.master')

@section('title', 'إحصائيات المقالات')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.articles.index') }}">المقالات</a></li>
                <li class="breadcrumb-item active">الإحصائيات</li>
            </ol>
        </nav>

        <div class="row g-3 mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon"><i class="ti ti-news"></i></div>
                    <div class="stats-number">{{ number_format($stats['total']) }}</div>
                    <div class="stats-label">إجمالي المقالات</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon"><i class="ti ti-circle-check"></i></div>
                    <div class="stats-number">{{ number_format($stats['active']) }}</div>
                    <div class="stats-label">مقالات نشطة</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon"><i class="ti ti-star"></i></div>
                    <div class="stats-number">{{ number_format($stats['featured']) }}</div>
                    <div class="stats-label">مقالات مميزة</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stats-card">
                    <div class="stats-icon"><i class="ti ti-eye"></i></div>
                    <div class="stats-number">{{ number_format($stats['total_views']) }}</div>
                    <div class="stats-label">إجمالي المشاهدات</div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header table-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">أكثر المقالات مشاهدة</h5>
                            <small class="text-muted">أعلى 10 مقالات من حيث عدد القراءات</small>
                        </div>
                        <a href="{{ route('admin.articles.index') }}" class="btn btn-light">
                            <i class="ti ti-arrow-right me-1"></i> قائمة المقالات
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>العنوان</th>
                                    <th>التصنيف</th>
                                    <th>المشاهدات</th>
                                    <th>الحالة</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topArticles as $article)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <a href="{{ route('admin.articles.show', $article) }}" class="fw-semibold">
                                                {{ $article->title }}
                                            </a>
                                        </td>
                                        <td>{{ $article->category->name ?? '-' }}</td>
                                        <td class="fw-bold">{{ number_format($article->views_count ?? 0) }}</td>
                                        <td>
                                            <span class="badge {{ $article->is_active ? 'bg-label-success' : 'bg-label-danger' }}">
                                                {{ $article->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">لا توجد مقالات حالياً</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header table-header">
                        <h6 class="mb-0">التوزيع حسب التصنيف</h6>
                    </div>
                    <div class="card-body">
                        @forelse ($categoriesStats as $cat)
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span>{{ $cat->name }}</span>
                                <span class="badge bg-label-success">{{ $cat->articles_count ?? 0 }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">لا توجد تصنيفات</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
