@extends('admin.layout.master')

@section('title')
Code
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
            <h5>Codes</h5>

            <a class="btn btn-success" href="{{route('admin.code.create')}}">Add </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Company</th>
                            <th>discount</th>
                            <th>type</th>
                            <th>from</th>
                            <th>to</th>
                            <th>time</th>
                            <th>status </th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($codes as $key=>$code)
                        <tr>
                            <td> {{++$key}}       </td>
                            <td>{{$code->code}} </td>
                            <td> {{$code->company->name}} </td>
                            <td>{{$code->discount}} </td>
                            <td>{{$code->type}}</td>
                            <td>{{$code->From}}</td>
                            <td>{{$code->to}}</td>
                            <td>{{$code->time}}</td>
                            <td>{{$code->status}}</td>

                            <td>
                               <button class="btn btn-success"> <a  href="{{route('admin.code.edit',[$code->id])}}"><i class="fa fa-edit text-white"></i>  </a> </button>
                                <form method="post" action="{{route('admin.code.destroy',$code->id)}}">
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
