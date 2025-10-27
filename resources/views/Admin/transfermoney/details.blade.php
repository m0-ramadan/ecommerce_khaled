@extends('Admin.layout.master')

@section('title', 'التحويلات المالية')

@section('css')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm rounded">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">تفاصيل عملية التحويل</h5>
            <a href="{{ route('admin.transfermoney.index') }}" class="btn btn-light btn-sm">عودة</a>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped text-center">
                <tbody>
                    <tr>
                        <th >تاريخ الأنشاء</th>
                        <th >حالة الحوالة</th>
                        <th  >اسم المرسل</th>
                        <th>رقم هاتف المرسل</th>
                        <th >اسم المستلم</th>
                        <th>رقم هاتف المستلم</th>
                    </tr>
                    <tr>
                        <td>{{ $transfer->created_at->format('Y-m-d H:i') }}</td>
                        <td style="color: #d22d3d; font-weight: bold;">{{ $transfer->transfer_status }}</td>
                        <td>{{ $transfer->sender_name ?? '-' }}</td>
                        <td>{{ $transfer->sender_phone ?? '-' }}</td>
                        <td  >{{ $transfer->receiver_name ?? '-' }}</td>
                        <td>{{ $transfer->receiver_phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>من مدينة</th>
                        <th>الى  مدينة</th>   
                        <th>من فرع </th> 
                        <th>الى فرع</th> 
                        <th>من  خزينة </th>
                        <th>الى خزينة</th>
                    </tr>

                    <tr>
                        <td>{{ $transfer->cityFromName ?? '-' }}</td>
                        <td>{{ $transfer->cityToName ?? '-' }}</td>
                        <td>{{ $transfer->branchFromName }}</td>
                        <td>{{$transfer->branchToName}}</td>
                        <td>{{ $transfer->VaultForm->name ?? '-' }} <span style="color:#d22d3d;padding-left:5"> ({{$transfer->VaultForm->balance ?? '-' }}){{ $transfer->VaultForm->currency_name ?? '-' }}</span></td>
                        <td>{{ $transfer->VaultTo->name ?? '-' }} <span style="color:#d22d3d;padding-left:5"> ({{ $transfer->VaultTo->balance ?? '-' }}){{ $transfer->VaultTo->currency_name ?? '-' }} </span></td>
                    </tr>

                    <tr>
                        <th>المسئول عن الأنشاء</th>
                        <th>الحوالة</th>
                        <th>العمولة</th>
                        <th>قيمة الصافى</th>
                        <th>قيمة الاستلام</th>
                        <th> نوع الحوالة</th>
                    </tr>
                     <tr>
                         <td> {{$transfer->creator->name}} </td>
                        <td> {{$transfer->transfer_amount}} {{ $transfer->currency_from }} </td>
                        <td> {{$transfer->transaction_fee}} {{ $transfer->currency_from }}  </td>
                        <td> {{$transfer->net_value}} {{ $transfer->currency_from }} </td>
                        <td> {{ $transfer->received_money_mount }} {{ $transfer->currency_to }}</td>
                        <td> {{$transfer->transfer_type_name   }}  </td>
                        
                     </tr>                  
                   
                    <tr>
                        <th>البنك</th>
                        <th>رقم الحساب </th>
                        <th>اسم صاحب البنك</th>
                        <th colspan="2">رقم الابيان</th>
                        <th>رقم المحظفة</th>
                    </tr>
                   <tr>
                   <td> {{$transfer->bank_name   }}  </td>
                   <td> {{$transfer->account_number   }}  </td>
                   <td> {{$transfer->account_holder_name   }}  </td>
                   <td  colspan="2"> {{$transfer->iban   }}  </td>
                   <td> {{$transfer->wallet_number   }}  </td>
                    </tr>

                    
                  
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
