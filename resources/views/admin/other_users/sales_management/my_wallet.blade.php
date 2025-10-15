@extends('admin.other_users.apps.sales_management.app')
@section('content')
  
     <div class="page-body">
        <!-- Modal -->
        <!-- Button trigger modal -->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">   المحفظة</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/sales-management')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">المحفظة  </li>
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
                                        <th style="font-family: 'Cairo', sans-serif;">القيمة الكلية </th>
                                        <th style="font-family: 'Cairo', sans-serif;">تاريخ الدفع</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الحالة</th>
                                        <th style="font-family: 'Cairo', sans-serif;"></th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                          <tr>
                                             <td>#</td>
                                             <td style="font-family: 'Cairo', sans-serif;">{{ $EmployeesWallet->total_value }}</td>
                                             <td style="font-family: 'Cairo', sans-serif;">{{$EmployeesWallet->payment_date }}</td>
                                             <td style="font-family: 'Cairo', sans-serif;">{{$EmployeesWallet->status === '1' ? '(paid)'  : '(not paid else)' }}    </td>
                                             <td style="font-family: 'Cairo', sans-serif;"> <a class='btn btn-sm btn-primary' href="#">    طريقة الدفع</a> </td>

                                        </tr>

 
                                     </tbody>
                                </table>
                            </div>
                             


                        </div>
                    </div>
                </div>
                <!-- DOM / jQuery  Ends-->
            </div>
            
            
            
            
            
            
            <!-------------------------------------details of order part ------------------------------>
                @isset( $EmployeesWallet->order)
                    <div class="container-fluid">
                        <div class="row">
                            <!-- DOM / jQuery  Starts-->
                            <div class="col-sm-12">
            
                                <div class="card">
                                    <div class="card-body">
            
                                            <div class="table-responsive">
                                                <table class="display datatables" id="dt-plugin-method">
                                                    <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th style="font-family: 'Cairo', sans-serif;">name   </th>
                                                        <th style="font-family: 'Cairo', sans-serif;">quantity</th>
                                                        <th style="font-family: 'Cairo', sans-serif;">price</th>
                                                        <th style="font-family: 'Cairo', sans-serif;">total</th>
                
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                            @foreach($EmployeesWallet->order->orderitem as $item)
                
                                                              <tr>
                                                                 <td>#</td>
                                                                <td style="font-family: 'Cairo', sans-serif;">{{ $item->product->name }}</td>
                                                                 <td style="font-family: 'Cairo', sans-serif;"> {{ $item->quantity }} </td>
                                                                 <td style="font-family: 'Cairo', sans-serif;"> {{ $item->price }}      </td>
                                                               <td style="font-family: 'Cairo', sans-serif;">{{ $item->total }}     </td>
                
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
                @endisset
            <!------------------------------end -------details of order part ------------------------------>


        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
@section('toaster')
    @jquery
    @toastr_js
    @toastr_render
@endsection
