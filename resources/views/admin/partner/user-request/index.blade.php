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
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الطلبات
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
                    <a href="{{ route('partners.requests.create') }}"><button class="btn-success btn-lg"
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
                                            <th style="font-family: 'Cairo', sans-serif;">اســــــم المنتج</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الرد</th>
                                            {{-- <th style="font-family: 'Cairo', sans-serif;">الضرائب</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الذكاة</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الربح</th>
                                            <th style="font-family: 'Cairo', sans-serif;">نسبة الفيزا</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الخصم</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الشحن</th>
                                            <th style="font-family: 'Cairo', sans-serif;">التطوير</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الدعاية</th>
                                            <th style="font-family: 'Cairo', sans-serif;">التأمين</th> --}}
                                            <th style="font-family: 'Cairo', sans-serif;">نشط</th>
                                            <th style="font-family: 'Cairo', sans-serif;">الحالة</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العمليـــات</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($userRequests as $userRequest)
                                            <tr>
                                                <td>{{ $userRequest->client->name }}</td>
                                                <td>{{ $userRequest->product->name }}</td>
                                                <td>{{ $userRequest->details }}</td>
                                                {{-- <td>{{ $userRequest->tax }}</td>
                                                <td>{{ $userRequest->zakat }}</td>
                                                <td>{{ $userRequest->profit_product }}</td>
                                                <td>{{ $userRequest->visa_percentage }}</td>
                                                <td>{{ $userRequest->discount }}</td>
                                                <td>{{ $userRequest->shipping }}</td>
                                                <td>{{ $userRequest->developement }}</td>
                                                <td>{{ $userRequest->advertising }}</td>
                                                <td>{{ $userRequest->Insurance }}</td> --}}
                                                <td>{{ $userRequest->is_active ? 'نشط' : 'غير نشط' }}</td>
                                                <td>
                                                    @if ($userRequest->status == $userRequest::REQUEST_STATUS['pending'])
                                                        منتظر الرد
                                                    @elseif($userRequest->status == $userRequest::REQUEST_STATUS['accept'])
                                                        تمت الموافقة
                                                    @elseif($userRequest->status == $userRequest::REQUEST_STATUS['reject'])
                                                        تم الرفض
                                                    @endif
                                                </td>
                                                <td>
                                                    <form action="{{ route('partners.requests.delete', $userRequest) }}"
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
