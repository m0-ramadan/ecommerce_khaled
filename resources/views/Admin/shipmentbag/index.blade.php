@extends('Admin.layout.master')

@section('title')
shipment bags
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
            <h5>shipmentbags</h5>

            <!-- <a class="btn btn-success" href="{{route('admin.shipmentbag.create')}}">Add </a> -->
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Count</th>
                            <th>address</th>
                            <th>email</th>
                            <th>phone</th>
                            <th>region</th>
                            <th>Country</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipmentbags as $key=>$shipmentbag)
                        <tr>
                            <td> {{++$key}}       </td>
                            <td>{{$shipmentbag->user->name}} </td>
                            <td>{{$shipmentbag->name}} </td>
                            <td>{{$shipmentbag->size}} </td>
                            <td>{{$shipmentbag->count}}</td>
                            <td>{{$shipmentbag->address}}</td>
                            <td>{{$shipmentbag->phone}} </td>
                            <td>{{$shipmentbag->region->name}} </td>
                            <td>{{$shipmentbag->country->name}} </td>

                            <td>
                               <!-- <button class="btn btn-success"> <a  href="{{route('admin.shipmentbag.edit',[$shipmentbag->id])}}"><i class="fa fa-edit"></i>  </a> </button> -->
                                <form method="post" action="{{route('admin.shipmentbag.destroy',$shipmentbag->id)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary"  ><i class="fa fa-trash-o"></i></button>
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
