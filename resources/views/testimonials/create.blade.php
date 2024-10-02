@extends('layouts.app')

@section('content')
    <div class="container-fluid ps-1 pt-4">
        <h4>
            <i class="fa fa-comments"></i>&nbsp; Testimonials
        </h4>
        <p>
            Create a new testimonial
        </p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{route('members.index')}}">Members</a></li>
                <li class="breadcrumb-item"><a href="{{route('testimonials.index')}}">Testimonials</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create Testimonial</li>
            </ol>
        </nav>
        <div class="mb-5">
            <hr>
        </div>
        <div class="mt-2">
            <div class="row">
                <div class="col-sm-12 col-md-8 col-lg-6">
                    <form action="{{route('testimonials.store')}}" method="post" enctype="multipart/form-data" autocomplete="off">
                        @csrf
                        <!-- Member Select -->
                        <div class="form-group">
                            <label for="member_id">Select Member</label>
                            <select name="member_id" id="member_id"
                                    class="form-control select-relation @error('member_id') is-invalid @enderror">
                                <option value="">Choose Member</option>
                                @foreach($members as $member)
                                    <option value="{{ $member->id }}" {{ old('member_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('member_id')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Testimonial Statement -->
                        <div class="form-group mt-3">
                            <label for="statement">Testimonial Statement</label>
                            <textarea name="statement" id="statement" rows="4"
                                      class="form-control @error('statement') is-invalid @enderror"
                                      placeholder="Enter testimonial">{{ old('statement') }}</textarea>
                            @error('statement')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                                              <!-- Image Upload (Optional) -->
                        <div class="form-group mt-3">
                            <label for="image">Upload Image (Optional)</label>
                            <input type="file" name="image" id="image" class="form-control"
                                   onchange="previewImage(event)">
                        </div>
                        <hr style="height: .3em;" class="border-theme">
                        <img id="preview" src="#" alt="" style="max-width: 100%; max-height: 200px;">
                        <hr style="height: .3em;" class="border-theme">

                        <!-- Submit Button -->
                        <div class="form-group mt-3">
                            <button class="btn btn-md btn-primary rounded-0">
                                <i class="fa fa-paper-plane"></i> Save Testimonial
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
