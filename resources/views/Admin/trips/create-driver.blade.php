@extends('Admin.layout.master')

@section('title')
    إنشاء رحلة سائق
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .content-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .content-table th,
        .content-table td {
            padding: 10px;
            border: 1px solid #ddd;
            font-family: 'Cairo', sans-serif;
        }

        .content-table th {
            background-color: #f8f9fa;
        }

        .content-table td input[type="number"] {
            width: 80px;
            padding: 5px;
            box-sizing: border-box;
        }

        .content-table td input[type="checkbox"] {
            margin-left: 10px;
        }

        .invalid-quantity {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-family: 'Cairo', sans-serif;
            color: #dc3545;
            font-weight: bold;
            display: none;
            font-size: 0.875rem;
        }

        .search-container {
            margin-bottom: 15px;
        }

        .search-container input {
            font-family: 'Cairo', sans-serif;
            text-align: right;
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }

        .center {
            text-align: center !important;
        }

        .no-results {
            font-family: 'Cairo', sans-serif;
            color: #dc3545;
            display: none;
            margin-top: 10px;
        }

        .action-btn {
            font-size: 14px;
            padding: 5px 10px;
        }

        .modal-content {
            font-family: 'Cairo', sans-serif;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <h1>إنشاء رحلة سائق</h1>
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info" role="alert">
                        {{ session('info') }}
                    </div>
                @endif
                <form action="{{ route('admin.trips.store.driver') }}" method="POST" data-parsley-validate>
                    @csrf
                    <div class="row">
                        <!-- Shipments -->
                        <div class="col-md-12 mt-4">
                            <label class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">الشحنات</label>
                            <div class="no-results" id="no-results">لا توجد شحنات مطابقة لكود البحث</div>
                            <div class="table-responsive">
                                <table class="content-table" id="basic-1">
                                    <thead>
                                        <tr>
                                            <th class="center">#</th>
                                            <th class="center">كود الشحنة</th>
                                            <th class="center">سعر الشحنة</th>
                                            <th class="center">نوع المركبة</th>
                                            <th class="center">من فرع</th>
                                            <th class="center">إلى فرع</th>
                                            <th class="center">الإجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 1; @endphp
                                        @foreach ($shipments as $shipment)
                                            @if ($shipment->contents && $shipment->contents->count() > 0)
                                                <tr
                                                    data-code="{{ $shipment->code ? htmlspecialchars($shipment->code) : '' }}">
                                                    <td class="center">{{ $i++ }}</td>
                                                    <td class="center">{{ $shipment->code ?? '--' }}</td>
                                                    <td class="center">{{ $shipment->price ?? '--' }}</td>
                                                    <td class="center">{{ $shipment->type_vehicle?->name ?? '--' }}</td>
                                                    <td class="center">{{ $shipment->branchFrom?->name ?? '--' }}</td>
                                                    <td class="center">{{ $shipment->branchTo?->name ?? '--' }}</td>
                                                    <td class="center">
                                                        <button class="btn btn-success action-btn" data-bs-toggle="modal"
                                                            data-bs-target="#contentModal"
                                                            data-content='{{ json_encode(
                                                                $shipment->contents->map(function ($content) {
                                                                        return [
                                                                            'id' => $content->id,
                                                                            'code' => $content->code ?? '--',
                                                                            'name' => $content->name ?? '--',
                                                                            'quantity' => $content->quantity ?? 0,
                                                                            'remaining' => $content->remaining ?? 0,
                                                                            'taken' => $content->taken ?? 0,
                                                                            'barcode' => $content->barcode ?? '--',
                                                                            'price' => $content->price ?? 0, // Added price
                                                                        ];
                                                                    })->toArray(),
                                                            ) }}'>
                                                            <i class="fa fa-eye text-white"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Selected Contents -->
                        <!-- Selected Contents -->
                        <div class="col-md-12 mt-4">
                            <label class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">المحتويات المختارة</label>
                            <div class="table-responsive">
                                <table class="content-table" id="selected-contents-table">
                                    <thead>
                                        <tr>
                                            <th class="center">كود المحتوى</th>
                                            <th class="center">اسم المحتوى</th>
                                            <th class="center">سعر المحتوى</th>
                                            <th class="center">كود الشحنة</th>
                                            <th class="center">الكمية المتاحة</th>
                                            <th class="center">الكمية المختارة</th>
                                            <th class="center">الاجمالي</th>
                                            <th class="center">إزالة</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selected-contents-body"></tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" class="center"><strong>إجمالي القيمة:</strong></td>
                                            <td class="center" colspan="2">
                                                <input type="text" id="grand-total" class="form-control" value="0"
                                                    readonly>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            @error('contents')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <!-- Representative -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="representative_id"
                                style="font-family: 'Cairo', sans-serif;">السائق</label>
                            <select class="form-control select2 @error('representative_id') is-invalid @enderror"
                                name="representative_id" id="representative_id">
                                <option value="">-- اختر السائق --</option>
                                @foreach ($representatives as $representative)
                                    <option value="{{ $representative->id }}"
                                        @if (old('representative_id') == $representative->id) selected @endif>
                                        {{ $representative->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('representative_id')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Branch From -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="branch_from" style="font-family: 'Cairo', sans-serif;">من
                                فرع</label>
                            @if (auth('admin')->user()->role === \App\Models\Admin::ROLE_SUPER_ADMIN)
                                <select class="form-control select2 @error('branch_from') is-invalid @enderror"
                                    name="branch_from" id="branch_from">
                                    <option value="">اختر فرع</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_from') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }} ({{ $branch->region->region_ar ?? 'غير محدد' }})
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="branch_from" value="{{ auth('admin')->user()->branch_id }}">
                                <input type="text" class="form-control"
                                    value="{{ auth('admin')->user()->branch->name ?? 'غير محدد' }}" disabled>
                            @endif
                            @error('branch_from')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Branch To -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="branch_to" style="font-family: 'Cairo', sans-serif;">إلى
                                فرع</label>
                            <select class="form-control select2 @error('branch_to') is-invalid @enderror" name="branch_to"
                                id="branch_to">
                                <option value="">اختر فرع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_to') == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} ({{ $branch->region->region_ar ?? 'غير محدد' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_to')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <!-- Type Coin -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="type_coin" style="font-family: 'Cairo', sans-serif;">نوع
                                العملة</label>
                            <select class="form-control select2 @error('type_coin') is-invalid @enderror" name="type_coin"
                                id="type_coin">
                                <option disabled selected>من فضلك حدد عملة الخزينة</option>
                                <option value="1" @if (old('type_coin') == '1') selected @endif>LYD</option>
                                <option value="2" @if (old('type_coin') == '2') selected @endif>EGP</option>
                                <option value="3" @if (old('type_coin') == '3') selected @endif>$ (USD)</option>
                                <option value="4" @if (old('type_coin') == '4') selected @endif>TRY</option>
                            </select>
                            @error('type_coin')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <!-- Driver-specific fields -->
                        <div class="col-md-4 mt-3">
                            <label for="value_drive" style="font-family: 'Cairo', sans-serif;">القيمة الكلية</label>
                            <input type="number" step="0.01"
                                class="form-control @error('value_drive') is-invalid @enderror" name="value_drive"
                                id="value_drive" value="{{ old('value_drive') }}" required>
                            @error('value_drive')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 mt-3 d-none">
                            <label for="expense_value" style="font-family: 'Cairo', sans-serif;">قيمة المدفوع</label>
                            <input type="number" step="0.01"
                                class="form-control @error('expense_value') is-invalid @enderror" name="expense_value"
                                id="expense_value" value="0">
                            @error('expense_value')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <div class="col-md-4 mt-3 d-none">
                            <label for="refund_value" style="font-family: 'Cairo', sans-serif;">قيمة الباقي</label>
                            <input type="number" step="0.01"
                                class="form-control @error('refund_value') is-invalid @enderror" name="refund_value"
                                id="refund_value" value="{{ old('refund_value') }}" readonly>
                            @error('refund_value')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="notes"
                                style="font-family: 'Cairo', sans-serif;">ملاحظات</label>
                            <input class="form-control @error('notes') is-invalid @enderror" name="notes"
                                id="notes" type="text" placeholder="ملاحظات" value="{{ old('notes') }}"
                                data-parsley-trigger="change">
                            @error('notes')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">إنشاء الرحلة</button>
                        <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Content Modal -->
    <div class="modal fade" id="contentModal" tabindex="-1" aria-labelledby="contentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contentModalLabel">تفاصيل المحتويات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="content-table">
                        <thead>
                            <tr>
                                <th class="center">اختيار</th>
                                <th class="center">كود المحتوى</th>
                                <th class="center">اسم المحتوى</th>
                                <th class="center">سعر المحتوى</th>
                                <th class="center">كود الشحنة</th>
                                <th class="center">الكمية المتاحة</th>
                                <th class="center">الكمية المختارة</th>
                            </tr>
                        </thead>
                        <tbody id="modalContentBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="add-selected-contents">إضافة المحتويات
                        المختارة</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for dropdowns
            $('#representative_id, #branch_from, #branch_to').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || "اختر";
                },
                allowClear: true
            });

            // Initialize Parsley for form validation
            $('form').parsley();

            // متغير لتتبع حالة الإرسال
            let isSubmitting = false;

            // Calculate refund_value automatically
            function calculateRefundValue() {
                const expenseValue = parseFloat($('#expense_value').val()) || 0;
                const valueDrive = parseFloat($('#value_drive').val()) || 0;
                const refundValue = valueDrive - expenseValue;
                $('#refund_value').val(refundValue.toFixed(2));
            }

            // Listen for changes in expense_value or value_drive
            $('#expense_value, #value_drive').on('input', calculateRefundValue);
            calculateRefundValue();

            // Debounce action button clicks to prevent multiple modal triggers
            let isModalOpening = false;
            $('.action-btn').on('click', function() {
                if (isModalOpening) return;
                isModalOpening = true;

                // Remove any existing backdrops
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');

                try {
                    const contents = $(this).data('content');
                    const $modalBody = $('#modalContentBody');
                    const shipmentCode = $(this).closest('tr').find('td:eq(1)').text();
                    $modalBody.empty();

                    console.log('Opening modal with contents:', contents);
                    if (contents && Array.isArray(contents) && contents.length > 0) {
                        contents.forEach(content => {
                            if (!content.id || !content.remaining) {
                                console.warn(`Invalid content data:`, content);
                                return;
                            }
                            const isChecked = $(
                                `#selected-contents-body input[name="contents[${content.id}][id]"]`
                            ).length > 0;
                            $modalBody.append(`
                                <tr data-content-id="${content.id}">
                                    <td class="center">
                                        <input type="checkbox" class="content-checkbox" data-content-id="${content.id}" ${isChecked ? 'checked' : ''}>
                                    </td>
                                    <td class="center">${String(content.code || '--')}</td>
                                    <td class="center">${content.name || '--'}</td>
                                    <td class="center">${content.price !== null && content.price !== undefined ? content.price : '--'}</td>
                                    <td class="center">${shipmentCode}</td>
                                    <td class="center">${content.remaining || 0}</td>
                                    <td class="center">
                                        <input type="number" class="form-control content-quantity" min="1" max="${content.remaining || 0}"
                                               value="${isChecked ? $(`#selected-contents-body input[name="contents[${content.id}][quantity]"]`).val() || '' : ''}"
                                               data-max="${content.remaining || 0}">
                                        <span class="invalid-feedback">الكمية يجب أن تكون بين 1 و ${content.remaining || 0}</span>
                                    </td>
                                </tr>
                            `);
                        });
                    } else {
                        $modalBody.append(
                            '<tr><td colspan="7" class="center">لا توجد محتويات متاحة</td></tr>');
                    }
                } catch (error) {
                    console.error('Error opening modal:', error);
                    alert('حدث خطأ أثناء فتح نافذة المحتويات. يرجى التحقق من وحدة التحكم.');
                } finally {
                    isModalOpening = false;
                }
            });

            // Ensure backdrop is removed when modal is hidden
            $('#contentModal').on('hidden.bs.modal', function() {
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open');
                $('body').css('padding-right', '');
                console.log('Modal hidden, backdrop removed');
            });

            // Validate quantity inputs in modal
            $(document).on('input', '.content-quantity', function() {
                const max = parseInt($(this).attr('data-max')) || 0;
                const value = parseInt($(this).val()) || 0;
                const $checkbox = $(this).closest('tr').find('.content-checkbox');
                const $error = $(this).siblings('.invalid-feedback');

                if (value < 1 || value > max) {
                    $(this).addClass('invalid-quantity');
                    $error.show();
                    $checkbox.prop('checked', false);
                    $(this).val('');
                } else {
                    $(this).removeClass('invalid-quantity');
                    $error.hide();
                    $checkbox.prop('checked', true);
                    $(this).attr('required', true);
                }
            });

            // Sync checkbox with quantity input in modal
            $(document).on('change', '.content-checkbox', function() {
                const $quantityInput = $(this).closest('tr').find('.content-quantity');
                const $error = $quantityInput.siblings('.invalid-feedback');
                if (!$(this).is(':checked')) {
                    $quantityInput.val('');
                    $quantityInput.removeAttr('required');
                    $error.hide();
                } else {
                    $quantityInput.attr('required', true);
                }
            });

            // Add selected contents to the selected contents table
            $('#add-selected-contents').on('click', function(e) {
                e.preventDefault(); // منع أي submit غير متوقع
                try {
                    const $modalRows = $('#modalContentBody tr[data-content-id]');
                    const $selectedTableBody = $('#selected-contents-body');
                    $modalRows.each(function() {
                        const contentId = $(this).data('content-id');
                        const $checkbox = $(this).find('.content-checkbox');
                        const $quantityInput = $(this).find('.content-quantity');
                        const quantity = parseInt($quantityInput.val()) || 0;
                        const code = $(this).find('td:eq(1)').text();
                        const name = $(this).find('td:eq(2)').text();
                        const price = parseFloat($(this).find('td:eq(3)').text()) || 0;
                        const shipmentCode = $(this).find('td:eq(4)').text();
                        const remaining = parseInt($(this).find('td:eq(5)').text()) || 0;

                        if ($checkbox.is(':checked') && quantity >= 1 && quantity <= remaining) {
                            $(`#selected-contents-body tr[data-content-id="${contentId}"]`)
                                .remove();
                            $selectedTableBody.append(`
                                <tr data-content-id="${contentId}">
                                    <td class="center">${code}</td>
                                    <td class="center">${name}</td>
                                    <td class="center">${price}</td>
                                    <td class="center">${shipmentCode}</td>
                                    <td class="center">${remaining}</td>
                                    <td class="center">
                                        <input type="number" name="contents[${contentId}][id]" value="${contentId}" hidden>
                                        <input type="number" name="contents[${contentId}][quantity]" value="${quantity}"
                                               class="form-control content-quantity" min="1" max="${remaining}" data-max="${remaining}" required>
                                        <span class="invalid-feedback">الكمية يجب أن تكون بين 1 و ${remaining}</span>
                                    </td>
                                    <td class="center">
                                        <input type="text" class="form-control content-total" 
                                               value="${(price * quantity).toFixed(2)}" readonly>
                                    </td>
                                    <td class="center">
                                        <button type="button" class="btn btn-danger btn-sm remove-content">إزالة</button>
                                    </td>
                                </tr>
                            `);
                        } else {
                            $(`#selected-contents-body tr[data-content-id="${contentId}"]`)
                                .remove();
                        }
                    });

                    updateGrandTotal();
                    $('#contentModal').modal('hide');
                } catch (error) {
                    console.error('Error in add-selected-contents:', error);
                    alert('حدث خطأ أثناء إضافة المحتويات. يرجى التحقق من وحدة التحكم.');
                    $('#contentModal').modal('hide');
                }
            });

            function updateGrandTotal() {
                let total = 0;
                $('#selected-contents-body .content-total').each(function() {
                    total += parseFloat($(this).val()) || 0;
                });
                $('#grand-total').val(total.toFixed(2));
            }

            // Remove content from selected contents table
            $(document).on('click', '.remove-content', function() {
                $(this).closest('tr').remove();
                updateGrandTotal();
            });

            // Validate and update total when changing quantity in selected contents
            $(document).on('input', '#selected-contents-body .content-quantity', function() {
                const $row = $(this).closest('tr');
                const price = parseFloat($row.find('td:eq(2)').text()) || 0;
                const quantity = parseInt($(this).val()) || 0;
                const max = parseInt($(this).attr('data-max')) || 0;
                const $error = $(this).siblings('.invalid-feedback');

                if (quantity < 1 || quantity > max) {
                    $(this).addClass('invalid-quantity');
                    $error.show();
                    $(this).val('');
                    $row.find('.content-total').val('0.00');
                } else {
                    $(this).removeClass('invalid-quantity');
                    $error.hide();
                    $row.find('.content-total').val((price * quantity).toFixed(2));
                }

                updateGrandTotal();
            });

            // منع إرسال الـ form أكثر من مرة
            $('form').on('submit', function(e) {
                if (isSubmitting) {
                    e.preventDefault();
                    return false;
                }

                const $submitBtn = $(this).find('button[type="submit"]');
                if ($submitBtn.prop('disabled')) {
                    e.preventDefault();
                    return false;
                }

                // التحقق من التحقق من صحة النموذج باستخدام Parsley
                if ($(this).parsley().isValid()) {
                    isSubmitting = true;
                    $submitBtn.prop('disabled', true).text('جاري الإنشاء ...');

                    // إعادة الزر إلى حالته الأصلية بعد فترة زمنية (اختياري)
                    setTimeout(() => {
                        isSubmitting = false;
                        $submitBtn.prop('disabled', false).text('إنشاء الرحلة');
                    }, 5000); // 5 ثواني كمثال
                } else {
                    e.preventDefault();
                    alert('يرجى التحقق من البيانات المدخلة.');
                }
            });

            // Restore old input for selected contents
            @if (old('contents'))
                @foreach (old('contents') as $contentId => $contentData)
                    try {
                        const content = {
                            id: {{ $contentId }},
                            code: '{{ $contents->find($contentId)->code ?? '--' }}',
                            name: '{{ $contents->find($contentId)->name ?? '--' }}',
                            price: {{ $contents->find($contentId)->price ?? 0 }},
                            shipmentCode: '{{ $contents->find($contentId)->shipment->code ?? '--' }}',
                            remaining: {{ $contents->find($contentId)->remaining ?? 0 }},
                            quantity: {{ $contentData['quantity'] ?? 0 }}
                        };
                        $('#selected-contents-body').append(`
                            <tr data-content-id="${content.id}">
                                <td class="center">${content.code}</td>
                                <td class="center">${content.name}</td>
                                <td class="center">${content.price !== null && content.price !== undefined ? content.price : '--'}</td>
                                <td class="center">${content.shipmentCode}</td>
                                <td class="center">${content.remaining}</td>
                                <td class="center">
                                    <input type="number" name="contents[${content.id}][id]" value="${content.id}" hidden>
                                    <input type="number" name="contents[${content.id}][quantity]" value="${content.quantity}"
                                           class="form-control content-quantity" min="1" max="${content.remaining}" data-max="${content.remaining}" required>
                                    <span class="invalid-feedback">الكمية يجب أن تكون بين 1 و ${content.remaining}</span>
                                </td>
                                <td class="center">
                                    <input type="text" class="form-control content-total" 
                                           value="${(content.price * content.quantity).toFixed(2)}" readonly>
                                </td>
                                <td class="center">
                                    <button type="button" class="btn btn-danger btn-sm remove-content">إزالة</button>
                                </td>
                            </tr>
                        `);
                    } catch (error) {
                        console.error('Error restoring content ID {{ $contentId }}:', error);
                    }
                @endforeach
                updateGrandTotal();
            @endif
        });
    </script>
@endsection
