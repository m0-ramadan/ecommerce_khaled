@extends('Admin.layout.master')

@section('title')
خدمات الشركة
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
            <h5>خدمات شركة اويا</h5>

             <a class="btn btn-success" href="{{route('admin.logisticservice.create')}}">اضافة خدمة جديدة</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="display" id="basic-1">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th  style="text-align: center;">عنوان الخدمة</th>
                            <th style="text-align: center;">صورة الخدمة</th>
                              <th style="text-align: center;">العمليات</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logisticservices as $key=>$logisticservice)
                        <tr>
                            <td style="text-align: center;"> {{++$key}}       </td>
                            <td style="text-align: center;">{{$logisticservice->name}} </td>
                            <td style="text-align: center;"> <img style= "width:200px;"src= "{{asset($logisticservice->image)}}"> </td>
 
                            <td style="text-align: center;">
                               <button class="btn btn-success"> <a  href="{{route('admin.logisticservice.edit',[$logisticservice->id])}}"><i class="fa fa-edit text-white"></i>  </a> </button>
                                <form method="post" action="{{route('admin.logisticservice.destroy',$logisticservice->id)}}">
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
