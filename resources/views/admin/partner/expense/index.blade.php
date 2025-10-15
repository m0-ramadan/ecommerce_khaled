@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Modal -->
        <!-- Button trigger modal -->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">المصاريف
                            </li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <!-- DOM / jQuery  Starts-->
                <div class="col-sm-6">
                    <a href="{{ route('partners.expenses.create') }}"><button class="btn-success btn-lg"
                            style="font-family: 'Cairo', sans-serif;">أضافة</button></a>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                        <tr>
                                            <th style="text-align:right;font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">اســــــم المستخــــــدم</th>
                                            <th style="text-align:right;font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">البند</th>
                                            <th style="text-align:right;font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">المبلغ</th>
                                              <th style="text-align:right;font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center"الملف</th>
                                            
                                            <th style="text-align:right;font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">العمليـــات</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->client->name }}</td>
                                                <td>{{ $expense->details }}</td>
                                                <td>{{ $expense->total_money }}</td>
                                                <td><a href="{{ url('public/' . $expense->file) }}"
                                                    class="btn btn-info">File</td>
                                                <td>
                                                    <form action="{{ route('partners.expenses.delete', $expense) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('toaster')
    @jquery
    @toastr_js
    @toastr_render
@endsection
