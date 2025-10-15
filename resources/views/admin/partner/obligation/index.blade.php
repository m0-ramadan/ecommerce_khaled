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
                        <h3 style="font-family: 'Cairo', sans-serif;">بيانات الشريك الاستثماري</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">بيانات الشريك الاستثماري
                            </li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">التكايف
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
                    <a href="{{ route('partners.obligation.create') }}"><button class="btn-success btn-lg"
                            style="font-family: 'Cairo', sans-serif;">أضافة</button></a>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                        <tr>
                                            <th style="font-family: 'Cairo', sans-serif;">اســــــم المستخــــــدم</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الموضوع</th>
                                            {{-- <th style="font-family: 'Cairo', sans-serif;">شهري</th> --}}
                                            {{-- <th style="font-family: 'Cairo', sans-serif;">سنوي</th> --}}
                                            <th style="font-family: 'Cairo', sans-serif;">اجمالي</th>
                                            <th style="font-family: 'Cairo', sans-serif;">نوع العملية</th>
                                            <th style="font-family: 'Cairo', sans-serif;">التاريخ</th>
                                            <th style="font-family: 'Cairo', sans-serif;">النوع</th>
                                            <th style="font-family: 'Cairo', sans-serif;">ملف</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العمليـــات</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($obligations as $obligation)
                                            <tr>
                                                <td>{{ $obligation->client->name }}</td>
                                                <td>{{ $obligation->subject }}</td>
                                                {{-- <td>{{ $obligation->monthly_dues }}</td> --}}
                                                {{-- <td>{{ $obligation->annual_dues }}</td> --}}
                                                <td>{{ $obligation->total_money }}</td>
                                                <td>{{ $obligation->payment_method }}</td>
                                                <td>{{ $obligation->date }}</td>
                                                <td>{{ $obligation->date }}</td>
                                                <td><a href="{{ url('public/' . $obligation->file) }}"
                                                        class="btn btn-info">File</td>
                                                <td>
                                                    <form action="{{ route('partners.obligation.delete', $obligation) }}"
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
