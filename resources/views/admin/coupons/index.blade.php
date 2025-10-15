@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;">الكوبونات</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}" style="font-family: 'Cairo', sans-serif;">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">الكوبونات</li>
                        </ol>
                    </div>
                    <div class="col-sm-6"></div>
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
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div>
                                <a href="{{route('coupons.create')}}"><button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;">أضافة كوبون</button></a>
                            </div>
                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th style="font-family: 'Cairo', sans-serif;">الرمز</th>
                                        <th style="font-family: 'Cairo', sans-serif;">قيمة الخصم</th>
                                        <th style="font-family: 'Cairo', sans-serif;">تاريخ البداية</th>
                                        <th style="font-family: 'Cairo', sans-serif;">تاريخ الانتهاء</th>
                                        <th style="font-family: 'Cairo', sans-serif;">العمليـــــات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $i = 0; ?>
                                    @foreach($coupons as $coupon)
                                        <tr>
                                            <?php $i++; ?>
                                            <td>{{$i}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$coupon->code}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;"> {{(int) $coupon->mount }}%</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$coupon->start_date}}</td>
                                            <td style="font-family: 'Cairo', sans-serif;">{{$coupon->expiry_date}}</td>
                                            <td>
                                                <button class="btn btn-success" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#edit{{$coupon->id}}"><i class="fa fa-edit"></i></button>
                                               
                                                <a href="#">
                                                    <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#delete{{ $coupon->id }}">
                                                        <i class="fa fa-remove"></i>
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="edit{{$coupon->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document" style="max-width:950px">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel" style="font-family: 'Cairo', sans-serif;">تعديل بيانات الكوبون</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{route('coupons.update', $coupon->id)}}" method="post">
                                                            {{ method_field('patch') }}
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <label class="form-label" style="font-family: 'Cairo', sans-serif;">الرمز</label>
                                                                    <input name="code" class="form-control" type="text" value="{{$coupon->code}}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label" style="font-family: 'Cairo', sans-serif;">قيمة الخصم</label>
                                                                    <input name="mount" class="form-control" type="number" value="{{$coupon->mount}}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label" style="font-family: 'Cairo', sans-serif;">تاريخ البداية</label>
                                                                    <input name="start_date" class="form-control" type="date" value="{{$coupon->start_date}}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label" style="font-family: 'Cairo', sans-serif;">تاريخ الانتهاء</label>
                                                                    <input name="expiry_date" class="form-control" type="date" value="{{$coupon->expiry_date}}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label" style="font-family: 'Cairo', sans-serif;">عدد الاستخدامات لكل مستخدم</label>
                                                                    <input name="num_use_user" class="form-control" type="number" value="{{$coupon->num_use_user}}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label" style="font-family: 'Cairo', sans-serif;">إجمالي عدد الاستخدامات</label>
                                                                    <input name="num_times" class="form-control" type="number" value="{{$coupon->num_times}}" required>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <label class="form-label" style="font-family: 'Cairo', sans-serif;">الحالة</label>
                                                                    <select name="status" class="form-control" required>
                                                                        <option value="1" {{ $coupon->status == '1' ? 'selected' : '' }}>نشط</option>
                                                                        <option value="0" {{ $coupon->status == '0' ? 'selected' : '' }}>غير نشط</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                                                                <button class="btn btn-secondary" type="submit">حفظ</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Delete Modal -->
                                        <div class="modal fade" id="delete{{ $coupon->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel" style="font-family: 'Cairo', sans-serif;">تأكيد الحذف</h5>
                                                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        هل أنت متأكد أنك تريد حذف الكوبون؟
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">إلغاء</button>
                                                        <form action="{{ route('coupons.destroy', $coupon->id) }}" method="POST" style="display: inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-danger" type="submit">حذف</button>
                                                        </form>
                                                    </div>
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
            </div>
        </div>
    </div>
@endsection
@section('toaster')
    @jquery
    @toastr_js
    @toastr_render
@endsection
