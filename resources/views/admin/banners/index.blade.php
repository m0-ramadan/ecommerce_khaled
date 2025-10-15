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
                        <h3 style="font-family: 'Cairo', sans-serif;">الصــــــور المتحركــــة</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيـــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الصــور المتحركـــــــة</li>
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
                                <a href="{{url(route('banners.create'))}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">أضافـــــــة صــورة جـديــدة</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الصــــــورة </th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليـــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($banners as $banner)
                                    
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td><img width="120" src="{{asset('public/' . $banner->image)}}"></td>
                                            {{-- <td><img width="120" src="{{asset('public/' . $banner->image_mop)}}"></td> --}}
                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$banner->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$banner->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$banner->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات القسم الرئيسي</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('banners.update', $banner->id) }}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div>
                                                                <div>
                                                                    <label> صورة البانر الخاصة بالموقع للغة العربية</label>
                                                                    <img width="120" src="{{ asset('public/' . $banner->getTranslation('image', 'ar')) }}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image_ar">
                                                                </div>
                                                                <br>
                                                                <div>
                                                                    <label> صورة البانر الخاصة بالموقع للغة الانجليزية</label>
                                                                    <img width="120" src="{{ asset('public/' . $banner->getTranslation('image', 'en')) }}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image_en">
                                                                </div>
                                                                <br>
                                                                {{-- <div>
                                                                    <label> صورة البانر الخاصة بالموقع للغة الايطالية</label>
                                                                    <img width="120" src="{{ asset('public/' . $banner->getTranslation('image', 'it')) }}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image_it">
                                                                </div>
                                                                <br> --}}
                                                                
                                                                {{-- <div>
                                                                    <label> صورة البانر الخاصة بالموبيل للغة العربية</label>
                                                                    <img width="120" src="{{ asset('public/' . $banner->getTranslation('image_mop', 'ar')) }}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image_mop_ar">
                                                                </div>
                                                                <br>
                                                                <div>
                                                                    <label> صورة البانر الخاصة بالموبيل للغة الانجليزية</label>
                                                                    <img width="120" src="{{ asset('public/' . $banner->getTranslation('image_mop', 'en')) }}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image_mop_en">
                                                                </div>
                                                                <br>
                                                                <div>
                                                                    <label> صورة البانر الخاصة بالموبيل للغة الايطالية</label>
                                                                    <img width="120" src="{{ asset('public/' . $banner->getTranslation('image_mop', 'it')) }}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image_mop_it">
                                                                </div>
                                                                <br> --}}
                                                                
                                                                <br>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                                <button class="btn btn-secondary" type="submit">حفظ</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$banner->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف الصورة المتحركة</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('banners.destroy',$banner->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $banner->id }}">
                                                        <div class="modal-body">هل أنت متاكد من الحذف</div>
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
    @jquery
    @toastr_js
    @toastr_render
@endsection

