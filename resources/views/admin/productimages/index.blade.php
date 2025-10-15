@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <!-- Modal -->
        <!-- Button trigger modal -->
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">معرض صور المنتج</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيســــية</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">المعرض</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">

                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <!-- DOM / jQuery  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                    <a href="{{url(route('productimagesCreate',$id))}}"><button class="btn-success btn-lg mb-4" style="font-family: 'Cairo', sans-serif;">إضافة صورة جديدة</button></a>
                            <br>
                            </div>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;"> اســــم المنتـــج</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الصورة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الأسم البديل</th>
                                        <th style="font-family: 'Cairo', sans-serif;">عنوان الصورة</th>
                                         <th  style="font-family: 'Cairo', sans-serif;">العمليــــــاتــ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($features as $feature)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$feature->product->name}}</td>
                                            <td><img width="70" src="{{asset('public/'.$feature->src)}}" alt="product"> </td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$feature->alttxt}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$feature->titletxt}}</td>
                                            <td>

                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$feature->id}}"><i class="fa fa-remove"></i>
                                                </button>

                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$feature->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                            </td>
                                        </tr>
                                       <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$feature->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف صورة المنتج</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('productimagesdestroy')}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $feature->id }}">
                                                                 <input id="id" type="hidden" name="product_id" class="form-control"
                                                               value="{{ $feature->product_id }}">
                                                        <div class="modal-body">هل انت متاكد من    حذف الصورة   </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                            <button class="btn btn-secondary" type="submit">حذف</button>
                                                        </div>
                                                    </form>

                                                </div>
                                            </div>
                                        </div>




    <div class="modal fade " id="edit{{$feature->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog " style="max-width:1200px" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('productimagesupdate',$feature->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-md-12">
                                                                <div class="card col-md-12">
                                                                    <div class="card-body col-md-12">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$feature->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيـة</a></li>
                                                                          </ul>
                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$feature->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Alt TXT </label>
                                                                                            <input name="alttxt" class="form-control" id="validationCustom01" type="text" value="{{$feature->alttxt}}" required="">
                                                                                            
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $feature->id }}">
                                                                                        </div>


                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Title TXT </label>
                                                                                            <input name="titletxt" class="form-control" id="validationCustom01" type="text" value="{{$feature->titletxt}}" required="">
                                                                                            
                                                                                         </div>
                                                                                        
                                                                                       
                                                                                         <div>
                                                                                            <label style="display: block;">  الصورة الرئيسية </label>
                                                                                            <img width="120px" src="{{asset('public/'.$feature->src)}}">
                                                                                            <input class="form-control"  type="file" aria-label="file example" name="image">
                                                                                        </div>
                                                                                        
                                                                                    </div>
                                                                                    <br>
                                                                      
                                                                                </div>
                                                                            </div>
                                                                           
                                                                      
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                              

                          
                             
                                                            </div>
                                                          
                                                    
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلـــق</button>
                                                                <button class="btn btn-secondary" type="submit">حفـــظ</button>
                                                            </div>
                                                        </form>
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
                <!-- DOM / jQuery  Ends-->
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
@section('toaster')
    @toastr_js
    @toastr_render
@endsection
