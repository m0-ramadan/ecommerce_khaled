@extends('Admin.layout.master')

@section('title','price ')
@section('css')

@endsection

@section('content')

<div class="card">
  <div class="card-header pb-0">
    <h5>Edit price </h5>
  </div>
    <div class="card-body">

  <form class="form theme-form" action="{{route('admin.shipmentprice.update')}}" method="post" enctype="multipart/form-data">
    @csrf
    @method ('PUT')
  <div class="row">
        <div class="col-12">
  <input type="hidden" name="id" value="{{$price->id}}">


                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">price</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="price" id="exampleInputPassword2" type="number" placeholder="price" value="{{old('price',$price->price ?? '')}}">
                      @error('price')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">weight</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="weight" id="exampleInputPassword2" type="weight" placeholder="weight" value="{{old('weight',$price->weight ?? '')}}">
                      @error('weight')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">tax</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="tax" id="exampleInputPassword2" type="number" placeholder="tax" value="{{old('tax',$price->tax ?? '')}}">
                      @error('tax')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">increase</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="increase" id="exampleInputPassword2" type="increase" placeholder="increase" value="{{old('increase',$price->increase ?? '')}}">
                      @error('increase')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">currency</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="currency" id="exampleInputPassword2" type="text" placeholder="currency" value="{{old('currency' ,$price->currency ?? '')}}">
                      @error('currency')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
 </div>

      <div class="card-footer text-end">
        <button class="btn btn-primary" type="submit"> Update</button>
        <a class="btn btn-light" href="{{ URL::previous() }}">cancel </a>
      </div>
    </div>

  </form>
</div>
</div>
@endsection

@section('js')

<script src="{{asset('admin/assets/js/tooltip-init.js')}}"></script>
@endsection
