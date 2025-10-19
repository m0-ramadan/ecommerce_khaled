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
                        <h3 style="font-family: 'Cairo', sans-serif;">الموظفين</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}"
                                    style="font-family: 'Cairo', sans-serif;">الرئيسيـــــــة</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">كــل الموظفين</li>
                        </ol>
                    </div>
                    <div class="col-sm-6">
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
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

                            <div>
                                @can('edit users')
                                    <button class="btn-success btn-lg" style="font-family: 'Cairo', sans-serif;"
                                        data-bs-toggle="modal" data-bs-target="#addUserModal" title="إضافة مستخدم">
                                        أضـــــافة
                                    </button>
                                @endcan
                            </div>

                            <br>
                            <div class="table-responsive">
                                <table class="display datatables" id="dt-plugin-method">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="font-family: 'Cairo', sans-serif;">اسم الموظف</th>
                                            <th style="font-family: 'Cairo', sans-serif;">رقم الهاتف</th>
                                            <th style="font-family: 'Cairo', sans-serif;">البريد الإلكتروني</th>
                                            <th style="font-family: 'Cairo', sans-serif;">العنوان</th>
                                            <th style="font-family: 'Cairo', sans-serif;"> تاريخ الانضمام </th>
                                            <th style="font-family: 'Cairo', sans-serif;"> الدور </th>
                                            <th style="font-family: 'Cairo', sans-serif;">العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($users as $index => $user)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->phone }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->address }}</td>
                                                <td>{{ date('Y-m-d', strtotime($user->created_at)) }}</td>
                                                <td>{{ $user->roles->first()->name }}</td>
                                                <td>
                                                    <button class="btn btn-sm btn-danger" type="button"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deleteUserModal{{ $user->id }}">
                                                        <i class="fa fa-remove"></i>
                                                    </button>
                                                    <button class="btn btn-success" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#editRoleModal{{ $user->id }}"
                                                        title="تعديل دور المستخدم">
                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                </td>
                                            </tr>

                                            <!-- Edit User Role Modal -->
                                            <div class="modal fade" id="editRoleModal{{ $user->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="editRoleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editRoleModalLabel">تعديل دور الموظف
                                                            </h5>
                                                            <button class="btn-close" type="button" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('users.update', $user->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="name">الاسم</label>
                                                                    <input type="text" name="name" id="name"
                                                                        class="form-control"
                                                                        value="{{ old('name', $user->name) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="email">البريد الإلكتروني</label>
                                                                    <input type="email" name="email" id="email"
                                                                        class="form-control"
                                                                        value="{{ old('email', $user->email) }}" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="password">كلمة المرور</label>
                                                                    <input type="password" name="password" id="password"
                                                                        class="form-control"
                                                                        placeholder="اتركه فارغًا إذا لم ترغب في تغيير كلمة المرور">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="password_confirmation">تأكيد كلمة
                                                                        المرور</label>
                                                                    <input type="password" name="password_confirmation"
                                                                        id="password_confirmation" class="form-control"
                                                                        placeholder="تأكيد كلمة المرور">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="role">اختيار الدور</label>
                                                                    <select name="role" id="role"
                                                                        class="form-control" required>
                                                                        <option value="">اختر دوراً</option>
                                                                        <option value="admin"
                                                                            {{ $user->type == 'admin' ? 'selected' : '' }}>
                                                                            مدير</option>
                                                                        <option value="super-admin"
                                                                            {{ $user->type == 'super-admin' ? 'selected' : '' }}>
                                                                            مدير أعلى</option>
                                                                        <!-- Add more roles as necessary -->
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button"
                                                                    data-bs-dismiss="modal">غلق</button>
                                                                <button class="btn btn-success" type="submit">تعديل
                                                                    الدور</button>
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete User Modal -->
                                            <div class="modal fade" id="deleteUserModal{{ $user->id }}"
                                                tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteUserModalLabel">حذف الموظف
                                                            </h5>
                                                            <button class="btn-close" type="button"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('users.destroy', $user->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <div class="modal-body">هل أنت متأكد من حذف هذا الموظف؟</div>
                                                            <div class="modal-footer">
                                                                <button class="btn btn-primary" type="button"
                                                                    data-bs-dismiss="modal">غلق</button>
                                                                <button class="btn btn-danger" type="submit">حذف</button>
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
            </div>
        </div>
    </div>
    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">إضافة موظف جديد</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">اسم الموظف</label>
                            <input type="text" name="name" id="name" class="form-control" required
                                placeholder="أدخل اسم المستخدم">
                        </div>
                        <div class="form-group">
                            <label for="email">البريد الالكتروني</label>
                            <input type="email" name="email" id="email" class="form-control" required
                                placeholder="أدخل البريد الالكتروني">
                        </div>
                        <div class="form-group">
                            <label for="password">كلمة المرور</label>
                            <input type="password" name="password" id="password" class="form-control" required
                                placeholder="أدخل كلمة المرور">
                        </div>
                        <div class="form-group">
                            <label for="role">الدور</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="">اختر دوراً</option>
                                <option value="admin">مدير</option>
                                <option value="super-admin">مدير أعلى</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">غلق</button>
                        <button class="btn btn-success" type="submit">إضافة</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


@endsection
@section('toaster')
    @jquery
    @toastr_js
    @toastr_render
@endsection
