@extends('Admin.layout.master')

@section('title')
shipmentprice
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
            <h5>shipment prices</h5>

            <a class="btn btn-success" href="{{route('admin.shipmentprice.create')}}">Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>company</th>
                            <th>weight</th>
                            <th>price</th>
                            <th>currency</th>
                            <th>increase</th>
                            <th>tax</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipmentprices as $key=>$shipmentprice)
                        <tr>
                            <td> {{++$key}}                          </td>
                            <td> {{$shipmentprice->company->name}}   </td>
                            <td> {{$shipmentprice->weight}}          </td>
                            <td> {{$shipmentprice->price}}           </td>
                            <td> {{$shipmentprice->currency}}        </td>
                            <td> {{$shipmentprice->increase}}        </td>
                            <td> {{$shipmentprice->tax}}             </td>
                            <td>
                               <button class="btn btn-success"> <a  href="{{route('admin.shipmentprice.edit',[$shipmentprice->id])}}"><i class="fa fa-edit text-white"></i>  </a> </button>

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
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="{{asset('assets/js/tooltip-init.js')}}"></script>
<!-- Plugins JS Ends-->
@endsection
