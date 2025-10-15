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
                            <form class="needs-validation" method="post" action="{{ route('partners.obligation.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="col-sm-12 col-xl-12 xl-100">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">


                                                <div class="col-6">
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
                                                    <div class="form-floating">
                                                        <select class="form-select" id="type" name="type"
                                                            aria-label="Floating label select example">
                                                            <option value="1">تكلفة رأس المال</option>
                                                            <option value="2">تكلفة تشغيلية</option>
                                                            <option value="3">تكلفة تأسيس</option>
                                                        </select>
                                                        <label for="type">نوع التكلفة</label>
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="subject"
                                                            style="font-family: 'Cairo', sans-serif;">الموضوع</label>
                                                        <input style="border:solid 1px #555" name="subject"
                                                            class="form-control" id="subject" type="text"
                                                            value="{{ old('subject') }}">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-floating">
                                                        <select class="form-select" id="payment_method" name="payment_method"
                                                            aria-label="Floating label select example">
                                                                <option value="شهرية">شهرية</option>
                                                                <option value="سنوية">سنوية</option>
                                                                <option value="ربع سنوية">ربع سنوية</option>
                                                                <option value="نصف سنوية">نصف سنوية</option>
                                                        </select>
                                                        <label for="payment_method">نوع الدفعة</label>
                                                    </div>
                                                </div>

                                                {{-- <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="monthly_dues"
                                                            style="font-family: 'Cairo', sans-serif;">دفعات شهرية</label>
                                                        <input style="border:solid 1px #555" name="monthly_dues"
                                                            class="form-control" id="monthly_dues" type="number"
                                                            value="{{ old('monthly_dues') }}">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="annual_dues"
                                                            style="font-family: 'Cairo', sans-serif;">دفعات سنوية</label>
                                                        <input style="border:solid 1px #555" name="annual_dues"
                                                            class="form-control" id="annual_dues" type="number"
                                                            value="{{ old('annual_dues') }}">
                                                    </div>
                                                </div> --}}

                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="total_money"
                                                            style="font-family: 'Cairo', sans-serif;">اجمالي التكلفة</label>
                                                        <input style="border:solid 1px #555" name="total_money"
                                                            class="form-control" id="total_money" type="number"
                                                            value="{{ old('total_money') }}">
                                                    </div>
                                                </div>

                                                {{-- <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="payment_method"
                                                            style="font-family: 'Cairo', sans-serif;">نوع العملية</label>
                                                        <input style="border:solid 1px #555" name="payment_method"
                                                            class="form-control" id="payment_method" type="text"
                                                            value="{{ old('payment_method') }}">
                                                    </div>
                                                </div> --}}

                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="date"
                                                            style="font-family: 'Cairo', sans-serif;">التاريخ</label>
                                                        <input style="border:solid 1px #555" name="date"
                                                            class="form-control" id="date" type="date"
                                                            value="{{ old('date') }}">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <div class="mb-3">
                                                            <label for="formFile" class="form-label">رفع ملف</label>
                                                            <input class="form-control" type="file" id="file" name="file">
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
