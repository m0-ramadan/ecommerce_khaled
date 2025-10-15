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
                            <h5 style="font-family: 'Cairo', sans-serif;">أضافـــة طلب جديد</h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" method="post" action="{{ route('partners.requests.store') }}">
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
                                                        <select class="form-select" id="product_id" name="product_id"
                                                            aria-label="Floating label select example">
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}">{{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <label for="product_id">المنتجات</label>
                                                    </div>
                                                </div>


                                                {{-- <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="details"
                                                            style="font-family: 'Cairo', sans-serif;">التفاصيل</label>
                                                        <input style="border:solid 1px #555" name="details"
                                                            class="form-control" id="details" type="text"
                                                            value="{{ old('details') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="tax"
                                                            style="font-family: 'Cairo', sans-serif;">الضرائب</label>
                                                        <input style="border:solid 1px #555" name="tax"
                                                            class="form-control" id="tax" type="number"
                                                            value="{{ old('tax') }}">
                                                    </div>
                                                </div>

                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="zakat"
                                                            style="font-family: 'Cairo', sans-serif;">الزكاة</label>
                                                        <input style="border:solid 1px #555" name="zakat"
                                                            class="form-control" id="zakat" type="number"
                                                            value="{{ old('zakat') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="profit_product"
                                                            style="font-family: 'Cairo', sans-serif;">الربح</label>
                                                        <input style="border:solid 1px #555" name="profit_product"
                                                            class="form-control" id="profit_product" type="number"
                                                            value="{{ old('profit_product') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="visa_percentage"
                                                            style="font-family: 'Cairo', sans-serif;">نسبة الفيزا</label>
                                                        <input style="border:solid 1px #555" name="visa_percentage"
                                                            class="form-control" id="visa_percentage" type="number"
                                                            value="{{ old('visa_percentage') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="discount"
                                                            style="font-family: 'Cairo', sans-serif;">الخصم</label>
                                                        <input style="border:solid 1px #555" name="discount"
                                                            class="form-control" id="discount" type="number"
                                                            value="{{ old('discount') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="shipping"
                                                            style="font-family: 'Cairo', sans-serif;">الشحن</label>
                                                        <input style="border:solid 1px #555" name="shipping"
                                                            class="form-control" id="shipping" type="number"
                                                            value="{{ old('shipping') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">

                                                    <div class="form-control">
                                                        <label class="form-label" for="developement"
                                                            style="font-family: 'Cairo', sans-serif;">التطوير</label>
                                                        <input style="border:solid 1px #555" name="developement"
                                                            class="form-control" id="developement" type="number"
                                                            value="{{ old('developement') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="advertising"
                                                            style="font-family: 'Cairo', sans-serif;">الدعاية</label>
                                                        <input style="border:solid 1px #555" name="advertising"
                                                            class="form-control" id="advertising" type="number"
                                                            value="{{ old('advertising') }}">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-control">
                                                        <label class="form-label" for="Insurance"
                                                            style="font-family: 'Cairo', sans-serif;">التأمين</label>
                                                        <input style="border:solid 1px #555" name="Insurance"
                                                            class="form-control" id="Insurance" type="number"
                                                            value="{{ old('Insurance') }}">
                                                    </div>
                                                </div> --}}
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
