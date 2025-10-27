@extends('Admin.layout.master')
@section('title')
wallets
@endsection
@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h5>withdraw&deposit</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                 <table class="display" id="example">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Amount</th>
                             <th>Task Type</th>
                             <th>Created At</th>
                            <th>Status</th>
                            <!--<th>Action</th>-->
                        </tr>
                    </thead>
                    <tbody id='shipment-table-body'>
                        @foreach($wallets as $key => $wallet)
                         <tr>
                            <td> {{++$key }}    </td>
                            <td>{{ $wallet->user->name }}</td>
                            <td>{{ $wallet->user->email }}</td>
                            <td>{{ $wallet->amount }}</td>
                             <td>{{ $wallet->type }}</td>
                             <td>{{ $wallet->created_at ?? ""}}</td>
                           <td>success</td>
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
    $('#example').DataTable( {
         pagingType: 'full_numbers',
             dom: 'lBfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                      columns: [0,1,2,3,4,5,6]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                      columns: [0,1,2,3,4,5,6]
                }
            },
          
            {
                extend: 'print',
                exportOptions: {
                    columns: [ 0, 1,2,3,4,5,6]
                }
            },
            'colvis'
        ],
        
        
    } );
} );
</script>
@endsection
