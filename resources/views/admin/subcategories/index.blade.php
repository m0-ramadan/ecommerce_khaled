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
                        <h3 style="font-family: 'Cairo', sans-serif;">الأقســــــام الفرعيــــــة</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيـــــسية</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الأقســـــــام الفرعيــــــة</li>
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
                                <a href="{{url(route('subcategory.create'))}}"><button class="btn-success btn-sm" style="font-family: 'Cairo', sans-serif;">أضــــــافة قســـــم فرعـــــي</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th style="text-align: center;">#</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align: center;"> العنوان</th>
                                         <th style="font-family: 'Cairo', sans-serif;text-align: center;"> القسم الرئيسى</th>
                                          <th style="font-family: 'Cairo', sans-serif;">الصـــــورة</th>
                                        <th style="font-family: 'Cairo', sans-serif;text-align: center;">العمليـــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($subCategories as $subcategory)
                                        <tr>
                                            <?php $i++; ?>
                                            <td style="text-align: center;">{{$i}}</td>
                                            <td style="text-align: center;">{{$subcategory->getTRanslation('name','ar')}}</td>
                                                 <td style="text-align: center;">{{App\Models\Category::where('id',$subcategory->category_id)->first()->name}}</td>
                                              <td><img width="120" src="{{asset('public/' . $subcategory->image)}}"></td> 
                                            <td style="text-align: center;">
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$subcategory->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$subcategory->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$subcategory->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعـديل بيـانـات القســم الـفرعي</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('subcategory.update',$subcategory->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-sm-12 col-xl-6 xl-100">
                                                                <div class="card">
                                                                    <div class="card-body">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$subcategory->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغــة العربيــة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$subcategory->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيــة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$subcategory->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الايطاليــة</a></li>
                                                                        </ul>
                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$subcategory->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الرئـيسي باللغــة العربيــة</label>
                                                                                        <input name="name_ar" class="form-control" id="validationCustom01" type="text" value="{{$subcategory->getTranslation('name', 'ar')}}" required="">
                                                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                                                               value="{{ $subcategory->id }}">
                                                                                    </div>
                                                                                </div>
                                                                                <br>
                                                                                <!--<div class="row">-->
                                                                                <!--    <div class="col-md-12">-->
                                                                                <!--        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيل باللغــة العربيــة</label>-->
                                                                                <!--        <input name="details_ar" class="form-control" id="validationCustom01" type="text" value="{{$subcategory->getTranslation('details', 'ar')}}">-->
                                                                                <!--    </div>-->
                                                                                <!--</div>-->
                                                                            </div>
                                                                            <div class="tab-pane fade" id="profile-icon{{$subcategory->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الرئـيسي باللغــة الانجليزيـــة</label>
                                                                                        <input name="name_en" class="form-control" id="validationCustom01" type="text" value="{{$subcategory->getTranslation('name', 'en')}}">
                                                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                                                               value="{{ $subcategory->id }}">
                                                                                    </div>
                                                                                </div>
                                                                                <br>
                                                                                <!--<div class="row">-->
                                                                                <!--    <div class="col-md-12">-->
                                                                                <!--        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيل باللغــة الانجليزيـــة</label>-->
                                                                                <!--        <input name="details_en" class="form-control" id="validationCustom01" type="text" value="{{$subcategory->getTranslation('details', 'en')}}">-->
                                                                                <!--    </div>-->
                                                                                <!--</div>-->
                                                                            </div>
                                                                            <div class="tab-pane fade" id="contact-icon{{$subcategory->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                                <div class="row">
                                                                                    <div class="col-md-12">
                                                                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم القســم الرئـيسي باللغــة الايطاليـــة</label>
                                                                                        <input name="name_it" class="form-control" id="validationCustom01" type="text" value="{{$subcategory->getTranslation('name', 'it')}}">
                                                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                                                               value="{{ $subcategory->id }}">
                                                                                    </div>
                                                                                </div>
                                                                                <!--<div class="row">-->
                                                                                <!--    <div class="col-md-12">-->
                                                                                <!--        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيل باللغــة الايطاليــــة</label>-->
                                                                                <!--        <input name="details_it" class="form-control" id="validationCustom01" type="text" value="{{$subcategory->getTranslation('details', 'it')}}">-->
                                                                                <!--    </div>-->
                                                                                <!--</div>-->
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div>
                                                                <br>


                                <!--              <div class="row g-3">-->
                                <!--    <div class="col-md-12">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Meta description</label>-->
                                <!--     <textarea name="cat_meta" style="border:solid 1px #555; height:80px" class="form-control col-md-12">{{$subcategory->cat_meta}}</textarea>-->
                                <!--    </div>-->

                                <!--    </div>-->
                                <!--                            <div class="row g-3">-->
                                <!--    <div class="col-md-3">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصورة</label>-->
                                <!--        <input name="img_title" value="{{$subcategory->img_title}}" style="border:solid 1px #555" class="form-control"  type="text" >-->
                                <!--    </div>-->
                                <!--    <div class="col-md-3">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الأسم البديل</label>-->
                                <!--        <input name="alt_txt" value="{{$subcategory->alt_txt}}" style="border:solid 1px #555" class="form-control"  type="text">-->
                                <!--    </div>-->

                                <!--    <div class="col-md-3">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصفحة </label>-->
                                <!--        <input name="img_title" value="{{$subcategory->img_title}}" style="border:solid 1px #555" class="form-control"  type="text">-->
                                <!--    </div>-->
                                <!--    <div class="col-md-3">-->
                                <!--        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Slug</label>-->
                                <!--        <input name="slug" value="{{$subcategory->slug}}" style="border:solid 1px #555" class="form-control"  type="text">-->
                                <!--    </div>-->
                                <!--</div>-->

                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleFormControlTextarea1" style="font-family: 'Cairo', sans-serif;">اســـم القســــم الرئــيسي
                                                                        :</label>
                                                                    <select class="form-control form-control-lg"
                                                                            id="exampleFormControlSelect1" name="subcategory_id">
{{--                                                                        <option value="{{$subcategory->category->id}}">--}}
{{--                                                                            {{$subcategory->category->name}}--}}
{{--                                                                        </option>--}}
                                                                        @foreach($categories as $category)
                                                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                <br><br>
                                                             <div>
                                                                <img width="120" src="{{asset('public/' . $subcategory->image)}}">-->
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
                                        <div class="modal fade" id="exampleModal{{$subcategory->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف القسم الفرعي</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('subcategory.destroy',$subcategory->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $subcategory->id }}">
                                                        <div class="modal-body">هل أنت متاكد من حذف القسم الفرعي</div>
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
