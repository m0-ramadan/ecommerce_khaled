@extends('Admin.layout.master')

@section('title','خدمات اويا')
@section('css')

@endsection

@section('content')

<div class="card">
  <div class="card-header pb-0">
    <h5>تعديل الخدمات</h5>
  </div>
    <div class="card-body">

  <form class="form theme-form" action="{{route('admin.logisticservice.update')}}" method="post" enctype="multipart/form-data">
    @csrf
    @method ('PUT')
  <div class="row">
        <div class="col-12">
  <input type="hidden" name="id" value="{{$logisticservice->id}}">
  <div class="row g-3  mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">الخدمة</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="title" id="exampleInputPassword2" type="text" placeholder="عنوان الخدمة" value="{{old('title',$logisticservice->name ?? '')}}">
                      @error('title')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>


                  <div class="row g-3 mb-3">
                    <div class="col-md-12 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">تفاصيل الخدمة</label>
                      <textarea style="height: 100px;" class="form-control  @error('image') is-invalid fparsley-error parsley-error @enderror" name="description" id="exampleInputPassword2" type="description" placeholder="description" >{{old('description',$logisticservice->description ?? '')}}</textarea>
                      @error('description')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
       
                  <div class="row g-3 mb-3">
                    <div class="col-md-8 m-2">
                      <label class="mr-sm-2" for="validationCustom02" style="font-family: 'Cairo', sans-serif;">صورة الخدمة</label>
                      <input class="form-control @error('image') is-invalid fparsley-error parsley-error @enderror" name="image" id="exampleInputPassword2" type="file" placeholder="image" value="{{old('image',$logisticservice->image ?? '')}}">
                      @error('image')
                      <span class="invalid-feedback text-black font-weight-bold text-capitalize mt-2" role="alert">
                        <p>{{ $message }}</p>
                      </span>
                      @enderror
                    </div>


                  </div>
              
 </div>

      <div class="card-footer text-end">
        <button class="btn btn-primary" type="submit"> حفظ المطلوب</button>
        <a class="btn btn-light" href="{{ URL::previous() }}">إلغاء </a>
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
