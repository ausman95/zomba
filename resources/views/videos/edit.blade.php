@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-video"></i>&nbsp; Videos
        </h4>
        <p>
            Update video details
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Videos</a></li>
                <li class="breadcrumb-item"><a href="{{ route('videos.index') }}">Videos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Update Video</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <form action="{{ route('videos.update', $video->id) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        @method('PUT') <!-- This indicates that the form will be updating the resource -->

                        <!-- Video Title -->
                        <div class="form-group">
                            <label for="title">Video Title</label>
                            <input type="text" name="title" id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   placeholder="Enter video title" value="{{ old('title', $video->title) }}">
                            @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Video URL -->
                        <div class="form-group mt-3">
                            <label for="url">YouTube Video URL</label>
                            <input type="text"
                                   class="form-control @error('url') is-invalid @enderror"
                                   placeholder="Enter YouTube video URL" value="{{ old('url', $video->url) }}" readonly>
                            @error('url')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Submit Button -->
                        <div class="form-group mt-3">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-paper-plane"></i> Update Video
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
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@stop
