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
                        <h3 style="font-family: 'Cairo', sans-serif;">الالهام والافكار</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الالهام والافكار</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
    @endif
    <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <!-- DOM / jQuery  Starts-->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <a href="{{url(route('inspiration.create'))}}"><button class="btn-success btn-lg">أضــــافة</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الصورة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الرابط</th>
                                        <th style="font-family: 'Cairo', sans-serif;">مكان الصوره في الموقع</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($inspirations as $inspiration)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td>
                                             <?php $textt =substr($inspiration->image, -3); ?>
                                                     @if($textt == "mp4")
                                                    <video width="120" height="100" controls>
                                                      <source src="{{asset('public/'.$inspiration->image)}}" type="video/mp4">
                                                      <source src="{{asset('public/'.$inspiration->image)}}" type="video/ogg">
                                                      Your browser does not support the video tag.
                                                    </video>
                                                    @else
                                                  <img class="img-thumbnail" width="120" src="{{asset('public/'.$inspiration->image)}}">
                                                  @endif
                                            </td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$inspiration->url_link}}</td>
                                            @if($inspiration->type == 0)<td style="font-family: 'Cairo', sans-serif;">الجزء الايمن</td>
                                            @elseif($inspiration->type == 1)<td style="font-family: 'Cairo', sans-serif;">الجزء الوسط</td>
                                            @elseif($inspiration->type ==2)<td style="font-family: 'Cairo', sans-serif;">الجزء الايسر</td>
                                            @endif
                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$inspiration->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$inspiration->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$inspiration->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديل</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('inspiration.update',$inspiration->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div>
                                                                <div class="row g-3">
                                                                    <div>
                                                                        <img width="120px" src="{{asset('public/' . $inspiration->image)}}">
                                                                        <hr>
                                                                        <input class="form-control" type="file" aria-label="file example" name="image">
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-md-12">
                                                                        <label class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">مكان الصورة ف الصفحة:</label>
                                                                        <div class="box ">
                                                                            <select class="fancyselect form-control" style="border:solid 1px #555" name="type">
                                                                                     <option value="">حدد مكان الصورة  </option>
                                                                                <option value="0">القسم الايمن</option>
                                                                                <option value="1">القسم الوسط</option>
                                                                                <option value="2">القسم الايسر</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <hr>


                                                                                                                                <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">كود الاول -المستخدم فى الموبايل</label>
                                                                <input style="border:solid 1px #555" name="url_link" value="{{ $inspiration->link_id}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                             <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الأول افقى</label>
                                                                <input style="border:solid 1px #555" name="location_h" value="{{ $inspiration->link_posh}}" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الأول رائيسى</label>
                                                                <input style="border:solid 1px #555" name="location_v" value="{{ $inspiration->link_posv}}" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">الكود الثانى</label>
                                                                <input style="border:solid 1px #555" name="url_link1"  value="{{ $inspiration->link_id1}}" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>


                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الثانى افقى</label>
                                                                <input style="border:solid 1px #555" name="location_h1"  value="{{ $inspiration->link_posh1}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الثانى رئيسى </label>
                                                                <input style="border:solid 1px #555" name="location_v1"  value="{{ $inspiration->link_posv1}}"  class="form-control" id="validationCustom01" type="text" >
                                                            </div>


                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> الكود الثالث</label>
                                                                <input style="border:solid 1px #555" name="url_link2" value="{{ $inspiration->link_id2}}" class="form-control" id="validationCustom01" type="text"   >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الثالث افقى</label>
                                                                <input style="border:solid 1px #555" name="location_h2"  value="{{ $inspiration->link_posh1}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> موقع الكود الثالث رائيسى</label>
                                                                <input style="border:solid 1px #555" name="location_v2" value="{{ $inspiration->link_posv2}}" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>


                                                                </div>
                                                                <br>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلــــق</button>
                                                                <button class="btn btn-secondary" type="submit">حفـــــــظ</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$inspiration->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف </h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('inspiration.destroy',$inspiration->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $inspiration->id }}">
                                                        <div class="modal-body">هل أنت متاكد من عملية الحذف</div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                            <button class="btn btn-secondary" type="submit">حذف</button>
                                                        </div>
                                                    </form>

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
