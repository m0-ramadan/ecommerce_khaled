@extends('Admin.layout.master')

@section('title', 'تعديل السؤال الشائع')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">الرئيسية</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.faqs.index') }}">الأسئلة الشائعة</a></li>
                <li class="breadcrumb-item active">تعديل سؤال</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-header table-header d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">تعديل السؤال الشائع</h5>
                    <small class="text-muted">تحديث نص السؤال والإجابة والترتيب والحالة</small>
                </div>
                <a href="{{ route('admin.faqs.index') }}" class="btn btn-light">
                    <i class="ti ti-arrow-right me-1"></i> رجوع
                </a>
            </div>

            <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="question" class="form-label">السؤال</label>
                            <textarea class="form-control @error('question') is-invalid @enderror" id="question"
                                name="question" rows="2" required>{{ old('question', $faq->question) }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="answer" class="form-label">الإجابة</label>
                            <textarea class="form-control @error('answer') is-invalid @enderror" id="answer"
                                name="answer" rows="5" required>{{ old('answer', $faq->answer) }}</textarea>
                            @error('answer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sort_order" class="form-label">الترتيب</label>
                            <input type="number" class="form-control @error('sort_order') is-invalid @enderror"
                                id="sort_order" name="sort_order" min="0"
                                value="{{ old('sort_order', $faq->sort_order ?? 0) }}">
                            @error('sort_order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">الحالة</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="1" {{ old('status', (string) ($faq->status ? 1 : 0)) == '1' ? 'selected' : '' }}>نشط</option>
                                <option value="0" {{ old('status', (string) ($faq->status ? 1 : 0)) == '0' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">إلغاء</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-device-floppy me-1"></i> حفظ التغييرات
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
