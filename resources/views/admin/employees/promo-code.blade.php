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
                        <h3 style="font-family: 'Cairo', sans-serif;">اكواد الخصم</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">اكواد الخصم</li>
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
                               <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                        data-original-title="test" data-bs-target="#createPromoCodeModel">اضافة
                                </button>
                                
                                <div class="modal fade" id="createPromoCodeModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديل</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('employees.promo-code.store')}}" method="post" >
                                                            @csrf
                                                            <div>
                                                                <div class="row g-3">
                  
                                                                    <div class="mb-3">
                                                                      <label for="code" class="form-label">كود الخصم</label>
                                                                      <input type="text" class="form-control" id="code" name="code" value="{{ old('code') }}" placeholder="code ..">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                      <label for="value" class="form-label">كمية الخصم</label>
                                                                      <input type="number" class="form-control" id="value" name="value" value="{{ old('value') }}" placeholder="value ..">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                      <label for="counts" class="form-label">عدد مرات الاستخدام لكل مستخدم</label>
                                                                      <input type="number" class="form-control" id="counts" name="counts" value="{{ old('counts') }}" placeholder="counts ..">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                      <label for="start_date" class="form-label">تاريخ البداية</label>
                                                                      <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                      <label for="end" class="form-label">تاريخ النهاية</label>
                                                                      <input type="date" class="form-control" id="end" name="end" value="{{ old('end') }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <select class="form-select" aria-label="Default select example" name="client_id">
                                                                          <option selected>-- اختر موظف --</option>
                                                                            @foreach($clients as $client)
                                                                                @if(!$codes->contains('client_id', $client->id))
                                                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                                                @endif
                                                                            @endforeach
                                                                        </select>
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
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الكود</th>
                                        <th style="font-family: 'Cairo', sans-serif;">كمية الخصم</th>
                                        <th style="font-family: 'Cairo', sans-serif;">بداية</th>
                                        <th style="font-family: 'Cairo', sans-serif;">نهاية</th>
                                        <th style="font-family: 'Cairo', sans-serif;">عدد مرات الاستخدام لكل مستخدم</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($codes as $code)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td>{{$code->code}}</td>
                                             <td>{{$code->value}}</td>
                                            <td>{{$code->start_date}}</td>
                                            <td>{{$code->end}}</td>
                                            <td>{{$code->counts}}</td>

                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#edit{{$code->id}}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button class="btn btn-danger" type="button" data-bs-toggle="modal"
                                                        data-original-title="test" data-bs-target="#exampleModal{{$code->id}}"><i class="fa fa-remove"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- edit_modal_Grade -->
                                        <div class="modal fade" id="edit{{$code->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديل</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('employees.promo-code.update',$code)}}" method="post" enctype="multipart/form-data">
                                                            {{ method_field('PUT') }}
                                                            @csrf
                                                            <div>
                                                                <div class="row g-3">
                  
                                                                    <div class="mb-3">
                                                                      <label for="code" class="form-label">كود الخصم</label>
                                                                      <input type="text" class="form-control" id="code" name="code" value="{{ $code->code }}" placeholder="code ..">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                      <label for="value" class="form-label">كمية الخصم</label>
                                                                      <input type="number" class="form-control" id="value" name="value" value="{{ $code->value }}" placeholder="value ..">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                      <label for="counts" class="form-label">عدد مرات الاستخدام لكل مستخدم</label>
                                                                      <input type="number" class="form-control" id="counts" name="counts" value="{{ $code->counts }}" placeholder="counts ..">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                      <label for="start_date" class="form-label">تاريخ البداية</label>
                                                                      <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $code->start_date }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                      <label for="end" class="form-label">تاريخ النهاية</label>
                                                                      <input type="date" class="form-control" id="end" name="end" value="{{ $code->end }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <select class="form-select" aria-label="Default select example" name="client_id">   
                                                                             @foreach($clients as $client)
                                                                                <option value="{{ $client->id }}" @if($client->id == $code->client_id) selected @endif>
                                                                                    {{ $client->name }}
                                                                                </option>
                                                                            @endforeach 
                                                                        </select>
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
                                        <div class="modal fade" id="exampleModal{{$code->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">حذف </h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{route('employees.promo-code.delete',$code)}}" method="post">
                                                        {{ method_field('Delete') }}
                                                        @csrf
                                                        
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
