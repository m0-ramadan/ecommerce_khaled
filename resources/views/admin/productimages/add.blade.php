@extends('admin.app')
@section('content')

    <!-- Page Sidebar Ends-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">صورة المنتج</h3>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">اضــــافة    صورة جديدة  </h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" method="POST" enctype="multipart/form-data" action="{{route('productimagestore')}}">
                                @csrf
                                
                                <input type="hidden"  name="product_id"  value={{$id}}>
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <ul class="nav nav-tabs" id="icon-tab" role="tablist">
                                                <li class="nav-item"><a class="nav-link active" id="icon-home-tab" data-bs-toggle="tab" href="#icon-home" role="tab" aria-controls="icon-home" aria-selected="true" style="font-family: 'Cairo', sans-serif;">اللغـــة العربية</a></li>
                                              </ul>
                                            <br>
                                            <div class="tab-content" id="icon-tabContent">
                                                <div class="tab-pane fade show active" id="icon-home" role="tabpanel" aria-labelledby="icon-home-tab">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Alt TXT </label>
                                                            <input name="alttxt" class="form-control" id="validationCustom01" type="text" value="{{old('alttxt')}}" required="">
                                                          </div>


                                                        <div class="col-md-6">
                                                            <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> Title TXT </label>
                                                            <input name="titletxt" class="form-control" id="validationCustom01" type="text" value="{{old('titletxt')}}" required="">
                                                            
                                                         </div>
 
                                                         <div>
                                                            <label style="display: block;">  الصورة الرئيسية </label>
                                                            <input class="form-control"  type="file" aria-label="file example" name="image">
                                                        </div>

                                                    </div>
                                                    
                                                </div>
                                                 
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



