@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-money-bill-alt"></i>News
        </h4>
        <p>
            Manage News
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{route('human-resources.index')}}">Human Resources</a></li>
                <li class="breadcrumb-item"><a href="{{route('informations.index')}}">Information</a></li>
                <li class="breadcrumb-item"><a href="{{route('informations.create')}}">step 1</a></li>
                <li class="breadcrumb-item active" aria-current="page">Step 2</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
            <p>
                News.
            </p>
            <div class="mt-4 row">
                <div class="card-footer" style="text-align: center">
                    <form action="{{route('informations.save')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <div class="row">
                            <div class="col-sm-12 col-md-4  mb-2">
                                <div class="form-group">
                                    <label>Image Post</label>
                                    <input type="file" name="image" class="form-control" onchange="previewImage(event)" required>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8  mb-2">
                                <img id="preview" src="#" alt="" style="max-width: 100%; max-height: 200px;">
                            </div>
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-pencil-alt"></i>  Publish
                            </button>

                        </div>
                    </form>
                </div>
            </div>
        </div>

        @stop
        @section('scripts')
            <script>
                function previewImage(event) {
                    var reader = new FileReader();
                    reader.onload = function () {
                        var output = document.getElementById('preview');
                        output.src = reader.result;
                        // Store the image name in a hidden input field
                        document.getElementById('image_name').value = event.target.files[0].name;
                    }
                    reader.readAsDataURL(event.target.files[0]);
                }
            </script>
            <script>
                $(document).ready(function () {
                    $('.type').on('change', function () {
                        let status = $(this).val();
                        if(status==='1'){
                            $('.material').addClass('show').removeClass('d-none');
                            $('.others').addClass('d-none').removeClass('show');
                            $('.fuel').addClass('d-none').removeClass('show');
                        }
                        if(status==='2'){
                            $('.material').addClass('d-none').removeClass('show');
                            $('.others').addClass('show').removeClass('d-none');
                            $('.fuel').addClass('d-none').removeClass('show');
                        }
                        if(status==='3'){
                            $('.material').addClass('d-none').removeClass('show');
                            $('.others').addClass('d-none').removeClass('show');
                            $('.fuel').addClass('show').removeClass('d-none');
                        }
                    });
                });
            </script>
@endsection
