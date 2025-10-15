@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/offers')}}">العروض</a></li>
                            <li class="breadcrumb-item">أضافة عرض جديد</li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">أضـافــة عرض جديـد</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('offers.store')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغــــة العربيـــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-bs-toggle="tab" href="#profile-icon" role="tab" aria-controls="profile-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــــة الانجليزيـــــة</a></li>
                                                <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-bs-toggle="tab" href="#contact-icon" role="tab" aria-controls="contact-icon" aria-selected="false" style="font-family: 'Cairo', sans-serif;">اللغـــــة الايطاليـــــة</a></li>
                                            </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="row g-3">
                                                        
                                                        <div class="col-md-4">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">النص الأول</label>
                                                            <input name="title_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                                                                                         
                                                            <div class="col-md-4">                                                      
                                                                <label class="mr-sm-2" for="validationCustom01">النص الثانى</label>
                                                                <input style="border:solid 1px #555;" name="title2_ar" class="form-control" id="validationCustom01" type="text" >
                                                             </div>
                                                             
                                                             <div class="col-md-4">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الثالث</label>
                                                                <input style="border:solid 1px #555;" name="title3_ar" class="form-control" id="validationCustom01" type="text"  >
                                                             </div>
                                                            
                                                            <div class="col-md-6">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الرابع</label>
                                                                <input style="border:solid 1px #555;" name="title4_ar" class="form-control" id="validationCustom01" type="text"  >
                                                             </div>
                                                             
                                                             <div class="col-md-6">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الخامس</label>
                                                                <input style="border:solid 1px #555;" name="title5_ar" class="form-control" id="validationCustom01" type="text" >
                                                             </div>
                                                        
                                                        <div class="col-md-12">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> محتـــوي العرض باللغـــة العربية</label>
                                                            <input name="body_ar" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                        
                                                     </div>
                                                    <br>
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">تفاصيــــل العرض باللغـــة العربية</label>
                                                            <textarea name="details_ar" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12" style="height: 200px"></textarea>
                                                        </div>
                                                        
                                                          <div class="col-md-12">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اختر الصورة</label>
                                        <input name="image_ar" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">
                                    </div>
                                    
                                             <div class="col-md-12">
                                              <label class="mr-sm-2" for="validationCustom01">صورة الموبايل</label>
                                             <input class="form-control" type="file" aria-label="file example"  name="mob_image_ar">
                                            <br>
                                            <div class="invalid-feedback">Example invalid form file feedback</div>
                                            </div>
                                    
                                                    </div>
                                                </div>
                                                <div class="tab-pane fade" id="profile-icon" role="tabpanel" aria-labelledby="profile-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">النص الأول</label>
                                                            <input name="title_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                        
                                                         <div class="col-md-4">                                                      
                                                                <label class="mr-sm-2" for="validationCustom01">النص الثانى</label>
                                                                <input style="border:solid 1px #555;" name="title2_en" class="form-control" id="validationCustom01" type="text" >
                                                             </div>
                                                             
                                                             <div class="col-md-4">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الثالث</label>
                                                                <input style="border:solid 1px #555;" name="title3_en" class="form-control" id="validationCustom01" type="text"  >
                                                             </div>
                                                            
                                                            <div class="col-md-6">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الرابع</label>
                                                                <input style="border:solid 1px #555;" name="title4_en" class="form-control" id="validationCustom01" type="text"  >
                                                             </div>
                                                             
                                                             <div class="col-md-6">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الخامس</label>
                                                                <input style="border:solid 1px #555;" name="title5_en" class="form-control" id="validationCustom01" type="text" >
                                                             </div>
                                                        
                                                        <div class="col-md-12">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> محتـــوي العرض باللغـــة الانجليزيــــة</label>
                                                            <input name="body_en" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">تفاصيــــل العرض باللغـــة الانجليزيــــة</label>
                                                            <textarea name="details_en" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12" style="height: 200px"></textarea>
                                                        </div>
                                                        
                                                          <div class="col-md-12">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اختر الصورة</label>
                                        <input name="image_en" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">
                                    </div>
                                                    </div>
                                                    
                                                    
                                        <div class="col-md-12">
                                              <label class="mr-sm-2" for="validationCustom01">صورة الموبايل</label>
                                             <input class="form-control" type="file" aria-label="file example"  name="mob_image_en">
                                            <br>
                                            <div class="invalid-feedback">Example invalid form file feedback</div>
                                          </div>
                                                    
                                                </div>
                                                <div class="tab-pane fade" id="contact-icon" role="tabpanel" aria-labelledby="contact-icon-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-4">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">عنـــوان العرض باللغـــة الايطاليـــــة</label>
                                                            <input name="title_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                        
                                                       
                                                         <div class="col-md-4">                                                      
                                                                <label class="mr-sm-2" for="validationCustom01">النص الثانى</label>
                                                                <input style="border:solid 1px #555;" name="title2_it" class="form-control" id="validationCustom01" type="text" >
                                                             </div>
                                                             
                                                             <div class="col-md-4">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الثالث</label>
                                                                <input style="border:solid 1px #555;" name="title3_it" class="form-control" id="validationCustom01" type="text"  >
                                                             </div>
                                                            
                                                            <div class="col-md-6">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الرابع</label>
                                                                <input style="border:solid 1px #555;" name="title4_it" class="form-control" id="validationCustom01" type="text"  >
                                                             </div>
                                                             
                                                             <div class="col-md-6">
                                                                <label class="mr-sm-2" for="validationCustom01">النص الخامس</label>
                                                                <input style="border:solid 1px #555;" name="title5_it" class="form-control" id="validationCustom01" type="text" >
                                                             </div>
                                                        
                                                        <div class="col-md-12">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> محتـــوي العرض باللغـــة الايطاليـــــة</label>
                                                            <input name="body_it" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <div class="row g-3">
                                                        <div class="col-md-10">
                                                            <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">تفاصيــــل العرض باللغـــة الايطاليـــــة</label>
                                                            <textarea name="details_it" style="border:solid 1px #555; height: 150px;" class="form-control col-md-12" style="height: 200px"></textarea>
                                                        </div>
                                                    </div>
                                                       <div class="col-md-12">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">اختر الصورة</label>
                                        <input name="image_it" style="border:solid 1px #555" class="form-control" type="file" aria-label="file example">
                                    </div>
                                    
                                    
                                     <div class="col-md-12">
                                              <label class="mr-sm-2" for="validationCustom01">صورة الموبايل</label>
                                             <input class="form-control" type="file" aria-label="file example"  name="mob_image_it">
                                            <br>
                                            <div class="invalid-feedback">Example invalid form file feedback</div>
                                            </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="mr-sm-2" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">الخصــــم</label>
                                        <input name="discount" style="border:solid 1px #555" class="form-control" id="validationCustom01" type="text" value="" required="">
                                    </div>
                                  
                                </div>

                                <br>
                                <br>
                                <button class="btn btn-primary" type="submit" style="font-family: 'Cairo', sans-serif;">حفـــظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection

