@extends('Admin.layout.master')

@section('title')
    رسائل تواصل معنا
@endsection

@section('css')
    <!-- DataTables Core CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <!-- DataTables Responsive -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- DataTables Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <!-- Select2 (إذا أردت استخدامه في الفلاتر لاحقاً) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        /* تنسيق إضافي للجدول */
        .dataTables_wrapper .dt-buttons {
            margin-bottom: 15px;
        }
        .message-cell {
            max-width: 250px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            cursor: pointer;
        }
        .message-cell:hover {
            text-decoration: underline;
        }
        .dt-buttons .btn {
            padding: 5px 10px;
            font-size: 13px;
        }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y" dir="rtl">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.index') }}">الرئيسية</a>
                </li>
                <li class="breadcrumb-item active">رسائل تواصل معنا</li>
            </ol>
        </nav>

        <!-- Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0">رسائل تواصل معنا</h5>
                <div class="d-flex gap-2">
                    <!-- حقل البحث -->
                    <div class="position-relative" style="min-width: 280px;">
                        <input type="text" id="customSearch" class="form-control" placeholder="بحث في الرسائل...">
                        <i class="fas fa-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #adb5bd;"></i>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="contactsTable" style="width:100%">
                        <thead>
                            <tr>
                                <th style="text-align: center;">#</th>
                                <th style="text-align: center;">الاسم الأول</th>
                                <th style="text-align: center;">اسم العائلة</th>
                                <th style="text-align: center;">رقم الجوال</th>
                                <th style="text-align: center;">البريد الإلكتروني</th>
                                <th style="text-align: center;">المشكلة</th>
                                <th style="text-align: center;">الرسالة</th>
                                <th style="text-align: center;">تاريخ الإرسال</th>
                                <th style="text-align: center;">العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($contacts as $key => $contact)
                                <tr>
                                    <td style="text-align: center;">{{ ++$key }}</td>
                                    <td style="text-align: center;">{{ $contact->first_name }}</td>
                                    <td style="text-align: center;">{{ $contact->last_name }}</td>
                                    <td style="text-align: center;" dir="ltr">{{ $contact->phone }}</td>
                                    <td style="text-align: center;" dir="ltr">{{ $contact->email }}</td>
                                    <td style="text-align: center;">{{ $contact->company ?? 'غير محدد' }}</td>
                                    <td style="text-align: right;">
                                        <span class="message-cell" 
                                              data-bs-toggle="tooltip" 
                                              data-bs-placement="top" 
                                              title="{{ e($contact->message) }}">
                                            {{ Str::limit($contact->message, 60) }}
                                        </span>
                                    </td>
                                    <td style="text-align: center;" dir="ltr">
                                        {{ $contact->created_at ? $contact->created_at->format('Y-m-d H:i') : 'غير محدد' }}
                                    </td>
                                    <td style="text-align: center;">
                                        <button class="btn btn-danger btn-sm delete-contact" 
                                                data-id="{{ $contact->id }}" 
                                                data-name="{{ $contact->first_name . ' ' . $contact->last_name }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- jQuery (تأكد من وجوده في الماستر، إذا لم يوجد أضفه) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables Core -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Responsive -->
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <!-- DataTables Buttons + التصدير -->
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap Tooltip -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            // تهيئة Tooltip
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // إعداد DataTable
            var table = $('#contactsTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/ar.json'
                },
                responsive: true,
                autoWidth: false,
                ordering: true,
                dom: '<"row"<"col-md-6"B><"col-md-6"f>>rt<"row"<"col-md-6"i><"col-md-6"p>>',
                buttons: [
                    {
                        extend: 'copyHtml5',
                        text: '<i class="far fa-copy"></i> نسخ',
                        className: 'btn btn-outline-secondary btn-sm',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel"></i> Excel',
                        className: 'btn btn-outline-success btn-sm',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="far fa-file-pdf"></i> PDF',
                        className: 'btn btn-outline-danger btn-sm',
                        exportOptions: { columns: ':visible' }
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> طباعة',
                        className: 'btn btn-outline-dark btn-sm',
                        exportOptions: { columns: ':visible' }
                    }
                ],
                initComplete: function() {
                    // ربط البحث المخصص بالـ DataTable
                    $('#customSearch').on('keyup', function() {
                        table.search(this.value).draw();
                    });
                }
            });

            // حذف الرسالة مع SweetAlert
            $(document).on('click', '.delete-contact', function() {
                var contactId = $(this).data('id');
                var contactName = $(this).data('name');

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "سيتم حذف رسالة " + contactName + " نهائياً",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذفها',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // إنشاء فورم مخفي للإرسال
                        var form = $('<form>', {
                            'method': 'POST',
                            'action': '{{ route("admin.contactus.destroy", ":id") }}'.replace(':id', contactId),
                            'style': 'display:none;'
                        });
                        form.append($('<input>', { 'name': '_token', 'value': '{{ csrf_token() }}' }));
                        form.append($('<input>', { 'name': '_method', 'value': 'DELETE' }));
                        $('body').append(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
