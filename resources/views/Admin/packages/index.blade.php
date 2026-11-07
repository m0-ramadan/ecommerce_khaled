@extends('Admin.layout.master')
@section('title', 'الباقات المميزة')
@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">الباقات المميزة</h4>
                <a href="{{ route('admin.packages.create') }}" class="btn btn-primary">+ إضافة باقة</a>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الصورة</th>
                            <th>الاسم</th>
                            <th>عدد المنتجات</th>
                            <th>الترتيب</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($packages as $package)
                            <tr>
                                <td>{{ $package->id }}</td>
                                <td>
                                    @if ($package->image)
                                        <img src="{{ asset('storage/' . $package->image) }}" style="height:50px" class="rounded">
                                    @endif
                                </td>
                                <td>{{ $package->name }}</td>
                                <td>{{ $package->products_count }}</td>
                                <td>{{ $package->order }}</td>
                                <td>
                                    <span class="badge {{ $package->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $package->is_active ? 'نشطة' : 'متوقفة' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.packages.edit', $package) }}" class="btn btn-sm btn-warning">تعديل</a>
                                    <form action="{{ route('admin.packages.destroy', $package) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('حذف الباقة؟')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">حذف</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">لا توجد باقات بعد</td></tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $packages->links() }}
            </div>
        </div>
    </div>
@endsection
