@extends('admin.app')
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">الرئيسية</a></li>
                        </ol>
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
                            <h5 style="font-family: 'Cairo', sans-serif;">أضافـــة تكلفة جديدة</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" method="post" action="{{ route('partners.expenses.store') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="col-sm-12 col-xl-12 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">


                                                <div class="col-12">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="client_id" name="client_id"
                                                            aria-label="Floating label select example">
                                                            @foreach ($clients as $client)
                                                                <option value="{{ $client->id }}">{{ $client->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="client_id">الشركاء</label>
                                                    </div>
                                                </div>



                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="details"
                                                            style="font-family: 'Cairo', sans-serif;">البند</label>
                                                        <input style="border:solid 1px #555" name="details"
                                                            class="form-control" id="details" type="text"
                                                            value="{{ old('details') }}">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="total_money"
                                                            style="font-family: 'Cairo', sans-serif;">المبلغ</label>
                                                        <input style="border:solid 1px #555" name="total_money"
                                                            class="form-control" id="total_money" step="0.01" type="number"
                                                            value="{{ old('total_money') }}">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <div class="mb-3">
                                                            <label for="formFile" class="form-label">رفع ملف</label>
                                                            <input class="form-control" type="file" id="file"
                                                                name="file">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>


                                            <div class="row g-3">
                                                <div class="col-md-12" style="text-align:center;padding-top:30px">
                                                    <button class="btn btn-primary"
                                                        style="font-family: 'Cairo', sans-serif;"
                                                        type="submit">حفظ</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
    </div>

@endsection
