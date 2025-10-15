
@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسية</a></li>
                            <li class="breadcrumb-item">موظفى التطبيق</li>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">أضافـــة</h5>
                        </div>
                        <div class="card-body">
                            <form enctype="multipart/form-data" method="post" action="{{route('employees.store')}}">
                                @csrf
                                <div class="col-sm-12 col-xl-6 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <br>
                                                            <div>
                                                                <div class="row g-3">
                                                                    <div>
                                                                         <input class="form-control" type="file" aria-label="file example" name="image">
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-md-12">
                                                                        <label class="mr-sm-2" style="font-family: 'Cairo', sans-serif;"> نوع المستخدم</label>
                                                                        <div class="box ">
                                                                            <select class="fancyselect form-control" style="border:solid 1px #555" name="type">
                                                                                 <option value="0"> شريك استثماري  </option>
                                                                                <option value="1"> ادارة المبيعات و التسويق و العمولة  </option>
                                                                             </select>
                                                                        </div>
                                                                    </div>
                                                                    <hr>
                                                                    
                                                                    
                                                             <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">   الأسم  </label>
                                                                <input style="border:solid 1px #555" name="name"   class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                             <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">  البريد الألكترونى      </label>
                                                                <input style="border:solid 1px #555" name="email"   class="form-control" id="validationCustom01" type="email"  >
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">      كلمة المرور</label>
                                                                <input style="border:solid 1px #555" name="password"   class="form-control" id="validationCustom01" type="password"  >
                                                            </div>
                                                            
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">   رقم الهاتف</label>
                                                                <input style="border:solid 1px #555" name="phone"    class="form-control" id="validationCustom01" type="text"  >
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">رأس المال</label>
                                                                <input style="border:solid 1px #555" name="capital"  value="{{old('capital')}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">هامش الربح</label>
                                                                <input style="border:solid 1px #555" name="percentage"  value="{{old('percentage')}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">صافى الربح</label>
                                                                <input style="border:solid 1px #555" name="profit"  value="{{old('profit')}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                          <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المتبقى</label>
                                                                <input style="border:solid 1px #555" name="residual"  value="{{old('residual')}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                          

                                                                     
                                                                    
                                                                </div>
                                                                <br>
                                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <button class="btn btn-primary" type="submit">حفظ</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection
