@extends('Admin.layout.master')

@section('title')
    تعديل الرحلة
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>تعديل الرحلة</h1>
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.trips.update', $trip->id) }}" method="POST" data-parsley-validate>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Representative -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="representative_id"
                                style="font-family: 'Cairo', sans-serif;">المندوب</label>
                            <select class="form-control @error('representative_id') is-invalid @enderror"
                                name="representative_id" id="representative_id">
                                <option value="">-- اختر المندوب --</option>
                                @foreach ($representatives as $representative)
                                    <option value="{{ $representative->id }}"
                                        {{ old('representative_id', $trip->representative_id) == $representative->id ? 'selected' : '' }}>
                                        {{ $representative->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('representative_id')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        {{-- <!-- Type Coin -->
                    <div class="col-md-4 mt-3">
                        <label class="mr-sm-2" for="type_coin" style="font-family: 'Cairo', sans-serif;">نوع
                            العملة</label>
                        <select class="form-select" name="type_coin" id="currency" aria-label="اختر العملة">
                            <option disabled {{ !old('type_coin', $trip->type_coin) ? 'selected' : '' }}>من فضلك حدد
                                عملة الخزينة</option>
                            <option value="1" {{ old('type_coin', $trip->type_coin) == 1 ? 'selected' : '' }}>LYD
                            </option>
                            <option value="2" {{ old('type_coin', $trip->type_coin) == 2 ? 'selected' : '' }}>EGP
                            </option>
                            <option value="3" {{ old('type_coin', $trip->type_coin) == 3 ? 'selected' : '' }}>$ (USD)
                            </option>
                            <option value="4" {{ old('type_coin', $trip->type_coin) == 4 ? 'selected' : '' }}>TRY
                            </option>
                        </select>
                        @error('type_coin')
                        <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                            {{ $message }}
                        </span>
                        @enderror
                    </div> --}}

                        <!-- Shipments -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="shipments" style="font-family: 'Cairo', sans-serif;">الشحنات</label>
                            <select style="direction: rtl; text-align: right;"
                                class="form-control select2 @error('shipments') is-invalid @enderror" name="shipments[]"
                                id="shipments" multiple>
                                @foreach ($shipments as $shipment)
                                    <option value="{{ $shipment->id }}"
                                        {{ in_array($shipment->id, old('shipments', $trip->shipments->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $shipment->code }}
                                        ({{ $shipment->branchFrom?->name . '->' . $shipment->branchTo?->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('shipments')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- From Branch -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="branch_from" style="font-family: 'Cairo', sans-serif;">من
                                فرع</label>
                            <select class="form-control select2 @error('branch_from') is-invalid @enderror"
                                name="branch_from" id="branch_from">
                                <option value="">اختر فرع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_from', $trip->branches_from) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} ({{ $branch->region->region ?? 'غير محدد' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_from')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- To Branch -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="branch_to" style="font-family: 'Cairo', sans-serif;">إلى فرع</label>
                            <select class="form-control select2 @error('branch_to') is-invalid @enderror" name="branch_to"
                                id="branch_to">
                                <option value="">اختر فرع</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}"
                                        {{ old('branch_to', $trip->branches_to) == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }} ({{ $branch->region->region ?? 'غير محدد' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('branch_to')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- City -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="city_id" style="font-family: 'Cairo', sans-serif;">المدينة</label>
                            <select class="form-control select2 @error('city_id') is-invalid @enderror" name="city_id"
                                id="city_id">
                                <option value="">اختر المدينة</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}"
                                        {{ old('city_id', $trip->city_id) == $region->id ? 'selected' : '' }}>
                                        {{ $region->region_ar ?? 'غير محدد' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('city_id')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="name" style="font-family: 'Cairo', sans-serif;">ملاحظات</label>
                            <input class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                                type="text" placeholder="ملاحظات" value="{{ old('name', $trip->name) }}"
                                data-parsley-trigger="change">
                            @error('name')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 m-2">
                        <button type="submit" class="btn btn-primary">تحديث الرحلة</button>
                        <a href="{{ route('admin.trips.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for shipments
            $('#shipments').select2({
                placeholder: "اختر الشحنات",
                allowClear: true
            });

            // Initialize Parsley for form validation
            $('form').parsley();
        });
    </script>
@endsection
