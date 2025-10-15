@extends('admin.app')
@section('content')

    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">اضــــافة منتـــج جديــــد</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيســـــية</a></li>
                            <li class="breadcrumb-item"> <a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">المنتجــــات</a>  </li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
                        <!-- Bookmark Start-->
                        <div class="bookmark">
                        </div>
                        <!-- Bookmark Ends-->
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 style="font-family: 'Cairo', sans-serif;">اضافـــــــة منتـــج جديـــد</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" method="POST" enctype="multipart/form-data" action="{{route('product.store')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـــة العربية</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــة الانجليزيـــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــة الايطاليــــة</a></li>
                                            </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـــم المنتــــج باللغــة العربيـــة</label>
                                                            <input value="{{old('name_ar')}}" name="name_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{ old('name') }}"  >

                                                        </div>
                                                            <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">url titlte</label>
                                                            <input value="{{old('name_url')}}" name="name_url_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{ old('name') }}"  >

                                                        </div>
                                                        
                                                        
                                                    </div>
                                                    
                                                    
                                             <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">تكلفة التوصيل</label>
                                                                    <input value="{{old('shippingcharges_ar')}}" name="shippingcharges_ar" class="form-control" id="validationCustom01" type="text" >
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                             <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">مدة التوصيل</label>
                                                                    <input value="{{old('deliverytime_ar')}}" name="deliverytime_ar" class="form-control" id="validationCustom01" type="text" >
                                                                </div>
                                                                
                                                                
                                                                         <div class="col-md-3">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان المقاسات</label>
                                                                                            <input  value="{{old('productfeaturename_ar')}}" name="productfeaturename_ar" class="form-control" id="validationCustom01" type="text" >
                                                                                        </div>
                                                                                        
                                                                                         <div class="col-md-9">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المقاسات</label>
                                                                                         <textarea    name="productfeaturedescription_ar" style="border:solid 1px #555; height:40px" class="form-control col-md-12">{{old('productfeaturedescription_ar')}}</textarea>
                                                                                         </div>
                                                                                         
                                                                                         
                                                            </div>
                            
                                                    <br>
                                                    <div class="col-md-12">
                                                        <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغــة العربيـــة</label>
                                                        <div class="row">
                                                            <div class="">
                                                                <textarea name="details_ar" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px">{{old('details_ar')}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                    <div class="col-md-12">
                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــل المنتج باللغة  العربية قى الموقع</label>
                                        <div class="">
                                            <textarea name="detailsweb_ar" style="border:solid 1px #555; height:100px" class="ckeditor"></textarea>
                                        </div>
                                    </div>

                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـــم المنتــــج باللغــة الانجليــزيــة</label>
                                                            <input name="name_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{ old('name_en') }}"  >

                                                        </div>
                                                        
                                                         <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">url titlte</label>
                                                            <input value="{{old('name_url')}}" name="name_url_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{ old('name') }}"  >

                                                        </div>
                                                    </div>
                                                    
                                                    
                                                           <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">تكلفة التوصيل</label>
                                                                    <input value="{{ old('shippingcharges_en') }}" name="shippingcharges_en" class="form-control" id="validationCustom01" type="text" >
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                             <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">مدة التوصيل</label>
                                                                    <input name="deliverytime_en" value="{{old('deliverytime_en')}}" class="form-control" id="validationCustom01" type="text" >
                                                                </div>
                                                          
                                                            
                                                            
                                                                    <div class="col-md-3">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان المقاسات</label>
                                                                                            <input name="productfeaturename_en" value="{{old('productfeaturename_en')}}" class="form-control" id="validationCustom01" type="text" >
                                                                                        </div>
                                                                                        
                                                                                         <div class="col-md-9">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المقاسات</label>
                                                                                         <textarea name="productfeaturedescription_en" value="{{old('productfeaturedescription_en')}}" style="border:solid 1px #555; height:40px" class="form-control col-md-12"> </textarea>
                                                                                         </div>
                                                              </div>
                                                    <br>
                                                    <div class="col-md-12">
                                                        <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغــة الانجليــزيــة</label>
                                                        <div class="row">
                                                            <div class="">
                                                                <textarea name="details_en" style="border:solid 1px #555; height:200px" class="form-control col-md-12" style="height: 200px">{{old('details_en')}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                             <div class="col-md-12">
                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــل المنتج باللغة  الأنجليزية قى الموقع</label>
                                        <div class="">
                                            <textarea name="detailsweb_en"   class="ckeditor"></textarea>
                                        </div>
                                    </div>

                                       

                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اســـــم المنتــــج باللغــة الايطاليـــة</label>
                                                            <input name="name_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{old('name_it') }}"  >

                                                        </div>
                                                        
                                                         <div class="col-md-6">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">url titlte</label>
                                                            <input value="{{old('name_url')}}" name="name_url_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{ old('name') }}"  >

                                                        </div>
                                                    </div>
                                                    
                                                           <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">تكلفة التوصيل</label>
                                                                    <input name="shippingcharges_it" class="form-control" id="validationCustom01" value="{{old('shippingcharges_it') }}"  type="text" >
                                                                </div>
                                                            </div>
                                                            
                                                            
                                                             <div class="row">
                                                                <div class="col-md-12">
                                                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">مدة التوصيل</label>
                                                                    <input name="deliverytime_it" value="{{old('deliverytime_it') }}" class="form-control" id="validationCustom01" type="text" >
                                                                </div>
                                                            
                                                            
                                                            
                                                                    <div class="col-md-3">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان المقاسات</label>
                                                                                            <input value="{{old('productfeaturename_it')}}" name="productfeaturename_it" class="form-control" id="validationCustom01" type="text" >
                                                                                        </div>
                                                                                        
                                                                                         <div class="col-md-9">
                                                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المقاسات</label>
                                                                                         <textarea name="productfeaturedescription_it" value="{{old('productfeaturedescription_it') }}" style="border:solid 1px #555; height:40px" class="form-control col-md-12"> </textarea>
                                                                                         </div>
                                                            </div>
                                                    <br>
                                                    <div class="col-md-8">
                                                        <label class="mr-sm-2" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">التفاصيــــل باللغــة الايطاليـــة</label>
                                                        <div class="row">
                                                            <div class="">
                                                                <textarea name="details_it" style="border:solid 1px #555; height:200px" class="form-control col-md-12">{{old('details_it')}}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> التفاصيـــل المنتج باللغة  الايطاليــــة قى الموقع</label>
                                                        <div class="">
                                                            <textarea name="detailsweb_it"   class="ckeditor">{{old('detailsweb_it')}}</textarea>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3">
                                    
                                                           <div class="col-md-12">
                                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">   Meta KeyWords</label>
                                                        <div class="">
                                                            <textarea name="keywords" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{old('keywords')}}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Meta Description </label>
                                                        <div class="">
                                                            <textarea name="metadescription" style="border:solid 1px #555; height:100px" class="form-control col-md-12">{{old('metadescription')}}</textarea>
                                                        </div>
                                                    </div>

                                                                 
                                                                 
                                                            <div class="col-md-6">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الرابط  </label>
                                        <input name="aliexpress_url"  value="{{old('aliexpress_url')}}" style="border:solid 1px #555" class="form-control"  type="text" value="">
                                    </div>
                                            <div class="col-md-6">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">المرجع</label>
                                        <input name="ref_name" value="{{old('ref_name')}}" style="border:solid 1px #555" class="form-control"  type="text"  >
                                    </div>
                                    
                                    
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">عنوان الصورة</label>
                                        <input name="img_title" style="border:solid 1px #555" class="form-control"   type="text" value="{{old('img_title')}}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">الأسم البديل</label>
                                        <input name="alt_title" style="border:solid 1px #555" class="form-control" value="{{old('alt_title')}}"  type="text">
                                    </div>

                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">رقم التسلسلى</label>
                                        <input name="serail_no" style="border:solid 1px #555" class="form-control" value="{{old('serail_no')}}"  type="text">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">Slug</label>
                                        <input name="slug" style="border:solid 1px #555" class="form-control" value="{{old('slug')}}"  type="text">
                                    </div>
                                </div>
                               
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;"> سعر المنتج القديم</label>
                                        <input name="old_price" style="border:solid 1px #555" class="form-control" id="validationCustom02" value="{{old('old_price')}}" type="number" >
                                    </div>
                                    

                                    <div class="col-md-3">
                                        <label class="mr-sm-2" for="validationCustom03" style="font-family: 'Cairo', sans-serif;"> سعر المنتج الحالى </label>
                                        <input  value="{{old('current_price')}}" name="current_price" style="border:solid 1px #555" class="form-control" id="validationCustom03" type="number">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;"> سعر التحكم الذاتى   </label>
                                        <input value="{{old('smart_price')}}" name="smart_price" style="border:solid 1px #555" class="form-control"  type="number">
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;"> قيمة الضريبة المضافة</label>
                                        <input value="{{old('tax_amount')}}" name="tax_amount" style="border:solid 1px #555" class="form-control"  type="number">
                                    </div>
                                    
                                </div>
                                <br>
                                <div class="row g-3">
                                    
                                    <div class="col-md-3">
                                        <label class="mr-sm-2" for="validationCustom03" style="font-family: 'Cairo', sans-serif;"> الكــــــمية</label>
                                        <input value="{{old('quantity')}}" name="quantity" style="border:solid 1px #555" class="form-control" id="validationCustom03" type="number">
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="store_id" class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">اســـــــم المتـــجر :</label>
                                        <div class="box">
                                            <select style="border:solid 1px #555" class="form-control mainSt" name="store_id" id="store_id">
                                                <option value="" style="font-family: 'Cairo', sans-serif;">اختــــر أســــــــم المتـــجر</option>
                                                @foreach ($stores as $atore)
                                                    <option value="{{ $atore->id}}"  {{old('store_id') == $atore->name? 'selected' : '' }}>{{ $atore->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="store_id" class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">اســــــم القســــم الرئيســـــي</label>
                                        <div class="box dropdown-content">
                                            <select  style="border:solid 1px #555" class="form-control maincat" name="category_id" id="category_id" required>
                                                <option selected value="">اختر المتجر اولا"</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 mb-2">
                                        <label for="store_id" class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">اســــــم القســـم الفرعـــي</label>
                                        <div class="box dropdown-content">
                                            <select style="border:solid 1px #555" class="form-control" id="subcat" name="sub_category_id">
                                                <option selected value="">غير تابع لقسم فرعي</option>
                                            </select>
                                        </div>
                                    </div>
                               <div class="col-md-3">
                                        <label class="mr-sm-2"  style="font-family: 'Cairo', sans-serif;">تكلفة التوصيل بالأرقام</label>
                                        <input name="shippingcharges_value"    style="border:solid 1px #555" class="form-control"  type="number">
                                    </div>
                                   <div class="col-md-8 mb-5"> 
                                   <div>
                                          <label for="store_id" class="mr-sm-2">  الصورة الرئيسية </label>
                                               <div class="box dropdown-content">
                                            <input style="border:solid 1px #555" class="form-control" name="image" type="file" aria-label="file example" required >
                                        </div>
                                             
                                        </div>
                                             <br>
                                     <div>
                                        <label for="store_id" class="mr-sm-2"> معرض الصور </label>
                                                               
                                           <div class="box dropdown-content">
                                            <input style="border:solid 1px #555" class="form-control" name="images[]" type="file" aria-label="file example" required multiple >
                                        </div>
                                            </div>
                                    
                                     
                                    </div>

                                </div>
                                <button class="btn btn-primary" type="submit">حفـــظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection

@section('script')

    <script src="{{ asset('admin/assets/js/editor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('admin/assets/js/editor/ckeditor/ckeditor.custom.js') }}"></script>


    <script>
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on('click','.form-control.mainSt', function(e) {
                e.preventDefault();
                var cat = $('.form-control.mainSt').val();
                /**Ajax code**/
                $.ajax({
                    type: "POST",
                    url:"{{route('cateStore')}}",
                    data:{cat:cat},
                    success: function (data) {
                        $('select[name="category_id"]').empty();
                        $('select[name="category_id"]').append('<option selected value="">غير تابع لمتجر</option>');
                        $('select[name="category_id"]').append(data.data);
                    },
                    error:function(){
                        toastr.error('برجاء اختيار متجر');
                    }
                });
                /**Ajax code ends**/
            });
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on('click','.form-control.maincat', function(e) {
                e.preventDefault();
                var cat = $('.form-control.maincat').val();
                /**Ajax code**/
                $.ajax({
                    type: "POST",
                    url:"{{route('subcats')}}",
                    data:{cat:cat},

                    success: function (data) {
                        $('select[name="sub_category_id"]').empty();
                        $('select[name="sub_category_id"]').append('<option selected value="">غير تابع لقسم فرعي</option>');
                        $('select[name="sub_category_id"]').append(data.data);
                    },
                    error:function(){
                        console.log('برجاء اختيار قسم');
                    }
                });
                /**Ajax code ends**/
            });
        });
    </script>
@endsection

