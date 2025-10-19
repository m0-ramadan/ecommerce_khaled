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
                        <h3 style="font-family: 'Cairo', sans-serif;">المستخـــــــدميــن</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيـــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">كــل المستخدميــــن</li>
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
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="font-family: 'Cairo', sans-serif;">اســــــم المستخــــــدم</th>
                                            <th style="font-family: 'Cairo', sans-serif;">رقــــــم الهاتــــف</th>
                                            <th style="font-family: 'Cairo', sans-serif;"> تاريخ الانضمام </th>
                                            <th style="font-family: 'Cairo', sans-serif;">البريــد الالكتروني</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العنوان</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العمليـــات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 0; ?>
                                        @foreach ($clients as $client)
                                            <tr>
                                                <?php $i++; ?>
                                                <td>{{ $i }}</td>
                                                <td>{{ $client->name }}</td>
                                                <td>{{ $client->phone }}</td>
                                                <td>{{ date('Y-m-d', strtotime($client->created_at)) }}</td>
                                                <td>{{ $client->email }}</td>
                                                <td>{{ $client->address }}</td>
                                                {{-- <td><img width="50" src="{{asset('public/' . $store->image)}}"></td> --}}
                                                <td>
                                                    <!-- Edit Button -->
                                                    <button class="btn btn-sm btn-primary" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editRoleModal{{ $client->id }}"
                                                        title="تعديل بيانات العميل">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" type="button"
                                                        data-bs-toggle="modal" data-original-title="test"
                                                        data-bs-target="#exampleModal{{ $client->id }}"><i
                                                            class="fa fa-remove"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-success" type="button"
                                                        data-bs-toggle="modal" data-original-title="test"
                                                        data-bs-target="#notificationModal{{ $client->id }}"><i
                                                            class="fa fa-comment "></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <!-- Edit Client Modal -->
                                            <div class="modal fade" id="editRoleModal{{ $client->id }}" tabindex="-1"
                                                aria-labelledby="editRoleModalLabel{{ $client->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editRoleModalLabel{{ $client->id }}">تعديل بيانات
                                                                العميل</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="editClientForm{{ $client->id }}" method="POST"
                                                                action="{{ route('client.update', $client->id) }}">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="mb-3">
                                                                    <label for="name" class="form-label">اسم
                                                                        العميل</label>
                                                                    <input type="text" class="form-control"
                                                                        name="name" value="{{ $client->name }}"
                                                                        required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="email" class="form-label">البريد
                                                                        الإلكتروني</label>
                                                                    <input type="email" class="form-control"
                                                                        name="email" value="{{ $client->email }}"
                                                                        required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="password" class="form-label">كلمة
                                                                        المرور</label>
                                                                    <input type="password" class="form-control"
                                                                        name="password"
                                                                        placeholder="اترك فارغًا إذا لم ترغب في تغيير كلمة المرور">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="password_confirmation"
                                                                        class="form-label">تأكيد كلمة المرور</label>
                                                                    <input type="password" class="form-control"
                                                                        name="password_confirmation"
                                                                        placeholder="تأكيد كلمة المرور">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="phone" class="form-label">رقم
                                                                        الهاتف</label>
                                                                    <input type="text" class="form-control"
                                                                        name="phone" value="{{ $client->phone }}">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="state_id" class="form-label">المحافظة
                                                                    </label>
                                                                    <select name="state_id" class="form-select">
                                                                        <option value="">اختر المحافظة</option>
                                                                        @foreach ($cities as $city)
                                                                            <option value="{{ $city->id }}"
                                                                                {{ $city->id == $client->state_id ? 'selected' : '' }}>
                                                                                {{ $city->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="mb-3">
                                                                    <label for="address"
                                                                        class="form-label">العنوان</label>
                                                                    <input type="text" class="form-control"
                                                                        name="address" value="{{ $client->address }}">
                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-primary">تحديث</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- SendNotification_modal_Grade -->
                                            <div class="modal fade" id="notificationModal{{ $client->id }}"
                                                tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">ارسال اشعار
                                                            </h5>
                                                            <button class="btn-close" type="button"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @if (session('status'))
                                                                <div class="alert alert-success" role="alert">
                                                                    {{ session('status') }}
                                                                </div>
                                                            @endif
                                                            <form action="{{ route('sendNotification') }}"
                                                                method="POST">
                                                                @csrf
                                                                <input id="id" type="hidden" name="client_id"
                                                                    class="form-control" value="{{ $client->id }}">
                                                                <div class="form-group">
                                                                    <label>عنوان الاشعار</label>
                                                                    <input type="text" class="form-control"
                                                                        name="title">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>محتوي الاشعار</label>
                                                                    <textarea class="form-control" name="message"></textarea>
                                                                </div>
                                                                <button type="submit"
                                                                    class="btn btn-success btn-block">ارسال</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- delete_modal_Grade -->
                                            <div class="modal fade" id="exampleModal{{ $client->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">حذف المستخدم
                                                            </h5>
                                                            <button class="btn-close" type="button"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('client.destroy', $client->id) }}"
                                                            method="post">
                                                            {{ method_field('Delete') }}
                                                            @csrf
                                                            <input id="id" type="hidden" name="id"
                                                                class="form-control" value="{{ $client->id }}">
                                                            <div class="modal-body">هل أنت متاكد من حذف هذا المستخدم</div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button"
                                                                    data-bs-dismiss="modal">غلق</button>
                                                                <button class="btn btn-secondary"
                                                                    type="submit">حذف</button>
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
    @jquery
    @toastr_js
    @toastr_render
@endsection
