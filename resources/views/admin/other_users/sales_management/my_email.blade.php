
@extends('admin.other_users.apps.sales_management.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/sales-management')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item"> البريد الالكتروني    </li>
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
    
    @if(session()->has('error'))
         <div class='alert alert-danger' >{{ session()->get('error')}} </div> 
    @elseif(session()->has('success'))
        <div class='alert alert-success' >{{ session()->get('success')}} </div>
    @endif
    <!-- Container-fluid starts-->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 style="font-family: 'Cairo', sans-serif;">  البريد الالكتروني</h5>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data" method="post" action="{{route('storeEmailAddressMessage.sales_management')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                            
                                                            <div>
                                                                <div class="row g-3">
                                                                     
                                                                    
                                                             <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">   الاسم  </label>
                                                                <input style="border:solid 1px #555" name="name" value="" required class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                             <div class="col-md-8">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">  العنوان      </label>
                                                                <input style="border:solid 1px #555" name="address" value=""  required  class="form-control" id="validationCustom02" type="text"  >
                                                            </div>
                                                            
                                                             
                                                             
                                                            
                                                             
 
                                                            <div class="col-md-12">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">      التفاصيل</label>
                                                                <textarea style="border:solid 1px #555" name="message"   required  class="form-control" id="validationCustom03" type="text" > </textarea>
                                                            </div>

                                                                     
                                                                    
                                                                </div>
                                                                <br>
                                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button class="btn btn-primary" type="submit">ارسال</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection
