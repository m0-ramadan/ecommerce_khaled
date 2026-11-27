@extends('Admin.layout.master')

@section('title', 'عرض السؤال الشائع')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.faqs.index') }}">الأسئلة الشائعة</a></li>
                <li class="breadcrumb-item active">تفاصيل السؤال</li>
            </ol>
        </nav>

        <div class="row g-3">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header table-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">تفاصيل السؤال الشائع</h5>
                            <small class="text-muted">الترتيب: {{ $faq->sort_order ?? 0 }}</small>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.faqs.edit', $faq) }}" class="btn btn-warning btn-sm" style="width:auto !important; padding:0 12px !important;">
                                <i class="ti ti-edit me-1"></i> تعديل
                            </a>
                            <a href="{{ route('admin.faqs.index') }}" class="btn btn-light">
                                <i class="ti ti-arrow-right me-1"></i> رجوع
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <span class="badge {{ $faq->status ? 'bg-label-success' : 'bg-label-danger' }}">
                                {{ $faq->status ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                        <h6 class="fw-bold mb-2">{{ $faq->question }}</h6>
                        <div class="p-3 rounded" style="background:#f8fafc; border:1px solid #edf0f5;">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header table-header">
                        <h6 class="mb-0">أسئلة ذات صلة</h6>
                    </div>
                    <div class="card-body">
                        @forelse ($relatedFaqs as $related)
                            <div class="mb-2 pb-2 border-bottom">
                                <a href="{{ route('admin.faqs.show', $related) }}" class="fw-medium">
                                    {{ Str::limit($related->question, 70) }}
                                </a>
                            </div>
                        @empty
                            <p class="text-muted mb-0">لا توجد أسئلة أخرى نشطة</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
