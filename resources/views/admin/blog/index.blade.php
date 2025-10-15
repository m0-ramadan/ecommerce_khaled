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
                        <h3 style="font-family: 'Cairo', sans-serif;">المقــــــــالاتــــــ</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a style="font-family: 'Cairo', sans-serif;" href="{{url('admin/dashboard')}}">الرئيــــسـية</a></li>
                            <li class="breadcrumb-item">المقــــالات</li>
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
                                <a style="font-family: 'Cairo', sans-serif;" href="{{url(route('blog.create'))}}"><button class="btn-success btn-lg">أضــــافة مقـــال جديــــد</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العنــــوان</th>
                                        <th style="font-family: 'Cairo', sans-serif;">المحتــــوي</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الصـــورة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمـــــليات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($blogs as $blog)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$blog->getTranslation('title','ar')}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$blog->getTranslation('content','ar')}}</td>
                                            <td>@if($blog->image)<img width="120" src="{{asset('public/' . $blog->image)}}">@else
                                                    <img width="120px" src="{{asset('public/'.'images/zummXD2dvAtI.png')}}">@endif</td>
                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$blog->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$blog->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$blog->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">تعديل المقال</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('blog.update',$blog->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <label class="form-label" for="validationCustom01">عنوان المقال عربي</label>
                                                                        <input name="title_ar" class="form-control" id="validationCustom01" type="text" value="{{ $blog->getTranslation('title', 'ar') }}" required="">
                                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                                               value="{{ $blog->id }}">
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row">
                                                                    <label class="form-label">المحتوي عربي</label>
                                                                    <div class="">
                                                                            <textarea  name="body" value="" class="col-md-12" style="height: 100px">{{$blog->content}}</textarea>

                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <br>
                                                                <div>
                                                                    <img width="120" src="{{asset('public/' . $blog->image)}}">
                                                                    <input class="form-control" type="file" aria-label="file example" name="image">
                                                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                                                </div>
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
                                        <div class="modal fade" id="exampleModal{{$blog->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف المقال</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('blog.destroy',$blog->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $blog->id }}">
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
    @toastr_js
    @toastr_render
@endsection
