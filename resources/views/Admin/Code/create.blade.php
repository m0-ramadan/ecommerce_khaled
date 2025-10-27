@extends('admin.layout.master')

@section('title','code ')
@section('css')

@endsection

@section('content')

<div class="card">
  <div class="card-header pb-0">
    <h5>Create code </h5>
  </div>
    <div class="card-body">

  <form class="form theme-form" action="{{route('admin.code.store')}}" method="post" enctype="multipart/form-data">
    @csrf
  <div class="row">
        <div class="col-12">

      <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">code</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="code" id="exampleInputPassword2" type="text" placeholder="code" value="{{old('code')}}">
                      @error('code')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="col-md-8 m-2">
  <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">ShipmentCompany</label>
                                        <div class="input-group mb-3 ">

                                                    <select class="form-select" aria-label="Default select example"  name="company">
                                                        <option selected> ShipmentCompany</option>
                                                       @foreach($companies as $company)
                                                        <option value="{{$company->id}}" @if (old('company') ==  $company->id)   selected @endif > {{$company->name}}</option>
 @endforeach
                                                    </select>
                                                </div>





                                    </div>

                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">discount</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="discount" id="exampleInputPassword2" type="number" placeholder="discount" value="{{old('discount')}}">
                      @error('discount')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>



                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">type</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="type" id="exampleInputPassword2" type="type" placeholder="type" value="{{old('type')}}">
                      @error('type')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">from</label>
                    <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="from" id="exampleInputPassword2" type="date" placeholder="from" value="{{old('from' )}}">
                      @error('from')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>

                  <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">to</label>
                    <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="to" id="exampleInputPassword2" type="date" placeholder="to" value="{{old('to' )}}">
                      @error('to')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">time</label>
                    <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="time" id="exampleInputPassword2" type="text" placeholder="time" value="{{old('time' )}}">
                      @error('time')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>


                  </div>
 </div>

      <div class="card-footer text-end">
        <button class="btn btn-primary" type="submit">Add</button>
        <input class="btn btn-light" type="reset" value="Cancel">
      </div>
    </div>

  </form>
</div>
</div>
@endsection

@section('js')

<script src="{{asset('admin/assets/js/tooltip-init.js')}}"></script>
@endsection
