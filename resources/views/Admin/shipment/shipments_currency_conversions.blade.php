@extends('Admin.layout.master')

@php
    $currentRoute = request()->path();
@endphp

@section('title')
    تحويلات العملات للشحنات
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <x-breadcrumb :items="[
                    'لوحة التحكم' => '/admin',
                    'تحويلات العملات للشحنات' => '',
                ]" />
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="display" id="example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>من العملة</th>
                                <th>إلى العملة</th>
                                <th>سعر التحويل</th>
                                <th>سعر التحويل للحولات</th>

                                <th>تاريخ الإنشاء</th>
                                <th>تاريخ التحديث</th>
                                {{-- <th>تاريخ الحذف</th> --}}
                                {{-- <th>العمليات</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($conversions as $key => $conversion)
                                <tr class="{{ $conversion->deleted_at ? 'bg-red-100' : '' }}">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $conversion->from_currency_name }}</td>
                                    <td>{{ $conversion->to_currency_name }}</td>
                                    <td>
                                        <form
                                            action="{{ route('admin.shipments_currency_conversions.update', $conversion->id) }}"
                                            method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="conversion_rate"
                                                value="{{ $conversion->conversion_rate }}" step="0.00000001"
                                                class="form-control w-32 p-1" required>
                                            <button type="submit" class="btn btn-success btn-sm" title="تحديث سعر التحويل">
                                                <i class="fa fa-save text-white"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>
                                        <form
                                            action="{{ route('admin.shipments_currency_conversions.update.transfer', $conversion->id) }}"
                                            method="POST" class="d-flex gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="number" name="conversion_transfer_rate"
                                                value="{{ $conversion->conversion_transfer_rate }}" step="0.00000001"
                                                class="form-control w-32 p-1" required>
                                            <button type="submit" class="btn btn-success btn-sm" title="تحديث سعر التحويل">
                                                <i class="fa fa-save text-white"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td>{{ $conversion->created_at ? $conversion->created_at->format('Y-m-d H:i:s') : 'غير متوفر' }}
                                    </td>
                                    <td>{{ $conversion->updated_at ? $conversion->updated_at->format('Y-m-d H:i:s') : 'غير متوفر' }}
                                    </td>
                                    {{-- <td>{{ $conversion->deleted_at ? $conversion->deleted_at->format('Y-m-d H:i:s') : 'غير محذوف' }} --}}
                                    </td>
                                    {{-- <td class="d-flex gap-1">
                                        <form method="post" action="#">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-primary btn-sm" title="حذف">
                                                <i class="fa fa-trash-o text-white"></i>
                                            </button>
                                        </form>
                                    </td> --}}
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
    <script>
        $(document).ready(function() {
            $('#example').DataTable({
                pagingType: 'full_numbers',
                dom: 'lBfrtip',
                buttons: [{
                        extend: 'copyHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6]
                        }
                    },
                    'colvis'
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.24/i18n/Arabic.json'
                }
            });
        });
    </script>
@endsection
