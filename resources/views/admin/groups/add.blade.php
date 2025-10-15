@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3 style="font-family: 'Cairo', sans-serif;"> طـــرق الدفـــع</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{url('admin/dashboard')}}">الرئيسيــــــة</a></li>
                            <li class="breadcrumb-item"><a href="{{url('admin/groups')}}">الشركاء</a></li>
                            <li class="breadcrumb-item" style="font-family: 'Cairo', sans-serif;">أضافــة شريك جديد </li>
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
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header pb-0">
                            <h5 style="font-family: 'Cairo', sans-serif;">اضافة صورة الشريك</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate="" enctype="multipart/form-data" method="post" action="{{route('groups.store')}}">
                                @csrf
                                <br>

                                <div class="col-md-10">
                                    <label> اضافــة صـــورة</label>
                                    <input style="border:solid 1px #555" name="image" class="form-control" type="file" aria-label="file example" required="">
                                </div>
                                <br>
                                <div class="col-md-10">
                                    <label> اضافــة رابـــــط</label>
                                    <input class="form-control" style="border:solid 1px #555" name="link" placeholder="أضافــة رابــــط">
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
