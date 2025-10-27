@extends('Admin.layout.master')

@php
    $currentRoute = request()->path();
@endphp

@section('title')
    @if ($currentRoute == 'admin/shipment/all/shipments/n-priced')
        شحنات الزبائن منتظرة التسعير
    @elseif ($currentRoute == 'admin/shipment/all/shipments/n-active-Merch')
        شحنات التجار منتظرة التأكيد
    @elseif ($currentRoute == 'admin/shipment/all/shipments/n-active')
        شحنات الزبائن منتظرة تأكيد الأدارة
    @endif
@endsection


@section('content')
    @if (!auth()->user()->role || auth()->user()->hasPermissionTo('عرض شحنات الزبائن'))
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    @if (request()->routeIs('admin.shipment.nPriced'))
                        <x-breadcrumb :items="[
                            'لوحة التحكم' => '/admin',
                            'شحنات الزبائن' => '/admin/shipment/client',
                            'شحنات الزبائن منتظرة التسعير' => '',
                        ]" />
                    @elseif (request()->routeIs('admin.shipment.nActive'))
                        <x-breadcrumb :items="[
                            'لوحة التحكم' => '/admin',
                            'شحنات الزبائن' => '/admin/shipment/client',
                            'شحنات الزبائن منتظرة تأكيد الإدارة' => '',
                        ]" />
                    @elseif (request()->routeIs('admin.shipment.nActiveMerch'))
                        <x-breadcrumb :items="[
                            'لوحة التحكم' => '/admin',
                            'شحنات التجار' => '/admin/shipment/1',
                            'شحنات التجار منتظرة التأكيد' => '',
                        ]" />
                    @else
                        <x-breadcrumb :items="[
                            'لوحة التحكم' => '/admin',
                            'شحنات الزبائن' => '/admin/shipment/client',
                        ]" />
                    @endif
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.shipment.client') }}" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <input type="text" name="search" class="form-control"
                                    placeholder="ابحث باستخدام الكود أو المسار" value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">بحث</button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('admin.shipment.client') }}" class="btn btn-secondary">إعادة تعيين</a>
                            </div>
                        </div>
                    </form>

                    <!-- Display Success and Error Messages -->
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="display" id="example">
                            <thead>
                                <tr>
                                    <th style="text-align: center;">#</th>
                                    <th style="text-align: center;">المسئول</th>
                                    <th style="text-align: center;">الكود</th>
                                    <th style="text-align: center;">المسار</th>
                                    <th style="text-align: center;">الوزن</th>
                                    <th style="text-align: center;">المقاس</th>
                                    <th style="text-align: center;">قيمة الشحنة</th>
                                    <th style="text-align: center;">قيمة الخصم</th>
                                    <th style="text-align: center;">الحالة</th>
                                    <th style="text-align: center;">النوع</th>
                                    <th style="text-align: center;">التوصيل</th>
                                    <th style="text-align: center;">التاريخ</th>
                                    <th style="text-align: center;">العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shipments as $key => $shipment)
                                    <tr>
                                        <td style="text-align: center;">{{ $shipments->firstItem() + $key }}</td>
                                        <td style="text-align: center;">{{ $shipment->admin_create->name ?? 'غير محدد' }}
                                        </td>
                                        <td style="text-align: center;">{{ $shipment->code }}</td>
                                        <td style="text-align: center;">
                                            {{ $shipment->branchFrom->name ?? 'غير محدد' }} -
                                            {{ $shipment->branchTo->name ?? 'غير محدد' }}
                                        </td>
                                        <td style="text-align: center;">{{ $shipment->weight ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">{{ $shipment->size ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">{{ $shipment->price ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">{{ $shipment->discount ?? '0' }}</td>
                                        <td style="text-align: center;">
                                            <span
                                                class="badge {{ $shipment->status_badge_class }}">{{ $shipment->status_label }}</span>
                                        </td>
                                        <td style="text-align: center;">{{ $shipment->service?->name ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">{{ $shipment->shipping_cost ?? 'غير محدد' }}</td>
                                        <td style="text-align: center;">
                                            {{ date('Y-m-d', strtotime($shipment->created_at)) }}</td>
                                        <td class="d-flex gap-1 justify-content-center">
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('طباعة شحنة'))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.printPdf', [$shipment->id]) }}"
                                                    title="طباعة PDF">
                                                    <i class="fa fa-print text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('إضافة جمرك الشحنة'))
                                                <a class="btn text-white" style="background-color: #6f42c1;" href="#"
                                                    data-toggle="modal" data-target="#addCustomsModal-{{ $shipment->id }}"
                                                    title="إضافة الجمارك">
                                                    <i class="fa fa-plane-departure"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('إعادة طباعة شحنة'))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.printPdfReset', [$shipment->id]) }}"
                                                    title="إعادة طباعة PDF">
                                                    <i class="fa fa-receipt text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('عرض محتويات الشحنة'))
                                                <a class="btn btn-info"
                                                    href="{{ route('admin.shipment.contents', [$shipment->id]) }}"
                                                    title="عرض المحتويات">
                                                    <i class="fa fa-file-lines text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('طباعة باركود الشحنة'))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.printContentBarcodes', [$shipment->id]) }}"
                                                    title="طباعة باركود المحتويات">
                                                    <i class="fa fa-boxes text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('إضافة مصاريف الشحنة'))
                                                <a class="btn btn-success" href="#" data-toggle="modal"
                                                    data-target="#addExpensesModal-{{ $shipment->id }}"
                                                    title="إضافة مصاريف">
                                                    <i class="fa fa-dollar-sign text-white"></i>
                                                </a>
                                            @endif
                                            @if (request()->routeIs(['admin.shipment.nActive', 'admin.shipment.nActiveMerch']) &&
                                                    (!auth()->user()->role || auth()->user()->hasPermissionTo('تأكيد الشحنة')))
                                                <form
                                                    action="{{ route('admin.shipment.confirm_shipment', $shipment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        title="تأكيد الشحنة">
                                                        <i class="fa fa-check text-white"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($shipment->status_id != 2 && (!auth()->user()->role || auth()->user()->hasPermissionTo('تعديل الشحنة')))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.edit', [$shipment->id]) }}"
                                                    title="تعديل">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('عرض تفاصيل الشحنة'))
                                                <a class="btn btn-success"
                                                    href="{{ route('admin.shipment.show', [$shipment->id]) }}"
                                                    title="عرض">
                                                    <i class="fa fa-eye text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('تعيين خصم الشحنة'))
                                                <a href="#" class="btn btn-danger" data-toggle="modal"
                                                    data-target="#discountModal-{{ $shipment->id }}" title="تعيين خصم">
                                                    <i class="fa fa-percent text-white"></i>
                                                </a>
                                            @endif
                                            @if (!auth()->user()->role || auth()->user()->hasPermissionTo('حذف الشحنة'))
                                                <form method="POST"
                                                    action="{{ route('admin.shipment.destroy', $shipment->id) }}"
                                                    onsubmit="return confirm('هل أنت متأكد من حذف هذه الشحنة؟');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-primary" title="حذف">
                                                        <i class="fa fa-trash-o"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($shipment->is_priced != 2 && (!auth()->user()->role || auth()->user()->hasPermissionTo('تعديل سعر المندوب')))
                                                <a href="#" class="btn btn-warning" data-toggle="modal"
                                                    data-target="#editPriceModal-{{ $shipment->id }}"
                                                    title="تعديل سعر المندوب">
                                                    <i class="fa fa-dollar-sign text-white"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>

                                    <!-- Add Expenses Modal -->
                                    <div class="modal fade" id="addExpensesModal-{{ $shipment->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="addExpensesModalLabel-{{ $shipment->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"
                                                        id="addExpensesModalLabel-{{ $shipment->id }}">
                                                        إضافة مصاريف للشحنة #{{ $shipment->code }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form id="expenses-form-{{ $shipment->id }}"
                                                    action="{{ route('admin.shipment_expenses.store', $shipment->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div id="expenses-container-{{ $shipment->id }}">
                                                            @foreach ($shipment->expenses as $index => $expense)
                                                                <div class="expense-row flex gap-2 mb-2">
                                                                    <input type="hidden"
                                                                        name="expenses[{{ $index }}][id]"
                                                                        value="{{ $expense->id }}">
                                                                    <div class="form-group w-1/3">
                                                                        <label>السعر
                                                                            ({{ $shipment->effective_currency_label ?? 'غير معروف' }})
                                                                        </label>
                                                                        <input type="number"
                                                                            name="expenses[{{ $index }}][price]"
                                                                            step="0.01" class="form-control"
                                                                            value="{{ $expense->price }}" readonly>
                                                                    </div>
                                                                    <div class="form-group w-2/3">
                                                                        <label>السبب</label>
                                                                        <input type="text"
                                                                            name="expenses[{{ $index }}][reason]"
                                                                            class="form-control"
                                                                            value="{{ $expense->reason }}" readonly>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                            <!-- Initial empty row for new expenses -->
                                                            <div class="expense-row flex gap-2 mb-2">
                                                                <div class="form-group w-1/3">
                                                                    <label>السعر
                                                                        ({{ $shipment->effective_currency_label ?? 'غير معروف' }})</label>
                                                                    <input type="number"
                                                                        name="expenses[{{ $shipment->expenses->count() }}][price]"
                                                                        step="0.01" class="form-control" required>
                                                                </div>
                                                                <div class="form-group w-2/3">
                                                                    <label>السبب</label>
                                                                    <input type="text"
                                                                        name="expenses[{{ $shipment->expenses->count() }}][reason]"
                                                                        class="form-control" required>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-success">حفظ
                                                            المصاريف</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Discount Modal -->
                                    <div class="modal fade" id="discountModal-{{ $shipment->id }}" tabindex="-1"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form method="POST"
                                                    action="{{ route('admin.setDiscount', $shipment->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">تعيين خصم للشحنة #{{ $shipment->code }}
                                                        </h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="إغلاق">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>نوع الخصم</label>
                                                            <select class="form-control" name="is_rate"
                                                                id="is_rate-{{ $shipment->id }}" required>
                                                                <option value="1">نسبة (%)</option>
                                                                <option value="0">قيمة ثابتة</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>قيمة الخصم</label>
                                                            <input type="number" class="form-control" name="discount"
                                                                id="discount-{{ $shipment->id }}" min="0"
                                                                max="100" step="0.01" required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">حفظ</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">إلغاء</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add Customs Modal -->
                                    <div class="modal fade" id="addCustomsModal-{{ $shipment->id }}" tabindex="-1"
                                        role="dialog" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <form method="POST"
                                                    action="{{ route('admin.setCustoms', $shipment->id) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">إضافة قيمة جمرك للشحنة
                                                            #{{ $shipment->code }}</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="إغلاق">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="customs_currency">نوع العملة</label>
                                                            <select
                                                                class="form-control @error('customs_currency') is-invalid @enderror"
                                                                name="customs_currency" id="customs_currency">
                                                                <option value="">من فضلك حدد عملة الخزينة</option>
                                                                <option value="1"
                                                                    {{ old('customs_currency', $shipment->customs_currency) == '1' ? 'selected' : '' }}>
                                                                    LYD</option>
                                                                <option value="2"
                                                                    {{ old('customs_currency', $shipment->customs_currency) == '2' ? 'selected' : '' }}>
                                                                    EGP</option>
                                                                <option value="3"
                                                                    {{ old('customs_currency', $shipment->customs_currency) == '3' ? 'selected' : '' }}>
                                                                    $ (USD)</option>
                                                                <option value="4"
                                                                    {{ old('customs_currency', $shipment->customs_currency) == '4' ? 'selected' : '' }}>
                                                                    TRY</option>
                                                            </select>
                                                            @error('customs_currency')
                                                                <span class="invalid-feedback">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label>قيمة الجمرك</label>
                                                            <input type="number" class="form-control"
                                                                name="customs_cost" id="customs_cost-{{ $shipment->id }}"
                                                                min="0" step="0.01"
                                                                value="{{ old('customs_cost', $shipment->customs_cost) }}"
                                                                required>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" class="btn btn-danger">حفظ</button>
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">إلغاء</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Edit Price Representative Modal -->
                                    <div class="modal fade" id="editPriceModal-{{ $shipment->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="editPriceModalLabel-{{ $shipment->id }}"
                                        aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editPriceModalLabel-{{ $shipment->id }}">
                                                        تعديل سعر المندوب</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('admin.shipment.updatePriceRepresentative') }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="modal-body">
                                                        <input type="hidden" name="shipment_id"
                                                            value="{{ $shipment->id }}">
                                                        <div class="form-group">
                                                            <label for="price-{{ $shipment->id }}">سعر الشحنة</label>
                                                            <input type="number" step="0.01" name="price"
                                                                id="price-{{ $shipment->id }}" class="form-control"
                                                                value="{{ $shipment->price ?? '' }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="shipping_cost-{{ $shipment->id }}">سعر التوصيل
                                                                الداخلي</label>
                                                            <input type="number" step="0.01" name="shipping_cost"
                                                                id="shipping_cost-{{ $shipment->id }}"
                                                                class="form-control"
                                                                value="{{ $shipment->price_representative ?? '' }}"
                                                                required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="packaging_cost-{{ $shipment->id }}">سعر
                                                                التغليف</label>
                                                            <input type="number" step="0.01" name="packaging_cost"
                                                                id="packaging_cost-{{ $shipment->id }}"
                                                                class="form-control"
                                                                value="{{ $shipment->packaging_cost ?? '' }}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="customs_cost-{{ $shipment->id }}">سعر
                                                                الجمرك</label>
                                                            <input type="number" step="0.01" name="customs_cost"
                                                                id="customs_cost-{{ $shipment->id }}"
                                                                class="form-control"
                                                                value="{{ $shipment->customs_cost ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">إغلاق</button>
                                                        <button type="submit" class="btn btn-primary">حفظ
                                                            التغييرات</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links -->
                    <div class="d-flex justify-content-center mt-4">
                        <ul class="pagination">
                            @if ($shipments->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">⏮ السابق</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $shipments->previousPageUrl() }}"
                                        rel="prev">⏮ السابق</a></li>
                            @endif

                            @foreach ($shipments->links()->elements[0] ?? [] as $page => $url)
                                @if ($page == $shipments->currentPage())
                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                @else
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $url }}">{{ $page }}</a></li>
                                @endif
                            @endforeach

                            @if ($shipments->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $shipments->nextPageUrl() }}"
                                        rel="next">التالي ⏭</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">التالي ⏭</span></li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            ليس لديك صلاحية لعرض هذه الصفحة.
        </div>
    @endif
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // تهيئة DataTable واحدة
            $('#example').DataTable({
                paging: false, // تعطيل تصفح DataTables لاستخدام تصفح Laravel
                searching: false, // تعطيل البحث الافتراضي لـ DataTable
                ordering: true,
                info: false,
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
                        }
                    },
                    'colvis'
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/ar.json'
                }
            });

            // إضافة صفوف المصاريف ديناميكيًا لكل modal
            $('.add-expense-row').click(function() {
                const shipmentId = $(this).data('shipment-id');
                const container = $(`#expenses-container-${shipmentId}`);
                console.log('Adding new row for shipment ID:', shipmentId);

                // التحقق مما إذا كان الصف الأخير (غير القراءة فقط) مملوءًا
                const lastRow = container.find('.expense-row').last();
                const lastPriceInput = lastRow.find('input[name$="[price]"]').not('[readonly]');
                const lastReasonInput = lastRow.find('input[name$="[reason]"]').not('[readonly]');
                const lastPrice = lastPriceInput.val();
                const lastReason = lastReasonInput.val();

                console.log('Last row check:', {
                    isReadonly: lastRow.find('input[readonly]').length > 0,
                    price: lastPrice,
                    reason: lastReason
                });

                if (lastPriceInput.length > 0 && (!lastPrice || !lastReason)) {
                    alert('يرجى ملء الحقول الحالية قبل إضافة مصروف جديد.');
                    return;
                }

                // حساب أعلى مؤشر من المدخلات الحالية
                let maxIndex = -1;
                container.find('input[name^="expenses"][name$="[price]"]').each(function() {
                    const match = $(this).attr('name').match(/expenses\[(\d+)\]\[price\]/);
                    if (match) {
                        const currentIndex = parseInt(match[1]);
                        if (currentIndex > maxIndex) {
                            maxIndex = currentIndex;
                        }
                    }
                });

                // استخدام المؤشر التالي
                const newIndex = maxIndex + 1;
                const currencyLabel = container.find('input[name^="expenses"][name$="[price]"]').first()
                    .parent().find('label').text().match(/\((.*)\)/)?.[1] || 'غير معروف';

                const newRow = `
                        <div class="expense-row flex gap-2 mb-2">
                            <div class="form-group w-1/3">
                                <label>السعر (${currencyLabel})</label>
                                <input type="number" name="expenses[${newIndex}][price]" step="0.01" class="form-control" required>
                            </div>
                            <div class="form-group w-2/3">
                                <label>السبب</label>
                                <input type="text" name="expenses[${newIndex}][reason]" class="form-control" required>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm remove-expense" style="margin-top: 28px;">
                                <i class="fa fa-trash text-white"></i>
                            </button>
                        </div>`;
                container.append(newRow);
                console.log('New row added with index:', newIndex);
                console.log('Container HTML after append:', container.html());
            });

            // إزالة صف المصروفات (فقط للصفوف غير القراءة فقط)
            $(document).on('click', '.remove-expense', function() {
                if (!$(this).is(':disabled')) {
                    $(this).closest('.expense-row').remove();
                    console.log('Removed expense row');
                }
            });

            // تسجيل محتويات النموذج قبل الإرسال
            $('form[id^="expenses-form-"]').on('click', 'button[type="submit"]', function() {
                const $form = $(this).closest('form');
                const formId = $form.attr('id');
                const container = $form.find(`div[id^="expenses-container-"]`);
                console.log(`Form ${formId} contents before submission:`, container.html());
                console.log(`Form ${formId} serialized data:`, $form.serializeArray());
            });

            // منع الإرسال المزدوج والتحقق من صحة النموذج
            $('form[id^="expenses-form-"]').on('submit', function(e) {
                const $form = $(this);
                const $submitButton = $form.find('button[type="submit"]');
                let hasErrors = false;

                // التحقق من أن جميع الحقول غير القراءة فقط مملوءة
                $form.find('input[name$="[price]"]').not('[readonly]').each(function() {
                    if (!$(this).val()) {
                        hasErrors = true;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });
                $form.find('input[name$="[reason]"]').not('[readonly]').each(function() {
                    if (!$(this).val()) {
                        hasErrors = true;
                        $(this).addClass('is-invalid');
                    } else {
                        $(this).removeClass('is-invalid');
                    }
                });

                if (hasErrors) {
                    e.preventDefault();
                    alert('يرجى ملء جميع الحقول المطلوبة.');
                    $submitButton.prop('disabled', false);
                    return false;
                }

                // منع الإرسال المزدوج
                if ($submitButton.is(':disabled')) {
                    e.preventDefault();
                    return false;
                }
                $submitButton.prop('disabled', true).html(
                    '<i class="fa fa-spinner fa-spin"></i> جاري الحفظ...');
            });

            // معالجة قيود إدخال نافذة الخصم
            @foreach ($shipments as $shipment)
                const isRateSelect_{{ $shipment->id }} = document.getElementById('is_rate-{{ $shipment->id }}');
                const discountInput_{{ $shipment->id }} = document.getElementById('discount-{{ $shipment->id }}');

                isRateSelect_{{ $shipment->id }}.addEventListener('change', function() {
                    if (this.value === '1') {
                        discountInput_{{ $shipment->id }}.max = 100;
                        discountInput_{{ $shipment->id }}.min = 0;
                        discountInput_{{ $shipment->id }}.step = 0.01;
                        discountInput_{{ $shipment->id }}.placeholder = 'أدخل النسبة (0-100)';
                    } else {
                        discountInput_{{ $shipment->id }}.removeAttribute('max');
                        discountInput_{{ $shipment->id }}.min = 0;
                        discountInput_{{ $shipment->id }}.step = 1;
                        discountInput_{{ $shipment->id }}.placeholder = 'أدخل القيمة الثابتة';
                    }
                });

                isRateSelect_{{ $shipment->id }}.dispatchEvent(new Event('change'));
            @endforeach
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endsection
