@extends('admin.other_users.apps.sales_management.app')
@section('content')
  
     <div class="page-body">
        <!-- Modal -->
        <!-- Button trigger modal -->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">كود المستخدم</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/sales-management')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">كود المستخدم</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">

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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                             </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الكود</th>
                                           <th style="font-family: 'Cairo', sans-serif;">القيمة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الحالة</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                          <tr>
                                             <td>#</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{ $promoCode->code }}</td>
                                             <td style="font-family: 'Cairo', sans-serif;">{{$promoCode->value }}</td>
                                             <td style="font-family: 'Cairo', sans-serif;">{{$promoCode->status === '1' ? 'active'  : 'deactive'}}</td>
                                        </tr>

 
                                     </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- DOM / jQuery  Ends-->
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
@section('toaster')
    @jquery
    @toastr_js
    @toastr_render
@endsection
