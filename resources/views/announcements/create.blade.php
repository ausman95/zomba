@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-list-ol"></i>Announcements
        </h4>
        <p>
            Manage Announcements
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('announcements.index')}}">Announcements</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Announcements</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-4">
                    <form action="{{route('announcements.store')}}" enctype="multipart/form-data"  method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group">
                            <label> From </label>
                            <select name="ministry_id" required
                                    class="form-select select-relation @error('ministry_id') is-invalid @enderror" style="width: 100%">
                                <option value="">-- Select ---</option>
                                @foreach($ministries as $ministry)
                                        <option value="{{$ministry->id}}"
                                            {{old('ministry_id')===$ministry->id ? 'selected' : ''}}>
                                            {{$ministry->name}}</option>
                                @endforeach
                            </select>
                            @error('ministry_id')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date"
                                   class="form-control @error('start_date') is-invalid @enderror"
                                   value="{{old('start_date')}}" placeholder="from"
                                   required>
                            @error('start_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date"
                                   class="form-control @error('end_date') is-invalid @enderror"
                                   value="{{old('end_date')}}" placeholder="from"
                                   required>
                            @error('end_date')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{old('title')}}" placeholder="Title"
                                   required>
                            @error('title')
                            <span class="invalid-feedback">
                               {{$message}}
                        </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Full Announcement</label>
                            <textarea name="body" rows="3" placeholder="Provide a short description"
                                      class="form-control @error('body') is-invalid @enderror">{{old('body')}}</textarea>
                            @error('body')
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
                                <img id="preview" src="#" alt="" style="max-width: 100%; max-height: 200px;">
                            </div>
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <div class="form-group">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-paper-plane"></i>Save
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
@endsection
