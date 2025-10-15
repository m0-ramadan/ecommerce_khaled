@extends('admin.app')
@section('css')
    @toastr_css
@endsection
@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-6">
                        <h3>ارسال ايميل واشعار</h3>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('admin/dashboard') }}">الرئيسيـــــــــة</a></li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <div class="container-fluid ">
            <div class="row">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <h4>اشعارات المستخدمين خلال البريد الألكترونى</h4>
                            <form method="POST" action="{{ route('emails.send') }}" enctype="multipart/form-data">
                                @csrf
                                @if (request()->has('client_email') && request()->has('client_id'))
                                    <div class="alert alert-info">Email : {{ request()->client_email }}</div>
                                    <input class="form-check-input" type="hidden" value="{{ request()->client_email }}"
                                        name="client_email">
                                    <input class="form-check-input" type="hidden" value="{{ request()->client_id }}"
                                        name="client_id">
                                @else
                                    <div class="col-12 mb-3">
                                        <select class="form-select" multiple aria-label="Multiple select example"
                                            name="clients[]">
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-check col-2 mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="emailCheckbox"
                                        name="email">
                                    <label class="form-check-label" for="emailCheckbox">
                                        With email?
                                    </label>
                                </div>

                                <div class="col mb-3" id="fileInputContainer" style="display: none;">
                                    <label for="file" class="form-label">File with emails?</label>
                                    <input class="form-control" type="file" name="file" id="file">
                                </div>


                                <div class="form-floating col mb-3">
                                    <input type="title" class="form-control" id="title" placeholder="Title"
                                        name="title">
                                    <label for="title">Title</label>
                                </div>

                                <div class="form-floating col mb-3">
                                    <textarea name="message" class="form-control" placeholder="Leave a comment here" id="floatingTextarea"></textarea>
                                    <label for="floatingTextarea">Message</label>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>

                        </div>
                    </div>
                </div>

              
            </div>
        </div>
    </div>
    <script>
        // Get the checkbox and file input elements
        var emailCheckbox = document.getElementById('emailCheckbox');
        var fileInputContainer = document.getElementById('fileInputContainer');

        // Add an event listener to the checkbox
        emailCheckbox.addEventListener('change', function() {
            // If the checkbox is checked, show the file input; otherwise, hide it
            if (emailCheckbox.checked) {
                fileInputContainer.style.display = 'block';
            } else {
                fileInputContainer.style.display = 'none';
            }
        });
    </script>
@endsection
@section('toaster')
    @toastr_js
    @toastr_render
@endsection
