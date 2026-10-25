@extends('Admin.layout.master')

@section('title', 'نظام النقاط')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1"><i class="ti ti-gift text-warning me-2"></i>نظام النقاط</h4>
            <p class="text-muted mb-0">إعدادات اكتساب واستبدال النقاط، وأرصدة العملاء وآخر الحركات.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card"><div class="card-body"><small class="text-muted">عملاء لديهم نقاط</small><h4 class="mb-0">{{ number_format($stats['members']) }}</h4></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><small class="text-muted">إجمالي الأرصدة الحالية</small><h4 class="mb-0">{{ number_format($stats['points_balance']) }} نقطة</h4></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><small class="text-muted">إجمالي النقاط المكتسبة</small><h4 class="mb-0 text-success">{{ number_format($stats['points_earned']) }}</h4></div></div>
        </div>
        <div class="col-md-3">
            <div class="card"><div class="card-body"><small class="text-muted">إجمالي النقاط المستخدمة</small><h4 class="mb-0 text-danger">{{ number_format($stats['points_redeemed']) }}</h4></div></div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom"><h5 class="mb-0">إعدادات النقاط</h5></div>
        <div class="card-body">
            <form action="{{ route('admin.loyalty.settings.update') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label class="form-label fw-bold">حالة النظام</label>
                    <div class="form-check form-switch mt-2">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ $settings->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">مفعّل</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">طريقة اكتساب النقاط</label>
                    <select name="earn_mode" class="form-select" required>
                        <option value="amount" {{ ($settings->earn_mode ?? 'amount') === 'amount' ? 'selected' : '' }}>حسب قيمة الطلب (لكل 1 ر.س)</option>
                        <option value="per_order" {{ ($settings->earn_mode ?? 'amount') === 'per_order' ? 'selected' : '' }}>نقاط ثابتة لكل طلب</option>
                        <option value="both" {{ ($settings->earn_mode ?? 'amount') === 'both' ? 'selected' : '' }}>الاثنان معًا</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">نقاط ثابتة لكل طلب</label>
                    <input type="number" name="points_per_order" value="{{ $settings->points_per_order ?? 0 }}" min="0" step="1" class="form-control">
                    <small class="text-muted">تُستخدم مع "نقاط ثابتة لكل طلب" أو "الاثنان معًا".</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">نقاط لكل 1 ر.س</label>
                    <input type="number" name="earn_rate" value="{{ $settings->earn_rate }}" min="0" step="0.01" class="form-control" required>
                    <small class="text-muted">تُستخدم مع "حسب قيمة الطلب" أو "الاثنان معًا".</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">عدد النقاط لاستبدال 1 ر.س</label>
                    <input type="number" name="redeem_rate" value="{{ $settings->redeem_rate }}" min="0.01" step="0.01" class="form-control" required>
                    <small class="text-muted">مثال: 20 يعني 100 نقطة = 5 ر.س</small>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">الحد الأدنى للاستبدال (نقطة)</label>
                    <input type="number" name="min_redeem_points" value="{{ $settings->min_redeem_points }}" min="0" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">أقصى نسبة خصم من الطلب (%)</label>
                    <input type="number" name="max_redeem_percent" value="{{ $settings->max_redeem_percent }}" min="0" max="100" step="0.01" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold">صلاحية النقاط (أيام)</label>
                    <input type="number" name="points_expiry_days" value="{{ $settings->points_expiry_days }}" min="0" class="form-control" placeholder="اتركه فارغًا بدون صلاحية">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">شروط الاستخدام (تظهر للعميل)</label>
                    <input type="text" name="terms" value="{{ $settings->terms }}" class="form-control" maxlength="2000">
                </div>
                <div class="col-12"><button class="btn btn-primary" type="submit"><i class="ti ti-device-floppy me-1"></i>حفظ الإعدادات</button></div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom d-flex flex-wrap align-items-center justify-content-between gap-2">
                    <h5 class="mb-0">أرصدة العملاء</h5>
                    <form method="GET" action="{{ route('admin.loyalty.index') }}" class="d-flex gap-2">
                        <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="ابحث بالاسم أو الجوال أو البريد">
                        <button class="btn btn-sm btn-outline-primary text-nowrap">بحث</button>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light"><tr><th>العميل</th><th>الجوال</th><th>الرصيد</th><th>تعديل</th></tr></thead>
                            <tbody>
                                @forelse ($users as $user)
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td dir="ltr">{{ $user->phone ?: '—' }}</td>
                                        <td><span class="badge bg-label-primary">{{ number_format($user->points) }}</span></td>
                                        <td>
                                            <form action="{{ route('admin.loyalty.adjust') }}" method="POST" class="d-flex gap-1">
                                                @csrf
                                                <input type="hidden" name="user_id" value="{{ $user->id }}">
                                                <input type="number" name="points" class="form-control form-control-sm" style="width:90px" placeholder="+/-" required>
                                                <button class="btn btn-sm btn-outline-primary" title="تعديل"><i class="ti ti-plus-minus"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted py-4">لا يوجد عملاء لديهم نقاط بعد.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-bottom"><h5 class="mb-0">آخر حركات النقاط</h5></div>
                <div class="card-body">
                    <div class="table-responsive" style="max-height:520px;overflow:auto">
                        <table class="table table-sm align-middle">
                            <thead class="table-light"><tr><th>العميل</th><th>النقاط</th><th>الرصيد</th><th>الوصف</th><th>التاريخ</th></tr></thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->user?->name ?? '—' }}</td>
                                        <td class="{{ $transaction->points >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ $transaction->points > 0 ? '+' : '' }}{{ number_format($transaction->points) }}
                                        </td>
                                        <td>{{ number_format($transaction->balance_after) }}</td>
                                        <td class="small text-muted">{{ $transaction->description }}</td>
                                        <td class="small text-muted">{{ $transaction->created_at?->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted py-4">لا توجد حركات بعد.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
