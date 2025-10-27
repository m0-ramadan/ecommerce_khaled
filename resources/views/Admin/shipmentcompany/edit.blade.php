@extends('Admin.layout.master')

@section('title','shipmentcompany')
@section('css')

@endsection

@section('content')

<div class="card">
  <div class="card-header pb-0">
    <h5>Edit shipmentcompany </h5>
  </div>
    <div class="card-body">

  <form class="form theme-form" action="{{route('admin.shipmentcompany.update')}}" method="post" enctype="multipart/form-data">
    @csrf
    @method ('PUT')
  <div class="row">
        <div class="col-12">
  <input type="hidden" name="id" value="{{$shipmentcompany->id}}">
  <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">name</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="name" id="exampleInputPassword2" type="text" placeholder="name" value="{{old('name',$shipmentcompany->name ?? '')}}">
                      @error('name')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>



                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">image</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="image" id="exampleInputPassword2" type="file" placeholder="image" value="{{old('image',$shipmentcompany->image ?? '')}}">
                      @error('image')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">active</label>
                      <input {{($shipmentcompany->status == 1 ? 'checked' : '')}} name="status" type="checkbox" class="form-check-input" id="exampleCheck1" >
                      @error('status')
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
