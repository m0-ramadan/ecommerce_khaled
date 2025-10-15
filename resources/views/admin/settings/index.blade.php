@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">اعــــدادات الموقـــــــع</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الاعـــــدادات</li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">اعـــدادات الموقـــــع</h5>
                        </div>
                        <div class="card-body">
                            <form method="post" enctype="multipart/form-data" action="{{route('settingSave')}}">
                                @csrf


                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Meta Keywords </label>
                                        <div class="">
                                            <textarea name="keywords" style="border:solid 1px #555; height:80px" class="form-control col-md-12">{{$setting->keywords}}</textarea>
                                        </div>
                                    </div>
                                      
                                 
                                 <div class="col-md-12">
                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Meta Description </label>
                                    <div class="">
                                        <textarea name="metadescription" style="border:solid 1px #555; height:80px" class="form-control col-md-12">{{$setting->metadescription}}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> About In Footer Ar </label>
                                    <div class="">
                                        <textarea name="about_footer_ar" style="border:solid 1px #555; height:80px" class="form-control col-md-12">{{ $settings->getTranslation('about_footer', 'ar') }}</textarea>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> About In Footer en </label>
                                    <div class="">
                                        <textarea name="about_footer_en" style="border:solid 1px #555; height:80px" class="form-control col-md-12">{{ $settings->getTranslation('about_footer', 'en') }}</textarea>
                                    </div>
                                </div>


                             </div>    


{{--                                
                                 <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنوان رسالة عدم التسجيل</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{$settings->registered_notification_title}}" name="registered_notification_title">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">محتوي رسالة عدم التسجيل</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{$settings->registered_notification_content}}" name="registered_notification_content">
                                    </div>
                                    
                                     <div class="col-md-2">
                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اجمالى الربح</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{$setting->profit}}" name="profit">
                                     </div>
                                    
                                     <div class="col-md-2">
                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المتبقى</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{$setting->residual}}" name="residual">
                                     </div>
                                    
                                    
                                </div> --}}
                                <hr>
                                <div class="row g-3">
                                   
                                    
                                   
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">رابــــط التوتك</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="{{$settings->tiktok}}" name="tiktok">
                                        <input  type="hidden" class="form-control" id="validationCustom01" type="text" value="" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">رابــــط تويتــــر</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom02" type="text" name="twitter" value="{{$settings->twitter}}" >

                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">رابـــط الانستاجـــرام</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom02" name="instagram" type="text" value="{{$settings->instagram}}" >

                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">رابـــط اليوتيوب</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom02" name="youtube" type="text" value="{{$settings->youtube}}">

                                    </div>
                             
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">اســـم الموقـــــــع</label>
                                        <input style="border:solid 1px #555" class="form-control"  id="validationCustom03" type="text" name="app_name" value="{{$settings->app_name}}">
                                    </div>
                                </div>
                                <br>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">رقـــــم الهاتـــف</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom03" type="text" name="phone" value="{{$settings->phone}}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">العنـــــــــوان</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom03" type="text" value="{{$settings->address}}" name="address">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">المــوقـــــــع</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom03" name="location" value="{{$settings->location}}" type="text">
                                    </div>
                                </div>
                                <div class="row g-3 mt-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="cashback_value" style="font-family: 'Cairo', sans-serif;">قيمة الكاش باك لكل نقطة</label>
                                        <input style="border:solid 1px #555" class="form-control" id="cashback_value" value="{{$setting->cashback_value}}" type="text" name="cashback_value" min="0" placeholder="أدخل قيمة الكاش باك" >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="lower_value" style="font-family: 'Cairo', sans-serif;"> عدد النقاط لكل عملة واحده </label>
                                        <input style="border:solid 1px #555" class="form-control" id="lower_value" value="{{$setting->mount_pound}}"  type="text" name="mount_pound" >
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="lower_cashback" style="font-family: 'Cairo', sans-serif;"> اقل قيمة لسحب الكاش باك</label>
                                        <input style="border:solid 1px #555" class="form-control" id="lower_cashback" value="{{$setting->lower_cashback}}" type="text" name="lower_cashback"  >
                                    </div>
                                </div>
                                <br>
                                  <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">نسبة الضريبة</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom03" type="number" name="tax_rate" value="{{$settings->tax_rate}}">
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">نسبة الزكاة</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom03" type="number" value="{{$settings->zakat_percentage}}" name="zakat_percentage">
                                    </div> --}}
                                    {{-- <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">هامش الربح</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom03" name="profit_product" value="{{$settings->profit_product}}" type="number">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">نسبه دفع فيزا</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom03" name="visa_percentage" value="{{$settings->visa_percentage}}" type="number">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">نسبة شركة الشحن</label>
                                        <input style="border:solid 1px #555" class="form-control" id="validationCustom03" name="shipping_percentage" value="{{$settings->shipping_percentage}}" type="number">
                                    </div> --}}
                             

                                </div>
                                <br>
                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">اختـــر شعـــار الموقـــــع</label>
                                        <input name="image" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">

                                    </div>
                                    <div class="col-md-2">
                                        <img width="100" src="{{asset('public/' . $settings->image)}}">
                                    </div>
                                    
             

                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">اختـــر صورة العروض ف الصفحة الرئيسية</label>
                                        <input name="offer_image" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">

                                    </div>
                                    <div class="col-md-2">
                                        <img width="100" src="{{asset('public/' . $settings->offer_image)}}">
                                    </div>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-10">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">رابط الفيديو</label>
                                        <input style="border:solid 1px #555" class="form-control" name="video_link"  type="file">
                                    </div>
                                </div>
                                <br>
                                <div class="row g-3">
                                    <!--<div class="col-md-4">-->
                                    <!--    <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">اختـــر الصورة بالاعلي الصفحات الداخلية</label>-->
                                    <!--    <input name="head_image" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">-->
                                    <!--</div>-->
                                    <!--<div class="col-md-2">-->
                                    <!--    <img width="100" src="{{asset('public/' . $settings->head_image)}}">-->
                                    <!--</div>-->
                                    {{-- <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;"> اختـــر الصورة بالاسفل الصفحة الرئيسية</label>
                                        <input name="bottom_image" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">

                                    </div>
                                    <div class="col-md-2">
                                        <img width="100" src="{{asset('public/' . $settings->bottom_image)}}">
                                    </div>
                                </div>
                                <br> --}}
{{-- 
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;">اختـــر الصورة الالهام والابتكار موبيل</label>
                                        <input name="insp_mop_img" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">
                                    </div>
                                    <div class="col-md-2">
                                        <img width="100" src="{{asset('public/' . $settings->insp_mop_img)}}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label" for="validationCustom03" style="font-family: 'Cairo', sans-serif;"> اختـــر الصورة الالهام والابتكار موقع</label>
                                        <input name="insp_web_img" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">

                                    </div>
                                    <div class="col-md-2">
                                        <img width="100" src="{{asset('public/' . $settings->insp_web_img)}}">
                                    </div>
                                </div> --}}
                                <br>
                                {{-- <div class="mb-3">
                                    <div class="form-check">
                                        <div class="col-md-10">
                                            <label class="form-label" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">الاحكــــــام والشــروط</label>
                                            <textarea style="border:solid 1px #555; height: 300px" class="form-control" name="judgments">{{$settings->judgments}} </textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <div class="col-md-10">
                                            <label class="form-label" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">عن التطـــــبيق</label>
                                            <textarea style="border:solid 1px #555; height: 300px" class="form-control" name="details">{{$settings->details}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <div class="col-md-10">
                                            <label class="form-label" for="validationCustom04" style="font-family: 'Cairo', sans-serif;">سياسيــــــــة الاستبـــــدال</label>
                                            <textarea style="border:solid 1px #555; height: 300px" class="form-control" name="replacement">{{$settings->replacement}} </textarea>
                                        </div>
                                    </div>
                                </div> --}}
                                <button class="btn btn-sm btn-primary" type="submit">حفـــظ البيانــات</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>
@endsection
@section('js')
    @toastr_js
    @toastr_render
@endsection
