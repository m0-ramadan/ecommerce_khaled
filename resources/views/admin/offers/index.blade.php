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
                        <h3 style="font-family: 'Cairo', sans-serif;">العـــروض</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">كـــل العــروض</li>
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
                                <a href="{{url(route('offers.create'))}}"><button class="btn-success btn-lg">أضــــافة عرض جـديـد</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العنــوان</th>
                                        <th style="font-family: 'Cairo', sans-serif;">المحتــوي</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الخصــم</th>
                                        <th style="font-family: 'Cairo', sans-serif;">التفاصيــل</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الصـــورة</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($offers as $offer)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$offer->getTranslation('title','ar')}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$offer->getTranslation('content','ar')}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$offer->discount}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$offer->details}}</td>
                                            <td><img width="50" src="{{asset('public/' . $offer->image)}}"></td>
                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                            data-original-title="test" data-bs-target="#edit{{$offer->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                            data-original-title="test" data-bs-target="#exampleModal{{$offer->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$offer->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديل العـــــــرض</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('offers.update',$offer->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div>
                                                                <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                    <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$offer->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيــة</a></li>
                                                                    <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$offer->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيـــة</a></li>
                                                                    <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$offer->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الايطاليــة</a></li>
                                                                </ul>
                                                                <br>
                                                                <div class="tab-content" id="icon-tabContent">
                                                                    <div class="tab-pane fade show active" id="icon-home{{$offer->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                        <div class="row g-3">
                                                                            <div class="col-md-4">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الأول</label>
                                                                                <input style="border:solid 1px #555;" name="title_ar" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title','ar') }}">
                                                                             </div>
                                                                            
                                                                            <div class="col-md-4">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الثانى</label>
                                                                                <input style="border:solid 1px #555;" name="title2_ar" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title2','ar') }}">
                                                                             </div>
                                                                             
                                                                             <div class="col-md-4">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الثالث</label>
                                                                                <input style="border:solid 1px #555;" name="title3_ar" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title3','ar') }}">
                                                                             </div>
                                                                            
                                                                            <div class="col-md-6">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الرابع</label>
                                                                                <input style="border:solid 1px #555;" name="title4_ar" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title4','ar') }}">
                                                                             </div>
                                                                             
                                                                             <div class="col-md-6">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الخامس</label>
                                                                                <input style="border:solid 1px #555;" name="title5_ar" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title5','ar') }}">
                                                                             </div>
                                                                            
                                                                            
                                                                            <div class="col-md-12">
                                                                                <label class="mr-sm-2" for="validationCustom01">محتوي العرض باللغة العربية</label>
                                                                                <input style="border:solid 1px #555;" name="body_ar" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('content','ar') }}">
                                                                            </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-12">
                                                                                <label class="mr-sm-2" for="validationCustom01">تفاصيل العرض باللغة العربية</label>
                                                                                <textarea  style="border:solid 1px #555; height: 150px" class="form-control" name="details_ar">{{$offer->getTranslation('details','ar')}}</textarea>
                                                                            </div>
                                                                            
                                                                              <div class="col-md-12">
                                                                    <img width="120" src="{{asset('public/' . $offer->getTranslation('image','ar'))}}">
                                                                    <input class="form-control" type="file" aria-label="file example"  name="image_ar">
                                                                    <br>
                                                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                                                </div>
                                                                
                                                                <div class="col-md-12">
                                                                      <label class="mr-sm-2" for="validationCustom01">صورة الموبايل</label>
                                                                    <img width="120" src="{{asset('public/' . $offer->getTranslation('mob_image','ar'))}}">
                                                                    <input class="form-control" type="file" aria-label="file example"  name="mob_image_ar">
                                                                    <br>
                                                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                                                </div>
                                                                
                                                                        </div>
                                                                        <br>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="profile-icon{{$offer->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                        <div class="row g-3">
                                                                            <div class="col-md-4">
                                                                                    <label class="mr-sm-2" for="validationCustom01">النص الأول</label>
                                                                                    <input style="border:solid 1px #555;" name="title_en" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title','en') }}">
                                                                                    <input id="id" type="hidden" name="id" class="form-control"
                                                                                           value="{{ $offer->id }}">
                                                                                </div>
                                                                                
                                                                                
                                                                                <div class="col-md-4">                                     
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الثانى</label>
                                                                                <input style="border:solid 1px #555;" name="title2_en" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title2','en') }}">
                                                                             </div>
                                                                             
                                                                             <div class="col-md-4">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الثالث</label>
                                                                                <input style="border:solid 1px #555;" name="title3_en" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title3','en') }}">
                                                                             </div>
                                                                            
                                                                            <div class="col-md-6">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الرابع</label>
                                                                                <input style="border:solid 1px #555;" name="title4_en" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title4','en') }}">
                                                                             </div>
                                                                             
                                                                             <div class="col-md-6">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الخامس</label>
                                                                                <input style="border:solid 1px #555;" name="title5_en" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title5','en') }}">
                                                                             </div>
                                                                            
                                                                                
                                                                                
                                                                                
                                                                            <div class="col-md-12">
                                                                                    <label class="mr-sm-2" for="validationCustom01">محتوي العرض باللغة الانجليزية</label>
                                                                                    <input style="border:solid 1px #555;" name="body_en" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('content','en') }}">
                                                                                </div>
                                                                        </div>
                                                                        <br>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-12">
                                                                                <label class="mr-sm-2" for="validationCustom01">تفاصيل العرض باللغة الانجليزية</label>
                                                                                <textarea  style="border:solid 1px #555; height: 150px" class="form-control" name="details_en">{{$offer->getTranslation('details','en')}}</textarea>
                                                                            </div>
                                                                            
                                                                 <div class="col-md-12">
                                                                       <label class="mr-sm-2" for="validationCustom01">صورة على اللاب</label>
                                                                    <img width="120" src="{{asset('public/' . $offer->getTranslation('image','en'))}}">
                                                                    <input class="form-control" type="file" aria-label="file example"  name="image_en">
                                                                    <br>
                                                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                                                </div>
                                                                
                                                                
                                                                <div class="col-md-12">
                                                                      <label class="mr-sm-2" for="validationCustom01">صورة الموبايل</label>
                                                                    <img width="120" src="{{asset('public/' . $offer->getTranslation('mob_image','en'))}}">
                                                                    <input class="form-control" type="file" aria-label="file example"  name="mob_image_en">
                                                                    <br>
                                                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                                                </div>
                                                                
                                                                
                                                                        </div>
                                                                    </div>
                                                                    <div class="tab-pane fade" id="contact-icon{{$offer->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                        <div class="row g-3">
                                                                            <div class="col-md-4">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الأول</label>
                                                                                <input style="border:solid 1px #555;" name="title_it" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title','it') }}">
                                                                                <input id="id" type="hidden" name="id" class="form-control"
                                                                                       value="{{ $offer->id }}">
                                                                            </div>
                                                                            
                                                                                                                                                            
                                                                            <div class="col-md-4">                                                      
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الثانى</label>
                                                                                <input style="border:solid 1px #555;" name="title2_it" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title2','it') }}">
                                                                             </div>
                                                                             
                                                                             <div class="col-md-4">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الثالث</label>
                                                                                <input style="border:solid 1px #555;" name="title3_it" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title3','it') }}">
                                                                             </div>
                                                                            
                                                                            <div class="col-md-6">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الرابع</label>
                                                                                <input style="border:solid 1px #555;" name="title4_it" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title4','it') }}">
                                                                             </div>
                                                                             
                                                                             <div class="col-md-6">
                                                                                <label class="mr-sm-2" for="validationCustom01">النص الخامس</label>
                                                                                <input style="border:solid 1px #555;" name="title5_it" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('title5','it') }}">
                                                                             </div>
                                                                            
                                                                            
                                                                            
                                                                            <div class="col-md-12">
                                                                                <label class="mr-sm-2" for="validationCustom01">محتوي العرض باللغة الايطالية</label>
                                                                                <input style="border:solid 1px #555;" name="body_it" class="form-control" id="validationCustom01" type="text" value="{{ $offer->getTranslation('content','it') }}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="row g-3">
                                                                            <div class="col-md-12">
                                                                                <label class="mr-sm-2" for="validationCustom01">تفاصيل العرض باللغة الايطالية</label>
                                                                                <textarea  style="border:solid 1px #555; height: 150px" class="form-control" name="details_it">{{$offer->getTranslation('details','it')}}</textarea>
                                                                            </div>
                                                                            
                                                                            
                                                                           <div class="col-md-12">
                                                                    <img width="120" src="{{asset('public/' . $offer->getTranslation('image','it'))}}">
                                                                    <input class="form-control" type="file" aria-label="file example"  name="image_it">
                                                                    <br>
                                                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                                                </div>
                                                                
                                                                
                                                                <div class="col-md-12">
                                                                      <label class="mr-sm-2" for="validationCustom01">صورة الموبايل</label>
                                                                    <img width="120" src="{{asset('public/' . $offer->getTranslation('mob_image','it'))}}">
                                                                    <input class="form-control" type="file" aria-label="file example"  name="mob_image_it">
                                                                    <br>
                                                                    <div class="invalid-feedback">Example invalid form file feedback</div>
                                                                </div>
                                                                
                                                                
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <br>
                                                                <div class="row g-3">
                                                                    <div class="col-md-6">
                                                                        <label class="mr-sm-2" for="validationCustom01">الخصم</label>
                                                                        <input style="border:solid 1px #555;" name="discount" class="form-control" id="validationCustom01" type="text" value="{{ $offer->discount }}" required="">
                                                                    </div>
                                                                </div>
                                                                <br>

                                                                
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
                                        <div class="modal fade" id="exampleModal{{$offer->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف العرض</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('offers.destroy',$offer->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $offer->id }}">
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
