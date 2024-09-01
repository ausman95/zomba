@extends('layouts.app')
@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ul"></i>News
        </h4>
        <p>
            Update News
        </p>
        <nav>
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('news.index')}}">News</a></li>
                <li class="breadcrumb-item"><a href="{{route('news.show',$news->id)}}">{{$news->title}}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('news.update',$news->id)}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden"  name="updated_by" value="{{request()->user()->id}}" required>
                        <div class="form-group">
                            <label>Title</label>
                            <textarea name="title" rows="3"
                                      placeholder="News Title here"
                                      class="form-control @error('note') is-invalid
                                       @enderror" required>{{old('title') ??  $news->title}}</textarea>
                            @error('title')
                            <span class="invalid-feedback">
                               {{$message}}
                            </span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-4  mb-2">
                                <div class="form-group">
                                    <label>Image Post</label>
                                    <input type="file" name="image" class="form-control" onchange="previewImage(event)" required>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-8  mb-2">
                                <label>Current Image Post</label>
                                <img id="" src="../../img/blog/{{$news->url}}" alt="" style="max-width: 100%; max-height: 200px;">
                            </div>
                            <div class="col-sm-12 col-md-8  mb-2">
                                <label>New Image Post</label>
                                <img id="preview" src="" alt="" style="max-width: 100%; max-height: 200px;">
                            </div>
                        </div>
                        <div class="form-group ">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-edit"></i>Update
                            </button>
                        </div>
                    </form>
                </div>
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
