@extends('Admin.layout.master')

@section('title', 'التحويلات المالية')

@section('css')
<!-- Plugins css start-->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/datatables.css') }}">
<!-- Plugins css Ends-->
@endsection

@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
             <a class="btn btn-success" href="{{ route('admin.transfermoney.create') }}">انشاء حوالة</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="text-align: center;" class="center">المرسل</th>
                            <th style="text-align: center;" class="center">هاتف المرسل</th>
                            <th style="text-align: center;" class="center">المستلم</th>
                            <th style="text-align: center;" class="center">  هاتف المستلم</th>
                            <th style="text-align: center;" class="center">الحوالة</th>
                            <th style="text-align: center;" class="center">العمولة</th>
                            <th style="text-align: center;" class="center">قيمة الصافى</th>
                            <th style="text-align: center;" class="center">عملة الحوالة</th>
                            <th style="text-align: center;" class="center">عملة الاستلام</th>
                            <th style="text-align: center;" class="center">قيمة الاستلام</th>
                            <th style="text-align: center;" class="center">نوع التحويل</th>
                            <th style="text-align: center;" class="center">من فرع</th>
                            <th style="text-align: center;"  class="center">الى فرع</th>
                            <th style="text-align: center;"  class="center" >حالة الحوالة</th>
                            <th style="text-align: right !important;">العمليات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transfermoney as $key => $transfer)


                        <?php
                        $count = \App\Models\TransferMoney::where('transfers_id_created', $transfer->id)->count();
                        
                        ?>
                        <tr>
                            <td  style="text-align: center;"  class="center"> {{ ++$key }}</td>
                            <td style="text-align: center;" class="center"><a href="{{ route('admin.transfermoney.details', $transfer->id) }}"> {{ $transfer->sender_name }}</a></td>
                            <td style="text-align: center;" class="center">{{ $transfer->sender_phone }}</td>
                            <td style="text-align: center;" class="center">{{ $transfer->receiver_name }}</td>
                            <td style="text-align: center;" class="center">{{ $transfer->receiver_phone }}</td>
                            <td style="text-align: center;" class="center"> {{$transfer->transfer_amount}}  </td>
                            <td style="text-align: center;" class="center"> {{$transfer->transaction_fee}}  </td>
                            <td style="text-align: center;" class="center"> {{$transfer->net_value}}  </td>
                            <td style="text-align: center;"  class="center"> {{ $transfer->currency_from }}</td>
                            <td style="text-align: center;" class="center"> {{ $transfer->currency_to }}</td>
                            <td style="text-align: center;" class="center"> {{ $transfer->received_money_mount }}</td>
                            <td style="text-align: center;" class="center"> {{$transfer->transfer_type_name   }}  </td>
                            <td style="text-align: center;" class="center">
    {{ $transfer->branch_from_name ?? '-' }} - 
    {{ $transfer->VaultForm->name ?? '-' }}
</td>
<td style="text-align: center;">
    {{ $transfer->branch_to_name  ?? '-' }} - 
    {{ $transfer->VaultTo->name ?? '-' }}
</td>

                            <td style="text-align: center;" > {{ $transfer->getTransferStatusAttribute() }}</td>
                            <td style="text-align: center;"   class="d-flex gap-1">
                                <a href="{{ route('admin.employees.edit', $transfer->id) }}" class="btn btn-success">
                                    <i class="fa fa-edit text-white"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.transfermoney.destroy', $transfer->id) }}"
                                    style="display:inline;"
                                    onsubmit="return confirm('هل انت متأكد من حذف التحويل المالى');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
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
@endsection

@section('js')
<!-- Plugins JS start-->
<script src="{{ asset('assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatable/datatables/datatable.custom.js') }}"></script>
<script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
<!-- Plugins JS Ends-->
@endsection