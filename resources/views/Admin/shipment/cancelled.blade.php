@extends('Admin.layout.master')

@section('title')
shipment
@endsection

@section('css')
<!-- Plugins css start-->
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">
<!-- Plugins css Ends-->
@endsection

@section('content')


<div class="col-sm-12">
    <div class="card">
        <div class="card-header">
            <h4 class="m-3 p-3 bg-success">shipment cancelled</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>

                            <th>Company</th>
                            <th> code</th>
                            <th>Shipment Number</th>

                            <th>Sender name</th>
                            <th>Sender email</th>
                            <th>Sender Phone</th>

                            <th>Client name</th>
                            <th>Client Phone</th>
                            <th>Client Phone 2</th>
                            
                            <th>Weight</th>
                            <th>Price</th>
                            <th>Payment Way</th>
                            
                            <th>Updated at</th>
                            <th>Cancel Reason</th>
                            <th>Custom Status</th>
                            <th> Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipments as $key=>$shipment)
                        <tr>
                            <td> {{++$key}}       </td>
                            <td>{{$shipment->user->name}}</td>
                            
                            <td>{{$shipment->company->name}}</td>
                            <td>{{$shipment->code}}</td>
                            <td>{{$shipment->shipment_no}}</td>
                            
                            <td>{{$shipment->sender_name}}</td>
                            <td>{{$shipment->sender_email}}</td>
                            <td>{{$shipment->sender_phone}}</td>


                            <td>{{$shipment->client_name}}</td>
                            <td>{{$shipment->client_phone}}</td>
                            <td>{{$shipment->client_phone2}}</td>

                            <td> {{$shipment->shipmentDetail->size}}</td>
                            <td> {{$shipment->shipmentDetail->price}}</td>
                            <td> @if($shipment->cash == 1 )
                                Package
                                @else
                                   wallet
                                @endif
                            </td>
                            
                            <td>{{$shipment->updated_at}}</td>
                            <td>{{$shipment->cancel_reason}}</td>
                            <td>
                                <form action="{{route('admin.shipment.custom-check-box',[$shipment->id])}}" method="post">
                                    @csrf
                                    <input class="form-check-input" type="checkbox" id="{{ $shipment->id }}"
                                    @if ($shipment->custom_status) checked @endif>
                                      <label class="form-check-label" for="{{ $shipment->id }}">check</label>
                            
                                    <button type="submit" class="btn btn-sm">Save</button>
                                </form>
                            </td>





                            <td>
                                @if($shipment->cost_returend == 1)
                               <p class="text-success"> <i class="fa fa-check"></i>  </p>
                                @else

                               <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#exampleModal{{$shipment->id}}">Return COst </button>

                             <button class="btn btn-success"> <a  href="{{route('admin.shipment.edit',[$shipment->id])}}"><i class="fa fa-edit text-white"></i>  </a> </button> 




                                @endif

                            </td>

                        </tr>
<!-- model -->

<div class="modal fade" id="exampleModal{{$shipment->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">

<div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">Attach Teacher to Lessons</h5>
      <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">

      <div class="mb-3">
    <p class="text-danger">Are you sure you want to return cost ?</p>
    </div>
    <div class="modal-footer">
      <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
      <a href="{{route('admin.shipment.returncost',$shipment->id)}}" class="btn btn-secondary" >Yes</a>
    </div>
  </div>

</div>
</div>
</div>

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
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="{{asset('assets/js/tooltip-init.js')}}"></script>
<!-- Plugins JS Ends-->
@endsection
