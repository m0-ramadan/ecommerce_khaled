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
                        <h3 style="font-family: 'Cairo', sans-serif;">الموظفين</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الموظفين</li>
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
                                <a href="{{url(route('employees.update' , 1))}}"><button class="btn-success btn-lg">أضــــافة</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                    <th>#</th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">الاسم</th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">الايميل</th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">الهاتف</th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">رأس المال</th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">نسبة هامش الربح</th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">اجمالى الربح </th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">المتبقى</th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">المصاريف   </th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">النوع  </th>
                                    <th style="font-family: 'Cairo', sans-serif;font-weight:400;font-size:12px;text-align:center">العمليــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($employees as $employee)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td>
                                                {{$employee->name}}
                                            </td>
                                             <td style="font-family: 'Cairo', sans-serif;"> {{$employee->email}}</td>
                                             <td style="font-family: 'Cairo', sans-serif;"> {{$employee->phone}}</td>
                                             <td style="font-family: 'Cairo', sans-serif;"> {{$employee->capital}}</td>
                                             <td style="font-family: 'Cairo', sans-serif;"> {{$employee->percentage}}</td>
                                             <td style="font-family: 'Cairo', sans-serif;"> {{$employee->profit}}</td>
                                             <td style="font-family: 'Cairo', sans-serif;"> {{$employee->residual}}</td>
                                              <td style="font-family: 'Cairo', sans-serif;"> {{ App\Models\Expenses::where('client_id', $employee->id)->sum('total_money') ?? '' }}</td>
                                             <td>
                                               @if($employee->type==0) شريك استثمارى @else  دعم فنى ومسوق بالعمولة وخدمة عملاء  @endif
                                            </td>

                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$employee->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$employee->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديل</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('employees.update',$employee->id)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div>
                                                                <div class="row g-3">
                                                                    <div>
                                                                        <img width="120px" src="{{asset('public/' . $employee->image)}}">
                                                                        <hr>
                                                                        <input class="form-control" type="file" aria-label="file example" name="image">
                                                                    </div>
                                                                    <br>
                                                                    <div class="col-md-12">
                                                                        <label class="mr-sm-2" style="font-family: 'Cairo', sans-serif;">  type of employee  :</label>
                                                                        <div class="box ">
                                                                            <select class="fancyselect form-control" style="border:solid 1px #555" name="type">
                                                                                 <option {{ $employee->type == 1 ? 'selected' : ''}}  value="1">   super admin </option>
                                                                                <option {{ $employee->type == 2 ? 'selected' : ''}}  value="2"> دعم فني  </option>
                                                                                <option  {{ $employee->type ==0 ? 'selected' : ''}} value="0"> شريك استثماري  </option>
                                                                                <option  {{ $employee->type == 3 ? 'selected' : ''}} value="3"> ادارة المبيعات و التسويق و العمولة  </option>
 
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                    <hr>

                                                            
                                                            
                                                             <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> الأسم   </label>
                                                                <input style="border:solid 1px #555" name="name" value="{{ $employee->name}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                             <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;"> البريد الألكترونى </label>
                                                                <input style="border:solid 1px #555" name="email" value="{{ $employee->email}}" class="form-control" id="validationCustom01" type="email"  >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">  كلمة المرور</label>
                                                                <input style="border:solid 1px #555" name="password"  class="form-control" id="validationCustom01" type="password"  >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">التليفون</label>
                                                                <input style="border:solid 1px #555" name="phone"  value="{{ $employee->phone}}" class="form-control" id="validationCustom01" type="text"  >
                                                            </div>


                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">رأس المال</label>
                                                                <input style="border:solid 1px #555" name="capital"  value="{{ $employee->capital}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">هامش الربح</label>
                                                                <input style="border:solid 1px #555" name="percentage"  value="{{ $employee->percentage}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>

                                                            <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">صافى الربح</label>
                                                                <input style="border:solid 1px #555" name="profit"  value="{{ $employee->profit}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                            
                                                             <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المصاريف</label>
                                                                <input style="border:solid 1px #555"  readonly  value="{{ App\Models\Expenses::where('client_id', $employee->id)->sum('total_money') ?? '' }}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>
                                                            
                                                            
                                                          <div class="col-md-4">
                                                                <label class="form-label" for="validationCustom01" style="font-family: 'Cairo', sans-serif;">المتبقى</label>
                                                                <input style="border:solid 1px #555" name="residual"  value="{{ $employee->residual}}" class="form-control" id="validationCustom01" type="text" >
                                                            </div>


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
                                        <div class="modal fade" id="exampleModal{{$employee->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف </h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('employees.destroy',$employee->id)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        <input id="id" type="hidden" name="id" class="form-control"
                                                               value="{{ $employee->id }}">
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
