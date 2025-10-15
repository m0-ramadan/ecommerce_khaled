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
                        <h3 style="font-family: 'Cairo', sans-serif;">قصص النجاح</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">قصص النجاح</li>
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
                                <a href="{{url(route('story.create'))}}"><button class="btn-success btn-lg">أضــــافة قصــة جـديـدة</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align:right">العنــوان</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align:right">المحتــوي</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align:right">راس المال </th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align:right">نسبة هامش الربح</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align:right">الربح</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align:right">المتبقى</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align:right">الصـــورة</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align:right">العمليــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($stories as $story)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$story->getTranslation('title','ar')}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$story->getTranslation('content','ar')}}</td>
                                            <td>
                                                @php $img = explode(',', $story->image); @endphp
                                                <img width="100" src="{{asset('public/' . $img[0])}}">
                                            </td>
                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$story->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$story->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$story->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديل العـــــــرض</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('story.update',$story->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-sm-12 col-xl-6 xl-100">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$story->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيــة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$story->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيـــة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$story->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الايطاليــة</a></li>
                                                                        </ul>

                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$story->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان القصة باللغة العربية</label>
                                                                                            <input name="title_ar" class="form-control" id="validationCustom01" type="text" value="{{$story->getTranslation('title','ar') }}" required="">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $story->id }}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتوي القصة باللغة العربية</label>
                                                                                            <textarea name="content_ar" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12"> {{$story->getTranslation('content','ar') }}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="profile-icon{{$story->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان القصة باللغــة الانجليزيـــة</label>
                                                                                            <input name="title_en" class="form-control" id="validationCustom01" type="text" value="{{$story->getTranslation('title', 'en')}}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتوي القصة باللغـــة الانجليزيــــة</label>
                                                                                            <textarea name="content_en" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12"> {{$story->getTranslation('content','en') }}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="contact-icon{{$story->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان القصة باللغــة الايطاليـــة</label>
                                                                                            <input name="title_it" class="form-control" id="validationCustom01" type="text" value="{{$story->getTranslation('title', 'it')}}">
                                                                                        </div>
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتوي القصة باللغـــة الايطاليــــة</label>
                                                                                            <textarea name="content_it" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12"> {{$story->getTranslation('content','it') }}</textarea>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <br>
                                                                <div>
                                                                    <img width="120" src="{{asset('public/' . $story->image)}}">
                                                                    <input class="form-control" type="file" aria-label="file example"  name="image[]" multiple>
                                                                    <br>
                                                                    <div class="invalid-feedback">Example invalid form file feedback</div>
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
                                        <div class="modal fade" id="exampleModal{{$story->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف العرض</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('story.destroy',$story->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $story->id }}">
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
