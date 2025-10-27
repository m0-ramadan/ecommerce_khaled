@extends('Admin.layout.master')

@section('title')
shipmentcompany
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
            <h5>shipment companies</h5>

            <a class="btn btn-success" href="{{route('admin.shipmentcompany.create')}}">Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>status</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipmentcompanies as $key=>$shipmentcompany)
                        <tr>
                            <td> {{++$key}}       </td>
                            <td>{{$shipmentcompany->name}} </td>
                            <td> <img style= "width:200px;"src= "{{asset($shipmentcompany->image)}}"> </td>

                            <td>@if($shipmentcompany->status == 1) <p class="text-success">Active </p>@else <p class="text-danger"> inactive</p>@endif</td>

                            <td>
                               <button class="btn btn-success"> <a  href="{{route('admin.shipmentcompany.edit',[$shipmentcompany->id])}}"><i class="fa fa-edit text-white"></i>  </a> </button>
                                <form method="post" action="{{route('admin.shipmentcompany.destroy',$shipmentcompany->id)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary"  ><i class="fa fa-trash-o "></i></button>
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
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script src="{{asset('assets/js/tooltip-init.js')}}"></script>
<!-- Plugins JS Ends-->
@endsection
