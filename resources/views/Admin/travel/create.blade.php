@extends('Admin.layout.master')

@section('title')
    إنشاء رحلة جديدة
@endsection

@section('content')
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <x-breadcrumb :items="[
                    'لوحة التحكم' => '/admin',
                    'قائمة الرحلات' => '/admin/travel/trips',
                    'إنشاء رحلة جديدة' => '',
                ]" />
            </div>
            <div class="card-body">
                <form action="{{ route('admin.travel.trips.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- رقم الرحلة -->
                        <div class="col-md-6 mb-3">
                            <label for="trip_number" class="form-label">رقم الرحلة</label>
                            <input type="text" name="trip_number" id="trip_number" class="form-control"
                                value="{{ old('trip_number') }}" required>
                            @error('trip_number')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- الوجهة -->
                        <div class="col-md-6 mb-3">
                            <label for="destination" class="form-label">الوجهة</label>
                            <input type="text" name="destination" id="destination" class="form-control"
                                value="{{ old('destination') }}" required>
                            @error('destination')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- نوع المركبة -->
                        <div class="col-md-6 mb-3">
                            <label for="vehicle_type" class="form-label">نوع المركبة</label>
                            <select name="vehicle_type" id="vehicle_type" class="form-control" required>
                                <option value="">اختر نوع المركبة</option>
                                <option value="bus" {{ old('vehicle_type') == 'bus' ? 'selected' : '' }}>حافلة</option>
                                <option value="minibus" {{ old('vehicle_type') == 'minibus' ? 'selected' : '' }}>ميني باص
                                </option>
                                <option value="car" {{ old('vehicle_type') == 'car' ? 'selected' : '' }}>سيارة</option>
                            </select>
                            @error('vehicle_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- من المحطة -->
                        <div class="col-md-6 mb-3">
                            <label for="from_station" class="form-label">من المحطة</label>
                            <input type="text" name="from_station" id="from_station" class="form-control"
                                value="{{ old('from_station') }}" required>
                            @error('from_station')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- إلى المحطة -->
                        <div class="col-md-6 mb-3">
                            <label for="to_station" class="form-label">إلى المحطة</label>
                            <input type="text" name="to_station" id="to_station" class="form-control"
                                value="{{ old('to_station') }}" required>
                            @error('to_station')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- إضافة الركاب -->
                        <div class="col-md-12 mb-3">
                            <label for="passengers" class="form-label">الركاب</label>
                            <select name="passengers[]" id="passengers" class="form-control select2" multiple>
                                @foreach ($passengers as $passenger)
                                    <option value="{{ $passenger->id }}">{{ $passenger->full_name }}
                                        ({{ $passenger->passport_number }})</option>
                                @endforeach
                            </select>
                            @error('passengers')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- ملاحظات -->
                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label">ملاحظات</label>
                            <textarea name="notes" id="notes" class="form-control" rows="4">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- أزرار الإرسال والإلغاء -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-success">إنشاء الرحلة</button>
                        <a href="{{ route('admin.travel.trips.index') }}" class="btn btn-secondary">إلغاء</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // تهيئة Select2 لاختيار الركاب
            $('#passengers').select2({
                placeholder: 'اختر الركاب',
                allowClear: true,
                dir: 'rtl'
            });
        });
    </script>
@endsection
