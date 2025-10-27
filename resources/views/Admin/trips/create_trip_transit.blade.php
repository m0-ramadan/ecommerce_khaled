@extends('Admin.layout.master')

@section('title')
    إنشاء رحلة مندوب
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection

@section('content')
    <div class="container">
        <h1>إنشاء رحلة مندوب للوارد المرحل</h1>
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{ route('admin.trips.store.representative.transit') }}" method="POST" data-parsley-validate>
                    @csrf
                    <div class="row">
                        <!-- Representative -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="representative_id"
                                style="font-family: 'Cairo', sans-serif;">المندوب</label>
                            <select class="form-control select2 @error('representative_id') is-invalid @enderror"
                                name="representative_id" id="representative_id">
                                <option value="">-- اختر المندوب --</option>
                                @foreach ($representatives as $representative)
                                    <option value="{{ $representative->id }}"
                                        @if (old('representative_id') == $representative->id) selected @endif>
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
                            <select class="form-control select2 @error('type_coin') is-invalid @enderror" name="type_coin"
                                id="type_coin">
                                <option disabled selected>من فضلك حدد عملة الخزينة</option>
                                <option value="1" @if (old('type_coin') == '1') selected @endif>LYD</option>
                                <option value="2" @if (old('type_coin') == '2') selected @endif>EGP</option>
                                <option value="3" @if (old('type_coin') == '3') selected @endif>$ (USD)</option>
                                <option value="4" @if (old('type_coin') == '4') selected @endif>TRY</option>
                            </select>
                            @error('type_coin')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div> --}}

                        <!-- Shipments -->
                        <div class="col-md-6 mt-4">
                            <label class="mr-sm-2" for="contents" style="font-family: 'Cairo', sans-serif;">محتويات
                                الشحنه</label>
                            <select style="direction: rtl; text-align: right;"
                                class="form-control select2 @error('contents') is-invalid @enderror" name="contents[]"
                                id="shipments" multiple>
                                @foreach ($contents as $content)
                                    <option value="{{ $content->shipment_content_id }}"
                                        @if (is_array(old('contents')) && in_array($content->shipment_content_id, old('contents'))) selected @endif>
                                        (اسم المحتوى: {{ $content->shipmentContent->name }})
                                        (الكمية: {{ $content->quantity }})
                                        (كود المحتوي: {{ $content->shipmentContent->code }})
                                        ({{ $content->shipmentContent->shipment->branchFrom?->name ?? '--' }} →
                                        {{ $content->shipmentContent->shipment->branchTo?->name ?? '--' }})
                                        (سعر التوصيل الداخلي:
                                        {{ $content->shipmentContent->shipment->price_representative }})
                                    </option>
                                @endforeach

                            </select>
                            @error('contents')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Branch From -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="branch_from" style="font-family: 'Cairo', sans-serif;">من
                                فرع</label>

                            @if (auth('admin')->user()->role === \App\Models\Admin::ROLE_SUPER_ADMIN)
                                <select class="form-control select2 @error('branch_from') is-invalid @enderror"
                                    name="branch_from" id="branch_from">
                                    <option value="">اختر فرع</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_from', request('branch_from')) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }} ({{ $branch->region->region_ar ?? 'غير محدد' }})
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="branch_from" value="{{ auth('admin')->user()->branch_id }}">
                                <input type="text" class="form-control"
                                    value="{{ auth('admin')->user()->branch->name ?? 'غير محدد' }}" disabled>
                            @endif

                            @error('branch_from')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        @if (0)
                            <!-- Branch To -->
                            <div class="col-md-4 mt-3">
                                <label class="mr-sm-2" for="branch_to" style="font-family: 'Cairo', sans-serif;">إلى
                                    فرع</label>
                                <select class="form-control select2 @error('branch_to') is-invalid @enderror"
                                    name="branch_to" id="branch_to">
                                    <option value="">اختر فرع</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_to') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }} ({{ $branch->region->region_ar ?? 'غير محدد' }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('branch_to')
                                    <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        @endif

                        <!-- City -->
                        <div class="col-md-4 mt-3">
                            <label class="mr-sm-2" for="city_id" style="font-family: 'Cairo', sans-serif;">إلى
                                مدينة</label>
                            <select class="form-control select2 @error('city_id') is-invalid @enderror" name="city_id"
                                id="city_id">
                                <option value="">اختر المدينة</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}"
                                        {{ old('city_id') == $region->id ? 'selected' : '' }}>
                                        {{ $region->region_ar }}
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
                            <label class="mr-sm-2" for="notes" style="font-family: 'Cairo', sans-serif;">ملاحظات</label>
                            <input class="form-control @error('notes') is-invalid @enderror" name="notes" id="notes"
                                type="text" placeholder="ملاحظات" value="{{ old('notes') }}"
                                data-parsley-trigger="change">
                            @error('notes')
                                <span class="invalid-feedback text-black font-weight-bold text-capitalize" role="alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">إنشاء الرحلة</button>
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
            // Initialize Select2 for shipments and other dropdowns
            $('#shipments, #representative_id, #type_coin, #branch_from, #branch_to, #city_id').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || "اختر";
                },
                allowClear: true
            });

            // Initialize Parsley for form validation
            $('form').parsley();
        });
    </script>
@endsection
