@extends('Admin.layout.master')
@section('title', 'إضافة باقة')
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header"><h4 class="mb-0">إضافة باقة مميزة</h4></div>
            <div class="card-body">
                @include('Admin.packages._form')
            </div>
        </div>
    </div>
@endsection
