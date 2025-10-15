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
                        <h3 style="font-family: 'Cairo', sans-serif;">المنتجـــــات</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيســــية</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">المنتجــــــات</li>
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
                                <a href="{{url(route('product.create'))}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">أضـــــافة منتـــج جديــــد</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center"> اســــم المنتـــج</th>
                                            <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">  الرابط  </th>
                                            <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">المرجع</th>
                                            <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">  الرابط الثانى  </th>
                                            <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">المرجع الثانى</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">السعر <br> الحالى</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">السعر <br> القديم</th>
                                         <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">ID</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">الكـــــمية</th>
                                        <th style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">القسم</th>
                                        <th  style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">معرض الصور</th>
                                        <th  style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">الصــــــورة</th>
                                        <th    style="font-family: 'Cairo', sans-serif;font-size:11px !important;padding-left: 0px !important;padding-right: 0px !important;text-align:center">العمليــــــاتــ</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($products as $product)
                                    
                                             <?php 
                                                     
                                                       $fea=App\Models\ProductFeature::where('product_id',$product->id)->first();
                                                      
                                             ?>
                                                                                       
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{mb_substr($product->name,0,20)}}</td>
                                              <td style="font-family: 'Cairo', sans-serif;"><a href="{{$product->aliexpress}}">الرابط</a></td>
                                                <td style="font-family: 'Cairo', sans-serif;">{{$product->ref_name}}</td>
                                                <td style="font-family: 'Cairo', sans-serif;"><a href="{{$product->url}}">الرابط</a></td>
                                                <td style="font-family: 'Cairo', sans-serif;">{{$product->ref_name1}}</td>
                                              <td style="font-family: 'Cairo', sans-serif;">{{str_replace(',', '', number_format($product->current_price,2)) }}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{str_replace(',', '', number_format($product->old_price,2))}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$product->id}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$product->quantity}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{App\Models\Category::where('id',$product->category_id)->first()->name ?? ''}}</td>
                                         <td style="font-family: 'Cairo', sans-serif;"><a href="{{route('productimages',$product->id )}}">معرض الصور</a></td>
                                            <td><img width="70" src="{{asset('public/'.$product->image)}}" alt="product"> </td>
                                            <td  class="fitwidth">

                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$product->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$product->id}}"><i class="fa fa-remove"></i>
                                                </button>

                                              
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade " id="edit{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog " style="max-width:1200px" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">تعديل بيانات المنتج</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('product.update',$product->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="col-md-12">
                                                                <div class="card col-md-12">
                                                                    <div class="card-body col-md-12">
                                                                        <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                                            <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home{{$product->id}}" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـة العربيـة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon{{$product->id}}" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغــة الانجليزيـة</a></li>
                                                                            <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon{{$product->id}}" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـة الايطاليــة</a></li>
                                                                        </ul>
                                                                        <br>
                                                                        <div class="tab-content" id="icon-tabContent">
                                                                            <div class="tab-pane fade show active" id="icon-home{{$product->id}}" role="tabpanel" aria-labelledby="icon-home-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المنتج باللغة العربية</label>
                                                                                            <input name="name_ar" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('name', 'ar')}}" required="">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $product->id }}">
                                                                                        </div>
                                                                                        
                                                                                                                                           <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">url titlte</label>
                                                            <input value="{{$product->getTranslation('name_url', 'ar')}}" name="name_url_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text"    >

                                                        </div>
                                                                                        
                                                                                        
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">مدة التوصيل</label>
                                                                                            <input name="delivery_time_ar" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('delivery_time', 'ar')}}" >
                                                                                         </div>
                                                                                        
                                                                                        
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">وصف تكلفة الشحن</label>
                                                                                            <input name="shippingcharges_ar" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('shippingcharges', 'ar')}}">
                                                                                         </div>
                                                                                        
                                                                                        <div class="col-md-3">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان المقاسات</label>
                                                                                            <input name="productfeaturename_ar" class="form-control" id="validationCustom01" type="text" value="@if($fea){{$fea->getTranslation('name','ar')}} @endif" >
                                                                                        </div>
                                                                                        
                                                                                         <div class="col-md-9">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المقاسات</label>
                                                                                         <textarea name="productfeaturedescription_ar" style="border:solid 1px #555; height:40px" class="form-control col-md-12">@if($fea){{$fea->getTranslation('description','ar')}} @endif</textarea>
                                                                                         </div>
                                                                                        
                                                                                        
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــل المنتج باللغة العربية فى التطبيق</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_ar" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$product->getTranslation('details', 'ar')}}</textarea>
                                                                                            </div>
                                                                                        </div>


                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــل المنتج باللغة  العربية قى الموقع</label>
                                                                                            <div class="">
                                                                                                <textarea name="detailsweb_ar" style="border:solid 1px #555; height:100px" class="ckeditor">{{$product->getTranslation('detailsweb', 'ar')}}</textarea>
                                                                                            </div>
                                                                                        </div>


                                                                                         
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="profile-icon{{$product->id}}" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المنتج باللغــة الانجليزيـــة</label>
                                                                                            <input name="name_en" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('name', 'en')}}">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $product->id }}">
                                                                                        </div>
                                                                                        
                                                   <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">url titlte</label>
                                                            <input value="{{$product->getTranslation('name_url', 'en')}}" name="name_url_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text"    >

                                                        </div>

                                                                                        
                                                                                                          <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">مدة التوصيل</label>
                                                                                            <input name="delivery_time_en" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('delivery_time', 'en')}}" >
                                                                                         </div>
                                                                                        
                                                                                        
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">وصف تكلفة الشحن</label>
                                                                                            <input name="shippingcharges_en" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('shippingcharges', 'en')}}">
                                                                                         </div>
                                                                                        <div class="col-md-3">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان المقاسات</label>
                                                                                            <input name="productfeaturename_en" class="form-control" id="validationCustom01" type="text" value="@if($fea){{$fea->getTranslation('name','en')}} @endif" >
                                                                                        </div>
                                                                                        
                                                                                         <div class="col-md-9">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المقاسات</label>
                                                                                         <textarea name="productfeaturedescription_en" style="border:solid 1px #555; height:40px" class="form-control col-md-12">@if($fea){{$fea->getTranslation('description','en')}} @endif</textarea>
                                                                                         </div>
                                                                                     
                                                                                        
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل باللغـــة الأنجليزية فى التطبيق</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_en" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$product->getTranslation('details', 'en')}}</textarea>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــل المنتج باللغة  الأنجليزية قى الموقع</label>
                                                                                            <div class="">
                                                                                                <textarea name="detailsweb_en"   class="ckeditor">{{$product->getTranslation('detailsweb', 'en')}}</textarea>
                                                                                            </div>
                                                                                        </div>

                                                                                          
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="tab-pane fade" id="contact-icon{{$product->id}}" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                                                <div>
                                                                                    <div class="row">
                                                                                        <div class="col-md-6">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـم المنتج باللغــة الايطاليـــة</label>
                                                                                            <input name="name_it" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('name', 'it')}}">
                                                                                            <input id="id" type="hidden" name="id" class="form-control" value="{{ $product->id }}">
                                                                                        </div>
                                                                                        
                                                                                                                                           <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">url titlte</label>
                                                            <input value="{{$product->getTranslation('name_url', 'it')}}" name="name_url_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text"    >

                                                        </div>
                                                                                                          <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">مدة التوصيل</label>
                                                                                            <input name="delivery_time_it" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('delivery_time', 'it')}}" >
                                                                                         </div>
                                                                                        
                                                                                        
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">وصف تكلفة الشحن</label>
                                                                                            <input name="shippingcharges_it" class="form-control" id="validationCustom01" type="text" value="{{$product->getTranslation('shippingcharges', 'it')}}">
                                                                                         </div>
                                                                                         
                                                                                          <div class="col-md-3">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان المقاسات</label>
                                                                                            <input name="productfeaturename_it" class="form-control" id="validationCustom01" type="text" value="@if($fea){{$fea->getTranslation('name','it')}} @endif" >
                                                                                        </div>
                                                                                        
                                                                                         <div class="col-md-9">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المقاسات</label>
                                                                                         <textarea name="productfeaturedescription_it" style="border:solid 1px #555; height:40px" class="form-control col-md-12">@if($fea){{$fea->getTranslation('description','it')}} @endif</textarea>
                                                                                         </div>
                                                                                        
                                                                                        
                                                                                    </div>
                                                                                    <br>
                                                                                    <div class="row">
                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التفاصيـــل باللغـــة الايطاليــــة</label>
                                                                                            <div class="">
                                                                                                <textarea name="details_it" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$product->getTranslation('details', 'it')}}</textarea>
                                                                                            </div>
                                                                                        </div>

                                                                                        <div class="col-md-12">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــل المنتج باللغة  الايطاليــــة قى الموقع</label>
                                                                                            <div class="">
                                                                                                <textarea name="detailsweb_it"   class="ckeditor">{{$product->getTranslation('detailsweb', 'it')}}</textarea>
                                                                                            </div>
                                                                                        </div>

                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                             <div class="row g-3">
                                                                 
                                                   <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Meta Keywords </label>
                                                        <div class="">
                                                            <textarea name="keywords" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$product->keywords}}</textarea>
                                                        </div>
                                                    </div>
                                                      
                                                 
                                                 <div class="col-md-12">
                                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Meta Description </label>
                                                    <div class="">
                                                        <textarea name="metadescription" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{$product->metadescription}}</textarea>
                                                    </div>
                                                </div>
                                             </div>    


                                                                 
                                    <div class="col-md-6">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الرابط الاول</label>
                                        <input name="aliexpress_url" value="{{$product->aliexpress}}" style="border:solid 1px #555" class="form-control"  type="text"  >
                                    </div>
                                    
                                    
                                     <div class="col-md-6">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">المرجع</label>
                                        <input name="ref_name" value="{{$product->ref_name}}" style="border:solid 1px #555" class="form-control"  type="text"  >
                                    </div>
                                    
                                    
                                    <div class="col-md-6">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الرابط الثانى</label>
                                        <input name="url" value="{{$product->url}}" style="border:solid 1px #555" class="form-control"  type="text"  >
                                    </div>
                                    
                                    
                                     <div class="col-md-6">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">المرجع</label>
                                        <input name="ref_name1" value="{{$product->ref_name1}}" style="border:solid 1px #555" class="form-control"  type="text"  >
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصورة</label>
                                        <input name="img_title" value="{{$product->title_img}}" style="border:solid 1px #555" class="form-control"  type="text"  >
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الأسم البديل</label>
                                        <input name="alt_title"  value="{{$product->alt_text}}" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">رقم التسلسلى</label>
                                        <input name="serail_no" value="{{$product->serial_no}}" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Slug</label>
                                        <input name="slug"  value="{{$product->slug}}" style="border:solid 1px #555" class="form-control"  type="text">
                                    </div>
                                </div>


                                                            <div class="row g-3">

                                                                <div class="col-md-3">
                                                                    <label class="form-label" for="validationCustom02">السعر القديم </label>
                                                                    <input name="old_price" readonly class="form-control" id="validationCustom02" type="text" value="{{$product->old_price}}">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label class="form-label" for="validationCustom03"> السعر الحالى </label>
                                                                    <input name="current_price" readonly class="form-control" id="validationCustom03" type="text"  value="{{$product->current_price}}">
                                                                </div>
                                                                
                                                                
                                                                <div class="col-md-3">
                                                                    <label class="form-label" for="validationCustom02">السعر القديم الأساسى </label>
                                                                    <input name="mainoldprice" class="form-control" id="validationCustom02" type="text" value="{{$product->mainoldprice}}">
                                                                </div>
                                                                
                                                                <div class="col-md-3">
                                                                    <label class="form-label" for="validationCustom03"> السعر الحالى الأساسى </label>
                                                                    <input name="mainprice" class="form-control" id="validationCustom03" type="text"  value="{{$product->mainprice}}">
                                                                </div>
                                                                
                                                                

                                    <div class="col-md-4">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;"> سعر التحكم الذاتى   </label>
                                        <input name="smart_price"  value="{{$product->smart_price}}" style="border:solid 1px #555" class="form-control"  type="number">
                                    </div>

                                    <div class="col-md-4">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;"> قيمة الضريبة المضافة</label>
                                        <input name="tax_amount" value="{{$product->tax_amount}}" style="border:solid 1px #555" class="form-control"  type="number">
                                    </div>


                                    <div class="col-md-4">
                                        <label for="store_id" class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">اســـــــم المتـــجر :</label>
                                        <div class="box">
                                            <select style="border:solid 1px #555" class="form-control mainSt" name="store_id" id="store_id">
                                                <option value="" style="font-family: 'Cairo', sans-serif;">اختــــر أســــــــم المتـــجر</option>
                                                @foreach ($stores as $atore)
                                                    <option value="{{ $atore->id}}"  @if($atore->id == $product->store_id) selected @endif>{{ $atore->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                                            </div>
                                                            <br>
                                                            <div class="row g-3">
                                                                
                                                                 <div class="col-md-3">
                                            <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">تكلفة  التوصيل بالأرقام</label>
                                        <input name="shippingcharges_value"  value="{{$product->shippingcharges_value}}" style="border:solid 1px #555" class="form-control"  type="number">
                                    </div>
                                    
                                    
                                                                <div class="col-md-3">
                                                                    <label class="form-label" for="validationCustom03">الكمية</label>
                                                                    <input name="quantity" value="{{$product->quantity}}" class="form-control" id="validationCustom03" type="text">
                                                                </div>
                                                                <div class="col-md-3">
                                                                    <label for="store_id" class="mr-sm-2">اسم القسم الرئيسي</label>
                                                                    <div class="box dropdown-content">
                                                                        <select  class="form-control maincat" name="category_id" id="category_id">
                                                                            <option value=""></option>
                                                                                @foreach($categories as $category)
                                                                                    <option value="{{ $category->id}}" @if($category->id == $product->category_id) selected @endif>{{ $category->name}}</option>
                                                                                @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                                     <div class="col-md-3 mb-5">
                                                                    <label for="store_id" class="mr-sm-2">اسم القسم الفرعي</label>
                                                                    <div class="box dropdown-content">
                                                                        <select  class="form-control" id="subcat" name="sub_category_id">
                                                                            <option value=""></option>
                                                                        @foreach($subcategories as $subcategory)
                                                                                <option value="{{ $subcategory->id}}" @if($subcategory->id == $product->sub_category_id) selected @endif>{{ $subcategory->name}}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                
                                                                
                                                          
                                                            </div>
                                                            <div>
                                                                <label style="display: block;">  الصورة الرئيسية </label>
                                                                <img width="120px" src="{{asset('public/'.$product->image)}}">
                                                                <input class="form-control"  type="file" aria-label="file example" name="image">
                                                            </div>
                                                            <div>
                                                                <label style="display: block;"> معرض الصور </label>
                                                                 <?php $images = App\Models\Image::where('product_id',$product->id)->get(); ?>

                                                                  @foreach($images as $image)

                                                                  <img width="120px" src="{{asset('public/'.$image->src)}}">
                                                                  @endforeach
                                                                <input class="form-control" multiple type="file" aria-label="file example" name="images[]">
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
                                        
                                        <!-- delete_modal_Grade -->
                                        <div class="modal fade" id="exampleModal{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog"  role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف المنتج</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('product.destroy',$product->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $product->id }}">
                                                        <div class="modal-body">هل أنت متاكد من حذف هذا المنتج</div>
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
 
    <script src="{{ asset('admin/assets/js/editor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/assets/js/editor/ckeditor/ckeditor.custom.js') }}"></script>

  
@endsection
@section('toaster')
    @toastr_js
    @toastr_render
@endsection