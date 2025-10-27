@extends('Admin.layout.master')

@section('title','logisticservice ')
@section('css')

@endsection

@section('content')

<div class="card">
  <div class="card-header pb-0">
    <h5>Create logistic service </h5>
  </div>
    <div class="card-body">

  <form class="form theme-form" action="{{route('admin.logisticservice.store')}}" method="post" enctype="multipart/form-data">
    @csrf
  <div class="row">
        <div class="col-12">

      <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">title</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="title" id="exampleInputPassword2" type="text" placeholder="title" value="{{old('title')}}">
                      @error('title')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>





                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">description</label>
                      <textarea class="form-control  @error('image') is-invalid fparsley-error parsley-error @enderror" name="description" id="exampleInputPassword2" type="description" placeholder="description" >{{old('description')}}</textarea>
                      @error('description')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">details</label>
                      <textarea class="form-control  ckeditor @error('image') is-invalid fparsley-error parsley-error @enderror" name="details" id="exampleInputPassword2" type="text" placeholder="details" >{{old('details' )}}"</textarea>
                      @error('details')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">image</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="image" id="exampleInputPassword2" type="file" placeholder="image" value="{{old('image')}}">
                      @error('image')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">detail image</label>
                      <input class="form-control @error('detail_image') is-invalid fparsley-error parsley-error @enderror" name="detail_image" id="exampleInputPassword2" type="file" placeholder="detail image" >
                      @error('detail_image')
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
        <a class="btn btn-light" href="{{ URL::previous() }}">cancel </a>
      </div>
    </div>

  </form>
</div>
</div>
@endsection

@section('js')

@section('js')
<script src="{{asset('admin/assets/js/tooltip-init.js')}}"></script>
<script src="{{ asset('assets/js/editor/ckeditor/ckeditor.js')}}"></script>
    <script src="{{ asset('assets/js/editor/ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{ asset('assets/js/editor/ckeditor/styles.js')}}"></script>
    <script src="{{ asset('assets/js/editor/ckeditor/ckeditor.custom.js')}}"></script>
@endsection
@endsection
