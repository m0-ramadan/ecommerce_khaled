@extends('Admin.layout.master')
@section('title', 'تعديل باقة')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h4 class="mb-0">تعديل الباقة: {{ $package->name }}</h4></div>
            <div class="card-body">
                @include('Admin.packages._form', ['package' => $package])
            </div>
        </div>
    </div>
@endsection
